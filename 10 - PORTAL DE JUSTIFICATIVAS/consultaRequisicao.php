<?php
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome'])) {
    // Se o usuário não estiver logado, redireciona para a página de login
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

// Obtendo o ID da requisição via GET
$idRequisicao = isset($_GET['idrequisicao']) ? intval($_GET['idrequisicao']) : 0;

// Consultar a requisição no banco de dados
$sql = "SELECT r.idrequisicao, c.siglacurso AS curso, cf.categoria, 
               DATE_FORMAT(r.data_inicial, '%d %m %Y') AS data_inicial,
               DATE_FORMAT(r.data_final, '%d %m %Y') AS data_final,
               r.comentarioprofessor, r.statusrequisicao, r.comentariocoordenacao
        FROM tb_requisicao_faltas r
        JOIN tb_cursos c ON r.idcurso = c.idcurso
        JOIN tb_categoria_faltas cf ON r.idcategoria = cf.idcategoria
        WHERE r.idrequisicao = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idRequisicao]);
$linha = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$linha) {
    echo "Requisição não encontrada.";
    exit();
}

// Consultar as reposições associadas à requisição
$sqlReposicao = "SELECT ra.* 
                 FROM tb_reposicao_aulas ra
                 WHERE ra.idrequisicao = ?";
$stmtReposicao = $pdo->prepare($sqlReposicao);
$stmtReposicao->execute([$idRequisicao]);
$reposicoes = $stmtReposicao->fetchAll(PDO::FETCH_ASSOC);

// Verificando se existe conteúdo no comentário da coordenação
$comentarioCoord = htmlspecialchars($linha['comentariocoordenacao']) ?: 'Não há nenhum comentário'; // Se não houver conteúdo
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Consulta de Requisição e Reposição</title>
    <link rel="stylesheet" href="consultaRequisicao_Prof.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
    <script>
        // Função para ajustar a largura do input com base no conteúdo
        function adjustInputWidth(input) {
            const text = input.value || input.placeholder; // Considera o conteúdo ou o placeholder
            const canvas = document.createElement("canvas");
            const context = canvas.getContext("2d");

            // Estilize conforme a fonte do seu CSS
            context.font = window.getComputedStyle(input).font;

            // Calcula a largura do texto
            const width = context.measureText(text).width;
            
            // Adiciona margem para o padding do input
            input.style.width = (width + 10) + "px";
        }

        // Inicializa todos os inputs ao carregar a página
        window.onload = function() {
            document.querySelectorAll("input[disabled]").forEach(adjustInputWidth);
        };
    </script>
</head>
<body>
<header>
    <div class="header-container">
        <div class="divA">
            <div class="subDiv1">
                <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                <h3>Consulta de Requisição e Reposição de Aulas</h3>
            </div>
            
            <div class="subDiv2"> 
                <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
            </div>
        </div>
    </div>
</header>

<div class="principal">
    <div class="divA1">
        <h1 class="text">Detalhes da Requisição</h1>
    </div>
    <div class="divB">
        <div class="divB1">
            
                <form>
                    <div class="form1">
                        <div class="dado1">
                            <label>Sigla do Curso:</label>
                            <input type="text" value="<?php echo htmlspecialchars($linha['curso']); ?>" disabled oninput="adjustInputWidth(this)">
                        </div>
                        <div class="dado2">
                            <label>Categoria:</label>
                            <input type="text" value="<?php echo htmlspecialchars($linha['categoria']); ?>" disabled oninput="adjustInputWidth(this)">
                        </div>
                    </div>
                    <div class="form1"> 
                        <div class="dado1"> 
                            <label>Data Inicial:</label>
                            <input type="text" value="<?php echo htmlspecialchars($linha['data_inicial']); ?>" disabled oninput="adjustInputWidth(this)">
                        </div>
                        <div class="dado2">
                            <label>Data Final:</label>
                            <input type="text" value="<?php echo htmlspecialchars($linha['data_final']); ?>" disabled oninput="adjustInputWidth(this)">
                        </div>
                    </div>
                    <div class="form1-comentario">
                        <div class="f1">
                            <label>Comentário do Professor:</label>
                        </div>
                        <div class="f2">
                            <textarea disabled><?php echo htmlspecialchars($linha['comentarioprofessor']); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Exibição condicional do comentário da coordenação -->
                    <div class="form1-comentario">
                        <div class="f1">
                            <label>Comentário da Coordenação:</label>
                        </div>
                        <div class="f2">
                            <textarea disabled><?php echo $comentarioCoord; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="form2">
                        <label>Status:</label>
                        <input type="text" value="<?php echo htmlspecialchars($linha['statusrequisicao']) ?: 'Sem status no momento'; ?>" disabled>
                    </div>
                </form>
            
        </div>

        <div class="divA2">
            <h1>Consulta de Reposições</h1>
        </div>
        <div class="subB2">
            <div class="tabela">
                <table>
                    <thead>
                    <th>Data Reposição</th>
                    <th>Status Reposição</th>
                    <th>Comentário Coordenação</th>
                    <th>Ações</th>

                    </thead>
                    <tbody>
                        <?php
                        // Exibição das reposições
                        if (count($reposicoes) > 0) {
                    
                            echo "<tr>";
                            foreach ($reposicoes as $reposicao) {
                            
                                echo "<td>" . htmlspecialchars($reposicao['datareposicao']) . "</td>";
                                echo "<td>" . htmlspecialchars($reposicao['statusreposicao']) . "</td>";
                                echo "<td>" . htmlspecialchars($reposicao['comentariocoordenacao'] ?? 'Nenhum comentário') . "</td>";
                                if (strtolower($reposicao['statusreposicao']) === 'reprovado') {
                                    echo "<td><a href='alterarReposicao.php?idreposicao=" . $reposicao['idreposicao'] . "'><button>Refazer Plano de Reposição</button></a></td>";
                                } else {
                                    echo "<td>—</td>";
                                }
                            }
                            echo "</tr>";
                        } else {
                            echo "<p>Não há reposições associadas a esta requisição.</p>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="subB3">
                <a href="homepageProfessor.php"> Voltar </a>
            </div>
        </div>

        
       
    </div>
</div>

<div class="foot">

</div>
<br>
</body>
</html>
