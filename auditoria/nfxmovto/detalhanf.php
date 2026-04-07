<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalha NF</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	$hoje = date('d/m/Y');
	echo "RELAÇÃO DE ITENS DA NOTA FISCAL &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Emissão: ".$hoje."<br><br>";
	echo "<input type='button' value='Voltar' onClick='history.go(-1)'>&nbsp &nbsp &nbsp <br><br>";
	
	
	include_once "../../conexao.php";


try{
	//EXECUÇÃO DA INSTRUCAO SQL
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
	$dtnf = filter_var($_GET['dtnf']);

	$consulta = $conectar->query("SELECT
    A.ITEM AS ITEM,
    A.NOTA AS NOTA,
	B.RAZAOCLI AS RAZAO,
    A.PRODUTO AS CODIGO,
    A.DESCR AS DESCRICAO,
    A.UNID AS UNID,
    A.QTDE AS QTDE,
	A.UNIDENTRADA AS UNID2,
	A.QTDENTRADA AS QTDE2,
    (COMPLEMENTO1 + ' / ' + COMPLEMENTO2 + ' / ' + COMPLEMENTO3 + ' / ' + COMPLEMENTO4 + ' / ' + COMPLEMENTO5 + ' / ' + COMPLEMENTO6 + ' / ' + COMPLEMENTO7 + ' / ' + COMPLEMENTO8 + ' / ' +
    COMPLEMENTO9 + ' / ' + COMPLEMENTO10 + ' / ' + COMPLEMENTO11) AS COMPLEMENTO
    FROM FAT00001.dbo.nota2 AS A
	LEFT JOIN FAT00001.dbo.nota1 AS B ON A.NOTA = B.NOTA AND A.CONTROLE = B.CONTROLE
    WHERE A.NOTA='$id'
    ");
	
	echo "<table id=tbordprod>
		<tr>
			<td>ITEM</td>
			<td>NOTA</td>
			<td>RAZAO SOCIAL</td>
			<td>DATA</td>
			<td>CÓDIGO</td>
			<td>DESCRIÇÃO</td>
			<td>UNID</td>
            <td>QUANTIDADE</td>
			<td>UNID (2)</td>
            <td>QUANT (2)</td>
			<td>COMPLEMENTO</td>
		</tr>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

		$qtde_nf = number_format($linha['QTDE'],5,',', '.');
		$qtde_nf2 = number_format($linha['QTDE2'],5,',', '.');

		echo "<tr>
		<td>$linha[ITEM]</td>
		<td>$linha[NOTA]</td>
		<td>$linha[RAZAO]</td>
		<td>$dtnf</td>
        <td>$linha[CODIGO]</td>
        <td>$linha[DESCRICAO]</td>
        <td>$linha[UNID]</td>
		<td>$linha[UNID2]</td>
		<td class=tdright>$qtde_nf</td>
		<td class=tdright>$qtde_nf2</td>
        <td>$linha[COMPLEMENTO]</td>
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
