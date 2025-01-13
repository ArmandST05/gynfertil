<?php
$conn = Database::getCon();;

$ti_user = isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null;
$user = UserData::getLoggedIn();
$userType = $user->tipo_usuario;

/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;


$columns = array(
	// datatable column index  => database column name
	0 => 'id',
	1 => 'name',
	2 => 'calle',
	3 => 'tel',
	4 => 'email',
	5 => 'ref',
	6 => 'status',
	7 => 'relative_name'
);

// getting total number records without any search
$sql = "SELECT id, name,relative_name, calle, tel, email, ref,status FROM pacient WHERE pacient.donor_id != '' ";
if ($userType == "an") { //En el usuario de andrología sólo mostrar los donantes masculinos
	$sql .= " AND pacient.sex_id = 2 ";
}

$query = mysqli_query($conn, $sql) or die("./?action=pacients: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if (!empty($requestData['search']['value'])) {
	// if there is a search parameter
	$sql = "SELECT pacient.id,pacient.donor_id,pacient.name,relative_name, calle, tel, email, ref,status,";
	$sql .= " patient_categories.id as category_id, patient_categories.name as category_name, patient_treatments.name as treatment_name";
	$sql .= " FROM pacient";
	$sql .= " LEFT JOIN patient_category_treatments ON pacient.id = patient_category_treatments.patient_id AND (patient_category_treatments.treatment_status_id = 1 OR patient_category_treatments.treatment_status_id = 2)";
	$sql .= " LEFT JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id";
	$sql .= " LEFT JOIN patient_treatments ON patient_category_treatments.patient_treatment_id = patient_treatments.id";
	$sql .= " WHERE pacient.donor_id != '' AND (pacient.name LIKE '%" . $requestData['search']['value'] . "%' ";    // $requestData['search']['value'] contains search parameter
	$sql .= " OR pacient.tel LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR pacient.email LIKE '" . $requestData['search']['value'] . "%' ";
	$sql .= " OR pacient.relative_name LIKE '" . $requestData['search']['value'] . "%' ) ";
	if ($userType == "an") { //En el usuario de andrología sólo mostrar los donantes masculinos
		$sql .= " AND pacient.sex_id = 2 ";
	}
	$sql .= " GROUP BY pacient.id ORDER BY pacient.donor_id DESC";

	$query = mysqli_query($conn, $sql) or die("./?action=pacients: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql .= " ORDER BY pacient.id DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'] . " "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query = mysqli_query($conn, $sql) or die("./?action=pacients: get PO"); // again run query with limit

} else {
	$sql = "SELECT pacient.id, pacient.donor_id,pacient.name, relative_name, calle, tel, email,ref,status,";
	$sql .= " patient_categories.id as category_id, patient_categories.name as category_name, patient_treatments.name as treatment_name";
	$sql .= " FROM pacient";
	$sql .= " LEFT JOIN patient_category_treatments ON pacient.id = patient_category_treatments.patient_id AND (patient_category_treatments.treatment_status_id = 1 OR patient_category_treatments.treatment_status_id = 2)";
	$sql .= " LEFT JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id";
	$sql .= " LEFT JOIN patient_treatments ON patient_category_treatments.patient_treatment_id = patient_treatments.id";
	$sql .= " WHERE pacient.donor_id != ''";
	if ($userType == "an") { //En el usuario de andrología sólo mostrar los donantes masculinos
		$sql .= " AND pacient.sex_id = 2 ";
	}
	$sql .= " GROUP BY pacient.id";
	$sql .= " ORDER BY pacient.donor_id DESC LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
	$query = mysqli_query($conn, $sql) or die("./?action=pacients: get PO");
}

$data = array();
while ($row = mysqli_fetch_array($query)) {  // preparing an array
	$nestedData = array();

	$nestedData[] = $row["donor_id"];
	$nestedData[] = $row["name"];
	$nestedData[] = $row["calle"];
	$nestedData[] = $row["tel"];
	$nestedData[] = $row["email"];
	$nestedData[] = $row["relative_name"];
	$nestedData[] = $row["ref"];
	if ($row["status"] == 1) {
		$nestedData[] = "<td style= background-color:#ACFA58 ><b>Paciente AAA</b></td>";
	} else if ($row["status"] == 2) {
		$nestedData[] = "<td style= background-color:; color:;><b>Paciente Normal</b></td>";
	} else {
		$nestedData[] = "<td style= background-color:#CDCDCD; color:;><b>Paciente XO</b></td>";
	}

	//Mostrar la categoría/tratamiento actual y después mostrar el historial
	//En la lista que se obtiene vienen todos resumidos, quitar el primero, para ponerlo con el nombre completo y el historial sí resumido.
	$categoriesHistory = PatientCategoryData::getPatientHistoryResume($row["id"]);
	$arrayCategoriesHistory = explode("</b>", $categoriesHistory);

	if ($row["category_id"] && $row["category_id"] == 3) {
		$nestedData[] = "<td><b>" . $row["category_name"] . " " . $row["treatment_name"] . "</b>" . $arrayCategoriesHistory[1] . "</td>";
	} else if ($row["category_id"]) {
		$nestedData[] = "<td><b>" . $row["category_name"] . "</b>" . $arrayCategoriesHistory[1] . "</td>";
	} else {
		$nestedData[] = "<td><b>NO CLASIFICADO</b></td>";
	}

	if ($userType == "su" || $userType == "sub") {
		$nestedData[] = '<td >
				<a href="index.php?view=patients/record&id_paciente=' . $row["id"] . '" class="btn btn-default btn-xs">Expediente</a>
				<a href="index.php?view=pacient_edocuenta&id_paciente=' . $row["id"] . '&name=' . $row["name"] . '" class="btn btn-success btn-xs">Estado de cuenta</a>
				<br><a href="index.php?view=pacient_histo&id_paciente=' . $row["id"] . '&name=' . $row["name"] . '" class="btn btn-info btn-xs">Historial</a>
				<a href="index.php?view=patients/edit&id=' . $row["id"] . '" class="btn btn-warning btn-xs">Editar</a>
				<a href="index.php?action=delpaciente&id=' . $row["id"] . '" class="btn btn-danger btn-xs" onClick="return confirmarEliminar()">Eliminar</a>
                
				</td>';
	} else if ($userType == "do") {
		$nestedData[] = '<td >
				<a href="index.php?view=patients/record&id_paciente=' . $row["id"] . '" class="btn btn-default btn-xs">Expediente</a>
				</td>';
	} else if ($userType == "an") {
		$nestedData[] = '<td >
				<a href="index.php?view=patients/record&id_paciente=' . $row["id"] . '" class="btn btn-default btn-xs">Expediente</a>
				</td>';
	} else {
		$nestedData[] = '<td >
				<a href="index.php?view=pacient_histo&id_paciente=' . $row["id"] . '&name=' . $row["name"] . '" class="btn btn-info btn-xs">Historial</a>
				<a href="index.php?view=patients/edit&id=' . $row["id"] . '" class="btn btn-warning btn-xs">Editar</a>
				</td>';
	}
	$data[] = $nestedData;
}

$json_data = array(
	"draw"            => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal"    => intval($totalData),  // total number of records
	"recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
