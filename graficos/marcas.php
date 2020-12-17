<?php





//echo $sql;	
							$rs = odbc_exec( $conn, $sql );
							if ( !$rs )
							{
							exit( "Error en la consulta SQL" );
							}
				/*	$.get("graficos/traeVentas.php",function(datosEmpleado){
								 var dEmp = JSON.parse(datosEmpleado[]);
							    alert (dEmp.country);
								
								
								$.get("graficos/traeVentas.php",function(datosEmpleado){
								 var dEmp = JSON.parse(datosEmpleado);
							   
			var myChartData = datosEmpleado.replace(/\"country\"/ig, "country").replace(/\"visits\"/ig, "visits").replace(/\"color\"/ig, "color");		;					
				 alert (myChartData);   
            var chartData = eval(myChartData);
			 });	//fin funcion obtener datos de empleados 	
				   
            var chartData = datosEmpleado[];
			 });*/	//fin funcion obtener datos de empleados 					
		
?>   


       
        <script type="text/javascript">
            var chart;

            var chartData = [
			
			<?php 
 		while($resultado = odbc_fetch_array($rs)){ 
							  
									echo '{ 
										country: "'.utf8_encode($resultado["Name"]).'",
										visits: '.$resultado["SumaMarca"].',
										color: "#0489B1"
									},'; //fin data
									
		 }//fin while
	
?>
			
			];
			
			//alert(chart);


            AmCharts.ready(function () {
                // SERIAL CHART
                chart = new AmCharts.AmSerialChart();
                chart.dataProvider = chartData;
                chart.categoryField = "country";
                chart.startDuration = 1;

                // AXES
                // category
                var categoryAxis = chart.categoryAxis;
                categoryAxis.labelRotation = 45; // this line makes category values to be rotated
                categoryAxis.gridAlpha = 0;
                categoryAxis.fillAlpha = 1;
                categoryAxis.fillColor = "#FAFAFA";
                categoryAxis.gridPosition = "start";

                // value
                var valueAxis = new AmCharts.ValueAxis();
                valueAxis.dashLength = 5;
                valueAxis.title = "Ventas Por Marca"
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";
                graph.colorField = "color";
                graph.balloonText = "[[category]]: [[value]]";
                graph.type = "column";
                graph.lineAlpha = 0;
                graph.fillAlphas = 1;
                chart.addGraph(graph);

                // WRITE
                chart.write("ingresos");
            });
        </script>
   