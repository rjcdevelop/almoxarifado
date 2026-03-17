<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movimento Estoque</title>
	<link href="../css/estilo.css" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background: #f8fbff;
			color: #0b1d3f;
			min-height: 100vh;
			padding: 20px;
		}

		.main-container {
			max-width: 1100px;
			margin: 0 auto;
		}

		.header {
			background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(240, 248, 255, 0.95) 100%);
			border-radius: 20px;
			padding: 40px;
			margin-bottom: 40px;
			border: 1px solid rgba(11, 29, 63, 0.15);
			box-shadow: 0 15px 35px rgba(11, 29, 63, 0.08), 0 5px 15px rgba(11, 29, 63, 0.05);
			display: flex;
			align-items: center;
			justify-content: flex-start;
			gap: 25px;
			position: relative;
			overflow: hidden;
		}

		.header::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: linear-gradient(45deg, transparent 30%, rgba(11, 29, 63, 0.02) 50%, transparent 70%);
			pointer-events: none;
		}

		.logo {
			width: 120px;
			height: auto;
			filter: drop-shadow(0 4px 8px rgba(11, 29, 63, 0.1));
			transition: transform 0.3s ease;
		}

		.logo:hover {
			transform: scale(1.05);
		}

		.company-name {
			background: linear-gradient(45deg, #0b1d3f, #1e3a5f);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
			font-size: 2.2em;
			font-weight: 800;
			letter-spacing: 3px;
			margin-bottom: 15px;
			position: relative;
			z-index: 1;
		}

		.page-title {
			font-size: 1.1em;
			color: rgba(11, 29, 63, 0.7);
			letter-spacing: 1.5px;
			font-weight: 500;
			position: relative;
			z-index: 1;
		}

		.header-text {
			flex: 1;
			text-align: left;
		}

		.back-button {
			position: absolute;
			top: 20px;
			left: 20px;
			padding: 10px 18px;
			background: rgba(11, 29, 63, 0.15);
			border: 1px solid rgba(11, 29, 63, 0.3);
			color: #0b1d3f;
			border-radius: 8px;
			cursor: pointer;
			text-decoration: none;
			font-size: 0.9em;
			transition: all 0.3s ease;
		}

		.back-button:hover {
			background: rgba(11, 29, 63, 0.25);
			border-color: rgba(11, 29, 63, 0.4);
		}

		.search-form {
			display: flex;
			gap: 20px;
			align-items: end;
			margin-bottom: 30px;
			flex-wrap: wrap;
			padding: 20px;
			background: rgba(255, 255, 255, 0.9);
			border-radius: 12px;
			border: 1px solid rgba(11, 29, 63, 0.2);
			box-shadow: 0 4px 12px rgba(11, 29, 63, 0.1);
		}

		.form-group {
			display: flex;
			flex-direction: column;
			gap: 8px;
		}

		.search-form label {
			font-weight: 600;
			color: #0b1d3f;
			font-size: 0.9em;
			letter-spacing: 0.5px;
		}

		.search-form input[type="date"] {
			padding: 10px 12px;
			border: 1px solid rgba(11, 29, 63, 0.3);
			border-radius: 8px;
			font-size: 14px;
			background: #fff;
			transition: all 0.3s ease;
			min-width: 150px;
		}

		.search-form input[type="date"]:focus {
			outline: none;
			border-color: #0b1d3f;
			box-shadow: 0 0 0 3px rgba(11, 29, 63, 0.1);
		}

		.search-form button[type="submit"] {
			padding: 10px 24px;
			background: linear-gradient(135deg, #0b1d3f, #1e3a5f);
			color: #fff;
			border: none;
			border-radius: 8px;
			font-size: 14px;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}

		.search-form button[type="submit"]:hover {
			background: linear-gradient(135deg, #1e3a5f, #0b1d3f);
			transform: translateY(-2px);
			box-shadow: 0 6px 20px rgba(11, 29, 63, 0.3);
		}

		@media (max-width: 768px) {
			.header {
				padding: 30px 20px;
				flex-direction: column;
				text-align: center;
				gap: 15px;
			}

			.logo {
				width: 80px;
				margin-bottom: 0;
			}

			.company-name {
				font-size: 1.5em;
			}

			.page-title {
				font-size: 1em;
			}

			.header-text {
				text-align: center;
			}

			.search-form {
				flex-direction: column;
				align-items: stretch;
				gap: 15px;
			}

			.search-form input[type="date"] {
				min-width: auto;
			}
		}
	</style>
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