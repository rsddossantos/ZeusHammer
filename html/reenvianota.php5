<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' />
		<script type="text/javascript" src="js/reenvianota.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaNotaMbs" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Reenvia Nota MBS</h3>
							<hr/>
							<p>Digite os dados da nota e envie o formulário. Será efetuado um update
							na tabela TAB_NOTA_HEADER, marcando o campo FLG_INTEGRACAO_01 para o valor zero, fazendo com que o registro
							volte para a fila de reenvio para o integrador.</p>
							<p>Caso houver mais de uma nota, pode ser informado quantas quiser, separando os documentos
							por vírgula.</p>
							<p><code>Atenção!</code> Não informar mais de uma série, ela deverá ser única por transação.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Reenvia Nota MBS</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaNotaMbs' style='float:right;'> Ajuda</button>
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
						<input type="text" class="form-control" id="nota" name="nota" >
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

if( isset($_POST['loja']) && empty ($_POST['loja'])==false && isset($_POST['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
	isset($_POST['serie'])  && empty ($_POST['serie'])==false && 
	isset($_POST['nota']) && empty($_POST['nota'])==false ) {
	
	$loja=addslashes($_POST['loja']);
	$serie=addslashes($_POST['serie']);
	$nota=addslashes($_POST['nota']);
	$conect= new ClassBD();
	$conect->reenviaNota($loja,$serie,$nota);
}

?>				
				
			</div>
		</div>	
	</body>
</html>