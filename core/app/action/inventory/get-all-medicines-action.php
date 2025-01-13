<?php
$conn = Database::getCon();
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;

$columns = array(
	// datatable column index  => database column name
	0 => 'barcode',
	1 => 'name',
	2 => 'price_in'
);

// getting total number records without any search
$sql = "SELECT id, name, type,inventary_min,price_in,price_out FROM product WHERE type='MEDICAMENTO'";

$query = mysqli_query($conn, $sql) or die("./?action=getInventary: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

if (!empty($requestData['search']['value'])) {
	// if there is a search parameter
	$sql = "SELECT  id, name, type,inventary_min,price_in,price_out ";
	$sql .= " FROM product";
	$sql .= " WHERE type='MEDICAMENTO'";
	$sql .= " AND name LIKE '" . $requestData['search']['value'] . "%' ";    // $requestData['search']['value'] contains search parameter
	$sql .= " OR barcode LIKE '" . $requestData['search']['value'] . "%' ";

	$query = mysqli_query($conn, $sql) or die("./?action=getInventary: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql .= " ORDER BY name LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query = mysqli_query($conn, $sql) or die("./?action=getInventary: get PO"); // again run query with limit

} else {
	$sql = "SELECT id,name,type,inventary_min,price_in, price_out ";
	$sql .= " FROM product";
	$sql .= " WHERE type='MEDICAMENTO'";
	$sql .= " ORDER BY name LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	$query = mysqli_query($conn, $sql) or die("./?action=getInventary: get PO");
}

$data = array();
while ($row = mysqli_fetch_array($query)) {  // preparing an array
	$nestedData = array();
	
	$q = OperationData::getStockByProduct($row["id"]);
	$qF = OperationData::getAllExpirationDatesByProduct($row["id"]);
	
	if ($q >= $row["inventary_min"]) {
		$color = "#C0FFB8";
	} else {
		$color = "#FFBDBD";
	}

	//Aciones de producto
	$nestedData[] =  '<td>
					  <a href="index.php?view=products/edit&id=' . $row["id"] . '" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
					  <a href="index.php?action=products/delete&id=' . $row["id"] . '" class="btn btn-xs btn-danger" onClick="return confirmDelete()"><i class="fa fa-trash"></i></a>
							</td>';
	$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $row["id"] . '</p>';
	$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $row["name"] . '</p>';
	$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $q . '</p>';
	$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $row["inventary_min"] . '</p>';
	$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $row["price_in"] . '</p>';
	$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $row["price_out"] . '</p>';
	//$nestedData[] = '<p style="background-color:' . $color . '" class="success"> ' . $row["type"] . '</p>';

	//Acciones de inventario
	$nestedData[] =  '<td >
               <a href="index.php?view=inventory/input-medicine&id=' . $row["id"] . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Entrada</a>
		       <a href="index.php?view=inventory/history&productId=' . $row["id"] . '" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-pencil"></i>Historial</a>
		     	     </td>';

	$data[] = $nestedData;
}

$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
