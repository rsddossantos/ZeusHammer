<?php
/*
 * @author: Rodrigo Silveira 
 * @version: 1.0
 */

class ClassBD{

	// Variáveis de conexão
	public $db="";
	public $conn ="";
	public $file="config/config.ini";

	// Conecta com o banco
	public function conectBanco() {
		//lendo config.ini
		if(file_exists($this->file)){
			$params=file($this->file);
			//string:
			$this->db=trim($params[1]);
			$pos=strpos($this->db,':');
			$this->db=substr($this->db,$pos+1,1024);
			//usuario:
			$user_bd=trim($params[2]);
			$pos=strpos($user_bd,':');
			$user_bd=substr($user_bd,$pos+1,1024);
			//senha:
			$senha_bd=trim($params[3]);
			$pos=strpos($senha_bd,':');
			$senha_bd=substr($senha_bd,$pos+1,1024);
			$this->conn = oci_new_connect($user_bd, $senha_bd,$this->db, american.we8iso8859p1);
			if ($this->conn==TRUE){
				//echo "<div style='font-size:17px;position:fixed;right:0px;bottom:0px;'><b>Conexão no banco com sucesso!!!</b></div>";
				}
				else{
					$e=oci_error();
					return $e['message'];
					//echo "<div style='font-size:17px;color:red;position:fixed;right:0px;bottom:0px;'><b>Erro no banco de dados: ".$e['message']."</b></div>";
			}
		}else{
			return false;
			//echo "<div style='font-size:17px;color:red;position:fixed;right:0px;bottom:0px;'><b>Falha ao ler arquivo config/config.ini</b></div>";
			//die();
		}
	}

	// Validação de login ( permite somente perfil 20)
	function login($usuario,$senha){
		if ($this->conectBanco()===false){
			echo "<script type='text/javascript'>confirm('ARQUIVO DE CONFIGURAÇÃO NÃO ENCONTRADO!<br/>Deseja efetuar a configuração de acesso?');</script>";
		} else{
			$errobd=strpos($this->conectBanco(),'-');
			echo $errobd;
			if ($errobd==3){
				$e=$this->conectBanco();
				echo "<script type='text/javascript'>confirm('COMANDO NÃO EXECUTADO!</br><b>$e</b><br/><br/>Deseja efetuar a configuração de acesso?');</script>";
			} else{
				$query="SELECT * FROM TAB_FUNCIONARIO FUNC
					WHERE (FUNC.NOME='$usuario' AND FUNC.SENHA='$senha') OR	
					(TO_CHAR(FUNC.COD_FUNCIONARIO)='$usuario' AND FUNC.SENHA='$senha' )";
				$parse=oci_parse($this->conn,$query);
				ociexecute($parse);
				$rows=oci_fetch_all($parse,$campos);
				if ($parse==TRUE){
					if ($rows>0) {
						$query2="SELECT * FROM
								(SELECT FUNC.COD_FUNCIONARIO,FUNC.NOME,PERF.COD_PERFIL 
								FROM TAB_FUNCIONARIO FUNC
								inner JOIN TAB_PERFIL_FUNCIONARIO PERF ON
								FUNC.COD_FUNCIONARIO=PERF.COD_FUNCIONARIO
								WHERE (FUNC.NOME='$usuario' AND FUNC.SENHA='$senha') OR	
								(TO_CHAR(FUNC.COD_FUNCIONARIO)='$usuario' AND FUNC.SENHA='$senha' ))X
								WHERE COD_PERFIL=20 ";
						$parse2=oci_parse($this->conn,$query2);
						ociexecute($parse2);
						$rows2=oci_fetch_all($parse2,$campos2);
						if ($rows2>0){
							$_SESSION['id']=$usuario;
							echo '<script type="text/javascript">
								alert("ATENÇÃO!!! Alguns programas executam alterações diretamente no banco de produção!!! Tenha atenção ao selecionar os dados pois a ação será irreversível!!!");
								</script>';
						}else{
							echo "<script type='text/javascript'>alert('SEM PERMISSÃO DE ACESSO!');</script>";
						}
					}else{
						echo "<script type='text/javascript'>alert('USUÁRIO/SENHA INVÁLIDO!');</script>";
						}
				}else {
					$e=oci_error();
					$erro=$e['message'];
					echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO!<br/><b>$erro</b>');</script>";
				}
				oci_free_statement($parse);
			}
		}
	}

	// Seleciona lojas
	function getLoja(){
		$this->conectBanco();
		$query=oci_parse($this->conn,"select cod_loja,des_fantasia from tab_loja order by cod_loja");
		$sql=ociexecute($query);
		while (($row = oci_fetch_object($query)) != false) {
          	echo "<option value=".$row->COD_LOJA.">".$row->DES_FANTASIA."</option>";
        }
		oci_free_statement($parse);
	}

	// Valida usuário de login
	function getUser($user){
		$this->conectBanco();
		$query="SELECT DISTINCT FUNC.COD_FUNCIONARIO,FUNC.NOME FROM TAB_FUNCIONARIO FUNC
		WHERE (FUNC.NOME='$user') OR (TO_CHAR(FUNC.COD_FUNCIONARIO)='$user')";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$campos);
		return $campos;
	}

