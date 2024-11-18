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

// Verificando se o ID da requisição foi passado na URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Buscando a requisição específica do banco
$idRequisicao = $_GET['id'];
$query_requisicao = "SELECT r.*, c.curso, cf.categoria
                     FROM tb_requisicao_faltas r
                     JOIN tb_cursos c ON r.idcurso = c.idcurso
                     JOIN tb_categoria_faltas cf ON r.idcategoria = cf.idcategoria
                     WHERE r.idrequisicao = ?";
$stmt = $conn->prepare($query_requisicao);
$stmt->bind_param("i", $idRequisicao);
$stmt->execute();
$result_requisicao = $stmt->get_result();

if ($result_requisicao->num_rows === 0) {
    echo "Requisição não encontrada!";
    exit();
}

$requisicao = $result_requisicao->fetch_assoc();

// Variável para indicar se o formulário foi enviado e os dados foram atualizados
$form_saved = false;

// Verificando se o formulário foi enviado e processando a atualização
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar'])) {
    // Capturando os dados do formulário
    $data_inicial = $_POST['data_inicial'];
    $data_final = $_POST['data_final'];
    $categoria = $_POST['categoria'];
    $justificativa = $_POST['justificativa'];
    $comentariocoordenacao = isset($_POST['comentariocoordenacao']) ? trim($_POST['comentariocoordenacao']) : '';

    // Atualizando a requisição no banco de dados
    $query_update = "UPDATE tb_requisicao_faltas 
                     SET data_inicial = ?, data_final = ?, idcategoria = ?, idjustificativa = ?, statusrequisicao = 'pendente', comentariocoordenacao = ?
                     WHERE idrequisicao = ?";
    
    // Se o comentário coordenador for vazio, passamos NULL
    $comentariocoordenacao = empty($comentariocoordenacao) ? NULL : $comentariocoordenacao;

    // Preparando o statement
    $stmt_update = $conn->prepare($query_update);

    // Ajuste o bind_param para corresponder aos tipos esperados
    if ($comentariocoordenacao === NULL) {
        $stmt_update->bind_param("sssisi", $data_inicial, $data_final, $categoria, $justificativa, $comentariocoordenacao, $idRequisicao);
    } else {
        $stmt_update->bind_param("sssisi", $data_inicial, $data_final, $categoria, $justificativa, $comentariocoordenacao, $idRequisicao);
    }

    // Executando a consulta
    if ($stmt_update->execute()) {
        // Indicando que os dados foram salvos
        $form_saved = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editar_req.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
    <title>Editar Requisição</title>
    <script>
        function mostrarJustificativas() {
            const categoriaRadios = document.getElementsByName('categoria');
            const justificativaSelect = document.getElementById('justificativa');
            const selectedCategory = Array.from(categoriaRadios).find(radio => radio.checked).value;

            // Limpa o select de justificativas
            justificativaSelect.innerHTML = '<option value="">Selecione uma justificativa</option>';

            // Preenche o select com as justificativas da categoria selecionada
            const justificativas = <?php echo json_encode($justificativas); ?>;  // Passando o array PHP para o JS

            // Verifica se existem justificativas para a categoria selecionada
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
            // Marcar a categoria atual como selecionada por padrão
            const categoriaRadios = document.getElementsByName('categoria');
            categoriaRadios.forEach(radio => {
                if (radio.value == <?php echo $requisicao['idcategoria']; ?>) {
                    radio.checked = true;
                }
            });
            mostrarJustificativas(); // Preencher justificativas para a categoria atual

            // Verificando se o formulário foi salvo com sucesso
            <?php if ($form_saved): ?>
                // Exibindo o pop-up de sucesso
                alert("As informações foram salvas com sucesso!");

                // Após o usuário clicar em "OK", redireciona para a página de reposição
                window.location.href = 'alterarReposicao.php?idrequisicao=<?php echo $requisicao['idrequisicao']; ?>';
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Editar Requisição</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
        </div>
    </header>
    <div class="principal">
        <div class="divA1">
            <h1 class="text">Editar Requisição</h1>
        </div>
        
        <div class="subA1">
            <p class="p1"><?php echo htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['sobrenome']); ?></p>
            <p class="p2">Matrícula:</p><?php echo htmlspecialchars($_SESSION['matricula']); ?>
        </div>

        <div class="divB">
            <form action="" method="POST">
                <!-- Campos de Data -->
                <h2 class="text">Período</h2>
                <label for="data_inicial">Data Inicial:</label>
                <input type="date" name="data_inicial" id="data_inicial" value="<?php echo htmlspecialchars($requisicao['data_inicial']); ?>" required>
                
                <label for="data_final" >Data Final:</label>
                <input type="date" name="data_final" id="data_final" value="<?php echo htmlspecialchars($requisicao['data_final']); ?>" required>

                <!-- Radio Buttons para Categorias -->
                <h2 class="text">Categorias de Faltas</h2>
                <?php while ($row_categoria = $result_categoria->fetch_assoc()): ?>
                    <div>
                        <input type="radio" id="categoria<?php echo $row_categoria['idcategoria']; ?>" name="categoria" value="<?php echo $row_categoria['idcategoria']; ?>" required onclick="mostrarJustificativas()" <?php echo $row_categoria['idcategoria'] == $requisicao['idcategoria'] ? 'checked' : ''; ?>>
                        <label for="categoria<?php echo $row_categoria['idcategoria']; ?>"><?php echo htmlspecialchars($row_categoria['categoria']); ?></label>
                    </div>
                <?php endwhile; ?>

                <!-- Dropdown para Justificativas -->
                <h2 class="text">Justificativas</h2>
                <label for="justificativa">Selecione a justificativa:</label>
                <select name="justificativa" id="justificativa" required>
                    <option value="">Selecione uma justificativa</option>
                </select>

                <script>
                    // Preencher justificativas ao carregar a página
                    mostrarJustificativas();
                </script>
                <div class="dados1">
                <!-- Campo para upload de novo PDF -->
                    <p>Selecione o arquivo: </p><label for="pdf" class="custom-file-label">Arquivo</label>
                    <input type="file" name="pdf" id="pdf" accept="application/pdf" class="custom-file-input">
                </div>
            
                <div class="button-container">
                <!-- Botão para salvar as informações -->
                    <input type="hidden" name="idrequisicao" value="<?php echo htmlspecialchars($requisicao['idrequisicao']); ?>">
                    
                    <button onclick="window.location.href='homepageProfessor.php'" class="Voltarbtn">Página Principal</button>
                    <button type="submit" name="salvar" class="salvar">Salvar Alterações</button>
                </div>
            </form>

            <!-- Botão para voltar para a homepage -->
            
        </div>
    </div>

</body>
</html>
