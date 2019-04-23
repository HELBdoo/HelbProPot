<?php
header('Access-Control-Allow-Origin: *'); 
?>
<?php    
$serverName = "HELBSKADA01";     
$connectionInfo = array( "Database"=>"helbscada", "UID"=>"ibosilj", "PWD"=>"jpn5DKjv", "ReturnDatesAsStrings"=>true );   

$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
	die( print_r( sqlsrv_errors(), true));
	} 

$sql = "SELECT vrijeme2, gro1, gro2, pac FROM en24h
		WHERE vrijeme1 BETWEEN (DATEADD(day, DATEDIFF(day, 366, GETDATE()), 0))
		AND (DATEADD(day, DATEDIFF(day, 0, GETDATE()), 0))
		ORDER BY vrijeme2";

$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

$result = array(

	"cols" => array(
		array("label" => "Vrijeme", "type" => "datetime"),
		array("label" => "GRO1", "type" => "number"),
		array("label" => "GRO2", "type" => "number"),
		array("label" => "Solarna", "type" => "number")
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
		array("v" => $row["gro1"]),
		array("v" => $row["gro2"]), 
		array("v" => $row["pac"])
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   