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
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
<script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>        
        <!-- styles javascript -->
        <script type="text/javascript" src="scripts/validarDados.js"></script>
        <title>Gerenciamento de Notas Fiscais</title>
    </head>

    <body>
        <div class="container">
            <div class="page-header">
                <h1>Cadastro de Notas Fiscais</h1>
            </div>
            <!-- Início inserindo um novo cadastro de nota fiscal -->
                <?php
                    /* Verifica se data de pagamento é inferior a data de faturamento */
                    if (isset($_POST['cadastrar'])){
                        $notafiscal = $_POST['notafiscal'];
                        $descricao = $_POST['descricao'];
                        $faturamento = implode('-',array_reverse(explode('/',$_POST['faturamento'])));
                        $pagamento = implode('-',array_reverse(explode('/',$_POST['pagamento'])));
                        $status = $_POST['status'];
                        $agendamento; $pagamentoaux;
                        $cmpFaturamento = date('d/m/Y',strtotime($faturamento));
                        $cmpPagamento = date('d/m/Y',strtotime($pagamento));

                        if ($cmpPagamento < $cmpFaturamento){
                            echo "<div class='alert alert-danger'>
                                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                    <strong>Erro ao cadastrar. <br /> A data de pagemento não deve ser inferior a data de faturamento!</strong>
                                 </div>";
                        }
                            else{
                                /* Select para verificar duplicidade de nota fiscal */
                                    $sqlSelect = 'SELECT * FROM cadastronf WHERE notafiscal = :notafiscal';
                                    $createSelect = $db->prepare($sqlSelect);
                                    $createSelect->bindValue(':notafiscal', $notafiscal, PDO::PARAM_STR);
                                    $createSelect->execute();
                                    $countRegister = $createSelect->rowCount();
                                /* Fim select */

                                /* se existir duplicidade é exibida uma mensagem de erro */
                                if ($countRegister > 0){
                                    echo "<div class='alert alert-danger'>
                                            <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                            <strong>Erro ao cadastrar. <br />Nota fiscal já está cadastrada em nossa base de dados.</strong>
                                        </div>";
                                }
                                    /* se não existir duplicidade é reaalizado o cadastro de usuário */
                                    else{
                                        $sql = 'INSERT INTO cadastronf (notafiscal, descricao, faturamento, pagamento, status, agendamento, pagamentoaux)';
                                        $sql .= 'VALUES (:notafiscal, :descricao, :faturamento, :pagamento, :status, 0, :pagamento)';
                                        try{
                                            $create = $db->prepare($sql);
                                            $create->bindValue(':notafiscal', $notafiscal, PDO::PARAM_STR);
                                            $create->bindValue(':descricao', $descricao, PDO::PARAM_STR);
                                            $create->bindValue(':faturamento', $faturamento, PDO::PARAM_STR);
                                            $create->bindValue(':pagamento', $pagamento, PDO::PARAM_STR);
                                            $create->bindValue(':status', $status, PDO::PARAM_STR);
                                            $create->bindValue(':pagamentoaux', $pagamento, PDO::PARAM_STR);
                                            if($create->execute()){
                                                echo "<div class='alert alert-success'>
                                                        <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                                        <strong>Inserido com sucesso!</strong>
                                                    </div>";
                                            }
                                                }catch(PDOException $e){
                                                    echo "<div class='alert alert-danger'>
                                                    <button type='button' class='close' data-dismiss='alert'>&times;</button>
                                                    <strong>Erro ao inserir dados!</strong> " . $e->getMessage() . "
                                                    </div>";
                                                }       
                                    }
                        }
                    }
                ?>
            <!-- Fim inserção -->

                <form id="formCadastro" method="post" action="">
                    <table id="tabelaCadastro">                                
                        <tr>
                            <td><span>Número Nota Fiscal:</span></td> <td><input class="input" type="text" name="notafiscal" placeholder="Número da nota fiscal..." required autofocus/></td>
                        </tr>
                        <tr>
                            <td><span>Descrição do Produto:</span></td> <td> <input class="input" type="text" name="descricao" placeholder="Nome completo do produto..." required autofocus/></td>
                        </tr>
                        <tr>
                            <td><span>Data Faturamento:</span></td> <td> <input id="calendarFat" class="data" type="text" name="faturamento" placeholder="dd/mm/aaaa" required/></td>
                        </tr>
                        <tr>
                            <td><span>Data Pagamento:</span></td> <td> <input id="calendarPag" class="data" type="text" name="pagamento"  placeholder="dd/mm/aaaa" required/></td>
                        </tr>
                        <tr>    
                            <td><span>Status:</span></td> 
                        <td> 
                            <select name="status" class="data">
                                <option value="ativo">Ativo</option>
                                <option value="inativo">Inativo</option>
                                <option value="pendente">Pendente</option>
                            </select>
                        </td>
                    </table>
                    <table id="tabelaCadastroBotao">
                        <td><input class="btn btn-success" name="cadastrar" type="submit" value="Cadastrar"></td>
                        <td><input type="reset"class="btn btn-danger" value="Limpar"></td>
                        <td><input class="btn btn-warning" value="Ver NF" onclick="window.location.href='returnDados.php'"></td>
                        <td><input class="btn btn-primary" value="Voltar" onclick="window.location.href='index.php'"></td>
                    </table>
                </form>
            </div>
        </div>
    </body>
</html>