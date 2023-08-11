<?php 

namespace SOMASolucoes\CloudZ\FTP;

use SOMASolucoes\CloudZ\FTP\FTPAccount;

class FTPAccountBuilder
{
    private FTPAccount $ftpAccount;

    public function __construct(int $code)
    {
        $this->ftpAccount = new FTPAccount($code);
    }

    public function usingHost(string $host)
    {
        $this->ftpAccount->host = $host;
        return $this;
    }

    public function atPort(int $port)
    {
        $this->ftpAccount->port = intval($port);
        return $this;
    }

    public function withUser(string $user)
    {
        $this->ftpAccount->user = $user;
        return $this;
    }

    public function withPassword(string $password)
    {
        $this->ftpAccount->password = $password;
        return $this;
    }

    public function beingPassive(bool $isPassive)
    {
        $this->ftpAccount->isPassive = $isPassive;
        return $this;
    }

    public function atWorkDir(string $workDir)
    {
        $this->ftpAccount->workDir = rtrim($workDir, '/');
        return $this;
    }

    public function onAccessUrl(string $accessUrl)
    {
        $this->ftpAccount->accessUrl = rtrim($accessUrl, '/');
        return $this;
    }
    
    public function usingSSH(string $useSSH)
    {
        $this->ftpAccount->useSSh = $useSSH;
        return $this;
    }

    public function build()
    {
        return $this->ftpAccount;
    }
}