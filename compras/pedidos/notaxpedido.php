<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Notas x Pedidos</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>
	
<?php

//---------------------------------------------------------------------------------------------------------
//	TITULO E CABEÇALHO DO FORMULARIO
	$hoje = date('d/m/Y');
	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b><br>";
	echo "<b>N O T A S &nbsp &nbsp &nbsp P O R &nbsp &nbsp &nbsp P E D I D O</b><br><br>";

	include_once "../../conexao.php";


//---------------------------------------------------------------------------------------------------------
//CABEÇALHO DA ORDEM DE PRODUCAO
	//EXECUÇÃO DA INSTRUCAO SQL
	$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
	

	echo "<table border='0'>
		<tr>
			<td align=right><b>Data Consulta: </b></td>
			<td>$hoje</td><td>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<td>
			<td align=right><b>Pedido: </b></td>
			<td>$id</td>
		</tr>
		</table>";

echo "<br><br><br>";



//---------------------------------------------------------------------------------------------------------
	//RELACAO DE ITENS DO PEDIDO
try{
	$relnf = $conectar->query("SELECT
	B.ITEM AS ITEM,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
	B.NOTA AS NOTA,
	B.PRODUTO AS PRODUTO,
	B.DESCRI AS DESCRICAO,
	B.QUANT AS QTDE,
	B.VALUNI AS VALUNI,
	B.VALTOT AS VALTOT,
	B.CFOP AS CFOP
	FROM FAT00001.dbo.entrada1 AS A
	LEFT JOIN FAT00001.dbo.entrada2 AS B ON A.CONTROLE = B.CONTROLE
	WHERE
	A.PEDIDO = '{$id}'
	ORDER BY B.NOTA,B.ITEM");

		$somanfs = $conectar->query("SELECT
			SUM(B.QUANT) AS SOMAQTDE,
			SUM(B.VALTOT) AS SOMAVALTOT
			FROM FAT00001.dbo.entrada1 AS A
			LEFT JOIN FAT00001.dbo.entrada2 AS B ON A.CONTROLE = B.CONTROLE
			WHERE
			A.PEDIDO = '{$id}'");

	echo "<table id=tbordprod>
		<tr>
			<td width='80px'><b>Emissão</b></td>
			<td width='30px'><b>Item</b></td>
			<td width='75x'><b>NF</b></td>
			<td width='140px'><b>Código</b></td>
			<td width='600px'><b>Descrição</b></td>
			<td width='80px'><b>Qtde</b></td>
			<td width='110px'><b>Valor Unit</b></td>
			<td width='110px' align=right><b>Valor Total</b></td>
			<td width='45px'><b>CFOP</b></td>
		</tr>";
		
	while
	($linha = $relnf->fetch(PDO::FETCH_ASSOC)) {

		$qtde = number_format($linha['QTDE'],2,',', '.');
		$vlr_unit = number_format($linha['VALUNI'],2,',', '.');
		$vlr_total = number_format($linha['VALTOT'],2,',', '.');
		$countqtde = $linha['QTDE'] ++;

		echo "<tr>
				<td>$linha[EMISSAO]</td>
				<td>$linha[ITEM]</td>
				<td>$linha[NOTA]</td>
				<td>$linha[PRODUTO]</td>
				<td>$linha[DESCRICAO]</td>
				<td align=right>$qtde</td>
				<td align=right>$vlr_unit</td>
				<td align=right>$vlr_total</td>
				<td>$linha[CFOP]</td>
			</tr>";
			
	}
	while ($row = $somanfs->fetch(PDO::FETCH_ASSOC)){
		$qtdetot = number_format($row['SOMAQTDE'],2,',', '.');
		$valortotal = number_format($row['SOMAVALTOT'],2,',', '.');
		echo "<tr><td COLSPAN = '5'>TOTAIS</td>
	<td align=right>$qtdetot</td><td></td><td align=right>$valortotal</td></tr></table>";
	}
	
	echo "<br><br><br>";

}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

echo "<br><br><br>";

		
?>
</body>
</html>
