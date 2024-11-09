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
    <title>Homepage Coordenação</title>
    <!-- Importando o arquivo CSS externo -->
    <link rel="stylesheet" href="homepageCoordenacao.css">
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
    <!-- Header fixo no topo da página -->
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

    <!-- Conteúdo principal da página -->
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
                    <p>Solicitações realizadas</p>
                    <input type="text" id="filtroInput" class="inputFiltro" placeholder="Filtrar dados..." onkeyup="filtrarDados()">
                </div>
                <div class="subB2">
                    <form>
                        
                        <table>
                            <thead>
                                <tr>
                                <th>Professor</th>
                                <th>Curso</th>
                                <th>Categoria</th>
                                <th>Data da Falta</th>
                                <th>Status</th>
                                <th>Comentário</th>
                                <th>Alterar</th>
                                <th>Consultar</th>
                                <th>Plano de Reposição</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Loop para exibir as requisições na tabela
                                while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($linha['professor']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['curso']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['categoria']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['data_falta']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['statusrequisicao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['comentariocoordenacao'] ?? ' ') . "</td>";
                                    
                                    // Botão para alterar status
                                    echo "<td>
                                            <form action='alterarStatusCoordenacao.php' method='GET'>
                                                <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                                <button type='submit'>Alterar</button>
                                            </form>
                                        </td>";
                                    
                                    // Botão para consultar requisição com o parâmetro de origem "coordenador"
                                    echo "<td>
                                            <form action='consultarRequisicaoPronta.php' method='GET'>
                                                <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                                <input type='hidden' name='origem' value='coordenador'>
                                                <button type='submit'>Consultar</button>
                                            </form>
                                        </td>";

                                    // Link para o "Plano de Reposição" - passando o idrequisicao
                                    echo "<td>
                                            <form action='consultarPlanoReposicao.php' method='GET'>
                                                <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                                <button type='submit'>Plano de Reposição</button>
                                            </form>
                                        </td>";

                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                       
                    </form>
                </div>
            
        </div>
    </div>
</body>
</html>