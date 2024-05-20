<?php
include 'navBar.php';
echo $GLOBALS['navbar']; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Fórum de Discussão</title>
    <link rel="stylesheet" href="estilos.css"> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
</head>
<body>
    <div class="post-container">
        <div class="post" onclick="window.location.href = '/postagem-detalhada.html';">
            <!-- A imagem do perfil inicialmente não é exibida até que uma seja selecionada -->
            <img src="" alt="Ícone do Usuário" class="imagem-perfil" style="display: none;">
            <!-- Ícone que será clicável para atualizar a imagem de perfil -->
            <i class="fas fa-user-circle avatar-icon" onclick="document.getElementById('fileInput').click()"></i>
            <div class="conteudo-post">
                <p>Resumo da postagem...</p>
                <p>Leia mais...</p>
            </div>
        </div>
    </div>

    <!-- Input de arquivo escondido -->
    <input type="file" id="fileInput" accept="image/*" style="display: none;" onchange="atualizarPerfilEPostagens(this)" />

    <script src="scripts.js"></script>
</body>
</html>
