<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Entitlement_longevity_pay extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_hr_model', 'rm');
	}	
	
	public function generate_report_data( $param = NULL, $office = NULL, $date = NULL)
	{
		try
		{
			
			$data                  = array();
			$office_child 		   = $this->rm->get_office_child('', $office);
			$records 	   		   = $this->rm->get_entitlement_longevity_pay_list($office_child);
			$data['agency']		   = $this->rm->get_agency_info($office);
			$data['records'] 	   = $records;	

			$lp_num = array();
			foreach ($records as $record) {
				$lp_num[] = $record['max_lp_num'];
			}

			$data['max_lp_num'] = max($lp_num);

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


/* End of file Entitlement_longevity_pay.php */
/* Location: ./application/modules/main/controllers/reports/hr/Entitlement_longevity_pay.php */