<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_2307_certificate_of_creditable_tax_withheld_at_source extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data 	= array();
			
			// PERIODS
			$data 	= $this->get_periods($params['quarter'], $params['year_only']);
			
			$data 	= $this->get_doh_info($data);

			$where 					= array();
			$where['A.quarter']		= $params['quarter'];
			$where['A.year']		= $params['year_only'];
			$where['B.employee_id']	= $params['employee_filtered'];
			
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
			$data['payer_info']	= $this->rm->get_reports_data($fields, $payer_tables, $where, FALSE);
			
			$fields 			= array('B.income_payment_ewt ewt', 'B.atc', 'B.payment_mo_1 pay_1', 'B.payment_mo_2 pay_2', 'B.payment_mo_3 pay_3', 'B.payment_total', 'B.tax_withheld');
			$tables 			= array(
				'main'      	=> array(
					'table'     => $this->rm->tbl_form_2307_header,
					'alias'     => 'A',
				),
				't2'      		=> array(
					'table'     => $this->rm->tbl_form_2307_details,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.form_2307_id = B.form_2307_id'
				)
			);

			$where 					= array();
			$where['A.quarter']		= $params['quarter'];
			$where['A.year']		= $params['year_only'];
			$where['A.employee_id']	= $params['employee_filtered'];	
			$where['B.tax_withheld']= array('0.00', array("!="));			
			$data['details']	= $this->rm->get_reports_data($fields, $tables, $where, TRUE);

			$field                    		 = array('format');
			$table                    		 = $this->rm->tbl_param_identification_types;
			$where                    		 = array();
			$where['identification_type_id'] = TIN_TYPE_ID;
			$data['tin_format']    	  	  	 = $this->rm->get_reports_data($field, $table, $where, FALSE); 
			
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
	
	public function get_periods($quarter, $year)
	{
		$data = array();
		switch ($quarter)
		{
			case 1:
				$mon_fr = "01";
				$mon_to = "03";
				$day_fr = "01";
				$day_to = cal_days_in_month ( CAL_GREGORIAN, 3, $year);
				break;
			case 2:
				$mon_fr = "04";
				$mon_to = "06";
				$day_fr = "01";
				$day_to = cal_days_in_month ( CAL_GREGORIAN, 6, $year);
				break;
			case 3:
				$mon_fr = "07";
				$mon_to = "09";
				$day_fr = "01";
				$day_to = cal_days_in_month ( CAL_GREGORIAN, 9, $year);
				break;
			case 4:
				$mon_fr = "10";
				$mon_to = "12";
				$day_fr = "01";
				$day_to = cal_days_in_month ( CAL_GREGORIAN, 12, $year);
				break;
		}
		
		$data['period_from'] = $mon_fr . $day_fr . $year[2] . $year[3]; 
		$data['period_to'] = $mon_to . $day_to . $year[2] . $year[3];
		
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