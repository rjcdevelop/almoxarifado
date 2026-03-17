<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Saldo Mês Atual</title>
</head>

<body>

<?php
	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b><br>Listagem do saldo produtos para consumo e patrimônio<br><br>";
	echo "<a href='index.php'><button>Voltar</button></a>&nbsp &nbsp &nbsp<br><br>";	

	include_once "../../conexao.php";


if(isset($_POST['dt_ini'])){
	$id_dtini=filter_var($_POST['dt_ini']);
	}else {
		$id_dtini='';
	}

	if(isset($_POST['dt_fim'])){
		$id_dtfim=filter_var($_POST['dt_fim']);
		}else {
			$id_dtfim='';
		}

echo "
<form action='listasaldo.php' method='POST'>
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim'/></td>
<td><input type='submit' value='Buscar'></td>
<td></td>
</tr>
</table>
</form>";

echo "Período de lançamentos: " .date('d/m/Y', strtotime($id_dtini))." a ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";


try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.PROD AS PRODUTO,
	B.DESCRICAO AS DESCRICAO,
	SUM(A.QUANT) AS SOMA
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD=B.CODIGO
	WHERE
	A.DATA >= '$id_dtini'
	AND A.DATA <= '$id_dtfim'
	AND B.TIPINV IN ('0006','0007','0008','')
	GROUP BY A.PROD,B.DESCRICAO
	ORDER BY SOMA, A.PROD");
	
	echo "<table id=tbordprod>
		<tr>
			<td>COD PRODUTO</td>
			<td>DESCRICAO</td>
			<td>QUANTIDADE</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$somaqtde = number_format($linha['SOMA'],2,',', '.');
		echo "<tr>
				<td><a href='movimento.php?id=$linha[PRODUTO]&&dtini=$id_dtini&&dtfim=$id_dtfim'>$linha[PRODUTO]</a></a></td>
				<td>$linha[DESCRICAO]</td>
				<td align='right'>$somaqtde</td>				
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