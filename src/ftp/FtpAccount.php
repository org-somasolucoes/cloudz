<?php 

namespace SOMASolucoes\CloudZ\FTP;

class FTPAccount
{
    private int $code;
    public string $host = '';
    public int $port;
    public string $user = '';
    public string $password = '';
    public bool $isPassive = false;
    public string $workDir = '';
    public string $dirPlugin = '';
    public string $accessUrl = '';
    public bool $useSSH = false;
    
    public function __construct(int $code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}