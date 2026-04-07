<!DOCTYPE html>
<html>
<head>
<title>Editar Pedido</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	include_once "../../conexao.php";

	
if(isset($_POST['id'])){
	$id=filter_var($_POST['id'], FILTER_SANITIZE_STRING);
	}else {
		$id = '';
	}

	
	echo "<form action='formeditpedido.php' method='POST'>
<table>
<tr>
<td><label>Digite o número do Pedido: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:150px;' name='id'/></td>
<td><input type='submit' value='Buscar'></td>
<td></td>
</tr>
</table>
</form>
<br><br>";
	//QUERY DO PEDIDO
	$sql = $conectar->query("SELECT
	A.PEDIDO,
	A.FORN,
	A.RAZFORN,
	A.OBS1,
	A.OBS2,
	A.OBS3,
	A.OBS4,
	A.OBS5,
	A.FANT
	FROM
	FAT00001.dbo.Compra AS A
	WHERE A.PEDIDO = '$id'
	ORDER BY A.PEDIDO DESC
	");
	
	$row = $sql->fetch(PDO::FETCH_ASSOC);

	echo "R J C DEFESA E AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='index.php'><button>Voltar</button></a><br><br>";
	echo "Altere os dados do Pedido:<br><br>";

	echo "<form action='editpedido.php' method='post'>
	<table>
	<tr><td>Pedido:</td><td><input type='text' readonly='readonly' name='pedido' id='pedido' style='font-size: 10pt; height: 16px; width:100px;' value='$row[PEDIDO]'/></td></tr>
	<tr><td>Cód. Fornecedor:</td><td><input type='text' name='codfor' id='codfor' style='font-size: 10pt; height: 16px; width:100px;'value='$row[FORN]'/></td></tr>
	<tr><td>Razão Fornecedor:</td><td><input type='text' readonly='readonly' name='razforn' id='razforn' style='font-size: 10pt; height: 16px; width:600px;' value='$row[RAZFORN]'/></td></tr>
	<tr><td>Obs1:</td><td><input type='text' name='obs1' id='obs1' style='font-size: 10pt; height: 16px; width:600px;' value='$row[OBS1]'/></td></tr>
	<tr><td>Obs2:</td><td><input type='text' name='obs2' id='obs2' style='font-size: 10pt; height: 16px; width:600px;' value='$row[OBS2]'/></td></tr>
	<tr><td>Obs3:</td><td><input type='text' name='obs3' id='obs3' style='font-size: 10pt; height: 16px; width:600px;' value='$row[OBS3]'/></td></tr>
	<tr><td>Obs4:</td><td><input type='text' name='obs4' id='obs4' style='font-size: 10pt; height: 16px; width:600px;' value='$row[OBS4]'/></td></tr>
	<tr><td>Obs5:</td><td><input type='text' name='obs5' id='obs5' style='font-size: 10pt; height: 16px; width:600px;' value='$row[OBS5]'/></td></tr>
	<tr><td></td><td><input type='hidden' name='fant' id='fant' style='font-size: 10pt; height: 16px; width:600px;' value='$row[FANT]'/></td></tr>
	</table><br>
	<input type='submit' value='Alterar'><br>
	</form>
	";
?>

<br>
</body>
</html>
