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

    // Preparando a consulta SQL para inserção
    $sql = "INSERT INTO posts (username, Titulo, Categoria, Diretor, DataDeLancamento, Descricao, Caminho_Imagem) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erro na preparação: " . $conn->error);
    }

    // Vinculando parâmetros e executando
    $stmt->bind_param("ssssiis",$User, $titulo, $categoria, $diretor, $anoDeLancamento, $descricao, $caminhoArquivo);
    if ($stmt->execute()) {
        echo "Nova postagem criada com sucesso!";
    } else {
        echo "Erro ao inserir dados: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Método de requisição inválido.";
}

$conn->close();
?>
