<?php
// Definindo as constantes para a conexão com o banco de dados
if (!defined('DB_CONFIG')) {
    define('DB_CONFIG', [
        'host' => 'localhost',
        'dbname' => 'nome_do_banco',
        'user' => 'usuario_mysql',
        'pass' => 'senha_mysql'
    ]);
}

// Criando a conexão com o banco de dados usando PDO
try {
    $dsn = 'mysql:host=' . DB_CONFIG['host'] . ';dbname=' . DB_CONFIG['dbname'];
    $conexao = new PDO($dsn, DB_CONFIG['user'], DB_CONFIG['pass']);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<strong>ERRO DE PDO = </strong>" . $e->getMessage();
}
