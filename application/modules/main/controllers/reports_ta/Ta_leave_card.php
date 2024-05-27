<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_leave_card extends Main_Controller {
	
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
			
			$employee_id           = $this->hash($params['employee']);
			$data['employee_info'] = $this->rm->get_employee_basic_info($employee_id);
			
			$employee_id           = $params['employee'];
			$data['leave_detail']  = $this->rm->get_employee_leave_card($employee_id);	
			
			/*======================================= MARVIN : START : GET TRANSACTION_TYPE_ID AND REMARKS FIELDS =======================================*/
			$leave_trans_remarks = array();
			foreach($data['leave_detail'] as $leave)
			{
				$leave_trans_remarks[] = $this->rm->get_leave_transaction_type_id($leave['employee_id'], $leave['leave_type_id'], $leave['transaction_date']);
			}
			for($i=0; $i<count($leave_trans_remarks); $i++)
			{
				$data['leave_detail'][$i]['leave_transaction_type_id'] = $leave_trans_remarks[$i]['leave_transaction_type_id'];
				$data['leave_detail'][$i]['remarks'] = $leave_trans_remarks[$i]['remarks'];
			}
			/*======================================= MARVIN : END : GET TRANSACTION_TYPE_ID AND REMARKS FIELDS =======================================*/
			
			/*RLog::error('TEST=>'.json_encode($data['leave_detail']));
			echo '<pre>';
			print_r($data['leave_detail']);
			die();*/
			$fields                      = array('MIN(employ_start_date) as employ_start_date'
			);
			$where                     = array();
			$where['employee_id']      = $params['employee'];
			$where['employ_type_flag'] = array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
			$table                     = $this->rm->tbl_employee_work_experiences;
			$data['employment_date']   = $this->rm->get_reports_data($fields,$table,$where,FALSE);
			

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


/* End of file Ta_leave_card.php */
/* Location: ./application/modules/main/controllers/reports/ta/Ta_leave_card.php */