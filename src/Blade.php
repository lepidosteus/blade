<?php
declare(strict_types = 1);

namespace Lepidosteus\Blade;

use \Illuminate\Container\Container as LaravelContainer;

/**
 * 
 * Most of the credits and copyright go to the original at
 * 
 * https://github.com/mattstauffer/Torch/blob/master/components/view/index.php
 * 
 * I'm merely packaging it in a one-liner library with a few fixes 
 * 
 **/ 

class Blade
{
    protected $_container;
    protected $_factory;
    protected $_compiler;

    public function __construct($templates_path, string $compiled_path, ?LaravelContainer $container = null)
    {
        if (!\is_array($templates_path)) {
            $templates_path = [$templates_path];
        }

        if (!$container) {
            $container = Container::getInstance();
        }

        $this->_container = $container;

        // setup our facades
        \Illuminate\Support\Facades\Facade::setFacadeApplication($this->_container);

        // filesystem, used to find and read template files
        $filesystem = new \Illuminate\Filesystem\Filesystem;

        // compiler, turns blade files into executable php
        $this->_compiler = new \Illuminate\View\Compilers\BladeCompiler($filesystem, $compiled_path);

        // resolver, provides templates engines (we register blade and php)
        $resolver = new \Illuminate\View\Engines\EngineResolver;
        $resolver->register('blade', function () {
            return new \Illuminate\View\Engines\CompilerEngine($this->_compiler);
        });
        $resolver->register('php', function () use ($filesystem) {
            return new \Illuminate\View\Engines\PhpEngine($filesystem);
        });

        // factory, what people call "the Blade instance"
        $this->_factory = new \Illuminate\View\Factory(
            $resolver,
            new \Illuminate\View\FileViewFinder($filesystem, $templates_path), 
            new \Illuminate\Events\Dispatcher($this->_container)
        );
        $this->_factory->setContainer($this->_container);

        // provides getNamespace()
        $this->_container->instance(\Illuminate\Contracts\Foundation\Application::class, $this->_container);

        // allows View facade calls
        $this->_container->instance(\Illuminate\Contracts\View\Factory::class, $this->_factory);
        $this->_container->alias(
            \Illuminate\Contracts\View\Factory::class, 
            (new class extends \Illuminate\Support\Facades\View {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor()
        );

        // allows ifdirective (BladeCompiler translates them to several directive doing Facades\Blade calls)
        $this->_container->instance(\Illuminate\View\Compilers\BladeCompiler::class, $this->_compiler);
        $this->_container->alias(
            \Illuminate\View\Compilers\BladeCompiler::class, 
            (new class extends \Illuminate\Support\Facades\Blade {
                public static function getFacadeAccessor() { return parent::getFacadeAccessor(); }
            })::getFacadeAccessor()
        );
    }

    public function render($view, $data = [], array $mergeData = [])
    {
        return $this->_factory->make($view, $data, $mergeData)->render();
    }

    public function view()
    {
        return $this->_factory;
    }

    public function __invoke()
    {
        return $this->_factory;
    }

    public function directive(string $name, Callable $directive)
    {
        $this->_compiler->directive($name, $directive);
    }

    public function if(string $name, Callable $test)
    {
        $this->_compiler->if($name, $test);
    }

    public function share(array|string $key, mixed $value)
    {
        $this->_factory->share($key, $value);
    }
}