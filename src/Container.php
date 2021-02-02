<?php 
declare(strict_types = 1);

namespace Lepidosteus\Blade;

use Illuminate\Container\Container as LaravelContainer;

class Container extends LaravelContainer
{
    public function getNamespace()
    {
        return '';
    }
}
