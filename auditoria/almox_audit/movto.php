<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Movimento Estoque</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
include_once "../../conexao.php";

$hoje = date('d/m/Y');

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

	$datainicial = date('d/m/Y', strtotime($id_dtini));
	$datafinal = date('d/m/Y', strtotime($id_dtfim));
		echo "RELATÓRIO DE ANÁLISE PRODUTOS - NOTAS SAÍDAS X MOVIMENTAÇÃO ESTOQUE<br>Emissão: ".$hoje."&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Período: ".$datainicial." a ".$datafinal."<br><br>";
		echo "<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";

		
echo "
<form action='movto.php' method='POST'>
<table>
<tr>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_ini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim'/></td>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
<br><br> ";


try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.CODIGO AS PRODUTO,
	A.DESCRICAO AS DESCRICAO,
	B.DESCRICAO AS INVENTARIO,
	A.UNID AS UNIDADE,
	CASE WHEN F.SALDO_INI IS NULL THEN 0 ELSE F.SALDO_INI END AS SALDO_INICIAL,
	CASE WHEN C.QTDE_MOVTO_E IS NULL THEN 0 ELSE C.QTDE_MOVTO_E END AS QTDE_MOV_E,
	CASE WHEN D.QTDE_MOVTO_S IS NULL THEN 0 ELSE D.QTDE_MOVTO_S END AS QTDE_MOV_S,
	((CASE WHEN F.SALDO_INI IS NULL THEN 0 ELSE F.SALDO_INI END) + (CASE WHEN C.QTDE_MOVTO_E IS NULL THEN 0 ELSE C.QTDE_MOVTO_E END) +
	CASE WHEN D.QTDE_MOVTO_S IS NULL THEN 0 ELSE  D.QTDE_MOVTO_S END) AS SALDO_FINAL
	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN (SELECT A.PROD,SUM(A.QUANT) AS SALDO_INI FROM FAT00001.dbo.movto AS A
	WHERE A.DATA < '$id_dtini' GROUP BY A.PROD) AS F ON F.PROD = A.CODIGO
	LEFT JOIN FAT00001.dbo.TipoInv AS B ON A.TIPINV = B.CODIGO,
	(SELECT C.PROD,SUM(C.QUANT) AS QTDE_MOVTO_E FROM FAT00001.dbo.movto AS C 
	WHERE C.TIPO='E' AND C.DATA >= '$id_dtini' AND C.DATA <= '$id_dtfim'
	GROUP BY C.PROD) AS C
	FULL OUTER JOIN (SELECT C.PROD,SUM(C.QUANT) AS QTDE_MOVTO_S FROM FAT00001.dbo.movto AS C 
	WHERE C.TIPO='S' AND C.DATA >= '$id_dtini' AND C.DATA <= '$id_dtfim'
	GROUP BY C.PROD) AS D ON C.PROD = D.PROD

	WHERE
	A.CODIGO = C.PROD OR A.CODIGO = D.PROD

	ORDER BY B.CODIGO,A.CODIGO");
	
	echo "<table id=tbordprod>
	<tr>
        <td>COD PRODUTO</td>
        <td>DESCRICAO</td>
		<td>INVENTARIO</td>
		<td>UNID</td>
		<td>SDO. INICIAL</td>
		<td>ENTRADAS</td>
		<td>SAIDAS</td>
		<td>SDO. FINAL</td>
	</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$qtde_inicial = number_format($linha['SALDO_INICIAL'],5,',', '.');
		$qtde_mov_e = number_format($linha['QTDE_MOV_E'],5,',', '.');
		$qtde_mov_s = number_format($linha['QTDE_MOV_S'],5,',', '.');
		$qtde_final = number_format($linha['SALDO_FINAL'],5,',', '.');

		echo "<tr>
		<td>$linha[PRODUTO]</td>
		<td>$linha[DESCRICAO]</td>
		<td>$linha[INVENTARIO]</td>
		<td>$linha[UNIDADE]</td>
		<td class=tdright>$qtde_inicial</td>
		<td class=tdright>$qtde_mov_e</td>
		<td class=tdright>$qtde_mov_s</td>
		<td class=tdright>$qtde_final</td>
			</tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos<br><br>";

	
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>
</html>
