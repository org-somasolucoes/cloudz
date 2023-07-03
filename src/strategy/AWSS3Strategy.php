<?php

namespace SomaSolucoes\Cloudz\Strategy;

use Exception;
use SomaSolucoes\Cloudz\Aws\AwsAccount;
use SomaSolucoes\Cloudz\CloudServiceFile;
use SomaSolucoes\Cloudz\CloudServiceSettings;
use SomaSolucoes\Cloudz\DeleteCloudServiceFile;
use SomaSolucoes\Cloudz\Strategy\CloudServiceStrategy;
use SomaSolucoes\Cloudz\Tool\CloudServiceAccountTool;
use SomaSolucoes\Cloudz\Tool\JsonTools\CloudServiceJsonRealPaths;
use SomaSolucoes\Cloudz\Tool\JsonTools\CloudServiceJsonTool;

class AWSS3Strategy extends CloudServiceStrategy
{
    private AwsAccount $awsAccount;
    private $bucketName;
    private $sdk;

    public function __construct(AwsAccount $awsAccount, CloudServiceSettings $settings)
    {
        parent::__construct($settings);
        $this->awsAccount = $awsAccount;
        $this->sdk = new \Aws\S3\S3Client([
            'credentials' => [
                'key'     => $this->awsAccount->key,
                'secret'  => $this->awsAccount->secretKey
            ],

            'region'  => $this->awsAccount->region,
            'version' => 'latest'
        ]);
        
        $awsS3Data = 
            CloudServiceAccountTool::awsS3Selector($this->awsAccount->settings, $this->awsAccount->s3Code);
        $this->bucketName = $awsS3Data->bucketName;
    }

    protected function beforeExecute()
    {
        if (!$this->sdk) {
            throw new Exception('Sem conexão com o AWS.', 400);
        }
    }
    
    protected function defaultPathOfUpload()
    {
        if ($this->settings->get('path', false)) {
            return (rtrim($this->settings->get('path', false), '/') . '/');
        }
    }

    protected function doUpload(CloudServiceFile $file)
    {
        if (!$this->bucketName) {
            throw new Exception('Não foi informado o nome do Bucket.');
        }
        
        $uploadPath     = $this->defaultPathOfUpload();
        $remoteFileName = $file->getRemoteFileName($this->settings->get('canEncryptName', false));
        $fileName       = $file->getLocalFile();

        $response = $this->sdk->putObject([
            'Bucket'     => $this->bucketName,
            'Key'        => $uploadPath . $remoteFileName,
            'SourceFile' => $fileName
        ]);

        if ($response['@metadata']['statusCode'] != 200) {
            throw new Exception("O arquivo '{$fileName}' não foi transferido corretamente para o servidor AWS.", 400);
        }

        $resourceUrl = $response['@metadata']['effectiveUri'] ?: '';
        return $resourceUrl;
    }

    protected function doDelete(DeleteCloudServiceFile $file)
    {
        $uploadPath = $this->defaultPathOfUpload();
        $remoteFileName = $file->getRemoteFileName();

        $response = $this->sdk->deleteObject([
            'Bucket' => $this->bucketName,
            'Key'    => $uploadPath . $remoteFileName
        ]);

        if ($response['@metadata']['statusCode'] != 204) {
            throw new Exception('O arquivo não foi deletado corretamente do servidor FTP.', 400);
        }

        return "O arquivo '{$remoteFileName}' foi deletado com sucesso do servidor AWS.";
    }

    protected function afterExecute()
    {
    }
}
