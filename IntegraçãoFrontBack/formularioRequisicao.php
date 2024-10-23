<?php 
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    // Se o usuário não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Conectando ao banco de dados
$conexao = new mysqli("localhost", "root", "", "justificativafaltas");

// Verificando a conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Buscando informações do professor
$nomeProfessor = htmlspecialchars($_SESSION['nome']);
$matriculaProfessor = htmlspecialchars($_SESSION['matricula']);
$funcaoProfessor = "Professor de Ensino Superior"; // Função fixa
$regimeJuridico = "CLT"; // Texto fixo

// Buscando os cursos
$cursos = [];
$stmtCursos = $conexao->prepare("SELECT idcurso, curso FROM tb_cursos");
$stmtCursos->execute();
$stmtCursos->bind_result($idCurso, $nomeCurso);

while ($stmtCursos->fetch()) {
    $cursos[] = ['id' => $idCurso, 'nome' => $nomeCurso];
}
$stmtCursos->free_result(); // Libera os resultados da consulta
$stmtCursos->close(); // Fecha a declaração

// Buscando as categorias de faltas
$categorias = [];
$stmtCategorias = $conexao->prepare("SELECT idcategoria, categoria FROM tb_categoria_faltas");
$stmtCategorias->execute();
$stmtCategorias->bind_result($idCategoria, $nomeCategoria);

while ($stmtCategorias->fetch()) {
    $categorias[$idCategoria] = ['id' => $idCategoria, 'nome' => $nomeCategoria, 'justificativas' => []];
}
$stmtCategorias->close(); // Fecha o statement de categorias

// Obtendo todas as justificativas de uma vez
$stmtJustificativas = $conexao->prepare("SELECT idjustificativa, justificativa, codcategoria FROM tb_justificativa_faltas");
$stmtJustificativas->execute();
$stmtJustificativas->bind_result($idJustificativa, $descricaoJustificativa, $idCategoria);

// Agrupando as justificativas em suas respectivas categorias
while ($stmtJustificativas->fetch()) {
    if (isset($categorias[$idCategoria])) {
        $categorias[$idCategoria]['justificativas'][] = ['id' => $idJustificativa, 'descricao' => $descricaoJustificativa];
    }
}
$stmtJustificativas->close(); // Fecha o statement de justificativas
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Requisição de Faltas</title>
    <link rel="stylesheet" href="FormularioRequisicao.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="principal">
            <div class="divA">
                <div class="subDiv1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Nova Requisição - Justificativa de Faltas</h3>
                </div>
                
                <div class="subDiv2">        
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">
                </div>
            </div>
        </div>
    </header>
    <br /><br />
    <main>
        <form method="POST" action="processar_requisicao.php"> <!-- Define o método e ação do formulário -->
            <div class="divPrincipal">
                <div class="div1">
                    <div> Nome Professor: <?php echo $nomeProfessor; ?></div>
                    <div> Número Matrícula: <?php echo $matriculaProfessor; ?></div>
                    <div> Função: <?php echo $funcaoProfessor; ?></div>
                    <div> Regime Jurídico: <?php echo $regimeJuridico; ?></div>
                </div>

                <div class="div2">
                    <div class="subdiv1">
                        <div>
                            <label>Selecione o curso:</label>
                            <select id="cursos" name="curso" aria-label="Selecione o curso" required>
                                <option value="">Selecione um curso</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?php echo htmlspecialchars($curso['id']); ?>"><?php echo htmlspecialchars($curso['nome']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label>Insira a data inicial:</label>
                            <input type="date" id="data-inicial" name="data_inicial" aria-label="Data inicial" required>
                        </div>
                        <div>
                            <label>Insira a data final:</label>
                            <input type="date" id="data-final" name="data_final" aria-label="Data final" required>
                        </div>
                    </div>
                    <div class="subdiv2">
                        <div class="nova-div1">
                            <div class="radio-container" id="licenca-falta">
                                <input type="radio" id="licenca-falta-radio" name="justificativa" aria-label="Licença e Falta médica">
                                <label class="label-radio" for="licenca-falta-radio">01 Licença e Falta médica</label>
                                <select id="justificativa1" name="justificativa1" aria-label="Justificativa 1" required>
                                    <option value="">Selecione a justificativa</option>
                                    <?php if (isset($categorias[1]['justificativas'])): ?>
                                        <?php foreach ($categorias[1]['justificativas'] as $justificativa): ?>
                                            <option value="<?php echo htmlspecialchars($justificativa['id']); ?>">
                                                <?php echo htmlspecialchars($justificativa['descricao']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="radio-container" id="faltas-injustificadas">
                                <input type="radio" id="faltas-injustificadas-radio" name="justificativa" aria-label="Faltas Injustificadas">
                                <label class="label-radio" for="faltas-injustificadas-radio">02 Faltas Injustificadas</label>
                                <select id="justificativa2" name="justificativa2" aria-label="Justificativa 2" required>
                                    <option value="">Selecione a justificativa</option>
                                    <?php if (isset($categorias[2]['justificativas'])): ?>
                                        <?php foreach ($categorias[2]['justificativas'] as $justificativa): ?>
                                            <option value="<?php echo htmlspecialchars($justificativa['id']); ?>">
                                                <?php echo htmlspecialchars($justificativa['descricao']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="radio-container" id="faltas-justificadas">
                                <input type="radio" id="faltas-justificadas-radio" name="justificativa" aria-label="Faltas Justificadas">
                                <label class="label-radio" for="faltas-justificadas-radio">03 Faltas Justificadas</label>
                                <select id="justificativa3" name="justificativa3" aria-label="Justificativa 3" required>
                                    <option value="">Selecione a justificativa</option>
                                    <?php if (isset($categorias[3]['justificativas'])): ?>
                                        <?php foreach ($categorias[3]['justificativas'] as $justificativa): ?>
                                            <option value="<?php echo htmlspecialchars($justificativa['id']); ?>">
                                                <?php echo htmlspecialchars($justificativa['descricao']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="radio-container" id="faltas-previstas">
                                <input type="radio" id="faltas-previstas-radio" name="justificativa" aria-label="Faltas Previstas em Lei">
                                <label class="label-radio" for="faltas-previstas-radio">04 Faltas Previstas em Lei</label>
                                <select id="justificativa4" name="justificativa4" aria-label="Justificativa 4" required>
                                    <option value="">Selecione a justificativa</option>
                                    <?php if (isset($categorias[4]['justificativas'])): ?>
                                        <?php foreach ($categorias[4]['justificativas'] as $justificativa): ?>
                                            <option value="<?php echo htmlspecialchars($justificativa['id']); ?>">
                                                <?php echo htmlspecialchars($justificativa['descricao']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="nova-div2">
                            <textarea id="comentarios" name="comentarios" rows="10" placeholder="Complemente aqui a sua justificativa de falta"></textarea>
                        </div>
                    </div>
                    <div class="button-container">
                        <input type="file" id="input-file" name="input-file" style="display:none;">
                        <button type="button" id="upload-documento" aria-label="Upload de Documento">
                            <img src="img/Icones/ArquivoUpload.svg" alt="Ícone de upload" style="width: 20px; height: 20px; margin-right: 5px;">
                            Upload de Documento
                        </button>
                        <button id="proxima-pagina" aria-label="Reposição de Aula">Reposição de Aula</button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Importa o arquivo JavaScript -->
    <script src="FormularioRequisicao.js"></script>
</body>
</html>

<?php
// Fechando a conexão
$conexao->close();
?>
