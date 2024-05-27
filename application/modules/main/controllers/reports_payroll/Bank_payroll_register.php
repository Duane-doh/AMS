<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_payroll_register extends Main_Controller {
	
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
			$data                  = array();
			/*
			$tables				= array(
					'main'		=> array(
							'table'		=> $this->rm->tbl_payout_summary,
							'alias' 	=> 'A'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias' 	=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'B.payroll_hdr_id = C.payroll_hdr_id'
					),
					't4'		=> array(
							'table'		=> $this->rm->tbl_employee_identifications,
							'alias' 	=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.employee_id = D.employee_id AND D.identification_type_id = ' . BANKACCT_TYPE_ID
					),
					't5'		=> array(
							'table'		=> $this->rm->tbl_employee_personal_info,
							'alias' 	=> 'E',
							'type'		=> 'JOIN',
							'condition'	=> 'B.employee_id =  E.employee_id'
					),
					't6'		=> array(
							'table'		=> $this->rm->tbl_payout_summary_dates,
							'alias' 	=> 'F',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id =  F.payout_summary_id AND C.effective_date = F.effective_date'
					)
			);
			*/
			// ====================== jendaigo : start : include tbl_employee_identification_details and tbl_attendance_period_hdr ============= //
			$tables				= array(
					'main'		=> array(
							'table'		=> $this->rm->tbl_payout_summary,
							'alias' 	=> 'A'
					),
					't2'		=> array(
							'table'		=> $this->rm->tbl_payout_header,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id'
					),
					't3'		=> array(
							'table'		=> $this->rm->tbl_payout_details,
							'alias' 	=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'B.payroll_hdr_id = C.payroll_hdr_id'
					),
					't4'		=> array(
							'table'		=> $this->rm->tbl_employee_identifications,
							'alias' 	=> 'D',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'B.employee_id = D.employee_id AND D.identification_type_id = ' . BANKACCT_TYPE_ID
					),
					't5'		=> array(
							'table'		=> $this->rm->tbl_employee_personal_info,
							'alias' 	=> 'E',
							'type'		=> 'JOIN',
							'condition'	=> 'B.employee_id =  E.employee_id'
					),
					't6'		=> array(
							'table'		=> $this->rm->tbl_payout_summary_dates,
							'alias' 	=> 'F',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id =  F.payout_summary_id AND C.effective_date = F.effective_date'
					),
					't7'		=> array(
							'table'		=> $this->rm->tbl_attendance_period_hdr,
							'alias' 	=> 'G',
							'type'		=> 'JOIN',
							'condition'	=> 'A.attendance_period_hdr_id = G.attendance_period_hdr_id'
					),
					't8'		=> array(
							'table'		=> $this->rm->tbl_employee_identification_details,
							'alias' 	=> 'H',
							'type'		=> 'LEFT JOIN',
							'condition'	=> 'D.employee_identification_id = H.employee_identification_id AND 
											IF(H.start_date IS NULL, current_date(), H.start_date) <= G.date_to AND 
											IF(H.end_date IS NULL, current_date(), H.end_date) >= G.date_to'
					)
			);
			// ====================== jendaigo : start : include tbl_employee_identification_details and tbl_attendance_period_hdr =========== //

			$where                               = array();
			$where['A.bank_id']                  = $params['bank'];
			$where['A.attendance_period_hdr_id'] = $params['payroll_period'];
			$where['F.payout_summary_date_id']   = $params['payout_date'];
			
			//STORES EFFECTIVE DATE
			$fields 								= array('C.effective_date');
			$data['effective_date']					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, array('C.effective_date' => 'ASC'), array('C.effective_date'));

			//STORES EMPLOYEE DETAILS
			// $fields									= array('B.payroll_hdr_id', 'D.identification_value acct_no', 'CONCAT(E.last_name, " ", E.ext_name, ", ", E.first_name, " ", LEFT(E.middle_name, (1)), ". ") full_name');
			// ====================== jendaigo : start : change name format and include start_date of table employee_identification_details ============= //
			$fields									= array('B.payroll_hdr_id', 'H.start_date', 'D.identification_value acct_no', 'CONCAT(E.last_name, ", ", E.first_name, IF(E.ext_name="", "", CONCAT(" ", E.ext_name)), IF((E.middle_name="NA" OR E.middle_name="N/A" OR E.middle_name="-" OR E.middle_name="/"), "", CONCAT(" ", LEFT(E.middle_name, 1), ". "))) full_name', 'CONCAT(E.last_name, ", ", E.first_name, IF(E.ext_name="", "", CONCAT(" ", E.ext_name)), IF((E.middle_name="NA" OR E.middle_name="N/A" OR E.middle_name="-" OR E.middle_name="/"), "", CONCAT(" ", E.middle_name))) emp_full_name');
			// ====================== jendaigo : end : change name format adn include start_date of table employee_identification_details ============= //
			
			// $group_by								= array('B.payroll_hdr_id');
			// ====================== jendaigo : start : include H.employee_identification_id ============= //
			$group_by								= array('B.payroll_hdr_id', 'H.employee_identification_id');
			// ====================== jendaigo : end : include H.employee_identification_id ============= //
			
			$employee_details						= $this->rm->get_reports_data($fields, $tables, $where, TRUE, array('full_name' => 'ASC'), $group_by);

			// IDENTIFICATION TYPE FORMAT
			$format  = $this->rm->get_reports_data(array('format'), $this->rm->tbl_param_identification_types, array('identification_type_id' => BANKACCT_TYPE_ID), FALSE);

			foreach ($data['effective_date'] as $date)
			{
				$effective_date	= $date['effective_date'];
				//STORES COMPENSATION PER EFFECTIVE DATE
				$compensation[$effective_date] = $this->get_compensation($tables, $where, $date);
				
				//STORES DEDUCTION PER EFFECTIVE DATE
				$deduction[$effective_date]    = $this->get_deduction($tables, $where, $date);


				foreach ($employee_details as $dtl)
				{
					/*	
					$com_amt = ISSET($compensation[$effective_date][$dtl['payroll_hdr_id']]) ? $compensation[$effective_date][$dtl['payroll_hdr_id']] : 0; 
					$ded_amt = ISSET($deduction[$effective_date][$dtl['payroll_hdr_id']]) ? $deduction[$effective_date][$dtl['payroll_hdr_id']] : 0;
					
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['acct_no']	= format_identifications($dtl['acct_no'], $format['format']);
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['full_name']	= $dtl['full_name']; 
					$data['results'][$effective_date][$dtl['payroll_hdr_id']]['amount']		= $com_amt - $ded_amt;
					*/
					// ====================== jendaigo : start : prioritize row matched with effectivity dates ============= //
					if($dtl['start_date'])
					{
						$com_amt = ISSET($compensation[$effective_date][$dtl['payroll_hdr_id']]) ? $compensation[$effective_date][$dtl['payroll_hdr_id']] : 0; 
						$ded_amt = ISSET($deduction[$effective_date][$dtl['payroll_hdr_id']]) ? $deduction[$effective_date][$dtl['payroll_hdr_id']] : 0;
						
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['acct_no']	= format_identifications($dtl['acct_no'], $format['format']);
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['full_name']	= $dtl['full_name']; 
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['amount']		= $com_amt - $ded_amt;
						//format for excel extraction
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['emp_acct_no']	= $dtl['acct_no'];
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['emp_full_name']	= $dtl['emp_full_name']; 
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['emp_amount'] 	= sprintf('%014d', preg_replace("/[^0-9]/", "", number_format($data['results'][$effective_date][$dtl['payroll_hdr_id']]['amount'], 2)) );
					}
					
					if(empty($data['results'][$effective_date][$dtl['payroll_hdr_id']]))
					{
						$com_amt = ISSET($compensation[$effective_date][$dtl['payroll_hdr_id']]) ? $compensation[$effective_date][$dtl['payroll_hdr_id']] : 0; 
						$ded_amt = ISSET($deduction[$effective_date][$dtl['payroll_hdr_id']]) ? $deduction[$effective_date][$dtl['payroll_hdr_id']] : 0;
						
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['acct_no']	= format_identifications($dtl['acct_no'], $format['format']);
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['full_name']	= $dtl['full_name']; 
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['amount']		= $com_amt - $ded_amt;
						//format for excel extraction
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['emp_acct_no']	= $dtl['acct_no'];
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['emp_full_name']	= $dtl['emp_full_name']; 
						$data['results'][$effective_date][$dtl['payroll_hdr_id']]['emp_amount'] 	= sprintf('%014d', preg_replace("/[^0-9]/", "", number_format($data['results'][$effective_date][$dtl['payroll_hdr_id']]['amount'], 2)) );
					}
					// ====================== jendaigo : end : prioritize row matched with effectivity dates ============= //
				}
			}

			// ====================== jendaigo : start : query payroll period ============= //
			// GET PAYROLL PERIOD
			$fields					= array('date_from', 'date_to');
			$table					= $this->rm->tbl_attendance_period_hdr;
			$where 					= array();
			$where['attendance_period_hdr_id']	= $params['payroll_period'];
			$data['payroll_period'] = $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);
			// ====================== jendaigo : end : query payroll period ============= //
			
			// GET BANK DETAILS
			$fields				= array('bank_name', 'branch_code');
			$table				= $this->rm->tbl_param_banks;
			$where 				= array();
			$where['bank_id']	= $params['bank'];
			$data['bank']		= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);
				
			// GET SIGNATORY DETAILS
			$fields				= array('batch_code', 'approved_by', 'certified_by', 'certified_cash_by');
			$table				= $this->rm->tbl_payout_summary;
			$where 				= array();
			$where['attendance_period_hdr_id']	= $params['payroll_period'];
			$data['details']	= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);
			

			if($params['signatory_a'])
				$data['certified_by']		= $this->cm->get_report_signatory_details($params['signatory_a']);

			if($params['signatory_b'])
				$data['approved_by']		= $this->cm->get_report_signatory_details($params['signatory_b']);

			if($params['signatory_c'])
				$data['certified_cash_by']	= $this->cm->get_report_signatory_details($params['signatory_c']);

			$data['check_hash'] = $params['check_hash'];
			$data['batch_code'] = $params['alphalist_batch_no'];
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
	
	public function get_compensation($tables, $where, $date)
	{
		$fields						= array('B.payroll_hdr_id', 'SUM(C.amount) amount');
		
		$where['C.effective_date']	= $date['effective_date'];
		$where['C.compensation_id']	= "IS NOT NULL";
		
		// $com_temp					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, array('B.payroll_hdr_id'));
		// ====================== jendaigo : start : include checking of bank account details ============= //
		$com_temp					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, array('B.payroll_hdr_id', 'H.employee_identification_detail_id'));
		// ====================== jendaigo : end : include checking of bank account details ============= //
		
		$com_final = array();
		foreach ($com_temp as $temp)
		{
			$com_final[$temp['payroll_hdr_id']] = $temp['amount'];
		}
		
		return $com_final;
	}
	
	public function get_deduction($tables, $where, $date)
	{
		$fields						= array('B.payroll_hdr_id', 'SUM(C.amount) amount');
		$where['C.effective_date']	= $date['effective_date'];
		$where['C.deduction_id']	= "IS NOT NULL";
		
		// $ded_temp				 	= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, array('B.payroll_hdr_id'));
		// ====================== jendaigo : start : include checking of bank account details ============= //
		$ded_temp				 	= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, array('B.payroll_hdr_id', 'H.employee_identification_detail_id'));
		// ====================== jendaigo : end : include checking of bank account details ============= //
		
		$ded_final = array();
		foreach ($ded_temp as $temp)
		{
			$ded_final[$temp['payroll_hdr_id']] = $temp['amount'];	
		}
		
		return $ded_final;
	}
}


/* End of file Bank_payroll_register.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bank_payroll_register.php */