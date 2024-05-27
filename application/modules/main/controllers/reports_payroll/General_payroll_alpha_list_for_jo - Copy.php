<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_payroll_alpha_list_for_jo extends Main_Controller {
	private $payroll_common;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
		$this->payroll_common = modules::load('main/payroll_common');
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
			$payroll_type					= $this->rm->get_sysparam_value(SYS_PARAM_TYPE_PAYROLL_TYPE_JOB_ORDER);
			
			// STORES DATE_FROM AND DATE_TO
			$fields 							= array('date_from, date_to');
			$tables								= $this->rm->tbl_attendance_period_hdr;
			$where								= array();
			$where['attendance_period_hdr_id']	= $params['payroll_period'];
			$where['payroll_type_id'] 			= $payroll_type['sys_param_value'];
			$date_from_to   					= $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, NULL);

			// STORES NUMBER OF WORKING DAYS
			$data['working_days_in_period'] 	= $this->payroll_common->compute_working_days($date_from_to['date_from'], $date_from_to['date_to']);
			
			// STORES HEADER
			$where                               = array();
			// $where['C.payroll_type_id']          = $payroll_type['sys_param_value'];
			$where['C.attendance_period_hdr_id'] = $params['payroll_period'];
			// $where['A.net_pay']                  = array(0, array(">"));
			if(!EMPTY($params['office_list']))
			{
				$where['A.office_id'] 					= $params['office_list'];
			}
			$data['header']							= $this->get_header($where);
			
			
			// EMPLOYEE IDS
			$employee_ids 	= array();
			foreach($data['header'] as $hdr)
			{
				$employee_ids[] = $hdr['employee_id'];
			}
			
			/* COMPENSATION STUFFS START */
			
			// COMPENSATION IDS
			$where 		= array();
			if(!EMPTY($params['office_list']))
			{
				$where['A.office_id'] 				= $params['office_list'];
			}
			$where['B.compensation_id']			= "IS NOT NULL";
			$where['A.employee_id'] 			= array($employee_ids, array('IN'));
			$where['C.attendance_period_hdr_id']= $params['payroll_period'];
			$compensation_ids 					= $this->get_records(array('GROUP_CONCAT(DISTINCT B.compensation_id) compensation_ids'), $where, array('B.compensation_id'=>'ASC'));
			$compensation_ids					= explode(',', $compensation_ids['compensation_ids']);
			sort($compensation_ids);
			
			//GET COMPENSATION DETAILS
			$fields = array('compensation_id', 'report_short_code');
			$where	= array();
			$where['compensation_id']  	= array($compensation_ids, array('IN'));
			$where['basic_salary_flag']	= NO;
			$tables	= $this->rm->tbl_param_compensations;
			$data['compensation_hdr'] = $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, NULL);
			
			/* COMPENSATION STUFFS END */
			
			
			/* DEDUCTION STUFFS START */
			
			// STORES DEDUCTION GROUPS
			$deduction_groups[]			= $this->get_deduction_groups_codes(SYS_PARAM_TYPE_ALPHALIST_JO_DED1);
			$deduction_groups[]			= $this->get_deduction_groups_codes(SYS_PARAM_TYPE_ALPHALIST_JO_DED2);
				
			$deduction_codes_in_group	= "";
			foreach ($deduction_groups as $group)
			{
				$deduction_codes_in_group 	= $deduction_codes_in_group .  $group['sys_param_value'] . ",";
				//CONTAINS DEDUCTION_ID, DEDUCTION_CODE, AND REPORT_SHORT_NAME PER GROUP
				$deduction_ids_per_groups[] 	= explode(',',$group['sys_param_value']);
			}
			$deduction_codes_in_group		= rtrim($deduction_codes_in_group, ',');
			$deduction_ids_in_group			= $this->get_deduction_groups_ids($deduction_codes_in_group);
			$deduction_ids_in_group			= explode(',', $deduction_ids_in_group['deduction_ids']);
			
			
			$fields = array('deduction_id', 'deduction_code', 'report_short_code');
			$where	= array();
			foreach($deduction_ids_per_groups as $key => $ids_per_group)
			{
				foreach($ids_per_group as $id)
				{
					$where['deduction_code']  = $id;
					$tables	= $this->rm->tbl_param_deductions;
					$data['deduction_groups'][$key][$id] = $this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, NULL);
				}
			}
			
			// GET DEDUCTION DETAILS IN GROUP
			$fields = array('deduction_id', 'report_short_code');
			$where	= array();
			$where['deduction_id']  = array($deduction_ids_in_group, array('IN'));
			$tables	= $this->rm->tbl_param_deductions;
			$data['deduction_hdr_in_group'] = $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, NULL);
			
 			// STORES REGULAR DEDUCTION IDS
			$ewt_id 				= $this->rm->get_sysparam_value(PARAM_DEDUCTION_ST_BIR_EWT_ID);
			$gmp_id 				= $this->rm->get_sysparam_value(PARAM_DEDUCTION_ST_BIR_VAT_ID);
			$regular_deduction_ids	= array($ewt_id['sys_param_value'], $gmp_id['sys_param_value']);		
			sort($regular_deduction_ids);
			
			//GET REGULAR DEDUCTIONS DETAILS
			$fields = array('deduction_id', 'report_short_code');
			$where	= array();
			$where['deduction_id']  = array($regular_deduction_ids, array('IN'));
			$tables	= $this->rm->tbl_param_deductions;
			$data['regular_deduction_hdr'] = $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, NULL);
			
			// STORES OTHER DEDUCTION IDS
			$where 		= array();
			if(!EMPTY($params['office_list']))
			{
				$where['A.office_id'] 					= $params['office_list'];
			}
			$where['B.deduction_id']				= "IS NOT NULL";
			$where['B.deduction_id']				= array(array_merge($regular_deduction_ids, $deduction_ids_in_group), array('NOT IN'));
			$where['A.employee_id'] 				= array($employee_ids, array('IN'));
			$where['C.attendance_period_hdr_id']	= $params['payroll_period'];
			$other_deduction_ids 					= $this->get_records(array('GROUP_CONCAT(DISTINCT B.deduction_id) deduction_ids'), $where, array('B.deduction_id'=>'ASC'));
			$other_deduction_ids					= explode(',', $other_deduction_ids['deduction_ids']);
			sort($other_deduction_ids);
			
			//GET OTHER DEDUCTIONS DETAILS
			$fields = array('deduction_id', 'report_short_code');
			$where	= array();
			$where['deduction_id']  = array($other_deduction_ids, array('IN'));
			$tables	= $this->rm->tbl_param_deductions;
			$data['other_deduction_hdr'] = $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, NULL);
			
			/* DEDUCTION STUFFS END */

			// RECORDS
			$fields		= array('A.employee_id', 'A.basic_amount', 'A.net_pay', 'IFNULL(A.month_work_days, 0) month_work_days', 'A.daily_rate', 'GROUP_CONCAT(DISTINCT A.payroll_hdr_id) hdr_ids', 'C.attendance_period_hdr_id');
			$where 		= array();
			if(!EMPTY($params['office_list']))
			{
				$where['A.office_id'] 					= $params['office_list'];
			}
			$where['D.attendance_period_hdr_id']	= $params['payroll_period'];
			$where['D.payroll_type_id'] 			= $payroll_type['sys_param_value'];
				
			foreach ($data['header'] as $hdr)
			{
				$where['A.employee_id'] 				= $hdr['employee_id'];
				$data['records'][$hdr['employee_id']]	= $this->get_records($fields, $where);
			}
			
			$data['rates']	= array();
			foreach($data['records'] as $key=>$record)
			{
				// STORES WORKING DAYS
				if($record['month_work_days'] > 0)
				{
					$data['working_days_in_month'] = $record['month_work_days'];
				}
				
				$data['rates'][$key] = $this->compute_rates($key, $record['daily_rate']);

				// STORES BASIC SALARY
				$data['bs'][$key]		= $this->get_basic_compensations($record['hdr_ids']);
				// $data['bs'][$key]		= $this->get_compensations($record['hdr_ids'],NULL,TRUE);
				
				// STORES COMPENSATION AMOUNTS PER ID
				foreach ($data['compensation_hdr'] as $com)
				{
					$data['compensation_amounts'][$key][$com['compensation_id']]	= $this->get_compensations($record['hdr_ids'], $com['compensation_id']);
				}
				
				
				// STORES DEDUCTIONS IN GROUP AMOUNTS PER ID
				foreach ($data['deduction_groups'] as $group_key => $ded_group)
				{
					foreach ($ded_group as $ded)
					{
						$data['ded_amt_in_group'][$key][$group_key][$ded['deduction_id']]	= $this->get_deductions($record['hdr_ids'], $ded['deduction_id']);
					}
				}
				
				// STORES REGULAR DEDUCTIONS AMOUNTS PER ID
				foreach ($data['regular_deduction_hdr'] as $ded)
				{
					$data['regular_deduction_amt'][$key][$ded['deduction_id']]	= $this->get_deductions($record['hdr_ids'], $ded['deduction_id']);
				}
				
				// STORES OTHER DEDUCTIONS AMOUNTS PER ID
				foreach ($data['other_deduction_hdr'] as $ded)
				{
					$other_deduction_amt[$key][]	= $this->get_deductions($record['hdr_ids'], $ded['deduction_id']);
				}
				
				// STORES SUM OF OTHER DEDUCTION AMOUNT PER EMPLOYEE
				foreach ($other_deduction_amt[$key] as $other_ded)
				{
					$data['other_deduction_amt'][$key]	+= $other_ded['amount'];
				}
			}
			
			// STORES UNDERTIME, ABSENCE LESS AMOUNTS
			$data['less_amounts'] = array();
			foreach($data['rates'] as $key=>$rate)
			{
				$data['less_amounts'][$key]	= $this->get_less_amount($params['payroll_period'], $rate);
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
	
	// HEADER
	public function get_header($where)
	{
		$fields				= array("A.employee_id", "D.agency_employee_id", "CONCAT(D.last_name, ', ', D.first_name, ' ', LEFT(D.middle_name, (1)), '. ', D.ext_name) full_name",
									"G.identification_value tin", "H.identification_value atm_no");
		$tables		 		= array(
				'main'      => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'A'
				),
				't2'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.payroll_summary_id = B.payroll_summary_id'
				),
				't3'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'B.attendance_period_hdr_id = C.attendance_period_hdr_id'
				),
				't4'        => array(
						'table'     => $this->rm->tbl_employee_personal_info,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'A.employee_id = D.employee_id'
				),
				't7'        => array(
						'table'     => $this->rm->tbl_employee_identifications,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = G.employee_id AND G.identification_type_id = ' . TIN_TYPE_ID
				),
				't8'        => array(
						'table'     => $this->rm->tbl_employee_identifications,
						'alias'     => 'H',
						'type'      => 'LEFT JOIN',
						'condition' => 'A.employee_id = H.employee_id AND H.identification_type_id = ' . BANKACCT_TYPE_ID
				)
		);
	
		$order_by					= array('full_name' => 'ASC');
		$group_by					= array('A.employee_id');
