<?php

namespace SomaGestao\CloudService\Strategy;

use Exception;
use SomaGestao\CloudService\CloudService;
use SomaGestao\CloudService\Aws\AwsAccount;
use SomaGestao\CloudService\CloudServiceFile;
use SomaGestao\CloudService\CloudServiceSettings;
use SomaGestao\CloudService\DeleteCloudServiceFile;
use SomaGestao\CloudService\Strategy\CloudServiceStrategy;

class AWSS3Strategy extends CloudServiceStrategy
{
    private CloudService $cloudService;
    private AwsAccount $awsAccount;
    private $bucketName;
    private $sdk;

    public function __construct(CloudService $cloudService, AwsAccount $awsAccount, CloudServiceSettings $settings)
    {
        parent::__construct($settings);

        $this->cloudService = $cloudService;
        $this->awsAccount = $awsAccount;

        $this->sdk = new \Aws\S3\S3Client([
            'credentials' => [
                'key'     => $this->awsAccount->key,
                'secret'  => $this->awsAccount->secretKey
            ],

            'region'  => $this->awsAccount->region,
            'version' => 'latest'
        ]);

        $CI = get_instance();
        $CI->load->model('AWSConta_Gestao');

        $cloudServiceCode = $this->cloudService->getCloudServiceCode();
        $this->bucketName = $CI->AWSConta_Gestao->getBucketName($cloudServiceCode);
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
