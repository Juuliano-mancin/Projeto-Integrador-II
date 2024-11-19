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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Alterar Status</title>
    <link rel="stylesheet" href="statusCoordenacao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Alterar Status</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
            <div class="divA_2">
           
            </div>
        </div>
    </header>

    <br>

    <div class="principal">
        <div class="divA1">
            <div class="subA1">
                <h1>Alterar Status da Requisição ID: <?php echo htmlspecialchars($idRequisicao); ?></h1>
            </div>
        </div>

        <div class="divB">
            <div class="subB1">
                <form action="" method="POST">
                    <div class="sub-Forms1">
                        <label for="status">Status:</label>
                        <select name="status">
                            <option value="aprovado">APROVADO</option>
                            <option value="reprovado">REPROVADO</option>
                        </select>
                    </div>
                    <br><br>
                    <div class="sub-Forms1">
                        <label for="comentario">Comentário:</label>
                        <textarea name="comentario" placeholder="COMENTÁRIO"></textarea>
                    </div>
                    <br><br>
                    <div class="btn">
                        <div class="btn1">
                            <a href="homepageCoordenacao.php"> <button class="btn-voltar">Voltar<button</a>
                        </div>

                        <div class="btn2">
                            <button type="submit" class="btn-atualizar">Atualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
