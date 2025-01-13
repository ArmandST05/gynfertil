<?php
    $search = $_GET["q"];
    $treatments = AndrologyProcedureData::getByCodeNameSearch($search);  
    echo json_encode($treatments);
?>