<?php

namespace Framework\Core\Route;


class Yaml
{

    const PREFIX_COMMENT = "#";

    private $line;
    private $totalNumberLine;
    private $afterLine = 1;
    private $currentKey;
    private $optionCurrentLine = 0;
    private $filename;
    private $data = array();

    /**
     * Yaml constructor.
     * @param $filename
     */
    public function __construct(string $filename)
    {
        if((! is_file($filename)) && ! is_readable($filename)) {
            throw new \RuntimeException("Not Found $filename");
        }
        $this->doParser(file_get_contents($filename));
    }

    /**
     * @return array
     */
    public function getData()
    {
        return (array) $this->data;
    }

    /**
     * @param string $value
     */
    private function doParser(string $value)
    {
        $value = $this->cleanParser($value);
        $this->line = explode("\n", $value);
        foreach($this->line as $val) {

            if(trim($val) !== '' && is_bool(strpos($val, self::PREFIX_COMMENT))) {
                $val = urlencode($val);
                if(preg_match('/(^[^\+][\w]*)/m', $val, $preg)) {
                    $this->totalNumberLine +=1;
                    $this->currentKey = $preg[0];
                    $this->data[$this->currentKey] = array();
                }

                if(preg_match('/(^[\+]{4}+[\w]+.*)/m', $val, $preg)) {
                    $preg[0] = str_replace('::', '#', urldecode($preg[0]));
                    list($key, $value) = explode(':', trim(urldecode($preg[0])));
                    $value = empty(trim($value))? $this->parseBlock(): trim($value);
                    $this->data[$this->currentKey][trim($key)] = $value;
                }
            }
            $this->afterLine++;
        }
    }

    /**
     * @param string $value
     * @return string
     */
    private function cleanParser(string $value): string
    {
        $value = str_replace(array("\r\n", "\r"), "\n", $value);
        return $value;
    }

    private function parseBlock()
    {
        $matched = array();
        $this->optionCurrentLine = 0;
        while($this->getWhileLine()) {
            if(!empty($preg = $this->getNextCurrentLine($this->afterLine + $this->optionCurrentLine))) {
                list($key, $value) = explode(':', urldecode($preg));
                $matched[trim($key)] = trim($value);
            } else {
                return $matched;
            }
            $this->optionCurrentLine++;
        }
    }

    /**
     * @return bool
     */
    private function getWhileLine()
    {
        if($this->afterLine + $this->optionCurrentLine >= count($this->line)) {
            return false;
        }
        return true;
    }

    /**
     * @param int $line
     * @return null|string
     */
    private function getNextCurrentLine(int $line)
    {
        if(preg_match('/(^[\+]{8,}+[\w]+.*)/m', urlencode($this->line[$line]), $match)) {
            return urldecode($match[0]);
        }
        return null;
    }
}