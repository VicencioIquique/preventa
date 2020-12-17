// JavaScript Document para tabla magica de agregar y eliminar

            $(document).ready(function(){
			
				

				//$("#horizontalForm").validate();
				fn_total();
				fn_cantidad();
				$('a').css('cursor', 'pointer');
				
				
				/**para resaltar los inputs ***/
				 $('input:text,select ').focus(function() {
					$(this).stop().animate({ borderColor: '#14b5e3' }, 250);
						}).blur(function() {
							$(this).stop().animate({ borderColor: '#c9c9c9' }, 250);
						});
                //FIN INPUTS
				
				 
            });
			
			function fn_cantidad(){
				cantidad = $("#ssptable tbody").find("tr").length;
				$("#span_cantidad").html(cantidad);
			};
			
			function fn_total() {
					var re;
					var valor = 0
					$('#preform').find('.totalprod').each(function(){
						re = $(this).val();
						valor += parseFloat(re)
					});
					$('#total').val(valor.toFixed(0));
				}
/***************************** PAIS ************************************************************************************/ 
		 function fn_agregar_usuario(){
			
				$.get("modulos/usuarios/obtenerid.php",function(data){
					//	$('#idpais').attr("id",data);
						data++;
						alert("Usuario agregado con el id "+data +" exitosamente!");
						cadena = "<tr>";
				 		cadena = cadena + "<td>" + $("#usuario").val() + "</td>"; 
						cadena = cadena + "<td>" + $("#nombre").val() + "</td>"; 
						cadena = cadena + "<td>" + $("#rol").val() + "</td>"; 
						cadena = cadena + "<td>" + $("#modulo option:selected").text()+ "</td>"; 
				 		
						$("#ssptable tbody").append(cadena);
						$.post("modulos/usuarios/agregar.php", {ide: data, usuario: $("#usuario").val(),nombre: $("#nombre").val(),rol: $("#rol").val(),modulo: $("#modulo").val(),pass: $("#pass").val()});
						fn_eliminar_pais().flush();
						fn_cantidad();
						
					});
					
            }; // fin funcion agregar usuario
			
  function fn_agregar_nuevaSolicitud(){
                              
                    //aqui puedes enviar un conunto de tados ajax para agregar al usuairo
                    $.post("modulos/solicitudes/agregar.php", {usuario_id: $("#usuario_id").val(),modulo: $("#modulo").val()});
                    
                fn_eliminar_tipo();
				fn_cantidad();
                alert("Nueva Solicitud Creada agregado");
				location.href = 'index.php?opc=nuevaSolicitud' ;
            };
			
	function fn_eliminar_solicitud(){
                $("a.elimina_solicitud").click(function(){
                    id = $(this).attr("id");
                    respuesta = confirm("Desea eliminar esta solicitud : " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             //alert("cliente " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/solicitudes/eliminar.php", {id_sol: id})
                            
                        })
                    }
                });
            };
			
			
         function fn_agregar_lista(){
				
				if($("#cant").val() >0)
				{
					
					
				 var articulo = $("#articulo").val().split('|');
				 var sku = articulo[1];
				 var descrip = articulo[0];
				 //alert (sku);
					
				$.get("modulos/solicitudes/productoenlista.php?sku="+sku+"&idsol="+$("#idsol").val(),function(idart){ // para preguntar si esta la guia de despacho creada anteriormente
																											  
				//alert (idart);
				
				 if(idart == 1)
				 {
						$.post("modulos/solicitudes/agregarlistamas.php", { idsol: $("#idsol").val(), cant: $("#cant").val(), sku: sku});
						alert("Articulo Sumado");
						location.href = 'index.php?opc=paso2&idsol='+ $("#idsol").val() ;
				 }
				 else if(idart == 0) // el producto no ha sido ingresado anteriormente
				 {
						cadena = "<tr>";
						cadena = cadena + "<td>" +sku + "</td>";
						cadena = cadena + "<td>" + descrip + "</td>";
						cadena = cadena + "<td>" + $("#stock").val() + "</td>";
						cadena = cadena + "<td>" + $("#cant").val() + "</td>";
						cadena = cadena + "<td>" + $("#cant").val() + "</td>";
						cadena = cadena + "<td><a class='elimina_lista' id='"+sku+"'><img src='images/delete.png' /></a></td>";
						$("#ssptable tbody").append(cadena);
						
							//aqui puedes enviar un conunto de tados ajax para agregar al usuairo
							$.post("modulos/solicitudes/agregarlista.php", { idsol: $("#idsol").val(), sku: sku, stock: $("#stock").val(), cant: $("#cant").val(), descrip:descrip, total: $("#total").val()});
						
						fn_eliminar_articulolista();
						fn_cantidad();
						alert("Articulo agregado a la Lista");
						 $("#articulo").val("");
						 $("#articulo").focus();
						
				
				 }//fin else
   					}); // fin get
				
				}//fin cantidad mayor quie cero
				else
				{
					alert("No se puede agregar el articulo, debe especificar una cantidad mayor a 0");
				}//fin sino de menor que cero
            };
            
			
			 function fn_buscar_stock(){
				 
				  var articulo = $("#articulo").val().split('|');
				 var sku = articulo[1];
				
               //alert ("hola mundo");
			   $.get("modulos/solicitudes/obtenerstock.php?sku="+sku,function(data){
					//	$('#idpais').attr("id",data);
						$('#stock').val(data);
			
						
					});
            };

           
            function fn_agregar_pais(){
			
				$.get("modulos/pais/obtenerid.php",function(data){
					//	$('#idpais').attr("id",data);
						data++;
						alert("País agregado con el id "+ data +" exitosamente!");
						cadena = "<tr>";
				 		cadena = cadena + "<td>" + $("#valor_uno").val() + "</td>"; 
				 		cadena = cadena + "<td><a class='elimina_pais' id='"+ data +"' ><img src='images/delete.png' /></a></td>";
				 		cadena = cadena + "<td><a class='editar_pais'  id='" + data + "' ><img src='images/modificar.png' /></a></td></tr>";
						$("#ssptable tbody").append(cadena);
						$.post("modulos/pais/agregar.php", {ide_usu: data, nom_usu: $("#valor_uno").val()});
						fn_eliminar_pais().flush();
						fn_cantidad();
						
					});
	
            };
            
            function fn_eliminar_pais(){
                $("a.elimina_pais").click(function(){
                    id = $(this).attr("id");// trae lo que esta en id
				    nombre = $(this).parents("tr").find("td").html(); 
                    respuesta = confirm("Desea eliminar el pais: " + nombre);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                           //  alert("Usuario " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/pais/eliminar.php", {ide_usu: id})
                            
                        })
                    }
                });
            };
			
			 function fn_editar_pais(){
                $("a.editar_pais").click(function(){
                    id = $(this).attr("id");
					$( ".idTabs" ).tabs({ active: 2 });
					valor = $(this).parents("tr").find("td").html();  
					$("#nomedit").val(valor);
					  
                  //  respuesta = confirm("Desea editar el usuario: " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             alert("Usuario " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/pais/eliminar.php", {ide_usu: id})
                            
                        })
                    }
                });
            };
			
