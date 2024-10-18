<?php
// Iniciando a sessão
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Administrativo</title>
    <link rel="stylesheet" href="homepageAdministrativo.css">
</head>
<body>
    <header>
        <form action="logout.php" method="POST" style="float: right; margin: 10px;">
            <button type="submit">Logout</button>
        </form>
        Placeholder para o Header
    </header>

    <div class="principal">
        <div class="divA">
            <div class="subDiv1">
                Logado como administrativo <br>
                <?php
                // Verificando se o nome está definido na sessão
                if (isset($_SESSION['nome'])) {
                    echo "Bem vindo, " . htmlspecialchars($_SESSION['nome']) . "!";
                } else {
                    echo "Bem vindo!";
                }
                ?>
            </div>
            <div class="subDiv2">
                <!-- Outros elementos podem ser adicionados aqui -->
            </div>
        </div>

        <div class="divB">
            Solicitações realizadas
            <br><br>
            <input type="text" class="inputFiltro" placeholder="Filtrar dados...">
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Professor</th>
                        <th>Curso</th>
                        <th>Disciplina</th>
                        <th>Status</th>
                        <th>Comentário Coordenação</th>
                        <th>Finalizar</th>
                        <th>Visualizar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- As linhas de dados irão aqui -->
                </tbody>
            </table>
            <button class="imprimirBtn">
                <img src="img/Icones/filePlaceholder.svg" alt="Imprimir" class="icon"> <!-- Ícone de imprimir -->
                Imprimir Relatório
            </button>
        </div>
    </div>
</body>
</html>
