<!DOCTYPE html>
<html>

<head>
<title>Relação de Clientes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "LISTA DE CLIENTES PARA CADASTRAR O.F.<br><br>";
	echo "<a href='../index.php'><button>Voltar</button></a><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../conexao.php";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.CL_CODIGO AS 'CODIGO',
	A.CL_RAZ_SOC AS 'RAZAO',
	A.CL_CID AS 'CIDADE',
	A.CL_UF AS 'UF'
	FROM
	FIN00001.dbo.cadcli AS A
	ORDER BY
	A.CL_CODIGO
	");
	
	echo "<table id=tbordzebr>
		<tr>
			<td>Código</td>
			<td>Razão Social</td>
			<td>Cidade</td>
			<td>UF</td>
			<td>Cadastrar O.F.</td>
			<td>Listar O.F.</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[RAZAO]</td>
				<td>$linha[CIDADE]</td>
				<td>$linha[UF]</td>
				<td><a href='formcadastrarof.php?id=$linha[CODIGO]'>Cadastrar</td>
				<td><a href='listarof.php?id=$linha[CODIGO]'>Listar</td>
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
