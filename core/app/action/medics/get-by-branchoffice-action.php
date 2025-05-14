<?php
    $medics = MedicData::getAllByBranchOffice($_GET["branchOfficeId"]);
    echo json_encode($medics);
?>