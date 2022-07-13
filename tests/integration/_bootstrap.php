<?php

// start DI container
$container = require __DIR__ . '/../../app/_bootstrap.php';

// store container
\Codeception\Util\Fixtures::add('container', $container);
