<?php

namespace SOMASolucoes\Cloudz;

use InvalidArgumentException;
use SOMASolucoes\Cloudz\CloudServiceTypes;
use SOMASolucoes\Cloudz\AWSS3\AWSS3AccountBuilder;
use SOMASolucoes\Cloudz\FTP\FTPAccountBuilder;
use SOMASolucoes\Cloudz\Tool\CloudServiceAccountTool;
use SOMASolucoes\Cloudz\Tool\JsonTools\CloudServiceJsonRealPaths;
use SOMASolucoes\Cloudz\Tool\JsonTools\CloudServiceJsonTool;

class CloudServiceAccountFactory 
{
    public static function assemble(string $cloudServiceType, int $cloudServiceCode) 
    {
        switch ($cloudServiceType) {
            case CloudServiceTypes::FTP_ACCOUNT:
                $jsonOfAccounts = CloudServiceJsonTool::recoverJson(CloudServiceJsonRealPaths::FTP_REAL_PATH);
                $FTPAccountData = CloudServiceAccountTool::selector($jsonOfAccounts->FTPAccount, $cloudServiceCode);

                $FTPAccountBuilder = new FTPAccountBuilder($FTPAccountData->code);
                $FTPAccount = $FTPAccountBuilder->usingHost($FTPAccountData->host)
                                                ->atPort($FTPAccountData->port)
                                                ->withUser($FTPAccountData->user)
                                                ->withPassword($FTPAccountData->password)
                                                ->beingPassive($FTPAccountData->isPassive)
                                                ->atWorkDir($FTPAccountData->dirWork)
                                                ->onAccessURL($FTPAccountData->URLAcess)
                                                ->usingSSH($FTPAccountData->usesSSH)
                                                ->build();
                return $FTPAccount;

            case CloudServiceTypes::AWS_S3_ACCOUNT:
                $jsonOfAccounts = CloudServiceJsonTool::recoverJson(CloudServiceJsonRealPaths::AWS_REAL_PATH);
                $AWSS3AccountData = CloudServiceAccountTool::selector($jsonOfAccounts->AWSS3Account, $cloudServiceCode);
                
                $AWSS3AccountBuilder = new AWSS3AccountBuilder($AWSS3AccountData->code);
                $AWSS3Account = $AWSS3AccountBuilder->usingKey($AWSS3AccountData->AWSKey)
                                                    ->usingSecretKey($AWSS3AccountData->AWSSecretKey)
                                                    ->atRegion($AWSS3AccountData->AWSRegion)
                                                    ->withType($AWSS3AccountData->AWSType)
                                                    ->inBucket($AWSS3AccountData->bucketName)
                                                    ->build();
                return $AWSS3Account;

            default:
                throw new InvalidArgumentException('Tipo de serviço da nuvem inválido ou não implementado.');
        }
    }
}