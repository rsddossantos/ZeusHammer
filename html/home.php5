<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet'/>
	</head>
	<body>
		<div class="container"> 			
			<div id="ajuda" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Bem-Vindo ao ZeusHammer</h3>
							<hr/>
							<p>Em cada tela você encontrará o conteúdo de ajuda referente ao processo, não deixe de clicar no botão AJUDA e conferir!</p>
							<hr/>
							<p>Informações do Sistema:</p>
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
			<div class="titulo">
				<h2><b>Home</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajuda' style='float:right;'> Ajuda</button>
				</h2>
			</div>
			<div id='nav-tabs' style='margin-top:-5%;'>
				<ul class="nav nav-tabs">
					<li class="active">
						<a href="#1" data-toggle="tab" class="tabs">Reprocessamento</a>
					</li>
					<li>
						<a href="#2" data-toggle="tab" class="tabs">Processos</a>
					</li>
					<li>
						<a href="#3" data-toggle="tab" class="tabs">Consultas</a>
					</li>
					<li>
						<a href="#4" data-toggle="tab" class="tabs">Arquivos</a>
					</li>
				</ul>
			</div>
			<div id='tab-content' class="tab-content" >
				<div id="1" class="tab-pane active in fade">
					<h3>Reprocessamento</h3>
					<p>Alterações para reenvio de informações:<p>
					<div class="panel panel-primary">
						<div class="panel-heading">Escolha as opções:</div>
						<a href="reenviacupom.php5"><div class="panel-body"><h4>Reenvia Cupom MBS</h4></div></a>
						<a href="reenvianota.php5"><div class="panel-body"><h4>Reenvia Nota MBS</h4></div></a>
						<a href="reprocessanotas.php5"><div class="panel-body"><h4>Reprocessa Notas Sefaz</h4></div></a>
					</div>
				</div>
				<div id="2" class="tab-pane fade">
					<h3>Processos</h3>
					<p>Alterações em processos do sistema:</p>
					<div class="panel panel-primary">
						<div class="panel-heading">Escolha as opções:</div>
                        <a><div class="panel-body" id="rejeicao">
                                <h4>Rejeições Sefaz <span class="caret"></span></h4>
                                <div id="menurejeicao" class="panel panel-primary">
                                    <a href="notasemcest.php5"><div class="panel-body sub"><h4>806 - Nota sem CEST</h4></div></a>
                                    <a href="fcpinvalido.php5"><div class="panel-body sub"><h4>874 - FCP inválido</h4></div></a>
                                    <a href="ncminexistente.php5"><div class="panel-body sub"><h4>778 - NCM inexistente</h4></div></a>
                                    <a href="cpfinvalido.php5"><div class="panel-body sub"><h4>237 - CPF inválido</h4></div></a>
                                    <a href="totalbcdif.php5"><div class="panel-body sub"><h4>531 - Total BC difere dos itens</h4></div></a>
                                    <a href="totalfcpdif.php5"><div class="panel-body sub"><h4>861 - Total FCP difere (Reproc)</h4></div></a>
									<!-- Desabilitado totalcbenef.php5-->
                                    <a style="cursor:not-allowed"><div class="panel-body sub"><h4>930 - CBenef inexistente (Reproc)</h4></div></a>
                                </div>
                        </a></div>
                        <a href="liberacupom.php5"><div class="panel-body"><h4>Libera Cupom Devolução</h4></div></a>
                        <a href="reimprime.php5"><div class="panel-body"><h4>Reimprime Voucher</h4></div></a>
                        <a href="geracontrole.php5"><div class="panel-body"><h4>Gera Controle NF</h4></div></a>
                        <!-- Desabilitado fechamentocaixa.php5-->
                        <a style="cursor:not-allowed"><div class="panel-body"><h4>Fechamento Tesouraria</h4></div></a>
					</div>
				</div>
				<div id="3" class="tab-pane fade">
					<h3>Consultas</h3>
					<p>Formulários para consultas diversas:</p>
					<div class="panel panel-primary">
						<div class="panel-heading">Escolha as opções:</div>
						<a href="consultapreco.php5"><div class="panel-body"><h4>Consulta Preço PDV</h4></div></a>
						<a href="consultavenda.php5"><div class="panel-body"><h4>Consulta Venda por COO</h4></div></a>
                        <a href="consultanota.php5"><div class="panel-body"><h4>Consulta Nota <span class="label label-warning">new</span></h4></div></a>
					</div>
				</div>
				<div id="4" class="tab-pane fade">
					<h3>Arquivos</h3>
					<p>Geração de arquivos:</p>
					<div class="panel panel-primary">
						<div class="panel-heading">Escolha as opções:</div>
						<a href="geraarquivoxml.php5"><div class="panel-body"><h4>Gera XML de NF</h4></div></a>
					</div>
				</div>
				<div id="5"></div>
			</div>
		</div>	
	</body>
</html>			
