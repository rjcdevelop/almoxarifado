<!DOCTYPE html>
<html>

<head>
<title>Almoxarifado</title>
</head>

<body>

<?php
	
	include_once "../../conexao.php";
	$hoje = date('Y/m/d');

	if(isset($_POST['pesquisa'])){
	$id=filter_var($_POST['pesquisa']);
	}else {
		$id='Digite número NF';
	}

	if(isset($_POST['dt_ini'])){
		$id_dtini=filter_var($_POST['dt_ini']);
		}else {
			$id_dtini = $hoje;
		}
	
	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='index.php'><button>Voltar</button></a><br><br>";


	echo "<form action='confnota.php' method='POST'>
<table>
<tr>
<td><label>Digite nº NF:</label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' name='pesquisa' value='$id' placeholder='Digite número da NF'/></td>&nbsp &nbsp
<td><label>A partir de: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini' value='$id_dtini'/></td>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
";
	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT
	convert(varchar(10),A.DATA,103) AS DATA_ENTR,
	A.NOTA AS NOTA_ENTR,
	A.ITEM AS ITEM_ENTR,
	A.PRODUTO AS PROD_ENTR,
	A.DESCRI AS DESC_ENTR,
	A.QUANT AS QTDE_ENTR,
	B.QUANT AS QTDE_MOVTO,
	A.VALUNI AS VALUNI,
	B.VALTOT
	FROM
	FAT00001.dbo.entrada2 AS A
	LEFT JOIN FAT00001.dbo.movto AS B ON A.CONTROLE=B.CONTROLE AND A.PRODUTO=B.PROD AND A.ITEM=B.ITEM AND A.PEDIDO=B.PEDNF
	WHERE
	A.DATA >= '$id_dtini'
	AND A.NOTA LIKE '%$id%'
	ORDER BY A.DATA DESC,A.CONTROLE,A.ITEM
	
--REPLACE(CONVERT(VARCHAR,CAST(((CASE WHEN C.S1TOTAL IS NULL THEN 0 ELSE C.S1TOTAL END) -(CASE WHEN D.S2TOTAL IS NULL THEN 0 ELSE D.S2TOTAL END)) AS NUMERIC(18,2)), 1),'.',',') AS SALDO
");

echo "<br><br>RELAÇÃO DE ITENS DA NOTA FISCAL<br>";

try{
	
	echo "<table id=tbordprod>
		<tr>
			<td>DATA</td>
			<td>N.F.</td>
			<td>ITEM</td>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>QTDE NA NOTA FISCAL</td>
			<td>QTDE GERADA ESTOQUE</td>
			<td>VALOR UNIT</td>
			<td>VALOR TOTAL</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$qtdeentr = number_format($linha['QTDE_ENTR'],2,',', '.');
		$qtdemovto = number_format($linha['QTDE_MOVTO'],2,',', '.');
		$vlitem = number_format($linha['VALUNI'],2,',', '.');
		$vltotal = number_format($linha['VALTOT'],2,',', '.');
		echo "<tr>
				<td>$linha[DATA_ENTR]</td>
				<td>$linha[NOTA_ENTR]</td>
				<td>$linha[ITEM_ENTR]</td>
				<td>$linha[PROD_ENTR]</td>
				<td>$linha[DESC_ENTR]</td>
				<td align='right'>$qtdeentr</td>
				<td align='right'>$qtdemovto</td>
				<td align='right'>$vlitem</td>
				<td align='right'>$vltotal</td>
			</tr>";
	}
	echo "</table>";
	
	echo $sql->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>

</html>