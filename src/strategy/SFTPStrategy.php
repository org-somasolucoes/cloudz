<?php

namespace SomaSolucoes\Cloudz\Strategy;

use Exception;
use phpseclib3\Net\SFTP;
use SomaSolucoes\Cloudz\Ftp\FtpAccount;
use SomaSolucoes\Cloudz\CloudServiceFile;
use SomaSolucoes\Cloudz\DeleteCloudServiceFile;
use SomaSolucoes\Cloudz\Ftp\StrategyBasedOnProtocolFTP;

class SFTPStrategy extends StrategyBasedOnProtocolFTP
{
    private FtpAccount $sftpAccount;
    private $sftp;

    public function __construct(FtpAccount $sftpAccount, $settings)
    {
        parent::__construct($settings);
        $this->sftpAccount = $sftpAccount;
    }

    protected function login()
    {
        $this->sftp = new SFTP($this->sftpAccount->host);
        if (!$this->sftp) {
            throw new Exception('Ocorreu algum problema ao iniciar a conexão com o SFTP.', 400);
        }

        $loginResult = $this->sftp->login($this->sftpAccount->user, $this->sftpAccount->password);
        if (!$loginResult) {
            throw new Exception('Ocorreu algum problema ao logar no SFTP.', 400);
        }
    }

    protected function changeToWorkDir()
    {
        $realWorkDir = "{$this->sftpAccount->workDir}/{$this->settings->get('path')}";
        if (!$this->sftp->file_exists($realWorkDir)) {
            $this->sftp->mkdir($realWorkDir, 0777, true);
        }
        $this->sftp->chdir($realWorkDir);
        return $realWorkDir;
    }

    protected function beforeExecute()
    {
        if (empty($this->sftpAccount->accessUrl)) {
            throw new Exception('A URL de acesso para os recursos deste FTP, não foi definida.', 400);
        }

        $this->login();
        $this->changeToWorkDir();
    }

    protected function doUpload(CloudServiceFile $file)
    {
        $localFile = $file->getLocalFile();
        $remoteFileName = $file->getRemoteFileName($this->settings->get('canEncryptName', false));

        $wasUploaded = $this->sftp->put($remoteFileName, $localFile, SFTP::SOURCE_LOCAL_FILE);
        if (!$wasUploaded) {
            throw new Exception("O arquivo '{$localFile}' não foi transferido corretamente para o servidor SFTP.", 400);
        }
        
        $resourcePath = "{$this->sftpAccount->accessUrl}" . (!empty($this->settings->get('path')) ? "/{$this->settings->get('path')}" : '');
        $resourceUrl = "{$resourcePath}{$remoteFileName}";

        return $resourceUrl;
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
