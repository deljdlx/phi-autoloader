<?php
namespace Phi\Autoloader;


class Autoloader
{


    protected $namespaces = array();
    protected $classIndex = false;


    public function addNamespace($namespace, $folder)
    {
        $this->namespaces[$namespace] = $folder;
        $this->generateNamespaceClassIndex($namespace, $folder);
        return $this;
    }


    public function autoload($calledClassName)
    {


        if (!$this->classIndex) {
            foreach ($this->namespaces as $namespace => $folder) {
                $this->generateNamespaceClassIndex($namespace, $folder);
            }
        }

        $normalizedClassName = strtolower($calledClassName);

        if (isset($this->classIndex[$normalizedClassName])) {
            include($this->classIndex[$normalizedClassName]);
        }
    }

    public function register()
    {
        spl_autoload_register(function ($calledClassName) {
            $this->autoload($calledClassName);
        });
    }


    protected function generateNamespaceClassIndex($namespace, $folder)
    {
        $folder = normalizeFilepath($folder);

        if (is_dir($folder)) {
            $dir_iterator = new \RecursiveDirectoryIterator($folder);
            $iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($iterator as $file) {
                if (strrpos($file, '.php')) {

                    $fileName = str_replace('\\', '/', (string)$file);
                    $className = filepathToClassName(str_replace($folder, $namespace, $fileName));
                    $parts = explode('\\', $className);

                    $size = count($parts);
                    if($parts[$size - 1] == $parts[$size - 2]) {
                        array_pop($parts);
                        $className = implode('\\', $parts);
                    }

                    $this->classIndex[strtolower($className)] = (string)$file;
                }
            }
        }
    }
}