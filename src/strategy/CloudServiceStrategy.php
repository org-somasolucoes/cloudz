<?php 

namespace SomaGestao\CloudService\Strategy;

use Throwable;
use SomaGestao\CloudService\CloudServiceFile;
use SomaGestao\CloudService\BeingCloudService;
use SomaGestao\CloudService\CloudServiceSettings;
use SomaGestao\CloudService\DeleteCloudServiceFile;
use SomaGestao\CloudService\Response\CloudServiceResponseError;
use SomaGestao\CloudService\Response\CloudServiceResponseSuccess;
use SomaGestao\CloudService\Response\CloudServiceResponseDeleteSuccess;

abstract class CloudServiceStrategy implements BeingCloudService
{
    protected CloudServiceSettings $settings;

    protected abstract function beforeExecute();
    protected abstract function doUpload(CloudServiceFile $file);
    protected abstract function doDelete(DeleteCloudServiceFile $file);
    protected abstract function afterExecute();

    public function __construct(CloudServiceSettings $settings)
    {
        $this->settings = $settings;
    }

    public function upload(CloudServiceFile $file)
    {
        error_reporting(0);
        try {
            $this->beforeExecute();

            $resourceUrl = $this->doUpload($file);

            if ($this->settings->get('canDeleteAfterUpload', true)) {
                @unlink($file->getLocalFile());
            }

            $response = new CloudServiceResponseSuccess(200, $resourceUrl);
        } catch (Throwable $e) {
            $response = new CloudServiceResponseError($e->getCode(), $e->getMessage());
        } finally {
            $this->afterExecute();
        }

        return $response;
    }

    public function delete(DeleteCloudServiceFile $file)
    {
        error_reporting(0);
        try{
            $this->beforeExecute();

            $resourceMessage = $this->doDelete($file);

            $response = new CloudServiceResponseDeleteSuccess(200, $resourceMessage);
        } catch (Throwable $e) {
            $response = new CloudServiceResponseError($e->getCode(), $e->getMessage());
        } finally {
            $this->afterExecute();
        }

        return $response;
    }
}