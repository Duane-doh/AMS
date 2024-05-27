<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('reports_model', 'rm');
		$this->load->model('pds_model', 'pds');
	}	
	public function index($page)
	{
		try
		{
			$data 			= array();
			$resources 		= array();
			$data['page'] 	= $page;
			
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_DATATABLE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_DATATABLE, JS_LABELAUTY);

			$tables = array(

				'main'      => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'A',
				),
				't2'        => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'B',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.employee_id = B.employee_id',
			 	),
				't3'        => array(
				'table'     => $this->rm->tbl_param_offices,
				'alias'     => 'C',
				'type'      => 'LEFT JOIN',
				'condition' => 'C.office_id = A.employ_office_id',
			 	)
			);

			$select_fields             = array("A.*, CONCAT(B.first_name,' ',B.last_name) employee_name, org_code");
			$data['employees']         = $this->rm->get_all_employees($select_fields, $tables);

			$data['wp_employees']      = $this->rm->get_wp_employees_list();

			$data['employee_longevity']= $this->rm->get_employees_longevity_list();

			//OFFICE WITH ID
			$field                     = array("*") ;
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_organizations;
			$where                     = array();/*
			$where['org_parent']	   = 'IS NULL';*/
			$data['parent_offices']    = $this->rm->get_reports_data($field, $table, $where, TRUE);

			//WITHOUT DATE
			$field                     = array("*") ;
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_organizations;
			$where                     = array();
			$data['offices_list'] 	   = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->rm->tbl_param_position_levels; 
			$where                     = array();
			$data['position_level']    = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->rm->tbl_param_position_class_levels;
			$where                     = array();
			$data['classes']           = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_genders;
			$where                     = array();
			$data['genders']           = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->rm->tbl_param_education_degrees;
			$where                     = array();
			$data['professions']       = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->rm->tbl_param_employment_status;
			$where                     = array();
			$data['employment_status'] = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->rm->tbl_param_compensations;
			$where                     = array();
			$data['benefit_types']     = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$data['salary_grades']     = $this->rm->get_salary_grade_list();

			$field                     = array("effectivity_date", "other_fund_flag") ;
			$table                     = $this->rm->tbl_param_salary_schedule; 
			$where                     = array();
			$group_by				   = array("effectivity_date", "other_fund_flag");
			$data['effectivity_date']  = $this->rm->get_table_group($field, $table, $where, TRUE, NULL, $group_by);

			$field                     = array("effective_date") ;
			$table                     = $this->rm->tbl_payout_details; 
			$where                     = array();
			$group_by				   = array("effective_date");
			$data['payout_effective_date']  = $this->rm->get_table_group($field, $table, $where, TRUE, NULL, $group_by);

			$field                     = array("compensation_id", "compensation_name") ;
			$table                     = $this->rm->tbl_param_compensations; 
			$where                     = array();
			$data['compensation_types']    = $this->rm->get_table_group($field, $table, $where, TRUE);

			$field                     = array("deduction_id", "deduction_name") ;
			$table                     = $this->rm->tbl_param_deductions; 
			$where                     = array();
			$data['deduction_types']    = $this->rm->get_table_group($field, $table, $where, TRUE);

			$field  					= array("payroll_type_id", "payroll_type_name");
			$table						= $this->rm->tbl_param_payroll_types;
			$where                      = array();
			$data['payroll_types']	    = $this->rm->get_reports_data($field, $table, $where, TRUE);

		
		$data['message'] = $message;

		/*BREADCRUMBS*/
		$breadcrumbs 			= array();

		switch ($page) {
			case 'human_resources':
				$key					= "Human Resources"; 
				break;
			
			case 'attendance':
				$key					= "Time & Attendance"; 
				break;
			
			case 'payroll':
				$key					= "Payroll"; 
				break;
			default:
				$key  = "Human Resources"; 
				$page = 'human_resources';
			break;
		}
		
		$breadcrumbs[$key]		= PROJECT_MAIN."/reports/index/".$page;
		$key					= "Reports"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/reports/index/".$page;

		set_breadcrumbs($breadcrumbs, TRUE);

		$this->template->load('reports', $data, $resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
	}

	public function generate_reports($format = 'pdf', $report = NULL, $param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			$data       = array();
			$params     = get_params();
			
			$landscape  		= FALSE;
			$papersize_short  	= FALSE;
			$set_footer 		= FALSE;

			// FOR TYPE OF FILE (EXCEL/PDF)
			switch ($report) {
				/******************************** HR REPORTS DEMOGRAPHICS ********************************/
				case REPORT_AGE:

					$param_arr 		= explode('-', $param);
					$age_from  		= $param_arr[0];
					$age_to    		= $param_arr[1];

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	)						
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					$select_fields[] = 'A.birth_date';
					
					$where                        								= array();
					$where['ROUND(DATEDIFF(current_date,birth_date)/365.25,0)'] = array(array($age_from, $age_to), array('BETWEEN'));
					$where['D.org_code']          								= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        								= array($office, array('=', ')'));
					$where['B.employ_start_date'] 								= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   		= array($date, array('>=', ')'));
					$where['B.employ_type_flag']  								= array(array('AP','WP','JO'), array('IN'));

					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;
					$data['param']	 = $param;

				break;

				case REPORT_BENEFIT_ENTITLEMENT:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.position_id = B.employ_position_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_position_levels,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.position_level_id = F.position_level_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_employee_compensations,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'H.employee_id = B.employee_id'
					 	),
						't9'        => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'I.compensation_id = H.compensation_id'
					 	)						
					);

					$select_fields                = array();
					$select_fields[]              = 'A.agency_employee_id AS personnel_number';
					$select_fields[]              = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[]              = 'D.name AS office';
					$select_fields[]              = 'G.position_level_name';
					$select_fields[]              = 'I.compensation_name';
					
					$where                                            = array();
					$where['I.compensation_id']                       = $param;
					$where['D.org_code']                              = array($office, array('=', 'OR', '('));
					$where['D.org_parent']                            = array($office, array('=', ')'));
					$where['B.employ_start_date']                     = array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)'] = array($date, array('>=', ')'));
					$where['B.employ_type_flag']                      = array(array('AP','WP','JO'), array('IN'));
					
					$data['records']                                  = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']                                     = $date;

				break;

				case REPORT_BIRTH_DATE:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	)						
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					$select_fields[] = 'A.birth_date';
					
					$where                        						= array();
					$where['DATE_FORMAT(A.birth_date, \'%m\')'] 		= $param;
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));

					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;
					$data['param']	 = $param;

				break;

				case REPORT_CLASS:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.position_id = B.employ_position_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_position_class_levels,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.position_class_level_id = F.position_class_id'
					 	)							
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					$select_fields[] = 'G.position_class_level_name';
					
					$where 						  						= array();
					$where['F.position_class_id'] 						= $param;
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));

					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_EMPLOYMENT_STATUS:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.position_id = B.employ_position_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_position_levels,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.position_level_id = F.position_level_id'
					 	)						
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name';
					$select_fields[] = 'G.position_level_name';
					
					$where                                            = array();
					$where['E.employment_status_id']                  = $param;
					$where['D.org_code']                              = array($office, array('=', 'OR', '('));
					$where['D.org_parent']                            = array($office, array('=', ')'));
					$where['B.employ_start_date']                     = array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)'] = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  					  = array(array('AP','WP','JO'), array('IN'));
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_GENDER:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	)						
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					$select_fields[] = 'A.birth_date';
					
					$where                        						= array();
					$where['A.gender_code'] 	  						= $param;
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));

					$data['records']   = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']      = $date;
					if($param == 'M')
						$data['param'] = 'Male';
					else
						$data['param'] = 'Female';

				break;

				case REPORT_SERVICE_LENGTH:

					$param_arr 				= explode('-', $param);
					$service_length_from  	= $param_arr[0];
					$service_length_to    	= $param_arr[1];

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.position_id = B.employ_position_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_position_levels,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.position_level_id = F.position_level_id'
					 	)						
					);

					$select_fields                = array();
					$select_fields[]              = 'A.agency_employee_id AS personnel_number';
					$select_fields[]              = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[]              = 'D.name AS office';
					$select_fields[]              = 'G.position_level_name';
					$select_fields[]			  = 'TIMESTAMPDIFF(MONTH,MIN(employ_start_date),ifnull(MAX(employ_end_date),current_date)) AS service_length';
					$select_fields[]			  = '(TIMESTAMPDIFF(MONTH,MIN(employ_start_date),ifnull(MAX(employ_end_date),current_date)) BETWEEN ' . $service_length_from . ' AND ' . $service_length_to . ') AS residency';
					
					$where                                            = array();
					$where['D.org_code']                              = array($office, array('=', 'OR', '('));
					$where['D.org_parent']                            = array($office, array('=', ')'));
					$where['B.employ_start_date']                     = array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)'] = array($date, array('>=', ')'));
					$where['B.employ_type_flag']                      = array(array('AP','WP','JO'), array('IN'));
					$group_by										  = array("B.employee_id");									
					
					$records                                  	      = $this->rm->get_employment_records($select_fields, $tables, $where, NULL, TRUE, $group_by);
					$record_arr										  = array();
					
					foreach($records AS $r)
					{
						if($r['residency'])
							$record_arr[] = $r;
					}

					$data['records']								  = $record_arr;
					$data['from']									  = $service_length_from;
					$data['to']										  = $service_length_to;
					$data['date']                                     = $date;

				break;

				case REPORT_OFFICE:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	)						
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					
					$where                        						= array();
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_POSITION_TITLE:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.position_id = B.employ_position_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_position_levels,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.position_level_id = F.position_level_id'
					 	)				
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					$select_fields[] = 'G.position_level_name';
					
					$where 						  						= array();
					$where['F.position_level_id'] 						= $param;
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));

					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_PROFESSION:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_employee_educations,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = F.employee_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_education_degrees,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.degree_id = F.education_degree_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'H.position_id = B.employ_position_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_param_position_levels,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'I.position_level_id = H.position_level_id'
					 	)							
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'I.position_level_name';
					$select_fields[] = 'G.degree_name';
					
					$where                          					= array();
					$where['F.education_degree_id'] 					= $param;
					$where['D.org_code']            					= array($office, array('=', 'OR', '('));
					$where['D.org_parent']          					= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));

					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;
				break;

 				case REPORT_SALARY_GRADE:

 					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.position_id = B.employ_position_id'
					 	)				
					);

					$select_fields   = array();
					$select_fields[] = 'A.agency_employee_id AS personnel_number';
					$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.employment_status_name AS service_status';
					$select_fields[] = 'F.salary_grade';
					
					$where 						  						= array();
					$where['F.salary_grade']      						= $param;
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['B.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(B.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['B.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));

					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_DROPPED_EMPLOYEES:
				
					$where          	= array();
					$where[]        	=$office;
					$where[]        	=$office;
					$where[] 			= $date;
					$where[]   			= $date;
					$where[]   			= 'AP';
					$where[]   			= 'WP';
					$where[]   			= 'JO';

					$data['records']    = $this->rm->get_dropped_employees_list($where);
					$data['date']       = $date;

				break;
				
				case REPORT_PROMOTED_EMPLOYEES:

					$where          	= array();
					$where[]        	=$office;
					$where[]        	=$office;
					$where[] 			= $date;
					$where[]   			= $date;
					$where[]   			= 'AP';
					$where[]   			= 'WP';
					$where[]   			= 'JO';

					$data['records']    = $this->rm->get_promoted_employees_list($where);
					$data['date']       = $date;
					
				break;
				
				case REPORT_RESIGNED_EMPLOYEES:

					$where          	= array();
					$where[]        	=$office;
					$where[]        	=$office;
					$where[] 			= $date;
					$where[]   			= $date;
					$where[]   			= 'AP';
					$where[]   			= 'WP';
					$where[]   			= 'JO';

					$data['records']    = $this->rm->get_retirees_list($where);
					$data['date']       = $date;

				break;

				case REPORT_RETIREES:

					$where          	= array();
					$where[]        	=$office;
					$where[]        	=$office;
					$where[] 			= $date;
					$where[]   			= $date;
					$where[]   			= 'AP';
					$where[]   			= 'WP';
					$where[]   			= 'JO';

					$data['records']    = $this->rm->get_retirees_list($where);
					$data['date']       = $date;

				break;

				/******************************** HR REPORTS ORGANIZATIONAL STRUCTURE ********************************/
				case REPORT_SERVICE_RECORD:

					$where                         = array();
					$where['A.employee_id']        =  $param;
					$order_by['A.employ_end_date'] = 'ASC';
					
					$tables			= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A',
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employment_status_id = D.employment_status_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_office_id = E.office_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->DB_CORE.".".$this->rm->tbl_organizations,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.org_code = E.org_code'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_government_branches,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.government_branch_id = G.branch_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_param_personnel_movements,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_personnel_movement_id = H.personnel_movement_id'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'B.last_name';
					$select_fields[] = 'B.first_name';
					$select_fields[] = 'B.middle_name';
					$select_fields[] = 'B.birth_date';
					$select_fields[] = 'B.birth_place';
					$select_fields[] = 'A.employ_plantilla_id';
					$select_fields[] = 'A.employ_start_date';
					$select_fields[] = 'A.employ_end_date';
					$select_fields[] = '(A.employ_monthly_salary * 12) AS annual_salary';
					$select_fields[] = 'IFNULL(C.position_name,A.employ_position_name) position_name';
					$select_fields[] = 'D.employment_status_name';
					$select_fields[] = 'F.name';
					$select_fields[] = 'G.branch_name';
					$select_fields[] = 'H.personnel_movement_name';
					
					$data['record']  = $this->rm->get_employment_records($select_fields, $tables, $where, $order_by);

				break;

				case REPORT_APPOINTMENT_CERTIFICATE:

					$where                    = array();
					$where['A.employee_id']   = $param;
					$order_by 				  = array();
					$limit 					  = 'LIMIT 1';

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A',
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employment_status_id = D.employment_status_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_office_id = E.office_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->DB_CORE.".".$this->rm->tbl_organizations,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.org_code = E.org_code'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_plantilla_items,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_plantilla_id = G.plantilla_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_param_personnel_movements,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_personnel_movement_id = H.personnel_movement_id'
					 	),
						't9'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.prev_employee_id = I.employee_id'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.first_name, \' \', B.middle_name, \'. \', B.last_name , \' \', B.ext_name) employee_name';
					$select_fields[] = 'C.position_name';
					$select_fields[] = 'CONCAT(\'SG-\', A.employ_salary_grade) salary_grade';
					$select_fields[] = 'D.employment_status_name';
					$select_fields[] = 'F.name agency_name';
					$select_fields[] = 'A.employ_monthly_salary';
					$select_fields[] = 'H.personnel_movement_name';
					$select_fields[] = 'CONCAT(I.first_name, \' \', I.middle_name, \'. \', I.last_name , \' \', I.ext_name) ex_employee_name';
					$select_fields[] = 'G.plantilla_code';
					
					$data['record']  = $this->rm->get_employment_records($select_fields, $tables, $where, $order_by, FALSE);
					$data['record']['employ_monthly_salary_by_word'] = number_to_words($data['record']['employ_monthly_salary']);
				
				break;

				//NCOCAMPO
				case REPORT_ASSUMPTION_TO_DUTY:

					$where                    = array();
					$where['A.employee_id']   = $param;
					$order_by 				  = array();
					$limit 					  = 'LIMIT 1';

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A',
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employment_status_id = D.employment_status_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_office_id = E.office_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->DB_CORE.".".$this->rm->tbl_organizations,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.org_code = E.org_code'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_plantilla_items,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_plantilla_id = G.plantilla_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_param_personnel_movements,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_personnel_movement_id = H.personnel_movement_id'
					 	),
						't9'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.prev_employee_id = I.employee_id'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.first_name, \' \', B.middle_name, \'. \', B.last_name , \' \', B.ext_name) employee_name';
					$select_fields[] = 'C.position_name';
					$select_fields[] = 'CONCAT(\'SG-\', A.employ_salary_grade) salary_grade';
					$select_fields[] = 'D.employment_status_name';
					$select_fields[] = 'F.name agency_name';
					$select_fields[] = 'A.employ_monthly_salary';
					$select_fields[] = 'H.personnel_movement_name';
					$select_fields[] = 'CONCAT(I.first_name, \' \', I.middle_name, \'. \', I.last_name , \' \', I.ext_name) ex_employee_name';
					$select_fields[] = 'G.plantilla_code';
					
					$data['record']  = $this->rm->get_employment_records($select_fields, $tables, $where, $order_by, FALSE);
				
				break;
				//01/11/2024

				case REPORT_MONTHLY_ACCESSION:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_office_id = C.office_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.org_code = D.org_code'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = E.position_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employment_status_id = F.employment_status_id'
					 	)						
					);

					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.last_name, \', \', B.first_name, \' \', LEFT(B.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'D.name AS office';
					$select_fields[] = 'E.position_name';
					$select_fields[] = 'F.employment_status_name';
					$select_fields[] = 'A.employ_start_date';
					$select_fields[] = 'A.employ_salary_grade';
					
					$where                        						= array();
					$where['D.org_code']          						= array($office, array('=', 'OR', '('));
					$where['D.org_parent']        						= array($office, array('=', ')'));
					$where['A.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(A.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['A.employ_type_flag']  						= 'AP';
					$where['A.employ_end_date']    						= 'IS NULL';
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_MONTHLY_SEPARATION:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A',
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'A.employment_status_id = D.employment_status_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'E',
						'type'      => 'JOIN',
						'condition' => 'A.employ_office_id = E.office_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'F',
						'type'      => 'JOIN',
						'condition' => 'E.org_code = F.org_code'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_param_separation_modes,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.separation_mode_id = G.separation_mode_id'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.first_name, \' \', B.middle_name, \'. \', B.last_name) employee_name';
					$select_fields[] = 'C.position_name';
					$select_fields[] = 'F.name office_name';
					$select_fields[] = 'A.employ_salary_grade salary_grade';
					$select_fields[] = 'D.employment_status_name';
					$select_fields[] = 'A.employ_end_date';
					$select_fields[] = 'G.separation_mode_name';

					$where                         						= array();
					$where['F.org_code']          						= array($office, array('=', 'OR', '('));
					$where['F.org_parent']        						= array($office, array('=', ')'));
					$where['A.employ_end_date']    						= 'IS NOT NULL';
					$where['A.separation_mode_id'] 						= 'IS NOT NULL';

					$data['records']  = $this->rm->get_employment_records($select_fields, $tables, $where);

				break;

				case REPORT_FILLED_UNFILLED_POSITION:

					$data['filled_positions']      = $this->rm->get_filled_positions_list();
					$data['unfilled_positions']    = $this->rm->get_unfilled_positions_list();
					
				break;
 
				case REPORT_PSIPOP_PLANTILLA:

					$where                      = array();
					$where['A.employ_end_date'] =  'IS NULL';

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A',
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'A.employment_status_id = D.employment_status_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_plantilla_items,
						'alias'     => 'E',
						'type'      => 'JOIN',
						'condition' => 'A.employ_plantilla_id = E.plantilla_id'
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_position_levels,
						'alias'     => 'F',
						'type'      => 'JOIN',
						'condition' => 'C.position_level_id = F.position_level_id'
					 	),
						't7'        => array(
						'table'     => $this->rm->tbl_employee_identifications,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employee_id = G.employee_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_employee_eligibility,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employee_id = H.employee_id'
					 	),
						't9'        => array(
						'table'     => $this->rm->tbl_param_eligibility_types,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'H.eligibility_type_id = I.eligibility_type_id'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'E.plantilla_code';
					$select_fields[] = 'CONCAT(C.position_name, \' - \', C.salary_grade) position';
					$select_fields[] = 'C.salary_step';
					$select_fields[] = 'F.position_level_code';
					$select_fields[] = 'CONCAT(B.first_name, \' \', B.middle_name, \' \', B.last_name) employee_name';
					$select_fields[] = 'B.gender_code';
					$select_fields[] = 'B.birth_date';
					$select_fields[] = 'G.identification_value';
					$select_fields[] = 'A.employ_start_date date_of_appointment';
					$select_fields[] = 'D.employment_status_name';
					$select_fields[] = 'I.eligibility_type_code';

					$data['record']  = $this->rm->get_employment_records($select_fields, $tables, $where, NULL, TRUE, array('A.employee_id'));
				
				break;

				case REPORT_PERSONNEL_MOVEMENT:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_plantilla_items,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'A.employ_plantilla_id = D.plantilla_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employment_status_id = E.employment_status_id'
					 	),
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_personnel_movements,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_personnel_movement_id = F.personnel_movement_id'
					 	),			
					 	't7'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_office_id = G.office_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.org_code = H.org_code'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.last_name, \', \', B.first_name, \' \', LEFT(B.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'C.position_name';
					$select_fields[] = 'D.plantilla_code';
					$select_fields[] = 'A.employ_monthly_salary';
					$select_fields[] = 'CONCAT(\'SG-\', A.employ_salary_grade) salary_grade';
					$select_fields[] = 'E.employment_status_name';
					$select_fields[] = 'F.personnel_movement_name';
					$select_fields[] = 'A.employ_office_id';
					$select_fields[] = 'H.name';

					$where                        						= array();
					$where['H.org_code']         						= array($office, array('=', 'OR', '('));
					$where['H.org_parent']        						= array($office, array('=', ')'));
					$where['A.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(A.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['A.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_PERSONAL_DATA_SHEET:

					$id                                  = $param;

					$data['personal_info']				 = $this->rm->get_pds_personal_info($id);

					$field                               = array("*");
					$table                               = $this->pds->db_core.'.'.$this->pds->tbl_param_genders;
					$where                               = array();
					$data['per_params']['gender']        = $this->pds->get_general_data($field, $table, $where, TRUE);

					$field                               = array("*") ;
					$table                               = $this->pds->tbl_param_civil_status;
					$where                               = array();
					$data['per_params']['civil_status']  = $this->pds->get_general_data($field, $table, $where, TRUE);

					//GET MUNICIPALITY PROVINCE AND REGION 
					$tables = array(
						'main'	=> array(
							'table'		=> $this->pds->tbl_employee_addresses,
							'alias'		=> 'A',
						),
						't1'	=> array(
							'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_barangays,
							'alias'		=> 'B',
							'type'		=> 'left join',
							'condition'	=> 'A.barangay_code = B.barangay_code',
						),
						't2'	=> array(
							'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_municities,
							'alias'		=> 'C',
							'type'		=> 'left join',
							'condition'	=> 'A.municity_code = C.municity_code',
						),
						't3'	=> array(
							'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_provinces,
							'alias'		=> 'D',
							'type'		=> 'left join',
							'condition'	=> 'A.province_code = D.province_code',
						),
						't4'	=> array(
							'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_regions,
							'alias'		=> 'E',
							'type'		=> 'left join',
							'condition'	=> 'A.region_code = E.region_code',
						)
					);
					
					$field                               = array("A.address_type_id, A.postal_number, concat_ws(', ', A.address_value, B.barangay_name, C.municity_name, D.province_name, E.region_name)  as address ") ;					
					$where                               = array();
					$where['A.employee_id']              = $id;
					$data['address_info']                = $this->pds->get_general_data($field, $tables, $where, TRUE);
						
					//GET IDENTIFICATIONS
					$table = array(
						'main' => array(
							'table' => $this->pds->tbl_employee_identifications,
							'alias' => 'A'
						),
						't2' => array(
							'table' => $this->pds->tbl_param_identification_types,
							'alias' => 'B',
							'type' => 'JOIN',
							'condition' => 'B.identification_type_id = A.identification_type_id'
						)
					);
					$where                               = array();
					$where['A.employee_id']              = $id;
					$where['B.builtin_flag']	         = 'Y';
					$order_by 							 = array('B.identification_type_id' => 'ASC');
					$identification_info                 = $this->pds->get_general_data(array("*"), $table, $where, TRUE, $order_by);

					$data['identification_info']         = $identification_info;			

					//GET CONTACTS
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_contacts;
					$where                               = array();
					$where['employee_id']              	 = $id;
					$contact_info                        = $this->pds->get_general_data($field, $table, $where, TRUE);
					$data['contact_info']                = $contact_info;
						
					/*FAMILY BACKGROUND DATA*/
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_relations;
					$where                               = array();
					$where['employee_id']              	 = $id;
					$where['relation_type_id']           = FAMILY_SPOUSE;
					$data['spouse']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_relations;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$where['relation_type_id']           = FAMILY_FATHER;
					$data['father']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_relations;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$where['relation_type_id']           = FAMILY_MOTHER;
					$data['mother']                      = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					
					$field                               = array("*","CONCAT(relation_first_name, ' ',relation_last_name) as name", "DATE_FORMAT(relation_birth_date, '%m/%d/%Y') AS relation_birth_date") ;
					$table                               = $this->pds->tbl_employee_relations;
					$where                               = array();
					$where['employee_id']            	 = $id;
					$where['relation_type_id']           = FAMILY_CHILD;
					$data['child']                       = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$data['educ_details']                = $this->rm->get_pds_education($id);
					
					$where                               = array();
					$where['active_flag']                = 'Y';
					$data['educ_list']                   = $this->pds->get_general_data(array('*'), $this->pds->tbl_param_educational_levels);
					
					$data['govt_exam']                   = $this->rm->get_pds_eligibility($id);
					$data['work_exp']                    = $this->rm->get_pds_work_experience($id);
					
					$field                               = array("*", "DATE_FORMAT(volunteer_start_date, '%m/%d/%Y') AS volunteer_start_date", "DATE_FORMAT(volunteer_end_date, '%m/%d/%Y') AS volunteer_end_date") ;
					$table                               = $this->pds->tbl_employee_voluntary_works;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$data['vol_details']                 = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                               = array("*", "DATE_FORMAT(training_start_date, '%m/%d/%Y') AS training_start_date", "DATE_FORMAT(training_end_date, '%m/%d/%Y') AS training_end_date") ;
					$table                               = $this->pds->tbl_employee_trainings;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$data['train_details']               = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					/*OTHER INFORMATION DATA*/
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_other_info;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$where['other_info_type_id']         = OTHER_SKILLS;
					$data['other_params']['skills_list'] = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_other_info;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$where['other_info_type_id']         = OTHER_RECOGNITION;
					$data['other_params']['recog_list']  = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_other_info;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$where['other_info_type_id']         = OTHER_ASSOCIATION;
					$data['other_params']['member_list'] = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					
					$data['questions']                   = $this->rm->get_pds_questions($id);
					/*START QUESTIONS*/
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_param_questions;
					$where                               = array();
					$where['parent_question_id']         = "IS NULL";
					$data['parent_questions']            = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_param_questions;
					$where                               = array();
					$where['parent_question_flag']       = "N";
					$data['child_questions']             = $this->pds->get_general_data($field, $table, $where, TRUE);
					/*GET EMPLOYEE PREVIOUS ANSWERS*/
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_questions;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$data['answers']                     = $this->pds->get_general_data($field, $table, $where, TRUE);
					/*END QUESTIONS*/
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_references;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$data['refn_details']                = $this->pds->get_general_data($field, $table, $where, TRUE);
					
					$field                               = array("*") ;
					$table                               = $this->pds->tbl_employee_declaration;
					$where                               = array();
					$where['employee_id']             	 = $id;
					$data['declaration']                 = $this->pds->get_general_data($field, $table, $where, FALSE);

				break;

				case REPORT_RAI_PART1:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_plantilla_items,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'A.employ_plantilla_id = D.plantilla_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employment_status_id = E.employment_status_id'
					 	),
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_personnel_movements,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_personnel_movement_id = F.personnel_movement_id'
					 	),			
					 	't7'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_office_id = G.office_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.org_code = H.org_code'
					 	)		
					);

					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.last_name, \', \', B.first_name, \' \', LEFT(B.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'C.position_name';
					$select_fields[] = 'D.plantilla_code';
					$select_fields[] = 'A.employ_monthly_salary';
					$select_fields[] = 'CONCAT(\'SG-\', A.employ_salary_grade) salary_grade';
					$select_fields[] = 'E.employment_status_name';
					$select_fields[] = 'F.personnel_movement_name';
					$select_fields[] = 'A.employ_office_id';
					$select_fields[] = 'H.name';

					$where                        						= array();
					$where['H.org_code']          						= array($office, array('=', 'OR', '('));
					$where['H.org_parent']       						= array($office, array('=', ')'));
					$where['A.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(A.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['A.employ_type_flag']  						= array(array('AP','WP','JO'), array('IN'));
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_RAI_PART2:

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_employee_educations,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = D.employee_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_education_degrees,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.education_degree_id = E.degree_id'
					 	),
					 	't6'        => array(
						'table'     => $this->rm->tbl_employee_trainings,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = F.employee_id'
					 	),
					 	't7'        => array(
						'table'     => $this->rm->tbl_employee_eligibility,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = G.employee_id'
					 	),
						't8'        => array(
						'table'     => $this->rm->tbl_param_eligibility_types,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.eligibility_type_id = H.eligibility_type_id'
					 	),				
					 	't9'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'I',
						'type'      => 'JOIN',
						'condition' => 'A.employ_office_id = I.office_id'
					 	),
						't10'        => array(
						'table'     => $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
						'alias'     => 'J',
						'type'      => 'LEFT JOIN',
						'condition' => 'I.org_code = J.org_code'
					 	)						
					);
					
					$select_fields   = array();
					$select_fields[] = 'CONCAT(B.last_name, \', \', B.first_name, \' \', LEFT(B.middle_name, 1), \'. \') employee_name';
					$select_fields[] = 'C.position_name';
					$select_fields[] = 'E.degree_name';
					$select_fields[] = 'CONCAT(A.employ_start_date, \'- \', A.employ_end_date) work_experience';
					$select_fields[] = 'F.training_name';
					$select_fields[] = 'H.eligibility_type_code';
					$select_fields[] = 'G.exam_place';
					$select_fields[] = 'G.exam_date';
					$select_fields[] = 'G.rating';
					$select_fields[] = 'B.birth_date';
					$select_fields[] = 'B.birth_place';
					$select_fields[] = 'A.employ_office_id';
					$select_fields[] = 'J.name';

					$where                       						= array();
					$where['J.org_code']          						= array($office, array('=', 'OR', '('));
					$where['J.org_parent']        						= array($office, array('=', ')'));
					$where['A.employ_start_date'] 						= array($date, array('<=', 'AND', '('));
					$where['ifnull(A.employ_end_date, current_date)']   = array($date, array('>=', ')'));
					$where['A.employ_type_flag'] 						= array(array('AP','WP','JO'), array('IN'));
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);
					$data['date']    = $date;

				break;

				case REPORT_TRANSFEREES:

					$where          	= array();
					$where[]        	=$office;
					$where[]        	=$office;
					$where[] 			= $date;
					$where[]   			= $date;
					$where[]   			= 'AP';
					$where[]   			= 'WP';
					$where[]   			= 'JO';

					$data['records']    = $this->rm->get_transferees_list($where);
					$data['date']       = $date;

				break;

				case REPORT_PRIME_HRM_ASSESSMENT:

					$where                     		= array();
					$where['B.employ_end_date']    	= 'IS NULL';

					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = B.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_param_positions,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'B.employ_position_id = C.position_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_param_position_class_levels,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'C.position_class_id = D.position_class_level_id'
					 	),
						't5'        => array(
						'table'     => $this->rm->tbl_param_employment_status,
						'alias'     => 'E',
						'type'      => 'JOIN',
						'condition' => 'B.employment_status_id = E.employment_status_id'
					 	)
					);

					$select_fields   = array();
					$select_fields[] = 'A.gender_code';
					$select_fields[] = 'D.position_class_level_name';
					$select_fields[] = 'D.position_class_level_id';
					$select_fields[] = 'E.employment_status_name';
					$select_fields[] = 'E.employment_status_id';
					
					$data['records'] = $this->rm->get_employment_records($select_fields, $tables, $where);

				break;

				/******************************** HR REPORTS WELFARE AND BENEFITS ********************************/
				case REPORT_ENTITLEMENT_LONGEVITY_PAY:

					$landscape 			   = TRUE;

					$data['records'] 	   = $this->rm->get_entitlement_longevity_pay_list();

				break;

				case REPORT_NOTICE_SALARY_ADJUSTMENT:

					$where          		= array();
					$where[]        		= $param;

					$data['records']   	 	= $this->rm->get_employee_with_salary_adjustment_list($where);

				break;

				//ncocampo 05/06/2024
				case REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT:
					$where          		= array();
					$where[]        		= $param;

					$data['records']   	 	= $this->rm->get_employee_with_salary_adjustment_compulsory_retirement_list($where);
				//ncocampo 05/06/2024

				case REPORT_NOTICE_STEP_INCREMENT:

					$where          		= array();
					$where[]        		= $param;

					$data['records']   	 	= $this->rm->get_employee_with_step_increment_list($where);
					
				break;

				case REPORT_NOTICE_LONGEVITY_PAY:

					$where          		= array();
					$where[]        		= $param;

					$data['records']   	 	= $this->rm->get_employee_longevity_pay_list($where);
					
				break;

				case REPORT_NOTICE_LONGEVITY_PAY_INCREASE:

					$where          		= array();
					$where[]        		= $param;
					$where[]        		= $param;

					$data['records']   	 	= $this->rm->get_employee_longevity_pay_increase_list($where);
					
				break;

				case REPORT_GSIS_CERTIFICATE_CONTRIBUTION:

					$where 				= array();
					$where[] 	  		= $param;
					$where[] 	  		= $office;
					$where[]   			= $date;

					$data['records']    = $this->rm->get_gsis_certificate_of_contribution_list($where);

				break;

				case REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION:
					
					$where 				= array();
					$where[] 	  		= $param;
					$where[] 	  		= $office;
					$where[]   			= $date;

					$data['records']    = $this->rm->get_philhealth_certificate_of_contribution_list($where);

				break;

				case REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION:
					
					$where 				= array();
					$where[] 	  		= $param;
					$where[] 	  		= $office;
					$where[]   			= $date;

					$data['records']    = $this->rm->get_pagibig_certificate_of_contribution_list($where);

				break;

				/******************************** HR REPORTS MEMBERSHIP ********************************/
				case REPORT_PAGIBIG_MEMBERSHIP_FORM:
					
					$papersize_short  	= TRUE;

				break;

				case REPORT_PHILHEALTH_MEMBERSHIP_FORM:
					

				break;

				case REPORT_GSIS_MEMBERSHIP_FORM:

					$papersize_short  		= TRUE;

					$where          		= array();
					$where[]        		= $param;

					$data['records']   	 	= $this->rm->get_employee_personal_info_list($where);

				break;

