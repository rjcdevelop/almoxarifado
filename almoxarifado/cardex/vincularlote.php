<!DOCTYPE html>
<html>

<head>
<title>Lista de Requisições</title>
<link href="css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "VINCULAR LOTE - SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA<br><br>
	<input type='button' value='Voltar' onClick='history.go(-1)'>
	<a href='index.php'><button>Kardex</button></a><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../conexao.php";
$hoje = date('d/m/Y');
$acao1 = 'I';
$acao2 = 'A';

if(isset($_GET['filtro']) == "Vincular"){
	$filtro = filter_var($_GET['filtro']);
	}elseif(isset($_GET['filtro']) == "todos"){
		$filtro = '';
	}else{
		$filtro = 'Vincular';
	}
if(isset($_GET['busca'])){
	$busca=filter_var($_GET['busca']);
	}else {
		$busca='';
	}
if(isset($_GET['dt_ini'])){
	$id_dtini=filter_var($_GET['dt_ini']);
	}else {
		$id_dtini=$hoje;
	}
if(isset($_GET['dt_fim'])){
	$id_dtfim=filter_var($_GET['dt_fim']);
	}else {
		$id_dtfim=$hoje;
	}
if(isset($_GET['rotina'])){
	$rotina=filter_var($_GET['rotina']);
	}else {
		$rotina='REQ';
	}
				
//echo "Exbir: $filtro <br>";

echo "<form action='vincularlote.php' method='GET'>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' value='$id_dtini' name='dt_ini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' value='$id_dtfim' name='dt_fim'/></td><br>
<td>Movimento:</td>";

if($rotina == "REQ"){
	echo "<td>	<input type='radio' name='rotina' value='REQ' checked>Remessa</td>
	<input type='radio' name='rotina' value='DEV' >Entrega</td>";
}else{
	echo "<td>	<input type='radio' name='rotina' value='REQ' >Remessa</td>
	<input type='radio' name='rotina' value='DEV' checked>Entrega</td>";
}

if($filtro == "Vincular"){
	echo "<br><td>Pendentes: (**Filtro em desenvolvimento) 	<input type='radio' name='filtro' value='Vincular' checked>Pendentes</td>
															<input type='radio' name='filtro' value='todos'>Todos</td>";
}else{
	echo "<br><td>Pendentes: (**Filtro em desenvolvimento) 	<input type='radio' name='filtro' value='Vincular'>Pendentes</td>
															<input type='radio' name='filtro' value='todos' checked>Todos</td>";
}

echo "<table><tr><td><label>Pesquisar Registro: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' value='$busca' name='busca'/></td>
<td><input type='submit' value='Buscar'></td>
</tr></table>
</form><br><br>";

