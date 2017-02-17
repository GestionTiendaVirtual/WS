<?php
include_once 'Data/Data.php';
include_once 'lib/nusoap.php';
$servicio = new soap_server();

$ns = "urn:miserviciowsdl";
$servicio->configureWSDL("MiPrimerServicioWeb",$ns);
$servicio->schemaTargetNamespace = $ns;

$servicio->register("MiFuncion", array('nombre' => 'xsd:string','numero_cuenta' => 'xsd:integer'
		,'csc' => 'xsd:integer','monto' => 'xsd:integer','nombre_negocio' => 'xsd:string','numero_factura' => 'xsd:integer','fecha' => 'xsd:string','email' => 'xsd:string'),array('return' => 'xsd:boolean'),$ns );

function MiFuncion($nombre, $numero_cuenta,$csc,$monto,$nombre_negocio,$numero_factura,$fecha,$email){
	$data=new Data();
	$conn = new mysqli($data->server,$data->user,$data->password,$data->db);
    $conn->set_charset('utf8');
    $query = "select * from user where css=".$csc." and numbercard=".$numero_cuenta."";
    $resultado = mysqli_query($conn,$query);
    $row= mysqli_fetch_array($resultado);
    	
 	$done=false;
	if (sizeof($row) >= 1) {
		$done=true;
		$query2="insert into bill (name,numbercard,csc,rode,namebusiness,numberbill,datee,email)
    		values('$nombre','$numero_cuenta',$csc,$monto,'$nombre_negocio',$numero_factura,'$fecha','$email')";
    		$resultado2 = mysqli_query($conn,$query2);	
	}
	else{
		$done=false;
	}
			return $done;

	}	
	

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$servicio->service($HTTP_RAW_POST_DATA);


?>
