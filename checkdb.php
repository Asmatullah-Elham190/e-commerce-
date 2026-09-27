<?php
$m = @new mysqli("localhost", "root", "12345");
if ($m->connect_error) { echo "CONNECT ERROR: " . $m->connect_error . "\n"; exit; }
$r = $m->query("SHOW DATABASES");
echo "---- DATABASES ----\n";
while ($row = $r->fetch_array()) { echo "DB: " . $row[0] . "\n"; }

$m2 = @new mysqli("localhost", "root", "12345", "db_ecommerce");
if ($m2->connect_error) { echo "db_ecommerce ERROR: " . $m2->connect_error . "\n"; exit; }
echo "---- TABLES in db_ecommerce ----\n";
$r = $m2->query("SHOW TABLES");
$names = array();
while ($row = $r->fetch_array()) { $names[] = $row[0]; }
echo "TABLE COUNT: " . count($names) . "\n";
echo implode(", ", $names) . "\n";

foreach (array("users", "business_settings", "roles", "settings") as $t) {
    if (in_array($t, $names)) {
        $c = $m2->query("SELECT COUNT(*) c FROM `$t`");
        $row = $c->fetch_array();
        echo "COUNT $t = " . $row['c'] . "\n";
    }
}
if (in_array("users", $names)) {
    $c = $m2->query("SELECT id,name,email,user_type FROM users LIMIT 10");
    while ($row = $c->fetch_array()) { echo "USER: {$row['id']} | {$row['name']} | {$row['email']} | {$row['user_type']}\n"; }
}
if (in_array("business_settings", $names)) {
    $c = $m2->query("SELECT type,value FROM business_settings WHERE type IN ('purchase_code','system_default_currency','home_default_currency')");
    while ($row = $c->fetch_array()) { echo "BS: {$row['type']} = {$row['value']}\n"; }
}

﻿<?php
$m = @new mysqli("localhost","root","12345");
if ($m->connect_error) { echo "CONNECT ERROR: ".$m->connect_error."\n"; exit; }
$r = $m->query("SHOW DATABASES");
while($row=$r->fetch_row) echo "DB: ".$row[0]."\n";
echo "---- tables in db_ecommerce ----\n";
$m2 = @new mysqli("localhost","root","12345","db_ecommerce");
if($m2->connect_error){ echo "db_ecommerce ERROR: ".$m2->connect_error."\n"; exit; }
$r=$m2->query("SHOW TABLES"); $n=0; $names=[];
while($row=$r->fetch_row){$names[]=$row[0];$n++;}
echo "TABLE COUNT: $n\n";
echo implode(", ", array_slice($names,0,40))."\n";
