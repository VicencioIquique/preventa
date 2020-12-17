<?php class MySQL{

  private $conexion; private $total_consultas;

  public function MySQL(){ 
    if(!isset($this->conexion)){
      $this->conexion = (mysql_connect("192.168.0.162","root","savara"))
        or die(mysql_error());
      mysql_select_db("tpv2",$this->conexion) or die(mysql_error());
    }
  }

  public function consulta($consulta){ 
    $this->total_consultas++; 
    $resultado = mysql_query($consulta,$this->conexion);
    if(!$resultado){ 
      echo 'No se encontraron resultados';
      exit;
    }
    return $resultado;
  }

  public function fetch_array($consulta){
   return mysql_fetch_array($consulta);
  }
  
  public function ultimo_id(){
   return mysql_insert_id();
  }
  public function num_rows($consulta){
   return mysql_num_rows($consulta);
  }

  public function fetch_row($consulta){
   return mysql_fetch_row($consulta);
  }
  
  public function getTotalConsultas(){
   return $this->total_consultas; 
  }

  //CLASE PARA INSERTAR
  public function insert($insert){ 
    $insertar = mysql_query($insert,$this->conexion);
	?>
	<script language="javascript">
  	parent.location.href="<?php echo $PHP_SELF?>";
  	</script>
    <?php
  }
  
   //CLASE PARA Update
  public function update($update){ 
    $actualizar = mysql_query($update,$this->conexion);
	?>
	<!--<script language="javascript">
  	parent.location.href="<?php //echo $PHP_SELF?>";
  	</script>-->
    <?php
  }
  

  //CLASE PARA ELIMINAR
  public function delete($delete){ 
    $eliminar = mysql_query($delete,$this->conexion);
	?>
	<script language="javascript">
  	parent.location.href="index.php";
  	</script>
    <?php
  }

  //CLASE PARA INSERTAR 
   public function insertprod($insert){ 
    $insertar = mysql_query($insert,$this->conexion);
  }
      
}

$dbhost = '192.168.0.162';
$dbuser = 'root';
$dbpass = 'savara';
$dbname = 'tpv2';

//conexion a la bd
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error conectando con Base de Datos MySQL');
mysql_select_db($dbname);

?>
