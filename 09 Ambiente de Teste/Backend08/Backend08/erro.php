<?php
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Obtendo a mensagem de erro
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Ocorreu um erro desconhecido.';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro na Requisição</title>
</head>
<body>
    <h1>Erro ao Enviar Requisição</h1>
    <p><?php echo $error_message; ?></p>

    <a href="formularioRequisicao.php">Voltar ao Formulário</a>
</body>
</html>