if($rotina === 'REQ'){
	$leftjoin = "LEFT JOIN FAT00001.dbo.requis1 AS C ON A.NOTA = C.DOCUM";
	$obsjoin = "(A.OBSSAI + '/' + C.OBSERV) AS OBSERVACAO";
	$where1 = "(A.OBSSAI + C.OBSERV + A.PROD + A.DESCRICAO)";
}elseif($rotina === 'DEV'){
	$leftjoin = "LEFT JOIN FAT00001.dbo.Devol1 AS C ON A.NOTA = C.DOCUM";
	$obsjoin = "(A.OBSSAI + '/' + C.OBSERV) AS OBSERVACAO";
	$where1 = "(A.OBSSAI + C.OBSERV + A.PROD + A.DESCRICAO)";
}else{}

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	if ($filtro == 'Vincular'){
		$consulta = $conectar->query("SELECT
		CONVERT(varchar(10),A.DATA,103) AS 'DATA',
		A.CONTROLE AS CONTROLE,
		A.ITEM AS ITEM,
		A.PROD AS PROD,
		A.DESCRICAO AS DESCRICAO,
		A.QUANT AS QUANT,
		CASE WHEN B.valuni IS NULL THEN A.VALUNI ELSE B.valuni END AS VALUNI,
		CASE WHEN B.valtot IS NULL THEN A.VALTOT ELSE B.valtot END AS VALTOT,
		CASE WHEN A.CONTROLE=B.doc_id THEN B.cod_lote ELSE 'Vincular' END AS 'CODLOTE',
		$obsjoin
		FROM
		FAT00001.dbo.movto AS A
		LEFT JOIN pcp_producao.dbo.reglote AS B ON A.CONTROLE=B.doc_id AND A.PROD=B.codproduto AND A.ITEM = B.item
		$leftjoin
		WHERE
		A.DATA >= '$id_dtini' AND A.DATA <= '$id_dtfim'
		AND A.ROTINA = '$rotina'
		AND $where1 LIKE '%$busca%'
		AND B.doc_id IS NULL
		ORDER BY A.DATA DESC,A.CONTROLE,A.ITEM
	");

	}else{
		$consulta = $conectar->query("SELECT
		CONVERT(varchar(10),A.DATA,103) AS 'DATA',
		A.CONTROLE AS CONTROLE,
		A.ITEM AS ITEM,
		A.PROD AS PROD,
		A.DESCRICAO AS DESCRICAO,
		A.QUANT AS QUANT,
		CASE WHEN B.valuni IS NULL THEN A.VALUNI ELSE B.valuni END AS VALUNI,
		CASE WHEN B.valtot IS NULL THEN A.VALTOT ELSE B.valtot END AS VALTOT,
		CASE WHEN A.CONTROLE=B.doc_id THEN B.cod_lote ELSE 'Vincular' END AS 'CODLOTE',
		$obsjoin
		FROM
		FAT00001.dbo.movto AS A
		LEFT JOIN pcp_producao.dbo.reglote AS B ON A.CONTROLE=B.doc_id AND A.PROD=B.codproduto AND A.ITEM = B.item
		$leftjoin
		WHERE
		A.DATA >= '$id_dtini' AND A.DATA <= '$id_dtfim'
		AND A.ROTINA = '$rotina'
		AND $where1 LIKE '%$busca%'
		ORDER BY A.DATA DESC,A.CONTROLE,A.ITEM
	");
	}
	
	echo "<table id=tbordprod>
		<tr>
			<td width='70px'>DATA</td>
			<td width='50px'>REGISTRO</td>
			<td width='70px'>ITEM</td>
			<td width='150px'>CODIGO</td>
			<td width='500px'>DESCRICAO</td>
			<td width='150px'>QTDE</td>
			<td width='90px'>VLR UNIT</td>
			<td width='120px'>VLR TOTAL</td>
			<td width='80px'>LOTE</td>
			<td width='400px'>OBSERVAÇÃO</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$qtde = number_format($linha['QUANT'],3,',', '.');
		$valuni = number_format($linha['VALUNI'],2,',', '.');
		$valtot = number_format($linha['VALTOT'],2,',', '.');

		echo "<tr>
				<td>$linha[DATA]</td>
				<td>$linha[CONTROLE]</td>
				<td>$linha[ITEM]</td>
				<td>$linha[PROD]</td>
				<td>$linha[DESCRICAO]</td>
				<td align='right'>$qtde</td>
				<td align='right'>$valuni</td>
				<td align='right'>$valtot</td>
				";

				if($linha["CODLOTE"] != 'Vincular'){
					echo "<td><a href='formvincular.php?doc_id=$linha[CONTROLE]&item=$linha[ITEM]&prod=$linha[PROD]&dt_ini=$id_dtini&dt_fim=$id_dtfim&rotina=$rotina&busca=$busca&acao=$acao2&qtde=$linha[QUANT]&vlr_unit=$linha[VALUNI]&vlr_tot=$linha[VALTOT]&cod_lote=$linha[CODLOTE]'>$linha[CODLOTE]</a>";
				}else{
					echo "<td><a href='formvincular.php?doc_id=$linha[CONTROLE]&item=$linha[ITEM]&prod=$linha[PROD]&dt_ini=$id_dtini&dt_fim=$id_dtfim&rotina=$rotina&busca=$busca&acao=$acao1&qtde=$linha[QUANT]&vlr_unit=$linha[VALUNI]&vlr_tot=$linha[VALTOT]&cod_lote='>$linha[CODLOTE]</a>";
				} echo "</td>

				<td>$linha[OBSERVACAO]</td>
			</tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>

</body>

</html>
