<?php

namespace SomaSolucoes\Cloudz;

class DeleteCloudServiceFile
{
    private string $remoteAccessUrl;

    public function __construct(string $remoteAccessUrl)
    {
        $this->remoteAccessUrl = $remoteAccessUrl;
    }

    public function getRemoteFileName()
    {
        $remoteAccessUrl = explode('/', $this->remoteAccessUrl);
        $remoteFileName = end($remoteAccessUrl);
        return $remoteFileName;
    }
}