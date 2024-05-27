<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Philhealth_membership_form extends Main_Controller {
	
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

			$data								 = array();
			
			// PERSONAL INFO
			$data['personal_info']				 = $this->rm->get_philhealth_personal_info($param);
			
			// PERMANENT ADDRESS
			$data['permanent_address']			 = $this->rm->get_philhealth_permanent_address_info($param);
			
			// CONTACTS INFO
			$data['contacts_info']				 = $this->rm->get_philhealth_contacts_info($param);
			
			// FAMILY BACKGROUND DATA		
			$field                               = array("philhealth_number, relation_first_name, relation_last_name, relation_middle_name, relation_ext_name, DATE_FORMAT(relation_birth_date, '%m-%d-%Y') AS relation_birth_date, pwd_flag, relation_gender_code") ;
			
			$table                               = $this->rm->tbl_employee_relations;
			$where                               = array();
			$where['employee_id']                = $param;
			$where['relation_type_id']           = FAMILY_SPOUSE;
			$data['spouse']                      = $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			$table                               = $this->rm->tbl_employee_relations;
			$where                               = array();
			$where['employee_id']                = $param;
			$where['relation_type_id']           = FAMILY_FATHER;
			$data['father']                      = $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			$table                               = $this->rm->tbl_employee_relations;
			$where                               = array();
			$where['employee_id']                = $param;
			$where['relation_type_id']           = FAMILY_MOTHER;
			$data['mother']                      = $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			
			$table								= $this->rm->tbl_employee_relations;
			$where								= array();
			$where['employee_id']				= $param;
			$where['relation_type_id']			= FAMILY_CHILD;
			$where['philhealth_flag']			= YES;
			$data['child']						= $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			// FORMAL ECONOMY
			$data['formal_economy']				= $this->rm->get_formal_economy_info($param);
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


/* End of file Philhealth_membership_form.php */
/* Location: ./application/modules/main/controllers/reports/hr/Philhealth_membership_form.php */