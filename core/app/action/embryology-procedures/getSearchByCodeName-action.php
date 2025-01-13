<?php
    $search = $_GET["q"];
    $treatments = PatientCategoryData::getByCodeNameSearch($search);  
    echo json_encode($treatments);
?>