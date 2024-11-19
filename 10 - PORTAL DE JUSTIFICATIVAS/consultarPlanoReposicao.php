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

// Pegando o idrequisicao da URL
$idrequisicao = $_GET['idrequisicao'] ?? null;

if (!$idrequisicao) {
    echo "ID de requisição não fornecido.";
    exit();
}

// Consultando as reposições associadas a essa requisição
$sql = "SELECT r.idreposicao, r.datareposicao, r.tiporeposicao, r.numeroaulas, 
               r.horainicioreposicao, r.horafinalreposicao, d.disciplina, c.curso,
               r.statusreposicao
        FROM tb_reposicao_aulas r
        JOIN tb_disciplinas d ON r.iddisciplina = d.iddisciplina
        JOIN tb_cursos c ON r.idcurso = c.idcurso
        WHERE r.idrequisicao = :idrequisicao";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':idrequisicao', $idrequisicao, PDO::PARAM_INT);
$stmt->execute();

$reposicoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plano de Reposição</title>
    <link rel="stylesheet" href="consulta_plano.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Consulta de Reposição</h3>
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
            <h1 class="text">Detalhes da Reposição</h1>
        </div>
        <div class="divB">
            <div class="divB1">
                <h2>Plano de Reposição para a Requisição ID: <?php echo htmlspecialchars($idrequisicao); ?></h2>
                <div class="divB2">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Reposição</th>
                                <th>Data da Reposição</th>
                                <th>Tipo de Reposição</th>
                                <th>Número de Aulas</th>
                                <th>Horário da Reposição</th>
                                <th>Disciplina</th>
                                <th>Curso</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Exibindo os dados da reposição
                            if ($reposicoes) {
                                foreach ($reposicoes as $reposicao) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($reposicao['idreposicao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['datareposicao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['tiporeposicao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['numeroaulas']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['horainicioreposicao']) . " - " . htmlspecialchars($reposicao['horafinalreposicao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['disciplina']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['curso']) . "</td>";
                                    echo "<td>" . htmlspecialchars($reposicao['statusreposicao']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9'>Nenhuma reposição encontrada para esta requisição.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="button-container">
                    <a href="homepageCoordenacao.php">Voltar</a>
                </div>
            </div>
        </div>
    </div>
    <div class="foot"></div>
</body>
</html>
