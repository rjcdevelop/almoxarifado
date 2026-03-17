<!DOCTYPE html>
<html>

<head>
<title>Almoxarifado</title>
</head>

<body>

<?php
	
	include_once "../../conexao.php";
	
	if(isset($_POST['pesquisa'])){
	$id=filter_var($_POST['pesquisa']);
	}else {
		$id='';
	}

	echo "SISTEMA DO ALMOXARIFADO - R J C DEFESA AEROESPACIAL LTDA
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='cadof/'><button>Cadastrar OF/Lote</button></a> &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='vincularlote.php'><button>Registrar OF/Lote</button></a> &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='relatorios/index.php'><button>Acessar Relatórios</button></a> &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='../index.php'><button>Voltar</button></a>
	<br><br>
	Localize o item desejado:<br>";

echo "
<form action='index.php' method='POST'>
<table>
<tr>
<td><label>Pesquisar: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' name='pesquisa' value='$id' placeholder='Digite código ou descrição do item'/></td>
<td><input type='submit' value='Buscar'></td>
<td></td>
</tr>
</table>
</form>
";

	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT TOP 200
	A.CODIGO AS CODIGO,
	A.DESCRICAO AS DESCRICAO,
	A.UNID AS UNIDADE,
	((CASE WHEN C.S1TOTAL IS NULL THEN 0 ELSE C.S1TOTAL END) + (CASE WHEN D.S2TOTAL IS NULL THEN 0 ELSE D.S2TOTAL END)) AS SALDO
	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN (SELECT S1.PROD AS S1ITEM, SUM(S1.QUANT) AS S1TOTAL FROM FAT00001.dbo.movto AS S1 WHERE S1.TIPO='E' GROUP BY S1.PROD) AS C ON A.CODIGO=C.S1ITEM
	LEFT JOIN (SELECT S2.PROD AS S2ITEM, SUM(S2.QUANT) AS S2TOTAL FROM FAT00001.dbo.movto AS S2 WHERE S2.TIPO='S' GROUP BY S2.PROD) AS D ON A.CODIGO=D.S2ITEM
	WHERE CODIGO LIKE '%$id%' OR DESCRICAO LIKE '%$id%'
	ORDER BY SALDO DESC,A.CODIGO ASC
	");

echo "<br><br>RELAÇÃO DE ITENS PESQUISADOS<br>";

try{
	
	echo "<table id=tbordzebr>
		<tr>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>UNID</td>
			<td>SALDO</td>
			<td>CARDEX</td>
		</tr>";

	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)){

		$saldo = number_format($linha['SALDO'],2,',', '.');

		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNIDADE]</td>
				<td align='right'>$saldo</td>
				<td><a href='cardex.php?id=$linha[CODIGO]'>Acessar</a></td>
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