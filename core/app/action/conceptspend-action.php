<?php
$conn = Database::getCon();
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 => 'name',
    1 => 'categoria' 
);

// getting total number records without any search
$sql = "SELECT con.id,con.name,cat.name categoria  FROM  product con, category_spend cat WHERE con.idCat=cat.id AND con.type='CONCEPTOEGRE'";

$query=mysqli_query($conn, $sql) or die("./?action=conceptspend: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT  con.id,con.name,cat.name categoria";
	$sql.=" FROM product con, category_spend cat";
	$sql.=" WHERE con.idCat=cat.id AND con.type='CONCEPTOEGRE'";
	$sql.=" AND (con.name LIKE '".$requestData['search']['value']."%' "; 
	$sql.=" OR cat.name LIKE '".$requestData['search']['value']."%') ";
	$query=mysqli_query($conn, $sql) or die("./?action=conceptspend: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY name LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("./?action=conceptspend: get PO"); // again run query with limit
	
} else {	

	$sql = "SELECT con.id,con.name,cat.name categoria";
	$sql.=" FROM product con, category_spend cat";
	$sql.=" WHERE con.idCat=cat.id AND con.type='CONCEPTOEGRE'";
	$sql.=" ORDER BY name LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("./?action=conceptspend: get PO");
	
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["name"];
	$nestedData[] = $row["categoria"];
	
    $nestedData[] =  '<td >
			   <a href="index.php?view=editconspend&id='.$row["id"].'" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		      	     </td>';	
    
	$data[] = $nestedData;
    
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format

?>
