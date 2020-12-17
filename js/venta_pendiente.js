$(function(){
		$('#pendiente').click(function(){

			$.gritter.add({
				// (string | mandatory) the heading of the notification
				title: '<a href="index.php?opc=venta&carrito=listar" style="color:#EEE; text-decoration:none">INFORMACION IMPORTANTE!</a>',
				// (string | mandatory) the text inside the notification
				text: '<a href="index.php?opc=venta&carrito=listar" style="color:#FFF; text-decoration:none">Usted tiene una Venta Pendiente que no ha finalizado.</a>',
				// (string | optional) the image to display on the left
				image: 'images/info.png',
				// (bool | optional) if you want it to fade out on its own or just sit there
				sticky: false,
				// (int | optional) the time you want it to be alive for before fading out
				time: ''
			});

			return false;

		});
});