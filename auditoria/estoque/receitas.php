<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Produtos vs Receitas</title>
	<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	$hoje = date('d/m/Y');
	
	include_once "../../conexao.php";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
    
	$consulta = $conectar->query("SELECT
	A.CODIGO AS CODIGO,
	A.DESCRICAO AS DESCRICAO,
	B.CODIGO AS RECEITA
	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN FAT00001.dbo.Compone AS B ON A.CODIGO = B.CODIGO
	WHERE A.GRUPO = '01'
	GROUP BY A.CODIGO,A.DESCRICAO,B.CODIGO
	ORDER BY A.CODIGO
    ");
	
	echo "<table id=tbordprod>
		<tr>
			<td>COD COMPONENTE</td>
			<td>DESCRIÇÃO</td>
			<td>RECEITA</td>
		</tr>";
	
	
	echo "<b>CADASTRO DE PRODUTOS E RECEITAS</b> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp Emissão: ".$hoje."<br>";
	echo "<input type='button' value='Voltar' onClick='history.go(-1)'>&nbsp &nbsp &nbsp <br><br>";	
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		if ($linha['RECEITA'] <> NULL) {
			$receita = "<a href='../nfxmovto/composicao.php?id=$linha[RECEITA]&&desc=$linha[DESCRICAO]'>Receita</a>";
		}else{
			$receita = "N/C";
		}
		/*<td></td>*/
		echo "<tr>
		<td>$linha[CODIGO]</td>
        <td>$linha[DESCRICAO]</td>
		<td>$receita</td>
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
