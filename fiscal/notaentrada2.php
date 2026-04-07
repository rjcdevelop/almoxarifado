<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Notas de Entradas</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>
<body>

<?php
include_once "../conexao.php";
//include_once "conexao2.php";
$hoje2 = date('d/m/Y');

echo "RELATÓRIO DE NOTAS FISCAIS DE ENTRADAS - e-FISCAL / ESTOQUE<br>Emissão: ".$hoje2."&nbsp &nbsp &nbsp";
echo "<a href='index.php'><button>Voltar</button></a><br><br>";

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

	echo "<form action='notaentrada2.php' method='POST'>
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

	echo "<br><br>";

try{
	//EXECUCAO SQL ESTOQUE / ALMOXARIFADO
	/*$notasb = $conectar->query("SELECT
	CONVERT(varchar(10),A.EMISSAO,103) AS DTEMISSAO,
	CONVERT(varchar(10),A.ENTRADA,103) AS DTENTRADA,
	A.CONTROLE AS CONTROLE,
	A.NOTA AS NOTA,
	B.CGC AS CNPJ,
	B.RAZAO_SOC AS RAZAO,
	A.NATU AS CFOP
	
	FROM
	FAT00001.dbo.entrada1 AS A,
	FIN00001.dbo.cadfor AS B
	
	WHERE
	A.FORN=B.CODFOR
	AND A.EMISSAO >= '$id_dtini'
	AND A.EMISSAO <= '$id_dtfim'
	ORDER BY A.EMISSAO,A.NOTA");
	*/

	//EXECUÇÃO DA INSTRUCAO SQL EFISCAL
	$notase = $conn->query("SELECT
	A.dtemiss,
	A.dtdocum,
	A.codlanc,
	A.numinici,
	A.cnpj,
	A.vlrcont
	
	FROM e1100.nfentrad AS A

	WHERE
	dtemiss >= '$id_dtini'
	AND dtemiss <= '$id_dtfim'

	ORDER BY A.dtemiss,A.numinici");
	
	echo "<table id=tbordprod>
		<tr>
			<td>EMISSAO</td>
			<td>ENTRADA</td>
			<td>CONTROLE</td>
			<td>No NOTA</td>
			<td>C.N.P.J.</td>
			<td>VLR NOTA</td>
		</tr>";

	while
	($linha = $notase->fetch(PDO::FETCH_ASSOC)){	
		$vlrnota = number_format($linha['vlrcont'],2,',', '.');

		echo "<tr>
		<td>$linha[dtemiss]</td>
		<td>$linha[dtdocum]</td>
		<td>$linha[codlanc]</td>
		<td class=tdright>$linha[numinici]</td>
		<td class=tdright>$linha[cnpj]</td>
		<td class=tdright>$vlrnota</td>
		</tr>";
	}
	echo "</table>";
	
	//echo $consulta->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>
</html>
