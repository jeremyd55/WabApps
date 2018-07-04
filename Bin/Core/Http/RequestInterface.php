<?php namespace Framework\Core\Http;


interface RequestInterface
{

    public function __construct();

    public function getParam(string $key = null);

    public function getServer(string $key = null);

    public function getEnvironment(string $key = null);

    public function getMethod();
}