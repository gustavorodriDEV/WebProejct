<?php

require_once 'autenticacao.php';

autenticacao::checkLogin();
$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn . connect_error);
}

$biografia = $_POST['biografia'];
$nomeUsuario = $_SESSION['username'];

$stmt = $conn->prepare("UPDATE perfilusuario SET biografia = ? WHERE nomecompleto = ?");
$stmt->bind_param("ss", $biografia, $nomeUsuario);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Sucesso";
} else {
    echo "Erro ou nenhum dado modificado.";
}

$stmt->close();
$conn->close();
?>
