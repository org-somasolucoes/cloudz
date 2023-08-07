<?php

namespace SOMASolucoes\CloudZ;

use InvalidArgumentException;
use SOMASolucoes\CloudZ\CloudServiceTypes;
use SOMASolucoes\CloudZ\FTP\FTPAccountBuilder;
use SOMASolucoes\CloudZ\Tool\CloudServiceAccountTool;
use SOMASolucoes\CloudZ\AWS\AWSS3\AWSS3AccountBuilder;
use SOMASolucoes\CloudZ\Tool\JsonTools\CloudServiceJsonRealPaths;
use SOMASolucoes\CloudZ\Tool\JsonTools\CloudServiceJsonTool;

class CloudServiceAccountFactory 
{
    public static function assemble(string $cloudServiceType, int $cloudServiceCode) 
    {
        switch ($cloudServiceType) {
            case CloudServiceTypes::FTP_ACCOUNT:
                $jsonOfAccounts = CloudServiceJsonTool::getJson(CloudServiceJsonRealPaths::getFTPRealPath());
                $FTPAccountData = CloudServiceAccountTool::selector($jsonOfAccounts->FTPAccount, $cloudServiceCode);
                
                $FTPAccountBuilder = new FTPAccountBuilder($FTPAccountData->code);
                $FTPAccount = $FTPAccountBuilder->usingHost($FTPAccountData->host)
                                                ->atPort($FTPAccountData->port)
                                                ->withUser($FTPAccountData->user)
                                                ->withPassword($FTPAccountData->password)
                                                ->beingPassive($FTPAccountData->isPassive)
                                                ->atWorkDir($FTPAccountData->dirWork)
                                                ->onAccessUrl($FTPAccountData->urlAcess)
                                                ->usingSsh($FTPAccountData->useSsh)
                                                ->build();
                return $FTPAccount;

            case CloudServiceTypes::AWS_S3_ACCOUNT:
                $jsonOfAccounts = CloudServiceJsonTool::getJson(CloudServiceJsonRealPaths::getAWSS3RealPath());
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