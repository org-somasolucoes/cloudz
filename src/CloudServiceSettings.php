<?php

namespace SOMASolucoes\Cloudz;

use Ds\Map;

class CloudServiceSettings {

    private Map $settings;

    public function __construct()
    {
        $this->settings = new Map();
    }

    public function add(string $key, $value)
    {
        if (is_bool($value) OR !empty($value)) {
            $this->settings->put($key, $value);
        }
    }

    public function get(string $key, $default = null)
    {
        return $this->settings->get($key, $default);
    }

    public function contains(string $key)
    {
        return $this->settings->hasKey($key); 
    }
}