<?php

include_once "../../conexao.php";

if(isset($_POST['dt_ini'])){
	$id_dtini=filter_var($_POST['dt_ini']);
	}else {
		$id_dtini='';
	}

if(isset($_POST['dt_fim'])){
	$id_dtfim=filter_var($_POST['dt_fim']);
	}else {
		$id_dtfim='';
	}

    $cabdtini = date('dmY', strtotime($id_dtini));
    $cabdtfim = date('dmY', strtotime($id_dtfim));
try{

    $sqlop1 = $conectar->query("SELECT
	A.DATA AS DTOP,
	A.CODIGO AS CODOP,
	A.CODPROD AS PRODFINAL,
	A.QTDE AS QTPRODFINAL
	FROM
	FAT00001.dbo.CadOp2 AS A
	WHERE
	A.DATA>='$id_dtini' AND A.DATA<='$id_dtfim'
	ORDER BY A.DATA, A.CODIGO");

    $linhas = "";
    //$abbre = fopen("K230_$id_dtini-$id_dtfim.txt", "a+");
	
while ($k230 = $sqlop1->fetch(PDO::FETCH_ASSOC)){
		// CABEÇALHO DA ORDEM DE PRODUÇÃO
		$prod_final = "$k230[PRODFINAL]";
        $dataop = "$k230[DTOP]";
        $date = date('dmY', strtotime($dataop));
        $codop = "$k230[CODOP]";
		$cod_prodfinal = "$k230[PRODFINAL]";
        $qtde_prodfinal = number_format($k230['QTPRODFINAL'],3,',', '');

        $linhas = "|K230|". $date ."|". $date ."|". $codop ."|". $cod_prodfinal ."|". $qtde_prodfinal . "|\r\n" ;
        echo $linhas . "<br>";
        //fwrite($abbre, $linhas);

           			// BLOCO DAS MATERIAS PRIMAS UTILIZADAS NA O.P."
					$sql_op2 = $conectar->query("SELECT
					B.DATA AS DATAOP2,
					A.CODIGO_OP AS CODOP2,
					B.ITEM AS ITEM2,
					A.CODIGO_MP AS PRODUTO2,
					A.DESCRICAO_MP AS DESCRICAO2,
					A.QTDETEMP2 AS QTPROD2
					FROM
					FAT00001.dbo.CadOp3 AS A LEFT JOIN FAT00001.dbo.CadOp2 AS B ON A.CODIGO_OP=B.CODIGO AND A.CODIGO_PR = B.CODPROD
					WHERE
					B.DATA>='$id_dtini' AND B.DATA<='$id_dtfim'
					AND A.CODIGO_OP = '{$codop}' AND A.CODIGO_PR = '$prod_final'
					ORDER BY A.CODIGO_OP,B.ITEM
					");
                        while($k235 = $sql_op2 -> fetch(PDO::FETCH_ASSOC)){
									
                                    $cod_matprima = "$k235[PRODUTO2]";
                                    $qtde_prod2 = number_format($k235['QTPROD2'],3,',', '');
                                    
                                    $linhas2 = "|K235|". $date ."|". $cod_matprima ."|". $qtde_prod2 . "||\r\n" ;
                                    echo $linhas2 . "<br>";
                                    //fwrite($abbre, $linhas2);
								}
								

       }

    //fclose($abbre);

}
catch(PDOExceprion $e){
	echo $e->getMessasge();
}
    



?>
