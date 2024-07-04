<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $db = new SQLite3('cadastro.db');

    $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();

    $user = $result->fetchArray(SQLITE3_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        echo "Login bem-sucedido!";
    } else {
        echo "Email ou senha incorretos.";
    }
}
?>
