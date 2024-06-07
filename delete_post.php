<?php
// Inicia a sessão para acessar variáveis de sessão
session_start();

// Verificar se o usuário está autenticado antes de permitir a exclusão
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redireciona para a página de login
    exit;
}

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

// Verifica se o ID da postagem foi enviado e é válido
if (isset($_POST['PostID']) && is_numeric($_POST['PostID'])) {
    $post_id = intval($_POST['PostID']);

    // Inicia uma transação para garantir que todas as operações sejam concluídas com sucesso
    $conn->begin_transaction();

    try {
        // Primeiro, tenta excluir registros dependentes (avaliações)
        $stmt = $conn->prepare('DELETE FROM avaliacoes WHERE PostID = ?');
        if ($stmt) {
            $stmt->bind_param('i', $post_id);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Erro ao preparar a declaração para deletar avaliações: " . $conn->error);
        }

        // Prepare uma declaração para deletar a postagem
        $stmt = $conn->prepare('DELETE FROM posts WHERE PostID = ?');
        if ($stmt) {
            $stmt->bind_param('i', $post_id);
            $stmt->execute();

            // Verifica se a postagem foi deletada
            if ($stmt->affected_rows > 0) {
                echo "Postagem deletada com sucesso.";
                $conn->commit();  // Confirma as transações
            } else {
                throw new Exception("Não foi possível deletar a postagem.");
            }

            $stmt->close();
        } else {
            throw new Exception("Erro ao preparar a declaração: " . $conn->error);
        }
    } catch (Exception $e) {
        $conn->rollback();  // Reverte todas as operações se ocorreu algum erro
        echo "Erro: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "ID da postagem não fornecido ou inválido.";
}

// Redireciona de volta para a página de onde veio, ou uma padrão se HTTP_REFERER não estiver disponível
$redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $redirectUrl);
exit();
?>
