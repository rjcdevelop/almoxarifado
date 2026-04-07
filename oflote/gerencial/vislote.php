<!DOCTYPE html>
<html>

<head>
<title>Consulta Lotes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "Olá!!<br>RELATÓRIO DE LOTES DA ORDEM DE PRODUÇÃO<br><br>";
	echo "<a href='form_of.php'><button>Listar O.F.'s</button></a><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../conexao.php";

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT	
	A.cod_of AS COD_OF,
	B.cod_lote AS COD_LOTE,
	B.unid AS UNID_LOTE,
	B.qtde_lote AS 'QTDE_LOTE',	
	CONVERT(varchar(10),A.prazo_of,103) AS PRAZO_OF,
	F.CL_RAZ_SOC AS RAZAO,
	CASE WHEN COUNT(E.cod_lote) IS NULL THEN '0' ELSE COUNT(E.cod_lote) END AS QT_TRANSF
	FROM
	pcp_producao.dbo.cad_of AS A
	LEFT JOIN pcp_producao.dbo.cad_lote AS B ON A.cod_of=B.cod_of
	LEFT JOIN (SELECT * FROM  pcp_producao.dbo.ordprod AS D) AS E ON B.cod_lote=E.cod_lote
	LEFT JOIN FIN00001.dbo.cadcli AS F ON A.codcli=F.CL_CODIGO
	WHERE
	A.COD_OF='$id'	
	GROUP BY
	A.cod_of,B.cod_lote,F.CL_RAZ_SOC,B.unid,B.qtde_lote,A.prazo_of	
	ORDER BY
	A.cod_of,B.cod_lote
	");

	echo "<table id=tbordprod>
		<tr>
			<td width=3%>O.F.</td>
			<td width=5%>LOTE</td>
			<td width=8%>QTDE PRODUZIDA</td>
			<td width=8%>QTDE DE TRANSF</td>
			<td width=3%>UNIDADE</td>
			<td width=6%>PRAZO O.F.</td>
			<td width=30%>RAZAO SOCIAL</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {


		echo "<tr>
				<td>$linha[COD_OF]</td>
				<td><a href='visprediolote.php?id=$linha[COD_LOTE]'>$linha[COD_LOTE]</a></td>
				<td align=right>$linha[QTDE_LOTE]</td>
				<td align=right>$linha[QT_TRANSF]</td>
				<td align=right>$linha[UNID_LOTE]</td>
				<td align=right>$linha[PRAZO_OF]</td>
				<td>$linha[RAZAO]</td>
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
