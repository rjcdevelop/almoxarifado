<!DOCTYPE html>
<html>

<head>
<title>Editar O.F.</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	//echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	echo "<a href='index.php'><button>Voltar</button></a><br><br>";
	
	include_once "../../../conexao.php";
	
	//recebe numero do LOTE
	$id=filter_var($_GET['id'], FILTER_SANITIZE_STRING);

	//QUERY DO LOTE PARA FORMULARIO
	$sql_form = $conectar->query("SELECT
	A.cod_of AS 'COD_OF_LOTE',
	B.cod_lote AS 'COD_LOTE',
	CONVERT(varchar(10),B.prazo_lote,103) as 'PRAZO_LOTE',
	B.prazo_lote as 'DATAFORM',
	B.qtde_lote AS 'QTDE_LOTE',
	B.unid AS UNID_LOTE
	
	FROM pcp_producao.dbo.cad_of AS A
	LEFT JOIN pcp_producao.dbo.cad_lote AS B ON A.cod_of = B.cod_of
	
	WHERE
	B.cod_lote = '$id'
	");

	//RELACIONA DADOS PARA O FORMULARIO
	$alterar = $sql_form->fetch(PDO::FETCH_ASSOC);

	//SELECIONA A O.F. PARA FILTRO DAS QUERY SEGUINTES
	$id_of = $alterar['COD_OF_LOTE'];

	echo "Dados da ORDEM DE FABRICAÇÃO: ".$id;

	//QUERY DA O.F.
	$sql = $conectar->query("SELECT
	A.cod_of AS CODIGOOF,
	A.codcli AS CODIGOCLIENTE,
	A.observ1 AS OBS_OF,
	B.CL_RAZ_SOC AS RAZAO,
	CONVERT(varchar(10),A.prazo_of,103) AS PRAZO_OF,
	A.qtde_of AS QTDEOF
	FROM pcp_producao.dbo.cad_of AS A
	LEFT JOIN FIN00001.dbo.cadcli AS B ON A.codcli = B.CL_CODIGO
	
	WHERE A.cod_of = '$id_of'
	");

	$row = $sql->fetch(PDO::FETCH_ASSOC);
	
	//QUERY DOS LOTES	
	$sql_lote = $conectar->query("SELECT
	A.cod_of AS 'COD_OF_LOTE',
	B.cod_lote AS 'COD_LOTE',
	CONVERT(varchar(10),B.prazo_lote,103) as 'PRAZO_LOTE',
	B.qtde_lote AS 'QTDE_LOTE',
	B.unid AS UNID_LOTE
	
	FROM pcp_producao.dbo.cad_of AS A
	LEFT JOIN pcp_producao.dbo.cad_lote AS B ON A.cod_of = B.cod_of
	
	WHERE
	A.cod_of = '$id_of'
	");

?>

<form>
<table>
<tr>
<td>Número O.F.:</td>
<td><input type="text" readonly="readonly" style="font-size: 10pt; height: 16px; width:60px;" name="codigo_of" value="<?php echo $row['CODIGOOF'] ?>" id="codigo_of"/></td>
</tr>
<tr>
<td>Prazo:</td>
<td><input type="text" readonly="readonly" style="font-size: 10pt; height: 16px; width:90px;" value="<?php echo $row['PRAZO_OF'] ?>" id="prazo_of"/></td>
</tr>
<tr>
<td>Cliente:</td>
<td><input type="text" readonly="readonly" style="font-size: 10pt; height: 16px; width:500px;" value="<?php echo $row['RAZAO'] ?>"/></td>
</tr>

<tr>
<td>Observações:</td>
<td><input type="text" readonly="readonly" style="font-size: 10pt; height: 16px; width:500px;" value="<?php echo $row['OBS_OF'] ?>"/></td>
</tr>

<tr>
<td>Quantidade da O.F.:</td>
<td><input type="number" readonly="readonly" style="font-size: 10pt; height: 16px; width:180px;" value="<?php echo $row['QTDEOF'] ?>"/></td>
</tr>



	


</table>
<br>
</form>
<br>

<?php
echo '<br><br>Cadastre os lotes da O.F.: '.$row['CODIGOOF'].'<br>';


?>
<form action="editarlote.php" method="post">
<table>
<tr>
<td>Lote:</td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:90px;" name="cod_lote" value="<?php echo $alterar['COD_LOTE'] ?>" id="cod_lote"/></td>
<td>Quantidade:</td>
<td><input type="number" style="font-size: 10pt; height: 16px; width:90px;" name="qtde_lote" value="<?php echo $alterar['QTDE_LOTE'] ?>" id="qtde_lote"/></td>
<td>Unidade:</td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:50px;" name="unid_lote" value="<?php echo $alterar['UNID_LOTE'] ?>" id="unid_lote"/></td>
<td>Prazo:</td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="prazo_lote" value="<?php echo $alterar['DATAFORM'] ?>" id="prazo_lote"/></td>
<td><input type="radio" name="acao" value="A" checked>Alterar<input type="radio" name="acao" value="E">Excluir</td>
<td><input type="hidden" name="cod_of" value="<?php echo $row['CODIGOOF'] ?>" id="cod_of"/>
<td><input type="submit" value="Enviar"></td>
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
