<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Receita Detalhada</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	$hoje = date('d/m/Y');
	$hoje1 = date('Y-m-d');
	$hoje2 = date('Y-m-d', strtotime('-180 days'));
	
	include_once "../../conexao.php";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
    $id = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
	$desc = filter_var($_GET['desc'], FILTER_SANITIZE_STRING);

	$consulta = $conectar->query("SELECT
	A.CODIGO AS PRODFINAL,
	A.MATERIAL AS COMPONENTE,
	A.DESCRICAO AS DESCRICAO,
	A.UNID AS UNID,
	A.QTDE AS QTDE,
	A.DATAINI AS DATAINI,
	A.DATAFIM AS DATAFIM
	FROM FAT00001.dbo.Compone AS A
	WHERE A.DATAFIM > GETDATE() AND A.CODIGO='$id'
	ORDER BY A.MATERIAL
    ");
	
	echo "<table id=tbordprod>
		<tr>
			<td>COD COMPONENTE</td>
			<td>DESCRIÇÃO</td>
			<td>UNIDADE</td>
			<td>QUANTIDADE</td>
			<td>RECEITA</td>
		</tr>";
	
	
	echo "<b>RELAÇÃO DE ITENS DA RECEITA / COMPOSIÇÃO DO PRODUTO</b> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Emissão: ".$hoje."<br><br>";
	echo "Material Produzido: <a href='movimento.php?id=$id&&dt_ini=$hoje2&&dt_fim=$hoje1'>$id</a>   /  ".$desc."<br><br>";

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

		$qtde = number_format($linha['QTDE'],5,',', '.');

		echo "<tr>
		<td><a href='movimento.php?id=$linha[COMPONENTE]&&dt_ini=$hoje2&&dt_fim=$hoje1'>$linha[COMPONENTE]</a></td>
        <td>$linha[DESCRICAO]</td>
        <td>$linha[UNID]</td>
		<td>$qtde</td>
		<td><a href='composicao.php?id=$linha[COMPONENTE]&&desc=$linha[DESCRICAO]'>Composição</a></td>
        </tr>";
	}
	echo "</table>";
	
	echo $consulta->rowCount() . " Registros Exibidos<br><br>";

	echo "<input type='button' value='Voltar' onClick='history.go(-1)'>&nbsp &nbsp &nbsp <br><br>";

}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>
</html>
