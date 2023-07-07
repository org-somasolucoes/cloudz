<?php

namespace SOMASolucoes\Cloudz\Strategy;

use InvalidArgumentException;
use SOMASolucoes\Cloudz\CloudServiceSettings;
use SOMASolucoes\Cloudz\CloudServiceTypes;
use SOMASolucoes\Cloudz\Strategy\AWSS3Strategy;
use SOMASolucoes\Cloudz\Strategy\FTPStrategy;
use SOMASolucoes\Cloudz\Strategy\SFTPStrategy;

class CloudServiceStrategyFactory 
{
    public static function assemble(string $cloudServiceType, $cloudServiceAccount, CloudServiceSettings $settings) 
    {
        switch ($cloudServiceType) {
            case CloudServiceTypes::FTP_ACCOUNT:
                if ($cloudServiceAccount->useSSH == 'S') {
                    return new SFTPStrategy($cloudServiceAccount, $settings);
                }
                return new FTPStrategy($cloudServiceAccount, $settings);
            case CloudServiceTypes::AWS_S3_ACCOUNT:
                return new AWSS3Strategy($cloudServiceAccount, $settings);
            default:
                throw new InvalidArgumentException('Tipo de serviço da nuvem inválido ou não implementado.');
        }
    }
}