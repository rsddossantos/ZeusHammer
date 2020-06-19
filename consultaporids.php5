<?php
/*
 * @author: Rodrigo Silveira
 * @version: 1.0
 */
include_once("classes/class_bd.php5");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Consulta Notas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel='stylesheet' href='bootstrap/bootstrap.min.css'/>
    <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
    <script src='bootstrap/bootstrap.min.js'></script>
    <link rel='stylesheet' href='css/index.css'/>
    <script type="text/javascript">
        function voltar() {
            window.history.back()
        }
    </script>
</head>
<body>
<div class="container">
    <?php
    if ( isset($_GET['ids']) && !empty($_GET['ids']) ){
        $ids=$_GET['ids'];
        $conect= new ClassBD();
        $conect->consultaPorIds($ids);
    }else{
        echo "<div class='alert alert-danger fade in' >
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Falha na captura dos dados, pesquise novamente!</b>
		</div>";
    }
    ?>
</div>
</body>
</html>
