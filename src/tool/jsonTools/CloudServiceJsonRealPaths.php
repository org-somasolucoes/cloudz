<?php

namespace SOMASolucoes\CloudZ\Tool\JsonTools;

class CloudServiceJsonRealPaths
{
    private static $cloudzConfigDir = '.cloudz';
    private static $ftpConfigFilename = 'ftp.json';
    private static $awsS3ConfigFilename = 'aws-s3.json';

    private static function getBaseRealPath(string $configFilename)
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . self::$cloudzConfigDir . DIRECTORY_SEPARATOR . $configFilename;
    }

    public static function getFTPRealPath()
    {
        return self::getBaseRealPath(self::$ftpConfigFilename);
    }

    public static function getAWSS3RealPath()
    {
        return self::getBaseRealPath(self::$awsS3ConfigFilename);
    }
}
