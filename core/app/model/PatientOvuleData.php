<?php
class PatientOvuleData
{
	public static $tablename = "patient_ovules";
	public static $tablenameProcedure = "patient_embryology_procedure_ovules";
	public static $tablenameProcedureDetails = "patient_embryology_procedure_ovule_details";
	public static $tablenameOvuleSections = "embryology_procedure_ovule_sections";
	public static $tablenameOvuleSectionDetails = "embryology_procedure_ovule_section_details";
	public static $tablenameTreatments = "patient_category_treatments";

	public static $months = ["01" => "Enero", "02" => "Febrero", "03" => "Marzo", "04" => "Abril", "05" => "Mayo", "06" => "Junio", "07" => "Julio", "08" => "Agosto", "09" => "Septiembre", "10" => "Octubre", "11" => "Noviembre", "12" => "Diciembre"];

	/*ESTATUS DE ÓVULOS/EMBRIONES
	- (1) RECUPERADO 
	- (2) ÓVULO CONGELADO
	- (3) EMBRIÓN CONGELADO
	- (4) TRANSFERIDO
	- (5) ÓVULO NO VÁLIDO
	- (6) EMBRIÓN NO VÁLIDO
	*/

	public function __construct()
	{
		$this->title = "";
		$this->email = "";
		$this->image = "";
		$this->password = "";
		$this->is_public = "0";
		$this->created_at = "NOW()";
	}

	public function getPatient(){return PatientData::getById($this->patient_id);}
	public function getDonor(){return PatientData::getById($this->donor_id);}
	public function getMedic(){return MedicData::getById($this->medic_id);}
	public function getTreatment(){return PatientCategoryData::getTreatmentById($this->patient_treatment_id);}
	public function getProcedureOvuleSemen(){return AndrologyProcedureData::getPatientProcedureById($this->patient_andrology_procedure_id);}//Obtiene el procedimiento de andrología que se utilizó en el óvulo/embrión
	public function getOriginPatientTreatment(){return PatientCategoryData::getById($this->origin_patient_category_treatment_id);}
	public function getImage(){return EmbryologyProcedureData::getFileByTreatmentOvuleSectionId($this->patient_category_treatment_id,$this->id,1);}

	/*----------------ÓVULOS/EMBRIONES DEL PACIENTE----------------------- */
	public function add()
	{
		$sql = "INSERT INTO " . self::$tablename . " (procedure_code,origin_patient_category_treatment_id,donor_id,recipient_id,donation_date,ovule_status_id) ";
		$sql .= "value (\"$this->procedure_code\",\"$this->origin_patient_category_treatment_id\",\"$this->donor_id\",\"$this->recipient_id\",\"$this->donation_date\",\"$this->ovule_status_id\")";
		return Executor::doit($sql);
	}

