<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();

include 'navBar.php';
echo $GLOBALS['navbar'];
require_once 'FeedClass.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Fórum de Discussão</title>
        <link rel="stylesheet" href="estilos.css"> 
        <link rel="stylesheet" href="FeedStyleSheet.css">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    </head>
    <body>
        <?php
        require 'conexao.php';
        MovieDetails::display($conn);
        $conn->close();
        ?>
        <script src="scripts.js"></script>
    </body>
</html>
