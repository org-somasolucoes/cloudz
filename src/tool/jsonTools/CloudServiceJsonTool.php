<?php

namespace SOMASolucoes\CloudZ\Tool\JsonTools;

abstract class CloudServiceJsonTool {
    public static function getJson(string $realPath) {
        $absolutePath = realpath($realPath);
        $jsonData = json_decode(file_get_contents($absolutePath));

        return $jsonData;
    }
}