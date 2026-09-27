<?php
require "vendor/autoload.php";
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(".");
$dotenv->load();
$host = $_ENV["DB_HOST"];
$user = $_ENV["DB_USERNAME"];
$pass = $_ENV["DB_PASSWORD"];
$db = $_ENV["DB_DATABASE"];
$port = $_ENV["DB_PORT"];
$conn = mysqli_connect($host, $user, $pass, $db, $port);
if (!$conn) { echo "Connection failed\n"; exit; }
$result = mysqli_query($conn, "SHOW TABLES");
echo "Tables in $db:\n";
while ($row = mysqli_fetch_row($result)) {
    echo "  - " . $row[0] . "\n";
}
mysqli_close($conn);
