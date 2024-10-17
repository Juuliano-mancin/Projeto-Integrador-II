<!DOCTYPE html>
<html lang="pt-BR"> <!-- Declaração do tipo de documento e idioma -->
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configuração responsiva -->
    <title>Login</title> <!-- Título da página -->
    <link rel="stylesheet" href="login.css"> <!-- Link para o arquivo CSS -->
</head>
<body>

    <div class="principal"> <!-- Div principal que contém todo o conteúdo do formulário -->
        <div class="login-text"> <!-- Div para o texto de login -->
            <p class="titulo">Faça Login</p> <!-- Título do formulário -->
            <p class="subtitulo">para continuar</p> <!-- Subtítulo do formulário -->
        </div>

        <br><br> <!-- Quebras de linha para espaçamento -->
        
        <!-- Início do código PHP -->
        <?php
        // Verificar se o formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Conectar ao banco de dados
            $servidor = "localhost"; // endereço do servidor MySQL
            $usuario_db = "root";     // nome de usuário do MySQL
            $senha_db = "";           // senha do MySQL
            $banco = "login_sistema"; // nome do banco de dados

            // Criar conexão
            $conn = new mysqli($servidor, $usuario_db, $senha_db, $banco);

            // Verificar conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Capturar os dados do formulário
            $login = $_POST['login'];
            $senha = $_POST['senha'];

            // Proteção contra SQL Injection
            $login = mysqli_real_escape_string($conn, $login);
            $senha = md5($senha); // Encriptar a senha

            // Query para verificar se o usuário existe
            $sql = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha'";
            $resultado = $conn->query($sql);


            if ($resultado->num_rows > 0) {
                // Se o login for bem-sucedido
               // echo "<p style='color: green;'>Login realizado com sucesso!</p>";
                header("Location: homepageProfessor.html");
                exit(); // Sempre use exit após redirecionar
            } else {
                // Se falhar
                echo "<p style='color: red;'>Login ou senha inválidos!</p>";
            }

            // Fechar conexão
            $conn->close();
        }
        ?>
        <!-- Fim do código PHP -->
        
        <form action="" method="POST"> <!-- Formulário com método POST -->
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

            <input type="submit" value="Fazer Login"> <!-- Botão de envio do formulário -->
        </form>           
        <br><br> <!-- Quebras de linha para espaçamento -->

        <p>Esqueceu sua senha?<br> <!-- Texto informativo sobre recuperação de senha -->
        Solicite uma nova senha <a href="novasenha.html">aqui</a>!</p> <!-- Link para solicitar nova senha -->
    </div>
<!-- ('usuario_teste', MD5('senha_teste')) -->
</body>
</html>
