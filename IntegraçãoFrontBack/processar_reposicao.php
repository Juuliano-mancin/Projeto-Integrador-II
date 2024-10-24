<?php
session_start();

if (!isset($_SESSION['matricula'])) {
    header("Location: login.php");
    exit();
}

// Conectando ao banco de dados
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Para obter mensagens de erro mais detalhadas
$conexao = new mysqli("localhost", "root", "", "justificativafaltas");

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Coletando dados do formulário
$turno = $_POST['turno'] ?? null;
$tipo = $_POST['tipo'] ?? null;

if ($turno === null || $tipo === null) {
    echo json_encode(["success" => false, "error" => "Turno ou Tipo não informados."]);
    exit();
}

// Preparando a consulta para inserir as informações
$stmt = $conexao->prepare("INSERT INTO tb_reposicao (turno, tipo, matricula_professor) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $turno, $tipo, $_SESSION['matricula']);

if ($stmt->execute()) {
    $idReposicao = $stmt->insert_id; // ID da nova inserção

    // Inserindo as informações na tabela de detalhes
    for ($i = 1; $i <= 6; $i++) {
        if (isset($_POST["data_aula_$i"]) && isset($_POST["disciplina_$i"])) {
            $dataAula = $_POST["data_aula_$i"] ?? null;
            $numeroAulas = $_POST["numero_aulas_$i"] ?? null;
            $disciplina = $_POST["disciplina_$i"] ?? null;
            $dataReposicao = $_POST["data_reposicao_$i"] ?? null;
            $horarioInicio = $_POST["horario_inicio_$i"] ?? null;
            $horarioFinal = $_POST["horario_final_$i"] ?? null;

            // Verificando se todos os campos necessários estão preenchidos
            if ($dataAula && $numeroAulas && $disciplina && $dataReposicao && $horarioInicio && $horarioFinal) {
                // Inserir na tabela de detalhes de reposição
                $stmtDetalhes = $conexao->prepare("INSERT INTO tb_reposicao_detalhes (id_reposicao, data_aula, numero_aulas, disciplina, data_reposicao, horario_inicio, horario_final) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmtDetalhes->bind_param("issssss", $idReposicao, $dataAula, $numeroAulas, $disciplina, $dataReposicao, $horarioInicio, $horarioFinal);
                $stmtDetalhes->execute();
                $stmtDetalhes->close();
            } else {
                echo json_encode(["success" => false, "error" => "Campos de detalhes não estão preenchidos corretamente."]);
                exit();
            }
        }
    }
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conexao->close();
?>
