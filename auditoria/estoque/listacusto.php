<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movimentação Produto</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b><br>Listagem da movimentação do produto - Entradas<br><br>";
	echo "<input type='button' value='Voltar' onClick='history.go(-1)'>&nbsp &nbsp &nbsp<br><br>";	

	include_once "../../conexao.php";

    $id=filter_var($_GET['id'], FILTER_SANITIZE_STRING);

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
    convert(varchar(10),A.DATA,103) AS DATA,
    A.PROD,
    A.DESCRICAO,
    A.QUANT,
    A.VALUNI,
    A.VALTOT,
    A.CLIFOR,
    A.RAZAO,
    A.OBSSAI,
    A.NOTA
    FROM FAT00001.dbo.movto AS A
    WHERE
    A.TIPO = 'E'
    AND A.PROD = '$id'
    ORDER BY A.DATA
    ");
	
	echo "<table id=tbordprod>
		<tr>
			<td>DATA</td>
            <td>COD PRODUTO</td>
			<td>DESCRICAO</td>
            <td>QUANTIDADE</td>
			<td>VALOR UNIT</td>
            <td>VALOR TOT</td>
            <td>COD FORN</td>
            <td>RAZAO SOCIAL</td>
            <td>OBS SAIDA</td>
            <td>NRO. NOTA</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$quant = number_format($linha['QUANT'],5,',', '.');
        $valuni = number_format($linha['VALUNI'],5,',', '.');
        $valtot = number_format($linha['VALTOT'],5,',', '.');
		echo "<tr>
				<td>$linha[DATA]</td>
				<td>$linha[PROD]</td>
				<td>$linha[DESCRICAO]</td>
                <td>$quant</td>
                <td>$valuni</td>
                <td>$valtot</td>
                <td>$linha[CLIFOR]</td>
                <td>$linha[RAZAO]</td>
                <td>$linha[OBSSAI]</td>
                <td>$linha[NOTA]</td>
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
