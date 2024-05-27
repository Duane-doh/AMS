<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gsis_membership_form extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$param 							= $params['employee'];

			$data            				= array();
			
			// PERSONAL INFO
			$data['personal_info'] 			= $this->rm->get_gsis_personal_info($param);
			
			// RESIDENTIAL ADDRESS
			$data['residential_address']	= $this->rm->get_gsis_residential_address_info($param);
			RLog::error('ADDRESS : '.json_encode($data['residential_address']));
			
			// CONTACTS INFO
			$data['contacts_info']			= $this->rm->get_gsis_contacts_info($param);
			
			// EMLOYMENT INFO
			$data['employment_info']		= $this->rm->get_gsis_employment_info($param);
			
			$field                    		= array("DATE_FORMAT(min(employ_start_date), '%m/%d/%Y') AS start_date");
			$table                    		= $this->rm->tbl_employee_work_experiences;
			$where                    		= array();
			$where['employee_id']  			= $param;
			$where['employ_type_flag']  	= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
			$data['start_date']      		= $this->rm->get_reports_data($field, $table, $where, FALSE);

			// DOH ADDRESS
			$data['doh_building']			= $this->rm->get_doh_address(DOH_ADDRESS_BUILDING);
			$data['doh_street']				= $this->rm->get_doh_address(DOH_ADDRESS_STREET);
			$data['doh_subdivision']		= $this->rm->get_doh_address(DOH_ADDRESS_SUBDIVISION);
			$data['doh_barangay']			= $this->rm->get_doh_address(DOH_ADDRESS_BARANGAY);
			$data['doh_municity']			= $this->rm->get_doh_address(DOH_ADDRESS_MUNICITY);
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


/* End of file Gsis_membership_form.php */
/* Location: ./application/modules/main/controllers/reports/hr/Gsis_membership_form.php */