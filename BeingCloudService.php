<?php 

namespace SomaGestao\CloudService;

interface BeingCloudService {
    function upload(CloudServiceFile $file);
    function delete(DeleteCloudServiceFile $file);
}