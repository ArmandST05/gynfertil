<?php

$category = CategoryMedicData::getById($_GET["id"]);

$category->del();
Core::redir("./index.php?view=catmedic");


?>