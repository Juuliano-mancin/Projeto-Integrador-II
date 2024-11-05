<?php
// Definindo as credenciais do banco de dados
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "justificativafaltas";

// Criando a conexão
$conexao = new mysqli($servidor, $usuario, $senha, $banco);

// Verificando se houve erro na conexão
if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

// Se tudo estiver correto, a conexão está pronta para ser usada
?>
