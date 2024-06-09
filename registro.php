<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$dbname = "webpro";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$name = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$confirmPassword = filter_input(INPUT_POST, 'confirmPassword', FILTER_SANITIZE_STRING);

// Verifica o e-mail
if (!$email) {
    $_SESSION['error_message'] = 'Endereço de e-mail inválido.';
    header('Location: registro.php');
    exit;
}

// Verifica a senha
if (strlen($password) < 8 || strlen($password) > 20) {
    $_SESSION['error_message'] = 'A senha deve ter entre 8 e 20 caracteres.';
    header('Location: registro.php');
    exit;
}

// Confirma a senha
if ($password !== $confirmPassword) {
    $_SESSION['error_message'] = 'As senhas não correspondem.';
    header('Location: registro.php');
    exit;
}

// Verifica se o nome de usuário já existe
$checkUser = $conn->prepare("SELECT nomeUsuario FROM perfilusuario WHERE nomeUsuario = ?");
$checkUser->bind_param("s", $name);
$checkUser->execute();
$checkUser->store_result();
if ($checkUser->num_rows > 0) {
    $_SESSION['error_message'] = 'Este nome de usuário já está em uso. Por favor, escolha outro.';
    header('Location: cadastro.php');
    exit;
}
$checkUser->close();

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO perfilusuario (nomeUsuario, email, senha, DataDeCriacao) VALUES (?, ?, ?, CURDATE())";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error_message'] = 'Erro ao preparar a declaração: ' . htmlspecialchars($conn->error);
    header('Location: cadastro.php');
    exit;
}

$stmt->bind_param("sss", $name, $email, $hashedPassword);
if ($stmt->execute()) {
    $_SESSION['success_message'] = 'Cadastro realizado com sucesso. Faça login!';
    header('Location: login.php');
    exit();
} else {
    $_SESSION['error_message'] = 'Erro ao cadastrar usuário: ' . $stmt->error;
    header('Location: cadastro.php');
    exit;
}

$stmt->close();
$conn->close();
?>
