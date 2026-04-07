<!DOCTYPE html>
<html>

<head>
<title>Receita x Custo</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "R J C DEFESA E AEROESPACIAL LTDA <br> RELATÓRIO DE PRODUTOS POR RECEITA x CUSTO<br><br>";
	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';
	//echo "<a href='formordprod.php'><button>Voltar</button></a><br>";

include_once "../../conexao.php";

$hoje = date('d/m/Y');

if(isset($_POST['id_prod'])){
	$id_prod=filter_var($_POST['id_prod']);
	$codigo_pf = '';
	$descricao_pf = '';
}elseif(isset($_GET['id_prod'])){
	$id_prod=filter_var($_GET['id_prod']);
	$codigo_pf = '';
	$descricao_pf = '';
	}else {
		$id_prod = '';
		$codigo_pf = '';
		$descricao_pf = '';
	}


echo "<form action='receitaxcusto.php' method='POST'>
<table><tr>
<td><label>Informe Código do Poduto: </label></td>
<td><input type='textbox' style='font-size: 10pt; height: 16px; width:200px;' name='id_prod' value='$id_prod'/></td>
<td><input type='submit' value='Buscar'></td>
<td></td>
</tr></table>
</form>
<br>";


try{
// 1. BLOCO DOS REGISTROS "ORDEM DE PRODUÇÃO"
	$sqldescr = $conectar->query("SELECT
	A.CODIGO,
	B.DESCRICAO
	FROM FAT00001.dbo.Compone AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.CODIGO = B.CODIGO
	WHERE A.DATAFIM > '$hoje' AND A.CODIGO = '$id_prod'
	");
	
	while ($relacao2 = $sqldescr->fetch(PDO::FETCH_ASSOC)){
		$codigo_pf = $relacao2['CODIGO'];
		$descricao_pf = $relacao2['DESCRICAO'];
	}

	echo "CUSTO DO MATERIAL COM BASE NA RECEITA DO ITEM <b>$codigo_pf</b> / <b>$descricao_pf</b>:<br><br>";
	$sqlcomp = $conectar->query("SELECT
	A.CODIGO,
	A.MATERIAL,
	A.DESCRICAO,
	A.UNID,
	A.QTDE,
	B.PRECOCUS,
	(A.QTDE * B.PRECOCUS) AS VLRCUSTO,
	B.PREMED,
	(A.QTDE * B.PREMED) AS VLRPRECOMEDIO
	FROM FAT00001.dbo.Compone AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.MATERIAL = B.CODIGO
	WHERE A.DATAFIM > '$hoje' AND A.CODIGO = '$id_prod'
	");
	
// 1. ENQUANTO SELECIONO 1 ITEM
echo "<table id=tbordrel2>
<tr bgcolor='#E0FFFF'>
<td width='180px'>MAT PRIMA</td>
<td width='600px'>DESCRICAO MP</td>
<td width='50px'>UNID</td>
<td width='120px'>QUANTIDADE</td>
<td width='120px'>CUSTO UNIT</td>
<td width='120px'>TOT C. UNIT</td>
<td width='120px'>P. MED UNIT</td>
<td width='120px'>TOT P. MED</td>
</tr>
";

$soma_custo = 0;
$soma_medio = 0;
	while
	($relacao1 = $sqlcomp->fetch(PDO::FETCH_ASSOC)){
		
		//EXEMPLO - CASO PRECISE RELACIONAR ALGUM CAMPO PARA VARIÁVEL PHP E PUXAR PARA OUTRA QUERY SQL
		$qtde_mp = number_format($relacao1['QTDE'],5,',', '.');
		$custo_unit = number_format($relacao1['PRECOCUS'],5,',', '.');
		$custo_tot = number_format($relacao1['VLRCUSTO'],5,',', '.');
		$medio_unit = number_format($relacao1['PREMED'],5,',', '.');
		$medio_tot = number_format($relacao1['VLRPRECOMEDIO'],5,',', '.');
		$soma_custo += number_format($relacao1['VLRCUSTO'],5,'.', '');
		$soma_medio += number_format($relacao1['VLRPRECOMEDIO'],5,'.', '');
			// 2. RELACIONA ITENS DA RECEITA
				echo "<tr>
						<td>$relacao1[MATERIAL]</td>
						<td>$relacao1[DESCRICAO]</td>
						<td>$relacao1[UNID]</td>
						<td align=right>$qtde_mp</td>
						<td align=right>$custo_unit</td>
						<td align=right>$custo_tot</td>
						<td align=right>$medio_unit</td>
						<td align=right>$medio_tot</td>
						</tr>
				";
						}

						$vlr_custo = number_format($soma_custo,5,',', '.');
						$vlr_medio = number_format($soma_medio,5,',', '.');
				echo "<tr bgcolor='#D8BFD8'>
				<td COLSPAN = '5'>TOTAIS GERAIS</td>
				<td align=right>$vlr_custo</td>
				<td></td>
				<td align=right>$vlr_medio</td>
				</tr></table><br><br>";
				

}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>

</body>

</html>
