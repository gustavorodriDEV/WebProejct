<?php
session_start();
require_once 'autenticacao.php';
autenticacao::checkLogin();
$nomeUsuario = autenticacao::getUsername();

$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$diretorio = "imgUsuario/";
if (!file_exists($diretorio)) {
    mkdir($diretorio, 0755, true);
}

if (isset($_FILES['image']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'upload') {
    $caminhoArquivo = $diretorio . basename($_FILES['image']['name']);
    if ($_FILES['image']['error'] == 0) {
        $fileType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $caminhoArquivo)) {
                $stmt = $conn->prepare("UPDATE perfilusuario SET fotoPerfil = ? WHERE nomeUsuario = ?");
                $stmt->bind_param("ss", $caminhoArquivo, $nomeUsuario);
                $stmt->execute();
                $stmt->close();
                header("Refresh:0");
            } else {
                echo "Erro ao mover o arquivo.";
            }
        } else {
            echo "Tipo de arquivo não permitido.";
        }
    } else {
        echo "Erro no upload: " . $_FILES['image']['error'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST['action'])) {
    $novaBiografia = !empty($_POST['biografia']) ? $_POST['biografia'] : 'Sem descrição';
    $stmt = $conn->prepare("UPDATE perfilusuario SET biografia = ? WHERE nomeUsuario = ?");
    $stmt->bind_param("ss", $novaBiografia, $nomeUsuario);
    $stmt->execute();
    $stmt->close();
    header("Refresh:0");
}

// Após recuperar os dados do banco de dados
$stmt = $conn->prepare("SELECT nomeUsuario, biografia, DataDeCriacao, fotoPerfil FROM perfilusuario WHERE nomeUsuario = ?");
$stmt->bind_param("s", $nomeUsuario);
$stmt->execute();
$stmt->bind_result($nomeUsuarioRetornado, $biografia, $dataDeCriacao, $fotoPerfil);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($dataDeCriacao) {
    $dataDeCriacao = new DateTime($dataDeCriacao);
    $dataAtual = new DateTime();
    $intervalo = $dataDeCriacao->diff($dataAtual);
    $diasDeConta = $intervalo->days;
} else {
    $diasDeConta = 0;
}

if ($diasDeConta == 0) {
    $mensagemDiasConta = "hoje";
} elseif ($diasDeConta == 1) {
    $mensagemDiasConta = "1 dia atrás";
} elseif ($diasDeConta < 7) {
    $mensagemDiasConta = "$diasDeConta dias atrás";
} elseif ($diasDeConta < 30) {
    $mensagemDiasConta = "há " . floor($diasDeConta / 7) . " semanas";
} elseif ($diasDeConta < 365) {
    $mensagemDiasConta = "há " . floor($diasDeConta / 30) . " meses";
} else {
    $mensagemDiasConta = "há mais de um ano";
}
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil do Usuário</title>
        <link rel="stylesheet" href="estilos.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <style>
            .profile-image {
                border-radius: 50%;
                width: 150px;
                height: 150px;
                object-fit: cover;
            }
        </style>
    </head>
    <body>
        <div class="avatar" onclick="document.getElementById('fileInput').click();">
            <?php if (isset($fotoPerfil) && file_exists($fotoPerfil)): ?>
                <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de perfil" class="profile-image">
            <?php else: ?>
                <i class="fas fa-user-circle avatar-icon" style="font-size: 150px;"></i>
            <?php endif; ?>
            <form method="POST" action="" enctype="multipart/form-data">
                <input id="fileInput" type="file" name="image" style="display: none;" onchange="this.form.submit();">
                <input type="hidden" name="action" value="upload">
            </form>
            <div class="user-name"><?php echo htmlspecialchars($nomeUsuario); ?></div>
        </div>




        <div class="info-container">
            <div class="line-separator"></div>
            <div class="name-description">
                <p id="userNameDisplay" class="placeholder-text"><?php echo htmlspecialchars($nomeUsuarioRetornado); ?></p>
                <p id="userBioDisplay" class="placeholder-text"><?php echo htmlspecialchars($biografia); ?></p>
                <?php if ($nomeUsuario === $nomeUsuarioRetornado): ?>
                    <form method="POST" action="">
                        <button type="button" id="openModalButton">Alterar</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form method="POST" action="">
                    <label for="userDescriptionDisplay">Biografia:</label>
                    <textarea id="userDescriptionDisplay" name="biografia"><?php echo htmlspecialchars($biografia); ?></textarea>
                    <button type="submit" id="submitInfo">Salvar Alterações</button>
                </form>
            </div>
        </div>

        <!-- Exibição da data de criação e dos dias de conta -->
        <div class="date-container" style="text-align: center; margin-top: 20px;">
            <p>Entrou em: <?php echo $dataDeCriacao->format('d/m/Y'); ?></p>
            <p class="creation-time-statement">Conta criada: <span class="time-detail"><?php echo $mensagemDiasConta; ?></span></p>

        </div>

        <script src="scripts.js"></script>
    </body>
</html>