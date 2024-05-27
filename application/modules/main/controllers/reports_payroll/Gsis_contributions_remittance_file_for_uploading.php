<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsis_contributions_remittance_file_for_uploading extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{			
			$data              			= array();
			$year						= $params['year'];
			$month						= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month					= $year.$month;
			
			$payroll_type_ids = "";
			foreach($params['payroll_type_rem'] as $pt)
			{
				if($pt == end($params['payroll_type_rem']))
				{
					$payroll_type_ids .= " A.payroll_type_ids LIKE '%" . $pt . "%'";
				}else{
					$payroll_type_ids .= " A.payroll_type_ids LIKE '%" . $pt . "%' OR";
				}
			
			}
			
			$deduction_id				= $this->rm->get_deduction_id_sys_param();
			$deduc_converted			= $this->convert_deduction_id($deduction_id);
			
			$data['agency']				= $this->rm->get_remittance_report_header("REMITTANCE_AGENCY");
			$data['code']				= $this->rm->get_remittance_report_header("REMITTANCE_OFFICE_CODE");
			$data['gsis_remittances']	= $this->rm->get_gsis_contributions_remittance_list($year_month, REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING, $deduc_converted, $payroll_type_ids);			
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
	
	// $deduction_id TO ARRAY WITH BETTER KEY AND VALUE
	public function convert_deduction_id($deduction_id){
		$new_array = array();
		
		foreach($deduction_id as $data){
			$new_array[$data['sys_param_name']] = $data['sys_param_value'];
		}
		
		return $new_array;
	}

}


/* End of file Gsis_contributions_remittance_file_for_uploading.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Gsis_contributions_remittance_file_for_uploading.php */