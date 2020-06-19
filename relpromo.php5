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
        <title>Consulta Promoção</title>
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel='stylesheet' href='bootstrap/bootstrap.min.css'/>
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
        <script src='bootstrap/bootstrap.min.js'></script>
        <link rel='stylesheet' href='css/index.css'/>
        <script type="text/javascript">
            function voltar() {
                window.history.back();
            }
            $(function () {
                 $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </head>
    <body>
        <div class="container">
<?php
if (isset($_GET['merc']) && !empty($_GET['merc']) && isset($_GET['loja']) && !empty($_GET['loja'])){
    $merc=$_GET['merc'];
    $loja=$_GET['loja'];
    $conect= new ClassBD();
    $conect->relPromo($merc,$loja);
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
