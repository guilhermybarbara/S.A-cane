<?php
session_start();
include '../includes/conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (!$nome || !$email || !$senha) {
        $mensagem = "❌ Preencha todos os campos.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "❌ E-mail inválido.";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            $mensagem = "✅ Usuário criado com sucesso!";
        } else {
            if ($conn->errno == 1062) {
                $mensagem = "❌ Este e-mail já está cadastrado.";
            } else {
                $mensagem = "❌ Erro ao criar usuário: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Usuário</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            padding: 20px;
        }
        body {
            background-image: url('sistema.png');
            background-size:auto;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 60;
            padding: 60;
        }
        .container {
            background: transparent;
            max-width: 400px;
            margin: auto;
            padding: 30px;
            border-radius: 50px;
             border: 3px solid #ccc;
        }
        h2, .header {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 95%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            margin-top: 20px;
            background: transparent;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100.5%;
             border: 3px solid #ccc;
        }
        button:hover {
            background: green;
        }
        .success {
            background: transparent;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
        }
        nav a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: black;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Criar Novo Usuário</h2>

        <?php if ($mensagem): ?>
            <div class="<?= strpos($mensagem, '✅') !== false ? 'success' : 'error' ?>">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Criar Usuário</button>
        </form>

        <nav>
            <a href="login.php">← Ir para o Login</a>
        </nav>
    </div>

</body>
</html>
