<?php
try {
    $db = new SQLite3('cadastro.db');
    $query = 'CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL
    )';
    $db->exec($query);
    echo "Banco de dados e tabela criados com sucesso!";
} catch (Exception $e) {
    die("Erro ao criar banco de dados ou tabela: " . $e->getMessage());
}
?>
