<?php
session_start();
require_once 'autenticacao.php';
autenticacao::checkLogin();
$nomeUsuario = autenticacao::getUsername();
include 'navBar.php';
echo $GLOBALS['navbar'];
// Iniciando conexão
$conn = new mysqli('localhost', 'root', '', 'webPro');
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$postID = intval($_POST['PostID'] ?? 0);

// Consulta SQL para obter os detalhes do post
$sqlPost = "SELECT p.PostID, p.username, p.Titulo, p.Diretor, p.Categoria, p.DataDeLancamento, p.Descricao, p.Caminho_Imagem, u.FotoPerfil
            FROM posts p
            LEFT JOIN Perfilusuario u ON p.username = u.nomeUsuario
            WHERE p.PostID = ?
            ORDER BY p.DataDeLancamento DESC";

$stmt = $conn->prepare($sqlPost);
$commentsHTML = '';
if ($stmt) {
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Usando htmlspecialchars para evitar XSS
        $titulo = htmlspecialchars($row['Titulo']);
        $diretor = htmlspecialchars($row['Diretor']);
        $categoria = htmlspecialchars($row['Categoria']);
        $dataDeLancamento = htmlspecialchars($row['DataDeLancamento']);
        $descricao = nl2br(htmlspecialchars($row['Descricao']));
        $caminhoImagem = htmlspecialchars($row['Caminho_Imagem']);
        $fotoPerfil = htmlspecialchars($row['FotoPerfil']);
        $pontuacao = isset($row['Pontuacao']) ? intval($row['Pontuacao']) : null;
    } else {
        echo "<p>Post não encontrado.</p>";
    }
    $stmt->close();
} else {
    echo "Erro na preparação da consulta: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Detalhes do Filme</title>
        <link rel="stylesheet" href="modal.css">
        <link rel="stylesheet" href="comment_StyleSheet.css">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="content">
            <div class="user-info">
                <?php if (empty($fotoPerfil)): ?>
                    <i class="fas fa-user-circle avatar-icon default-avatar" style="font-size: 55px; color: #777;"></i>
                <?php else: ?>
                    <img src="<?= $fotoPerfil ?>" alt="Perfil do usuário" class="profile-image">
                <?php endif; ?>
                <span class="user-name"><?= htmlspecialchars($row['username']) ?></span>
            </div>

            <?php if (isset($row)): ?>
                <div class="movie-details">
                    <div class="movie-img"><img src="<?= $caminhoImagem ?>" alt="Imagem do filme"></div>
                    <div class="inform">
                        <div class="info-item"><strong>Título do filme:</strong> <span><?= $titulo ?></span></div><br>
                        <div class="info-item"><strong>Diretor:</strong> <span><?= $diretor ?></span></div><br>
                        <div class="info-item"><strong>Categoria:</strong> <span><?= $categoria ?></span></div><br>
                        <div class="info-item"><strong>Ano:</strong> <span><?= $dataDeLancamento ?></span></div><br>
                        <div class="info-item"><strong>Avaliação:</strong> 
                            <span>
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="fas fa-star" style="color: <?= ($i < $pontuacao ? 'gold' : 'lightgray') ?>; margin-right: 2px;"></i>
                                <?php endfor; ?>
                                <?= $pontuacao ?> / 5
                            </span>
                        </div><br>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <div class="movie-description">
            <?= $descricao_formatada = wordwrap($descricao, 70, "<br />\n", true);
 ?>
        </div>


        <div>
            <?php
// Consulta para buscar comentários
            $sqlComentarios = "SELECT Conteudo, Username, DataDeComentario FROM comentarios WHERE PostID = $postID ORDER BY DataDeComentario DESC";
            $resultComentarios = $conn->query($sqlComentarios);

            if ($resultComentarios && $resultComentarios->num_rows > 0) {
                echo '<div class="comments-section">';
                echo '<h3>Comentários</h3>';

                while ($row = $resultComentarios->fetch_assoc()) {
                    $conteudo = nl2br(htmlspecialchars($row['Conteudo']));
                    $username = htmlspecialchars($row['Username']);
                    $data = htmlspecialchars($row['DataDeComentario']);
                    $dataFormatada = date('d/m/Y H:i:s', strtotime($data));

                    echo "<div class='comment'>";
                    echo "<form action='PerfilComment.php' method='post'>";
                    echo "<strong>";
                    echo "<button type='submit' style='border: none; background: none; padding: 0; font: inherit; cursor: pointer; color: blue; text-decoration: underline;'>";
                    echo $username;
                    echo "</button>";
                    echo "</strong>";
                    echo "<input type='hidden' name='username' value='" . $username . "'>";
                    echo "<span>" . $dataFormatada . "</span>";
                    echo "</form><br>";
                    echo "<div class='comment-text'>" . $conteudo . "</div>";
                    echo "</div>";
                }

                echo '</div>';
            } else {
                echo '<p>Sem comentários.</p>';
            }
            ?>

            <?php
            echo $commentsHTML;

            $conn->close();
            ?>
        </div>

        <div class="add-comment">
            <h3>Faça um Comentário</h3>
            <form action="inserecomentario.php" method="POST">
                <!-- Campo oculto para PostID -->
                <input type="hidden" name="PostID" value="<?php echo isset($postID) ? htmlspecialchars($postID) : ''; ?>">
                <input type="hidden" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                <textarea id="conteudo" name="conteudo" required></textarea>
                <input type="submit" value="Enviar">
            </form>
        </div>

        <div id="userProfileModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close" onclick="closeUserProfileModal()">&times;</span>
                <?php if ($perfilUsuario): ?>
                    <div class="avatar">
                    </div>
                    <div id="modalUsername"><?php echo htmlspecialchars($perfilUsuario['nomeUsuario']); ?></div>
                    <p id="modalBio"><?php echo htmlspecialchars($perfilUsuario['biografia']); ?></p>
                    <p>Entrou em: <span id="modalJoinedDate"><?php echo (new DateTime($perfilUsuario['dataDeCriacao']))->format('d/m/Y'); ?></span></p>
                    <p>Conta criada há: <span id="modalAccountAge"><?php echo $mensagemDiasConta; ?></span></p>
                <?php else: ?>
                    <p>Usuário não encontrado.</p>
                <?php endif; ?>
                <script src="modal.js"></script>
            </div>

    </body>

</html>
