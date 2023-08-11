<?php

namespace SOMASolucoes\CloudZ\Utility\Path;

use SOMASolucoes\CloudZ\Utility\CloudServiceUtility;

abstract class CloudServicePathUtility extends CloudServiceUtility
{

    const INTEGRATION_DIR = 'integracoes';
    const SOLUTION_DIR = 'solucoes';

    public static function mountSolutionPath(string $root, string $solutionName, string $module)
    {
        return preg_replace("/\/{2,}/", '/', "$root/" . self::SOLUTION_DIR . "/$solutionName/$module/");
    }

    public static function mountIntegrationPath(string $root, string $integrationName)
    {
        return preg_replace("/\/{2,}/", '/', "$root/" . self::INTEGRATION_DIR . "/$integrationName/");
    }
}
