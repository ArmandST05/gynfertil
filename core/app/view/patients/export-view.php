<?php
$user = UserData::getLoggedIn();
$userType = (isset($user)) ? $user->user_type : null;
$branchOfficeId = isset($_GET['branchOfficeId']) ? $_GET['branchOfficeId'] : 0;
$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : 'all';
$companyId = isset($_GET['companyId']) ? $_GET['companyId'] : 'all';
$categories = PatientData::getAllCategories();
$companies = CompanyData::getAll();

if ($userType == "r") {
	$branchOfficeId = $user->getBranchOffice()->id;
	$branchOffices = [$user->getBranchOffice()];
} else {
	$branchOffices = BranchOfficeData::getAllByStatus(1);
	if ($userType == "co") {
		$branchOfficeId = (isset($_GET["branchOfficeId"])) ? $_GET["branchOfficeId"] : $user->getBranchOffice()->id;
	} else {
		$branchOfficeId = (isset($_GET["branchOfficeId"])) ? $_GET["branchOfficeId"] : 0;
	}
}
if ($branchOfficeId) {
	$medics = MedicData::getAllByBranchOffice($branchOfficeId);
}

$medicId = (isset($_GET["medicId"])) ? $_GET["medicId"] : 0;
$categoryId = (isset($_GET["categoryId"])) ? $_GET["categoryId"] : "all";
$companyId = (isset($_GET["companyId"])) ? $_GET["companyId"] : "all";
$patients = PatientData::getAllExport();

function applyFilters($patients, $branchOfficeId, $medicId, $categoryId, $companyId) {
    return array_filter($patients, function($patient) use ($branchOfficeId, $medicId, $categoryId, $companyId) {
        if ($branchOfficeId != 0 && $patient->branch_office_id != $branchOfficeId) {
            return false;
        }
        if ($medicId != 0) {
            if (empty($patient->medic_id) || $patient->medic_id != $medicId) {
                return false;
            }
        }
        if ($categoryId !== 'all') {
            if ($categoryId === 'active') {
                if (!in_array($patient->category_id, [1, 4])) {
                    return false;
                }
            } else {
                if ($patient->category_id != $categoryId) {
                    return false;
                }
            }
        }
        if ($companyId !== 'all') {
            if ($companyId === 'company') {
                if (empty($patient->company_id)) return false;
            } elseif ($companyId === 'withoutCompany') {
                if (!empty($patient->company_id)) return false;
            } else {
                if ($patient->company_id != $companyId) return false;
            }
        }
        return true;
    });
}

$filteredPatients = applyFilters($patients, $branchOfficeId, $medicId, $categoryId, $companyId);
?>
<form method="GET" action="">
    <div class="row">
        <div class="col-md-3">
            <label class="control-label">Sucursal:</label>
            <select name="branchOfficeId" class="form-control" required>
                <?php if ($userType == "su" || $userType == "co") : ?>
                    <option value="0">-- TODAS --</option>
                <?php endif; ?>
                <?php foreach ($branchOffices as $branchOffice) : ?>
                    <option value="<?php echo $branchOffice->id; ?>" <?php echo ($branchOfficeId == $branchOffice->id) ? "selected" : "" ?>><?php echo $branchOffice->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="control-label">Psicólogo:</label>
            <div class="form-group">
                <select name="medicId" class="form-control" required>
                    <option value="0">-- TODOS --</option>
                    <?php foreach ($medics as $medic) : ?>
                        <option value="<?php echo $medic->id; ?>" <?php echo ($medicId == $medic->id) ? "selected" : "" ?>><?php echo $medic->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <label class="control-label">Categoría:</label>
            <div class="form-group">
                <select name="categoryId" class="form-control" required>
                    <option value="all" <?php echo ($categoryId == "all") ? "selected" : "" ?>>-- TODOS --</option>
                    <option value="active" <?php echo ($categoryId == "active") ? "selected" : "" ?>>ACTIVOS</option>
                    <option value="1" <?php echo ($categoryId == 1) ? "selected" : "" ?>>ACTIVO (NO REINGRESO)</option>
                    <option value="4" <?php echo ($categoryId == 4) ? "selected" : "" ?>>ACTIVO (REINGRESO)</option>
                    <option value="2" <?php echo ($categoryId == 2) ? "selected" : "" ?>>ALTA</option>
                    <option value="3" <?php echo ($categoryId == 3) ? "selected" : "" ?>>INACTIVO</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <label class="control-label">Empresa:</label>
            <div class="form-group">
                <select name="companyId" class="form-control" required>
                    <option value="all" <?php echo ($companyId == "all") ? "selected" : "" ?>>-- TODOS --</option>
                    <option value="company" <?php echo ($companyId == "company") ? "selected" : "" ?>>SON DE EMPRESA</option>
                    <option value="withoutCompany" <?php echo ($companyId == "withoutCompany") ? "selected" : "" ?>>NO SON DE EMPRESA</option>
                    <?php foreach ($companies as $company) : ?>
                        <option value="<?php echo $company->id ?>" <?php echo ($companyId == $company->id) ? "selected" : "" ?>><?php echo $company->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <br>
<button type="submit" class="btn btn-sm btn-primary btn-block">Buscar</button>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12">
        <h1>Pacientes (Vista general)</h1>
        <table class="table table-bordered table-hover">
            <thead class="bg-light" align="center">
                <tr>
                    <th>Clave</th>
                    <th>Nombre completo</th>
                    <th>Dirección</th>
                    <th>Teléfonos</th>
                    <th>Email</th>
                    <th>Familiar</th>
                    <th>Psicólogo</th>
                    <th>Empresa</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredPatients as $patient): ?>
                    <tr>
                        <td><?php echo $patient->patient_id; ?></td>
                        <td><?php echo $patient->patient_name; ?></td>
                        <td>
                            <?php
                                $direccion = "{$patient->street} {$patient->number}, {$patient->colony}";
                                if (!empty($patient->county_name)) {
                                    $direccion .= ", {$patient->county_name}";
                                }
                                echo $direccion;
                            ?>
                        </td>
                        <td>
                            <?php
                                $telefonos = [];
                                if ($patient->cellphone) $telefonos[] = $patient->cellphone;
                                if ($patient->homephone) $telefonos[] = $patient->homephone;
                                echo implode(" / ", $telefonos);
                            ?>
                        </td>
                        <td><?php echo $patient->email; ?></td>
                        <td><?php echo $patient->relative_name; ?></td>
                        <td><?php echo $patient->medic_name ?? 'Sin asignar'; ?></td>
                        <td><?php echo $patient->company_name ?? 'Sin empresa'; ?></td>
                        <td><?php echo $patient->patient_category_name; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
