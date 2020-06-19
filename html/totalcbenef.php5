<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet'/>
    <script type="text/javascript" src='js/totalcbenef.js'></script>
</head>
<body>
<div class="container">
    <div id="ajudaReproc930" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ZeusHammer 1.0</h4>
                </div>
                <div class="modal-body">
                    <h3>Rejeição 930 - Benefício Fiscal Não Informado (Reprocessamento)</h3>
                    <hr/>
                    <p>Segundo setor de impostos, os benefícios estão todos cadastrados, e os produtos com CST isento deveriam ser substitutos.
                    <p>Portanto será exibido todas as notas da base que estão com a rejeição 930.</p>
                    <p>Ao clicar em <kbd>Reprocessar</kbd>, será localizado todos os itens que estão com CST 40/240/20/220 e modificado o CST para ST (060).</p>
                    <p>Automaticamente essas notas serão remarcadas com a rejeição 394 para reprocessamento do RADEZ.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="titulo">
        <h2><b>Rejeição 930 <small>- CBenef Inexistente</small></b>
            <button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaReproc930' style='float:right;'> Ajuda</button>
        </h2>
    </div>
    <form id='target'>
        <ul class="pager">
            <li ><button style='width:150px' class="btn btn-primary glyphicon glyphicon-refresh">  Atualizar <span id="tempo"></span></button></li>
            <li><button id='reproc' type="button" style='width:150px' class="btn btn-primary glyphicon glyphicon-arrow-right" onclick='update()' disabled> Reprocessar</button></li>
        </ul>
    </form>

    <div id='gif' style='display:none;text-align: center'><img id='gif' src="imagens/aguarde.gif" />
        <b>Aguarde...</b><br/><br/>
    </div>
    <div>

        <?php

        $conect= new ClassBD();
        $conect->totalcbenef();

        ?>

    </div>
</div>
</body>
</html>

