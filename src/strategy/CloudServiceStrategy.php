<?php 

namespace SomaSolucoes\Cloudz\Strategy;

use Throwable;
use SomaSolucoes\Cloudz\CloudServiceFile;
use SomaSolucoes\Cloudz\BeingCloudService;
use SomaSolucoes\Cloudz\CloudServiceSettings;
use SomaSolucoes\Cloudz\DeleteCloudServiceFile;
use SomaSolucoes\Cloudz\Response\CloudServiceResponseError;
use SomaSolucoes\Cloudz\Response\CloudServiceResponseSuccess;
use SomaSolucoes\Cloudz\Response\CloudServiceResponseDeleteSuccess;


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