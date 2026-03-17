<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Nota Fiscal x O.P - RJC</title>
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
			<img src="../img/rjclogo.png" alt="Logo RJC" class="logo">
			<div class="header-text">
				<div class="company-name">R J C DEFESA E AEROESPACIAL</div>
				<div class="page-title">Nota Fiscal x O.P</div>
			</div>
		</div>

<div style="font-size: small">
<?php
	$hoje = date('d/m/Y');
	echo "RELATÓRIO DE ANÁLISE PRODUTOS - NOTAS SAÍDAS X MOVIMENTAÇÃO ESTOQUE<br>Emissão: ".$hoje."<br><br>";
	// removed back button
	
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

?>

<form action="nfxop.php" method="POST" class="search-form">
<div class="form-group">
<label>Data Inicial:</label>
<input type="date" value="<?php echo $id_dtini; ?>" name="dt_ini">
</div>
<div class="form-group">
<label>Data Final:</label>
<input type="date" value="<?php echo $id_dtfim; ?>" name="dt_fim">
</div>
<button type="submit">Buscar</button>
</form>
<br><br>

<?php

try{
/*
//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	B.PRODUTO AS PRODUTO,
	D.DESCRICAO AS DESCRICAO,
	SUM(B.QTDE) AS QTDE_NF_S,
	CASE WHEN E.QTDE_MOVTO_S IS NULL THEN 0 ELSE E.QTDE_MOVTO_S END AS QTDE_MOV_S,	
	CASE WHEN C.QTDE_MOVTO_E IS NULL THEN 0 ELSE C.QTDE_MOVTO_E END AS QTDE_MOV_E,	
	CASE WHEN F.SALDO_INI IS NULL THEN 0 ELSE F.SALDO_INI END AS SALDO_INICIAL,	
	((CASE WHEN F.SALDO_INI IS NULL THEN 0 ELSE F.SALDO_INI END) + (CASE WHEN C.QTDE_MOVTO_E IS NULL THEN 0 ELSE C.QTDE_MOVTO_E END) +
	CASE WHEN E.QTDE_MOVTO_S IS NULL THEN 0 ELSE  E.QTDE_MOVTO_S END) AS SALDO_FINAL
	FROM
	FAT00001.dbo.nota1 AS A,
	FAT00001.dbo.nota2 AS B
	LEFT JOIN (SELECT C.PROD,SUM(C.QUANT) AS QTDE_MOVTO_E FROM FAT00001.dbo.movto AS C 
	WHERE C.TIPO='E' AND C.DATA >= '$id_dtini' AND C.DATA <= '$id_dtfim'
	--DATEADD(MM, DATEDIFF(mm,0,GETDATE()), 0)
	GROUP BY C.PROD) AS C ON B.PRODUTO=C.PROD
	LEFT JOIN (SELECT C.PROD,SUM(C.QUANT) AS QTDE_MOVTO_S FROM FAT00001.dbo.movto AS C 
	WHERE C.TIPO='S' AND
	C.DATA >= '$id_dtini' AND C.DATA <= '$id_dtfim'
	--DATEADD(MM, DATEDIFF(mm,0,GETDATE()), 0)
	GROUP BY C.PROD) AS E ON B.PRODUTO=E.PROD
	LEFT JOIN (SELECT A.PROD,SUM(A.QUANT) AS SALDO_INI FROM FAT00001.dbo.movto AS A
	WHERE A.DATA < '$id_dtini' GROUP BY A.PROD) AS F ON F.PROD=B.PRODUTO,
	FAT00001.dbo.produto AS D
	WHERE
	A.NOTA=B.NOTA
	AND B.PRODUTO=D.CODIGO
	AND A.STATUSNFE IN ('Autorizada','Em Digitação')
	AND A.FORNE = ''
	AND A.EMISSAO >= '$id_dtini' AND A.EMISSAO <= '$id_dtfim'
	--DATEADD(MM, DATEDIFF(mm,0,GETDATE()), 0)''
	AND B.CFOP NOT IN ('5.902','6.902','5.923','6.923','5.551','6.551','7.551')
	GROUP BY B.PRODUTO,D.DESCRICAO,C.QTDE_MOVTO_E,E.QTDE_MOVTO_S,F.SALDO_INI
	ORDER BY B.PRODUTO");
	
	echo "<table id=tbordprod>
		<tr>
			<td>PRODUTO</td>
			<td>DESCRICAO</td>
			<td>QT NOTA</td>
			<td>S.INICIAL</td>
			<td>ENTRADAS</td>
			<td>SAIDAS</td>
			<td>S.FINAL</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

		$qtde_nf_s = number_format($linha['QTDE_NF_S'],5,',', '.');
		$qtde_mov_s = number_format($linha['QTDE_MOV_S'],5,',', '.');
		$qtde_mov_e = number_format($linha['QTDE_MOV_E'],5,',', '.');
		$qtde_inicial = number_format($linha['SALDO_INICIAL'],5,',', '.');
		$qtde_final = number_format($linha['SALDO_FINAL'],5,',', '.');

		echo "<tr>
		<td><a href='movimento.php?id=$linha[PRODUTO]&&dt_ini=$id_dtini&&dt_fim=$id_dtfim'>$linha[PRODUTO]</td>
		<td>$linha[DESCRICAO]</td>
		<td class=tdright>$qtde_nf_s</td>
		<td class=tdright>$qtde_inicial</td>
		<td class=tdright>$qtde_mov_e</td>
		<td class=tdright>$qtde_mov_s</td>
		<td class=tdright>$qtde_final</td>
			</tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos<br><br>";
*/
	//RELACIONAR NOTAS FISCAIS ---------------------------------------

	$consulta_nf = $conectar->query("SELECT
	A.NOTA AS NOTA,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
	B.PRODUTO AS PRODUTO,
	C.DESCRICAO AS DESCRICAO,
	B.CFOP AS CFOP,
	SUM(B.QTDE) AS QTDE_NF_S,
	SUM(B.VLRTOT) AS VLR_ITEM,
	D.QTDE AS QTDEPRODUCAO
	FROM
	FAT00001.dbo.nota1 AS A, FAT00001.dbo.nota2 AS B
	LEFT JOIN (SELECT S1.CODPROD,SUM(S1.QTDE) AS QTDE FROM FAT00001.dbo.CadOp2 AS S1 WHERE S1.DATA >= '$id_dtini' AND S1.DATA <= '$id_dtfim' GROUP BY S1.CODPROD) AS D ON B.PRODUTO = D.CODPROD,
	FAT00001.dbo.produto AS C
	WHERE
	A.NOTA=B.NOTA
	AND B.PRODUTO=C.CODIGO
	AND A.STATUSNFE IN ('Autorizada','Em Digitação')
	AND A.FORNE = ''
	AND A.EMISSAO >= '$id_dtini'
	AND A.EMISSAO <= '$id_dtfim'
	AND B.CFOP NOT IN ('5.902','6.902','5.923','6.923','5.551','6.551','7.551')
	GROUP BY A.NOTA, A.EMISSAO, B.PRODUTO,C.DESCRICAO,B.CFOP,D.QTDE
	ORDER BY B.PRODUTO
	");
	
	echo "<table id=tbordprod>
		<tr>
			<td>N. F.</td>
			<td>DATA</td>
			<td>COD ITEM</td>
			<td>DESCRIÇÃO</td>
			<td>QTDE</td>
			<td>C.F.O.P.</td>
			<td>VR ITEM</td>
			<td>QT O.P.</td>
		</tr>";
	while
	($linha = $consulta_nf->fetch(PDO::FETCH_ASSOC)) {

		$qtde_nf_s = number_format($linha['QTDE_NF_S'],2,',', '.');
		$vlr_item = number_format($linha['VLR_ITEM'],2,',', '.');
		$qtde_prod = number_format($linha['QTDEPRODUCAO'],2,',', '.');

		echo "<tr>
		<td><a href='detalhanf.php?id=$linha[NOTA]&&dtnf=$linha[EMISSAO]'>$linha[NOTA]</a></td>
		<td>$linha[EMISSAO]</td>
		<td><a href='composicao.php?id=$linha[PRODUTO]&&desc=$linha[DESCRICAO]'>$linha[PRODUTO]</a></td>
		<td>$linha[DESCRICAO]</td>
		<td class=tdright>$qtde_nf_s</td>
		<td>$linha[CFOP]</td>
		<td class=tdright>$vlr_item</td>
		<td class=tdright>$qtde_prod</td>
			</tr>";
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