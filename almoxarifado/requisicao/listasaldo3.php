<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Saldo Mês Atual</title>
</head>

<body>

<?php
	echo "Olá!!<br> Listagem do saldo produtos para consumo e patrimônio<br><br>";
	echo "<a href='index.php'><button>Voltar</button></a>&nbsp &nbsp &nbsp";
	echo "<a href='listasaldo.php'><button>Mês Atual</button></a>&nbsp &nbsp";
	echo "<a href='listasaldo1.php'><button>Mês -1</button></a>&nbsp &nbsp";
	echo "<a href='listasaldo2.php'><button>Mês -2</button></a>&nbsp &nbsp";
	echo "<a href='listasaldo3.php'><button>Mês -3</button></a><br><br>";	

	include_once "../../conexao.php";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	//$consulta = $conectar->query("SELECT * FROM FAT00001.dbo.produto ");
	$consulta = $conectar->query("SELECT
	A.PROD AS PRODUTO,
	B.DESCRICAO AS DESCRICAO,
	SUM(A.QUANT) AS SOMA
	FROM
	FAT00001.dbo.movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.PROD=B.CODIGO
	WHERE
	A.DATA >= DATEADD(MM, DATEDIFF(mm,0,GETDATE())-3, 0)
	AND A.DATA < DATEADD(MM, DATEDIFF(mm,0,GETDATE())-2, 0)
	AND B.TIPINV IN ('0003','0006','0007','0008','0009','0010','')
	GROUP BY A.PROD,B.DESCRICAO
	ORDER BY SOMA, A.PROD");
	
	echo "<table id=tbordprod>
		<tr>
			<td>COD PRODUTO</td>
			<td>DESCRICAO</td>
			<td>QUANTIDADE</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>
				<td><a href='movimento.php?id=$linha[PRODUTO]'>$linha[PRODUTO]</a></a></td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[SOMA]</td>				
			</tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
//<td><a href='formeditproduto.php?id=$linha[id]'>Editar</a> - <a href='excluirproduto.php?id=$linha[id]'>Excluir</a></td>


?>

</body>

</html>
