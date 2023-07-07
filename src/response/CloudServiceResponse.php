<?php

namespace SOMASolucoes\Cloudz\Response;

abstract class CloudServiceResponse 
{
    private int $code;
    protected string $URL;
    protected string $message;

    public function __construct(int $code)
    {
        $this->code = $code;
    }
    
    public function getCode() {
        return $this->code;
    }

    public function getURL() {
        return $this->URL;
    }

    public function getMessage() {
        return $this->message;
    }

}