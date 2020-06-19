<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet'/>
    <script type="text/javascript" src='js/totalfcpdif.js'></script>
</head>
<body>
<div class="container">
    <div id="ajudaReproc861" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ZeusHammer 1.0</h4>
                </div>
                <div class="modal-body">
                    <h3>Rejeição 861 - Total FCP Difere dos Itens (Reprocessamento)</h3>
                    <hr/>
                    <p>Será exibido todas as notas da base que estão com o código do erro 861.</p>
                    <p>Ao clicar em <kbd>Reprocessar</kbd>, será localizado os itens dessas notas que estejam com CST 40/240 e com valor de FCP calculado, fazendo um update para zero nos valores de FCP.</p>
                    <p>Automaticamente essas notas serão remarcadas com a rejeição 394 para reprocessamento do RADEZ.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="titulo">
        <h2><b>Rejeição 861 <small>- Total FCP difere dos itens</small></b>
            <button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaReproc861' style='float:right;'> Ajuda</button>
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
        $conect->totalfcpdif();

        ?>

    </div>
</div>
</body>
</html>

