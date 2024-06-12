#!/usr/bin/env php
<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Pockit\Common\Database;

require __DIR__ . '/../src/bootstrap.php';

$em = Database::getEM();
ConsoleRunner::run(
    new SingleManagerProvider($em)
);