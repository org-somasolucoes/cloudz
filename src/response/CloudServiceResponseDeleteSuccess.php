<?php

namespace SOMASolucoes\Cloudz\Response;

class CloudServiceResponseDeleteSuccess extends CloudServiceResponse 
{
    public function __construct(int $code, string $message)
    {
        parent::__construct($code);
        $this->message = $message;
    }
}