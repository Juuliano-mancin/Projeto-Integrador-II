<?php
session_start();

// Verificando se o coordenador está logado
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

// Obtendo o ID da requisição
$idRequisicao = $_GET['idrequisicao'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['status'];
    $comentario = $_POST['comentario'];

    // Atualizando o status e o comentário da requisição
    $sql = "UPDATE tb_requisicao_faltas SET statusrequisicao = ?, comentariocoordenacao = ? WHERE idrequisicao = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$status, $comentario, $idRequisicao]);

    // Redirecionando de volta para a página principal
    header("Location: homepageCoordenacao.php");
    exit();
}

// Obtendo os dados da requisição para exibir
$sql = "SELECT * FROM tb_requisicao_faltas WHERE idrequisicao = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idRequisicao]);
$requisicao = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Status</title>
    <link rel="stylesheet" href="homepageCoordenacao.css">
</head>
<body>
    <header>
        <form action="logout.php" method="POST" style="float: right; margin: 10px;">
            <button type="submit">Logout</button>
        </form>
        Placeholder para o Header
    </header>

    <br>

    <div class="principal">
        <h2>Alterar Status da Requisição ID: <?php echo htmlspecialchars($idRequisicao); ?></h2>
        <form action="" method="POST">
            <label for="status">Status:</label>
            <select name="status">
                <option value="aprovado">APROVADO</option>
                <option value="reprovado">REPROVADO</option>
            </select>
            <br><br>
            <label for="comentario">Comentário:</label>
            <textarea name="comentario" placeholder="COMENTÁRIO"></textarea>
            <br><br>
            <button type="submit">Atualizar</button>
        </form>
    </div>
</body>
</html>
