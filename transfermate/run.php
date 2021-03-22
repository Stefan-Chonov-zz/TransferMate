<?php

require __DIR__ . '/vendor/autoload.php';

use Transfermate\Core\DB;
use Transfermate\Utils\Utils;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Utils::createBooksDbTable(DB::getInstance());

$app = new Transfermate\App();
$app->start();