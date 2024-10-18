<?php
// Iniciando a sessão
session_start();

// Incluindo o arquivo de conexão
include 'conexao.php';

// Verificando se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturando os dados do formulário
    $matricula = $_POST['login'];
    $senha = $_POST['senha'];

    // Preparando a consulta para evitar SQL Injection
    $stmt = $conexao->prepare("SELECT nome, sobrenome, função FROM tb_professores WHERE matricula = ? AND senha = ?");
    $stmt->bind_param("is", $matricula, $senha);
    $stmt->execute();
    $stmt->store_result();

    // Verificando se o usuário existe
    if ($stmt->num_rows > 0) {
        // Armazenando o nome, sobrenome e a função do usuário
        $stmt->bind_result($nomeDoProfessor, $sobrenomeDoProfessor, $funcao);
        $stmt->fetch();

        // Armazenando nome e sobrenome na sessão
        $_SESSION['nome'] = $nomeDoProfessor;
        $_SESSION['sobrenome'] = $sobrenomeDoProfessor;

        // Redirecionando conforme a função do usuário
        switch ($funcao) {
            case 'professor':
                header("Location: HomepageProfessor.php");
                break;
            case 'administrativo':
                header("Location: HomepageAdministrativo.php");
                break;
            case 'coordenacao':
                header("Location: HomepageCoordenacao.php");
                break;
        }
        
        exit(); // Encerrando o script após o redirecionamento
    } else {
        echo "<script>alert('Matrícula ou senha inválidos.');</script>";
    }

    $stmt->close(); // Fechando a declaração
}

$conexao->close(); // Fechando a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR"> <!-- Declaração do tipo de documento e idioma -->
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configuração responsiva -->
    <title>Login</title> <!-- Título da página -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Roboto+Slab:wght@100;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="login.css"> <!-- Link para o arquivo CSS -->
</head>
<body>

    <div class="principal"> <!-- Div principal que contém todo o conteúdo do formulário -->
        <div class="login-text"> <!-- Div para o texto de login -->
            <p class="titulo">Faça Login</p> <!-- Título do formulário -->
            <p class="subtitulo">para continuar</p> <!-- Subtítulo do formulário -->
        </div>

        <br><br> <!-- Quebras de linha para espaçamento -->
        <form action="#" method="POST"> <!-- Formulário com método POST -->
            <label for="login"> <!-- Rótulo para o campo de login -->
                <img src="img/Icones/Login.svg" alt="Ícone de Login" class="icon"> <!-- Ícone de login -->
                Login <!-- Texto do rótulo -->
            </label><br>
            <input type="text" id="login" name="login" placeholder="Matrícula" required><br><br> <!-- Campo de entrada para matrícula -->

            <label for="senha"> <!-- Rótulo para o campo de senha -->
                <img src="img/Icones/Password.svg" alt="Ícone de Senha" class="icon"> <!-- Ícone de senha -->
                Senha <!-- Texto do rótulo -->
            </label><br>
            <input type="password" id="senha" name="senha" placeholder="Senha" required><br><br> <!-- Campo de entrada para senha -->

            <input type="submit" value="Login"> <!-- Botão de envio do formulário -->
        </form>           
        <br><br> <!-- Quebras de linha para espaçamento -->

        <p>Esqueceu sua senha?<br> <!-- Texto informativo sobre recuperação de senha -->
        Solicite uma nova senha <a href="novasenha.html">aqui</a>!</p> <!-- Link para solicitar nova senha -->
    </div>

</body>
</html>
