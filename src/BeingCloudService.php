<?php 

namespace SomaSolucoes\Cloudz;

interface BeingCloudService {
    function upload(CloudServiceFile $file);
    function delete(DeleteCloudServiceFile $file);
}