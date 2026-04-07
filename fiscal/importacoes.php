<!DOCTYPE html>
<html>

<head>
<title>Importações</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "<b>R J C DEFESA E AEROESPACIAL LTDA</b><br> RELATÓRIO DE IMPORTAÇÕES REALIZADAS<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	//echo "<a href='formordprod.php'><button>Voltar</button></a><br><br>";

include_once "../conexao.php";

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

<form action="importacoes.php" method="POST">
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
// 1. BLOCO DOS REGISTROS "VENDAS / FATURAMENTO BASE FATUMATIC"
	
	$sqlop1 = $conectar->query("SELECT
	A.NOTA AS NOTA,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
    A.RAZAOFOR AS RAZAO,
	A.NATOPER AS CFOP,
	A.TOTVAL AS TPROD,
	A.TOTIPI AS IPI,
	A.VLRFRETE AS FRETE,
	(A.TOTNOT-A.TOTVAL-A.TOTIPI-A.VLRFRETE) AS OUTROS,
	A.TOTNOT AS TNOTA
	FROM
	FAT00001.dbo.nota1 AS A
	WHERE
	A.EMISSAO>='$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<='$id_dtfim'
	--'2022-07-31'
	AND A.STATUSNFE='Autorizada'
	AND A.NATOPER like '%3.%'
	ORDER BY A.NOTA");
	
	$somatot = $conectar->query("SELECT
	SUM(A.TOTNOT-A.TOTIPI)AS SEMIPI,
	SUM(A.TOTVAL) AS TPROD,
	SUM(A.TOTIPI) AS IPI,
	SUM(A.VLRFRETE) AS FRETE,
	SUM(A.TOTNOT-A.TOTVAL-A.TOTIPI-A.VLRFRETE) AS OUTROS,
	SUM(A.TOTNOT) AS TNOTA
	FROM
	FAT00001.dbo.nota1 AS A
	WHERE
	A.EMISSAO>='$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<='$id_dtfim'
	--'2022-01-31'
	AND A.NATOPER like '%3.%'
	AND A.STATUSNFE='Autorizada'");

	echo "VENDAS / FATURAMENTO ENTRE ".date('d/m/Y', strtotime($id_dtini))." A ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";

// 1. RELACAO DE NOTAS FISCAIS

echo "<table id=tbordzebr>
		<tr>
			<th width='55px' scope='col'>NOTA</th>
			<th width='80px'>EMISSÃO</th>
			<th width='300px'>RAZAO SOCIAL</th>
			<th width='40px'>CFOP</th>
			<th width='130px'>T. PROD</th>
			<th width='100px'>IPI</th>
			<th width='100px'>FRETE</th>
			<th width='100px'>OUTROS</th>
			<th width='130px'>T. NOTA</th>
		</tr>";

		while
		($relacao1 = $sqlop1->fetch(PDO::FETCH_ASSOC)) {
            // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
			$vlr_tprod = number_format($relacao1['TPROD'],2,',', '.');
            $vlr_ipi = number_format($relacao1['IPI'],2,',', '.');
            $vlr_frete = number_format($relacao1['FRETE'],2,',', '.');
            $vlr_outros = number_format($relacao1['OUTROS'],2,',', '.');
            $vlr_tnota = number_format($relacao1['TNOTA'],2,',', '.');

		echo "<tr>
			<td>$relacao1[NOTA]</td>
			<td>$relacao1[EMISSAO]</td>
			<td>$relacao1[RAZAO]</td>
			<td>$relacao1[CFOP]</td>
			<td align=right>$vlr_tprod</td>
			<td align=right>$vlr_ipi</td>
			<td align=right>$vlr_frete</td>
			<td align=right>$vlr_outros</td>
			<td align=right>$vlr_tnota</td>
			</tr>";
	}
	
	while
		($relacao2 = $somatot->fetch(PDO::FETCH_ASSOC)) {
			$somavendas = $relacao2['SEMIPI'];
           // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
		   $vlr_sipi = number_format($relacao2['SEMIPI'],2,',', '.');
		   $vlr_tprod = number_format($relacao2['TPROD'],2,',', '.');
		   $vlr_ipi = number_format($relacao2['IPI'],2,',', '.');
		   $vlr_frete = number_format($relacao2['FRETE'],2,',', '.');
		   $vlr_outros = number_format($relacao2['OUTROS'],2,',', '.');
		   $vlr_tnota = number_format($relacao2['TNOTA'],2,',', '.');
	echo "<tr>
			<th align=left>TOTAIS</th>
			<th></th>
			<th align=right>$vlr_sipi</th>
			<th></th>
			<th align=right>$vlr_tprod</th>
			<th align=right>$vlr_ipi</th>
			<th align=right>$vlr_frete</th>
			<th align=right>$vlr_outros</th>
			<th align=right>$vlr_tnota</th>			
		</tr>";

		}
	echo "</table>";
	
	echo "<br><br>";




}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}
?>
</body>
</html>
