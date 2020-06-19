<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' />
		<script type="text/javascript" src="js/notasemcest.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaNotaCEST" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Rejeição 806 - Nota sem CEST</h3>
							<hr/>
							<p>Preencher os dados da nota e enviar o formulário. Será verificado os itens 
							que estão sem CEST e será preenchido com o CEST genérico 2899900.</p>
							<p>Será efetuado também um update com o código 394, para que o RADEZ gere novamente o XML e reenvie.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Rejeição 806 <small>- Nota sem CEST</small></b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaNotaCEST' style='float:right;'> Ajuda</button>
				</h2>
			</div>
			<form method='POST'>
				<div class="row">
					<div class="form-group col-md-4" >
						<label for="loja">Loja</label>
						<select id="loja" class="form-control" name="loja" >
							<option value='0' >NENHUM</option>
							<?php
							echo $conect->getLoja();
							?>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="serie">Serie</label>
						<input type="number" class="form-control" id="serie" name="serie">
					</div>
					<div class="form-group col-md-4" >
						<label for="nota">Nota</label>
						<input type="number" class="form-control" id="nota" name="nota" >
					</div>
					<div class="form-group col-md-12">
						<button type="submit" class="btn btn-primary" id="enviar" onclick=clicou() id='enviar'>Enviar</button>
					</div>			
				</div>
			</form>	
			<div id='gif' style='display:none;'><img id='gif' src="imagens/aguarde.gif" />
				<b>Aguarde...</b><br/><br/>
			</div>
			<!--Retorno do PHP-->
			<div>	
				<div class='alert alert-danger fade in' id='controle' style='display:none'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Todos os campos devem estar preechidos antes de enviar!</b>
				</div>
<?php

if( isset($_REQUEST['loja']) && empty ($_REQUEST['loja'])==false && isset($_REQUEST['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
	isset($_REQUEST['serie'])  && empty ($_REQUEST['serie'])==false &&
	isset($_REQUEST['nota']) && empty($_REQUEST['nota'])==false ) {
	$loja=addslashes($_REQUEST['loja']);
	$serie=addslashes($_REQUEST['serie']);
	$nota=addslashes($_REQUEST['nota']);
	$conect= new ClassBD();
	$conect->geraCest($loja,$serie,$nota);
}
?>				
				
			</div>
		</div>	
	</body>
</html>

