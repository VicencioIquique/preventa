<?php

$sql2="SELECT     TOP (100) PERCENT SUM(dbo.RP_ReceiptsDet_SAP.PrecioExtendido) AS SumaTotal, dbo.RP_ReceiptsDet_SAP.Vendedor, 
                      SBO_Import_Eximben_SAC.dbo.OSLP.SlpName, SBO_Import_Eximben_SAC.dbo.OSLP.SlpCode
FROM         dbo.RP_ReceiptsDet_SAP INNER JOIN
                      dbo.RP_ReceiptsCab_SAP ON dbo.RP_ReceiptsDet_SAP.ID = dbo.RP_ReceiptsCab_SAP.ID LEFT OUTER JOIN
                      SBO_Import_Eximben_SAC.dbo.OSLP ON dbo.RP_ReceiptsDet_SAP.Vendedor LIKE '%0' + CONVERT(varchar, SBO_Import_Eximben_SAC.dbo.OSLP.SlpCode)
WHERE     (dbo.RP_ReceiptsCab_SAP.FechaDocto >= '".$finicio2." 00:00:00.000') AND (dbo.RP_ReceiptsCab_SAP.FechaDocto <= '".$ffin2." 23:59:59.000') AND (dbo.RP_ReceiptsDet_SAP.TipoDocto <> 3)
 ".$conModulo." 
GROUP BY dbo.RP_ReceiptsDet_SAP.Vendedor, SBO_Import_Eximben_SAC.dbo.OSLP.SlpName, SBO_Import_Eximben_SAC.dbo.OSLP.SlpCode".$conModuloGroup."
ORDER BY SumaTotal DESC";




//echo $sql;	
							$rs2 = odbc_exec( $conn, $sql2 );
							if ( !$rs2 )
							{
							exit( "Error en la consulta SQL" );
							}
							
		
?>   


       
        <script type="text/javascript">
            var chart2;

            var chartData2 = [
			
			<?php 
 		while($resultado2 = odbc_fetch_array($rs2)){ 
							  
									echo '{ 
										country: "'.utf8_encode($resultado2["SlpName"]).'",
										visits: '.$resultado2["SumaTotal"].',
										color: "#39b283"
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
                valueAxis2.title = "Top Vendedores"
                valueAxis2.axisAlpha = 0;
                chart2.addValueAxis(valueAxis2);

                // GRAPH
                var graph2 = new AmCharts.AmGraph();
                graph2.valueField = "visits";
                graph2.colorField = "color";
                graph2.balloonText = "[[category]]: [[value]]";
                graph2.type = "column";
                graph2.lineAlpha = 0;
                graph2.fillAlphas = 1;
                chart2.addGraph(graph2);

                // WRITE
                chart2.write("topVendedores");
            });
        </script>
   