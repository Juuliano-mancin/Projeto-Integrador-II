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

$sqlCursos = "SELECT idcurso, curso FROM tb_cursos";
$stmtCursos = $pdo->prepare($sqlCursos);
$stmtCursos->execute();
$cursos = $stmtCursos->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['curso'])) {
    $idCurso = $_GET['curso'];
    $sqlDisciplinas = "SELECT iddisciplina, disciplina FROM tb_disciplinas WHERE idcurso = ?";
    $stmtDisciplinas = $pdo->prepare($sqlDisciplinas);
    $stmtDisciplinas->execute([$idCurso]);
    $disciplinas = $stmtDisciplinas->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($disciplinas);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tipoReposicao = $_POST['tipo_reposicao'];
    $cxJustificativa = $_POST['cxjustificativa'];
    $numeroAulas = $_POST['numero_aulas'];
    $dataReposicao = $_POST['data_reposicao'];
    $horarioInicio = $_POST['horario_inicio'];
    $horarioFinal = $_POST['horario_final'];
    $idCurso = $_POST['curso'];
    $idDisciplina = $_POST['disciplina'];
    $dataFaltaAprovada = $_POST['data_falta'];
    $idRequisicao = $_POST['idrequisicao']; // Pegar o ID da requisição

    $sqlInsert = "INSERT INTO tb_reposicao_aulas (tiporeposicao, cxjustificativa, numeroaulas, datareposicao, horainicioreposicao, horafinalreposicao, idprofessor, idcurso, iddisciplina, datafaltaaprovada, idrequisicao)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmtInsert = $pdo->prepare($sqlInsert);

    try {
        $stmtInsert->execute([$tipoReposicao, $cxJustificativa, $numeroAulas, $dataReposicao, $horarioInicio, $horarioFinal, $idProfessor, $idCurso, $idDisciplina, $dataFaltaAprovada, $idRequisicao]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo "Erro ao salvar a reposição: " . $e->getMessage();
    }
}

$sqlReposicoes = "
    SELECT r.idreposicao, d.disciplina, c.curso, r.tiporeposicao, r.cxjustificativa, 
           r.horainicioreposicao, r.horafinalreposicao, r.numeroaulas, r.statusreposicao, 
           r.datafaltaaprovada, r.idrequisicao
    FROM tb_reposicao_aulas r
    JOIN tb_disciplinas d ON r.iddisciplina = d.iddisciplina
    JOIN tb_cursos c ON r.idcurso = c.idcurso
    WHERE r.idprofessor = ?";
$stmtReposicoes = $pdo->prepare($sqlReposicoes);
$stmtReposicoes->execute([$idProfessor]);
$reposicoes = $stmtReposicoes->fetchAll(PDO::FETCH_ASSOC);

$sqlDatasAprovadas = "
    SELECT r.idrequisicao, r.data_inicial 
    FROM tb_requisicao_faltas r 
    WHERE r.idprofessor = ? AND r.statusrequisicao = 'Aprovado'";
$stmtDatasAprovadas = $pdo->prepare($sqlDatasAprovadas);
$stmtDatasAprovadas->execute([$idProfessor]);
$datasAprovadas = $stmtDatasAprovadas->fetchAll(PDO::FETCH_ASSOC);

// Filtrar os IDs de requisição utilizados
$idsRequisicaoUtilizados = [];
foreach ($reposicoes as $reposicao) {
    $idsRequisicaoUtilizados[] = $reposicao['idrequisicao'];
}

// Filtrar as datas aprovadas que não têm ID de requisição já utilizado
$datasAprovadasFiltradas = array_filter($datasAprovadas, function($data) use ($idsRequisicaoUtilizados) {
    return !in_array($data['idrequisicao'], $idsRequisicaoUtilizados);
});

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
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
        <p>Bem-vindo, <?php echo htmlspecialchars($nomeProfessor); ?> (ID <?php echo htmlspecialchars($idProfessor); ?>)</p>

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

            <div class="form-group">
                <label for="disciplina">Selecione uma Disciplina:</label>
                <select name="disciplina" id="disciplina" class="form-control">
                    <option value="">Selecione uma disciplina</option>
                </select>
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
                <label>Selecione uma Data de Falta Aprovada:</label>
                <select name="data_falta" id="data_falta" class="form-control" required>
                    <option value="">Selecione uma data</option>
                    <?php foreach ($datasAprovadasFiltradas as $data): ?>
                        <option value="<?php echo htmlspecialchars($data['data_inicial']); ?>" data-id="<?php echo htmlspecialchars($data['idrequisicao']); ?>">
                            <?php echo formatarData($data['data_inicial']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="idrequisicao" id="idrequisicao" value="">
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <h2>Reposições Salvas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Reposição</th>
                    <th>Disciplina</th>
                    <th>Curso</th>
                    <th>Tipo de Reposição</th>
                    <th>Justificativa</th>
                    <th>Hora Inicial</th>
                    <th>Hora Final</th>
                    <th>Número de Aulas</th>
                    <th>Status</th>
                    <th>Data da Falta Aprovada</th>
                    <th>Confirmar Reposição</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reposicoes as $reposicao): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reposicao['idreposicao']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['disciplina']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['curso']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['tiporeposicao']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['cxjustificativa']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['horainicioreposicao']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['horafinalreposicao']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['numeroaulas']); ?></td>
                        <td><?php echo htmlspecialchars($reposicao['statusreposicao']); ?></td>
                        <td><?php echo formatarData($reposicao['datafaltaaprovada']); ?></td>
                        <td>
                            <?php if ($reposicao['statusreposicao'] !== 'finalizada'): ?>
                                <form action="confirmar_reposicao.php" method="POST">
                                    <input type="hidden" name="idreposicao" value="<?php echo htmlspecialchars($reposicao['idreposicao']); ?>">
                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                </form>
                            <?php else: ?>
                                <span class="text-success">Reposição Confirmada</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="homepageProfessor.php" class="btn btn-secondary mt-4">Voltar à Página Principal</a>
    </div>

    <script>
        function carregarDisciplinas(cursoId) {
            const selectDisciplinas = document.getElementById('disciplina');
            selectDisciplinas.innerHTML = '';
            const optionDefault = document.createElement('option');
            optionDefault.value = '';
            optionDefault.textContent = 'Selecione uma disciplina';
            selectDisciplinas.appendChild(optionDefault);
            if (cursoId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '?curso=' + cursoId, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        const disciplinas = JSON.parse(this.responseText);
                        disciplinas.forEach(disciplina => {
                            const option = document.createElement('option');
                            option.value = disciplina.iddisciplina;
                            option.textContent = disciplina.iddisciplina + ' - ' + disciplina.disciplina;
                            selectDisciplinas.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }
        }

        function mostrarJustificativa() {
            const tipoReposicao = document.querySelector('input[name="tipo_reposicao"]:checked');
            const justificativaField = document.getElementById('justificativaField');
            justificativaField.style.display = tipoReposicao && tipoReposicao.value === 'Outros' ? 'block' : 'none';
        }

        // Atualizar o campo hidden com o id da requisição ao selecionar uma data
        document.getElementById('data_falta').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const idRequisicao = selectedOption.getAttribute('data-id');
            document.getElementById('idrequisicao').value = idRequisicao;
        });
    </script>
</body>
</html>
