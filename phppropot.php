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

$sql = "SELECT vrijeme12, gro11, gro12, pac1 FROM snage10s
		WHERE vrijeme11 BETWEEN (DATEADD(day, DATEDIFF(day, 0, GETDATE()), 0))
		AND (DATEADD(day, DATEDIFF(day, -1, GETDATE()), 0))
		ORDER BY vrijeme12";

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
	$string = strval((($row["vrijeme12"] - 2082844800)*1000));
	$newstr = explode(".",$string);
	$finaldate = $start . $newstr[0] . $end;
	
	$result["rows"][] = array("c" => array(
		array("v" => $finaldate),
		array("v" => $row["gro11"]),
		array("v" => $row["gro12"]), 
		array("v" => $row["pac1"])
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   