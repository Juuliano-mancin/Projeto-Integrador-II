<?php
// Iniciando a sessão
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

// Obtendo o ID da requisição via GET
$idRequisicao = isset($_GET['idrequisicao']) ? intval($_GET['idrequisicao']) : 0;

// Consultar a requisição no banco de dados
$sql = "SELECT r.idrequisicao, c.siglacurso AS curso, cf.categoria, 
               DATE_FORMAT(r.data_inicial, '%d %m %Y') AS data_inicial,
               DATE_FORMAT(r.data_final, '%d %m %Y') AS data_final,
               r.comentarioprofessor, r.statusrequisicao, r.comentariocoordenacao
        FROM tb_requisicao_faltas r
        JOIN tb_cursos c ON r.idcurso = c.idcurso
        JOIN tb_categoria_faltas cf ON r.idcategoria = cf.idcategoria
        WHERE r.idrequisicao = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idRequisicao]);
$linha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$linha) {
    echo "<h2>Requisição não encontrada.</h2>";
    echo '<a href="homepageProfessor.php"><button>Voltar</button></a>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Requisição</title>
    <link rel="stylesheet" href="consultaRequisicao.css">
</head>
<body>
    <header>
        <form action="logout.php" method="POST" style="float: right; margin: 10px;">
            <button type="submit">Logout</button>
        </form>
        <h1>Consulta de Requisição</h1>
    </header>

    <main class="principal">
        <h2>Detalhes da Requisição</h2>
        <table>
            <tr>
                <th>ID da Requisição</th>
                <td><?php echo htmlspecialchars($linha['idrequisicao']); ?></td>
            </tr>
            <tr>
                <th>Sigla do Curso</th>
                <td><?php echo htmlspecialchars($linha['curso']); ?></td>
            </tr>
            <tr>
                <th>Categoria</th>
                <td><?php echo htmlspecialchars($linha['categoria']); ?></td>
            </tr>
            <tr>
                <th>Data Inicial</th>
                <td><?php echo htmlspecialchars($linha['data_inicial']); ?></td>
            </tr>
            <tr>
                <th>Data Final</th>
                <td><?php echo htmlspecialchars($linha['data_final']); ?></td>
            </tr>
            <tr>
                <th>Comentário do Professor</th>
                <td><?php echo nl2br(htmlspecialchars($linha['comentarioprofessor'])); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($linha['statusrequisicao']); ?></td>
            </tr>
            <tr>
                <th>Comentário da Coordenação</th>
                <td><?php echo nl2br(htmlspecialchars($linha['comentariocoordenacao'])); ?></td>
            </tr>
        </table>

        <div class="button-container">
            <a href="homepageCoordenacao.php">
                <button>Voltar</button>
            </a>
        </div>
    </main>
</body>
</html>
