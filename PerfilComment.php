<?php
session_start();
require_once 'autenticacao.php';

$nomeUsuario = autenticacao::getUsername();
// Tenta obter o nome de usuário da URL, caso contrário, usa o nome de usuário da sessão
$nomeUsuario = isset($_GET['username']) ? $_GET['username'] : autenticacao::getUsername();

// Conecta ao banco de dados e busca informações do usuário
function buscarInformacoesUsuario($nomeUsuario) {
    $conn = new mysqli('localhost', 'root', '', 'webPro');
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT nomeUsuario, biografia, DataDeCriacao, fotoPerfil FROM perfilusuario WHERE nomeUsuario = ?");
    $stmt->bind_param("s", $nomeUsuario);
    $stmt->execute();
    $stmt->bind_result($nomeUsuarioRetornado, $biografia, $dataDeCriacao, $fotoPerfil);
    if (!$stmt->fetch()) {
        $stmt->close();
        $conn->close();
        return null; // Retorna nulo se não encontrar o usuário
    }
    $stmt->close();
    $conn->close();

    return [
        'nomeUsuario' => $nomeUsuarioRetornado, // Corrigido de 'nomeUsuarioRetornado' para 'nomeUsuario'
        'biografia' => $biografia,
        'dataDeCriacao' => $dataDeCriacao,
        'fotoPerfil' => $fotoPerfil
    ];
}

$perfilUsuario = buscarInformacoesUsuario($nomeUsuario);
if (!$perfilUsuario) {
    die("Usuário não encontrado.");
}

// Define o timezone
date_default_timezone_set('America/Sao_Paulo');
$dataDeCriacao = new DateTime($perfilUsuario['dataDeCriacao']);
$dataAtual = new DateTime();

// Formata ambas as datas para 'Y-m-d' para comparação
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
        <link rel="stylesheet" href="estilos.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: transparent; /* Fundo transparente */
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column; /* Adiciona esta linha para empilhar verticalmente */
                align-items: center; /* Centraliza os elementos horizontalmente */
                justify-content: center; /* Centraliza os elementos verticalmente na página */
                height: 100vh;
            }
            .modal {
                display: none; /* Escondido por padrão */
                position: fixed; /* Fixo na tela */
                z-index: 1; /* Sobre outros elementos */
                left: 0;
                top: 0;
                width: 100%; /* Largura total da tela */
                height: 100%; /* Altura total da tela */

            }

            .modal-content {
                position: relative;
                margin: 10% auto; /* Centralizado na tela */
                padding: 20px;
                border: 1px solid #888;
                width: 50%; /* Metade da largura da tela */
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                border-radius: 10px;
                background-image: linear-gradient(to bottom, #6e45e2, #88d3ce, #ffcc2f);

            }

            .close {
                color: #aaa;
                position: absolute;
                top: 10px;
                right: 25px;
                font-size: 30px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }

        </style>
    </head>
    <body>
        <!-- Janela Modal para o Perfil do Usuário -->

        <div id="userProfileModal" class="modal" style="display: none;">
            <span class="close" onclick="closeUserProfileModal()">&times;</span>
            <div class="avatar">
                <?php if (isset($perfilUsuario['fotoPerfil']) && file_exists($perfilUsuario['fotoPerfil'])): ?>
                    <img src="<?php echo htmlspecialchars($perfilUsuario['fotoPerfil']); ?>" alt="Foto de perfil" class="profile-image">
                <?php else: ?>
                    <i class="fas fa-user-circle avatar-icon" style="font-size: 150px;"></i>
                <?php endif; ?>
                <div class="user-name"><?php echo htmlspecialchars($perfilUsuario['nomeUsuario']); ?></div>
            </div>


            <div class="info-container" style="padding: 20px; text-align: center;">
                <p class="user-bio"><?php echo htmlspecialchars($perfilUsuario['biografia']); ?></p>
            </div>

            <div class="date-container" style="text-align: center; margin-top: 20px;">
                <p>Entrou em: <?php echo $dataDeCriacao->format('d/m/Y'); ?></p>
                <p class="creation-time-statement">Conta criada: <span class="time-detail"><?php echo $mensagemDiasConta; ?></span></p>
            </div>
        </div>


        <button onclick="openUserProfileModal();">Mostrar Perfil</button>


        <script src="scripts.js"></script>
    </body>
</html>
