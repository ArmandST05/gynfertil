<?php
$conn = Database::getCon();
/* Database connection end */

// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;
$ti_user=isset($_SESSION["user_id"])  ? $_SESSION["user_id"] : null ;
         $ti_usua =UserData::get_tipo_usuario($ti_user);

         foreach ($ti_usua as $key) {
           $tipo=$key->tipo_usuario;
   }



$columns = array( 
// datatable column index  => database column name
	0 => 'id',
    1 => 'nombre_dia', 
	2 => 'fecha',
	3 => 'total',
    4 => 'comentarios',
    5 => 'pag',
    6 => 'fac',
    7 => 'nofac',
    8 => 'banco',
    9 => 'status'
    
);

// getting total number records without any search
$sql = "SELECT s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,s.status,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha FROM sell s WHERE operation_type_id='1'";

$query=mysqli_query($conn, $sql) or die("./?action=expenses: get InventoryItems");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


if( !empty($requestData['search']['value']) ) {
	// if there is a search parameter
	$sql = "SELECT  s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,s.status,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha,DATE_FORMAT(s.created_at,'%Y-%m-%d')as fecha_gasto";
	$sql.=" FROM sell s";
	$sql.=" WHERE operation_type_id='1'"; 
	$sql.=" AND id LIKE '%".$requestData['search']['value']."%' ";    // $requestData['search']['value'] contains search parameter
	$sql.=" OR noFac LIKE '%".$requestData['search']['value']."%' ";
	$sql.=" OR comentarios LIKE '%".$requestData['search']['value']."%' ";
	$query=mysqli_query($conn, $sql) or die("./?action=expenses: get PO");
	$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result without limit in the query 

	$sql.=" ORDER BY s.id DESC  LIMIT ".$requestData['start']." ,".$requestData['length']."   "; // $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc , $requestData['start'] contains start row number ,$requestData['length'] contains limit length.
	$query=mysqli_query($conn, $sql) or die("./?action=expenses: get PO"); // again run query with limit
	
} else {	

	$sql = "SELECT s.comentarios,s.banco,s.noFac,s.id,s.total,s.created_at,s.fac,s.status,CONCAT(ELT(WEEKDAY(s.created_at) + 1, 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo')) AS nombre_dia, DATE_FORMAT(s.created_at,'%d/%m/%Y')as fecha,DATE_FORMAT(s.created_at,'%Y-%m-%d')as fecha_gasto";
	$sql.=" FROM sell s";
	$sql.=" WHERE operation_type_id='1'"; 
	$sql.=" ORDER BY s.id DESC   LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
	$query=mysqli_query($conn, $sql) or die("./?action=expenses: get PO");
	
}

	

$data = array();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

   		 if($row["status"]==1){
                 	 $t="success";
                 }else{
                     $t="danger";
                 }
                   if($tipo=="su" || $tipo=="sub"){

                   $nestedData[] =  '<td >
               <a href="index.php?view=delbuy&id='.$row["id"].'" class="btn btn-xs btn-danger" onClick="return confirmar()"><i class="glyphicon glyphicon-trash"></i></a>
                     </td>';

                    $nestedData[] =  '<td >
			   <a href="index.php?view=buyUpd&id='.$row["id"].'&date='.$row["fecha_gasto"].'" class="btn btn-xs btn-warning"><i class="glyphicon glyphicon-pencil"></i></a>
		     	     </td>';
                      }else{
                 $nestedData[] =  '<td ></td>';
                  $nestedData[] =  '<td ></td>';
               }	
    
 
	$nestedData[] = '<td>'.$row["id"]."</td>";
    $nestedData[] = '<td>'.$row["nombre_dia"]."</td>";
	$nestedData[] = '<td>'.$row["fecha"]."</td>";
	$nestedData[] = '<td>'.number_format($row["total"],2)."</td>";
    $nestedData[] = '<td>'.$row["comentarios"]."</td>";
    //RECORRIDO  
    $tPa="";$tBa="";$typeP=0;

    $typeP = OperationData::getAllBySellPayE($row["id"]);
    foreach ($typeP as $key) {
    
    $typeP= '<td>'.number_format($key->cash,2).'</td>';
    }
    $nestedData[]= $typeP;
    $typeP = OperationData::getAllBySellPay($row["id"]);
    foreach ($typeP as $key) {
    
            if($key->tname=="T. DEBITO" || $key->tname=="T. CREDITO")
                	{

                    $tPa="Santander";
                    
                	}else if ($key->tname=="TRANSFERENCIA" || $key->tname=="CHEQUES" || $key->tname=="INST. VIDA" || $key->tname=="SEGUROS" || $key->tname=="STAR MED" || $key->tname=="OTROS"){
                     $tBa="Banco";
                	}else{
                      $tPa="NA";
                	}
    }

    if($row["fac"]==1){
			 $nestedData[] =	 '<td>
                 <form id="uptExpFac" method="GET" role="form">
                 <input type="hidden" name="view" id="view" value="uptExpFac">
                 <button type="submit" name="valor" id="valor" class="btn btn-xs btn-success" value="0">Facturar</button>
			     <input type="hidden" name="idExp" id="idExp" value="'.$row["id"].'">
				 </form></td>';
				  
				  $nestedData[] ='<td>
                  <form id="uptFaExpS" method="GET" role="form" autocomplete="off">
                 <input type="hidden" name="view" id="view" value="uptFaExpS">
                 <input type="text" name="noFac" id="noFac" value="'.$row["noFac"].'">
			     <input type="hidden" name="idExp" id="idExp" value="'.$row["id"].'">
				 </form></td>';
                 }else{
                 $nestedData[] =  '<td>
                 <form  method="GET"  id="uptExpFac" role="form">
                 <input type="hidden" name="view" id="view" value="uptExpFac">
                 <button type="submit" name="valor" id="valor" class="btn btn-danger btn-xs" value="1">No facturar</button>
                 <input type="hidden" name="idExp" id="idExp" value="'.$row["id"].'">
                 </form></td>';
                
                 $nestedData[] = '<td><label>No aplica</label></td>';
                 }


                 if ($tBa=="Banco"){
                           
                 if($row["banco"] ==0){
                   $nestedData[] =  '<td>
                  <form  method="GET"  id="uptBanFacE" role="form">
                  <input type="hidden" name="idExp" id="idExp" value="'.$row["id"].'">
                  <input type="hidden" name="view" id="view" value="uptBanFacE">
                  <button type="submit" name="ban" class="btn btn-xs btn-success" id="valor" value="1">Santander</button></td>';
                 '
                 </form>';
                 }
                 else if($row["banco"] ==1){
                   $nestedData[] = '<td>
                  <form  method="GET"  id="uptBanFacE" role="form">
                  <input type="hidden" name="idExp" id="idExp" value="'.$row["id"].'">
                  <input type="hidden" name="view" id="view" value="uptBanFacE"><button type="submit" name="ban" id="ban"  class="btn btn-danger btn-xs" value="0">Banorte</button></td>';
                  '
                 </form>';
                 }
     
               }

                else if ($tPa=="Santander"){
                 $nestedData[] =  "<td><label>Santander</label></td>";
                }
                else{
                 $nestedData[] =  "<td><label>No aplica</label></td>";
                }
				

                 if($row["status"]==1){
                 	 $nestedData[]= '<td><b class="success">PAGADA</b></td>';
                 }else{
                     $nestedData[]= "<td><b>PENDIENTE</b></td>";
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
