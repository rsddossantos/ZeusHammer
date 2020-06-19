<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' />
		<script type="text/javascript" src="js/config_bd.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaConfigBD" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Configura BD</h3>
							<hr/>
							<p>Modifica a configuração de acesso ao banco de dados. Digite os dados de acesso, efetue o teste e envie o formulário.</p>
							<p>Será modificado o arquivo <code>config/config.ini</code> com a nova configuração, que será utilizada em todas as conexões com o banco.</p>
							<p><code>ATENÇÃO!</code> A configuração incorreta desses parâmetros irá afetar todas as funcionalidades do sistema. Nessas condições efetue um logout/login para exibir a opção da página de conf novamente.<p/>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Configura BD</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaConfigBD' style='float:right;'> Ajuda</button>
				</h2>
			</div>	
			<form method='POST'>
				<div class="row">
					<div class="form-group col-md-12">
						<label for="string">String de conexão</label>
						<input type="text" class="form-control" id="string" name="string" autocomplete="off" placeholder="Ex: (DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=ZEUS-SCAN)(PORT=1521)))(CONNECT_DATA=(SERVICE_NAME=ZEUSAPP)(SERVER=DEDICATED)))">
					</div>
					<div class="form-group col-md-12">
						<label for="usuario">Usuário</label>
						<input type="text" class="form-control" id="usuario" name="usuario" style="width:360px;" autocomplete="off" placeholder="Ex: ZEUS">
					</div>
					<div class="form-group col-md-12">
						<label for="senha">Senha</label>
						<input type="text" class="form-control" id="senha" name="senha" style="width:360px;" autocomplete="off" placeholder="Ex: Zan@123"><br/>
					</div>
					<div class="form-group col-md-12">
						<ul class="pager">
							<li><button type="submit" style='width:150px' class="btn btn-primary glyphicon glyphicon-refresh" onclick='testar()' id='testar'>  Testar </button></li>
							<li><button  type="submit" style='width:150px' class="btn btn-primary glyphicon glyphicon-arrow-right" id='enviar'> Enviar</button></li>
						</ul>
					</div>	
				</div>
				<input type='hidden' id='action' name='action'/>
			</form>	
			<div id='gif' style='display:none;'><img id='gif' src="imagens/aguarde.gif" />
				<b>Aguarde...</b><br/><br/>
			</div>
			<!--Retorno do PHP-->
			<div>
			
				<div class='alert alert-danger fade in' id='controle' style='display:none;' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Favor preencher todos os campos!</b>
				</div>
<?php

// Essa página deverá alimentar o arquivo config/config.ini com os parametros de conexão.

session_start();

if (isset($_POST['action']) && $_POST['action']=='testou'){
	$string=strtoupper(addslashes($_POST['string']));
	$usuario=strtoupper(addslashes($_POST['usuario']));
	$senha=addslashes($_POST['senha']);
	$conect= new ClassBD();
	$conect->testaConexao($string,$usuario,$senha);
} else{
	if( isset($_POST['string']) && empty($_POST['string'])==false && 
		isset($_POST['usuario'])  && empty($_POST['usuario'])==false && 
		isset($_POST['senha']) && empty($_POST['senha'])==false ) {
		
		if ($_SESSION['testeok']=='ok'){
			$string=strtoupper(addslashes($_POST['string']));
			$usuario=strtoupper(addslashes($_POST['usuario']));
			$senha=addslashes($_POST['senha']);
			$arquivo='config/config.ini';
			$conect= new ClassBD();
			$conect->geraConfig($string,$usuario,$senha,$arquivo);
		} else{
			echo "<div class='alert alert-danger fade in' style='margin-top:0px;' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Efetue o teste antes de enviar a configuração!</kbd></b>
				</div>";
		}
	}
}
?>				
			</div>
			<div id="5"></div>
		</div>	
	</body>
</html>