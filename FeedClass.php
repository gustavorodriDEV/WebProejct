<?php

class MovieDetails {

    public static function display($conn) {
        $sql = "SELECT p.PostID, p.username, p.Titulo, p.Diretor, p.Categoria, p.DataDeLancamento, p.Descricao, p.Caminho_Imagem, u.adm, u.FotoPerfil, a.Pontuacao, a.DataDaAvaliacao 
                FROM posts p
                LEFT JOIN Perfilusuario u ON p.username = u.nomeUsuario
                LEFT JOIN Avaliacoes a ON p.PostID = a.PostID
                ORDER BY p.DataDeLancamento DESC";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $nomeUsuario = autenticacao::getUsername();
            while ($row = $result->fetch_assoc()) {
                echo '<div class="content" style="position: relative;">';
                echo '  <div class="movie-details">';
                echo '    <div class="movie-info">';
                echo '      <div class="user-info">';
                if (empty($row['FotoPerfil'])) {
                    echo '        <i class="fas fa-user-circle avatar-icon default-avatar" style="font-size: 40px; color: #777;"></i>';
                } else {
                    echo '        <img src="' . htmlspecialchars($row['FotoPerfil']) . '" alt="Perfil do usuário" class="profile-image">';
                }
                echo '        <span class="user-name">' . htmlspecialchars($row['username']) . '</span>';
                echo '      </div>'; // Close user-info
                echo '    </div>'; // Close movie-info
                echo '    <div class="movie-image">';
                echo '      <img src="' . htmlspecialchars($row['Caminho_Imagem']) . '" alt="Imagem do filme" style="width: 100%; max-width: 400px;">';
                echo '    </div>'; // Close movie-image
                echo '    <div class="inform">';
                echo '      <div class="info-item"><strong>Título do filme:</strong> <span>' . htmlspecialchars($row['Titulo']) . '</span></div><br>';
                echo '      <div class="info-item"><strong>Diretor:</strong> <span>' . htmlspecialchars($row['Diretor']) . '</span></div><br>';
                echo '      <div class="info-item"><strong>Categoria:</strong> <span>' . htmlspecialchars($row['Categoria']) . '</span></div><br>';
                echo '      <div class="info-item"><strong>Ano:</strong> <span>' . date('Y', strtotime($row['DataDeLancamento'])) . '</span></div><br>';
                echo '      <div class="info-item"><strong>Avaliação:</strong> <span>';
                if ($row['Pontuacao'] !== null) {
                    for ($i = 0; $i < 5; $i++) {
                        echo '<i class="fas fa-star" style="color: ' . ($i < $row['Pontuacao'] ? 'gold' : 'lightgray') . '; margin-right: 2px;"></i>';
                    }
                    echo ' ' . htmlspecialchars($row['Pontuacao']) . ' / 5';
                } else {
                    echo 'Não avaliado';
                }
                echo '</span></div><br>';
                echo '      <div class="movie-description">' . nl2br(htmlspecialchars($row['Descricao'])) . '</div>';
                echo '    </div>'; // Close inform
                echo '  </div>'; // Close movie-details
                echo '  <form action="comments_page.php" method="POST">';
                echo '    <input type="hidden" name="PostID" value="' . htmlspecialchars($row['PostID']) . '">';
                echo '    <input type="hidden" name="Titulo" value="' . htmlspecialchars($row['Titulo']) . '">';
                echo '    <input type="hidden" name="Diretor" value="' . htmlspecialchars($row['Diretor']) . '">';
                echo '    <input type="hidden" name="Categoria" value="' . htmlspecialchars($row['Categoria']) . '">';
                echo '    <input type="hidden" name="DataDeLancamento" value="' . htmlspecialchars($row['DataDeLancamento']) . '">';
                echo '    <input type="hidden" name="Descricao" value="' . htmlspecialchars($row['Descricao']) . '">';
                echo '    <button type="submit" class="comment-button">Ver Comentários</button>';
                echo '  </form>';

                if ($row['username'] == $nomeUsuario || $row['adm'] == TRUE) {
                    echo '  <form action="delete_post.php" method="POST" style="position: absolute; top: 10px; right: 10px;">';
                    echo '    <input type="hidden" name="PostID" value="' . htmlspecialchars($row['PostID']) . '">';
                    echo '    <button type="submit" class="delete-button">Deletar</button>';
                    echo '  </form>';
                }
                echo '</div>'; // Close content
            }
        } else {
            echo "<p>Nenhuma mídia cadastrada.</p>";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes do Filme</title>
        <link rel="stylesheet" href="FeedStyleSheet.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    </head>
    <body>
     
        <?php
        $conn = new mysqli('localhost', 'root', '', 'webPro');
        if ($conn->connect_error) {
            die("Conexão falhou: " . $conn->connect_error);
        }

        // Chamando a função para exibir detalhes do filme
        MovieDetails::display($conn);
        $conn->close();
        ?>
    </body>
</html>
