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
							<h3>Reimprime Voucher</h3>
							<hr/>
							<p>Selecione a loja,data e clique em <kbd>Enviar</kbd>. Serão exibidas todas as devoluções efetuadas.</p>
							<p>Selecione a troca ou reembolso que deseja e clique em <kbd>EFETIVAR</kbd>.</p>
							<p><code>Atenção!</code> O sistema só irá modificar para "Não Impresso" os vouchers não finalizados,
							não cancelados e que estão com status "Reimpresso".<p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Reimprime Voucher</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaReimprime' style='float:right;'> Ajuda</button>
				</h2>
			</div>
			<form name='formulario'>
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

if( isset($_REQUEST['data']) && empty ($_REQUEST['data'])==false && 
	isset($_REQUEST['loja']) && empty ($_REQUEST['loja'])==false && isset($_REQUEST['loja'])>0) {
	
	isset($_REQUEST['data']);
	$data=addslashes($_REQUEST['data']);
	isset($_REQUEST['loja']);
	$loja=addslashes($_REQUEST['loja']);
	$conect= new ClassBD();
	$conect->reimprime($data,$loja);	
}
?>					
			</div>
			<div id="5"></div>
		</div>	
	</body>
</html>