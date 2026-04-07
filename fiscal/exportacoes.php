<!DOCTYPE html>
<html>

<head>
<title>Faturamento</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA<br> RELATÓRIO DE NOTAS EMITIDAS P/FATURAMENTO<br><br>";
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

<form action="exportacoes.php" method="POST">
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
	A.RAZAOCLI AS RAZAO,
    (A.TOTNOT-A.TOTIPI) AS SIPI,
	A.NATOPER AS CFOP,
	A.TOTVAL AS TPROD,
	A.TOTIPI AS IPI,
	A.VLRFRETE AS FRETE,
	(A.TOTNOT-A.TOTVAL-A.TOTIPI-A.VLRFRETE) AS OUTROS,
	A.TOTNOT AS TNOTA,
	(B.OBS5+'\'+B.OBS6+'\'+B.OBS7+'\'+B.OBS8+'\'+B.OBS9+'\'+B.OBS10) AS OBSERVACAO
	FROM
	FAT00001.dbo.nota1 AS A
	LEFT JOIN FAT00001.dbo.obsnot AS B ON A.NOTA=B.NOTA AND A.CODSERIE=B.CODSERIE
	WHERE
	A.EMISSAO>='$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<='$id_dtfim'
	--'2022-01-31'
	AND A.FAT='S'
	AND A.STATUSNFE='Autorizada'
	AND A.NATOPER NOT IN ('5.551','6.551')
	AND A.NATOPER LIKE '%7.%'
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
	AND A.NATOPER NOT IN ('5.551','6.551')
	AND A.FAT='S'
	AND A.STATUSNFE='Autorizada'");

	echo "VENDAS / FATURAMENTO ENTRE ".date('d/m/Y', strtotime($id_dtini))." A ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";

// 1. RELACAO DE NOTAS FISCAIS

echo "<table id=tbordzebr>
		<tr>
			<th width='52px' scope='col'>NOTA</th>
			<th width='55px'>EMISSAO</th>
			<th width='100px'>T. S/IPI</th>
			<th width='40px'>CFOP</th>
			<th width='100px'>T. PROD</th>
			<th width='80px'>IPI</th>
			<th width='80px'>FRETE</th>
			<th width='80px'>OUTROS</th>
			<th width='100px'>T. NOTA</th>
			<th>RAZAO</th>
			<th>OBSERVAÇÕES</th>
		</tr>";

		while
		($relacao1 = $sqlop1->fetch(PDO::FETCH_ASSOC)) {
            // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
			$vlr_sipi = number_format($relacao1['SIPI'],2,',', '.');
            $vlr_tprod = number_format($relacao1['TPROD'],2,',', '.');
            $vlr_ipi = number_format($relacao1['IPI'],2,',', '.');
            $vlr_frete = number_format($relacao1['FRETE'],2,',', '.');
            $vlr_outros = number_format($relacao1['OUTROS'],2,',', '.');
            $vlr_tnota = number_format($relacao1['TNOTA'],2,',', '.');

		echo "<tr>
			<td >$relacao1[NOTA]</td>
			<td >$relacao1[EMISSAO]</td>
			<td align=right>$vlr_sipi</td>
			<td>$relacao1[CFOP]</td>
			<td align=right>$vlr_tprod</td>
			<td align=right>$vlr_ipi</td>
			<td align=right>$vlr_frete</td>
			<td align=right>$vlr_outros</td>
			<td align=right>$vlr_tnota</td>
			<td >$relacao1[RAZAO]</td>
			<td >$relacao1[OBSERVACAO]</td>
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
			<th align=left colspan='2'>TOTAIS</th>
			<th align=right>$vlr_sipi</th>
			<th></th>
			<th align=right>$vlr_tprod</th>
			<th align=right>$vlr_ipi</th>
			<th align=right>$vlr_frete</th>
			<th align=right>$vlr_outros</th>
			<th align=right>$vlr_tnota</th>
			<th></th><th></th>
		</tr>";

		}
	echo "</table>";
	
	echo "<br><br>";


	//___________________________________________________________________________________________________________________
	// 1. BLOCO DOS REGISTROS "NOTAS DE ENTRADAS / DEVOLUCOES"
	
	$sqlent = $conectar->query("SELECT
	A.NOTA AS NOTA,
	(A.TOTNOTA-A.VALIPI) AS SIPI,
	A.NATU AS CFOP,
	A.VALMER AS TPROD,
	A.VALIPI AS IPI,
	A.VALFRET AS FRETE,
	(A.TOTNOTA-A.VALMER-A.VALIPI-A.VALFRET) AS OUTROS,
	A.TOTNOTA AS TNOTA
	FROM
	FAT00001.dbo.entrada1 AS A
	WHERE
	A.EMISSAO>=
	'$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<=
	'$id_dtfim'
	--'2022-06-30'
	AND A.NATU IN ('1.201','2.201','3.201','1.202','2.202','3.202','1.411','2.411')
	ORDER BY A.NOTA");
	
	$somaent = $conectar->query("SELECT
	SUM(A.TOTNOTA-A.VALIPI)AS SEMIPI,
	SUM(A.VALMER) AS TPROD,
	SUM(A.VALIPI) AS IPI,
	SUM(A.VALFRET) AS FRETE,
	SUM(A.TOTNOTA-A.VALMER-A.VALIPI-A.VALFRET) AS OUTROS,
	SUM(A.TOTNOTA) AS TNOTA
	FROM
	FAT00001.dbo.entrada1 AS A
	WHERE
	A.EMISSAO>=
	'$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<=
	'$id_dtfim'
	--'2022-06-30'
	AND A.NATU IN ('1.201','2.201','3.201','1.202','2.202','3.202','1.411','2.411')");

	echo "NOTAS FISCAIS DE DEVOLUÇÃO ENTRE ".date('d/m/Y', strtotime($id_dtini))." A ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";

// 1. RELACAO DE NOTAS FISCAIS DE DEVOLUCAO

echo "<table id=tbordzebr>
		<tr>
			<th width='55px' scope='col'>NOTA</th>
			<th width='130px'>T. S/IPI</th>
			<th width='40px'>CFOP</th>
			<th width='130px'>T. PROD</th>
			<th width='100px'>IPI</th>
			<th width='100px'>FRETE</th>
			<th width='100px'>OUTROS</th>
			<th width='130px'>T. NOTA</th>
		</tr>";

		while
		($relacao1 = $sqlent->fetch(PDO::FETCH_ASSOC)) {
			
            // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
			$vlr_sipi = number_format($relacao1['SIPI'],2,',', '.');
            $vlr_tprod = number_format($relacao1['TPROD'],2,',', '.');
            $vlr_ipi = number_format($relacao1['IPI'],2,',', '.');
            $vlr_frete = number_format($relacao1['FRETE'],2,',', '.');
            $vlr_outros = number_format($relacao1['OUTROS'],2,',', '.');
            $vlr_tnota = number_format($relacao1['TNOTA'],2,',', '.');

		echo "<tr>
			<td >$relacao1[NOTA]</td>
			<td align=right>$vlr_sipi</td>
			<td>$relacao1[CFOP]</td>
			<td align=right>$vlr_tprod</td>
			<td align=right>$vlr_ipi</td>
			<td align=right>$vlr_frete</td>
			<td align=right>$vlr_outros</td>
			<td align=right>$vlr_tnota</td>
			</tr>";
	}
	
	while
		($relacao2 = $somaent->fetch(PDO::FETCH_ASSOC)) {
			$somadevolucoes = $relacao2['SEMIPI'];
           // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
		   $vlr_sipi = number_format($relacao2['SEMIPI'],2,',', '.');
		   $vlr_tprod = number_format($relacao2['TPROD'],2,',', '.');
		   $vlr_ipi = number_format($relacao2['IPI'],2,',', '.');
		   $vlr_frete = number_format($relacao2['FRETE'],2,',', '.');
		   $vlr_outros = number_format($relacao2['OUTROS'],2,',', '.');
		   $vlr_tnota = number_format($relacao2['TNOTA'],2,',', '.');
	echo "<tr>
			<th align=left>TOTAIS</th>
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
		$fatliq = $somavendas-$somadevolucoes;
		$faturamentoliq = number_format($fatliq,2,',', '.');
	echo "<br><br> Venda líquida: ".$faturamentoliq."<br><br>";


}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}
?>
</body>
</html>
