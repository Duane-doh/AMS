<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_2306_certificate_of_final_tax_withheld_at_source extends Main_Controller {
	
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
			
			$data['date_from']	= date("mdY", strtotime($params['date_range_from']));
			$data['date_to']	= date("mdY", strtotime($params['date_range_to']));
			
			$year_from		= date("Y", strtotime($params['date_range_from']));
			$year_to		= date("Y", strtotime($params['date_range_to']));
			$month_from		= date("m", strtotime($params['date_range_from']));
			$month_to		= date("m", strtotime($params['date_range_to']));
			
			$data = $this->get_doh_info($data);

			$field                    		 = array('format');
			$table                    		 = $this->rm->tbl_param_identification_types;
			$where                    		 = array();
			$where['identification_type_id'] = TIN_TYPE_ID;
			$data['tin_format']    	  	  	 = $this->rm->get_reports_data($field, $table, $where, FALSE); 

			// PAYER'S INFO 
			$fields 			= array('A.employee_tin tin', 'A.local_address', 'A.local_addr_zip_code loc_zip', 'A.foreign_address', 'A.foreign_addr_zip_code for_zip', 'B.first_name', 'B.last_name', 'LEFT(B.middle_name, 1) middle_name', 'C.employ_position_name');
			$payer_tables 		= array(
				'main'      	=> array(
					'table'     => $this->rm->tbl_form_2307_header,
					'alias'     => 'A',
				),
				't2'      		=> array(
					'table'     => $this->rm->tbl_employee_personal_info,
					'alias'     => 'B',
					'type'      => 'LEFT JOIN',
					'condition' => 'A.employee_id = B.employee_id'
				),
				't3'      		=> array(
					'table'     => $this->rm->tbl_employee_work_experiences,
					'alias'     => 'C',
					'type'      => 'LEFT JOIN',
					'condition' => 'C.employee_id = B.employee_id'
				)
			);
			$where 								= array();
			$where['B.employee_id']				= $params['employee_filtered'];
			$where['C.active_flag']				= YES;
			$data['payer_info']					= $this->rm->get_reports_data($fields, $payer_tables, $where, FALSE);
			
			$fields 							= array('B.nature_payment_2306 nature', 'B.atc_2306 atc', 'B.payment_amount_2306 payment', '(B.tax_withheld_2306) tax');
			$where 								= array();
			$where['A.employee_id']				= $params['employee_filtered'];
			$where['CONCAT(B.year, IF(B.month>9, B.month, CONCAT(0,B.month)))']	= array($value = array($year_from.$month_from, $year_to.$month_to), array("BETWEEN"));
			
			$tables = array(
					'main'      => array(
							'table'     => $this->rm->tbl_form_2307_header,
							'alias'     => 'A',
					),
					't2'      => array(
							'table'     => $this->rm->tbl_form_2307_monthly_details,
							'alias'     => 'B',
							'type'      => 'JOIN',
							'condition' => 'A.form_2307_id = B.form_2307_id'
					)
			);
			
			$data['details']		= $this->rm->get_reports_data($fields, $tables, $where, TRUE);
			
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
	
	
	public function get_doh_info($data){
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
		
		return $data;
	}
	
}


/* End of file Bir_2316_certificate_of_compensation_payment_tax_withheld.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_2316_certificate_of_compensation_payment_tax_withheld.php */