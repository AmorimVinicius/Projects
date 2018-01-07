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
        <title>Cadastro de novo login</title>
    </head>

    <body>
        <div class="container">
            <div class="page-header">
                <h1>Novo Login</h1>
            </div>

            <!-- Início Inserindo novo usuário -->
                <?php
                    if (isset($_POST['cadastrar'])){
                        $user = $_POST['user'];
                        $nome = $_POST['employee'];
                        $senha = $_POST['password'];
                        $confirmSenha = $_POST['passwordConfirm'];

                        /* Select para verificar se já existe usuario duplicado */
                            $sqlSelect = 'SELECT * FROM login WHERE user = :user OR nome = :nome';
                            $createSelect = $db->prepare($sqlSelect);
                            $createSelect->bindValue(':user', $user, PDO::PARAM_STR);
                            $createSelect->bindValue(':nome', $nome, PDO::PARAM_STR);
                            $createSelect->execute();
                            $countRegister = $createSelect->rowCount();
                        /* Fim select */

                        /* se existir duplicidade é exibida uma mensagem de erro */
                            if ($countRegister > 0){
                                echo "<div id='msgCadastro' class='alert alert-danger'>
                                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                        <strong>Erro ao cadastrar. <br />Usuário ou colaborador já cadastrado! Favor, verificar.</strong>
                                    </div>";
                            }
                        /* se não existir duplicidade é reaalizado o cadastro de usuário */
                            else{
                                $sql = 'INSERT INTO login (user, nome, senha, confirmSenha)';
                                $sql .= 'VALUES (:user, :nome, :senha, :confirmSenha)';
                                try{
                                    $create = $db->prepare($sql);
                                    $create->bindValue(':user', $user, PDO::PARAM_STR);
                                    $create->bindValue(':nome', $nome, PDO::PARAM_STR);
                                    $create->bindValue(':senha', $senha, PDO::PARAM_STR);
                                    $create->bindValue(':confirmSenha', $confirmSenha, PDO::PARAM_STR);
                                    if($create->execute()){
                                        echo "<div id='msgCadastro' class='alert alert-success'>
                                                <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                                <strong>Inserido com sucesso!</strong>
                                            </div>";
                                    }
                                        }catch(PDOException $e){
                                            echo "<div id='msgCadastro' class='alert alert-danger'>
                                            <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                            <strong>Erro ao inserir dados!</strong> " . $e->getMessage() . "
                                            </div>";
                                        }
                            }
                    }                    
                ?>
            <!-- Fim Inserindo novo usuário --> 
                    <!-- Início . Ocultar mensagem de confirmação/erro de cadastro -->
                        <script>
                            var div = document.getElementById('msgCadastro');
                                setTimeout(function() {
                                div.style.display = 'none';
                            }, 2500);
                        </script>
                    <!-- Fim . Ocultar mensagem de confirmação/erro de cadastro -->
            
            <form id="formCadastroLogin" method="post" action="">       
                <table id="tabelaCadastroLogin">
                        <tr>
                            <td>
                                <div id="perfil" <p>*Perfil: Analista</p></div>
                            </td>
                        </tr>
                        <tr>
                        <td> <label for="inputEmployeeLogin">Colaborador: </label> </td>
                            <td> <input type="text" name="employee" id="inputEmployeeLogin" placeholder="Nome completo..." required autofocus> </td>
                        </tr>
                        <tr>
                            <td> <label for="inputUserLogin">Usuário: </label> </td>
                            <td> <input type="text" name="user" id="inputUserLogin" placeholder="Nome de usuário..." required autofocus> </td>
                        </tr>
                        <tr>    
                            <td> <label for="inputPasswordLogin">Senha: </label> </td>
                            <td> <input type="password" name="password" id="inputPasswordLogin" placeholder="Digite sua senha..." required autofocus> </td>
                        </tr> 
                        <tr>    
                            <td> <label for="inputPasswordConfirm">Confirmar senha: </label> </td>
                            <td> <input type="password" name="passwordConfirm" id="inputPasswordConfirm" placeholder="Confirme sua senha..." required autofocus> </td>
                        </tr> 
                    </form>
                </table>
                <table id="tabelaCadastroLogin">
                    <td><input type="submit" class="btn btn-warning" value="Cadastrar" name="cadastrar" onclick="validarSenha();"></td>
                    <td><input type="reset" class="btn btn-danger" value="Limpar"></td>
                    <td><input class="btn btn-primary" value="Voltar" onclick="window.location.href='index.php'"></td>   
                </table>
            </form>
        </div>
    </body>
</html>