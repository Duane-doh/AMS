<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payslip_for_regulars_and_non_careers extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data  = array();
			$where = array();
			
			$data['office'] = $this->rm->get_agency_info($params['office_spe_pay']);
			
			//STORES payroll_summary_id
			$where = array();
			$where['payout_type_flag'] = PAYOUT_TYPE_FLAG_SPECIAL;
			$where['compensation_id'] = $params['compensation_special_type'];
			$summary_id = $this->rm->get_payroll_summary_id($where);
			
			//STORES HEADER
			$where = array();
			
			if(!EMPTY($params['employee_gen_pay']))
			{
				$where['employee_id'] = $params['employee_gen_pay'];
			}
			$where['A.office_id'] = $params['office_spe_pay'];
			$where['C.payroll_summary_id'] = $summary_id['payroll_summary_id'];
			$results = $this->rm->get_special_payout_header($where);
			
			// STORES SPECIFIC COMPENSATION NAME
			$where = array();
			$where['compensation_id'] = $params['compensation_special_type'];
			$data['compensation_name'] = $this->rm->get_specific_compensation($where);

			// STORES MONTH
			$data['month_year'] = $params['month_text'] . " " . $params['year'];
			$data['year'] = $params['year'];
			

			//GET DETAILS
			$where = array();
			$where['EXTRACT(YEAR FROM A.effective_date)'] = $params['year']; 
			$where['EXTRACT(MONTH FROM A.effective_date)'] = $params['month']; 
			
			$ctr = 0;
			foreach($results as $result){
				$data['results'][$ctr]['header'] = $result;
				
				$where['A.payroll_hdr_id '] = $result['payroll_hdr_id'];
				$data['results'][$ctr]['details'] = $this->get_details($where);
				$ctr++;
			}
			
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
	
	public function get_details($where){
		$detail_results = array();
		$detail_results = $this->rm->get_special_payslip_details($where);
		return $detail_results;
	}

}


/* End of file Special_payslip_for_regulars_and_non_careers.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Special_payslip_for_regulars_and_non_careers.php */