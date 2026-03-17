<!DOCTYPE html>
<html>

<head>
<title>Ordem de Produção</title>
<link href="../css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA <br> RELATÓRIO DE ORDEM DE PRODUÇÃO / TRANSFERÊNCIAS DAS ORDEM DE PRODUÇÃO<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	//echo "<a href='formordprod.php'><button>Voltar</button></a><br>";

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
if(isset($_POST['sequencia'])){
	$sequencia = filter_var($_POST['sequencia']);
	}else {
		$sequencia = 'A.DATA,A.CODIGO';
	}
		
	
		echo "<form action='exportar.php' method='POST'>
		<table>
		<tr>
		<td><input type='hidden' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini' value='$id_dtini'/></td>
		<td><input type='hidden' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$id_dtfim'/></td>
		</tr>
		<td><input type='submit' value='Exportar'></td>
		</table>
		</form>
		<br>";

echo "<form action='ordprod.php' method='POST'>
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini' value='$id_dtini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$id_dtfim'/></td>
<td>Ordem:</td>	<td>
<input type='radio' name='sequencia' value='A.DATA,A.CODIGO' checked>Data
<input type='radio' name='sequencia' value='A.CODPROD,A.DATA'>Produto</td>
<td><input type='submit' value='Buscar'></td>
<td></td>
</tr>
</table>
</form>
<br>";


try{
// 1. BLOCO DOS REGISTROS "ORDEM DE PRODUÇÃO"

	$sqlop1 = $conectar->query("SELECT
	CONVERT(varchar(10),A.DATA,103) AS DTOP,
	A.CODIGO AS CODOP,
	--C.CODTRANS AS CODTRANSF,
	A.CODPROD AS PRODFINAL,
	A.DESCRICAO AS DESCRICAO,
	A.QTDE AS QTPRODFINAL,
	A.OBS1 + ' ' + A.OBS2 AS OBSOP
	FROM
	FAT00001.dbo.CadOp2 AS A
	--LEFT JOIN FAT00001.dbo.CadOp3 AS B ON A.CODIGO=B.CODIGO_OP
	--LEFT JOIN FAT00001.dbo.Transf1 AS C ON A.CODIGO=C.CODOP
	WHERE
	A.DATA>='$id_dtini' AND A.DATA<='$id_dtfim'
	ORDER BY $sequencia");

// 1. ENQUANTO SELECIONO 1 ITEM
	while
	($relacao1 = $sqlop1->fetch(PDO::FETCH_ASSOC)){
		echo "Ordem de Produção $relacao1[CODOP] <br>
		
		<table id=tbordrel2>
		";
		
		// CASO PRECISE RELACIONAR ALGUM CAMPO PARA VARIÁVEL PHP E PUXAR PARA OUTRA QUERY SQL
		$id_codop1 = "$relacao1[CODOP]";
		$prod_final = "$relacao1[PRODFINAL]";
		$qtde_prodfinal = number_format($relacao1['QTPRODFINAL'],5,',', '.');
		
			// 2. RELACIONA ITENS DA ORDEM DE PRODUÇÃO / TRANSFERÊNCIA
				echo "
				<tr>
					<td width='75px'>$relacao1[DTOP]</td>
					<td width='60px'>$relacao1[CODOP]</td>
					<td width='180px'>$relacao1[PRODFINAL]</td>
					<td width='500px'>$relacao1[DESCRICAO]</td>
					<td width='120px' align=right>$qtde_prodfinal</td>
				</tr>
				<tr>
					<td width='800px' COLSPAN='6'>$relacao1[OBSOP]</td>
				</tr>";
					
				echo "</table>";

				// 3. BLOCO DOS REGISTROS "ITENS BAIXADOS DA O.P."
					$sql_op2 = $conectar->query("SELECT
					CONVERT(varchar(10),B.DATA,103) AS DATAOP2,
					A.CODIGO_OP AS CODOP2,
					B.ITEM AS ITEM2,
					A.CODIGO_MP AS PRODUTO2,
					A.DESCRICAO_MP AS DESCRICAO2,
					A.QTDETEMP2 AS QTPROD2
					FROM
					FAT00001.dbo.CadOp3 AS A LEFT JOIN FAT00001.dbo.CadOp2 AS B ON A.CODIGO_OP=B.CODIGO AND A.CODIGO_PR = B.CODPROD
					WHERE
					B.DATA>='$id_dtini' AND B.DATA<='$id_dtfim'
					AND A.CODIGO_OP = '{$id_codop1}' AND A.CODIGO_PR = '$prod_final'
					ORDER BY A.CODIGO_OP,B.ITEM
					");

								echo "<table id=tbordrel>
								";

								while($relacao_op2 = $sql_op2 -> fetch(PDO::FETCH_ASSOC)){
									$qtde_prod2 = number_format($relacao_op2['QTPROD2'],5,',', '.');
								echo "
								<tr>
										<td width='75px'>$relacao_op2[DATAOP2]</td>
										<td width='60px'>$relacao_op2[CODOP2]</td>
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