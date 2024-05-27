<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ENGAS_file_for_uploading extends Main_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data                  = array();
			
			// GET PAYROLL PERIOD
			$data 		= array();	
			$field 		= array("CONCAT(DATE_FORMAT(date_from,'%M %d'), ' - ', DATE_FORMAT(date_to,'%d, %Y')) payroll_period");
			$table 		= $this->rm->tbl_attendance_period_hdr;
			$where 		= array();
			$where['attendance_period_hdr_id']	= $params['payroll_period'];
			$data['period_detail']				= $this->rm->get_reports_data($field, $table, $where, FALSE);
			
			// GET HEADER DATA
			$tables 		= array(

				'main'      => array(
					'table'     => $this->rm->tbl_attendance_period_hdr,
					'alias'     => 'A'
				),
				't2'        => array(
					'table'     => $this->rm->tbl_payout_summary,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.attendance_period_hdr_id = B.attendance_period_hdr_id'
			 	),
				't3'        => array(
					'table'     => $this->rm->tbl_payout_header,
					'alias'     => 'C',
					'type'      => 'JOIN',
					'condition' => 'B.payroll_summary_id  = C.payroll_summary_id'
			 	),
				't4'        => array(
					'table'     => $this->rm->tbl_employee_personal_info,
					'alias'     => 'D',
					'type'      => 'JOIN',
					'condition' => 'C.employee_id = D.employee_id'
			 	), 
				't5'        => array(
					'table'     => $this->rm->tbl_employee_responsibility_codes,
					'alias'     => 'E',
					'type'      => 'LEFT JOIN',
					'condition' => 'D.employee_id = E.employee_id AND
									IF(E.start_date IS NULL, current_date(), E.start_date) <= A.date_to AND 
									IF(E.end_date IS NULL, current_date(), E.end_date) >= A.date_to'
			 	), 
				't6'        => array(
					'table'     => $this->rm->tbl_payout_details,
					'alias'     => 'F',
					'type'      => 'LEFT JOIN',
					'condition' => 'F.payroll_hdr_id = C.payroll_hdr_id AND
									F.compensation_id = 1'
			 	)			
			);
			
			$where = array();
			$where['A.attendance_period_hdr_id'] = $params['payroll_period'];
			$data['header'] = $this->get_header($tables, $where);

			//GET PAYROLL HDR IDs
			$field	 = array('GROUP_CONCAT(DISTINCT C.payroll_hdr_id) payroll_hdr_ids');
			$phdr_ids = $this->rm->get_reports_data($field, $tables, $where, FALSE);
			$phdr_ids = explode(',', $phdr_ids['payroll_hdr_ids']);
			
			//GET EMPLOYEE IDs
			$field	 = array('GROUP_CONCAT(DISTINCT C.employee_id) employee_ids');
			$emp_ids = $this->rm->get_reports_data($field, $tables, $where, FALSE);
			$emp_ids = explode(',', $emp_ids['employee_ids']);

			// GET DEDUCTIONS
			$data['deductions'] = $this->get_deductions($phdr_ids, $data['header']);
			
			// GET IDENTIFICATIONS
			$data['identifications'] = $this->get_identifications($emp_ids);
				
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

	public function get_header($tables, $where)
	{
		$select_fields  = array('C.employee_id', 'C.office_name', 'C.salary_grade', 'F.orig_amount gross_salary', 'C.total_income net_salary', 'C.net_pay', 'D.biometric_pin', 'D.last_name', 'D.first_name', 'D.middle_name', 'D.ext_name', 'E.responsibility_center_code respcode');

		return $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('D.last_name' => 'ASC'));
	}
	
	public function get_deductions($phdr_ids, $header)
	{
		$field = array('A.employee_id', 'B.compensation_id', 'B.deduction_id', 'B.amount');
		$tables 		= array(

				'main'      => array(
					'table'     => $this->rm->tbl_payout_header,
					'alias'     => 'A'
				),
				't2'        => array(
					'table'     => $this->rm->tbl_payout_details,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.payroll_hdr_id = B.payroll_hdr_id AND
									B.compensation_id IS NULL'
			 	)			
			);
		$where = array();
		$where['A.payroll_hdr_id']	= array($phdr_ids, array('IN'));

		$deductions = $this->rm->get_reports_data($field, $tables, $where, TRUE);

		foreach ($deductions as $deduction)
		{
			switch($deduction['deduction_id']) {
				case DEDUC_BIR_EWT:
					$data_deductions[$deduction['employee_id']]['ewt'] = $deduction['amount'];
					break;
				case DEDUC_BIR_VAT:
					$data_deductions[$deduction['employee_id']]['gmp'] = $deduction['amount'];
					break;
				case DEDUC_SSS:
					$data_deductions[$deduction['employee_id']]['sss'] = $deduction['amount'];
					break;
				case DEDUC_HMDF1_JO:
					$data_deductions[$deduction['employee_id']]['hmdf'] = $deduction['amount'];
					break;
				case DEDUC_PHILHEALTH:
					$data_deductions[$deduction['employee_id']]['phic'] = $deduction['amount'];
					break;
				case DEDUC_HMDF2_JO:
					$data_deductions[$deduction['employee_id']]['hmdf2'] = $deduction['amount'];
					break;
				case DEDUC_OVERPAY_JO:
					$data_deductions[$deduction['employee_id']]['overpay'] = $deduction['amount'];
					break;
			}
		}
		return $data_deductions;
	}
	
	public function get_identifications($emp_ids)
	{
		$where  = array();
		$where['employee_id']	= array($emp_ids, array('IN'));

		//GET PRC LICENSE No.
		$field  = array('employee_id', 'license_no');
		$table = $this->rm->tbl_employee_eligibility;
		
		$eligibility_ids = $this->rm->get_reports_data($field, $table, $where, TRUE);
		
		foreach ($eligibility_ids as $eligibility_id)
		{
			if(isset($eligibility_id['license_no']))
			$data_identifications[$eligibility_id['employee_id']]['license_no'] = $eligibility_id['license_no'];
		}

		//GET TAGGED DEDUCTION IDS
		$field  = array('employee_id', 'deduction_id', 'other_deduction_detail_value');
		$tables 		= array(

				'main'      => array(
					'table'     => $this->rm->tbl_employee_deductions,
					'alias'     => 'A'
				),
				't2'        => array(
					'table'     => $this->rm->tbl_employee_deduction_other_details,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.employee_deduction_id = B.employee_deduction_id'
			 	)			
			);
		
		$deduc_ids = $this->rm->get_reports_data($field, $tables, $where, TRUE);
		
		foreach ($deduc_ids as $deduc_id)
		{
			switch($deduc_id['deduction_id']) {
				case DEDUC_BIR_EWT:
					$data_identifications[$deduc_id['employee_id']]['tin'] = $deduc_id['other_deduction_detail_value'];
					break;                                                            
				case DEDUC_BIR_VAT:                                                   
					$data_identifications[$deduc_id['employee_id']]['tin'] = $deduc_id['other_deduction_detail_value'];
					break;                                                            
				case DEDUC_SSS:                                                     
					$data_identifications[$deduc_id['employee_id']]['sss'] = $deduc_id['other_deduction_detail_value'];
					break;                         
				case DEDUC_HMDF1_JO:              
					$data_identifications[$deduc_id['employee_id']]['hmdf'] = $deduc_id['other_deduction_detail_value'];
					break;                         
				case DEDUC_HMDF2_JO:              
					$data_identifications[$deduc_id['employee_id']]['hmdf2'] = $deduc_id['other_deduction_detail_value'];
					break;                         
				case DEDUC_PHIC:           
					$data_identifications[$deduc_id['employee_id']]['phic'] = $deduc_id['other_deduction_detail_value'];
					break;                         
			}
		}
		
		//GET PDS IDS
		$field  = array('employee_id', 'identification_type_id', 'identification_value');
		$tables = $this->rm->tbl_employee_identifications;

		$identifications = $this->rm->get_reports_data($field, $tables, $where, TRUE);

		foreach ($identifications as $identification)
		{
			switch($identification['identification_type_id']) {
				case TIN_TYPE_ID:
					if(!ISSET($data_identifications[$identification['employee_id']]['tin']) AND ($identification['identification_value'] != 'NA'))
					$data_identifications[$identification['employee_id']]['tin'] = $identification['identification_value'];
					break;
				case SSS_TYPE_ID:
					if(!ISSET($data_identifications[$identification['employee_id']]['sss']) AND ($identification['identification_value'] != 'NA'))
					$data_identifications[$identification['employee_id']]['sss'] = $identification['identification_value'];
					break;
				case PAGIBIG_TYPE_ID:
					if(!ISSET($data_identifications[$identification['employee_id']]['hmdf']) AND ($identification['identification_value'] != 'NA'))
					$data_identifications[$identification['employee_id']]['hmdf'] = $identification['identification_value'];
					break;
				case PHILHEALTH_TYPE_ID:
					if(!ISSET($data_identifications[$identification['employee_id']]['phic']) AND ($identification['identification_value'] != 'NA'))
					$data_identifications[$identification['employee_id']]['phic'] = $identification['identification_value'];
					break;
				case BANKACCT_TYPE_ID:
					$data_identifications[$identification['employee_id']]['atm'] = $identification['identification_value'];
					break;
			}
		}

		return $data_identifications;
	}
	
	
	
}


/* End of file ENGAS_file_for_uploading.php */
/* Location: ./application/modules/main/controllers/reports/payroll/ENGAS_file_for_uploading.php */