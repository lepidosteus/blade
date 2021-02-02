# blade
Use blade 8 outside of laravel, in an easy to use library

Supports rendering, subviews, inheritance, components, anonymous components, directive, custom if statements, ...

I created this because:
- I wanted a ready-to-use library rather than snippet of codes getting out of syncs
- I wanted something that supported anonymous components and custom if statements


This is mostly code from [mattstauffer/Torch](https://github.com/mattstauffer/Torch/blob/master/components/view/index.php) but packaged in a ready to use library, then I added if statements support and cleaned up the Facades faking so it doesn't have hardcoded values anymore.

I couldn't find any License or in-file copyright in their repository but comment and description make it clear it's meant to beused and shared. Original authors remain the full owner of their code, and deserve a million thanks for doing all the hard parts.

Whatever I added on top is under Public Domain.

```php
<?php
require __DIR__.DIRECTORY_SEPARATOR.'/../vendor/autoload.php';

use Lepidosteus\Blade\Blade;

$blade = new Blade('/path/to/template', '/path/to/compiled/files');


echo $blade->render('template_name', ['key' => 'value']);
// has the same result as:
echo $blade()->make('template_name', ['key' => 'value'])->render();
// has the same result as:
echo $blade->view()->make('template_name', ['key' => 'value'])->render();

$blade()->share('shared_key', 'shared_value');

// easily add a directive
$blade->directive('upperize', function ($expression) {
    return "<?php echo strtoupper($expression); ?>";
});
// same as
$blade->view()->directive('upperize', function ($expression) {
    return "<?php echo strtoupper($expression); ?>";
});

// easily add a custom if 
$blade->if('foo', function ($value) {
    return 'bar' === $value;
});
// same as
$blade->view()->if('foo', function ($value) {
    return 'bar' === $value;
});
```

Notes on requires:

"php": ">=7.4": might work with earlier versions (probably anythng 7.* at least), but I don't test it 
"illuminate/container": "^8.25": needed to provide our own replacement
"illuminate/view": "^8.25": blade itself, it will pull every other dependency it needs
"ramsey/uuid": "^4.1": needed for custom directives
