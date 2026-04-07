<!DOCTYPE html><html><head><title>Vincular Ordem de Produção" type=""></object></title>
<link href="/css/estilo.css" rel="stylesheet">
<script>function alert_alterado(){
alert("Gravado com Sucesso!");
history.go(-2);
}</script>
</head><body>

<?php
	echo "VINCULAR ORDEM DE PRODUÇÃO - R J C DEFESA E AEROESPACIAL LTDA<br>
	<input type='button' value='Voltar' onClick='history.go(-1)'><br><br>";
	
	include_once "../../conexao.php";
	
	//RECEBE O NUMERO DOCUMENTO PARA VINCULAR

	if(isset($_POST['pesquisar'])){
		$nota = filter_var($_POST['nota']);
		$item=filter_var($_POST['item']);
		$prod=filter_var($_POST['prod']);
		$dt_ini = filter_var($_POST['dt_ini']);
		$dt_fim = filter_var($_POST['dt_fim']);
		$pesquisar = filter_var($_POST['pesquisar']);
		
		}else {
			$nota = filter_var($_GET['nota']);
			$item = filter_var($_GET['item']);
			$prod = filter_var($_GET['prod']);
			$dt_ini = filter_var($_GET['dt_ini']);
			$dt_fim = filter_var($_GET['dt_fim']);
			$pesquisar = '';
			}

			$detalhanf = $conectar->query("SELECT
   				 B.EMISSAO AS DATANF,
				 A.ITEM AS ITEM,
				 A.NOTA AS NOTA,
				 A.PRODUTO AS CODIGO,
				 A.DESCR AS DESCRICAO,A.UNID AS UNID,
				 A.QTDE AS QTDE,
				 A.UNIDENTRADA AS UNID2,
				 A.QTDENTRADA AS QTDE2,
				 (COMPLEMENTO1 + ' / ' + COMPLEMENTO2 + ' / ' + COMPLEMENTO3 + ' / ' + COMPLEMENTO4 + ' / ' + COMPLEMENTO5 + ' / ' + COMPLEMENTO6 + ' / ' + COMPLEMENTO7 + ' / ' + COMPLEMENTO8 + ' / ' +
				 COMPLEMENTO9 + ' / ' + COMPLEMENTO10 + ' / ' + COMPLEMENTO11) AS COMPLEMENTO
				 FROM FAT00001.dbo.nota2 AS A
				 LEFT JOIN FAT00001.dbo.nota1 AS B ON A.NOTA = B.NOTA
				 WHERE A.NOTA='$nota' AND A.PRODUTO = '$prod'AND A.ITEM = '$item'
				 ");

	while
	($linha = $detalhanf->fetch(PDO::FETCH_ASSOC)) {
			$obs_nf = $linha['COMPLEMENTO'];
			$dt_nf = new DateTime($linha['DATANF']);
			$dt_nf = $dt_nf->format('d/m/Y');
			$qtde1_nf = number_format($linha['QTDE'],5,',', '.');
			$qtde2_nf = number_format($linha['QTDE2'],5,',', '.');
	}


	echo "Data NF: $dt_nf Quantidade(1): $qtde1_nf Quantidade(2): $qtde2_nf <br> Código: $prod Observação: $obs_nf<br><br>";

if($prod != ''){
	$sql = $conectar->query("SELECT
		A.CODIGO AS num_op,
		A.DATA AS data,
		A.ITEM AS item,
		A.CODPROD AS produto,
		A.DESCRICAO AS descricao,
		A.QTDE AS qtde,
		(A.OBS1 + ' ' + A.OBS2) AS OBS
		FROM FAT00001.dbo.CadOp2 AS A
		WHERE
		DATA >= '$dt_ini' AND DATA <= '$dt_fim'
		AND A.CODPROD = '$prod'
		ORDER BY A.DATA, A.ITEM
		");
	
	echo "<table id=tbordprod>
	<tr>
		<td width='100px'>NUM OP</td>
		<td>OBSERVAÇÃO OP</td>
		<td>NOTA</td>
		<td>ITEM</td>
		<td>PROD</td>
		<td>QUANTIDADE</td>
	</tr>";
		while
			($linha = $sql->fetch(PDO::FETCH_ASSOC)) {

			echo "<tr>
				<form action='vincularop.php' method='GET'>
				<td><input type='text' name='num_op' value='$linha[num_op]' id='num_op'/></td>
				<td><input type='text' name='obs' value='$linha[OBS]' id='obs'/></td>
				<td><input type='text' name='nota' value='$nota' id='nota'/></td>
				<td><input type='text' name='item' value='$item' id='item'/></td>
				<td><input type='text' name='prod' value='$prod' id='prod'/>
				<td><input type='text' name='qtde' value='$linha[qtde]' id='qtde'/>
				<input type='hidden' name='dt_ini' value='$dt_ini' id='dt_ini'/>
				<input type='hidden' name='dt_fim' value='$dt_fim' id='dt_fim'/>
				<input type='submit' value='Enviar'></td></form></tr>";
		}
	echo "</table><br><br>";

}else{}

?>

</body>
</html>
