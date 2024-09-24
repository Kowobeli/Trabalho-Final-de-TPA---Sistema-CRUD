<?php
// db_connection.php

function db_connect() {
    $host = 'localhost'; // Endereço do servidor MySQL
    $db = 'shop_dbb'; // Nome do banco de dados
    $user = 'root'; // Usuário do banco de dados
    $pass = ''; // Senha do banco de dados

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        // Configurar o PDO para lançar exceções em caso de erro
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Falha na conexão: " . $e->getMessage();
        exit();
    }
}
?>
