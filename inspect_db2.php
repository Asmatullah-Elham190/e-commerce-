<?php
$m2 = @new mysqli("localhost", "root", "12345", "db_ecommerce");
if ($m2->connect_error) { echo "ERR: " . $m2->connect_error . "\n"; exit; }
echo "---- ROLES ----\n";
$r = $m2->query("SELECT id,name,guard_name FROM roles");
while ($row = $r->fetch_array()) { echo "ROLE: " . $row['id'] . " | " . $row['name'] . " | " . $row['guard_name'] . "\n"; }
echo "---- users ----\n";
$r = $m2->query("SELECT id,name,email,user_type FROM users");
while ($row = $r->fetch_array()) { echo "USER: " . $row['id'] . " | " . $row['name'] . " | " . $row['email'] . " | " . $row['user_type'] . "\n"; }
echo "---- model_has_roles ----\n";
$r = $m2->query("SELECT * FROM model_has_roles");
while ($row = $r->fetch_array()) { echo json_encode($row) . "\n"; }
echo "---- key business_settings ----\n";
$r = $m2->query("SELECT type,LEFT(value,60) v FROM business_settings WHERE type IN ('purchase_code','system_default_currency','home_default_currency','system_name','current_currency')");
while ($row = $r->fetch_array()) { echo $row['type'] . " = " . $row['v'] . "\n"; }
echo "---- currencies count ----\n";
$r = $m2->query("SELECT COUNT(*) c FROM currencies");
echo $r->fetch_array()['c'] . "\n";
echo "---- shops ----\n";
$r = $m2->query("SELECT id,name FROM shops");
while ($row = $r->fetch_array()) { echo "SHOP: " . $row['id'] . " | " . $row['name'] . "\n"; }
