<?php

require __DIR__ . '/../vendor/autoload.php';

use Transfermate\Web\Core\DB;
use Transfermate\Web\Utils\Utils;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Utils::createBooksDbTable(DB::getInstance());

new Transfermate\Web\Web();
