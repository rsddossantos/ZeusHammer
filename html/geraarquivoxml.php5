<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href=''/>
		<script type="text/javascript" src="js/geraarquivoxml.js"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaGeraXML" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Gera XML de NF</h3>
							<hr/>
                            <p><code>ATENÇÃO!!!</code> Antes de iniciar o processo certifique-se que não tem nenhum arquivo antigo
                            na pasta de gravação!</p>
							<p>Escolha a opção do tipo de arquivo. No formulário pode ser digitado o ID da nota ou quantos ids forem
							necessários desde que separados por vírgula. Não pode ser inserido aspas simples nem qualquer outro caractere separador.
							Ao clicar no botão <kbd>Enviar</kbd> serão gerados os arquivos de acordo com os ids localizados.</p>
							<p>Caso for um range de notas muito grande, a tabela <code>TEMP_NOTAS_XML</code> pode ser
							alimentada através deste  
								<a data-toggle="tooltip" data-placement="bottom" title="INSERT INTO TEMP_NOTAS_XML (ID_NFE,DSC_XML_NFE,DSC_XML_RETORNO_NFE)
								(select id_nfe,DSC_XML_NFE,DSC_XML_RETORNO_NFE from tab_controle_nfe
								where id_nfe in 
								('id_nfe1','id_nfe2','id_nfe3',...))">
								comando
								</a>.
							Mais abaixo temos a segunda opção, onde o botão <kbd>Gerar</kbd> irá comandar a geração dos arquivos de todas as notas encontradas no objeto referido.
                                <code>ATENÇÃO!!!</code> Antes de usar essa opção certifique-se que a tabela <code>TEMP_NOTAS_XML</code> está vazia.</p>
							<p>Em ambas as opções, os arquivos serão gerados em <code>/manager_suporte/arquivos</code></p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>
            <div id="ajudaGeraXML_tab" class="modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">ZeusHammer 1.0</h4>
                        </div>
                        <div class="modal-body">
                            <h3>Gera XML de NF - TEMP_NOTAS_XML</h3>
                            <hr/>
                            <p><code>ATENÇÃO!!!</code> Antes de iniciar o processo certifique-se que não tem nenhum dado na tabela TEMP_NOTAS_XML !</p>
                            <p>Essa tabela pode ser alimentada com um número indeterminado de notas através do comando abaixo, bastando
                                informar todos os ids de nota no filtro:</p>
                            <p><b>INSERT INTO TEMP_NOTAS_XML (ID_NFE,DSC_XML_NFE,DSC_XML_RETORNO_NFE)<br/>
								(select id_nfe,DSC_XML_NFE,DSC_XML_RETORNO_NFE from tab_controle_nfe<br/>
								where id_nfe in ('id_nfe1','id_nfe2','id_nfe3',...))</br></p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
			<div class="titulo">
				<h2><b>Gera XML de NF</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaGeraXML' style='float:right;'> Ajuda</button>
				</h2>
			</div>
			<form method='POST'>
				<div class="row">
                    <div class="form-group col-md-12" >
                        <label for="defaultChecked"><h4><b>Escolha a opção do arquivo:</b></h4></label>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="defaultChecked" value=1 name="tipo" checked>
                            <label class="custom-control-label" for="defaultChecked">Somente XML de envio</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="defaultUnchecked" value=2 name="tipo">
                            <label class="custom-control-label" for="defaultUnchecked">XML de envio e retorno</label>
                        </div>
                    </div>
					<div class="form-group col-md-12" >
                        <label for="id_nfe"><h4><b>Digite o ID_NFE ou um range de ids separados por vírgula:</b></h4></b></h4></label>
						<input type="text" class="form-control" id="id_nfe" name="id_nfe" >
					</div>	
					<div class="form-group col-md-12">
						<button type="submit" class="btn btn-primary" id="enviar" onclick=clicou()>Enviar</button>
					</div>			
				</div>
			</form>	
			<div id='gif' style='display:none;'><img id='gif' src="imagens/aguarde.gif" />
				<b>Aguarde...</b><br/><br/>
			</div>
            <div class='alert alert-danger fade in' id='controle' style='display:none'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <b>Todos os campos devem estar preechidos antes de enviar!</b>
            </div>
			<div class='alert alert-info fade in' style='margin-bottom:5%;' id='temp_notas'>
                <b>Ou clique no botão para efetuar a geração a partir da TEMP_NOTAS_XML
                    <a class='glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaGeraXML_tab' style='font-size:20px;cursor:pointer;text-decoration:none'></a>
                </b><br/><br/>
				<button class="btn btn-primary" id="gerar" onclick=update()>Gerar</button>
			</div>
			<div>
<?php

if( isset($_REQUEST['id_nfe']) && empty($_REQUEST['id_nfe'])==false ) {
	
$id_nfe=addslashes($_REQUEST['id_nfe']);
$tipo=$_REQUEST['tipo'];
$conect= new ClassBD();
$conect->GeraArquivoXml($id_nfe,$tipo);
	
}
?>					
			</div>
			<div id="5"></div>
		</div>	
	</body>
</html>