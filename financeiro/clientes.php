<!DOCTYPE html>
<html>
<head>
<title>Clientes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA <br> Relatório de Clientes<br><br>";
	//echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	echo "<a href='index.php'><button>Voltar</button></a><br><br>";

	$hoje = date('Y-m-d'); // para data formatada caso precise('d/m/Y')
	//echo "Exibir a data de hoje: " . $hoje . "<br><br>";

include_once "../conexao.php";


if(isset($_POST['dttipo'])){
	$dttipo = filter_var($_POST['dttipo']);
}else{
	$dttipo = 'dtpago';
}

if($dttipo == 'dtemis'){
	$dttipoini = "A.DATAEMIS";
	$dttipofin = "A.DATAEMIS";
}elseif($dttipo == 'dtvenc'){
	$dttipoini = "A.DATAVENC";
	$dttipofin = "A.DATAVENC";
}else {
	$dttipoini = "A.DTPAG";
	$dttipofin = "A.DTPAG";
	}

if(isset($_POST['dt_ini'])){
	$id_dtini = filter_var($_POST['dt_ini']);
	}else{
		$id_dtini = $hoje;
	}
if(isset($_POST['dt_fim'])){
	$id_dtfim = filter_var($_POST['dt_fim']);
	}else{
		$id_dtfim = $hoje;
	}

	if(isset($_POST['tipo'])){
		$tipo = filter_var($_POST['tipo']);
	}else{
		$tipo = 'Financeiro';
	}


if(isset($_POST['situacao'])){
	$situacao = filter_var($_POST['situacao']);
}else{
	$situacao = 'Ambos';
}

if(isset($_POST['razsocial'])){
	$razsocial = "AND A.RAZAO LIKE '%" . filter_var($_POST['razsocial']) . "%'";
}else{
	$razsocial = '';
	}

	if(isset($_POST['nronf'])){
	$nronf = "AND A.DUPLIC LIKE '%" . filter_var($_POST['nronf']) . "%'";
}else{
	$nronf = '';
	}

//echo "Exibir a variável desejada: " . $dttipo . $dttipofin . $dttipoini . "<br><br>";
//echo "Exibir o tipo de relatório: " . $tipo . "<br><br>";
//echo "Data de emissão entre ".date('d/m/Y', strtotime($id_dtini))." a ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";

echo "<form action='clientes.php' method='POST'>
<table>
<tr>
<td><label>Data: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini' value='$id_dtini'/>
<input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$id_dtfim'/></td>
<td><label>Razão Social: </label></td>
<td><input type='textbox' style='font-size: 10pt; height: 16px; width:500px;' name='razsocial'/></td>
</tr>
<tr>
<td>Tipo:</td>
<td><input type='radio' name='tipo' value='Contabil'>Contábil
<input type='radio' name='tipo' value='Financeiro' checked>Financeiro</td>
<td><label>No NF: </label></td>
<td><input type='textbox' style='font-size: 10pt; height: 16px; width:180px;' name='nronf'/></td>
</tr>
<tr>
<td>Status:</td>
<td><input type='radio' name='situacao' value='Ambos'>Ambos
<input type='radio' name='situacao' value='Aberto'>Aberto
<input type='radio' name='situacao' value='Fechado' checked>Fechado</td>
<td>Buscar Data:</td>
<td><input type='radio' name='dttipo' value='dtemis'>Emissão
<input type='radio' name='dttipo' value='dtvenc'>Vencimento
<input type='radio' name='dttipo' value='dtpago' checked>Pago</td>
</tr>
<tr>
<td colspan = '4' align = 'center'><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
<br>";

