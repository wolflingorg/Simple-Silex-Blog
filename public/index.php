<?php
require_once(__DIR__ . '/../vendor/autoload.php');

putenv("APP_ENV=DEV");

app\application(true)->run();
