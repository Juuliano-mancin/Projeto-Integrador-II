<?php
// Iniciando a sessão
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Coordenação</title>
    <!-- Importando o arquivo CSS externo -->
    <link rel="stylesheet" href="homepageCoordenacao.css">
</head>
<body>
    <!-- Header fixo no topo da página -->
    <header>
        <form action="logout.php" method="POST" style="float: right; margin: 10px;">
            <button type="submit">Logout</button>
        </form>
        Placeholder para o Header
    </header>

    <!-- Conteúdo principal da página -->
    <div class="principal">
        <!-- DivA contendo as subdivisões lado a lado -->
        <div class="divA">
            <!-- SubDiv1 - Exibe informações de login -->
            <div class="subDiv1">
                Logado como coordenador <br>
                <?php
                // Verificando se o nome e sobrenome estão definidos na sessão
                if (isset($_SESSION['nome']) && isset($_SESSION['sobrenome'])) {
                    echo "Bem vindo, " . htmlspecialchars($_SESSION['nome']) . " " . htmlspecialchars($_SESSION['sobrenome']) . "!";
                } else {
                    echo "Bem vindo!";
                }
                ?>
            </div>
            <!-- SubDiv2 - Botão "Nova Solicitação" com ícone -->
            <div class="subDiv2">
                <!-- Botão de nova solicitação pode ser adicionado aqui se necessário -->
            </div>
        </div>

        <!-- DivB - Exibe as solicitações realizadas e a tabela de dados -->
        <div class="divB">
            Solicitações realizadas
            <br><br>
            <!-- Input para filtrar os dados -->
            <input type="text" class="inputFiltro" placeholder="Filtrar dados...">
            <!-- Tabela para exibir as solicitações -->
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Professor</th>
                        <th>Curso</th>
                        <th>Disciplina</th>
                        <th>Status</th>
                        <th>Comentário</th>
                        <th>Visualizar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas de dados irão aqui -->
                </tbody>
            </table>
            <!-- Botão para imprimir o relatório, com ícone -->
            <button class="imprimirBtn">
                <img src="img/Icones/filePlaceholder.svg" alt="Imprimir" class="icon"> <!-- Ícone de imprimir -->
                Imprimir Relatório
            </button>
        </div>
    </div>
</body>
</html>
