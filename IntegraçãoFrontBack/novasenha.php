<?php
// Ativar a exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciando a sessão
session_start();

// Incluindo o arquivo de conexão
include 'conexao.php';

// Mensagem de feedback
$mensagem = "";

// Verificando se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturando a matrícula e nova senha do formulário
    $matricula = $_POST['matricula'];
    $nova_senha = $_POST['nova-senha'];
    $confirmar_senha = $_POST['confirmar-senha'];

    // Verificando se as senhas são iguais
    if ($nova_senha === $confirmar_senha) {
        // Atualizando a senha no banco de dados
        $stmt = $conexao->prepare("UPDATE tb_professores SET senha = ? WHERE matricula = ?");
        $stmt->bind_param("si", $nova_senha, $matricula); // Mudei para armazenar a senha em texto claro

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $mensagem = "Senha atualizada com sucesso!";
            } else {
                $mensagem = "Matrícula não encontrada ou a senha não foi alterada.";
            }
        } else {
            $mensagem = "Erro ao executar a atualização: " . $stmt->error;
        }

        $stmt->close(); // Fechando a declaração
    } else {
        $mensagem = "As senhas não coincidem. Tente novamente.";
    }
}

$conexao->close(); // Fechando a conexão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Nova Senha</title>
    <link rel="stylesheet" href="novasenha.css"> <!-- Link para o arquivo CSS -->
</head>
<body>

<div class="principal">
    <div class="login-text">
        <p class="titulo">Solicitar Nova Senha</p>
        <p class="subtitulo">Digite sua matrícula e a nova senha</p>
    </div>

    <?php if ($mensagem): ?>
        <p><?php echo $mensagem; ?></p>
    <?php endif; ?>

    <form action="novasenha.php" method="POST">
        <label for="matricula">Matrícula</label><br>
        <input type="text" id="matricula" name="matricula" placeholder="Digite sua matrícula" required><br><br>

        <label for="nova-senha">Nova Senha</label><br>
        <input type="password" id="nova-senha" name="nova-senha" placeholder="Nova Senha" required><br><br>

        <label for="confirmar-senha">Confirmar Senha</label><br>
        <input type="password" id="confirmar-senha" name="confirmar-senha" placeholder="Confirmar Senha" required><br><br>

        <input type="submit" value="Atualizar Senha">
    </form>

    <p>Voltar para o <a href="login.php">login</a>.</p>
</div>

</body>
</html>
