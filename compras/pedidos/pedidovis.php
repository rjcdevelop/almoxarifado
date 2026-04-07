<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pedido de Compra</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>
	
<?php

//---------------------------------------------------------------------------------------------------------
//	TITULO E CABEÇALHO DO FORMULARIO
	$hoje = date('d/m/Y');
	echo "<b><center>R J C DEFESA E AEROESPACIAL LTDA</b></center><br>";
	echo "<center><b>P ED I D O &nbsp &nbsp &nbsp D E &nbsp &nbsp &nbsp C O M P R A</b></center><br>";

	include_once "../../conexao.php";


//---------------------------------------------------------------------------------------------------------
//CABEÇALHO DA ORDEM DE PRODUCAO
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
	$get_vlr = filter_var($_GET['vlr'], FILTER_SANITIZE_STRING);
	$vlr_pedido = number_format($get_vlr,2,',', '.');

	$cabecalho = $conectar->query("SELECT
CONVERT(varchar(10),A.DATA,103) AS DATACOMPRA,
A.PEDIDO AS PEDIDO
FROM
FAT00001.dbo.compra AS A
WHERE
A.PEDIDO = '{$id}'");

	while
	($exibe = $cabecalho->fetch(PDO::FETCH_ASSOC)) {
	echo "<center><table width='80%' border='0'>
		<tr>
			<td align=right><b>Data Consulta: </b></td>
			<td>$hoje</td>
			<td align=right><b>Pedido: </b></td>
			<td>$exibe[PEDIDO]</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td align=right><b>Data Pedido Compra: </b></td>
			<td>$exibe[DATACOMPRA]</td>
		</tr>
		</table></center>";
}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
echo "<br><br><br>";


//---------------------------------------------------------------------------------------------------------
//OBSERVACOES DO PEDIDO DE COMPRA
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$prodfinal = $conectar->query("SELECT
	A.PEDIDO,
	A.FORN,
	A.RAZFORN,
	A.OBS1,
	A.OBS2,
	A.OBS3,
	A.OBS4,
	A.OBS5,
	A.FANT
FROM
FAT00001.dbo.compra AS A
WHERE
A.PEDIDO = '{$id}'");

	while
	($exibe = $prodfinal->fetch(PDO::FETCH_ASSOC)) {
		echo "<table width:'100%' border ='0'>
		<tr><td>Razão Social: $exibe[RAZFORN]</td></tr>
		<tr><td><b>Observações:</b></td></tr>
		<tr><td>$exibe[OBS1]</td></tr>
		<tr><td>$exibe[OBS2]</td></tr>
		<tr><td>$exibe[OBS3]</td></tr>
		<tr><td>$exibe[OBS4]</td></tr>
		<tr><td>$exibe[OBS5]</td></tr>
		</table><br>";
		
}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

//---------------------------------------------------------------------------------------------------------
	//RELACAO DE ITENS DO PEDIDO
try{
	$relmp = $conectar->query("SELECT
B.ITEM AS ITEM,
B.MATERIAL AS MATERIAL,
B.DESCRI AS DESCRICAO,
B.QUANT AS QTDE,
B.UNIDADE AS UNIDADE,
B.VALUNI AS VLRUNIT,
B.VALTOT AS VLRTOTAL
FROM
FAT00001.dbo.compra AS A
LEFT JOIN FAT00001.dbo.compra2 AS B ON A.PEDIDO=B.PEDIDO AND A.CONTROLE=B.CONTROLE
WHERE
A.PEDIDO = '{$id}'
--GROUP BY B.ITEM,B.CODIGO_PR,B.CODIGO_MP,B.DESCRICAO_MP,B.UNID,B.QTDE,B.QTDETEMP2
ORDER BY B.ITEM, B.MATERIAL");
	
	echo "<table width='100%' id=tbordprod>
		<tr>
			<td width=3%><b>Item</b></td>
			<td width=10%><b>Código</b></td>
			<td width=40%><b>Descrição</b></td>
			<td width=3%><b>Unid</b></td>
			<td width=5%><b>Qtde</b></td>
			<td width=5%><b>Valor Unit</b></td>
			<td width=5% align=right><b>Valor Total</b></td>
		</tr>";

	while
	($linha = $relmp->fetch(PDO::FETCH_ASSOC)) {

		$qtde = number_format($linha['QTDE'],2,',', '.');
		$vlr_unit = number_format($linha['VLRUNIT'],2,',', '.');
		$vlr_total = number_format($linha['VLRTOTAL'],2,',', '.');

		echo "<tr>
				<td>$linha[ITEM]</td>
				<td>$linha[MATERIAL]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNIDADE]</td>
				<td align=right>$qtde</td>
				<td align=right>$vlr_unit</td>
				<td align=right>$vlr_total</td>
			</tr>";
	}
	echo "</table>";
	
	echo "TOTAL DE ITENS: " . $relmp->rowCount();
	echo "<br><br>TOTAL DO PEDIDO: " . $vlr_pedido;
	echo "<br><br><br>";

}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

echo "<br><br><br>";

//---------------------------------------------------------------------------------------------------------
// RODAPE COM ASSINATURAS
/*echo "<table border='0'>
		<tr>
			<td>____________________________________&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
			<td>____________________________________</td>
		</tr>
		<tr>
			<td>Responsável pela Admin Produção</td>
			<td>Responsável pela Produção</td>
		</tr></table>";*/
		
?>
</body>
</html>
