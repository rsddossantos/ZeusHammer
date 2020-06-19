<?php
/*
 * @author: Rodrigo Silveira 
 * @version: 1.0
 */

include_once("classes/class_bd.php5");
session_start();
if( isset($_SESSION['id']) && empty($_SESSION['id'])==false){
	$user=$_SESSION['id'];
	$conect= new ClassBD();
} else {
	header("Location:login.php5");
	}
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
		<link rel='stylesheet' href='css/index.css'/>
		<script type="text/javascript" src="js/template.js"></script>
	</head>
	<body>
		<div class="container"> 
			<nav class="navbar navbar-inverse navbar-fixed-top">
				<div class="container-fluid">
					<div class="navbar-header dropdown">
						<a class="navbar-brand dropdown-toggle" data-toggle="dropdown"><b>Zeus</b>Hammer</a>
						<ul class="dropdown-menu">
							<li class="dropdown-header">Links Úteis:</li>
							<li><a href="https://zanthus.zendesk.com/hc/pt-br/sections/115000366391-Base-de-Conhecimento-MAKRO" target="_blank">Kbase Zendesk</a></li>
							<li ><a href="http://10.52.254.223/nagios/" target="_blank">Nagios</a></li>
							<li><a href="https://10.52.234.90:7802/em" target="_blank">Oracle EM</a></li>
							<li><a href="http://zeus/manager/login.php5" target="_blank">ZeusRetail</a></li>
							<li class="divider">
							<li><a href="#" data-toggle="modal" data-target="#janela">Sobre</a></li>
						</ul>		
					</div>
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Reprocessamento<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="reenviacupom.php5">Reenvia Cupom MBS</a></li>
								<li><a href="reenvianota.php5">Reenvia Nota MBS</a></li>
								<li><a href="reprocessanotas.php5">Reprocessa Notas Sefaz</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Processos<span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu">
                                <li class="dropdown-submenu pull-left">
                                    <a tabindex="-1" href="#">Rejeições Sefaz</a>
                                    <ul class="dropdown-menu">
                                        <li><a tabindex="-1" href="notasemcest.php5">806 - Nota sem CEST</a></li>
                                        <li><a tabindex="-1" href="fcpinvalido.php5">874 - FCP inválido</a></li>
                                        <li><a tabindex="-1" href="ncminexistente.php5">778 - NCM inexistente</a></li>
                                        <li><a tabindex="-1" href="cpfinvalido.php5">237 - CPF inválido</a></li>
                                        <li><a tabindex="-1" href="totalbcdif.php5">531 - Total BC difere dos itens</a></li>
                                        <li><a tabindex="-1" href="totalfcpdif.php5">861 - Total FCP difere - Reprocessamento</a></li>
                                        <li class="disabled"><a tabindex="-1" href="totalcbenef.php5">930 - CBenef inexistente - Reprocessamento</a></li>
                                    </ul>
                                </li>
                                <li><a tabindex="-1" href="liberacupom.php5">Libera Cupom Devolução</a></li>
                                <li><a tabindex="-1" href="reimprime.php5">Reimprime Voucher</a></li>
                                <li><a tabindex="-1" href="geracontrole.php5">Gera Controle NF</a></li>
                                <li class="disabled"><a>Fechamento Tesouraria</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Consultas<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="consultapreco.php5">Consulta Preço PDV</a></li>
								<li><a href="consultavenda.php5">Consulta Venda por COO</a></li>
                                <li><a href="consultanota.php5">Consulta Nota</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">Arquivos<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="geraarquivoxml.php5">Gera XML de NF</a></li>
							</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="#" data-toggle="tooltip" data-placement="bottom" onclick="voltar()" title="Voltar" >
							<img class="icone" src="imagens/back-arrow.png" width="20" />
							</a>
						</li>
						<li><a href="index.php5" data-toggle="tooltip" data-placement="bottom" title="Ir para a página principal">
							<img src="imagens/home.png" width="20" />
							</a>
						</li>
						<li class="dropdown">
							<a href="" class="dropdown-toggle" data-toggle="dropdown">
							<img src="imagens/user.png" width="20" />
							<?php 
							$campos=$conect->getUser($user);
							echo $campos[NOME][0];
							?>
							<span class="caret"></span></a></a>
							<ul class="dropdown-menu">
								<li class="dropdown-header"><h5>Código Funcionário: <b><?php echo $campos[COD_FUNCIONARIO][0];?></b></h5></li>
								<li class="dropdown-header"><h5>Usuário: <b><?php echo $campos[NOME][0];?></b></h5></li>
								<li class="divider">
								<li><a href="login.php5?act=logout">Logout</a></li>
							</ul>
								
						</li>
						<li><a href="config_bd.php5" data-toggle="tooltip" data-placement="bottom" title="Configurações">
							<img src="imagens/repair-tools.png" width="20" />
							</a>
						</li>
					</ul>
				</div>
			</nav>
			<div id="janela" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<p>Conectado na base de dados:</p>
							<hr/>
							<p>Banco: Oracle</p> 
							<?php
							$conect->consultaInstancia();
							?>	
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
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
<!-- o fechamento das tags se dá em cada página que será exibida-->			
		</div>	
	</body>
</html>	