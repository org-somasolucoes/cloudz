<?php

namespace SomaSolucoes\Cloudz\Tool;

use DomainException;

abstract class CloudServiceAccountTool {
    public static function accountSelector($cloudServiceAccounts, $cloudServiceCode) 
    {
        $isCollection = is_array($cloudServiceAccounts);
        if (!$isCollection) {
            return $cloudServiceAccounts;
        }
        
        if (empty($cloudServiceCode)) {
            throw new DomainException('Código da conta de serviço nuvem não informado.');
        }

        foreach ($cloudServiceAccounts as $cloudServiceAccount) {

            if ($cloudServiceAccount->code === $cloudServiceCode) {
                return $cloudServiceAccount;
            }
        }
    }

    public static function awsS3Selector($awsS3Settings, $awsS3Code) 
    {
        $isCollection = is_array($awsS3Settings);
        if (!$isCollection) {
            return $awsS3Settings;
        }

        if (empty($awsS3Code)) {
            throw new DomainException('Código do serviço AWS S3 não informado.');
        }
        
        foreach ($awsS3Settings as $awsS3) {
            if ($awsS3->code === $awsS3Code) {
                return $awsS3;
            }
        }
    }
}