<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' />
		<script type="text/javascript" src="js/reimprime.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaReimprime" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Fechamento Tesouraria</h3>
							<hr/>
							<p><code>Atenção!</code> Esse procedimento só deverá ser executado para os casos em que a tesouraria 
							permanece aberta mesmo com dias posteriores fechados, conforme ilustração abaixo, e
							<code>NÃO PODE SER REALIZADO EM LOJAS QUE ENTROU O PROJETO ONE FINANCE!</code></p>
							<p><a href='imagens/ajuda_fechamento.png' target="_blank"><img src="imagens/ajuda_fechamento.png" width='565' /></a></p>
							<p>Selecione a loja,data e clique em <kbd>Enviar</kbd>. O sistema irá criar os registros de fechamento.<p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Fechamento Tesouraria</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign ajudates animation' data-toggle='modal' data-target='#ajudaReimprime' style='float:right;' id='ajudates'> Ajuda</button>
				</h2>
			</div>
			<form name='formulario' method='POST'>
				<div class="row">
					<div class="form-group col-md-6">
						<label for="data">Data</label>
						<input type="date" class="form-control" id="data" name="data">
					</div>
					<div class="form-group col-md-6">
						<label for="loja">Loja</label>
						<select id="loja" class="form-control" name="loja">
							<option value='0' >NENHUM</option>
							<?php
							echo $conect->getLoja();
							?>
						</select>
					</div>
					<div class="form-group col-md-12">
						<button type="submit" class="btn btn-primary" onclick=clicou() id='enviar'>Enviar</button>
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

if( isset($_POST['data']) && empty ($_POST['data'])==false && 
	isset($_POST['loja']) && empty ($_POST['loja'])==false && isset($_POST['loja'])>0) {
	
	$data=addslashes($_POST['data']);
	$loja=addslashes($_POST['loja']);
	$conect= new ClassBD();
	$conect->fechaTesouraria($data,$loja);	
}
?>					
			</div>
		</div>	
	</body>
</html>