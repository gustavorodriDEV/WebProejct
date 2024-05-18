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

        // Redirecionar para feed.php após a inserção do comentário
        header('Location: feed.php');
        exit;
    } else {
        echo "Erro ao preparar a inserção: " . $conn->error;
    }
}

// Buscar e exibir comentários
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['PostID'])) {
    $postID = intval($_GET['PostID']); // Default PostID é 1, se não fornecido
    $sql = "SELECT Conteudo FROM comentarios WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $postID);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . htmlspecialchars($row['Conteudo']) . "</p>";
        }
        $stmt->close();

        // Redirecionar para feed.php após exibir os comentários
        header('Location: feed.php');
        exit;
    } else {
        echo "Erro ao buscar comentários: " . $conn->error;
    }
}

$conn->close();

?>