	function reenviaCupom($data,$loja,$pdv,$cupom){
		$this->conectBanco();
		$query="Select m00zza01 from zan_m00 where M00AF=to_date('$data','yyyy-mm-dd') and M00ZA=$loja and M00AC=$pdv and M00AD in ($cupom)";
		$parse=oci_parse($this->conn,$query);
		$sql=ociexecute($parse);
		$rows=oci_fetch_all($parse,$item);
		if ($parse==TRUE){
			if ($rows>0) {
				echo "<div class='alert alert-success fade in' >
			    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			    <b>Foi selecionado $rows cupom(s)</b>
		        </div>";
				oci_free_statement($parse);
				//aqui entrará o update
				$query="update zan_m00 set m00zza01=0 where M00AF=to_date('$data','yyyy-mm-dd') and M00ZA=$loja and M00AC=$pdv and M00AD in ($cupom)";
				$parse=oci_parse($this->conn,$query);
				$sql=ociexecute($parse);
				// aqui entrará o retorno desse update
				if (oci_num_rows($parse)>0) {
					echo "<div class='alert alert-success fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Reprocessamento com sucesso! Data: ".date('d/m/Y', strtotime($data))." Loja: $loja Pdv: $pdv Cupom(s): $cupom</b>
					</div>";
					$this->geraLog();
					oci_free_statement($parse);
				}else{
					echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Erro no Update! Indisponibilidade no banco, tente novamente</b>
					</div>";
					}
			}else{
				echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Cupom não encontrado! Verifique os dados digitados e tente novamente!</b>
					</div>";
				echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					  Clique para ver a query
					  </button>
					  <div class='collapse' id='query' >
					  	<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					  </div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in' >
				  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			   	  <b>Erro no Banco! Tente novamente!</b>
				  </div>";
			$e=oci_error();
			echo "<br/><br/>Retorno do Banco: ".$e['message'];
			}
		oci_free_statement($sql);
		oci_close($conn);
	}

	function reenviaNota($loja,$serie,$nota){
		$this->conectBanco();
		$query="Select flg_integracao_01 from tab_nota_header where codloja=$loja and serie_nf=$serie and numnota in ($nota)";
		$parse=oci_parse($this->conn,$query);
		$sql=ociexecute($parse);
		$rows=oci_fetch_all($parse,$item);
		if ($parse==TRUE){
			if ($rows>0) {
				echo "<div class='alert alert-success fade in'>
			    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			    <b>Foi selecionado $rows nota(s)</b>
		        </div>";
				oci_free_statement($parse);
				//aqui entrará o update
				$query="update tab_nota_header set flg_integracao_01=0 where codloja=$loja and serie_nf=$serie and numnota in ($nota)";
				$parse=oci_parse($this->conn,$query);
				$sql=ociexecute($parse);
				// aqui entrará o retorno desse update
				if (oci_num_rows($parse)>0) {
					echo "<div class='alert alert-success fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Reprocessamento com sucesso! Loja: $loja Serie: $serie Nota(s): $nota</b>
					</div>";
					$this->geraLog();
					oci_free_statement($parse);
				}else{
					echo "<div class='alert alert-danger fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Erro no Update! Indisponibilidade no banco, tente novamente</b>
					</div>";
					}
			}else{
				echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Nota não encontrada! Verifique os dados digitados e tente novamente!</b>
					</div>";
				echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					  Clique para ver a query
					  </button>
					  <div class='collapse' id='query' >
					  	<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					  </div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in' >
				  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			   	  <b>Erro no Banco! Tente novamente!</b>
				  </div>";
			$e=oci_error();
			echo "<br/><br/>Retorno do Banco: ".$e['message'];
			}
		oci_free_statement($sql);
		oci_close($conn);
	}

	function reprocessaNota(){
		$this->conectBanco();
		$query="SELECT ID_NFE,
				TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<nNF>') + 5, INSTR(DSC_XML_NFE, '</nNF>') - INSTR(DSC_XML_NFE, '<nNF>') - 5 ) ) AS NOTA,
				TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<serie>') + 7, INSTR(DSC_XML_NFE, '</serie>') - INSTR(DSC_XML_NFE, '<serie>') - 7 ) ) AS SERIE,
				COD_TIPO_EMISSAO_NFE,COD_SITUACAO_ENVIO_NFE,COD_ERRO_NFE,COD_EMPRESA,TRUNC(DTH_INCLUSAO) DATA,TIPO_MODELO_NF,ID_LOTE_NFE 
				FROM TAB_CONTROLE_NFE 
				WHERE COD_ERRO_NFE IS NULL AND DTH_INCLUSAO BETWEEN SYSDATE -35 AND SYSDATE -2/24";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$notas);
		echo "<div class='alert alert-info' style='width:350px;'><b>Total de Ocorrências: <span style='font-size:20px;'>$rows</span></b></div>";
		if ($parse==TRUE){
			if ($rows>0) {
				?>
				<table class="table table-striped" >
					<thead>
						<tr>
							<th>Id_nfe</th>
							<th>Nota</th>
							<th>Serie</th>
							<th>Emissão</th>
							<th>Situação</th>
							<th>Erro</th>
							<th>Empresa</th>
							<th>Data</th>
							<th>Modelo</th>
							<th>Lote</th>
						</tr>
					</thead>
				<?php
				$parse2=oci_parse($this->conn,$query);
				oci_execute($parse2);
				while (($row=oci_fetch_object($parse2)) != false){
					echo '<tr>';
                    echo "<td><a href='consulta_status.php5?nota=".$row->NOTA."&loja=".$row->COD_EMPRESA."&serie=".$row->SERIE."' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Consultar nota\">".$row->ID_NFE."</a></td>";
					echo '<td>'.$row->NOTA."</td>";
					echo '<td>'.$row->SERIE."</td>";
					echo '<td>'.$row->COD_TIPO_EMISSAO_NFE."</td>";
					echo '<td>'.$row->COD_SITUACAO_ENVIO_NFE."</td>";
					echo '<td>'.$row->COD_ERRO_NFE."</td>";
					echo '<td>'.$row->COD_EMPRESA."</td>";
					echo '<td>'.date('d/m/Y', strtotime($row->DATA))."</td>";
					echo '<td>'.$row->TIPO_MODELO_NF."</td>";
					echo '<td>'.$row->ID_LOTE_NFE."</td>";
					echo '</tr>';
				}
				oci_free_statement($parse2);
				echo "</table>";
			}else{
				echo "<div class='alert alert-danger fade in' style='width:350px;'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Não existem notas a serem reprocessadas!</b>
				</div>
				<script>$('#reproc').remove()</script>";
			}
		}else {
				echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Erro no banco!</b>
				</div>";
				$e=oci_error();
				echo "Retorno do Banco: ".$e['message'];
				die();
		}
		oci_free_statement($parse);
	}

	function liberaCupom($data,$loja,$pdv,$cupom){
		$this->conectBanco();
		$query="Select m00ad from zan_m01 where M00AF=to_date('$data','yyyy-mm-dd') and M00ZA=$loja and M00AC=$pdv and M00AD in ($cupom) and m01ak>0 
		        UNION ALL
				Select m00ad from zan_m45 where M00AF=to_date('$data','yyyy-mm-dd') and M00ZA=$loja and M00AC=$pdv and M00AD in ($cupom) and m45ak>0";
		$parse=oci_parse($this->conn,$query);
		$sql=ociexecute($parse);
		$rows=oci_fetch_all($parse,$item);
		if ($parse==TRUE){
				if ($rows>0) {
					echo "<div class='alert alert-success fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Foi selecionado $rows cupom(s)</b>
					</div>";
					oci_free_statement($parse);
					//aqui entrará os 2 updates, somente o que trouxer modificação será exibido
					$query1="update zan_m03 set qtd_trocado=null,qtd_reembolso=null,val_reembolso=null where M00AF=to_date('$data','yyyy-mm-dd') and M00ZA=$loja and M00AC=$pdv and M00AD in ($cupom)";
					$query2="update zan_m43 set qtd_trocado=null,qtd_reembolso=null,val_reembolso=null where M00AF=to_date('$data','yyyy-mm-dd') and M00ZA=$loja and M00AC=$pdv and M00AD in ($cupom)";
					$parse1=oci_parse($this->conn,$query1);
					ociexecute($parse1);
					$parse2=oci_parse($this->conn,$query2);
					ociexecute($parse2);
					if ($parse1==TRUE && $parse2==TRUE){
						// aqui entrará o retorno dos updates. Lembrando que só exibirá o retorno de quem trouxer linhas modificadas
						if (oci_num_rows($parse1)>0) {
							$this->geraLog();
							echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Update com sucesso! Sua venda é um Cupom Fiscal! Itens alterados: ".oci_num_rows($parse1)."</b>
							</div>";
							echo "<div class='alert alert-success fade in'>
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Dados da venda: Data: ".date('d/m/Y', strtotime($data))." Loja: $loja Pdv: $pdv Cupom(s): $cupom</b>
							</div>";
							oci_free_statement($parse1);
						}
						if (oci_num_rows($parse2)>0) {
							$this->geraLog();
							echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Update com sucesso! Sua venda é um Documento Eletrônico! Itens alterados: ".oci_num_rows($parse2)."</b>
							</div>";
							echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Data: ".date('d/m/Y', strtotime($data))." Loja: $loja Pdv: $pdv Cupom(s): $cupom</b>
							</div>";
							oci_free_statement($parse2);
						}

					}else{
						echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Erro no Update! Indisponibilidade no banco, tente novamente</b>
						</div>";
						}
				}else{
					echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Venda não encontrada! Verifique os dados digitados e tente novamente!</b>
					</div>";
					echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					  Clique para ver a query
					  </button>
					  <div class='collapse' id='query' >
					  	<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					  </div>";
				}
		}else{
				echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Erro no banco!</b>
				</div>";
				$e=oci_error();
				echo "Retorno do Banco: ".$e['message'];
				die();
				}
		oci_free_statement($sql);
	}

	function reimprime($data,$loja){
		$this->conectBanco();
		$querypart1="select tipo,voucher,to_char(data_cadastro,'dd-mm-yyyy') data_cadastro,cod_loja,flg_status_vale,flg_impresso from (select 'REEMBOLSO' tipo,rcf.cod_registro voucher,rc.data_cadastro,rcf.cod_loja,rcf.flg_status_vale,rcf.flg_impresso ";
		$querypart2="from tab_reembolso_cliente_final rcf left join tab_reembolso_cliente rc on rcf.cod_loja=rc.cod_loja and rcf.cod_registro=rc.cod_registro union all";
		$querypart3=" select 'TROCA' tipo,cod_troca voucher,trunc(data_cadastro),cod_loja,flg_status_vale,flg_impresso from tab_troca_vale)x where data_cadastro=to_date('$data','yyyy-mm-dd') and cod_loja=$loja order by tipo,voucher";
		$query=$querypart1.$querypart2.$querypart3;
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$troca);
		if ($parse==TRUE){
				if ($rows>0) {
					?>
						<table class='table table-striped'>
							<thead>
								<tr>
									<th>TIPO</th>
									<th>VOUCHER</th>
									<th>DATA</th>
									<th>LOJA</th>
									<th>STATUS</th>
									<th>REIMPRESSO</th>
									<th>AÇÃO</th>
								</tr>
							</thead>
					<?php
					$parse2=oci_parse($this->conn,$query);
					oci_execute($parse2);
					while (($row=oci_fetch_object($parse2)) != false){
						echo '<tr>';
						echo '<td>'.$row->TIPO."</td>";
						echo '<td>'.$row->VOUCHER."</td>";
						echo '<td>'.$row->DATA_CADASTRO."</td>";
						echo '<td>'.$row->COD_LOJA."</td>";
						echo '<td>'.$row->FLG_STATUS_VALE."</td>";
						echo '<td>'.$row->FLG_IMPRESSO."</td>";
						echo '<td><a href="reimprime_update.php5?id='.$row->VOUCHER.'&loja='.$row->COD_LOJA.'&tipo='.$row->TIPO.'&status='.$row->FLG_STATUS_VALE.'&reimpresso='.$row->FLG_IMPRESSO.'&data='.$row->DATA_CADASTRO.'">EFETIVAR</a></td>';
						echo '</tr>';
					}
					oci_free_statement($parse2);
					echo "</table>";
				}else{
					echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Nenhuma devolução encontrada para essa data e loja!</b>
					</div>";
					echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
							Clique para ver a query
							</button>
							<div class='collapse' id='query' >
								<div class='card card-body'>
									<div class='alert alert-info fade in' >
									$query
									</div>
								</div>
							</div>";
				}
			}else {
				echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Erro no banco!</b>
				</div>";
				$e=oci_error();
				echo "Retorno do Banco: ".$e['message'];
				die();
			}
			oci_free_statement($parse);
	}

	function geraControle($loja,$serie,$nota){
		$this->conectBanco();
		$query="SELECT numnota FROM TAB_NOTA_HEADER WHERE NUMNOTA in ($nota) AND CODLOJA=$loja AND SERIE_NF=$serie";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$dados);
		if ($parse==TRUE){
			if ($rows>0) {
				$insert="INSERT INTO TAB_CONTROLE_NFE(ID_NFE,COD_TIPO_EMISSAO_NFE,COD_SITUACAO_ENVIO_NFE,COD_FINALIDADE_NFE,COD_ERRO_NFE,COD_UNIDADE,COD_EMPRESA,DTH_INCLUSAO,DTH_ALTERACAO,DTH_RECBTO,TIPO_MODELO_NF,ID_VERSAO_LAYOUT_NFE)(SELECT ID_NFE,'1','RP','NN','394',CODLOJA,CODLOJA,SYSDATE,SYSDATE,SYSDATE,TIPO_MODELO_NF,'4' FROM TAB_NOTA_HEADER WHERE NUMNOTA in ($nota) AND CODLOJA=$loja AND SERIE_NF=$serie)";
				$update="UPDATE TAB_NOTA_HEADER SET FLG_INTEGRACAO_01=0 WHERE NUMNOTA in ($nota) AND CODLOJA=$loja AND SERIE_NF=$serie";
				$parse2=oci_parse($this->conn,$insert);
				ociexecute($parse2);
				if ($parse2==TRUE){
					$result=oci_num_rows($parse2);
					if ($result>0){
						$parse3=oci_parse($this->conn,$update);
						ociexecute($parse3);
						echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Inserido com sucesso! Registros inseridos: ".$result."</b>
							</div>";
						echo "<a href='consulta_status.php5?nota=$nota&loja=$loja&serie=$serie' class='btn btn-info active' role='button' aria-pressed='true'>
							Clique para ver o status da nota</a><br/><br/>";
						$this->geraLog();
					}else {
						echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Registro inserido: ".$result." (Provavelmente já existe registro na TAB_CONTROLE)</b>
						</div>";
					}
					oci_free_statement($insert);
				}else{
					echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Comando não executado, tente novamente!</b>
					</div>";
					$e=oci_error();
					echo "Retorno do Banco: ".$e['message'];
					die();
				}
			}else{
				echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Nenhuma nota encontrada, verifique os dados e tente novamente!</b>
					</div>";
				echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
						Clique para ver a query
						</button>
						<div class='collapse' id='query' >
							<div class='card card-body'>
								<div class='alert alert-info fade in' >
								$query
								</div>
							</div>
						</div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Erro no banco!</b>
				</div>";
				$e=oci_error();
				echo "Retorno do Banco: ".$e['message'];
				die();
		}
		oci_free_statement($parse);
	}

	function statusNota($loja,$serie,$nota){
		$this->conectBanco();
		$query="SELECT ID_NFE,
				TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<nNF>') + 5, INSTR(DSC_XML_NFE, '</nNF>') - INSTR(DSC_XML_NFE, '<nNF>') - 5 ) ) AS NOTA,
				TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<serie>') + 7, INSTR(DSC_XML_NFE, '</serie>') - INSTR(DSC_XML_NFE, '<serie>') - 7 ) ) AS SERIE,
				COD_TIPO_EMISSAO_NFE,COD_SITUACAO_ENVIO_NFE,COD_ERRO_NFE,COD_EMPRESA,TRUNC(DTH_INCLUSAO) DATA,TIPO_MODELO_NF,ID_LOTE_NFE,DSC_XML_NFE 
				FROM TAB_CONTROLE_NFE 
				WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA =$nota AND SERIE_NF=$serie AND CODLOJA=$loja)";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		if ($parse==TRUE){
			$row=oci_fetch_object($parse);
			if ($row>0){
				?>
				<table class="table table-striped" >
						<thead>
							<tr>
								<th>Id_nfe</th>
								<th>Nota</th>
								<th>Serie</th>
								<th>Emissão</th>
								<th>Situação</th>
								<th>Erro</th>
								<th>Loja</th>
								<th>Data</th>
								<th>Mod</th>
								<th>Lote</th>
                                <th></th>
							</tr>
						</thead>
				<?php
				echo "<form><div class='row'>
						<div class='form-group col-md-12'>
							<a href='consulta_status.php5?nota=$nota&loja=$loja&serie=$serie' class='btn btn-primary glyphicon glyphicon-refresh' role='button' aria-pressed='true' > Atualizar</a>
						</div>			
					</div></form>";
				$parse2=oci_parse($this->conn,$query);
				oci_execute($parse2);
				while (($row2=oci_fetch_object($parse2)) != false){
						echo '<tr>';
						echo "<td><a href='' data-toggle='modal' data-target='#xml' >".$row->ID_NFE."</a></td>";
						echo '<td>'.$row->NOTA."</td>";
						echo '<td>'.$row->SERIE."</td>";
						echo '<td>'.$row->COD_TIPO_EMISSAO_NFE."</td>";
						echo '<td>'.$row->COD_SITUACAO_ENVIO_NFE."</td>";
						echo '<td>'.$row->COD_ERRO_NFE."</td>";
						echo '<td>'.$row->COD_EMPRESA."</td>";
						echo '<td>'.date('d/m/Y', strtotime($row->DATA))."</td>";
						echo '<td>'.$row->TIPO_MODELO_NF."</td>";
						echo '<td>'.$row->ID_LOTE_NFE."</td>";
                        echo "<td><a href='consulta_status_reprocessa.php5?nota=$nota&loja=$loja&serie=$serie' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Reprocessar com erro 394\"><img src='imagens/refresh-page-arrow-button.png' width='20' /></a></td>";
						echo '</tr>';
				}
				echo "</table>";
				echo "<div class='alert alert-info fade in' >
						<b>Caso queira direcionar a nota clique nas opções abaixo!</b></div>";
				echo "<div class='btn-group btn-group-justified' style='width: 100%;margin:auto;margin-bottom: 5%;box-shadow: 0px 0px 11px -2px rgba(0,0,0,0.75);'>
			    	        <a href='notasemcest.php5?loja=".$loja."&serie=".$serie."&nota=".$nota."' class='btn btn-primary' >Rejeição 806<br/>Nota sem CEST</a>
				            <a href='fcpinvalido.php5?loja=".$loja."&serie=".$serie."&nota=".$nota."' class='btn btn-primary' >Rejeição 874<br/>FCP Inválido</a>
				            <a href='ncminexistente.php5?loja=".$loja."&serie=".$serie."&nota=".$nota."' class='btn btn-primary' >Rejeição 778<br/>NCM Inexistente</a>
				            <a href='cpfinvalido.php5?loja=".$loja."&serie=".$serie."&nota=".$nota."' class='btn btn-primary' >Rejeição 237<br/>CPF Inválido</a>
				            <a href='totalbcdif.php5?loja=".$loja."&serie=".$serie."&nota=".$nota."' class='btn btn-primary' >Rejeição 531<br/>Total BC Difere Itens</a>  
				            <a href='totalfcpdif.php5' class='btn btn-primary' >Rejeição 861<br/>Total FCP Difere Itens</a> 
				      </div>";
			}else{
				echo "<div class='alert alert-danger fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota não encontrada! Clique no ícone <kbd><</kbd> no menu principal e refaça a pesquisa!</b>
							</div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Comando não executado, tente novamente!</b>
				</div>";
				$e=oci_error();
				echo "Retorno do Banco: ".$e['message'];
				die();
		}
	}

