<?php

namespace SOMASolucoes\Cloudz\AWSS3;

class AWSS3Account 
{
    private int $code;
    public string $key;
    public string $secretKey;
    public string $region;
    public string $type;
    public string $bucketName;

    public function __construct(int $code)
    {
        $this->code = $code;
    }
}