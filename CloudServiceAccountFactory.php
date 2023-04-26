<?php

namespace SomaGestao\CloudService;

use InvalidArgumentException;
use SomaGestao\CloudService\CloudService;
use SomaGestao\CloudService\CloudServiceTypes;
use SomaGestao\CloudService\Aws\AWSAccountBuilder;
use SomaGestao\CloudService\Ftp\FTPAccountBuilder;

class CloudServiceAccountFactory 
{
    public static function assemble(CloudService $cloudService) 
    {
        $CI = get_instance();

        switch ($cloudService->getType()) {
            case CloudServiceTypes::FTP_ACCOUNT:
                $CI->load->model('FTPConta_Gestao');
                $ftpAccountData = $CI->FTPConta_Gestao->getFTPAccount($cloudService->getAccountCode());

                $ftpAccountBuilder = new FTPAccountBuilder($ftpAccountData['CD_FTP_CONTA']);
                $ftpAccount = $ftpAccountBuilder->usingHost($ftpAccountData['HOST'])
                                                ->atPort($ftpAccountData['PORTA'])
                                                ->withUser($ftpAccountData['USUARIO'])
                                                ->withPassword($ftpAccountData['SENHA'])
                                                ->beingPassive($ftpAccountData['AO_PASSIVO'])
                                                ->atWorkDir($ftpAccountData['DIR_TRABALHO'])
                                                ->onAccessUrl($ftpAccountData['URL_ACESSO'])
                                                ->usingSSH($ftpAccountData['UTILIZA_SSH'])
                                                ->build();
                return $ftpAccount;

            case CloudServiceTypes::AWS_ACCOUNT:
                $CI->load->model('AWSConta_Gestao');
                $awsAccountData = $CI->AWSConta_Gestao->getAWSAccount($cloudService->getAccountCode());
                
                $awsAccountBuilder = new AWSAccountBuilder($awsAccountData['CD_AWS_CONTA']);
                $awsAccount = $awsAccountBuilder->usingKey($awsAccountData['AWS_KEY'])
                                                ->usingSecretKey($awsAccountData['AWS_SECRET_KEY'])
                                                ->atRegion($awsAccountData['AWS_REGION'])
                                                ->withType($awsAccountData['AWS_TIPO'])
                                                ->ofTypeS3($awsAccountData['CD_AWS_S3'])
                                                ->build();
                return $awsAccount;

            default:
                throw new InvalidArgumentException('Tipo de serviço da nuvem inválido ou não implementado.');
        }
    }
}