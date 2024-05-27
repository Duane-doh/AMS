<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_alphalist_terminated_before_year_end extends Main_Controller {
	
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


/* End of file Bir_alphalist_terminated_before_year_end.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_alphalist_terminated_before_year_end.php */