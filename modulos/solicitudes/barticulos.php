<?php
require_once("../../clases/conexionocdb.php");



if (isset($_GET['term']))
{
	$return_arr = array();
	
	$sql="SELECT [ItemCode]
      ,[ItemName]
  FROM [RP_VICENCIO].[dbo].[oITM_From_SBO]
  WHERE [ItemCode] LIKE '%'+'".$_REQUEST['term']."'+'%' OR [ItemName] LIKE '%'+'".$_REQUEST['term']."'+'%' ";//'%'+'".$_GET['term']."'+'%'
   
   //echo $sql;


$stmt = odbc_exec( $conn, $sql );
							
							if ( !$stmt )
							{
							exit( "Error en la consulta SQL" );
							}
							//odbc_execute(array('term' => '%'.$_GET['term'].'%'));
							
							  while($row = odbc_fetch_array($stmt)){ 
							 	$return_arr[] = array('label' => $row['ItemName'].'|'.$row['ItemCode']); //$row['usuario_nombre'].' '.$row['usuario_id'];
							 
						}



    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
} // fin if

odbc_close( $conn );


?>