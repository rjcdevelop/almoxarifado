<!DOCTYPE html>
<html>
<head>
<title>Relação de Lotes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	echo 'RELAÇÃO DE O.F. POR CLIENTE<br><br>';
	//echo "<a href='index.php'><button>Voltar</button></a><br><br>";
	//echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

	include_once "../../conexao.php";
	
if(isset($_POST['of_ini'])){
	$id_ofini=filter_var($_POST['of_ini']);
	}else {
		$id_ofini='';
	}

	if(isset($_POST['of_fim'])){
		$id_offim=filter_var($_POST['of_fim']);
		}else {
			$id_offim='';
		}

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.cod_of AS CODIGOOF,
	A.codcli AS CODIGOCLI,
	A.observ1 AS OBSERVACAO1,
	CONVERT(varchar(10),A.prazo_of,103) AS PRAZOOF,
	B.CL_RAZ_SOC AS RAZAO,
	C.QTDE_LOTE AS QT_LOTE
	FROM
	pcp_producao.dbo.cad_of AS A
	LEFT JOIN FIN00001.dbo.cadcli AS B ON A.codcli=B.CL_CODIGO
	LEFT JOIN (SELECT LT.cod_of AS CODOF,COUNT(LT.cod_lote) AS QTDE_LOTE FROM pcp_producao.dbo.cad_lote AS LT GROUP BY LT.cod_of,LT.cod_of) AS C ON A.cod_of=C.CODOF
	WHERE
	A.cod_of >= '$id_ofini' AND A.cod_of <='$id_offim'
	ORDER BY
	A.cod_of
	");
	
	echo "<table id=tbordzebr>
		<tr>
			<td>Código</td>
			<td>Razão Social</td>
			<td>No O.F.</td>
			<td>Prazo O.F.</td>
			<td>Observações</td>
			<td>Lotes</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>
				<td>$linha[CODIGOCLI]</td>
				<td>$linha[RAZAO]</td>
				<td><a href='blocok_of.php?id=$linha[CODIGOOF]'>$linha[CODIGOOF]</td>
				<td>$linha[PRAZOOF]</td>
				<td>$linha[OBSERVACAO1]</td>
				<td><a href='blocok_lote.php'>$linha[QT_LOTE]</td>
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
