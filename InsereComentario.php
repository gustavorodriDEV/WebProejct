<?php

// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die('Conexão falhou: ' . $conn->connect_error);
}

// Verifica se os dados foram enviados pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['conteudo'], $_POST['PostID'], $_POST['username'])) {
    // Capturar e sanitizar os dados enviados via POST
    $postID = intval($_POST['PostID']);
    $username = htmlspecialchars($_POST['username']);
    $conteudo = $conn->real_escape_string(htmlspecialchars($_POST['conteudo']));
    date_default_timezone_set('America/Sao_Paulo');

    // Cria um objeto DateTime para a data e hora atual
    $DataDeComentario = new DateTime();
    $dataHora = $DataDeComentario->format('Y-m-d H:i:s'); // Formata a data e hora para uma string compatível com SQL

    // SQL para inserir o novo comentário junto com a data e hora
    $sql = "INSERT INTO comentarios (PostID, Username, Conteudo, DataDeComentario) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("isss", $postID, $username, $conteudo, $dataHora);
        $stmt->execute();
        $stmt->close();

        // Redirecionar para feed.php após a inserção do comentário
        header('Location: Feed.php');
        exit;
    } else {
        echo "Erro ao preparar a inserção: " . $conn->error;
    }
}

$conn->close();
?>
