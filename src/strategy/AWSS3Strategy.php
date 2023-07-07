<?php

namespace SOMASolucoes\Cloudz\Strategy;

use Exception;
use SOMASolucoes\Cloudz\AWSS3\AWSS3Account;
use SOMASolucoes\Cloudz\CloudServiceFile;
use SOMASolucoes\Cloudz\CloudServiceSettings;
use SOMASolucoes\Cloudz\DeleteCloudServiceFile;
use SOMASolucoes\Cloudz\Strategy\CloudServiceStrategy;

class AWSS3Strategy extends CloudServiceStrategy
{
    private AWSS3Account $AWSS3Account;
    private string $bucketName;
    private $SDK;

    public function __construct(AWSS3Account $AWSS3Account, CloudServiceSettings $settings)
    {
        parent::__construct($settings);
        $this->AWSS3Account = $AWSS3Account;
        $this->SDK = new \Aws\S3\S3Client([
            'credentials' => [
                'key'     => $this->AWSS3Account->key,
                'secret'  => $this->AWSS3Account->secretKey
            ],

            'region'  => $this->AWSS3Account->region,
            'version' => 'latest'
        ]);

        $this->bucketName = $AWSS3Account->bucketName;
    }

    protected function beforeExecute()
    {
        if (!$this->SDK) {
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

        $response = $this->SDK->putObject([
            'Bucket'     => $this->bucketName,
            'Key'        => $uploadPath . $remoteFileName,
            'SourceFile' => $fileName
        ]);

        if ($response['@metadata']['statusCode'] != 200) {
            throw new Exception("O arquivo '{$fileName}' não foi transferido corretamente para o servidor AWS.", 400);
        }

        $resourceURL = $response['@metadata']['effectiveUri'] ?: '';
        return $resourceURL;
    }

    protected function doDelete(DeleteCloudServiceFile $file)
    {
        $uploadPath = $this->defaultPathOfUpload();
        $remoteFileName = $file->getRemoteFileName();

        $response = $this->SDK->deleteObject([
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
