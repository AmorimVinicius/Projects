<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- styles css -->
        <link rel="stylesheet" type="text/css" href="Estilos/styles/index.css">
        <!-- styles bootstrap -->
        <link href="Estilos/bootstrap/bootstrap.min.css" rel="stylesheet">
        <!-- syles javascript -->
        <script type="text/javascript" src="scripts/consultarDados.js"></script>
        <script type="text/javascript" src="scripts/jquery-3.2.1.min.js"></script>
        <script src="https://code.jquery.com/jquery-2.2.4.js"  integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
  crossorigin="anonymous"></script>
        <title>Visualizar Notas Fiscais</title>
    </head>

    <body>
        <div class="container">
            <div class="page-header">
                    <h1>Visualizar Notas Fiscais</h1>
            </div>
            <form method="POST" action="" id="teste">
                <input type="text" name="pesquisa" id="pesquisa">
                <input type="submit" name="enviar" value="Pesquisar">
            </form>
            <ul class="resultado">
            </ul>
        </div>
    </body>
</html>