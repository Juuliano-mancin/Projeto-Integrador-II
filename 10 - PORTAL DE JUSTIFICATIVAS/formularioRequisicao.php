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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Requisição de Falta</title>
    <link rel="stylesheet" href="forms_requisicao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
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
    <div class="principal">
        <div class="divA1">
            <h1 class="text">Formulário de Requisição</h1>
        </div>
        
        <div class="subA1">
            <p class="p1"><?php echo htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['sobrenome']); ?></p>
            <p class="p2">Matrícula:</p><?php echo htmlspecialchars($_SESSION['matricula']); ?>
        </div>
    
        <div class="divB">
            <form action="confirmarRequisicao.php" method="POST" enctype="multipart/form-data">
                <!-- Dropdown para Selecionar Curso -->
                <h2 class="text1">Selecione o Curso</h2>
                <label for="curso">Curso:</label>
                <select name="curso" id="curso" required onchange="carregarDisciplinas(this.value)">
                    <option value="">Selecione um curso</option>
                    <?php while ($row_curso = $result_cursos->fetch_assoc()): ?>
                        <option value="<?php echo $row_curso['idcurso']; ?>">
                            <?php echo htmlspecialchars($row_curso['curso']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Campos de Data -->
                <h2 class="text">Período da Falta</h2>
                <label for="data_inicial">Data Inicial:</label>
                <input type="date" name="data_inicial" id="data_inicial" required>
                
                <label for="data_final">Data Final:</label>
                <input type="date" name="data_final" id="data_final" required>

                <!-- Radio Buttons para Categorias -->
                <h2 class="text">Categorias de Faltas</h2>
                <?php while ($row_categoria = $result_categoria->fetch_assoc()): ?>
                    <div>
                        <input type="radio" id="categoria<?php echo $row_categoria['idcategoria']; ?>" name="categoria" value="<?php echo $row_categoria['idcategoria']; ?>" required onclick="mostrarJustificativas()">
                        <label for="categoria<?php echo $row_categoria['idcategoria']; ?>"><?php echo htmlspecialchars($row_categoria['categoria']); ?></label>
                    </div>
                <?php endwhile; ?>

                <!-- Dropdown para Justificativas -->
                <h2 class="text">Justificativas</h2>
                <label for="justificativa">Selecione a justificativa:</label>
                <select name="justificativa" id="justificativa" required>
                    <option value="">Selecione uma justificativa</option>
                </select>

                <!-- Caixa de Comentários -->
                <h2 class="text">Comentários</h2>
                <div class="forms_comentario">
                    <label for="comentarios" class="comentario">Adicione seus comentários:</label>
                    <textarea name="comentarios" id="comentarios" rows="4" cols="50" placeholder="Escreva seus comentários aqui..."></textarea>
                </div>
                <div class="containerbtn">
                    <a href="homepageProfessor.php">Página Principal</></a>
                    <button type="submit" class="BtnEnviar">Enviar Requisição</button>
                    
                </div>
            </form>
        </div>
    </div>
    <div class="foot"></div>
    <br>
</body>
</html>

<?php
// Fechando a conexão com o banco de dados
$conn->close();
?>
