<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cadastro Produtos - eFiscal</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	$hoje = date('d/m/Y');
	echo "RELATÓRIO DE ANÁLISE DO CADASTRO DEPRODUTOS - TIPO DE PRODUTO E INVENTÁRIO<br>Emissão: ".$hoje." Mês: Atual<br><br>";
	echo "<a href='index.php'><button>Voltar</button></a>&nbsp &nbsp &nbsp";
	//echo "<a href='nfxmovto.php'><button>Mês Atual</button></a>&nbsp &nbsp";
	//echo "<a href='nfxmovto1.php'><button>Mês -1</button></a>&nbsp &nbsp";
	//echo "<a href='nfxmovto2.php'><button>Mês -2</button></a>&nbsp &nbsp";
	//echo "<a href='nfxmovto3.php'><button>Mês -3</button></a><br><br>";	
	echo "<br><br>";
	
	include_once "../conexao.php";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conn->query("SELECT
	CASE WHEN A.codigoefd='' THEN A.codigo ELSE A.codigoefd END AS CODIGO,
	A.descricao,
	A.tipo,
	A.tipoinv
	FROM
	e1100.pro_ser AS A
	--WHERE
	--A.tipoinv=''
	ORDER BY CODIGO");
	
	echo "<table id=tbordprod>
		<tr>
			<td>COD PRODUTO</td>
			<td>DESCRICAO</td>
			<td>TIPO PROD</td>
			<td>TIPO INV</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>
		<td>$linha[codigo]</td>
		<td>$linha[descricao]</td>
		<td class=tdright>$linha[tipo]</td>
		<td class=tdright>$linha[tipoinv]</td>
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
