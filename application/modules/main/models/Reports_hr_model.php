<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_hr_model extends Main_Model {

	public function __construct() {
		parent:: __construct();
	
	}
	// SCHEMA NAME
	public $DB_MAIN                     = DB_MAIN;
	public $DB_CORE                     = DB_CORE;
	
	public $tbl_organizations           = 'organizations';
	public $tbl_genders            		= 'param_genders';
	public $tbl_barangays            	= 'param_barangays';
	public $tbl_municities            	= 'param_municities';
	public $tbl_provinces           	= 'param_provinces';
	public $tbl_regions           		= 'param_regions';
	public $tbl_employee_addresses      = 'employee_addresses';
	public $tbl_param_address_type      = 'param_address_type';
	public $tbl_param_office_types      = 'param_office_types';

	public function get_reports($aColumns, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
EOS;
			RLog::debug($query);
			$stmt = $this->query($query, $filter_params, $multiple);
		
			return $stmt;
		}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;			
		}	
	}

	public function get_employee_list()
	{
		try
		{
			$val   = array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO);
			$query = <<<EOS
				SELECT 
				A.employee_id, 
				UPPER(CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='' OR A.ext_name IS NULL,'',CONCAT(' ', A.ext_name)))) as employee_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				WHERE B.employ_type_flag IN (?, ?, ?)
				ORDER BY employee_name ASC

EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_reg_employee_list()
	{
		try
		{
			$val   = array(DOH_GOV_APPT, DOH_GOV_NON_APPT);
			$query = <<<EOS
				SELECT 
				A.employee_id, 
				UPPER(CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='' OR A.ext_name IS NULL,'',CONCAT(' ', A.ext_name)))) as reg_employee_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				WHERE B.employ_type_flag IN (?, ?)
				ORDER BY reg_employee_name ASC

