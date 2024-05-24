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
$sql = "SELECT Senha FROM perfilusuario WHERE NomeCompleto = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Erro de preparação: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    // Apenas para diagnóstico
    if ($passwordForm !== $user['Senha']) {
        // Falha na autenticação, exibir senhas para diagnóstico
        echo "Falha na autenticação.<br>";
        echo "Senha recebida (não modificada): " . htmlspecialchars($passwordForm) . "<br>";
        echo "Senha no banco de dados: " . $user['Senha'];
        exit;
    } else {
        // A senha está correta, configurar a sessão
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;

        // Redirecionar para index.html
        header("Location: index.php");
        exit;
    }
} else {
    // Nome de usuário não encontrado
    echo "Nome de usuário não encontrado.";
}

$stmt->close();
$conn->close();
?>
