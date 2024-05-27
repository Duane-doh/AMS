<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disbursement_voucher extends Main_Controller {
	
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

			$data = array();
			$field = array("A.payroll_summary_id","B.payroll_hdr_id","A.voucher_description","A.voucher_footer","B.employee_name","C.agency_employee_id","B.total_income","B.total_deductions","B.net_pay", "D.identification_value", "E.responsibility_center_code") ;
			$table = array(
					'main'	=> array(
						'table' =>	$this->rm->tbl_vouchers,
						'alias' => 'A'
					),
					't1' 	=> array(
		 				'table' => $this->rm->tbl_payout_header,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.payroll_summary_id = B.payroll_summary_id'
					),
					't2' 	=> array(
						'table' => $this->rm->tbl_employee_personal_info,
						'alias' => 'C',
						'type'  => 'JOIN',
						'condition' => 'B.employee_id = C.employee_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_employee_identifications,
							'alias' 	=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'C.employee_id = D.employee_id AND D.identification_type_id = ' . BANKACCT_TYPE_ID
					),
					't4' 	=> array(
						'table' => $this->rm->tbl_param_offices,
						'alias' => 'E',
						'type'  => 'JOIN',
						'condition' => 'B.office_id = E.office_id'
					)
			);
			$where                 = array();
			$where['A.voucher_id'] = $params['voucher'];
			$data['voucher_info']  = $this->rm->get_reports_data($field, $table, $where, FALSE);	
			
			$payroll_hdr_id     = $data['voucher_info']['payroll_hdr_id'];
			$payroll_summary_id = $data['voucher_info']['payroll_summary_id'];

			$field = array("A.amount","B.compensation_name") ;
			$table = array(
					'main'	=> array(
						'table' =>	$this->rm->tbl_payout_details,
						'alias' => 'A'
					),
					't1' 	=> array(
		 				'table' => $this->rm->tbl_param_compensations,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.compensation_id = B.compensation_id'
					)
			);
			$where                 = array();
			$where['A.payroll_hdr_id'] = $payroll_hdr_id;
			$data['compensations']  = $this->rm->get_reports_data($field, $table, $where, TRUE);	

			$field = array("A.amount","B.deduction_name") ;
			$table = array(
					'main'	=> array(
						'table' =>	$this->rm->tbl_payout_details,
						'alias' => 'A'
					),
					't1' 	=> array(
		 				'table' => $this->rm->tbl_param_deductions,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.deduction_id = B.deduction_id'
					)
			);
			$where                 = array();
			$where['A.payroll_hdr_id'] = $payroll_hdr_id;
			$data['deductions']  = $this->rm->get_reports_data($field, $table, $where, TRUE);	
			
			

			$field                       = array("certified_by","approved_by","certified_cash_by") ;
			$table                       = $this->rm->tbl_payout_summary;
			$where                       = array();
			$where['payroll_summary_id'] = $payroll_summary_id;
			$voucher_details             = $this->rm->get_reports_data($field, $table, $where, FALSE);	
			
			$data['com_count'] = count($data['compensations']);
			$data['ded_count'] = count($data['deductions']);
			
			
			$data['count'] = ($data['com_count']>$data['ded_count']) ? $data['com_count']:$data['ded_count'];
			
			
			$com_total = 0;
			
			foreach ($data['compensations'] as $com)
			{
				$data['com_total'] = $data['com_total'] + $com['amount'];
			}
			
			$ded_total = 0;
			foreach ($data['deductions'] as $com)
			{
				$data['ded_total'] = $data['ded_total'] + $com['amount'];
			}
			
			$data['amount_due']	= $data['com_total'] - $data['ded_total'];
			
			
			if($params['signatory_a'])
				$data['signatory_a']			= $this->cm->get_report_signatory_details($params['signatory_a']);

			if($params['signatory_b'])
				$data['signatory_b']			= $this->cm->get_report_signatory_details($params['signatory_b']);

			if($params['signatory_c'])
				$data['signatory_c']			= $this->cm->get_report_signatory_details($params['signatory_c']);


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

	public function get_voucher_dropdown()
	{
		
		$list        = array();
		
		$params      = get_params();
		$employee_id = $params['employee'];

		$field = array("A.voucher_id","A.voucher_description");
		$table = array(
				'main'	=> array(
					'table' =>	$this->rm->tbl_vouchers,
					'alias' => 'A'
				),
				't1' 	=> array(
					'table' => $this->rm->tbl_payout_header,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.payroll_summary_id = B.payroll_summary_id'
				)
		);
		$where                  = array();
		$where['B.employee_id'] = $employee_id;
		$vouchers               = $this->rm->get_reports_data($field, $table, $where, TRUE);
		
		if(!EMPTY($vouchers ))
		{
			foreach ($vouchers  as $aRow):
				$list[] = array(
								"value" => $aRow["voucher_id"],
								"text" => $aRow['voucher_description']
						);
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}
}


/* End of file Disbursement_voucher.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Disbursement_voucher.php */