	function geraCest($loja,$serie,$nota){
		$this->conectBanco();
		$query="SELECT * FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$qtde);
		if ($parse==TRUE){
			if ($rows>0) {
				$update1="UPDATE TAB_NOTA_ITEM SET CEST='2899900' 
							WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie AND coalesce(CEST,0)=0";
				$update2="UPDATE TAB_CONTROLE_NFE SET cod_situacao_envio_nfe='RP',cod_erro_nfe=394,cod_erro_1 = '806',cod_erro_2 = null , cod_erro_3 = null, cod_erro_4 = null, controle_robo = null 
							WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
				$parse2=oci_parse($this->conn,$update1);
				ociexecute($parse2);
				$resultupdate1=oci_num_rows($parse2);
				if ($parse2==TRUE){
					if ($resultupdate1>0){
						$parse3=oci_parse($this->conn,$update2);
						ociexecute($parse3);
						$resultupdate2=oci_num_rows($parse3);
						echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Alterado com sucesso! Qtde item(s) alterado(s): ".$resultupdate1."</b>
							</div>";
						$this->geraLog();
						if ($resultupdate2>0){
							echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota introduzida na fila de reprocessamento com sucesso</b>
							</div>";
							echo "<a href='consulta_status.php5?nota=$nota&loja=$loja&serie=$serie' class='btn btn-info active' role='button' aria-pressed='true'>
							Clique para ver o status da nota</a><br/><br/>";
						}else {
							echo "<div class='alert alert-danger fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota não encontrada para ser reprocessada, verifique se existe na TAB_CONTROLE_NFE!</b>
							</div>";
						}
						oci_free_statement($parse3);
					}else {
						echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Nenhum item sem CEST! Verifique a nota e tente novamente!</b>
						</div>";
                        echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					        Clique para ver a query
					        </button>
					        <div class='collapse' id='query' >
					    	    <div class='card card-body'>
							        <div class='alert alert-info fade in' >
							        $update1
							        </div>
						        </div>
					        </div>";
					}
					oci_free_statement($parse2);
				}else{
					echo "<div class='alert alert-danger fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Comando não executado, tente novamente!</b>
					</div>";
					$e=oci_error();
					echo "Retorno do Banco: ".$e['message'];
					die();
				}
			}else{
			echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Nenhuma nota encontrada! Verifique os dados e tente novamente!</b>
				</div>";
			echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					Clique para ver a query
					</button>
					<div class='collapse' id='query' >
						<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					</div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Comando não executado, tente novamente!</b>
			</div>";
			$e=oci_error();
			echo "Retorno do Banco: ".$e['message'];
			die();
		}
		oci_free_statement($parse);
	}

	function GeraArquivoXml($id_nfe,$tipo){
        $query="Select id_nfe,dsc_xml_nfe,dsc_xml_retorno_nfe from tab_controle_nfe where id_nfe in (".$id_nfe.")";
		$this->conectBanco();
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$item);
		if ($parse==TRUE){
			if ($rows>0) {
				echo "<div class='alert alert-success fade in'>
			    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			    <b>$rows arquivo(s) gerado(s) com sucesso! Verifique a pasta <kbd>arquivos/</kbd></b>
		        </div>";
				echo "<script type='text/javascript'>$('#temp_notas').remove();</script>";
				$this->geraLog();
				$parse2=oci_parse($this->conn,$query);
				ociexecute($parse2);
				$rows2=oci_fetch_all($parse2,$campos);
				echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#lista' aria-expanded='false' aria-controls='collapseExample'	>
					  Clique para ver a lista
					  </button>
					  <div class='collapse' id='lista' >
					  	<div class='card card-body' >
							<div class='alert alert-info fade in'>";
				if ($tipo==1) {
                    for ($x = 0; $x < $rows2; $x++) {
                        file_put_contents('arquivos/' . $campos[ID_NFE][$x] . '.xml', $campos[DSC_XML_NFE][$x]);
                        echo "Arquivo: <b>" . $campos[ID_NFE][$x] . '.xml </b> criado!<br/>';
                    }
                }else{
                    for ($x = 0; $x < $rows2; $x++) {
                        file_put_contents('arquivos/' . $campos[ID_NFE][$x] . '.xml', $campos[DSC_XML_NFE][$x].PHP_EOL.$campos[DSC_XML_RETORNO_NFE][$x]);
                        echo "Arquivo: <b>" . $campos[ID_NFE][$x] . '.xml </b> criado!<br/>';
                    }
                }
				echo "			
							</div>
						</div>
					  </div>";
				oci_free_statement($parse2);
			}else{
				echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Nenhum registro encontrado! Verifique os dados digitados e tente novamente!</b>
					</div>";
				echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					  Clique para ver a query
					  </button>
					  <div class='collapse' id='query' >
					  	<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					  </div>";
				echo "<script type='text/javascript'>$('#temp_notas').remove();</script>";
				}
		}else {
			echo "<div class='alert alert-danger fade in' >
				  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			   	  <b>Erro no Banco! Tente novamente!</b>
				  </div>";
			echo "<script type='text/javascript'>$('#temp_notas').remove();</script>";
			$e=oci_error();
			echo "<br/><br/>Retorno do Banco: ".$e['message'];
			}
		oci_free_statement($sql);
		oci_close($conn);
	}

