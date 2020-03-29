<?php

function registerNamespace($namespace, $folder)
{
    static $autoloader;

    if (!$autoloader) {
        $autoloader = new \Phi\Autoloader\Autoloader();
        spl_autoload_register(function ($calledClassName) use ($autoloader) {
            $autoloader->autoload($calledClassName);
        });
    }
    $autoloader->addNamespace($namespace, $folder);

}