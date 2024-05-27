<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir_tax_payments extends Main_Controller {
	
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
			
			// YEAR MONTH
			$tables   		=  array(
				'main' 			=> array(
						'table' 	=> $this->rm->tbl_remittances,
						'alias' 	=> 'A'
				)
			);
			$fields					= array('A.deduction_start_date', 'A.year_month ym');
			$where					= array();
			$where['A.remittance_id']	= $params['remittance_period_bir_tax_payment'];
			$year_month 	 = $this->rm->get_reports_data($fields, $tables, $where, FALSE);

			// TEXT FOR MONTH YEAR
			$data['month_year_text'] = strtotime($year_month['deduction_start_date']);
			$data['month_year_text'] = date('F Y', $data['month_year_text']);

			$where_rem					= array();
			$where_rem['sys_param_type']= 'REMITTANCE_TYPE_WITHHOLDING_TAX';
			$fields_rem 				= array('sys_param_value');
			$table_rem					= $this->rm->DB_CORE . '.' . $this->rm->tbl_sys_param;
			$remittance_type			= $this->rm->get_reports_data($fields_rem, $table_rem, $where_rem, FALSE);

			// RECORDS
			$tables 			=  array(
					'main'  	=> array (
					'table' 	=>	$this->rm->tbl_remittances,
					'alias'		=> 'A'
				),
				't1'			=> array (
					'table'		=> $this->rm->tbl_param_remittance_types,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.remittance_type_id = B.remittance_type_id'
				),
				't2'			=> array (
					'table'		=> $this->rm->tbl_remittance_details,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.remittance_id = C.remittance_id'
				),
				't3'			=> array (
					'table'		=> $this->rm->tbl_payout_details,
					'alias'		=> 'D',
					'type'		=> 'LEFT JOIN',
					'condition' => 'C.payroll_dtl_id = D.payroll_dtl_id'
				),
				't4'			=> array (
					'table'		=> $this->rm->tbl_payout_header,
					'alias'		=> 'E',
					'type'		=> 'LEFT JOIN',
					'condition' => 'D.payroll_hdr_id = E.payroll_hdr_id'
				)
			); 
			$fields          				= array('E.employee_name', 'E.position_name', 'E.basic_amount AS basic_salary', 'SUM(D.amount) remitted', '(D.effective_date) as processed_date');
			$where							= array();
			$where['B.remittance_type_id'] 	= $remittance_type['sys_param_value'];
			$where['D.deduction_id'] 		= DEDUC_BIR;
			$where['D.effective_date'] 		= "IS NOT NULL";
			$where['A.year_month'] 			= $year_month['ym'];
			$where['E.office_id'] 			= array($this->rm->get_office_child('', $params['office_list']),array('IN'));
			$order_by        				= array('E.employee_name' => 'ASC');
			$group_by        				= array('E.employee_id');
			$data['records'] 				= $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by); 

			// HEADER
			$fields   			= array('A.name AS office_name');
			$tables   			=  array(
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
			$where   		= array('B.office_id' => $params['office_list']);

			$data['header'] = $this->rm->get_reports_data($fields, $tables, $where, FALSE);

			//SIGNATORIES
			$data['certified_by']		= $this->cm->get_report_signatory_details($params['rem_certified_by']);
			$data['prepared_by']		= $this->cm->get_report_signatory_details($params['rem_prepared_by']);
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


/* End of file Bir_tax_payments.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_tax_payments.php */