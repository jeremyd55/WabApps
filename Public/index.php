<?php

declare(strict_types = 1);

use Framework\Core\Autoload;

$required = '../Bin/Core/Autoload.php';

if(! file_exists($required)) {
    throw new \RuntimeException("Not found $required");
}

require $required;

if(! class_exists(Autoload::class)) {
    throw new \RuntimeException('Not found in the Autoload');
}

(new Autoload())->load(['../.env']);

new \Framework\Core\Route\Container();
