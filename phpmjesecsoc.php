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

$sql = "SELECT vrijeme2, soc1, soc2, soc3 FROM en1h
		WHERE vrijeme1 BETWEEN (DATEADD(day, DATEDIFF(day, 31, GETDATE()), 0))
		AND (DATEADD(day, DATEDIFF(day, 0, GETDATE()), 0))
		ORDER BY vrijeme2";

$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

$result = array(

	"cols" => array(
		array("label" => "Vrijeme", "type" => "datetime"),
		array("label" => "Klima kat", "type" => "number"),
		array("label" => "Klima prizemlje", "type" => "number"),
		array("label" => "Grijanje vode", "type" => "number")
	),
	
	"rows" => array()
); 

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
	
	$start = "Date(";
	$end = ")";
	$string = strval((($row["vrijeme2"] - 2082844800)*1000));
	$newstr = explode(".",$string);
	$finaldate = $start . $newstr[0] . $end;
	
	$result["rows"][] = array("c" => array(
		array("v" => $finaldate),
		array("v" => $row["soc1"]),
		array("v" => $row["soc2"]),
		array("v" => $row["soc3"])
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   
