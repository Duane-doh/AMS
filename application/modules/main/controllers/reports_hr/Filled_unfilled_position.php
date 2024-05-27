<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filled_unfilled_position extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data                  			= array();

			if($office != 'A')
			{
				$office_child 				= $this->rm->get_office_child('', $office);
			}			

			$data['filled_positions']       = $this->rm->get_filled_positions_list($office_child);
			$data['unfilled_positions']    	= $this->rm->get_unfilled_positions_list($office_child);

			$agency							=  $this->rm->get_agency_info($office);
			$data['office_name'] 			= !EMPTY($agency) ? $agency['name'] : 'DEPARTMENT OF HEALTH - CENTRAL OFFICE';
		
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


/* End of file Filled_unfilled_position.php */
/* Location: ./application/modules/main/controllers/reports/hr/Filled_unfilled_position.php */