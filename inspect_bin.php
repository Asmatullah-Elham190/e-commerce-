<?php
$m = new mysqli("localhost", "root", "12345");
$r = $m->query("show binary logs");
$logs = array();
while ($row = $r->fetch_array()) { $logs[] = $row[0]; }
$cur = end($logs);
$r = $m->query("show binlog events in '$cur' limit 6000");
$prev = array();
$started = false;
while ($row = $r->fetch_array()) {
    $info = $row['Info'] ? preg_replace('/\s+/', ' ', $row['Info']) : '';
    if (stripos($info, 'DROP TABLE IF EXISTS `countries`') !== false) {
        $started = true;
        echo "===== DROP CONTEXT =====" . PHP_EOL;
        foreach ($prev as $p) { echo "  PREV: " . $p . PHP_EOL; }
        echo "  >>> " . $row['End_log_pos'] . " | " . $row['Event_type'] . " | " . substr($info, 0, 150) . PHP_EOL;
    } elseif ($started) {
        echo "  AFTER: " . $row['End_log_pos'] . " | " . $row['Event_type'] . " | " . substr($info, 0, 150) . PHP_EOL;
        $started = false;
    }
    $prev[] = $row['End_log_pos'] . " | " . $row['Event_type'] . " | " . substr($info, 0, 130);
    if (count($prev) > 8) { array_shift($prev); }
}




