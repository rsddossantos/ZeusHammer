<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' />
    <script type="text/javascript" src="js/ncminexistente.js"></script>
</head>
<body>
<div class="container">
    <div id="ajudaNCM" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ZeusHammer 1.0</h4>
                </div>
                <div class="modal-body">
                    <h3>Rejeição 778 - NCM Inexistente</h3>
                    <hr/>
                    <p>Preencher os dados da nota e enviar o formulário. Será exibido o item que foi acusado pela SEFAZ com NCM inexistente
                    e, mediante pesquisa, deverá ser informado o novo NCM (pode ser com a máscara de pontos ou sem).</p>
                    <p>Esse novo NCM será substituído em todas as posições onde o item se repetir na venda determinada.</p>
                    <p>Será efetuado também um update com o código 394, para que o RADEZ gere novamente o XML e reenvie.</p>
                    <p><code>Atenção!</code> Alguns XMLs de retorno não apresentam o item recusado. Nesse caso não irá funcionar
                    pois o sistema depende dessa informação para detectar o item a ser ajustado.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="titulo">
        <h2><b>Rejeição 778 <small>- NCM Inexistente</small></b>
            <button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaNCM' style='float:right;'> Ajuda</button>
        </h2>
    </div>
    <form method='POST'>
        <div class="row">
            <div class="form-group col-md-4" >
                <label for="loja">Loja</label>
                <select id="loja" class="form-control" name="loja" >
                    <option value='0' >NENHUM</option>
                    <?php
                    echo $conect->getLoja();
                    ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="serie">Serie</label>
                <input type="number" class="form-control" id="serie" name="serie">
            </div>
            <div class="form-group col-md-4" >
                <label for="nota">Nota</label>
                <input type="number" class="form-control" id="nota" name="nota" >
            </div>
            <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary" id="enviar" onclick=clicou() id='enviar'>Enviar</button>
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

        if( isset($_REQUEST['loja']) && empty ($_REQUEST['loja'])==false && isset($_REQUEST['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
            isset($_REQUEST['serie'])  && empty ($_REQUEST['serie'])==false &&
            isset($_REQUEST['nota']) && empty($_REQUEST['nota'])==false ) {
            $loja=addslashes($_REQUEST['loja']);
            $serie=addslashes($_REQUEST['serie']);
            $nota=addslashes($_REQUEST['nota']);
            $conect= new ClassBD();
            $conect->ncmInexistente($loja,$serie,$nota);
        }
        ?>
		</div>
		<div id="5"></div>
	</div>
</body>
</html>

