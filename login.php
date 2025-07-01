<?php
session_start();
include '../includes/conexao.php';

$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                header("Location: painel.php");
                exit();
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }

        $stmt->close();
    } else {
        $erro = "Erro ao consultar o banco de dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - CRM Clientes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #121212;
            font-family: Arial, sans-serif;
        }
        body {
            background-image: url('sistema.png');
            background-size:1430px 1000px;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding: 20px;
            background-color: transparent;
            color: black;
            font-size: 24px;
            margin-top: 20px;
        }
        .container {
            max-width: 400px;
            margin: 40px auto;
            background: transparent;
            padding: 30px;
            border-radius: 50px;
            border: 3px solid #ccc;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: transparent;
            color: black;
            padding: 10px;
            margin-top: 15px;
            border: none;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            border: 3px solid #ccc;
        }
        button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
        nav a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: black;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">Login</div>

    <div class="container">
        <h2>Entrar no Sistema</h2>

        <?php if ($erro): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <nav>
            <a href="criar_usuario.php">Criar Novo Usuário</a>
        </nav>
    </div>

</body>
</html>
