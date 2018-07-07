<?php

error_reporting(E_ALL);

set_time_limit(0);

ob_implicit_flush();


$host = "localhost";
$port = "13840";
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

define("SOCKET_LAST_ERROR", "\"" . socket_strerror(socket_last_error($socket)) . "\"\n");

if($socket === false) {
    echo "La creación del socket ha fallado debido a " . SOCKET_LAST_ERROR;
}

if(socket_bind($socket, $host, $port) === false) {
    echo "El bindeo del socket ha fallado debido a " .  SOCKET_LAST_ERROR;
}

if(socket_listen($socket, 5) === false) {
    echo "El listen en el socket ha fallado debido a " . SOCKET_LAST_ERROR;
}

do {
    if(($msgSocket = socket_accept($socket)) === false) {
        echo "El socket_accept ha fallado por " . SOCKET_LAST_ERROR;
        break;

        $msg = "Bienvenido al servidor de Da Homa.\n Para salir del servidor de Da Homa puedes escribir 'quit'.\n Para cerrar el servidor puedes escribir 'shutdown'\n";
        socket_write($msgSocket, $msg, strlen($msg));
    }
    do {

        if(($buf = socket_read($msgSocket, 2048, PHP_NORMAL_READ)) === false) {
            echo "El socket_read ha fallado por " . SOCKET_LAST_ERROR;
            break;
        }

        if(!$buf = trim($buf)) {
            continue;
        }

        if($buf == 'quit') {
            break;
        }

        if($buf == 'shutdown') {
            socket_close($msgSocket);
            break 2;
        }

        $m = "[" . date('H:i') . "] Usuario dijo: " . $buf . ".\n";
        socket_write($msgSocket, $m, strlen($m));
        echo "Mensaje escrito: " . $buf . "\n";

    } while (true);
    socket_close($msgSocket);
} while (true);
socket_close($socket);

echo "Servidor alojado en " . $host . " en el puerto " . $port . ", waiting for connection.";

?>