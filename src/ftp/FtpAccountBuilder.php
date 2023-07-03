<?php 

namespace SomaSolucoes\Cloudz\Ftp;

use SomaSolucoes\Cloudz\Ftp\FTPAccount;

class FTPAccountBuilder
{
    private FTPAccount $ftpAccount;

    public function __construct(int $code)
    {
        $this->ftpAccount = new FTPAccount($code);
    }

    public function usingHost($host)
    {
        $this->ftpAccount->host = $host ?? '';
        return $this;
    }

    public function atPort($port)
    {
        $this->ftpAccount->port = intval($port) ?: null;
        return $this;
    }

    public function withUser($user)
    {
        $this->ftpAccount->user = $user ?? '';
        return $this;
    }

    public function withPassword($password)
    {
        $this->ftpAccount->password = $password ?? '';
        return $this;
    }

    public function beingPassive($isPassive)
    {
        $this->ftpAccount->isPassive = $isPassive ?? false;
        return $this;
    }

    public function atWorkDir($workDir)
    {
        $this->ftpAccount->workDir = rtrim($workDir, '/');
        return $this;
    }

    public function onAccessUrl($accessUrl)
    {
        $this->ftpAccount->accessUrl = rtrim($accessUrl, '/');
        return $this;
    }
    
    public function usingSSH($useSSH)
    {
        $this->ftpAccount->useSSH = $useSSH ?? false;
        return $this;
    }

    public function build()
    {
        return $this->ftpAccount;
    }
}