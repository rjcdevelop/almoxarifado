<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema Controle de Produção - RJC Defesa</title>
	<link href="css/estilo.css" rel="stylesheet">

</head>

<body>
	<div class="container">
		<div class="logo-section">
			<div class="company-name">⚙ R J C DEFESA E AEROESPACIAL</div>
			<div class="company-subtitle">Tecnologia, Precisão e Excelência</div>
			<div class="system-title">Sistema de Controle, Produção e Estoque</div>
		</div>

		<div class="modules-grid">
			<?php
				echo '<div class="module-card">';
				echo '<a href="almoxarifado" class="module-button"> Almoxarifado</a>';
				echo '</div>';

				echo '<div class="module-card">';
				echo '<a href="compras" class="module-button"> Compras</a>';
				echo '</div>';

				echo '<div class="module-card">';
				echo '<a href="auditoria" class="module-button"> Auditoria</a>';
				echo '</div>';
			?>
		</div>

		<div class="footer-info">
			<p>Acesso restrito • Controle de Operações Críticas</p>
		</div>
	</div>
</body>
</html>
