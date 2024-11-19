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
    $tipoReposicao = $_POST['tipo_reposicao'];
    $cxJustificativa = $_POST['cxjustificativa'];
    $numeroAulas = $_POST['numero_aulas'];
    $dataReposicao = $_POST['data_reposicao'];
    $horarioInicio = $_POST['horario_inicio'];
    $horarioFinal = $_POST['horario_final'];
    $idCurso = $_POST['curso'];
    $idDisciplinas = $_POST['disciplina']; // Agora pode ser um array de disciplinas
    $idRequisicao = $_POST['idrequisicao']; // ID da última requisição

    // Inserção da reposição
    $sqlInsert = "
        INSERT INTO tb_reposicao_aulas 
        (tiporeposicao, cxjustificativa, numeroaulas, datareposicao, horainicioreposicao, horafinalreposicao, idprofessor, idcurso, iddisciplina, idrequisicao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmtInsert = $pdo->prepare($sqlInsert);

    try {
        foreach ($idDisciplinas as $disciplina) {
            $stmtInsert->execute([$tipoReposicao, $cxJustificativa, $numeroAulas, $dataReposicao, $horarioInicio, $horarioFinal, $idProfessor, $idCurso, $disciplina, $idRequisicao]);
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
    <title>Reposição de Aulas</title>
    <link rel="stylesheet" href="forms_repo.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Requisição de Faltas e Reposição de Aulas</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
        </div>
    </header>
    <div class=principal>
        
    <div class="divA1">
            <h1 class="text">Formulário de Reposição</h1>
        </div>
        
        <div class="subA1">
            <p class="p1"><?php echo htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['sobrenome']); ?></p>
            <p class="p2">Matrícula:</p><?php echo htmlspecialchars($_SESSION['matricula']); ?>
        </div>
    
        <div class="divB">
            <form action="" method="POST">
                <div class="form-group">
                    <label class="text">Tipo de Reposição:</label><br>
                    <label class="mr-3"><input type="radio" name="tipo_reposicao" value="Aula Ministrada" required onchange="mostrarJustificativa()"> Aula Ministrada</label>
                    <label class="mr-3"><input type="radio" name="tipo_reposicao" value="Atividades" onchange="mostrarJustificativa()"> Atividades</label>
                    <label><input type="radio" name="tipo_reposicao" value="Outros" onchange="mostrarJustificativa()"> Outros</label>
                </div>
                <div id="justificativaField" class="form-group" style="display: none;">
                    <input type="text" name="cxjustificativa" class="form-control" placeholder="Justificativa">
                </div>

                <div class="form-group">
                    <label class="text">Selecione um Curso:</label><br>
                    <?php foreach ($cursos as $curso): ?>
                    <label class="mr-3">
                        <input type="radio" name="curso" value="<?php echo htmlspecialchars($curso['idcurso']); ?>" onchange="carregarDisciplinas(this.value)">
                        <?php echo htmlspecialchars($curso['idcurso'] . ' - ' . $curso['curso']); ?>
                    </label><br>
                <?php endforeach; ?>
                </div>

                <div class="form-group" id="disciplinasContainer">
                    <label class="text">Selecione uma ou mais Disciplinas:</label><br>
                    <!-- As disciplinas serão carregadas aqui via AJAX -->
                </div>

                <div class="form-group2" class="text">
                    <label for="numero_aulas" class="text">Número de Aulas:</label>
                    <select name="numero_aulas" id="numero_aulas" class="form-control">
                        <option value="1">01 Aula</option>
                        <option value="2">02 Aulas (meio dia)</option>
                        <option value="3">03 Aulas</option>
                        <option value="4">04 Aulas (dia todo)</option>
                    </select>
                </div>

                <div class="form-group2">
                    <label for="data_reposicao" class="text">Data Reposição:</label>
                    <input type="date" name="data_reposicao" id="data_reposicao" class="form-control" required>
                </div>

                <div class="form-group2">
                    <label for="horario_inicio" class="text">Horário Início:</label>
                    <input type="time" name="horario_inicio" id="horario_inicio" class="form-control" required>
        
                    <label for="horario_final" class="text">Horário Final:</label>
                    <input type="time" name="horario_final" id="horario_final" class="form-control" required>
                </div>

                <div class="form-group2">
                    <label class="text">Última Requisição Feita:</label>
                    <select name="idrequisicao" id="idrequisicao" class="form-control" required>
                        <option value="<?php echo htmlspecialchars($ultimaRequisicao['idrequisicao']); ?>">
                            <?php echo htmlspecialchars($ultimaRequisicao['data_inicial']); ?>
                        </option>
                    </select>
                </div>
                <div class="button-container">
                    <a href="homepageProfessor.php">Página Principal</></a>
                    <button type="submit" class="BtnEnviar">Enviar</button>
                </div>
            </form>
            
        </div>
    </div>
    <div class="foot"></div>
   </br>

   <script>
        // Verifica se o PHP passou a variável de sucesso para o JavaScript
        <?php if ($sucesso): ?>
            alert("Reposição enviada com sucesso!");
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
                        let checkboxesHTML = '<label>Selecione uma ou mais Disciplinas:</label><br>';
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
