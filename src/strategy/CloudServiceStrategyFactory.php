<?php

namespace SomaSolucoes\Cloudz\Strategy;

use InvalidArgumentException;
use SomaSolucoes\Cloudz\CloudServiceSettings;
use SomaSolucoes\Cloudz\CloudServiceTypes;
use SomaSolucoes\Cloudz\Strategy\AWSS3Strategy;
use SomaSolucoes\Cloudz\Strategy\FTPStrategy;
use SomaSolucoes\Cloudz\Strategy\SFTPStrategy;

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
            case CloudServiceTypes::AWS_ACCOUNT:
                return new AWSS3Strategy($cloudServiceAccount, $settings);
            default:
                throw new InvalidArgumentException('Tipo de serviço da nuvem inválido ou não implementado.');
        }
    }
}