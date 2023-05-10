<?php

namespace SomaGestao\CloudService;

use SomaGestao\CloudService\Strategy\CloudServiceStrategy;

final class CloudServiceFacade 
{
    private $cloudServiceAccount;
    private CloudService $cloudService;
    public CloudServiceSettings $settings;
    private CloudServiceStrategy $cloudServiceStrategy;

    public function __construct(CloudService $cloudService)
    {
        $this->cloudService = $cloudService;
        $this->cloudServiceAccount = CloudServiceAccountFactory::assemble($this->cloudService);
        $this->settings = new CloudServiceSettings();
        $this->cloudServiceStrategy =
            CloudServiceStrategyFactory::assemble($this->cloudService, $this->cloudServiceAccount, $this->settings);
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