<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ordem de Produção</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>
	
<?php

//---------------------------------------------------------------------------------------------------------
//	TITULO E CABEÇALHO DO FORMULARIO
	$hoje = date('d/m/Y');
	echo "<b><center>R J C DEFESA E AEROESPACIAL LTDA</b></center><br>";
	echo "<center><b>O R D E M &nbsp &nbsp &nbsp D E &nbsp &nbsp &nbsp P R O D U Ç Ã O</b></center><br>";

	include_once "../../conexao.php";


//---------------------------------------------------------------------------------------------------------
//CABEÇALHO DA ORDEM DE PRODUCAO
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

	$cabecalho = $conectar->query("SELECT
CONVERT(varchar(10),A.DATA,103) AS DATAOP,
A.CODIGO AS OP
FROM
FAT00001.dbo.CadOP1 AS A
WHERE
A.CODIGO = '{$id}'");

	while
	($exibe = $cabecalho->fetch(PDO::FETCH_ASSOC)) {
	echo "<center><table width='80%' border='0'>
		<tr>
			<td align=right><b>Emissão: </b></td>
			<td>$hoje</td>
			<td align=right><b>O.P.: </b></td>
			<td>$exibe[OP]</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td align=right><b>Data da Ordem Produção: </b></td>
			<td>$exibe[DATAOP]</td>
		</tr>
		</table></center>";
}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
echo "<br><br><br>";


//---------------------------------------------------------------------------------------------------------
//OBSERVACOES DA ORDEM DE PRODUCAO
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$prodfinal = $conectar->query("SELECT
A.OBSERVACAO AS OBS1,
A.OBSERVACAO2 AS OBS2
FROM
FAT00001.dbo.CadOP1 AS A
WHERE
A.CODIGO = '{$id}'");

	while
	($exibe = $prodfinal->fetch(PDO::FETCH_ASSOC)) {
		echo "<table width:'100%' border ='0'>
		<tr>
			<td><b>Observações:</b></td>
		</tr>
		<tr>
			<td>$exibe[OBS1]</td>
		</tr>
		<tr>
			<td>$exibe[OBS2]</td>
		</tr>
		</table><br>";
		
}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

//---------------------------------------------------------------------------------------------------------
//CORPO ITENS A SEREM PRODUZIDOS
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$prodfinal = $conectar->query("SELECT
B.UNID AS UNIDADE,
B.QTDE AS QTDEPRODFINAL,
B.CODPROD AS CODPRODFINAL,
B.DESCRICAO AS DESCPRODFINAL
FROM
FAT00001.dbo.CadOP1 AS A,
FAT00001.dbo.CadOp2 AS B
WHERE
A.CODIGO=B.CODIGO
AND A.CODIGO = '{$id}'");

	while($exibe = $prodfinal->fetch(PDO::FETCH_ASSOC)) {
		
		$qtde_prodfinal = number_format($exibe['QTDEPRODFINAL'],5,',', '.');
	
		echo "<table width:'100%' border ='0'>
		<tr>
			<td width=20%><b>Cód Prod Final</b></td>
			<td width=50%><b>Descrição Produto Final</b></td>
			<td width=10%><b>Unidade</b></td>
			<td width=20% align=right><b>Quantidade</b></td>
		</tr>";

		echo "<tr>
				<td width=20%>$exibe[CODPRODFINAL]</td>
				<td width=50%>$exibe[DESCPRODFINAL]</td>
				<td width=10%>$exibe[UNIDADE]</td>
				<td width=20% align=right>$qtde_prodfinal</td>
		</tr></table>";
		
		$pr_final = "$exibe[CODPRODFINAL]";

	//---------------------------------------------------------------------------------------------------------
	//RELACAO DE MATERIA PRIMA
	$relmp = $conectar->query("SELECT
B.ITEM AS ITEMMP,
B.CODIGO_MP AS CODIGOMP,
B.DESCRICAO_MP AS DESCRICAOMP,
B.QTDE AS QTDEUNIT,
B.UNID AS UNIDMP,
B.QTDETEMP2 AS QTDE
FROM
FAT00001.dbo.CadOP2 AS A,
FAT00001.dbo.CadOp3 AS B
WHERE
A.CODIGO=B.CODIGO_OP
AND A.CODIGO = '{$id}' AND B.CODIGO_PR='{$pr_final}'
GROUP BY B.ITEM,B.CODIGO_PR,B.CODIGO_MP,B.DESCRICAO_MP,B.UNID,B.QTDE,B.QTDETEMP2
ORDER BY B.CODIGO_PR, B.ITEM");
	
	echo "<table width='100%' id=tbordprod>
		<tr>
			<td width=5%><b>Item</b></td>
			<td width=20%><b>Cod Mat Prima</b></td>
			<td width=50%><b>Descrição M.P.</b></td>
			<td width=5%><b>Qtde Unit</b></td>
			<td width=5%><b>Unid</b></td>
			<td width=15% align=right><b>Qtde Total</b></td>
		</tr>";

	while
	($linha = $relmp->fetch(PDO::FETCH_ASSOC)) {

		$qtde_unit = number_format($linha['QTDEUNIT'],5,',', '.');
		$qtde_total = number_format($linha['QTDE'],5,',', '.');

		echo "<tr>
				<td>$linha[ITEMMP]</td>
				<td>$linha[CODIGOMP]</td>
				<td>$linha[DESCRICAOMP]</td>
				<td align=right>$qtde_unit</td>
				<td>$linha[UNIDMP]</td>
				<td align=right>$qtde_total</td>
			</tr>";
	}
	echo "</table>";
	
	echo "Total de itens: " . $relmp->rowCount();
	
	echo "<br><br><br>";
	}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

echo "<br><br><br>";

//---------------------------------------------------------------------------------------------------------
// RODAPE COM ASSINATURAS
echo "<table border='0'>
		<tr>
			<td>____________________________________&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
			<td>____________________________________</td>
		</tr>
		<tr>
			<td>Responsável pela Admin Produção</td>
			<td>Responsável pela Produção</td>
		</tr></table>";
		
?>
</body>
</html>
