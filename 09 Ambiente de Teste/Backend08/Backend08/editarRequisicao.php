<?php
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=justificativafaltas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}

// Verificando se o ID da requisição foi passado via GET
if (!isset($_GET['id'])) {
    echo "ID da requisição não fornecido.";
    exit();
}

$idRequisicao = $_GET['id'];

// Consultando a requisição no banco de dados
$stmt = $pdo->prepare("SELECT * FROM tb_requisicao_faltas WHERE idrequisicao = :idrequisicao");
$stmt->execute(['idrequisicao' => $idRequisicao]);
$requisicao = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificando se a requisição foi encontrada
if (!$requisicao) {
    echo "Requisição não encontrada.";
    exit();
}

// Processando o envio do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizando os dados da requisição
    $data_inicial = $_POST['data_inicial'];
    $data_final = $_POST['data_final'];
    $idcategoria = $_POST['idcategoria'];
    $idjustificativa = $_POST['idjustificativa'];
    $comentarioprofessor = $_POST['comentarioprofessor'];

    $stmt = $pdo->prepare("UPDATE tb_requisicao_faltas SET data_inicial = :data_inicial, data_final = :data_final, idcategoria = :idcategoria, idjustificativa = :idjustificativa, comentarioprofessor = :comentarioprofessor WHERE idrequisicao = :idrequisicao");
    $stmt->execute([
        'data_inicial' => $data_inicial,
        'data_final' => $data_final,
        'idcategoria' => $idcategoria,
        'idjustificativa' => $idjustificativa,
        'comentarioprofessor' => $comentarioprofessor,
        'idrequisicao' => $idRequisicao
    ]);

    echo "Requisição atualizada com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Requisição</title>
</head>
<body>
    <header>
        <h1>Editar Requisição</h1>
    </header>

    <form action="" method="POST">
        <label for="data_inicial">Data Inicial:</label>
        <input type="date" name="data_inicial" value="<?php echo htmlspecialchars($requisicao['data_inicial']); ?>"><br>

        <label for="data_final">Data Final:</label>
        <input type="date" name="data_final" value="<?php echo htmlspecialchars($requisicao['data_final']); ?>"><br>

        <label for="idcategoria">Categoria:</label>
        <select name="idcategoria">
            <?php
            // Consultando as categorias disponíveis
            $stmt = $pdo->query("SELECT * FROM tb_categoria_faltas");
            while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $categoria['idcategoria'] . '"' . ($categoria['idcategoria'] == $requisicao['idcategoria'] ? ' selected' : '') . '>' . htmlspecialchars($categoria['categoria']) . '</option>';
            }
            ?>
        </select><br>

        <label for="idjustificativa">Justificativa:</label>
        <select name="idjustificativa">
            <?php
            // Consultando as justificativas disponíveis
            $stmt = $pdo->query("SELECT * FROM tb_justificativa_faltas");
            while ($justificativa = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $justificativa['idjustificativa'] . '"' . ($justificativa['idjustificativa'] == $requisicao['idjustificativa'] ? ' selected' : '') . '>' . htmlspecialchars($justificativa['justificativa']) . '</option>';
            }
            ?>
        </select><br>

        <label for="comentarioprofessor">Comentário do Professor:</label>
        <textarea name="comentarioprofessor"><?php echo htmlspecialchars($requisicao['comentarioprofessor']); ?></textarea><br>

        <button type="submit">Atualizar Requisição</button>
    </form>

    <br>
    <button onclick="window.location.href='homepageProfessor.php'">Voltar</button>
</body>
</html>