/***************************** COMUNA ************************************************************************************/            
            function fn_agregar_comuna(){
				
				
				$.get("modulos/comuna/obtenerid.php",function(data2){
						
						data2++;
						alert("Comuna agregada con el id "+ data2 +" exitosamente!");
							
							cadena = "<tr>";
							cadena = cadena + "<td>" + $("#nombreComuna").val() + "</td>";
						    cadena = cadena + "<td>" + $("#idpais option:selected").text() + "</td>";
							cadena = cadena + "<td><a class='elimina_comuna' id='"+ data2 +"' ><img src='images/delete.png' /></a></td></tr>";
						
							
							$("#ssptable tbody").append(cadena);
				 
							 $.post("modulos/comuna/agregar.php", {id_comuna: data2, nom_comuna: $("#nombreComuna").val(), ide_pais: $("#idpais").val()});
                
                fn_eliminar_comuna();
				fn_cantidad();
               
				});
				
            };
            
            function fn_eliminar_comuna(){
                $("a.elimina_comuna").click(function(){
                    id = $(this).attr("id");
                    respuesta = confirm("Desea eliminar la comuna: " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             //alert("Usuario " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/comuna/eliminar.php", {ide: id})
                            
                        })
                    }
                });
            };
			
			 function fn_editar_comuna(){
                $("a.elimina_comuna").click(function(){
                    id = $(this).attr("id");
					
                    respuesta = confirm("Desea eliminar el usuario: " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             alert("Usuario " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/pais/eliminar.php", {ide_usu: id})
                            
                        })
                    }
                });
            };
			
