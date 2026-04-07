<!DOCTYPE html>
<html>

<head>
<title>Consulta Lotes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "Olá!!<br>RELATÓRIO DE LOTES DA ORDEM DE PRODUÇÃO<br><br>";
	echo "<a href='../index.php'><button>Menu Principal</button></a>
			<a href='blocok_d.php'><button>Por Período</button></a><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../conexao.php";

if(isset($_POST['busca'])){
	$id=filter_var($_POST['busca']);
	}else {
		$id='';
	}

?>

<form action="form_of.php" method="POST">
<table>
<tr>
<td><label>Pesquisar O.F.: </label></td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:300px;" name="busca"/></td>
<td><input type="submit" value="Buscar"></td>
</tr>
</table>
</form>
<br><br>

<?php

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT	
	A.cod_of AS COD_OF,
	CONVERT(varchar(10),A.prazo_of,103) AS PRAZO_OF,
	F.CL_RAZ_SOC AS RAZAO,
	C.transf_op AS TRANSF
	FROM
	pcp_producao.dbo.cad_of AS A
	LEFT JOIN FIN00001.dbo.cadcli AS F ON A.codcli=F.CL_CODIGO
	LEFT JOIN pcp_producao.dbo.cad_lote AS B ON A.cod_of=B.cod_of
	LEFT JOIN pcp_producao.dbo.ordprod AS C ON C.cod_lote=B.cod_of
	WHERE
	A.cod_of LIKE '%$id%'
	GROUP BY
	A.cod_of,F.CL_RAZ_SOC,A.prazo_of,C.transf_op
	ORDER BY
	A.cod_of
	");

	echo "<table id=tbordprod>
		<tr>
			<td width=3%>O.F.</td>
			<td width=6%>PRAZO O.F.</td>
			<td width=30%>RAZAO SOCIAL</td>
			<td width=6%></td>
		</tr>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>
				<td><a href='vislote.php?id=$linha[COD_OF]'>$linha[COD_OF]</a></td>
				<td align=right>$linha[PRAZO_OF]</td>
				<td>$linha[RAZAO]</td>
				<td><a href='blocok.php?id=$linha[COD_OF]'>Relatório</a></td>
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
