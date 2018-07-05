<?php namespace Framework\Core;


final class Environment
{

    const SEPARATOR = "=";
    const SEPARATOR_OPTION = ",";

    public function __construct(array $paths = array())
    {
        foreach($paths as $path) {
            if((! is_readable($path)) && is_dir($path)) {
                throw new \RuntimeException("Not loaded $path !");
            }
            $this->setEnvironment($this->parser(file_get_contents($path)));
        }
    }

    private function setEnvironment(array $data): void
    {
        foreach($data as $key => $value) {
            if(isset($_ENV[$key]) || isset($_SERVER[$key])) {
                continue;
            }
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
            $envKey[$key] = true;
        }
        if($envKey) {
            $envKey = implode(',', array_keys($envKey));
            $_SERVER['FRAMEWORK'] = $envKey;
            $_ENV['FRAMEWORK'] = $envKey;
        }
    }

    private function parser(?string $data): array
    {
        $data = $this->cleanParser($data);
        foreach(explode('\\n', $data) as $val) {
            if(!empty(preg_match('/(^[\w\s=:\/@.,-]+)/m', $val, $match))) {
                if(is_bool(strpos($match[0], self::SEPARATOR)) === false) {
                    list($key, $value) = explode(self::SEPARATOR, $match[0]);
                    $environment[trim($key)] = (is_bool(strpos($value, self::SEPARATOR_OPTION)) === false)
                        ? explode(self::SEPARATOR_OPTION, $value)
                        : trim($value);
                }
            }
        }
        return $environment ?? [];
    }

    private function cleanParser(?string $value): string
    {
        $value = str_replace(array("\r\n", "\r"), "\\n", $value);
        return $value;
    }
}