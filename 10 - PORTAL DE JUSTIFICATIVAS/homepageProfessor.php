<?php
// Iniciando a sessão
session_start();

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

// Obtendo o ID do professor logado
$idProfessor = $_SESSION['idprofessor'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Homepage</title>
    <link rel="stylesheet" href="homepageProf.css">
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
    <STYLE>A {text-decoration: none;} </STYLE>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Professor</h3>
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
                    <div class="subB1a">
                        <a href="formularioRequisicao.php">
                            <button class="solicitacaoBtn"> 
                                <img src="img/icones/plus_azul.svg" alt="Adicionar" class="icon"> <!-- Ícone de adicionar --> 
                                Nova Solicitação
                            </button>
                        </a>
                    </div>
                    <div class="subB1a">
                        
                    </div>
                    <input type="text" id="filtroInput" class="inputFiltro" placeholder="Filtrar dados..." onkeyup="filtrarDados()">
                </div>
                <div class="subB2">
                    <form>
                        
                        <table>
                            <thead>
                                <tr>
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
                                    echo "<td>" . htmlspecialchars($linha['curso']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['categoria']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['data_falta']) . "</td>"; // Exibindo a data formatada
                                    echo "<td>" . htmlspecialchars($linha['statusrequisicao']) . "</td>";
                                    echo "<td>" . htmlspecialchars($linha['comentariocoordenacao'] ?? ' ') . "</td>"; // Máscara para NULL
                                    echo "<td><a href='editarRequisicao.php?id=" . htmlspecialchars($linha['idrequisicao']) . "'><img src='img/Icones/editar_A2.svg' alt='Consultar' class = 'icon'></a></td>"; // Link para alterar
                                    echo "<td><a href='consultaRequisicao.php?idrequisicao=" . htmlspecialchars($linha['idrequisicao']) . "'><img src='img/Icones/eye2.svg' alt='Consultar' class = 'icon'></a></td>"; // Link para consulta
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