<?php
// Iniciando a sessão
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Homepage</title>
    <link rel="stylesheet" href="homepageProf.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&family=Roboto+Slab:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <STYLE>A {text-decoration: none;} </STYLE>
    <header>
        <div class="header-container">
            <div class="divA">
                <div class="subDiv1">
                    <h2>PORTAL JUSTIFICATIVA DE FALTAS</h2>
                    <h3>Professor</h3>
                </div>
                
                <div class="subDiv2"> 
                    <img src="img/Logos_oficiais/logo_cps_versao_br.png" alt="Logo">       
                </div>
            </div>
            <div class="divA_2">
           
            </div>
        </div>
    </header>

    <div class="principal">
        <div class="divA1">
            <div class="subA1">
                <?php
                // Verificando se o nome está definido na sessão
                if (isset($_SESSION['nome'])) {
                    echo "Bem vindo, " . htmlspecialchars($_SESSION['nome']) . "!";
                } else {
                    echo "Bem vindo!";
                }
                ?>
            </div>
            <div class="subA2">
            <form action="logout.php" method="POST" style="float: right; margin: 10px;">
                    <button type="submit" class="Sairbtn">
                        SAIR
                    </button>
                </form>
            </div>
        </div>

        <div class="divB">
                <div class="subB1">
                    <div class="subB1a">
                        <a href="formularioRequisicao.php">
                            <button class="solicitacaoBtn"> 
                                <img src="img/icones/plus_azul.svg" alt="Adicionar" class="icon"> <!-- Ícone de adicionar --> 
                                Nova Solicitação
                            </button>
                        </a>
                    </div>
                    <input type="text" class="inputFiltro" placeholder="Filtrar dados...">
                </div>
                <div class="subB2">
                    <form>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Curso</th>
                                    <th>Disciplina</th>
                                    <th>Status</th>
                                    <th>Comentário Coordenação</th>
                                    <th>Alteração</th>
                                    <th>Visualizar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td>23/10/24</td>
                                <td>DSM</td>
                                <td>Eng Software</td>
                                <td>ANÁLISE</td>
                                <td>Necessário atestado</td>
                                <td>EDITAR</td>
                                <td>arquivo.pdf</td>

                                <!-- As linhas de dados irão aqui -->
                            </tbody>
                        </table>
                        <button class="imprimirBtn">
                            <img src="img/Icones/arquivo_pdf.svg" alt="Imprimir" class="icon"> <!-- Ícone de imprimir -->
                            Imprimir Relatório
                        </button>
                    </form>
                </div>
            
        </div>
    </div>
    
</body>
</html>