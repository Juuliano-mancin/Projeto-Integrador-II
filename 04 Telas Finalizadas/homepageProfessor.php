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
    <script>
        function filtrarDados() {
            const input = document.getElementById('filtroInput');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('solicitacoesTable');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const tds = tr[i].getElementsByTagName('td');
                let match = false;

                for (let j = 0; j < tds.length; j++) {
                    if (tds[j]) {
                        const txtValue = tds[j].textContent || tds[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            match = true;
                            break;
                        }
                    }
                }

                tr[i].style.display = match ? '' : 'none';
            }
        }
    </script>
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
                    echo "Bem vindo, " . htmlspecialchars($_SESSION['nome']);
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
                    <div class="subB1a">
                        
                    </div>
                    <input type="text" id="filtroInput" class="inputFiltro" placeholder="Filtrar dados..." onkeyup="filtrarDados()">
                </div>
                <div class="subB2">
                    <form>
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Data da Requisição</th>
                                    <th>Sigla do Curso</th>
                                    <th>Categoria</th>
                                    <th>Data da Falta</th>
                                    <th>Status</th>
                                    <th>Comentário Coordenação</th>
                                    <th>Alterar</th>
                                    <th>Consulta</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <td>23/10/2024</td>
                                    <td>DSM</td>
                                    <td>Presencial</td>
                                    <td>19/10/2024</td>
                                    <td>APROVADO</td>
                                    <td></td>
                                    <td>Alterar</td>
                                    <td>Consulta</td> 
                            </tbody>
                        </table>
                        
                    </form>
                </div>
            
        </div>
    </div>
    
</body>
</html>