<?php
session_start(); 
require_once 'autenticacao.php';
require 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['conteudo'], $_POST['PostID'], $_POST['username'])) {
    // Capturar e sanitizar os dados enviados via POST
    $postID = intval($_POST['PostID']);
    $_SESSION['last_post_id'] = $postID; // Salva o PostID na sessão

    $username = autenticacao::getUsername();
    $conteudo = $conn->real_escape_string(htmlspecialchars($_POST['conteudo']));
    date_default_timezone_set('America/Sao_Paulo');

    // Cria um objeto DateTime para a data e hora atual
    $DataDeComentario = new DateTime();
    $dataHora = $DataDeComentario->format('Y-m-d H:i:s'); // Formata a data e hora para uma string compatível com SQL

    $sql = "INSERT INTO comentarios (PostID, Username, Conteudo, DataDeComentario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isss", $postID, $username, $conteudo, $dataHora);
        $stmt->execute();
        $stmt->close();

        echo "<form id='redirectForm' action='comments_page.php' method='POST' style='display:none;'>
                <input type='hidden' name='PostID' value='$postID'>
              </form>
              <script type='text/javascript'>
                document.getElementById('redirectForm').submit();
              </script>";
        exit;
    } else {
        echo "Erro ao preparar a inserção: " . $conn->error;
    }
}

$conn->close();
?>
