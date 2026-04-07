<!DOCTYPE html>
<html>
<head>
<title>Relatórios</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
	
	include_once "../../../conexao.php";

	
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

	echo "R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='index.php'><button>Voltar</button></a><br>
	Relatório do Almoxarifado por Data<br><br><br>";

	?>

	<form action="inv_saldo.php" method="POST">
	<table>
	<tr>
	<td><label>Data Inicial: </label></td>
	<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="dt_ini"/></td>
	<td><label>Data Final: </label></td>
	<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" name="dt_fim"/></td>
	<td><input type="submit" value="Buscar"></td>
	<td></td>
	</tr>
	</table>
	</form>
	
	<?php
	echo '* Para corrigir o lote de algum lançamento, clique no código exibido no campo "LOTE"<br>
		* Se desejar acessar o CARDEX de algum item, clique no código exibido no campo "CÓDIGO"';

	//QUERY PESQUISA DE PRODUTOS
	$sql = $conectar->query("SELECT
	A.ID_ALMOX AS MOVTO_ID,
	A.LOTE AS LOTE,
	CONVERT(varchar(10),A.DATA,103) as DATA,
	A.COD_ITEM AS CODIGO,
	B.DESCRICAO AS DESCRICAO,
	B.UNID AS UNIDADE,
	A.QTDE AS QTDE,
	B.PREMED AS CUNIT,
	A.QTDE*B.PREMED AS CITEM,
	A.OBSERVACAO AS OBSERVACAO,
	A.TP_MOVTO AS TIPO
	FROM
	pcp_producao.dbo.almox_movto AS A
	LEFT JOIN FAT00001.dbo.produto AS B ON A.COD_ITEM=B.CODIGO
	WHERE A.DATA >= '$id_dtini' AND A.DATA <= '$id_dtfim'
	ORDER BY A.DATA
	");

echo "<br><br>RELAÇÃO DE ITENS DO LOTE:<br>";

try{
	echo "<table id=tbordzebr>
		<tr>
			<td>LOTE</td>
			<td>DATA</td>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>UN</td>
			<td>QTDE</td>
			<td>C. UNIT</td>
			<td>C. ITEM</td>
			<td>OBSERVAÇÃO</td>
			<td>E/S</td>
		</tr>";

	while($linha = $sql->fetch(PDO::FETCH_ASSOC)) {
		$qtde = number_format($linha['QTDE'],5,',', '.');
		$custo_unit = number_format($linha['CUNIT'],2,',', '.');
		$custo_item = number_format($linha['CITEM'],2,',', '.');		
		echo "<tr>
				<td>";
				if($linha['LOTE'] === ''){
					echo "<a href='formeditcardex.php?id=$linha[MOVTO_ID]'>$linha[MOVTO_ID]</a></td>";
					}else {
						echo "<a href='formeditcardex.php?id=$linha[MOVTO_ID]'>$linha[LOTE]</a></td>";
					}				
		echo	"<td>$linha[DATA]</td>
				<td><a href='../cardex.php?id=$linha[CODIGO]'>$linha[CODIGO]</a></td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[UNIDADE]</td>
				<td align='right'>$qtde</td>
				<td align='right'>$custo_unit</td>
				<td align='right'>$custo_item</td>
				<td>$linha[OBSERVACAO]</td>
				<td>$linha[TIPO]</td>
			</tr>";
	}
	echo "</table>";
	
	echo $sql->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>

</html>
