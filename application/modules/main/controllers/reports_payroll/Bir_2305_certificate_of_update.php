<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_2305_certificate_of_update extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data = array();
			
			// STORES 2316 DETAILS
			$result_2316 = $this->get_2316_details($params);
			$data = array_merge($data, $result_2316);
			
			// STORES SOME PERSONAL DETAILS
			$personal_dtl = $this->get_personal_details($params['employee_filtered']);
			$data = array_merge($data, $personal_dtl);
			
			// STORES CLAIM EXCEPTION DETAILS
			$exception_dtl = $this->get_claim_exception_details($params['employee_filtered']);
			$data = array_merge($data, $exception_dtl);
			
			// STORES SOME SPOUSE DETAILS
			$spouse_dtl = $this->get_spouse_details($params['employee_filtered']);
			$data = array_merge($data, $spouse_dtl);
			
			// STORES DOH DETAILS
			$doh_dtl = $this->get_doh_details();
			$data = array_merge($data, $doh_dtl);
				
			// STORES PREVIOUS EMPLOYER DETAILS
			$prevoius_employer_dtl = $this->get_previous_employer_details($params['employee_filtered']);
			$data = array_merge($data, $prevoius_employer_dtl);
			
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
	
	public function get_2316_details($params)
	{
		$data = array();
		
		// STORES 2316 HEADER
		$columns = array('form_2316_id', 'year', 'employee_id', 'employee_name', 'employee_tin', 'rdo_code',
				'registered_address', 'registered_addr_zip_code', 'local_address', 'local_addr_zip_code',
				'foreign_address', 'foreign_addr_zip_code', 'DATE_FORMAT(birth_date,"%m%d%Y")birth_date', 'telephone_num', 'exemption_status',
				'wife_claim_exemption');
		
		$where['year']			= $params['year_only'];
		$where['employee_id'] 	= $params['employee_filtered'];
		
		$data['header']	= $this->rm->get_2316_header($columns, $where);
		$data['header']['employee_tin'] = str_replace('-', '', $data['header']['employee_tin']);
			
		$header_id		= $data['header']['form_2316_id'];
		
		// STORES 2316 DEPENDENTS
		$dependents = $this->get_2316_dependents($header_id);
		$data = array_merge($data, $dependents);
		
		return $data;
	}
	
	
	public function get_2316_dependents($header_id)
	{
		$data = array();
		
		$fields = array('B.relation_first_name fn', 'B.relation_middle_name mn', 'B.relation_last_name ln', 'B.pwd_flag', 'DATE_FORMAT(A.birth_date,"%m%d%Y") birth_date');
		$tables = array(
				'main'      => array(
						'table'     => $this->rm->tbl_form_2316_dependents,
						'alias'     => 'A',
				),
				't2'      => array(
						'table'     => $this->rm->tbl_employee_relations,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_relation_id = B.employee_relation_id'
				)
		);
			
		$where = array();
		$where['form_2316_id'] 	= $header_id;
		
		$data['dependents']	= $this->rm->get_reports_data($fields, $tables, $where, TRUE);
		
		$where['relation_type_id']	= FAMILY_CHILD;
		$data['child_dependents']	= $this->rm->get_reports_data($fields, $tables, $where, TRUE);
		
		
		return $data;
	}
	
	public function get_doh_details()
	{
		$data = array();
		$where = array();
		
		// ADDRESS
		$building		= $this->rm->get_sysparam_value(DOH_ADDRESS_BUILDING);
		$street			= $this->rm->get_sysparam_value(DOH_ADDRESS_STREET);
		$subdivision	= $this->rm->get_sysparam_value(DOH_ADDRESS_SUBDIVISION);
		$barangay		= $this->rm->get_sysparam_value(DOH_ADDRESS_BARANGAY);
		$municity	= $this->rm->get_sysparam_value(DOH_ADDRESS_MUNICITY);
		
		$data['doh_building']		= $building['sys_param_value'];
		$data['doh_street']			= $street['sys_param_value'];
		$data['doh_subdivision']	= $subdivision['sys_param_value'];
		$data['doh_barangay']		= $barangay['sys_param_value'];
		$data['doh_municity']		= $municity['sys_param_value'];
		
		// ZIP CODE
		$zip_code	= $this->rm->get_sysparam_value(DOH_ADDRESS_ZIP_CODE);
		$data['doh_zip_code']	= $zip_code['sys_param_value'];
		
		// RDO CODE
		$rdo_code	= $this->rm->get_sysparam_value(PARAM_DOH_RDO_CODE);
		$data['doh_rdo_code']	= $rdo_code['sys_param_value'];
		
		// TIN
		$result_tin	= $this->rm->get_sysparam_value(DOH_TIN);
		// REMOVE "-" FROM TIN
		$result_tin = str_replace("-","", $result_tin);
		$data['doh_tin'] = $result_tin['sys_param_value'];
		
		return $data;
	}
	
	
	public function get_personal_details($emp_id)
	{
		$data = array();
		$where = array();
		
		$fields 				= array("CONCAT(first_name, ' ', LEFT(middle_name, (1)), '. ', last_name, IF(ext_name='' OR ISNULL(ext_name), '', CONCAT(', ', ext_name))) employee_name", 'gender_code', 'civil_status_id');
		$table					= $this->rm->tbl_employee_personal_info;
		$where['employee_id']	= $emp_id;
		$data['personal_info']	= $this->rm->get_reports_data($fields, $table, $where, FALSE);
		
		return $data;
	}
	
	public function get_spouse_details($emp_id)
	{
		$data = array();
		$where = array();
	
		$fields 					= array('relation_first_name fn', 'relation_middle_name mn', 'relation_last_name ln', 'relation_gender_code gender', 'relation_employment_status_id rel_employment_stat', 'relation_company');
		$table						= $this->rm->tbl_employee_relations;
		$where['employee_id']		= $emp_id;
		$where['relation_type_id']	= FAMILY_SPOUSE;
		$data['spouse_info']		= $this->rm->get_reports_data($fields, $table, $where, FALSE);
	
		return $data;
	}
	
	public function get_claim_exception_details($emp_id)
	{
		$data = array();
	
		$bir_id			= $this->rm->get_sysparam_value(PARAM_DEDUCTION_ST_BIR_ID);
		$husband_ex		= $this->rm->get_sysparam_value(PARAM_TAX_HUSBAND_EXCEPTION);
		$wife_ex		= $this->rm->get_sysparam_value(PARAM_TAX_WIFE_EXCEPTION);
		
		$fields = array('A.employee_deduction_id');
		$tables = array(
				'main'      => array(
						'table'     => $this->rm->tbl_employee_deductions,
						'alias'     => 'A',
				),
				't2'      => array(
						'table'     => $this->rm->tbl_employee_deduction_other_details,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.employee_deduction_id = B.employee_deduction_id'
				)
		);
			
		$where = array();
		$where['A.employee_id'] = $emp_id;
		$where['A.deduction_id'] = $bir_id['sys_param_value'];
	
		// HUSBAND EXCEPTION
		$where['B.other_deduction_detail_id'] = $husband_ex['sys_param_value'];
		$data['husband_exception']	= $this->rm->get_reports_data($fields, $tables, $where, FALSE);
		
		// WIFE EXCEPTION
		$where['B.other_deduction_detail_id'] = $wife_ex['sys_param_value'];
		$data['wife_exception'] = $this->rm->get_reports_data($fields, $tables, $where, FALSE);
			
		return $data;
	}
	
	public function get_previous_employer_details($emp_id)
	{
		$data =array();
	
		$bir_id					= $this->rm->get_sysparam_value(PARAM_DEDUCTION_ST_BIR_ID);
		$prev_employer_tin 		= $this->rm->get_sysparam_value(PARAM_TAX_PREV_EMPLOYER_TIN);
		$prev_employer_name		= $this->rm->get_sysparam_value(PARAM_TAX_PREV_EMPLOYER_NAME);
			
		$where = array();
		$where['A.employee_id'] = $emp_id;
		$where['A.deduction_id'] = $bir_id['sys_param_value'];
		$where['B.other_deduction_detail_id'] = $prev_employer_tin['sys_param_value'];
		$data['prev_employer_tin'] = $this->rm->get_previous_employer_detail($where);
		// REMOVE "-" FROM TIN
		$data['prev_employer_tin'] = str_replace('-', '', $data['prev_employer_tin']);
	
		$where['B.other_deduction_detail_id'] = $prev_employer_name['sys_param_value'];
		$data['prev_employer_name'] = $this->rm->get_previous_employer_detail($where);
	
		return $data;
	}
	
}


/* End of file Bir_2316_certificate_of_compensation_payment_tax_withheld.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_2316_certificate_of_compensation_payment_tax_withheld.php */