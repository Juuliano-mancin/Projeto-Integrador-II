<?php 
// Iniciando a sessão
session_start();

// Verificando se o usuário está logado
if (!isset($_SESSION['nome']) || !isset($_SESSION['sobrenome']) || !isset($_SESSION['matricula'])) {
    header("Location: login.php");
    exit();
}

// Conectando ao banco de dados
$conexao = new mysqli("localhost", "root", "", "justificativafaltas");

if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Buscando informações do professor
$nomeProfessor = htmlspecialchars($_SESSION['nome']);
$matriculaProfessor = htmlspecialchars($_SESSION['matricula']);

// Buscando as disciplinas
$disciplinas = [];
$stmtDisciplinas = $conexao->prepare("SELECT iddisciplina, disciplina FROM tb_disciplinas");

if ($stmtDisciplinas === false) {
    die("Erro na preparação da consulta: " . $conexao->error);
}

$stmtDisciplinas->execute();
$stmtDisciplinas->bind_result($idDisciplina, $nomeDisciplina);

while ($stmtDisciplinas->fetch()) {
    $disciplinas[] = ['id' => $idDisciplina, 'disciplina' => $nomeDisciplina];
}
$stmtDisciplinas->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="FormularioReposicao.css">
    <script src="FormularioReposicao.js" defer></script>
    <title>Reposição de aulas</title>
    <script>
        function validateForm(event) {
            event.preventDefault(); // Impede o envio padrão do formulário
            
            // Coleta os dados do formulário
            const formData = new FormData(document.querySelector('form'));
            const request = new XMLHttpRequest();
            request.open('POST', 'processar_reposicao.php', true);

            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    // Sucesso!
                    alert("Reposição enviada com sucesso!");
                    // Limpa o formulário após o envio
                    document.querySelector('form').reset();
                } else {
                    alert("Erro ao enviar a reposição. Tente novamente.");
                }
            };

            request.send(formData);
        }
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="div1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Nova Requisição - Reposição de aulas</h3>
                </div>
                <div class="div2">        
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">
                </div>
            </div>
        </div>
    </header>

    <div class="divprincipal">
        <form method="post" action="" onsubmit="validateForm(event)">
            <div class="div1-principal">
                <div class="field-container">
                    <fieldset>
                        <legend>Turno da Reposição:</legend>
                        <label>
                            <input type="radio" name="turno" value="manha" required> Manhã
                        </label>
                        <label>
                            <input type="radio" name="turno" value="tarde" required> Tarde
                        </label>
                        <label>
                            <input type="radio" name="turno" value="noite" required> Noite
                        </label>
                    </fieldset>
                </div>

                <div class="field-container">
                    <fieldset>
                        <legend>Tipo de Reposição:</legend>
                        <label>
                            <input type="radio" name="tipo" value="aula" required> Aula Ministrada
                        </label>
                        <label>
                            <input type="radio" name="tipo" value="atividade" required> Atividade
                        </label>
                        <label>
                            <input type="radio" name="tipo" value="outros" required> Outros
                        </label>
                    </fieldset>
                </div>
            </div>

            <div class="div2-principal">
                <div class="tabela-container">
                    <table id="tabela1">
                        <thead>
                            <tr>
                                <th colspan="2">Número de faltas</th>
                                <th>Data Aula</th>
                                <th>Número Aulas</th>
                                <th>Disciplina</th>
                                <th>Data Reposição</th>
                                <th>Horário Início</th>
                                <th>Horário Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                            <tr>
                                <td><input type="checkbox" onclick="toggleFields(this, this.closest('tr'))"></td>
                                <td><?php echo $i; ?></td>
                                <td class="date_input"><input type="date" name="data_aula_<?php echo $i; ?>" disabled></td>
                                <td>
                                    <select name="numero_aulas_<?php echo $i; ?>" disabled>
                                        <option value="">Selecione número de aulas</option>
                                        <option value="1">01 Aula</option>
                                        <option value="2">02 Aulas</option>
                                        <option value="3">03 Aulas</option>
                                        <option value="4">04 Aulas</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="disciplina_<?php echo $i; ?>" disabled>
                                        <option value="">Selecione a disciplina</option>
                                        <?php foreach ($disciplinas as $disciplina): ?>
                                            <option value="<?php echo htmlspecialchars($disciplina['id']); ?>">
                                                <?php echo htmlspecialchars($disciplina['disciplina']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input type="date" name="data_reposicao_<?php echo $i; ?>" disabled></td>
                                <td><input type="time" name="horario_inicio_<?php echo $i; ?>" disabled></td>
                                <td><input type="time" name="horario_final_<?php echo $i; ?>" disabled></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="button-container">
                <div class="botao">
                    <button type="submit">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
