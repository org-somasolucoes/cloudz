<?php

namespace SomaSolucoes\Cloudz\Aws;

use SomaSolucoes\Cloudz\Aws\AWSAccount;

class AWSAccountBuilder
{
    private AWSAccount $awsAccount;

    public function __construct($AccountCode)
    {
        $this->awsAccount = new AWSAccount($AccountCode);
    }

    public function usingKey($key)
    {
        $this->awsAccount->key = $key ?? '';
        return $this;
    }

    public function usingSecretKey($secretKey)
    {
        $this->awsAccount->secretKey = $secretKey ?? '';
        return $this;
    }

    public function atRegion($region)
    {
        $this->awsAccount->region = $region ?? '';
        return $this;
    }

    public function withType($type)
    {
        $this->awsAccount->type = $type ?? '';
        return $this;
    }

    public function ofTypeS3($s3Code)
    {
        $this->awsAccount->s3Code = $s3Code ?? '';
        return $this;
    }

    public function usingSettings($settings) {
        $this->awsAccount->settings = $settings ?? '';
        return $this;
    }

    public function build()
    {
        return $this->awsAccount;
    }
}