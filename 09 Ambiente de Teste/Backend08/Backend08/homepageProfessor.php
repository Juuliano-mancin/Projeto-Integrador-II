<?php
// Iniciando a sessão
session_start();

// Obtendo o ID do professor logado
$idProfessor = $_SESSION['idprofessor'];
// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=justificativafaltas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
    exit();
}



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Professor</title>
    <link rel="stylesheet" href="homepageProfessor.css">
    <script>
        function filtrarDados() {
            const input = document.getElementById('filtroInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('solicitacoesTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const tds = tr[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < tds.length; j++) {
                    if (tds[j]) {
                        const txtValue = tds[j].textContent || tds[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                tr[i].style.display = match ? '' : 'none';
            }
        }
    </script>
</head>
<body>
    <header>
        <form action="logout.php" method="POST" style="float: right; margin: 10px;">
            <button type="submit">Logout</button>
        </form>
        Placeholder para o Header
    </header>

    <br>

    <div class="principal">
        <div class="divA">
            <div class="subDiv1">
                Logado como professor <br>
                <?php
                echo "Bem vindo, " . htmlspecialchars($_SESSION['nome']) . " " . htmlspecialchars($_SESSION['sobrenome']) . "!";
                ?>
            </div>
            <div class="subDiv2">
                <a href="formularioRequisicao.php">
                    <button class="imprimirBtn"> 
                        <img src="img/icones/NovaSolicitacao.svg" alt="Adicionar" class="icon"> 
                        Nova Solicitação
                    </button>
                </a>
            </div>
            <div class="subDiv3" style="display: inline-block; margin-left: 10px;">
                <a href="formularioReposicao.php">
                    <button class="imprimirBtn"> 
                        <img src="img/icones/reposicao.svg" alt="Reposição" class="icon"> 
                        Reposição de Faltas
                    </button>
                </a>
            </div>
        </div>

        <div class="divB">
            Solicitações realizadas
            <br><br>
            <input type="text" id="filtroInput" class="inputFiltro" placeholder="Filtrar dados..." onkeyup="filtrarDados()">
            <table id="solicitacoesTable">
                <thead>
                    <tr>
                        <th>ID Requisição</th>
                        <th>Sigla do Curso</th>
                        <th>Categoria</th>
                        <th>Data da Falta</th>
                        <th>Status</th>
                        <th>Comentário Coordenação</th>
                        <th>Alterar</th>
                        <th>Consulta</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Consulta SQL para buscar as requisições do professor logado
                    $sql = "SELECT r.idrequisicao, c.siglacurso AS curso, cf.categoria, 
                                   DATE_FORMAT(r.data_inicial, '%d %m %Y') AS data_falta, 
                                   r.statusrequisicao, r.comentariocoordenacao
                            FROM tb_requisicao_faltas r
                            JOIN tb_cursos c ON r.idcurso = c.idcurso
                            JOIN tb_categoria_faltas cf ON r.idcategoria = cf.idcategoria
                            WHERE r.idprofessor = ?";

                    // Preparar e executar a consulta
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$idProfessor]);

                    // Loop para exibir os dados na tabela
                    while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($linha['idrequisicao']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['curso']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['categoria']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['data_falta']) . "</td>"; // Exibindo a data formatada
                        echo "<td>" . htmlspecialchars($linha['statusrequisicao']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['comentariocoordenacao'] ?? ' ') . "</td>"; // Máscara para NULL
                        echo "<td><a href='editarRequisicao.php?id=" . htmlspecialchars($linha['idrequisicao']) . "'>Alterar</a></td>"; // Link para alterar
                        echo "<td><a href='consultaRequisicao.php?idrequisicao=" . htmlspecialchars($linha['idrequisicao']) . "'>Consultar</a></td>"; // Link para consulta
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <button class="imprimirBtn">
                <img src="img/Icones/arquivoPDF.svg" alt="Imprimir" class="icon"> 
                Imprimir Relatório
            </button>
        </div>
    </div>
</body>
</html>
