<?php
    require_once 'init.php';

    // get form
    $notafiscal = isset($_POST['notafiscal']) ? $_POST['notafiscal'] : null;
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : null;
    $faturamento = isset($_POST['faturamento']) ? $_POST['faturamento'] : null;
    $pagamento = isset($_POST['pagamento']) ? $_POST['pagamento'] : null;
    $status = isset($_POST['status']) ? $_POST['status'] : null;
    //$antecipacaoNotaFiscal = = isset($_POST['status'] ? $_POST['status'] : null);


    // add in database
    $PDO = DB_Connect();
    $sql = "INSERT INTO cadastroNotaFiscal (notafiscal, descricao, faturamento, pagamento, status)
            VALUES (:notafiscal, :descricao, :faturamento, :pagamento, :status)";
    
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':notafiscal', $notafiscal);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':faturamento', $faturamento);
    $stmt->bindParam(':pagamento', $pagamento);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()){
        header('Localtion: index.php');
    }
    else{
        echo "Erro ao cadastrar";
        print_r($stmt->errorInfo());
    }

?>