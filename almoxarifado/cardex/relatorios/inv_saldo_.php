<!DOCTYPE html>
<html>

<head>
<title>Estoque Mínimo</title>
<link href="/css/estilo.css" rel="stylesheet">
</head>

<body>

<?php
	
	echo "R J C DEFESA AEROESPACIAL LTDA &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	<a href='index.php'><button>Voltar</button></a><br><br>";

	include_once "../../../conexao.php";

		//QUERY PESQUISA DE PRODUTOS
        $sql = $conectar->query("SELECT
        A.CODIGO AS CODIGO,
        A.DESCRICAO AS DESCRICAO,
        A.UNID AS UNIDADE,
        ((CASE WHEN C.S1TOTAL IS NULL THEN 0 ELSE C.S1TOTAL END) -(CASE WHEN D.S2TOTAL IS NULL THEN 0 ELSE D.S2TOTAL END)) AS SALDO,
		A.ESTMIN AS ESTMIN
        FROM
        FAT00001.dbo.produto AS A
        LEFT JOIN (SELECT S1.COD_ITEM AS S1ITEM, SUM(S1.QTDE) AS S1TOTAL
        FROM pcp_producao.dbo.almox_movto S1 
        WHERE S1.TP_MOVTO='E'
        GROUP BY S1.COD_ITEM) AS C ON A.CODIGO=C.S1ITEM
        
        LEFT JOIN (SELECT S2.COD_ITEM AS S2ITEM, SUM(S2.QTDE) AS S2TOTAL
        FROM pcp_producao.dbo.almox_movto S2 
        WHERE S2.TP_MOVTO='S' 
        GROUP BY S2.COD_ITEM) AS D ON A.CODIGO=D.S2ITEM
        
        WHERE ((CASE WHEN C.S1TOTAL IS NULL THEN 0 ELSE C.S1TOTAL END) -(CASE WHEN D.S2TOTAL IS NULL THEN 0 ELSE D.S2TOTAL END)) < A.ESTMIN
            
        ORDER BY SALDO DESC,A.CODIGO ASC
            ");
        
        echo "<br><br>RELAÇÃO DE PRODUTOS ABAIXAO DO ESTOQUE MÍNIMO:<br>";
        
        try{
            
            echo "<table id=tbordzebr>
                <tr>
                    <td>CÓDIGO</td>
                    <td>DESCRIÇÃO</td>
                    <td>UNID</td>
                    <td>SALDO</td>
                    <td>EST MIN</td>
                </tr>";
        
            while
            ($linha = $sql->fetch(PDO::FETCH_ASSOC)){
        
                $saldo = number_format($linha['SALDO'],2,',', '.');
                $estmin = number_format($linha['ESTMIN'],2,',', '.');
        
                echo "<tr>
                        <td>$linha[CODIGO]</td>
                        <td>$linha[DESCRICAO]</td>
                        <td>$linha[UNIDADE]</td>
                        <td align='right'>$saldo</td>
                        <td align='right'>$estmin</td>
                    </tr>";
            }
            echo "</table>";
            
            echo $sql->rowCount() . " Registros Exibidos";
        }catch(PDOExceprion $e){
            echo $e->getMessasge();
        }
	
?>
</body>

</html>
