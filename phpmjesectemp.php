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

$sql = "SELECT vrijeme2, vanjskamin, vanjskamax, katmin, katmax, prizemljemin, prizemljemax, radionamin, radionamax FROM tempminmax
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
		array("label" => "Vanjska-min", "type" => "number"),
		array("label" => "Vanjska-max", "type" => "number"),
		array("label" => "Kat-min", "type" => "number"),
		array("label" => "Kat-max", "type" => "number"),
		array("label" => "Prizemlje-min", "type" => "number"),
		array("label" => "Prizemlje-max", "type" => "number"),
		array("label" => "Radiona-min", "type" => "number"),
		array("label" => "Radiona-max", "type" => "number")
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
		array("v" => $row["vanjskamin"]),
		array("v" => $row["vanjskamax"]),
		array("v" => $row["katmin"]),
		array("v" => $row["katmax"]),
		array("v" => $row["prizemljemin"]),
		array("v" => $row["prizemljemax"]),
		array("v" => $row["radionamin"]),
		array("v" => $row["radionamax"])
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   
