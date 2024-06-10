<?php

session_start();
require 'conexao.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login2.php'); // Redireciona para a página de login
    exit;
}

if (isset($_POST['PostID']) && is_numeric($_POST['PostID'])) {
    $post_id = intval($_POST['PostID']);

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare('DELETE FROM avaliacoes WHERE PostID = ?');
        if ($stmt) {
            $stmt->bind_param('i', $post_id);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Erro ao preparar a declaração para deletar avaliações: " . $conn->error);
        }

        $stmt = $conn->prepare('DELETE FROM posts WHERE PostID = ?');
        if ($stmt) {
            $stmt->bind_param('i', $post_id);
            $stmt->execute();
    
            if ($stmt->affected_rows > 0) {
                echo "Postagem deletada com sucesso.";
                $conn->commit();  
            } else {
                throw new Exception("Não foi possível deletar a postagem.");
            }

            $stmt->close();
        } else {
            throw new Exception("Erro ao preparar a declaração: " . $conn->error);
        }
    } catch (Exception $e) {
        $conn->rollback();  
        echo "Erro: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "ID da postagem não fornecido ou inválido.";
}

$redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("Location: " . $redirectUrl);
exit();
?>
