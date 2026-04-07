<!DOCTYPE html>
<html>

<head>
<title>Lista de Requisições</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "RELACAO ITENS COMPRADOS - IMOBILIZADO - R J C DEFESA AEROESPACIAL LTDA<br><br>
	<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../conexao.php";
$hoje = date('d/m/Y');


if(isset($_POST['busca'])){
	$busca='1';
	}else {
		$busca='';
	}
if(isset($_POST['dt_ini'])){
	$id_dtini=filter_var($_POST['dt_ini']);
	}else {
		$id_dtini=$hoje;
	}
if(isset($_POST['dt_fim'])){
	$id_dtfim=filter_var($_POST['dt_fim']);
	}else {
		$id_dtfim=$hoje;
	}
				
//echo "Exbir: $filtro <br>";

echo "<form action='imobilizado.php' method='POST'>
<td><label>Data Inicial: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' value='$id_dtini' name='dt_ini'/></td>
<td><label>Data Final: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' value='$id_dtfim' name='dt_fim'/><input type='hidden' value='1' name='busca'/><td><input type='submit' value='Buscar'></td>
</tr></table>
</form><br><br>";

if($busca === '1'){
try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
CONVERT(varchar(10),B.DATA,103) AS DATA,
A.CODIGO,
A.DESCRICAO,
E.DESCRICAO AS LOCALIZACAO,
A.COMPL1,
A.COMPL2,
A.COMPL3,
A.COMPL4,
A.COMPL5,
A.COMPL6,
A.COMPL7,
A.COMPL8,
A.COMPL9,
A.COMPL10,
A.COMPL11,
A.COMPL12,
A.COMPL13,
A.COMPL14,
A.COMPL15,
C.DESCRICAO AS GRUPO,
D.DESCRICAO AS SUBGRUPO,
B.NOTA,
B.RAZAO AS FORNECEDOR,
A.UNID,
B.QUANT AS QUANT
FROM FAT00001.dbo.produto AS A
LEFT JOIN FAT00001.dbo.movto AS B ON A.CODIGO = B.PROD
LEFT JOIN FAT00001.dbo.grupo AS C ON A.GRUPO = C.CODIGO
LEFT JOIN FAT00001.dbo.SubGrupo AS D ON A.SUBGRUPO = D.CODIGO
LEFT JOIN FAT00001.dbo.local AS E ON A.CODLOC = E.CODIGO
WHERE
B.DATA >= '$id_dtini' AND B.DATA <= '$id_dtfim'
AND B.TIPO = 'E'
AND A.GRUPO = '02' AND A.SUBGRUPO IN ('07','08')
	");

echo "<table id=tbordprod>
		<tr>
			<td width='70px'>DATA</td>
			<td width='150px'>CODIGO</td>
			<td width='600px'>DESCRICAO</td>
			<td width='100px'>LOCALIZACAO</td>
			<!-- <td width='70px'>FICHA TÉCNICA</td> -->
			<td width='80px'>SUBGRUPO</td>
			<td width='100px'>NOTA</td>
			<td width='600px'>FORNECEDOR</td>
			<td width='40px'>UNID</td>
			<td width='60px'>QUANT</td>	</tr>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$qtde = number_format($linha['QUANT'],2,',', '.');

		echo "<tr>
				<td>$linha[DATA]</td>
				<td>$linha[CODIGO]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[LOCALIZACAO]</td>
				<td>$linha[SUBGRUPO]</td>
				<td align='right'>$linha[NOTA]</td>
				<td>$linha[FORNECEDOR]</td>
				<td>$linha[UNID]</td>
				<td align='right'>$qtde</td>
			</tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos";
	
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
	
	
}else{	echo 'VAZIO OK';}

?>

</body>

</html>
