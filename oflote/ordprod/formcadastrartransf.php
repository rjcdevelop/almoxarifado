<!DOCTYPE html>
<html>
<head>
<title>Vincular Lote x Transferência</title>
<link href="/css/estilo.css" rel="stylesheet">
<script>function alert_alterado(){alert("Gravado com Sucesso!");}</script>
</head>

<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA<br>
	Vincule a Transferência da O.P. ao Lote:<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)">
			<a href="../cadof/index.php"><button>Cadastrar Lote</button></a><br><br>';
	
			include_once "../../conexao.php";
	
	//RECEBE O CODIGO DA TRASFERENCIA
	$id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
	//echo "numero da transferência: ".$id;
	
	$sql = $conectar->query("SELECT
	A.CODTRANS AS COD_TRANSF,
	A.CODOP AS COD_OP,
	CONVERT(VARCHAR(10),A.DATATU,103) AS 'DATA',
	D.OBSERVACAO+D.OBSERVACAO2 AS OBS_OP,
	A.OBSERVACAO AS OBS_TRANSF
	FROM
	FAT00001.dbo.Transf1 AS A
	LEFT OUTER JOIN	pcp_producao.dbo.ordprod AS B ON B.transf_op=A.CODTRANS
	LEFT OUTER JOIN pcp_producao.dbo.cad_lote AS C ON B.cod_lote=C.cod_lote,
	FAT00001.dbo.CadOP1 AS D
	WHERE
	A.CODOP=D.CODIGO
	AND A.CODTRANS='$id'
	");

	$row = $sql->fetch(PDO::FETCH_ASSOC);

	$sql_lote = $conectar->query("SELECT cod_lote AS SQL_LOTE FROM cad_lote");
	
	$sql_predio = $conectar->query("SELECT cod_predio AS SQL_PREDIO FROM cad_predio");

	$sql_transf = $conectar->query("SELECT A.transf_op AS SQL_TRANSF FROM ordprod AS A WHERE A.transf_op = '$id'");
	//$row_transf = $sql_transf->fetch(PDO::FETCH_ASSOC);
	
	if ($sql_transf->rowCount() === 0){
		echo "<form action='cadastrartransf.php' method='post'>
		<table>
		<tr><td>Lote:</td>
		<td><select name='cod_lote'>";
		while ($row_lote = $sql_lote->fetch(PDO::FETCH_ASSOC)){
			echo "<option id='lote_sql' name='lote_sql' value='$row_lote[SQL_LOTE]'>$row_lote[SQL_LOTE]</option>";}
			echo "</tr><tr><td>Prédio:</td><td><select name='cod_predio'>";
		while ($row_predio = $sql_predio->fetch(PDO::FETCH_ASSOC)){
			echo "<option id='predio_sql' name='predio_sql' value='$row_predio[SQL_PREDIO]'>$row_predio[SQL_PREDIO]</option>";}
			echo "</tr><tr><td>Transf O.P.:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:90px;' name='cod_transf' value='$id' id='cod_transf'/></td></tr>
			<tr><td>Obs. O.P.:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:800px;' value='$row[OBS_OP]'/></td></tr>
			<tr><td>Obs. Transf:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:800px;' value='$row[OBS_TRANSF]'/></td></tr>
			</table><br>
			<input type='hidden' readonly='readonly' name='cod_op' id='cod_op' value='$row[COD_OP]'/>
			<input type='hidden' readonly='readonly' name='acao' id='acao' value='I'/>
			<input type='submit' value='Cadastrar'><br>
			</form>
			";
	}else {
		echo "<form action='cadastrartransf.php' method='post'>
		<table>
		<tr><td>Lote:</td><td><select name='cod_lote'>";
		while ($row_lote = $sql_lote->fetch(PDO::FETCH_ASSOC)){
			echo "<option id='lote_sql' name='lote_sql' value='$row_lote[SQL_LOTE]'>$row_lote[SQL_LOTE]</option>";}
			echo "</tr><tr><td>Prédio:</td><td><select name='cod_predio'>";
		while ($row_predio = $sql_predio->fetch(PDO::FETCH_ASSOC)){
			echo "<option id='predio_sql' name='predio_sql' value='$row_predio[SQL_PREDIO]'>$row_predio[SQL_PREDIO]</option>";}
			echo "</tr><tr><td>Transf O.P.:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:90px;' name='cod_transf' value='$id' id='cod_transf'/></td></tr>
			<tr><td>Obs. O.P.:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:800px;' value='$row[OBS_OP]'/></td></tr>
			<tr><td>Obs. Transf:</td><td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:800px;' value='$row[OBS_TRANSF]'/></td></tr><br>
			<tr><td><input type='radio' name='acao' value='A' checked>Alterar<input type='radio' name='acao' value='E'>Excluir</td></tr><br>
			<tr><td><input type='submit' value='Enviar' onclick='alert_alterado()'></td></tr>
			<input type='hidden' readonly='readonly' name='cod_op' id='cod_op' value='$row[COD_OP]'/>
			<input type='submit' value='Cadastrar'><br>
			</form>
			";
	}
		

?>

</body>
</html>
