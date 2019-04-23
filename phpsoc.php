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

$sql = "SELECT vrijeme12, soc11, soc12, soc13 FROM snage10s
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
		array("label" => "Klima kat", "type" => "number"),
		array("label" => "Klima prizemlje", "type" => "number"),
		array("label" => "Grijanje vode", "type" => "number")
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
		array("v" => $row["soc11"]),
		array("v" => $row["soc12"]),
		array("v" => $row["soc13"])
	));
}
	
sqlsrv_free_stmt( $stmt);    
sqlsrv_close( $conn);  

echo json_encode($result);
  
exit();
?>   