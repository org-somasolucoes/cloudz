<?php

namespace SOMASolucoes\Cloudz\Response;

class CloudServiceResponseSuccess extends CloudServiceResponse 
{
    public function __construct(int $code, string $URL)
    {
        parent::__construct($code);
        $this->URL = $URL;
    }
}