<?php





//echo $sql;	
							$rs2 = odbc_exec( $conn, $sql2 );
							if ( !$rs2 )
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
            var chart2;

            var chartData2 = [
			
			<?php 
 		while($resultado2 = odbc_fetch_array($rs2)){ 
							  
									echo '{ 
										country: "'.substr($resultado2["HORA"], 11, 2).':00 Hrs. ",
										visits: '.$resultado2["total"].',
										color: "#0489B1"
									},'; //fin data
									
		 }//fin while
	
?>
			
			];


            AmCharts.ready(function () {
                // SERIAL CHART
               chart2 = new AmCharts.AmSerialChart();
                chart2.dataProvider = chartData2;
                chart2.categoryField = "country";
                chart2.startDuration = 1;

                // AXES
                // category
                 var categoryAxis2 = chart2.categoryAxis;
                categoryAxis2.labelRotation = 45; // this line makes category values to be rotated
                categoryAxis2.gridAlpha = 0;
                categoryAxis2.fillAlpha = 1;
                categoryAxis2.fillColor = "#FAFAFA";
                categoryAxis2.gridPosition = "start";

                // value
               var valueAxis2 = new AmCharts.ValueAxis();
                valueAxis2.dashLength = 5;
                valueAxis2.title = "Ventas CLP"
                valueAxis2.axisAlpha = 0;
                chart2.addValueAxis(valueAxis2);

                // GRAPH
                var graph2 = new AmCharts.AmGraph();
                graph2.valueField = "visits";
                graph2.colorField = "color";
				graph2.lineColor = "#0489B1";
				 graph2.type = "line";
                 graph2.customBullet = "graficos/images/azulcircle.png"; // bullet for all data points
                graph2.balloonText = "[[category]]: [[value]]";
				  graph2.bulletSize = 14; // bullet image should be a rectangle (width = height)
				
                chart2.addGraph(graph2);
				
				 var chartCursor = new AmCharts.ChartCursor();
                chart2.addChartCursor(chartCursor);

                // WRITE
                chart2.write("horaPuntaClp");
            });
        </script>
   