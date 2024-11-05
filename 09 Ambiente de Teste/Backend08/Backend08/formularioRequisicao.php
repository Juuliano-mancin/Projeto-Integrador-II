<?php
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'justificativafaltas');

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultando as categorias
$query_categoria = "SELECT * FROM tb_categoria_faltas";
$result_categoria = $conn->query($query_categoria);

// Consultando as justificativas
$query_justificativas = "SELECT * FROM tb_justificativa_faltas";
$result_justificativas = $conn->query($query_justificativas);

// Consultando os cursos
$query_cursos = "SELECT * FROM tb_cursos";
$result_cursos = $conn->query($query_cursos);

// Armazenando as justificativas em um array, agora usando idcategoria
$justificativas = [];
while ($row = $result_justificativas->fetch_assoc()) {
    $justificativas[$row['idcategoria']][] = $row; // Alterado para idcategoria
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Requisição</title>
    <link rel="stylesheet" href="forms_requisicao.css">
    <script>
        function mostrarJustificativas() {
            const categoriaRadios = document.getElementsByName('categoria');
            const justificativaSelect = document.getElementById('justificativa');
            const selectedCategory = Array.from(categoriaRadios).find(radio => radio.checked).value;

            // Limpa o select de justificativas
            justificativaSelect.innerHTML = '<option value="">Selecione uma justificativa</option>';

            // Preenche o select com as justificativas da categoria selecionada
            const justificativas = <?php echo json_encode($justificativas); ?>;

            if (justificativas[selectedCategory]) {
                justificativas[selectedCategory].forEach(j => {
                    const option = document.createElement('option');
                    option.value = j.idjustificativa;
                    option.textContent = j.justificativa;
                    justificativaSelect.appendChild(option);
                });
            }
        }

        function carregarDisciplinas(cursoId) {
            const selectDisciplinas = document.getElementById('disciplina');
            selectDisciplinas.innerHTML = '<option value="">Selecione uma disciplina</option>'; // Limpa o select

            if (cursoId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_disciplinas.php?curso=' + cursoId, true); // Altere para o arquivo que lida com essa requisição
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

        window.onload = () => {
            // Marcar a primeira categoria como selecionada por padrão
            const categoriaRadios = document.getElementsByName('categoria');
            if (categoriaRadios.length > 0) {
                categoriaRadios[0].checked = true;
                mostrarJustificativas(); // Preencher justificativas para a primeira categoria
            }
        };
    </script>
</head>
<body>
        <!-- Header fixo no topo da página -->
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Coordenação</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
            <div class="divA_2">
           
            </div>
        </div>
    </header>

    <div class="principal">
        <div class="divA1">
            <div class="subA1">
                <p><?php echo htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['sobrenome']); ?></p>
            </div>
            <div class="subA1a">
                <p>Matrícula: <?php echo htmlspecialchars($_SESSION['matricula']); ?></p>
            </div>

            <div class="subA2">
            <form action="logout.php" method="POST" style="float: right; margin: 10px;">
                    <button type="submit" class="Sairbtn">
                        SAIR
                    </button>
                </form>
            </div>
            
        
        </div>

        <div class="divB">
            <div class="subB1">
                <h1>Formulário de Requisição</h1>
            </div>
            <div class="subB2">
                <form action="confirmarRequisicao.php" method="POST" enctype="multipart/form-data">
                    <!-- Dropdown para Selecionar Curso -->
                    <h2 class="text">Selecione o Curso e a Disciplina</h2>
                    <div class="subForms">
                        <div class="subForms-curso">
                            <label for="curso">Curso:</label>
                            <select name="curso" id="curso" required onchange="carregarDisciplinas(this.value)">
                                <option value="">Selecione um curso</option>
                                <?php while ($row_curso = $result_cursos->fetch_assoc()): ?>
                                    <option value="<?php echo $row_curso['idcurso']; ?>">
                                        <?php echo htmlspecialchars($row_curso['curso']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="subForms-B">
                            <label for="disciplina" class="disciplina">Disciplina:</label>
                            <select name="disciplina" id="disciplina" required>
                                <option value="">Selecione uma disciplina</option>
                            </select>
                        </div>
                    </div>

                    <!-- Campos de Data -->
                    <h2 class="text">Período</h2>
                    <div class="subForms2">
                        <div class="subForms-datainicial">
                            <label for="data_inicial">Data Inicial:</label>
                            <input type="date" name="data_inicial" id="data_inicial" required>
                        </div>
                        <div class="subForms-datafinal">
                            <label for="data_final" class="dataFinal">Data Final:</label>
                            <input type="date" name="data_final" id="data_final" required>
                        </div>
                    </div>

                    <!-- Radio Buttons para Categorias -->
                    <h2 class="text">Categorias de Faltas</h2>
                    <div class="subForms3">
                        <?php while ($row_categoria = $result_categoria->fetch_assoc()): ?>
                            <div class="subForms-faltas">
                                <input type="radio" id="categoria<?php echo $row_categoria['idcategoria']; ?>" name="categoria" value="<?php echo $row_categoria['idcategoria']; ?>" required onclick="mostrarJustificativas()">
                                <label for="categoria<?php echo $row_categoria['idcategoria']; ?>"><?php echo htmlspecialchars($row_categoria['categoria']); ?></label>
                            </div>
                        <?php endwhile; ?>
                    </div>

                    <!-- Dropdown para Justificativas -->
                    <h2 class="text">Justificativas</h2>
                    <div class="subForms2">
                        <label for="justificativa">Selecione a justificativa:</label>
                        <select name="justificativa" id="justificativa" required>
                            <option value="">Selecione uma justificativa</option>
                        </select>
                    </div>

                    <!-- Caixa de Comentários -->
                    <h2 class="text">Comentários</h2>
                    <div class="subForms2">
                        <label for="comentarios">Adicione seus comentários:</label>
                        <textarea name="comentarios" id="comentarios" rows="4" cols="50" placeholder="Escreva seus comentários aqui..."></textarea>
                    </div>        
                    <button type="submit">Enviar Requisição</button>
                </form>
            </div>
        </div>
    </div>
    <div class="foot">

    </div>
    <br>
</body>
</html>

<?php
// Fechando a conexão com o banco de dados
$conn->close();
?>