	function testaConexao($string,$usuario,$senha){
		$testconnect=oci_new_connect($usuario,$senha,$string, american.we8iso8859p1);
		if ($testconnect==TRUE){
			session_start();
			$_SESSION['testeok']='ok';
			echo "<div class='alert alert-success fade in' style='margin-top:0px;'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Conexão efetuada com sucesso! Clique em <kbd>Enviar</kbd> para efetivar a configuração!</b>
				</div>";
			echo "<table class='table table-striped'>
					<tr> 
						<td><b>String: </b>$string</td>
					</tr>
					<tr> 
						<td><b>Usuário: </b>$usuario</td>
					</tr>
					<tr> 
						<td><b>Senha: </b>$senha</td>
					</tr>";
			echo "<script type='text/javascript'>$('#string').val('".$string."');$('#usuario').val('".$usuario."');$('#senha').val('".$senha."')</script>";
			}
			else{
				$e=oci_error();
				$erro=$e['message'];
				echo "<div class='alert alert-danger fade in' style='margin-top:0px;' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Erro na conexão: $erro </b>
				</div>";
			}
	}

	function geraConfig($string,$usuario,$senha,$arquivo){
		$conteudo = "[ORACLE]".PHP_EOL."String:".$string.PHP_EOL."Usuario:".$usuario.PHP_EOL."Senha:".$senha;
		file_put_contents($arquivo, $conteudo);
		if(file_exists($arquivo)){
			echo "<div class='alert alert-success fade in' style='margin-top:0px;'>
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Configurações gravadas com sucesso!</b>
				</div>";
			echo "<table class='table table-striped'>
					<tr> 
						<td><b>String: </b>$string</td>
					</tr>
					<tr> 
						<td><b>Usuário: </b>$usuario</td>
					</tr>
					<tr> 
						<td><b>Senha: </b>$senha</td>
					</tr>";
			$this->geraLog();
			session_destroy();
		}else{
			echo "<div class='alert alert-danger fade in' style='margin-top:0px;' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Falha na criação/gravação do arquivo <kbd>config/config.ini</kbd></b>
				</div>";
			session_destroy();
		}
	}

