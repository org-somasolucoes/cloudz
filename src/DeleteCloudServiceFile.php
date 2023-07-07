<?php

namespace SOMASolucoes\Cloudz;

class DeleteCloudServiceFile
{
    private string $remoteaccessURL;

    public function __construct(string $remoteaccessURL)
    {
        $this->remoteaccessURL = $remoteaccessURL;
    }

    public function getRemoteFileName()
    {
        $remoteaccessURL = explode('/', $this->remoteaccessURL);
        $remoteFileName = end($remoteaccessURL);
        return $remoteFileName;
    }
}