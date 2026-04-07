<!DOCTYPE html><html>
<head>
	<title>Movimento BlocoK x Almoxarifado</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	echo "R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp	<br>
	Relatório Movimento do Estoque (Bloco K / Almoxarifado)<br><br>";
	//<a href='index.php'><button>Voltar</button></a><br>

	include_once "../../conexao.php";

	$hoje = date('Y-m-d');

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

echo "<form action='rel_almox_folha.php' method='POST'>
<table>
<tr>
<td><label>Data: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini' value='$id_dtini'/>
<input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$id_dtfim'/></td>
<td></td>
<td></td>
</tr>
<tr>
<td colspan = '4' align = 'center'><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
<br>";

	//QUERY BLOCOK / FOLHAMATIC
	$sql = $conectar->query("SELECT
	B.CODIGO AS CODIGO,
	B.DESCRICAO AS DESCRICAO,
	B.TIPINV AS TIPINV,
	B.UNID AS UNIDADE,
	SUM(TOTENTRADAS) AS QTDE_K_E,
	SUM(TOTSAIDAS) AS QTDE_K_S,
	B.QUANT AS SALDO_K,
	SUM(ALMENTRADAS) AS QTDE_ALM_E,
	SUM(ALMSAIDAS) AS QTDE_ALM_S,
	SUM(S1TOTAL-S2TOTAL) AS SALDO_ALMOX
	FROM
	(SELECT
	K.DATA,
	K.PROD,
	CASE WHEN K.TIPO='E' THEN SUM(K.QUANT) END AS TOTENTRADAS,
	CASE WHEN K.TIPO='S' THEN SUM(K.QUANT) END AS TOTSAIDAS
	FROM
	FAT00001.dbo.movto AS K
	WHERE
	K.DATA>='$id_dtini' AND K.DATA<='$id_dtfim'
	GROUP BY K.DATA, K.PROD,K.TIPO) AS A
	
	
	--UNIAO DA TABELA ALMOX
	FULL JOIN
	(SELECT
	L.DATA,
	L.COD_ITEM,
	CASE WHEN L.TP_MOVTO='E' THEN SUM(L.QTDE) END AS ALMENTRADAS,
	CASE WHEN L.TP_MOVTO='S' THEN SUM(L.QTDE)*-1 END AS ALMSAIDAS,
	SUM(M.S1TOTAL) AS S1TOTAL,
	SUM(N.S2TOTAL) AS S2TOTAL
	FROM
	pcp_producao.dbo.almox_movto AS L
	--JUNTAR SALDO EM ESTOQUE
	LEFT JOIN (SELECT S1.COD_ITEM AS S1ITEM, SUM(S1.QTDE) AS S1TOTAL
	FROM pcp_producao.dbo.almox_movto AS S1
	WHERE S1.TP_MOVTO='E' AND S1.DATA <= '$id_dtfim'
	GROUP BY S1.COD_ITEM) AS M ON M.S1ITEM = L.COD_ITEM
	LEFT JOIN (SELECT S2.COD_ITEM AS S2ITEM, SUM(S2.QTDE) AS S2TOTAL
	FROM pcp_producao.dbo.almox_movto AS S2
	WHERE S2.TP_MOVTO='S' AND S2.DATA <= '$id_dtfim'
	GROUP BY S2.COD_ITEM) AS N ON N.S2ITEM = L.COD_ITEM
	WHERE
	L.DATA>='$id_dtini' AND L.DATA<='$id_dtfim'
	GROUP BY L.DATA, L.COD_ITEM,L.TP_MOVTO) AS C
	ON A.PROD=C.COD_ITEM,
	
	--TABELA PRINCIPAL DE PRODUTOS
	FAT00001.dbo.produto AS B
	--WHERE PRINCIPAL
	WHERE
	A.PROD=B.CODIGO OR C.COD_ITEM=B.CODIGO
	GROUP BY B.CODIGO, B.DESCRICAO, B.TIPINV, B.UNID, B.QUANT, S1TOTAL
	");

try{
	echo "
	<table id=tbordprod>
		<tr>
			<td width='150px'>CODIGO</td>
			<td width='800px'>DESCRIÇÃO</td>
			<td width='40px'>INV</td>
			<td width='40px'>UN</td>
			<td width='110px'>ENT BLOCO K</td>
			<td width='110px'>SAI BLOCO K</td>
			<td width='110px'>SALDO K</td>
			<td width='110px'>ENT ALMOX</td>
			<td width='110px'>SAI ALMOX</td>
			<td width='110px'>SALDO ALMOX</td>
		</tr>";
	while
	($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$qtde_ent_k = number_format($linha['QTDE_K_E'],2,',', '.');
		$qtde_sai_k = number_format($linha['QTDE_K_S'],2,',', '.');
		$saldo_k = number_format($linha['SALDO_K'],2,',', '.');
		$qtde_ent_alm = number_format($linha['QTDE_ALM_E'],2,',', '.');
		$qtde_sai_alm = number_format($linha['QTDE_ALM_S'],2,',', '.');
		$saldo_alm = number_format($linha['QTDE_ALM_S'],2,',', '.');
		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[TIPINV]</td>
				<td>$linha[UNIDADE]</td>
		    	<td align='right'>$qtde_ent_k</td>
				<td align='right'>$qtde_sai_k</td>
				<td align='right'>$saldo_k</td>
				<td align='right'>$qtde_ent_alm</td>
				<td align='right'>$qtde_sai_alm</td>
				<td align='right'>$saldo_alm</td>
			</tr>";
	}
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>
</html>
