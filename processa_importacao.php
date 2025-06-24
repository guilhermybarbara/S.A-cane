<?php
session_start();
include '../includes/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: importar.php");
    exit();
}

if (!isset($_FILES['arquivo_csv']) || $_FILES['arquivo_csv']['error'] != 0) {
    die("Erro no upload do arquivo.");
}

$arquivo = $_FILES['arquivo_csv']['tmp_name'];
$handle = fopen($arquivo, "r");

if ($handle === false) {
    die("Erro ao abrir o arquivo.");
}

$linhas = [];
$erros = [];
$linha_num = 0;

// Espera colunas: nome, cpf, email, telefone, cep, endereco, cidade, estado
while (($data = fgetcsv($handle, 1000, ",")) !== false) {
    $linha_num++;

    // Pula a linha de cabeçalho (assumindo que tenha)
    if ($linha_num == 1 && strtolower($data[0]) == 'nome') {
        continue;
    }

    if (count($data) < 8) {
        $erros[] = "Linha $linha_num: faltam colunas.";
        continue;
    }

    list($nome, $cpf, $email, $telefone, $cep, $endereco, $cidade, $estado) = array_map('trim', $data);

    $erro_linha = [];

    if (!$nome) $erro_linha[] = "Nome vazio";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erro_linha[] = "E-mail inválido";
    if (strlen($cpf) != 14) $erro_linha[] = "CPF inválido (formato esperado: XXX.XXX.XXX-XX)";

    if (count($erro_linha) > 0) {
        $erros[] = "Linha $linha_num: " . implode(", ", $erro_linha);
        continue;
    }

    $linhas[] = [
        'nome' => $nome,
        'cpf' => $cpf,
        'email' => $email,
        'telefone' => $telefone,
        'cep' => $cep,
        'endereco' => $endereco,
        'cidade' => $cidade,
        'estado' => $estado
    ];
}

fclose($handle);

if (count($erros) > 0) {
    echo "<h2>Erros na importação</h2><ul>";
    foreach ($erros as $erro) {
        echo "<li style='color:red;'>$erro</li>";
    }
    echo "</ul>";
    echo "<p><a href='importar.php'>Voltar</a></p>";
    exit();
}

$sql = "INSERT INTO clientes (nome, cpf, email, telefone, cep, endereco, cidade, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

$sucesso = 0;
$duplicados = 0;

foreach ($linhas as $cliente) {
    $stmt->bind_param(
        "ssssssssi",
        $cliente['nome'],
        $cliente['cpf'],
        $cliente['email'],
        $cliente['telefone'],
        $cliente['cep'],
        $cliente['endereco'],
        $cliente['cidade'],
        $cliente['estado'],
        $id_usuario
    );
    if (@$stmt->execute()) {
        $sucesso++;
    } else {
        // Erro duplicado (cpf único)
        if ($conn->errno == 1062) {
            $duplicados++;
        } else {
            echo "Erro ao inserir cliente: " . $stmt->error . "<br>";
        }
    }
}

echo "<h2>Importação concluída</h2>";
echo "<p>$sucesso clientes importados com sucesso.</p>";
if ($duplicados > 0) {
    echo "<p style='color:orange;'>$duplicados clientes já estavam cadastrados e foram ignorados.</p>";
}
echo "<p><a href='importar.php'>Voltar</a></p>";
