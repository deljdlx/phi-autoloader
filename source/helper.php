<?php

function registerNamespace($namespace, $folder)
{
    static $autoloader;
    static $componentAutoloader;
    if (!$autoloader) {
        $autoloader = new \Phi\Autoloader();
        spl_autoload_register(function ($calledClassName) use ($autoloader) {
            $autoloader->autoload($calledClassName);
        });
    }
    $autoloader->addNamespace($namespace, $folder);


    if (!$componentAutoloader) {
        $componentAutoloader = new \Phi\Autoloader\Package();
        spl_autoload_register(function ($calledClassName) use ($componentAutoloader) {
            $componentAutoloader->autoload($calledClassName);
        });
    }
    $componentAutoloader->addNamespace($namespace, $folder);
}