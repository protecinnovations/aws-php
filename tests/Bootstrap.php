<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Mockery\Loader;
$loader->register();

function aws_autoload($class)
{
    if (\strpos($class, '\\') === false)
    {
        $class = __NAMESPACE__ . '\\' . $class;
    }

    $path = str_replace('\\','/', $class);

    $path = ltrim($path, '/') . '.php';

    $basepath = realpath(dirname(__FILE__) . '/../library');

    $path = $basepath . '/' . $path;

    if (!\file_exists($path))
    {
        return false;
    }

    require_once($path);
};

spl_autoload_register('aws_autoload');
