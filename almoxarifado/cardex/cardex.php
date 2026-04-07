<!DOCTYPE html>
<html>
<head>
<title>Kardex</title>
<link href="../../css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	$id=filter_var($_GET['id'], FILTER_SANITIZE_STRING);
	$hoje = date('d/m/Y');
	
	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA<br><br>";
	echo "<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";
	
	include_once "../../conexao.php";
	
	$sql_saldo = $conectar->query("SELECT
	A.PROD AS ITEM,
	B.DESCRICAO AS DESCRICAO,
	REPLACE(CONVERT(VARCHAR,CAST(((CASE WHEN C.S1TOTAL IS NULL THEN 0 ELSE C.S1TOTAL END) + (CASE WHEN D.S2TOTAL IS NULL THEN 0 ELSE D.S2TOTAL END)) AS NUMERIC(18,2)), 1),'.',',') AS SALDO,
	((CASE WHEN C.S1TOTAL IS NULL THEN 0 ELSE C.S1TOTAL END) + (CASE WHEN D.S2TOTAL IS NULL THEN 0 ELSE D.S2TOTAL END)) AS SALDO2,
	E.DESCRICAO AS TIPINV,
	B.ESTMIN AS ESTMIN
	
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD = B.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS E ON B.TIPINV = E.CODIGO
	LEFT JOIN (SELECT S1.PROD AS S1ITEM, SUM(S1.QUANT) AS S1TOTAL FROM FAT00001.dbo.movto S1 WHERE S1.TIPO='E' AND S1.PROD='$id' GROUP BY S1.PROD) AS C ON A.PROD=C.S1ITEM
	LEFT JOIN (SELECT S2.PROD AS S2ITEM, SUM(S2.QUANT) AS S2TOTAL FROM FAT00001.dbo.movto S2 WHERE S2.TIPO='S' AND S2.PROD='$id' GROUP BY S2.PROD) AS D ON A.PROD=D.S2ITEM
	WHERE
	A.PROD='$id'
	GROUP BY A.PROD,B.DESCRICAO,C.S1TOTAL,D.S2TOTAL,E.DESCRICAO,B.ESTMIN
	");

	$linha1 = $sql_saldo->fetch(PDO::FETCH_ASSOC);
	$saldo_item = "$linha1[SALDO]";
	$saldo_item2 = number_format($linha1['SALDO2'],2,',', '.');
	$est_min = number_format($linha1['ESTMIN'],2,',', '.');
	
	if("$linha1[SALDO2]" <= "$linha1[ESTMIN]"){
		$corfundo = 'red';
	}else{
		$corfundo = '#fff78c';
	}

	echo "CARTÃO KARDEX DO ITEM:<br>
		<table id=tbordzebr>
		<tr>
			<td width='150px'>CÓDIGO</td>
			<td width='440px'>DESCRIÇÃO</td>
			<td width='130px'>SALDO</td>
			<td width='130px'>EST. MÍNIMO</td>
			<!-- <td width='200px'>INVENTARIO</td> -->
		</tr>
		<tr>
			<td><!-- <a href='movtofiscal.php?id=$linha1[ITEM]'></a> -->$linha1[ITEM]</td>
			<td>$linha1[DESCRICAO]</td>
			<td align=right>$saldo_item2</td>
			<td align=right bgcolor=$corfundo>$est_min</td>
			<!-- <td bgcolor=$corfundo>$linha1[TIPINV]</td> -->
		</tr>
		</table>
		";


	echo "<br><br>KARDEX do item:";
	


	$sql = $conectar->query("SELECT
	A._RID_ AS ID_ALMOX,
	A.PROD AS PROD,
	A.TIPO AS TP_MOVTO,
	CONVERT(varchar(10),A.DATA,103) as 'DATA',
	A.QUANT AS QTDE,
	A.VALUNI AS VALOR,
	B.UNID AS UNID,
	CASE WHEN A.CONTROLE=C.doc_id THEN C.cod_lote ELSE '' END AS 'LOTE',
	(A.OBSSAI
	+ CASE
	WHEN D.OBSERV IS NOT NULL THEN ('/' + D.OBSERV)
	WHEN D.OBSERV IS NULL THEN ''
	END
	) AS OBSERVACAO,
	A.ROTINA AS ROTINA
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD=B.CODIGO
	LEFT JOIN pcp_producao.dbo.reglote AS C ON A.CONTROLE = C.doc_id AND A.PROD = C.codproduto
	LEFT JOIN FAT00001.dbo.Devol1 AS D ON A.CONTROLE = D.CONTROLE AND A.NOTA = D.DOCUM
	WHERE
	A.PROD = '$id'
	ORDER BY A.DATA DESC
");

try{
	
	echo "<table id=tbordzebr>
		<tr>
			<td width='70px'>DATA</td>
			<td width='40px'>ROTINA</td>
			<td width='40px'>MOVTO</td>
			<td width='30px'>UNID</td>
			<td width='120px'>QTDE</td>
			<td width='120px'>VALOR</td>
			<td>OBSERVAÇÃO</td>
			<td width='80px'>LOTE</td>
			<td width='100px'>DOCUMENTO</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$qtde = number_format($linha['QTDE'],2,',', '.');
		$vlrmovto = number_format($linha['VALOR'],5,',', '.');
		echo "<tr>
				<td>$linha[DATA]</td>
				<td>$linha[ROTINA]</td>
				<td>$linha[TP_MOVTO]</td>
				<td>$linha[UNID]</td>
				<td align=right>$qtde</td>
				<td align=right>$vlrmovto</td>
				<td>$linha[OBSERVACAO]</td>
				<td>$linha[LOTE]</td>
				<td>$linha[ID_ALMOX]</td>
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
