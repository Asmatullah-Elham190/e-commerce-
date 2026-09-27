<?php
$s = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if (socket_bind($s, '127.0.0.1', 8002)) {
    echo "Port 8002 OK\n";
    socket_listen($s);
    sleep(5);
} else {
    echo "Failed to bind\n";
}
socket_close($s);
