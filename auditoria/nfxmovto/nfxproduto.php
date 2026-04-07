<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Vendas por Produto</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>
<div style="font-size: small">
<?php
	$hoje = date('d/m/Y');
	echo "R J C DEFESA E AEROESPACIAL LTDA <br>
	RELATÓRIO DE VENDAS POR PRODUTOS - NOTAS SAÍDAS<br>Emissão: ".$hoje."<br><br>";
	echo "<a href='../index.php'><button>Voltar</button></a>&nbsp &nbsp &nbsp <br><br>";
	
	
	include_once "../../conexao.php";

if(isset($_POST['dt_ini']) && ($_POST['dt_fim']) && ($_POST['id_prod'])){
	$id_dtini=filter_var($_POST['dt_ini']);
	$id_dtfim=filter_var($_POST['dt_fim']);
	$id_prod=filter_var($_POST['id_prod']);
	?>
	<form action="nfxproduto.php" method="POST">
	<table>
	<tr>
	<td><label>Data Inicial: </label></td>
	<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" value="<?php echo $id_dtini; ?>" name="dt_ini"/></td>
	<td><label>Data Final: </label></td>
	<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" value="<?php echo $id_dtfim; ?>" name="dt_fim"/></td>
	<td><label>Cód. Produto: </label></td>
	<td><input type="textbox" style="font-size: 10pt; height: 16px; width:200px;" value="<?php echo $id_prod; ?>" name="id_prod"/></td>
	<td><input type="submit" value="Buscar"></td>
	</tr>
	</table>
	</form>
	<br><br>
	<?php
try{

	//RELACIONAR NOTAS FISCAIS ---------------------------------------

	$consulta_nf = $conectar->query("SELECT
	A.NOTA AS NOTA,
	CONVERT(varchar(10),A.EMISSAO,103) AS EMISSAO,
	A.RAZAOCLI AS RAZAO,
	D.CL_CID AS CIDADE,
	D.CL_UF AS UF,
	B.PRODUTO AS PRODUTO,
	C.DESCRICAO AS DESCRICAO,
	B.CFOP AS CFOP,
	SUM(B.QTDE) AS QTDE_NF_S,
	SUM(B.VLRTOT) AS VLR_ITEM
	FROM
	FAT00001.dbo.nota1 AS A
	LEFT JOIN FIN00001.dbo.cadcli AS D ON D.CL_CODIGO = A.CLIENTE, FAT00001.dbo.nota2 AS B, FAT00001.dbo.produto AS C
	WHERE
	A.NOTA=B.NOTA
	AND B.PRODUTO=C.CODIGO
	AND A.STATUSNFE IN ('Autorizada','Em Digitação')
	AND A.EMISSAO >= '$id_dtini'
	AND A.EMISSAO <= '$id_dtfim'
	AND B.PRODUTO = '$id_prod'
	AND B.CFOP IN ('5.101','6.101','5.102','6.102','6.107','6.107','5.124','6.124','5.125','6.125','5.118','6.118','7.101','7.102','7.127')
	GROUP BY A.NOTA, A.EMISSAO, A.RAZAOCLI,D.CL_CID,D.CL_UF,B.PRODUTO,C.DESCRICAO,B.CFOP
	ORDER BY A.EMISSAO
	");
	
	echo "<table id=tbordprod>
		<tr>
			<td>DATA</td>
			<td>N. F.</td>
			<td>RAZAO SOCIAL</td>
			<td>CIDADE</td>
			<td>UF</td>
			<td>COD ITEM</td>
			<td>DESCRIÇÃO</td>
			<td>QTDE</td>
			<td>VR ITEM</td>
			<td>C.F.O.P.</td>
		</tr>";
	while
	($linha = $consulta_nf->fetch(PDO::FETCH_ASSOC)) {

		$qtde_nf_s = number_format($linha['QTDE_NF_S'],2,',', '.');
		$vlr_item = number_format($linha['VLR_ITEM'],2,',', '.');

		echo "<tr>
		<td>$linha[EMISSAO]</td>
		<td>$linha[NOTA]</td>
		<td>$linha[RAZAO]</td>
		<td>$linha[CIDADE]</td>
		<td>$linha[UF]</td>
		<td>$linha[PRODUTO]</td>
		<td>$linha[DESCRICAO]</td>
		<td class=tdright>$qtde_nf_s</td>
		<td class=tdright>$vlr_item</td>
		<td>$linha[CFOP]</td>
			</tr>";
	}
	echo "</table>";
	echo $consulta_nf->rowCount() . " Registros Exibidos<br><br>";

}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

	}else {
		$id_dtini='';
		$id_dtfim='';
		$id_prod='';
		?>
		<form action="nfxproduto.php" method="POST">
		<table>
		<tr>
		<td><label>Data Inicial: </label></td>
		<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" value="<?php echo $id_dtini; ?>" name="dt_ini"/></td>
		<td><label>Data Final: </label></td>
		<td><input type="date" style="font-size: 10pt; height: 16px; width:150px;" value="<?php echo $id_dtfim; ?>" name="dt_fim"/></td>
		<td><label>Cód. Produto: </label></td>
		<td><input type="textbox" style="font-size: 10pt; height: 16px; width:200px;" name="id_prod"/></td>
		<td><input type="submit" value="Buscar"></td>
		</tr>
		</table>
		</form>
		<br><br>

		<?php
		}


?>
</div>
</body>
</html>