/**** START OF PAYROLL******************************************************************************************************/
				case REPORT_MONTHLY_SALARY_SCHEDULE:

					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_param_salary_schedule
						)
					 	
					);

					$select_fields                = array();
					$select_fields[]              = 'effectivity_date';
					$select_fields[]              = 'salary_grade';
					$select_fields[]              = 'salary_step';
					$select_fields[]              = 'amount';
				
					
					$where                        = array();
					$where_d					  = explode("-", $param);
					$where['effectivity_date']    = $where_d[0] . '-' . $where_d[1] . '-' . $where_d[2];
					$where['other_fund_flag']     = $where_d[3];
		
					
					$data['records']              = $this->rm->get_employment_records($select_fields, $tables, $where);
					
				break;

				case REPORT_PAYROLL_COVER_SHEET:

					$landscape 			   = TRUE;

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();
					

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where);

				break;

				case REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL:

					$field                             = array("DATE_FORMAT(date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(date_to,'%m/%d/%Y') as date_to") ;
					$table                             = $this->rm->tbl_attendance_period_hdr;
					$where                             = array();
					$where["attendance_period_hdr_id"] = $params['payroll_period'];
					$data['period_detail']				= $this->rm->get_reports_data($field, $table, $where, FALSE);


					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.compensation_id = B.compensation_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_payroll_type_status_offices,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.payroll_type_id = F.payroll_type_id'
					 	)			
					);

					

					$select_fields  = array();
					$select_fields[] = 'A.compensation_name';
					$select_fields[] = 'SUM(B.amount) as amount';
					
					$where         	= array();
					$where['E.attendance_period_hdr_id'] = $param['payroll_period'];
					$where['A.general_payroll_flag'] = 'Y';

				

					$group_by = array('A.compensation_name');

					

					$data['compensation_records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by);

					//DEDUCTIONS
					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_param_deductions,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.deduction_id = B.deduction_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_payroll_type_status_offices,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.payroll_type_id = F.payroll_type_id'
					 	)			
					);

					$select_fields  = array();
					$select_fields[] = 'A.deduction_name';
					$select_fields[] = 'SUM(B.amount) as amount';
					
					$where         	= array();
					$where['A.general_payroll_flag'] = 'Y';

					$group_by = array('A.deduction_name');

					

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by);
					
				break;

				case REPORT_GENERAL_PAYROLL_PER_OFFICE: 

					$field                             = array("DATE_FORMAT(date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(date_to,'%m/%d/%Y') as date_to") ;
					$table                             = $this->rm->tbl_attendance_period_hdr;
					$where                             = array();
					$where["attendance_period_hdr_id"] = $params['payroll_period'];
					$data['period_detail']			   = $this->rm->get_reports_data($field, $table, $where, FALSE);


					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.compensation_id = B.compensation_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_payroll_type_status_offices,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.payroll_type_id = F.payroll_type_id'
					 	)			
					);

					$select_fields  = array();
					$select_fields[] = 'A.compensation_name';
					$select_fields[] = 'SUM(B.amount) as amount';
					
					$where         	= array();
					$where['A.general_payroll_flag'] = 'Y';

					$group_by = array('A.compensation_name');

					

					$data['compensation_records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by);

					//DEDUCTIONS
					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_param_deductions,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.deduction_id = B.deduction_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_payroll_type_status_offices,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.payroll_type_id = F.payroll_type_id'
					 	)			
					);

					$select_fields  = array();
					$select_fields[] = 'A.deduction_name';
					$select_fields[] = 'SUM(B.amount) as amount';
					
					$where         	= array();
					$where['A.general_payroll_flag'] = 'Y';

					$group_by = array('A.deduction_name');

					

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by);

				break;

				case REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE: 

					$tables 		= array(
						
						'main'      => array(
						'table'     => $this->rm->tbl_remittances,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_remittance_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_id = B.remittance_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.compensation_id = E.compensation_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_deductions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.deduction_id = F.deduction_id'
					 	),	
					 	't7'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.office_id = G.office_id'
					 	)


						);

					$select_fields  = array();
					$select_fields[] = 'D.employee_name';  
					$select_fields[] = 'D.position_name';
					$select_fields[] = 'D.plantilla_item_number'; 
					$select_fields[] = 'ifnull(C.compensation_id,0)'; 
					$select_fields[] = 'E.compensation_name'; 
					$select_fields[] = 'ifnull(C.deduction_id,0)'; 
					$select_fields[] = 'F.deduction_name'; 
					$select_fields[] = 'C.amount'; 

					$where = array();
					$where['F.general_payroll_flag'] = 'Y';
					
					$group_by = array();
					$group_by[]       	= 'C.payroll_dtl_id';

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by);

				break;

				case REPORT_SPECIAL_PAYROLL_COVER_SHEET:					

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL: 

					$field                             = array("compensation_name") ;
					$table                             = $this->rm->tbl_param_compensations;
					$where                             = array();
					$where["compensation_id"] 		   = $params['compensation_type'];
					$data['compensation_details']	   = $this->rm->get_reports_data($field, $table, $where, FALSE);

					$where["parent_compensation_id"]   = $params['compensation_type'];
					$data['compensation_sub_details']  = $this->rm->get_reports_data($field, $table, $where, FALSE);



					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_remittances,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_remittance_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_id = B.remittance_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_param_remittance_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_status_id = E.remittance_status_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.compensation_id = F.compensation_id'
					 	),	
					 	't7'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.employee_id = G.employee_id'
					 	),
					 	't8'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'H.compensation_id = F.parent_compensation_id'
					 	)		
					);

					$select_fields   = array();
					$select_fields[] = 'D.employee_name';  
					$select_fields[] = 'D.position_name';
					$select_fields[] = 'G.employ_salary_grade'; 
					$select_fields[] = 'H.amount as amount'; 
					$select_fields[] = 'C.amount as other_amount'; 
					$select_fields[] = 'H.amount + C.amount as net_amount'; 
					
					$where         	= array();  
					$where['F.parent_compensation_id']  = $params['compensation_type'];
					

					$group_by  = array('D.employee_id');

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by); 

				break;

				case REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE: 
					
					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_remittances,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_remittance_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_id = B.remittance_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_param_remittance_status,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_status_id = E.remittance_status_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.compensation_id = F.compensation_id'
					 	),	
					 	't7'        => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.employee_id = G.employee_id'
					 	),
					 	't8'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'H.compensation_id = F.parent_compensation_id'
					 	),
					 	't9'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.payroll_summary_id = I.payroll_summary_id'
					 	)		
					);

					$select_fields   = array();
					$select_fields[] = 'D.employee_name';  
					$select_fields[] = 'D.position_name';
					$select_fields[] = 'G.employ_salary_grade'; 
					$select_fields[] = 'H.amount as amount'; 
					$select_fields[] = 'C.amount as other_amount'; 
					$select_fields[] = 'H.amount + C.amount as net_amount'; 
					
					$where         	= array();  
					$where['F.parent_compensation_id']  = $params['compensation_type'];
					$where['I.payout_type_flag']   = 'S';
					

					$group_by  = array('D.employee_id');

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by); 

				break;

				case REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE: 

					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_remittances,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_remittance_details ,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_id = B.remittance_id'
					 	),
						't3'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
					 	),
						't4'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
					 	), 
						't5'        => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.compensation_id = E.compensation_id'
					 	),	
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_deductions,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.deduction_id = F.deduction_id'
					 	),	
					 	't7'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.office_id = G.office_id'
					 	),
					 	't8'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.payroll_summary_id = H.payroll_summary_id'
					 	),
					 	't9'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'I',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.payroll_hdr_id = I.payroll_type_id'
					 	)
					);

					$select_fields  = array();
					$select_fields[] = 'D.employee_id';  
					$select_fields[] = 'C.payroll_hdr_id';
					$select_fields[] = 'D.employee_name'; 
					$select_fields[] = 'D.position_name'; 
					$select_fields[] = 'D.plantilla_item_number'; 
					$select_fields[] = 'group_concat(C.compensation_id)';
					$select_fields[] = 'group_concat(F.deduction_name)';
					$select_fields[] = 'group_concat(C.amount)';


					
					 
					$where         	= array();

					$where['H.attendance_period_hdr_id']  = '9';
					$where['H.payout_type_flag']   = 'R';

					$group_by  = array('D.employee_id');


					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by); 

				break;

				case REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_BANK_PAYROLL_REGISTER: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_ATM_ALPHA_LIST: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL:

				/*SELECT D.employee_name,  D.position_name, G.employ_salary_grade, H.amount as pay1, C.amount as pay 
					FROM remittances A
					LEFT JOIN remittance_details B ON A.remittance_id = B.remittance_id
					LEFT JOIN payout_details C ON B.payroll_dtl_id = C.payroll_dtl_id
					LEFT JOIN payout_header D ON C.payroll_hdr_id = D.payroll_hdr_id
					LEFT JOIN param_remittance_status E ON A.remittance_status_id = E.remittance_status_id 
					LEFT JOIN param_compensations F ON C.compensation_id = F.compensation_id
					LEFT JOIN employee_work_experiences G ON D.employee_id = G.employee_id
					LEFT JOIN payout_details H ON H.compensation_id = F.parent_compensation_id
					WHERE F.parent_compensation_id = 1
					GROUP BY D.employee_id; */
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_REMITTANCE_SUMMARY_PER_OFFICE: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_REMITTANCE_LIST_PER_OFFICE:

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE: 
					
					$tables 		= array(
						'main'      => array(
						'table'     => $this->rm->tbl_remittances,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_remittance_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_id = B.remittance_id'
					 	),
					 	't3'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_dtl_id = C.payroll_dtl_id'
					 	),
					 	't4'      => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_hdr_id = D.payroll_hdr_id'
						),
						't5'        => array(
						'table'     => $this->rm->tbl_param_remittance_type_deductions,
						'alias'     => 'E',
						'type'      => 'JOIN',
						'condition' => 'C.deduction_id = E.deduction_id'
					 	),
					 	't6'        => array(
						'table'     => $this->rm->tbl_param_remittance_types,
						'alias'     => 'F',
						'type'      => 'JOIN',
						'condition' => 'E.remittance_type_id = F.remittance_type_id'
					 	),
					 	't7'        => array(
						'table'     => $this->rm->tbl_param_remittance_status,
						'alias'     => 'G',
						'type'      => 'JOIN',
						'condition' => 'A.remittance_status_id = G.remittance_status_id AND G.remittance_status_id = 3'
					 	),
					 	't8'        =>array(
					 	'table'     => $this->rm->DB_CORE.".".$this->rm->tbl_organizations,
						'alias'     => 'H',
						'type'      => 'JOIN',
						'condition' => 'D.office_name = H.org_code'	
					 	)		

					);

					$group_by 		 = array('D.employee_id');
					$select_fields   = array();
					$select_fields[] = 'D.employee_id'; 
					$select_fields[] = 'D.employee_name'; 
					$select_fields[] = 'group_concat(F.remittance_type_id) remittances';
					$select_fields[] = 'group_concat(C.amount + ifnull(C.employer_amount,0)) AS amount';
					$select_fields[] = 'H.name';
					// print_r($office);
					// die();
					$where['D.office_name']         	= $office;

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where, array(), TRUE, $group_by); 
					$data['remittances_hdr'] = $this->rm->get_employment_records(array('*'), $this->rm->tbl_param_remittance_types,array());
				break;

				case REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE: 

		
					$tables 		= array(

						'main'      => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'A'
						),
						't2'        => array(
						'table'     => $this->rm->tbl_remittance_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_dtl_id = A.payroll_dtl_id'
					 	),
					 	't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_hdr_id = A.payroll_hdr_id'
					 	),
					 	't4'      => array(
						'table'     => $this->rm->tbl_employee_work_experiences,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.employee_id = C.employee_id'
						),
					 	't5'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.employee_id = D.employee_id',
					 	),
						't6'        => array(
						'table'     => $this->rm->tbl_param_offices,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.employ_office_id = F.office_id'
						),
						't7'        => array(
						'table'     => $this->rm->tbl_param_deductions,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.deduction_id = A.deduction_id'
						)
					);

					$select_fields    = array();
					$select_fields[]  = "C.employee_id";
					$select_fields[]  = "C.employee_name";
					$select_fields[]  = "A.reference_text"; 
					$select_fields[]  = "SUM(A.amount)";
					$select_fields[]  = "count(A.deduction_id)";
					$select_fields['d']  = "G.deduction_name";
					
					$where         	= array();

					$data['deduction_type'] = $d;

				

					$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;
 
				case REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING:
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_BIR_TAX_PAYMENTS: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_DOH_COOP_REMITTANCE_FILE:

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break; 

				case REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records( $select_fields, $tables, $where); 
				break;

				case REPORT_BIR_ALPHALIST:
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				
				break;

				case REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE: 
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;

				case REPORT_DISBURSEMENT_VOUCHER: 

					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 
				break;

				case REPORT_ENGAS_FILE_FOR_UPLOADING:
					
					$tables 		= array();

					$select_fields  = array();
					
					$where         	= array();

					//$data['records'] 	= $this->rm->get_employment_records($select_fields, $tables, $where); 

				break;
