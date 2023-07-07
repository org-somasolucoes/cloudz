<?php

namespace SOMASolucoes\Cloudz\Tool;

use DomainException;

abstract class CloudServiceAccountTool {
    public static function selector($canBeCollection, int $comparator) 
    {
        $isCollection = is_array($canBeCollection);
        if (!$isCollection) {
            $singleRecord = $canBeCollection;
            return $singleRecord;
        }
        
        if (empty($comparator)) {
            throw new DomainException('Código da conta de serviço nuvem não informado.');
        }
        
        $collection = $canBeCollection;
        foreach ($collection as $register) {
            if ($register->code == $comparator) {
                return $register;
            }
        }
    }
}