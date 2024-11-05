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

// Verificando se o ID da reposição foi enviado
if (isset($_POST['idreposicao'])) {
    $idReposicao = $_POST['idreposicao'];

    // Atualizando o status da reposição
    $sqlUpdate = "UPDATE tb_reposicao_aulas SET statusreposicao = 'finalizada' WHERE idreposicao = ?";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    try {
        $stmtUpdate->execute([$idReposicao]);
        // Redireciona após a atualização
        header("Location: formularioReposicao.php"); // ou para a página desejada
        exit();
    } catch (PDOException $e) {
        echo "Erro ao atualizar o status: " . $e->getMessage();
    }
} else {
    echo "ID da reposição não enviado.";
}
?>
