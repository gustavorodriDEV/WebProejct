<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $navbar = '
<div class="navbar">
    <a href="index.php" class="nav-item"><i class="fas fa-home"></i> Início</a>
    <a href="perfil.php" class="nav-item"><i class="fas fa-user"></i> Perfil</a>';
    $navbar .= '
    <a href="novaPostagem.php" class="nav-item"><i class="fas fa-pencil-alt"></i> Postagem</a>';

    // Adicionar o link Editar Perfil somente se não estiver na página perfilComment.php
    if ($currentPage === 'perfil.php') {
        $navbar .= '<a href="#" class="nav-item" onclick="document.getElementById(\'infoModal\').style.display=\'block\'"><i class="fas fa-edit"></i> Editar Perfil</a>';
    }

    $navbar .= '<a href="Sair.php" class="nav-item"><i class="fas fa-sign-out-alt"></i> Sair</a>';
    $navbar .= '</div>';
}
// Torne a variável $navbar global
$GLOBALS['navbar'] = $navbar;
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes do Filme</title>
        <link rel="stylesheet" href="estilos.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">

        <style>
            /* styles.css */
            .navbar {
                width: 100%;
                background: linear-gradient(to right, #6e45e2, #88d3ce);
                padding: 10px 0;
                display: flex;
                justify-content: space-evenly;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                position: fixed;
                top: 0;
                z-index: 1000;
                font-size: 25px;
            }

            .nav-item i {
                margin-right: 8px;
            }

            .nav-item {
                color: white;
                text-decoration: none;
                font-size: 16px;
                padding: 8px 16px;
                border-radius: 20px;
                transition: background-color 0.3s;
            }

            .nav-item:hover {
                background-color: rgba(255, 255, 255, 0.2);
            }

            @media (max-width: 700px) {
                .navbar {
                    font-size: 18px;
                }

                .nav-item {
                    font-size: 10px;
                    padding: 10px 20px;
                    width: 100%;
                    box-sizing: border-box;
                }
            }

            @media (max-width: 480px) {
                .navbar {
                    font-size: 16px;
                }

                .nav-item {
                    font-size: 12px;
                }
            }
        </style>
    </head>
    <body>
    </body>
</html>