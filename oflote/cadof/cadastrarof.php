<?php

include_once "../../conexao.php";

try{	
	$prazo_of = filter_var($_POST['prazo_of']);
	$cod_of = filter_var($_POST['codigo_of']);
	$codcli = filter_var($_POST['cliente']);	
	$observ1 = filter_var($_POST['observ1']);
		
	$insert = $conectar->prepare("INSERT INTO cad_of(prazo_of,cod_of,codcli,observ1)
								VALUES(:prazo_of,:cod_of,:codcli,:observ1)");
	$insert->bindParam(':prazo_of',$prazo_of);
	$insert->bindParam(':cod_of',$cod_of);
	$insert->bindParam(':codcli',$codcli);
	$insert->bindParam(':observ1',$observ1);
	$insert->execute();
	
	header("location: listarof.php?id=$codcli");
	
} catch(PDOException $e){
	echo 'Erro: ' . $e->getMessage();
}
