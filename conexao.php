<?php
// Ajuste seus dados de conexão aqui
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'crm_clientes';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