	function fechaTesouraria($data,$loja){
		$dataanterior=date("Y-m-d",strtotime("-1 day",strtotime($data)));
		$this->conectBanco();
		$query="select * from tab_fechamento_caixa
		where cod_loja=$loja and data_fechamento_caixa=to_date('$data','yyyy-mm-dd')";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$item);
		if ($rows>0){
			echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Data já está fechada!  Data: ".date('d/m/Y', strtotime($data))." Loja: $loja</b>
					</div>";
		} else{
			$querynextval="select max(cod_fechamento_caixa) id from tab_fechamento_caixa";
			$parsenextval=oci_parse($this->conn,$querynextval);
			ociexecute($parsenextval);
			if ($parsenextval==TRUE){
				oci_fetch_all($parsenextval,$id);
				$nextval=$id[ID][0];
				$querymin="select coalesce(min(cod_fechamento_caixa),0) min from tab_fechamento_caixa where cod_loja=$loja and data_fechamento_caixa=to_date('$dataanterior','yyyy-mm-dd')";
				$parsemin=oci_parse($this->conn,$querymin);
				ociexecute($parsemin);
				oci_fetch_all($parsemin,$min);
				$min=$min[MIN][0];
				if ($min>0){
					$dif=($nextval-$min)+1;
					$query="insert into tab_fechamento_caixa
						(cod_loja,cod_fechamento_caixa,data_fechamento_caixa,hora_fechamento_caixa,cod_funcionario_lancamento,
						num_caixa,cod_finalizadora,val_fechamento_caixa_digitado,valor_fechamento_caixa_fisico,
						flg_fechamento_parcial,data_ultima_alteracao,controle,observacao)
						(select cod_loja,cod_fechamento_caixa+$dif,to_date('$data','yyyy-mm-dd'),sysdate,
						cod_funcionario_lancamento,num_caixa,cod_finalizadora,0,0,
						flg_fechamento_parcial,sysdate,controle,observacao 
						from tab_fechamento_caixa
						where cod_loja=$loja and data_fechamento_caixa=to_date('$dataanterior','yyyy-mm-dd'))";
					$parse=oci_parse($this->conn,$query);
					ociexecute($parse);
					if ($parse=TRUE){
						echo "<div class='alert alert-success fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Fechamento da Tesouraria com sucesso! Data: ".date('d/m/Y', strtotime($data))." Loja: $loja</b>
						</div>";
						$this->geraLog();
						// incrementando a sequence
						$querysequence='SELECT SEQ_COD_FECHAMENTO_CAIXA.NEXTVAL ID FROM DUAL';
						$parseseq=oci_parse($this->conn,$querysequence);
						ociexecute($parseseq);
						oci_fetch_all($parseseq,$seq);
						$seq=$seq[ID][0];
						// coletando novo valor máximo
						$querynextval="select max(cod_fechamento_caixa) id from tab_fechamento_caixa";
						$parsenextval=oci_parse($this->conn,$querynextval);
						ociexecute($parsenextval);
						oci_fetch_all($parsenextval,$id);
						$nextval=$id[ID][0];
						$incremento=$nextval-$seq;
						$i=1;
						while ($i<=$incremento){
							$i++;
							$querysequence='SELECT SEQ_COD_FECHAMENTO_CAIXA.NEXTVAL ID FROM DUAL';
							$parseseq=oci_parse($this->conn,$querysequence);
							ociexecute($parseseq);
						}
					}else{
						echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Erro no Banco! Tente novamente!</b>
						</div>";
					}
				}else{
					echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>O dia anterior ".date('d/m/Y', strtotime($dataanterior))." não está fechado na tesouraria ou sem movimento!</b>
					</div>";
				}
			} else {
				echo "<div class='alert alert-danger fade in' >
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Erro no Banco! Tente novamente!</b>
					</div>";
			}
		}
	}

	function consultaInstancia(){
		$this->conectBanco();
		$view1='v$database';
		$query="select name from $view1";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		oci_fetch_all($parse,$item);
		$sid=$item[NAME][0];
		$view2='v$instance';
		$query="select instance_name,host_name,version from $view2";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		oci_fetch_all($parse,$item);
		$in=$item[INSTANCE_NAME][0];
		$host=$item[HOST_NAME][0];
		$version=$item[VERSION][0];
		$params=file($this->file);
		$user=trim($params[2]);
		$pos=strpos($user,':');
		$user=substr($user,$pos+1,1024);
		echo "<p>Versão: $version</p>
			<p>SID: $sid</p>
			<p>Nome Instância(nó): $in</p>
			<p>Host: $host</p>
			<p>Usuário: $user</p>";
	}

	function geraLog($x=1){
		$data=date('d/m/Y H:i:s');
		$url=$_SERVER['PHP_SELF'];
		$user=$_SESSION['id'];
		$arquivo="Log/ZeusHammer_".date(m)."_".date(Y).".zlg";
		$campos=$this->getUser($user);
		$codigouser=$campos[COD_FUNCIONARIO][0];
		$nomeuser=$campos[NOME][0];
		$conteudo = "Data/Hora-> ".$data."  ||  Programa-> ".$url."  ||  Ocorrências-> ".$x."  ||  Cód. Usuário-> ".$codigouser."  ||  Nome Usuário-> ".$nomeuser.PHP_EOL;
        if (!is_dir("Log")){
            mkdir('Log/', 0777, true);
        }
        if (file_exists($arquivo)) {
            $file = fopen($arquivo, 'a');
            fwrite($file, $conteudo);
            fclose($file);
        } else {
            file_put_contents($arquivo, $conteudo);
        }
	}

