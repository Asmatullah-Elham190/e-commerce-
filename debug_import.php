<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sql_path = base_path('shop.sql');
echo "SQL file exists: " . (file_exists($sql_path) ? "YES" : "NO") . "\n";
echo "SQL file size: " . filesize($sql_path) . " bytes\n";

try {
    DB::unprepared(file_get_contents($sql_path));
    echo "shop.sql imported successfully\n";
} catch (Exception $e) {
    echo "Error importing shop.sql: " . $e->getMessage() . "\n";
}
