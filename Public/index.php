<?php

declare(strict_types = 1);

use Framework\Autoload;

$required = '../Bin/Autoload.php';

if(! file_exists($required)) {
    throw new \RuntimeException("Not found $required");
}

require $required;

if(! class_exists(Autoload::class)) {
    throw new \RuntimeException('Not found in the Autoload');
}

(new Autoload())->load(['../.env']);

