<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Paginação
$limite = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina - 1) * $limite : 0;

// Total de clientes
$sql_count = "SELECT COUNT(*) AS total FROM clientes WHERE id_usuario = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $id_usuario);
$stmt_count->execute();
$res_count = $stmt_count->get_result();
$total_clientes = $res_count->fetch_assoc()['total'];
$total_paginas = ceil($total_clientes / $limite);

// Clientes desta página
$sql = "SELECT * FROM clientes WHERE id_usuario = ? ORDER BY nome LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $id_usuario, $inicio, $limite);
$stmt->execute();
$result = $stmt->get_result();

function ocultarCPF($cpf) {
    return substr($cpf, 0, 3) . ".***.***-" . substr($cpf, -2);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Listagem de Clientes</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f3f5;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #e9ecef;
        }
        tr:hover {
            background-color: #f8f9fa;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .pagination a {
            padding: 8px 12px;
            margin: 0 3px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        nav {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="header">Meus Clientes</div>

    <div class="container">
        <nav>
            <a href="painel.php">← Voltar ao Painel</a> |
            <a href="cadastrar_cliente.php">+ Cadastrar Novo Cliente</a> |
            <a href="importar.php">⬆ Importar</a>
        </nav>

        <table>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>

            <?php while ($cliente = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= htmlspecialchars(ocultarCPF($cliente['cpf'])) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $cliente['id'] ?>">✏️ Editar</a> |
                        <a href="excluir.php?id=<?= $cliente['id'] ?>" onclick="return confirm('Confirma exclusão deste cliente?')">🗑️ Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_paginas; $i++) : ?>
                <a href="?pagina=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>

</body>
</html>
