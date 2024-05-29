<?php
session_start(); // Iniciar a sessão

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

// Obter username e password do formulário
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$passwordForm = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// Buscar usuário pelo nome de usuário no banco
$sql = "SELECT senha FROM perfilusuario WHERE nomeUsuario = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Erro de preparação: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    if (!password_verify($passwordForm, $user['senha'])) {
        echo "Falha na autenticação.";
        exit;
    } else {
        // A senha está correta, configurar a sessão
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        header("Location: index.php");
        exit;
    }
} else {
    echo "Nome de usuário não encontrado.";
}

$stmt->close();
$conn->close();
?>
