<?php

namespace SOMASolucoes\Cloudz\AWSS3;

class AWSS3AccountBuilder
{
    private AWSS3Account $AWSAccount;

    public function __construct($code)
    {
        $this->AWSAccount = new AWSS3Account($code);
    }

    public function usingKey($key)
    {
        $this->AWSAccount->key = $key ?? '';
        return $this;
    }

    public function usingSecretKey($secretKey)
    {
        $this->AWSAccount->secretKey = $secretKey ?? '';
        return $this;
    }

    public function atRegion($region)
    {
        $this->AWSAccount->region = $region ?? '';
        return $this;
    }

    public function withType($type)
    {
        $this->AWSAccount->type = $type ?? '';
        return $this;
    }

    public function inBucket($bucketName) {
        $this->AWSAccount->bucketName = $bucketName ?? '';
        return $this;
    }

    public function build()
    {
        return $this->AWSAccount;
    }
}