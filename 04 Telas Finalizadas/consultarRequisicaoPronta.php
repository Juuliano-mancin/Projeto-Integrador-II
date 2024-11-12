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

// Obtendo o ID da requisição via GET
$idRequisicao = isset($_GET['idrequisicao']) ? intval($_GET['idrequisicao']) : 0;

// Determinando a página de retorno com base no parâmetro de origem
if (isset($_GET['origem']) && $_GET['origem'] === 'administrativo') {
    $paginaAnterior = 'homepageAdministrativo.php';
} else {
    $paginaAnterior = 'homepageCoordenacao.php';
}

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
    echo "<h2>Requisição não encontrada.</h2>";
    echo '<a href="' . htmlspecialchars($paginaAnterior) . '"><button>Voltar</button></a>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Consulta de Requisição</title>
    <link rel="stylesheet" href="consultaRequisicao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
<header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2 class="h2-header">PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Consulta de Requisição</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
            <div class="divA_2">
           
            </div>
        </div>
    </header>

    <main>
        <div class="principal">
            <div class="divA1">
             
                <h1 class="text">Detalhes da Requisição</h1>
               
            </div>

            <div class="divB">
                <div class="divB1">
                    <div class="subB1">
                        <div class="subText1">
                            <h2 class="text">Sigla do Curso:</h2>
                        </div>
                        <div class="subDado1">
                            <p><?php echo htmlspecialchars($linha['curso']); ?></p>
                        </div>
                    </div>
                    <div class="subB2">
                        <div class="subText2">
                                <h2 class="text">Categoria:</h2>
                        </div>
                        <div class="subDado2">
                                <p><?php echo htmlspecialchars($linha['categoria']); ?></p>
                        </div>
                    </div>   
                </div>

                <div class="divB1">
                    <div class="subB1">
                        <div class="subText1">
                            <h2 class="text">Data Inicial:</h2>
                        </div>
                        <div class="subDado1">
                            <p><?php echo htmlspecialchars($linha['data_inicial']); ?></p>
                        </div>
                    </div>
                    <div class="subB2">
                        <div class="subText2">
                            <h2 class="text">Data Final:</h2>
                        </div>
                        <div class="subDado2">
                            <p><?php echo htmlspecialchars($linha['data_final']); ?></p>
                        </div>
                    </div>   
                </div>

                <div class="divB1">
                    <div class="subB1">
                        <div class="subText1">
                            <h2 class="text">Comentário do Professor:</h2>
                        </div>
                        <div class="subDado1">
                            <p><?php echo nl2br(htmlspecialchars($linha['comentarioprofessor'])); ?></p>
                        </div>
                    </div>
                    
                </div>
             

                <div class="divB1">
                    <div class="subB1">
                        <div class="subText1">
                            <h2 class="text">Status:</h2>
                        </div>
                        <div class="subDado1">
                            <p><?php echo htmlspecialchars($linha['statusrequisicao']); ?></p>
                        </div>
                    </div>
                    
                </div>

                <div class="divB1">
                    <div class="subB1">
                        <div class="subText1">
                            <h2 class="text">Comentário da Coordenação:</h2>
                        </div>
                        <div class="subDado1">
                            <p><?php echo nl2br(htmlspecialchars($linha['comentariocoordenacao'])); ?></p>
                        </div>
                    </div>
                    
                </div>    

                <!-- Botão Voltar com redirecionamento correto -->
                <div class="button-container">
                    <a href="<?php echo htmlspecialchars($paginaAnterior); ?>">
                        <button class="Voltarbtn">Voltar</button>
                    </a>
                </div>
        </div>
        
    </main>
</body>
</html>
