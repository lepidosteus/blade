<?php

namespace Lepidosteus\Blade;

use Illuminate\View\Component as LaravelComponent;

abstract class Component extends LaravelComponent
{
    protected function view($view, $data = [], array $mergeData = []): string
    {
        /**
         * You should probably use whatever Container you have to get your Blade instance instead
         * But if you don't have anything else, this helper function will allow you to render from components
         */
        return Container::getInstance()->get(Blade::class)->render($view, $data, $mergeData);
    }
}
