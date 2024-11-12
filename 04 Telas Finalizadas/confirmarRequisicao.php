<?php
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    header("Location: login.php");
    exit();
}

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando os dados do formulário para exibição
    $curso_id = htmlspecialchars($_POST['curso']); // Aqui usamos 'curso_id'
    $data_inicial = htmlspecialchars($_POST['data_inicial']);
    $data_final = htmlspecialchars($_POST['data_final']);
    $categoria_id = htmlspecialchars($_POST['categoria']);
    $justificativa_id = htmlspecialchars($_POST['justificativa']);
    $comentarios = htmlspecialchars($_POST['comentarios']);
} else {
    header("Location: formularioRequisicao.php");
    exit();
}

// Conexão com o banco de dados
$conn = new mysqli('localhost', 'root', '', 'justificativafaltas');

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consultando o nome do curso
$query_curso = "SELECT curso FROM tb_cursos WHERE idcurso = ?"; // Altere 'tb_cursos' e 'nome_curso' para os nomes reais de sua tabela e coluna
$stmt_curso = $conn->prepare($query_curso);
$stmt_curso->bind_param("i", $curso_id);
$stmt_curso->execute();
$result_curso = $stmt_curso->get_result();
$curso_texto = $result_curso->fetch_assoc()['curso'];

// Consultando a categoria
$query_categoria = "SELECT categoria FROM tb_categoria_faltas WHERE idcategoria = ?";
$stmt_categoria = $conn->prepare($query_categoria);
$stmt_categoria->bind_param("i", $categoria_id);
$stmt_categoria->execute();
$result_categoria = $stmt_categoria->get_result();
$categoria_texto = $result_categoria->fetch_assoc()['categoria'];

// Consultando a justificativa
$query_justificativa = "SELECT justificativa FROM tb_justificativa_faltas WHERE idjustificativa = ?";
$stmt_justificativa = $conn->prepare($query_justificativa);
$stmt_justificativa->bind_param("i", $justificativa_id);
$stmt_justificativa->execute();
$result_justificativa = $stmt_justificativa->get_result();
$justificativa_texto = $result_justificativa->fetch_assoc()['justificativa'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Confirmar Requisição de Falta</title>
    <link rel="stylesheet" href="confirmar_req.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
<header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Confirmação Requisição</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
        </div>
    </header>
    <div class="principal">
        <div class="divA1">
            <h1>Confirmação da Requisição</h1>
        </div>
        <div class="subA1">
            <p class="p1"><?php echo htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['sobrenome']); ?></p>
            <p class="p2">Matrícula:</p> <?php echo htmlspecialchars($_SESSION['matricula']); ?>
        </div>
        <div class="divB">    
            <h2>Dados da Requisição</h2>
            <div class="dados1">
                <h3 class="texto">Curso:</h3><p class="dados"><?php echo $curso_id . ' - ' . htmlspecialchars($curso_texto); ?></p>
            </div>
            <div class="dados1">
                <h3 class="texto">Data Inicial:</h3><p class="dados"><?php echo $data_inicial; ?></p>
                <h3 class="texto">Data Final:</h3><p class="dados"><?php echo $data_final; ?></p>
            </div>
            <div class="dados1">
                <h3 class="texto">Categoria:</h3> <p class="dados"><?php echo $categoria_id . ' - ' . htmlspecialchars($categoria_texto); ?></p>
                <h3 class="texto">Justificativa:</h3> <p class="dados"><?php echo $justificativa_id . ' - ' . htmlspecialchars($justificativa_texto); ?></p>
            </div>
            <div class="dados1">
                <h3 class="texto">Comentários:</h3> 
                <p class="dados"><?php echo !empty($comentarios) ? htmlspecialchars($comentarios) : "<em>Nenhum comentário</em>";?></p>
            </div>

            <!-- Formulário para confirmar ou cancelar o envio -->
            <form action="processarRequisicao.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="curso" value="<?php echo $curso_id; ?>">
                <input type="hidden" name="data_inicial" value="<?php echo $data_inicial; ?>">
                <input type="hidden" name="data_final" value="<?php echo $data_final; ?>">
                <input type="hidden" name="categoria" value="<?php echo $categoria_id; ?>">
                <input type="hidden" name="justificativa" value="<?php echo $justificativa_id; ?>">
                <input type="hidden" name="comentarios" value="<?php echo $comentarios; ?>">
                <br>
                <!-- Campo para Upload de Arquivo -->
                <h3 class="texto">Upload de Arquivo</h3>
                <div class="dados1">
                    <p class="label_tex">Selecione um ou mais arquiivos: </p><label for="arquivos" class="custom-file-label">Arquivos</label>
                    <input type="file" name="arquivos[]" id="arquivos" class="custom-file-input" multiple>
                </div>
                <div class="button-container">
                
                    <a href="formularioRequisicao.php"><button class="Voltarbtn">Voltar</button></a>
                    <button type="submit" class="btnConfirmar">Confirmar</button>
                </div>
                
            </form>
            
        </div>
    </div>
</body>
</html>

<?php
// Fechando a conexão com o banco de dados
$conn->close();
?>
