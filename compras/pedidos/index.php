<!DOCTYPE html>
<html>

<head>
<title>Compras</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	
	include_once "../../conexao.php";
	
	if(isset($_POST['pesquisa'])){
	$id=filter_var($_POST['pesquisa']);
	}else {
		$id='';
	}

	echo "Pedido de Compras - R J C DEFESA AEROESPACIAL LTDA
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='../index.php'><button>Voltar</button></a>
	<br><br>
	Localize o Pedido desejado:<br>";
?>

<form action="index.php" method="POST">
<table>
<tr>
<td><label>Pesquisar: </label></td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:300px;" name="pesquisa" placeholder="Digite nº Pedido ou nome Fornecedor"/></td>
<td><input type="submit" value="Buscar"></td>
<td></td>
</tr>
</table>
</form>

<?php
	//QUERY PESQUISA DO PEDIDO
	$sql = $conectar->query("SELECT TOP 2000
	CONVERT(varchar(10),A.DATA,103) AS DATA,
	A.PEDIDO,
	A.FORN,
	A.RAZFORN,
	A.TOTPED
	FROM
	FAT00001.dbo.Compra AS A
	WHERE PEDIDO LIKE '%$id%' OR A.RAZFORN LIKE '%$id%'
	ORDER BY A.PEDIDO DESC
	");


echo "<br><br>";

try{
	
	echo "<table id=tbordzebr>
		<tr>
			<td>DATA</td>
			<td>PEDIDO</td>
			<td>COD FORN</td>
			<td>RAZÃO SOCIAL</td>
			<td>VALOR PEDIDO</td>
			<td>NF RECEBIDAS</td>
		</tr>";

	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)){

		$vlrpedido = number_format($linha['TOTPED'],2,',', '.');

		echo "<tr>
				<td>$linha[DATA]</td>
				<td><a href='pedidovis.php?id=$linha[PEDIDO]&vlr=$linha[TOTPED]'>$linha[PEDIDO]</a></td>
				<td>$linha[FORN]</td>
				<td>$linha[RAZFORN]</td>
				<td align='right'>$vlrpedido</td>
				<td><a href='notaxpedido.php?id=$linha[PEDIDO]'>Notas Fiscais</a></td>
			</tr>";
	}
	echo "</table>";
	
	echo $sql->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>

</html>
