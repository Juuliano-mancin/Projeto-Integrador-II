<?php
// Iniciando a sessão
session_start();

// Verificando se o coordenador está logado
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

// Obtendo as requisições dos professores sem duplicar
$sql = "SELECT r.idrequisicao, c.siglacurso AS curso, cf.categoria, 
               DATE_FORMAT(r.data_inicial, '%d %m %Y') AS data_falta, 
               r.statusrequisicao, r.comentariocoordenacao, p.nome AS professor
        FROM tb_requisicao_faltas r
        JOIN tb_cursos c ON r.idcurso = c.idcurso
        JOIN tb_categoria_faltas cf ON r.idcategoria = cf.idcategoria
        JOIN tb_professores p ON r.idprofessor = p.idprofessor";

$stmt = $pdo->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Coordenador</title>
    <link rel="stylesheet" href="homepageCoordenacao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
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
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Coordenação</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
            <div class="divA_2">
           
            </div>
        </div>
    </header>

    <div class="principal">
        <div class="divA1">
            <div class="subA1">
            <?php
                echo "Bem-vindo(a), " . htmlspecialchars($_SESSION['nome']) . " " . htmlspecialchars($_SESSION['sobrenome']);
                ?>
            </div>
            <div class="subA2">
            <form action="logout.php" method="POST" style="float: right; margin: 10px;">
                    <button type="submit" class="Sairbtn">
                        SAIR
                    </button>
                </form>
            </div>
        </div>

        <div class="divB">
            <div class="subB1">
                <p>Solicitações dos Professores</p>
                <input type="text" id="filtroInput" class="inputFiltro" placeholder="Filtrar dados..." onkeyup="filtrarDados()">
            </div>

            <div class="subB2">
                <table id="solicitacoesTable">
                    <thead>
                        <tr>
                            <th>Professor</th>
                            <th>Curso</th>
                            <th>Categoria</th>
                            <th>Data da Falta</th>
                            <th>Status</th>
                            <th>Comentário</th>
                            <th>Ações</th>
                            <th>Plano de Reposição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop para exibir as requisições na tabela
                        while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Verificando se o status da requisição é "aprovado"
                            $status = htmlspecialchars($linha['statusrequisicao']);
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($linha['professor']) . "</td>";
                            echo "<td>" . htmlspecialchars($linha['curso']) . "</td>";
                            echo "<td>" . htmlspecialchars($linha['categoria']) . "</td>";
                            echo "<td>" . htmlspecialchars($linha['data_falta']) . "</td>";
                            echo "<td>" . htmlspecialchars($status) . "</td>";
                            echo "<td>" . htmlspecialchars($linha['comentariocoordenacao'] ?? ' ') . "</td>";

                            // Coluna "Ações" combinando Alterar e Consultar Requisição
                            echo "<td>";
                            if ($status !== 'aprovado') {
                                echo "<form action='alterarStatusCoordenacao.php' method='GET' style='display: inline;'>
                                        <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                        <button type='submit' style='all: unset; cursor: pointer;' class='btnEditar'><img src='img/Icones/editar_A2.svg' alt='Editar' class = 'icon'></button>
                                    </form>";
                            }
                            echo "<form action='consultarRequisicaoPronta.php' method='GET' style='display: inline;'>
                                    <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                    <input type='hidden' name='origem' value='coordenador'>
                                    <button type='submit' style='all: unset; cursor: pointer;' class='btnConsultar'><img src='img/Icones/eye2.svg' alt='Consultar' class = 'icon'></button>
                                </form>";
                            echo "</td>";

                            // Link para o "Plano de Reposição" - passando o idrequisicao
                            echo "<td>
                                    <form action='consultarPlanoReposicao.php' method='GET'>
                                        <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                        <button type='submit' style='all: unset; cursor: pointer;'><img src='img/Icones/reposicao.svg' alt='Reposição' class = 'icon'></button>
                                    </form>
                                </td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
