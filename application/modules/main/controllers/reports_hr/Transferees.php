<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transferees extends Main_Controller {
	
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
			$select_fields[] = 'B.employ_start_date';
			$select_fields[] = 'B.employ_end_date';
			$select_fields[] = 'A.agency_employee_id AS personnel_number';
			
			// $select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, \' \', LEFT(A.middle_name, 1), \'. \') employee_name';
			// ====================== jendaigo : start : change name format ============= //
			$select_fields[] = 'CONCAT(A.last_name, \', \', A.first_name, IF(A.ext_name=\'\', \'\', CONCAT(\' \', A.ext_name)), IF((A.middle_name=\'NA\' OR A.middle_name=\'N/A\' OR A.middle_name=\'-\' OR A.middle_name=\'/\'), \'\', CONCAT(\' \', LEFT(A.middle_name, 1), \'. \'))) employee_name';
			// ====================== jendaigo : end : change name format ============= //
			
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name AS office' : 'B.employ_office_name AS office';
			$select_fields[] = 'E.employment_status_name AS service_status';
			$select_fields[] = 'B.employ_position_name position_name';
			$select_fields[] = 'B.transfer_to';
			$select_fields[] = 'B.transfer_flag';
			$select_fields[] = (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_id office_id' : 'B.employ_office_id office_id';

			$field                    		= array("sys_param_value");
			$param_table                	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
			$param_where                   	= array();
			$param_where['sys_param_type'] 	= 'MOVT_TRANSFER_ID';
			$sys_param_transfer				= $this->rm->get_reports_data($field, $param_table, $param_where, FALSE);

			$where 								= array();
			if($param == MOVT_TRANSFER_PROMOTION)
			{
				$field                    		= array("sys_param_value");
				$param_table                	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
				$param_where                   	= array();
				$param_where['sys_param_type'] 	= 'MOVT_TRANSFER_PROMOTION_ID';
				$sys_param      				= $this->rm->get_reports_data($field, $param_table, $param_where, FALSE);

				$where['B.employ_start_date']   		 = array($value = array($date_from,$date_to), array("BETWEEN", "(", "OR"));
				$where['B.employ_end_date']   		 	 = array($value = array($date_from,$date_to), array("BETWEEN", ")"));
				$where['B.employ_personnel_movement_id'] = $sys_param['sys_param_value'];
			}
			else if ($param == TRANSFER_IN)
			{
				$where['B.employ_start_date']   		 = array($value = array($date_from,$date_to), array("BETWEEN"));
				$where['B.transfer_flag'] 				 = $param;
				$where['B.employ_personnel_movement_id'] = $sys_param_transfer['sys_param_value'];
			}
			else
			{
				$field                    		= array("sys_param_value");
				$param_table                	= $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param;
				$param_where                   	= array();
				$param_where['sys_param_type'] 	= 'MOVT_TRANSFER_OUT';
				$sys_param_out					= $this->rm->get_reports_data($field, $param_table, $param_where, FALSE);

				$where['B.employ_end_date']   		 	 = array($value = array($date_from,$date_to), array("BETWEEN"));
				$where['B.transfer_flag'] 				 = $param;
				$where['B.employ_personnel_movement_id'] = array($sys_param_transfer['sys_param_value'], array("=", "(", "OR")); 
			  	$where['B.separation_mode_id'] 			 = array($sys_param_out['sys_param_value'], array("=", ")"));
			}
			
			if($office != 'A')
			{
				if(USE_ADMIN_OFFICE == YES)
				{
					$where['B.admin_office_id']  = array($this->rm->get_office_child('', $office),array('IN'));
				}
				else
				{					
					$where['B.employ_office_id'] = array($this->rm->get_office_child('', $office),array('IN'));
				}
			}

			$office_fields 		= array();
			$office_fields[] 	= (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_id office_id' : 'B.employ_office_id office_id';
			$office_fields[] 	= (USE_ADMIN_OFFICE == YES) ? 'B.admin_office_name AS office' : 'B.employ_office_name AS office';

			$order_by 			= array('office_id' => 'ASC', 'employee_name' => 'ASC');
			$data['records'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, $order_by, $group_by);

			$order_by 			= array('office_id' => 'ASC');
			$group_by 			= array('office_id', 'office');
			$data['office'] 	= $this->rm->get_reports_data($office_fields, $tables, $where, TRUE, $order_by, $group_by);

			$data['agency']		= $this->rm->get_agency_info($office);
			//DATE HEADER
			$date_from_hdr		= date_format(date_create($date_from), 'F d, Y');
			$date_to_hdr		= date_format(date_create($date_to), 'F d, Y');
			$data['date_hdr'] 	= 'For the period of ' . strtoupper(date_format(date_create($date_from_hdr), 'F d, Y')) . ' - ' .  strtoupper($date_to_hdr);

			$data['header']     = $param;

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


/* End of file Transferees.php */
/* Location: ./application/modules/main/controllers/reports/hr/Transferees.php */