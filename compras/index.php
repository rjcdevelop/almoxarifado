<!DOCTYPE html>
<html>

<head>
<title>Compras - RJC</title>
<link href="css/estilo.css" rel="stylesheet">
<style>
	* {
		margin: 0;
		padding: 0;
		box-sizing: border-box;
	}

	body {
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		background: #f8fbff;
		color: #0b1d3f;
		min-height: 100vh;
		padding: 20px;
	}

	.container {
		max-width: 900px;
		margin: 0 auto;
		background: rgba(255, 255, 255, 0.95);
		border: 1px solid rgba(11, 29, 63, 0.2);
		border-radius: 15px;
		box-shadow: 0 20px 40px rgba(11, 29, 63, 0.12);
		padding: 40px 35px;
	}

	.header {
		text-align: center;
		margin-bottom: 30px;
	}

	.header h1 {
		font-size: 1.8em;
		font-weight: 700;
		color: #0b1d3f;
		letter-spacing: 1px;
		margin-bottom: 10px;
	}

	.header p {
		color: rgba(11, 29, 63, 0.75);
		font-size: 1em;
		margin: 0;
	}

	.links {
		display: flex;
		flex-direction: row;
		gap: 14px;
		justify-content: center;
		flex-wrap: wrap;
	}

	.link-item {
		display: block;
		padding: 14px 18px;
		background: rgba(11, 29, 63, 0.05);
		border: 1px solid rgba(11, 29, 63, 0.2);
		border-radius: 10px;
		color: #0b1d3f;
		text-decoration: none;
		font-weight: 600;
		font-size: 1.1em;
		transition: all 0.25s ease;
		flex: 1;
		text-align: center;
	}

	.link-item:hover {
		background: rgba(11, 29, 63, 0.1);
		border-color: rgba(11, 29, 63, 0.35);
		transform: translateY(-1px);
	}

	.footer {
		margin-top: 40px;
		text-align: center;
		color: rgba(11, 29, 63, 0.65);
		font-size: 0.9em;
	}
	.back-button {
		position: absolute;
		top: 20px;
		left: 20px;
		padding: 8px 15px;
		background: rgba(11, 29, 63, 0.15);
		border: 1px solid rgba(11, 29, 63, 0.3);
		color: #0b1d3f;
		border-radius: 8px;
		cursor: pointer;
		text-decoration: none;
		font-size: 0.9em;
		transition: all 0.3s ease;
	}

	.back-button:hover {
		background: rgba(11, 29, 63, 0.25);
		border-color: rgba(11, 29, 63, 0.4);
	}

	@media (max-width: 480px) {
		.container {
			padding: 30px 20px;
		}
	}
</style>
</head>

<body>
	<a href="../index.php" class="back-button">← Voltar</a>

<?php

	
	echo "<div class='container'>\n	<div class='header'>\n		<h1>R J C DEFESA E AEROESPACIAL</h1>\n		<p>Sistema de Compras</p>\n	</div>\n\n	<div class='links'>";
	echo "<a href='pedidos' class='link-item'>Pedido de Compras</a>";
	echo "<a href='estoque/' class='link-item'>Cadastro de Produtos</a>";
	echo "</div>\n\n<div class='footer'>\n\t<p>Área de Compras • RJC Defesa Aeroespacial LTDA</p>\n</div>\n</div>";
	/*echo "<a href='almox_audit/movto.php'>Movimento Estoque</a><br><br>";
	echo "<a href='nfxmovto/nfxmovto.php'>Nota x Movimento</a><br><br>";
	echo "<a href='estoque/index.php'>Cadastro de Produtos</a><br><br>";
	echo "<a href='estoque/rel_almox_folha.php'>Movto almox x folha</a><br><br>";
	echo "<a href='correlacaoitem.php'>Correlação de Item</a><br><br>";*/
	




?>

</body>

</html>
