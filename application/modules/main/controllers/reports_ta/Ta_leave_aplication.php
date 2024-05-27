<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_leave_aplication extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			
			$data                  = array();
			
			$data['sick_balance'] = 0;
			$data['vac_balance']  = 0;
			
			$request_id           = $params['leave_request'];
			$data['leave_detail'] = $this->rm->get_leave_application_detail($request_id);
			if($data['leave_detail']['request_status_id'] == REQUEST_APPROVED)
			{
				$data['leave_detail']['approved_flag'] = "TRUE";
				
			}
			if($data['leave_detail']['request_status_id'] == REQUEST_REJECTED)
			{
				$data['leave_detail']['approved_flag'] = "FALSE";
				$leave_remarks = $this->rm->get_leave_remarks($request_id);
				if($leave_remarks){
					$data['leave_detail']['remarks'] = $leave_remarks['remarks'];
				}
				
			}

			if(!in_array($data['leave_detail']['leave_type_id'], array(LEAVE_TYPE_MATERNITY,LEAVE_TYPE_SICK)))
			{
				$field                = array("leave_type_name") ;
				$table                = $this->rm->tbl_param_leave_types;
				$where                = array();
				$where["leave_type_id"] = $data['leave_detail']['leave_type_id'];
				$leave_types       = $this->rm->get_reports_data($field, $table, $where, FALSE);
				if($leave_types)
				{
					$data['leave_detail']['leave_type_name'] = $leave_types['leave_type_name'];
				}
			}
			


		/*	$field                = array("*") ;
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
			}*/

			
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


/* End of file Ta_leave_aplication.php */
/* Location: ./application/modules/main/controllers/reports/hr/Ta_leave_aplication.php */