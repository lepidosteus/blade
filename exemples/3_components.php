<?php
require __DIR__.'/../vendor/autoload.php';

use Lepidosteus\Blade\Blade;

$path_to_templates = __DIR__.'/templates';
$path_to_compiled = __DIR__.'/compiled';

$blade = new Blade($path_to_templates, $path_to_compiled);

echo $blade->render('3_anonymous_component', ['title' => 'working !']);
