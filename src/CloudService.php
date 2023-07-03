<?php

namespace SomaSolucoes\Cloudz;

use Exception;
use SomaSolucoes\Cloudz\Strategy\CloudServiceStrategy;
use SomaSolucoes\Cloudz\Strategy\CloudServiceStrategyFactory;

final class CloudService
{
    private string $cloudServiceType;
    private ?int $cloudServiceCode;
    private $cloudServiceAccount;
    public CloudServiceSettings $settings;
    private CloudServiceStrategy $cloudServiceStrategy;

    public function __construct(string $cloudServiceType, ?int $cloudServiceCode = null)
    {
        $this->cloudServiceType = $cloudServiceType;
        $this->cloudServiceCode = $cloudServiceCode;
        $this->settings = new CloudServiceSettings();
        try {
            $this->cloudServiceAccount = 
                CloudServiceAccountFactory::assemble($this->cloudServiceType, $this->cloudServiceCode?:0);
            $this->cloudServiceStrategy =
                CloudServiceStrategyFactory::assemble($this->cloudServiceType, $this->cloudServiceAccount, $this->settings);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function upload(CloudServiceFile $file)
    {
        return $this->cloudServiceStrategy->upload($file); 
    }

    function delete(DeleteCloudServiceFile $file)
    {
        return $this->cloudServiceStrategy->delete($file);
    }
}