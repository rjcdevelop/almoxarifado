<!DOCTYPE html>
<html>

<head>
<title>Relação de Clientes</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	echo "CADASTRAR O.F. E LOTE - R J C DEFESA E AEROESPACIAL LTDA<br><br>";
	echo "<a href='../index.php'><button>Voltar</button></a><br><br>";
//	echo '<input type="button" value="Voltar" onClick="history.go(-1)"><br><br>';

include_once "../../../conexao.php";

if(isset($_POST['busca'])){
	$busca = filter_var($_POST['busca']);
	}else {
	$busca = '';
	}
if(isset($_POST['select'])){
	$select = filter_var($_POST['select']);
	}else {
	$select = '';
	}

	//echo "<br>Consulta ativa: $select<br>";

if ($select === 'cliente'){
echo "<form action='index.php' method='POST'>
<td>Registro:</td>
<td>	<input type='radio' name='select' value= 'cod_of'>O.F.</td>
		<input type='radio' name='select' value='cliente' checked>Cliente</td>
		<input type='radio' name='select' value='todos'>Todos</td>	
<table><tr><td><label>Localizar / Pesquisar: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' value='$busca' name='busca'/></td>
<td><input type='submit' value='Buscar'></td>
</tr></table>
</form><br><br>";

try{	
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.CL_CODIGO AS 'CODIGO',
	A.CL_RAZ_SOC AS 'RAZAO',
	A.CL_CID AS 'CIDADE',
	A.CL_UF AS 'UF',
	A.CL_CGC AS 'CNPJ'
	FROM
	FIN00001.dbo.cadcli AS A
	WHERE A.CL_RAZ_SOC LIKE '%$busca%' OR A.CL_CGC LIKE '%$busca%'
	ORDER BY
	A.CL_CODIGO
	");
	
	echo "<table id=tbordzebr>
		<tr>
			<td>Código</td>
			<td>Razão Social</td>
			<td>Cidade</td>
			<td>UF</td>
			<td>C.N.P.J.</td>
			<td>Cadastrar O.F.</td>
			<td>Listar O.F.</td>
		</tr>";
	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		echo "<tr>
				<td>$linha[CODIGO]</td>
				<td>$linha[RAZAO]</td>
				<td>$linha[CIDADE]</td>
				<td>$linha[UF]</td>
				<td>$linha[CNPJ]</td>
				<td><a href='formcadastrarof.php?id=$linha[CODIGO]'>Cadastrar</td>
				<td><a href='listarof.php?id=$linha[CODIGO]'>Listar</td>
			</tr>";
	}
	echo "</table>";
	echo $consulta->rowCount() . " Registros Exibidos";
}catch(PDOExceprion $e){
	echo $e->getMessasge();
}
}elseif($select === 'cod_of' && $busca != ''){
	echo "<form action='index.php' method='POST'>
<td>Registro:</td>
<td>	<input type='radio' name='select' value= 'cod_of' checked>O.F.</td>
		<input type='radio' name='select' value='cliente'>Cliente</td>
		<input type='radio' name='select' value='todos'>Todos</td>	
<table><tr><td><label>Localizar / Pesquisar: </label></td>
<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' value='$busca' name='busca'/></td>
<td><input type='submit' value='Buscar'></td>
</tr></table>
</form><br><br>";
	try{
		//EXECUÇÃO DA INSTRUCAO SQL
		$consulta = $conectar->query("SELECT
		A.cod_of AS CODIGOOF,
		A.codcli AS CODIGOCLI,
		A.observ1 AS OBSERVACAO1,
		CONVERT(varchar(10),A.prazo_of,103) AS PRAZOOF,
		B.CL_RAZ_SOC AS RAZAO,
		C.QTDE_LOTE AS QT_LOTE
	
		FROM
		pcp_producao.dbo.cad_of AS A
		LEFT JOIN FIN00001.dbo.cadcli AS B ON A.codcli=B.CL_CODIGO
		LEFT JOIN (SELECT LT.cod_of AS CODOF,COUNT(LT.cod_lote) AS QTDE_LOTE FROM pcp_producao.dbo.cad_lote AS LT GROUP BY LT.cod_of,LT.cod_of) AS C ON A.cod_of=C.CODOF
	
		WHERE
		A.cod_of = '$busca'
		ORDER BY
		A.codcli,A.cod_of
		");
		
		echo "<table id=tbordzebr>
			<tr>
				<td>Código</td>
				<td>Razão Social</td>
				<td>No O.F.</td>
				<td>Prazo O.F.</td>
				<td>Observações</td>
				<td>Lotes</td>
			</tr>";
		while
		($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr>
					<td>$linha[CODIGOCLI]</td>
					<td>$linha[RAZAO]</td>
					<td><a href='formcadastrarlote.php?id=$linha[CODIGOOF]'>$linha[CODIGOOF]</td>
					<td>$linha[PRAZOOF]</td>
					<td>$linha[OBSERVACAO1]</td>
					<td>$linha[QT_LOTE]</td>
				</tr>";
		}
		echo "</table>";
		
		if($consulta->rowCount() === 0){
			echo "Deseja Cadastrar o lote?<BR>Localize o cliente para opções de cadastro";
		}else{
		echo $consulta->rowCount() . " Registros Exibidos";
		}
		
	}catch(PDOExceprion $e){
		echo $e->getMessasge();
	}
}else{
	echo "<form action='index.php' method='POST'>
	<td>Registro:</td>
	<td>	<input type='radio' name='select' value= 'cod_of'>O.F.</td>
			<input type='radio' name='select' value='cliente'>Cliente</td>
			<input type='radio' name='select' value='todos' checked>Todos</td>

	<table><tr><td><label>Localizar / Pesquisar: </label></td>
	<td><input type='text' style='font-size: 10pt; height: 16px; width:300px;' value='$busca' name='busca'/></td>
	<td><input type='submit' value='Buscar'></td>
	</tr></table>
	</form><br><br>";
	try{
		//EXECUÇÃO DA INSTRUCAO SQL
		$consulta = $conectar->query("SELECT
		A.cod_of AS CODIGOOF,
		A.codcli AS CODIGOCLI,
		A.observ1 AS OBSERVACAO1,
		CONVERT(varchar(10),A.prazo_of,103) AS PRAZOOF,
		B.CL_RAZ_SOC AS RAZAO,
		C.QTDE_LOTE AS QT_LOTE
	
		FROM
		pcp_producao.dbo.cad_of AS A
		LEFT JOIN FIN00001.dbo.cadcli AS B ON A.codcli = B.CL_CODIGO
		LEFT JOIN (SELECT LT.cod_of AS CODOF,COUNT(LT.cod_lote) AS QTDE_LOTE FROM pcp_producao.dbo.cad_lote AS LT GROUP BY LT.cod_of,LT.cod_of) AS C ON A.cod_of=C.CODOF
		
		WHERE A.cod_of != ''
		ORDER BY
		A.cod_of DESC
		");
		
		echo "<table id=tbordzebr>
			<tr>
				<td>Código</td>
				<td>Razão Social</td>
				<td>No O.F.</td>
				<td>Prazo O.F.</td>
				<td>Observações</td>
				<td>Lotes</td>
			</tr>";
		while
		($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr>
					<td>$linha[CODIGOCLI]</td>
					<td>$linha[RAZAO]</td>
					<td><a href='formcadastrarlote.php?id=$linha[CODIGOOF]'>$linha[CODIGOOF]</td>
					<td>$linha[PRAZOOF]</td>
					<td>$linha[OBSERVACAO1]</td>
					<td>$linha[QT_LOTE]</td>
				</tr>";
		}
		echo "</table>";
		
		echo $consulta->rowCount() . " Registros Exibidos";
	}catch(PDOExceprion $e){
		echo $e->getMessasge();
	}

}
?>

</body>

</html>
