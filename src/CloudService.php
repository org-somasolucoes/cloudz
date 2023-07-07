<?php

namespace SOMASolucoes\Cloudz;

use Exception;
use SOMASolucoes\Cloudz\Strategy\CloudServiceStrategy;
use SOMASolucoes\Cloudz\Strategy\CloudServiceStrategyFactory;

final class CloudService
{
    private string $type;
    private ?int $code;
    private $account;
    public CloudServiceSettings $settings;
    private CloudServiceStrategy $strategy;

    public function __construct(string $type, ?int $code = null)
    {
        $this->type = $type;
        $this->code = $code;
        $this->settings = new CloudServiceSettings();
        
        try {
            $this->account = CloudServiceAccountFactory::assemble($this->type, $this->code?:0);
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