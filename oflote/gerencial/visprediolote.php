<!DOCTYPE html>
<html>

<head>
<title>Visualizar Processo</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "Olá!!<br> Seja bem vindo a visualização do Processo por LOTE<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	//echo "<a href='formordprod.php'><button>Voltar</button></a><br><br>";

include_once "../../conexao.php";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	
	//$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
	$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
	
	$consulta1 = $conectar->query("SELECT
	A.cod_predio AS PREDIO_A,
	B.CODPROD AS CODPROD
	FROM
	pcp_producao.dbo.ordprod AS A
	LEFT JOIN FAT00001.dbo.Transf2 AS B ON A.transf_op=B.CODTRANS	
	WHERE
	A.cod_lote='{$id}'
	GROUP BY
	A.cod_predio,B.CODPROD
	");
	
	while($linha1 = $consulta1 -> fetch(PDO::FETCH_ASSOC)){
		echo "<br>Transferências referentes ao prédio:  $linha1[PREDIO_A]<br>
		<table id=tbordprod>
		<tr>
			<td width=2%>PREDIO</td>
			<td width=3%>LOTE</td>
			<td width=4%>DATA</td>
			<td width=3%>COD TRANSF</td>
			<td width=2%>ITEM</td>
			<td width=8%>CODPROD</td>
			<td width=15%>DESCRICAO</td>
			<td width=2%>UN</td>
			<td width=8%>QTDE</td>
		</tr>";
		
		$id_predio = "$linha1[PREDIO_A]";
		$id_produto = "$linha1[CODPROD]";
		
		//echo "Dentro do while está o número prédio: $linha1[PREDIO_A]";
		//echo "exibiir variavel".$predio_un;
		$consulta2 = $conectar->query("SELECT
	A.cod_predio AS PREDIO,
	A.cod_lote AS LOTE,
	CONVERT(varchar(10),B.DATA,103)AS DT_TRANSF,
	A.transf_op AS TRANSF,
	B.ITEM AS ITEM,
	B.CODPROD AS CODPROD,
	B.DESCRICAO AS DESCRICAO,
	B.UNID AS UNIDADE,
	REPLACE(CONVERT(varchar,CAST(B.QTDE AS NUMERIC(18,5)), 1),'.',',') AS QTDE
	FROM
	pcp_producao.dbo.ordprod AS A
	LEFT JOIN FAT00001.dbo.Transf2 AS B ON A.transf_op=B.CODTRANS
	WHERE
	A.cod_lote ='{$id}' AND A.cod_predio ='{$id_predio}' AND B.CODPROD = '{$id_produto}'
	ORDER BY
	A.cod_predio,B.CODPROD,B.DATA,B.ITEM
	");
		
	//'$linha1[PREDIO_A]'
	
	while($linha2 = $consulta2 -> fetch(PDO::FETCH_ASSOC)){
		echo "
		<tr>
				<td>$linha2[PREDIO]</td>
				<td>$linha2[LOTE]</td>
				<td>$linha2[DT_TRANSF]</td>
				<td>$linha2[TRANSF]</td>
				<td>$linha2[ITEM]</td>
				<td>$linha2[CODPROD]</td>
				<td>$linha2[DESCRICAO]</td>
				<td>$linha2[UNIDADE]</td>
				<td align=right>$linha2[QTDE]</td>
			</tr>";
	}	
	echo "</table>";
	echo "Total de Transferências: " . $consulta2->rowCount();
	
	$consulta3 = $conectar->query("SELECT
	REPLACE(CONVERT(varchar,CAST(SUM(B.QTDE) AS NUMERIC(18,5)), 1),'.',',') AS QT_PRODUZIDA
	FROM
	pcp_producao.dbo.ordprod AS A
	LEFT JOIN FAT00001.dbo.Transf2 AS B ON A.transf_op=B.CODTRANS	
	WHERE
	A.cod_lote='{$id}' AND A.cod_predio='{$id_predio}' AND B.CODPROD = '{$id_produto}'
	");

	while($qtde = $consulta3 -> fetch(PDO::FETCH_ASSOC)){
	echo "  Quantidade Produzida: " . "$qtde[QT_PRODUZIDA]";
	}

	echo "<br>";
}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>

</body>
</html>
