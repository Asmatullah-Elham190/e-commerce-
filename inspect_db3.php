<?php
foreach (array("db_ecommerce", "db_ecommerce_updates") as $dbn) {
    $m = @new mysqli("localhost", "root", "12345", $dbn);
    if ($m->connect_error) { echo "$dbn -> " . $m->connect_error . "\n"; continue; }
    $c = $m->query("SELECT COUNT(*) c FROM users");
    echo "$dbn users = " . $c->fetch_array()['c'] . "\n";
    $c = $m->query("SELECT id,name,email,user_type FROM users");
    while ($row = $c->fetch_array()) { echo "   " . $row['id'] . " | " . $row['name'] . " | " . $row['email'] . " | " . $row['user_type'] . "\n"; }
    $c = $m->query("SELECT id,name FROM roles");
    while ($row = $c->fetch_array()) { echo "   ROLE " . $row['id'] . " " . $row['name'] . "\n"; }
}
