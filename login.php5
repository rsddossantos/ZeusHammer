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
		<title>ZeusHammer</title>
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel='stylesheet' href='bootstrap/bootstrap.min.css'/>
		<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
		<script src='bootstrap/bootstrap.min.js'></script>
		<link rel='stylesheet' href='css/login.css'/>
		<script type="text/javascript" src="js/login.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div class="login" >
				<div class="img"><img id="logo" src="imagens/logo_cliente.jpg" /></div>
				<form class="form-group left-inner-addon" method="POST">
					<h4>Seja bem-vindo, por favor identifique-se:</h4>
					<div class="iconInput">
						<i class="glyphicon glyphicon-user"></i>
						<input type="text" class="form-control" placeholder="Usuário" name="usuario" required autocomplete="off" />
					</div>
					<div class="iconInput">
						<i class="glyphicon glyphicon-lock"></i>
						<input type="password" class="form-control"  placeholder="Senha" name="senha" required autocomplete="off" />
					</div>
					<button id="botao" type="submit" class="btn btn-default glyphicon glyphicon-home"><b/> Entrar</b></button> 
				</form><br/><br/><br/><br/>
			</div>		
			<div class="info">
				ZeusHammer 1.0<br/>
				© 2000-2019 Suporte Técnico - Zanthus S/A<br/>
				Compatível com Google Chrome versão 69 ou superior<br/><br/>
				<img src="imagens/zanthus_logomarca.png" height="25"/><br/><br/>
			</div>
			<!--Modal Confirm-->
			<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="confirm">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">ZeusHammer</h4>
						</div>	
						<div class="modal-body" id="myModalLabel">Confirmar</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" id="modal-btn-si">OK</button>
							<button type="button" class="btn btn-default" id="modal-btn-no">Cancelar</button>
						</div>
					</div>
				</div>
			</div>
			<!--Modal Alert-->
			<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="alert">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">ZeusHammer</h4>
						</div>	
						<div class="modal-body" id="myModalLabelalert">Confirmar</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-primary" id="modal-btn-ok">OK</button>
						</div>
					</div>
				</div>
			</div>
		
		</div> 
	</body>
</html>
<?php
session_start();
if(isset($_POST['usuario']) && empty ($_POST['usuario'])==false &&
   isset($_POST['senha']) && empty ($_POST['senha'])==false){
	   
    $usuario=strtoupper(addslashes($_POST['usuario']));
    $senha=base64_encode(addslashes($_POST['senha']));		
	$conect= new ClassBD();
	$conect->login($usuario,$senha);
}
if($_GET["act"]=="logout"){
	session_destroy();
	header("location: index.php5");
}
