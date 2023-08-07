<?php 

namespace SOMASolucoes\CloudZ;

interface BeingCloudService {
    function upload(CloudServiceFile $file);
    function delete(DeleteCloudServiceFile $file);
}