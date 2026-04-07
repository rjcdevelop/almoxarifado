<!DOCTYPE html>
<html>

<head>
<title>Cadastro de Produtos</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	$hoje = date('d/m/Y');

	if(isset($_POST['dt_fim'])){
		$datafim = filter_var($_POST['dt_fim']);
		}else{
			$datafim = date('Y-m-d');
		}


	echo "R J C DEFESA E AEROESPACIAL LTDA<br>Cadastro de Produtos - Saldo Atual/Data: ". $hoje."<br><br>";
	echo "<a href='../index.php'><button>Voltar</button></a><br><br>";	

include_once "../../conexao.php";

echo "<br>$datafim<br>";

echo "<form action='index.php' method='POST'>
<table>
<tr>
<td><label>Data do inventário: </label></td>
<td><input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$datafim'/>
<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
<br>";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.CODIGO AS PROD,
	A.GRUPO AS GRUPO,
	A.UNID AS UNID,
	B.DESCRICAO AS SUBGRUPO,
	A.DESCRICAO AS DESCRICAO,
	A.TIPINV AS TIPOINV,

	A.QUANT AS SALDOCONT,
	C.SOMAQUANT AS SALDOMOVTO,
	(A.QUANT - C.SOMAQUANT) AS DIFERENCA
	
	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN(SELECT SB.CODIGO,SB.DESCRICAO FROM FAT00001.dbo.SubGrupo AS SB) AS B ON A.SUBGRUPO=B.CODIGO
	LEFT JOIN(SELECT SM.PROD,SUM (SM.QUANT) AS SOMAQUANT FROM FAT00001.dbo.movto AS SM WHERE SM.DATA <= '$datafim' GROUP BY SM.PROD) AS C ON A.CODIGO=C.PROD
	WHERE
	A.ATIVO='Sim'
	GROUP BY A.CODIGO,A.GRUPO,A.UNID,B.DESCRICAO,A.DESCRICAO,A.TIPINV,A.QUANT,C.SOMAQUANT
	ORDER BY DIFERENCA DESC, A.GRUPO,B.DESCRICAO,A.CODIGO
		");

	echo "<table id=tbordprod>
		<tr>
			<td width='220px'>CODIGO</td>
			<td width='800px'>DESCRIÇÃO</td>
			<td width='200px'>GRUPO</td>
			<td width='40px'>INV</td>
			<td width='35px'>UN</td>
			<td width='110px'>SD CONT</td>
            <td width='110px'>SD MOVTO</td>
            <td width='110px'>DIFERENÇA</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		$saldocont = number_format($linha['SALDOCONT'],5,',', '.');
		$saldomovto = number_format($linha['SALDOMOVTO'],5,',', '.');
		$diferenca = number_format($linha['DIFERENCA'],5,',', '.');
		echo "<tr>
				<td>$linha[PROD]</td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[SUBGRUPO]</td>
				<td>$linha[TIPOINV]</td>
				<td>$linha[UNID]</td>
				<td class=tdright>$saldocont</td>
                <td class=tdright>$saldomovto</td>
                <td class=tdright>$diferenca</td>
				";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>
</html>
