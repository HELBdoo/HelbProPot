<?php
header('Access-Control-Allow-Origin: *'); 
?>
<?php    
$connectionInfo = array("UID" => "filip@myserverfilip", "pwd" => "Sifra1234", "Database" => "SamoLabavo", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0, "ReturnDatesAsStrings"=>true);
$serverName = "tcp:myserverfilip.database.windows.net,1433";    

$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
	die( print_r( sqlsrv_errors(), true));
	} 

$sql = "SELECT gro11, gro12, pac1, soc11, soc12, soc13 FROM snage10s
		WHERE vrijeme12 = (SELECT MAX(vrijeme12) FROM snage10s)";

$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

$result = array(

	"cols" => array(
		array("label" => "GRO1", "type" => "number"),
		array("label" => "GRO2", "type" => "number"),
		array("label" => "SOLARNA", "type" => "number"),
		array("label" => "KLIMA1", "type" => "number"),
		array("label" => "KLIMA2", "type" => "number"),
		array("label" => "PTV", "type" => "number")
	),
	
	"rows" => array()
); 

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		
	$result["rows"][] = array("c" => array(
		array("v" => $row["gro11"]),
		array("v" => $row["gro12"]),
		array("v" => $row["pac1"]),
		array("v" => $row["soc11"]),
		array("v" => $row["soc12"]),
		array("v" => $row["soc13"]),
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   
