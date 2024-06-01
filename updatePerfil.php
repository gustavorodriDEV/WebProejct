<?php
require_once 'autenticacao.php';
autenticacao::checkLogin();
$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: perfil.php');
    exit();
}

$diretorio = "imgUsuario/";
if (!file_exists($diretorio)) {
    mkdir($diretorio, 0755, true);
}

function uploadFoto($conn, $diretorio) {
    $caminhoArquivo = $diretorio . basename($_FILES['image']['name']);
    if ($_FILES['image']['error'] == 0) {
        $fileType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $caminhoArquivo)) {
                $nomeUsuario = $_SESSION['username'];
                $stmt = $conn->prepare("UPDATE perfilusuario SET fotoPerfil = ? WHERE nomeUsuario = ?");
                $stmt->bind_param("ss", $caminhoArquivo, $nomeUsuario);
                $stmt->execute();
                $stmt->close();
                header("Refresh:0");
            } else {
                echo "Erro ao mover o arquivo.";
            }
        } else {
            echo "Tipo de arquivo não permitido.";
        }
    } else {
        echo "Erro no upload: " . $_FILES['image']['error'];
    }
}

function removeFoto($conn) {
    $nomeUsuario = $_SESSION['username'];
    // Buscar o caminho da foto de perfil atual
    $stmt = $conn->prepare("SELECT fotoPerfil FROM perfilusuario WHERE nomeUsuario = ?");
    $stmt->bind_param("s", $nomeUsuario);
    $stmt->execute();
    $stmt->bind_result($fotoPerfil);
    $stmt->fetch();
    $stmt->close();

    // Verificar se existe uma foto e tentar removê-la do diretório
    if ($fotoPerfil && file_exists($fotoPerfil)) {
        unlink($fotoPerfil); // Remove a foto do diretório
    }

    // Atualizar o banco de dados para remover o caminho da foto de perfil
    $stmt = $conn->prepare("UPDATE perfilusuario SET fotoPerfil = NULL WHERE nomeUsuario = ?");
    $stmt->bind_param("s", $nomeUsuario);
    $stmt->execute();
    $stmt->close();

    echo "Foto de perfil removida com sucesso!";
    header("Location: perfil.php"); // Redireciona de volta à página de perfil após a remoção
    exit();
}



function updateBio($conn) {
    $nomeUsuario = $_SESSION['username'];
    $biografia = empty($_POST['bio']) ? "A" : $_POST['bio'];
    $stmt = $conn->prepare("SELECT biografia FROM perfilusuario WHERE nomeUsuario = ?");
    $stmt->bind_param("s", $nomeUsuario);
    $stmt->execute();
    $stmt->bind_result($biografiaAtual);
    $stmt->fetch();
    $stmt->close();

    if ($biografia === $biografiaAtual) {
        echo "A nova biografia é igual à biografia atual. Nenhuma atualização foi feita.";
        $conn->close();
        exit();
    }

    $stmt = $conn->prepare("UPDATE perfilusuario SET biografia = ? WHERE nomeUsuario = ?");
    $stmt->bind_param("ss", $biografia, $nomeUsuario);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Biografia atualizada com sucesso!";
    } else {
        echo "Erro ou nenhum dado modificado.";
    }

    $stmt->close();
    header("Location: perfil.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['action']) {
        case 'uploadFoto':
            uploadFoto($conn, $diretorio);
            break;
        case 'updateBio':
            updateBio($conn);
            break;
        case 'removeFoto':
            removeFoto($conn);
            break;
        default:
            echo "Ação inválida!";
            break;
    }
    $conn->close();
} else {
    echo "Método de requisição inválido.";
    $conn->close();
    exit();
}

?>
