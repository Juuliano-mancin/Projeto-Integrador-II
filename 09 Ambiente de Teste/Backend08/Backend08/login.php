<?php
session_start();
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricula = $_POST['login'];
    $senha = $_POST['senha'];

    $stmt = $conexao->prepare("SELECT idprofessor, matricula, nome, sobrenome, funcao FROM tb_professores WHERE matricula = ? AND senha = ?");
    $stmt->bind_param("is", $matricula, $senha);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($idProfessor, $matriculaProfessor, $nomeDoProfessor, $sobrenomeDoProfessor, $funcao);
        $stmt->fetch();

        $_SESSION['matricula'] = $matriculaProfessor;
        $_SESSION['nome'] = $nomeDoProfessor;
        $_SESSION['sobrenome'] = $sobrenomeDoProfessor;
        $_SESSION['idprofessor'] = $idProfessor;

        switch ($funcao) {
            case 'professor':
                header("Location: homepageProfessor.php");
                break;
            case 'administrativo':
                header("Location: homepageAdministrativo.php");
                break;
            case 'coordenacao':
                header("Location: homepageCoordenacao.php");
                break;
        }

        exit();
    } else {
        echo "<script>alert('Matrícula ou senha inválidos.'); window.location.href = 'login.html'</script>";
    }

    $stmt->close();
}

$conexao->close();
?>