<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Requisições</title>
</head>

<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA <br> Cadastro de Requisições &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='listasaldo.php'><button>Relatórios</button></a>&nbsp &nbsp &nbsp";
	echo "<a href='confnota.php'><button>Conf N.F.</button></a>&nbsp &nbsp &nbsp";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../conexao.php";

$hoje = date('Y-m-d');
$emissao = date('d/m/Y');

echo "Emissão: ".$emissao;

if(isset($_POST['dt_ini'])){
	$id_dtini=filter_var($_POST['dt_ini']);
	}else {
		$id_dtini = $hoje;
	}

	if(isset($_POST['dt_fim'])){
		$id_dtfim=filter_var($_POST['dt_fim']);
		}else {
			$id_dtfim = $hoje;
		}

	if(isset($_POST['sequencia'])){
		$sequencia = filter_var($_POST['sequencia']);
		}else {
			$sequencia = "Remessa";
		}
echo "<form action='index.php' method='POST'>
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type='date' class='input-date' value='$id_dtini' name='dt_ini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' class='input-date' value='$id_dtfim' name='dt_fim'/></td>
<td>Selecione:</td>	<td>
<input type='radio' name='sequencia' value='Remessa' checked>Remessa
<input type='radio' name='sequencia' value='Entrega'>Entrega</td>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
<br><br>
";	

if ($sequencia === "Remessa"){
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	//$consulta = $conectar->query("SELECT * FROM FAT00001.dbo.produto ");
	$consulta = $conectar->query("SELECT
convert(varchar(10),A.EMISSAO,103) AS 'DATA',
A.DOCUM AS 'REQUISICAO',
A.OBSERV AS 'OBSERVACAO',
A.CCUSTO AS 'CCUSTO',
B.ITEM AS 'ITEM',
B.PRODUTO AS 'PRODUTO',
B.DESCR AS 'DESCRICAO',
B.QUANT AS 'QUANT',
B.VLRUNI AS 'VLRUNI',
B.VLRTOT AS 'VLRTOT'
FROM
FAT00001.dbo.requis1 AS A
LEFT JOIN FAT00001.dbo.requis2 AS B ON A.CONTROLE = B.CONTROLE
WHERE 
A.EMISSAO >= '$id_dtini' AND A.EMISSAO <= '$id_dtfim'
ORDER BY A.EMISSAO, A.DOCUM, B.ITEM
");
	
	echo "<table id=tbordprod>
		<tr>
			<td>DATA</td>
			<td>REQUISIÇÃO</td>
			<td>OBSERVAÇÃO</td>
			<td>CENTRO CUSTO</td>
			<td>ITEM</td>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>QUANT</td>
			<td>VLR UNIT</td>
			<td>VLR TOTAL</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$quant = number_format($linha['QUANT'],2,',', '.');
		$vlruni = number_format($linha['VLRUNI'],2,',', '.');
		$vlrtot = number_format($linha['VLRTOT'],2,',', '.');
		echo "<tr>
				<td>$linha[DATA]</td>
				<td>$linha[REQUISICAO]</td>
				<td>$linha[OBSERVACAO]</td>
				<td>$linha[CCUSTO]</td>
				<td>$linha[ITEM]</td>
				<td>$linha[PRODUTO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$quant</td>
				<td>$vlruni</td>
				<td>$vlrtot</td>
			</tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
//<td><a href='formeditproduto.php?id=$linha[id]'>Editar</a> - <a href='excluirproduto.php?id=$linha[id]'>Excluir</a></td>

}
else {
	try{
		//EXECUÇÃO DA INSTRUCAO SQL
		//$consulta = $conectar->query("SELECT * FROM FAT00001.dbo.produto ");
		$consulta = $conectar->query("SELECT
	convert(varchar(10),A.EMISSAO,103) AS 'DATA',
	A.DOCUM AS 'REQUISICAO',
	A.OBSERV AS 'OBSERVACAO',
	A.CCUSTO AS 'CCUSTO',
	B.ITEM AS 'ITEM',
	B.PRODUTO AS 'PRODUTO',
	B.DESCR AS 'DESCRICAO',
	B.QUANT AS 'QUANT',
	B.VLRUNI AS 'VLRUNI',
	B.VLRTOT AS 'VLRTOT'
	FROM
	FAT00001.dbo.Devol1 AS A
	LEFT JOIN FAT00001.dbo.Devol2 AS B ON A.CONTROLE = B.CONTROLE
	WHERE 
	A.EMISSAO >= '$id_dtini' AND A.EMISSAO <= '$id_dtfim'
	ORDER BY A.EMISSAO, A.DOCUM, B.ITEM
	");
		
		echo "<table id=tbordprod>
			<tr>
				<td>DATA</td>
				<td>REQUISIÇÃO</td>
				<td>OBSERVAÇÃO</td>
				<td>CENTRO CUSTO</td>
				<td>ITEM</td>
				<td>CÓDIGO</td>
				<td>DESCRIÇÃO</td>
				<td>QUANT</td>
				<td>VLR UNIT</td>
				<td>VLR TOTAL</td>
			</tr>";
		while
		($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
			$quant = number_format($linha['QUANT'],2,',', '.');
			$vlruni = number_format($linha['VLRUNI'],2,',', '.');
			$vlrtot = number_format($linha['VLRTOT'],2,',', '.');
			echo "<tr>
					<td>$linha[DATA]</td>
					<td>$linha[REQUISICAO]</td>
					<td>$linha[OBSERVACAO]</td>
					<td>$linha[CCUSTO]</td>
					<td>$linha[ITEM]</td>
					<td>$linha[PRODUTO]</td>
					<td>$linha[DESCRICAO]</td>
					<td>$quant</td>
					<td>$vlruni</td>
					<td>$vlrtot</td>
				</tr>";
		}
		echo "</table>";
		
		echo $consulta->rowCount() . " Registros Exibidos";
	}catch(PDOExceprion $e){
		echo $e->getMessasge();
	}
	//<td><a href='formeditproduto.php?id=$linha[id]'>Editar</a> - <a href='excluirproduto.php?id=$linha[id]'>Excluir</a></td>
	
	}

?>

</body>

</html>