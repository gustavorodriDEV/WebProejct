<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'webPro';

// Estabelecendo a conexão com o banco de dados
$conn = new mysqli($host, $username, $password, $database);
$conn->set_charset("utf8mb4"); // Certifique-se de que a codificação é utf8mb4

// Checar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Dados de teste
$username = 'usuario_teste';
$titulo = 'Teste de Título';
$categoria = 'Categoria Teste';
$diretor = 'Diretor Teste';
$anoDeLancamento = 2024;
$descricao = 'Descrição de teste estática para verificar a inserção no banco.';
$caminhoArquivo = 'caminho/para/imagem_teste.jpg';

// Preparando a consulta SQL para inserção
$sql = "INSERT INTO posts (username, Titulo, Categoria, Diretor, DataDeLancamento, Descricao, Caminho_Imagem) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Erro na preparação: " . $conn->error);
}

// Vinculando parâmetros e executando
$stmt->bind_param("ssssiis", $username, $titulo, $categoria, $diretor, $anoDeLancamento, $descricao, $caminhoArquivo);
if ($stmt->execute()) {
    echo "Nova postagem criada com sucesso!";
} else {
    echo "Erro ao inserir dados: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
