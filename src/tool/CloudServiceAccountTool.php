<?php

namespace SOMASolucoes\CloudZ\Tool;

abstract class CloudServiceAccountTool {
    public static function selector($json, int $code) 
    {
        $jsonCollection = !is_array($json) ? [$json] : $json;
        foreach ($jsonCollection as $account) {
            if ($account->code == $code) {
                return $account;
            }
        }
    }
}