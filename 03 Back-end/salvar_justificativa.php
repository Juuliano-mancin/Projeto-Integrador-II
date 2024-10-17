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

    <?php
    // Conectar ao banco de dados
    $servername = "localhost";
    $username = "root";  // Coloque o seu usuário do MySQL
    $password = "";      // Coloque a sua senha do MySQL
    $dbname = "portal_justificativas";

    // Cria conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        echo "<pre>";
        print_r($_POST);
        // print_r($_FILES);
        echo "</pre>";
        // Obter dados do formulário
        $nomeProfessor = "Nome vindo do banco de dados"; // Substitua conforme necessário
        $numeroMatricula = "Matrícula do banco de dados"; // Substitua conforme necessário
        $curso = $_POST["cursos"];
        $dataInicial = $_POST["data-inicial"];
        $dataFinal = $_POST["data-final"];
        $tipoJustificativa = "";

        // Determina o tipo de justificativa selecionada
        if (isset($_POST["justificativa"])) {
            switch ($_POST["justificativa"]) {
                case "licenca-falta":
                    $tipoJustificativa = "Licença e Falta médica";
                    break;
                case "faltas-injustificadas":
                    $tipoJustificativa = "Faltas Injustificadas";
                    break;
                case "faltas-justificadas":
                    $tipoJustificativa = "Faltas Justificadas";
                    break;
                case "faltas-previstas":
                    $tipoJustificativa = "Faltas Previstas em Lei";
                    break;
            }
        }

        $comentarios = $_POST["comentarios"];

        // Upload do documento
        if (isset($_FILES["input-file"]) && $_FILES["input-file"]["error"] == 0) {
            $documentoNome = $_FILES["input-file"]["name"];
            $documentoTemp = $_FILES["input-file"]["tmp_name"];
            $documentoTipo = $_FILES["input-file"]["type"];

            // Ler o conteúdo do arquivo em formato binário
            $documento = file_get_contents($documentoTemp);

            // Prepara a query para inserção no banco de dados
            $stmt = $conn->prepare("INSERT INTO justificativas (nome_professor, numero_matricula, curso, data_inicial, data_final, tipo_justificativa, comentarios, documento, documento_nome) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssss", $nomeProfessor, $numeroMatricula, $curso, $dataInicial, $dataFinal, $tipoJustificativa, $comentarios, $documento, $documentoNome);

            // Executa a query
            if ($stmt->execute()) {
                echo "<p style='color:green;'>Justificativa salva com sucesso!</p>";
            } else {
                echo "<p style='color:red;'>Erro ao salvar justificativa: " . $stmt->error . "</p>";
            }

            $stmt->close();
        } else {
            echo "<p style='color:red;'>Nenhum arquivo foi selecionado ou houve um erro no upload.</p>";
        }
    }

    // Fechar conexão
    $conn->close();
    ?>

    <header>
        <div class="principal">
            <div class="divA">
                <div class="subDiv1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Nova Requisição - Justificativa de Faltas</h3>
                </div>
                <div class="subDiv2">
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png">
                </div>
            </div>
        </div>
    </header>
    <br /><br />
    <main>
        <!-- Formulário para justificativa -->
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="divPrincipal">
                <div class="div1">
                    <div> Nome Professor: Informação do banco de dados </div>
                    <div> Número Matrícula: Informação do banco de dados </div>
                    <div> Função: Professor de Ensino Superior </div>
                    <div> Regime Jurídico: CLT </div>
                </div>

                <div class="div2">
                    <div class="subdiv1">
                        <div>
                            <label>Selecione o curso:</label>
                            <select id="cursos" name="cursos" aria-label="Selecione o curso">
                                <option value="">Selecione um curso</option>
                                <option value="curso1">Desenvolvimento de Software Multiplataforma</option>
                                <option value="curso2">Gestão de Produção Industrial</option>
                                <option value="curso3">Gestão Empresarial</option>
                            </select>
                        </div>
                        <div>
                            <label>Insira a data inicial:</label>
                            <input type="date" id="data-inicial" name="data-inicial" aria-label="Data inicial">
                        </div>
                        <div>
                            <label>Insira a data final:</label>
                            <input type="date" id="data-final" name="data-final" aria-label="Data final">
                        </div>
                    </div>
                    <div class="subdiv2">
                        <div class="nova-div1">
                            <!-- Radios e selects -->
                            <div class="radio-container" id="licenca-falta">
                                <input type="radio" id="licenca-falta-radio" name="justificativa" value="licenca-falta" aria-label="Licença e Falta médica">
                                <label class="label-radio" for="licenca-falta-radio">01 Licença e Falta médica</label>
                            </div>
                            <div class="radio-container" id="faltas-injustificadas">
                                <input type="radio" id="faltas-injustificadas-radio" name="justificativa" value="faltas-injustificadas" aria-label="Faltas Injustificadas">
                                <label class="label-radio" for="faltas-injustificadas-radio">02 Faltas Injustificadas</label>
                            </div>
                            <div class="radio-container" id="faltas-justificadas">
                                <input type="radio" id="faltas-justificadas-radio" name="justificativa" value="faltas-justificadas" aria-label="Faltas Justificadas">
                                <label class="label-radio" for="faltas-justificadas-radio">03 Faltas Justificadas</label>
                            </div>
                            <div class="radio-container" id="faltas-previstas">
                                <input type="radio" id="faltas-previstas-radio" name="justificativa" value="faltas-previstas" aria-label="Faltas Previstas em Lei">
                                <label class="label-radio" for="faltas-previstas-radio">04 Faltas Previstas em Lei</label>
                            </div>
                        </div>
                        <div class="nova-div2">
                            <textarea id="comentarios" name="comentarios" rows="10" placeholder="Complemente aqui a sua justificativa de falta"></textarea>
                        </div>
                    </div>

                    <div class="button-container">
                        <!-- Botão para selecionar arquivo -->
                        <input type="file" id="input-file" name="input-file" style="display:none;">
                        <button type="button" id="upload-documento" aria-label="Upload de Documento">
                            <img src="img/Icones/arquivo_pdf.svg" alt="Ícone de upload" style="width: 20px; height: 20px; margin-right: 5px;">
                            Upload de Documento
                        </button>
                        <button type="submit" id="proxima-pagina" aria-label="Reposição de Aula">Reposição de Aula</button>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <!-- Script para simular clique no campo de upload de arquivos -->
    <script>
        document.getElementById("upload-documento").addEventListener("click", function() {
            document.getElementById("input-file").click();
        });
    </script>

</body>
</html>
