<?php

namespace SOMASolucoes\CloudZ\Strategy;

use Exception;
use SOMASolucoes\CloudZ\CloudServiceFile;
use SOMASolucoes\CloudZ\CloudServiceSettings;
use SOMASolucoes\CloudZ\AWS\AWSS3\AWSS3Account;
use SOMASolucoes\CloudZ\DeleteCloudServiceFile;
use SOMASolucoes\CloudZ\Strategy\CloudServiceStrategy;

class AWSS3Strategy extends CloudServiceStrategy
{
    private AWSS3Account $awsS3Account;
    private string $bucketName;
    private $sdk;

    public function __construct(AWSS3Account $awsS3Account, CloudServiceSettings $settings)
    {
        parent::__construct($settings);
        $this->awsS3Account = $awsS3Account;
        $this->sdk = new \Aws\S3\S3Client([
            'credentials' => [
                'key'     => $this->awsS3Account->key,
                'secret'  => $this->awsS3Account->secretKey
            ],

            'region'  => $this->awsS3Account->region,
            'version' => 'latest'
        ]);

        $this->bucketName = $awsS3Account->bucketName;
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
        
        $uploadPath = $this->defaultPathOfUpload();
        $remoteFileName = $file->getRemoteFileName($this->settings->get('canEncryptName', false));
        $fileName = $file->getLocalFile();

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
