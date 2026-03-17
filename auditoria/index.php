<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Auditoria Interna - RJC</title>
	<link href="css/estilo.css" rel="stylesheet">

</head>

<body>
	<a href="../index.php" class="back-button">← Voltar</a>

	<div class="main-container">
		<div class="header">
			<img src="img/rjclogo01.png" alt="Logo RJC" class="logo">
			<div class="header-text">
				<div class="company-name">R J C DEFESA E AEROESPACIAL</div>
				<div class="page-title">Sistema de Auditoria Interna</div>
			</div>
		</div>

		<div class="sections-container">
			<?php
				// Seção 1: Bloco K
				echo '<div class="section-card">';
				echo '<div class="section-header">';
				echo '<h2 class="section-title">Relatórios Bloco K</h2>';
				echo '</div>';
				echo '<div class="section-links">';
				echo '<a href="nfxmovto/nfxmovto.php" class="link-item">▸ Nota x Movimento</a>';
				echo '<a href="nfxmovto/nfxop.php" class="link-item">▸ Nota x O.P</a>';
				echo '<a href="opxtransf/ordprod.php" class="link-item">▸ Ordem de Produção</a>';
				echo '<a href="nfxmovto/nfxproduto.php" class="link-item">▸ Vendas por Produto</a>';
				echo '</div>';
				echo '</div>';

				// Seção 2: Almoxarifado
				echo '<div class="section-card">';
				echo '<div class="section-header">';
				echo '<h2 class="section-title">Relatórios Almoxarifado</h2>';
				echo '</div>';
				echo '<div class="section-links">';
				echo '<a href="opxtransf/optransf.php" class="link-item">▸ O.P. e Transferências</a>';
				echo '<a href="almox_audit/movto.php" class="link-item">▸ Movimento de Estoque</a>';
				echo '<a href="estoque/index.php" class="link-item">▸ Cadastro de Produtos</a>';
				echo '<a href="estoque/rel_almox_folha.php" class="link-item">▸ Movto Almox x Folha</a>';
				echo '<a href="estoque/saldoblocok.php" class="link-item">▸ Saldo Estoque por Data</a>';
				echo '</div>';
				echo '</div>';

				// Seção 3: Análise de Custo
				echo '<div class="section-card">';
				echo '<div class="section-header">';
				echo '<h2 class="section-title">Análise de Custo</h2>';
				echo '</div>';
				echo '<div class="section-links">';
				echo '<a href="opxtransf/receitaxcusto.php" class="link-item">▸ Custo Por Produto</a>';
				echo '<a href="estoque/custo.php" class="link-item">▸ Custo das Mercadorias</a>';
				echo '<a href="estoque/receitas.php" class="link-item">▸ Cadastro de Receitas</a>';
				echo '</div>';
				echo '</div>';

				// Seção 4: Lançamento de Notas
				echo '<div class="section-card">';
				echo '<div class="section-header">';
				echo '<h2 class="section-title">Lançamento de Notas</h2>';
				echo '</div>';
				echo '<div class="section-links">';
				echo '<a href="correlacaoitem.php" class="link-item">▸ Correlação de Item</a>';
				echo '</div>';
				echo '</div>';
			?>
		</div>

		<div class="footer-line">
			<p>Sistema de Auditoria e Conformidade • RJC Defesa Aeroespacial LTDA</p>
		</div>
	</div>
</body>

</html>
