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

// Se o usuário confirmou a exclusão
if (isset($_POST['confirmar'])) {
    $sql_delete = "DELETE FROM clientes WHERE id = ? AND id_usuario = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $id_cliente, $id_usuario);

    if ($stmt_delete->execute()) {
        header("Location: listar_clientes.php?msg=excluido");
        exit();
    } else {
        $erro = "❌ Erro ao excluir cliente: " . $stmt_delete->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Excluir Cliente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin: 10px;
            cursor: pointer;
        }
        .confirm {
            background: #dc3545;
            color: white;
        }
        .cancel {
            background: #6c757d;
            color: white;
        }
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

    <div class="box">
        <h2>Deseja realmente excluir o cliente?</h2>
        <p><strong><?= htmlspecialchars($cliente['nome']) ?></strong></p>

        <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

        <form method="POST">
            <button type="submit" name="confirmar" class="confirm">Sim, Excluir</button>
            <a href="listar_clientes.php"><button type="button" class="cancel">Cancelar</button></a>
        </form>
    </div>

</body>
</html>
