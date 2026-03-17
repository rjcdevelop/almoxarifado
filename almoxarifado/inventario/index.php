<!DOCTYPE html>
<html>

<head>
<title>Inventário</title>
</head>

<body>

<?php
	
	include_once "../../conexao.php";
	
	if(isset($_POST['pesquisa'])){
	$id=filter_var($_POST['pesquisa']);
	}else {
		$id='';
	}

	echo "INVENTÁRIO SISTEMA FOLHAMATIC - R J C DEFESA AEROESPACIAL LTDA
	&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='../index.php'><button>Voltar</button></a>
	<br><br>
	Localize o item desejado:<br>";
?>

<form action="index.php" method="POST">
<table>
<tr>
<td><label>Pesquisar: </label></td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:300px;" name="pesquisa" placeholder="Digite código ou descrição do item"/></td>
<td><input type="submit" value="Buscar"></td>
<td></td>
</tr>
</table>
</form>

<?php
	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT TOP 200
	A.CODIGO AS PROD,
	A.GRUPO AS GRUPO,
	A.UNID AS UNID,
	C.DESCRICAO AS SUBGRUPO,
	A.DESCRICAO AS DESCRICAO,
	A.TIPINV AS TIPOINV,
	A.QUANT AS 'SALDOCONT'
	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN(SELECT OP3.CODIGO_MP,SUM(OP3.QTDETEMP2) AS SOMAOP FROM FAT00001.dbo.CadOp1 AS OP1,FAT00001.dbo.CadOp3 AS OP3
	WHERE OP1.CODIGO=OP3.CODIGO_OP AND OP1.STATUS<>'F' GROUP BY OP3.CODIGO_MP) AS B ON A.CODIGO=B.CODIGO_MP
	LEFT JOIN(SELECT SB.CODIGO,SB.DESCRICAO FROM FAT00001.dbo.SubGrupo AS SB) AS C ON A.SUBGRUPO=C.CODIGO
	WHERE A.GRUPO IN ('01','') AND A.ATIVO='Sim' 
	AND A.CODIGO LIKE '%$id%' OR A.DESCRICAO LIKE '%$id%'
	GROUP BY A.CODIGO,A.GRUPO,A.UNID,C.DESCRICAO,A.DESCRICAO,A.QUANT,B.SOMAOP,A.TIPINV
	ORDER BY A.GRUPO,C.DESCRICAO,A.CODIGO
	");


echo "<br><br>RELAÇÃO DE ITENS PESQUISADOS<br>";

try{
	
	echo "<table id=tbordzebr>
		<tr>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>GRUPO</td>
			<td>TIPO INVENT</td>
			<td>SALDO ESTOQUE</td>
		</tr>";

	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)){

		$saldo = number_format($linha['SALDOCONT'],2,',', '.');

		echo "<tr>
				<td>$linha[PROD]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNID]</td>
				<td>$linha[TIPOINV]</td>
				<td align='right'>$saldo</td>
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