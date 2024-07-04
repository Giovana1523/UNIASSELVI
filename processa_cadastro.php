<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $db = new SQLite3('cadastro.db');
    } catch (Exception $e) {
        die("Erro ao abrir banco de dados: " . $e->getMessage());
    }

    // Verificar se o e-mail já está em uso
    $stmt = $db->prepare('SELECT email FROM usuarios WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    if ($row) {
        die("Erro ao realizar cadastro: E-mail já está em uso.");
    }

    // Inserir novo usuArio
    try {
        $stmt = $db->prepare('INSERT INTO usuarios (nome, email, password) VALUES (:name, :email, :password)');
        if (!$stmt) {
            throw new Exception("Erro ao preparar instrução: " . $db->lastErrorMsg());
        }
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':password', $password, SQLITE3_TEXT);

        $result = $stmt->execute();
        if ($result) {
            // Cadastro realizado com sucesso, exibir mensagem e redirecionar
            echo "
            <!DOCTYPE html>
            <html lang='pt-br'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Cadastro Bem-sucedido</title>
                <link rel='stylesheet' href='styles.css'>
                <style>
                    .message-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        background-color: #f0f0f0;
                    }
                    .message-box {
                        text-align: center;
                        background-color: #fff;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 10px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .message-box h2 {
                        margin-bottom: 20px;
                        color: #772d4f;
                        
                    }
                    .message-box p {
                        margin-bottom: 20px;
                        font-size: 1.1em;
                    }
                    .message-box a {
                        color: #772d4f;
                        text-decoration: none;
                        font-weight: bold;
                    }
                </style>
                <script>
                    setTimeout(function(){
                        window.location.href = 'login.html';
                    }, 3000);
                </script>
            </head>
            <body>
                <div class='message-container'>
                    <div class='message-box'>
                        <h2>Cadastro realizado com sucesso!</h2>
                        <p>Você será redirecionado para a página de login em 3 segundos.</p>
                        <p>Se não for redirecionado, <a href='login.html'>clique aqui</a>.</p>
                    </div>
                </div>
            </body>
            </html>";
        } else {
            throw new Exception("Erro ao executar a instrução SQL: " . $db->lastErrorMsg());
        }
    } catch (Exception $e) {
        die("Erro ao realizar cadastro: " . $e->getMessage());
    }
} else {
    echo "Método de requisição inválido.";
}
?>
