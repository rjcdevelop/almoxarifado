<!DOCTYPE html>
<html>

<head>
<title>Correlação Item por Fornecedor</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	
	include_once "../conexao.php";
	
	$cod_item=filter_var($_GET['cod_item']);
	$cod_forn=filter_var($_GET['cod_forn']);
	$prod_for=filter_var($_GET['prod_for']);

	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";

echo "<form action='editcorrelacaoitem.php' method='POST'>
<table>
<tr>
<td><label>Código Fornecedor:</label></td>
<td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:80px;' name='cod_forn' value='$cod_forn'/></td>&nbsp &nbsp
<td><label>Código Produto: </label></td>
<td><input type='text' readonly='readonly' style='font-size: 10pt; height: 16px; width:200px;' name='cod_item' value='$cod_item' /></td>
<td><label>Código fornecedor: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:200px;' name='new_item' placeholder='Digite o novo código'/></td>
<td><input type='hidden' name='prod_for' value='$prod_for'/></td>
<td><input type='submit' value='Confirmar'></td>
</tr>
</table>
</form>";

?>
</body>

</html>
