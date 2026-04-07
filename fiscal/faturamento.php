<!DOCTYPE html>
<html>

<head>
<title>Faturamento</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
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

echo "R J C DEFESA E AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
		<input type='button' value='Voltar' onClick='history.go(-1)'><br>
		Relatório do Faturamento<br>Período: ".date('d/m/Y', strtotime($id_dtini))." a ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";

echo"<form action='faturamento.php' method='POST'>
<table><tr>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim'/></td>
<td><input type='submit' value='Buscar'></td>
<td></td></tr></table></form><br><br>";

try{
//1. BLOCO DOS REGISTROS "VENDAS / FATURAMENTO BASE FATUMATIC"
	$sqlfat1 = $conectar->query("SELECT
	A.NOTA AS NOTA,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
    (C.TPROD+A.VLRFRETE) AS SIPI,
	C.CFOP AS CFOP,
	C.TPROD AS TPROD,
	A.TOTIPI AS IPI,
	A.VLRFRETE AS FRETE,
	C.TICMS AS VLRICMS,
	(A.TOTNOT-C.TPROD-A.TOTIPI-A.VLRFRETE) AS OUTROS,
	A.TOTNOT AS TNOTA,
	B.CL_CGC AS CNPJCLI,
	A.RAZAOCLI AS RAZAOCLI,
	A.ESTADO AS ESTADO,
	A.CIDADE AS CIDADE
	FROM
	FAT00001.dbo.nota1 AS A
	LEFT JOIN (SELECT NF.NOTA,NF.CFOP,SUM(NF.VLRTOT) AS TPROD, SUM(NF.VLRICMS) AS TICMS, SUM(NF.VALDESC) AS TDESC, SUM(VLRIPI) AS TIPI
	FROM FAT00001.dbo.Nota2 AS NF WHERE NF.CFOP NOT IN ('5.551','6.551','1.556','2.556','5.902','6.902')
	GROUP BY NF.NOTA,NF.CFOP) AS C ON C.NOTA = A.NOTA
	LEFT JOIN FIN00001.dbo.cadcli AS B ON A.CLIENTE=B.CL_CODIGO
	WHERE
	A.EMISSAO>='$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<='$id_dtfim'
	--'2022-01-31'
	AND A.FAT='S'
	AND A.STATUSNFE='Autorizada'
	AND A.NATOPER NOT IN ('5.551','6.551','1.556','2.556','5.902','6.902')
	ORDER BY A.NOTA");
	
	$somafat1 = $conectar->query("SELECT
	SUM(C.TPROD+A.VLRFRETE)AS SEMIPI,
	SUM(C.TPROD) AS TPROD,
	SUM(C.TIPI) AS IPI,
	SUM(A.VLRFRETE) AS FRETE,
	SUM(A.TOTNOT-C.TPROD-A.TOTIPI-A.VLRFRETE) AS OUTROS,
	SUM(A.TOTNOT) AS TNOTA,
	SUM(C.TICMS) AS TICMS	
	FROM
	FAT00001.dbo.nota1 AS A
	LEFT JOIN (SELECT NF.NOTA,NF.CFOP,SUM(NF.VLRTOT) AS TPROD, SUM(NF.VLRICMS) AS TICMS, SUM(NF.VALDESC) AS TDESC, SUM(VLRIPI) AS TIPI
	FROM FAT00001.dbo.Nota2 AS NF WHERE NF.CFOP NOT IN ('5.551','6.551','1.556','2.556','5.902','6.902')
	GROUP BY NF.NOTA,NF.CFOP) AS C ON C.NOTA = A.NOTA
	LEFT JOIN FIN00001.dbo.cadcli AS B ON A.CLIENTE=B.CL_CODIGO
	WHERE
	A.EMISSAO>='$id_dtini'
	--'2022-01-01'
	AND A.EMISSAO<='$id_dtfim'
	--'2022-01-31'
	AND A.NATOPER NOT IN ('5.551','6.551','1.556','2.556','5.902','6.902')
	AND A.FAT='S'
	AND A.STATUSNFE='Autorizada'");

//1.2 RELACAO DE NOTAS FISCAIS DE FATURAMENTO
echo "Notas Fiscais de Faturamento:<br>
	<table id=tbordzebr>
		<tr>
			<th width='80px' scope='col'>NOTA</th>
			<th width='55px'>EMISSAO</th>
			<th width='100px'>T. S/IPI</th>
			<th width='40px'>CFOP</th>
			<th width='100px'>T. PROD</th>
			<th width='80px'>IPI</th>
			<th width='80px'>FRETE</th>
			<th width='80px'>OUTROS</th>
			<th width='100px'>T. NOTA</th>
			<th width='130px'>CNPJ</th>
			<th width='600px'>RAZAO SOCIAL</th>
			<th width='100px'>T. ICMS</th>
			<th width='30px'>UF</th>
			<th width='130px'>CIDADE</th>
		</tr>";

	while
		($relacao1 = $sqlfat1->fetch(PDO::FETCH_ASSOC)) {
            // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
			$vlr_sipi = number_format($relacao1['SIPI'],2,',', '.');
            $vlr_tprod = number_format($relacao1['TPROD'],2,',', '.');
            $vlr_ipi = number_format($relacao1['IPI'],2,',', '.');
            $vlr_frete = number_format($relacao1['FRETE'],2,',', '.');
            $vlr_outros = number_format($relacao1['OUTROS'],2,',', '.');
            $vlr_tnota = number_format($relacao1['TNOTA'],2,',', '.');
			$vlr_icms = number_format($relacao1['VLRICMS'],2,',', '.');
		echo "<tr>
			<td  align=right>$relacao1[NOTA]</td>
			<td>$relacao1[EMISSAO]</td>
			<td align=right>$vlr_sipi</td>
			<td>$relacao1[CFOP]</td>
			<td align=right>$vlr_tprod</td>
			<td align=right>$vlr_ipi</td>
			<td align=right>$vlr_frete</td>
			<td align=right>$vlr_outros</td>
			<td align=right>$vlr_tnota</td>
			<td>$relacao1[CNPJCLI]</td>
			<td>$relacao1[RAZAOCLI]</td>
			<td align=right>$vlr_icms</td>
			<td>$relacao1[ESTADO]</td>
			<td>$relacao1[CIDADE]</td>
			</tr>";
	}
	
	while
		($relacao2 = $somafat1->fetch(PDO::FETCH_ASSOC)) {
			$somavendas = $relacao2['SEMIPI'];
			$somaicms = $relacao2['TICMS'];
           // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
		   $vlr_sipi = number_format($relacao2['SEMIPI'],2,',', '.');
		   $vlr_tprod = number_format($relacao2['TPROD'],2,',', '.');
		   $vlr_ipi = number_format($relacao2['IPI'],2,',', '.');
		   $vlr_frete = number_format($relacao2['FRETE'],2,',', '.');
		   $vlr_outros = number_format($relacao2['OUTROS'],2,',', '.');
		   $vlr_tnota = number_format($relacao2['TNOTA'],2,',', '.');
		   $vlricms = number_format($relacao2['TICMS'],2,',', '.');
	echo "<tr>
			<th align=left colspan='2'>TOTAIS</th>
			<th align=right>$vlr_sipi</th>
			<th></th>
			<th align=right>$vlr_tprod</th>
			<th align=right>$vlr_ipi</th>
			<th align=right>$vlr_frete</th>
			<th align=right>$vlr_outros</th>
			<th align=right>$vlr_tnota</th>
			<th></th>
			<th></th>
			<th align=right>$vlricms</th>
		</tr>";
		}
	echo "</table><br><br>";

//2. BLOCO DOS REGISTROS "DEVOLUÇÕES EMISSAO PRÓPRIA BASE FATUMATIC"
	$sqlfat2 = $conectar->query("SELECT
		A.NOTA AS NOTA,
		CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
		(A.TOTNOT-A.TOTIPI) AS SIPI,
		A.NATOPER AS CFOP,
		A.TOTVAL AS TPROD,
		A.TOTIPI AS IPI,
		A.VLRFRETE AS FRETE,
		(A.TOTNOT-A.TOTVAL-A.TOTIPI-A.VLRFRETE) AS OUTROS,
		A.TOTNOT AS TNOTA,
		B.CGC AS CNPJCLI,
		A.RAZAOFOR AS RAZAOCLI
		FROM
		FAT00001.dbo.nota1 AS A
		LEFT JOIN FIN00001.dbo.cadfor AS B ON A.FORNE=B.CODFOR
		WHERE
		A.EMISSAO>='$id_dtini'
		AND A.EMISSAO<='$id_dtfim'
		AND A.STATUSNFE='Autorizada'
		AND A.NATOPER IN ('1.201','2.201','3.201','1.202','2.202','3.202','1.411','2.411')
		ORDER BY A.NOTA");
	
	$somafat2 = $conectar->query("SELECT
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
		AND A.EMISSAO<='$id_dtfim'
		AND A.NATOPER IN ('1.201','2.201','3.201','1.202','2.202','3.202','1.411','2.411')
		AND A.STATUSNFE='Autorizada'");

		//2.2 RELACAO DE NOTAS FISCAIS DE FATURAMENTO
		echo "Notas Fiscais de Devolução (emissão própria):<br>
			<table id=tbordzebr>
				<tr>
					<th width='80px' scope='col'>NOTA</th>
					<th width='55px'>EMISSAO</th>
					<th width='100px'>T. S/IPI</th>
					<th width='40px'>CFOP</th>
					<th width='100px'>T. PROD</th>
					<th width='80px'>IPI</th>
					<th width='80px'>FRETE</th>
					<th width='80px'>OUTROS</th>
					<th width='100px'>T. NOTA</th>
					<th width='130px'>CNPJ</th>
					<th width='600px'>RAZAO SOCIAL</th>
				</tr>";

	while($relacao1 = $sqlfat2->fetch(PDO::FETCH_ASSOC)) {
		// VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
		$vlr_sipi = number_format($relacao1['SIPI'],2,',', '.');
		$vlr_tprod = number_format($relacao1['TPROD'],2,',', '.');
		$vlr_ipi = number_format($relacao1['IPI'],2,',', '.');
		$vlr_frete = number_format($relacao1['FRETE'],2,',', '.');
		$vlr_outros = number_format($relacao1['OUTROS'],2,',', '.');
		$vlr_tnota = number_format($relacao1['TNOTA'],2,',', '.');

	echo "<tr>
		<td align=right>$relacao1[NOTA]</td>
		<td>$relacao1[EMISSAO]</td>
		<td align=right>$vlr_sipi</td>
		<td>$relacao1[CFOP]</td>
		<td align=right>$vlr_tprod</td>
		<td align=right>$vlr_ipi</td>
		<td align=right>$vlr_frete</td>
		<td align=right>$vlr_outros</td>
		<td align=right>$vlr_tnota</td>
		<td>$relacao1[CNPJCLI]</td>
		<td>$relacao1[RAZAOCLI]</td>
		</tr>";
	}

	while($relacao2 = $somafat2->fetch(PDO::FETCH_ASSOC)) {
		$somavendas2 = $relacao2['SEMIPI'];
	   // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
	   $vlr_sipi2 = number_format($relacao2['SEMIPI'],2,',', '.');
	   $vlr_tprod2 = number_format($relacao2['TPROD'],2,',', '.');
	   $vlr_ipi2 = number_format($relacao2['IPI'],2,',', '.');
	   $vlr_frete2 = number_format($relacao2['FRETE'],2,',', '.');
	   $vlr_outros2 = number_format($relacao2['OUTROS'],2,',', '.');
	   $vlr_tnota2 = number_format($relacao2['TNOTA'],2,',', '.');
	echo "<tr>
			<th align=left colspan='2'>TOTAIS</th>
			<th align=right>$vlr_sipi2</th>
			<th></th>
			<th align=right>$vlr_tprod2</th>
			<th align=right>$vlr_ipi2</th>
			<th align=right>$vlr_frete2</th>
			<th align=right>$vlr_outros2</th>
			<th align=right>$vlr_tnota2</th>
			<th></th>
			<th></th>
		</tr>";
	}
	echo "</table><br><br>";

//3. BLOCO DOS REGISTROS "NOTAS DE ENTRADAS / DEVOLUCOES DE TERCEIROS"
	
	$sqlent = $conectar->query("SELECT
	A.NOTA AS NOTA,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
	(A.TOTNOTA-A.VALIPI) AS SIPI,
	A.NATU AS CFOP,
	A.VALMER AS TPROD,
	A.VALIPI AS IPI,
	A.VALFRET AS FRETE,
	(A.TOTNOTA-A.VALMER-A.VALIPI-A.VALFRET) AS OUTROS,
	A.TOTNOTA AS TNOTA,
	A.RAZFORN AS RAZAO
	FROM
	FAT00001.dbo.entrada1 AS A
	WHERE
	A.EMISSAO >= '$id_dtini'
	--'2023-01-01'
	AND A.EMISSAO <= '$id_dtfim'
	--'2023-12-31'
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
		A.EMISSAO >= '$id_dtini'
		--'2022-01-01'
		AND A.EMISSAO <= '$id_dtfim'
		--'2022-06-30'
		AND A.NATU IN ('1.201','2.201','3.201','1.202','2.202','3.202','1.411','2.411')");

	echo "";

// 3.2 RELACAO DE NOTAS FISCAIS DE DEVOLUCAO
	echo "Notas Fiscais de Devolução (Terceiros):<br>
		<table id=tbordzebr>
			<tr>
				<th width='80px' scope='col'>NOTA</th>
				<th width='55px'>EMISSAO</th>
				<th width='100px'>T. S/IPI</th>
				<th width='40px'>CFOP</th>
				<th width='100px'>T. PROD</th>
				<th width='80px'>IPI</th>
				<th width='80px'>FRETE</th>
				<th width='80px'>OUTROS</th>
				<th width='100px'>T. NOTA</th>
				<th width='600px'>RAZAO SOCIAL</th>
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
			<td align=right>$relacao1[NOTA]</td>
			<td >$relacao1[EMISSAO]</td>
			<td align=right>$vlr_sipi</td>
			<td>$relacao1[CFOP]</td>
			<td align=right>$vlr_tprod</td>
			<td align=right>$vlr_ipi</td>
			<td align=right>$vlr_frete</td>
			<td align=right>$vlr_outros</td>
			<td align=right>$vlr_tnota</td>
			<td >$relacao1[RAZAO]</td>
			</tr>";
	}
	
	//4. SOMA TOTAL LÍQUIDO
	while($relacao2 = $somaent->fetch(PDO::FETCH_ASSOC)) {
		$somadevolucoes = $relacao2['SEMIPI'];
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
			<th></th>
		</tr>";

		}
	echo "</table>";
		$fatliq = $somavendas-$somavendas2-$somadevolucoes;
		$faturamentoliq = number_format($fatliq,2,',', '.');
		$vlr_icms = number_format($somaicms,2,',', '.');
		$vlrbasepiscofins = $fatliq-$somaicms;
		$basepiscofins = number_format($vlrbasepiscofins,2,',', '.');
		$vlrpis = $vlrbasepiscofins * 0.0065;
		$vlr_pis = number_format($vlrpis,2,',', '.');;
		$vlrcofins = $vlrbasepiscofins * 0.03;
		$vlr_cofins = number_format($vlrcofins,2,',', '.');

	echo "<br><br><table id=tbordzebr>
				<tr>
				<th width='150px'>Venda Líquida</th>
				<th width='150px'>Soma ICMS</th>

				<!--
				<th width='150px'>B.C. P/C</th>
				<th width='150px'>Valor PIS</th>
				<th width='150px'>Valor COFINS</th>
				-->

				</tr>
				<tr>
				<td align=right>$faturamentoliq</td>
				<td align=right>$vlr_icms</td>
				
				<!--
				<td align=right>$basepiscofins</td>
				<td align=right>$vlr_pis</td>
				<td align=right>$vlr_cofins</td>
				-->

				</tr>
				</table>

				<br><br>";
}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}
?>
</body>
</html>
