<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movimento</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	//echo "<a href="index.php"><button>Voltar</button></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp<br><a href="index.php"><button>Voltar</button></a>";
	
	$hoje = date('d/m/Y');

	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b><br>";
	echo "<b>Movimentação do estoque</b></center><br><br>";
	echo '<b>Data emissão: </b>'.$hoje;
	echo "&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";

	include_once "../../conexao.php";

//$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
//echo $id;

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	if(isset($_GET['id'])){
		$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
		
	}else {
		$id = filter_var($_POST['id']);
	}

	if(isset($_GET['dt_ini'])){
		$id_dtini=filter_var($_GET['dt_ini'], FILTER_SANITIZE_STRING);
	}else {
		$id_dtini=filter_var($_POST['dt_ini']);
	}

	if(isset($_GET['dt_fim'])){
		$id_dtfim=filter_var($_GET['dt_fim'], FILTER_SANITIZE_STRING);
	}else {
		$id_dtfim=filter_var($_POST['dt_fim']);
	}

?>

<form action="movimento.php" method="POST">
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="dt_ini"/></td>
<td><label>Data Final: </label></td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="dt_fim"/></td>

<td><input type="hidden" name="id" value="<?php echo $id; ?>" /></td>

<td><input type="submit" value="Buscar"></td>
</tr>
</table>
</form>
<br><br>

<?php

	echo "Período de lançamentos: " .date('d/m/Y', strtotime($id_dtini))." a ".date('d/m/Y', strtotime($id_dtfim))." do item: ".$id."<br><br>";


	$consulta = $conectar->query("SELECT
	convert(varchar(10),A.DATA,103) AS 'EMISSAO',
	A.NOTA AS NOTA,
	A.RAZAO AS RAZAO,
	A.OBSSAI AS OBSSAI,
	A.PROD AS CODIGO,
	REPLACE(CONVERT(VARCHAR,CAST(A.QUANT AS NUMERIC(18,5)), 1),'.',',') AS QTDE,
	A.DESCRICAO AS DESCRICAO
	FROM FAT00001.dbo.movto AS A
	WHERE
	A.DATA >= '$id_dtini' AND A.DATA <= '$id_dtfim'
	AND A.PROD='$id'
	ORDER BY A.DATA
	");
echo "<table id=tbordprod>
<tr>
	<td>DATA</td>
	<td>NOTA</td>
	<td>RAZÃO SOCIAL</td>
	<td>OBSERVAÇÃO</td>
	<td>CODIGO</td>
	<td>QUANTIDADE</td>
	<td>DESCRICAO</td>
</tr>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
echo "<tr>
		<td>$linha[EMISSAO]</td>
		<td>$linha[NOTA]</td>
		<td>$linha[RAZAO]</td>
		<td>$linha[OBSSAI]</td>
		<td>$linha[CODIGO]</td>
		<td>$linha[QTDE]</td>
		<td>$linha[DESCRICAO]</td>
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
