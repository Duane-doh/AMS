<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expanded_withholding_tax_and_government_money_payment extends Main_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data				= array();
			
			// STORES OFFICE NAME
			if(!EMPTY($params['office_list']))
			{
				$fields   = array('A.name');
				$tables   		=  array(
						'main' 			=> array(
								'table' 	=> $this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
								'alias' 	=> 'A'
						),
						't1'			=> array(
								'table' 	=> $this->rm->tbl_param_offices,
								'alias'		=> 'B',
								'type'  	=> 'JOIN',
								'condition' => 'A.org_code = B.org_code'
						)
				);
				$where   = array('B.office_id' => $params['office_list']);
				$data['office_name'] = $this->rm->get_reports_data($fields, $tables, $where, FALSE);
			}
			// PERIOD TEXT
			$data['payroll_period_text'] = $params['payroll_period_text'];
			
			// STORES PAYROLL TYPE
			$payroll_type					= $this->rm->get_sysparam_value(SYS_PARAM_TYPE_DEDUCTION_ST_BIR_EWT_ID);
						
			// STORES HEADER
			$where                               = array();
			$where['A.attendance_period_hdr_id'] = $params['payroll_period'];
			if(!EMPTY($params['office_list']))
			{
				$where['B.office_id'] 					= $params['office_list'];
			}
			$data['header']						= $this->get_header($where);
			
			//EMPLOYEE IDS
// 			$employee_ids 	= array();
// 			foreach($data['header'] as $hdr)
// 			{
// 				$employee_ids[] = $hdr['employee_id'];
// 			}
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
	
	// HEADER
	public function get_header($where)
	{
		
		$table_eligibility = $this->rm->tbl_employee_eligibility;
		$tbl_eligibility 	= <<<EOS
					(SELECT F.employee_id,GROUP_CONCAT(DISTINCT IF(UPPER(F.license_no) = 'NA' ,null,F.license_no)) license_no
					FROM $table_eligibility F
					GROUP BY F.employee_id)
EOS;
		$fields				= array("C.agency_employee_id", "E.identification_value", "C.last_name", "C.first_name", "B.total_income net_pay", 
									"SUM(IF (D.deduction_id = ".DEDUC_BIR_VAT.", IFNULL(D.amount, 0), 0)) gmp_amount",
									"SUM(IF (D.deduction_id = ".DEDUC_BIR_EWT.", IFNULL(D.amount, 0), 0)) ewt_amount",
								    "B.office_name", "F.license_no", "G.format");
		$tables		 		= array(
				'main'      => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'A'
				),
				't2'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'B.payroll_summary_id = A.payroll_summary_id'
				),
				't3'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'C.employee_id = B.employee_id'
				),
				't4'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'D.payroll_hdr_id = B.payroll_hdr_id AND D.deduction_id IN ('.DEDUC_BIR_EWT.','.DEDUC_BIR_VAT.')'
				),
				't5'        => array(
						'table'     => $this->rm->tbl_employee_identifications,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.employee_id = C.employee_id AND E.identification_type_id = '.TIN_TYPE_ID
				),
				't6'        => array(
						'table'     => $this->rm->tbl_param_identification_types,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.identification_type_id = G.identification_type_id'
				),
				// 't7'        => array(
				// 		'table'     => $this->rm->tbl_employee_other_info,
				// 		'alias'     => 'F',
				// 		'type'      => 'LEFT JOIN',
				// 		'condition' => 'F.employee_id = C.employee_id AND F.other_info_type_id = '.OTHER_INFO_TYPE_PROFESSIONAL
				// ),
				't7'        => array(
						'table'     => $tbl_eligibility,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.employee_id = C.employee_id'
				)
		);
		$order_by					= array('employee_name' => 'ASC');
		$group_by					= array('B.employee_id');
		return $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
	}
}


/* End of file Atm_alpha_list.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Atm_alpha_list.php */