	public function updateRecipientPatient()
	{
		$sql = "UPDATE " . self::$tablename . " set recipient_id=\"$this->recipient_id\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public static function updateDonationDateByTreatmentId($patientCategoryTreamentId,$donationDate)
	{
		$sql = "UPDATE " . self::$tablename . " set donation_date = $donationDate
		WHERE origin_patient_category_treatment_id = $patientCategoryTreamentId";
		return Executor::doit($sql);
	}

	public function updateStatusPhase()
	{
		$sql = "UPDATE " . self::$tablename . " set ovule_status_id=\"$this->ovule_status_id\",ovule_phase_id=\"$this->ovule_phase_id\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function delete()
	{
		$sql = "DELETE FROM " . self::$tablename . " WHERE id =$this->id";
		Executor::doit($sql);
	}

	public static function getById($id)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE id = $id";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	public static function getTotalByDonorId($patientId)
	{
		$sql = "SELECT COUNT(id) AS total FROM " . self::$tablename . " WHERE donor_id = $patientId";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}
	
	public static function getAllByDonorId($patientId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE donor_id = $patientId";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	//Obtiene los óvulos del paciente que los donó que aún son utilizables para procedimientos
	public static function getValidByDonorId($patientId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE donor_id = $patientId AND (ovule_status_id != 5 AND ovule_status_id != 6 AND ovule_status_id != 4)";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	public static function getAllByRecipientId($patientId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE recipient_id = $patientId";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	//Obtiene los óvulos del paciente que aún son utilizables para procedimientos
	public static function getValidByRecipienId($patientId)
	{
		$sql = "SELECT * FROM " . self::$tablename . " WHERE recipient_id = $patientId AND (ovule_status_id != 5 AND ovule_status_id != 6 AND ovule_status_id != 4)";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	/*-----------------------ÓVULOS/EMBRIONES DEL PROCEDIMIENTO/TRATAMIENTO DEL PACIENTE---------------------- */

	public function addByProcedure()
	{
		$sql = "INSERT INTO " . self::$tablenameProcedure . " (initial_ovule_status_id,patient_category_treatment_id,section_id,patient_ovule_id) ";
		$sql .= "VALUE (\"$this->ovule_status_id\",\"$this->patient_category_treatment_id\",\"$this->section_id\",\"$this->patient_ovule_id\")";
		return Executor::doit($sql);
	}

	public function updateEndStatusPhase(){
		$sql = "UPDATE ".self::$tablenameProcedure. " SET end_ovule_status_id=\"$this->end_ovule_status_id\",
			end_ovule_phase_id=\"$this->end_ovule_phase_id\" 
			WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Actualiza el procedimiento de andrología (muestra de semen) utilizado para fertilizar el óvulo/embrión
	public function updateProcedureOvuleSemen()
	{
		$sql = "UPDATE " . self::$tablenameProcedure . " SET patient_andrology_procedure_id=\"$this->patient_andrology_procedure_id\" WHERE id = $this->id";
		return Executor::doit($sql);
	}

	//Al eliminar una muestra de semen de un tratamiento, eliminar todas las referencias (todos los óvulos que indicaron que se fertilizaron con esa muestra) 
	public static function deleteOvuleProcedureSemenByTreatment($patientCategoryTreamentId,$patientAndrologyProcedureId)
	{
		$sql = "UPDATE " . self::$tablenameProcedure . " SET patient_andrology_procedure_id = 0 WHERE patient_andrology_procedure_id = '$patientAndrologyProcedureId' AND patient_category_treatment_id = '$patientCategoryTreamentId' ";
		return Executor::doit($sql);
	}

	public function updateEndPhase(){
		$sql = "UPDATE ".self::$tablenameProcedure. " SET end_ovule_phase_id=\"$this->end_ovule_phase_id\" 
			WHERE id = $this->id";
		return Executor::doit($sql);
	}

	public function deleteByOvuleProcedure()
	{
		$sql = "DELETE FROM " . self::$tablenameProcedure . " WHERE id = $this->id";
		return Executor::doit($sql);
	}
	
	//Obtiene el detalle del óvulo del procedimiento por el id
	public static function getProcedureOvuleById($procedureOvuleId){
		$sql = "SELECT " . self::$tablenameProcedure . ".*,
		" . self::$tablename. ".procedure_code
		 FROM " . self::$tablenameProcedure . " 
		 INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id =  " . self::$tablenameProcedure . ".patient_ovule_id
		 WHERE " . self::$tablenameProcedure . ".id = '$procedureOvuleId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	//Obtiene el detalle del óvulo del procedimiento por el id del óvulo del paciente
	public static function getProcedureOvuleByPatientOvuleId($procedureId,$patientOvuleId){
		$sql = "SELECT * FROM " . self::$tablenameProcedure . " 
		WHERE patient_category_treatment_id = '$procedureId' 
		AND patient_ovule_id = '$patientOvuleId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}
	
	public static function getTotalProcedureOvulesByPatient($treatmentId,$patientId){
		//Obtiene el total de óvulos registrados para el paciente de todos los tratamientos primarios (sin incluir los subtratamientos que repiten los óvulos del tratamiento padre)
		$sql = "SELECT COUNT(ppo.id) AS total 
			FROM " . self::$tablenameProcedure . " ppo
			INNER JOIN " . self::$tablenameTreatments . " pt ON pt.id = ppo.patient_category_treatment_id
			AND pt.id < '$treatmentId'
			AND pt.patient_id = '$patientId'
			AND pt.primary_treatment_id = 0";

		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	//+++++++++++++++++++++ Tabla Detalles de Óvulos +++++++++++++++++

	//SECCIONES
	public static function getAllSectionsByTreatment($treatmentId,$subsectionId){
		//Obtiene todos los títulos de las sub columnas de las secciones para la tabla
		$sql = "SELECT " . self::$tablenameOvuleSections . ".*,
			(SELECT COUNT(id) FROM  " . self::$tablenameOvuleSectionDetails . " 
				WHERE  " . self::$tablenameOvuleSectionDetails . ".embryology_procedure_ovule_section_id = " . self::$tablenameOvuleSections . ".id
				AND  " . self::$tablenameOvuleSectionDetails . ".status = 1) 
				AS total_section_details
			FROM " . self::$tablenameOvuleSections . " 
			WHERE " . self::$tablenameOvuleSections . ".status = 1
			AND " . self::$tablenameOvuleSections . ".treatment_id = '$treatmentId'
			AND " . self::$tablenameOvuleSections . ".treatment_subsection_id = '$subsectionId'
			ORDER BY " . self::$tablenameOvuleSections . ".ordering ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

	//DETALLES DE LAS SECCIONES/SUB-SECCIONES/SUB-COLUMNAS
	public static function getAllSectionDetailsByTreatment($treatmentId,$subsectionId){
		//Obtiene todos los títulos de las sub columnas de las secciones para la tabla
		$sql = "SELECT " . self::$tablenameOvuleSectionDetails . ".* 
			FROM " . self::$tablenameOvuleSectionDetails . "
			INNER JOIN " . self::$tablenameOvuleSections . " ON " . self::$tablenameOvuleSections . ".id = " . self::$tablenameOvuleSectionDetails . ".embryology_procedure_ovule_section_id
			AND " . self::$tablenameOvuleSections . ".treatment_id = '$treatmentId'
			AND " . self::$tablenameOvuleSections . ".treatment_subsection_id = '$subsectionId'
			WHERE " . self::$tablenameOvuleSections . ".status = 1
			AND " . self::$tablenameOvuleSectionDetails . ".status = 1
			ORDER BY " . self::$tablenameOvuleSections . ".ordering," . self::$tablenameOvuleSectionDetails . ".ordering ASC";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

	//OBTENER FILAS DE LA TABLA/OVOCITOS UTILIZADOS EN ESE PROCEDIMIENTO
	public static function getOvulesByProcedureSectionId($id,$sectionId){
		//Obtiene los datos de los óvulos utilizados en el procedimiento.
		$sql = "SELECT " . self::$tablenameProcedure . ".*,
				" . self::$tablename . ".procedure_code," . self::$tablename . ".origin_patient_category_treatment_id,
				" . self::$tablename . ".donor_id," . self::$tablename . ".recipient_id
				FROM " . self::$tablenameProcedure . "			
				INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameProcedure . ".patient_ovule_id
				WHERE " . self::$tablenameProcedure . ".patient_category_treatment_id = '$id'
				AND " . self::$tablenameProcedure . ".section_id = '$sectionId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

	public static function getSectionDetailsByOvuleId($treatmentId,$subsectionId,$procedureOvuleId){
		//Obtiene todos los títulos de las sub columnas de las secciones y si hay datos capturados en ese procedimiento específico.
		$sql = "SELECT " . self::$tablenameOvuleSectionDetails . ".id AS ovule_section_detail_id,
			" . self::$tablenameOvuleSectionDetails . ".name AS embryology_procedure_section_detail_name,
			" . self::$tablenameProcedureDetails . ".procedure_ovule_id,
			" . self::$tablenameProcedureDetails . ".value
			FROM " . self::$tablenameOvuleSectionDetails . "
			INNER JOIN " . self::$tablenameOvuleSections . " ON " . self::$tablenameOvuleSections . ".id = " . self::$tablenameOvuleSectionDetails . ".embryology_procedure_ovule_section_id 
			AND " . self::$tablenameOvuleSections . ".treatment_id = '$treatmentId'
			AND " . self::$tablenameOvuleSections . ".treatment_subsection_id = '$subsectionId'
			LEFT JOIN " . self::$tablenameProcedureDetails . " ON " . self::$tablenameOvuleSectionDetails . ".id = " . self::$tablenameProcedureDetails . ".ovule_section_detail_id
			AND  " . self::$tablenameProcedureDetails . ".procedure_ovule_id = '$procedureOvuleId'
			WHERE " . self::$tablenameOvuleSections . ".status = 1
			AND " . self::$tablenameOvuleSectionDetails . ".status = 1
			ORDER BY " . self::$tablenameOvuleSections . ".ordering," . self::$tablenameOvuleSectionDetails . ".ordering ASC";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

	public static function getSectionDetailValueByOvuleId($treatmentId,$subsectionId,$procedureOvuleId,$sectionDetailId){
		//Obtiene el valor capturado en cierto títulos de las sub columnas de las secciones y si hay datos capturados en ese procedimiento específico.
		$sql = "SELECT " . self::$tablenameOvuleSectionDetails . ".id AS ovule_section_detail_id,
			" . self::$tablenameOvuleSectionDetails . ".name AS embryology_procedure_section_detail_name,
			" . self::$tablenameProcedureDetails . ".procedure_ovule_id,
			" . self::$tablenameProcedureDetails . ".value
			FROM " . self::$tablenameOvuleSectionDetails . "
			INNER JOIN " . self::$tablenameOvuleSections . " ON " . self::$tablenameOvuleSections . ".id = " . self::$tablenameOvuleSectionDetails . ".embryology_procedure_ovule_section_id 
			AND " . self::$tablenameOvuleSections . ".treatment_id = '$treatmentId'
			AND " . self::$tablenameOvuleSections . ".treatment_subsection_id = '$subsectionId'
			LEFT JOIN " . self::$tablenameProcedureDetails . " ON " . self::$tablenameOvuleSectionDetails . ".id = " . self::$tablenameProcedureDetails . ".ovule_section_detail_id
			AND  " . self::$tablenameProcedureDetails . ".procedure_ovule_id = '$procedureOvuleId'
			WHERE " . self::$tablenameOvuleSections . ".status = 1
			AND " . self::$tablenameOvuleSectionDetails . ".status = 1
			AND " . self::$tablenameOvuleSectionDetails . ".id = '$sectionDetailId'
			ORDER BY " . self::$tablenameOvuleSections . ".ordering," . self::$tablenameOvuleSectionDetails . ".ordering ASC";

		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	//OBTENER FILAS DE LA TABLA/EMBRIONES VITRIFICADOS EN ESE PROCEDIMIENTO
	public static function getOvulesByStatusPhaseProcedureId($procedureId,$statusId,$phaseId){
		//Obtiene los óvulos/embriones de acuerdo a su estatus o fase (óvulos/embriones)
		$sql = "SELECT " . self::$tablenameProcedure . ".*," . self::$tablename . ".procedure_code
				FROM " . self::$tablenameProcedure . "
				INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameProcedure . ".patient_ovule_id 
				WHERE " . self::$tablenameProcedure . ".patient_category_treatment_id = '$procedureId'
				AND " . self::$tablenameProcedure . ".end_ovule_status_id = '$statusId'
				AND " . self::$tablenameProcedure . ".end_ovule_phase_id = '$phaseId'
				ORDER BY id";

		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

	public static function getTotalOvulesByStatusPhaseProcedureId($procedureId,$statusId,$phaseId){
		//Obtiene los óvulos/embriones de acuerdo a su estatus o fase (óvulos/embriones)
		$sql = "SELECT COUNT(" . self::$tablenameProcedure . ".id) AS total
				FROM " . self::$tablenameProcedure . "
				INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameProcedure . ".patient_ovule_id 
				WHERE " . self::$tablenameProcedure . ".patient_category_treatment_id = '$procedureId'
				AND " . self::$tablenameProcedure . ".end_ovule_status_id = '$statusId'
				AND " . self::$tablenameProcedure . ".end_ovule_phase_id = '$phaseId'";

		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	public static function getSectionDetailOvule($procedureOvuleId,$sectionDetailId){
		$sql = "SELECT " . self::$tablenameProcedureDetails. ".*
			FROM " . self::$tablenameProcedureDetails. "
			WHERE " . self::$tablenameProcedureDetails. ".ovule_section_detail_id = '$sectionDetailId'
			AND " . self::$tablenameProcedureDetails. ".procedure_ovule_id = '$procedureOvuleId'";
		$query = Executor::doit($sql);
		return Model::one($query[0], new PatientOvuleData());
	}

	public function addSectionDetailOvule(){
		$sql = "INSERT INTO " . self::$tablenameProcedureDetails. " (ovule_section_detail_id,procedure_ovule_id,value) ";
		$sql .= "VALUE (\"$this->ovule_section_detail_id\",\"$this->procedure_ovule_id\",\"$this->value\")";
		return Executor::doit($sql);
	}

	public function updateSectionDetailOvule(){
		$sql = "UPDATE ".self::$tablenameProcedureDetails." 
			SET value=\"$this->value\" 
			WHERE ovule_section_detail_id = $this->ovule_section_detail_id 
			AND procedure_ovule_id = $this->procedure_ovule_id";
		return Executor::doit($sql);
	}

	//Obtiene los datos de los óvulos/embriones de determinado procedimiento dependiendo de su fase final (embrión/óvulo).
	//Que sean válidos (omite los no viables, transferidos o utilizados en un tratamiento posterior)
	//Destinos válidos son congelado(2),pgta(5),cultivo(6)
	public static function getValidOvulesByEndPhaseProcedureId($procedureId,$endPhaseId){
		//patient_procedure_code incluye id del paciente (validar si es donor_id) + código de procedimiento del óvulo para identificar de qué paciente es en los select
		$sql = "SELECT " . self::$tablename . ".id, CONCAT(" . self::$tablename . ".donor_id,'-'," . self::$tablename . ".procedure_code) AS patient_procedure_code, " . self::$tablename . ".procedure_code
				FROM " . self::$tablenameProcedure . "
				INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameProcedure . ".patient_ovule_id 
				WHERE " . self::$tablenameProcedure . ".patient_category_treatment_id = '$procedureId'
				AND " . self::$tablenameProcedure . ".end_ovule_phase_id = '$endPhaseId'
				AND (" . self::$tablenameProcedure . ".end_ovule_status_id = '2' 
				|| " . self::$tablenameProcedure . ".end_ovule_status_id = '5'
				|| " . self::$tablenameProcedure . ".end_ovule_status_id = '6')";
				/*
				AND (SELECT COUNT(ot.id) FROM 
				" . self::$tablenameProcedure . " ot
				WHERE " . self::$tablename . ".id = ot.patient_ovule_id) < 2
				 */
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

	//Obtiene los datos de los óvulos/embriones de determinado procedimiento en cualquiera de sus fases(embrión/óvulo)
	//Que sean válidos (omite los no viables, transferidos o utilizados en un tratamiento posterior).
	//Destinos válidos son congelado(2),pgta(5),cultivo(6)
	public static function getValidOvulesByProcedureId($procedureId){
		//patient_procedure_code incluye id del paciente (validar si es donor_id) + código de procedimiento del óvulo para identificar de qué paciente es en los select
		$sql = "SELECT " . self::$tablename . ".id, CONCAT(" . self::$tablename . ".donor_id,'-'," . self::$tablename . ".procedure_code) AS patient_procedure_code, " . self::$tablename . ".procedure_code
				FROM " . self::$tablenameProcedure . "
				INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameProcedure . ".patient_ovule_id 
				INNER JOIN " . self::$tablenameTreatments . " ON " . self::$tablenameTreatments . ".id = " . self::$tablenameProcedure . ".patient_category_treatment_id 
				WHERE " . self::$tablenameProcedure . ".patient_category_treatment_id = '$procedureId'
				AND (" . self::$tablenameProcedure . ".end_ovule_status_id = '2' 
				|| " . self::$tablenameProcedure . ".end_ovule_status_id = '5'
				|| " . self::$tablenameProcedure . ".end_ovule_status_id = '6')";
				/* 
				AND (SELECT COUNT(ot.id) FROM 
				" . self::$tablenameProcedure . " ot
				WHERE " . self::$tablename . ".id = ot.patient_ovule_id) < 2
				*/
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}

		//Obtiene los procedimientos posteriores en los que se utilizaron los óvulos/embriones de un determinado procedimiento en cualquiera de sus fases(embrión/óvulo).
		public static function getUsedOvulesByProcedureId($procedureId, $statusId, $phaseId)
		{
			//obtener el siguiente tratamiento en el que se utilizó un óvulo vitrificado de x procedimiento.
			$sql = "SELECT next_treatment.treatment_code,
				(SELECT COUNT(po_sq.id) AS total
					FROM " . self::$tablenameProcedure . " AS ppo_sq
					INNER JOIN " . self::$tablename . " po_sq ON po_sq.id = ppo_sq.patient_ovule_id 
					INNER JOIN " . self::$tablenameTreatments . " original_treatment_sq ON original_treatment_sq.id = ppo_sq.patient_category_treatment_id
					WHERE ppo_sq.end_ovule_status_id = '$statusId'
					AND ppo_sq.end_ovule_phase_id = '$phaseId'
					AND (SELECT nt_sq.id FROM 
					" . self::$tablenameProcedure . " next_ovule_procedure_sq
					INNER JOIN " . self::$tablenameTreatments . " nt_sq ON nt_sq.id = next_ovule_procedure_sq.patient_category_treatment_id
					WHERE ppo_sq.id = next_ovule_procedure_sq.patient_ovule_id 
					AND nt_sq.start_date > original_treatment_sq.start_date LIMIT 1) = next_treatment.id
					AND ppo_sq.patient_category_treatment_id = original_treatment.id
				) AS total

				FROM " . self::$tablenameProcedure . " AS ppo
				INNER JOIN " . self::$tablename . " po ON po.id = ppo.patient_ovule_id 
				INNER JOIN " . self::$tablenameTreatments . " original_treatment ON original_treatment.id = ppo.patient_category_treatment_id 
				INNER JOIN " . self::$tablenameTreatments . " next_treatment ON next_treatment.id = (SELECT nt.id FROM 
				" . self::$tablenameProcedure . " next_ovule_procedure
				INNER JOIN " . self::$tablenameTreatments . " nt ON nt.id = next_ovule_procedure.patient_category_treatment_id
				WHERE ppo.id = next_ovule_procedure.patient_ovule_id 
				AND nt.start_date > original_treatment.start_date LIMIT 1)
				WHERE ppo.patient_category_treatment_id = '$procedureId'
				AND ppo.end_ovule_status_id = '$statusId'
				AND ppo.end_ovule_phase_id = '$phaseId'
				GROUP BY next_treatment.treatment_code 
				ORDER BY next_treatment.start_date";
			$query = Executor::doit($sql);
			return Model::many($query[0], new PatientOvuleData());
		}

		/*

		(SELECT next_treatment.treatment_code FROM 
				" . self::$tablenameProcedure . " next_ovule_procedure
				INNER JOIN " . self::$tablenameTreatments . " next_treatment ON next_treatment.id = next_ovule_procedure.patient_category_treatment_id
				WHERE ppo.id = next_ovule_procedure.patient_ovule_id 
				AND next_treatment.start_date > original_treatment.start_date LIMIT 1) AS next_treatment_code



				AND (SELECT COUNT(next_ovule_procedure.id) FROM 
					" . self::$tablenameProcedure . " next_ovule_procedure
					INNER JOIN " . self::$tablenameTreatments . " next_treatment ON next_treatment.id = next_ovule_procedure.patient_category_treatment_id
					WHERE ppo.id = next_ovule_procedure.patient_ovule_id 
					AND next_treatment.start_date > original_treatment.start_date) >= 1










						INNER JOIN " . self::$tablenameTreatments . " next_treatment ON next_treatment.id = 
				(SELECT ot.id FROM 
				" . self::$tablenameProcedure . " ot
				WHERE po.id = ot.patient_ovule_id 
				ORDER BY ot.patient_category_treatment_id DESC LIMIT 1)


						INNER JOIN " . self::$tablenameTreatments . " next_treatment ON next_treatment.id = (SELECT ot.id FROM 
				" . self::$tablenameProcedure . " ot
				INNER JOIN " . self::$tablenameTreatments . " otp ON otp.id = ot.patient_category_treatment_id
				WHERE " . self::$tablename . ".id = ot.patient_ovule_id 
				AND otp.start_date >= " . self::$tablename . ".start_date LIMIT 1) 

				*/

	//Obtiene el registro del óvulo del procedimiento si ya se agregó ese óvulo del paciente al procedimiento
	public static function validateOvuleByProcedure($procedureId,$ovuleId){
		$sql = "SELECT * FROM " . self::$tablenameProcedure . "
				INNER JOIN " . self::$tablename . " ON " . self::$tablename . ".id = " . self::$tablenameProcedure . ".patient_ovule_id 
				WHERE " . self::$tablenameProcedure . ".patient_category_treatment_id = '$procedureId'
				AND " . self::$tablenameProcedure . ".patient_ovule_id = '$ovuleId'";
		$query = Executor::doit($sql);
		return Model::many($query[0], new PatientOvuleData());
	}
}
