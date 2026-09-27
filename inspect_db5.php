<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "DB_CONNECTION=" . config('database.default') . PHP_EOL;
echo "DB_DATABASE=" . config('database.connections.mysql.database') . PHP_EOL;
echo "DB_HOST=" . config('database.connections.mysql.host') . PHP_EOL;
try {
    $r = Illuminate\Support\Facades\DB::select('select database() as db, @@hostname as host, @@port as port, @@server_uuid as uuid, @@version as ver, @@socket as sock');
    echo "LARAVEL: db=" . $r[0]->db . " host=" . $r[0]->host . " port=" . $r[0]->port . " uuid=" . $r[0]->uuid . " ver=" . $r[0]->ver . " sock=" . $r[0]->sock . PHP_EOL;
} catch (\Throwable $e) {
    echo "DB ERROR: " . $e->getMessage() . PHP_EOL;
}
$m = @new mysqli("localhost", "root", "12345", "db_ecommerce");
$r = $m->query("select database() db, @@hostname host, @@port port, @@server_uuid uuid, @@version ver, @@socket sock");
$r = $r->fetch_array();
echo "MYSQLI : db=" . $r['db'] . " host=" . $r['host'] . " port=" . $r['port'] . " uuid=" . $r['uuid'] . " ver=" . $r['ver'] . " sock=" . $r['sock'] . PHP_EOL;
$c = $m->query("select count(*) c from languages");
echo "MYSQLI languages = " . $c->fetch_array()['c'] . PHP_EOL;
$c = $m->query("show tables like 'languages'");
echo "MYSQLI show tables languages rows=" . $c->num_rows . PHP_EOL;
try {
    $c = Illuminate\Support\Facades\DB::table('languages')->count();
    echo "languages count = $c" . PHP_EOL;
} catch (\Throwable $e) {
    echo "languages ERROR: " . $e->getMessage() . PHP_EOL;
}
