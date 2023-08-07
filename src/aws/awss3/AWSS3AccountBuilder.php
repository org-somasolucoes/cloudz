<?php

namespace SOMASolucoes\CloudZ\AWS\AWSS3;

class AWSS3AccountBuilder
{
    private AWSS3Account $awsS3Account;

    public function __construct($code)
    {
        $this->awsS3Account = new AWSS3Account($code);
    }

    public function usingKey($key)
    {
        $this->awsS3Account->key = $key ?? '';
        return $this;
    }

    public function usingSecretKey($secretKey)
    {
        $this->awsS3Account->secretKey = $secretKey ?? '';
        return $this;
    }

    public function atRegion($region)
    {
        $this->awsS3Account->region = $region ?? '';
        return $this;
    }

    public function withType($type)
    {
        $this->awsS3Account->type = $type ?? '';
        return $this;
    }

    public function inBucket($bucketName) {
        $this->awsS3Account->bucketName = $bucketName ?? '';
        return $this;
    }

    public function build()
    {
        return $this->awsS3Account;
    }
}