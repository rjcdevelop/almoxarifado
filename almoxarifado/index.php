<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema Almoxarifado - RJC Defesa</title>
	<link href="css/estilo.css" rel="stylesheet">
</head>

<body>
	<a href="../index.php" class="back-button">← Voltar</a>
	
	<div class="container">
		<div class="logo-section">
			<div class="company-name"> Almoxarifado</div>
			<div class="company-subtitle">Gestão Inteligente de Estoque</div>
			<div class="system-title">Sistema de Controle de Almoxarifado</div>
		</div>

		<div class="modules-grid">
			<?php
				echo '<div class="module-card">';
				echo '<a href="cardex" class="module-button"> Kardex</a>';
				echo '</div>';	

				echo '<div class="module-card">';
				echo '<a href="requisicao" class="module-button"> Requisições</a>';
				echo '</div>';

				echo '<div class="module-card">';
				echo '<a href="inventario" class="module-button"> Inventário</a>';
				echo '</div>';
			?>
		</div>

		<div class="footer-info">
			<p>Gestão e Controle de Estoque • RJC Defesa Aeroespacial</p>
		</div>
	</div>
</body>
</html>
