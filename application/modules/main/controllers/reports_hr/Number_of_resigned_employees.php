<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Number_of_resigned_employees extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data          				= array();
			
			$date_arr 		= explode('A', $date);
			$date_from 		= $date_arr[0];
			$date_to 		= $date_arr[1];

			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'SEPARATION_RESIGN';
			$sys_param      			= $this->rm->get_reports_data($field, $table, $where, FALSE);

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
				't5'        => array(
				'table'     => $this->rm->tbl_param_employment_status,
				'alias'     => 'E',
				'type'      => 'LEFT JOIN',
				'condition' => 'B.employment_status_id = E.employment_status_id'
			 	)
			);

			$select_fields   = array();
			$select_fields[] = 'DATE_FORMAT(B.employ_end_date, "%Y/%m/%d") employ_end_date';
			$select_fields[] = 'A.agency_employee_id AS personnel_number';
			
			// $select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
			// ====================== jendaigo : start : change name format ============= //
			$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) employee_name';
			// ====================== jendaigo : end : change name format ============= //
			
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name AS office' : 'B.employ_office_name AS office';
			$select_fields[] = 'E.employment_status_name AS service_status';
			$select_fields[] = 'B.employ_position_name as position_name';
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_id office_id' : 'B.employ_office_id office_id';

			$where                        	= array();
			$where['B.separation_mode_id']  = array($sys_param, array('IN'));
			$where['B.employ_end_date']     = array($value = array($date_from,$date_to), array("BETWEEN"));
			$where['B.employ_type_flag']  	= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
			if($office != 'A')
			{
				if(USE_ADMIN_OFFICE == YES)
				{
					$where['B.admin_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
				else
				{					
					$where['B.employ_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
			}

			$order_by 			= array('office_id' => 'ASC', 'employee_name' => 'ASC');
			$group_by 			= array('B.employee_id');
			$data['records'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);
			
			$order_by 			= array('office_id' => 'ASC');
			$group_by 			= array('office_id');
			$data['office'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);
			$data['agency']		= $this->rm->get_agency_info($office);

			//DATE HEADER
			$date_from_hdr		= date_format(date_create($date_from), 'F d, Y');
			$date_to_hdr		= date_format(date_create($date_to), 'F d, Y');
			$data['date_hdr'] 	= 'For the period of ' . strtoupper($date_from_hdr) . ' - ' .  strtoupper($date_to_hdr);
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


/* End of file Number_of_resigned_employees.php */
/* Location: ./application/modules/main/controllers/reports/hr/Number_of_resigned_employees.php */