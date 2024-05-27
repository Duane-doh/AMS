<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_tax_diff extends Main_Controller {
	
	private $form2316;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('common_model');
		$this->load->model('payroll_tax_model');

		$this->form2316 = modules::load('main/payroll_form_2316');
	}
	
	public function compute_tax_refund($personnels, $payout_date, $tax_table_flag=TAX_ANNUALIZED, $payroll_summary_id=NULL, $included_employees=array(), $sys_param_stat_approved=NULL, $save=FALSE, $project_tax=FALSE, $sys_params=array())
	{
		RLog::info("START: compute_tax_refund [$payroll_summary_id] [$payout_date] [$tax_table_flag]");

		if ( empty($sys_params[PARAM_COMPENSATION_ID_TAX_REFUND_ANNUAL]) OR empty($sys_params[PARAM_COMPENSATION_CODE_TAX_REFUND_ANNUAL])
				OR empty($sys_params[PARAM_COMPENSATION_ID_TAX_REFUND_MONTHLY]) OR empty($sys_params[PARAM_COMPENSATION_CODE_TAX_REFUND_MONTHLY]) )
			throw new Exception('Tax Refund ID: ' . $this->lang->line('param_not_defined'));		
		if ( empty($payout_date) )
			throw new Exception('Compute Tax Refund [Payout Date]: ' . $this->lang->line('param_not_defined'));

			
		$tax_refund_id	= $sys_params[PARAM_COMPENSATION_ID_TAX_REFUND_ANNUAL];
		$tax_bir_id		= $sys_params[PARAM_DEDUCTION_ST_BIR_ID];
			
		$employee_comp = array();
		
		try 
		{
			// COMPUTE WITHHOLDING TAX
			$dt 			= new DateTime($payout_date);
			$payout_year	= $dt->format('Y');
			
			$form_2316_ids = $this->form2316->construct_form_2316($tax_table_flag, $payout_date, NULL, $payroll_summary_id, $included_employees, $sys_param_stat_approved, $save, $project_tax);
			
			RLog::info('--S: RECOMPUTE 2316 FOR REFUND--');
				RLog::info($form_2316_ids);
			RLog::info('--E: RECOMPUTE 2316 FOR REFUND--');
			
			$form_2316 = $this->payroll_tax_model->get_form_2316_by_ids('tax_diff', NULL, $included_employees, $payout_year);
			$compensation_personnels = array();
			foreach ($personnels as $personnel)
			{
				$diff_amt = isset($form_2316[$personnel['employee_id']]) ? $form_2316[$personnel['employee_id']] : 0;
				$diff_amt = ($diff_amt * -1); 
				//$diff_amt = ($diff_amt < 0 ? ($diff_amt * -1) : $diff_amt);
				$personnel['amount'][KEY_AMOUNT] = $diff_amt;
				
				$compensation_personnels[] = $personnel;
			}
			
			RLog::info('--S3: RECOMPUTE 2316 FOR REFUND--');
				RLog::info($form_2316);
				//RLog::info($compensation_personnels);
			RLog::info('--E3: RECOMPUTE 2316 FOR REFUND--');

			RLog::info("END: compute_tax_refund");		
			
			return $compensation_personnels;			
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function re_compute_tax($personnels, $payout_date, $included_employees=array(), $sys_param_stat_approved=NULL, $save=FALSE, $project_tax=FALSE, $monthly_only=FALSE, $sys_params=array())
	{
		RLog::info("START: re_compute_tax [$payout_date][$sys_param_stat_approved][$save][$project_tax][$monthly_only]");
		
		if ( empty($payout_date) )
			throw new Exception('Re-Compute Tax [Payout Date]: ' . $this->lang->line('param_not_defined'));

		//$tax_refund_id	= $sys_params[PARAM_COMPENSATION_ID_TAX_REFUND_ANNUAL];
		$tax_bir_id		= $sys_params[PARAM_DEDUCTION_ST_BIR_ID];
			
		$employee_comp = array();
		
		try 
		{
			$dt 			= new DateTime($payout_date);
			$payout_year	= $dt->format('Y');
			$payout_month	= intval($dt->format('m'));			

			$form_2316_val	= array('form_2316_id', 'tax_due', 'total_tax_withheld');			
			
			// get current form 2316 (before re-compute)
			$curr_form_2316 = $this->payroll_tax_model->get_form_2316_by_ids($form_2316_val, NULL, $included_employees, $payout_year, $payout_month, FALSE);
			
			RLog::info("== curr_form_2316 ==");
			RLog::info($curr_form_2316);
			
			$tax_table_flag = TAX_ANNUALIZED;
			$form_2316_ids = $this->form2316->construct_form_2316($tax_table_flag, $payout_date, NULL, NULL, $included_employees, $sys_param_stat_approved, $save, $project_tax, $monthly_only);
			
			$new_form_2316 = $this->payroll_tax_model->get_form_2316_by_ids($form_2316_val, $form_2316_ids, NULL, 0, $payout_month, $monthly_only);
			
			RLog::info("== new_form_2316 ==");
			RLog::info($new_form_2316);			

			$compensation_personnels = array();
			$processed_employees = array();
			
			$remaining_months = WORKING_MONTHS - $payout_month + 1;
			RLog::info("recompute tax [$remaining_months]");
		
			foreach ($personnels as $personnel)
			{
				$emp_id = $personnel['employee_id'];
				
				if ( ! isset($processed_employees[$emp_id]))
				{
					$old_annual_wt_amount	= isset($curr_form_2316[$emp_id]['tax_due']) ? $curr_form_2316[$emp_id]['tax_due'] : 0;
					
					if ($old_annual_wt_amount <= 0)
					{
						$deductions = array();
						$deduction_amount = 0;
						if (isset($personnel['deductions']))
						{
							$deductions = $personnel['deductions'];
							$deduction_amount = $personnel['deduction_amount'];
						}
						$deductions[$tax_bir_id] = 0;
						
						$personnel['deductions'] = $deductions;
						$personnel['deduction_amount'] = $deduction_amount;						
						
						$compensation_personnels[] = $personnel;
						$processed_employees[$emp_id] = YES;
						continue;
					}
					
					$old_tax_withheld		= isset($curr_form_2316[$emp_id]['total_tax_withheld']) ? $curr_form_2316[$emp_id]['total_tax_withheld'] : 0;
					$old_diff_amt			= $old_annual_wt_amount - $old_tax_withheld;

					//$old_annual_wt_amount	= isset($curr_form_2316[$emp_id]) ? $curr_form_2316[$emp_id] : 0;
					//$old_monthly_wt_amount	= round( ($old_annual_wt_amount/$remaining_months), 2, PHP_ROUND_HALF_UP);					
					
					$new_annual_wt_amount	= isset($new_form_2316[$emp_id]['tax_due']) ? $new_form_2316[$emp_id]['tax_due'] : 0;
					$new_diff_amt			= $new_annual_wt_amount - $old_tax_withheld;
					
					$diff_amt				= ($new_diff_amt - $old_diff_amt);
					
					RLog::info("== diff_amt before div by remaining months [$old_diff_amt] [$new_annual_wt_amount][$new_diff_amt] [$diff_amt]");
					
					if ($diff_amt >= 0)
					{
						$diff_amt	= round ( ($diff_amt / $remaining_months), 2, PHP_ROUND_HALF_UP );
						
						RLog::info("== diff_amt after div by remaining months [$diff_amt]");						
						
						$deductions = array();
						$deduction_amount = 0;
						if (isset($personnel['deductions']))
						{
							$deductions = $personnel['deductions'];
							$deduction_amount = $personnel['deduction_amount'];
						}
						$deductions[$tax_bir_id] = $diff_amt;
						$deduction_amount += $diff_amt;
						
						$personnel['deductions'] = $deductions;
						$personnel['deduction_amount'] = $deduction_amount;
						
						if ($diff_amt >= 0)
						{
							// update form_2316_details tax withheld
							$this->payroll_tax_model->update_form_2316_tax_withheld($new_form_2316[$emp_id]['form_2316_id'], $payout_year, $payout_month, $diff_amt);
						}						
					}
				}
				
				$compensation_personnels[] = $personnel;
				
				$processed_employees[$emp_id] = YES;
			}

			RLog::info("END: re_compute_tax");		
			
			return $compensation_personnels;			
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}	
	
}
	
/* End of file Payroll_tax_diff */
/* Location: ./application/modules/main/controllers/Payroll_tax_diff.php */