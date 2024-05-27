<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_payslip_for_regulars_and_non_careers extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data  = array();
			$where = array();
			

			$field                    = array("employ_type_flag");		
			
			$table                    = $this->rm->tbl_param_payroll_types;
			$where                    = array();
			$where["payroll_type_id"] = $params['payroll_type'];
			$employ_type_flag         = $this->rm->get_reports_data($field, $table, $where, FALSE);
			/*END: GET EMPLOYMENT TYPE FLAG*/

			$payroll_type_flag[] = $employ_type_flag['employ_type_flag'];	
			$payroll_type_flag[] = PAYROLL_TYPE_FLAG_ALL;
			
			if($employ_type_flag['employ_type_flag'] == PAYROLL_TYPE_FLAG_REG)
			{

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

				$payroll_date_text				= $month. ' 01-'.$max_date.', '.$year;
			}
			else
			{
				// GET PAYOUT DATE
				$table                             = $this->rm->tbl_attendance_period_hdr;
				$fields                            = array('date_from','date_to');
				$where                             = array();
				$where['attendance_period_hdr_id'] = $params['payroll_period'];
				$payroll_date                      = $this->rm->get_reports_data($fields, $table, $where, FALSE, NULL);


				$month =  date("F", strtotime($payroll_date['date_from']));
				$year =  date("Y", strtotime($payroll_date['date_from']));
				$min_date =  date("d", strtotime($payroll_date['date_from']));
				$max_date =  date("d", strtotime($payroll_date['date_to']));

				$payroll_date_text				= $month.' '.$min_date.'-'.$max_date.', '.$year;
			}
			
			//STORES payroll_summary_id
			$where = array();
			$where['payout_type_flag'] = PAYOUT_TYPE_FLAG_REGULAR;
			$where['attendance_period_hdr_id'] = $params['payroll_period'];
			$summary_id = $this->rm->get_payroll_summary_id($where);
			
			//STORES HEADER
			$where = array();
			
			if(!EMPTY($params['employee_gen_pay']))
			{
				$where['A.employee_id'] = $params['employee_gen_pay'];
			}
			$where['A.office_id'] = $params['office_gen_pay'];
			$where['A.payroll_summary_id'] = $summary_id['payroll_summary_id'];
			$results = $this->rm->get_payout_header($where);

			$where_com = array();
			$where_com['A.employ_type_flag'] = array($payroll_type_flag, array("IN"));
			$where_com['A.general_payroll_flag'] = 'Y';
			
			$where_ded = array();
			$where_ded['A.general_payroll_flag'] = 'Y';
			$where_ded['A.employ_type_flag'] = array($payroll_type_flag, array("IN"));
				
			
			//FOR DATE
			$where_date = array();
			$where_date['attendance_period_hdr_id'] = $params['payroll_period'];
			
			//FOR CUTOFF NET
			$where_cutoff = array();
			$where_cutoff['C.payroll_summary_id'] = $summary_id['payroll_summary_id'];
			
			$ctr = 0;
			foreach ($results as $result)
			{
				
				$data['results'][$ctr]['header']        = $result;
				$data['results'][$ctr]['date']          = $payroll_date_text;
				$data['results'][$ctr]['compensations'] = $this->get_compensations($where_com, $result['payroll_hdr_id']);

				//REMOVING INACTIVE COMPENSATION IF IT HAS NO VALUE OR EQUAL TO ZERO
				foreach ($data['results'][$ctr]['compensations'] as $key=>$com)
				{
					if($com['active_flag'] == NO && (EMPTY($com['amount']) || $com['amount'] <= 0))
					{
						unset($data['results'][$ctr]['compensations'][$key]);
					}
					if($com['basic_salary_flag'] == YES)
					{
						$data['results'][$ctr]['basic_total'] += $com['amount'];
					}
					else
					{
						$data['results'][$ctr]['other_comp_total'] += $com['amount'];
					}
				}
				
				$data['deductions_results'] = $this->get_deductions($where_ded, $result['payroll_hdr_id']);
				
				//REMOVING INACTIVE DEDUCTION IF IT HAS NO VALUE OR EQUAL TO ZERO
				foreach ($data['deductions_results'] as $key=>$ded)
				{
					if($ded['active_flag'] == NO && (EMPTY($ded['amount']) || $ded['amount'] <= 0))
					{
						unset($data['deductions_results'][$key]);
					}
					if($ded['statutory_flag'] == YES)
					{
						$data['results'][$ctr]['stat_ded_total'] += $ded['amount'];
					}
					else
					{
						$data['results'][$ctr]['other_ded_total'] += $ded['amount'];
					}
				}
				
				$data['deductions_st'] = array();
				$data['deductions_non_st'] = array();
				foreach ($data['deductions_results'] as $ded)
				{
					if($ded['statutory_flag'] == YES)
					{
						$ded['paid_count'] = "&nbsp;&nbsp;";
						$data['deductions_st'][] = $ded;
					}else {
						$ded['paid_count'] = isset($ded['paid_count']) ? "(".$ded['paid_count'].")" : $ded['paid_count'];
						$data['deductions_non_st'][] = $ded;
					}
				}
				
				$data['deductions'] = array_merge($data['deductions_st'], $data['deductions_non_st']);
				
				//GET LESS ABSENCE FLAG
				$abs_ctr = $this->count_less_absence_flag($data['results'][$ctr]['compensations']);
				
				
				$slice = round(count($data['deductions']) / 2);
				// $slice = 21;
				
				$data['results'][$ctr]['deduction_1'] = array_slice($data['deductions'], 0, $slice);
				$data['results'][$ctr]['deduction_2'] = array_slice($data['deductions'], $slice);

				//GET ID#'s
				$where_id = array();
				$where_id['A.employee_id'] = $result['employee_id'];

				$where_id['A.identification_type_id'] =  TIN_TYPE_ID;
				$data['results'][$ctr]['id'][TIN_TYPE_ID] = $this->rm->get_one_specific_id($where_id);

				$where_id['A.identification_type_id'] =  PHILHEALTH_TYPE_ID;
				$data['results'][$ctr]['id'][PHILHEALTH_TYPE_ID] = $this->rm->get_one_specific_id($where_id);

				$where_id['A.identification_type_id'] =  GSIS_TYPE_ID;
				$data['results'][$ctr]['id'][GSIS_TYPE_ID] = $this->rm->get_one_specific_id($where_id);

				$where_id['A.identification_type_id'] =  PAGIBIG_TYPE_ID;
				$data['results'][$ctr]['id'][PAGIBIG_TYPE_ID] = $this->rm->get_one_specific_id($where_id);

				//GET CUTOFF NET
				$where_cutoff['employee_id'] = $result['employee_id'];
				$cutoffs = $this->rm->get_cutoff_net($where_cutoff);
				foreach ($cutoffs as $key => $cutoff) {
					$data['results'][$ctr]['payouts'][] = $cutoff['compensation_amount'] - $cutoff['deduction_amount'];
				}
				
				$ctr++;
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
	
	public function get_compensations($where, $id)
	{
		$result = array();
		$where['A.parent_compensation_id'] = "IS NULL";
		$result = $this->rm->get_compensations($where, $id);
		
		return $result;
	}
	
	public function get_deductions($where, $id)
	{
		$result = array();
		$result = $this->rm->get_deductions($where, $id);
		
		return $result;
	}
	
	public function count_less_absence_flag($results)
	{
		$abs_ctr = 0;
		foreach($results as $result){
			if($result['less_absence_flag'] == 'Y')
			{
				$abs_ctr++;
			}
		}
		
		return $abs_ctr;
	}

}


/* End of file General_payslip_for_regulars_and_non_careers.php */
/* Location: ./application/modules/main/controllers/reports/payroll/General_payslip_for_regulars_and_non_careers.php */