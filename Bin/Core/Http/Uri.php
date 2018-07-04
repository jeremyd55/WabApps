<?php

namespace Framework\Core\Http;


class Uri implements UriInterface
{

    private $port;
    private $host;
    private $scheme;
    private $query;
    private $path;

    public function __construct(array $param = array())
    {
        if(empty($param)) {
            throw new \RuntimeException('Not found URL');
        }

        $this->parseUri($param);
    }

    private function parseUri($param)
    {
        $this->setScheme('http');

        if(isset($param['SERVER_NAME'])) {
            $this->setHost($param['SERVER_NAME']);
            if(isset($param['SERVER_PORT'])) {
                $this->setPort((int) $param['SERVER_PORT']);
            }
        }

        if(! empty($param['REQUEST_URI'])) {
            preg_match('/^[^\?#]*/', $param['REQUEST_URI'], $match);
            $uri = $match[0];
        }

        $requestUri = $uri ?? '/';

        if(! isset($param['QUERY_STRING'])) {
            preg_match('/^url=([^#]*)/', $param['QUERY_STRING'], $match);
            $requestQuery = $match[1];
            $this->setQuery($requestQuery);
        }

        if(! empty($requestQuery)) {
            $requestUri = substr($requestUri, 0, -strlen($requestQuery));
        }

        $this->setPath($requestUri);

        return $this;
    }

    private function setScheme($scheme): self
    {
        $this->scheme = $scheme;
        return $this;
    }

    private function setHost($host): self
    {
        $this->host = $host;
        return $this;
    }

    private function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    private function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    private function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getScheme()
    {
        return $this->scheme;
    }
}