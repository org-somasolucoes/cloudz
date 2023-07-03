<?php

namespace SomaSolucoes\Cloudz\Response;

abstract class CloudServiceResponse 
{
    private int $code;
    protected string $url;
    protected string $message;

    public function __construct(int $code)
    {
        $this->code = $code;
    }
    
    public function getCode() {
        return $this->code;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getMessage() {
        return $this->message;
    }

}