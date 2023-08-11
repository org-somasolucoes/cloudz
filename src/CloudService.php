<?php

namespace SOMASolucoes\CloudZ;

use Exception;
use SOMASolucoes\CloudZ\Strategy\CloudServiceStrategy;
use SOMASolucoes\CloudZ\Strategy\CloudServiceStrategyFactory;

final class CloudService
{
    private string $type;
    private int $code;
    private $account;
    public CloudServiceSettings $settings;
    private CloudServiceStrategy $strategy;

    public function __construct(string $type, int $code)
    {
        $this->type = $type;
        $this->code = $code;
        $this->settings = new CloudServiceSettings();
        
        try {
            $this->account = CloudServiceAccountFactory::assemble($this->type, $this->code);
            $this->strategy = CloudServiceStrategyFactory::assemble($this->type, $this->account, $this->settings);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function upload(CloudServiceFile $file)
    {
        return $this->strategy->upload($file); 
    }

    function delete(DeleteCloudServiceFile $file)
    {
        return $this->strategy->delete($file);
    }
}