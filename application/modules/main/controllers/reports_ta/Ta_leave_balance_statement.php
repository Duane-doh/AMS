<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_leave_balance_statement extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			
			$data                 = array();
			$office_id            = $params['office_list'];
			
			$employee_id          = $params['prepared_by'];
			$data['prepared_by']  = $this->cm->get_report_signatory_details($employee_id);
			
			$employee_id          = $params['approved_by'];
			$data['approved_by']  = $this->cm->get_report_signatory_details($employee_id);
			
			$offices              = $this->rm->get_office_child('', $office_id);
			$data['employee_dtl'] = $this->rm->get_leave_balance_statement($params, $offices);
			
			$data['agency']       = $this->rm->get_agency_info($office_id);
			
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
/* Location: ./application/modules/main/controllers/reports/ta/Ta_leave_balance_statement.php */