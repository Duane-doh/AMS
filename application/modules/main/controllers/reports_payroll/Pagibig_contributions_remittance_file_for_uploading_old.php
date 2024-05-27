<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagibig_contributions_remittance_file_for_uploading_old extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data                  		 = array();
			$date_range_from			 = $params['date_range_from'];
			$date_range_to				 = $params['date_range_to'];
			$report_name				 = $params['reports'];
			
			$payroll_type_ids = "";
			foreach($params['payroll_type_rem'] as $pt)
			{
				if($pt == end($params['payroll_type_rem']))
				{
					$payroll_type_ids .= " C.payroll_type_ids LIKE '%" . $pt . "%'";
				}else{
					$payroll_type_ids .= " C.payroll_type_ids LIKE '%" . $pt . "%' OR";
				}
			
			}
			
			$data['pagibig_remittances'] = $this->rm->get_pagibig_contributions_remittance_list($date_range_from, $date_range_to, $report_name, $payroll_type_ids);
			
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


/* End of file Pagibig_contributions_remittance_file_for_uploading.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Pagibig_contributions_remittance_file_for_uploading.php */