try{

	/*---------------------------------------------------------------------
	 INSTRUCOES PARA ELABORAR RELATORIO FINANCEIRO*/

if($tipo == 'Financeiro'){
	if($situacao == 'Aberto'){
	$sql1 = $conectar->query("SELECT
	A.CODCLI AS CODCLI,
	A.RAZAO AS RAZAO,
	CONVERT(varchar(10),A.DATAEMIS,103) AS DTEMISSAO,
	CONVERT(varchar(10),A.DATAVENC,103) AS DTVENCIMENTO,
	CONVERT(varchar(10),A.DTPAG,103) AS DTRECEBIMENTO,
	A.VALOR AS VLRORIGINAL,
	A.[DESC] AS DESCONTO,
	A.JUROS AS JUROS,
	A.PAGO AS VLRPAGO,
	A.DUPLIC AS DUPLIC,
	A.[STATUS] AS SITUACAO
	FROM
	FIN00001.dbo.arqrec AS A
	WHERE
	$dttipoini >= '$id_dtini' AND $dttipofin <= '$id_dtfim' AND A.[STATUS] NOT IN ('Fechado')
	$razsocial
	$nronf
	ORDER BY A.RAZAO,A.DATAEMIS");

	$sqlsoma = $conectar->query("SELECT
	SUM(A.VALOR) AS SOMAVLRORIGINAL,
	SUM(A.[DESC]) AS SOMADESCONTO,
	SUM(A.JUROS) AS SOMAJUROS,
	SUM(A.PAGO) AS SOMAVLRPAGO
	FROM
	FIN00001.dbo.arqrec AS A
	WHERE
	$dttipoini >= '$id_dtini' AND $dttipofin <= '$id_dtfim' AND A.[STATUS] NOT IN ('Fechado')
	$razsocial
	$nronf
	");

	}elseif($situacao == 'Fechado'){
	$sql1 = $conectar->query("SELECT
	A.CODCLI AS CODCLI,
	A.RAZAO AS RAZAO,
	CONVERT(varchar(10),A.DATAEMIS,103) AS DTEMISSAO,
	CONVERT(varchar(10),A.DATAVENC,103) AS DTVENCIMENTO,
	CONVERT(varchar(10),A.DTPAG,103) AS DTRECEBIMENTO,
	A.VALOR AS VLRORIGINAL,
	A.[DESC] AS DESCONTO,
	A.JUROS AS JUROS,
	A.PAGO AS VLRPAGO,
	A.DUPLIC AS DUPLIC,
	A.[STATUS] AS SITUACAO
	FROM
	FIN00001.dbo.arqrec AS A
	WHERE
	$dttipoini >= '$id_dtini' AND $dttipofin <= '$id_dtfim' AND A.[STATUS] = 'Fechado'
	$razsocial
	$nronf

	ORDER BY A.RAZAO,A.DATAEMIS");

	$sqlsoma = $conectar->query("SELECT
	SUM(A.VALOR) AS SOMAVLRORIGINAL,
	SUM(A.[DESC]) AS SOMADESCONTO,
	SUM(A.JUROS) AS SOMAJUROS,
	SUM(A.PAGO) AS SOMAVLRPAGO
	FROM
	FIN00001.dbo.arqrec AS A
	WHERE
	$dttipoini >= '$id_dtini' AND $dttipofin <= '$id_dtfim' AND A.[STATUS] = 'Fechado'
	$razsocial
	$nronf

	");

	}else{
	$sql1 = $conectar->query("SELECT
	A.CODCLI AS CODCLI,
	A.RAZAO AS RAZAO,
	CONVERT(varchar(10),A.DATAEMIS,103) AS DTEMISSAO,
	CONVERT(varchar(10),A.DATAVENC,103) AS DTVENCIMENTO,
	CONVERT(varchar(10),A.DTPAG,103) AS DTRECEBIMENTO,
	A.VALOR AS VLRORIGINAL,
	A.[DESC] AS DESCONTO,
	A.JUROS AS JUROS,
	A.PAGO AS VLRPAGO,
	A.DUPLIC AS DUPLIC,
	A.[STATUS] AS SITUACAO
	FROM
	FIN00001.dbo.arqrec AS A
	WHERE
	$dttipoini >= '$id_dtini' AND $dttipofin <= '$id_dtfim'
	$razsocial
	$nronf
	ORDER BY A.RAZAO,A.DATAEMIS");
	
	$sqlsoma = $conectar->query("SELECT
	SUM(A.VALOR) AS SOMAVLRORIGINAL,
	SUM(A.[DESC]) AS SOMADESCONTO,
	SUM(A.JUROS) AS SOMAJUROS,
	SUM(A.PAGO) AS SOMAVLRPAGO
	FROM
	FIN00001.dbo.arqrec AS A
	WHERE
	$dttipoini >= '$id_dtini' AND $dttipofin <= '$id_dtfim'
	$razsocial
	$nronf
	");

	/*---------------------------------------------------------------------
	 INSTRUCOES PARA ELABORAR RELATORIO CONTABIL */
	
	}}else{
		
		// echo "Relatório Contábil a implantar";
		
		if($situacao == 'Aberto'){
			$sql1 = $conectar->query("SELECT
			A.CODCLI AS CODCLI,
			A.RAZAO AS RAZAO,
			CONVERT(varchar(10),A.DATAEMIS,103) AS DTEMISSAO,
			CONVERT(varchar(10),A.DATAVENC,103) AS DTVENCIMENTO,
			CONVERT(varchar(10),A.DTPAG,103) AS DTRECEBIMENTO,
			A.VALOR AS VLRORIGINAL,
			A.[DESC] AS DESCONTO,
			A.JUROS AS JUROS,
			A.PAGO AS VLRPAGO,
			A.DUPLIC AS DUPLIC,
			A.[STATUS] AS SITUACAO
			FROM
			(SELECT * FROM FIN00001.dbo.arqrec AS A WHERE A.DATAEMIS >= '$id_dtini' AND A.DATAEMIS <= '$id_dtfim'
			$razsocial
			$nronf
			) AS A
			WHERE
			A.DTPAG IS NULL OR A.DTPAG > '$id_dtfim'
			ORDER BY A.RAZAO,A.DATAEMIS");
		
			$sqlsoma = $conectar->query("SELECT
			SUM(A.VALOR) AS SOMAVLRORIGINAL,
			SUM(A.[DESC]) AS SOMADESCONTO,
			SUM(A.JUROS) AS SOMAJUROS,
			SUM(A.PAGO) AS SOMAVLRPAGO
			FROM
			(SELECT * FROM FIN00001.dbo.arqrec AS A WHERE A.DATAEMIS >= '$id_dtini' AND A.DATAEMIS <= '$id_dtfim'
			$razsocial
			$nronf
			) AS A
			WHERE
			A.DTPAG IS NULL OR A.DTPAG > '$id_dtfim'
			");
		
			}elseif($situacao == 'Fechado'){
			$sql1 = $conectar->query("SELECT
			A.CODCLI AS CODCLI,
			A.RAZAO AS RAZAO,
			CONVERT(varchar(10),A.DATAEMIS,103) AS DTEMISSAO,
			CONVERT(varchar(10),A.DATAVENC,103) AS DTVENCIMENTO,
			CONVERT(varchar(10),A.DTPAG,103) AS DTRECEBIMENTO,
			A.VALOR AS VLRORIGINAL,
			A.[DESC] AS DESCONTO,
			A.JUROS AS JUROS,
			A.PAGO AS VLRPAGO,
			A.DUPLIC AS DUPLIC,
			A.[STATUS] AS SITUACAO
			FROM
			(SELECT * FROM FIN00001.dbo.arqrec AS A WHERE A.DATAEMIS >= '$id_dtini' AND A.DATAEMIS <= '$id_dtfim'
			$razsocial
			$nronf
			) AS A
			WHERE
			A.DTPAG <= '$id_dtfim'
			ORDER BY A.RAZAO,A.DATAEMIS");
		
			$sqlsoma = $conectar->query("SELECT
			SUM(A.VALOR) AS SOMAVLRORIGINAL,
			SUM(A.[DESC]) AS SOMADESCONTO,
			SUM(A.JUROS) AS SOMAJUROS,
			SUM(A.PAGO) AS SOMAVLRPAGO
			FROM
			(SELECT * FROM FIN00001.dbo.arqrec AS A WHERE A.DATAEMIS >= '$id_dtini' AND A.DATAEMIS <= '$id_dtfim'
			$razsocial
			$nronf
			) AS A
			WHERE
			A.DTPAG <= '$id_dtfim'
			");
		
			}else{
			$sql1 = $conectar->query("SELECT
			A.CODCLI AS CODCLI,
			A.RAZAO AS RAZAO,
			CONVERT(varchar(10),A.DATAEMIS,103) AS DTEMISSAO,
			CONVERT(varchar(10),A.DATAVENC,103) AS DTVENCIMENTO,
			CONVERT(varchar(10),A.DTPAG,103) AS DTRECEBIMENTO,
			A.VALOR AS VLRORIGINAL,
			A.[DESC] AS DESCONTO,
			A.JUROS AS JUROS,
			A.PAGO AS VLRPAGO,
			A.DUPLIC AS DUPLIC,
			A.[STATUS] AS SITUACAO
			FROM
			FIN00001.dbo.arqrec AS A
			WHERE
			A.DATAEMIS >= '$id_dtini' AND A.DATAEMIS <= '$id_dtfim'
			$razsocial
			$nronf
			ORDER BY A.RAZAO,A.DATAEMIS");
			
			$sqlsoma = $conectar->query("SELECT
			SUM(A.VALOR) AS SOMAVLRORIGINAL,
			SUM(A.[DESC]) AS SOMADESCONTO,
			SUM(A.JUROS) AS SOMAJUROS,
			SUM(A.PAGO) AS SOMAVLRPAGO
			FROM
			FIN00001.dbo.arqrec AS A
			WHERE
			A.DATAEMIS >= '$id_dtini' AND A.DATAEMIS <= '$id_dtfim'
			$razsocial
			$nronf
			");
	}}
	
	echo "<table id=tbordzebr>
		<tr>
			<th width='80px'>COD CLI</th>
			<th width='500px'>RAZAO</th>
			<th width='75px'>EMISSAO</th>
			<th width='75px'>VENCTO</th>
			<th width='75px'>DT PAG</th>
			<th width='100px'>ORIGINAL</th>
			<th width='100px'>DESCONTO</th>
			<th width='100px'>JUROS</th>
			<th width='100px'>VLR PAGO</th>
			<th width='130px'>DUPLICATA</th>
			<th width='60px'>STATUS</th>
		</tr>";

		while
		($relacao1 = $sql1->fetch(PDO::FETCH_ASSOC)) {
            // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
			$vlr_original = number_format($relacao1['VLRORIGINAL'],2,',', '.');
            $vlr_desconto = number_format($relacao1['DESCONTO'],2,',', '.');
			$vlr_juros = number_format($relacao1['JUROS'],2,',', '.');
			$vlr_pago = number_format($relacao1['VLRPAGO'],2,',', '.');

		echo "<tr>
		    <td >$relacao1[CODCLI]</td>
			<td >$relacao1[RAZAO]</td>
			<td >$relacao1[DTEMISSAO]</td>
			<td >$relacao1[DTVENCIMENTO]</td>
			<td >$relacao1[DTRECEBIMENTO]</td>
			<td align=right>$vlr_original</td>
			<td align=right>$vlr_desconto</td>
			<td align=right>$vlr_juros</td>
			<td align=right>$vlr_pago</td>
			<td>$relacao1[DUPLIC]</td>
			<td>$relacao1[SITUACAO]</td>
			</tr>";
	}

	while
		($soma = $sqlsoma->fetch(PDO::FETCH_ASSOC)) {
			$vlr_somaoriginal = number_format($soma['SOMAVLRORIGINAL'],2,',', '.');
            $vlr_somadesconto = number_format($soma['SOMADESCONTO'],2,',', '.');
			$vlr_somajuros = number_format($soma['SOMAJUROS'],2,',', '.');
			$vlr_somapago = number_format($soma['SOMAVLRPAGO'],2,',', '.');
	echo "<tr>
			<th align=left colspan='5'>TOTAIS</th>
			<th align=right>$vlr_somaoriginal</th>
			<th align=right>$vlr_somadesconto</th>
			<th align=right>$vlr_somajuros</th>
			<th align=right>$vlr_somapago</th>
			<th colspan='2'></th>
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
