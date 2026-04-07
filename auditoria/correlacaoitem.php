<!DOCTYPE html>
<html>

<head>
<title>Correlação Item por Fornecedor</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	
	include_once "../conexao.php";
	
	if(isset($_POST['pesquisa'])){
	$id=filter_var($_POST['pesquisa']);
	$id_descr=filter_var($_POST['descr']);
	
	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";

echo "<form action='correlacaoitem.php' method='POST'>
<table>
<tr>
<td><label>Digite Código Fornecedor:</label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' name='pesquisa' value='$id'/></td>&nbsp &nbsp
<td><label>Digite a descrição do item: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:500px;' name='descr' value='$id_descr' placeholder='Digite código ou descrição do item'/></td>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>";


	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT
	A.CODIGO AS CODIGO,
	A.CODFOR AS FORNECEDOR,
	A.DESFOR AS RAZAO,
	A.CODPRODINTERNO AS CODPRODFOR,
	B.DESCRICAO AS DESCRICAO
	FROM
	FAT00001.dbo.MatFor AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.CODIGO = B.CODIGO
	WHERE
	A.CODFOR LIKE '%$id%'
	AND B.DESCRICAO LIKE '%$id_descr%'
");

echo "<br><br>RELAÇÃO DE ITENS DA NOTA FISCAL<br>";

try{
	
	echo "<table id=tbordprod>
		<tr>
			<td>CODIGO</td>
			<td>COD FORN</td>
			<td>RAZAO SOCIAL</td>
			<td>PROD FOR</td>
			<td>ALTERAR</td>
			<td>DESCRIÇÃO</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		//$qtdeentr = number_format($linha['QTDE_ENTR'],2,',', '.');
		//$qtdemovto = number_format($linha['QTDE_MOVTO'],2,',', '.');
		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[FORNECEDOR]</td>
				<td>$linha[RAZAO]</td>
				<td>$linha[CODPRODFOR]</td>
				<td><a href='formedit.php?cod_item=$linha[CODIGO]&cod_forn=$linha[FORNECEDOR]&prod_for=$linha[CODPRODFOR]'/a>Alterar</td>
				<td>$linha[DESCRICAO]</td>
				
			</tr>";
	}
	echo "</table>";
	
	echo $sql->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
	}elseif (isset($_GET['pesquisa'])){
		$id=filter_var($_GET['pesquisa']);
		$id_descr=filter_var($_GET['descr']);
		
	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";

echo "<form action='correlacaoitem.php' method='POST'>
<table>
<tr>
<td><label>Digite Código Fornecedor:</label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' name='pesquisa' value='$id'/></td>&nbsp &nbsp
<td><label>Digite a descrição do item: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:500px;' name='descr' value='$id_descr' placeholder='Digite código ou descrição do item'/></td>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>";


	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT
	A.CODIGO AS CODIGO,
	A.CODFOR AS FORNECEDOR,
	A.DESFOR AS RAZAO,
	A.CODPRODINTERNO AS CODPRODFOR,
	B.DESCRICAO AS DESCRICAO
	FROM
	FAT00001.dbo.MatFor AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.CODIGO = B.CODIGO
	WHERE
	A.CODFOR LIKE '%$id%'
	AND B.CODIGO LIKE '%$id_descr%'
");

echo "<br><br>RELAÇÃO DE ITENS DA NOTA FISCAL<br>";

try{
	
	echo "<table id=tbordprod>
		<tr>
			<td>CODIGO</td>
			<td>COD FORN</td>
			<td>RAZAO SOCIAL</td>
			<td>PROD FOR</td>
			<td>ALTERAR</td>
			<td>DESCRIÇÃO</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		//$qtdeentr = number_format($linha['QTDE_ENTR'],2,',', '.');
		//$qtdemovto = number_format($linha['QTDE_MOVTO'],2,',', '.');
		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[FORNECEDOR]</td>
				<td>$linha[RAZAO]</td>
				<td>$linha[CODPRODFOR]</td>
				<td><a href='formedit.php?cod_item=$linha[CODIGO]&cod_forn=$linha[FORNECEDOR]&prod_for=$linha[CODPRODFOR]'/a>Alterar</td>
				<td>$linha[DESCRICAO]</td>
				
			</tr>";
	}
	echo "</table>";
	
	echo $sql->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
	}else {
	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='index.php'><button>Voltar</button></a><br><br>";

echo "<form action='correlacaoitem.php' method='POST'>
<table>
<tr>
<td><label>Digite Código Fornecedor:</label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' name='pesquisa' placeholder='Informe Código Fornecedor'</td>&nbsp &nbsp
<td><label>Digite a descrição do item: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:500px;' name='descr' placeholder='Digite código ou descrição do item'/></td>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>";
}

?>
</body>

</html>
