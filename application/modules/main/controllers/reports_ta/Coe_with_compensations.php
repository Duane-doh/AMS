<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coe_with_compensations extends Main_Controller {
	private $payroll_common;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'rm');

		$this->load->model('payroll_process_model', 'payroll_process');
		$this->load->model('common_model', 'common');
		$this->payroll_common = modules::load('main/payroll_common');
	}	
	
	public function generate_report_data($params, $with=TRUE)
	{
		try
		{
			$param = $params['employee'];
			$code  = $params['unique_code'];
			if($with) 
			{
				$sys_params = $this->payroll_process->get_sys_gen_params();
				$fields = array('A.employee_id, 
								IF(B.inherit_parent_id_flag = "Y",D.compensation_name,B.compensation_name) compensation_name,
								B.compensation_id, B.compensation_code, B.compensation_type_flag, 
								B.amount, B.multiplier_id, B.rate, B.frequency_id,
								B.general_payroll_flag, B.special_payroll_flag, B.basic_salary_flag,
								B.tenure_rqmt_flag, B.tenure_rqmt_val, B.pro_rated_flag,
								C.employ_salary_grade, C.employ_salary_step, C.employ_monthly_salary'
								);

				$table = array(
					'main' => array(
						'table' => $this->rm->tbl_employee_compensations,
						'alias' => 'A'
					),
					't1' => array(
						'table' => $this->rm->tbl_param_compensations,
						'alias' => 'B',
						'type' => 'JOIN',
						'condition' => 'A.compensation_id = B.compensation_id'
					),
					't2' => array(
						'table' => $this->rm->tbl_employee_work_experiences,
						'alias' => 'C',
						'type' => 'JOIN',
						'condition' => 'A.employee_id = C.employee_id AND C.active_flag = "Y"'
					),
					't3' => array(
						'table' => $this->rm->tbl_param_compensations,
						'alias' => 'D',
						'type' => 'LEFT JOIN',
						'condition' => 'B.parent_compensation_id = D.compensation_id'
					)
				);
				$where                           = array();
				$where['A.employee_id']          = $param;
				$where['C.active_flag']          = YES;
				$where['A.end_date']             = 'IS NULL';
				$where['B.general_payroll_flag'] = YES;
				$order_by = array('B.compensation_id' => 'asc');
				$compensations = $this->rm->get_reports_data($fields,$table,$where,TRUE,$order_by);

				

				foreach ($compensations as $key => $cmpnstn) 
				{
					$compensation_id = $cmpnstn['compensation_id'];
					$frequency          = $cmpnstn['frequency_id'];
					
					$prorated_rates 	= array();
					if ($cmpnstn['pro_rated_flag'] != PRORATE_NA)
					{
						$field          = array('compensation_id', 'from_val', 'to_val', 'percentage', 'separated_flag') ;
						$table          = $this->common->tbl_param_compensation_prorated;
						$where          = array('compensation_id' => $compensation_id);
						$order_by		= array('from_val' => 'ASC'); 
						$prorated_rates	= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
					}	
					
					$year = date('Y');
					$covered_date_from = $year.'-01-01'; // first day of the year
					$covered_date_to = $year.'-12-31'; // last day of the year
					
					
					$ret_amount = array();
					switch ($cmpnstn['compensation_type_flag']) 
					{
						case COMPENSATION_TYPE_FLAG_FIXED:
                            $tenure_rqmt_flag     = isset($cmpnstn['tenure_rqmt_flag']) ? $cmpnstn['tenure_rqmt_flag'] : '';
                            if ( $tenure_rqmt_flag == TENURE_RQMT_DAYS_PRESENT AND $frequency == FREQUENCY_DAILY)
                                // $working_days = 22;
                                $working_days = 249 / 12;
                            else
                                $working_days = 1;
                            $amount = isset($cmpnstn['amount']) ? $cmpnstn['amount'] : 0.00;
                            $amount    = $amount * $working_days;
                            $fixed_amount_arr = array(KEY_AMOUNT => $amount, KEY_LESS_AMOUNT => 0.00, KEY_ORIG_AMOUNT => $amount);

                            $ret_amount[$compensation_id] = $fixed_amount_arr;
                        break;
					
						case COMPENSATION_TYPE_FLAG_VARIABLE:
							#VARIABLE AMOUNT
							$ret_amount[$compensation_id][KEY_AMOUNT]	= $this->payroll_common->compute_variable_compensation_type($cmpnstn, $covered_date_to);
						break;
						
						case COMPENSATION_TYPE_FLAG_SYSTEM:
							#SYSTEM GENERATED AMOUNT
							
							// IF BASIC SALARY
							// GET VALUE FROM employee_work_experiences.employ_monthly_salary
							if ($cmpnstn['compensation_code'] == $sys_params[PARAM_COMPENSATION_BASIC_SALARY])
							{
								$ret_amount[$compensation_id][KEY_AMOUNT] = $cmpnstn['employ_monthly_salary'];
							}
							else
							{
								$ret_amount	= $this->payroll_common->get_system_generated_amount($cmpnstn, NULL, 
										$covered_date_from, $covered_date_to, 
										NULL, $sys_params, 
										array(), NULL);

							}
						break;
					}
					
					if ($cmpnstn['pro_rated_flag'] != PRORATE_NA)
					{
						$tenure_in_months	= $cmpnstn['tenure_rqmt_val'];
						$salary_grade		= $cmpnstn['employ_salary_grade'];
						
						$tenure_num = $tenure_in_months;
						if ($cmpnstn['pro_rated_flag'] == PRORATE_DAY)
						{
							$working_days = $sys_params[PARAM_WORKING_DAYS];
							$lwop_count = ( ! empty($attendance_count[ATTENDANCE_STATUS_ABSENT]) ? $attendance_count[ATTENDANCE_STATUS_ABSENT] : 0);
							$lwop_count += ( ! empty($attendance_count[ATTENDANCE_STATUS_LEAVE_WOP]) ? $attendance_count[ATTENDANCE_STATUS_LEAVE_WOP] : 0);
							$tenure_num = $working_days - $lwop_count;
						}

						$ret_amount[$compensation_id] = $this->payroll_common->compute_prorated_value($cmpnstn, $ret_amount[$compensation_id][KEY_AMOUNT], $tenure_num, $salary_grade, $prorated_rates);
					}	
					$compensations[$key]['ret_amount'] = $ret_amount;

					$compensations[$key]['ret_amount'][$compensation_id]['amount'] = $compensations[$key]['ret_amount'][$compensation_id]['amount'] * 12;
				}


				$data = $this->_get_categorized_compensations($compensations);

				$data['special_ben'] = $this->rm->get_coe_special_benefits($param);
				
				$data['comp_year'] = date('Y') - 1;
			}
			$where = array();
			$where['employee_id'] = $param;
			$where['active_flag'] = 'N';
			$where['separation_mode_id'] = 'IS NOT NULL';
			$where['employ_type_flag'] = array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));

			$check_separation = $this->rm->get_reports_data(array('count(employee_id) cnt'),$this->rm->tbl_employee_work_experiences,$where,FALSE);

			$where = array();
			$where['employee_id'] = $param;
			$where['active_flag'] = 'Y';
			$where['employ_type_flag'] = array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
			
			$data['separated_flag'] = $separated_flag = ($check_separation['cnt'] > 0 ) ? TRUE : FALSE;

			$check_active_work_exp = $this->rm->get_reports_data(array('count(employee_id) cnt'),$this->rm->tbl_employee_work_experiences,$where,FALSE);
			$active_work_exp_flag = ($check_active_work_exp['cnt'] > 0 ) ? TRUE : FALSE;

			if($active_work_exp_flag)
			{
				$data['separated_flag'] = $separated_flag = FALSE;
			}
			else
			{
				$data['separated_flag'] = $separated_flag = TRUE;

			}
			
			$fields = array("CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/' OR B.middle_name IS NULL), '', CONCAT(' ', LEFT(B.middle_name,1), '. ')) , B.last_name, ' ', IF(B.ext_name=''  OR B.ext_name IS NULL, '', CONCAT(' ', B.ext_name))) AS employee_name, 
							LOWER(A.employ_position_name) AS employ_position_name,
							LOWER(C.employment_status_name) AS employment_status_name,
							LOWER(A.employ_office_name) AS employ_office_name,
							B.last_name,
							B.gender_code,
							A.employ_type_flag,
							B.civil_status_id"
							);
			$table = array(
				'main' => array(
					'table' => $this->rm->tbl_employee_work_experiences,
					'alias' => 'A'
				),
				't1' => array(
					'table' => $this->rm->tbl_employee_personal_info,
					'alias' => 'B',
					'type' => 'JOIN',
					'condition' => 'A.employee_id = B.employee_id'
				),
				't2' => array(
					'table' => $this->rm->tbl_param_employment_status,
					'alias' => 'C',
					'type' => 'JOIN',
					'condition' => 'A.employment_status_id = C.employment_status_id'
				)
			);
			$where = array();
			$where['A.employee_id'] = $param;
			
			if($separated_flag)
			{
				$where['A.active_flag'] = 'N';
				$where['A.separation_mode_id'] = 'IS NOT NULL';
			}
			else
			{
				$where['A.active_flag'] = 'Y';
				$where['A.employ_end_date'] = 'IS NULL';
			}
			
			$where['A.employ_type_flag'] = array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
			
			
			$data['employee_info'] = $this->rm->get_reports_data($fields,$table,$where,FALSE);
			
			// dacorrea check if current work exp is permanent, if permanent will not count JO work exp as etd
			$employ_type_flag = $this->rm->get_employee_latest_work_experience($param);

			$fields = array('MIN(A.employ_start_date) as employ_start_date',
			'MAX(A.employ_end_date) as employ_end_date'
			);
			$where = array();
			$where['A.employee_id'] = $param;

			if($employ_type_flag['employ_type_flag'] == 'JO')
			{
				$where['A.employ_type_flag'] = array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT,DOH_JO), array("IN"));
			}
			else
			{
				$where['A.employ_type_flag'] = array($value = array(DOH_GOV_APPT,DOH_GOV_NON_APPT), array("IN"));
			}

			$data['employment_date'] = $this->rm->get_reports_data($fields,$table,$where,FALSE);
			$data['code'] 		   = $code;
			$employee_id           = $params['certified_by'];
			$data['certified_by']  = $this->common->get_report_signatory_details($employee_id);


			// ==========davcorrea position/eligibility for salutation



			$data['position']  = $this->rm->get_employee_salutation($param);

			$fields 				= array('eligibility_type_id');
			$table 					= 'employee_eligibility';
			$where 					= array();
			$where['employee_id'] 	= $param;
			$result 				= $this->cm->get_general_data($fields, $table, $where, TRUE);
			foreach($result as $r)
			{
				$data['position']['eligibility'][] = $r['eligibility_type_id'];			
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

	private function _get_categorized_compensations($compensations) 
	{
		try
		{
			$data = array();
			foreach ($compensations as $key => $value) {
				if($value['general_payroll_flag'] == 'Y') $data['general_payroll_list'][] = $value;
				else $data['special_payroll_list'][] = $value;
			}
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		return $data;
	}

}


/* End of file Gsis_membership_form.php */
/* Location: ./application/modules/main/controllers/reports/hr/Gsis_membership_form.php */