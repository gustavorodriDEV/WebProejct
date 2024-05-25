<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();
$nomeUsuario = autenticacao::getUsername();

$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Atualização da biografia se houver uma requisição POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $novaBiografia = $_POST['biografia'] ?: 'Sem descrição';  // Se vazio, retorna 'Sem descrição'
    $stmt = $conn->prepare("UPDATE perfilusuario SET biografia = ? WHERE nomecompleto = ?");
    $stmt->bind_param("ss", $novaBiografia, $nomeUsuario);
    $stmt->execute();
    $stmt->close();
    // Recarrega a página para refletir as mudanças imediatamente
    header("Refresh:0");
}

// Buscar os dados do usuário
$stmt = $conn->prepare("SELECT nomecompleto, email, biografia, localizacao FROM perfilusuario WHERE nomecompleto = ?");
$stmt->bind_param("s", $nomeUsuario);
$stmt->execute();
$stmt->bind_result($nomecompleto, $email, $biografia, $localizacao);
$stmt->fetch();
$stmt->close();
$conn->close();

$biografia = $biografia ?: 'Sem descrição';  // Exibe 'Sem descrição' se biografia for vazia
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="estilos.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
</head>
<body>
    <div class="avatar" onclick="window.location.href = 'avatarPerfil.html';">
        <i class="fas fa-user-circle avatar-icon" style="font-size: 150px;"></i>
        <div class="user-name"><?php echo htmlspecialchars($nomecompleto); ?></div>
    </div>

    <div class="info-container">
        <div class="line-separator"></div>
        <div class="name-description">
            <p id="userNameDisplay" class="placeholder-text"><?php echo htmlspecialchars($nomecompleto); ?></p>
            <p id="userEmailDisplay" class="placeholder-text"><?php echo htmlspecialchars($email); ?></p>
            <form method="POST" action="">
                <textarea id="userDescriptionDisplay" name="biografia" class="placeholder-text"><?php echo htmlspecialchars($biografia); ?></textarea>
                <p id="userLocationDisplay" class="placeholder-text"><?php echo htmlspecialchars($localizacao); ?></p>
                <button type="submit" id="openModalButton">Alterar</button>
            </form>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>
</html>
