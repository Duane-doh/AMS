<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagibig_contributions_remittance_file_for_uploading extends Main_Controller {
	
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
			$data               = array();

			$rem_certified_by    = $params['rem_certified_by'];

			$date_range_from    = $params['date_range_from'];
			$date_range_to      = $params['date_range_to'];
			$report_name        = $params['reports'];
			$data['month_year'] =  date("F Y", strtotime($params['date_range_from']));
			
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
			$office_list = array();
			if($params['office_list'])
			{
				$office_list = $this->rm->get_office_child('', $params['office_list']);
				$office_name = $this->rm->get_agency_info($params['office_list']);
				$data['office_name'] = $office_name['name'];

			}
			$data['pagibig_remittances'] = $this->rm->get_pagibig_contributions_remittance_list($date_range_from, $date_range_to, $report_name, $payroll_type_ids,$office_list);
			$doh_sub 						= $this->rm->get_sysparam_value(DOH_ADDRESS_SUBDIVISION);
			$doh_brgy						= $this->rm->get_sysparam_value(DOH_ADDRESS_BARANGAY);
			$doh_municity					= $this->rm->get_sysparam_value(DOH_ADDRESS_MUNICITY);
			$doh_zip					= $this->rm->get_sysparam_value(DOH_ADDRESS_ZIP_CODE);
			$data['doh_address']			= $doh_sub['sys_param_value'] . ", " . $doh_brgy['sys_param_value'] . ", " . $doh_municity['sys_param_value'];
			$data['zip_code']				= $doh_zip['sys_param_value'];
			$doh_tin						= $this->rm->get_sysparam_value(DOH_TIN);
			$data['doh_tin']				= $doh_tin['sys_param_value'];

			$doh_hmdf						= $this->rm->get_sysparam_value(DOH_HMDF);
			$data['doh_hmdf']				= $doh_hmdf['sys_param_value'];
			
			//SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($rem_certified_by);
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