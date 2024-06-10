<?php
session_start();
require_once 'autenticacao.php';
autenticacao::checkLogin();
require 'conexao.php';
$nomeUsuario = autenticacao::getUsername();
include 'navBar.php';
echo $GLOBALS['navbar'];


// Após recuperar os dados do banco de dados
$stmt = $conn->prepare("SELECT nomeUsuario, biografia, DataDeCriacao, fotoPerfil FROM perfilusuario WHERE nomeUsuario = ?");
$stmt->bind_param("s", $nomeUsuario);
$stmt->execute();
$stmt->bind_result($nomeUsuarioRetornado, $biografia, $dataDeCriacao, $fotoPerfil);
$stmt->fetch();
$stmt->close();
$conn->close();

date_default_timezone_set('America/Sao_Paulo');

$dataDeCriacao = new DateTime($dataDeCriacao);
$dataAtual = new DateTime();

$dataDeCriacaoFormatada = $dataDeCriacao->format('Y-m-d');
$dataAtualFormatada = $dataAtual->format('Y-m-d');

if ($dataDeCriacaoFormatada == $dataAtualFormatada) {
    $mensagemDiasConta = "hoje";
} else {
    $dataDeCriacao->setTime(0, 0, 0);
    $dataAtual->setTime(0, 0, 0);
    $intervalo = $dataDeCriacao->diff($dataAtual);
    $totalDias = $intervalo->days;

    if ($totalDias == 1) {
        $mensagemDiasConta = "há 1 dia atrás";
    } elseif ($totalDias < 7) {
        $mensagemDiasConta = "há $totalDias dias atrás";
    } elseif ($totalDias < 30) {
        $mensagemDiasConta = "há " . floor($totalDias / 7) . " semana(s)";
    } elseif ($totalDias < 365) {
        $mensagemDiasConta = "há " . floor($totalDias / 30) . " mês(es)";
    } else {
        $mensagemDiasConta = "há " . floor($totalDias / 365) . " ano(s)";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil do Usuário</title>
        <link rel="stylesheet" href="Perfil_StyleSheet.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

    </head>
    <body>
        <div class="avatar">
            <?php if (isset($fotoPerfil) && file_exists($fotoPerfil)): ?>
                <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de perfil" class="profile-image">
            <?php else: ?>
                <i class="fas fa-user-circle avatar-icon" style="font-size: 150px;"></i>
            <?php endif; ?>
            <div class="user-name"><?php echo htmlspecialchars($nomeUsuario); ?></div>
        </div>

        <div class="info-container">
            <div class="divider"></div>
            <div class="name-description">
                <p id="userNameDisplay" class="placeholder-text"><?php echo nl2br(htmlspecialchars($nomeUsuarioRetornado)); ?></p>
                <p id="userBioDisplay" class="placeholder-text"><?php echo nl2br(htmlspecialchars($biografia)); ?></p>
            </div>
        </div>
        <div id="bioErrorMessage" style="color: red; display: none; margin-bottom: 10px;"></div>
        <div class="date-container" style="text-align: center; margin-top: 20px;">
            <p>Entrou em: <?php echo $dataDeCriacao->format('d/m/Y'); ?></p>
            <p class="creation-time-statement">Conta criada: <span class="time-detail"><?php echo $mensagemDiasConta; ?></span></p>
        </div>

        <script src="scripts.js"></script>
        <script src="perfil_Script.js"></script>

        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('infoModal').style.display = 'none';">&times;</span>
                <form action="updatePerfil.php" method="post" enctype="multipart/form-data">
                    <label for="bio" style="color: white;">Biografia:</label>
                    <textarea id="bio" name="bio" rows="4" cols="50" maxlength="200"></textarea>
                    <input type="hidden" name="action" value="updateBio">
                    <button type="submit" style="color: white; background-color: black;">Atualizar Biografia</button>
                </form>
                <form action="updatePerfil.php" method="post" enctype="multipart/form-data">
                    <button type="button" onclick="document.getElementById('fileInput').click();" style="color: white; background-color: black;">Trocar Foto de Perfil</button>
                    <input id="fileInput" type="file" name="image" style="display: none;" onchange="this.form.submit();">
                    <input type="hidden" name="action" value="uploadFoto">
                </form>
                <form action="updatePerfil.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="removeFoto">
                    <button type="submit" style="background-color: red; color: white;">Remover Foto de Perfil</button>
                </form>
            </div>
        </div>

    </body>
</html>