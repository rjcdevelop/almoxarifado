<!DOCTYPE html>
<html>

<head>
<title>Cadastrar O.F.</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "CADASTRAR ORDEM DE FABRICAÇÃO - R J C DEFESA E AEROESPACIAL LTDA<br>
	** Verifique se já existe o lote cadastrado, só é possível alterar se não houver nenhum lote vinculado **
	<br><br><br>";
	//echo "<a href='index.php'><button>Voltar</button></a><br><br>";
	echo "<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";
	
include_once "../../../conexao.php";

	//TRAZ O CODIGO DO CLIENTE
	$id=filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

	//QUERY PARA FORMULARIO
	$sql = $conectar->query("SELECT
	A.CL_CODIGO AS 'CODIGO',
	A.CL_RAZ_SOC AS 'RAZAO'
	FROM
	FIN00001.dbo.cadcli AS A
	WHERE
	A.CL_CODIGO = '$id'
	ORDER BY
	A.CL_CODIGO
");
	
$row = $sql->fetch(PDO::FETCH_ASSOC);
	
?>
<form action="cadastrarof.php" method="POST">
<table>
<tr>
<td>Número O.F.:</td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:90px;" name="codigo_of" id="codigo_of"/></td>
</tr>
<tr>
<td>Prazo:</td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="prazo_of" id="prazo_of"/></td>
</tr>
<tr>
<td>Código Cliente:</td>
<td><input type="text" readonly="readonly" style="font-size: 10pt; height: 16px; width:90px;" name="cliente" value="<?php echo $row['CODIGO'] ?>" id="cliente"/></td>
</tr>
<tr>
<td>Razão Social:</td>
<td><input type="text" readonly="readonly" style="font-size: 10pt; height: 16px; width:400px;" name="razao" value="<?php echo $row['RAZAO'] ?>" id="razao"/></td>
</tr>
<tr>
<td>Observações:</td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:600px;" name="observ1" id="observ1"/></td>
</tr>

<tr>
<td>Quantidade da O.F.:</td>
<td><input type="number" style="font-size: 10pt; height: 16px; width:180px;" name="qtde_of" value="$row[QTDEOF]" id="qtde_of" min="0" step=".01"/></td>
</tr>

</table>
<br>
<input type="submit" value="Cadastrar"><br>
</form>

</body>
</html>
