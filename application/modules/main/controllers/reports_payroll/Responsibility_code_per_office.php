<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsibility_code_per_office extends Main_Controller {
	private $gen_payroll_jo;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_payroll_model', 'rm');
		$this->gen_payroll_jo = modules::load('main/reports_payroll/general_payroll_alpha_list_for_jo');
	}	
	
	public function generate_report_data($params)
	{
		try
		{
			$data 		= array();	
			// $field 		= array("CONCAT(DATE_FORMAT(date_from,'%M %d'), ' - ', DATE_FORMAT(date_to,'%d, %Y')) payroll_period");
			// ====================== jendaigo : start : include date_from and date_to ============= //
			$field 		= array("CONCAT(DATE_FORMAT(date_from,'%M %d'), ' - ', DATE_FORMAT(date_to,'%d, %Y')) payroll_period", 
							    "date_from", "date_to");
			// ====================== jendaigo : end : include date_from and date_to ============= //					
								
			$table 		= $this->rm->tbl_attendance_period_hdr;
			$where 		= array();
			$where['attendance_period_hdr_id']	= $params['payroll_period'];
			$data['period_detail']				= $this->rm->get_reports_data($field, $table, $where, FALSE);

			/* $fields = array('C.employ_office_id', 'D.responsibility_center_code rc_code', 'B.employee_name emp_name', 'C.employ_position_name job_desc',
 					'F.employment_status_code es', 'E.org_code', 'B.total_income gross_pay');
			*/		
			// ====================== jendaigo : start : include payout_header.employee_id, employee_work_experiences variables ============= //
			$fields = array('C.employ_office_id', 'D.responsibility_center_code rc_code', 'B.employee_name emp_name', 
					'B.employee_id emp_id', 'C.employ_position_name job_desc', 'C.employ_start_date', 'C.employ_end_date', 
					'C.employ_salary_grade emp_sg', 'C.employ_monthly_salary emp_mosal',
 					'F.employment_status_code es', 'E.org_code', 'B.total_income net_salary');	
			// ====================== jendaigo : end : include payout_header.employee_id, employee_work_experiences variables ============= //
			
			$tables = array(
					'main' => array(
							'table'		=> 	$this->rm->tbl_payout_summary,
							'alias'		=>  'A'
					),
					't2' => array(
						'table' 		=> $this->rm->tbl_payout_header,
						'alias'			=> 'B',
						'type'			=> 'JOIN',
						'condition'		=> 'A.payroll_summary_id = B.payroll_summary_id'
					),
					't3' => array(
							'table' 	=> $this->rm->tbl_employee_work_experiences,
							'alias'		=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'B.employee_id = C.employee_id'
					),
					't4' => array(
							'table' 	=> $this->rm->tbl_param_offices,
							'alias'		=> 'D',
							'type'		=> 'JOIN',
							'condition'	=> 'C.employ_office_id = D.office_id'
					),
					't5' => array(
							'table' 	=> $this->rm->tbl_param_offices,
							'alias'		=> 'E',
							'type'		=> 'JOIN',
							'condition'	=> 'C.admin_office_id = E.office_id'
					),
					't6' => array(
							'table' 	=> $this->rm->tbl_param_employment_status,
							'alias'		=> 'F',
							'type'		=> 'JOIN',
							'condition'	=> 'C.employment_status_id = F.employment_status_id'
					)
			);
			
			$office_list = $this->rm->get_office_child('', $params['office_list']);
			
			$where			 						= array();
			$where['A.attendance_period_hdr_id'] 	= $params['payroll_period'];
			$where['C.employ_office_id']            = array($office_list, array('IN'));
			// $where['D.active_flag']					= YES;
			
			// ====================== jendaigo : start : modify getting of salary based on work exp effectivity date ============= //
			$key 									= 'IF(c.employ_start_date IS NULL, current_date(), c.employ_start_date)';
			$where[$key] 							= array($data['period_detail']['date_to'], array("<="));
			$key									= 'IF(c.employ_end_date IS NULL, current_date(), c.employ_end_date)';
			$where[$key] 							= array($data['period_detail']['date_from'], array(">="));
			// ====================== jendaigo : end : modify getting of salary based on work exp effectivity date ============= //

			$order_by	= array('emp_name' => 'ASC');
			// $data['results'] = $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by);
			
			// ====================== jendaigo : start : get previous sg based on work exp effectivity date if started in the middle of cutoff ============= //
			$results = $this->rm->get_reports_data($fields, $tables, $where, TRUE, $order_by);
			
			foreach ($results as $result)
			{
				if($emp_id != $result['emp_id'])
					$data['results'][] = $result;
				
				$emp_id = $result['emp_id'];
			}
			// ====================== jendaigo : start : get previous sg based on work exp effectivity date if started in the middle of cutoff ============= //

			// ====================== jendaigo : start : add queries for JO ============= //
			//GET IF EMPLOYEE STATUS IS JO
			if($params['payroll_type'] == 3)
			{
				//GET EMPLOYEE IDs
				$field	 = array('GROUP_CONCAT(DISTINCT B.employee_id) employee_ids');
				$emp_ids = $this->rm->get_reports_data($field, $tables, $where, FALSE);
				$emp_ids = explode(',', $emp_ids['employee_ids']);
				
				//GET EMPLOYEE RESPCODE
				$data['respcodes']	= array();
				$fields 			= array('A.employee_id', 'A.responsibility_center_code','B.responsibility_center_desc');
				$tables = array(
							'main' => array(
									'table'		=> $this->rm->tbl_employee_responsibility_codes,
									'alias'		=> 'A'
							),
							't2' => array(
								'table' 		=> $this->rm->tbl_param_responsibility_centers,
								'alias'			=> 'B',
								'type'			=> 'JOIN',
								'condition'		=> 'A.responsibility_center_code = B.responsibility_center_code'
							),
							't3' => array(
								'table' 		=> $this->rm->tbl_attendance_period_hdr,
								'alias'			=> 'C',
								'type'			=> 'JOIN',
								'condition'		=> 'IF(A.start_date IS NULL, current_date(), A.start_date) <= C.date_to AND 
													IF(A.end_date IS NULL, current_date(), A.end_date) >= C.date_to'
							)
					);
				
				$where 									= array();
				$where['A.employee_id']  				= array($emp_ids, array('IN'));
				$where['C.attendance_period_hdr_id']  	= $params['payroll_period'];
				$respcodes								= $this->rm->get_reports_data($fields, $tables, $where, TRUE);
				
				foreach ($respcodes as $respcode)
				{
					$data['respcodes'][$respcode['employee_id']] = $respcode;
				}
				
				//GET UACSCODE
				$data['uacscodes']	= array();
				$sys_param			= $this->rm->get_sysparam_value(SYS_PARAM_TYPE_REPORT_UACS_JO);
				$fields 			= array('uacs_object_code', 'account_title');
				$table  			= $this->rm->tbl_param_uacs_object_codes;
				
				$where 						= array();
				$where['uacs_object_code'] 	= $sys_param['sys_param_value'];
				$data['uacscodes']			= $this->rm->get_reports_data($fields, $table, $where, FALSE);
				
				// GET COMPENSATION PREMIUM RATE
				$prem_code		 			= $this->rm->get_sysparam_value(PARAM_COMPENSATION_PREMIUM);
				$fields 					= array('rate', 'report_short_code');
				$where						= array();
				$where['report_short_code']	= $prem_code['sys_param_value'];
				$where['active_flag']		= YES;
				$tables						= $this->rm->tbl_param_compensations;
				$comp_prem					= $this->rm->get_reports_data($fields, $tables, $where, TRUE, NULL, NULL);
				$data['comp_prem'] 			= $comp_prem[0]['rate'];
				
				// GET TOTAL AMOUNT OF ABS/LATE/TARDINESS
				$fields 					= array('B.employee_id', 'B.daily_rate', 'C.less_amount');
				$where						= array();
				$where['A.attendance_period_hdr_id']  = $params['payroll_period'];
				$where['B.employee_id']  	= array($emp_ids, array('IN'));
				$sys_param					= $this->rm->get_sysparam_value(COMPENSATION_ID_BASIC_SALARY);
				$where['C.compensation_id'] = $sys_param['sys_param_value'];
				
				$tables = array(
						'main' => array(
								'table'		=> 	$this->rm->tbl_payout_summary,
								'alias'		=>  'A'
						),
						't2' => array(
							'table' 		=> $this->rm->tbl_payout_header,
							'alias'			=> 'B',
							'type'			=> 'JOIN',
							'condition'		=> 'A.payroll_summary_id = B.payroll_summary_id'
						),
						't3' => array(
								'table' 	=> $this->rm->tbl_payout_details,
								'alias'		=> 'C',
								'type'		=> 'JOIN',
								'condition'	=> 'B.payroll_hdr_id = C.payroll_hdr_id'
						)
				);
				$less_attendance = $this->rm->get_reports_data($fields, $tables, $where, TRUE);
				
				foreach ($less_attendance as $less_attend)
				{
					$data['less_attendance'][$less_attend['employee_id']] = $less_attend;
					$rates[$less_attend['employee_id']] = $this->gen_payroll_jo->compute_rates($less_attend['employee_id'], $less_attend['daily_rate']);
				}

				foreach($rates as $key=>$rate)
				{
					$data['less_amounts'][$key]	= $this->gen_payroll_jo->get_less_amount($params['payroll_period'], $rate);
				}

				// GET DEDUCTIONS
				$fields 					= array('A.employee_id', 'A.deduction_id', 'C.amount', 'G.amount dtl_amount', 'D.other_deduction_detail_id', 'D.other_deduction_detail_value', 'I.deduction_detail_type_code', 'H.employee_deduction_detail_detail_id ededdtl_dtl_id', 'J.employee_deduction_detail_detail_id ededdtl_dtl_id2', );
				$where						= array();
				$where['A.employee_id']  	= array($emp_ids, array('IN'));
				$where['F.attendance_period_hdr_id']  	= $params['payroll_period'];
				
				$tables = array(
							'main' => array(
									'table'		=> $this->rm->tbl_employee_deductions,
									'alias'		=> 'A'
							),
							't2' => array(
								'table' 		=> $this->rm->tbl_payout_header,
								'alias'			=> 'B',
								'type'			=> 'JOIN',
								'condition'		=> 'A.employee_id = B.employee_id'
							),
							't3' => array(
								'table' 		=> $this->rm->tbl_payout_details,
								'alias'			=> 'C',
								'type'			=> 'JOIN',
								'condition'		=> 'B.payroll_hdr_id = C.payroll_hdr_id AND 
													A.deduction_id = C.deduction_id'
							),
							't4' => array(
								'table' 		=> $this->rm->tbl_employee_deduction_other_details,
								'alias'			=> 'D',
								'type'			=> 'LEFT JOIN',
								'condition'		=> 'A.employee_deduction_id = D.employee_deduction_id'
							),
							't5' => array(
								'table' 		=> $this->rm->tbl_param_other_deduction_details,
								'alias'			=> 'E',
								'type'			=> 'LEFT JOIN',
								'condition'		=> 'D.other_deduction_detail_id = E.other_deduction_detail_id'
							),
							't6' => array(
								'table' 		=> $this->rm->tbl_payout_summary,
								'alias'			=> 'F',
								'type'			=> 'JOIN',
								'condition'		=> 'B.payroll_summary_id = F.payroll_summary_id'
							),
							't7' => array(
								'table' 		=> $this->rm->tbl_employee_deduction_details,
								'alias'			=> 'G',
								'type'			=> 'LEFT JOIN',
								'condition'		=> 'G.employee_deduction_id = A.employee_deduction_id'
							),
							't8' => array(
								'table' 		=> $this->rm->tbl_employee_deduction_detail_details,
								'alias'			=> 'H',
								'type'			=> 'LEFT JOIN',
								'condition'		=> 'H.employee_deduction_detail_id = G.employee_deduction_detail_id'
							),
							't9' => array(
								'table' 		=> $this->rm->tbl_param_deduction_detail_types,
								'alias'			=> 'I',
								'type'			=> 'LEFT JOIN',
								'condition'		=> 'I.deduction_detail_type_id = H.deduction_detail_type_id'
							),
							't10' => array(
								'table' 		=> $this->rm->tbl_employee_deduction_paid_count_details,
								'alias'			=> 'J',
								'type'			=> 'LEFT JOIN',
								'condition'		=> 'J.employee_deduction_detail_detail_id = H.employee_deduction_detail_detail_id 
													AND J.attendance_period_hdr_id = F.attendance_period_hdr_id'
							)
					);
				$deductions					= $this->rm->get_reports_data($fields, $tables, $where, TRUE);

				foreach($deductions as $ded)
				{
					if(!empty($ded['ededdtl_dtl_id']))
					{
						if($ded['ededdtl_dtl_id'] == $ded['ededdtl_dtl_id2'])
						$data['deductions'][$ded['employee_id']][] = $ded;
					}
					else
					{
						$data['deductions'][$ded['employee_id']][] = $ded;
					}
				}
			}
			// ====================== jendaigo : end : add queries for JO ============= //

			//SEPERATE RESULTS BY OFFICE
			$data['records']	= array();
			$fields 			= array('B.office_id org_id', 'A.name','C.responsibility_center_desc');
			$tables = array(
					'main' => array(
							'table'		=> 	$this->rm->DB_CORE.'.'.$this->rm->tbl_organizations,
							'alias'		=>  'A'
					),
					't2' => array(
						'table' 		=> $this->rm->tbl_param_offices,
						'alias'			=> 'B',
						'type'			=> 'JOIN',
						'condition'		=> 'A.org_code = B.org_Code'
					),
					't3' => array(
						'table' 		=> $this->rm->tbl_param_responsibility_centers,
						'alias'			=> 'C',
						'type'			=> 'LEFT JOIN',
						'condition'		=> 'B.responsibility_center_code = C.responsibility_center_code'
					)
			);
			
			$where 					= array();
				
			foreach ($office_list as $office)
			{
				$where['B.office_id']		= $office;
				$data['organizations'][]	= $this->rm->get_reports_data($fields, $tables, $where, FALSE);
				
				foreach($data['results'] as $result)
				{
					if($office == $result['employ_office_id'])
					{
							$data['records'][$office][] = $result;
					}
				}
				
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
	
}


/* End of file Responsibility_code_per_office.php */
/* Location: ./application/modules/main/controllers/reports/payroll/Bir_2316_certificate_of_compensation_payment_tax_withheld.php */