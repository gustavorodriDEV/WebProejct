<?php
session_start();
require_once 'autenticacao.php';
autenticacao::checkLogin();
require 'conexao.php';
$nomeUsuario = autenticacao::getUsername();
include 'navBar.php';
echo $GLOBALS['navbar'];

$postID = intval($_POST['PostID'] ?? 0);

$sqlPost = "SELECT p.PostID, p.username, p.Titulo, p.Diretor, p.Categoria, p.DataDeLancamento, p.Descricao, p.Caminho_Imagem, u.FotoPerfil, a.Pontuacao
                FROM posts p
                LEFT JOIN Perfilusuario u ON p.username = u.nomeUsuario
                LEFT JOIN Avaliacoes a ON p.PostID = a.PostID
                WHERE p.PostID = ?
                ORDER BY a.DataDaAvaliacao DESC
                LIMIT 1;
                ";

$stmt = $conn->prepare($sqlPost);
$commentsHTML = '';
if ($stmt) {
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

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
                <?php if (empty($fotoPerfil)): echo $pontuacao ?>
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
                                <?php
                                if ($pontuacao !== null) {
                                    for ($i = 0; $i < 5; $i++):
                                        echo '<i class="fas fa-star" style="color: ' . ($i < $pontuacao ? 'gold' : 'lightgray') . '; margin-right: 2px;"></i>';
                                    endfor;
                                } else {
                                    echo 'Não avaliado';
                                }
                                ?>
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

            function tempoRelativo($data) {
                date_default_timezone_set('America/Sao_Paulo');

                $timestamp = strtotime($data);
                $diferenca = time() - $timestamp;
                $minutos = floor($diferenca / 60);
                $horas = floor($diferenca / 3600);
                $dias = floor($diferenca / 86400);
                $semanas = floor($diferenca / 604800);
                $meses = floor($diferenca / 2592000);
                $anos = floor($diferenca / 31536000);

                if ($diferenca < 60) {
                    return "Agora mesmo";
                } elseif ($minutos < 60) {
                    return $minutos == 1 ? 'Há um minuto' : "Há $minutos minutos";
                } elseif ($horas < 24) {
                    return $horas == 1 ? 'Há uma hora' : "Há $horas horas";
                } elseif ($dias < 7) {
                    return $dias == 1 ? 'Ontem' : "Há $dias dias";
                } elseif ($semanas < 5) {
                    return $semanas == 1 ? 'Há uma semana' : "Há $semanas semanas";
                } elseif ($meses < 12) {
                    return $meses == 1 ? 'Há um mês' : "Há $meses meses";
                } else {
                    return $anos == 1 ? 'Há um ano' : "Há $anos anos";
                }
            }

            if ($resultComentarios && $resultComentarios->num_rows > 0) {
                echo '<div class="comments-section">';
                echo '<h3>Comentários</h3>';

                while ($row = $resultComentarios->fetch_assoc()) {
                    $conteudo = nl2br(htmlspecialchars($row['Conteudo']));
                    $username = htmlspecialchars($row['Username']);
                    $data = htmlspecialchars($row['DataDeComentario']);
                    $dataFormatada = tempoRelativo($data);

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
                <input type="hidden" name="PostID" value="<?php echo isset($postID) ? htmlspecialchars($postID) : ''; ?>">
                <input type="hidden" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
                <textarea id="conteudo" name="conteudo" required></textarea>
                <input type="submit" value="Enviar">
            </form>
        </div>


    </body>

</html>
