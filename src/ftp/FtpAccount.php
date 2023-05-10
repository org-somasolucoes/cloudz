<?php 

namespace SomaGestao\CloudService\Ftp;

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
    public $accessUrl = '';
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