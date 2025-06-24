<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Painel</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: white;
            font-family: Arial, sans-serif;
        }
        body {
            background-color: black; /* cor azul clarinha, suave */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0; 
        }
        
        body {
            background-image: url('sistema.png');
            background-size:1000px 990px;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
    }

        .header {
            background-color: black;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: transparent;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px white(0,0,0,0.1);
            text-align: center;
        }
        h2 {
            color: #333;
        }
        nav {
            margin-top: 25px;
        }
        nav a {
            display: inline-block;
            margin: 10px;
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        nav a:hover {
            background-color: #218838;
        }
        nav a.logout {
            background-color: #dc3545;
        }
        nav a.logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="header">Painel Principal</div>

    <div class="container">
        <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']); ?>!</h2>

        <nav>
            <a href="listar_clientes.php">📋 Listar Clientes</a>
            <a href="cadastrar_cliente.php">➕ Cadastrar Cliente</a>
            <a href="importar_clientes.php">⬆ Importar</a>
            <a href="logout.php" class="logout">🚪 Sair</a>
        </nav>
    </div>

</body>
</html>
