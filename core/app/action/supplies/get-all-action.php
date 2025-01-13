<?php
$conn = Database::getCon();
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 => 'name',
    1 => 'inventary_min'
    
);

// getting total number records without any search
$sql = "SELECT id,name, inventary_min  FROM product WHERE type='INSUMOS'";

$query=mysqli_query($conn, $sql) or die("./?action=supplies: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT  id,name, inventary_min ";
	$sql.=" FROM product";
	$sql.=" WHERE type='INSUMOS' ";
	$sql.=" AND name LIKE '".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$query=mysqli_query($conn, $sql) or die("./?action=supplies: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY name LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("./?action=supplies/get-all: get PO"); // again run query with limit
	
} else {	

	$sql = "SELECT id,name, inventary_min  ";
	$sql.=" FROM product";
	$sql.=" WHERE type='INSUMOS' ";
	$sql.=" ORDER BY name LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("./?action=supplies/get-all: get PO");
	
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["name"];
	$nestedData[] = $row["inventary_min"];
   
    $nestedData[] =  '<td >
			   <a href="index.php?view=supplies/edit&id='.$row["id"].'" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		       <a href="index.php?view=supplies/delete&id='.$row["id"].'" class="btn btn-xs btn-danger" onClick="return confirmDelete()"><i class="fa fa-trash"></i></a>
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
