<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justificativa de Faltas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Arial:wght@400&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f0f0f0;
            color: #333;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            flex: 1;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            max-width: 80%;
            height: auto;
            margin: 0 auto;
        }

        .imagens {
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 20px;
        }

        .imagens img {
            width: 240px;
            height: auto;
            margin: 0 100px;
        }

        .destaque {
            font-size: 40px;
            font-weight: 700;
            margin: 15px 0;
            color: #C72B1A;
        }

        .subtitulo {
            font-size: 35px;
            font-weight: 400;
            margin: 10px 0;
            color: #343A40;
        }

        .subtitulo2 {
            font-size: 20px;
            font-weight: 400;
            margin-bottom: 30px;
            color: #343A40;
        }

        .botao {
            padding: 12px 25px;
            font-size: 16px;
            background-color: #C72B1A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-weight: 700;
        }

        .botao:hover {
            background-color: #cc0000;
        }

        footer {
            display: flex;
            align-items: center;
            background-color: #f0f0f0;
            color: #666;
            text-align: center;
            padding: 10px 20px;
            border-top: 1px solid #e0e0e0;
            width: 100%;
        }
    </style>
</head>
<body>

<main>
    <div class="container">
        <div class="imagens">
            <img src="img/Logos_oficiais/logo_cps_versao_cor.png" alt="Logo do Centro Paula Souza">
        </div>
        <br>
        <div class="destaque">Portal de Justificativa de Faltas</div>
        <div class="subtitulo">Fatec Itapira</div>
        <div class="subtitulo2">Ogari de Castro Pacheco</div>
        <a href="login.html" class="botao">Acesse o Portal</a>
    </div>
</main>

<footer>
    2º semestre desenvolvimento de software multiplataforma 2024
</footer>

</body>
</html>