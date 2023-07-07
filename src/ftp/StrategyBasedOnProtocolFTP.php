<?php

namespace SOMASolucoes\Cloudz\FTP;

use SOMASolucoes\Cloudz\Strategy\CloudServiceStrategy;

abstract class StrategyBasedOnProtocolFTP extends CloudServiceStrategy
{
    protected abstract function login();
    protected abstract function changeToWorkDir();
}