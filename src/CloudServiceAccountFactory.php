<?php

namespace SomaSolucoes\Cloudz;

use InvalidArgumentException;
use SomaSolucoes\Cloudz\CloudServiceTypes;
use SomaSolucoes\Cloudz\Aws\AWSAccountBuilder;
use SomaSolucoes\Cloudz\Ftp\FTPAccountBuilder;
use SomaSolucoes\Cloudz\Tool\CloudServiceAccountTool;
use SomaSolucoes\Cloudz\Tool\JsonTools\CloudServiceJsonRealPaths;
use SomaSolucoes\Cloudz\Tool\JsonTools\CloudServiceJsonTool;

class CloudServiceAccountFactory 
{
    public static function assemble(String $cloudServiceType, int $cloudServiceCode) 
    {
        switch ($cloudServiceType) {
            case CloudServiceTypes::FTP_ACCOUNT:
                $jsonOfAccounts = 
                    CloudServiceJsonTool::recoverJson(CloudServiceJsonRealPaths::FTP_REAL_PATH);
                $ftpAccountData = 
                    CloudServiceAccountTool::accountSelector($jsonOfAccounts->ftpAccount, $cloudServiceCode);

                $ftpAccountBuilder = new FTPAccountBuilder($ftpAccountData->code);
                $ftpAccount = $ftpAccountBuilder->usingHost($ftpAccountData->host)
                                                ->atPort($ftpAccountData->port)
                                                ->withUser($ftpAccountData->user)
                                                ->withPassword($ftpAccountData->password)
                                                ->beingPassive($ftpAccountData->passive)
                                                ->atWorkDir($ftpAccountData->dirWork)
                                                ->onAccessUrl($ftpAccountData->urlAcess)
                                                ->usingSSH($ftpAccountData->usesSsh)
                                                ->build();
                return $ftpAccount;

            case CloudServiceTypes::AWS_ACCOUNT:
                $jsonOfAccounts = 
                    CloudServiceJsonTool::recoverJson(CloudServiceJsonRealPaths::AWS_REAL_PATH);
                $awsAccountData = 
                    CloudServiceAccountTool::accountSelector($jsonOfAccounts->awsAccount, $cloudServiceCode);
                
                $awsAccountBuilder = new AWSAccountBuilder($awsAccountData->code);
                $awsAccount = $awsAccountBuilder->usingKey($awsAccountData->awsKey)
                                                ->usingSecretKey($awsAccountData->awsSecretKey)
                                                ->atRegion($awsAccountData->awsRegion)
                                                ->withType($awsAccountData->awsType)
                                                ->ofTypeS3($awsAccountData->s3->default)
                                                ->usingSettings($awsAccountData->s3->settings)
                                                ->build();
                return $awsAccount;

            default:
                throw new InvalidArgumentException('Tipo de serviço da nuvem inválido ou não implementado.');
        }
    }
}