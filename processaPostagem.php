<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'webPro';

// Estabelecendo a conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $database);

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $categoria = filter_input(INPUT_POST, 'categories', FILTER_SANITIZE_STRING);
    $diretor = filter_input(INPUT_POST, 'director', FILTER_SANITIZE_STRING);
    $anoDeLancamento = filter_input(INPUT_POST, 'releaseYear', FILTER_SANITIZE_NUMBER_INT);
    $descricao = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    $sql = "INSERT INTO posts (Titulo, Categoria, Diretor, DataDeLancamento, Descricao) 
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação: " . $conn->error);
    }

    $stmt->bind_param("sssis", $titulo, $categoria, $diretor, $anoDeLancamento, $descricao);
    if ($stmt->execute()) {
        echo "Nova postsagem criada com sucesso!";
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método de requisição inválido.";
}

$conn->close();
?>
