<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_model extends Main_Model {

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

	public function get_parent_name_list($id)
	{
		try
		{
			
			$query = <<<EOS
				SELECT name, org_code, org_parent
				FROM $this->DB_CORE.$this->tbl_organizations
				WHERE org_code = ?
EOS;
	
			$stmt = $this->query($query, $id, TRUE);
		
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
			
			$query = <<<EOS
				SELECT B.name, A.office_id, A.org_code 
				FROM $this->tbl_param_offices A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_code;
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

	public function get_parent_office_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT A.org_code, A.org_parent, A.name AS parent_name, B.name AS child_name, B.org_code AS child_code, C.name AS grandchild_name, C.org_code AS grandchild_code 
				FROM $this->DB_CORE.$this->tbl_organizations A
				JOIN $this->DB_CORE.$this->tbl_organizations B ON A.org_code = B.org_parent
				JOIN $this->DB_CORE.$this->tbl_organizations c ON B.org_code = C.org_parent;
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

	public function get_wp_employees_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT CONCAT(D.last_name, ', ', D.first_name, ' ', LEFT(D.middle_name, 1), '. ') employee_name, D.employee_id
				FROM $this->tbl_employee_work_experiences A 
				JOIN $this->tbl_param_salary_schedule B ON A.employ_start_date = B.effectivity_date AND A.employ_salary_grade = B.salary_grade AND A.employ_salary_step = B.salary_step
				JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id
				JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				WHERE A.employ_type_flag='WP'
				AND A.employ_personnel_movement_id IN (12)
				AND C.employ_start_date = (select max(employ_start_date) from $this->tbl_employee_work_experiences E
				WHERE E.employee_id = A.employee_id
				AND E.employ_start_date < B.effectivity_date);
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

	public function get_employee_personal_info_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT A.last_name, A.first_name, A.middle_name, A.birth_place, D.name, E.position_name, F.civil_status_name, G.citizenship_name, H.employment_status_name,
				B.employ_start_date, B.employ_monthly_salary, I.gender, DATE_FORMAT(A.birth_date, '%M %e, %Y') AS birth_date, DATE_FORMAT(B.employ_start_date, '%M %e, %Y') AS employ_start_date,
				J.identification_value, K.address_value, L.barangay_name, M.municity_name,
				N.province_name, K.postal_number
				FROM $this->tbl_employee_personal_info A 
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employ_end_date IS NULL
				JOIN $this->tbl_param_offices C ON B.employ_office_id = C.office_id
				JOIN $this->DB_CORE.$this->tbl_organizations D ON C.org_code = D.org_code
				LEFT JOIN $this->tbl_param_positions E ON B.employ_position_id = E.position_id
				LEFT JOIN $this->tbl_param_civil_status F ON A.civil_status_id = F.civil_status_id
				LEFT JOIN $this->tbl_param_citizenships G ON A.citizenship_id = G.citizenship_id
				LEFT JOIN $this->tbl_param_employment_status H ON B.employment_status_id = H.employment_status_id
				LEFT JOIN $this->DB_CORE.$this->tbl_genders I ON A.gender_code = I.gender_code
				LEFT JOIN $this->tbl_employee_identifications J ON A.employee_id = J.employee_id AND J.identification_type_id = 1
				LEFT JOIN $this->tbl_employee_addresses K ON A.employee_id = K.employee_id AND K.address_type_id = 1
				LEFT JOIN $this->DB_CORE.$this->tbl_barangays L ON K.barangay_code = L.barangay_code
				LEFT JOIN $this->DB_CORE.$this->tbl_municities M ON K.municity_code = M.municity_code
				LEFT JOIN $this->DB_CORE.$this->tbl_provinces N ON K.province_code = N.province_code 
				WHERE A.employee_id = ?  
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

	public function get_employees_longevity_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT CONCAT(C.last_name, ', ', C.first_name, ' ', LEFT(C.middle_name, 1), '. ') employee_name, A.employee_id
				FROM $this->tbl_employee_longevity_pay A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employ_end_date IS NULL
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

	public function get_employee_with_salary_adjustment_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, H.position_name, B.budget_circular_number, DATE_FORMAT(B.budget_circular_date, '%M %d, %Y') AS budget_circular_date, B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %d, %Y') AS execute_order_date, DATE_FORMAT(B.effectivity_date, '%M %d, %Y') AS effectivity_date,
				A.employ_salary_grade AS first_grade, A.employ_salary_step AS first_step, A.employ_monthly_salary AS first_salary, C.employ_salary_grade AS sec_grade, C.employ_salary_step AS sec_step, C.employ_monthly_salary AS sec_salary, F.name, G.plantilla_code
				FROM $this->tbl_employee_work_experiences A 
				JOIN $this->tbl_param_salary_schedule B ON A.employ_start_date = B.effectivity_date AND A.employ_salary_grade = B.salary_grade AND A.employ_salary_step = B.salary_step
				JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id
				JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				JOIN $this->tbl_param_offices E ON C.employ_office_id = E.office_id
				JOIN $this->DB_CORE.$this->tbl_organizations F ON E.org_code = F.org_code
				JOIN $this->tbl_param_plantilla_items G ON C.employ_plantilla_id = G.plantilla_id
				JOIN $this->tbl_param_positions H ON C.employ_position_id = H.position_id
				WHERE A.employ_type_flag='WP'
				AND A.employ_personnel_movement_id IN (12)
				AND C.employ_start_date = (select max(employ_start_date) from $this->tbl_employee_work_experiences E
				WHERE E.employee_id = A.employee_id
				AND E.employ_start_date < B.effectivity_date)
				AND D.employee_id = ?
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

	//ncocampo 05/06/2024
	public function get_employee_with_salary_adjustment_compulsory_retirement_list($where)
	{
		try
		{			
			$query = <<<EOS
				SELECT CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, H.position_name, B.budget_circular_number, DATE_FORMAT(B.budget_circular_date, '%M %d, %Y') AS budget_circular_date, B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %d, %Y') AS execute_order_date, DATE_FORMAT(B.effectivity_date, '%M %d, %Y') AS effectivity_date,
				A.employ_salary_grade AS first_grade, A.employ_salary_step AS first_step, A.employ_monthly_salary AS first_salary, C.employ_salary_grade AS sec_grade, C.employ_salary_step AS sec_step, C.employ_monthly_salary AS sec_salary, F.name, G.plantilla_code
				FROM $this->tbl_employee_work_experiences A 
				JOIN $this->tbl_param_salary_schedule B ON A.employ_start_date = B.effectivity_date AND A.employ_salary_grade = B.salary_grade AND A.employ_salary_step = B.salary_step
				JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id
				JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				JOIN $this->tbl_param_offices E ON C.employ_office_id = E.office_id
				JOIN $this->DB_CORE.$this->tbl_organizations F ON E.org_code = F.org_code
				JOIN $this->tbl_param_plantilla_items G ON C.employ_plantilla_id = G.plantilla_id
				JOIN $this->tbl_param_positions H ON C.employ_position_id = H.position_id
				WHERE A.employ_type_flag='WP'
				AND A.employ_personnel_movement_id IN (12)
				AND C.employ_start_date = (select max(employ_start_date) from $this->tbl_employee_work_experiences E
				WHERE E.employee_id = A.employee_id
				AND E.employ_start_date < B.effectivity_date)
				AND D.employee_id = ?
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
	//ncocampo 05/06/2024
	


	public function get_employee_with_step_increment_list($where)
	{
		try
		{			
			$query = <<<EOS
				SELECT CONCAT(D.first_name, ' ', LEFT(D.middle_name, 1), '. ', D.last_name) employee_name, H.position_name, B.budget_circular_number, DATE_FORMAT(B.budget_circular_date, '%M %e, %Y') AS budget_circular_date, B.executive_order_number, DATE_FORMAT(B.execute_order_date, '%M %e, %Y') AS execute_order_date, DATE_FORMAT(B.effectivity_date, '%M %e, %Y') AS effectivity_date, DATE_FORMAT(C.employ_end_date, '%M %e, %Y') AS end_date,
				C.employ_monthly_salary AS first_salary, A.employ_salary_grade AS sec_grade, A.employ_salary_step AS sec_step, A.employ_monthly_salary AS sec_salary, F.name
				FROM $this->tbl_employee_work_experiences A 
				JOIN $this->tbl_param_salary_schedule B ON A.employ_start_date = B.effectivity_date AND A.employ_salary_grade = B.salary_grade AND A.employ_salary_step = B.salary_step
				JOIN $this->tbl_employee_work_experiences C ON A.employee_id = C.employee_id
				JOIN $this->tbl_employee_personal_info D ON A.employee_id = D.employee_id
				JOIN $this->tbl_param_offices E ON C.employ_office_id = E.office_id
				JOIN $this->DB_CORE.$this->tbl_organizations F ON E.org_code = F.org_code
				JOIN $this->tbl_param_positions H ON C.employ_position_id = H.position_id
				WHERE A.employ_type_flag='WP'
				AND A.employ_personnel_movement_id IN (12)
				AND C.employ_start_date = (select max(employ_start_date) from $this->tbl_employee_work_experiences E
				WHERE E.employee_id = A.employee_id
				AND E.employ_start_date < B.effectivity_date)
				AND D.employee_id = ?
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

	public function get_salary_grade_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT salary_grade  FROM 
				$this->tbl_param_salary_schedule where effectivity_date =(
					SELECT MAX(effectivity_date)  FROM $this->tbl_param_salary_schedule
					 where effectivity_date < NOW()) group by salary_grade
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

	public function get_transferees_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT B.agency_employee_id AS personnel_number, CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, C.position_name, E.name
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id
					JOIN $this->tbl_param_offices D ON A.employ_office_id = D.office_id
					LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON D.org_code = E.org_code
					WHERE employ_personnel_movement_id IN (SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = 'MOVT_TRANSFER')
					AND (E.org_code = ? OR E.org_parent = ?) 
					AND (A.employ_start_date <= ? AND ifnull(A.employ_end_date, current_date) >= ? )  AND  A.employ_type_flag IN(?,?,?) 
					
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

	public function get_promoted_employees_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT B.agency_employee_id AS personnel_number, CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, C.position_name, E.name
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id
					JOIN $this->tbl_param_offices D ON A.employ_office_id = D.office_id
					LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON D.org_code = E.org_code
					WHERE employ_personnel_movement_id IN (SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = 'MOVT_PROMOTION')
					AND (E.org_code = ? OR E.org_parent = ?) 
					AND (A.employ_start_date <= ? AND ifnull(A.employ_end_date, current_date) >= ? )  AND  A.employ_type_flag IN(?,?,?) 
					
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

	public function get_retirees_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT B.agency_employee_id AS personnel_number, CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, C.position_name, E.name
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id
					JOIN $this->tbl_param_offices D ON A.employ_office_id = D.office_id
					LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON D.org_code = E.org_code
					WHERE separation_mode_id IN (SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = 'SEPARATION_RETIRE')
					AND (E.org_code = ? OR E.org_parent = ?) 
					AND (A.employ_start_date <= ? AND ifnull(A.employ_end_date, current_date) >= ? )  AND  A.employ_type_flag IN(?,?,?) 
					
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

	public function get_resigned_employees_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT B.agency_employee_id AS personnel_number, CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, C.position_name, E.name
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id
					JOIN $this->tbl_param_offices D ON A.employ_office_id = D.office_id
					LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON D.org_code = E.org_code
					WHERE separation_mode_id IN (SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = 'SEPARATION_RESIGN')
					AND (E.org_code = ? OR E.org_parent = ?) 
					AND (A.employ_start_date <= ? AND ifnull(A.employ_end_date, current_date) >= ? )  AND  A.employ_type_flag IN(?,?,?) 
					
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

	public function get_dropped_employees_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT B.agency_employee_id AS personnel_number, CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, C.position_name, E.name
					FROM $this->tbl_employee_work_experiences A
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN $this->tbl_param_positions C ON A.employ_position_id = C.position_id
					JOIN $this->tbl_param_offices D ON A.employ_office_id = D.office_id
					LEFT JOIN $this->DB_CORE.$this->tbl_organizations E ON D.org_code = E.org_code
					WHERE separation_mode_id IN (SELECT sys_param_value 
					FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = 'SEPARATION_DROP')
					AND (E.org_code = ? OR E.org_parent = ?) 
					AND (A.employ_start_date <= ? AND ifnull(A.employ_end_date, current_date) >= ? )  AND  A.employ_type_flag IN(?,?,?) 
					
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

	public function get_employees_with_entitlement_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT B.agency_employee_id AS personnel_number, CONCAT(B.last_name, ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, C.compensation_name, C.compensation_code, E.position_name, G.name
					FROM $this->tbl_employee_compensations A 
					JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
					LEFT JOIN $this->tbl_param_compensations C ON A.compensation_id = C.compensation_id
					JOIN $this->tbl_employee_work_experiences D ON B.employee_id = D.employee_id
					JOIN $this->tbl_param_positions E ON D.employ_position_id = E.position_id
					JOIN $this->tbl_param_offices F ON D.employ_office_id = F.office_id
					LEFT JOIN $this->DB_CORE.$this->tbl_organizations G ON F.org_code = G.org_code
					WHERE compensation_code IN (
					SELECT sys_param_value FROM $this->DB_CORE.$this->tbl_sys_param
					WHERE sys_param_type = ?);
					AND (G.org_code = ? OR G.org_parent = ?) 
					AND (D.employ_start_date <= ? AND ifnull(D.employ_end_date, current_date) >= ? )  
					AND  D.employ_type_flag IN(?,?,?)

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

	public function get_filled_positions_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT C.position_name, A.plantilla_code
					FROM $this->tbl_param_plantilla_items A 
					JOIN $this->tbl_employee_work_experiences B ON A.plantilla_id = B.employ_plantilla_id
					JOIN $this->tbl_param_positions C ON A.position_id = C.position_id
					GROUP BY C.position_id

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

	public function get_unfilled_positions_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT C.position_name, A.plantilla_code
					FROM $this->tbl_param_plantilla_items A 
					JOIN $this->tbl_employee_work_experiences B ON A.plantilla_id = B.employ_plantilla_id
					JOIN $this->tbl_param_positions C ON A.position_id != C.position_id
					GROUP BY C.position_id

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

	public function get_gsis_certificate_of_contribution_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT DATE_FORMAT(A.date_processed, '%M %Y') AS date_processed, D.amount, D.employer_amount, A.or_no, A.or_date, 
				CONCAT(F.first_name, ' ', LEFT(F.middle_name, 1), '. ', F.last_name) employee_name,
				F.last_name, F.agency_employee_id, H.name
				FROM $this->tbl_remittances A
				JOIN $this->tbl_remittance_details B ON B.remittance_id = A.remittance_id 
				JOIN $this->tbl_remittance_history C ON C.remittance_id = A.remittance_id 
				LEFT JOIN $this->tbl_payout_details D ON D.payroll_dtl_id = B.payroll_dtl_id 
				LEFT JOIN $this->tbl_payout_header E ON E.payroll_hdr_id = D.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info F ON E.employee_id = F.employee_id 
				LEFT JOIN $this->tbl_param_offices G ON E.office_id = G.office_id 
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code 
				JOIN $this->tbl_param_deductions I ON D.deduction_id = I.deduction_id
				WHERE E.employee_id = ? 
				AND A.date_processed BETWEEN ? AND ?
				AND I.deduction_code IN (SELECT sys_param_value 
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = 'DEDUCTION_ST_GSIS')

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

	public function get_philhealth_certificate_of_contribution_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT DATE_FORMAT(A.date_processed, '%M %Y') AS date_processed, D.amount, D.employer_amount, A.or_no, A.or_date, 
				CONCAT(F.first_name, ' ', LEFT(F.middle_name, 1), '. ', F.last_name) employee_name,
				F.last_name, F.agency_employee_id, H.name
				FROM $this->tbl_remittances A
				JOIN $this->tbl_remittance_details B ON B.remittance_id = A.remittance_id 
				JOIN $this->tbl_remittance_history C ON C.remittance_id = A.remittance_id 
				LEFT JOIN $this->tbl_payout_details D ON D.payroll_dtl_id = B.payroll_dtl_id 
				LEFT JOIN $this->tbl_payout_header E ON E.payroll_hdr_id = D.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info F ON E.employee_id = F.employee_id 
				LEFT JOIN $this->tbl_param_offices G ON E.office_id = G.office_id 
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code 
				JOIN $this->tbl_param_deductions I ON D.deduction_id = I.deduction_id
				WHERE E.employee_id = ? 
				AND A.date_processed BETWEEN ? AND ?
				AND I.deduction_code IN (SELECT sys_param_value 
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = 'DEDUCTION_ST_PHILHEALTH')

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

	public function get_pagibig_certificate_of_contribution_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT DATE_FORMAT(A.date_processed, '%M %Y') AS date_processed, D.amount, D.employer_amount, A.or_no, A.or_date, 
				CONCAT(F.first_name, ' ', LEFT(F.middle_name, 1), '. ', F.last_name) employee_name,
				F.last_name, F.agency_employee_id, H.name
				FROM $this->tbl_remittances A
				JOIN $this->tbl_remittance_details B ON B.remittance_id = A.remittance_id 
				JOIN $this->tbl_remittance_history C ON C.remittance_id = A.remittance_id 
				LEFT JOIN $this->tbl_payout_details D ON D.payroll_dtl_id = B.payroll_dtl_id 
				LEFT JOIN $this->tbl_payout_header E ON E.payroll_hdr_id = D.payroll_hdr_id 
				LEFT JOIN $this->tbl_employee_personal_info F ON E.employee_id = F.employee_id 
				LEFT JOIN $this->tbl_param_offices G ON E.office_id = G.office_id 
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code 
				JOIN $this->tbl_param_deductions I ON D.deduction_id = I.deduction_id
				WHERE E.employee_id = ? 
				AND A.date_processed BETWEEN ? AND ?
				AND I.deduction_code IN (SELECT sys_param_value 
				FROM $this->DB_CORE.$this->tbl_sys_param
				WHERE sys_param_type = 'DEDUCTION_ST_PAGIBIG')

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

	public function get_employee_longevity_pay_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT  A.lp_num, DATE_FORMAT(A.effective_date, '%M %d, %Y') AS effective_date, A.salary_grade, A.salary_step, A.basic_amount, A.pay_amount, A.total_amount, 
				C.last_name, CONCAT(C.first_name, ' ', LEFT(C.middle_name, 1), '. ', C.last_name) employee_name, D.plantilla_code, E.position_name, G.name
				FROM $this->tbl_employee_longevity_pay A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employ_end_date is NULL
				JOIN $this->tbl_employee_personal_info C ON A.employee_id = C.employee_id
				JOIN $this->tbl_param_plantilla_items D ON B.employ_plantilla_id = D.plantilla_id
				JOIN $this->tbl_param_positions E ON A.position_id = E.position_id
				JOIN $this->tbl_param_offices F ON B.employ_office_id = F.office_id
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations G ON F.org_code = G.org_code
				WHERE A.employee_id = ?
				GROUP BY A.lp_num
				ORDER BY A.lp_num desc

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

	public function get_employee_longevity_pay_increase_list($where)
	{
		try
		{
			
			$query = <<<EOS
				SELECT  A.lp_num, DATE_FORMAT(A.effective_date, '%M %d, %Y') AS effective_date, A.salary_grade, A.salary_step, A.basic_amount, A.pay_amount, A.total_amount, 
				C.last_name, CONCAT(C.first_name, ' ', LEFT(C.middle_name, 1), '. ', C.last_name) employee_name, D.plantilla_code, E.position_name, G.name
				FROM $this->tbl_employee_longevity_pay A
				JOIN $this->tbl_employee_work_experiences B ON A.employee_id = B.employee_id AND B.employ_end_date is NULL
				JOIN $this->tbl_employee_personal_info C ON A.employee_id = C.employee_id
				JOIN $this->tbl_param_plantilla_items D ON B.employ_plantilla_id = D.plantilla_id
				JOIN $this->tbl_param_positions E ON A.position_id = E.position_id
				JOIN $this->tbl_param_offices F ON B.employ_office_id = F.office_id
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations G ON F.org_code = G.org_code
				WHERE A.employee_id = ?
				AND A.lp_num = (SELECT max(lp_num) FROM $this->tbl_employee_longevity_pay WHERE employee_id = ?)

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
	
	public function get_entitlement_longevity_pay_list()
	{
		try
		{
			
			$query = <<<EOS
				SELECT CONCAT(UCASE(B.last_name), ', ', B.first_name, ' ', LEFT(B.middle_name, 1), '. ') employee_name, A.employee_id, group_concat(A.lp_num) lp_num, group_concat(A.basic_amount) basic_amount,
				group_concat(DATE_FORMAT(A.effective_date, '%c/%d/%Y')) effective_date, group_concat(A.pay_amount) pay_amount, group_concat(A.total_amount) total_amount
				FROM $this->tbl_employee_longevity_pay A
				JOIN $this->tbl_employee_personal_info B ON A.employee_id = B.employee_id
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
	
	public function get_group_list($columns, $filters, $params, $params_where, $count_flag = FALSE, $multiple=TRUE, $group="")
	{
		try {
	
			
			$fields 	= str_replace(" , ", " ", implode(", ", $columns));
			$filter		= $this->filtering($filters, $params, FALSE);
			$order		= $this->ordering($filters, $params);
			$limit		= (!$count_flag) ?  $this->paging($params) : "";
	
			$where		= $filter["search_str"];
			$val		= $filter["search_params"];
	
			// PARAMS WHERE
			if(!EMPTY($params_where))
			{
				if(!EMPTY($where))
				{
					$where .= " AND " .$params_where;
				}
				else
				{
					$where .= " WHERE " .$params_where;
				}
			}
	
	
			$query = <<<EOS
				SELECT *
				FROM (
					SELECT $fields
					FROM $this->tbl_table_group_hdr A
					JOIN $this->tbl_table_group_dtl B ON A.group_hdr_id = B.group_hdr_id
					JOIN $this->db_core.$this->tbl_users C ON A.created_by = C.user_id
					$group		
	    	    	$order	        		
	        	) T
				$where
				$limit			



EOS;

			return $this->query($query, $val, $multiple);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_download_templates($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("name", "last_downloaded_title", "status");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				$sOrder
				$sLimit
EOS;
			RLog::debug($query);

			return $this->query($query, $filter_params, $multiple);

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

	public function get_work_calendar_info()
	{
		try
		{
			$query = <<<EOS
							SELECT
				WC.work_calendar_id, WC.title, WC.description, WC.holiday_date, HT.color_code
				FROM $this->tbl_param_work_calendar WC
				JOIN $this->tbl_param_holiday_type HT ON WC.holiday_type_id = HT.holiday_type_id
				GROUP BY WC.holiday_date
EOS;
			
			$stmt = $this->query($query);
		
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

	public function get_nature_employment_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("nature_employment_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY nature_employment_id
				$sOrder
				$sLimit
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


	public function get_appointment_type_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("appointment_type_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY appointment_type_id
				$sOrder
				$sLimit
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

	public function get_replacement_reason_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("replacement_reason_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY replacement_reason_id
				$sOrder
				$sLimit
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

	public function get_remittance_type_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("remittance_type_name", "active_flag");
			$sWhere 		= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY remittance_type_id
				$sOrder
				$sLimit
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

	public function plantilla_appointment_status()
	{
		try
		{
			$val 	= array();
			$flag   = "active_flag";

			$query = <<<EOS
				SELECT appointment_status_name, appointment_status_id
				FROM $this->tbl_param_appointment_status
				WHERE (appointment_status_name = 'Contractual' OR  appointment_status_name = 'Regular') AND active_flag = 'Y'
EOS;
			RLog::debug($query);
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

	public function get_salary_schedule($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.effectivity_date');
			/* For Advanced Filters */
		
			$sWhere = $this->filtering($cColumns, $params, TRUE);
		
			$query = <<<EOS
				SELECT * 
				FROM $this->$tbl_param_salary_schedule A
				JOIN $this->tbl_param_salary B ON A.effectivity_date = B.salary_id
			
				WHERE $key = ?
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

	/*------------------------------ PAYROLL GET LIST START------------------------------*/
	public function get_voucher_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("voucher_name","active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY voucher_id
				$sOrder
				$sLimit
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

	public function get_philhealth_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("effectivity_date", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY philhealth_id
				$sOrder
				$sLimit
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

	public function get_pagibig_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("effectivity_date", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY pagibig_id
				$sOrder
				$sLimit
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

	public function get_gsis_list($aColumns, $bColumns, $params, $table, $where, $multiple = TRUE)
	{
		try
		{
			$fields 		= str_replace(" , ", " ", implode(", ", $aColumns));
			$cColumns 		= array("insurance_coverage_type", "personal_share_life", "personal_share_retirement", "gov_share_life", "gov_share_retirement", "active_flag");
			$sWhere			= $this->filtering($cColumns, $params, FALSE);
			$sOrder 		= $this->ordering($bColumns, $params);
			$sLimit 		= $this->paging($params);
			$filter_str 	= $sWhere["search_str"];
			$filter_params 	= $sWhere["search_params"];
			
			$query = <<<EOS
				SELECT $fields 
				FROM $table
				$filter_str
				GROUP BY gsis_id
				$sOrder
				$sLimit
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

	/*------------------------------ PAYROLL GET LIST END------------------------------*/

	public function get_employee_basic_info($id)
	{
		try
		{
			$val 	= array($id);
			$key 	= $this->get_hash_key('A.employee_id');
			$fields = "A.employee_id, A.agency_employee_id, CONCAT(A.last_name, ', ', A.first_name, ' ',A.middle_name, ' ', LEFT(A.ext_name,1)) as fullname, E.name as office_name, D.position_name, F.employment_status_name";

			$query = <<<EOS
				SELECT SQL_CALC_FOUND_ROWS $fields 
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
	public function delete($table, $where)
	{
		try
		{
			return $this->delete_data($table, $where);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	
	public function get_hdr($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_download_template_dtl($fields, $table, $where)
	{
		try
		{
			return $this->select_all($fields, $table, $where);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function insert_download_template($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function update_download_template($table, $fields, $where)
	{
		try
		{
			$this->update_data($table, $fields, $where);
			return TRUE;

		}
		catch (PDOException $e)
		{
			throw $e;
		}
	}
	public function insert_template_dtl($table, $params)
	{
		$where = array();
		$where['hdr_reference_no']	= $params[0]['hdr_reference_no'];

		$this->delete_template_dtl($table, $where);

		foreach($params AS $r) 
		{
			$this->insert_data($table, $r);
		}
	}
	
	public function delete_template_dtl($table, $where)
	{
		try
		{
			return $this->delete_data($table, $where);

		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_download_details($fields, $table, $where, $order_by)
	{
		try 
		{
			$columns = str_replace(" , ", " ", implode(", ", $fields));
			$order_by = !EMPTY($order_by) ? " ORDER BY " . $order_by : "";
			$query = <<<EOS
				SELECT $columns FROM $table
				$where
				$order_by
EOS;
		// RLog::debug("=======================> " . $query);
			return $this->query($query);
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function execute($script, $val= NULL, $multiple = NULL, $execute = TRUE)
	{
		try 
		{
			return $this->query($script, $val, $multiple, $execute);
		}
		catch(PDOException $e)
		{
			
			throw $e;
		}
	}

	public function insert_table_group($table, $fields, $return_id = FALSE)
	{
		try
		{
			return $this->insert_data($table, $fields, $return_id);
	
		}
		catch(PDOException $e)
		{
			
			$msg = $e->getMessage();
			
			if (strpos($msg,1062))
			{	
				throw new PDOException($msg, 1062);
			}
			else if (strpos($msg,1048))
			{
				throw new PDOException($msg, 1048);
			}
			else 
			{
				throw $e;
			}
		}
	}

	public function get_table_group($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
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
		catch(PDOException $e)
		{
			throw $e;
		}
	}

	public function get_table_group_columns($select_fields, $tables, $where)
	{
		try
		{


			$fields = (!empty($select_fields)) ? $select_fields : array("*");
			
			$stmt = $this->select_all($fields, $tables, $where);
			
		
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

	public function get_employment_records($select, $tables, $where, $order_by=NULL, $all=TRUE, $group_by=NULL)
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

	public function get_pds_personal_info($id)
	{
		try
		{
			$val 	= array($id);
			
			$query = <<<EOS

				SELECT A.*,D.citizenship_name,B.blood_type_name
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
			$val 	= array($id);
			
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
			$val 	= array($id);
			
			$query = <<<EOS

				SELECT A.*, B.*, DATE_FORMAT(A.release_date, '%m/%d/%Y') AS release_date, DATE_FORMAT(A.exam_date, '%m/%d/%Y') AS exam_date
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
			$val 	= array($id);
			
			$query = <<<EOS

				SELECT *, DATE_FORMAT(A.employ_start_date, '%m/%d/%Y') AS employ_start_date, DATE_FORMAT(A.employ_end_date, '%m/%d/%Y') AS employ_end_date
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
			$val 	= array($id);
			
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

	public function get_print_dtr_list($date_from,$date_to,$employee_id)
	{
		try
		{
			$val = array($employee_id,$date_from,$date_to);
			
		
			$columns = array("DATE_FORMAT(attendance_date,'%M %d, %Y') as attendance_date","DATE_FORMAT(attendance_date,'%W') as attendance_day", "min(TIME_FORMAT(time_in,'%r')) as time_in", "max(TIME_FORMAT(time_out,'%r')) as time_out", "min(TIME_FORMAT(break_in,'%r')) as break_in", "max(TIME_FORMAT(break_out,'%r')) as break_out");
						
			$fields = str_replace(" , ", " ", implode(", ", $columns));
			
			
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_employee_attendance
				WHERE employee_id  = ?
				AND attendance_date BETWEEN ? AND ?
				group by employee_id,attendance_date
				
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

	public function get_leave_application_detail($request_id)
	{
		try
		{
			
			$val = array($request_id);
			$key = $this->get_hash_key('A.request_id');
			
			$query = <<<EOS
				SELECT 
				A.employee_id,
				K.amount,
				DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_of_filing,H.name,E.first_name,E.last_name,E.middle_name,J.position_name,
				DATE_FORMAT(C.date_from,'%b %d, %Y') as inclusive_date_from,
				DATE_FORMAT(C.date_to,'%b %d, %Y') as inclusive_date_to,
				C.*
				FROM $this->tbl_requests A
				JOIN $this->tbl_requests_sub B ON A.request_id = B.request_id
				JOIN $this->tbl_requests_leaves C ON C.request_sub_id = B.request_sub_id
				JOIN $this->tbl_employee_personal_info E ON E.employee_id = A.employee_id
				LEFT JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.employ_end_date IS NULL
				LEFT JOIN $this->tbl_param_offices G ON G.office_id = F.employ_office_id
				LEFT JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code
				LEFT JOIN $this->tbl_param_positions J ON J.position_id = F.employ_position_id
				LEFT JOIN param_salary_schedule K ON F.employ_salary_grade = K.salary_grade AND F.employ_salary_step = K.salary_step AND K.effectivity_date<= NOW() AND K.active_flag = 'Y'


				WHERE $key = ?
				order by K.effectivity_date desc
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

	public function get_leave_requests($employee_id)
	{
		try
		{
			
			$val = array($employee_id, REQUEST_LEAVE_APPLICATION, REQUEST_CANCELLED);
			$key = $this->get_hash_key('A.request_id');
			
			$query = <<<EOS
				SELECT *
				FROM $this->tbl_requests 

				WHERE employee_id = ?
				AND request_type_id = ?
				AND request_status_id != ?

				order by date_requested desc
EOS;
			$stmt = $this->query($query, $val, true);
						
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
	public function get_employee_leave_card($employee_id)
	{
		try
		{
			
			$val = array($employee_id);
			$query = <<<EOS
				
				SELECT 
				
				A.leave_type_id,
				C.leave_type_name,
				CONCAT(DATE_FORMAT(A.leave_start_date,'%m/%d/%Y'),' - ',DATE_FORMAT(A.leave_end_date,'%m/%d/%Y')) as date_range,
				DATE_FORMAT(A.leave_transaction_date,'%m/%d/%Y') as leave_transaction_date,
				A.leave_earned_used,
				IF(A.leave_transaction_type_id < 4,A.leave_earned_used,0) as earned,
				IF(A.leave_transaction_type_id > 3,A.leave_earned_used,0) as withpay,
				A.leave_wop,
				SUM(IF(B.leave_transaction_type_id < 4,B.leave_earned_used,0))-SUM(IF(B.leave_transaction_type_id > 3,B.leave_earned_used,0)) balance
				FROM $this->tbl_employee_leave_details A
				JOIN $this->tbl_employee_leave_details B ON A.leave_type_id = B.leave_type_id AND A.employee_id = B.employee_id  AND A.leave_detail_id >= B.leave_detail_id 
				JOIN $this->tbl_param_leave_types C ON A.leave_type_id = C.leave_type_id
				
				WHERE
				A.employee_id = ?
				group by A.leave_detail_id
				order by A.leave_detail_id
EOS;
			$stmt = $this->query($query, $val, true);
						
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
	public function get_mra_office($attendance_period_hdr_id)
	{
		try
		{
			
			$val = array($attendance_period_hdr_id);
			$query = <<<EOS
				
				SELECT 
				G.office_id, H.name
				FROM
				$this->tbl_attendance_period_dtl A
				JOIN $this->tbl_employee_personal_info E ON E.employee_id = A.employee_id
				JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.employ_end_date IS NULL
				JOIN $this->tbl_param_offices G ON G.office_id = F.employ_office_id
				JOIN $this->DB_CORE.$this->tbl_organizations H ON G.org_code = H.org_code
				
				WHERE
				A.attendance_period_hdr_id = ?
				group by F.employ_office_id 
EOS;
			$stmt = $this->query($query, $val, true);
						
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
	public function get_mra_employee($params)
	{
		try
		{
			$where = "";
			$val = array($params['mra_attendance_period']);
			if($params['mra_office'])
			{
				$where = " AND ";
				$where .= " F.employ_office_id IN (";
				$cnt = 0;
				foreach ($params['mra_office'] as  $value) {

					$cnt++;
					if($cnt < count($params['mra_office']))
					{
						$where .= " ? ,";
					}
					else
					{
						$where .= " ?";
					}
					$val[] = $value;
					
				}
				$where .= ")";
			}

			$query = <<<EOS
				
				SELECT 
				E.employee_id, CONCAT(E.first_name, ' ', LEFT(E.middle_name, 1),' ', E.last_name, ' ', LEFT(E.ext_name, 3)) employee_name
				FROM
				$this->tbl_attendance_period_dtl A
				JOIN $this->tbl_employee_personal_info E ON E.employee_id = A.employee_id
				JOIN $this->tbl_employee_work_experiences F ON E.employee_id = F.employee_id and F.employ_end_date IS NULL
				
				WHERE
				A.attendance_period_hdr_id = ?

				$where

				group by A.employee_id 
EOS;
			$stmt = $this->query($query, $val, true);
						
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


public function get_payroll_period($payroll_type_id)
	{
		try
		{
			$val = array($payroll_type_id);
			
		
			$columns = array("attendance_period_hdr_id", "DATE_FORMAT(date_from,'%M %d, %Y') as date_from","DATE_FORMAT(date_to,'%M %d, %Y') as date_to");
						
			$fields = str_replace(" , ", " ", implode(", ", $columns));
			
			
			$query = <<<EOS
				SELECT $fields 
				FROM $this->tbl_attendance_period_hdr
				WHERE payroll_type_id  = ?
				
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

// 	public function get_monthly_report_of_attendance($params)
// 	{
// 		try
// 		{
// 			$where = "";
// 			$val = array($params['mra_attendance_period']);
// 			if($params['mra_employee'])
// 			{
// 				$where = " AND ";
// 				$where .= " A.employee_id IN (";
// 				$cnt = 0;
// 				foreach ($params['mra_employee'] as  $value) {

// 					$cnt++;
// 					if($cnt < count($params['mra_employee']))
// 					{
// 						$where .= " ? ,";
// 					}
// 					else
// 					{
// 						$where .= " ?";
// 					}
// 					$val[] = $value;
					
// 				}
// 				$where .= ")";
// 			}
// 			elseif($params['mra_office'])
// 			{
// 				$where = " AND ";
// 				$where .= " D.employ_office_id IN (";
// 				$cnt = 0;
// 				foreach ($params['mra_office'] as  $value) {

// 					$cnt++;
// 					if($cnt < count($params['mra_office']))
// 					{
// 						$where .= " ? ,";
// 					}
// 					else
// 					{
// 						$where .= " ?";
// 					}
// 					$val[] = $value;
					
// 				}
// 				$where .= ")";
// 			}

// 			$query = <<<EOS
				
// 				SELECT 
// 				G.name,
// 				CONCAT(B.last_name, ' ', LEFT(B.ext_name, 3),', ',B.first_name, ' ', LEFT(B.middle_name, 1),' ') employee_name,
// 				DATE_FORMAT(A.attendance_date,'%e') as attendance_date, 
// 				IF(C.leave_type_id = 1,IF(C.leave_earned_used < 1,C.leave_earned_used,1),null) as sick_leave, 
// 				IF(C.leave_type_id = 2,IF(C.leave_earned_used < 1,C.leave_earned_used,1),null) as vacation_leave,
// 				IF(8 - A.working_hours > 0, IF((8 - A.working_hours) % 1 > 0,round(8 - A.working_hours,0) - 1,round(8 - A.working_hours,0)),null) as undertime_hour,
// 				IF(8 - A.working_hours > 0,(8 - A.working_hours) % 1,null) as undertime_min,
// 				H.attendance_status_name
// 				FROM
// 				attendance_period_dtl A
// 				JOIN employee_personal_info B ON B.employee_id = A.employee_id
// 				LEFT JOIN employee_leave_details C ON C.employee_id = B.employee_id AND A.attendance_date BETWEEN C.leave_start_date AND DATE_SUB(C.leave_end_date,INTERVAL C.leave_wop DAY)
// 				JOIN employee_work_experiences D ON D.employee_id = A.employee_id and D.employ_end_date IS NULL
// 				JOIN param_offices F ON F.office_id = D.employ_office_id
// 				JOIN $this->DB_CORE.organizations G ON F.org_code = G.org_code
// 				JOIN param_attendance_status H ON H.attendance_status_id = A.attendance_status_id

// 				WHERE A.attendance_period_hdr_id = ?
// 				$where

// 				group by F.office_id,A.employee_id,A.attendance_date
// 				order by F.office_id,A.employee_id,A.attendance_date
// EOS;
// 			$stmt = $this->query($query, $val, true);
						
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
		catch(PDOException $e)
		{
			throw $e;
		}
	}
}