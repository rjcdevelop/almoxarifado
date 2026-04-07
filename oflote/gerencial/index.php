<!DOCTYPE html>
<html>
<head>
<title>Relatórios - O.F. e Lotes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php

include_once "../../conexao.php";

	echo "R J C DEFESA E AEROESPACIAL LTDA<br>
	Relatórios da Produção / Bloco K (O.F. / LOTE / O.P.):<br>
	<a href='../index.php'><button>Voltar</button></a><br><br><br>";


	echo "=> Bloco K por O.F.<br>
	<form action='relatorioof.php' method='POST' target='_blank'><table><tr>
	<td><label>O.F. Inicial: </label></td>
	<td><input type='text' style='font-size: 10pt; height: 16px; width:100px;' name='of_ini'/></td>
	<td><label>O.F. Final: </label></td>
	<td><input type='text' style='font-size: 10pt; height: 16px; width:100px;' name='of_fim'/></td>
	<td><input type='submit' value='Buscar'></td>
	</tr></table></form><br>";


	echo "=> Bloco K por período<br>
	<form action='blocok_d.php' method='POST' target='_blank'><table><tr>
	<td><label>Data Inicial: </label></td>
	<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini'/></td>
	<td><label>Data Final: </label></td>
	<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim'/></td>
	<td><input type='submit' value='Buscar'></td>
	</tr></table></form><br>";


	echo "=> Bloco K por O.F.<br>
	<form action='blocok_of.php' method='POST' target='_blank'><table><tr>
	<td><label>Número da O.F.: </label></td>
	<td><input type='text' style='font-size: 10pt; height: 16px; width:100px;' name='nro_of'/></td>
	<td><input type='submit' value='Buscar'></td>
	</tr></table></form><br>";


	echo "=> Bloco K por Lote<br>
	<form action='blocok_lote.php' method='POST' target='_blank'>
	<table><tr>
	<td><label>Número do Lote: </label></td>
	<td><input type='text' style='font-size: 10pt; height: 16px; width:100px;' name='nro_lote'/></td>
	<td><input type='submit' value='Buscar'></td>
	</tr></table></form><br>";


	echo "=> Custo por Lote<br>";
	
	$sql_lote = $conectar->query("SELECT A.LOTE AS SQL_LOTE FROM pcp_producao.dbo.almox_movto AS A GROUP BY A.LOTE ORDER BY A.LOTE");
	$sql_lote2 = $conectar->query("SELECT A.cod_lote AS K_LOTE FROM pcp_producao.dbo.ordprod AS A GROUP BY A.cod_lote ORDER BY A.cod_lote");
	echo "<form action='rel_custo.php' method='post' target='_blank'>
		<table><tr>
		<td><label>Almoxarifado/Lote: </label></td>
		<td><select name='pesquisa'>";
		while ($row_lote = $sql_lote->fetch(PDO::FETCH_ASSOC)){
	echo "<option id='pesquisa_lote' name='pesquisa_lote' value='$row_lote[SQL_LOTE]'>$row_lote[SQL_LOTE]</option>";}
	echo "</td><td><label>Bloco K/Lote: </label></td>
			<td><select name='pesquisa2'>";
			while ($row_lote2 = $sql_lote2->fetch(PDO::FETCH_ASSOC)){
	echo "<option id='pesquisa_k' name='pesquisa_k' value='$row_lote2[K_LOTE]'>$row_lote2[K_LOTE]</option>";}
	echo "</td><td><input type='submit' value='Buscar'></td>
	</tr></table></form>";
	
?>

</body>

</html>