// 		echo '<pre>';
// 		print_r($fields);
// 		die();
	
		return $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by, $group_by);
	}
	
	public function get_records($fields, $where, $order_by = NULL)
	{
		$tables		 		= array(
				'main'      => array(
						'table'     => $this->rm->tbl_payout_header,
						'alias'     => 'A'
				),
				't2'        => array(
						'table'     => $this->rm->tbl_payout_details,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.payroll_hdr_id = B.payroll_hdr_id'
				),
				't3'        => array(
						'table'     => $this->rm->tbl_payout_summary,
						'alias'     => 'C',
						'type'      => 'JOIN',
						'condition' => 'A.payroll_summary_id = C.payroll_summary_id'
				),
				't4'        => array(
						'table'     => $this->rm->tbl_attendance_period_hdr,
						'alias'     => 'D',
						'type'      => 'JOIN',
						'condition' => 'C.attendance_period_hdr_id = D.attendance_period_hdr_id'
				)
		);
	
		return $this->rm->get_reports_data($fields, $tables, $where, FALSE, $order_by, NULL);
	}
	
	
	// COMPENSATIONS
	public function get_bs($hdr_ids)
	{
		$fields							= array('B.amount', 'B.orig_amount');

		$tables				= array(
				'main'		=> array(
						'table'	=> $this->rm->tbl_param_compensations,
						'alias'	=> 'A'
				),
				't2'		=> array(
						'table'		=> $this->rm->tbl_payout_details,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.compensation_id = B.compensation_id'
				)
		);
		$where							= array();
		$where['A.basic_salary_flag']	= YES;
		$where['B.payroll_hdr_id']		= array($value = explode(",", $hdr_ids), array("IN"));
	
		return	$this->rm->get_reports_data($fields, $tables, $where, FALSE);
	}
	
	// RATES
	public function compute_rates($employee_id, $daily_rate)
	{
		$rates					= array();
		
		$rates['employee_id']	= $employee_id;
		
		//COMPUTE RATE/DAY
		$rates['rate_day']		= round($daily_rate, 4);
		//COMPUTE RATE/HOUR
		$rates['rate_hour']		= round(($rates['rate_day'] / 8), 4);
		//COMPUTE RATE/MINUTE
		$rates['rate_minute']	= round(($rates['rate_hour'] / 60), 4);
		
		return $rates;
	}
	
	//ABSENTS AND UNDERTIME
	public function get_less_amount($attendance_period_hdr_id, $rate)
	{
		$fields 								= array('SUM(tardiness_hr) tardiness_hr', 'SUM(tardiness_min) tardiness_min', 'SUM(undertime_hr) undertime_hr', 'SUM(undertime_min) undertime_min', 'SUM(lwop_hours) lwop_hours');
		$table									= $this->rm->tbl_attendance_period_dtl;
		$where['attendance_period_hdr_id']		= $attendance_period_hdr_id;
		$where['employee_id']					= $rate['employee_id'];
		$record									= $this->rm->get_reports_data($fields, $table, $where, FALSE);
		
		$fields 								= array('COUNT(attendance_status_id) count');
		$where['attendance_status_id']			= ATTENDANCE_STATUS_ABSENT; // REUSE EXISTING WHERE ABOVE
		$absent_count							= $this->rm->get_reports_data($fields, $table, $where, FALSE);
		$absent_hours							= $absent_count['count'] * 8;
		
		// STORES LESS AMOUNTS
		$less_amount		= array();
		
		// LWOP + ABSENT_HOURS
		$total_lwop_hours = $record['lwop_hours'] + $absent_hours;
		
		$remarks			= "";
		$time = 0;
		if(($record['tardiness_hr']!=NULL && $record['tardiness_hr']>0) || ($record['tardiness_min']!=NULL && $record['tardiness_min']>0))
		{
			$time = $record['tardiness_hr'] + ($record['tardiness_min']/60);
		}
		
		if(($record['undertime_hr']!=NULL && $record['undertime_hr']>0) || ($record['undertime_min']!=NULL && $record['undertime_min']>0))
		{
			$time = $time + $record['undertime_hr'] + ($record['undertime_min']/60);
		}
		if($total_lwop_hours > 0)
		{
			$lwop			= $this->count_days_hours_minutes($total_lwop_hours,true);
		}
		if($time > 0)
		{
			$undertime      = $this->count_days_hours_minutes($time,true);
			if($undertime['days'] > 0)
			$lwop['days']   += $undertime['days'];
			$undertime['days'] = 0;
		}
		
		if($lwop)
		{
			$remarks		= $remarks . " " . $this->get_remarks($lwop) . " LWOP<br>";
		}
		if($undertime)
		{
			$remarks		= $remarks . " " .$this->get_remarks($undertime) . " UT";
		}
		
		$time				= $record['tardiness_hr'] + $record['undertime_hr'] + $total_lwop_hours + (($record['tardiness_min'] + $record['undertime_min']) / 60);

		$time_arr			= $this->count_days_hours_minutes($time);
		
		$less_amount['remarks']			= rtrim($remarks, ',');
		$less_amount['day']				= round($time_arr['days'], 4);
		$less_amount['hour']			= round($time_arr['hours'], 4);
		$less_amount['minute']			= round($time_arr['minutes'], 4);
		
		$less_amount['rate_day']		= round(($time_arr['days'] * $rate['rate_day']), 4);
		$less_amount['rate_hour']		= round(($time_arr['hours'] * $rate['rate_hour']), 4);
		$less_amount['rate_minute']		= round(($time_arr['minutes'] * $rate['rate_minute']), 4);
		$less_amount['total_less']		= $less_amount['rate_day'] + $less_amount['rate_hour']	+ $less_amount['rate_minute'];
	
		return $less_amount;
	}
	
	public function count_days_hours_minutes($time, $convert_to_day = true)
	{
	
		$time_arr			= array();
		$days 				= 0;
		$hours 				= 0;
		$minutes			= 0;
	
		if($time>=8 AND $convert_to_day == TRUE)
		{
			$days		= floor($time / 8);
			$mod 		= floor($time/8);
			$time 		= $time - ($mod) * 8;
		}
			
		if($time>=1)
		{
			$hours		= floor($time);
			$time		= $time - $hours;
		}
			
		if($time != 0)
		{
			$minutes 	= $time * 60;
		}
	
		$time_arr['days'] 		= $days;
		$time_arr['hours'] 		= $hours;
		$time_arr['minutes'] 	= $minutes;
	
		return $time_arr;
	}
	
	public function get_remarks($time)
	{
		$remarks = "";
		
		if($time['days'] > 0)
		{
			$days = round($time['days'], 4);
			if($time['days'] > 1)
			{
				$remarks		= $remarks . $days . " days ";
			}
			else
			{
				$remarks		= $remarks . $days . " day ";
			}
		}
		
		
		if($time['hours'] > 0)
		{
			$hours = round($time['hours'], 4);
			if($time['hours'] > 1)
			{
				$remarks		= $remarks . $hours . " hrs ";
			}
			else
			{
				$remarks		= $remarks . $hours . " hr ";
			}
		}
		
		if($time['minutes'] != 0)
		{
			$minutes = round($time['minutes'], 4);
			if($time['minutes'] > 1)
			{
				$remarks		= $remarks . $minutes . " mins ";
			}
			else
			{
				$remarks		= $remarks . $minutes . " min ";
			}
		}
		
		return $remarks;
	}
	
	public function get_basic_compensations($hdr_ids)
	{
			$fields		= array('total_income as orig_amount', 'total_income as amount');
			
			$tables                    = $this->rm->tbl_payout_header;
			$where                     = array();
			$where['payroll_hdr_id'] = array($value = explode(",", $hdr_ids), array("IN"));
	
		return	$this->rm->get_reports_data($fields, $tables, $where, FALSE);
	}
	
	// COMPENSATIONS
	public function get_compensations($hdr_ids, $com_id, $basic_salary_flag=FALSE)
	{
		$fields		= array('A.report_short_code', 'SUM(B.amount) amount', 'SUM(B.orig_amount) orig_amount');
		$group_by 	= NULL;
		$tables				= array(
				'main'		=> array(
						'table'	=> $this->rm->tbl_param_compensations,
						'alias'	=> 'A'
				),
				't2'		=> array(
						'table'		=> $this->rm->tbl_payout_details,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.compensation_id = B.compensation_id'
				)
		);
		$where							= array();
		if($basic_salary_flag)
		{
			$where['A.basic_salary_flag']	= YES;
			$where['B.compensation_id']		= "IS NOT NULL";
		}else
		{
			$where['B.compensation_id']		= $com_id;
			$group_by						= array('A.report_short_code');
		}
		$where['B.payroll_hdr_id']		= array($value = explode(",", $hdr_ids), array("IN"));
	
		return	$this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, $group_by);
	}
	
	public function get_deductions($hdr_ids, $ded)
	{
		$fields		= array('A.deduction_code', 'A.report_short_code', 'B.amount');
			
		$tables				= array(
				'main'		=> array(
						'table'	=> $this->rm->tbl_param_deductions,
						'alias'	=> 'A'
				),
				't2'		=> array(
						'table'		=> $this->rm->tbl_payout_details,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.deduction_id = B.deduction_id'
				)
		);
		$where							= array();
		$where['B.payroll_hdr_id']		= $hdr_ids;
		
		if($in_group)
		{
			$where['A.deduction_code']	= array(explode(",", $ded), array("IN"));
		}else
		{
			$where['B.deduction_id']	= $ded;
		}
		
		return	$this->rm->get_reports_data($fields, $tables, $where, FALSE, NULL, $group_by);
	}
	
	public function get_deduction_groups_codes($params)
	{
		$field							= array('GROUP_CONCAT(DISTINCT sys_param_value) sys_param_value');
		
		$table							= DB_CORE.".".$this->rm->tbl_sys_param;
		$where							= array();
		$where['sys_param_type'] 		= array(explode(",",$params), array('IN'));;	
		
		return	$this->rm->get_reports_data($field, $table, $where, FALSE);
	}
	
	public function get_deduction_groups_ids($ded_in_group_codes)
	{
		$field							= array('GROUP_CONCAT(DISTINCT deduction_id) deduction_ids');
	
		$table							= $this->rm->tbl_param_deductions;
		$where							= array();
		$where['deduction_code'] 		= array(explode(",",$ded_in_group_codes), array('IN'));
	
		return	$this->rm->get_reports_data($field, $table, $where, FALSE);
	}
	
}


/* End of file Atm_alpha_list.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Atm_alpha_list.php */