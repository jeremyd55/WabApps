<?php namespace Framework\Core\Http;


class Request implements RequestInterface
{
    private $uri;
    private $param = [];
    private $method;

    public function __construct()
    {
        $this->setParameter('environment', $_ENV);
        $this->setParameter('server', $_SERVER);
        $this->setParameter('post', $_POST);
        $this->setUri($this->getServer());
        $this->setMethod($this->getServer('REQUEST_METHOD'));
    }

    private function setUri(array $server): self
    {
        $this->uri = new Uri($server);
        return $this;
    }

    private function setParameter(string $name, array $value)
    {
        if(! array_key_exists($name, $this->param)) {
            $this->param[$name] = $value;
        } else {
            $this->param[$name] = array_merge_recursive($this->param[$name], $value);
        }
        return $this;
    }

    private function setMethod(array $param)
    {
        $this->method = $param[0];
        return $this;
    }

    public function getParam(?string $key = null)
    {
        if(empty($key)) {
            return $this->param;
        }

        if(array_key_exists($key, $this->param)) {
            return (array) $this->param[$key];
        }
        return [];
    }

    public function setParam(string $key, string $value): self
    {
        $this->param = array_merge_recursive($this->param, [$key => $value]);
        return $this;
    }

    public function getServer(?string $key = null)
    {
        if(empty($key)) {
            return $this->param['server'];
        }

        if(array_key_exists($key, $this->param['server'])) {
            return (array) $this->param['server'][$key];
        }
        return [];
    }

    public function getEnvironment(?string $key = null)
    {
        if(empty($key)) {
            return $this->param['environment'];
        }

        if(array_key_exists($key, $this->param['environment'])) {
            return (array) $this->param['environment'][$key];
        }
        return [];
    }

    public function getMethod()
    {
        return $this->method;
    }



}