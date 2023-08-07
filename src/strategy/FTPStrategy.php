<?php

namespace SOMASolucoes\CloudZ\Strategy;

use Exception;
use SOMASolucoes\CloudZ\FTP\FtpAccount;
use SOMASolucoes\CloudZ\CloudServiceFile;
use SOMASolucoes\CloudZ\CloudServiceSettings;
use SOMASolucoes\CloudZ\DeleteCloudServiceFile;
use SOMASolucoes\CloudZ\FTP\StrategyBasedOnProtocolFTP;

class FTPStrategy extends StrategyBasedOnProtocolFTP 
{
    private FTPAccount $ftpAccount;
    private $fconn;

    public function __construct(FTPAccount $ftpAccount, CloudServiceSettings $settings)
    {
        parent::__construct($settings);
        $this->ftpAccount = $ftpAccount;
    }

    protected function login()
    {
        $this->fconn = ftp_connect($this->ftpAccount->host, $this->ftpAccount->port);
        if (!$this->fconn) {
            throw new Exception('Ocorreu algum problema ao iniciar a conexão com o FTP.', 400);
        }

        $loginResult = ftp_login($this->fconn, $this->ftpAccount->user, $this->ftpAccount->password);

        if (!$this->fconn or !$loginResult) {
            throw new Exception('Ocorreu algum problema ao logar no FTP.', 400);
        }
        ftp_pasv($this->fconn, $this->ftpAccount->isPassive); 
    }

    protected function changeToWorkDir()
    {
        $realWorkDir = "{$this->ftpAccount->workDir}/{$this->settings->get('path')}";

        $parts = explode('/', $realWorkDir);
        foreach ($parts as $part) {
            if (!empty($part) && !ftp_chdir($this->fconn, $part)) {
                ftp_mkdir($this->fconn, $part);
                ftp_chdir($this->fconn, $part);
                ftp_chmod($this->fconn, 0777, $part);
            }
        }
        return $realWorkDir;
    }

    protected function beforeExecute() 
    {
        if (empty($this->ftpAccount->accessUrl)) {
            throw new Exception('A URL de acesso para os recursos deste FTP, não foi definida.', 400);
        }

        $this->login();
        $this->changeToWorkDir();
    }

    protected function doUpload(CloudServiceFile $file) {
        $localFile = $file->getLocalFile();
        $remoteFileName = $file->getRemoteFileName($this->settings->get('canEncryptName', false));

        $wasUploaded = ftp_put($this->fconn, $remoteFileName, $localFile, FTP_BINARY);
        if (!$wasUploaded) {
            throw new Exception("O arquivo '{$localFile}' não foi transferido corretamente para o servidor FTP.", 400);
        }

        $resourcePath = "{$this->ftpAccount->accessUrl}" . (!empty($this->settings->get('path')) ? "/{$this->settings->get('path')}" : '');
        $resourceURL = "{$resourcePath}{$remoteFileName}";

        return $resourceURL;
    }

    protected function doDelete(DeleteCloudServiceFile $file) {
        $remoteFileName = $file->getRemoteFileName();
        $wasDelete = ftp_delete($this->fconn, $remoteFileName);
        
        if (!$wasDelete) {
            throw new Exception("O arquivo '{$remoteFileName}' não foi deletado corretamente do servidor FTP.", 400);
        }

        return "O arquivo '{$remoteFileName}' foi deletado com sucesso do servidor FTP.";
    }

    protected function afterExecute() {
        ftp_close($this->fconn);
    }  
}
