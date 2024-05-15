<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes do Filme</title>
        <style>
            /* Reset básico para padding e margin */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Arial', sans-serif; /* Garantindo uma fonte consistente */
                background-color: #ece5dd; /* Cor de fundo suave */
                color: #333; /* Cor de texto padrão */
            }

            /* Botão de voltar com melhor visibilidade e estilo moderno */
            .back-button {
                position: absolute;
                top: 20px;
                left: 20px;
                padding: 10px 15px;
                border: none;
                background-color: #f7f7f7;
                border-radius: 50%;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }

            .back-button:hover {
                background-color: #e1e1e1; /* Mudança sutil de cor ao passar o mouse */
            }

            /* Estilização do conteúdo principal com melhor alinhamento e responsividade */
            .content {
                width: 90%;
                max-width: 1024px;
                margin: 30px auto;
                background-color: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column; /* Melhor controle em telas menores */
                gap: 20px; /* Espaço entre os elementos */
            }

            @media (min-width: 768px) {
                .content {
                    flex-direction: row; /* Layout em linha em telas maiores */
                }
            }

            /* Estilização específica para imagem e informações do filme */
            .movie-image {
                flex: 1 1 40%; /* Flexibilidade na largura da imagem */
                max-width: 300px; /* Largura máxima para a imagem */
                background-color: #000; /* Fundo preto para destacar a imagem */
                overflow: hidden; /* Esconde qualquer parte que ultrapasse os limites */
            }

            .movie-image img {
                width: 100%; /* Ajusta a imagem para preencher o espaço disponível */
                height: auto; /* Mantém a proporção da imagem */
            }

            .movie-info {
                flex: 1 2 60%; /* Maior flexibilidade no conteúdo do texto */
                font-size: 16px; /* Tamanho de fonte adequado para leitura */
                display: flex;
                flex-direction: column; /* Coloca os itens em coluna */
                gap: 10px; /* Espaçamento entre elementos de informação */
            }

            /* Estilização de cabeçalhos e textos dentro das informações do filme */
            .movie-info strong {
                color: #555; /* Cor de destaque para títulos */
            }

            .movie-text p {
                text-align: justify; /* Justifica o texto para melhor fluidez */
                line-height: 1.6; /* Espaçamento de linha para leitura confortável */
            }

        </style>
    </head>
    <body>
        <!-- Este código estará na página onde estão listadas as postagens -->
        <?php
        $conn = new mysqli('localhost', 'root', '', 'webPro');
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

   $sql = "SELECT PostID, Titulo, Diretor, Categoria, DataDeLancamento, Descricao FROM posts ORDER BY DataDeLancamento DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="content">';
        echo '    <div class="movie-image"><img src="path/to/image/' . htmlspecialchars($row['Imagem']) . '" alt="Imagem do filme"></div>';
        echo '    <div class="movie-details">';
        echo '        <div class="movie-info">';
        echo '            <strong>Título do filme:</strong> ' . htmlspecialchars($row['Titulo']);
        echo '            <br><strong>Diretor:</strong> ' . htmlspecialchars($row['Diretor']);
        echo '            <br><strong>Categoria:</strong> ' . htmlspecialchars($row['Categoria']);
        echo '            <br><strong>Ano:</strong> ' . date('Y', strtotime($row['DataDeLancamento']));
        echo '        </div>';
        echo '        <div class="movie-description">' . nl2br(htmlspecialchars($row['Descricao'])) . '</div>';
        echo '        <!-- Formulário para redirecionar para a página de comentários com todos os dados da postagem -->';
        echo '        <form action="comments_page.php" method="POST">';
        echo '            <input type="hidden" name="PostID" value="' . htmlspecialchars($row['PostID']) . '">';
        echo '            <input type="hidden" name="Titulo" value="' . htmlspecialchars($row['Titulo']) . '">';
        echo '            <input type="hidden" name="Diretor" value="' . htmlspecialchars($row['Diretor']) . '">';
        echo '            <input type="hidden" name="Categoria" value="' . htmlspecialchars($row['Categoria']) . '">';
        echo '            <input type="hidden" name="DataDeLancamento" value="' . htmlspecialchars($row['DataDeLancamento']) . '">';
        echo '            <input type="hidden" name="Descricao" value="' . htmlspecialchars($row['Descricao']) . '">';
        echo '            <input type="hidden" name="Imagem" value="' . htmlspecialchars($row['Imagem']) . '">';
        echo '            <input type="hidden" name="AvaliacaoMedia" value="' . htmlspecialchars($row['AvaliacaoMedia']) . '">';
        echo '            <button type="submit" class="comment-button">Ver Comentários</button>';
        echo '        </form>';
        echo '    </div>';
        echo '</div>';
    }
} else {
    echo "<p>Nenhuma mídia cadastrada.</p>";
}
$conn->close();
?>
    }
    $conn->close();
    ?>
</body>
</html>