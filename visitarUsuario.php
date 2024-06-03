<?php

session_start();
require_once 'autenticacao.php';

class VisitarUsuario {

    private $nomeUsuario;
    private $perfilUsuario;

    public function __construct($nomeUsuario = null) {
        $this->nomeUsuario = $nomeUsuario ? $nomeUsuario : autenticacao::getUsername();
        }

  

       function buscarInformacoesUsuario($nomeUsuario) {
    $conn = new mysqli('localhost', 'root', '', 'webPro');
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT nomeUsuario, biografia, DataDeCriacao, fotoPerfil FROM perfilusuario WHERE nomeUsuario = ?");
    if (!$stmt) {
        echo "Erro de preparação: " . $conn->error;
        return [];
    }

    $stmt->bind_param("s", $nomeUsuario);
    $stmt->execute();
    $result = $stmt->get_result(); // Mudar para get_result para usar fetch_assoc
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        $conn->close();
        return $row;
    } else {
        $stmt->close();
        $conn->close();
        return []; // Retorna um array vazio se não encontrar o usuário
    }
}


public function getPerfilUsuario() {
    if (isset($_POST['nomeUsuario']) && !empty($_POST['nomeUsuario'])) {
        $nomeUsuario = $_POST['nomeUsuario'];
    } else {
        echo "Nome de usuário não fornecido.";
        return []; // Retorna um array vazio se não for fornecido um nome de usuário
    }

    if (!$this->perfilUsuario) {
        $this->perfilUsuario = $this->buscarInformacoesUsuario($nomeUsuario);
    }

    return $this->perfilUsuario;
}


    public function calcularTempoDeConta() {
        if (!$this->perfilUsuario) {
            $this->buscarInformacoesUsuario();
        }

        date_default_timezone_set('America/Sao_Paulo');
        $dataDeCriacao = new DateTime($this->perfilUsuario['dataDeCriacao']);
        $dataAtual = new DateTime();

        $dataDeCriacaoFormatada = $dataDeCriacao->format('Y-m-d');
        $dataAtualFormatada = $dataAtual->format('Y-m-d');

        if ($dataDeCriacaoFormatada == $dataAtualFormatada) {
            return "hoje";
        } else {
            $dataDeCriacao->setTime(0, 0, 0);
            $dataAtual->setTime(0, 0, 0);
            $intervalo = $dataDeCriacao->diff($dataAtual);
            $totalDias = $intervalo->days;

            if ($totalDias == 1) {
                return "há 1 dia atrás";
            } elseif ($totalDias < 7) {
                return "há $totalDias dias atrás";
            } elseif ($totalDias < 30) {
                return "há " . floor($totalDias / 7) . " semana(s)";
            } elseif ($totalDias < 365) {
                return "há " . floor($totalDias / 30) . " mês(es)";
            } else {
                return "há " . floor($totalDias / 365) . " ano(s)";
            }
        }
    }
}

?>
