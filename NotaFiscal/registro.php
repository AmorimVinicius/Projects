<?php
    /*include_once("init.php");
    $notafiscal = $_POST['notafiscal'];
    $descricao = $_POST['descricao'];
    $faturamento = $_POST['faturamento'];
    $pagamento = $_POST['pagamento'];
    $status = $_POST['status'];

    $faturamento = date("d-m-Y",strtotime(str_replace('/','-',$faturamento)));  
    $pagamento = date("d-m-Y",strtotime(str_replace('/','-',$pagamento)));  
    //$pagamento = date('d/m/Y', strtotime($pagamento));

    echo "olá";

    $DB_INSERT = "INSERT INTO cadastronf (notafiscal, descricao, faturamento, pagamento, status, agendamento, pagamentoaux)
               VALUES ('$notafiscal','$descricao','$faturamento','$pagamento','$status',0,'$pagamento')";
    $DB_RESULT = mysqli_query($DB_CONN, $DB_INSERT);*/
    include_once("bancopdo.php");
    $user = $_POST['user'];
    $nome = $_POST['employee'];
    $senha = $_POST['password'];
    $confirmSenha = $_POST['passwordConfirm'];

        $sqlSelect = 'SELECT * FROM login WHERE user = :user';
        echo "ok";

        $sqlInsert = 'INSERT INTO login (user, nome, senha, confirmSenha)';
        $sqlInsert .= 'VALUES (:user, :nome, :senha, :confirmSenha)';
        try{
            $create = $db->prepare($sqlInsert);
            $create->bindValue(':user', $user, PDO::PARAM_STR);
            $create->bindValue(':nome', $nome, PDO::PARAM_STR);
            $create->bindValue(':senha', $senha, PDO::PARAM_STR);
            $create->bindValue(':confirmSenha', $confirmSenha, PDO::PARAM_STR);
            if($create->execute()){
                echo "Cadastrado com sucesso!!!";
            }
        }catch(PDOException $e){
            echo "Erro ao cadastrar!!!";
        }
      

?>