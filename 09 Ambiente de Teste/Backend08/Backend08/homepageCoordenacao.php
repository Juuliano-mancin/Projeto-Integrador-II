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

// Obtendo as requisições dos professores
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
                Logado como coordenador <br>
                <?php
                echo "Bem vindo, " . htmlspecialchars($_SESSION['nome']) . " " . htmlspecialchars($_SESSION['sobrenome']);
                ?>
            </div>
        </div>

        <div class="divB">
            Requisições dos Professores
            <br><br>
            <input type="text" id="filtroInput" class="inputFiltro" placeholder="Filtrar dados..." onkeyup="filtrarDados()">
            <table id="solicitacoesTable">
                <thead>
                    <tr>
                        <th>ID Requisição</th>
                        <th>Professor</th>
                        <th>Curso</th>
                        <th>Categoria</th>
                        <th>Data da Falta</th>
                        <th>Status</th>
                        <th>Comentário</th>
                        <th>Alterar</th>
                        <th>Consultar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop para exibir as requisições na tabela
                    while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($linha['idrequisicao']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['professor']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['curso']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['categoria']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['data_falta']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['statusrequisicao']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['comentariocoordenacao'] ?? ' ') . "</td>";
                        
                        // Botão para alterar status
                        echo "<td>
                                <form action='alterarStatus.php' method='GET'>
                                    <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                    <button type='submit'>Alterar</button>
                                </form>
                              </td>";
                        
                        // Botão para consultar requisição
                        echo "<td>
                                <form action='consultarRequisicaoPronta.php' method='GET'>
                                    <input type='hidden' name='idrequisicao' value='" . htmlspecialchars($linha['idrequisicao']) . "'>
                                    <button type='submit'>Consultar</button>
                                </form>
                              </td>";

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
