<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

if (!isset($_GET['id'])) {
    header("Location: listar_clientes.php");
    exit();
}

$id_cliente = (int)$_GET['id'];

// Verificar se o cliente pertence ao usuário
$sql = "SELECT * FROM clientes WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_cliente, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Cliente não encontrado ou acesso negado.");
}

$cliente = $result->fetch_assoc();

$mensagem = '';
$erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $cep = trim($_POST['cep']);
    $endereco = trim($_POST['endereco']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "❌ E-mail inválido!";
    } else {
        $sql = "UPDATE clientes SET nome=?, email=?, cpf=?, telefone=?, cep=?, endereco=?, cidade=?, estado=? WHERE id=? AND id_usuario=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssiii", $nome, $email, $cpf, $telefone, $cep, $endereco, $cidade, $estado, $id_cliente, $id_usuario);

        if ($stmt->execute()) {
            $mensagem = "✅ Cliente atualizado com sucesso!";
            $cliente['nome'] = $nome;
            $cliente['email'] = $email;
            $cliente['cpf'] = $cpf;
            $cliente['telefone'] = $telefone;
            $cliente['cep'] = $cep;
            $cliente['endereco'] = $endereco;
            $cliente['cidade'] = $cidade;
            $cliente['estado'] = $estado;
        } else {
            $erro = "❌ Erro ao atualizar: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f7;
            padding: 20px;
        }
        .container {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">Editar Cliente</div>

        <?php if ($mensagem): ?>
            <div class="success"><?= $mensagem ?></div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" required value="<?= htmlspecialchars($cliente['nome']) ?>">

            <label>Email:</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($cliente['email']) ?>">

            <label>CPF:</label>
            <input type="text" name="cpf" required maxlength="14" value="<?= htmlspecialchars($cliente['cpf']) ?>">

            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?= htmlspecialchars($cliente['telefone']) ?>">

            <label>CEP:</label>
            <input type="text" name="cep" maxlength="9" value="<?= htmlspecialchars($cliente['cep']) ?>">

            <label>Endereço:</label>
            <input type="text" name="endereco" value="<?= htmlspecialchars($cliente['endereco']) ?>">

            <label>Cidade:</label>
            <input type="text" name="cidade" value="<?= htmlspecialchars($cliente['cidade']) ?>">

            <label>Estado:</label>
            <input type="text" name="estado" maxlength="2" value="<?= htmlspecialchars($cliente['estado']) ?>">

            <button type="submit">Salvar Alterações</button>
        </form>

        <a href="listar_clientes.php">← Voltar para a Listagem</a>
    </div>

</body>
</html>
