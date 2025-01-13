<?php
$conn = Database::getCon();

$ti_user=isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null ;
         $ti_usua =UserData::get_tipo_usuario($ti_user);

         foreach ($ti_usua as $key) {
           $tipo=$key->tipo_usuario;
   }
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;


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
$sql = "SELECT patients.id, patients.name,relative_name, calle, tel, email, ref,status FROM pacient AS patients INNER JOIN sexes ON sexes.id = patients.sex_id";

$query=mysqli_query($conn, $sql) or die("./?action=pacients: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT patients.id, patients.name,sexes.name AS sex_name,patients.relative_id,relative.name AS registered_relative_name,patients.relative_name, patients.calle, patients.tel, patients.email, patients.ref,patients.status,";
	$sql.= " patient_category_treatments.id as patient_category_treatment_id,patient_categories.id as category_id, patient_categories.name as category_name, patient_treatments.name as treatment_name";
	$sql.= " FROM pacient AS patients";
	$sql.= " LEFT JOIN patient_category_treatments ON patients.id = patient_category_treatments.patient_id AND (patient_category_treatments.treatment_status_id = 1 OR patient_category_treatments.treatment_status_id = 2)";
	$sql.= " LEFT JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id ";
	$sql.= " LEFT JOIN patient_treatments ON patient_category_treatments.patient_treatment_id = patient_treatments.id ";
	$sql.= " LEFT JOIN pacient relative ON patients.relative_id = relative.id ";
	$sql.= " INNER JOIN sexes ON sexes.id = patients.sex_id";
	$sql.= " WHERE patients.name LIKE '%".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.= " OR patients.tel LIKE '".$requestData['search']['value']."%' ";
	$sql.= " OR patients.email LIKE '".$requestData['search']['value']."%' ";
	/*$sql.= " OR ((relative.id = '' OR relative.id = 0) AND patients.relative_name LIKE '".$requestData['search']['value']."%') ";
	$sql.= " OR (relative.id != '' AND relative.id != 0 AND relative.name LIKE '".$requestData['search']['value']."%') ";*/
	$sql.= " GROUP BY patients.id";
	$query=mysqli_query($conn, $sql) or die("./?action=pacients: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY patients.id DESC LIMIT ".$requestData['start']." ,".$requestData['length']." "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query = mysqli_query($conn, $sql) or die("./?action=pacients: get PO"); // again run query with limit

} else {	
	$sql = "SELECT patients.id, patients.name,sexes.name AS sex_name,patients.relative_id,relative.name AS registered_relative_name,patients.relative_name, patients.calle, patients.tel, patients.email,patients.ref,patients.status,";
	$sql.= " patient_category_treatments.id as patient_category_treatment_id,patient_categories.id as category_id, patient_categories.name as category_name, patient_treatments.name as treatment_name";
	$sql.= " FROM pacient AS patients";
	$sql.= " LEFT JOIN patient_category_treatments ON patients.id = patient_category_treatments.patient_id AND (patient_category_treatments.treatment_status_id = 1 OR patient_category_treatments.treatment_status_id = 2)";
	$sql.= " LEFT JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id ";
	$sql.= " LEFT JOIN patient_treatments ON patient_category_treatments.patient_treatment_id = patient_treatments.id ";
	$sql.= " LEFT JOIN pacient relative ON patients.relative_id = relative.id ";
	$sql.= " INNER JOIN sexes ON sexes.id = patients.sex_id";
	$sql.= " GROUP BY patients.id";
	$sql.= " ORDER BY patients.id DESC LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	
	$query = mysqli_query($conn, $sql) or die("./?action=pacients: get PO");
}

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["id"];
    $nestedData[] = $row["name"];
	$nestedData[] = $row["sex_name"];
	$nestedData[] = $row["calle"];
	$nestedData[] = $row["tel"];
	$nestedData[] = $row["email"];
	$nestedData[] = (($row["relative_id"] != '' && $row["relative_id"] != 0) ? $row["registered_relative_name"] : $row["relative_name"]);

    $nestedData[] = $row["ref"];
    if($row["status"] == 1){
		$nestedData[] = "<td style= background-color:#ACFA58 ><b>Paciente AAA</b></td>";
	}
	else if($row["status"] == 2){
		$nestedData[] = "<td style= background-color:; color:;><b>Paciente Normal</b></td>";
	}
	else{
		$nestedData[] = "<td style= background-color:#CDCDCD; color:;><b>Paciente XO</b></td>";
    }

	//Mostrar la categoría/tratamiento actual y después mostrar el historial
	//En la lista que se obtiene vienen todos resumidos, quitar el primero, para ponerlo con el nombre completo y el historial sí resumido.

	/*$sqlCategories = "SELECT patient_categories.abbreviation ";
	$sqlCategories.= " FROM patient_category_treatments";
	$sqlCategories.= " INNER JOIN patient_categories ON patient_category_treatments.patient_category_id = patient_categories.id ";
	$sqlCategories.= " WHERE patient_id = '".$row['id']."' AND patient_category_treatments.id != '".$row['patient_category_treatment_id']."' ";
	$sqlCategories.= " ORDER BY patient_category_treatments.id DESC ";
	
	$queryCategories = mysqli_query($conn, $sqlCategories) or die("./?action=pacients: get PO");*/
	$categoryHistory = "<td><b>";

	if($row["category_id"] == 3){
		$categoryHistory = "<td><b>".$row["category_name"]." ".$row["treatment_name"]."</b></td>";
		/*while( $rowCategories=mysqli_fetch_array($queryCategories) ) {  // preparing an array
			$categoryHistory .= $rowCategories["abbreviation"].", ";
		}*/
		//$categoryHistory .= "</td>";
	}else{
		$categoryHistory .= "<td><b>NO CLASIFICADO</b></td>";
	}
	$nestedData[] = $categoryHistory;

   if($tipo=="su"){
		$nestedData[] = '<td>
					<a href="index.php?view=patients/record&id_paciente='.$row["id"].'" class="btn btn-default btn-xs">Expediente</a>
					<a href="index.php?view=pacient_edocuenta&id_paciente='.$row["id"].'&name='.$row["name"].'" class="btn btn-success btn-xs">Estado de cuenta</a>
					<br><a href="index.php?view=pacient_histo&id_paciente='.$row["id"].'&name='.$row["name"].'" class="btn btn-info btn-xs">Historial</a>
					<a href="index.php?view=patients/edit&id='.$row["id"].'" class="btn btn-warning btn-xs">Editar</a>
					<a href="index.php?action=delpaciente&id='.$row["id"].'" class="btn btn-danger btn-xs" onClick="return confirmarEliminar()">Eliminar</a>
					</td>';	
	}else if($tipo=="sub"){
		$nestedData[] = '<td>
					<a href="index.php?view=patients/record&id_paciente='.$row["id"].'" class="btn btn-default btn-xs">Expediente</a>
					<a href="index.php?view=pacient_edocuenta&id_paciente='.$row["id"].'&name='.$row["name"].'" class="btn btn-success btn-xs">Estado de cuenta</a>
					<br><a href="index.php?view=pacient_histo&id_paciente='.$row["id"].'&name='.$row["name"].'" class="btn btn-info btn-xs">Historial</a>
					<a href="index.php?view=patients/edit&id='.$row["id"].'" class="btn btn-warning btn-xs">Editar</a>
					<a href="index.php?action=delpaciente&id='.$row["id"].'" class="btn btn-danger btn-xs" onClick="return confirmarEliminar()">Eliminar</a>
					</td>';	
	}else if($tipo=="do"){
		$nestedData[] = '<td >
				<a href="index.php?view=patients/record&id_paciente='.$row["id"].'" class="btn btn-default btn-xs">Expediente</a>
				</td>';	
	}else if($tipo=="an"){
		$nestedData[] = '<td >
				<a href="index.php?view=patients/record&id_paciente='.$row["id"].'" class="btn btn-default btn-xs">Expediente</a>
				</td>';	
	}else{
	$nestedData[] = '<td>
				<a href="index.php?view=pacient_histo&id_paciente='.$row["id"].'&name='.$row["name"].'" class="btn btn-info btn-xs">Historial</a>
				<a href="index.php?view=patients/edit&id='.$row["id"].'" class="btn btn-warning btn-xs">Editar</a>
				</td>';	
	}
	$data[] = $nestedData;
}
$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format
