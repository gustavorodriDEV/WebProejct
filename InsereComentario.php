<?php
// Conectar ao banco de dados
$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die('Conexão falhou: ' . $conn->connect_error);
}

// Verifica se os dados foram enviados pelo método POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['conteudo']) && isset($_POST['PostID'])) {
    // Capturar e sanitizar os dados enviados via POST
    $postID = intval($_POST['PostID']);
    $conteudo = $conn->real_escape_string(htmlspecialchars($_POST['conteudo']));

    // SQL para inserir o novo comentário
    $sql = "INSERT INTO comentarios (PostID, Conteudo) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("is", $postID, $conteudo);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Erro ao preparar a inserção: " . $conn->error;
    }
}

// Fechar a conexão com o banco de dados
$conn->close();

// Redirecionar para a página anterior
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>
