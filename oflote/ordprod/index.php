<!DOCTYPE html>
<html>

<head>
<title>Lista de Transferências</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "Olá!!<br> Seja bem vindo a lista de Transferências das O.P.<br><br>";
	echo "<a href='../index.php'><button>Voltar</button></a>
			<a href='index.php'><button>Listar Transf</button></a><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../conexao.php";

if(isset($_POST['busca'])){
	$id=filter_var($_POST['busca']);
	}else {
		$id='';
	}

?>

<form action="index.php" method="POST">
<table>
<tr>
<td><label>Pesquisar O.F.: </label></td>
<td><input type="text" style="font-size: 10pt; height: 16px; width:300px;" name="busca"/></td>
<td><input type="submit" value="Buscar"></td>
</tr>
</table>
</form>
<br><br>

<?php

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
		CONVERT(varchar(10),A.DATATU,103) AS 'DATATRANSF',
		A.CODTRANS AS CODTRANS,
		A.CODOP AS CODOP,
		A.CODCUS AS CCUSTO,
		CASE WHEN A.CODTRANS=B.transf_op THEN B.cod_lote ELSE 'Vincular' END AS 'CODLOTE',
		B.cod_predio AS PREDIO,
		(A.OBSERVACAO+' '+C.OBSERVACAO+' '+C.OBSERVACAO2) AS OBSERVACAO
		FROM
		FAT00001.dbo.Transf1 AS A
		LEFT JOIN pcp_producao.dbo.ordprod AS B ON A.CODTRANS=B.transf_op
		LEFT JOIN FAT00001.dbo.CadOP1 AS C ON A.CODOP=C.CODIGO
		WHERE DATATU>'2022-01-01'
		AND (A.OBSERVACAO+C.OBSERVACAO+C.OBSERVACAO2) LIKE '%$id%'
		ORDER BY A.DATATU DESC
	");
	
	echo "<table id=tbordprod>
		<tr>
			<td width=4%>DT TRANSF</td>
			<td width=3%>TRANSF</td>
			<td width=3%>O.P.</td>
			<td width=4%>C CUSTO</td>
			<td width=3%>LOTE</td>
			<td width=3%>PREDIO</td>
			<td width=20%>OBSERVAÇÃO</td>			
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		/* FALTA IMPLEMENTAR CONDIÇÃO: SE NÃO HOUVER VINCULAÇÃO "CADASTRAR", SE JÁ HOUVER "ALTERAR"
		if($linha[CODLOTE] == 'Cadastrar'){
			echo "Cadastrar";
		}else{
			echo "Número da OP: ".$linha[CODOP];
		}
		//$lote = "";*/
		
		echo "<tr>
				<td>$linha[DATATRANSF]</td>
				<td>$linha[CODTRANS]</td>
				<td><a href='formordprodvis.php?id=$linha[CODOP]'>$linha[CODOP]</a></td>
				<td>$linha[CCUSTO]</td>
				<td><a href='formcadastrartransf.php?id=$linha[CODTRANS]'>$linha[CODLOTE]</a></td>
				<td>$linha[PREDIO]</td>
				<td>$linha[OBSERVACAO]</td>
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
