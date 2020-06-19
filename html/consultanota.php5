<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' />
    <script type="text/javascript" src="js/consultanota.js"></script>
</head>
<body>
<div class="container">
    <div id="ajudaNota" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ZeusHammer 1.0</h4>
                </div>
                <div class="modal-body">
                    <h3>Consulta Nota</h3>
                    <hr/>
                    <p>Digite os dados da nota e envie o formulário. O sistema irá direcionar para outro programa
                        fazer a consulta e obter o status da nota</p>
                    <p><code>Atenção!</code> Informar apenas UMA nota a cada consulta.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="titulo">
        <h2><b>Consulta Nota</b>
            <button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaNota' style='float:right;'> Ajuda</button>
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
                <input type="integer" class="form-control" id="serie" name="serie">
            </div>
            <div class="form-group col-md-4" >
                <label for="nota">Nota</label>
                <input type="text" class="form-control" id="nota" name="nota" >
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

        if( isset($_POST['loja']) && empty ($_POST['loja'])==false && isset($_POST['loja'])>0 &&	//aqui é para não deixar escolher opção default NENHUM
            isset($_POST['serie'])  && empty ($_POST['serie'])==false &&
            isset($_POST['nota']) && empty($_POST['nota'])==false ) {

            $loja=addslashes($_POST['loja']);
            $serie=addslashes($_POST['serie']);
            $nota=addslashes($_POST['nota']);
            Header("Location:consulta_status.php5?nota=$nota&loja=$loja&serie=$serie");
        }

        ?>

    </div>
</div>
</body>
</html>