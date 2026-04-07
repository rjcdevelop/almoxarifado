<!DOCTYPE html>
<html>

<head>
<title>Visualizar Processo</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "Olá!!<br> RELATÓRIO DE O.P. / TRANSFERÊNCIAS DA PRODUÇÃO<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	//echo "<a href='formordprod.php'><button>Voltar</button></a><br><br>";

	include_once "../../conexao.php";

if(isset($_POST['dt_ini'])){
	$id_dtini=filter_var($_POST['dt_ini']);
	}else {
		$id_dtini='';
	}

	if(isset($_POST['dt_fim'])){
		$id_dtfim=filter_var($_POST['dt_fim']);
		}else {
			$id_dtfim='';
		}

?>

<form action="optransf.php" method="POST">
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="dt_ini"/></td>
<td><label>Data Final: </label></td>
<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="dt_fim"/></td>
<td><input type="submit" value="Buscar"></td>
<td></td>
</tr>
</table>
</form>
<br><br>

<?php

try{
// 1. BLOCO DOS REGISTROS "ORDEM DE PRODUÇÃO"
	
	$sqlop1 = $conectar->query("SELECT
	CONVERT(varchar(10),A.DATA,103) AS DTTRANSF,
	A.CODOP AS CODOP,
	A.CODTRANS AS CODTRANSF,
	A.CODPROD AS PRODFINAL,
	A.DESCRICAO AS DESCRICAO,
	B.QUANT AS QTPRODFINAL,
	C.OBSERVACAO AS OBSTRANSF
	FROM
	FAT00001.dbo.Transf2 AS A
	LEFT JOIN FAT00001.dbo.movto AS B ON A.CONTRENTR=B.CONTROLE
	LEFT JOIN FAT00001.dbo.Transf1 AS C ON A.CODTRANS=C.CODTRANS
	WHERE
	A.DATA>='$id_dtini' AND A.DATA<='$id_dtfim'
	ORDER BY A.CODOP");
	
// 1. ENQUANTO SELECIONO 1 ITEM
	while
	($relacao1 = $sqlop1->fetch(PDO::FETCH_ASSOC)){
		echo "Ordem de Produção $relacao1[CODOP] / Transferência $relacao1[CODTRANSF] :<br>
		
		<table id=tbordrel2>
		";
		
		// CASO PRECISE RELACIONAR ALGUM CAMPO PARA VARIÁVEL PHP E PUXAR PARA OUTRA QUERY SQL
		$id_codop1 = "$relacao1[CODOP]";
		$id_codtransf1 = "$relacao1[CODTRANSF]";
		$qtde_prodfinal = number_format($relacao1['QTPRODFINAL'],5,',', '.');
		
			// 2. RELACIONA ITENS DA ORDEM DE PRODUÇÃO / TRANSFERÊNCIA
				echo "
				<tr>
						<td width='75px'>$relacao1[DTTRANSF]</td>
						<td width='60px'>$relacao1[CODOP]</td>
						<td width='60px'>$relacao1[CODTRANSF]</td>
						<td width='180px'>$relacao1[PRODFINAL]</td>
						<td width='500px'>$relacao1[DESCRICAO]</td>
						<td width='120px' align=right>$qtde_prodfinal</td>
				</tr><tr>
						<td width='800px' COLSPAN='6'>$relacao1[OBSTRANSF]</td>
					</tr>";
					
				echo "</table></p>";

				// 3. BLOCO DOS REGISTROS "ITENS BAIXADOS DA O.P."
					$sql_op2 = $conectar->query("SELECT
					CONVERT(varchar(10),A.DATA,103) AS DATAOP2,
					A.CODOP AS CODOP2,
					A.CODTRANS AS CODTRANSF2,
					B.ITEM AS ITEM2,
					B.PROD AS PRODUTO2,
					B.DESCRICAO AS DESCRICAO2,
					B.QUANT AS QTPROD2,
					B.OBSSAI AS OBSSAI
					FROM
					FAT00001.dbo.Transf2 AS A
					LEFT JOIN FAT00001.dbo.movto AS B ON A.CODTRANS=B.CONTROLE AND A.DATA=B.DATA
					WHERE
					A.DATA>='$id_dtini' AND A.DATA<='$id_dtfim'
					AND A.CODOP = '{$id_codop1}'
					AND B.CONTROLE = '{$id_codtransf1}'
					ORDER BY A.CODOP,B.ITEM");

								echo "Relação de produtos baixados na O.P.:<br>
								
								<table id=tbordrel>
								";

								while($relacao_op2 = $sql_op2 -> fetch(PDO::FETCH_ASSOC)){
									$qtde_prod2 = number_format($relacao_op2['QTPROD2'],5,',', '.');
								echo "
								<tr>
										<td width='75px'>$relacao_op2[DATAOP2]</td>
										<td width='60px'>$relacao_op2[CODOP2]</td>
										<td width='60px'>$relacao_op2[CODTRANSF2]</td>										
										<td width='180px'>$relacao_op2[PRODUTO2]</td>
										<td width='500px'>$relacao_op2[DESCRICAO2]</td>
										<td width='120px' align=right>$qtde_prod2</td>
								</tr>";
								}
								echo "</table>";
								
					
					echo '<hr color="red" size="5" />';
				}
	
}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>

</body>

</html>