/***************************** ARTICULOS ************************************************************************************/            
            function fn_agregar_articulo(){
               
               
                    //aqui puedes enviar un conjunto de datos ajax para agregar el articulo
                    $.post("modulos/articulos/agregar.php", {tipo: $("#select_uno").val(),nombre: $("#valor_uno_a").val(),enguia: $("#select_impreso option:selected").val(),  medida: $("#select_medida").val(), preciou: $("#valor_dos_a").val(), descripcion: $("#valor_tres_a").val(), comentario: $("#valor_cuatro_a").val()});
                
                fn_eliminar_articulo();
				fn_cantidad();
                alert("Articulo agregado");
				location.reload();
            };
               
			 function fn_eliminar_articulo(){
                $("a.elimina_articulo").click(function(){
                    id = $(this).attr("id");
                    respuesta = confirm("Desea eliminar el articulo: " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             //alert("Articulo " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/articulos/eliminar.php", {ide_usu: id})
                            
                        })
                    }
                });
            };


			
			
/***************************** AGREGAR PRODUCTO A LA LISTA ************************************************************************************/            
             function fn_agregar_lista2(){
				
				if($("#cant").val() >0)
				{
				
				$.get("modulos/guiadespacho2/productoenlista.php?idarticulo="+$("#idarticulo").val()+"&numeroguia="+$("#guianumero").val(),function(idart){ // para preguntar si esta la guia de despacho creada anteriormente
																											  
				//alert (idart);
				
				 if(idart == 1)
				 {
						$.post("modulos/guiadespacho2/agregarlistamas.php", { guianumero: $("#guianumero").val(), idarticulo: $("#idarticulo").val(), cant: $("#cant").val(), articulo: $("#articulo").val(), preciou: $("#precio").val(), total: $("#total").val()});
						alert("Articulo Sumado");
						location.href = 'index.php?opc=paso2&guianumero='+ $("#guianumero").val() ;
				 }
				 else if(idart == 0)
				 {
						cadena = "<tr>";
						cadena = cadena + "<td>" + $("#cant").val() + "</td>";
						cadena = cadena + "<td>" + $("#unidadarticulo").val() + "</td>";
						cadena = cadena + "<td>" + $("#articulo").val() + "</td>";
						cadena = cadena + "<td>" + $("#precio").val() + "</td>";
						cadena = cadena + "<td>" + $("#total").val() + "</td>";
						cadena = cadena + "<td><a class='elimina_lista' id='"+$("#idarticulo").val()+"'><img src='images/delete.png' /></a></td>";
						$("#ssptable tbody").append(cadena);
						
							//aqui puedes enviar un conunto de tados ajax para agregar al usuairo
							$.post("modulos/guiadespacho2/agregarlista.php", { guianumero: $("#guianumero").val(), idarticulo: $("#idarticulo").val(), cant: $("#cant").val(), articulo: $("#articulo").val(), preciou: $("#precio").val(), total: $("#total").val()});
						
						fn_eliminar_articulolista();
						fn_cantidad();
						alert("Articulo agregado a la Lista");
						
				
				 }//fin else
   					}); // fin get
				
				}//fin cantidad mayor quie cero
				else
				{
					alert("No se puede agregar el articulo, debe especificar una cantidad mayor a 0");
				}//fin sino de menor que cero
            };
            
              function fn_eliminar_articulolista(){
                $("a.elimina_lista").click(function(){
                    id = $(this).attr("id");
					
                    respuesta = confirm("Desea eliminar de la lista : " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             //alert("cliente " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/solicitudes/eliminardelista.php", {sku: id,idsol: $("#idsol").val()})
                            
                        })
                    }
                });
            };
			
