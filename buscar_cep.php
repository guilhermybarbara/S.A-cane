<?php
if (isset($_GET['cep'])) {
    $cep = preg_replace('/[^0-9]/', '', $_GET['cep']);
    $url = "https://viacep.com.br/ws/{$cep}/json/";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resposta = curl_exec($ch);
    curl_close($ch);

    echo $resposta;
}
?>