	function consultaPreco($lojas,$mercadoria){
		$this->conectBanco();
		$query="select 
				ordem_excecao finalizadora,
				case when flags_excecao=0 then 'Pr. Fixo' else (case when flags_excecao=1 then 'Desconto' else '?' end ) end tipo,
				dth_inclusao,
				dth_alteracao, 
				perc_desconto desconto,
				preco_excecao preco
				from tab_mercadoria_tipo_venda_exc
				where cod_loja=$lojas and trunc(cod_mercadoria)=$mercadoria";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		if ($parse==TRUE){
			?>
				<table class="table" id='table' align='center' >
						<thead>
							<tr>
								<th>Finalizadora</th>
								<th>Tipo</th>
								<th>Data Inclusão</th>
								<th>Data Alteração</th>
								<th>Desconto</th>
								<th>Preço</th>
							</tr>
						</thead>
				<?php
			$rows=oci_fetch_all($parse,$item);
			if($rows>0){
				$parse2=oci_parse($this->conn,$query);
				ociexecute($parse2);
				while (($row=oci_fetch_object($parse2)) != false){
						echo '<tr>';
						echo '<td>'.$row->FINALIZADORA."</td>";
						echo '<td>'.$row->TIPO."</td>";
						echo '<td>'.$row->DTH_INCLUSAO."</td>";
						echo '<td>'.$row->DTH_ALTERACAO."</td>";
						echo '<td>'.$row->DESCONTO."%</td>";
						echo '<td>R$ '.$row->PRECO."</td>";
						echo '</tr>';
				}
				echo "</table>";
			}else{
				echo "<tr><th>Sem exceção cadastrada!</th></tr></table>";
			}
		}
	}

	function relPromo($merc,$loja){
        $this->conectBanco();
        $query="SELECT promnova.codpromocao 
	,promnova.des_promocao 
	,promnova.datainicio
	,promnova.datatermino 
	,CASE 
		WHEN promnova.tipoacao = '4'
			THEN 'Desc Valor'
		ELSE (
				CASE 
					WHEN promnova.tipoacao = '2'
						THEN 'Desc Item'
					ELSE (CASE 
									WHEN promnova.tipoacao = '1'
						THEN 'Preco Fixo'
						ELSE  to_char(promnova.tipoacao) END)
					END
				)
		END AS acao
	,promnova.controlepromocao2 
	,promnova.valorfixo
	,promnova.percentual 
	,promloja.codloja 
FROM tab_novapromocao promnova
LEFT JOIN tab_promocao_loja promloja ON promnova.codpromocao = promloja.codpromocao
LEFT JOIN tab_promocao_produto promprod ON promnova.codpromocao = promprod.codpromocao
WHERE to_number(promprod.codproduto) = '$merc'
	AND promloja.codloja = '$loja'
	AND promloja.flgdesativa = 1
	AND promnova.STATUS = 1";
        $parse=oci_parse($this->conn,$query);
        ociexecute($parse);
        $rows=oci_fetch_all($parse,$item);
                if ($rows>0){
                ?>
                    <div class='alert alert-info fade in' ><b>Clique no código para ver a promoção no ZeusManager!</b></div>
                    <table class="table table-striped" >
                        <thead>
                        <tr>
                            <th>Cod.Promoção</th>
                            <th>Descrição</th>
                            <th>Data Inicio</th>
                            <th>Data Fim</th>
                            <th>Ação</th>
                            <th>Flags</th>
                            <th>Valor Fixo</th>
                            <th>Percentual</th>
                        </tr>
                        </thead>
                        <?php
                        $parse2=oci_parse($this->conn,$query);
                        oci_execute($parse2);
                        while (($row=oci_fetch_object($parse2)) != false){
                            echo '<tr>';
                            echo '<td><a href="http://zeus/manager/cad_promocao.php5?codpromocao='.$row->CODPROMOCAO.'&opt=Alt" 
                            target="_blank" data-toggle="tooltip" data-placement="bottom" title="Clique para acessar no ZeusManager (necessário estar logado no Manager em outra aba)">
	                        '.$row->CODPROMOCAO.'</a></td>';
                            echo '<td>'.$row->DES_PROMOCAO."</td>";
                            echo '<td>'.date('d/m/Y', strtotime($row->DATAINICIO))."</td>";
                            echo '<td>'.date('d/m/Y', strtotime($row->DATATERMINO))."</td>";
                            echo '<td>'.$row->ACAO."</td>";
                            echo '<td>'.$row->CONTROLEPROMOCAO2."</td>";
                            echo '<td>'.number_format(floatval($row->VALORFIXO),2,',','')."</td>";
                            echo '<td>'.$row->PERCENTUAL."%</td>";
                            echo '</tr>';
                        }
                        oci_free_statement($parse2);
                        echo "</table>";
                        echo "<button onclick='window.close();' class='btn btn-primary glyphicon glyphicon-log-out' role='button' aria-pressed='true' style='margin-top: 5%'> Fechar</button>";
                }else {
                     echo "<div class='alert alert-danger fade in'>
				           <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				           <b>Nenhuma promoção encontrada para o item <code>".$merc."</code> da loja <code>".$loja."</code> !</b>
				           </div>";
                     echo "<button onclick='window.close();' class='btn btn-primary glyphicon glyphicon-log-out' role='button' aria-pressed='true' style='margin-top: 5%'> Fechar</button>";
                }
    }

    function exibeXML($loja,$serie,$nota){
	    $this->conectBanco();
		$query="SELECT ID_NFE, DSC_XML_NFE, DSC_XML_RETORNO_NFE
				FROM TAB_CONTROLE_NFE 
				WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA =$nota AND SERIE_NF=$serie AND CODLOJA=$loja)";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		oci_fetch_all($parse,$item);
		$tipo=2;
		echo "<h3>Gerar arquivo XML</h3>";
		echo "Clique para direcionar a nota para geração de arquivo XML com o envio e resposta:<br/><br/>";
        echo "<a href='geraarquivoxml.php5?id_nfe=".$item[ID_NFE][0]."&tipo=".$tipo."' class='btn btn-primary' >Enviar</a>";
		echo "<hr/><h3>Xml de Envio</h3>";
        echo htmlentities($item[DSC_XML_NFE][0]);
        echo "<hr/><h3>Xml de Retorno</h3>";
        echo htmlentities($item[DSC_XML_RETORNO_NFE][0]);
    }

	function fcpInvalido($loja,$serie,$nota){
		$this->conectBanco();
		$query="SELECT * FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$qtde);
		if ($parse==TRUE){
			if ($rows>0) {
				$update1="UPDATE TAB_NOTA_ITEM SET valor_fundo_pobreza= '0', perc_fundo_pobreza ='0'
							WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie AND valor_fundo_pobreza <> 0 and perc_fundo_pobreza <> 0";
				$update2="UPDATE TAB_CONTROLE_NFE SET cod_situacao_envio_nfe='RP',cod_erro_nfe=394,cod_erro_1 = '874',cod_erro_2 = null , cod_erro_3 = null, cod_erro_4 = null, controle_robo = null 
							WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
				$parse2=oci_parse($this->conn,$update1);
				ociexecute($parse2);
				$resultupdate1=oci_num_rows($parse2);
				if ($parse2==TRUE){
					if ($resultupdate1>0){
						$parse3=oci_parse($this->conn,$update2);
						ociexecute($parse3);
						$resultupdate2=oci_num_rows($parse3);
						echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Alterado com sucesso! Qtde item(s) alterado(s): ".$resultupdate1."</b>
							</div>";
						$this->geraLog();
						if ($resultupdate2>0){
							echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota introduzida na fila de reprocessamento com sucesso</b>
							</div>";
							echo "<a href='consulta_status.php5?nota=$nota&loja=$loja&serie=$serie' class='btn btn-info active' role='button' aria-pressed='true'>
							Clique para ver o status da nota</a><br/><br/>";
						}else {
							echo "<div class='alert alert-danger fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota não encontrada para ser reprocessada, verifique se existe na TAB_CONTROLE_NFE!</b>
							</div>";
						}
						oci_free_statement($parse3);
					}else {
						echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Nenhum item com FCP! Verifique a nota e tente novamente!</b>
						</div>";
			            echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					            Clique para ver a query
					            </button>
					        <div class='collapse' id='query' >
					        	<div class='card card-body'>
						        	<div class='alert alert-info fade in' >
							        $update1
							        </div>
						        </div>
					        </div>";
					}
					oci_free_statement($parse2);
				}else{
					echo "<div class='alert alert-danger fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Comando não executado, tente novamente!</b>
					</div>";
					$e=oci_error();
					echo "Retorno do Banco: ".$e['message'];
					die();
				}
			}else{
			echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Nenhuma nota encontrada, verifique os dados e tente novamente!</b>
				</div>";
			echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					Clique para ver a query
					</button>
					<div class='collapse' id='query' >
						<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					</div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Comando não executado, tente novamente!</b>
			</div>";
			$e=oci_error();
			echo "Retorno do Banco: ".$e['message'];
			die();
		}
		oci_free_statement($parse);
	}

	function ncmInexistente($loja,$serie,$nota){
		$this->conectBanco();
		$query="SELECT DSC_XML_RETORNO_NFE FROM TAB_CONTROLE_NFE WHERE ID_NFE IN
		        (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
		$parse=oci_parse($this->conn,$query);
		ociexecute($parse);
		$rows=oci_fetch_all($parse,$qtde);
		//coletando o item acusado no XML de retorno para usar na condição da busca
		$xml=htmlentities($qtde[DSC_XML_RETORNO_NFE][0]);
		$pos=strpos($xml,'nItem:');
        $item=substr($xml,$pos+6,3);
        $item=preg_replace("/[^0-9]/", "", $item);
        if ($parse==TRUE){
				$query2="SELECT I.NUMITEM,I.NUMNOTA,I.SERIE_NF,I.CODLOJA,I.TIPO_MODELO_NF,I.CODPRODUTO,M.DESCRICAO,I.COD_NCM
				FROM TAB_NOTA_ITEM I INNER JOIN TAB_MERCADORIA M ON
				I.CODLOJA=M.COD_LOJA AND
				I.CODPRODUTO=M.COD_MERCADORIA
				WHERE I.NUMNOTA=$nota AND I.CODLOJA=$loja AND I.SERIE_NF=$serie AND I.NUMITEM=$item";
				$parse2=oci_parse($this->conn,$query2);
				ociexecute($parse2);
                $itens=oci_fetch_all($parse2,$item);
				if ($itens>0){
                        ?>
                        <table class="table table-striped" >
                            <thead>
                            <tr>
                                <th>Item</th>
                                <th>Nota</th>
                                <th>Serie</th>
                                <th>Loja</th>
                                <th>Modelo</th>
                                <th>Produto</th>
								<th>Descrição</th>
                                <th>Cód.NCM</th>
                                <th>Novo NCM</th>
                            </tr>
                            </thead>
                            <?php
                            $parse3=oci_parse($this->conn,$query2);
                            oci_execute($parse3);
                            while (($row=oci_fetch_object($parse3)) != false){
                                echo '<tr>';
                                echo '<td>'.$row->NUMITEM."</td>";
                                echo '<td>'.$row->NUMNOTA."</td>";
                                echo '<td>'.$row->SERIE_NF."</td>";
                                echo '<td>'.$row->CODLOJA."</td>";
                                echo '<td>'.$row->TIPO_MODELO_NF."</td>";
                                echo '<td>'.$row->CODPRODUTO."</td>";
								echo '<td>'.$row->DESCRICAO."</td>";
                                echo '<td>'.$row->COD_NCM."</td>";
                                echo '<td><input class="form-control" type="integer" id="ncm" name="ncm"/></td>';
                                echo '</tr>';
								$produto=$row->CODPRODUTO;
                            }
                            oci_free_statement($parse3);
                            echo "</table>";
                            echo "<div class='alert alert-success fade in'>
			                    <b>Clique no botão para enviar o novo código NCM <br/>
			                    <button onclick='var ncm=$(\"#ncm\").val();ncm=ncm.replace(/[^\d]+/g,\"\");location.href=\"ncminexistente_update.php5?&ncm=\"+ncm+\"&loja=".$loja."&serie=".$serie."&nota=".$nota."&produto=".$produto."\";' 
								class='btn btn-info active' style='margin:auto;margin-top:10;'>Enviar</button></br>
			                    </div>";
			}else{
			echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Nenhuma nota com NCM inexistente! Verifique os dados e tente novamente!</b>
				</div>";
			echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					Clique para ver a query
					</button>
					<div class='collapse' id='query' >
						<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					</div>";
				}
		}else {
			echo "<div class='alert alert-danger fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Comando não executado, tente novamente!</b>
			</div>";
			$e=oci_error();
			echo "Retorno do Banco: ".$e['message'];
			die();
		}
		oci_free_statement($parse);
	}

	function cpfInvalido($loja,$serie,$nota){
        $this->conectBanco();
        $query="SELECT * FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie";
        $parse=oci_parse($this->conn,$query);
        ociexecute($parse);
        $rows=oci_fetch_all($parse,$qtde);
        if ($parse==TRUE){
            if ($rows>0) {
                $select="SELECT * FROM TAB_CONTROLE_NFE
                            WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)
                            AND COD_ERRO_NFE='237'";
                $update1="UPDATE TAB_NOTA_HEADER SET cnpjcliente=(select num_cgc from tab_loja where cod_loja='$loja'),iecliente=(select num_insc_est from tab_loja where cod_loja='$loja')
							WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie";
                $update2="UPDATE TAB_CONTROLE_NFE SET cod_situacao_envio_nfe='RP',cod_erro_nfe=394,cod_erro_1 = '237',cod_erro_2 = null , cod_erro_3 = null, cod_erro_4 = null, controle_robo = null 
							WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
                $parsesel=oci_parse($this->conn,$select);
                ociexecute($parsesel);
                $rows=oci_fetch_all($parsesel,$qtde);
                if ($parsesel==TRUE){
                    if ($rows>0){
                        $parse2=oci_parse($this->conn,$update1);
                        ociexecute($parse2);
                        $parse3=oci_parse($this->conn,$update2);
                        ociexecute($parse3);
                        $resultupdate1=oci_num_rows($parse2);
                        if ($resultupdate1>0){
                            $this->geraLog();
                            echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Alterado com sucesso!</b>
							</div>";
                            echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota introduzida na fila de reprocessamento com sucesso</b>
							</div>";
                            echo "<a href='consulta_status.php5?nota=$nota&loja=$loja&serie=$serie' class='btn btn-info active' role='button' aria-pressed='true'>
							Clique para ver o status da nota</a><br/><br/>";
                        }else {
                            echo "<div class='alert alert-danger fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Falha no Update! Tente novamente!</b>
							</div>";
                        }
                        oci_free_statement($parse3);
                    }else {
                        echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Nota não está com rejeição 237! Verifique os dados e tente novamente!</b>
						</div>";
                        echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					        Clique para ver a query
					        </button>
					        <div class='collapse' id='query' >
					    	    <div class='card card-body'>
							        <div class='alert alert-info fade in' >
							        $select
							        </div>
						        </div>
					        </div>";
                    }
                    oci_free_statement($parse2);
                }else{
                    echo "<div class='alert alert-danger fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Comando não executado, tente novamente!</b>
					</div>";
                    $e=oci_error();
                    echo "Retorno do Banco: ".$e['message'];
                    die();
                }
            }else{
                echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Nenhuma nota encontrada! Verifique os dados e tente novamente!</b>
				</div>";
                echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					Clique para ver a query
					</button>
					<div class='collapse' id='query' >
						<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					</div>";
            }
        }else {
            echo "<div class='alert alert-danger fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Comando não executado, tente novamente!</b>
			</div>";
            $e=oci_error();
            echo "Retorno do Banco: ".$e['message'];
            die();
        }
        oci_free_statement($parse);
    }

    function totalBcDif($loja,$serie,$nota){
        $this->conectBanco();
        $query="SELECT * FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie";
        $parse=oci_parse($this->conn,$query);
        ociexecute($parse);
        $rows=oci_fetch_all($parse,$qtde);
        if ($parse==TRUE){
            if ($rows>0) {
                $update1="UPDATE TAB_NOTA_ITEM SET VALORICMS=(baseicms*aliquotaicms/100),SITUACAOTRIBUTARIA='000' 
							WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie AND (ALIQUOTAICMS>0 and SITUACAOTRIBUTARIA>20)";
                $update2="UPDATE TAB_CONTROLE_NFE SET cod_situacao_envio_nfe='RP',cod_erro_nfe=394,cod_erro_1 = '531',cod_erro_2 = null , cod_erro_3 = null, cod_erro_4 = null, controle_robo = null 
							WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
                $parse2=oci_parse($this->conn,$update1);
                ociexecute($parse2);
                $resultupdate1=oci_num_rows($parse2);
                if ($parse2==TRUE){
                    if ($resultupdate1>0){
                        $parse3=oci_parse($this->conn,$update2);
                        ociexecute($parse3);
                        $resultupdate2=oci_num_rows($parse3);
                        echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Alterado com sucesso! Qtde item(s) alterado(s): ".$resultupdate1."</b>
							</div>";
                        $this->geraLog();
                        if ($resultupdate2>0){
                            echo "<div class='alert alert-success fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota introduzida na fila de reprocessamento com sucesso</b>
							</div>";
                            echo "<a href='consulta_status.php5?nota=$nota&loja=$loja&serie=$serie' class='btn btn-info active' role='button' aria-pressed='true'>
							Clique para ver o status da nota</a><br/><br/>";
                        }else {
                            echo "<div class='alert alert-danger fade in' >
							<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
							<b>Nota não encontrada para ser reprocessada, verifique se existe na TAB_CONTROLE_NFE!</b>
							</div>";
                        }
                        oci_free_statement($parse3);
                    }else {
                        echo "<div class='alert alert-danger fade in' >
						<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
						<b>Não encontrado nenhum item com alíquota ICMS onde o CST seja não tributável!</b>
						</div>";
                        echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					        Clique para ver a query
					        </button>
					        <div class='collapse' id='query' >
					    	    <div class='card card-body'>
							        <div class='alert alert-info fade in' >
							        $update1
							        </div>
						        </div>
					        </div>";
                    }
                    oci_free_statement($parse2);
                }else{
                    echo "<div class='alert alert-danger fade in'>
					<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
					<b>Comando não executado, tente novamente!</b>
					</div>";
                    $e=oci_error();
                    echo "Retorno do Banco: ".$e['message'];
                    die();
                }
            }else{
                echo "<div class='alert alert-danger fade in' >
				<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
				<b>Nenhuma nota encontrada! Verifique os dados e tente novamente!</b>
				</div>";
                echo "<button class='btn btn-info' type='button' data-toggle='collapse' data-target='#query' aria-expanded='false' aria-controls='collapseExample'>
					Clique para ver a query
					</button>
					<div class='collapse' id='query' >
						<div class='card card-body'>
							<div class='alert alert-info fade in' >
							$query
							</div>
						</div>
					</div>";
            }
        }else {
            echo "<div class='alert alert-danger fade in'>
			<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Comando não executado, tente novamente!</b>
			</div>";
            $e=oci_error();
            echo "Retorno do Banco: ".$e['message'];
            die();
        }
        oci_free_statement($parse);
    }

    function totalfcpdif(){
       $this->conectBanco();
       $query="SELECT ID_NFE,
		    TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<nNF>') + 5, INSTR(DSC_XML_NFE, '</nNF>') - INSTR(DSC_XML_NFE, '<nNF>') - 5 ) ) AS NOTA,
		    TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<serie>') + 7, INSTR(DSC_XML_NFE, '</serie>') - INSTR(DSC_XML_NFE, '<serie>') - 7 ) ) AS SERIE,
		    COD_TIPO_EMISSAO_NFE,COD_SITUACAO_ENVIO_NFE,COD_ERRO_NFE,COD_EMPRESA,TRUNC(DTH_INCLUSAO) DATA,TIPO_MODELO_NF,ID_LOTE_NFE 
		    FROM TAB_CONTROLE_NFE 
		    WHERE COD_ERRO_NFE=861";
       $parse=oci_parse($this->conn,$query);
       ociexecute($parse);
       $rows=oci_fetch_all($parse,$notas);
       echo "<div class='alert alert-info' style='width:350px;'><b>Total de Ocorrências: <span style='font-size:20px;'>$rows</span></b></div>";
       if ($parse==TRUE){
           if ($rows>0) {
                ?>
                <table class="table table-striped" >
                    <thead>
                        <tr>
                            <th>Id_nfe</th>
                            <th>Nota</th>
                            <th>Serie</th>
                            <th>Emissão</th>
                            <th>Situação</th>
                            <th>Erro</th>
                            <th>Empresa</th>
                            <th>Data</th>
                            <th>Modelo</th>
                            <th>Lote</th>
                        </tr>
                    </thead>
                <?php
                $parse2=oci_parse($this->conn,$query);
                oci_execute($parse2);
                while (($row=oci_fetch_object($parse2)) != false){
                    echo '<tr>';
                    echo "<td><a href='consulta_status.php5?nota=".$row->NOTA."&loja=".$row->COD_EMPRESA."&serie=".$row->SERIE."' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Consultar nota\">".$row->ID_NFE."</a></td>";
                    echo '<td>'.$row->NOTA."</td>";
                    echo '<td>'.$row->SERIE."</td>";
                    echo '<td>'.$row->COD_TIPO_EMISSAO_NFE."</td>";
                    echo '<td>'.$row->COD_SITUACAO_ENVIO_NFE."</td>";
                    echo '<td>'.$row->COD_ERRO_NFE."</td>";
                    echo '<td>'.$row->COD_EMPRESA."</td>";
                    echo '<td>'.date('d/m/Y', strtotime($row->DATA))."</td>";
                    echo '<td>'.$row->TIPO_MODELO_NF."</td>";
                    echo '<td>'.$row->ID_LOTE_NFE."</td>";
                    echo '</tr>';
                }
                oci_free_statement($parse2);
                echo "</table>";
                }else{
                    echo "<div class='alert alert-danger fade in' style='width:350px;'>
			        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			        <b>Não existem notas com rejeição 861!</b>
			        </div>
			        <script>$('#reproc').remove()</script>";
                }
       }else {
           echo "<div class='alert alert-danger fade in' >
		    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Erro no banco!</b>
			</div>";
            $e=oci_error();
            echo "Retorno do Banco: ".$e['message'];
            die();
       }
       oci_free_statement($parse);
	}

    function consultaPorIds($ids){
        $this->conectBanco();
        $query="SELECT ID_NFE,
		    TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<nNF>') + 5, INSTR(DSC_XML_NFE, '</nNF>') - INSTR(DSC_XML_NFE, '<nNF>') - 5 ) ) AS NOTA,
		    TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<serie>') + 7, INSTR(DSC_XML_NFE, '</serie>') - INSTR(DSC_XML_NFE, '<serie>') - 7 ) ) AS SERIE,
		    COD_TIPO_EMISSAO_NFE,COD_SITUACAO_ENVIO_NFE,COD_ERRO_NFE,COD_EMPRESA,TRUNC(DTH_INCLUSAO) DATA,TIPO_MODELO_NF,ID_LOTE_NFE 
		    FROM TAB_CONTROLE_NFE 
		    WHERE ID_NFE IN ($ids)";
        $parse=oci_parse($this->conn,$query);
        ociexecute($parse);
        $rows=oci_fetch_all($parse,$notas);
        if ($parse==TRUE){
            if ($rows>0) {
                echo "<form><div class='row'>
						<div class='form-group col-md-12' style='margin-top: 5%'>
							<a href='consultaporids.php5?ids=$ids' class='btn btn-primary glyphicon glyphicon-refresh' role='button' aria-pressed='true' > Atualizar</a>
						</div>			
					</div></form>";
                ?>
                <table class="table table-striped" >
                    <thead>
                    <tr>
                        <th>Id_nfe</th>
                        <th>Nota</th>
                        <th>Serie</th>
                        <th>Emissão</th>
                        <th>Situação</th>
                        <th>Erro</th>
                        <th>Empresa</th>
                        <th>Data</th>
                        <th>Modelo</th>
                        <th>Lote</th>
                    </tr>
                    </thead>
                <?php
                $parse2=oci_parse($this->conn,$query);
                oci_execute($parse2);
                while (($row=oci_fetch_object($parse2)) != false){
                    echo '<tr>';
                    echo '<td>'.$row->ID_NFE."</td>";
                    echo '<td>'.$row->NOTA."</td>";
                    echo '<td>'.$row->SERIE."</td>";
                    echo '<td>'.$row->COD_TIPO_EMISSAO_NFE."</td>";
                    echo '<td>'.$row->COD_SITUACAO_ENVIO_NFE."</td>";
                    echo '<td>'.$row->COD_ERRO_NFE."</td>";
                    echo '<td>'.$row->COD_EMPRESA."</td>";
                    echo '<td>'.date('d/m/Y', strtotime($row->DATA))."</td>";
                    echo '<td>'.$row->TIPO_MODELO_NF."</td>";
                    echo '<td>'.$row->ID_LOTE_NFE."</td>";
                    echo '</tr>';
                }
                oci_free_statement($parse2);
                echo "</table>";
                echo "<form><div class='row'>
						<div class='form-group col-md-12' style='margin-top: 5%'>
							<button onclick='window.close();' class='btn btn-primary glyphicon glyphicon-log-out' role='button' aria-pressed='true' > Fechar</button>
						</div>			
					</div></form>";
            }else{
                echo "<div class='alert alert-danger fade in' style='width:350px;'>
			        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			        <b>Busca não trouxe resultados! Tente novamente!</b>
			        </div>";
            }
        }else {
            echo "<div class='alert alert-danger fade in' >
		    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Erro no banco! Tente Novamente</b>
			</div>";
        }
    }

    function totalcbenef(){
	    $this->conectBanco();
        $query="SELECT ID_NFE,
		    TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<nNF>') + 5, INSTR(DSC_XML_NFE, '</nNF>') - INSTR(DSC_XML_NFE, '<nNF>') - 5 ) ) AS NOTA,
		    TO_CHAR(SUBSTR(DSC_XML_NFE, INSTR(DSC_XML_NFE, '<serie>') + 7, INSTR(DSC_XML_NFE, '</serie>') - INSTR(DSC_XML_NFE, '<serie>') - 7 ) ) AS SERIE,
		    COD_TIPO_EMISSAO_NFE,COD_SITUACAO_ENVIO_NFE,COD_ERRO_NFE,COD_EMPRESA,TRUNC(DTH_INCLUSAO) DATA,TIPO_MODELO_NF,ID_LOTE_NFE 
		    FROM TAB_CONTROLE_NFE 
		    WHERE COD_ERRO_NFE=930";
        $parse=oci_parse($this->conn,$query);
        ociexecute($parse);
        $rows=oci_fetch_all($parse,$notas);
        echo "<div class='alert alert-info' style='width:350px;'><b>Total de Ocorrências: <span style='font-size:20px;'>$rows</span></b></div>";
        if ($parse==TRUE){
            if ($rows>0) {
                ?>
                    <table class="table table-striped" >
                        <thead>
                        <tr>
                            <th>Id_nfe</th>
                            <th>Nota</th>
                            <th>Serie</th>
                            <th>Emissão</th>
                            <th>Situação</th>
                            <th>Erro</th>
                            <th>Empresa</th>
                            <th>Data</th>
                            <th>Modelo</th>
                            <th>Lote</th>
                        </tr>
                        </thead>
                        <?php
                        $parse2=oci_parse($this->conn,$query);
                        oci_execute($parse2);
                        while (($row=oci_fetch_object($parse2)) != false){
                            echo '<tr>';
                            echo "<td><a href='consulta_status.php5?nota=".$row->NOTA."&loja=".$row->COD_EMPRESA."&serie=".$row->SERIE."' data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"Consultar nota\">".$row->ID_NFE."</a></td>";
                            echo '<td>'.$row->NOTA."</td>";
                            echo '<td>'.$row->SERIE."</td>";
                            echo '<td>'.$row->COD_TIPO_EMISSAO_NFE."</td>";
                            echo '<td>'.$row->COD_SITUACAO_ENVIO_NFE."</td>";
                            echo '<td>'.$row->COD_ERRO_NFE."</td>";
                            echo '<td>'.$row->COD_EMPRESA."</td>";
                            echo '<td>'.date('d/m/Y', strtotime($row->DATA))."</td>";
                            echo '<td>'.$row->TIPO_MODELO_NF."</td>";
                            echo '<td>'.$row->ID_LOTE_NFE."</td>";
                            echo '</tr>';
                        }
                        oci_free_statement($parse2);
                        echo "</table>";
            }else{
                echo "<div class='alert alert-danger fade in' style='width:350px;'>
			        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			        <b>Não existem notas com rejeição 930!</b>
			        </div>
			        <script>$('#reproc').remove()</script>";
            }
        }else {
            echo "<div class='alert alert-danger fade in' >
		    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
			<b>Erro no banco!</b>
			</div>";
            $e=oci_error();
            echo "Retorno do Banco: ".$e['message'];
            die();
        }
        oci_free_statement($parse);
	}








}


?>
