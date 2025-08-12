<?php
$dbhost = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "cosmetico";

// Conexão MySQLi (para compatibilidade com código existente)
$conexao = new mysqli($dbhost, $dbusername, $dbpassword, $dbname);

if ($conexao->connect_error) {
    die("Erro na conexão MySQLi: " . $conexao->connect_error);
}

// Conexão PDO (para os novos scripts)
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8mb4", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro na conexão PDO: " . $e->getMessage());
}z