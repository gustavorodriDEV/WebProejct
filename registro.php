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

// Verifique se o e-mail é válido
if (!$email) {
    echo "Endereço de e-mail inválido.";
    exit;
}

// Hashing da senha antes de armazenar no banco de dados
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Inserir dados no banco, incluindo a data de criação
$sql = "INSERT INTO perfilusuario (nomeUsuario, email, senha, DataDeCriacao) VALUES (?, ?, ?, CURDATE())";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Erro ao preparar a declaração: " . htmlspecialchars($conn->error);
    exit;
}

$stmt->bind_param("sss", $name, $email, $hashedPassword);

if ($stmt->execute()) {
    // Redirecionar para a tela de login
    header('Location: login.html');
    exit();
} else {
    // Imprimir erros se o cadastro falhar
    echo "Erro ao cadastrar usuário: " . $stmt->error;
}

// Fechar statement e conexão
$stmt->close();
$conn->close();
?>
