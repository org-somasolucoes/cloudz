<?php 

namespace SOMASolucoes\Cloudz\FTP;

class FTPAccount
{
    private int $code;
    public $host = '';
    public $port;
    public $user = '';
    public $password = '';
    public $isPassive = false;
    public $workDir = '';
    public $dirPlugin = '';
    public $accessURL = '';
    public $useSSH = false;
    
    public function __construct(int $code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}