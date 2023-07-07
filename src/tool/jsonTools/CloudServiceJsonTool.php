<?php

namespace SOMASolucoes\Cloudz\Tool\JsonTools;

abstract class CloudServiceJsonTool {
    public static function recoverJson(string $realPath) {
        $absolutePath = realpath($realPath);
        $jsonData = json_decode(file_get_contents($absolutePath));

        return $jsonData;
    }
}