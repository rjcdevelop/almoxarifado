<!DOCTYPE html>
<html>

<head>
<title>Editar O.F.</title>
<link href="/css/estilo.css" rel="stylesheet">

<script>function alert_alterado(){alert("Gravado com Sucesso!");}</script>

</head>

<body>

<?php
	//echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	echo "<a href='index.php'><button>Voltar</button></a><br><br>";
	
	include_once "../../../conexao.php";
	
	//recebe numero O.F.
	$id=filter_var($_GET['id']);

	//QUERY DA O.F.
	$sql = $conectar->query("SELECT
	A.cod_of AS CODIGOOF,
	A.codcli AS CODIGOCLIENTE,
	A.observ1 AS OBS_OF,
	B.CL_RAZ_SOC AS RAZAO,
	CONVERT(varchar(10),A.prazo_of,103) AS PRAZO_OF,
	A.prazo_of AS PRAZO_OF2,
	A.qtde_of AS QTDEOF
	FROM pcp_producao.dbo.cad_of AS A
	LEFT JOIN FIN00001.dbo.cadcli AS B ON A.codcli = B.CL_CODIGO	
	WHERE A.cod_of = '$id'
	");

	$row = $sql->fetch(PDO::FETCH_ASSOC);
	
	

	//QUERY DOS LOTES	
	$sql_lote = $conectar->query("SELECT
	A.cod_of AS COD_OF_LOTE,
	B.cod_lote AS COD_LOTE,
	CONVERT(varchar(10),B.prazo_lote,103) as PRAZO_LOTE,
	B.qtde_lote AS QTDE_LOTE,
	B.unid AS UNID_LOTE	
	FROM
	pcp_producao.dbo.cad_lote AS B
	LEFT JOIN pcp_producao.dbo.cad_of AS A ON A.cod_of = B.cod_of
	--pcp_producao.dbo.cad_of AS A
	--LEFT JOIN pcp_producao.dbo.cad_lote AS B ON A.cod_of = B.cod_of	
	WHERE
	A.cod_of = '$id'
	");

	echo "Dados da ORDEM DE FABRICAÇÃO:";

	
//$qtde_of = number_format($linha['QTDEOF'],2,',', '.');
if ($sql_lote->rowCount() === 0){
	echo "<br><form action='editarof.php' method='post'><table>
	<tr><td>Número O.F.:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:60px;' name='codigo_of' value='$row[CODIGOOF]' id='codigo_of'/></td></tr>
	<tr><td>Prazo:</td><td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='prazo_of' value='$row[PRAZO_OF2]' id='prazo_of'/></td></tr>
	<tr><td>Cliente:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:500px;' value='$row[RAZAO]'/></td></tr>
	<tr><td>Observações:</td><td><input type='text' style='font-size: 10pt; height: 16px; width:500px;' name='obs_of' value='$row[OBS_OF]' id='obs_of'/></td></tr>
	<tr><td><input type='radio' name='acao' value='A' checked>Alterar<input type='radio' name='acao' value='E'>Excluir</td></tr>
	<tr><td>Quantidade da O.F.:</td><td><input type='number' style='font-size: 10pt; height: 16px; width:180px;' name='qtde_of' value='$row[QTDEOF]' id='qtde_of' min='0' step='.000001'/></td></tr>
	</tr><tr><td><input type='submit' value='Enviar' onclick='alert_alterado()'></td></tr>
	</table></form><br>";
}else{
	echo "<br><form action='editarof.php' method='post'><table>
	<tr><td>Número O.F.:</td>
	<td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:60px;' name='codigo_of' value='$row[CODIGOOF]' id='codigo_of'/></td></tr>
	<tr><td>Prazo:</td><td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='prazo_of' value='$row[PRAZO_OF2]' id='prazo_of'/></td></tr><tr><td>Cliente:</td>
	<td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:500px;' value='$row[RAZAO]'/></td></tr>	
	<tr><td>Observações:</td><td><input type='text' style='font-size: 10pt; height: 16px; width:500px;' name='obs_of' value='$row[OBS_OF]' id='obs_of'/></td></tr>
	<tr><td>Quantidade da O.F.:</td><td><input type='number' style='font-size: 10pt; height: 16px; width:180px;' name='qtde_of' value='$row[QTDEOF]' id='qtde_of' min='0' step='.000001'/></td></tr>
	<tr><td><input type='hidden' name='acao' value='A' id='acao' </td></tr>
	</tr><tr><td><input type='submit' value='Enviar' onclick='alert_alterado()'></td></tr>
	</table></form>
	<br>";
}

echo '<br><br>Cadastre os lotes da O.F.: '.$row['CODIGOOF'].'<br>';

?>

<form name="formlote" action="cadastrarlote.php" method="post">
<table>
<tr>
<td>Lote:</td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:90px;" name="cod_lote" id="cod_lote"/></td>
<td>Quantidade:</td>
<td><input type="number" style="font-size: 10pt; height: 16px; width:90px;" name="qtde_lote" min='0' max='' step='.000001' id="qtde_lote"/></td>
<td>Unidade:</td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:50px;" name="unid_lote" id="unid_lote"/></td>
<td>Prazo:</td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="prazo_lote" id="prazo_lote"/></td>
<input type="hidden" name="cod_of" value="<?php echo $row['CODIGOOF'] ?>" id="cod_of"/>
<td><input type="submit" value="Cadastrar"></td>
</tr>
</table>
</form>

<?php

echo "<br><br>Relação de lotes cadastrados<br><br>";

try{
	
	echo "<table id=tbordzebr>
		<tr>
			<td>O.F.</td>
			<td>Lote</td>
			<td>Prazo Lote</td>
			<td>Quantidade</td>
			<td>Unidade</td>
		</tr>";
	while
	($linha = $sql_lote->fetch(PDO::FETCH_ASSOC)) {
		$qtde_lote = number_format($linha['QTDE_LOTE'],5,',', '.');
		echo "<tr>
				<td>$linha[COD_OF_LOTE]</td>
				<td><a href='formeditarlote.php?id=$linha[COD_LOTE]'>$linha[COD_LOTE]</td>				
				<td>$linha[PRAZO_LOTE]</td>
				<td align=right>$qtde_lote</td>
				<td>$linha[UNID_LOTE]</td>
			</tr>";
	}
	echo "</table>";
	
	echo $sql_lote->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}


?>
</body>

</html>
