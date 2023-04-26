<?php

namespace SomaGestao\CloudService;

use InvalidArgumentException;
use SomaGestao\CloudService\Strategy\AWSS3Strategy;
use SomaGestao\CloudService\Strategy\FTPStrategy;
use SomaGestao\CloudService\Strategy\SFTPStrategy;

class CloudServiceStrategyFactory 
{
    public static function assemble(CloudService $cloudService, $cloudServiceAccount, CloudServiceSettings $settings) 
    {
        switch ($cloudService->getType()) {
            case CloudServiceTypes::FTP_ACCOUNT:
                if ($cloudServiceAccount->useSSH == 'S') {
                    return new SFTPStrategy($cloudServiceAccount, $settings);
                }
                return new FTPStrategy($cloudServiceAccount, $settings);

            case CloudServiceTypes::AWS_ACCOUNT:
                return new AWSS3Strategy($cloudService, $cloudServiceAccount, $settings);

            default:
                throw new InvalidArgumentException('Tipo de serviço da nuvem inválido ou não implementado.');
        }
    }
}