<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_payroll_alpha_list_per_office extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data	= array();
			$office_list = array();
			if(!EMPTY($params['office_list']))
			{
				$office_list = $this->rm->get_office_child('', $params['office_list']);
				$data['display_office'] = TRUE;
			}
			else
			{
				$data['display_office'] = false;
				$data['display_signatories'] = TRUE;
			}
			
			$field    = array("DATE_FORMAT(A.date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(A.date_to,'%m/%d/%Y') as date_to", "C.office_name", "CONCAT(DATE_FORMAT(A.date_from,'%M %d'), ' - ', DATE_FORMAT(A.date_to,'%d, %Y')) payroll_period");

			$table    = array(
				'main'		=> 		array(
					'table'		=> 	$this->rm->tbl_attendance_period_hdr,
					'alias'		=>  'A'
				),
				't1'		=> 		array(
					'table'		=>  $this->rm->tbl_payout_summary,
					'alias'		=>  'B',
					'type'		=>	'JOIN',
					'condition' =>	'A.attendance_period_hdr_id = B.attendance_period_hdr_id'
				),
				't2'		=>		array(
					'table'		=>  $this->rm->tbl_payout_header,
					'alias'		=>	'C',
					'type'		=>	'JOIN',
					'condition' =>	'B.payroll_summary_id = C.payroll_summary_id'
				)
			);
			$where                               = array();
			$where["A.attendance_period_hdr_id"] = $params['payroll_period'];
			if(!EMPTY($params['office_list']))
			{
				$where['C.office_id']                = array($office_list, array('IN'));
			}
			$data['period_detail'] = $this->rm->get_reports_data($field, $table, $where, FALSE);
			$data                  = $this->get_compensation_infos($data, $office_list, $params['payroll_period']);
			$data                  = $this->get_deduction_infos($data, $office_list, $params['payroll_period']);

			$data['records']       = $this->consolidate_com_ded($data);
			// GET PAYOUT DATE
			$table 				= array(
					'main'		=> array(
							'table'	=> $this->rm->tbl_payout_summary,
							'alias'	=> 'A'
					),
					't2'		=> array(
							'table'	=> $this->rm->tbl_payout_summary_dates,
							'alias'		=> 'B',
							'type'  	=> 'LEFT JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payout_summary_id'
					)
			);
			$fields		= array('B.effective_date');
			$where									= array();
			$where['A.attendance_period_hdr_id']	= $params['payroll_period'];
			$payroll_date							= $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);

			$month =  date("F", strtotime($payroll_date['effective_date']));
			$year =  date("Y", strtotime($payroll_date['effective_date']));
			$max_date =  date("t", strtotime($payroll_date['effective_date']));

			$data['payroll_date_text']				= $month. ' 01-'.$max_date.', '.$year;
			/*
			 |COUNT PER PAGE TO DISPLAY PAGE TOTAL
			 */

			 /*START: GET EMPLOYMENT TYPE FLAG*/
			$field                    = array("employ_type_flag");		
			
			$table                    = $this->rm->tbl_param_payroll_types;
			$where                    = array();
			$where["payroll_type_id"] = $params['payroll_type'];
			$employ_type_flag         = $this->rm->get_reports_data($field, $table, $where, FALSE);
			/*END: GET EMPLOYMENT TYPE FLAG*/
			if($employ_type_flag['employ_type_flag'] == PAYROLL_TYPE_FLAG_REG)
			{
				$data['first_page_cnt']  = 5;
				$data['per_page_cnt']    = 5;
			}
			else
			{
				$data['first_page_cnt']  = 3;
				$data['per_page_cnt']    = 3;
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
	
	
	public function get_compensation_infos($data, $office_list, $payroll_period)
	{
		// COMPENSATIONS
		$tables 		= array(
		
				'main'      => array(
						'table'     => $this->rm->tbl_param_compensations,
						'alias'     => 'A'
				),
				't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.compensation_id = B.compensation_id'
				),
				't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
				),
				't4'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
				),
				't5'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
				),
				't6'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.employee_id = F.employee_id'
				)
		);
		$where = array();
		$where['A.general_payroll_flag'] = YES;
		$where['A.inherit_parent_id_flag'] = NA;
		$where['D.attendance_period_hdr_id'] = $payroll_period;
		if(!EMPTY($office_list))
		{
			$where['C.office_id'] = array($office_list,array('IN'));
		}
		
		// $select_fields = array('C.salary_grade','C.pay_step','C.employee_id', 'F.agency_employee_id', 'CONCAT(F.last_name, IF(F.ext_name="" OR ISNULL(F.ext_name), "", CONCAT(" ", F.ext_name)), ", ", F.first_name, " ", F.middle_name) employee_name', 'C.position_name');
		// ====================== jendaigo : start : change name format ============= //
		$select_fields = array('C.salary_grade','C.pay_step','C.employee_id', 'F.agency_employee_id', 'CONCAT(F.last_name, \', \', F.first_name, IF(F.ext_name=\'\', \'\', CONCAT(\' \', F.ext_name)), IF((F.middle_name=\'NA\' OR F.middle_name=\'N/A\' OR F.middle_name=\'-\' OR F.middle_name=\'/\'), \'\', CONCAT(\' \', F.middle_name))) employee_name', 'C.position_name');
		// ====================== jendaigo : end : change name format ============= //
		
		$data['employees'] = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('employee_name' => 'ASC'), array('C.employee_id'));
		
		$select_fields = array('A.compensation_id, A.compensation_name, A.report_short_code, A.compensation_code, A.basic_salary_flag');
		$data['com_hdr'] = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, NULL, array('A.compensation_id'));
		
		$select_fields = array('C.employee_id, A.compensation_id, A.compensation_name,A.compensation_code, A.report_short_code, SUM(ifnull(B.amount,0)) as amount,SUM(ifnull(B.orig_amount,0)) as orig_amount,SUM(ifnull(B.less_amount,0)) as less_amount,  GROUP_CONCAT(IF(B.remarks_compensation = "",null,B.remarks_compensation)) remarks_compensation');

		$com_temp = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('C.employee_id' => 'ASC'), array('A.compensation_id,C.employee_id'));
		
		$select_fields = array('C.employee_id,B.effective_date, SUM(ifnull(B.amount,0)) as amount');

		$pay_outs = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('C.employee_id' => 'ASC','B.effective_date' => 'ASC'), array('C.employee_id,B.effective_date'));

		$com_records	= array();
		foreach ($data['employees'] as $emp)
		{
			foreach ($data['com_hdr'] as $hdr)
			{
				foreach ($com_temp as $com)
				{
					if($emp['employee_id'] == $com['employee_id'] AND $hdr['compensation_id'] == $com['compensation_id'])
					{
							if($hdr['basic_salary_flag'] == YES OR $com['compensation_code'] == COMPENSATION_PERA)
							{
								$com_records[$emp['employee_id']]['basic_comp'][$hdr['compensation_id']]['amount'] = $com['orig_amount'];
								$com_records[$emp['employee_id']]['basic_comp'][$hdr['compensation_id']]['less_amount'] = $com['less_amount'];
								$com_records[$emp['employee_id']]['basic_comp'][$hdr['compensation_id']]['code'] = $hdr['report_short_code'];
								if($com['compensation_code'] == COMPENSATION_PERA)
								{
									$com_records[$emp['employee_id']]['basic_comp'][$hdr['compensation_id']]['exclude'] = TRUE;
								}
							}
							else
							{
								$com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['amount'] = $com['amount'];
								$com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['code']   = $hdr['report_short_code'];
								$com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['less_amount'] = $com['less_amount'];
								if(!EMPTY($com['remarks_compensation']))
								{
									if(EMPTY($com_records[$emp['employee_id']]['com_remarks']))
									{
										$com_records[$emp['employee_id']]['com_remarks'] = $com['remarks_compensation'];
									}else{
										$com_records[$emp['employee_id']]['com_remarks'] .=  ", " .  $com['remarks_compensation'];
									}
								}		
							}
											
					}
				}
				if(EMPTY($com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['code']) AND $hdr['basic_salary_flag'] == NO  AND $hdr['compensation_code'] != COMPENSATION_PERA)
				{
					$com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['code'] = $hdr['report_short_code'];
					$com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['amount'] = 0;
					$com_records[$emp['employee_id']]['compensation'][$hdr['compensation_id']]['less_amount'] = $com['less_amount'];
				}
			}
			foreach ($pay_outs as $pay_out)
			{
				if($emp['employee_id'] == $pay_out['employee_id'])
				{
					$com_records[$emp['employee_id']]['pay_outs_comp'][] = $pay_out['amount'];
				}
			}
		}
		$data['com_records'] = $com_records;

		// GET SUM TOTAL OF ALL COMPENSATION PER ID		
		$grand_total = array();
		foreach($data['com_hdr'] as $com_hdr)
		{
			foreach ($com_records as $record)
			{
				$grand_total[$com_hdr['compensation_id']] += $record['compensation'][$com_hdr['compensation_id']]['amount'];
			}
			
		}
		$data['com_grand_total'] = $grand_total;

		
		return $data;
	}
	
	public function get_deduction_infos($data, $office_list, $payroll_period)
	{
		
		//DEDUCTIONS
		$tables 		= array(
		
				'main'      => array(
						'table'     => $this->rm->tbl_param_deductions,
						'alias'     => 'A'
				),
				't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.deduction_id = B.deduction_id'
				),
				't3'        => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'B.payroll_hdr_id  = C.payroll_hdr_id'
				),
				't4'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'C.payroll_summary_id = D.payroll_summary_id'
				),
				't5'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'E',
						'type'      => 'JOIN',
						'condition' => 'D.attendance_period_hdr_id = E.attendance_period_hdr_id'
				),
				't7'        => array(
						'table'     => $this->rm->tbl_param_remittance_types,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.remittance_type_id = G.remittance_type_id'
				),
				't8'        => array(
						'table'     => $this->rm->tbl_param_remittance_payees,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'G.remittance_payee_id = H.remittance_payee_id'
				),
				't9'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'J',
						'type'      => 'JOIN',
						'condition' => 'D.attendance_period_hdr_id = J.attendance_period_hdr_id'
				),
				't10'        => array(
						'table'     => $this->rm->tbl_param_payroll_types,
						'alias'     => 'K',
						'type'      => 'JOIN',
						'condition' => 'J.payroll_type_id = K.payroll_type_id AND (A.employ_type_flag = K.employ_type_flag OR A.employ_type_flag="ALL")'
				)
		);

		$where = array();
		$where['A.general_payroll_flag'] = YES;
		$where['D.attendance_period_hdr_id'] = $payroll_period;
		if(!EMPTY($office_list))
		{
			$where['C.office_id'] = array($office_list,array('IN'));
		}
		

		/*GET REMITTANCE PAYEES WITH VALUES*/
		$select_fields 				= array('H.remittance_payee_id', 'IFNULL(H.remittance_payee_name,"Others") remittance_payee_name', 'IFNULL(H.remittance_payee_id,"x_last") order_payee','IFNULL(H.report_short_code,"Others") report_short_code');
		$data['remittance_payee'] 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('order_payee' => 'ASC'), array('H.remittance_payee_id'));
		

		$select_fields  = array('A.inherit_parent_id_flag, A.parent_deduction_id, C.employee_id, SUM(ifnull(B.amount,0)) as amount, A.deduction_id, A.deduction_code, A.report_short_code, H.remittance_payee_id, GROUP_CONCAT(IF(B.remarks_deduction = "",null,B.remarks_deduction)) remarks_deduction');
			
		
		
		$ded_temp 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('C.employee_id' => 'ASC'), array('C.employee_id, A.deduction_id'));

		$select_fields = array('C.employee_id,B.effective_date, SUM(ifnull(B.amount,0)) as amount');

		$pay_outs 	= $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, array('C.employee_id' => 'ASC','B.effective_date' => 'ASC'), array('C.employee_id, B.effective_date'));

		$select_fields = array('A.deduction_id, A.deduction_name, A.statutory_flag, A.deduction_code, H.remittance_payee_id');
		
		//STORES STATUTORY
		$where['A.statutory_flag'] = YES;
		$ded_st = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, NULL, array('A.deduction_id'));
		
		//STORES NON-STATUTORY
		$where['A.statutory_flag'] = NO;
		$ded_non_st = $this->rm->get_reports_data($select_fields, $tables, $where, TRUE, NULL, array('A.deduction_id'));
		
		$data['ded_hdr'] = array_merge($ded_st, $ded_non_st);
		
		foreach ($data['employees'] as $emp)
		{
			
			foreach ($data['remittance_payee'] as $rem)
			{
				foreach ($data['ded_hdr'] as $hdr)
				{
					foreach ($ded_temp as $ded)
					{
						if(($emp['employee_id'] == $ded['employee_id']) && ($rem['remittance_payee_id'] == $ded['remittance_payee_id']))
						{
							if($hdr['deduction_id'] == $ded['deduction_id'])
							{
								if($ded['inherit_parent_id_flag'] == YES)
								{
									$ded_records[$emp['employee_id']]['deduction'][$rem['remittance_payee_id']][$ded['parent_deduction_id']]['amount'] += $ded['amount'];
								}
								else
								{
									$ded_records[$emp['employee_id']]['deduction'][$rem['remittance_payee_id']][$ded['deduction_id']]['amount'] += $ded['amount'];
									$ded_records[$emp['employee_id']]['deduction'][$rem['remittance_payee_id']][$ded['deduction_id']]['code'] = $ded['report_short_code'];
								}
								
								if(!EMPTY($ded['remarks_deduction']))
								{
									if(EMPTY($ded_records[$emp['employee_id']]['ded_remarks']))
									{
										$ded_records[$emp['employee_id']]['ded_remarks'] = $ded['remarks_deduction'];
									}else{
										$ded_records[$emp['employee_id']]['ded_remarks'] .= ", " .  $ded['remarks_deduction'];
									}
								}
								
								$ded_amt = number_format($ded['amount'],2);
								// STRING LENGTHS
								$str_length[$rem['remittance_payee_id']]['name'][]		= strlen($hdr['deduction_code']);
								$str_length[$rem['remittance_payee_id']]['amount'][]	= strlen((string)$ded_amt);
							}
						}
					}
				}
					
				if(!ISSET($ded_records[$emp['employee_id']]['deduction'][$rem['remittance_payee_id']]))
				{
					foreach ($data['ded_hdr'] as $hdr)
					{
						$ded_records[$emp['employee_id']]['deduction'][$rem['remittance_payee_id']] = "";
					}
				}
			
			}
			foreach ($pay_outs as $pay_out)
			{
				if($emp['employee_id'] == $pay_out['employee_id'])
				{
					$ded_records[$emp['employee_id']]['pay_outs_dec'][] = $pay_out['amount'];
				}
			}
		}
		$data['ded_records'] = $ded_records;
		
		// GET SUM TOTAL OF ALL COMPENSATION PER ID
		$grand_total = array();
		foreach ($ded_records as $records)
		{
			foreach ($data['remittance_payee'] as $rem)
			{
				foreach ($records['deduction'] as $key=>$record)
				{
					if($rem['remittance_payee_id'] == $key)
					{
						foreach ($record as $rec)
						{
							$grand_total[$rem['remittance_payee_id']] += $rec['amount'];
						}
					}
				}
			}
				
		}
		$data['ded_grand_total'] = $grand_total;
		
		$longest_str = $this->get_longest_str($str_length, $data['remittance_payee']);
		$data['longest_code'] 	= $longest_str['longest_code'];
		$data['longest_amt'] 	= $longest_str['longest_amt'];
		
		return $data;
	}
	
	public function consolidate_com_ded($data)
	{
		$records = array();
		foreach ($data['employees'] as $emp)
		{
			$records[$emp['employee_id']]['employee_id']        = $emp['employee_id'];
			$records[$emp['employee_id']]['employee_name']      = $emp['employee_name'];
			$records[$emp['employee_id']]['position']           = $emp['position_name'];
			$records[$emp['employee_id']]['agency_employee_id'] = $emp['agency_employee_id'];
			$records[$emp['employee_id']]['grade']              = $emp['salary_grade'];
			$records[$emp['employee_id']]['step']               = $emp['pay_step'];
			$records[$emp['employee_id']]['amounts']            = array_merge($data['com_records'][$emp['employee_id']], $data['ded_records'][$emp['employee_id']]);
		}
		
		return $records;
	}
	
	public function get_longest_str($str_length, $remittance_payee)
	{
		$longest = array();
		
		
		foreach ($remittance_payee as $rem)
		{
			foreach ($str_length as $key=>$str_len)
			{
				if($rem['remittance_payee_id'] == $key)
				{
					$longest['longest_code'][$rem['remittance_payee_id']] = max($str_len['name']);
					$longest['longest_amt'][$rem['remittance_payee_id']] = max($str_len['amount']);
						
				}
			}
		}
		
		return $longest;
	}
}


/* End of file General_payroll_alpha_list_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/General_payroll_alpha_list_per_office.php */