<?php

namespace SomaGestao\CloudService\Aws;

class AWSAccount 
{
    private int $accountCode;
    public $s3Code;
    public $key;
    public $secretKey;
    public $region;
    public $type;

    public function __construct(int $accountCode)
    {
        $this->accountCode = $accountCode;
    }
}