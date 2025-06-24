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
    <title>importar</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }
        .header {
            text-align: center;
            font-size: 22px;
            color: #333;
            margin-bottom: 20px;
        }
        form label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        input[type="file"] {
            margin-bottom: 15px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        nav a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">Importar</div>

    <form action="processa_importacao.php" method="post" enctype="multipart/form-data">
        <label for="arquivo_csv">Selecione o arquivo CSV:</label>
        <input type="file" name="arquivo_csv" id="arquivo_csv" accept=".csv" required>

        <button type="submit">Importar</button>
    </form>

    <nav>
        <a href="painel.php">← Voltar ao Painel</a>
    </nav>
</div>

</body>
</html>
