<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();

include 'navBar.php';
echo $GLOBALS['navbar'];
require_once 'FeedClass.php'; // Assegure-se de que este arquivo contém a definição da classe MovieDetails
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

        <!-- Input de arquivo escondido -->
        <?php
        $conn = new mysqli('localhost', 'root', '', 'webPro');
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Chamando a função para exibir detalhes do filme
        MovieDetails::display($conn);
        $conn->close();
        ?>
        <script src="scripts.js"></script>
    </body>
</html>
