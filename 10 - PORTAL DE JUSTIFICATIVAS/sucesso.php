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
    <link rel="stylesheet" href="forms_repo.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Requisição de Faltas e Reposição de Aulas</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
        </div>
    </header>

    <div class="principal_sucesso">
        <div class="divA1">
            <h1>Requisição Enviada com Sucesso!</h1>
        </div>
        <div class="divB_sucesso">
            <p>Sua solicitação foi registrada e está <strong>pendente de aprovação</strong>.</p>
        </div>
        <div class="button-container_sucesso">
            <a href="formularioReposicao.php">Iniciar plano de Reposição de Aulas</a>
        </div>
    </div>
</body>
</html>
