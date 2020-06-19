<!DOCTYPE html>
<html>
	<head>
        <meta charset="UTF-8">
		<link rel='stylesheet' href='css/consulta_preco.css'/>
		<script type="text/javascript"></script>
	</head>
	<body>
		<div class="container"> 
			<div id="ajudaConsPrec" class="modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">ZeusHammer 1.0</h4>
						</div>
						<div class="modal-body">
							<h3>Consulta Preço PDV</h3>
							<hr/>
							<p>Escolha a loja, número do pdv e digite o código da mercadoria sem zeros à esquerda. 
							Ao enviar será feito a consulta no WS do pdv (o mesmo que responde para o Busca Preço),
							retornando a descrição e preço, contidos no arquivo 6.sdf.
							Caso a janela retornar vazia ou com erro, é porque foi isso que o WS do pdv retornou.
							Existe um link para efetuar a mesma consulta em outra aba caso necessário conferência.</p>
							<p>Mais abaixo, teremos um link para consulta no ZeusManager, mas só funcionará para códigos
							internos e mediante ao sistema estar logado em outra aba do navegador.</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-primary" data-dismiss="modal">Fechar</button>
						</div>
					</div>
				</div>
			</div>	
			<div class="titulo">
				<h2><b>Consulta Preço PDV</b>
				<button class='btn btn-primary glyphicon glyphicon-question-sign' data-toggle='modal' data-target='#ajudaConsPrec' style='float:right;'> Ajuda</button>
				</h2>
			</div>
			<div id="5"></div>
			<div class="area">
				<form  method="POST" >
					<table align=center >
						<tr>
							<td><b>Loja:&nbsp;</td>
								<td>
									<select id="Loja" name="lojas">
										<option value='0' >NENHUM</option>
											<?php
											echo $conect->getLoja();
											?>
									</select>
								</td>	
							<td>&nbsp;</td>
							<td><b>Pdv:&nbsp;</td>
							<td><input type='number' name='pdv' id='pdv' autocomplete="off" style="width:80px"></td>
							<td>&nbsp;</td>
							<td><b>Mercadoria:&nbsp;</td>
							<td><input type='number' name='mercadoria' id='mercadoria'></td>
							<td>&nbsp;</td>
							<td><input type="submit" value="Enviar" id='botao' /></td>
						</tr> 
					</table> 	
				</form>	
				<div class="result">
<?php	

if(isset($_POST['lojas']) && empty($_POST['lojas'])==false &&
isset($_POST['pdv']) && empty($_POST['pdv'])==false &&
isset($_POST['mercadoria']) && empty($_POST['mercadoria'])==false){

    $lojas=addslashes($_POST['lojas']);
    $pdv=addslashes($_POST['pdv']);
    $mercadoria=addslashes($_POST['mercadoria']);
    $consulta='http://10.52.'.$lojas.'.'.$pdv.':8877/serv/TC/6/'.$mercadoria;
    echo '<b>Consulta realizada em:</b><br/>
      <a href="'.$consulta.'" target="_blank">'.$consulta.'</a><br/>';
    echo "<iframe src='".$consulta."' height='35' width='400' align='center'></iframe><br/>";
    echo '<a href="http://zeus/manager/cad_mercadoria.php5?cod_loja='.$lojas.'&cod_mercadoria='.str_pad($mercadoria,17,0,STR_PAD_LEFT).'&opt=Alt" 
      target="_blank" data-toggle="tooltip" data-placement="bottom" title="(somente cód. interno) Necessário estar logado no ZeusManager em outra aba">
	  <b>Clique aqui para acessar no ZeusManager </a></b><br/><hr/>';
    echo "<b>Promoção Cartão MAKRO:<br/></b>";
    $conect->consultaPreco($lojas,$mercadoria);
    echo '<a style="cursor: pointer;" onclick="window.open(\'relpromo.php5?merc='.$mercadoria.'&loja='.$lojas.'\',\'_blank\',\'width=1000,height=380\')"><b>Clique para verificar outras promoções</b></a>';
}
?>
				</div>
			</div>
		</div>
	</body>	
</html>