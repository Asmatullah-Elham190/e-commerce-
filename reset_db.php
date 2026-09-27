<?php
require "vendor/autoload.php";
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(".");
$dotenv->load();
$host = $_ENV["DB_HOST"];
$user = $_ENV["DB_USERNAME"];
$pass = $_ENV["DB_PASSWORD"];
$port = $_ENV["DB_PORT"];
$conn = mysqli_connect($host, $user, $pass, "", $port);
if (!$conn) { echo "Connection failed\n"; exit; }
mysqli_query($conn, "DROP DATABASE IF EXISTS db_ecommerce");
mysqli_query($conn, "CREATE DATABASE db_ecommerce CHARACTER SET utf8 COLLATE utf8_unicode_ci");
echo "Database recreated\n";
$result = mysqli_query($conn, "SHOW TABLES FROM db_ecommerce");
echo "Tables: " . mysqli_num_rows($result) . "\n";
mysqli_close($conn);