/******************************************************************************* Busqueda de producto *************************************/

function fn_agregar_cod(){
$('#codbarra2').bind('keyup', function (e) {
								  var key = e.keyCode || e.which;
								  if (key === 13) 
								  {
									codBarra = $('#codbarra2').val();
									//alert(codBarra);
									
									cadena = "<tr>";
									cadena = cadena + "<td width='10'><a class='liquidar' id='41' ><img src='images/delete.png ' /></a></td>"; 
									cadena = cadena + "<td><input name='codigo' type='text'  size='40' value='" +codBarra+ "' readonly/></td>"; 
									cadena = cadena + "<td>hola</td>"; 
									cadena = cadena + "<td>hola</td>"; 
									cadena = cadena + "<td>hola</td>"; 
									cadena = cadena + "<td>hola</td>"; 
									cadena = cadena + "<td>hola</td></tr>"; 
									/*cadena = cadena + "<td>" + $("#nombre").val() + "</td>"; 
									cadena = cadena + "<td>" + $("#rol").val() + "</td>"; 
									cadena = cadena + "<td>" + $("#rol").val() + "</td>"; 
									cadena = cadena + "<td>" + $("#rol").val() + "</td>"; 
									cadena = cadena + "<td>" + $("#rol").val() + "</td>"; 
									cadena = cadena + "<td>" + $("#modulo option:selected").text()+ "</td></tr>"; */
				 		
									$("#pretabla tbody").append(cadena);
						
								//s	alert ($('#codbarra2').val());
									$.get("modulos/ticket/obtenerArticulo.php?codBarra="+$('#codbarra2').val(),function(articulo){	
										  var articulo = JSON.parse(articulo);
											
											
											
											$("#impUnico").val(ImpuestoFin.toFixed(0));
											//alert(isapre+adsalud);
											//calcularNoImp();	
											
								
									});	//fin funcion obtener 
								  }
			}); //fin captura codbarra

}								
								
								
	/*function fn_cambia(){
                $("#precio").keyup(function(event){
                    id = $(this).attr("id");
                    respuesta = confirm("Desea eliminar esta solicitud : " + id);
                    if (respuesta){
                        $(this).parents("tr").fadeOut("normal", function(){
                            $(this).remove();
                             //alert("cliente " + id + " eliminado");
                            
                                //aqui puedes enviar un conjunto de datos por ajax
                                $.post("modulos/solicitudes/eliminar.php", {id_sol: id})
                            
                        })
                    }
                });
            };	*/					

	function fn_cambia(){			
							$(".cantidad").keyup(function(event) 
							{
										  cant = $(this).attr("value");
										  cant = +cant;
										  $(this).val(cant);	
										  precio = $(this).parents("tr").find(".precioFinal").val();										
										  cantPrecio=cantidadPorPrecio(cant,precio);
										  //alert(cantPrecio);
										  $(this).parents("tr").find(".totalprod").val(cantPrecio.toFixed(0));
										  fn_total();
							});						
	};//fin fucion cambia								
								
function cantidadPorPrecio(cant,precio)
 {	
	var producto =cant*precio;
	return producto
}	

function fn_cambiaPrecio(){			
							$(".precioFinal").keyup(function(event) 
							{
										  precio = $(this).attr("value");
										  precio = +precio;
										  $(this).val(precio);	
										  cant = $(this).parents("tr").find(".cantidad").val();										
										  cantPrecio=cantidadPorPrecio(cant,precio);
										  //alert(cantPrecio);
										  $(this).parents("tr").find(".totalprod").val(cantPrecio.toFixed(0));
										  fn_total();
							});						
	};//fin fucion cambia								
								


function validarNumero(id)
        {
            if($.isNumeric($(id).val()))
            {
                //$(id).css('border-color','#808080');
                return parseFloat($(id).val());
            }else if($(id).val()==""){
                //$(id).css('border-color','#808080');
                return 0;
            }else{
                //$(id).css('border-color','#f00');
                return 0;
            }
        } 							
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								
								