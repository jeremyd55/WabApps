<?php

namespace Framework\Core\Route;


class Route implements RouteInterface
{
    private $listener = [];

    public function __construct($path = null)
    {
        $path = is_array($path)? $path[0]: $path;

        if((!file_exists($path)) && !is_readable($path)) {
            throw new \RuntimeException("Not found $path");
        }
        $this->setListener($path);
    }

    private function setListener($path)
    {
        if(!empty($data = (new Yaml($path))->getData())) {
            $this->parseUri($data);
        }
        return $this;
    }

    private function parseUri(array $data)
    {
        $list = [];
        foreach($data as $k => $v) {
            $list['name'] = $k;
            if(is_array($v)) {
                  foreach($v as $key => $val) {
                      switch($key) {
                          case "path":
                              $list = array_merge($list, $this->parsePath($val));
                              break;
                          case "from":
                              list($controller, $method) = explode("#", $val);
                              $list['controller'] = $controller;
                              $list['function'] = $method;
                              break;
                          case "methods":
                              $list['method'] = (strpos($val, '}') === (strlen($val)-1))
                                  ?substr($val, 1, -1)
                                  : $val;
                              break;
                          case 'defaults':
                              $list['defaults'] = $val;
                              break;
                          case "pattern":
                              $list['pattern'] = $val;
                              break;
                      }
                  }
            }
            $this->listener = array_merge_recursive($this->listener, [$list]);
        }
        return $this;
    }

    private function parsePath($path)
    {
        if(strpos($path, '{')) {
            if(preg_match('(\{.*\})', $path, $match)) {
                $path = substr($path, 0, (-strlen($match[0])) -1);
                preg_match_all('/(\{(.*?)\})/', $match[0], $match);
                $filter = array_unique($match[2]);
            }
        }

        return [
            'filter' => $filter ?? [],
            'path' => $path ?? '/'
        ];
    }

    public function get($key = null): array
    {
        if(empty($key)) {
            return $this->getListener();
        }

        foreach($this->getListener() as $k => $v) {
            if(array_key_exists($key, $v)) {
                $data[] = ['id' => $k, $key => $v[$key]];
            }
        }

        return $data ?? [];
    }

    public function getListener(): array
    {
        return $this->listener;
    }

    public function getPath($key = null): array
    {
        foreach($this->getListener() as $k => $v) {
            if(array_key_exists('path', $v)) {
                if(empty($key)) {
                    $list[] = $v['path'];
                } else {
                    if(in_array($key, $v)) {
                        return $v;
                    }
                }

            }
        }
        return $list ?? [];
    }
}