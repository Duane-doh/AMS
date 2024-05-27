<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees_paid_by_voucher extends Main_Controller {
	
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
			$data 			= array();
			$where 			= array();
			$year			= $params['year'];
			$month			= (($params['month'] > 9) ? $params['month'] : "0".$params['month']);
			$year_month		= $year.$month;
			$reviewed_by	= $params['cert_by'];
			$prepared_by	= $params['prep_by'];
			
			
			$data['year']		= $params['year'];
			$data['month_text']	= strtoupper($params['month_text']);
			
			
			// COMMON TABLES
			$tables = array(
					'main'      => array(
							'table'     => $this->rm->tbl_vouchers,
							'alias'     => 'A',
					),
					't2'      => array(
							'table'     => $this->rm->tbl_payout_header,
							'alias'     => 'B',
							'type'      => 'JOIN',
							'condition' => 'A.payroll_summary_id = B.payroll_summary_id'
					),
					't3'      => array(
							'table'     => $this->rm->tbl_payout_details,
							'alias'     => 'C',
							'type'      => 'JOIN',
							'condition' => 'B.payroll_hdr_id = C.payroll_hdr_id'
					)
			);
			
			$where['EXTRACT(YEAR_MONTH FROM A.payment_date)']	= $year_month;
			if(!EMPTY($params['office_list']))
			{
				$where['B.office_id']	= $params['office_list'];
				
				$fields					= array('office_name');
				$office					= $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, $group_by);
				$data['office_name']	= $office['office_name'];
			}
			
			// EMPLOYEES
			$data['employees']			= $this->get_employees($tables, $where);
			// COMPENSATION HEADERS
			$data['com_header']			= $this->get_compensation_headers($tables, $where);
			
			// DEDUCTION HEADERS 
			$data['ded_header']			= $this->get_deduction_headers($tables, $where);

			// COMPENSATIONS
			$ctr 						= 0;
			$fields						= array('SUM(C.amount) amount');
			$where_com['EXTRACT(YEAR_MONTH FROM A.payment_date)']	= $year_month;
			$group_by					= array('B.employee_id', 'C.compensation_id');
			foreach($data['employees'] as $emp)
			{
				$com_total 								= 0;
				$data['results'][$ctr]['emp_name']		= $emp['full_name'];
				$data['results'][$ctr]['voucher_desc']	= $emp['voucher_description'];
				$data['results'][$ctr]['check_date']	= $emp['payment_date'];
				$data['results'][$ctr]['check_no']		= $emp['payment_details'];
				foreach($data['com_header'] as $com)
				{
					$where_com['B.employee_id'] 		= $emp['employee_id'];
					$where_com['C.compensation_id'] 	= $com['compensation_id'];
					
					$result 							= $this->rm->get_reports_data($fields, $tables, $where_com, FALSE, NULL, $group_by);
					$amount								= ISSET($result['amount']) ? $result['amount'] : 0;
					$com_total 							= $com_total + $amount;
					$data['results'][$ctr][$com['compensation_code']]	= $amount;
				}
				$data['results'][$ctr]['com_total']		= $com_total;
				$ctr++;
			}


			// DEDUCTIONS
			$ctr = 0;
			$fields						= array('SUM(C.amount) amount');
			$where_ded['EXTRACT(YEAR_MONTH FROM A.payment_date)']	= $year_month;
			$group_by					= array('B.employee_id', 'C.deduction_id');
			foreach($data['employees'] as $emp)
			{
				$ded_total	= 0;
				foreach($data['ded_header'] as $ded)
				{
					$where_ded['B.employee_id'] 	= $emp['employee_id'];
					$where_ded['C.deduction_id'] 	= $ded['deduction_id'];
			
					$result 						= $this->rm->get_reports_data($fields, $tables, $where_ded, FALSE, NULL, $group_by);
					$amount							= ISSET($result['amount']) ? $result['amount'] : 0;
					$ded_total						= $ded_total + $amount;
					$data['results'][$ctr][$ded['deduction_code']]	= $amount;
				}
				$data['results'][$ctr]['ded_total']	= $ded_total;
				$ctr++;
			}
			
			
			// SET NET PAY
			$ctr = 0;
			foreach($data['results'] as $res)
			{
				$data['results'][$ctr]['net_pay'] = $res['com_total'] - $res['ded_total'];
				$ctr++;
			}
			
			
			// VERTICAL TOTALS
			$vertical_totals = array();
			foreach ($data['results'] as $key=>$value) {
				foreach ($value as $id=>$val) {
					if(is_numeric($val) and $id != 'check_no')
					{
						$vertical_totals[$id]+=$val;
					}
				}
			}
			
			$data['vertical_totals'] = $vertical_totals;

			//SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($reviewed_by);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($prepared_by);	
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
	
	public function get_employees($tables, $where)
	{
		$fields						= array("B.employee_id", "CONCAT(D.last_name, ' ', D.ext_name , ', ', D.first_name, ' ', LEFT(D.middle_name, (1)), '.') full_name", "A.voucher_description", "A.payment_date", "A.payment_details");
		$tables['t4']	= array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'D',
				'type'      => 'JOIN',
				'condition' => 'B.employee_id = D.employee_id'
		);
		$group_by			= array('B.employee_id');
		return $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, $group_by);
			
	}
	
	public function get_compensation_headers($tables, $where)
	{
		$fields 		= array('D.compensation_id','D.compensation_code');
		$tables['t4']	= array(
				'table'     => $this->rm->tbl_param_compensations,
				'alias'     => 'D',
				'type'      => 'JOIN',
				'condition' => 'C.compensation_id = D.compensation_id'
		);
		$group_by			= array('D.compensation_id');
		return $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, $group_by);
			
	}
	
	public function get_deduction_headers($tables, $where)
	{
		$fields 			= array('D.deduction_id','D.deduction_code');
		$tables['t4']		= array(
				'table'     => $this->rm->tbl_param_deductions,
				'alias'     => 'D',
				'type'      => 'JOIN',
				'condition' => 'C.deduction_id = D.deduction_id'
		);
		$group_by				= array('D.deduction_id');
			
		return $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, $group_by);
	}
	
	public function generate_consolidated_date()
	{
		$data['result']		= array();
		
		
	}
}


/* End of file Bir_2316_certificate_of_compensation_payment_tax_withheld.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_2316_certificate_of_compensation_payment_tax_withheld.php */