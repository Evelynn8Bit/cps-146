<?php
require __DIR__ . '/../vendor/autoload.php';

use Veems\DB;

$results = DB::query("SELECT * FROM users");
print_r($results);