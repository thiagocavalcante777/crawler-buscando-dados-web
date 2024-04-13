<html>
<head>
    <title>Crawler busca dados</title>
    <style>
        .input_palavras-chaves {
            width: 500px;
            height: 50px;
        }

        .button_buscar {
            width: 50px;
            height: 50px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<form method="GET" action="processar.php">
    <input class="input_palavras-chaves" type="text" name="palavra_chave"
           placeholder="Digite as plavras chaves separadas por ',' Virgulas">
    <input class="button_buscar" type="submit" value="Enviar">
</form>
</body>
</html>