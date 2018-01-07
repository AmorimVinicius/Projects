<?php
    include_once('init.php');
    $pesquisa = $_POST['palavra'];
    $pesquisa = "SELECT * FROM cadastronf WHERE notafiscal LIKE '%$pesquisa%'";
    $resultado_cursos = mysqli_query($DB_CONN, $pesquisa);

    if(mysqli_num_rown($resultado_cursos) <= 0){
        echo "Nada encontrado...";
    }
        else{
            while($rows = mysqli_fetch_assoc($resultado_cursos)){
                echo "<li>".$rows['notafiscal']."<li>";
            }
        }

?>