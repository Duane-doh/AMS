<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_record extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data           = array();
			
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
				't4'        => array(
				'table'     => $this->rm->tbl_param_employment_status,
				'alias'     => 'D',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.employment_status_id = D.employment_status_id'
			 	),
				't7'        => array(
				'table'     => $this->rm->tbl_param_government_branches,
				'alias'     => 'G',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.government_branch_id = G.branch_id'
			 	),
				't8'        => array(
				'table'     => $this->rm->tbl_param_separation_modes,
				'alias'     => 'H',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.separation_mode_id = H.separation_mode_id'
			 	),
				//MARVIN
				't9'        => array(
				'table'     => $this->rm->tbl_employee_leave_details,
				'alias'     => 'I',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.employee_id = I.employee_id AND I.leave_start_date BETWEEN A.employ_start_date AND A.employ_end_date'
			 	)
			);

			$select_fields   = array();
			$select_fields[] = 'B.last_name';
			$select_fields[] = 'B.first_name';
			$select_fields[] = 'B.middle_name';
			$select_fields[] = 'B.ext_name';
			$select_fields[] = 'DATE_FORMAT(B.birth_date, "%M %d, %Y") AS birth_date';
			$select_fields[] = 'B.birth_place';
			$select_fields[] = 'B.agency_employee_id';
			$select_fields[] = 'A.employ_plantilla_id';
			$select_fields[] = 'A.remarks';
			$select_fields[] = 'DATE_FORMAT(A.employ_start_date, "%m/%d/%Y") AS employ_start_date';
			$select_fields[] = 'DATE_FORMAT(A.employ_end_date, "%m/%d/%Y") AS employ_end_date';
			$select_fields[] = '(A.employ_monthly_salary * 12) AS annual_salary';
			$select_fields[] = 'A.employ_position_name position_name';
			$select_fields[] = 'D.employment_status_code';
			$select_fields[] = 'IFNULL(A.admin_office_name, A.employ_office_name) office_name';
			$select_fields[] = 'G.branch_name';
			$select_fields[] = 'G.branch_code';
			$select_fields[] = 'H.separation_mode_name';
			$select_fields[] = 'A.service_lwop';
			//MARVIN
			$select_fields[] = 'SUM(IF(I.leave_wop IS NULL, 0, I.leave_wop)) leave_wop';

			$where                        = array();
			$where['A.employee_id']       =  $param;
			$where['A.employ_type_flag']  = array(array(NON_DOH_GOV, DOH_GOV_APPT, DOH_GOV_NON_APPT), array('IN'));
			
			//MARVIN
			$group_by				= array('A.employ_start_date');

			$order_by 				= array('A.employ_start_date' => 'ASC', 'A.employ_end_date' => 'DESC');
			// $data['record']  		= $this->rm->get_employment_records($select_fields, $tables, $where, $order_by);
			
			//MARVIN
			$data['record']  		= $this->rm->get_employment_records($select_fields, $tables, $where, $group_by, $order_by);

			// SIGNATORIES
			$data['certified_by']  	= $this->cm->get_report_signatory_details($office);

			//LEGEND - EMPLOYMENT STATUS
			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'LEGEND_STATUS';
			$sys_param      			= $this->rm->get_reports_data($field, $table, $where, TRUE);

			$value 			= array();
			foreach($sys_param AS $sys)
			{
				$value[] 	= $sys['sys_param_value'];
			}

			$field                  		= array("LOWER(employment_status_name) employment_status_name", "UPPER(employment_status_code) employment_status_code");
			$table                  		= $this->rm->tbl_param_employment_status;
			$where                  		= array();
			$where['employment_status_id'] 	= array($value, array("IN"));
			// $employment_status      		= $this->rm->get_reports_data($field, $table, $where, TRUE);
			// ====================== jendaigo : start : sort employment status name ============= //
			$employment_status      		= $this->rm->get_reports_data($field, $table, $where, TRUE, array('employment_status_name' => 'ASC'));
			// ====================== jendaigo : end : sort employment status name ============= //

			//LEGEND - GOVET BRANCHES
			$field                  = array("LOWER(branch_name) branch_name", "UPPER(branch_code) branch_code");
			$table                  = $this->rm->tbl_param_government_branches;
			$where                  = array();
			$where['active_flag'] 	= YES;
			$government_branches    = $this->rm->get_reports_data($field, $table, $where, TRUE);

			$legend_status 			= array();
			$legend_branch			= array();
			foreach($employment_status AS $status)
			{
				// $legend_status[] 	= '[' . $status['employment_status_code'] . ']-' . ucwords($status['employment_status_name']) . '; ';
				// ====================== jendaigo : start : fix format ============= //
				$legend_stat		= '[' . $status['employment_status_code'] . '] - ' . ucwords($status['employment_status_name']) . '; ';
				$legend_status[] 	= str_replace(["With", "The", "Of", "W/"], ["with", "the", "of", "w/"], $legend_stat);
				// ====================== jendaigo : start : fix format ============= //
			}
			foreach($government_branches AS $branch)
			{
				$legend_branch[] 	= '[' . $branch['branch_code'] . ']-' . ucwords($branch['branch_name']) . '; ';
			}

			$data['legend_status'] 	= $legend_status;
			$data['legend_branch'] 	= $legend_branch;

			$data['date'] 	= $date;

		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}	

		return $data;			
	}

}


/* End of file Service_record.php */
/* Location: ./application/modules/main/controllers/reports/hr/Service_record.php */