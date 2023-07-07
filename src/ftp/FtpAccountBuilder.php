<?php 

namespace SOMASolucoes\Cloudz\FTP;

use SOMASolucoes\Cloudz\FTP\FTPAccount;

class FTPAccountBuilder
{
    private FTPAccount $FTPAccount;

    public function __construct(int $code)
    {
        $this->FTPAccount = new FTPAccount($code);
    }

    public function usingHost($host)
    {
        $this->FTPAccount->host = $host ?? '';
        return $this;
    }

    public function atPort($port)
    {
        $this->FTPAccount->port = intval($port) ?: null;
        return $this;
    }

    public function withUser($user)
    {
        $this->FTPAccount->user = $user ?? '';
        return $this;
    }

    public function withPassword($password)
    {
        $this->FTPAccount->password = $password ?? '';
        return $this;
    }

    public function beingPassive($isPassive)
    {
        $this->FTPAccount->isPassive = $isPassive ?? false;
        return $this;
    }

    public function atWorkDir($workDir)
    {
        $this->FTPAccount->workDir = rtrim($workDir, '/');
        return $this;
    }

    public function onAccessURL($accessURL)
    {
        $this->FTPAccount->accessURL = rtrim($accessURL, '/');
        return $this;
    }
    
    public function usingSSH($useSSH)
    {
        $this->FTPAccount->useSSH = $useSSH ?? false;
        return $this;
    }

    public function build()
    {
        return $this->FTPAccount;
    }
}