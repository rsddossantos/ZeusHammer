<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet'/>
		<script type="text/javascript" src="js/consulta_status.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaConsultaStatus" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Consulta Status NF</h3>
							<hr/>
							<p>Clique no botão <kbl>Atualizar</kbl> para obter novo status da nota fiscal obtida pela 
							TAB_CONTROLE_NFE<p>
                            <p>Caso o código de Erro informe alguma rejeição que exista nas opções dos botões,
                                você poderá direcionar a nota para a devida manutenção ao clicar na escolha.</p>
                            <p><code>Atenção!</code> Ao clicar, a nota será direcionada e automaticamente tratada pelo
                                programa específico, portanto tenha atenção na escolha.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
            <div id="xml" class="modal" role="dialog">
                <div class="modal-dialog" style="width:100%;margin:10px auto;">
                    <div class="modal-content" style="width: 950;margin: auto;word-wrap: break-word;">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">ZeusHammer 1.0</h4>
                        </div>
                        <div class="modal-body">
                            <?php
                            if( isset($_GET['loja']) && empty ($_GET['loja'])==false && isset($_GET['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
                                isset($_GET['serie'])  && empty ($_GET['serie'])==false &&
                                isset($_GET['nota']) && empty($_GET['nota'])==false ) {
                                $loja=addslashes($_GET['loja']);
                                $serie=addslashes($_GET['serie']);
                                $nota=addslashes($_GET['nota']);
                                $conect= new ClassBD();
                                $conect->exibeXML($loja,$serie,$nota);
                            }
                            ?>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
			<div class="titulo">
				<h2><b>Consulta Status NF</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaConsultaStatus' style='float:right;'> Ajuda</button>
				</h2>
			</div>
			<div id='gif' style='display:none;'><img id='gif' src="imagens/aguarde.gif" />
				<b>Aguarde...</b><br/><br/>
			</div>
			<div>
<?php

if( isset($_GET['loja']) && empty ($_GET['loja'])==false && isset($_GET['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
	isset($_GET['serie'])  && empty ($_GET['serie'])==false && 
	isset($_GET['nota']) && empty($_GET['nota'])==false ) {
	
	$loja=addslashes($_GET['loja']);
	$serie=addslashes($_GET['serie']);
	$nota=addslashes($_GET['nota']);
	$conect= new ClassBD();
	$conect->statusNota($loja,$serie,$nota);
}
?>				
				
			</div>
            <div id="5"></div>
		</div>	
	</body>
</html>