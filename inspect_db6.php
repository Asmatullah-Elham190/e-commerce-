<?php
$m = new mysqli("localhost", "root", "12345");
$r = $m->query("select @@hostname h, @@port p, @@server_uuid u, @@socket s, @@basedir b");
$r = $r->fetch_array();
echo "SERVER: host=" . $r['h'] . " port=" . $r['p'] . " uuid=" . $r['u'] . " sock=" . $r['s'] . " basedir=" . $r['b'] . PHP_EOL;
$r = $m->query("select count(*) c from information_schema.tables where table_schema='db_ecommerce'");
echo "db_ecommerce tables = " . $r->fetch_array()['c'] . PHP_EOL;
$m->select_db("db_ecommerce");
$r = $m->query("show tables");
$t = array();
while ($row = $r->fetch_array()) { $t[] = $row[0]; }
echo "SHOW TABLES count = " . count($t) . PHP_EOL;
echo "first 20: " . implode(", ", array_slice($t, 0, 20)) . PHP_EOL;
$r = $m->query("select count(*) c from users");
echo "users = " . $r->fetch_array()['c'] . PHP_EOL;
