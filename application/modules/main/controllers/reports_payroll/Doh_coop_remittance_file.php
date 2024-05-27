<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doh_coop_remittance_file extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			
			$data                  = array();
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


/* End of file Doh_coop_remittance_file.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Doh_coop_remittance_file.php */