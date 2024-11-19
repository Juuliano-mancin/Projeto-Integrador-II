<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=justificativafaltas', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['curso'])) {
        $idCurso = $_GET['curso'];
        
        // Consultar disciplinas relacionadas ao curso
        $sqlDisciplinas = "SELECT iddisciplina, disciplina FROM tb_disciplinas WHERE idcurso = ?";
        $stmtDisciplinas = $pdo->prepare($sqlDisciplinas);
        $stmtDisciplinas->execute([$idCurso]);
        $disciplinas = $stmtDisciplinas->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($disciplinas);
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
?>
