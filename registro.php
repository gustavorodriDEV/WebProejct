<?php
// Conexão com o banco de dados
$host = "localhost";
$user = "root";
$password = "";
$dbname = "webpro";
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);


// Inserir dados no banco
$sql = "INSERT INTO perfilusuario (NomeCompleto, email, senha) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);

if ($stmt->execute()) {
    // Não imprima nada se você for redirecionar.
    // Redirecionar para a tela de login
    header('Location: login.html');
    exit();
} else {
    // Somente imprima erros se o cadastro falhar
    echo "Erro ao cadastrar usuário: " . $stmt->error;
}

// Fechar statement e conexão
$stmt->close();
$conn->close();
?>
