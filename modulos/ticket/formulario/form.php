<?php
	/*WITRETAIL V5
		- DESCUENTOS AUTOMATICOS DE REGALOS DESHABILITADOS
		- CANTIDADES POR SEPARADO PARA EVITAR EL ERROR DE DESCUENTO EN CAJA DEL 1 PESO DEL REGALO SI ES QUE SE HACE UN DESCUENTO A UN PRODUCTO QUE TIENE 2 O MÁS UNIDADES
		
		WITRETAIL V6
		- SE OCULTA LA OPCIÓN DE INGRESO DE RUT DE CLIENTE DE CONVENIO DEBIDO A QUE EL SOFTWARE RETAIL PRO CAJA REALIZA LOS DESCUENTOS AUTOMÁTICAMENTE SEGÚN SU RUT
	*/
	//include("script/autodestroy.php");
	require_once("../../../clases/conexionocdb.php");
	session_start();
	if(empty($_SESSION['username'])) { 
        header('Location: ../index.php');
    }
	
	$sql="SELECT * FROM [RP_VICENCIO].dbo.RP_IP_BODEGAS WHERE ip = '".$_SESSION['bodega']."'";
	$rs = odbc_exec( $conn, $sql );
	if ( !$rs ){
		exit( "Error en la consulta SQL" );
	}
	while($resultado = odbc_fetch_array($rs)){ 
		$ip = $resultado['ip'];
	}
	if($ip == null){
		session_destroy();
		echo "<script>location.href='../index.php';</script>";
		// echo $resultado['ip'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="js/jquery-1.11.0.min.js"></script>
<!-- INICIO Bootstrap -->
<!-- CSS -->
	<!-- BOOSTRAP -->
<link href="css/bootstrap.css" rel="stylesheet">
	<!-- SELECT PICKER -->
<link href="css/bootstrap-select.css" rel="stylesheet">

<!-- JAVASCRIPT -->
	<!-- BOOSTRAP -->
<script src="js/bootstrap.min.js"></script>
	<!-- SELECT PICKET -->
<script src="js/bootstrap-select.js"></script>
<script src="js/i18n/datepicker-es.js" type="text/javascript"></script>

<!-- FIN Boostrap -->
<title>PREVENTA</title>

<!-- jQuery (required) & jQuery UI + theme (optional) -->
<link href="docs/css/jquery-ui.min.css" rel="stylesheet">
<script src="docs/js/jquery-ui.min.js"></script>
<script src="docs/js/jquery-migrate-1.2.1.min.js"></script>
    
<!-- keyboard widget css & script (required) -->
<link href="css/keyboard.css" rel="stylesheet">
<script src="js/jquery.keyboard.js"></script>

<!-- keyboard extensions (optional) -->
	<script src="js/jquery.mousewheel.js"></script>
	<script src="js/jquery.keyboard.extension-typing.js"></script>
	<script src="js/jquery.keyboard.extension-autocomplete.js"></script>
	<script src="js/jquery.keyboard.extension-caret.js"></script>

<!-- demo only -->
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css">
	<!--<link href="docs/css/tipsy.css" rel="stylesheet">
	<link href="docs/css/prettify.css" rel="stylesheet">
	<script src="docs/js/bootstrap.min.js"></script>
	<script src="docs/js/jquery.tipsy.min.js"></script>
	<script src="docs/js/prettify.js"></script> <!-- syntax highlighting -->

<!-- FORMATEO DE NUMEROS -->
<script src="js/jquery.number.js"></script>
<style>
	body {
		background-image: url("images/background.jpg");
	}
	.ui-keyboard-keyset{
		margin-top:40px;
	}
</style>
<script>
	function busqCodEncontrado(codigo){
		var encontrado = 0;
		$('#example tr').each(function(){
			$(this).find(".upc").each(function() {
					if($(this).html() == codigo){
						encontrado = 1
					}
				});
		});
		return encontrado;
	}
	function eliminarProd(){
		/*	ELIMINAR FILA SELECCIONADA CON BOTON DE ELIMINAR
			1.- Identificar la fila a eliminar y quitarla
			2.- Recalcular total al eliminar una fila
		*/
		/*1.- IDENTIFICAR LA FILA A ELIMINAR Y QUITARLA*/
		 $(".btnEliminarProd").click(function(){
			 $(this).parents("tr").fadeOut("normal", function(){
            	$(this).remove();
				/*2.- RECALCULAR TOTAL AL ELIMINAR UNA FILA*/
					var totales = Array();
					var acumTotal = 0;
					$('#example tr').each(function(){
						$(this).find(".total .NOTtxtKeyboard").each(function() {
								fila = $(this).val();
								totales.push(fila);
								acumTotal = acumTotal + parseInt(fila);
						});
					});
					$("#totalFinal").val(acumTotal);
					$('#codigoBusq').focus();
			/*FIN RECALCULAR TOTAL AL ELIMINAR UNA FILA*/
			});
		 });
		 /*FIN IDENTIFICAR LA FILA A ELIMINAR Y QUITARLA*/
		 
	}
	function actualizarTabla(){
		var toggleKeysIfEmpty = function( kb ) {
			var toggle = kb.$preview.val() == '';
			console.log( toggle, kb.$preview.val() );
			kb.$keyboard
				.find('.ui-keyboard-bksp, .ui-keyboard-accept')
				.toggleClass('disabled', toggle)
				.click('disabled', toggle)
				.prop('disabled', toggle);
			};
		
		$('.txtKeyboard').keyboard({
     			layout: 'custom',
				customLayout: {
					'default': [
						'1 2 3',
						'4 5 6',      //Teclado
						'7 8 9',
						'0',
						' {accept} {cancel} {bksp}'
						]
				},	
				visible : function(e, keyboard) {
					toggleKeysIfEmpty( keyboard );
				},
				change: function (e, keyboard) {
					toggleKeysIfEmpty( keyboard );
				},
				restrictInput : true,
				preventPaste : true, 
				autoAccept : false
		}).addTyping();
		$("#codigoBusq").focus();
		/*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
		var totales = Array();
		var acumTotal = 0;
		$('#example tr').each(function(){
			$(this).find(".total .NOTtxtKeyboard").each(function() {
					fila = $(this).val();
					totales.push(fila);
					acumTotal = acumTotal + parseInt(fila);
			});
		});
		$("#totalFinal").val(acumTotal);
		/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
		
		/*SUMAR Y ACUMULAR TOTAL AL CAMBIAR EL VALOR DE LA CELDA*/
		$(".total .NOTtxtKeyboard").focusout(function() {
            var totales = Array();
			var acumTotal = 0;
			$('#example tr').each(function(){
				$(this).find(".total .NOTtxtKeyboard").each(function() {
						fila = $(this).val();
						totales.push(fila);
						acumTotal = acumTotal + parseInt(fila);
				});
			});
			$("#totalFinal").val(acumTotal);
			$('#codigoBusq').focus();
        });
		/*FIN SUMAR Y ACUMULAR TOTAL AL CAMBIAR EL VALOR DE LA CELDA*/
		
		/*CALCULO DE CANTIDAD POR VALOR*/
		
		$(".cant .txtKeyboard").focusout(function() {
			window.respuesta;
			var multTotal = Array();
			var valDesc = Array();
			
			var cantidades = Array();
			var totales = Array();
			var descuentos = Array();
			var upc = Array();
			var totalFinal = Array();
			var totalDesc = Array();
			
			$('#example tr').each(function(){
				$(this).find(".cant .txtKeyboard").each(function() {
					fila = $(this).val();
					cantidades.push(fila);
				});
				$(this).find(".totalUnidad .txtKeyboard").each(function() {
					fila = $(this).val();
					totales.push(fila);
				});	
				$(this).find(".desc .txtKeyboard").each(function() {
					fila = $(this).val();
					descuentos.push(fila);
				});	
				$(this).find(".upc").each(function() {
					fila = $(this).html();
					upc.push(fila);
				});		
				$(this).find(".total .NOTtxtKeyboard").each(function() {
					fila = $(this).val();
					totalFinal.push(fila);
				});
				$(this).find(".valDesc .txtKeyboard").each(function() {
					fila = $(this).val();
					totalDesc.push(fila);
				});
			});
			/*ENCONTRAR INDICE DE VALOR A ALTERAR*/
			var indice = ($(this).closest('td').parent()[0].sectionRowIndex)-1;
			var descuento = totalDesc[indice];
			var codBusq = upc[indice];
			var codigoCantidad = Array();
			codigoCantidad.push(codBusq);
			$.post('script/obtenerProductoCant.php', {codCantidad : codigoCantidad}, function(resPHP){
				window.respuesta = $.parseJSON(resPHP);
			});
			if(window.respuesta['cantidad'] != undefined){
					if(cantidades[indice]<=parseInt(window.respuesta['cantidad'])){
					
					for(i=0;i<cantidades.length;i++){
						multTotal.push(((cantidades[i]*totales[i])*(100-descuentos[i]))/100);
					}
					for(i=0;i<cantidades.length;i++){
						valDesc.push(((cantidades[i]*totales[i])*descuentos[i])/100);
					}
					i=0;
					$('#example tr').each(function(){
						$(this).find(".total .NOTtxtKeyboard").each(function() {
							$(this).val(multTotal[i]);
							i++;
						});	
					});
					j=0;
					$('#example tr').each(function(){
						$(this).find(".valDesc .txtKeyboard").each(function() {
							$(this).val(valDesc[j]);
							j++;
						});	
					});
					/*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
					var totales = Array();
					var acumTotal = 0;
					$('#example tr').each(function(){
						$(this).find(".total .NOTtxtKeyboard").each(function() {
								fila = $(this).val();
								totales.push(fila);
								acumTotal = acumTotal + parseInt(fila);
						});
					});
					$("#totalFinal").val(acumTotal);
					//$("#totalFinal").number(2000.3456, 0, ',');
					/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
				}else{
					var primerPaso = parseInt(totalFinal[indice]);
					var segundoPaso = parseInt(descuento);
					var tercerPaso = parseInt(totales[indice]);
					var total = (primerPaso+segundoPaso)/tercerPaso;
					$(this).val(total);
					var codigo = Array();
					codigo.push(codBusq);
					$.post('script/obtenerDSM.php', {cod : codigo}, function(resPHP){
						if(resPHP!=""){
							alert("Actualmente tiene stock en un DSM pendiente, Número de DSM: "+resPHP);
							$("#codigoBusq").val("");
							$('#codigoBusq').focus();
						}else{
							alert("No dispone de stock suficiente");
							$("#codigoBusq").val("");
							$('#codigoBusq').focus();
						}
					});
				}
			}
			
			/*FIN ECNONTRAR INDICE DE VALOR A ALTERAR*/
			$('#codigoBusq').focus();
        });
		/*FIN DE CALCULO DE CANTIDAD POR VALOR*/
				
		/*ACTUALIZACIÓN DESPUÉS DE MODIFICAR PRECIO POR UNIDAD*/
		$(".totalUnidad .txtKeyboard").focusout(function() {
			var descTotal = Array();
			var valDesc = Array();
			var cantidades = Array();
			var descuentos = Array();
			var totales = Array();
			
			$('#example tr').each(function(){
				$(this).find(".cant .txtKeyboard").each(function() {
					fila = $(this).val();
					cantidades.push(fila);
				});
				$(this).find(".valDesc .txtKeyboard").each(function() {
					fila = $(this).val();
					descuentos.push(fila);
				});
				$(this).find(".totalUnidad .txtKeyboard").each(function() {
					fila = $(this).val();
					totales.push(fila);
				});		
			});
			for(i=0;i<cantidades.length;i++){
				descTotal.push((cantidades[i]*totales[i])-descuentos[i]);
			}
			for(i=0;i<cantidades.length;i++){
				if(parseInt((cantidades[i]*totales[i])) == 0){
					valDesc.push(0);
				}else{
					valDesc.push((descuentos[i]*100)/(cantidades[i]*totales[i]));
				}
				
			}
			i=0;
			$('#example tr').each(function(){
				$(this).find(".total .NOTtxtKeyboard").each(function() {
					$(this).val(descTotal[i]);
					i++;
				});	
			});
			j=0;
			$('#example tr').each(function(){
				$(this).find(".desc .txtKeyboard").each(function() {
					$(this).val(valDesc[j]);
					j++;
				});	
			});
			/*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
			var totales = Array();
			var acumTotal = 0;
			$('#example tr').each(function(){
				$(this).find(".total .NOTtxtKeyboard").each(function() {
						fila = $(this).val();
						totales.push(fila);
						acumTotal = acumTotal + parseInt(fila);
				});
			});
			$("#totalFinal").val(acumTotal);
			$('#codigoBusq').focus();
			/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
        });	
		/*FIN ACUTALIZACIÓN DESPUÉS DE MODIFICAR PRECIO POR UNIDAD*/
		
		
		/*ACTUALIZACIÓN DESPUÉS DE MODIFICAR DESCUENTO POR %*/
		$(".desc .txtKeyboard").focusout(function() {
			var descTotal = Array();
			var valDesc = Array();
			var cantidades = Array();
			var descuentos = Array();
			var totales = Array();
			
			$('#example tr').each(function(){
				$(this).find(".cant .txtKeyboard").each(function() {
					fila = $(this).val();
					cantidades.push(fila);
				});
				$(this).find(".desc .txtKeyboard").each(function() {
					fila = $(this).val();
					descuentos.push(fila);
				});
				$(this).find(".totalUnidad .txtKeyboard").each(function() {
					fila = $(this).val();
					totales.push(fila);
				});		
			});
			for(i=0;i<cantidades.length;i++){
				descTotal.push(((cantidades[i]*totales[i])*(100-descuentos[i]))/100);
			}
			for(i=0;i<cantidades.length;i++){
				valDesc.push(((cantidades[i]*totales[i])*descuentos[i])/100);
			}
			i=0;
			$('#example tr').each(function(){
				$(this).find(".total .NOTtxtKeyboard").each(function() {
					$(this).val(descTotal[i]);
					i++;
				});	
			});
			j=0;
			$('#example tr').each(function(){
				$(this).find(".valDesc .txtKeyboard").each(function() {
					$(this).val(valDesc[j]);
					j++;
				});	
			});
			/*FIN ACTUALIZAR DESPUÉS DE MODIFICAR DESCUENTO POR %*/
			/*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
			var totales = Array();
			var acumTotal = 0;
			$('#example tr').each(function(){
				$(this).find(".total .NOTtxtKeyboard").each(function() {
						fila = $(this).val();
						totales.push(fila);
						acumTotal = acumTotal + parseInt(fila);
				});
			});
			$("#totalFinal").val(acumTotal);
			/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
			$('#codigoBusq').focus();
        });	
		/*FIN ACUTALIZACIÓN DESPUÉS DE MODIFICAR DESCUENTO*/	
		
		/*DESCUENTO EN PESOS*/
		$(".valDesc .txtKeyboard").focusout(function(){
			var descTotal = Array();
			var valDescPesos = Array();
			var cantidades = Array();
			var descuentos = Array();
			var totales = Array();
			
			$('#example tr').each(function(){
				$(this).find(".cant .txtKeyboard").each(function() {
					fila = $(this).val();
					cantidades.push(fila);
				});
				$(this).find(".valDesc .txtKeyboard").each(function() {
					fila = $(this).val();
					descuentos.push(fila);
				});
				$(this).find(".totalUnidad .txtKeyboard").each(function() {
					fila = $(this).val();
					totales.push(fila);
				});	
			});
			
			for(i=0;i<cantidades.lenght;i++){
				alert(descuentos[i]);
			}
			
				for(i=0;i<cantidades.length;i++){
					descTotal.push((cantidades[i]*totales[i])-descuentos[i]);
				}
				for(i=0;i<cantidades.length;i++){
					if(parseInt((cantidades[i]*totales[i])) == 0){
						valDescPesos.push(0);
					}else{
						valDescPesos.push((descuentos[i]*100)/(cantidades[i]*totales[i]));
					}
				}
				i=0;
				$('#example tr').each(function(){
					$(this).find(".total .NOTtxtKeyboard").each(function() {
						$(this).val(descTotal[i]);
						i++;
					});	
				});
				j=0;
				$('#example tr').each(function(){
					$(this).find(".desc .txtKeyboard").each(function() {
						$(this).val(valDescPesos[j]);
						j++;
					});	
				});
			
			/*FIN ACTUALIZAR DESPUÉS DE MODIFICAR DESCUENTO POR %*/
			/*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
			var totales = Array();
			var acumTotal = 0;
			$('#example tr').each(function(){
				$(this).find(".total .NOTtxtKeyboard").each(function() {
						fila = $(this).val();
						totales.push(fila);
						acumTotal = acumTotal + parseInt(fila);
				});
			});
			$("#totalFinal").val(acumTotal);
			$('#codigoBusq').focus();
		});
		/*FIN DESCUENTO EN PESOS*/
		$(".ui-keyboard-keyset").css('margin-top','40px');
	}
	function actualizarMatriz(codigo){
			var codigo2 = Array();
			codigo2.push(codigo);
			
			$.post('script/obtenerProductoCod.php', {cod : codigo2}, function(resPHP){
				var resCod = $.parseJSON(resPHP);
				/*if(resCod['precio']=='1'){ DESHABILITACIÓN DESCUENTO REGALO AUTOMATICO
					alert("No puede ingresar un mismo regalo 2 veces "+resCod['precio']);
				}else{*/
					var rut = $("#RUT").val();
					if(rut){
						$.post('script/obtenerProductoCodConvenioEvColl.php', {cod : codigo2}, function(resPHP){
							var result = $.parseJSON(resPHP);
							if(result['upc'] == null){
								$.post('script/consultarPromoNavidad.php', {cod : codigo2}, function(resPHP){
									var res = $.parseJSON(resPHP);
									if(res['descuento']){
										var descuentoProm = res['descuento'];
									}else{
										var descuentoProm = 0;
									}
									var articulos = Array();
									var upc = Array();
									var descripcion = Array();
									var cantidades = Array();
									var totales = Array();
									var descuentos = Array();
									var valUnidad = Array();
									var descVal = Array();
									/*NOTtxtKeyboard nombre de la clase del input text del total para que no aparezca el teclado*/
									
									$('#example tr').each(function(){
										$(this).find(".cant .txtKeyboard").each(function() {
											fila = $(this).val();
											cantidades.push(fila);
										});
										$(this).find(".total .NOTtxtKeyboard").each(function() {
											fila = $(this).val();
											totales.push(fila);
										});
										$(this).find(".desc .txtKeyboard").each(function() {
											fila = $(this).val();
											descuentos.push(fila);
										});
										$(this).find(".art").each(function() {
											fila = $(this).html();
											articulos.push(fila);
										});
										$(this).find(".upc").each(function() {
											fila = $(this).html();
											upc.push(fila);
										});
										$(this).find(".descripcion").each(function() {
											fila = $(this).html();
											descripcion.push(fila);
										});
										$(this).find(".totalUnidad .txtKeyboard").each(function() {
											fila = $(this).val();
											valUnidad.push(fila);
										});	
										$(this).find(".valDesc .txtKeyboard").each(function() {
											fila = $(this).val();
											descVal.push(fila);
										});
									});
									
									var dim = articulos.length;
									var matriz= new Array(dim);
									for (i = 0; i < dim; i++){
										matriz[i]=new Array(8);
									} 
									for(i=0;i<articulos.length;i++){
										matriz[i][0] = articulos[i];
									}
									for(i=0;i<upc.length;i++){
										matriz[i][1] = upc[i];
									}
									for(i=0;i<descripcion.length;i++){
										matriz[i][2] = descripcion[i];
									}
									for(i=0;i<cantidades.length;i++){
										matriz[i][3] = cantidades[i];
									}
									for(i=0;i<descuentos.length;i++){
										matriz[i][4] = descuentos[i];
									}
									for(i=0;i<totales.length;i++){
										matriz[i][5] = valUnidad[i];
									}
									for(i=0;i<totales.length;i++){
										matriz[i][6] = totales[i];
									}
									for(i=0;i<descVal.length;i++){
										matriz[i][7] = descVal[i];
									}
									
								 /*FIN LEER Y CARGAR TODA LA MATRIZ*/
								 /*2.- BUSCAR INDICE DEL CODIGO REPETIDO*/
									var indice;
									for(i=0;i<upc.length;i++){
										if(matriz[i][1] == codigo){
											indice = i;
										}
									}
								 /*FIN BUSCAR INDICE DEL CODIGO REPETIDO*/
								 /*3.- MODIFICAR LOS DATOS EN EL INDICE ENCONTRADO*/
									matriz[indice][3] = parseInt(matriz[indice][3]) + 1;
									matriz[indice][7] = parseInt(matriz[indice][3]) * descuentoProm;
									//matriz[indice][6] = (matriz[indice][5]*parseInt(matriz[indice][3]))-parseInt(matriz[indice][7]);
									//matriz[indice][4] = 
									matriz[indice][6] = (matriz[indice][3] * matriz[indice][5])-(matriz[indice][7]);
									
								 /*FIN MODIFICAR LOS DATOS EN EL INDICE ENCONTRADO*/
								 /*4.- INSERTAR LOS VALORES DE LA MATRIZ EN LA TABLA DESDE 0*/
									i=0;
									j=0;
									k=0;
									$('#example tr').each(function(){
										$(this).find(".cant .txtKeyboard").each(function() {
											$(this).val(matriz[i][3]);
											i++;
										});	
										$(this).find(".valDesc .txtKeyboard").each(function() {
											$(this).val(matriz[k][7]);
											k++;
										});
										$(this).find(".total .NOTtxtKeyboard").each(function() {
											$(this).val(matriz[j][6]);
											j++;
										});				
									});
								 /*FIN INSERTAR LOS VALORES DE LA MATRIZ EN LA TABLA DESDE 0*/
								 /* 5.- ACTUALIZAR TOTAL*/
								 /*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
									var totales = Array();
									var acumTotal = 0;
									$('#example tr').each(function(){
										$(this).find(".total .NOTtxtKeyboard").each(function() {
												fila = $(this).val();
												totales.push(fila);
												acumTotal = acumTotal + parseInt(fila);
										});
									});
									$("#totalFinal").val(acumTotal);
									$('#codigoBusq').focus();
									/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
								 /*FIN ACTUALIZAR TOTAL*/
								});
							}else{
								var res = $.parseJSON(resPHP);
								if(res['descuento']){
									var descuentoProm = res['descuento'];
								}else{
									var descuentoProm = 0;
								}
								var articulos = Array();
								var upc = Array();
								var descripcion = Array();
								var cantidades = Array();
								var totales = Array();
								var descuentos = Array();
								var valUnidad = Array();
								var descVal = Array();
								/*NOTtxtKeyboard nombre de la clase del input text del total para que no aparezca el teclado*/
								
								$('#example tr').each(function(){
									$(this).find(".cant .txtKeyboard").each(function() {
										fila = $(this).val();
										cantidades.push(fila);
									});
									$(this).find(".total .NOTtxtKeyboard").each(function() {
										fila = $(this).val();
										totales.push(fila);
									});
									$(this).find(".desc .txtKeyboard").each(function() {
										fila = $(this).val();
										descuentos.push(fila);
									});
									$(this).find(".art").each(function() {
										fila = $(this).html();
										articulos.push(fila);
									});
									$(this).find(".upc").each(function() {
										fila = $(this).html();
										upc.push(fila);
									});
									$(this).find(".descripcion").each(function() {
										fila = $(this).html();
										descripcion.push(fila);
									});
									$(this).find(".totalUnidad .txtKeyboard").each(function() {
										fila = $(this).val();
										valUnidad.push(fila);
									});	
									$(this).find(".valDesc .txtKeyboard").each(function() {
										fila = $(this).val();
										descVal.push(fila);
									});
								});
								
								var dim = articulos.length;
								var matriz= new Array(dim);
								for (i = 0; i < dim; i++){
									matriz[i]=new Array(8);
								} 
								for(i=0;i<articulos.length;i++){
									matriz[i][0] = articulos[i];
								}
								for(i=0;i<upc.length;i++){
									matriz[i][1] = upc[i];
								}
								for(i=0;i<descripcion.length;i++){
									matriz[i][2] = descripcion[i];
								}
								for(i=0;i<cantidades.length;i++){
									matriz[i][3] = cantidades[i];
								}
								for(i=0;i<descuentos.length;i++){
									matriz[i][4] = descuentos[i];
								}
								for(i=0;i<totales.length;i++){
									matriz[i][5] = valUnidad[i];
								}
								for(i=0;i<totales.length;i++){
									matriz[i][6] = totales[i];
								}
								for(i=0;i<descVal.length;i++){
									matriz[i][7] = descVal[i];
								}
								
							 /*FIN LEER Y CARGAR TODA LA MATRIZ*/
							 /*2.- BUSCAR INDICE DEL CODIGO REPETIDO*/
								var indice;
								for(i=0;i<upc.length;i++){
									if(matriz[i][1] == codigo){
										indice = i;
									}
								}
							 /*FIN BUSCAR INDICE DEL CODIGO REPETIDO*/
							 /*3.- MODIFICAR LOS DATOS EN EL INDICE ENCONTRADO*/
								matriz[indice][3] = parseInt(matriz[indice][3]) + 1;
								matriz[indice][7] = parseInt(matriz[indice][3]) * descuentoProm;
								//matriz[indice][6] = (matriz[indice][5]*parseInt(matriz[indice][3]))-parseInt(matriz[indice][7]);
								//matriz[indice][4] = 
								
								matriz[indice][6] = (matriz[indice][3] * matriz[indice][5])-(matriz[indice][7]);
								var res = matriz[indice][6];
								var largo = String(res).length;
								matriz[indice][6] = String(res).substring(0, largo-1)+"0";
							 /*FIN MODIFICAR LOS DATOS EN EL INDICE ENCONTRADO*/
							 /*4.- INSERTAR LOS VALORES DE LA MATRIZ EN LA TABLA DESDE 0*/
								i=0;
								j=0;
								k=0;
								$('#example tr').each(function(){
									$(this).find(".cant .txtKeyboard").each(function() {
										$(this).val(matriz[i][3]);
										i++;
									});	
									$(this).find(".valDesc .txtKeyboard").each(function() {
										$(this).val(matriz[k][7]);
										k++;
									});
									$(this).find(".total .NOTtxtKeyboard").each(function() {
										$(this).val(matriz[j][6]);
										j++;
									});				
								});
							 /*FIN INSERTAR LOS VALORES DE LA MATRIZ EN LA TABLA DESDE 0*/
							 /* 5.- ACTUALIZAR TOTAL*/
							 /*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
								var totales = Array();
								var acumTotal = 0;
								$('#example tr').each(function(){
									$(this).find(".total .NOTtxtKeyboard").each(function() {
											fila = $(this).val();
											totales.push(fila);
											acumTotal = acumTotal + parseInt(fila);
									});
								});
								$("#totalFinal").val(acumTotal);
								$('#codigoBusq').focus();
								/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
							 /*FIN ACTUALIZAR TOTAL*/
							}
						});
					}else{
						$.post('script/consultarPromoNavidad.php', {cod : codigo2}, function(resPHP){
							var res = $.parseJSON(resPHP);
							if(res['descuento']){
								var descuentoProm = res['descuento'];
							}else{
								var descuentoProm = 0;
							}
							var articulos = Array();
							var upc = Array();
							var descripcion = Array();
							var cantidades = Array();
							var totales = Array();
							var descuentos = Array();
							var valUnidad = Array();
							var descVal = Array();
							/*NOTtxtKeyboard nombre de la clase del input text del total para que no aparezca el teclado*/
							
							$('#example tr').each(function(){
								$(this).find(".cant .txtKeyboard").each(function() {
									fila = $(this).val();
									cantidades.push(fila);
								});
								$(this).find(".total .NOTtxtKeyboard").each(function() {
									fila = $(this).val();
									totales.push(fila);
								});
								$(this).find(".desc .txtKeyboard").each(function() {
									fila = $(this).val();
									descuentos.push(fila);
								});
								$(this).find(".art").each(function() {
									fila = $(this).html();
									articulos.push(fila);
								});
								$(this).find(".upc").each(function() {
									fila = $(this).html();
									upc.push(fila);
								});
								$(this).find(".descripcion").each(function() {
									fila = $(this).html();
									descripcion.push(fila);
								});
								$(this).find(".totalUnidad .txtKeyboard").each(function() {
									fila = $(this).val();
									valUnidad.push(fila);
								});	
								$(this).find(".valDesc .txtKeyboard").each(function() {
									fila = $(this).val();
									descVal.push(fila);
								});
							});
							
							var dim = articulos.length;
							var matriz= new Array(dim);
							for (i = 0; i < dim; i++){
								matriz[i]=new Array(8);
							} 
							for(i=0;i<articulos.length;i++){
								matriz[i][0] = articulos[i];
							}
							for(i=0;i<upc.length;i++){
								matriz[i][1] = upc[i];
							}
							for(i=0;i<descripcion.length;i++){
								matriz[i][2] = descripcion[i];
							}
							for(i=0;i<cantidades.length;i++){
								matriz[i][3] = cantidades[i];
							}
							for(i=0;i<descuentos.length;i++){
								matriz[i][4] = descuentos[i];
							}
							for(i=0;i<totales.length;i++){
								matriz[i][5] = valUnidad[i];
							}
							for(i=0;i<totales.length;i++){
								matriz[i][6] = totales[i];
							}
							for(i=0;i<descVal.length;i++){
								matriz[i][7] = descVal[i];
							}
							
						 /*FIN LEER Y CARGAR TODA LA MATRIZ*/
						 /*2.- BUSCAR INDICE DEL CODIGO REPETIDO*/
							var indice;
							for(i=0;i<upc.length;i++){
								if(matriz[i][1] == codigo){
									indice = i;
								}
							}
						 /*FIN BUSCAR INDICE DEL CODIGO REPETIDO*/
						 /*3.- MODIFICAR LOS DATOS EN EL INDICE ENCONTRADO*/
							matriz[indice][3] = parseInt(matriz[indice][3]) + 1;
							matriz[indice][7] = parseInt(matriz[indice][3]) * descuentoProm;
							//matriz[indice][6] = (matriz[indice][5]*parseInt(matriz[indice][3]))-parseInt(matriz[indice][7]);
							//matriz[indice][4] = 
							matriz[indice][6] = (matriz[indice][3] * matriz[indice][5])-(matriz[indice][7]);
							
						 /*FIN MODIFICAR LOS DATOS EN EL INDICE ENCONTRADO*/
						 /*4.- INSERTAR LOS VALORES DE LA MATRIZ EN LA TABLA DESDE 0*/
							i=0;
							j=0;
							k=0;
							$('#example tr').each(function(){
								$(this).find(".cant .txtKeyboard").each(function() {
									$(this).val(matriz[i][3]);
									i++;
								});	
								$(this).find(".valDesc .txtKeyboard").each(function() {
									$(this).val(matriz[k][7]);
									k++;
								});
								$(this).find(".total .NOTtxtKeyboard").each(function() {
									$(this).val(matriz[j][6]);
									j++;
								});				
							});
						 /*FIN INSERTAR LOS VALORES DE LA MATRIZ EN LA TABLA DESDE 0*/
						 /* 5.- ACTUALIZAR TOTAL*/
						 /*ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
							var totales = Array();
							var acumTotal = 0;
							$('#example tr').each(function(){
								$(this).find(".total .NOTtxtKeyboard").each(function() {
										fila = $(this).val();
										totales.push(fila);
										acumTotal = acumTotal + parseInt(fila);
								});
							});
							$("#totalFinal").val(acumTotal);
							$('#codigoBusq').focus();
							/*FIN ACUMULAR TOTAL AL AGREGAR UNA NUEVA FILA*/
						 /*FIN ACTUALIZAR TOTAL*/
						});
					}
				//} DESHABILITACIÓN DESCUENTO REGALO AUTOMATICO
			});
	}
	function limiteVenta(codigo){
		/*
			1.- Cargar arreglo de UPC y Cantidad
			2.- Encontrar Cantidad según el Código
			3.- Retornar Cantidad encontrada
		*/
		/*1.- CARGAR ARREGLO DE UPC Y CANTIDAD*/
			var upc = Array();
			var cantidades = Array();
			
			$('#example tr').each(function(){
				$(this).find(".cant .txtKeyboard").each(function() {
					fila = $(this).val();
					cantidades.push(fila);
                });
				$(this).find(".upc").each(function() {
					fila = $(this).html();
					upc.push(fila);
				});
			});			
		/*FIN CARGAR ARREGLO DE UPC Y CANTIDAD*/
		/*2.- ENCONTRAR CANTIDAD SEGUN EL CODIGO*/
			var indice;
			for(i=0;i<upc.length;i++){
				if(upc[i] == codigo){
					indice = i;
				}
			}
		/*FIN ENCONTRAR CANTIDAD SEGUN EL CODIGO*/
		/*3.- RETORNAR CANTIDAD ENCONTRADA*/
			return cantidades[indice];
		/*FIN RETORNAR CANTIDAD ENCONTRADA*/
	}
	
	/*ACTUALIZAR PARA CERRAR SESIÓN DESPUÉS DE 10 SEGUNDOS DE INACTIVIDAD*/
	var tim = 0;
	function reload () {
		tim = setTimeout("location.reload(true);",30000);   // 3 minutes
	}
	function canceltimer() {
		window.clearTimeout(tim);  // cancel the timer on each mousemove/click
		reload();  // and restart it
	}
	/*FIN ACTUALIZAR PARA CERRAR SESIÓN*/
	$(document).ready(function() {
		var toggleKeysIfEmpty = function( kb ) {
			var toggle = kb.$preview.val() == '';
			console.log( toggle, kb.$preview.val() );
			kb.$keyboard
				.find('.ui-keyboard-bksp, .ui-keyboard-accept')
				.toggleClass('disabled', toggle)
				.click('disabled', toggle)
				.prop('disabled', toggle);
			};
		$('.txtKeyboardRUT').keyboard({
     			layout: 'custom',
				customLayout: {
					'default': [
						'1 2 3',
						'4 5 6',
						'7 8 9',
						'0 K',
						' {accept} {cancel} {bksp}'
						]
				},	
				visible : function(e, keyboard) {
					toggleKeysIfEmpty( keyboard );
				},
				change: function (e, keyboard) {
					toggleKeysIfEmpty( keyboard );
				},
				restrictInput : true,
				preventPaste : true, 
				autoAccept : false
		}).addTyping();
		$(".ui-keyboard-keyset").css('margin-top','40px');
		eliminarProd();
		$('#consultarCliente').click(function() {
			var contRut = 0;
			var rutVar = $('#RUT').val();
			var largo = rutVar.length;
			if(largo == 9){
				var rut = rutVar.substr(0,8)+"-"+rutVar.substr(8,1);
				$('#RUT').val(rut);
				contRut++;
			}else if(largo == 8){
				var rut = rutVar.substr(0,7)+"-"+rutVar.substr(7,1);
				$('#RUT').val(rut);
				contRut++;
			}else{
				alert("Ingrese un rut válido");
				$('#RUT').val("");
			}
			var json = {
				rut : rut
			};
			if(contRut == 1){
				$.post('script/obtenerNombre.php',{formulario : json},function(respuesta){
					var resPHP = $.parseJSON(respuesta);
					if(resPHP['nombres'] == null){
						alert("Cliente no registrado");
						$('#RUT').val("");
					}else{
						$("#socio").html("<label>Nombre cliente: "+resPHP['nombres']+" "+resPHP['apellidoPaterno']+" "+resPHP['apellidoMaterno']+"</label><br><label>Tipo de convenio: "+resPHP['empresa']+"</label><br>");
						$("#Modal").click();
						//alert("Cliente "+ resPHP['nombres'] +" registrado, descuento permitido");
					}
				});
				$('#codigoBusq').focus();
			}else{
				$('#RUT').focus();
			}
        });
		$('.selectpicker').selectpicker();
		
		$('#codigoBusq').focus();
			
		$("#codigoBusq").keypress(function(e){
			var code = e.keyCode || e.which;
			if(code == 13) { 
				window.codBusq = $("#codigoBusq").val();
				if(codBusq == ""){
					alert("Por favor ingrese un código de barra");
				}else{
					/*COMPROBAR SI ES PACK*/
					var codigoPack = Array();
					codigoPack.push(codBusq);
					$.post('script/comprobarPack.php', {codPack : codigoPack}, function(resPHP){
						var resPack = $.parseJSON(resPHP);
						/*SI ENCUENTRA UN PACK CON EL CÓDIGO INGRESADO*/
						if(resPack['codPack'] != ''){
							$('#example tr:last').after("<tr><td class='success art'>"+resPack['articulo']+"</td><td class='success upc'>"+resPack['codPack']+"</td><td class='success descripcion'>"+resPack['descripcion']+"</td><td class='cant success'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(223, 240, 216) none repeat scroll 0% 0%; color:#000;' type='text' value='1' disabled/></td></td><td class='desc success'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(223, 240, 216) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc success'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(223, 240, 216) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='totalUnidad success'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(223, 240, 216) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resPack['total']+"'/></td><td class='total success'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(223, 240, 216) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resPack['total']+"'/></td><td class='success'><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
							actualizarTabla();
							eliminarProd();	
							$("#codigoBusq").val("");
							$('#codigoBusq').focus();
							var codPack = resPack['codPack'];
							$.post('script/obtenerProductoPack.php',{codigo : codPack},function(resPHP){
								var result = $.parseJSON(resPHP);
								for(i=0;i<result.length;i++){
									if(result[i]['precio'] != 1){
										var precio = parseInt(result[i]['precio']) - 1;
									}else{
										var precio = parseInt(result[i]['precio']);
									}
									$('#example tr:last').after("<tr><td class='warning art'>"+result[i]['articulo']+"</td><td class='warning upc'>"+result[i]['upc']+"</td><td class='warning descripcion'>"+result[i]['descripcion']+"</td><td class='cant warning'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(252, 248, 227) none repeat scroll 0% 0%; color:#000;' type='text' value='1' disabled/></td></td><td class='desc warning'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(252, 248, 227) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc warning'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(252, 248, 227) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='totalUnidad warning'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(252, 248, 227) none repeat scroll 0% 0%; color:#000;' type='text' value='"+precio+"'/></td><td class='total warning'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(252, 248, 227) none repeat scroll 0% 0%; color:#000;' type='text' value='"+precio+"'/></td><td class=''><input class='warning btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
									eliminarProd();	
								}								
							});
						/*SI ALGUNO DE LOS PRODUCTOS NO TIENE LOTES DISPONIBLES*/							
						}else if((resPack['codPack'] == '')&&(resPack['descripcion'] == '1')){
							alert("Uno o más productos del pack no tiene lotes disponibles");
							$("#codigoBusq").val("");
							$('#codigoBusq').focus();
						/*SI NINGUNO DE LOS PRODUCTOS TIENE LOTES DISPONIBLES*/
						}else if((resPack['codPack'] == '')&&(resPack['descripcion'] == '0')){
							alert("Ningún producto del pack tiene lotes disponibles");
							$("#codigoBusq").val("");
							$('#codigoBusq').focus();
						/*SI EL CODIGO INGRESADO NO ES UN PACK*/
						}else{
							var codigoCantidad = Array();
							codigoCantidad.push(codBusq);
							$.post('script/obtenerProductoCant.php', {codCantidad : codigoCantidad}, function(resPHP){
								var respuesta = $.parseJSON(resPHP);
								//INICIO FUNCIÓN PARA SUMAR PRODUCTOS EN UNA MISMA FILA EN LA TABLA
								if((parseInt(respuesta['cantidad']) > 0))	{ //&& (comprobarLimite <= respuesta['cantidad'])
									resBusqueda = busqCodEncontrado(codBusq);
									if((resBusqueda == 1)){
										var comprobarLimite;
										comprobarLimite = parseInt(limiteVenta(codBusq));
										if(parseInt(respuesta['cantidad']) > comprobarLimite){
											actualizarMatriz(codBusq);
											$("#codigoBusq").val("");
											$('#codigoBusq').focus();
										}else{
											var codigo = Array();
											codigo.push(codBusq);
											$.post('script/obtenerDSM.php', {cod : codigo}, function(resPHP){
												if(resPHP!=""){
													alert("Actualmente tiene stock en un DSM pendiente, Número de DSM: "+resPHP);
													$("#codigoBusq").val("");
													$('#codigoBusq').focus();
												}else{
													alert("No dispone de stock suficiente");
													$("#codigoBusq").val("");
													$('#codigoBusq').focus();
												}
											});
										}
									}else{
										var codigo = Array();
										codigo.push(codBusq);
										var rut = $("#RUT").val();
										if(rut){
											$.post('script/obtenerProductoCodConvenioEvColl.php',{cod : codigo},function(resPHP){
													var result = $.parseJSON(resPHP);
													if(result['upc'] == null){
														$.post('script/consultarPromoNavidad.php', {cod : codigo},function(resPHP){
															var resProm = $.parseJSON(resPHP);
															if(resProm['upc']){
																		$('#example tr:last').after("<tr><td class='info art'>"+resProm['articulo']+"</td><td class='info upc'>"+resProm['upc']+"</td><td class='info descripcion'>"+resProm['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resProm['descuento']+"'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resProm['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resProm['precioOferta']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
																		actualizarTabla();
																		eliminarProd();	
																		$("#codigoBusq").val("");
																		$('#codigoBusq').focus();
															}else{ //ESCANEO NORMAL DE PRODUCTOS
																$.post('script/obtenerProductoCod.php',{cod : codigo},function(resPHP){
																	var result = $.parseJSON(resPHP);
																	if(result['upc'] == null){
																		alert("Artículo o producto no encontrado");
																		$("#codigoBusq").val("");
																	}else{
																		/*var rowCount = $('#example tr').length; DESHABILITACIÓN DESCUENTO REGALO AUTOMATICO
																		var prodRegular = rowCount-1;
																		//alert(prodRegular);
																		var valActual = $('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val();
																		if(valActual == '1' && result['precio'] == '1'){
																			alert("el producto anterior es un regalo, ingrese otro producto regular para realizar otro regalo.");
																		}
																		else if(result['precio'] == '1'){
																			$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
																																			
																			var rowCount = $('#example tr').length;
																			var prodRegular = rowCount-2;
																			//alert(prodRegular);
																			var valActual = $('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val();
																			
																			var restaUno = parseInt(valActual) - 1;
																			//$('#example tr:eq('+prodRegular+')').find('td').find('.txtKeyboard').eq(3).val(restaUno);
																			$('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val(restaUno);
																			
																			actualizarTabla();
																			eliminarProd();	
																		}else{*/
																			$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
																			actualizarTabla();
																			eliminarProd();	
																		//}	DESHABILITACIÓN DESCUENTO REGALO AUTOMATICO
																	}
																	$("#codigoBusq").val("");
																});
																$("#codigoBusq").val("");
															}
														});
													}else{
														/*var rowCount = $('#example tr').length;
														var prodRegular = rowCount-1;
														//alert(prodRegular);
														var valActual = $('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val();
														if(valActual == '1' && result['precio'] == '1'){
															alert("el producto anterior es un regalo, ingrese otro producto regular para realizar otro regalo.");
														}
														else if(result['precio'] == '1'){
															$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['descuento']+"'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['total']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
															
															var rowCount = $('#example tr').length;
															var prodRegular = rowCount-2;
															//alert(prodRegular);
															var valActual = $('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val();
															//alert(valActual);
															var restaUno = parseInt(valActual) - 1;
															//$('#example tr:eq('+prodRegular+')').find('td').find('.txtKeyboard').eq(3).val(restaUno);
															$('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val(restaUno);
															//alert(restaUno);
															actualizarTabla();
															eliminarProd();	
														}else{*/
															$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['descuento']+"'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['total']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
															actualizarTabla();
															eliminarProd();	
														//}	DESHABILITACIÓN DE DESCUENTO AUTOMÁTICO DE REGALO EN CONVENIO CON EMPESAS
													}
													$("#codigoBusq").val("");
												});
												$("#codigoBusq").val("");
										}else{
											//COMPROBACIÓN SI TIENE DESCUENTO AUTOMÁTICO POR PARTE DE BM EN MANTENEDOR DE DESCUENTOS
											$.post('script/consultarPromoNavidad.php', {cod : codigo},function(resPHP){
												var resProm = $.parseJSON(resPHP);
												if(resProm['upc']){
															$('#example tr:last').after("<tr><td class='info art'>"+resProm['articulo']+"</td><td class='info upc'>"+resProm['upc']+"</td><td class='info descripcion'>"+resProm['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resProm['descuento']+"'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resProm['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+resProm['precioOferta']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
															actualizarTabla();
															eliminarProd();	
															$("#codigoBusq").val("");
															$('#codigoBusq').focus();
												}else{ //ESCANEO NORMAL DE PRODUCTOS
													$.post('script/obtenerProductoCod.php',{cod : codigo},function(resPHP){
														var result = $.parseJSON(resPHP);
														if(result['upc'] == null){
															alert("Artículo o producto no encontrado");
															$("#codigoBusq").val("");
														}else{
															/*var rowCount = $('#example tr').length; DESHABILITACIÓN DESCUENTO REGALO AUTOMATICO
															var prodRegular = rowCount-1;
															//alert(prodRegular);
															var valActual = $('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val();
															if(valActual == '1' && result['precio'] == '1'){
																alert("el producto anterior es un regalo, ingrese otro producto regular para realizar otro regalo.");
															}
															else if(result['precio'] == '1'){
																$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
																																
																var rowCount = $('#example tr').length;
																var prodRegular = rowCount-2;
																//alert(prodRegular);
																var valActual = $('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val();
																
																var restaUno = parseInt(valActual) - 1;
																//$('#example tr:eq('+prodRegular+')').find('td').find('.txtKeyboard').eq(3).val(restaUno);
																$('#example tr:eq('+prodRegular+')').find('td').find('.NOTtxtKeyboard').eq(0).val(restaUno);
																
																actualizarTabla();
																eliminarProd();	
															}else{*/
																$('#example tr:last').after("<tr><td class='info art'>"+result['articulo']+"</td><td class='info upc'>"+result['upc']+"</td><td class='info descripcion'>"+result['descripcion']+"</td><td class='cant'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='1'/></td></td><td class='desc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='valDesc'><input class='txtKeyboard' style='width:65px; margin-right:0px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='0'/></td><td class='totalUnidad'><input class='txtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class='total'><input class='NOTtxtKeyboard' style='width:65px; border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000;' type='text' value='"+result['precio']+"'/></td><td class=''><input class='btn btn-danger btnEliminarProd' type='button' value='X'/></td></tr>");
																actualizarTabla();
																eliminarProd();	
															//}	DESHABILITACIÓN DESCUENTO REGALO AUTOMATICO
														}
														$("#codigoBusq").val("");
													});
													$("#codigoBusq").val("");
												}
											});
										}
									}
									//FIN FUNCIÓN PARA SUMAR PRODUCTOS EN UNA MISMA FILA EN LA TABLA
								}else if(respuesta['cantidad'] == null){
									alert("Artículo o producto no encontrado");
									$("#codigoBusq").val("");
									$('#codigoBusq').focus();
								}
								else{
									var codigo = Array();
									codigo.push(codBusq);
									$.post('script/obtenerDSM.php', {cod : codigo}, function(resPHP){
										if(resPHP!=""){
											alert("Actualmente tiene stock en un DSM pendiente, Número de DSM: "+resPHP);
											$("#codigoBusq").val("");
											$('#codigoBusq').focus();
										}else{
											alert("No dispone de stock suficiente");
											$("#codigoBusq").val("");
											$('#codigoBusq').focus();
										}
									});
								}
							});
						}
					});
					/*FIN COMPROBAR SI ES PACK*/	
				}
			}
		});
		
		$("#btnImprimir").click(function(){
			if($("#totalFinal").val() == 0){
				alert("La Pre venta no puede tener un valor total de 0, corregir para continuar.");
				$('#codigoBusq').focus();
			}else{
				var acumTotal = 0;
				var articulos = Array();
				var upc = Array();
				var descripcion = Array();
				var cantidades = Array();
				var totales = Array();
				var descuentos = Array();
				var valDescuentos = Array();
				var valUnidad = Array();
				/*NOTtxtKeyboard nombre de la clase del input text del total para que no aparezca el teclado*/
				
				$('#example tr').each(function(){
					$(this).find(".cant .txtKeyboard").each(function() {
						fila = $(this).val();
						cantidades.push(fila);
					});
					$(this).find(".total .NOTtxtKeyboard").each(function() {
						fila = $(this).val();
						totales.push(fila);
						acumTotal = acumTotal + parseInt(fila);
					});
					$(this).find(".desc .txtKeyboard").each(function() {
						fila = $(this).val();
						descuentos.push(fila);
					});
					$(this).find(".art").each(function() {
						fila = $(this).html();
						articulos.push(fila);
					});
					$(this).find(".upc").each(function() {
						fila = $(this).html();
						upc.push(fila);
					});
					$(this).find(".valDesc .txtKeyboard").each(function() {
						fila = $(this).val();
						valDescuentos.push(fila);
					});
					$(this).find(".descripcion").each(function() {
						fila = $(this).html();
						//alert(fila);
						descripcion.push(fila);
					});	
					$(this).find(".totalUnidad .txtKeyboard").each(function() {
						fila = $(this).val();
						valUnidad.push(fila);
					});
				});	
				var contProdCero = 0;
				for(i=0;i<totales.length;i++){
					if(totales[i] == 0){
						contProdCero = 1;
					}
				}
				if(contProdCero == 1){
					alert("Todos los productos deben tener un VALOR TOTAL (P$T$ PV) diferente a 0");
					$('#codigoBusq').focus();
				}else{
					var contMaxTotal = 0;
					for(i=0;i<cantidades.length;i++){
						if(parseInt(valDescuentos[i])>=parseInt(totales[i])){
							contMaxTotal = 0; //corregido para navidad, resolver en futuro
						}
					}	
//CONTINUAR AQUI MAÑANA, PROBLEMA CON EL CONTROL DE LOS DESCUENTOS QUE SUPERAN EL VALOR TOTAL.					
					if(contMaxTotal == 1){
						alert("Un descuento no puede ser superior al valor del producto, por favor corregir para continuar.")
						contMaxTotal = 0;
						$('#codigoBusq').focus();
					}else{
						contMaxTotal = 0;
						/*for(i=0;i<valDescuentos.length;i++){
							alert(valDescuentos[i]);
						}*/		

						//alert($("#totalFinal").val());
						//$("#totalFinal").val(acumTotal);
						
						var dim = articulos.length+1;
						var matriz= new Array(dim);
						for (i = 0; i < dim; i++){
							matriz[i]=new Array(7);
						} 
						for(i=0;i<articulos.length;i++){
							matriz[i][0] = articulos[i];
						}
						for(i=0;i<upc.length;i++){
							matriz[i][1] = upc[i];
						}
						for(i=0;i<descripcion.length;i++){
							matriz[i][2] = descripcion[i];
						}
						for(i=0;i<cantidades.length;i++){
							matriz[i][3] = cantidades[i];
						}
						for(i=0;i<descuentos.length;i++){
							matriz[i][4] = descuentos[i];
						}
						for(i=0;i<totales.length;i++){
							matriz[i][5] = totales[i];
						}
						for(i=0;i<totales.length;i++){
							matriz[i][6] = valDescuentos[i];
						}
						var tam = matriz.length-1;
						matriz[tam][0] = $("#totalFinal").val();
						matriz[tam][1] = $("#numPreVenta").val();
						matriz[tam][2] = window.fechaIdVenta;
						matriz[tam][3] = '<?php echo utf8_encode($_SESSION['vendedor']);?>';
						matriz[tam][4] = '<?php echo $_SESSION['slp']; ?>';
						if($("#RUT").val() == ''){
							matriz[tam][5] = '1-9';
						}else{
							//matriz[tam][5] = $("#RUT").val();
							matriz[tam][5] = '1-9';
						}
						$.post('../boleta.php', {arreglo : matriz}, function(data) {
						//var win=window.open('about:blank','',"width=247, height=500");
							var win=window.open('',"myWindow","width=247, height=530");
							with(win.document){
							  open();
							  write(data);
							  close();
							}
						});
						return false;
					}
				}
			}
		});
		
		$("#btnLimpiar").click(function(){
			$("#example").find("td").each(function() {
                $(this).fadeOut("normal", function(){
					$(this).remove();
            	})
			});
			$("#totalFinal").val("");
			$("#codigoBusq").focus();
		});
		
		/*FUNCIÓN PARA OBTENER IP DEL EQUIPO*/
	
		var ip = '<?php echo $_SESSION['bodega'];?>';
		var json = {
			ip:ip
		};
		window.bodega = 0;
		$.post('script/obtenerIP.php',{form : json},function(resPHP){
			respuesta = $.parseJSON(resPHP);
			var bodega = respuesta['bodega'];
						
			var tipoDoctoSel = $('#tipoVenta').val();
			if(tipoDoctoSel == "Boleta fiscal"){
				window.tipoDocto = 4;
			}else if(tipoDoctoSel == "Factura"){
				window.tipoDocto = 2;
			}else if(tipoDoctoSel == "Nota de crédito"){
				window.tipoDocto = 3;
			}else{
				window.tipoDocto = 1;
			}
			var d = new Date();
			var month = d.getMonth()+1;
			var day = d.getDate();

			window.fechaIdVenta = d.getFullYear() + 
				(month<10 ? '0' : '') + month +
				(day<10 ? '0' : '') + day;

			//alert(window.fechaIdVenta);
			$("#numPreVenta").val(window.tipoDocto+"1"+bodega+"xxxxx"+window.fechaIdVenta);
		});
		$("#tipoVenta").change(function(){
			var ip = '<?php echo $_SESSION['bodega'];?>';
			var json = {
				ip:ip
			};
			window.bodega = 0;
			$.post('script/obtenerIP.php',{form : json},function(resPHP){
				respuesta = $.parseJSON(resPHP);
				var bodega = respuesta['bodega'];
			
				var tipoDoctoSel = $('#tipoVenta').val();
				if(tipoDoctoSel == "Boleta fiscal"){
					window.tipoDocto = 1;
				}else if(tipoDoctoSel == "Factura"){
					window.tipoDocto = 2;
				}else if(tipoDoctoSel == "Nota de crédito"){
					window.tipoDocto = 3;
				}else{
					window.tipoDocto = 4;
				}
				
				var d = new Date();

				var month = d.getMonth()+1;
				var day = d.getDate();

				window.fechaIdVenta = d.getFullYear() + 
					(month<10 ? '0' : '') + month +
					(day<10 ? '0' : '') + day;

				$("#numPreVenta").val(window.tipoDocto+"1"+bodega+"xxxxx"+window.fechaIdVenta);
			});
		});
	
	/*FIN FUNCIÓN PARA OBTENER IP DEL EQUIPO*/
		
		$("#cerrarSesion").click(function(){
			var session_destroy = '<?php session_destroy();?>';
			location.href='../index.php';
		});
		
		$("#closeModal").click(function(){
			$("#codigoBusq").focus();
		});
	});
</script>
<!-- AUTO REFRESH PARA AUTODESLOGEO DE SESION-->
<script type = "text/javascript">

</script>
<!-- FIN AUTO REFRESH -->
</head>

<!--<body onmousemove = "canceltimer()" onclick = "canceltimer()">-->
<body>
<br>
	<div class="row" style="margin-left:10px; margin-right:10px; margin-top:0px;">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="form-group">
					<label for="exampleInputFile">Vendedor</label><button id="cerrarSesion" class="btn btn-danger" style="float:right;">Cerrar Sesión</button>
					<input type="email" class="form-control" style="width:300px;" id="vendedor" placeholder="Número Pre venta" value="<?php echo utf8_encode($_SESSION['vendedor']);?>" disabled>
			</div>
			
		</div>
    	<div class="col-lg-3 col-md-3 col-sm-3" style="display:;">
        	<div class="form-group" style="display:;">
            	<label for="exampleInputEmail1">RUT Cliente Asociado</label>
                <input type="email" class="form-control txtKeyboardRUT" id="RUT" placeholder="Rut" value="">
				<button id="consultarCliente" class="btn btn-info" style="margin-top:10px; padding: 10px 15px 10px 15px;">Consultar RUT</button>
            </div>
            <!--<div class="form-group">
             	<label for="exampleInputPassword1">Nombre Completo</label>
                <input type="email" class="form-control" id="nombreCompleto" placeholder="Nombre Completo" value="CLIENTE GENERICO LOCAL" disabled>
            </div>-->
            <div class="form-group" style="display:none;">
             	<label for="exampleInputFile">Número de preventa</label>
                <input type="email" class="form-control" id="numPreVenta" placeholder="Número Preventa" disabled>
            </div>
            <div class="form-group" style="width:100%; display:none;">
             	<label for="exampleInputFile" style="width:100%;">Tipo Preventa</label><br />
                <select class="selectpicker" id="tipoVenta" style="width:100%;">
                	<option>Boleta fiscal</option>
                    <option>Factura</option>
                    <option>Nota de crédito</option>
                    <option>Boleta manual</option>
                </select>
            </div>
            <div class="form-group" style="display:none;">
            	<div class="form-group">
					<label for="exampleInputFile">Vendedor</label>
					<input type="email" class="form-control" id="vendedor" placeholder="Número Pre venta" value="<?php echo utf8_encode($_SESSION['vendedor']);?>" disabled>
				</div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12">
        	<div class="form-group">
                <label for="exampleInputEmail1">Código a buscar</label>
                <!--<input type="text" class="form-control" id="codigoBusq" placeholder="Código" style="width:300px;" onmousemove = "canceltimer()" onclick = "canceltimer()" onkeypress = "canceltimer()"><br />-->
				<input type="text" class="form-control" id="codigoBusq" placeholder="Código" style="width:300px;"><br />
				<!-- -->
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th><label style="font-size:20px;">Art#</label></th>
                            <th><label style="font-size:20px;">UPC</label></th>
                            <th><label style="font-size:20px;">Descripción</label></th>
                            <th><label style="font-size:20px;">Cant</label></th>
                            <th><label style="font-size:20px;">Desc%</label></th>
							<th><label style="font-size:20px;">Desc$</label></th>
                            <th><label style="font-size:20px;">Val unidad</label></th>
                            <th><label style="font-size:20px;">P$T$ PV</label></th>
                            <th style="width:15px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <table id="total" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="82%" style="text-align:right; font-size:18px;">Total</th>
                            <th><input type="text" id="totalFinal" style='border: 0px; background:rgb(255, 255, 255) none repeat scroll 0% 0%; color:#000; font-size:18px;' disabled/></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            	<button type="submit" id ="btnImprimir" class="btn btn-success" style="width:160px; height:100px; margin-left:100px;">Imprimir</button>
                <button type="submit" id ="btnLimpiar" class="btn btn-info pull-right" style="width:160px; height:100px; margin-right:100px;">Limpiar</button>
        	</div>
    	</div><!-- FIN DEL ROW -->
    </div>
	
	<button id="Modal" type="button" class="btn btn-primary" data-toggle="modal" data-target=".modal" style="display:none;">Large modal</button>

		<div class="modal fade" role="dialog" aria-labelledby="gridSystemModalLabel">
			<div class="modal-dialog" role="document" style="width:50%;">
				<div class="modal-content">
				  <div class="modal-header" style="background-color:#E52524; color:#fff;">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="gridSystemModalLabel">Convenio con empresas - Vicencio Perfumerías</h4>
				  </div>
				  <div class="modal-body">
					<div id="convenio">
						<div id="socio">
							<label>Nombre cliente: </label><br>
							<label>Tipo de convenio: </label><br>
						</div>
						<label>Descuentos Perfumería: </label><br>
						<ul>
							<li>10% de descuento en todas las líneas excepto Dior y Chanel.</li>
						</ul>
						<label>Descuentos Vestuario y Accesorios: </label><br>
						<ul>
							<li>20% vestuario Hugo Boss.</li>
							<li>20% cueros Fedón y bijoutería fina italiana Antica Murrina.
						</ul>
						<label>Descuentos Make Up y Skin Care: </label><br>
						<ul>
							<li>20% maquillaje y tratamiento PUPA</li>
							<li>10% maquillajes y tratamiento H2O, Clarins, Anne Möller y Revlon.
						</ul>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" id="closeModal" class="btn btn-danger" data-dismiss="modal" style="padding:10px 30px 10px 30px;" aria-label="Close"><span aria-hidden="true">Cerrar</span></button>
				  </div>
				</div>
			</div>
		</div><!-- /.modal -->
	
</body>
</html>
