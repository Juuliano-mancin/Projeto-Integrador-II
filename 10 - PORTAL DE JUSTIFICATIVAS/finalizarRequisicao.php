<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome'])) {
    header("Location: login.php");
    exit();
}

// Verifique se o ID da requisição foi enviado via POST
if (isset($_POST['idrequisicao'])) {
    $idRequisicao = $_POST['idrequisicao'];

    try {
        // Conexão com o banco de dados
        $pdo = new PDO('mysql:host=localhost;dbname=justificativafaltas', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Atualizar o status da requisição para "finalizado"
        $sql = "UPDATE tb_requisicao_faltas SET statusrequisicao = 'finalizado' WHERE idrequisicao = :idrequisicao";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idrequisicao', $idRequisicao, PDO::PARAM_INT);

        // Executa a atualização
        if ($stmt->execute()) {
            // Redireciona de volta para a página administrativa após a atualização
            header("Location: homepageAdministrativo.php");
            exit();
        } else {
            echo "Erro ao atualizar a requisição.";
        }

    } catch (PDOException $e) {
        echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        exit();
    }
} else {
    echo "ID da requisição não fornecido.";
}
?>
