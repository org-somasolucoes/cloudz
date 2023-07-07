<?php

namespace SOMASolucoes\Cloudz\Strategy;

use Exception;
use phpseclib3\Net\SFTP;
use SOMASolucoes\Cloudz\FTP\FtpAccount;
use SOMASolucoes\Cloudz\CloudServiceFile;
use SOMASolucoes\Cloudz\CloudServiceSettings;
use SOMASolucoes\Cloudz\DeleteCloudServiceFile;
use SOMASolucoes\Cloudz\FTP\StrategyBasedOnProtocolFTP;

class SFTPStrategy extends StrategyBasedOnProtocolFTP
{
    private FTPAccount $SFTPAccount;
    private $SFTP;

    public function __construct(FTPAccount $SFTPAccount, CloudServiceSettings $settings)
    {
        parent::__construct($settings);
        $this->SFTPAccount = $SFTPAccount;
    }

    protected function login()
    {
        $this->SFTP = new SFTP($this->SFTPAccount->host);
        if (!$this->SFTP) {
            throw new Exception('Ocorreu algum problema ao iniciar a conexão com o SFTP.', 400);
        }

        $loginResult = $this->SFTP->login($this->SFTPAccount->user, $this->SFTPAccount->password);
        if (!$loginResult) {
            throw new Exception('Ocorreu algum problema ao logar no SFTP.', 400);
        }
    }

    protected function changeToWorkDir()
    {
        $realWorkDir = "{$this->SFTPAccount->workDir}/{$this->settings->get('path')}";
        if (!$this->SFTP->file_exists($realWorkDir)) {
            $this->SFTP->mkdir($realWorkDir, 0777, true);
        }
        $this->SFTP->chdir($realWorkDir);
        return $realWorkDir;
    }

    protected function beforeExecute()
    {
        if (empty($this->SFTPAccount->accessURL)) {
            throw new Exception('A URL de acesso para os recursos deste FTP, não foi definida.', 400);
        }

        $this->login();
        $this->changeToWorkDir();
    }

    protected function doUpload(CloudServiceFile $file)
    {
        $localFile = $file->getLocalFile();
        $remoteFileName = $file->getRemoteFileName($this->settings->get('canEncryptName', false));

        $wasUploaded = $this->SFTP->put($remoteFileName, $localFile, SFTP::SOURCE_LOCAL_FILE);
        if (!$wasUploaded) {
            throw new Exception("O arquivo '{$localFile}' não foi transferido corretamente para o servidor SFTP.", 400);
        }
        
        $resourcePath = "{$this->SFTPAccount->accessURL}" . (!empty($this->settings->get('path')) ? "/{$this->settings->get('path')}" : '');
        $resourceURL = preg_replace("/\/{2,}/", '/', "{$resourcePath}/{$remoteFileName}");

        return $resourceURL;
    }

    protected function doDelete(DeleteCloudServiceFile $file)
    {
        $remoteFileName = $file->getRemoteFileName();
        $wasDelete = $this->sftp->delete($remoteFileName);

        if (!$wasDelete) {
            throw new Exception("O arquivo '{$remoteFileName}' não foi deletado corretamente do servidor FTP.", 400);
        }

        return "O arquivo '{$remoteFileName}' foi deletado com sucesso do servidor FTP.";
    }
    
    protected function afterExecute(){}
}
