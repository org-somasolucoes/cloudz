<?php

namespace SomaSolucoes\Cloudz\Ftp;

use SomaSolucoes\Cloudz\Strategy\CloudServiceStrategy;

abstract class StrategyBasedOnProtocolFTP extends CloudServiceStrategy
{
    protected abstract function login();
    protected abstract function changeToWorkDir();
}