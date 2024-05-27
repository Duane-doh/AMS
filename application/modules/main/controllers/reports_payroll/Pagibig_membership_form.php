<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagibig_membership_form extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$param 								 = $params['employee'];

			$data            					 = array();

			$data['personal_info']				 = $this->rm->get_pagibig_personal_info($param);

			$field                               = array("*");
			$table                               = $this->rm->DB_CORE.'.'.$this->rm->tbl_genders;
			$where                               = array();
			$data['per_params']['gender']        = $this->rm->get_reports_data($field, $table, $where, TRUE);

			$field                               = array("*") ;
			$table                               = $this->rm->tbl_param_civil_status;
			$where                               = array();
			$data['per_params']['civil_status']  = $this->rm->get_reports_data($field, $table, $where, TRUE);

			//FAMILY BACKGROUND DATA
			$field                               = array("relation_first_name, relation_last_name, relation_middle_name, relation_ext_name") ;
			$table                               = $this->rm->tbl_employee_relations;
			$where                               = array();
			$where['employee_id']                = $param;
			$where['relation_type_id']           = FAMILY_SPOUSE;
			$data['spouse']                      = $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			$field                              = array("relation_first_name, relation_last_name, relation_middle_name, relation_ext_name") ;
			$table                              = $this->rm->tbl_employee_relations;
			$where                              = array();
			$where['employee_id']               = $param;
			$where['relation_type_id']          = FAMILY_FATHER;
			$data['father']                     = $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			$field                              = array("relation_first_name, relation_last_name, relation_middle_name, relation_ext_name") ;
			$table                              = $this->rm->tbl_employee_relations;
			$where                              = array();
			$where['employee_id']               = $param;
			$where['relation_type_id']          = FAMILY_MOTHER;
			$data['mother']                     = $this->rm->get_reports_data($field, $table, $where, FALSE);	

			//ADDRESS DATA
			$data['permanent']				 	= $this->rm->get_pagibig_address($param, PERMANENT_ADDRESS);
			$data['residential']				= $this->rm->get_pagibig_address($param, RESIDENTIAL_ADDRESS);
			
			// ID NUMBERS AND CONTACT INFOS
			$data['number_contact']				= $this->rm->get_pagibig_numbers_contacts($param);	
			
			// PRESENT EMPLOYMENT DETAILS
			$data['present_employment']			= $this->rm->get_present_employment_details($param);
			
			// DOH ADDRESS
			$data['doh_building']				= $this->rm->get_doh_address(DOH_ADDRESS_BUILDING);
			$data['doh_street']					= $this->rm->get_doh_address(DOH_ADDRESS_STREET);
			$data['doh_subdivision']			= $this->rm->get_doh_address(DOH_ADDRESS_SUBDIVISION);
			$data['doh_barangay']				= $this->rm->get_doh_address(DOH_ADDRESS_BARANGAY);
			$data['doh_municity']				= $this->rm->get_doh_address(DOH_ADDRESS_MUNICITY);
			$data['doh_zip_code']				= $this->rm->get_doh_address(DOH_ADDRESS_ZIP_CODE);
			
			// HEIRS
			$data['heir']						= $this->rm->get_pagibig_heirs($param);
			
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


/* End of file Pagibig_membership_form.php */
/* Location: ./application/modules/main/controllers/reports/hr/Pagibig_membership_form.php */