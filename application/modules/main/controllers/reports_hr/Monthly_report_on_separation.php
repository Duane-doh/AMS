<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monthly_report_on_separation extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($param = NULL, $office = NULL, $date = NULL, $status = NULL, $reviewed_by = NULL)
	{
		try
		{
			$date_arr 		= explode('A', $date);
			$date_from 		= $date_arr[0];
			$date_to 		= $date_arr[1];

			$field                    	= array("sys_param_value");
			$table                    	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$where                    	= array();
			$where['sys_param_type']  	= 'SEPARATION_FINISHED_CONTRACT';
			$sys_param      			= $this->rm->get_reports_data($field, $table, $where, FALSE);

			$data           = array();
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
				't4'        => array(
				'table'     => $this->rm->tbl_param_employment_status,
				'alias'     => 'D',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.employment_status_id = D.employment_status_id'
			 	),
				't5'        => array(
				'table'     => $this->rm->tbl_param_positions,
				'alias'     => 'E',
				'type'      => 'LEFT JOIN',
				'condition' => 'E.position_id = A.employ_position_id'
			 	),
				't6'        => array(
				'table'     => $this->rm->tbl_param_position_levels,
				'alias'     => 'F',
				'type'      => 'LEFT JOIN',
				'condition' => 'F.position_level_id = E.position_level_id'
			 	),
				't7'        => array(
				'table'     => $this->rm->tbl_param_separation_modes,
				'alias'     => 'G',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.separation_mode_id = G.separation_mode_id'
			 	)
			);

			$select_fields   = array();
			$select_fields[] = 'A.employ_start_date';
			
			// $select_fields[] = 'CONCAT(B.last_name, \', \', B.first_name, \' \', LEFT(B.middle_name, 1), \'. \') employee_name';
			// ====================== jendaigo : start : change name format ============= //
			$select_fields[] = 'CONCAT(B.last_name, \', \', B.first_name, IF(B.ext_name=\'\', \'\', CONCAT(\' \', B.ext_name)), IF((B.middle_name=\'NA\' OR B.middle_name=\'N/A\' OR B.middle_name=\'-\' OR B.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(B.middle_name, 1), \'. \'))) employee_name';
			// ====================== jendaigo : end : change name format ============= //
			
			$select_fields[] = 'A.employ_position_name position_name';
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name AS name' : 'A.employ_office_name AS name';
			$select_fields[] = 'A.employ_salary_grade salary_grade';
			$select_fields[] = 'D.employment_status_name';
			$select_fields[] = 'DATE_FORMAT(A.employ_end_date, "%Y/%m/%d") employ_end_date';
			$select_fields[] = 'G.separation_mode_name';
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id office_id' : 'A.employ_office_id office_id';
			$select_fields[] = 'F.position_level_name';

			$where                         																  		= array();
			$where['A.separation_mode_id'] 																  		= 'IS NOT NULL';
			$where['A.employ_end_date']                     													= array($value = array($date_from,$date_to), array("BETWEEN"));
			// $where['A.employ_type_flag']  																		= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
			if($status == REGULAR_PERMANENT) 
			{				
				$where['D.jo_flag'] 		   = NO;
				$where['A.separation_mode_id'] = array($sys_param['sys_param_value'], array("!="));
			}
			else
			{
				$where['D.jo_flag'] 		   = YES;
			}

			if($office != 'A')
			{
				if(USE_ADMIN_OFFICE == YES)
				{
					$where['A.admin_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
				else
				{					
					$where['A.employ_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
			}

			$office_fields 	 = array();
			$office_fields[] = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_id office_id' : 'A.employ_office_id office_id';
			$office_fields[] = (USE_ADMIN_OFFICE == YES) ? 'A.admin_office_name AS office' : 'A.employ_office_name AS office';

			$order_by 			= array('office_id' => 'ASC');
			//$group_by 			= array('A.employee_id');
			$data['records'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);

			$group_by 			= array('office_id');
			$data['office'] 	= $this->rm->get_reports_data($office_fields, $tables, $where, TRUE, $order_by, $group_by);
			
			$agency				=  $this->rm->get_agency_info($office);
			$data['office_name']= !EMPTY($agency) ? $agency['name'] : 'DEPARTMENT OF HEALTH - CENTRAL OFFICE';

			// SIGNATORIES
			$data['certified_by']  		= $this->cm->get_report_signatory_details($param);
			$data['approved_by']  		= $this->cm->get_report_signatory_details($reviewed_by);

			//DATE HEADER
			$date_from_hdr		= date_format(date_create($date_from), 'F d, Y');
			$date_to_hdr		= date_format(date_create($date_to), 'F d, Y');
			$data['date_hdr'] 	= $date_from_hdr . ' - ' .  $date_to_hdr;
		
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


/* End of file Monthly_report_on_separation.php */
/* Location: ./application/modules/main/controllers/reports/hr/Monthly_report_on_separation.php */