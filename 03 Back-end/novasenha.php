<!DOCTYPE html>
<html lang="pt-BR"> <!-- Declaração do tipo de documento e idioma -->
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configuração responsiva -->
    <title>Solicitar Nova Senha</title> <!-- Título da página -->
    <link rel="stylesheet" href="novasenha.css"> <!-- Link para o arquivo CSS -->
</head>
<body>

    <!-- Div principal que contém todo o conteúdo do formulário -->
    <div class="principal"> 
        <!-- Div para o texto de solicitação -->
        <div class="login-text"> 
            <p class="titulo">Solicitar Nova Senha</p> <!-- Título do formulário -->
            <p class="subtitulo">para continuar</p> <!-- Subtítulo do formulário -->
        </div>

        <br><br> <!-- Quebras de linha para espaçamento -->

        <?php
        // Verificar se o formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Conectar ao banco de dados
            $servidor = "localhost"; 
            $usuario_db = "root";     
            $senha_db = "";           
            $banco = "login_sistema"; 

            // Criar conexão
            $conn = new mysqli($servidor, $usuario_db, $senha_db, $banco);

            // Verificar conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Capturar os dados do formulário
            $nova_senha = $_POST['nova-senha'];
            $confirmar_senha = $_POST['confirmar-senha'];

            // Verificar se as senhas são iguais
            if ($nova_senha === $confirmar_senha) {
                // Proteção contra SQL Injection e criptografia de senha
                $nova_senha = md5(mysqli_real_escape_string($conn, $nova_senha));

                // Aqui você precisa capturar o login do usuário para identificar qual senha será alterada
                // Exemplo: $login = $_POST['login']; // Ou uma variável de sessão, dependendo da lógica de login
                $login = 'usuario_teste'; // Substitua isso pelo valor correto

                // Atualizar a senha no banco de dados
                $sql = "UPDATE usuarios SET senha = '$nova_senha' WHERE login = '$login'";
                if ($conn->query($sql) === TRUE) {
                    echo "<p style='color: green;'>Senha atualizada com sucesso!</p>";
                } else {
                    echo "<p style='color: red;'>Erro ao atualizar a senha: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color: red;'>As senhas não coincidem. Tente novamente.</p>";
            }

            // Fechar conexão
            $conn->close();
        }
        ?>

        <!-- Formulário com método POST -->
        <form action="novasenha.php" method="POST"> 
            <!-- Rótulo para o campo de nova senha -->
            <label for="nova-senha"> 
                <img src="img/Icones/Novasenha.svg" alt="Ícone de Nova Senha" class="icon"> <!-- Ícone de nova senha -->
                Nova Senha <!-- Texto do rótulo -->
            </label><br>
            <!-- Campo de entrada para nova senha -->
            <input type="password" id="nova-senha" name="nova-senha" placeholder="Nova Senha" required><br><br> 

            <!-- Rótulo para o campo de confirmar senha -->
            <label for="confirmar-senha"> 
                <img src="img/Icones/Novasenha.svg" alt="Ícone de Confirmar Senha" class="icon"> <!-- Ícone de confirmação -->
                Confirmar Senha <!-- Texto do rótulo -->
            </label><br>
            <!-- Campo de entrada para confirmar senha -->
            <input type="password" id="confirmar-senha" name="confirmar-senha" placeholder="Confirmar Senha" required><br><br> 

            <!-- Botão de envio do formulário -->
            <input type="submit" value="Solicitar Nova Senha"> 
        </form>           
        
        <br><br> <!-- Quebras de linha para espaçamento -->

        <!-- Link para voltar ao login -->
        <p>Voltar para o <a href="login.html">login</a>.</p> 
    </div>

</body>
</html>
