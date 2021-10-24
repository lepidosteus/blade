<?php

namespace Lepidosteus\Blade;

class Config
{
    public function __construct(
        protected array $settings
    ) {
    }

    public function get(string $key): string
    {
       return $this->settings[$key] ?? throw new \ErrorException('Unknown config key requested: '.$key);
    }
}