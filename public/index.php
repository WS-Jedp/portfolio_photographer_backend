<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Http\Request;
use Dotenv\Dotenv;

$PATH_ENV = __DIR__ . "/../";
$dotenv = Dotenv::createImmutable($PATH_ENV);
$dotenv->load();

$entry = new Request();

