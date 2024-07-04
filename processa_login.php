<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $db = new SQLite3('cadastro.db');
    } catch (Exception $e) {
        die("Erro ao abrir banco de dados: " . $e->getMessage());
    }

    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        
        $_SESSION['email'] = $email; // Salva o e-mail na sessão se precisar usar depois
        header("Location: index.html");
        exit;
    } else {
        die("Erro ao realizar login: Credenciais inválidas.");
    }
} else {
    echo "Método de requisição inválido.";
}
?>
