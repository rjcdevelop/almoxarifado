<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Requisição Amoxarifado</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	//echo "<a href="index.php"><button>Voltar</button></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp<br><a href="index.php"><button>Voltar</button></a>";
	
	$hoje = date('d/m/Y');
	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b><br><br>";
	echo "<center><b>R E Q U I S I Ç Ã O</b></center><br><br>";
	echo '<b>Data emissão: </b>' . $hoje;
	echo "<br><br>";

	include_once "../../conexao.php";

//$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
//echo $id;

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

	$cabecalho = $conectar->query("SELECT
A.DOCUM AS 'DOCUM',
convert(varchar(10),A.EMISSAO,103) AS 'EMISSAO',
A.CCUSTO AS 'CCUSTO',
A.OBSERV AS 'OBSERVACAO',
B.CCUSTO AS 'DESCCUSTO'
FROM FAT00001.dbo.requis1 AS A
LEFT JOIN FIN00001.dbo.cadcust AS B ON A.CCUSTO=B.CODCUS
WHERE
A.DOCUM = '{$id}'");

	while
	($exibe = $cabecalho->fetch(PDO::FETCH_ASSOC)) {
		echo "<table border='0'>
		<tr>
			<td><b>Requisição Nro: </b></td>
			<td>$exibe[DOCUM]</td>
			<td><b>Data de Movimento: </b></td>
			<td>$exibe[EMISSAO]</td>
		</tr>
		<tr>
			<td><b>Centro de Custo: </b></td>
			<td>$exibe[CCUSTO]</td>
			<td><b>Descrição C. Custo: </b></td>
			<td>$exibe[DESCCUSTO]</td>
		</tr><br></table>";
		echo "<br>";
		echo "<table border='0'>
		<tr>
			<td><b>Observação: </b></b></td><td>$exibe[OBSERVACAO]</td>
		</tr></table>";
			
}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}



try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

	$consulta = $conectar->query("SELECT
B.ITEM AS 'ITEM',
B.PRODUTO AS 'PRODUTO',
B.DESCR AS 'DESCRICAO',
B.UNID AS 'UNID',
--B.QUANT AS 'QUANT'
REPLACE(CONVERT(varchar,CAST(B.QUANT AS NUMERIC(18,5)), 1),'.',',') AS 'QUANT'

FROM FAT00001.dbo.requis1 AS A, FAT00001.dbo.requis2 AS B

WHERE
A.DOCUM=B.DOCUM
AND A.DOCUM = '{$id}'
ORDER BY B.ITEM");
//$consulta->bindParam(':id',$id);
//$consulta->execute();
	echo "<br>";
	echo "<table id=tbordprod>
		<tr>
			<td width=5%>ITEM</td>
			<td width=20%>COD PRODUTO</td>
			<td width=50%>DESCRIÇÃO</td>
			<td width=5%>UNID</td>
			<td align=right width=20%>QUANTIDADE</td>
			
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		
		echo "<tr>
				<td>$linha[ITEM]</td>
				<td>$linha[PRODUTO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNID]</td>
				<td align=right>$linha[QUANT]</td>
				

			</tr>";
	}
	echo "</table>";
	
	echo "Total de itens: " . $consulta->rowCount();

}catch(PDOExceprion $e){
	echo $e->getMessasge();
}


echo "<br><br><br>";

echo "<table border='0'>
		<tr>
			<td>____________________________________&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
			<td>____________________________________</td>
		</tr>
		<tr>
			<td>Responsável pelo Almoxarifado</td>
			<td>Requerente (Nome / Identidade)</td>
		</tr>
		<tr>
			<td></td>
			<td>Data retirada: _____/_____/__________</td>
		</tr>
		</table>";
		
//<td><a href='formeditproduto.php?id=$linha[id]'>Editar</a> - <a href='excluirproduto.php?id=$linha[id]'>Excluir</a></td>

?>

</body>

</html>
