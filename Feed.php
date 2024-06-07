<?php
require_once 'autenticacao.php';
require_once 'FeedClass.php'; // Assegure-se de que este arquivo contém a definição da classe MovieDetails

autenticacao::checkLogin();
if (!defined('USERNAME')) {
    define('USERNAME', autenticacao::getUsername());
}

        $conn = new mysqli('localhost', 'root', '', 'webPro');
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Chamando a função para exibir detalhes do filme
        MovieDetails::display($conn);
        $conn->close();
        ?>
  
