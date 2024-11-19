<?php
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    header("Location: login.php");
    exit();
}

// Verificando se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando os dados do formulário
    $curso = htmlspecialchars($_POST['curso']);
    $data_inicial = htmlspecialchars($_POST['data_inicial']);
    $data_final = htmlspecialchars($_POST['data_final']);
    $categoria = htmlspecialchars($_POST['categoria']);
    $justificativa = htmlspecialchars($_POST['justificativa']);
    $comentarios = htmlspecialchars($_POST['comentarios']);
    $id_professor = intval($_SESSION['idprofessor']);

    // Conexão com o banco de dados
    $conn = new mysqli('localhost', 'root', '', 'justificativafaltas');
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Inserindo na tabela tb_requisicao_faltas
    $query_inserir = "INSERT INTO tb_requisicao_faltas (idprofessor, idcurso, data_inicial, data_final, idcategoria, idjustificativa, comentarioprofessor, statusrequisicao) VALUES (?, ?, ?, ?, ?, ?, ?, 'pendente')";
    $stmt = $conn->prepare($query_inserir);
    $stmt->bind_param("iissiis", $id_professor, $curso, $data_inicial, $data_final, $categoria, $justificativa, $comentarios);

    // Executando a inserção
    if ($stmt->execute()) {
        $id_requisicao = $stmt->insert_id;

        // Processamento dos arquivos de upload
        if (isset($_FILES['arquivos'])) {
            $arquivos = $_FILES['arquivos'];
            $nomesArquivos = [];

            for ($i = 0; $i < count($arquivos['name']); $i++) {
                if ($arquivos['error'][$i] === UPLOAD_ERR_OK) {
                    $uniqueid = uniqid(); // Gerando um ID único
                    $nomeArquivo = $uniqueid . '_' . basename($arquivos['name'][$i]);
                    $caminhoArquivo = 'uploads/' . $nomeArquivo;

                    // Tente mover o arquivo e mostre mensagens de depuração
                    if (move_uploaded_file($arquivos['tmp_name'][$i], $caminhoArquivo)) {
                        // Adiciona o nome do arquivo à lista
                        $nomesArquivos[] = $nomeArquivo;
                    } else {
                        echo "Erro ao mover o arquivo: {$arquivos['name'][$i]}<br>";
                    }
                } else {
                    echo "Erro no upload do arquivo {$arquivos['name'][$i]}: Código de erro {$arquivos['error'][$i]}<br>";
                }
            }

            // Preparando as colunas para atualização na tabela
            $arquivo01 = isset($nomesArquivos[0]) ? $nomesArquivos[0] : null;
            $arquivo02 = isset($nomesArquivos[1]) ? $nomesArquivos[1] : null;
            $arquivo03 = isset($nomesArquivos[2]) ? $nomesArquivos[2] : null;

            // Atualizando a tabela com os nomes dos arquivos
            $query_atualizar = "UPDATE tb_requisicao_faltas SET arquivo01 = ?, arquivo02 = ?, arquivo03 = ? WHERE idrequisicao = ?";
            $stmt_atualizar = $conn->prepare($query_atualizar);
            $stmt_atualizar->bind_param("sssi", $arquivo01, $arquivo02, $arquivo03, $id_requisicao);
            $stmt_atualizar->execute();
            $stmt_atualizar->close();
        }

        // Redireciona para uma página de sucesso
        header("Location: sucesso.php");
        exit();
    } else {
        // Redireciona para uma página de erro
        header("Location: erro.php?error=" . urlencode($stmt->error));
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: formularioRequisicao.php");
    exit();
}
?>
