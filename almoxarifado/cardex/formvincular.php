<!DOCTYPE html>
<html>
<head>
<title>Vincular Lote</title>
<script>function alert_alterado(){
alert("Gravado com Sucesso!");
history.go(-2);
}</script>
</head>

<body>

<?php
	echo "VINCULAR LOTE - R J C DEFESA E AEROESPACIAL LTDA<br>
	<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";
	
	include_once "../../conexao.php";
	
	//RECEBE O NUMERO DOCUMENTO PARA VINCULAR

	
	

	if(isset($_POST['pesquisar'])){
		$pesquisar=filter_var($_POST['pesquisar']);
		$busca=filter_var($_POST['busca']);
		$item=filter_var($_POST['item']);
		$prod=filter_var($_POST['prod']);
		$doc_id = filter_var($_POST['doc_id']);
		$dt_ini = filter_var($_POST['dt_ini']);
		$dt_fim = filter_var($_POST['dt_fim']);
		$rotina = filter_var($_POST['rotina']);
		$vlr_unit = filter_var($_POST['vlr_unit']);
		$vlr_tot = filter_var($_POST['vlr_tot']);
		$acao = filter_var($_POST['acao']);
		$qtde = filter_var($_POST['qtde']);
		if(isset($_POST['cod_lote'])){$cod_lote = filter_var($_POST['cod_lote']);}else{}

		}else {
			$pesquisar = filter_var($_GET['cod_lote']);
			$busca = filter_var($_GET['busca']);
			$item = filter_var($_GET['item']);
			$prod = filter_var($_GET['prod']);
			$doc_id = filter_var($_GET['doc_id']);
			$dt_ini = filter_var($_GET['dt_ini']);
			$dt_fim = filter_var($_GET['dt_fim']);
			$rotina = filter_var($_GET['rotina']);
			$vlr_unit = filter_var($_GET['vlr_unit']);
			$vlr_tot = filter_var($_GET['vlr_tot']);
			$acao = filter_var($_GET['acao']);
			$qtde = filter_var($_GET['qtde']);
			if(isset($_GET['cod_lote'])){$cod_lote = filter_var($_GET['cod_lote']);}else{}
		}

		$sql_produto = $conectar->query("SELECT 
		A.codigo as produto,
		A.descricao as descricao
		FROM FAT00001.dbo.produto AS A
		WHERE
		A.codigo = '$prod'");
	while
	($linha = $sql_produto->fetch(PDO::FETCH_ASSOC)) {
		echo "Código: $linha[produto] / Descrição: $linha[descricao]<br><br>";
	}
	echo "<form action='formvincular.php' method='POST'>
	<tr>
	<td><input type='hidden' name='doc_id' value='$doc_id' id='doc_id'/></td>
	<td><input type='hidden' name='item' value='$item' id='item'/></td>
	<td><input type='hidden' name='prod' value='$prod' id='prod'/></td>
	<td><input type='hidden' name='dt_ini' value='$dt_ini' id='dt_ini'/></td>
	<td><input type='hidden' name='dt_fim' value='$dt_fim' id='dt_fim'/></td>
	<td><input type='hidden' name='rotina' value='$rotina' id='rotina'/></td>
	<td><input type='hidden' name='busca' value='$busca' id='busca'/></td>
	<td><input type='hidden' name='acao' value='$acao' id='acao'/></td>
	<td><input type='hidden' name='vlr_unit' value='$vlr_unit' id='vlr_unit'/></td>
	<td><input type='hidden' name='vlr_tot' value='$vlr_tot' id='vlr_tot'/></td>
	<td><input type='hidden' name='qtde' value='$qtde' id='qtde'/></td>
	<td><input type='hidden' name='cod_lote' value='$cod_lote' id='cod_lote'/></td>
	<td><label>Localizar Lote:</label></td>
	<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' value='$pesquisar' name='pesquisar'/></td>
	<td><input type='submit' value='Pesquisar'></td>
	</tr>
	</form><br><br>";	




if($pesquisar != ''){
	$sql = $conectar->query("SELECT
	A.cod_lote AS LOTE,
	A.cod_of AS ORDFAB
	FROM
	pcp_producao.dbo.cad_lote AS A
	WHERE
	A.cod_lote LIKE '%$pesquisar%'");
	
	echo "<table id=tbordprod>
	<tr>
		<td width='100px'>Lote</td>
		<td width='70px'>O.F.</td>
		<td width='150px'>Vlr Unitário</td>
		<td width='80px'>Selecionar</td>
	</tr>";
		while
			($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

			echo "<tr>
				<td><form action='reglote.php' method='GET'>
				<input type='text' readonly name='cod_lote' value='$linha[LOTE]' id='cod_lote'/></td>
				<td><input type='text' readonly name='cod_of' value='$linha[ORDFAB]' id='cod_of'/></td>
				<td><input type='number' name='vlr_unit' value='$vlr_unit' min='0' max='' step='.000001' id='vlr_unit'/></td>

				<td><input type='hidden' name='doc_id' value='$doc_id' id='doc_id'/>
				<input type='hidden' name='item' value='$item' id='item'/>
				<input type='hidden' name='prod' value='$prod' id='prod'/>
				<input type='hidden' name='dt_ini' value='$dt_ini' id='dt_ini'/>
				<input type='hidden' name='dt_fim' value='$dt_fim' id='dt_fim'/>
				<input type='hidden' name='rotina' value='$rotina' id='rotina'/>
				<input type='hidden' name='busca' value='$busca' id='busca'/>
				<input type='hidden' name='acao' value='$acao' id='acao'/>
				<input type='hidden' name='qtde' value='$qtde' id='qtde'/>
				<input type='submit' value='Enviar'></td></form></tr>";
		}
	echo "</table><br><br>";

}else{}

if ($acao == "A"){
		
	echo "<table id=tbordprod>
	<tr>
		<td width='120px'>Lote Cadastrado</td>
		<td width='150px'>Remover Lote</td>
	</tr>
	<tr>
				<td>$cod_lote</td>
				<td><a href='reglote.php?doc_id=$doc_id&item=$item&prod=$prod&acao=E&dt_ini=$dt_ini&dt_fim=$dt_fim&rotina=$rotina&busca=$busca'>Remover</a></td>
				";
	echo "</table>";
			}else{}
//<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' value='$pesquisar' name='pesquisar'/></td>

//echo "<br><br>EXIBIR: $acao / $busca / $doc_id / $dt_ini / $dt_fim / $rotina / $vlr_unit/ $vlr_tot";


//echo "<br><br>Exibir variavel id:$doc_id /$busca / $dt_ini / $dt_fim / $linha[LOTE]";


?>

</body>
</html>