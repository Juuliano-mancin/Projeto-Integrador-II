<?php
// Iniciando a sessão
session_start();

// Removendo todas as variáveis de sessão
$_SESSION = array();

// Se desejar, você pode destruir a sessão também
session_destroy();

// Redirecionando para a página de login
header("Location: login.html");
exit();
?>
