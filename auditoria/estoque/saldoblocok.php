<!DOCTYPE html>
<html>

<head>
<title>Saldos - Bloco K e H</title>
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
		
	if (isset($_POST['curva'])){
		$curva = filter_var($_POST['curva']);
		}else{
		$curva = 'padrao';
		}

	if ($curva === 'padrao'){
		$descrcurva = 'A.TIPINV, A.CODIGO';
		$mensagem = 'GRUPO / CÓDIGO';
		}elseif ($curva === 'valor'){
		$descrcurva = 'SUBTOTAL DESC';
		$mensagem = 'VALOR TOTAL DECRESCENTE';
		}elseif ($curva === 'qtde'){
		$descrcurva = 'C.SOMAQUANT DESC';
		$mensagem = 'QUANTIDADE DECRESCENTE';
		}else{
		$descrcurva = 'GRUPO / CÓDIGO';
		}
		
//A.CODIGO,A.GRUPO,A.UNID,B.DESCRICAO,A.DESCRICAO,A.TIPINV,A.QUANT,C.SOMAQUANT

	echo "R J C DEFESA E AEROESPACIAL LTDA<br>Cadastro de Produtos - Data de Emissão: ". $hoje."<br><br>";
	echo "<a href='../index.php'><button>Voltar</button></a>
		<form action='exportar.php' method='POST'>
		<table>
		<tr>
		<td><input type='hidden' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$datafim'/></td>
		</tr>
		<td><input type='submit' value='Exportar'></td>
		</table>
		</form>
	<br>";

include_once "../../conexao.php";

echo "<form action='saldoblocok.php' method='POST'>
<table>
<tr><td><input type='radio' name='curva' value='padrao' checked>Padrão
	<input type='radio' name='curva' value='valor'>Valor
	<input type='radio' name='curva' value='qtde'>Quantidade</td></tr>
<tr><td><label>Data do inventário: </label>
<input type='date' style='font-size: 10pt; height: 16px; width:150px;' name='dt_fim' value='$datafim'/>


<td><input type='submit' value='Buscar'></td>
</tr>
</table>
</form>
<br>";
echo "Relatório ordenado por: $mensagem";

