<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet'/>
		<script type="text/javascript" src='js/reprocessa.js'></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaReprocNotas" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Reprocessa Notas Sefaz</h3>
							<hr/>
							<p>Será exibido somente as notas que estão com o código do erro NULO e que foram emitidas há mais de 2hs.</p>
							<p>Ao clicar em <kbd>Reprocessar</kbd>, as notas modelo 65 serão marcadas com o erro 635, para processamento do RADEZ, e as notas modelo 55 serão devolvidas para a fila como pendentes e com lote removido.</p>
							<p>Portanto, após o UPDATE, os modelos 55 ainda serão visualizados na tela até que sejam processados e os modelos 65 aparecerão gradativamente conforme o RADEZ vai processando.</p>
							<p>OBS: Só será feito update nas notas onde o tipo de emissão for 1(normal) ou 9(contingência NFCe).</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="titulo">
				<h2><b>Reprocessa Notas Sefaz</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaReprocNotas' style='float:right;'> Ajuda</button>
				</h2>
			</div>	
			<form id='target'>
				<ul class="pager">
					<li ><button style='width:150px' class="btn btn-primary glyphicon glyphicon-refresh">  Atualizar <span id="tempo"></span></button></li>
					<li><button id='reproc' type="button" style='width:150px' class="btn btn-primary glyphicon glyphicon-arrow-right" onclick='update()'> Reprocessar</button></li>
				</ul>
			</form>	
			
			<div id='gif' style='display:none;text-align: center'><img id='gif' src="imagens/aguarde.gif" />
				<b>Aguarde...</b><br/><br/>
			</div>
			<div>

<?php

$conect= new ClassBD();
$conect->reprocessaNota();
	
?>				
				
			</div>	
		</div>	
	</body>
</html>

