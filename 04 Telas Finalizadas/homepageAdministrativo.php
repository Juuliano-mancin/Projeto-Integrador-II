<?php
session_start();

if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome'])) {
    header("Location: login.php");
    exit();
}

// Conexão com o banco de dados
try {
    $pdo = new PDO('mysql:host=localhost;dbname=justificativafaltas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtendo as requisições, com "aprovado" no topo e "finalizado" no final
    $sql = "SELECT r.idrequisicao, c.siglacurso AS curso, cf.categoria, 
                   DATE_FORMAT(r.data_inicial, '%d %m %Y') AS data_falta, 
                   r.statusrequisicao, r.comentariocoordenacao, p.nome AS professor
            FROM tb_requisicao_faltas r
            JOIN tb_cursos c ON r.idcurso = c.idcurso
            JOIN tb_categoria_faltas cf ON r.idcategoria = cf.idcategoria
            JOIN tb_professores p ON r.idprofessor = p.idprofessor
            ORDER BY 
                CASE WHEN r.statusrequisicao = 'aprovado' THEN 1 
                     WHEN r.statusrequisicao = 'finalizado' THEN 2 
                     ELSE 3 END";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Homepage</title>
    <link rel="stylesheet" href="homepageAdm.css">
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
                    <h3>Administrativo</h3>
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
                                    <th>Finalizar</th>
                                    <th>Consultar</th>
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

                                    // Exibir o status (aprovado, finalizado, ou outro)
                                    echo "<td>";
                                    if ($linha['statusrequisicao'] === 'aprovado') {
                                        echo "Aprovado";
                                    } elseif ($linha['statusrequisicao'] === 'finalizado') {
                                        echo "<strong>FINALIZADO</strong>";
                                    } else {
                                        echo $linha['statusrequisicao']; // Para outros status
                                    }
                                    echo "</td>";

                                    echo "<td>" . htmlspecialchars($linha['comentariocoordenacao'] ?? ' ') . "</td>";

                                    // Exibir "FINALIZADO" ou o botão "Finalizar" conforme o status
                                    echo "<td>";
                                    if ($linha['statusrequisicao'] === 'aprovado') {
                                        echo "<form action='finalizarRequisicao.php' method='POST'>
                                                <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                                <button type='submit'>Finalizar</button>
                                            </form>";
                                    } elseif ($linha['statusrequisicao'] === 'finalizado') {
                                        echo "<strong>FINALIZADO</strong>";
                                    } else {
                                        echo "-";
                                    }
                                    echo "</td>";

                                    // Botão para consultar requisição
                                    echo "<td>
                                            <form action='consultarRequisicaoPronta.php' method='GET'>
                                                <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                                <input type='hidden' name='origem' value='administrativo'>
                                                <button type='submit'>Consultar</button>
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