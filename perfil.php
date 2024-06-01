<?php
session_start();
require_once 'autenticacao.php';
autenticacao::checkLogin();
$nomeUsuario = autenticacao::getUsername();

$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Após recuperar os dados do banco de dados
$stmt = $conn->prepare("SELECT nomeUsuario, biografia, DataDeCriacao, fotoPerfil FROM perfilusuario WHERE nomeUsuario = ?");
$stmt->bind_param("s", $nomeUsuario);
$stmt->execute();
$stmt->bind_result($nomeUsuarioRetornado, $biografia, $dataDeCriacao, $fotoPerfil);
$stmt->fetch();
$stmt->close();
$conn->close();
// Definir o fuso horário, se necessário, para garantir consistência
date_default_timezone_set('America/Sao_Paulo');

// Supondo que $dataDeCriacao seja recuperada do banco de dados como uma string
$dataDeCriacao = new DateTime($dataDeCriacao);
$dataAtual = new DateTime();

// Formatar ambas as datas para remover a hora
$dataDeCriacaoFormatada = $dataDeCriacao->format('Y-m-d');
$dataAtualFormatada = $dataAtual->format('Y-m-d');

// Comparar as datas formatadas
if ($dataDeCriacaoFormatada == $dataAtualFormatada) {
    $mensagemDiasConta = "hoje";
} else {
    // Se não for hoje, calcular o número total de dias desde a criação
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
        <div class="avatar">
            <?php if (isset($fotoPerfil) && file_exists($fotoPerfil)): ?>
                <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de perfil" class="profile-image">
            <?php else: ?>
                <i class="fas fa-user-circle avatar-icon" style="font-size: 150px;"></i>
            <?php endif; ?>
            <div class="user-name"><?php echo htmlspecialchars($nomeUsuario); ?></div>
        </div>

        <div class="info-container">
            <div class="name-description">
                <p id="userNameDisplay" class="placeholder-text"><?php echo htmlspecialchars($nomeUsuarioRetornado); ?></p>
                <p id="userBioDisplay" class="placeholder-text"><?php echo htmlspecialchars($biografia); ?></p>
                <form method="POST" action="">
                    <button type="button" id="openModalButton">Alterar</button>
                </form>
            </div>
        </div>


        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="document.getElementById('infoModal').style.display = 'none';">&times;</span>
                <form action="updatePerfil.php" method="post" enctype="multipart/form-data">
                    <label for="bio" style="color: white;">Biografia:</label>
                    <textarea id="bio" name="bio" rows="4" cols="50"></textarea>
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



        <!-- Exibição da data de criação e dos dias de conta -->
        <div class="date-container" style="text-align: center; margin-top: 20px;">
            <p>Entrou em: <?php echo $dataDeCriacao->format('d/m/Y'); ?></p>
            <p class="creation-time-statement">Conta criada: <span class="time-detail"><?php echo $mensagemDiasConta; ?></span></p>
        </div>

        <script src="scripts.js"></script>
    </body>
</html>