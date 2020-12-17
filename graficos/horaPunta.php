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
								
				   
            var chartData = datosEmpleado[];
			 });*/	//fin funcion obtener datos de empleados 					
		
?>   


       
        <script type="text/javascript">
            var chart;

            var chartData = [
			
			<?php 
 		while($resultado = odbc_fetch_array($rs)){ 
							  
									echo '{ 
										country: "'.substr($resultado["HORA"], 11, 2).':00 Hrs. ",
										visits: '.$resultado["cantidad"].',
										color: "#0489B1"
									},'; //fin data
									
		 }//fin while
	
?>
			
			];


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
                valueAxis.title = "Cantidad de Ventas"
                valueAxis.axisAlpha = 0;
                chart.addValueAxis(valueAxis);

                // GRAPH
                var graph = new AmCharts.AmGraph();
                graph.valueField = "visits";
                graph.colorField = "color";
				 graph.type = "line";
                 graph.customBullet = "graficos/images/azulcircle.png"; // bullet for all data points
                graph.balloonText = "[[category]]: [[value]]";
				  graph.bulletSize = 14; // bullet image should be a rectangle (width = height)
				
                chart.addGraph(graph);
				
				 var chartCursor = new AmCharts.ChartCursor();
                chart.addChartCursor(chartCursor);

                // WRITE
                chart.write("horaPunta");
            });
        </script>
   