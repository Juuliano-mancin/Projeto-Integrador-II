<?php
session_start();

if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=justificativafaltas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}

$idProfessor = $_SESSION['idprofessor'];
$nomeProfessor = $_SESSION['nome'];

// Consultar cursos
$sqlCursos = "SELECT idcurso, curso FROM tb_cursos";
$stmtCursos = $pdo->prepare($sqlCursos);
$stmtCursos->execute();
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

// Puxar a última requisição do professor
$sqlUltimaRequisicao = "
    SELECT * 
    FROM tb_requisicao_faltas r 
    WHERE r.idprofessor = ? 
    ORDER BY r.idrequisicao DESC LIMIT 1";
$stmtUltimaRequisicao = $pdo->prepare($sqlUltimaRequisicao);
$stmtUltimaRequisicao->execute([$idProfessor]);
$ultimaRequisicao = $stmtUltimaRequisicao->fetch(PDO::FETCH_ASSOC);

// Variável para verificar sucesso
$sucesso = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificando se as variáveis estão definidas para evitar erros de índice indefinido
    $tipoReposicao = isset($_POST['tipo_reposicao']) ? $_POST['tipo_reposicao'] : '';
    $cxJustificativa = isset($_POST['cxjustificativa']) ? $_POST['cxjustificativa'] : '';
    $numeroAulas = isset($_POST['numero_aulas']) ? $_POST['numero_aulas'] : '';
    $dataReposicao = isset($_POST['data_reposicao']) ? $_POST['data_reposicao'] : '';
    $horarioInicio = isset($_POST['horario_inicio']) ? $_POST['horario_inicio'] : '';
    $horarioFinal = isset($_POST['horario_final']) ? $_POST['horario_final'] : '';
    $idCurso = isset($_POST['curso']) ? $_POST['curso'] : '';
    $idDisciplinas = isset($_POST['disciplina']) ? $_POST['disciplina'] : []; // Agora pode ser um array de disciplinas
    $idRequisicao = isset($_POST['idrequisicao']) ? $_POST['idrequisicao'] : ''; // ID da última requisição

    // Atualização da reposição, incluindo a mudança do status
    $sqlUpdate = "
        UPDATE tb_reposicao_aulas
        SET tiporeposicao = ?, cxjustificativa = ?, numeroaulas = ?, datareposicao = ?, 
            horainicioreposicao = ?, horafinalreposicao = ?, idcurso = ?, iddisciplina = ?, 
            idrequisicao = ?, statusreposicao = 'Pendente'  -- Atualiza o status para 'Pendente'
        WHERE idprofessor = ? AND idrequisicao = ?";  // Condição para garantir que só a reposição do professor e da requisição seja atualizada

    $stmtUpdate = $pdo->prepare($sqlUpdate);

    try {
        // Atualizando para cada disciplina selecionada (no caso, várias disciplinas podem ser selecionadas)
        foreach ($idDisciplinas as $disciplina) {
            $stmtUpdate->execute([$tipoReposicao, $cxJustificativa, $numeroAulas, $dataReposicao, 
                                  $horarioInicio, $horarioFinal, $idCurso, $disciplina, 
                                  $idRequisicao, $idProfessor, $idRequisicao]);
        }
        // Definir sucesso como verdadeiro
        $sucesso = true;
    } catch (PDOException $e) {
        echo "Erro ao salvar a reposição: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Reposição</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link para o CSS customizado -->
</head>
<body>
    <div class="container mt-5">
        <h1>Formulário de Reposição</h1>
        <p>Bem-vindo, <?php echo htmlspecialchars($nomeProfessor); ?></p>

        <form action="" method="POST" class="mb-4">
            <div class="form-group">
                <label>Tipo de Reposição:</label><br>
                <label class="mr-3"><input type="radio" name="tipo_reposicao" value="Aula Ministrada" required onchange="mostrarJustificativa()"> Aula Ministrada</label>
                <label class="mr-3"><input type="radio" name="tipo_reposicao" value="Atividades" onchange="mostrarJustificativa()"> Atividades</label>
                <label><input type="radio" name="tipo_reposicao" value="Outros" onchange="mostrarJustificativa()"> Outros</label>
            </div>
            <div id="justificativaField" class="form-group" style="display: none;">
                <input type="text" name="cxjustificativa" class="form-control" placeholder="Justificativa">
            </div>

            <div class="form-group">
                <label>Selecione um Curso:</label><br>
                <?php foreach ($cursos as $curso): ?>
                    <label class="mr-3">
                        <input type="radio" name="curso" value="<?php echo htmlspecialchars($curso['idcurso']); ?>" onchange="carregarDisciplinas(this.value)">
                        <?php echo htmlspecialchars($curso['idcurso'] . ' - ' . $curso['curso']); ?>
                    </label><br>
                <?php endforeach; ?>
            </div>

            <div class="form-group" id="disciplinasContainer">
                <label>Selecione uma ou mais Disciplinas:</label><br>
                <!-- As disciplinas serão carregadas aqui via AJAX -->
            </div>

            <div class="form-group">
                <label for="numero_aulas">Número de Aulas:</label>
                <select name="numero_aulas" id="numero_aulas" class="form-control">
                    <option value="1">01 Aula</option>
                    <option value="2">02 Aulas (meio dia)</option>
                    <option value="3">03 Aulas</option>
                    <option value="4">04 Aulas (dia todo)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data_reposicao">Data Reposição:</label>
                <input type="date" name="data_reposicao" id="data_reposicao" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="horario_inicio">Horário Início:</label>
                <input type="time" name="horario_inicio" id="horario_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="horario_final">Horário Final:</label>
                <input type="time" name="horario_final" id="horario_final" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Última Requisição Feita:</label>
                <select name="idrequisicao" id="idrequisicao" class="form-control" required>
                    <option value="<?php echo htmlspecialchars($ultimaRequisicao['idrequisicao']); ?>">
                        <?php echo htmlspecialchars($ultimaRequisicao['data_inicial']); ?>
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <a href="homepageProfessor.php" class="btn btn-secondary mt-4">Voltar à Página Principal</a>
    </div>

    <script>
        // Verifica se o PHP passou a variável de sucesso para o JavaScript
        <?php if ($sucesso): ?>
            alert("Reposição atualizada com sucesso!");
        <?php endif; ?>

        function carregarDisciplinas(cursoId) {
            const disciplinasContainer = document.getElementById('disciplinasContainer');
            disciplinasContainer.innerHTML = '<label>Carregando disciplinas...</label>'; // Mensagem de carregamento

            if (cursoId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'carregar_disciplinas.php?curso=' + cursoId, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        const disciplinas = JSON.parse(this.responseText);
                        let checkboxesHTML = '';
                        disciplinas.forEach(disciplina => {
                            checkboxesHTML += `
                                <label>
                                    <input type="checkbox" name="disciplina[]" value="${disciplina.iddisciplina}">
                                    ${disciplina.iddisciplina} - ${disciplina.disciplina}
                                </label><br>
                            `;
                        });
                        disciplinasContainer.innerHTML = checkboxesHTML;
                    }
                };
                xhr.send();
            } else {
                disciplinasContainer.innerHTML = '';
            }
        }

        // Controla a visibilidade do campo Justificativa
        function mostrarJustificativa() {
            const tipoReposicao = document.querySelector('input[name="tipo_reposicao"]:checked');
            const justificativaField = document.getElementById('justificativaField');
            if (tipoReposicao && tipoReposicao.value === 'Outros') {
                justificativaField.style.display = 'block';
            } else {
                justificativaField.style.display = 'none';
            }
        }
    </script>
</body>
</html>
