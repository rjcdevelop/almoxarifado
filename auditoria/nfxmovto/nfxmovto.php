<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movimento Estoque</title>
	<link href="../css/estilo.css" rel="stylesheet">

</head>

<body>
	<a href="../index.php" class="back-button">← Voltar</a>

	<div class="main-container">
		<div class="header">
			<img src="../img/rjclogo01.png" alt="Logo RJC" class="logo">
			<div class="header-text">
				<div class="company-name">R J C DEFESA E AEROESPACIAL</div>
				<div class="page-title">Nota Fiscal x Movimento</div>
			</div>
		</div>

<div style="font-size: small">
<?php
	$hoje = date('d/m/Y');
	echo "RELATÓRIO DE ANÁLISE PRODUTOS - NOTAS SAÍDAS X MOVIMENTAÇÃO ESTOQUE<br>Emissão: ".$hoje."<br><br>";
	// removed back button
	
	include_once "../../conexao.php";

if(isset($_GET['dt_ini'])){
	$id_dtini=filter_var($_GET['dt_ini']);
	}else {
		$id_dtini='';
	}

	if(isset($_GET['dt_fim'])){
		$id_dtfim=filter_var($_GET['dt_fim']);
		}else {
			$id_dtfim='';
		}


echo "<form action='nfxmovto.php' method='GET' class='search-form'>
<div class='form-group'>
<label>Data Inicial:</label>
<input type='date' value='$id_dtini' name='dt_ini'>
</div>
<div class='form-group'>
<label>Data Final:</label>
<input type='date' value='$id_dtfim' name='dt_fim'>
</div>
<button type='submit'>Buscar</button>
</form>
<br><br>";

try{
	//RELACIONAR NOTAS FISCAIS ---------------------------------------

	$consulta_nf = $conectar->query("SELECT
	A.NOTA AS NOTA,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
	B.ITEM AS ITEM,
	B.PRODUTO AS PRODUTO,
	P.DESCRICAO AS DESCRICAO,
	B.CFOP AS CFOP,
	SUM(B.QTDE) AS QTDE_NF_S,
	SUM(B.VLRTOT) AS VLR_ITEM,
	CONVERT(varchar(10),D.DATA,103) AS EMISSAODT,
	D.QTDE AS QTDEPRODUCAO,
	CASE WHEN D.NUM_OP IS NULL THEN 'Vincular' ELSE D.NUM_OP END AS NUM_OP
	
	FROM
	FAT00001.dbo.produto AS P, FAT00001.dbo.nota1 AS A, FAT00001.dbo.nota2 AS B

	LEFT JOIN (SELECT
		S1.num_op AS NUM_OP,
		S1.num_nf AS NUM_NF,
		S1.item_nf AS ITEM_NF,
		S1.codproduto_op AS CODPROD,
		S1.qtde_op AS QTDE,
		S2.DATA
		FROM  pcp_producao.dbo.vinc_nf_op AS S1
		LEFT JOIN FAT00001.dbo.CadOp2 AS S2 ON S1.num_op = S2.CODIGO AND S1.codproduto_op = S2.CODPROD
		WHERE S2.DATA >= '$id_dtini' AND S2.DATA <= '$id_dtfim') AS D ON B.NOTA = D.NUM_NF AND B.PRODUTO = D.CODPROD AND B.ITEM = D.ITEM_NF
	
	WHERE
	A.NOTA=B.NOTA
	AND B.PRODUTO=P.CODIGO
	AND A.STATUSNFE IN ('Autorizada','Em Digitação')
	AND A.FORNE = ''
	AND A.EMISSAO >= '$id_dtini'
	AND A.EMISSAO <= '$id_dtfim'
	AND B.CFOP NOT IN ('5.902','6.902','5.923','6.923','5.551','6.551','7.551','5.201','6.201','5.556','5.903','6.903')
	GROUP BY A.NOTA, A.EMISSAO, B.ITEM, B.PRODUTO, P.DESCRICAO, B.CFOP, D.QTDE, D.NUM_OP, D.DATA
	ORDER BY A.EMISSAO, A.NOTA, B.ITEM, B.PRODUTO
	");
	
	echo "<table id=tbordprod>
		<tr>
			<td>N. F.</td>
			<td>DATA</td>
			<td>ITEM</td>
			<td>CODIGO</td>
			<td>DESCRIÇÃO</td>
			<td>QTDE</td>
			<td>C.F.O.P.</td>
			<td>VR ITEM</td>
			<td>DATA O.P.</td>
			<td>QT O.P.</td>
			<td>NUM OP</td>
		</tr>";
	while
	($linha = $consulta_nf->fetch(PDO::FETCH_ASSOC)) {

		$qtde_nf_s = number_format($linha['QTDE_NF_S'],2,',', '.');
		$vlr_item = number_format($linha['VLR_ITEM'],2,',', '.');
		$qtde_prod = number_format($linha['QTDEPRODUCAO'],2,',', '.');

		echo "<tr>
		<td><a href='detalhanf.php?id=$linha[NOTA]&&dtnf=$linha[EMISSAO]'>$linha[NOTA]</a></td>
		<td>$linha[EMISSAO]</td>
		<td>$linha[ITEM]</td>
		<td><a href='composicao.php?id=$linha[PRODUTO]&&desc=$linha[DESCRICAO]'>$linha[PRODUTO]</a></td>
		<td>$linha[DESCRICAO]</td>
		<td class=tdright>$qtde_nf_s</td>
		<td>$linha[CFOP]</td>
		<td class=tdright>$vlr_item</td>
		<td>$linha[EMISSAODT]</td>
		<td class=tdright>$qtde_prod</td>";

		if($linha["NUM_OP"] != "Vincular"){
			echo "<td>$linha[NUM_OP]";
		}else {
			echo "<td><a href='formvincular.php?nota=$linha[NOTA]&item=$linha[ITEM]&prod=$linha[PRODUTO]&dt_ini=$id_dtini&dt_fim=$id_dtfim'>$linha[NUM_OP]</a>";
		}
		echo "</td>";
	}
	echo "</table>";
	echo $consulta_nf->rowCount() . " Registros Exibidos<br><br>";

}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</div>
</div>
</body>
</html>