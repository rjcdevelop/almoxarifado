<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movimento</title>
</head>

<body>

<?php
	//echo "<a href="index.php"><button>Voltar</button></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp<br><a href="index.php"><button>Voltar</button></a>";
	
	$hoje = date('d/m/Y');
	$hoje1 = date('Y-m-d');
	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b> &nbsp &nbsp &nbsp &nbsp &nbsp <input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";
	echo "<center><b>Movimentação do estoque</b></center><br><br>";
	echo '<b>Data emissão: </b>' . $hoje;
	echo "<br><br>";

	include_once "../../conexao.php";

//$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
//echo $id;
	
	$id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
	if (isset($_GET['dtini'])){
		$dtini = filter_var($_GET['dtini'], FILTER_SANITIZE_STRING);
		}else {
			$dtini = '2010-01-01';
	}
	if (isset($_GET['dtfim'])){
		$dtfim = filter_var($_GET['dtfim'], FILTER_SANITIZE_STRING);
		}else {
			$dtfim = $hoje1;
	}

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	

	echo "Período de lançamentos: " .date('d/m/Y', strtotime($dtini))." a ".date('d/m/Y', strtotime($dtfim))."<br><br>";


	$consulta = $conectar->query("SELECT
	convert(varchar(10),A.DATA,103) AS 'EMISSAO',
	A.NOTA AS NOTA,
	A.RAZAO AS RAZAO,
	A.OBSSAI AS OBSSAI,
	A.PROD AS CODIGO,
	A.QUANT AS QTDE,
	A.VALUNI AS VLRUNIT,
	A.VALTOT AS VLRTOTAL,
	A.DESCRICAO AS DESCRICAO,
	B.UNID AS UNID
	FROM FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD = B.CODIGO
	WHERE
	A.DATA >= '$dtini' AND A.DATA <= '$dtfim'
	AND A.PROD='{$id}'
	ORDER BY A.DATA
	");

	
$consulta2 = $conectar->query("SELECT
SUM(A.QUANT) AS SALDO
FROM FAT00001.dbo.movto AS A
WHERE
A.DATA >= '$dtini' AND A.DATA <= '$dtfim'
AND A.PROD='{$id}'
GROUP BY A.PROD
");

echo "<table id=tbordprod>
<tr>
	<td>DATA</td>
	<td>NOTA</td>
	<td>RAZÃO SOCIAL</td>
	<td>OBSERVAÇÃO</td>
	<td>CODIGO</td>
	<td>DESCRICAO</td>
	<td>UNID</td>
	<td>QUANTIDADE</td>
	<td>VLR UNIT</td>
	<td>VLR TOTAL</td>
</tr>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$qtde = number_format($linha['QTDE'],5,',', '.');
		$vlr_unit = number_format($linha['VLRUNIT'],2,',', '.');
		$vlr_total = number_format($linha['VLRTOTAL'],2,',', '.');

echo "<tr>
		<td>$linha[EMISSAO]</td>
		<td>$linha[NOTA]</td>
		<td>$linha[RAZAO]</td>
		<td>$linha[OBSSAI]</td>
		<td>$linha[CODIGO]</td>
		<td>$linha[DESCRICAO]</td>
		<td>$linha[UNID]</td>
		<td align=right>$qtde</td>
		<td align=right>$vlr_unit</td>
		<td align=right>$vlr_total</td>
		
	</tr>";
}
echo "</table>";


echo $consulta->rowCount() . " Registros Exibidos";

while
($linha1 = $consulta2->fetch(PDO::FETCH_ASSOC)) {
	$saldo = number_format($linha1['SALDO'],5,',', '.');}

echo "<br><br>Saldo em Estoque no final do período: $saldo";

}catch(PDOExceprion $e){
echo $e->getMessasge();
}

?>
</body>
</html>
