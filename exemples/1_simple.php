<?php
require __DIR__.'/../vendor/autoload.php';

use Lepidosteus\Blade\Blade;

$path_to_templates = __DIR__.'/templates';
$path_to_compiled = __DIR__.'/compiled';

$blade = new Blade($path_to_templates, $path_to_compiled);

echo $blade->render('1_simple', ['text' => 'This is a test !']);

echo $blade()->make('1_simple', ['text' => 'You may also want to access the view factory directly, use $blade() or $blade->view() for that'])->render();

$view = $blade->view();
echo $view->make('1_simple', ['text' => 'But if you do it often better to catch it once'])->render();

$view->share('foo', 'bar');
echo $view->make('1_simple', ['text' => 'Shared values also work'])->render();
