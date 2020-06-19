<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' />
		<script type="text/javascript" src="js/reenviacupom.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaCupomMbs" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Reenvia Cupom MBS</h3>
							<hr/>
							<p>Digite a data,loja,pdv e cupom. Ao enviar será efetuado um update
							na tabela ZAN_M00, marcando o campo M00ZZA01 para o valor zero, fazendo com que o registro
							volte para a fila de reenvio para o integrador.</p>
							<p>Caso houver mais de um cupom, pode ser informado quantos quiser, basta separar os documentos
							por vírgula.</p>
							<p><code>Atenção!</code> Não fazer o mesmo para o número de pdv, este deverá ser único por transação.<p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Reenvia Cupom MBS</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaCupomMbs' style='float:right;'> Ajuda</button>
				</h2>
			</div>	
			<form method='POST'>
				<div class="row">
					<div class="form-group col-md-3">
						<label for="data">Data</label>
						<input type="date" class="form-control" id="data" name="data">
					</div>
					<div class="form-group col-md-3">
						<label for="loja">Loja</label>
						<select id="loja" class="form-control" name="loja">
							<option value='0'>NENHUM</option>
							<?php
							echo $conect->getLoja();
							?>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label for="pdv">Pdv</label>
						<input type="number" class="form-control" id="pdv" name="pdv">
					</div>
					<div class="form-group col-md-3">
						<label for="cupom">Cupom</label>
						<input type="text" class="form-control" id="cupom" name="cupom">
					</div>
					<div class="form-group col-md-12">
						<button type="submit" class="btn btn-primary " onclick=clicou() id='enviar'>Enviar</button>
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

if( isset($_POST['data']) && empty($_POST['data'])==false && 
	isset($_POST['loja']) && empty($_POST['loja'])==false && isset($_POST['loja'])>0 &&
	isset($_POST['pdv'])  && empty($_POST['pdv'])==false && 
	isset($_POST['cupom']) && empty($_POST['cupom'])==false ) {
	
	isset($_POST['data']);
	$data=addslashes($_POST['data']);
	isset($_POST['loja']);
	$loja=addslashes($_POST['loja']);
	isset($_POST['pdv']);
	$pdv=addslashes($_POST['pdv']);
	isset($_POST['cupom']);
	$cupom=addslashes($_POST['cupom']);
	$conect= new ClassBD();
	$conect->reenviaCupom($data,$loja,$pdv,$cupom);
} 
?>				
			</div>
		</div>	
	</body>
</html>