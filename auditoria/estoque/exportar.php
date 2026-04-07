<?php

include_once "../../conexao.php";

if(isset($_POST['dt_fim'])){
	$id_dtfim=filter_var($_POST['dt_fim']);
	}else {
		$id_dtfim='';
	}

try{

    $sqlop1 = $conectar->query("SELECT
	A.CODIGO AS PROD,
	A.GRUPO AS GRUPO,
	A.UNID AS UNID,
	B.DESCRICAO AS SUBGRUPO,
	A.DESCRICAO AS DESCRICAO,
	A.TIPINV AS TIPOINV,
	C.SOMAQUANT AS SALDOMOVTO,
	SUM((CASE WHEN M.SOMAVLR IS NULL THEN 0 ELSE M.SOMAVLR END) + (CASE WHEN P.SOMAVLR IS NULL THEN 0 ELSE P.SOMAVLR END) + (CASE WHEN N.SOMAVLR IS NULL THEN 0 ELSE N.SOMAVLR END)) AS TOTMEDIA,
	SUM((CASE WHEN M.SOMAQTDE IS NULL THEN 0 ELSE M.SOMAQTDE END) + (CASE WHEN P.SOMAQTDE IS NULL THEN 0 ELSE P.SOMAQTDE END) + (CASE WHEN N.SOMAQTDE IS NULL THEN 0 ELSE N.SOMAQTDE END)) AS QTMEDIA,
	A.PCOD AS CONTACONTABIL
	FROM
	FAT00001.dbo.produto AS A
	LEFT JOIN(SELECT SB.CODIGO,SB.DESCRICAO FROM FAT00001.dbo.SubGrupo AS SB) AS B ON A.SUBGRUPO=B.CODIGO
	LEFT JOIN(SELECT SM.PROD, SUM(SM.QUANT) AS SOMAQUANT FROM FAT00001.dbo.movto AS SM WHERE SM.DATA <= '$id_dtfim' GROUP BY SM.PROD) AS C ON A.CODIGO=C.PROD
	LEFT JOIN (SELECT M.PROD, SUM(M.VALUNI*M.QUANT) AS SOMAVLR, SUM(M.QUANT) AS SOMAQTDE FROM FAT00001.dbo.movto AS M
	WHERE M.TIPO='E' AND DATA <= '$id_dtfim' AND M.ROTINA IN ('AJU','DEV','SI') GROUP BY M.PROD) AS M ON A.CODIGO=M.PROD
	LEFT JOIN (SELECT P.PRODUTO, SUM(P.VALTOT) AS SOMAVLR, SUM(P.QUANT) AS SOMAQTDE FROM FAT00001.dbo.entrada2 AS P
	WHERE P.CFOP NOT IN ('1.915','1.916','1.201','1.202','2.915','2.916','2.201','2.202','3.201','3.202') AND P.DATA<='$id_dtfim' GROUP BY P.PRODUTO) AS P ON A.CODIGO=P.PRODUTO

	LEFT JOIN (SELECT N.PRODUTO, SUM(N.VLRTOT) AS SOMAVLR, SUM(N.QTDE) AS SOMAQTDE FROM FAT00001.dbo.nota2 AS N, FAT00001.dbo.nota1 AS M
	WHERE N.CFOP IN ('3.101','3.102','3.556','3.556') AND N.NOTA=M.NOTA AND M.EMISSAO <= '$id_dtfim' GROUP BY N.PRODUTO) AS N ON A.CODIGO=N.PRODUTO

	WHERE
	A.ATIVO='Sim'
	AND C.SOMAQUANT > 0.0009
	GROUP BY A.CODIGO,A.GRUPO,A.UNID,B.DESCRICAO,A.DESCRICAO,A.TIPINV,A.QUANT,C.SOMAQUANT,A.PCOD
	ORDER BY A.TIPINV, A.CODIGO");

    $linhas = "";
    //$abbre = fopen("K230_$id_dtini-$id_dtfim.txt", "a+");

while ($h010 = $sqlop1->fetch(PDO::FETCH_ASSOC)){
		// CABEÇALHO DA ORDEM DE PRODUÇÃO

		$saldomovto = number_format($h010['SALDOMOVTO'],5,',', '.');
		$totmedia = $h010['TOTMEDIA'];
		$qtmedia = $h010['QTMEDIA'];

		if ($totmedia == 0) {
			$media = '0,00';
			$vlrtotal = 'Rever';
		}else {
			$vlr_media = $totmedia / $qtmedia;
			$media = number_format($vlr_media,3,',', '.');
			$calctotal = $h010['SALDOMOVTO'] * $vlr_media;
			$vlrtotal = number_format($calctotal,3,',', '.');
		}


		$cod_prod = "$h010[PROD]";
		$unidade = "$h010[UNID]";
		$qtde_prod = number_format($h010['SALDOMOVTO'],3,',', '');
		$contacontabil = "$h010[CONTACONTABIL]";
        

        $linhas = "|H010|". $cod_prod ."|". $unidade ."|". $qtde_prod ."|". $media ."|". $vlrtotal . "|0|||". $contacontabil . "|". $media . "|\r\n" ;
        echo $linhas . "<br>";
        //fwrite($abbre, $linhas);
								

       }

    //fclose($abbre);

}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}
    



?>
