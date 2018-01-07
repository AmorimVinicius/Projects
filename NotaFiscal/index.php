<!-- 
    Projeto: Gerencimanto de Notas Fiscais - Iteris
    Por: Vinicius Cesar de Amorim
    Versão: 1.0    
-->

<?php
    require_once 'bancopdo.php';
?>

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
        <!-- styles javascript -->
        <script type="text/javascript" src="scripts/validarDados.js"></script>
        <title>Gerenciamento de Notas Fiscais</title>
    </head>

    <body>
        <div class="container">
            <!-- begin logo Iteris -->
                <div align="center" class="logoIteris">
                    <a href="http://www.iteris.com.br/pt-br/index.html">
                        <img src="images/logoIteris.png" alt="logoIteris" />
                    </a>
                </div>
            <!-- end logo Iteris -->
            
            <form class="formMenuPrincipal">
                <div class="textoIndice">
                    <h1>Gerenciamento de Notas Fiscais</h1>
                </div>
            </form>
                <form id="formLogin" method="post" action="">
                    <table id="tabelaLogin" align="center">
                        <tr>
                            <td> <label for="inputPerfil">Perfil: </label> </td>
                            <td>
                                <select id="inputPerfil">
                                    <option value="analista">Analista</option>
                                    <option value="gestor">Gestor</option>
                                </select>
                            </td>
                        </tr>
                        <tr>    
                            <td> <label for="inputUser">Usuário: </label> </td>
                            <td> <input type="text" name="user" id="inputUser" placeholder="Digite seu usuário..." required autofocus> </td>
                        </tr> 
                        <tr>    
                            <td> <label for="inputPassword">Senha: </label> </td>
                            <td> <input type="password" name="password" id="inputPassword" placeholder="Digite sua senha..." required autofocus> </td>
                        </tr>
                        
                        <tr>  
                            <td colspan="2" align="center"><input class="btn btn-warning" name="acessar" type="submit" value="Acessar"></td>                           
                        </tr>
                        <tr>  
                            <td colspan="2" align="center"><input id="btnNovoLogin" class="btn btn-success" value="Novo Login" onclick="chamaNewLogin()"></td>                           
                        </tr>
                    </table>
                        <!-- verifica se o login e senha estão corretos -->
                            <?php
                                if(isset($_POST['acessar'])){
                                    $user = $_POST['user'];
                                    $senha = $_POST['password'];
                                    $sqlSelect = 'SELECT * FROM login WHERE user = :user AND senha = :senha';
                                    $createSelect = $db->prepare($sqlSelect);
                                    $createSelect->bindValue(':user', $user, PDO::PARAM_STR);
                                    $createSelect->bindValue(':senha', $senha, PDO::PARAM_STR);
                                    $createSelect->execute();
                                    $countRegister = $createSelect->rowCount();
                            // se estiver correto direciona para a página de cadastro
                                    if ($countRegister > 0){
                                        header('Location: cadastro.php');
                            // se não estiver correto, é exibida uma mensagem de erro
                                    }else{
                                        echo "<div class='alert alert-danger'>
                                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                                <strong>Usuário ou senha incorretos!</strong>
                                            </div>";
                                    }
                                }
                            ?>
                        <!-- fim verificação -->
                </form> 
    </body>
</html>

