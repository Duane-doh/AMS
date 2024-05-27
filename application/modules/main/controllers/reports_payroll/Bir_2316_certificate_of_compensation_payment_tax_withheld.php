<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_2316_certificate_of_compensation_payment_tax_withheld extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$where = array();
			$data =array();
			
			// STORES 2316 HEADER
			$columns = array('form_2316_id', 'year', 'employee_id', 'employee_name', 'employee_tin', 'rdo_code',
					'registered_address', 'registered_addr_zip_code', 'local_address', 'local_addr_zip_code',
					'foreign_address', 'foreign_addr_zip_code', 'DATE_FORMAT(birth_date,"%m%d%Y")birth_date', 'telephone_num', 'exemption_status',
					'wife_claim_exemption');
			
			$where['year']			= $params['year'];
			$where['employee_id'] 	= $params['employee_filtered'];
			
			$data['header']	= $this->rm->get_2316_header($columns, $where);
			$data['tin'] = $data['header']['employee_tin'];

			// REMOVE "-" FROM TIN
			$header_id		= $data['header']['form_2316_id'];
			
			// STORES 2316 DEPENDENTS
			$where = array();
			$where['form_2316_id'] 	= $header_id;
			$data['dependents']		= $this->rm->get_2316_dependents($where);

			$data['details'] = $this->rm->get_2316_details($where);
			
			// STORES DOH DETAILS
			// ADDRESS
			$building		= $this->rm->get_sysparam_value(DOH_ADDRESS_BUILDING);
			$street			= $this->rm->get_sysparam_value(DOH_ADDRESS_STREET);
			$subdivision	= $this->rm->get_sysparam_value(DOH_ADDRESS_SUBDIVISION);
			$barangay		= $this->rm->get_sysparam_value(DOH_ADDRESS_BARANGAY);
			$doh_municity	= $this->rm->get_sysparam_value(DOH_ADDRESS_MUNICITY);
			
			$data['doh_add'] 	= $building['sys_param_value'] . " " . $street['sys_param_value'] . ", " . $subdivision['sys_param_value'] . ", " . $barangay['sys_param_value'] . ", " . $doh_municity['sys_param_value'];
			
			// ZIP CODE
			$doh_zip_code		= $this->rm->get_sysparam_value(DOH_ADDRESS_ZIP_CODE);
			$data['doh_zip_code'] = $doh_zip_code['sys_param_value'];
			
			// TIN
			$result_tin	= $this->rm->get_sysparam_value(DOH_TIN);
			$result_tin = str_replace("-","", $result_tin);
			$data['doh_tin'] = $result_tin['sys_param_value'];
			// REMOVE "-" FROM TIN
			$data['doh_tin'] = str_replace('-', '', $data['doh_tin']);
			
			
			// STORES PREVIOUS EMPLOYER DETAILS
			$bir_id					= $this->rm->get_sysparam_value(PARAM_DEDUCTION_ST_BIR_ID);
			$prev_employer_tin 		= $this->rm->get_sysparam_value(PARAM_TAX_PREV_EMPLOYER_TIN);
			$prev_employer_name		= $this->rm->get_sysparam_value(PARAM_TAX_PREV_EMPLOYER_NAME);
			$prev_employer_address 	= $this->rm->get_sysparam_value(PARAM_TAX_PREV_EMPLOYER_ADDR);
				
			$where = array();
			$where['A.employee_id'] = $params['employee_filtered'];
			$where['A.deduction_id'] = $bir_id['sys_param_value'];
			$where['B.other_deduction_detail_id'] = $prev_employer_tin['sys_param_value'];
			$data['prev_employer_tin'] = $this->rm->get_previous_employer_detail($where);
			// REMOVE "-" FROM TIN
			$data['prev_employer_tin'] = str_replace('-', '', $data['prev_employer_tin']);
			
			$where['B.other_deduction_detail_id'] = $prev_employer_name['sys_param_value'];
			$data['prev_employer_name'] = $this->rm->get_previous_employer_detail($where);
			
			$where['B.other_deduction_detail_id'] = $prev_employer_address['sys_param_value'];
			$data['prev_employer_address'] = $this->rm->get_previous_employer_detail($where);

			// SIGNATORIES
			$data['signatory_a'] = $this->cm->get_report_signatory_details($params['signatory_ca1']);
			$data['signatory_b'] = $this->cm->get_report_signatory_details($params['signatory_ca2']);


			$field_date             	= array("MAX(employ_start_date) employ_start_date");
			$table_date             	= $this->rm->tbl_employee_work_experiences;
			$where_date             	= array();
			$where_date['employee_id']  = $params['employee_filtered'];
			$employ_start_date      	= $this->rm->get_reports_data($field_date, $table_date, $where_date, FALSE);

			$tables 		= array(
				'main'      => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'A'
				),
				't2'        => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'B.employee_id = A.employee_id'
			 	)
			);	

			$field               		  = array("CONCAT(A.first_name, ' ', LEFT(A.middle_name, 1), '. ', A.last_name, IF(A.ext_name='' OR ISNULL(A.ext_name), '', CONCAT(', ', A.ext_name))) name", "B.employ_end_date", "B.employ_start_date");
			$where               		  = array();
			$where['A.employee_id']		  = $params['employee_filtered'];
			$where['B.employ_start_date'] = $employ_start_date['employ_start_date'];	
			$employee    		  		  = $this->rm->get_reports_data($field, $tables, $where, FALSE);
			$data['employee']    		  = $employee;


			$data['period_from']	= "0101";
			$start_year 			= date('Y', strtotime($employee['employ_start_date']));
			if($start_year == $params['year'])
			{
				$data['period_from']= date('md', strtotime($employee['employ_start_date']));
			}
			else
			{
				$data['period_from']= "0101";
			}

			// STORES PERIOD
			$data['period_to']		= "1231";
			if(!EMPTY($params['month']))
			{				
				$where['month']		=  $params['month'];
				$num_days 			= cal_days_in_month ( CAL_GREGORIAN, $params['month'], $params['year']);
				$month				= ($params['month']>9) ? $params['month'] : "0".$params['month'];		
				$data['period_to']	= $month . $num_days;				
			}
			if(!EMPTY($employee['employ_end_date']))
			{				
				$data['period_to']	= date('md', strtotime($employee['employ_end_date']));			
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

}


/* End of file Bir_2316_certificate_of_compensation_payment_tax_withheld.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_2316_certificate_of_compensation_payment_tax_withheld.php */