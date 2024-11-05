<?php
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso na Requisição</title>
</head>
<body>
    <h1>Requisição Enviada com Sucesso!</h1>
    <p>Sua solicitação foi registrada e está pendente de aprovação.</p>

    <a href="homepageProfessor.php">Voltar ao home do Professor</a>
</body>
</html>
