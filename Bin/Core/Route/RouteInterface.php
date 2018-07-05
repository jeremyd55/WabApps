<?php

namespace Framework\Core\Route;


interface RouteInterface
{
    public function __construct($path);

    public function getListener();

    public function get($key = null);

    public function getPath($key = null);
}