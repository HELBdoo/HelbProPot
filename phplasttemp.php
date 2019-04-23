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

$sql = "SELECT kat, prizemlje, radiona, vanjska FROM temp10m
		WHERE vrijeme2 = (SELECT MAX(vrijeme2) FROM temp10m)";

$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

$result = array(

	"cols" => array(
		array("label" => "PRIZEMLJE", "type" => "number"),
		array("label" => "KAT", "type" => "number"),
		array("label" => "RADIONA", "type" => "number"),
		array("label" => "VANJSKA", "type" => "number")
	),
	
	"rows" => array()
); 

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		
	$result["rows"][] = array("c" => array(
		array("v" => $row["kat"]),
		array("v" => $row["prizemlje"]),
		array("v" => $row["radiona"]),
		array("v" => $row["vanjska"])
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   
