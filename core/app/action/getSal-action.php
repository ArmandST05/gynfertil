<?php
$conn = Database::getCon();
/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;



$columns = array( 
// datatable column index  => database column name
	0 => 'id',
    1 => 'nombre_dia', 
	2 => 'fecha',
	3 => 'nombre'

    
);

// getting total number records without any search
$sql = "SELECT id,created_at,comentarios FROM sell WHERE sal='1'";

$query=mysqli_query($conn, $sql) or die("./?action=getSal: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT id,created_at,comentarios FROM sell WHERE sal='1'";
	$sql.=" AND s.id LIKE '".$requestData['search']['value']."%')";
	
	$query=mysqli_query($conn, $sql) or die("./?action=getSal: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("./?action=getSal: get PO"); // again run query with limit
	
} else {	

	$sql = "SELECT id,created_at,comentarios FROM sell WHERE sal='1'";
	$sql.=" AND s.id LIKE '".$requestData['search']['value']."%')";
	$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("./?action=getSal: get PO");
	
}

	

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

                    $nestedData[] =  '<td >
			   <a href="index.php?view=sales/edit&id='.$row["id"].'" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		     	     </td>';	
    
 
	$nestedData[] = '<td>'.$row["id"]."</td>";
    $nestedData[] = '<td>'.$row["created_at"]."</td>";
    $nestedData[] = '<td>'.$row["comentarios"]."</td>";
   


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
