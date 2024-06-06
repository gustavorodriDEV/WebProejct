<?php
// Inicia a sessão
session_start();

// Conexão com o banco de dados
$host = 'localhost';  // ou o IP do servidor de banco de dados
$username = 'root';
$password = ''; // Senha (vazia se local e sem senha)
$database = 'webPro'; // Nome do banco de dados

// Cria a conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $database);

// Verifica se a conexão falhou
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Resto do código de delete_post.php...

// Verifica se o ID da postagem foi enviado
if (isset($_POST['PostID'])) {
    $post_id = $_POST['PostID'];

    // Prepare uma declaração para deletar
    if ($stmt = $conn->prepare('DELETE FROM posts WHERE PostID = ?')) {
        $stmt->bind_param('i', $post_id);
        
        // Executa a declaração
        $stmt->execute();

        // Verifica se a postagem foi deletada
        if ($stmt->affected_rows > 0) {
            echo "Postagem deletada com sucesso.";
        } else {
            echo "Não foi possível deletar a postagem.";
        }

        // Fecha a declaração
        $stmt->close();
    } else {
        echo "Erro ao preparar a declaração: " . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
} else {
    echo "ID da postagem não fornecido.";
}

// Redireciona de volta para a página de onde veio
header("Location: " . $_SERVER['HTTP_REFERER']);
?>
