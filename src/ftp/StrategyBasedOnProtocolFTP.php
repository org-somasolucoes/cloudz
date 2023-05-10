<?php

namespace SomaGestao\CloudService\Ftp;

use SomaGestao\CloudService\Strategy\CloudServiceStrategy;

abstract class StrategyBasedOnProtocolFTP extends CloudServiceStrategy
{
    protected abstract function login();
    protected abstract function changeToWorkDir();
}