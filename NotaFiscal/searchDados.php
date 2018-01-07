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
                    <h1>Resultado de Consulta</h1>
            </div>
            <form>
                <table id="tabelaExibe">
                        <tr>
                                <th>Nota Fiscal </th>
                                <th>Descrição </th>
                                <th>Pagamento </th>
                                <th>Status </th>
                        </tr>

                        <?php
                            include_once("init.php");
                            
                            $pesquisarnf = $_POST['pesquisarnf'];
                        
                            $DB_SELECT = "SELECT notafiscal, descricao, pagamento, status FROM cadastronf WHERE notafiscal = $pesquisarnf";
                            $DB_RESULT = mysqli_query($DB_CONN,$DB_SELECT);
                            
                            // for show all registers
                            while($DB_SHOW = mysqli_fetch_assoc($DB_RESULT)){
                                echo "<tr>"; 
                                    echo "<td>".$DB_SHOW["notafiscal"]."</td>";
                                    echo "<td>".$DB_SHOW["descricao"]."</td>";
                                    echo "<td>".$DB_SHOW["pagamento"]."</td>";
                                    echo "<td>".$DB_SHOW["status"]."</td>";
                                echo "</tr>";
                            }
                        ?>
                </table>
            </form>

            <hr>
            <table id="tabelaCadastroBotao">
                <td><input type="submit" class="btn btn-success" value="Voltar" onclick="window.location.href='returnDados.php'"></td>
                <!--<td><input type="reset"class="btn btn-warning" value="Limpar"></td>-->
                <td><input class="btn btn-default pgto" value="Antecipar Pgto" onclick="window.location.href='anteciparPgto.php'"></td>
            </table>

        </div>
    </body>
</html>