<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();

include 'navBar.php';
echo $GLOBALS['navbar']; // Aqui, use 'navbar' ao invés de 'navBar'
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
            <div class="user-name">Nome do Usuário</div>
        </div>

        <div class="info-container">
            <div class="line-separator"></div>
            <div class="name-description">
                <p id="userNameDisplay" class="placeholder-text">Nome</p>
                <p id="userDescriptionDisplay" class="placeholder-text">Descrição...</p>
                <button id="openModalButton">Alterar</button>
            </div>
        </div>
        <div class="line-separator2"></div>
        <div class="postsLike">
            <button class="buttonlike">Curtir</button>
            <button class="buttonposts">Postagens</button>
        </div>

        <div id="infoModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <label for="modalName">Nome:</label>
                <input type="text" id="modalName" name="modalName"><br>
                <label for="modalDescription">Descrição:</label>
                <textarea id="modalDescription" name="modalDescription"></textarea><br>
                <button id="submitInfo">Adicionar</button>
            </div>
        </div>

        <script src="scripts.js"></script>
    </body>
</html>