try{
	//EXECUÇÃO DA INSTRUCAO SQL
	$consulta = $conectar->query("SELECT
	A.CODIGO AS PROD,
	A.GRUPO AS GRUPO,
	A.UNID AS UNID,
	B.DESCRICAO AS SUBGRUPO,
	A.DESCRICAO AS DESCRICAO,
	A.TIPINV AS TIPOINV,
	C.SOMAQUANT AS SALDOMOVTO,
	SUM((CASE WHEN M.SOMAVLR IS NULL THEN 0 ELSE M.SOMAVLR END) + (CASE WHEN P.SOMAVLR IS NULL THEN 0 ELSE P.SOMAVLR END) + (CASE WHEN N.SOMAVLR IS NULL THEN 0 ELSE N.SOMAVLR END)) AS TOTMEDIA,
	SUM((CASE WHEN M.SOMAQTDE IS NULL THEN 0 ELSE M.SOMAQTDE END) + (CASE WHEN P.SOMAQTDE IS NULL THEN 0 ELSE P.SOMAQTDE END) + (CASE WHEN N.SOMAQTDE IS NULL THEN 0 ELSE N.SOMAQTDE END)) AS QTMEDIA,

	SUM((CASE WHEN M.SOMAVLR IS NULL THEN 1 ELSE M.SOMAVLR END) + (CASE WHEN P.SOMAVLR IS NULL THEN 1 ELSE P.SOMAVLR END) + (CASE WHEN N.SOMAVLR IS NULL THEN 1 ELSE N.SOMAVLR END)) / 
	SUM((CASE WHEN M.SOMAQTDE IS NULL THEN 1 ELSE M.SOMAQTDE END) + (CASE WHEN P.SOMAQTDE IS NULL THEN 1 ELSE P.SOMAQTDE END) + (CASE WHEN N.SOMAQTDE IS NULL THEN 1 ELSE N.SOMAQTDE END)) * C.SOMAQUANT
	AS SUBTOTAL

	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN(SELECT SB.CODIGO,SB.DESCRICAO FROM FAT00001.dbo.SubGrupo AS SB) AS B ON A.SUBGRUPO=B.CODIGO
	
	LEFT JOIN(SELECT SM.PROD, SUM(SM.QUANT) AS SOMAQUANT FROM FAT00001.dbo.movto AS SM WHERE SM.DATA <= '$datafim' GROUP BY SM.PROD) AS C ON A.CODIGO=C.PROD
	
	LEFT JOIN (SELECT M.PROD, SUM(M.VALUNI*M.QUANT) AS SOMAVLR, SUM(M.QUANT) AS SOMAQTDE FROM FAT00001.dbo.movto AS M
	WHERE M.TIPO='E' AND DATA <= '$datafim' AND M.ROTINA IN ('AJU','DEV','SI') GROUP BY M.PROD) AS M ON A.CODIGO=M.PROD
	
	LEFT JOIN (SELECT P.PRODUTO, SUM(P.VALTOT) AS SOMAVLR, SUM(P.QUANT) AS SOMAQTDE FROM FAT00001.dbo.entrada2 AS P
	WHERE P.CFOP NOT IN ('1.916','1.201','1.202','2.916','2.201','2.202','3.201','3.202') AND P.DATA<='$datafim' GROUP BY P.PRODUTO) AS P ON A.CODIGO=P.PRODUTO

	LEFT JOIN (SELECT N.PRODUTO, SUM(N.VLRTOT) AS SOMAVLR, SUM(N.QTDE) AS SOMAQTDE FROM FAT00001.dbo.nota2 AS N, FAT00001.dbo.nota1 AS M
	WHERE N.CFOP IN ('1.915','2.915','3.101','3.102','3.556','3.556') AND N.NOTA=M.NOTA AND M.EMISSAO <= '$datafim' GROUP BY N.PRODUTO) AS N ON A.CODIGO=N.PRODUTO

	WHERE
	A.ATIVO='Sim'
	AND C.SOMAQUANT NOT IN (0)
	AND A.TIPINV NOT IN ('0007','0006')
	GROUP BY A.CODIGO,A.GRUPO,A.UNID,B.DESCRICAO,A.DESCRICAO,A.TIPINV,A.QUANT,C.SOMAQUANT
	ORDER BY $descrcurva");



echo "<table id=tbordprod>
		<tr>
			<td width='220px'>CODIGO</td>
			<td width='800px'>DESCRIÇÃO</td>
			<td width='200px'>GRUPO</td>
			<td width='40px'>INV</td>
			<td width='35px'>UN</td>
            <td width='110px'>SD MOVTO</td>
			<td width='110px'>CUSTO UNIT</td>
			<td width='110px'>VL TOTAL</td>
		</tr>";

		$somavalor = 0;
		$somasaldo = 0;
		

	while
	($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
		
		$saldomovto = number_format($linha['SALDOMOVTO'],5,',', '.');
		$totmedia = $linha['TOTMEDIA'];
		$qtmedia = $linha['QTMEDIA'];
		$somasaldo += $linha['SALDOMOVTO'];

		if ($totmedia == 0) {
			$media = '0,00';
			$vlrtotal = 'Rever';
		}else {
			$vlr_media = $totmedia / $qtmedia;
			$media = number_format($vlr_media,3,',', '.');
			$calctotal = $linha['SALDOMOVTO'] * $vlr_media;
			$vlrtotal = number_format($calctotal,3,',', '.');
			$somavalor += $calctotal;
		}


		echo "<tr>
				<td class=tdleft><a href='../opxtransf/receitaxcusto.php?id_prod=$linha[PROD]'>$linha[PROD]</a></td>
				<td>$linha[DESCRICAO]</td>
				<td>$linha[SUBGRUPO]</td>
				<td>$linha[TIPOINV]</td>
				<td>$linha[UNID]</td>
                <td class=tdright><a href='../../almoxarifado/requisicao/movimento.php?id=$linha[PROD]'>$saldomovto</a></td>
				<td class=tdright>$media</td>
				<td class=tdright>$vlrtotal</td>
				";
	}
	echo "</table>";
	$totalsaldo = number_format($somasaldo,3,',', '.');
	$totalvalor = number_format($somavalor,3,',', '.');
	
	echo "<br>
	Quantidade Total no Inventário: $totalsaldo<br
	>Valor Total do Inventário: R$ $totalvalor";



}catch(PDOExceprion $e){
	echo $e->getMessasge();
}

?>
</body>
</html>
