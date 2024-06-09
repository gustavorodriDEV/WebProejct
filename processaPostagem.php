<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'webPro';


$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4");

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
    $avaliacao = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);  // Garanta que a avaliação seja um inteiro
    $User = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    
    $diretorio = "img/"; 
    $caminhoArquivo = $diretorio . basename($_FILES["imagePath"]["name"]);

    if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    if (move_uploaded_file($_FILES["imagePath"]["tmp_name"], $caminhoArquivo)) {
        echo "O arquivo " . htmlspecialchars(basename($_FILES["imagePath"]["name"])) . " foi carregado.";
    } else {
        echo "Erro ao carregar o arquivo.";
    }

    $sqlPosts = "INSERT INTO posts (username, Titulo, Categoria, Diretor, DataDeLancamento, Descricao, Caminho_Imagem) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtPosts = $conn->prepare($sqlPosts);
    if ($stmtPosts === false) {
        die("Erro na preparação: " . $conn->error);
    }

    $stmtPosts->bind_param("ssssiss", $User, $titulo, $categoria, $diretor, $anoDeLancamento, $descricao, $caminhoArquivo);
    if ($stmtPosts->execute()) {
        // Obtendo o ID do post inserido
        $postID = $conn->insert_id;

        $sqlAvaliacoes = "INSERT INTO avaliacoes (PostID, pontuacao) VALUES (?, ?)";
        $stmtAvaliacoes = $conn->prepare($sqlAvaliacoes);
        if ($stmtAvaliacoes === false) {
            die("Erro na preparação: " . $conn->error);
        }

        $stmtAvaliacoes->bind_param("ii", $postID, $avaliacao);
        if ($stmtAvaliacoes->execute()) {
            echo "Post e avaliação inseridos com sucesso.";
            header("Location: index.php"); 
        } else {
            echo "Erro ao inserir dados em avaliações: " . $stmtAvaliacoes->error;
        }

        $stmtAvaliacoes->close();
    } else {
        echo "Erro ao inserir dados em posts: " . $stmtPosts->error;
    }

    $stmtPosts->close();
} else {
    echo "Método de requisição inválido.";
}

$conn->close();
?>
