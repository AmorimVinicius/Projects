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