# blade
Use blade 8 outside of laravel, in an easy to use library

Supports:
- rendering 
  - subviews
  - inheritance / layouts 
  - rendering views from string
- customization
  - custom if statements
  - custom directives
  - custom echo handlers
- components
  - registered component
  - namespaced components
  - anonymous components
- mostly everything, really

I created this because:
- I wanted a ready-to-use library rather than snippet of codes getting out of syncs
- I wanted something that fully supported components, including namespaced and anonymous

Most of the original code was from [mattstauffer/Torch](https://github.com/mattstauffer/Torch/blob/master/components/view/index.php), I packaged it in a ready to use library, then added the support for features on top. I couldn't find any License or in-file copyright in their repository but comment and description make it clear it's meant to be used and shared. Original authors remain the full owner of their code, and deserve a million thanks for doing all the hard parts.

The actual rendering is done by Laravel's library under the hood, this library doesn't replicate anything it just created the environnement expected for all things to run smoothly (facades, container, config, ...).

Whatever I added on top is under Public Domain.

## usage

Install with ```composer require lepidosteus/blade```


```php
<?php
require __DIR__.'/../vendor/autoload.php';

use Lepidosteus\Blade\Blade;

$blade = new Blade('/path/to/template', '/path/to/compiled/files');

// done, you can start rendering
echo $blade->render('template_name', ['key' => 'value']);
// has the same result as:
echo $blade()->make('template_name', ['key' => 'value'])->render();
// has the same result as:
echo $blade->view()->make('template_name', ['key' => 'value'])->render();

// shared key will be available in all templates 
$blade()->share('shared_key', 'shared_value');

// easily add a directive
$blade->directive('upperize', function ($expression) {
    return "<?php echo strtoupper($expression); ?>";
});
// or $blade->view()->directive(...

// easily add a custom if 
$blade->if('foo', function ($value) {
    return 'bar' === $value;
});
// or $blade->view()->if(...

// register a component
$blade->component(\App\Components\Awesome::class, 'awesome');
// now use it in views with <x-awesome />

// register a namespace of components
$blade->componentNamespace('\\App\\Components', 'foo');
// now use it in views with <x-foo::awesome />
```

## required libraries:

- "php": ">=8.0": might work with earlier versions (probably anything >=7.4 at least), but I didn't bother testing it 
- "illuminate/container": "^8.25": needed to provide our own replacement
- "illuminate/view": "^8.25": blade itself, it will pull every other dependencies it needs
- "ramsey/uuid": "^4.1": needed for custom directives
