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
            background-color: #121212;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }body {
            background-image: url('sistema.png');
            background-size:1430px 1000px;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            background: transparent;
            padding: 30px;
            border-radius: 10px;
            border: 3px solid #ccc;
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
            background-color: transparent;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            border: 3px solid #ccc;
        }
        button:hover {
            background-color: green;
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
