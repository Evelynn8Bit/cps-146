<?php
require "../vendor/autoload.php";
require "db.php";

$results = DB::query("SELECT * FROM users");
print_r($results);