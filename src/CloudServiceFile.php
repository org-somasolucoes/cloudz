<?php

namespace SomaSolucoes\Cloudz;

class CloudServiceFile 
{
    private string $localFile;
    private ?string $remoteFileName;
    private string $localFilePath;
    private string $localFileName;
    private string $localFileExtension;

    public function __construct(string $localFile, ?string $remoteFileName = null)
    {
        $this->localFile = $localFile;
        $this->remoteFileName = $remoteFileName;

        $this->localFilePath = rtrim(pathinfo($this->localFile, PATHINFO_DIRNAME), '/');
        $this->localFileName = pathinfo($this->localFile, PATHINFO_BASENAME);
        $this->localFileExtension = pathinfo($this->localFile, PATHINFO_EXTENSION);
    }

    public function getLocalFile()
    {
        return $this->localFile;
    }

    public function getLocalFilePath()
    {
        return $this->localFilePath;
    }

    public function getLocalFileName()
    {
        return $this->localFileName;
    }

    public function getLocalFileExtension()
    {
        return $this->localFileExtension;
    }

    public function getRemoteFileName(bool $canEncrypt = false)
    {
        if ($canEncrypt) {
            $remoteFileName = (md5(uniqid(time() * rand(0, 9999))) . '.' . $this->getLocalFileExtension());
            return $remoteFileName;
        }
        
        $remoteFileName = $this->remoteFileName ?: $this->getLocalFileName();
        return $remoteFileName;
    }
}