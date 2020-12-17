<?php include "Barcode39.php"; ?>
<html>
<head>
</head>
<body>
	<?php  
	$bc = new Barcode39("123456789");
	$bc->barcode_text_size = 5; 
	$bc->barcode_bar_thick = 4;
	$bc->barcode_bar_thin = 2; 
	$bc->draw("codigo-de-barras.gif");
	$bc->draw();
	?>
</body>

</html>