<?php

namespace SomaGestao\CloudService\Response;

class CloudServiceResponseError extends CloudServiceResponse 
{
    public function __construct(int $code, string $message)
    {
        parent::__construct($code);
        $this->message = $message;
    }
}