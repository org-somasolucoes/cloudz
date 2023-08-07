<?php

namespace SOMASolucoes\CloudZ\FTP;

use SOMASolucoes\CloudZ\Strategy\CloudServiceStrategy;

abstract class StrategyBasedOnProtocolFTP extends CloudServiceStrategy
{
    protected abstract function login();
    protected abstract function changeToWorkDir();
}