<?php
include_once "../../conexao.php";

$hoje = date('Y-m-d');
$relatorio = [];
$mensagem = "";

// 1. CAPTURA DE FILTROS
$dt_ini = $_POST['dt_ini'] ?? $hoje;
$dt_fim = $_POST['dt_fim'] ?? $hoje;
$busca = trim($_POST['busca'] ?? '');
$rotina = $_POST['rotina'] ?? 'REQ';
$lote_destino = trim($_POST['lote_destino'] ?? '');
$vlr_fixo = isset($_POST['vlr_fixo']) ? floatval(str_replace(',', '.', $_POST['vlr_fixo'])) : 0;

// 2. LÓGICA DE PROCESSAMENTO (QUANDO CLICAR EM VINCULAR TUDO)
if (isset($_POST['executar_vinc_massa'])) {
    if ($lote_destino === '') {
        $mensagem = "<b style='color:red;'>Informe o lote destino antes de vincular.</b>";
    } else {
        try {
            $sql_update = "INSERT INTO pcp_producao.dbo.reglote (cod_lote, doc_id, codproduto, valuni, valtot, item)
                           SELECT 
                               :lote, A.CONTROLE, A.PROD, :vlr, 
                               (CASE WHEN A.ROTINA = 'REQ' THEN (:vlr_calc * A.QUANT * -1) ELSE (:vlr_calc * A.QUANT) END),
                               A.ITEM
                           FROM FAT00001.dbo.movto AS A
                           LEFT JOIN pcp_producao.dbo.reglote AS B ON A.CONTROLE=B.doc_id AND A.PROD=B.codproduto AND A.ITEM=B.item
                           LEFT JOIN FAT00001.dbo.requis1 AS C ON A.NOTA = C.DOCUM AND A.ROTINA = 'REQ'
                           LEFT JOIN FAT00001.dbo.Devol1 AS D ON A.NOTA = D.DOCUM AND A.ROTINA = 'DEV'
                           WHERE A.DATA BETWEEN :d1 AND :d2 
                           AND A.ROTINA = :rot 
                           AND B.doc_id IS NULL
                           AND (A.PROD LIKE :b OR A.DESCRICAO LIKE :b OR A.OBSSAI LIKE :b OR C.OBSERV LIKE :b OR D.OBSERV LIKE :b)";

            $stmt = $conectar->prepare($sql_update);
            $stmt->execute([
                ':lote' => $lote_destino,
                ':vlr' => $vlr_fixo,
                ':vlr_calc' => $vlr_fixo,
                ':d1' => $dt_ini,
                ':d2' => $dt_fim,
                ':rot' => $rotina,
                ':b' => "%$busca%"
            ]);

            $count = $stmt->rowCount();
            $mensagem = "<b style='color:green;'>Sucesso! $count registros vinculados ao lote $lote_destino.</b>";
        } catch (Exception $e) {
            $mensagem = "<b style='color:red;'>Erro: " . $e->getMessage() . "</b>";
        }
    }
}

// 3. LÓGICA DE PESQUISA/PRÉVIA
if (isset($_POST['pesquisar']) || isset($_POST['executar_vinc_massa'])) {
    $sql_previa = "SELECT A.DATA, A.CONTROLE, A.PROD, A.DESCRICAO, A.QUANT, A.VALUNI, (A.OBSSAI + ' / ' + ISNULL(C.OBSERV, ISNULL(D.OBSERV, ''))) as OBS_TOTAL
                   FROM FAT00001.dbo.movto AS A
                   LEFT JOIN pcp_producao.dbo.reglote AS B ON A.CONTROLE=B.doc_id AND A.PROD=B.codproduto AND A.ITEM=B.item
                   LEFT JOIN pcp_producao.dbo.requis1 AS C ON A.NOTA = C.DOCUM AND A.ROTINA = 'REQ'
                   LEFT JOIN pcp_producao.dbo.Devol1 AS D ON A.NOTA = D.DOCUM AND A.ROTINA = 'DEV'
                   WHERE A.DATA BETWEEN :d1 AND :d2 AND A.ROTINA = :rot AND B.doc_id IS NULL
                   AND (A.PROD LIKE :b OR A.DESCRICAO LIKE :b OR A.OBSSAI LIKE :b OR C.OBSERV LIKE :b OR D.OBSERV LIKE :b)
                   ORDER BY A.DATA, A.CONTROLE";

    $stmt = $conectar->prepare($sql_previa);
    $stmt->execute([':d1' => $dt_ini, ':d2' => $dt_fim, ':rot' => $rotina, ':b' => "%$busca%"]); 
    $relatorio = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manutenção em Massa</title>
    <link href="../../css/estilo.css" rel="stylesheet">
</head>
<body>
    <h3>MANUTENÇÃO EM MASSA - FILTRAR E VINCULAR</h3>
    <a href="vincularlote.php"><button>Voltar</button></a><br><br>

    <form method="POST" action='manutlote.php' style="background:#eee; padding:15px; border-radius:5px;">
        <strong>1. Filtros de Busca</strong><br>
        Data: <input type="date" name="dt_ini" value="<?=$dt_ini?>"> até <input type="date" name="dt_fim" value="<?=$dt_fim?>">
        Movimento: 
        <input type="radio" name="rotina" value="REQ" <?=$rotina=='REQ'?'checked':''?>> Remessa
        <input type="radio" name="rotina" value="DEV" <?=$rotina=='DEV'?'checked':''?>> Entrega <br><br>
        Pesquisar (Cód/Desc/Obs): <input type="text" name="busca" value="<?=$busca?>" style="width:300px;">
        <input type="submit" name="pesquisar" value="Visualizar Registros">
        
        <?php if(count($relatorio) > 0): ?>
            <hr>
            <strong>2. Ação em Massa (Aplica a todos os <?=count($relatorio)?> itens abaixo)</strong><br>
            Lote Destino: <input type="text" name="lote_destino" required>
            Vlr Unitário: <input type="number" name="vlr_fixo" step="0.000001" value="0.00">
            <input type="submit" name="executar_vinc_massa" value="VINCULAR LOTE EM TUDO" 
                   style="background:red; color:white;" onclick="return confirm('ATENÇÃO: Deseja vincular este lote em todos os registros listados?')">
        <?php endif; ?>
    </form>

    <br><?=$mensagem?><br>

    <table id="tbordzebr" width="100%">
        <tr style="background:#ccc;">
            <td>Data</td><td>Registro</td><td>Produto</td><td>Descrição</td><td>Qtde</td><td>Observações</td>
        </tr>
        <?php foreach($relatorio as $r): ?>
        <tr>
            <td><?=date('d/m/Y', strtotime($r['DATA']))?></td>
            <td><?=$r['CONTROLE']?></td>
            <td><?=$r['PROD']?></td>
            <td><?=$r['DESCRICAO']?></td>
            <td><?=number_format($r['QUANT'],3,',','.')?></td>
            <td style="font-size:9px;"><?=$r['OBS_TOTAL']?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
