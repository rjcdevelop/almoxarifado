<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Custo Médio - movimentos</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
		
	$hoje = date('d/m/Y');
	echo "<b><center>R J C DEFESA E AEROESPACIAL LTDA</b><br>
	<b>Custo Médio - Base em Movimentação Folhamatic</b><br>
	<b>Data emissão: </b>" . $hoje . "</center><br><br>";

	include_once "../../conexao.php";

if(isset($_POST['dtini'])){
	$dtini=filter_var($_POST['dtini']);
	}else {
		$dtini = date('Y/m/d');
	}

	if(isset($_POST['dtfim'])){
		$dtfim=filter_var($_POST['dtfim']);
		}else {
			$dtfim = date('Y/m/d');
		}

echo "<form action='custo.php' method='POST'>
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' value='$dtini' name='dtini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' value='$dtfim' name='dtfim'/></td>
<td><input type='submit' value='Buscar'></td>
<td></td>
</tr>
</table>
</form>
<br><br>";
echo "Período de lançamentos: " .date('d/m/Y', strtotime($dtini))." a ".date('d/m/Y', strtotime($dtfim))."<br><br>";

try{
	$consulta = $conectar->query("SELECT
	A.PROD AS CODIGO,
	B.DESCRICAO AS DESCRICAO,
	SUM(A.QUANT) AS SALDO,
	MD.TOTVALOR AS TOTMEDIA,
	MD.QTDE AS QTMEDIA
	FROM FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD = B.CODIGO
	LEFT JOIN (SELECT PROD, SUM(VALUNI * QUANT) AS TOTVALOR, SUM(QUANT) AS QTDE FROM FAT00001.dbo.movto WHERE TIPO='E' GROUP BY PROD) AS MD ON A.PROD = MD.PROD
	WHERE
	A.DATA >= '$dtini' AND A.DATA <= '$dtfim'
	GROUP BY A.PROD,B.DESCRICAO,MD.TOTVALOR,MD.QTDE
	ORDER BY A.PROD
	");

echo "<table id=tbordprod>
<tr>
	<td>CODIGO</td>
	<td>DESCRICAO</td>
	<td>SALDO INVENT</td>
	<td>CUSTO MEDIA</td>
</tr>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$saldo = number_format($linha['SALDO'],3,',', '.');
		$totmedia = $linha['TOTMEDIA'];
		$qtmedia = $linha['QTMEDIA'];
		if ($totmedia == 0) {
			$vlr_media = 'Rever';
		}else {
			$vlr_media = $totmedia / $qtmedia;
			$media = number_format($vlr_media,3,',', '.');
		}
		
		

echo "<tr>
		<td><a href='listacusto.php?id=$linha[CODIGO]'>$linha[CODIGO]</a></td>
		<td>$linha[DESCRICAO]</td>
		<td align=right>$saldo</td>
		<td align=right>$media</td>
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
