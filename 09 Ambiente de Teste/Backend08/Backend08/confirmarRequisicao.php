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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação da Requisição</title>
</head>
<body>
    <h1>Confirmação da Requisição</h1>
    
    <p><strong>Logado como:</strong> <?php echo htmlspecialchars($_SESSION['nome']) . ' ' . htmlspecialchars($_SESSION['sobrenome']); ?></p>
    <p><strong>Matrícula:</strong> <?php echo htmlspecialchars($_SESSION['matricula']); ?></p>
    
    <h2>Dados da Requisição</h2>
    <p><strong>Curso:</strong> <?php echo $curso_id . ' - ' . htmlspecialchars($curso_texto); ?></p>
    <p><strong>Data Inicial:</strong> <?php echo $data_inicial; ?></p>
    <p><strong>Data Final:</strong> <?php echo $data_final; ?></p>
    <p><strong>Categoria:</strong> <?php echo $categoria_id . ' - ' . htmlspecialchars($categoria_texto); ?></p>
    <p><strong>Justificativa:</strong> <?php echo $justificativa_id . ' - ' . htmlspecialchars($justificativa_texto); ?></p>
    <p><strong>Comentários:</strong> <?php echo $comentarios; ?></p>

    <!-- Formulário para confirmar ou cancelar o envio -->
    <form action="processarRequisicao.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="curso" value="<?php echo $curso_id; ?>">
        <input type="hidden" name="data_inicial" value="<?php echo $data_inicial; ?>">
        <input type="hidden" name="data_final" value="<?php echo $data_final; ?>">
        <input type="hidden" name="categoria" value="<?php echo $categoria_id; ?>">
        <input type="hidden" name="justificativa" value="<?php echo $justificativa_id; ?>">
        <input type="hidden" name="comentarios" value="<?php echo $comentarios; ?>">

        <!-- Campo para Upload de Arquivo -->
        <h2>Upload de Arquivo</h2>
        <label for="arquivos">Selecione um ou mais arquivos:</label>
        <input type="file" name="arquivos[]" id="arquivos" multiple>

        <button type="submit">Confirmar</button>
    </form>

    <a href="formularioRequisicao.php">Voltar ao Formulário</a>
</body>
</html>

<?php
// Fechando a conexão com o banco de dados
$conn->close();
?>
