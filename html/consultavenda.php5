<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' />
		<script type="text/javascript" src="js/consultavenda.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaConsultaVenda" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Consulta Venda por COO</h3>
							<hr/>
							<p>Digite a data,loja,pdv e cupom. Ao enviar será aberto uma sessão do ZeusManager
							com os dados do formulário e será visualizado o cupom pelo programa Gerenciamento de Lojas.</p>
							<p>Como o programa não tem integração com o Manager não é possível logar, portanto para o 
							funcionamento você deverá manter uma sessão do ZeusManager logada em outra aba antes do envio do formulário.
							No Warning você encontrará um botão com o link do Zeus.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Consulta Venda por COO</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaConsultaVenda' style='float:right;'> Ajuda</button>
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
							<option value='0' >NENHUM</option>
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
						<label for="pdv">Cupom</label>
						<input type="text" class="form-control" id="cupom" name="cupom">
					</div>
					<div class="form-group col-md-12">
						<button type="submit" class="btn btn-primary" onclick=clicou() id='enviar'>Enviar</button>
					</div>			
				</div>
			</form>	
			<div id='gif' style='display:none;'><img id='gif' src="imagens/aguarde.gif" />
				<b>Aguarde...</b><br/><br/>
			</div>
			<div>
				<div class='alert alert-danger fade in' id='controle' style='display:none'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Todos os campos devem estar preechidos antes de enviar!</b>
				</div>
			<div class='alert alert-warning fade in' style='margin-bottom:5%;'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Atenção! Clique no botão para acessar o ZeusManager antes de enviar o formulário
				e certifique-se que seu navegador permitirá popup!<br/><br/></b>
				<a style='margin:auto;' href="http://zeus/manager/login.php5" class="btn btn-info active" role="button" aria-pressed="true" target="_blank">Zeus Manager</a>
			</div>		
		<div>

<?php

if( isset($_POST['data']) && empty ($_POST['data'])==false && 
	isset($_POST['loja']) && empty ($_POST['loja'])==false && isset($_POST['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
	isset($_POST['pdv'])  && empty ($_POST['pdv'])==false && 
	isset($_POST['cupom']) && empty($_POST['cupom'])==false ) {
	
	isset($_POST['data']);
	$data=addslashes($_POST['data']);
	isset($_POST['loja']);
	$loja=addslashes($_POST['loja']);
	isset($_POST['pdv']);
	$pdv=addslashes($_POST['pdv']);
	isset($_POST['cupom']);
	$cupom=addslashes($_POST['cupom']);
	echo '<script type="text/javascript">	
			window.open("http://zeus/manager/mostra_ticket.php5?cod_loja='.$loja.'&pdv='.$pdv.'&tkt='.$cupom.'&data='.date('d-m-Y', strtotime($data)).'","_blank");	
	    </script>';
}
?>				
				
		
	</body>
</html>

