<?php
session_start();
include __DIR__ . '/../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

// Configuração da paginação
$limite = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina - 1) * $limite : 0;

// Contar total de clientes do usuário
$sql_count = "SELECT COUNT(*) as total FROM clientes WHERE id_usuario = ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("i", $id_usuario);
$stmt_count->execute();
$res_count = $stmt_count->get_result();
$total_clientes = $res_count->fetch_assoc()['total'];

$total_paginas = ceil($total_clientes / $limite);

// Buscar clientes para a página atual
$sql = "SELECT * FROM clientes WHERE id_usuario = ? ORDER BY nome LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $id_usuario, $inicio, $limite);
$stmt->execute();
$result = $stmt->get_result();

function ocultarCPF($cpf) {
    // Exibe o CPF com parte oculta, ex: 123.***.***-45
    return substr($cpf, 0, 4) . "***.***-" . substr($cpf, -2);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Listagem de Clientes</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <h2>Meus Clientes</h2>
    <p><a href="painel.php">Voltar ao Painel</a></p>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>CPF</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($cliente = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nome']) ?></td>
                    <td><?= htmlspecialchars(ocultarCPF($cliente['cpf'])) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefone']) ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?= $cliente['id'] ?>">Editar</a> |
                        <a href="excluir_cliente.php?id=<?= $cliente['id'] ?>" onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($total_paginas > 1): ?>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <?php if ($i == $pagina): ?>
                    <strong><?= $i ?></strong>
                <?php else: ?>
                    <a href="?pagina=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</body>
</html>
