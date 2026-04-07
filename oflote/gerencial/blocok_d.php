<!DOCTYPE html>
<html>
<head>
<title>Bloco K - Período</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>
<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA<br>
	RELATÓRIO BLOCO K / TRANSFERÊNCIAS DA PRODUÇÃO<br><br>";
	//echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
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
	$id_dtini_str = strtotime($id_dtini);
	$vis_id_dtini = date("d/m/Y", $id_dtini_str);

	$id_dtfim_str = strtotime($id_dtfim);
	$vis_id_dtfim = date("d/m/Y", $id_dtfim_str);
	

try{
// 1. BLOCO DOS REGISTROS "ORDEM DE PRODUÇÃO"	
	$sqlop1 = $conectar->query("SELECT
	CONVERT(varchar(10),A.DATA,103) AS DTTRANSF,
	E.cod_of AS NRO_OF,
	E.cod_lote AS NRO_LOTE,
	A.CODOP AS CODOP,
	A.CODTRANS AS CODTRANSF,
	A.CODPROD AS PRODFINAL,
	A.DESCRICAO AS DESCRICAO,
	B.QUANT AS QTPRODFINAL,
	F.PROD AS COD_MP,
	F.DESCRICAO AS DESCR_MP,
	F.QUANT AS QTDE_MP,	
	G.PREMED AS CUSTOUNIT,
	(G.PREMED*-F.QUANT) AS VLR_CUSTO,	
	C.OBSERVACAO AS OBSTRANSF
	FROM
	FAT00001.dbo.Transf2 AS A
	LEFT JOIN FAT00001.dbo.movto AS B ON A.CONTRENTR=B.CONTROLE
	LEFT JOIN FAT00001.dbo.Transf1 AS C ON A.CODTRANS=C.CODTRANS
	LEFT JOIN pcp_producao.dbo.ordprod AS D ON A.CODTRANS=D.transf_op
	LEFT JOIN pcp_producao.dbo.cad_lote AS E ON E.cod_lote=D.cod_lote
	LEFT JOIN FAT00001.dbo.movto AS F ON A.CODTRANS=F.CONTROLE AND A.DATA=F.DATA
	LEFT JOIN FAT00001.dbo.produto AS G ON F.PROD=G.CODIGO
	WHERE
	A.DATA>='$id_dtini' AND A.DATA<='$id_dtfim'
	ORDER BY E.cod_of,E.cod_lote");
	
// 1. ENQUANTO SELECIONO 1 ITEM
			echo "Período: " . $vis_id_dtini . " a " . $vis_id_dtfim . "<br><br><table id=tbordrel2>
					<tr>
						<td>DATA</td>
						<td>O.F.</td>
						<td>LOTE</td>
						<td>O.P.</td>
						<td>TRANSF</td>
						<td>COD P.F.</td>
						<td>DESCR PROD FINAL</td>
						<td>QTDE P.F.</td>
						<td>COD M.P.</td>
						<td>DESCR MAT PRIMA</td>
						<td>QTDE M.P.</td>
						<td>CUSTO UNIT.</td>
						<td>CUSTO TOTAL</td>
						<td>OBSERVAÇÕES TRANSF</td>
					</tr><tr>";
	while
	($relacao1 = $sqlop1->fetch(PDO::FETCH_ASSOC)){			
		// CASO PRECISE RELACIONAR ALGUM CAMPO PARA VARIÁVEL PHP E PUXAR PARA OUTRA QUERY SQL
		//$id_codop1 = "$relacao1[CODOP]";
		//$id_codtransf1 = "$relacao1[CODTRANSF]";
		$qtde_prodfinal = number_format($relacao1['QTPRODFINAL'],5,',', '.');
		$qtde_mp = number_format($relacao1['QTDE_MP'],5,',', '.');
		$cunit_mp = number_format($relacao1['CUSTOUNIT'],5,',', '.');
		$ctotal_mp = number_format($relacao1['VLR_CUSTO'],5,',', '.');
		
			// 2. RELACIONA ITENS DA ORDEM DE PRODUÇÃO / TRANSFERÊNCIA
				echo "<tr>
						<td 5%>$relacao1[DTTRANSF]</td>
						<td 5%>$relacao1[NRO_OF]</td>
						<td 5%>$relacao1[NRO_LOTE]</td>
						<td 5%>$relacao1[CODOP]</td>
						<td 5%>$relacao1[CODTRANSF]</td>
						<td 10%>$relacao1[PRODFINAL]</td>
						<td 20%>$relacao1[DESCRICAO]</td>
						<td 10% align=right>$qtde_prodfinal</td>
						<td 10%>$relacao1[COD_MP]</td>
						<td 20%>$relacao1[DESCR_MP]</td>
						<td 10% align=right>$qtde_mp</td>
						<td 10% align=right>$cunit_mp</td>
						<td 10% align=right>$ctotal_mp</td>
						<td 20%>$relacao1[OBSTRANSF]</td>
				</tr>";					
	}
	echo "</table>";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
			
?>

</body>
</html>
