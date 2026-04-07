<!DOCTYPE html>
<html>

<head>
<title>Integração Contábil</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA <br> RELATÓRIO PARA INTEGRAÇÃO FINANCEIRO / CONTÁBIL<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	//echo "<a href='formordprod.php'><button>Voltar</button></a><br><br>";

	include_once "../conexao.php";

	if(isset($_POST['empresa'])){
		$empresa=filter_var($_POST['empresa']);
		$id_dtini=filter_var($_POST['dt_ini']);
		$id_dtfim=filter_var($_POST['dt_fim']);

		echo "<form action='financeiro.php' method='POST'>
		<table>
		<tr>
		<td><label>Empresa: </label></td>
		<td><input type='text' style='font-size: 10pt; height: 16px; width:80px;' name='empresa'/></td>
		<td><label>Data Inicial: </label></td>
		<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini'/></td>
		<td><label>Data Final: </label></td>
		<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim'/></td>
		<td><input type='submit' value='Buscar'></td>
		<td></td>
		</tr>
		</table>
		</form>
		<br><br>";

try{
	// 1. BLOCO DOS REGISTROS "VENDAS / FATURAMENTO BASE FATUMATIC"
	if($empresa == '0001'){
		$sql1 = $conectar->query("SELECT
		A.CODBAN AS CODBANCO,
		CONVERT(varchar(10),A.DATA,103) AS DATA,
		A.TIPO AS TIPOFIN,
		B.TIPO AS TIPOCONT,
		A.NUMDOC AS DOCUMENTO,
		A.HISTORICO AS HISTFIN,
		A.VALOR AS VALOR,
		A.CREDITO AS CRED_FIN,
		B.CCP AS CRED_CONT,
		CASE WHEN A.CREDITO=B.CCP THEN C.PDES ELSE '** REVER **' END AS SIT_CREDITO,
		A.DEBITO AS DEB_FIN,
		B.CTB AS DEB_CONT,
		CASE WHEN A.DEBITO=B.CTB THEN D.PDES ELSE '** REVER **' END AS SIT_DEBITO
		FROM
		FIN00001.dbo.arqban AS A
		FULL JOIN FIN00001.dbo.contabil AS B ON A.DATA=B.DATA AND A.NUMDOC = B.DOCUM
		LEFT JOIN (SELECT * FROM FIN00001.dbo.plano) AS C ON A.CREDITO = C.PCOD
		LEFT JOIN (SELECT * FROM FIN00001.dbo.plano) AS D ON A.DEBITO = D.PCOD
		WHERE
		A.DATA >= '$id_dtini' AND A.DATA <= '$id_dtfim'
		AND B.TIPO NOT IN ('CP','CR','P1','P2','P2','P3','P4','P5','R1','R2','R3','R4','R5')
		AND B.TIPCONTA NOT IN ('R')
		ORDER BY A.DATA,B.DOCUM");
	}else{
		$sql1 = $conectar->query("SELECT
		A.CODBAN AS CODBANCO,
		CONVERT(varchar(10),A.DATA,103) AS DATA,
		A.TIPO AS TIPOFIN,
		B.TIPO AS TIPOCONT,
		A.NUMDOC AS DOCUMENTO,
		A.HISTORICO AS HISTFIN,
		A.VALOR AS VALOR,
		A.CREDITO AS CRED_FIN,
		B.CCP AS CRED_CONT,
		CASE WHEN A.CREDITO=B.CCP THEN C.PDES ELSE '** REVER **' END AS SIT_CREDITO,
		A.DEBITO AS DEB_FIN,
		B.CTB AS DEB_CONT,
		CASE WHEN A.DEBITO=B.CTB THEN D.PDES ELSE '** REVER **' END AS SIT_DEBITO
		FROM
		FIN00150.dbo.arqban AS A
		FULL JOIN FIN00150.dbo.contabil AS B ON A.DATA=B.DATA AND A.NUMDOC = B.DOCUM
		LEFT JOIN (SELECT * FROM FIN00150.dbo.plano) AS C ON A.CREDITO = C.PCOD
		LEFT JOIN (SELECT * FROM FIN00150.dbo.plano) AS D ON A.DEBITO = D.PCOD
		WHERE
		A.DATA >= '$id_dtini' AND A.DATA <= '$id_dtfim'
		AND B.TIPO NOT IN ('CP','CR','P1','P2','P2','P3','P4','P5','R1','R2','R3','R4','R5')
		AND B.TIPCONTA NOT IN ('R')
		ORDER BY A.DATA,B.DOCUM");
	}
	

	echo "Movimento Financeiro entre ".date('d/m/Y', strtotime($id_dtini))." a ".date('d/m/Y', strtotime($id_dtfim))."<br><br>";

	echo "<table id=tbordzebr>
		<tr>
			<th width='30px' scope='col'>BCO</th>
			<th width='70px'>DATA</th>
			<th width='55px'>T FIN</th>
			<th width='55px'>T CON</th>
			<th width='120px'>DOCUMENTO</th>
			<th width='500px'>HISTÓRICO FINANCEIRO</th>
			<th width='100px'>VALOR</th>
			<th width='80px'>CRED FIN</th>
			<th width='80px'>CRED CON</th>
			<th width='250px'>SIT. CREDITO/DESCR</th>
			<th width='80px'>DEB FIN</th>
			<th width='80px'>DEB CON</th>
			<th width='250px'>SIT. CREDITO/DESCR</th>
		</tr>";

		while
		($relacao1 = $sql1->fetch(PDO::FETCH_ASSOC)) {
            // VARIAVEIS CONVERTIDAS EM NUMERO DECIMAL COM SEPARADOR DE MILHAR DOS VALORES OBTIDOS PELO SQL
			$vlr_doc = number_format($relacao1['VALOR'],2,',', '.');
            
		echo "<tr>
			<td >$relacao1[CODBANCO]</td>
			<td >$relacao1[DATA]</td>
			<td >$relacao1[TIPOFIN]</td>
			<td >$relacao1[TIPOCONT]</td>
			<td >$relacao1[DOCUMENTO]</td>
			<td >$relacao1[HISTFIN]</td>
			<td align=right>$vlr_doc</td>
			<td>$relacao1[CRED_FIN]</td>
			<td>$relacao1[CRED_CONT]</td>
			<td>$relacao1[SIT_CREDITO]</td>
			<td>$relacao1[DEB_FIN]</td>
			<td>$relacao1[DEB_CONT]</td>
			<td>$relacao1[SIT_DEBITO]</td>
			</tr>";
	}
	echo "</table>";
	
	echo "<br><br>";
	
}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}

		}else {

			echo "<form action='financeiro.php' method='POST'>
			<table>
			<tr>
			<td><label>Empresa: </label></td>
			<td><input type='text' style='font-size: 10pt; height: 16px; width:80px;' name='empresa'/></td>
			<td><label>Data Inicial: </label></td>
			<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini'/></td>
			<td><label>Data Final: </label></td>
			<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim'/></td>
			<td><input type='submit' value='Buscar'></td>
			<td></td>
			</tr>
			</table>
			</form>
			<br><br>";
			}

?>
</body>
</html>
