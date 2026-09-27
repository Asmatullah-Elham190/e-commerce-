<?php
$m = new mysqli("localhost", "root", "12345", "db_ecommerce");
if ($m->connect_error) { echo "ERR\n"; exit; }
$r = $m->query("SELECT * FROM addons");
while ($row = $r->fetch_array()) { echo "ADDON: " . $row['id'] . " | " . $row['unique_identifier'] . " | " . $row['name'] . " | st:" . $row['status'] . "\n"; }
echo "--- addons columns ---\n";
$r = $m->query("SHOW COLUMNS FROM addons");
while ($row = $r->fetch_array()) { echo "  col " . $row['Field'] . "\n"; }
echo "--- languages ---\n";
$r = $m->query("SELECT id,name,code,status FROM languages");
while ($row = $r->fetch_array()) { echo "LANG: " . $row['id'] . " | " . $row['name'] . " | " . $row['code'] . " | " . $row['status'] . "\n"; }
echo "--- categories count ---\n";
$r = $m->query("SELECT COUNT(*) c FROM categories");
echo $r->fetch_array()['c'] . "\n";
echo "--- general_settings types sample ---\n";
$r = $m->query("SELECT type, LEFT(value,40) v FROM business_settings WHERE type LIKE 'general%' OR type LIKE 'shop%' LIMIT 10");
while ($row = $r->fetch_array()) { echo $row['type'] . " = " . $row['v'] . "\n"; }
echo "--- shop row ---\n";
$r = $m->query("SELECT id,user_id,name FROM shops");
while ($row = $r->fetch_array()) { echo "SHOP " . $row['id'] . " user:" . $row['user_id'] . " " . $row['name'] . "\n"; }
