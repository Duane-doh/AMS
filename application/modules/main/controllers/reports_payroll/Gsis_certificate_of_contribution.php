<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsis_certificate_of_contribution extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
		$this->load->model('common_model', 'cm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$param 					= $params['employee'];
			$office					= $params['date_range_from'];
			$date					= $params['date_range_to'];
			$reviewed_by			= $params['cert_by'];
			$prepared_by			= $params['prep_by'];
			$deduction_id			= $params['contrib_deduction_type'];

			$data            		= array();			
			$data['records'] 		= $this->rm->get_gsis_certificate_of_contribution_list($param, $office, $date,$deduction_id);
			$deduc_code_param 		= 'CERT_GSIS_CODE';
			$data['employee_info']  = $this->rm->get_certification_employee_info($param, $deduc_code_param);
			$data['office_name'] 	= (USE_ADMIN_OFFICE == YES) ? $data['employee_info']['admin_office_name'] : $data['employee_info']['employ_office_name'];

			$where                 = array();
			$field                 = array("deduction_name","employer_share_flag") ;
			$table                 = $this->rm->tbl_param_deductions;
			$where['deduction_id'] = $deduction_id;
			
			$data['deduction']     = $this->rm->get_reports_data($field, $table, $where,false);
			// SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($reviewed_by);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($prepared_by);
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


/* End of file Gsis_certificate_of_contribution.php */
/* Location: ./application/modules/main/controllers/reports/hr/Gsis_certificate_of_contribution.php */