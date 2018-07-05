<?php namespace Framework\Core;


class Autoload
{

    private $environment = null;

    public function load(array $pathEnv = array())
    {
        $this->environment = $this->setEnvironment(Environment::class, $pathEnv);
        $this->register();
    }

    private function setEnvironment($env, $path)
    {
        $class = explode('\\', $env);
        $class = str_replace('\\', DIRECTORY_SEPARATOR, __DIR__) . DIRECTORY_SEPARATOR
            .$class[count($class)-1] . '.php';
        if($this->requireFiles($class)) {
            if(class_exists($env)) {
                return new $env($path);
            }
        }
    }

    private function register()
    {
        spl_autoload_register(array($this, 'loadClass'), true);
    }

    private function loadClass($class)
    {

        $prefix = $class;
        while(false !== $pos = strrpos($prefix, '\\')) {

            $prefix = substr($class, 0, $pos + 1);
            $relative = substr($class, $pos + 1);

            $file = $this->fileLoad($prefix, $relative);

            if($file)
                return $file;

            $prefix = rtrim($prefix, '\\');
        }
        return false;
    }

    private function fileLoad($prefix, $relative)
    {

        $prefix = substr($prefix, 0, -1);
        if($prefix !== __NAMESPACE__) {
            return false;
        }

        $file = str_replace('\\', DIRECTORY_SEPARATOR, __DIR__) . DIRECTORY_SEPARATOR
            .str_replace('\\', DIRECTORY_SEPARATOR, $relative) . '.php';
        if($this->requireFiles($file))
            return $file;

        return false;
    }

    private function requireFiles(string $file): bool
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}