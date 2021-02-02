<?php
require __DIR__.'/../vendor/autoload.php';

use Lepidosteus\Blade\Blade;

$path_to_templates = __DIR__.'/templates';
$path_to_compiled = __DIR__.'/compiled';

$blade = new Blade($path_to_templates, $path_to_compiled);

$blade->directive('upperize', function ($expression) {
    return "<?php echo strtoupper($expression); ?>";
});

$blade->if('foo', function ($value) {
    return 'bar' === $value;
});

echo $blade->render('4_directives', ['title' => 'working !']);