/**** START OF TIME AND ATTENDANCE ******************************************************************************************************/
				case REPORTS_TA_DAILY_TIME_RECORD: 

					$date_from             = date('Y-m-d',strtotime($params['date_range_from']));
					$date_to               = date('Y-m-d',strtotime($params['date_range_to']));
					$employee_id           = $params['employee'];
					
					$data['date_from']     = date('F d, Y',strtotime($date_from));
					$data['date_to']       = date('F d, Y',strtotime($date_to));
					
					$data['employee_dtr']  = $this->rm->get_print_dtr_list($date_from,$date_to,$employee_id);
					
					$table                 = $this->rm->tbl_employee_personal_info;
					$where                 = array();
					$where['employee_id']  = $params['employee'];
					$data['personal_info'] = $this->rm->get_reports_data(array("*"), $table, $where, FALSE);


				break;
				case REPORTS_TA_LEAVE_APPLICATION: 

					$data['sick_balance'] = 0;
					$data['vac_balance']  = 0;
					
					$request_id           = $params['leave_request'];
					$data['leave_detail'] = $this->rm->get_leave_application_detail($request_id);
					
					$field                = array("*") ;
					$table                = $this->rm->tbl_employee_leave_balances;
					$where                = array();
					$where["employee_id"] = $data['leave_detail']['employee_id'];
					$leave_balances       = $this->rm->get_reports_data($field, $table, $where, TRUE);
					if($leave_balances)
					{
						foreach ($leave_balances as $value) {
							if($value['leave_type_id'] == LEAVE_TYPE_SICK)
							{
								$data['sick_balance'] = $value['leave_balance'];
							}
							if($value['leave_type_id'] == LEAVE_TYPE_VACATION)
							{
								$data['vac_balance'] = $value['leave_balance'];
							}
						}
					}

				break;
				case REPORTS_TA_LEAVE_CARD: 

					$employee_id           = $this->hash($params['employee']);
					$data['employee_info'] = $this->rm->get_employee_basic_info($employee_id);

					$employee_id           = $params['employee'];
					$data['leave_detail']  = $this->rm->get_employee_leave_card($employee_id);				

				break;
				case REPORTS_TA_MONTHLY_ATTENDANCE: 

					
					$set_footer       = TRUE;
					$mra_details      = $this->rm->get_monthly_report_of_attendance($params);

					$field                             = array("DATE_FORMAT(date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(date_to,'%m/%d/%Y') as date_to") ;
					$table                             = $this->rm->tbl_attendance_period_hdr;
					$where                             = array();
					$where["attendance_period_hdr_id"] = $params['mra_attendance_period'];
					$data['period_detail']				= $this->rm->get_reports_data($field, $table, $where, FALSE);


					$old_office       = "";
					$old_employee     = "";
					$office_counter   = 0;
					$employee_counter = 0;
					$detail_counter   = 0;

					$mra_array = array();

					foreach ($mra_details as $key => $mra) 
					{
						if($mra['name'] != $old_office)
						{
							$employee_counter = 0;
							$office_counter++;
							$old_office = $mra['name'];
							$mra_array[$office_counter]['office'] = $mra['name'];
						}
						if($mra['employee_name'] != $old_employee)
						{
							$old_employee = $mra['employee_name'];
							$employee_counter++;
							$mra_array[$office_counter]['employee'][$employee_counter]['employee_name'] = $mra['employee_name'];
							$mra_array[$office_counter]['employee'][$employee_counter]['mra_detail'][] = $mra;
						}
						else
						{
							$mra_array[$office_counter]['employee'][$employee_counter]['mra_detail'][] = $mra;
						}
							
					}
					$data['mra_array'] = $mra_array;

				break;


			}

			if(strtolower($format) == 'pdf')
			{
				ini_set('memory_limit', '512M'); // boost the memory limit if it's low
				$this->load->library('pdf');
				//Legal Size Paper
				if ($landscape)
				{
					$pdf 	= $this->pdf->load('utf-8', array(356,216));
				}
				else if ($papersize_short)
				{
					$pdf 	= $this->pdf->load('utf-8', array(216,280));
				}
				else
				{
					$pdf 	= $this->pdf->load('utf-8', array(216,356));
				}
				if($set_footer)
				{
					$footer = '<table width="100%">';
					$footer .= '<tr>';
					$footer .= '<td align="left"><font size="2"><b>Run Time : </b>'. date('m/d/Y g:i:s a') .'</font></td>';
					$footer .= '<td align="right"><font size="2">Page {PAGENO} of {nb}<font size="2"></td>';
					$footer .= '</tr></table>';
					
					$pdf->SetHTMLFooter($footer);
				}
				

				$html 	= $this->load->view('forms/reports/' . $report , $data, TRUE);
				$pdf->WriteHTML($html);
				$pdf->Output();
			}

			if(strtolower($format) == 'excel')
			{
				$this->load->view('forms/reports/' . $report , $data);
				
				$echo = ob_get_contents();
				ob_end_clean();
					
				header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
				header("Content-Disposition: attachment; filename=".date('F')."_".date('d')."_".date('Y').".xls");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: private",false);
				
				echo $echo;
			}
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			print_r($message);
			die();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}		
	}

	public function get_leave_requests()
	{
		$params      = get_params();
		$list        = array();
		
		$employee_id = $params['employee'];
		
		$requests    = $this->rm->get_leave_requests($employee_id);
		
		if(!EMPTY($requests))
		{
			foreach ($requests as $aRow):
				$request_id = $this->hash($aRow["request_id"]);	
				$list[] = array(
								"value" => $request_id,
								"text" => $aRow["request_code"]
						);
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}

	public function get_mra_attendance_period()
	{
		
		$list        = array();
		
		$field                     = array("*") ;
		$tables = array(

				'main'      => array(
				'table'     => $this->rm->tbl_attendance_period_hdr,
				'alias'     => 'A',
				),
				't2'        => array(
				'table'     => $this->rm->tbl_param_payroll_types,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.payroll_type_id = B.payroll_type_id',
			 	)
			);
		$where                     = array();
		$where['A.period_status_id'] = ATTENDANCE_PERIOD_COMPLETED;
		$attendance_period         = $this->rm->get_reports_data($field, $tables, $where, TRUE);
		
		if(!EMPTY($attendance_period))
		{
			foreach ($attendance_period as $aRow):
				$date_from = date('m/d/Y',strtotime($aRow["date_from"]));
				$date_to   = date('m/d/Y',strtotime($aRow["date_to"]));
				$list[] = array(
								"value" => $aRow["attendance_period_hdr_id"],
								"text" => $aRow["payroll_type_name"]." - ".$date_from." - ".$date_to
						);
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}
	public function get_mra_office()
	{
		
		$list        = array();
		
		$params = get_params();
		$offices         = $this->rm->get_mra_office($params['attendance_period']);
		
		if(!EMPTY($offices))
		{
			foreach ($offices as $aRow):
				$list[] = array(
								"value" => $aRow["office_id"],
								"text" => $aRow["name"]
						);
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}
	public function get_mra_employee()
	{
		
		$list      = array();
		
		$params    = get_params();
		$employees = $this->rm->get_mra_employee($params);
		
		if(!EMPTY($employees))
		{
			foreach ($employees as $aRow):
				$list[] = array(
								"value" => $aRow["employee_id"],
								"text" => $aRow["employee_name"]
						);
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}

	public function get_payroll_period()
	{
		
		$list        = array();
		
		$params = get_params();
		$p_period         = $this->rm->get_payroll_period($params['payroll_type']);
		
		if(!EMPTY($p_period ))
		{
			foreach ($p_period  as $aRow):
				$list[] = array(
								"value" => $aRow["attendance_period_hdr_id"],
								"text" => $aRow["date_from"] ." - ". $aRow["date_to"]
						);
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */