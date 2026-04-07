<!DOCTYPE html><html>
<head>
	<title>Relatório por Lote</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	echo "R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp	<br>
	Relatório de custo por LOTE (Bloco K / Almoxarifado)<br><br>";
	//<a href='index.php'><button>Voltar</button></a><br>

	include_once "../../conexao.php";
		
		$id1 = filter_var($_POST['pesquisa']);
		$id2 = filter_var($_POST['pesquisa2']);
		//$qtde_lote = filter_var($_POST['qtdelote']);
	
	//QUERY BLOCOK / FOLHAMATIC
	$sql = $conectar->query("SELECT
	A.TIPINV AS TIPINV,
	A.CODIGO AS CODIGO,
	A.DESCRICAO AS DESCRICAO,
	A.UNID AS UNIDADE,
	B.QTDE AS QTDE_ALMOX,
	B.QTDE * A.PREMED AS CUSTO_ALMOX,
	C.QTDE_MP * -1 AS QTDE_K,
	(SUM(C.QTDE_MP) * A.PREMED * -1) AS CUSTO_K
	FROM
	(SELECT P.CODIGO,P.TIPINV,P.PREMED,P.UNID,P.DESCRICAO FROM FAT00001.dbo.produto AS P
	WHERE P.TIPINV NOT IN ('0001','0003','0006','0007','0008','0009','0010','0011','0012')
	) AS A
	-- 2a TABELA
	LEFT JOIN(SELECT SUM(Q.QTDE) AS QTDE,Q.COD_ITEM AS COD_ITEM
	FROM pcp_producao.dbo.almox_movto AS Q
	WHERE LOTE = '$id1'
	GROUP BY Q.COD_ITEM) AS B ON A.CODIGO = B.COD_ITEM
	-- 3a TABELA
	LEFT JOIN (SELECT
	E.cod_lote AS NRO_LOTE,
	A.CODOP AS CODOP,
	A.CODTRANS AS CODTRANSF,
	A.CODPROD AS PRODFINAL,
	A.DESCRICAO AS DESCRICAO,
	B.QUANT AS QTPRODFINAL,
	F.PROD AS COD_MP,
	F.DESCRICAO AS DESCR_MP,
	F.QUANT AS QTDE_MP,
	C.OBSERVACAO AS OBSTRANSF
	FROM
	FAT00001.dbo.Transf2 AS A
	LEFT JOIN FAT00001.dbo.movto AS B ON A.CONTRENTR=B.CONTROLE
	LEFT JOIN FAT00001.dbo.Transf1 AS C ON A.CODTRANS=C.CODTRANS
	LEFT JOIN pcp_producao.dbo.ordprod AS D ON A.CODTRANS=D.transf_op
	LEFT JOIN pcp_producao.dbo.cad_lote AS E ON E.cod_lote=D.cod_lote
	LEFT JOIN FAT00001.dbo.movto AS F ON A.CODTRANS=F.CONTROLE AND A.DATA=F.DATA
	WHERE
	E.cod_lote = '$id2') AS C ON A.CODIGO = C.COD_MP
	-- WHERE PRINCIPAL
	WHERE
	B.COD_ITEM IS NOT NULL OR C.COD_MP IS NOT NULL
	GROUP BY A.TIPINV,A.CODIGO,A.DESCRICAO,A.UNID,B.QTDE,A.PREMED,C.QTDE_MP
	ORDER BY A.TIPINV");

	//QUERY ALMOXARIFADO
	$sql_almox = $conectar -> query("SELECT
	SUM(A.QTDE * B.PREMED) AS SOMA_ALMOX
	FROM pcp_producao.dbo.almox_movto AS A
	LEFT JOIN (SELECT P.CODIGO AS CODIGO, P.PREMED AS PREMED FROM FAT00001.dbo.produto AS P
	WHERE P.TIPINV NOT IN ('0001','0003','0006','0007','0008','0009','0010','0011','0012')) AS B ON B.CODIGO=A.COD_ITEM 
	WHERE LOTE = '$id1'
	");
	while
	($sql_somaalmox = $sql_almox->fetch(PDO::FETCH_ASSOC)) {
	$soma_sqlalmox = $sql_somaalmox['SOMA_ALMOX'];
}
	$soma_almox = number_format($soma_sqlalmox,2,',', '.');

//QUERY BLOCO K FOLHAMATIC
$sql_k = $conectar -> query("SELECT
SUM(B.QTDE_MP * A.PREMED * -1) AS SOMA_K
FROM (SELECT P.CODIGO AS CODIGO, P.PREMED AS PREMED FROM FAT00001.dbo.produto AS P
WHERE P.TIPINV NOT IN ('0001','0003','0006','0007','0008','0009','0010','0011','0012')) AS A
LEFT JOIN (SELECT F.PROD AS COD_MP, F.QUANT AS QTDE_MP FROM FAT00001.dbo.Transf2 AS A
LEFT JOIN FAT00001.dbo.movto AS B ON A.CONTRENTR=B.CONTROLE
LEFT JOIN FAT00001.dbo.Transf1 AS C ON A.CODTRANS=C.CODTRANS
LEFT JOIN pcp_producao.dbo.ordprod AS D ON A.CODTRANS=D.transf_op
LEFT JOIN pcp_producao.dbo.cad_lote AS E ON E.cod_lote=D.cod_lote
LEFT JOIN FAT00001.dbo.movto AS F ON A.CODTRANS=F.CONTROLE AND A.DATA=F.DATA
WHERE
E.cod_lote = '$id2') AS B ON A.CODIGO = B.COD_MP
");
while
($sql_somak = $sql_k->fetch(PDO::FETCH_ASSOC)) {
$soma_sqlk = $sql_somak['SOMA_K'];
}
$soma_k = number_format($soma_sqlk,2,',', '.');

//QUERY BUSCAR QUANTIDADE LOTE COM BASE LOTE FOLHAMATIC
$sql_lote = $conectar -> query("SELECT A.qtde_lote AS QTDE_LOTE FROM pcp_producao.dbo.cad_lote AS A WHERE A.cod_lote = '$id2'");
while
($sql_qtde = $sql_lote->fetch(PDO::FETCH_ASSOC)) {
$soma_sql_qtde = $sql_qtde['QTDE_LOTE'];
}
$soma_qtde_lote = number_format($soma_sql_qtde,2,',', '.');

$custo_item_almox = $soma_sqlalmox / $soma_sql_qtde;
$custo_item_k = $soma_sqlk / $soma_sql_qtde;

$exibe_custo_item_almox = number_format($custo_item_almox,2,',', '.');
$exibe_custo_item_k = number_format($custo_item_k,2,',', '.');



try{
	echo "Lote Almoxarifado: ".$id1." / Lote Bloco K: ".$id2." / Quantidade Lote: ".$soma_qtde_lote."<br><br>
	<table id=tbordz>
		<tr>
			<td colspan='3'>PRODUTOS APLICADOS NA PRODUÇÃO</td>
			<td colspan='2'>LOTE ALMOX</td>
			<td colspan='2'>LOTE BLOCO K</td>
		</tr>
		<tr>
			<td>CODIGO</td>
			<td>DESCRIÇÃO</td>
			<td>UNID</td>
			<td>QUANT</td>
			<td>VLR CUSTO</td>
			<td>QUANT</td>
			<td>VLR CUSTO</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$qtde_almox = number_format($linha['QTDE_ALMOX'],2,',', '.');
		$qtde_k = number_format($linha['QTDE_K'],2,',', '.');
		$custo_almox = number_format($linha['CUSTO_ALMOX'],2,',', '.');
		$custo_k = number_format($linha['CUSTO_K'],2,',', '.');		
		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNIDADE]</td>
				<td align='right'>$qtde_almox</td>
				<td align='right'>$custo_almox</td>
				<td align='right'>$qtde_k</td>
				<td align='right'>$custo_k</td>
			</tr>";
	}
	
	echo "<tr><td colspan='6'>TOTAL DE REGISTROS:</td><td align='right'>". $sql->rowCount() . "</td>
			<tr><td colspan='4'>CUSTO TOTAL DO LOTE:</td><td align='right'>$soma_almox</td><td></td><td align='right'>$soma_k</td></tr>
			<tr><td colspan='4'>CUSTO POR ITEM DO LOTE:</td><td align='right'>$exibe_custo_item_almox</td><td></td><td align='right'>$exibe_custo_item_k</td></tr>
			</table>";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>

</body>
</html>
