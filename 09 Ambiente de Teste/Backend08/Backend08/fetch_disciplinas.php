<?php
$conn = new mysqli('localhost', 'root', '', 'justificativafaltas');
$idCurso = $_GET['curso'];

$sqlDisciplinas = "SELECT iddisciplina, disciplina FROM tb_disciplinas WHERE idcurso = ?";
$stmt = $conn->prepare($sqlDisciplinas);
$stmt->bind_param('i', $idCurso);
$stmt->execute();
$result = $stmt->get_result();

$disciplinas = [];
while ($row = $result->fetch_assoc()) {
    $disciplinas[] = $row;
}

echo json_encode($disciplinas);
$conn->close();
?>
