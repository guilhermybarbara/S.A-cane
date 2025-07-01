<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];
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
        $erro = "E-mail inválido!";
    } else {
        $sql = "INSERT INTO clientes (nome, email, cpf, telefone, cep, endereco, cidade, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $nome, $email, $cpf, $telefone, $cep, $endereco, $cidade, $estado, $id_usuario);

        if ($stmt->execute()) {
            $mensagem = "✅ Cliente cadastrado com sucesso!";
        } else {
            if ($conn->errno == 1062) {
                $erro = "❌ CPF já cadastrado no sistema.";
            } else {
                $erro = "❌ Erro ao cadastrar: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            margin: 0;
            padding: 0px;
        }
        body {
            background-image: url('sistema.png');
            background-size:1430px 1000px;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 60;
            padding: 60;
        }
        .container {
            max-width: 600px;
            background: transparent;
            padding: 30px;
            border-radius: 50px;
            margin: auto;
            
        }
        h2, .header {
            text-align: center;
            color: black;
        }
        label {
            margin-top: 10px;
            display: block;
            font-weight: bold;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 4px;
            border: 3px solid #ccc;
            border-radius: 4px;
            background-color: transparent;
            color: #000;
        }
        button {
            background: transparent;
            color: black;
            border: none;
            padding: 15px 30px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 10px;
            width: 104%;
            border: 3px solid #ccc;
        }
        button:hover {
            background: green;
            border: 3px solid #ccc;
        }
        .success {
            background: transparent;
            color: #155724;
            padding: 0px;
            border-radius: 4px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 0px;
            border-radius: 4px;
        }
        a {
            display: block;
            margin-top: 15px;
            color: black;
            text-align: center;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Cadastrar Novo Cliente</h2>

        <?php if ($mensagem): ?>
            <div class="success"><?= $mensagem ?></div>
        <?php endif; ?>
        <?php if ($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>CPF:</label>
            <input type="text" name="cpf" required maxlength="14">

            <label>Telefone:</label>
            <input type="text" name="telefone">

            <label>CEP:</label>
            <input type="text" id="cep" name="cep" maxlength="9">

            <label>Endereço:</label>
            <input type="text" id="endereco" name="endereco">

            <label>Cidade:</label>
            <input type="text" id="cidade" name="cidade">

            <label>Estado:</label>
            <input type="text" id="estado" name="estado" maxlength="2">

            <button type="submit">Salvar Cliente</button>
        </form>

        <a href="listar_clientes.php">← Voltar para a Listagem</a>
    </div>

<script>
document.getElementById('cep').addEventListener('blur', function () {
    var cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
        fetch('https://viacep.com.br/ws/' + cep + '/json/')
            .then(response => {
                if (!response.ok) throw new Error('Erro ao buscar o CEP.');
                return response.json();
            })
            .then(data => {
                if (!data.erro) {
                    document.getElementById('endereco').value = data.logradouro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('estado').value = data.uf || '';
                } else {
                    alert('CEP não encontrado.');
                }
            })
            .catch(error => {
                alert('Erro ao buscar CEP: ' + error.message);
            });
    }
});
</script>

</body>
</html>