EOS;
	
			$stmt = $this->query($query, $val, TRUE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	public function get_nosa_employee_list()
	{
		try
		{
			$val   = array(675);
			$query = <<<EOS
				SELECT 
				A.employee_id, 
				UPPER(CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='' OR A.ext_name IS NULL,'',CONCAT(' ', A.ext_name)))) as nosa_employee_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				WHERE B.employ_personnel_movement_id = ?
				ORDER BY nosa_employee_name ASC

EOS;
	
			$stmt = $this->query($query, $val, TRUE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_signatory_list($signatory_type)
	{
		try
		{
			$val   = array(OTHER_INFO_TYPE_TITLE, $signatory_type);
			$query = <<<EOS
				SELECT 
			    A.employee_id,
				CONCAT(A.first_name, ' ', LEFT(A.middle_name, (1)), '. ', A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name)), IF(ISNULL(F.others_value), '', CONCAT(', ' , F.others_value))) employee_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				LEFT JOIN $this->tbl_employee_other_info F ON A.employee_id =  F.employee_id AND F.other_info_type_id = ?
				WHERE A.agency_employee_id IN (SELECT sys_param_value
			        FROM
			            $this->DB_CORE.$this->tbl_sys_param
			        WHERE
			            sys_param_type = ?)
				GROUP BY A.employee_id
EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}	

	public function get_office_list()
	{
		try
		{
			$val   = array(YES);
			$query = <<<EOS
				SELECT 
				B.name, 
				A.office_id, 
				A.org_code 
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code
				WHERE A.active_flag = ?
				AND A.office_type_id = "2"
EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_agency_info($office)
	{
		try
		{
			$val = array($office, YES);
			$query = <<<EOS
				SELECT 
				B.name, 
				A.office_id,
				B.org_code
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code
				WHERE A.office_id = ? AND A.active_flag = ?
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_personnel_movement_list($date_from, $date_to, $office, $movt_appointment, $salary_grade_rai)
	{
		try
		{

			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';
			$office_id    = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id' : 'A.employ_office_id';

			$where = array(DOH_GOV_APPT, DOH_GOV_NON_APPT, $date_from, $date_to);

				$movt_filter = '';
				if($movt_appointment)
				{
					$movt_cnt = count($movt_appointment);
					$movt_filter = ' AND A.employ_personnel_movement_id IN(';
					foreach ($movt_appointment as $key => $value) {
						if($movt_cnt > $key +1)
						{
							$movt_filter .= '?,';
						}
						else
						{
							$movt_filter .= '?';
						}
						$where[] = $value;				
					}
					$movt_filter .= ', 652)';
				}

				$office_filter = '';
				if($office)
				{
					$office_cnt = count($office);
					$office_filter = ' AND '.$office_id.' IN(';
					foreach ($office as $key => $value) {
						if($office_cnt > $key +1)
						{
							$office_filter .= '?,';
						}
						else
						{
							$office_filter .= '?';
						}
						$where[] = $value;				
					}
					$office_filter .= ')';
				}

				$sg_filter = NULL;

				if ($salary_grade_rai == 'X') 
				{
					$sg_filter = 'AND salary_grade < 19';
				}
				else if($salary_grade_rai == 'Y') 
				{
					$sg_filter = 'AND salary_grade BETWEEN 19 AND 25';
				}
				else
				{
					$sg_filter = 'AND salary_grade > 25';
				}
/*
			$query = <<<EOS
				SELECT 
				A.employ_start_date,
				CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, 
				A.employ_position_name position_name, 
				D.plantilla_code, 
				A.employ_monthly_salary, 
				CONCAT('SG-', cast(A.employ_salary_grade as char)) salary_grade, 
				E.employment_status_name, 
				F.personnel_movement_name, 
				$office_id office_id, 
				$office_name name,
				A.publication_date, 
				A.publication_place, 
				A.employ_start_date
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id 
				LEFT JOIN $this->tbl_param_plantilla_items D ON A.employ_plantilla_id = D.plantilla_id 
				LEFT JOIN $this->tbl_param_employment_status E ON A.employment_status_id = E.employment_status_id 
				LEFT JOIN $this->tbl_param_personnel_movements F ON A.employ_personnel_movement_id = F.personnel_movement_id 
				WHERE A.employ_type_flag IN(?,?)  
				AND A.employ_start_date BETWEEN ? AND ?
				$movt_filter
				$office_filter
				ORDER BY employee_name ASC
EOS;		
*/	
			// ====================== jendaigo : start : change name format and employ start date sorting ============= //
			$query = <<<EOS
				SELECT 
				A.employ_start_date,
				CONCAT(B.last_name, ', ', B.first_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name)), IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '. '))) employee_name, 
				A.employ_position_name position_name, 
				D.plantilla_code, 
				A.employ_monthly_salary, 
				CONCAT('SG-', cast(A.employ_salary_grade as char)) salary_grade, 
				E.employment_status_name, 
				F.personnel_movement_name, 
				$office_id office_id, 
				$office_name name,
				A.publication_date, 
				A.publication_date_to, 
				A.publication_place, 
				A.employ_start_date,
				B.last_name,
				B.first_name,
				B.ext_name,
				B.middle_name,
				G.employ_period_from,
				G.employ_period_to
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id 
				LEFT JOIN $this->tbl_param_plantilla_items D ON A.employ_plantilla_id = D.plantilla_id 
				LEFT JOIN $this->tbl_param_employment_status E ON A.employment_status_id = E.employment_status_id 
				LEFT JOIN $this->tbl_param_personnel_movements F ON A.employ_personnel_movement_id = F.personnel_movement_id 
				LEFT JOIN $this->tbl_employee_work_experience_details G ON G.employee_work_experience_id = A.employee_work_experience_id
				WHERE A.employ_type_flag IN(?,?)  
				AND A.employ_start_date BETWEEN ? AND ?
				$movt_filter
				$office_filter
				$sg_filter
				ORDER BY A.employ_start_date ASC
EOS;
			// ====================== jendaigo : end : change name format and employ start date sorting ============= //
			
			$stmt = $this->query($query, $where, TRUE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

// 	public function get_appointment_issued2_list($date_from, $date_to, $office, $movt_appointment)
// 	{
// 		try
// 		{			
// 			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';
// 			$office_id    = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id' : 'A.employ_office_id';

// 			$where = array(YES, YES, NON_DOH_GOV, YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, $date_from, $date_to);

// 			$movt_filter = '';
// 			if($movt_appointment)
// 			{
// 				$movt_cnt = count($movt_appointment);
// 				$movt_filter = ' AND A.employ_personnel_movement_id IN(';
// 				foreach ($movt_appointment as $key => $value) {
// 					if($movt_cnt > $key +1)
// 					{
// 						$movt_filter .= '?,';
// 					}
// 					else
// 					{
// 						$movt_filter .= '?';
// 					}
// 					$where[] = $value;				
// 				}
// 				$movt_filter .= ')';
// 			}

// 			$office_filter = '';
// 			if($office)
// 			{
// 				$office_cnt = count($office);
// 				$office_filter = ' AND '.$office_id.' IN(';
// 				foreach ($office as $key => $value) {
// 					if($office_cnt > $key +1)
// 					{
// 						$office_filter .= '?,';
// 					}
// 					else
// 					{
// 						$office_filter .= '?';
// 					}
// 					$where[] = $value;				
// 				}
// 				$office_filter .= ')';
// 			}

// 			$query = <<<EOS
// 				SELECT 
// 				A.employ_start_date,
// 				CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, 
// 				A.employ_position_name position_name,
// 				GROUP_CONCAT(DISTINCT E.degree_name SEPARATOR '<br>> ') degree_name,
// 				GROUP_CONCAT(DISTINCT F.training_name SEPARATOR '<br>> ') rel_training,
// 				GROUP_CONCAT(DISTINCT CONCAT(DATE_FORMAT(I.employ_start_date, '%m/%d/%Y'), '-', DATE_FORMAT(I.employ_end_date, '%m/%d/%Y'), ' ',     I.employ_position_name) SEPARATOR '<br>') rel_exp,
// 				H.eligibility_type_flag, 
// 				G.exam_place, 
// 				DATE_FORMAT(G.exam_date, '%m/%d/%Y') exam_date, 
// 				G.rating, 
// 				DATE_FORMAT(B.birth_date, '%m/%d/%Y') birth_date, 
// 				B.birth_place, 
// 				A.admin_office_id office_id, 
// 				A.admin_office_name name				
// 				FROM $this->tbl_employee_work_experiences A
// 				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
// 				LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id 
// 				LEFT JOIN $this->tbl_employee_educations D ON A.employee_id = D.employee_id AND D.relevance_flag = ?
// 				LEFT JOIN $this->tbl_param_education_degrees E ON D.education_degree_id = E.degree_id
// 				LEFT JOIN $this->tbl_employee_trainings F ON A.employee_id = F.employee_id AND F.relevance_flag = ?
// 				LEFT JOIN $this->tbl_employee_eligibility G ON A.employee_id = G.employee_id 
// 				LEFT JOIN $this->tbl_param_eligibility_types H ON G.eligibility_type_id = H.eligibility_type_id
// 				LEFT JOIN $this->tbl_employee_work_experiences I ON A.employee_id = I.employee_id AND I.employ_type_flag = ? AND I.relevance_flag = ?
// 				WHERE A.employ_type_flag IN(?,?) 
// 				AND A.employ_start_date BETWEEN ? AND ? 
// 				$movt_filter
// 				$office_filter
// 				GROUP BY A.employee_id
// 				ORDER BY employee_name ASC
				
// EOS;
	
// 			$stmt = $this->query($query, $where, TRUE);
		
// 			return $stmt;
// 		}
// 		catch (PDOException $e)
// 		{
// 			$this->rlog_error($e);
// 		}
// 		catch (Exception $e)
// 		{			
// 			$this->rlog_error($e);
// 		}	
// 	}


	//ncocampo 02/13/2024
	public function get_appointment_issued2_list($id, $movt_appointment)
	{
		try
		{
			$val = array(YES, YES, $id, DOH_GOV_APPT, DOH_GOV_NON_APPT);

			$movt_filter = '';
			if($movt_appointment)
			{
				$movt_cnt = count($movt_appointment);
				$movt_filter = ' AND A.employ_personnel_movement_id IN(';
				foreach ($movt_appointment as $key => $value) {
					if($movt_cnt > $key +1)
					{
						$movt_filter .= '?,';
					}
					else
					{
						$movt_filter .= '?';
					}
					$val[] = $value;				
				}
				$movt_filter .= ')';
			}

			$query = <<<EOS
				SELECT
				DATE_FORMAT(B.birth_date, '%m/%d/%Y') birth_date, 
				A.employ_salary_step, 
				TRIM('- DOH' FROM IFNULL(A.admin_office_name, A.employ_office_name)) agency_name, 
				GROUP_CONCAT(DISTINCT J.degree_name SEPARATOR '<br><br>* ') degree_name,
				GROUP_CONCAT(DISTINCT 
			    CASE 
			        WHEN K.training_start_date = K.training_end_date THEN
			            CONCAT(
			                K.training_name, '<br>',
			                DATE_FORMAT(K.training_start_date, '%b %d, %Y'), '<br>',
			                '(',
			                TRIM('.00' FROM (training_hour_count)), ' hours', ')'
			            )
			        ELSE
			            CONCAT(
			                K.training_name, '<br>',
			                DATE_FORMAT(K.training_start_date, '%b %d, %Y'), 
			                ' - ',
			                DATE_FORMAT(K.training_end_date, '%b %d, %Y'), '<br>',
			                '(',
			                TRIM('.00' FROM (training_hour_count)), ' hours', ')'
			            )
			    END
			    SEPARATOR '<br><br>* ') rel_training,
				GROUP_CONCAT(DISTINCT CONCAT(O.employ_position_name,  '<br>', '(', P.employment_status_name,')', '<br>', O.admin_office_name, '<br>',
				DATE_FORMAT(O.employ_start_date, '%b %d, %Y'), ' - ',
				IFNULL(DATE_FORMAT(O.employ_end_date, '%b %d, %Y'), 'PRESENT')) SEPARATOR '<br><br>* ') work_exp,
				N.education as req_education,
				N.experience as req_experience,
				N.training as req_training,
				N.eligibility as req_eligibility,
				GROUP_CONCAT(DISTINCT CONCAT(
					CASE 
						WHEN M.eligibility_type_flag = 'RA' THEN 'RA 1080 '
						WHEN M.eligibility_type_flag IS NULL OR M.eligibility_type_flag = '' THEN ''
						ELSE CONCAT(M.eligibility_type_flag, ' ')
					END,
					'(', M.eligibility_type_name,  ')'
				) SEPARATOR '<br><br>* ') AS eligibility_name,
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') start_date
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_employee_educations I ON A.employee_id = I.employee_id AND I.relevance_flag = ?
 				LEFT JOIN $this->tbl_param_education_degrees J ON I.education_degree_id = J.degree_id
 				LEFT JOIN $this->tbl_employee_trainings K ON A.employee_id = K.employee_id AND K.relevance_flag = ?
 				LEFT JOIN $this->tbl_employee_eligibility L ON A.employee_id = L.employee_id 
 				LEFT JOIN $this->tbl_param_eligibility_types M ON L.eligibility_type_id = M.eligibility_type_id AND L.relevance_flag = 'Y'
 				LEFT JOIN $this->tbl_param_positions N ON A.employ_position_id = N.position_id
 				LEFT JOIN $this->tbl_employee_work_experiences O ON A.employee_id = O.employee_id AND O.relevance_flag = 'Y'
				LEFT JOIN $this->tbl_param_employment_status P ON  P.employment_status_id = O.employment_status_id
 				
				WHERE A.employee_id = ? 
				AND A.employ_start_date = (SELECT MAX(A.employ_start_date))
				AND A.employ_type_flag IN(?,?) 
				$movt_filter				
				ORDER BY A.employ_start_date DESC

EOS;
			$stmt = $this->query($query, $val, FALSE);

			$val2 = array($id, DOH_GOV_APPT, DOH_GOV_NON_APPT, 640, 642 ,662, 653, 666, 651, 645);

			$query2 = <<<EOS
				SELECT 
				A.employ_salary_step, 
				B.education as req_education,
				B.experience as req_experience,
				B.training as req_training,
				B.eligibility as req_eligibility,
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') start_date
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_param_positions B ON A.employ_position_id = B.position_id
				WHERE A.employee_id = ? 
				AND A.employ_start_date = (SELECT MAX(A.employ_start_date))
				AND A.employ_type_flag IN(?,?) 
				$movt_filter				
				ORDER BY A.employ_start_date DESC
EOS;
			$stmt2 = $this->query($query2, $val2, FALSE);
				if ($stmt['employ_salary_step'] != $stmt2['employ_salary_step']) {
					$stmt['employ_salary_step'] = $stmt2['employ_salary_step'];
				}
				if ($stmt['req_education'] != $stmt2['req_education']) {
					$stmt['req_education'] = $stmt2['req_education'];
				}
				if ($stmt['req_experience'] != $stmt2['req_experience']) {
					$stmt['req_experience'] = $stmt2['req_experience'];
				}
				if ($stmt['req_training'] != $stmt2['req_training']) {
					$stmt['req_training'] = $stmt2['req_training'];
				}
				if ($stmt['req_eligibility'] != $stmt2['req_eligibility']) {
					$stmt['req_eligibility'] = $stmt2['req_eligibility'];
				}
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
//01/11/2024
	public function rai_part2_details($id)
	{
		try
		{
			$val = array($id);

			$query = <<<EOS
				SELECT 
				DATE_FORMAT(A.employ_start_date, '%b %d, %Y') start_date,
                IFNULL(DATE_FORMAT(A.employ_end_date, '%b %d, %Y'), 'PRESENT') end_date,
                A.employ_position_name,
                B.employment_status_name,
                A.employ_office_name

				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_param_employment_status B ON A.employment_status_id = B.employment_status_id
				WHERE A.employee_id = ? 
				AND A.relevance_flag = 'Y'
				AND A.employ_start_date = (SELECT MAX(A.employ_start_date))
				ORDER BY A.employ_start_date DESC
EOS;

			$stmt = $this->query($query, $val, TRUE);
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_office_child_list($date_from, $date_to, $office, $movt_appointment)
	{
		try
		{
			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';
			$office_id    = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id' : 'A.employ_office_id';

			$where = array(DOH_GOV_APPT, DOH_GOV_NON_APPT, $date_from, $date_to);

			$movt_filter = '';
			if($movt_appointment)
			{
				$movt_cnt = count($movt_appointment);
				$movt_filter = ' AND A.employ_personnel_movement_id IN(';
				foreach ($movt_appointment as $key => $value) {
					if($movt_cnt > $key +1)
					{
						$movt_filter .= '?,';
					}
					else
					{
						$movt_filter .= '?';
					}
					$where[] = $value;				
				}
				$movt_filter .= ')';
			}

			$office_filter = '';
			if($office)
			{
				$office_cnt = count($office);
				$office_filter = ' AND A.employ_office_id IN(';
				foreach ($office as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}
			

			$query = <<<EOS
				SELECT
				$office_name name, 
				$office_id office_id
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id 
				LEFT JOIN $this->tbl_param_plantilla_items D ON A.employ_plantilla_id = D.plantilla_id 
				LEFT JOIN $this->tbl_param_employment_status E ON A.employment_status_id = E.employment_status_id 
				LEFT JOIN $this->tbl_param_personnel_movements F ON A.employ_personnel_movement_id = F.personnel_movement_id
				WHERE A.employ_type_flag IN(?,?)  
				AND A.employ_start_date BETWEEN ? AND ?
				$movt_filter
				$office_filter
				GROUP BY $office_id
				ORDER BY $office_id ASC
EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_office_child_rai_list($date_from, $date_to, $office, $movt_appointment)
	{
		try
		{
			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';
			$office_id    = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id' : 'A.employ_office_id';
			
			$where = array(YES, NON_DOH_GOV, YES, DOH_GOV_APPT, DOH_GOV_NON_APPT, $date_from, $date_to);

			$movt_filter = '';
			if($movt_appointment)
			{
				$movt_cnt = count($movt_appointment);
				$movt_filter = ' AND A.employ_personnel_movement_id IN(';
				foreach ($movt_appointment as $key => $value) {
					if($movt_cnt > $key +1)
					{
						$movt_filter .= '?,';
					}
					else
					{
						$movt_filter .= '?';
					}
					$where[] = $value;				
				}
				$movt_filter .= ')';
			}

			$office_filter = '';
			if($office)
			{
				$office_cnt = count($office);
				$office_filter = ' AND A.employ_office_id IN(';
				foreach ($office as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}
			
			$query = <<<EOS
				SELECT 
				$office_name name, 
				$office_id office_id
				FROM $this->tbl_employee_work_experiences A
				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id 
				LEFT JOIN $this->tbl_employee_educations D ON A.employee_id = D.employee_id 
				LEFT JOIN $this->tbl_param_education_degrees E ON D.education_degree_id = E.degree_id 
				LEFT JOIN $this->tbl_employee_trainings F ON A.employee_id = F.employee_id AND F.relevance_flag = ?
				LEFT JOIN $this->tbl_employee_eligibility G ON A.employee_id = G.employee_id 
				LEFT JOIN $this->tbl_param_eligibility_types H ON G.eligibility_type_id = H.eligibility_type_id
				LEFT JOIN $this->tbl_employee_work_experiences I ON A.employee_id = I.employee_id AND I.employ_type_flag = ? AND I.relevance_flag = ?
				WHERE A.employ_type_flag IN(?,?) 
				AND A.employ_start_date BETWEEN ? AND ?
				-- AND A.employ_start_date >= ?
				-- AND IFNULL(A.employ_end_date, IF(? < current_date,?,current_date)) <= ?
				$movt_filter
				$office_filter
				GROUP BY $office_id
				ORDER BY $office_id ASC
EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employees_with_personnel_movt_list($where, $office_id)
	{
		try
		{
			$val   = array($where, $office_id);
			$query = <<<EOS
				SELECT 
				CONCAT(D.last_name, ', ', D.first_name, ' ', LEFT(D.middle_name, 1), '. ') employee_name, 
				D.employee_id
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				WHERE A.employ_personnel_movement_id IN (
					SELECT sys_param_value
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = ?)
				AND A.employ_office_id = ?
				GROUP BY A.employee_id
EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_step_incr_employees()
	{
		try
		{
			$val   = array('MOVT_STEP_INCR');
			$query = <<<EOS
				SELECT 
				CONCAT(D.last_name, ', ', D.first_name, ' ', LEFT(D.middle_name, 1), '. ') employee_name, 
				D.employee_id
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				WHERE A.employ_personnel_movement_id IN (
					SELECT sys_param_value
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = ?)
				GROUP BY A.employee_id
EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_certified_by_info($id)
	{
		try
		{
			$val = array(OTHER_INFO_TYPE_TITLE, $id, YES);
			$query = <<<EOS
				SELECT 
				CONCAT(A.first_name, ' ', LEFT(A.middle_name, (1)), '. ', A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name)), IF(ISNULL(F.others_value), '', CONCAT(', ' , F.others_value))) employee_name,
				B.admin_office_name, 
				B.employ_office_name,
				B.employ_position_name position_name
				FROM $this->tbl_employee_personal_info A
				JOIN $this->tbl_employee_work_experiences B 
				ON A.employee_id = B.employee_id
				LEFT JOIN $this->tbl_employee_other_info F
				ON A.employee_id =  F.employee_id AND F.other_info_type_id = ?
				WHERE A.employee_id = ?
				AND B.active_flag = ?
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employees_longevity_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT 
				CONCAT(C.last_name, ', ', C.first_name, ' ', LEFT(C.middle_name, 1), '. ') employee_name, 
				A.employee_id
				FROM $this->tbl_employee_longevity_pay A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
				JOIN $this->tbl_employee_personal_info C ON B.employee_id = C.employee_id
				GROUP BY A.employee_id
EOS;
	
			$stmt = $this->query($query, NULL, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_step_incr($id, $personnel_movt)
	{
		try
		{

			$office_name     = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';

			$val   = array($personnel_movt, $id);
			$query = <<<EOS
				SELECT 
				CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, 
				A.employ_position_name position_name, 
				A.employ_salary_grade AS sec_grade, 
				A.employ_salary_step AS sec_step, 
				A.employ_monthly_salary AS sec_salary, 
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS start_date,
				DATE_FORMAT(A.employ_end_date, '%M %d, %Y') AS end_date,
				$office_name employ_office_name,
				G.plantilla_code, 
				D.gender_code, 
				A.employ_office_name office_name,
				D.civil_status_id
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id
				WHERE A.employ_start_date  = (SELECT max(employ_start_date)
								            FROM $this->tbl_employee_work_experiences
								            WHERE employ_personnel_movement_id IN 
			                			   		(SELECT sys_param_value
								                FROM $this->DB_CORE.$this->tbl_sys_param
								                WHERE sys_param_type = ? AND employee_id = A.employee_id))
			    AND A.employee_id = ?
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_salary_adjustment($id, $date)
	{
		try
		{

			$office_name     = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';

			$val   = array($date, $date, $id);
			// $query = <<<EOS
				// SELECT 
				// CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, 
				// A.employ_position_name position_name, 
				// B.budget_circular_number, 
				// DATE_FORMAT(B.budget_circular_date, '%M %d, %Y') AS budget_circular_date, 
				// B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %d, %Y') AS execute_order_date, 
				// A.employ_salary_grade AS sec_grade, 
				// A.employ_salary_step AS sec_step, 
				// A.employ_monthly_salary AS sec_salary, 
				// DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS start_date,
				// DATE_FORMAT(A.employ_end_date, '%M %d, %Y') AS end_date,
				// $office_name employ_office_name,
				// G.plantilla_code, 
				// D.gender_code, 
				// D.civil_status_id
				// FROM $this->tbl_employee_work_experiences A 
				// LEFT JOIN $this->tbl_param_salary_schedule B ON A.employ_salary_grade = B.salary_grade 
					// AND A.employ_salary_step = B.salary_step
				// LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				// LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id
				// WHERE B.effectivity_date = ?
			    // AND A.employ_start_date  = ?
			    // AND A.employee_id = ?
// EOS;
			//===== marvin : include suffix : start =====
			$query = <<<EOS
				SELECT 
				CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name, ' ', D.ext_name) employee_name, 
				A.employ_position_name position_name,
				A.employ_position_id position_id,
				B.budget_circular_number, 
				DATE_FORMAT(B.budget_circular_date, '%M %d, %Y') AS budget_circular_date, 
				B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %d, %Y') AS execute_order_date, 
				A.employ_salary_grade AS sec_grade, 
				A.employ_salary_step AS sec_step, 
				A.employ_monthly_salary AS sec_salary, 
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS start_date,
				DATE_FORMAT(A.employ_end_date, '%M %d, %Y') AS end_date,
				$office_name employ_office_name,
				G.plantilla_code, 
				D.gender_code, 
				D.civil_status_id
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_param_salary_schedule B ON A.employ_salary_grade = B.salary_grade 
					AND A.employ_salary_step = B.salary_step
				LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id
				WHERE B.effectivity_date = ?
			    AND A.employ_start_date  = ?
			    AND A.employee_id = ?
EOS;
			//===== marvin : include suffix : end =====
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_salary_adjustment_per_office($id, $date)
	{
		try
		{

			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';
			$office_id    = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id' : 'A.employ_office_id';

			$val   = array($date, $date, $id);
			// $query = <<<EOS
				// SELECT 
				// CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, 
				// A.employ_position_name position_name, 
				// B.budget_circular_number, 
				// DATE_FORMAT(B.budget_circular_date, '%M %d, %Y') AS budget_circular_date, 
				// B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %d, %Y') AS execute_order_date, 
				// A.employ_salary_grade AS sec_grade, 
				// A.employ_salary_step AS sec_step, 
				// A.employ_monthly_salary AS sec_salary, 
				// DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS start_date,
				// DATE_FORMAT(A.employ_end_date, '%M %d, %Y') AS end_date,
				// $office_name employ_office_name,
				// G.plantilla_code,
				// D.gender_code, 
				// D.civil_status_id, 
				// A.employee_id
				// FROM $this->tbl_employee_work_experiences A 
				// LEFT JOIN $this->tbl_param_salary_schedule B ON A.employ_salary_grade = B.salary_grade 
					// AND A.employ_salary_step = B.salary_step
				// LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				// LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id
				// WHERE B.effectivity_date = ?
			    // AND A.employ_start_date  = ?
			    // AND $office_id = ?
// EOS;
			//marvin : include suffix : start
			$query = <<<EOS
				SELECT 
				CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name, ' ', D.ext_name) employee_name, 
				A.employ_position_name position_name, 
				A.employ_position_id position_id,
				B.budget_circular_number, 
				DATE_FORMAT(B.budget_circular_date, '%M %d, %Y') AS budget_circular_date, 
				B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %d, %Y') AS execute_order_date, 
				A.employ_salary_grade AS sec_grade, 
				A.employ_salary_step AS sec_step, 
				A.employ_monthly_salary AS sec_salary, 
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS start_date,
				DATE_FORMAT(A.employ_end_date, '%M %d, %Y') AS end_date,
				$office_name employ_office_name,
				G.plantilla_code,
				D.gender_code, 
				D.civil_status_id, 
				A.employee_id
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_param_salary_schedule B ON A.employ_salary_grade = B.salary_grade 
					AND A.employ_salary_step = B.salary_step
				LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id
				WHERE B.effectivity_date = ?
			    AND A.employ_start_date  = ?
			    AND $office_id = ?
				ORDER BY D.last_name ASC
EOS;
			//marvin : include suffix : end
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_first_record_wo_step_incr($id, $personnel_movt)
	{
		try
		{
			$val   = array($personnel_movt, $id);
			$query = <<<EOS
				SELECT 
				employ_salary_grade AS first_grade, 
				employ_salary_step AS first_step, 
				employ_monthly_salary AS first_salary,
				employee_id,
				DATE_FORMAT(employ_end_date, '%M %d, %Y') AS employ_end_date
				FROM $this->tbl_employee_work_experiences A
			    WHERE A.employ_start_date < (SELECT max(employ_start_date)
							               	 FROM $this->tbl_employee_work_experiences
							                 WHERE employ_personnel_movement_id IN 
			                			   		(SELECT sys_param_value
								                FROM $this->DB_CORE.$this->tbl_sys_param
								                WHERE sys_param_type = ?)
		                			   	     AND employee_id = A.employee_id)
			    AND A.employee_id = ?
				ORDER BY A.employ_start_date DESC
				LIMIT 1
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_first_record_wo_salary_adjustment($id, $date)
	{
		try
		{
			$val   = array($date, $id);
			$query = <<<EOS
				SELECT
				employ_salary_grade AS first_grade, 
				employ_salary_step AS first_step, 
				employ_monthly_salary AS first_salary,
				employee_id,
				DATE_FORMAT(employ_end_date, '%M %d, %Y') AS employ_end_date
				FROM $this->tbl_employee_work_experiences A
			    WHERE A.employ_start_date < ?
			    AND A.employee_id = ?
				ORDER BY A.employ_start_date DESC
				LIMIT 1
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_salary_step_adjustment($id, $personnel_movt)
	{
		try
		{
			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';

			$val   = array($personnel_movt, $id);
			$query = <<<EOS
				SELECT 
				CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, 
				A.employ_position_name,
				A.step_incr_reason_code,
				A.employ_salary_grade AS sec_grade, 
				A.employ_salary_step AS sec_step, 
				A.employ_monthly_salary AS sec_salary, 
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS start_date,
				DATE_FORMAT(A.employ_end_date, '%M %d, %Y') AS end_date,
				$office_name employ_office_name,
				G.plantilla_code, 
				D.gender_code, 
				A.employ_office_name office_name, 
				D.civil_status_id
				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id
				WHERE A.employ_start_date  = (SELECT max(employ_start_date)
								            FROM $this->tbl_employee_work_experiences
								            WHERE employ_personnel_movement_id IN 
			                			   		(SELECT sys_param_value
								                FROM $this->DB_CORE.$this->tbl_sys_param
								                WHERE sys_param_type = ?)
			                			   AND employee_id = A.employee_id)
			    AND A.employee_id = ?
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_salary_adjustment_comp_retirement($id, $personnel_movt)
	{
		try
		{
			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name' : 'A.employ_office_name';

			$val   = array($id, 675);
			$query = <<<EOS
				SELECT 
				CONCAT(E.first_name, ' ', LEFT(E.middle_name, 1), '. ', E.last_name) employee_name, 
				A.employ_salary_grade as sec_grade, 
				A.employ_position_id position_id,
				A.employ_salary_step as sec_step, 
				A.employ_monthly_salary as sec_salary,
				A.employ_position_name,
				A.employ_position_id as position_id,
				A.employee_id,
				E.gender_code, 
				$office_name employ_office_name,
				DATE_FORMAT(E.birth_date, '%M %d, %Y') AS birth_date,
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') AS employ_start_date,
				DATE_FORMAT(DATE_SUB(DATE_ADD(E.birth_date, INTERVAL 65 YEAR), INTERVAL -1 DAY), '%M %d, %Y') AS employ_end_date,
				A.separation_mode_id as separation,
				B.plantilla_code,
				D.amount,
				C.salary_grade,
				A.employ_monthly_salary - D.amount as sg_increase


				FROM $this->tbl_employee_work_experiences A 
				LEFT JOIN $this->tbl_param_plantilla_items B ON A.employ_plantilla_id = B.plantilla_id
				LEFT JOIN $this->tbl_param_positions C ON B.position_id = C.position_id
				LEFT JOIN $this->tbl_param_salary_schedule D ON C.salary_grade = D.salary_grade AND A.employ_salary_step = D.salary_step			    
			    AND (SELECT DATE_FORMAT(employ_start_date, '%Y-01-01') AS effectivity FROM employee_work_experiences WHERE employee_id = A.employee_id AND employ_personnel_movement_id = A.employ_personnel_movement_id) >= D.effectivity_date
				LEFT JOIN $this->tbl_employee_personal_info E ON A.employee_id = E.employee_id
				
				WHERE A.employee_id = ?
				AND A.employ_personnel_movement_id = ?
				ORDER BY D.effectivity_date DESC
				LIMIT 1
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_first_record_wo_salary_step_adjustment($id, $personnel_movt)
	{
		try
		{
			$val   = array($personnel_movt, $id);
			$query = <<<EOS
				SELECT 
				employ_salary_grade AS first_grade, 
				employ_salary_step AS first_step, 
				employ_monthly_salary AS first_salary,
				DATE_FORMAT(employ_end_date, '%M %d, %Y') AS employ_end_date
				FROM $this->tbl_employee_work_experiences A
			    WHERE A.employ_start_date < (SELECT max(employ_start_date)
							               	 FROM $this->tbl_employee_work_experiences
							                 WHERE employ_personnel_movement_id IN 
			                			   		(SELECT sys_param_value
								                FROM $this->DB_CORE.$this->tbl_sys_param
								                WHERE sys_param_type = ?)
		                			   	     AND employee_id = A.employee_id)
			    AND A.employee_id = ?
				ORDER BY A.employ_start_date DESC
				LIMIT 1
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_salary_grade_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT 
				salary_grade  
				FROM $this->tbl_param_salary_schedule 
				WHERE effectivity_date =(SELECT MAX(effectivity_date)  
				FROM $this->tbl_param_salary_schedule
				WHERE effectivity_date < NOW()) group by salary_grade
EOS;
	
			$stmt = $this->query($query, NULL, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_filled_positions_list($office)
	{
		try
		{
			$where = array();
			$office_filter = '';
			if($office)
			{
				$office_cnt = count($office);
				$office_filter = ' WHERE A.office_id IN(';
				foreach ($office as $key => $value) {
					if($office_cnt > $key +1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}

			$query = <<<EOS
			SELECT 
			C.position_name, 
			A.plantilla_code
			FROM $this->tbl_param_plantilla_items A 
			JOIN $this->tbl_employee_work_experiences B ON A.plantilla_id = B.employ_plantilla_id AND B.active_flag = 'Y'
			JOIN $this->tbl_param_positions C ON A.position_id = C.position_id
			$office_filter
			GROUP BY C.position_name, A.plantilla_code

EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_unfilled_positions_list($office)
	{
		try
		{
			$where = array();
			$office_filter = '';
			if($office)
			{
				$office_cnt = count($office);
				$office_filter = ' AND A.office_id IN(';
				foreach ($office as $key => $value) {
					if($office_cnt > $key +1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}

			$query = <<<EOS
				SELECT
				A.plantilla_code, 
				B.position_name 
				FROM $this->tbl_param_plantilla_items A
				LEFT JOIN $this->tbl_param_positions B ON A.position_id = B.position_id
				WHERE A.plantilla_id NOT IN (SELECT A.plantilla_id
											FROM $this->tbl_param_plantilla_items A 
											JOIN $this->tbl_employee_work_experiences B ON A.plantilla_id = B.employ_plantilla_id 
												AND B.active_flag = 'Y'
											JOIN $this->tbl_param_positions C ON A.position_id = C.position_id
											GROUP BY C.position_name, A.plantilla_code)
				AND A.active_flag = 'Y'
				$office_filter
				GROUP BY A.plantilla_code, B.position_name

EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_plantilla_list($offices, $date, $select_data, $group, $order)
	{
		try
		{
			$where = array(YES, $date, $date, TIN_TYPE_ID, YES, YES);

			$office_filter = '';
			if($offices)
			{
				$office_cnt = count($offices);
				$office_filter = ' AND A.office_id IN(';
				foreach ($offices as $key => $value) {
					if($office_cnt > $key + 1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}
			
			$query = <<<EOS
				SELECT 
				$select_data
				FROM $this->tbl_param_plantilla_items A
			    LEFT JOIN $this->tbl_employee_work_experiences B ON A.plantilla_id = B.employ_plantilla_id
			        AND B.active_flag = ?
			        AND DATE_FORMAT(B.employ_start_date, '%Y') <= ?
			        AND IFNULL(DATE_FORMAT(B.employ_end_date, '%Y'),
			        DATE_FORMAT(current_date, '%Y')) >= ?
			    LEFT JOIN $this->tbl_employee_personal_info C 
			    ON B.employee_id = C.employee_id
			    LEFT JOIN $this->tbl_param_employment_status D 
			    ON B.employment_status_id = D.employment_status_id
			    LEFT JOIN $this->tbl_param_positions E 
			    ON B.employ_position_id = E.position_id
			    LEFT JOIN $this->tbl_param_position_class_levels F 
			    ON E.position_class_id = F.position_class_level_id
			    LEFT JOIN $this->tbl_employee_identifications G 
			    ON B.employee_id = G.employee_id AND G.identification_type_id = ?
			    LEFT JOIN $this->tbl_param_identification_types L
			    ON G.identification_type_id = L.identification_type_id
			    LEFT JOIN $this->tbl_employee_eligibility H 
			    ON B.employee_id = H.employee_id
			    LEFT JOIN $this->tbl_param_eligibility_types I 
			    ON H.eligibility_type_id = I.eligibility_type_id
			    LEFT JOIN $this->tbl_param_positions J 
			    ON A.position_id = J.position_id
			    LEFT JOIN $this->tbl_param_salary_schedule K 
			    ON J.salary_grade = K.salary_grade AND J.salary_step = K.salary_step			    
			    AND (SELECT MAX(effectivity_date) FROM $this->tbl_param_salary_schedule WHERE salary_grade = K.salary_grade AND salary_step = K.salary_step) = K.effectivity_date
		        LEFT JOIN $this->tbl_param_plantilla_items X 
		        ON A.plantilla_id = X.plantilla_id
		        LEFT JOIN $this->tbl_param_positions Y 
		        ON X.position_id = Y.position_id
		        LEFT JOIN $this->tbl_param_position_class_levels V 
		        ON V.position_class_level_id = Y.position_class_id
		        LEFT JOIN $this->tbl_param_salary_schedule Z 
		        ON Y.salary_grade = Z.salary_grade AND Y.salary_step = Z.salary_step
		        	AND Z.effectivity_date = (SELECT MAX(effectivity_date) effectivity_date
										        FROM $this->tbl_param_salary_schedule
										        WHERE active_flag = ?)
				LEFT JOIN $this->tbl_param_offices O
					ON A.division_id = O.office_id
				LEFT JOIN $this->tbl_param_office_types P 
					ON O.office_type_id = P.office_type_id
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations Q 
					ON O.org_code = Q.org_code
				LEFT JOIN param_plantilla_levels R
					ON A.plantilla_level_id = R.plantilla_level_id
				WHERE
				    A.active_flag = ?
				$office_filter
				$group
				$order

EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_longevity_pay_list($id)
	{
		try
		{
			$office_name = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name' : 'B.employ_office_name';

			$val   = array($id);
			$query = <<<EOS
				SELECT  
				A.lp_num, 
				DATE_FORMAT(A.effective_date, '%M %d, %Y') AS effective_date, 
				A.effective_date AS start_date, 
				A.employee_id, 
				A.salary_grade, 
				A.salary_step, 
				A.basic_amount, 
				A.pay_amount, 
				A.total_amount, 
				C.last_name,
				C.gender_code,
				C.civil_status_id, 
				CONCAT(C.first_name, ' ', LEFT(C.middle_name, 1), '. ', C.last_name) employee_name, 
				D.plantilla_code, 
				B.employ_position_name position_name, 
				$office_name name
				FROM $this->tbl_employee_longevity_pay A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employ_end_date is NULL
				LEFT JOIN $this->tbl_employee_personal_info C ON A.employee_id = C.employee_id
				LEFT JOIN $this->tbl_param_plantilla_items D ON B.employ_plantilla_id = D.plantilla_id
				WHERE A.employee_id = ?
				GROUP BY A.lp_num
				ORDER BY A.lp_num desc

EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_longevity_pay_increase_list($id, $lp_num)
	{
		try
		{	
			$office_name = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name' : 'B.employ_office_name';

			$val   = array($id, $lp_num);
			$query = <<<EOS
				SELECT  
				A.lp_num, 
				A.tenure_effective_date,
				DATE_FORMAT(A.effective_date, '%M %d, %Y') AS effective_date, 
				A.salary_grade, 
				A.salary_step, 
				A.basic_amount, 
				A.pay_amount, 
				A.total_amount, 
				C.last_name,
				C.gender_code,
				C.civil_status_id, 
				CONCAT(C.first_name, ' ', LEFT(C.middle_name, 1), '. ', C.last_name) employee_name, 
				D.plantilla_code, 
				E.position_name, 
			    B.employ_position_name,
			    $office_name office_name
				FROM $this->tbl_employee_longevity_pay A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employ_end_date is NULL
				LEFT JOIN $this->tbl_employee_personal_info C ON A.employee_id = C.employee_id
				LEFT JOIN $this->tbl_param_plantilla_items D ON B.employ_plantilla_id = D.plantilla_id
				LEFT JOIN $this->tbl_param_positions E ON A.position_id = E.position_id
				WHERE A.employee_id = ?
				AND A.lp_num = ?
EOS;
	
			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_longevity_pay_increase_effective_date($effectivity_date)
	{
		try
		{	

			$val   = array($effectivity_date);
			$query = <<<EOS
				SELECT 
			    executive_order_number, 
			    effectivity_date,
			    DATE_FORMAT(effectivity_date, '%M %d, %Y') AS effective_date
				FROM $this->tbl_param_salary_schedule
				WHERE effectivity_date <= ?
				GROUP BY effectivity_date
				ORDER BY effectivity_date DESC
				LIMIT 1
EOS;
	
			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
	
	public function get_entitlement_longevity_pay_list($office)
	{
		try
		{
			$office_id     = (USE_ADMIN_OFFICE == YES) ? 'C.admin_office_id' : 'C.employ_office_id';

			$where 		   = array(YES);

			$office_filter = '';
			if($office)
			{
				$office_cnt = count($office);
				$office_filter = ' AND '. $office_id .' IN(';
				foreach ($office as $key => $value) {
					if($office_cnt > $key +1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}
			
			$query = <<<EOS
				SELECT 
				CONCAT(UCASE(B.last_name), ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, 
				A.employee_id, 
                max(A.lp_num) max_lp_num,
				group_concat(A.lp_num ORDER BY A.lp_num ASC) lp_num, 
				group_concat(A.basic_amount ORDER BY A.basic_amount ASC) basic_amount,
				group_concat(DATE_FORMAT(A.effective_date, '%m/%d/%Y') ORDER BY A.effective_date ASC) effective_date, 
				group_concat(A.pay_amount ORDER BY A.pay_amount ASC) pay_amount, 
				group_concat(A.total_amount ORDER BY A.total_amount ASC) total_amount
				FROM $this->tbl_employee_longevity_pay A
				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
			    LEFT JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id
			    WHERE C.active_flag = ?
			    $office_filter
				GROUP BY A.employee_id
				ORDER BY employee_name

EOS;
	
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_basic_info($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			$fields = "A.employee_id, A.agency_employee_id, CONCAT(A.last_name, ', ', A.first_name, ' ',A.middle_name, ' ', LEFT(A.ext_name,1)) as fullname, E.name as office_name, D.position_name, F.employment_status_name";

			$query = <<<EOS
				SELECT 
				SQL_CALC_FOUND_ROWS $fields 
				FROM $this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_offices C ON B.employ_office_id = C.office_id 
				LEFT JOIN $this->tbl_param_positions D ON B.employ_position_id = D.position_id
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON C.org_code = E.org_code				
				LEFT JOIN $this->tbl_param_employment_status F ON B.employment_status_id = F.employment_status_id
				WHERE $key = ?
EOS;
	
			$stmt = $this->query($query, $val, false);
						
			return $stmt;

		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}
	
	/*----------PERSONAL DATA SHEET FORM----------*/
	public function get_pds_personal_info($id)
	{
		try
		{
			$val   = array($id);			
			$query = <<<EOS

				SELECT 
				A.*,
				D.citizenship_name,
				B.blood_type_name
				FROM 
				$this->tbl_employee_personal_info A
				LEFT JOIN $this->tbl_param_blood_type B ON B.blood_type_id = A.blood_type_id
				LEFT JOIN $this->tbl_param_citizenships D ON D.citizenship_id = A.citizenship_id
				WHERE A.employee_id = ?

EOS;

			$stmt = $this->query($query, $val, FALSE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}
	}

	public function get_pds_education($id)
	{
		try
		{
			$val   = array($id);			
			$query = <<<EOS

				SELECT *
				FROM 
				$this->tbl_employee_educations A
				LEFT JOIN $this->tbl_param_educational_levels B ON B.educ_level_id = A.educational_level_id
				JOIN $this->tbl_param_schools C ON C.school_id = A.school_id
				JOIN $this->tbl_param_education_degrees D ON D.degree_id = A.education_degree_id
				LEFT JOIN $this->tbl_param_academic_honors E ON E.academic_honor_id = A.academic_honor
				WHERE A.employee_id = ?

EOS;
			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_pds_eligibility($id)
	{
		try
		{
			$val   = array($id);
			$query = <<<EOS

				SELECT 
				A.*, 
				B.*, 
				DATE_FORMAT(A.release_date, '%m/%d/%Y') AS release_date, 
				DATE_FORMAT(A.exam_date, '%m/%d/%Y') AS exam_date
				FROM $this->tbl_employee_eligibility A
				LEFT JOIN $this->tbl_param_eligibility_types B ON A.eligibility_type_id = B.eligibility_type_id
				WHERE A.employee_id = ?

EOS;

			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_pds_work_experience($id)
	{
		try
		{
			$val   = array($id);
			$query = <<<EOS

				SELECT *, 
				DATE_FORMAT(A.employ_start_date, '%m/%d/%Y') AS employ_start_date, 
				DATE_FORMAT(A.employ_end_date, '%m/%d/%Y') AS employ_end_date
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_param_employment_status B ON B.employment_status_id = A.employment_status_id
				JOIN $this->tbl_param_positions C ON C.position_id = A.employ_position_id
				LEFT JOIN $this->tbl_param_offices D ON D.office_id = A.employ_office_id
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON E.org_code = D.org_code
				WHERE A.employee_id = ?
				ORDER BY A.employ_start_date ASC

EOS;

			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_pds_questions($id)
	{
		try
		{
			$val   = array($id);			
			$query = <<<EOS

				SELECT * 
				FROM $this->tbl_param_questions A
				LEFT JOIN $this->tbl_employee_questions B ON A.question_id = B.question_id
				WHERE B.employee_id = ?

EOS;

			
			$stmt = $this->query($query, $val, TRUE);
						
			return $stmt;
		}	
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}

	public function get_doh_address($param)
	{
		try 
		{
			$val   = array($param);
			$query = <<<EOS
				SELECT
				sys_param_value
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
			
		}
		catch (PDOException $e)
		{
		$this->rlog($e);
		}
		
		catch(Exception $e)
		{
			$this->rlog($e);
		}
	}

	public function get_employee_milestone($id)
	{
		try
		{
			$val = array($id);
			$query = <<<EOS
				SELECT 
				lp_num
				FROM $this->tbl_employee_longevity_pay
				WHERE employee_id = ?
				GROUP BY lp_num
				HAVING count(lp_num) > 1
EOS;

			$stmt = $this->query($query, $val, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_appointment_cert_details($id, $movt_appointment)
	{
		try
		{
			$val = array($id, DOH_GOV_APPT, DOH_GOV_NON_APPT);

			$movt_filter = '';
			if($movt_appointment)
			{
				$movt_cnt = count($movt_appointment);
				$movt_filter = ' AND A.employ_personnel_movement_id IN(';
				foreach ($movt_appointment as $key => $value) {
					if($movt_cnt > $key +1)
					{
						$movt_filter .= '?,';
					}
					else
					{
						$movt_filter .= '?';
					}
					$val[] = $value;				
				}
				$movt_filter .= ')';
			}

			$query = <<<EOS
				SELECT A.prev_employee_id,
				CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/' OR B.middle_name IS NULL), ' ', CONCAT(' ', LEFT(B.middle_name, 1), '. ')), B.last_name, IF(B.ext_name='' OR B.ext_name IS NULL, '', CONCAT(' ', B.ext_name))) employee_name,
				B.last_name,
				A.employ_position_name position_name, 
				A.employ_salary_grade, 
			    A.publication_place,
			    A.posted_in,
			    DATE_FORMAT(A.deliberation_date, '%M %d, %Y') deliberation_date,
			    DATE_FORMAT(A.publication_date, '%M %d, %Y') publication_date,
			    DATE_FORMAT(A.publication_date_to, '%M %d, %Y') publication_date_to,
			    DATE_FORMAT(DATE_ADD(A.publication_date_to, INTERVAL 1 DAY), '%M %d, %Y') date_add,
				D.employment_status_name, 
				TRIM('- DOH' FROM IFNULL(A.admin_office_name, A.employ_office_name)) agency_name, 
				A.employ_monthly_salary, 
				H.personnel_movement_name, 
				CONCAT(I.first_name, IF((I.middle_name='NA' OR I.middle_name='N/A' OR I.middle_name='-' OR I.middle_name='/'), ' ', CONCAT(' ', LEFT(I.middle_name, 1), '. ')), I.last_name, IF(I.ext_name='', '', CONCAT(' ', I.ext_name)))ex_employee_name, 
				G.plantilla_code, 
				J.separation_mode_name AS separation_mode_name,
				A.employ_personnel_movement_id,
				B.gender_code,
				DATE_FORMAT(C.signing_date, '%M %d, %Y') signing_date,
				DATE_FORMAT(C.hrmpsb_date, '%M %d, %Y') hrmpsb_date,
				C.plantilla_page,
				B.civil_status_id
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_employee_work_experience_details C ON A.employee_work_experience_id = C.employee_work_experience_id
				LEFT JOIN $this->tbl_param_employment_status D ON A.employment_status_id = D.employment_status_id 
				LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id 
				LEFT JOIN $this->tbl_param_personnel_movements H ON A.employ_personnel_movement_id = H.personnel_movement_id 
				LEFT JOIN $this->tbl_employee_personal_info I ON A.prev_employee_id = I.employee_id 
				LEFT JOIN $this->tbl_param_separation_modes J ON A.prev_separation_mode_id = J.separation_mode_id 
				WHERE A.employee_id = ? 
				AND A.employ_start_date = (SELECT MAX(A.employ_start_date))
				AND A.employ_type_flag IN(?,?)  
				$movt_filter				
				ORDER BY A.employ_start_date DESC
EOS;

			$stmt = $this->query($query, $val, FALSE);

			$val3 = array($stmt['prev_employee_id'], DOH_GOV_APPT, DOH_GOV_NON_APPT);
			$query3 = <<<EOS
				SELECT C.separation_mode_name
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_param_separation_modes C ON A.separation_mode_id = C.separation_mode_id
				WHERE A.employee_id = ?
				AND A.employ_type_flag IN (?, ?) 	
				ORDER BY A.employ_start_date DESC
EOS;
			$stmt3 = $this->query($query3, $val3, FALSE);
			$val2 = array($stmt['prev_employee_id'], '642', '636', '652', '651', '653', '654', '662','640', '666', '645', DOH_GOV_APPT, DOH_GOV_NON_APPT);
			$query2 = <<<EOS
					SELECT B.personnel_movement_name, C.separation_mode_name
					FROM $this->tbl_employee_work_experiences A
					LEFT JOIN $this->tbl_param_personnel_movements B ON A.employ_personnel_movement_id = B.personnel_movement_id 
					LEFT JOIN $this->tbl_param_separation_modes C ON A.separation_mode_id = C.separation_mode_id
					WHERE A.employee_id = ?
					AND A.employ_personnel_movement_id IN (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)	
					AND A.employ_type_flag IN (?, ?) 	
					ORDER BY A.employ_start_date DESC LIMIT 1
EOS;
				$stmt2 = $this->query($query2, $val2, FALSE);
				if ($stmt['separation_mode_name'] != $stmt2['separation_mode_name']) {
					$stmt2['separation_mode_name'] = $stmt['separation_mode_name'];
				}
				if ($stmt['separation_mode_name'] != $stmt3['separation_mode_name']) {
					$stmt['separation_mode_name'] = $stmt3['separation_mode_name'];
				}
				if (EMPTY($stmt['separation_mode_name'])) {
					$stmt['separation_mode_name'] = $stmt2['personnel_movement_name'];
				}else{
					$stmt['separation_mode_name'];
				}

			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
//ncocampo 
	public function get_assumption_to_duty_details($id, $movt_appointment)
	{
		try
		{
			$val = array($id, DOH_GOV_APPT, DOH_GOV_NON_APPT);

			$movt_filter = '';
			if($movt_appointment)
			{
				$movt_cnt = count($movt_appointment);
				$movt_filter = ' AND A.employ_personnel_movement_id IN(';
				foreach ($movt_appointment as $key => $value) {
					if($movt_cnt > $key +1)
					{
						$movt_filter .= '?,';
					}
					else
					{
						$movt_filter .= '?';
					}
					$val[] = $value;				
				}
				$movt_filter .= ')';
			}

			$query = <<<EOS
				SELECT


				CONCAT(B.first_name, ' ', IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '. ')), B.last_name , ' ', B.ext_name) employee_name, 
				B.last_name,
				DATE_FORMAT(A.employ_start_date, '%M %d, %Y') start_date,
				A.employ_position_name position_name, 
				TRIM('- DOH' FROM IFNULL(A.admin_office_name, A.employ_office_name)) agency_name, 
				G.plantilla_code, 
				A.employ_personnel_movement_id,
				B.gender_code,
				B.civil_status_id,
				SUBSTRING_INDEX(I.address_value, '|', 1) AS strt_no,
				SUBSTRING_INDEX(SUBSTRING_INDEX(I.address_value, '|', 2), '|', -1) AS street_name,
				SUBSTRING_INDEX(SUBSTRING_INDEX(I.address_value, '|', 3), '|', -1) AS subdivision_name,
				CONCAT(K.barangay_name, ', ', L.municity_name) AS employee_address,
				M.province_name,
				N.region_code
				FROM $this->tbl_employee_work_experiences A
				LEFT JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id 
				LEFT JOIN $this->tbl_param_employment_status D ON A.employment_status_id = D.employment_status_id 
				LEFT JOIN $this->tbl_param_plantilla_items G ON A.employ_plantilla_id = G.plantilla_id 
				LEFT JOIN $this->tbl_param_personnel_movements H ON A.employ_personnel_movement_id = H.personnel_movement_id 
				LEFT JOIN $this->tbl_employee_addresses I ON  B.employee_id = I.employee_id
				LEFT JOIN $this->tbl_param_address_types J ON I.address_type_id = J.address_type_id
				LEFT JOIN $this->DB_CORE.$this->tbl_barangays K ON I.barangay_code = K.barangay_code
				LEFT JOIN $this->DB_CORE.$this->tbl_municities L ON I.municity_code = L.municity_code
				LEFT JOIN $this->DB_CORE.$this->tbl_provinces M ON I.province_code = M.province_code
				LEFT JOIN $this->DB_CORE.$this->tbl_regions N ON I.region_code = N.region_code
				WHERE A.employee_id = ? 
				AND A.employ_start_date = (SELECT MAX(A.employ_start_date))
				AND A.employ_type_flag IN(?,?) 
				AND J.address_type_id = '1'
				$movt_filter				
				ORDER BY A.employ_start_date DESC
EOS;

			$stmt = $this->query($query, $val, FALSE);

			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}
//01/11/2024


	public function get_prime_hrm_assessment_record($date_from, $date_to)
	{
		try
		{
			$val = array(POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_PERMANENT, POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_PERMANENT,
						 POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_PERMANENT, POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_PERMANENT,
						 POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_PERMANENT, POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_PERMANENT,
						 POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_PERMANENT, POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_PERMANENT,

						 POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_TEMPORARY, POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_TEMPORARY,
						 POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_TEMPORARY, POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_TEMPORARY,
						 POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_TEMPORARY, POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_TEMPORARY,
						 POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_TEMPORARY, POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_TEMPORARY,

						 POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_COTERMINOUS, POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_COTERMINOUS,
						 POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_COTERMINOUS, POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_COTERMINOUS,
						 POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_COTERMINOUS, POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_COTERMINOUS,
						 POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_COTERMINOUS, POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_COTERMINOUS,

						 POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_CASUAL, POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_CASUAL,
						 POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_CASUAL, POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_CASUAL,
						 POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_CASUAL, POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_CASUAL,
						 POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_CASUAL, POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_CASUAL,

						 POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_CONTRACTUAL, POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_CONTRACTUAL,
						 POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_CONTRACTUAL, POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_CONTRACTUAL,
						 POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_CONTRACTUAL, POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_CONTRACTUAL,
						 POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_CONTRACTUAL, POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_CONTRACTUAL,

						 POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_JO, POS_CLASS_LEVEL1, EMPLOYMENT_STATUS_JO,
						 POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_JO, POS_CLASS_LEVEL2, EMPLOYMENT_STATUS_JO,
						 POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_JO, POS_CLASS_LEVEL2_EXECUTIVE, EMPLOYMENT_STATUS_JO,
						 POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_JO, POS_CLASS_LEVEL3, EMPLOYMENT_STATUS_JO,

						 $date_from, $date_to, $date_to, $date_to);

			$query = <<<EOS
				SELECT 
				-- PERMANENT
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_male_level1,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_female_level1,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_male_level2,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_female_level2,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_male_level2_executive,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_female_level2_executive,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_male_level3,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS permanent_female_level3,
				-- TEMPORARY
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_male_level1,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_female_level1,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_male_level2,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_female_level2,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_male_level2_executive,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_female_level2_executive,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_male_level3,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS temporary_female_level3,
				-- CO TERMINOUS
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_male_level1,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_female_level1,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_male_level2,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_female_level2,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_male_level2_executive,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_female_level2_executive,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_male_level3,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS coterminous_female_level3,
				-- CASUAL
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_male_level1,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_female_level1,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_male_level2,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_female_level2,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_male_level2_executive,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_female_level2_executive,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_male_level3,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS casual_female_level3,
				 -- CONTRACTUALS
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_male_level1,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_female_level1,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_male_level2,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_female_level2,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_male_level2_executive,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_female_level2_executive,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_male_level3,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS contractual_female_level3,
				 -- JOB ORDER
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_male_level1,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_female_level1,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_male_level2,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_female_level2,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_male_level2_executive,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_female_level2_executive,
				 SUM(IF(A.gender_code = 'M' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_male_level3,
				 SUM(IF(A.gender_code = 'F' AND D.position_class_level_id = ? AND B.employment_status_id = ?, 1, 0)) AS jo_female_level3
				FROM
				    $this->tbl_employee_personal_info A
				        JOIN
				    $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id 
				    	AND B.employ_start_date = (SELECT max(employ_start_date) FROM employee_work_experiences WHERE employee_id = B.employee_id)
				        LEFT JOIN
				    $this->tbl_param_positions C ON B.employ_position_id = C.position_id
				        LEFT JOIN
				    $this->tbl_param_position_class_levels D ON C.position_class_id = D.position_class_level_id
				WHERE
				    B.employ_start_date >= ?
				AND IFNULL(B.employ_end_date, IF(? < current_date,?,current_date)) <= ?
EOS;

			$stmt = $this->query($query, $val, FALSE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_employee_service_length($offices, $date, $jo_flag)
	{
		try
		{

			$office_name  = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name' : 'B.employ_office_name';
			$office_id    = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_id' : 'B.employ_office_id';

			$where = array($date, $jo_flag, $date);

			$office_filter = '';
			if($offices)
			{
				$office_cnt = count($offices);
				$office_filter = ' AND '.$office_id.' IN(';
				foreach ($offices as $key => $value) {
					if($office_cnt > $key +1)
					{
						$office_filter .= '?,';
					}
					else
					{
						$office_filter .= '?';
					}
					$where[] = $value;				
				}
				$office_filter .= ')';
			}
/*
			$query = <<<EOS
				SELECT 
			    A.agency_employee_id AS personnel_number,
			    CONCAT(A.last_name,
			            ', ',
			            A.first_name,
			            ' ',
			            LEFT(A.middle_name, 1),
			            '. ') employee_name,
			    $office_name office,
			    G.position_level_name,
			    B.employ_position_name AS position_name,
			    $office_id office_id,
			    (SELECT 
			            TIMESTAMPDIFF(MONTH,
			                    MIN(SL.employ_start_date),
			                    ?)
			        FROM
			            $this->tbl_employee_work_experiences SL
			        WHERE
			            SL.employee_id = B.employee_id) service_length
			FROM
			    $this->tbl_employee_personal_info A
			        JOIN
			    $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
			        LEFT JOIN
			    $this->tbl_param_employment_status E ON B.employment_status_id = E.employment_status_id
			        LEFT JOIN
			    $this->tbl_param_positions F ON F.position_id = B.employ_position_id
			        LEFT JOIN
			    $this->tbl_param_position_levels G ON G.position_level_id = F.position_level_id
			WHERE
				B.employ_end_date IS NULL 
			    AND E.jo_flag = ?
			        AND B.employ_start_date = (SELECT 
			            MAX(employ_start_date)
			        FROM
			            $this->tbl_employee_work_experiences
			        WHERE
			            employ_start_date <= ?
			                AND employee_id = B.employee_id)
			        $office_filter
			GROUP BY A.employee_id
EOS;			
*/
			// ====================== jendaigo : start : change name format ============= //
			$query = <<<EOS
				SELECT 
			    A.agency_employee_id AS personnel_number,
			    CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) employee_name,
			    $office_name office,
			    G.position_level_name,
			    B.employ_position_name AS position_name,
			    $office_id office_id,
			    (SELECT 
			            TIMESTAMPDIFF(MONTH,
			                    MIN(SL.employ_start_date),
			                    ?)
			        FROM
			            $this->tbl_employee_work_experiences SL
			        WHERE
			            SL.employee_id = B.employee_id) service_length
			FROM
			    $this->tbl_employee_personal_info A
			        JOIN
			    $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id
			        LEFT JOIN
			    $this->tbl_param_employment_status E ON B.employment_status_id = E.employment_status_id
			        LEFT JOIN
			    $this->tbl_param_positions F ON F.position_id = B.employ_position_id
			        LEFT JOIN
			    $this->tbl_param_position_levels G ON G.position_level_id = F.position_level_id
			WHERE
				B.employ_end_date IS NULL 
			    AND E.jo_flag = ?
			        AND B.employ_start_date = (SELECT 
			            MAX(employ_start_date)
			        FROM
			            $this->tbl_employee_work_experiences
			        WHERE
			            employ_start_date <= ?
			                AND employee_id = B.employee_id)
			        $office_filter
			GROUP BY A.employee_id
EOS;
			// ====================== jendaigo : end : change name format ============= //
			
			$stmt = $this->query($query, $where, TRUE);
		
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
		}	
	}

	public function get_parent_office_id($office_id)
	{
		try
		{
			$val = array();
			$query = <<<EOS
				SELECT
				get_parent_office($office_id, 3) as parent
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}
	
	public function get_parent_office_name($office_id)
	{
		try
		{
			$val = array($office_id);
			$query = <<<EOS
				SELECT
				B.name,
				A.office_id
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code
				WHERE A.office_id = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}

	public function get_other_compensations($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$query = <<<EOS
				SELECT GROUP_CONCAT(B.compensation_name SEPARATOR ', ') as compensations
				FROM employee_compensations A 
				LEFT JOIN param_compensations B ON B.compensation_id =  A.compensation_id 
				WHERE A.employee_id = ?
				GROUP BY A.employee_id
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}

	public function get_position_description($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$query = <<<EOS
				SELECT employee_id, position_designation,proposed_title,position_classification,
  						immediate_position,next_higher_position,directly_supervised,work_tools_used,contacts,
						working_condition,unit_general_function
				FROM employee_position_description
				WHERE employee_id = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}

	public function get_employee_educations($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$query = <<<EOS
				SELECT GROUP_CONCAT(B.degree_name SEPARATOR ', ') as degrees
				FROM employee_educations A
				LEFT JOIN param_education_degrees B ON B.degree_id = A.education_degree_id
				WHERE 
				A.relevance_flag = 'Y' 
				AND A.employee_id = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}

	public function get_employee_experiences($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$query = <<<EOS
				SELECT GROUP_CONCAT(B.degree_name SEPARATOR ', ') as degrees
				FROM employee_educations A
				LEFT JOIN param_education_degress B ON B.degree_id = A.education_degree_id
				WHERE A.employee_id = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}

	public function get_employee_eligibility($employee_id)
	{
		try
		{
			$val = array($employee_id);
			$query = <<<EOS
				SELECT GROUP_CONCAT(CONCAT(B.eligibility_type_name, ' - ' , A.license_no) SEPARATOR ', ') as eligibility
				FROM employee_eligibility A
				LEFT JOIN param_eligibility_types B 
				ON B.eligibility_type_id = A.eligibility_type_id
				WHERE A.employee_id = ?
EOS;
			
			$stmt = $this->query($query, $val, FALSE);
			
			return $stmt;
		}
		catch (PDOException $e)
		{
			$this->rlog_error($e);
		}
		catch (Exception $e)
		{
			$this->rlog_error($e);
		}
	}

	public function get_all_employees($fields, $table)
	{
		try
		{
			$fields = (!empty($fields)) ? $fields : array("*");

			return $this->select_all($fields, $table);
			
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	// public function get_employment_records($select, $tables, $where, $order_by=NULL, $all=TRUE, $group_by=NULL)
	
	//MARVIN
	public function get_employment_records($select, $tables, $where, $group_by=NULL, $order_by=NULL, $all=TRUE)
	{
		try
		{
			$fields = (!empty($select)) ? $select : array("*");
			if($all)
				$stmt = $this->select_all($fields, $tables, $where, $order_by, $group_by);
			else
				$stmt = $this->select_one($fields, $tables, $where, $order_by, $group_by);
		
			return $stmt;

		}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	public function get_reports_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
	{
		try
		{
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group_by, $limit);
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group_by, $limit);
			}
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
		catch (Exception $e)
		{			
			RLog::error($e->getMessage());
			throw new Exception($e->getMessage());
		}
	}
}