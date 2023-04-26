<?php

namespace SomaGestao\CloudService;
class CloudService 
{
    private int $cloudServiceCode;
    private string $type;
    private int $accountCode;

    public function __construct(int $cloudServiceCode, string $type, int $accountCode)
    {
        $this->cloudServiceCode = $cloudServiceCode;
        $this->type = $type;
        $this->accountCode = $accountCode;
    }

    public function getCloudServiceCode()
    {
        return $this->cloudServiceCode;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAccountCode()
    {
        return $this->accountCode;
    }
}