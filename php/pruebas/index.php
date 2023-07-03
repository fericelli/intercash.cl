<?php
$mysqli = new mysqli("localhost", "u956446715_root", "3H?eEvoa>n", "u956446715_intercash");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";


?>