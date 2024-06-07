<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'webPro';

// Estabelecendo a conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4"); // Assegurando que a conexão está usando utf8mb4

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
    
    // Processando o upload da imagem
    $diretorio = "img/"; // Verifique se esta pasta existe no seu servidor
    $caminhoArquivo = $diretorio . basename($_FILES["imagePath"]["name"]);

    // Certifique-se de que o diretório de destino exista
    if (!file_exists($diretorio)) {
        mkdir($diretorio, 0777, true);
    }

    if (move_uploaded_file($_FILES["imagePath"]["tmp_name"], $caminhoArquivo)) {
        echo "O arquivo " . htmlspecialchars(basename($_FILES["imagePath"]["name"])) . " foi carregado.";
    } else {
        echo "Erro ao carregar o arquivo.";
    }

    // Preparando a consulta SQL para inserção usando binding em 'posts'
    $sqlPosts = "INSERT INTO posts (username, Titulo, Categoria, Diretor, DataDeLancamento, Descricao, Caminho_Imagem) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtPosts = $conn->prepare($sqlPosts);
    if ($stmtPosts === false) {
        die("Erro na preparação: " . $conn->error);
    }

    // Vinculando parâmetros e executando a inserção em 'posts'
    $stmtPosts->bind_param("ssssiss", $User, $titulo, $categoria, $diretor, $anoDeLancamento, $descricao, $caminhoArquivo);
    if ($stmtPosts->execute()) {
        // Obtendo o ID do post inserido
        $postID = $conn->insert_id;

        // Preparando a consulta SQL para inserção em 'avaliacoes'
        $sqlAvaliacoes = "INSERT INTO avaliacoes (PostID, pontuacao) VALUES (?, ?)";
        $stmtAvaliacoes = $conn->prepare($sqlAvaliacoes);
        if ($stmtAvaliacoes === false) {
            die("Erro na preparação: " . $conn->error);
        }

        // Vinculando parâmetros para a inserção em 'avaliacoes'
        $stmtAvaliacoes->bind_param("ii", $postID, $avaliacao);
        if ($stmtAvaliacoes->execute()) {
            echo "Post e avaliação inseridos com sucesso.";
            // header("Location: feed.php"); // Redireciona para feed.php se necessário
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
