<!DOCTYPE html>
<html>
<head>
<title>Relatório por Lote</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	echo "R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='index.php'><button>Voltar</button></a><br>
	Relatório do Almoxarifado por Lote<br><br><br>";

	include_once "../../../conexao.php";
	
	if(isset($_POST['pesquisa'])){
	$id=filter_var($_POST['pesquisa']);





	//$sql_lote = $conectar->query("SELECT A.LOTE AS SQL_LOTE FROM pcp_producao.dbo.almox_movto AS A GROUP BY A.LOTE ORDER BY A.LOTE");
	$sql_lote = $conectar->query("SELECT
	F.cod_of AS SQL_LOTE
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD = B.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS C ON B.TIPINV = C.CODIGO
	LEFT JOIN pcp_producao.dbo.reglote AS D ON D.doc_id = A.CONTROLE AND A.PROD = D.codproduto AND A.ITEM = D.item
	LEFT JOIN pcp_producao.dbo.cad_lote AS E ON E.cod_lote = D.cod_lote
	LEFT JOIN pcp_producao.dbo.cad_of AS F ON F.cod_of = E.cod_of
	LEFT JOIN FAT00001.dbo.requis2 AS G ON A.PROD = G.PRODUTO AND A.CONTROLE = G.CONTROLE AND A.ITEM = G.ITEM
	LEFT JOIN FAT00001.dbo.requis1 AS H ON G.CONTROLE = H.CONTROLE AND H.DOCUM = G.DOCUM
	LEFT JOIN FAT00001.dbo.Devol2 AS I ON A.PROD = I.PRODUTO AND A.CONTROLE = I.CONTROLE AND A.ITEM = I.ITEM
	LEFT JOIN FAT00001.dbo.Devol1 AS J ON I.CONTROLE = J.CONTROLE AND I.DOCUM = J.DOCUM
	WHERE F.cod_of IS NOT NULL
	GROUP BY F.cod_of
	ORDER BY F.cod_of
	");

	echo "<form action='rel_lote.php' method='post'>
		<table><tr>
		<td><label>Pesquisar Lote: </label></td>
		<td><select name='pesquisa'>";
		while ($row_lote = $sql_lote->fetch(PDO::FETCH_ASSOC)){
			echo "<option id='pesquisa_lote' name='pesquisa_lote' value='$row_lote[SQL_LOTE]'>$row_lote[SQL_LOTE]</option>";}
	echo "<td><input type='submit' value='Buscar'></td>
	</tr></table></form>";
	echo '* Se desejar corrigir o lote de algum item, clique no número exibido no campo "LOTE"';

	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT
	A.CONTROLE AS MOVTO_ID,
	A.LOTE AS LOTE1,
	CONVERT(varchar(10),A.DATA,103) as DATA,
	A.PROD AS CODIGO,
	B.DESCRICAO AS DESCRICAO,
	B.UNID AS UNIDADE,
	A.QUANT AS QTDE,
	A.VALUNI AS CUNIT,
	A.VALTOT AS CITEM,
	CASE WHEN A.ROTINA = 'REQ' THEN H.OBSERV ELSE J.OBSERV END AS OBSERVACAO,
	C.DESCRICAO AS TIPO_MATL,
	A.ROTINA AS ROTINA,
	D.cod_lote AS LOTE,
	E.cod_lote AS LOTE2,
	F.cod_of AS CODOF
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD = B.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS C ON B.TIPINV = C.CODIGO
	LEFT JOIN pcp_producao.dbo.reglote AS D ON D.doc_id = A.CONTROLE AND A.PROD = D.codproduto AND A.ITEM = D.item
	LEFT JOIN pcp_producao.dbo.cad_lote AS E ON E.cod_lote = D.cod_lote
	LEFT JOIN pcp_producao.dbo.cad_of AS F ON F.cod_of = E.cod_of
	LEFT JOIN FAT00001.dbo.requis2 AS G ON A.PROD = G.PRODUTO AND A.CONTROLE = G.CONTROLE AND A.ITEM = G.ITEM
	LEFT JOIN FAT00001.dbo.requis1 AS H ON G.CONTROLE = H.CONTROLE AND H.DOCUM = G.DOCUM
	LEFT JOIN FAT00001.dbo.Devol2 AS I ON A.PROD = I.PRODUTO AND A.CONTROLE = I.CONTROLE AND A.ITEM = I.ITEM
	LEFT JOIN FAT00001.dbo.Devol1 AS J ON I.CONTROLE = J.CONTROLE AND I.DOCUM = J.DOCUM

	WHERE F.cod_of ='$id'

	ORDER BY C.DESCRICAO, A.DATA
	");

	$sql_totais = $conectar -> query ("SELECT
	SUM(A.QTDE*B.PREMED) AS CITEM,	A.TP_MOVTO AS TIPO
	FROM	pcp_producao.dbo.almox_movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.COD_ITEM = B.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS C ON B.TIPINV = C.CODIGO
	WHERE LOTE ='$id' AND A.TP_MOVTO='S' GROUP BY A.TP_MOVTO");

	$sql_custo = $conectar -> query ("SELECT
	SUM(A.QTDE*B.PREMED) AS CITEM,	A.TP_MOVTO AS TIPO
	FROM	pcp_producao.dbo.almox_movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.COD_ITEM = B.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS C ON B.TIPINV = C.CODIGO
	WHERE LOTE ='$id' AND A.TP_MOVTO='S' AND B.TIPINV IN('0002','0004') GROUP BY A.TP_MOVTO");
	
echo "<br><br>RELAÇÃO DE ITENS DO LOTE: ".$id."<br>";

try{
	echo "<table id='tbordz'>
		<tr>
			<td>TIPO MATERIAL</td>
			<td>LOTE</td>
			<td>DATA</td>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>UN</td>
			<td>QTDE</td>
			<td>C. UNIT</td>
			<td>C. ITEM</td>
			<td>OBSERVAÇÃO</td>
			<td>E/S</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$qtde = number_format($linha['QTDE'],5,',', '.');
		$custo_unit = number_format($linha['CUNIT'],2,',', '.');
		$custo_item = number_format($linha['CITEM'],2,',', '.');
		
		echo "<tr>
				<td>$linha[TIPO_MATL]</td>
				<td>$linha[LOTE]</td>
				<td>$linha[DATA]</td>
				<td>$linha[CODIGO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNIDADE]</td>
				<td align='right'>$qtde</td>
				<td align='right'>$custo_unit</td>
				<td align='right'>$custo_item</td>
				<td>$linha[OBSERVACAO]</td>
				<td>$linha[ROTINA]</td>
			</tr>";
	}
	echo "</table><br>";

	if($sql_totais->rowCount() === 0){
		$custo_total = "0,00";
	}else{ while
	($row = $sql_totais->fetch(PDO::FETCH_ASSOC)) {
		$custo_total = number_format($row['CITEM'],2,',', '.');
	}}
	if($sql_custo->rowCount() === 0){
		$custo_mp = "0,00";
	}else{ while
	($row = $sql_custo->fetch(PDO::FETCH_ASSOC)) {
		$custo_mp = number_format($row['CITEM'],2,',', '.');
	}}
		echo "<table>
				<tr><td>VALOR TOTAL - TODAS AS SAÍDAS: </td><td align = 'right'>$custo_total</td></tr>
				<tr><td>CUSTO TOTAL - MAT. PRIMA / EMBALAGEM: </td><td align = 'right'>$custo_mp</td></tr>
				</table><br>";
	
	echo $sql->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
	}else {
		$id='VAZIO';
		//$sql_lote = $conectar->query("SELECT A.LOTE AS SQL_LOTE FROM pcp_producao.dbo.almox_movto AS A GROUP BY A.LOTE ORDER BY A.LOTE");
		$sql_lote = $conectar->query("SELECT
	F.cod_of AS SQL_LOTE
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD = B.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS C ON B.TIPINV = C.CODIGO
	LEFT JOIN pcp_producao.dbo.reglote AS D ON D.doc_id = A.CONTROLE AND A.PROD = D.codproduto AND A.ITEM = D.item
	LEFT JOIN pcp_producao.dbo.cad_lote AS E ON E.cod_lote = D.cod_lote
	LEFT JOIN pcp_producao.dbo.cad_of AS F ON F.cod_of = E.cod_of
	LEFT JOIN FAT00001.dbo.requis2 AS G ON A.PROD = G.PRODUTO AND A.CONTROLE = G.CONTROLE AND A.ITEM = G.ITEM
	LEFT JOIN FAT00001.dbo.requis1 AS H ON G.CONTROLE = H.CONTROLE AND H.DOCUM = G.DOCUM
	LEFT JOIN FAT00001.dbo.Devol2 AS I ON A.PROD = I.PRODUTO AND A.CONTROLE = I.CONTROLE AND A.ITEM = I.ITEM
	LEFT JOIN FAT00001.dbo.Devol1 AS J ON I.CONTROLE = J.CONTROLE AND I.DOCUM = J.DOCUM
	WHERE F.cod_of IS NOT NULL
	GROUP BY F.cod_of
	ORDER BY F.cod_of
	");

		echo "<form action='rel_lote.php' method='post'>
			<table><tr>
			<td><label>Pesquisar Lote: </label></td>
			<td><select name='pesquisa'>";
			while ($row_lote = $sql_lote->fetch(PDO::FETCH_ASSOC)){
				echo "<option id='pesquisa_lote' name='pesquisa_lote' value='$row_lote[SQL_LOTE]'>$row_lote[SQL_LOTE]</option>";}
		echo "<td><input type='submit' value='Buscar'></td>
		</tr></table></form>";

	}

	

?>
</body>

</html>
