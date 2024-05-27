<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_form_2307 extends Main_Model {

	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	private $db_core   = DB_CORE;
	
	public function __construct() {
		parent:: __construct();

		$this->load->model('payroll_tax_model', 'payroll_tax');
		$this->load->model('common_model', 'common');
		
		$this->permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	}
	
	public function compute_withholding_tax_2307($year, $month, $payroll_summary_id, $param_data, $employees=NULL)
	{
		$deductions_dtl = array();
		
		try
		{
			// get BIR code
			$sys_param_types	= array(PARAM_DEDUCTION_ST_BIR_EWT, PARAM_DEDUCTION_ST_BIR_VAT);
			$sys_param_values	= $this->common->get_sys_param_value($sys_param_types, TRUE);
			if( ! empty($sys_param_values))
				$sys_param_values = set_key_value($sys_param_values, 'sys_param_type', 'sys_param_value', FALSE);
			$sys_param_bir_ewt 	= (isset($sys_param_values[PARAM_DEDUCTION_ST_BIR_EWT]) ? $sys_param_values[PARAM_DEDUCTION_ST_BIR_EWT] : NULL);			
			$sys_param_bir_vat 	= (isset($sys_param_values[PARAM_DEDUCTION_ST_BIR_VAT]) ? $sys_param_values[PARAM_DEDUCTION_ST_BIR_VAT] : NULL);
			
			// get BIR PK
			$fields 				= array('deduction_code', 'deduction_id');
			$where 					= array();
			$where['deduction_code']= array(array($sys_param_bir_ewt, $sys_param_bir_vat), array('IN'));
			$deduction_rec			= $this->common->get_general_data($fields, $this->common->tbl_param_deductions, $where, TRUE);
			if( ! empty($deduction_rec))
				$deduction_rec = set_key_value($deduction_rec, 'deduction_code', 'deduction_id', FALSE);
			$sys_param_bir_ewt_id	= isset($deduction_rec[$sys_param_bir_ewt]) ? $deduction_rec[$sys_param_bir_ewt] : NULL;
			$sys_param_bir_vat_id	= isset($deduction_rec[$sys_param_bir_vat]) ? $deduction_rec[$sys_param_bir_vat] : NULL;
			
			if (empty($sys_param_bir_ewt) OR empty($sys_param_bir_ewt_id))
				throw new Exception('Deduction Code/ID for BIR EWT: ' . $this->lang->line('sys_param_not_defined'));

			// get Tax Details
			$tax_details 		= $this->payroll_tax->get_form_2307_info($payroll_summary_id, $year, $month, $employees);
			/*
			RLog::info("Payroll_form_2307.compute_withholding_tax_2307 tax_details - ");
			RLog::info($tax_details);
			*/
			
			foreach ($tax_details as $tax)
			{
				$monthly_wt_amount = $tax['tax_withheld_2307'];
				$deductions_dtl[$tax['payroll_hdr_id']][]	=	array(
					KEY_EMPLOYEE_ID			=> $tax['employee_id'],
					KEY_PAYROLL_HDR_ID 		=> $tax['payroll_hdr_id'],
					KEY_DEDUCTION_ID 		=> $sys_param_bir_ewt_id,
					KEY_RAW_DEDUCTION_ID	=> $sys_param_bir_ewt_id,
					KEY_EFFECTIVE_DATE 		=> '',
					KEY_AMOUNT				=> $monthly_wt_amount,
					KEY_EMPLOYER_AMOUNT		=> NULL,
					KEY_REFERENCE_TEXT		=> NULL,
					KEY_PRIORITY_NUM		=> 0
				);

				if ( ! empty($sys_param_bir_vat_id))
				{
					$monthly_wt_amount = $tax['tax_withheld_2306'];
					$deductions_dtl[$tax['payroll_hdr_id']][]	=	array(
						KEY_EMPLOYEE_ID			=> $tax['employee_id'],
						KEY_PAYROLL_HDR_ID 		=> $tax['payroll_hdr_id'],
						KEY_DEDUCTION_ID 		=> $sys_param_bir_vat_id,
						KEY_RAW_DEDUCTION_ID	=> $sys_param_bir_vat_id,
						KEY_EFFECTIVE_DATE 		=> '',
						KEY_AMOUNT				=> $monthly_wt_amount,
						KEY_EMPLOYER_AMOUNT		=> NULL,
						KEY_REFERENCE_TEXT		=> NULL,
						KEY_PRIORITY_NUM		=> 0					
					);					
				}
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		return $deductions_dtl;
	}	
	
	public function compute_withholding_tax($year, $payroll_summary_id, $param_data, $employees=NULL)
	{
		RLog::info("Payroll_form_2307.compute_withholding_tax [$year][$month][$payroll_summary_id]");
		
		$deductions_dtl = array();
		
		try
		{
			// get BIR code
			$sys_param_types	= array(PARAM_DEDUCTION_ST_BIR_EWT, PARAM_DEDUCTION_ST_BIR_VAT);
			$sys_param_values	= $this->common->get_sys_param_value($sys_param_types, TRUE);
			if( ! empty($sys_param_values))
				$sys_param_values = set_key_value($sys_param_values, 'sys_param_type', 'sys_param_value', FALSE);
			$sys_param_bir_ewt 	= (isset($sys_param_values[PARAM_DEDUCTION_ST_BIR_EWT]) ? $sys_param_values[PARAM_DEDUCTION_ST_BIR_EWT] : NULL);			
			$sys_param_bir_vat 	= (isset($sys_param_values[PARAM_DEDUCTION_ST_BIR_VAT]) ? $sys_param_values[PARAM_DEDUCTION_ST_BIR_VAT] : NULL);
			
			// get BIR PK
			$fields 				= array('deduction_code', 'deduction_id');
			$where 					= array();
			$where['deduction_code']= array(array($sys_param_bir_ewt, $sys_param_bir_vat), array('IN'));
			$deduction_rec			= $this->common->get_general_data($fields, $this->common->tbl_param_deductions, $where, TRUE);
			if( ! empty($deduction_rec))
				$deduction_rec = set_key_value($deduction_rec, 'deduction_code', 'deduction_id', FALSE);
			$sys_param_bir_ewt_id	= isset($deduction_rec[$sys_param_bir_ewt]) ? $deduction_rec[$sys_param_bir_ewt] : NULL;
			$sys_param_bir_vat_id	= isset($deduction_rec[$sys_param_bir_vat]) ? $deduction_rec[$sys_param_bir_vat] : NULL;
			
			if (empty($sys_param_bir_ewt) OR empty($sys_param_bir_ewt_id))
				throw new Exception('Deduction Code/ID for BIR EWT: ' . $this->lang->line('sys_param_not_defined'));

			// get Tax Details
			$fields = array('A.employee_id', 'A.professional_flag', 'C.payroll_hdr_id', 
				'IFNULL(B.payment_mo_1, 0) payment_mo_1', 'IFNULL(B.payment_mo_2, 0) payment_mo_2', 
				'IFNULL(B.payment_mo_3, 0) payment_mo_3', 'IFNULL(B.tax_withheld, 0) tax_withheld',
				'tax_withheld_2306');
			
			$tables	= array(
				'main'	=> array(
					'table'		=> $this->tbl_form_2307_header,
					'alias'		=> 'A'
				),
				'table1'=> array(
					'table'		=> $this->tbl_form_2307_details,
					'alias'		=> 'B',
					'type'		=> 'JOIN',
					'condition'	=> 'B.form_2307_id = A.form_2307_id'
				),
				'table2'=> array(
					'table'		=> $this->tbl_payout_header,
					'alias'		=> 'C',
					'type'		=> 'JOIN',
					'condition'	=> 'A.employee_id = C.employee_id'
				)
			);

			$where 				= array();
			$key 				= 'A.year';
			$where[$key]		= $year;
			$key 				= 'C.payroll_summary_id';
			$where[$key]		= $payroll_summary_id;				
			if ( ! is_null($employees))
			{
				$key 			= 'A.employee_id';
				if (is_array($employees))
					$where[$key]= array($employees, array('IN'));
				else
					$where[$key]= $employees;
			}
			
			$order_by			= array("A.employee_id" => 'ASC');
			$tax_details 		= $this->select_all($fields, $tables, $where, $order_by);
			
			foreach ($tax_details as $tax)
			{
				$monthly_wt_amount = $tax['tax_withheld'];
				$deductions_dtl[$tax['payroll_hdr_id']][]	=	array(
					'employee_id' 			=> $tax['employee_id'],
					'payroll_hdr_id' 		=> $tax['payroll_hdr_id'],
					'deduction_id' 			=> $sys_param_bir_ewt_id,
					'effective_date' 		=> '',
					'amount'				=> $monthly_wt_amount,
					'employer_amount'		=> NULL,
					'reference_text'		=> NULL,
					'priority_num'			=> 0
				);

				if ( ! empty($sys_param_bir_vat_id))
				{
					$monthly_wt_amount = $tax['tax_withheld_2306'];
					$deductions_dtl[$tax['payroll_hdr_id']][]	=	array(
						'employee_id' 			=> $tax['employee_id'],
						'payroll_hdr_id' 		=> $tax['payroll_hdr_id'],
						'deduction_id' 			=> $sys_param_bir_vat_id,
						'effective_date' 		=> '',
						'amount'				=> $monthly_wt_amount,
						'employer_amount'		=> NULL,
						'reference_text'		=> NULL,
						'priority_num'			=> 0
					);					
				}
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		return $deductions_dtl;
	}
	
	/** 
	* This function constructs BIR Form 2307 (either to compute for net taxable income OR insert to form_2307_* table. 
	* 
	* @access public
	* @param $year Effective year
	* @param $payroll_summary_id Payroll Summary ID
	* @param $payout_date (DateTime) Payout date, this will be the basis for quarter and BIR effectivity date
	* @param $sys_param_stat_approved Status code of payout (for filtering of actual payout; NULL if no filter) 
	* @param $save TRUE if result data is to be saved to form_2307_* tables; FALSE if return result without saving
	* @return boolean if $save is TRUE
	* @return array if $save is FALSE
	* 		  array('<emp_id>' => array(<net_tax_income>, <tax_due>, <tax_withheld>))
	*/
	// public function construct_form_2307($year, $payout_date, $payroll_summary_id, $included_employees=array(), $sys_param_stat_approved=NULL, $save=FALSE)
	// ====================== jendaigo : start : include pay_start_date and pay_end_date ============= //
	public function construct_form_2307($year, $payout_date, $payroll_summary_id, $included_employees=array(), $sys_param_stat_approved=NULL, $pay_start_date=NULL, $pay_end_date=NULL, $save=FALSE)
	// ====================== jendaigo : end : include pay_start_date and pay_end_date ============= //
	{
		RLog::info("construct_form_2307 [$year] [{$payout_date->format('Y-m-d')}] [$payroll_summary_id] [$sys_param_stat_approved] [$save]");
		try
		{
			$employees = array();
			if ( ! empty($payroll_summary_id))
				$employees 		= $this->payroll_tax->get_employee_2307_header_info($payroll_summary_id, $payout_date, $included_employees);
			
			// ====================== jendaigo : start : Get ID of Deduction 8% Income Tax ============= //
			$sys_param_type 		= array(PARAM_DEDUCTION_ST_BIR_EIGHT_ID);
			$deduct_eight_id  		= $this->common->get_sys_param_value($sys_param_type, FALSE);
			// ====================== jendaigo : end : Get ID of Deduction 8% Income Tax ============= //
				
			// insert to form_2307
			/*
			RLog::error('S: GET PAYOUT');
			RLog::error($employees);
			RLog::error('E: GET PAYOUT');
			*/

			$pay_month = intval($payout_date->format('m'));
			$quarter = get_quarter_of_month($pay_month);
			$pay_month_num = get_month_num_in_quarter($pay_month);
			$bir_effective_date = $payout_date->format('Y-m-d');
			
			$payment_mo_field = 'payment_mo_' . $pay_month_num;
			
			RLog::info("construct_form_2307 BIR [$bir_effective_date] QTR [$quarter] [$pay_month_num] [$payment_mo_field]");
			
			$cnt = 0;
			$form_2307_ids = array();
			$with_month_pay_13 = FALSE;
			
			$form_2307_hdr	= array();
			$form_2307_dtl	= array();
			$form_2307_emp_prof_flag = array();
			$form_actual_income = array();
			$employee_payroll_hdr_ids = array();
			
			$deductions_dtl = array();
			
			foreach ($employees AS $dtl)
			{
				$address_val = explode(',', $dtl['address_value']);
				$zipcode_val = explode(',', $dtl['postal_number']);				
				
				$form_2307 = array();
				$employee_id = $dtl['employee_id'];
				$save_data 	 = TRUE;
				
				// header
				$form_2307_hdr[$cnt]['year'] 						= $year;
				$form_2307_hdr[$cnt]['quarter'] 					= $quarter;
				$form_2307_hdr[$cnt]['employee_id'] 				= $employee_id;
				$form_2307_hdr[$cnt]['employee_name'] 				= $dtl['employee_name'];
				$form_2307_hdr[$cnt]['employee_tin']				= isset($dtl['employee_tin']) ? $dtl['employee_tin'] : '-'; 
				$form_2307_hdr[$cnt]['rdo_code']					= isset($dtl['rdo_code']) ? $dtl['rdo_code'] : '-';
				$form_2307_hdr[$cnt]['registered_address']			= isset($address_val[0]) ? $address_val[0] : '-';
				$form_2307_hdr[$cnt]['registered_addr_zip_code']	= isset($zipcode_val[0]) ? $zipcode_val[0] : '-';
				$form_2307_hdr[$cnt]['local_address']				= isset($address_val[1]) ? $address_val[1] : '-';
				$form_2307_hdr[$cnt]['local_addr_zip_code']			= isset($zipcode_val[1]) ? $zipcode_val[1] : '-';
				$form_2307_hdr[$cnt]['foreign_address']				= isset($dtl['foreign_address']) ? $dtl['foreign_address'] : '-';
				$form_2307_hdr[$cnt]['foreign_addr_zip_code']		= isset($dtl['foreign_addr_zip_code']) ? $dtl['foreign_addr_zip_code'] : '-';
				$form_2307_hdr[$cnt]['birth_date']					= isset($dtl['birth_date']) ? $dtl['birth_date'] : '0000-00-00';
				$form_2307_hdr[$cnt]['telephone_num']				= isset($dtl['telephone_num']) ? $dtl['telephone_num'] : '-';
				$form_2307_hdr[$cnt]['exemption_status']			= isset($dtl['exemption_status']) ? $dtl['exemption_status'] : '-';
				$form_2307_hdr[$cnt]['professional_flag']			= isset($dtl['professional_flag']) ? $dtl['professional_flag'] : NO;
				
				// detail
				$income_payment 		= empty($dtl['actual_income']) ? 0.00 : $dtl['actual_income'];
				$monthly_actual_amount	= empty($dtl['monthly_actual_amount']) ? 0.00 : $dtl['monthly_actual_amount'];
				
				$form_2307_dtl[$employee_id]['atc'] = '-';
				if ($dtl['professional_flag'] == NO)
					$form_2307_dtl[$employee_id]['income_payment_ewt'] = EWT_DESCRIPTION_NON_PROFESSIONAL;
				else
				{
					$form_2307_hdr[$cnt]['professional_flag']			= isset($dtl['professional_flag']) ? YES : NO;
					$form_2307_dtl[$employee_id]['income_payment_ewt'] 	= EWT_DESCRIPTION_PROFESSIONAL;
					
					$form_2307_emp_prof_flag[$employee_id]				= $form_2307_hdr[$cnt]['professional_flag'];
				}
				
				$form_2307_dtl[$employee_id][$payment_mo_field] = $monthly_actual_amount;
				$form_actual_income[$employee_id][CP_SALARY] = $income_payment;
				$employee_payroll_hdr_ids[$employee_id] = $dtl['payroll_hdr_id'];
				
				$cnt++;
			}
			
			$form_2307_ids = $this->_save_form_2307_header($form_2307_hdr, $year, $quarter, $included_employees, $save);

			foreach ($form_2307_ids as $form_emp_id => $form_id)
			{
				
				$form_2307_dtl[$form_emp_id]['form_2307_id'] = $form_id;
				
				$this->_save_form_2307_details($form_2307_dtl[$form_emp_id], $save);
				
				// for monthly details
				$form_2307_mo_dtl							= array();
				$form_2307_mo_dtl['form_2307_id'] 			= $form_id;
				$form_2307_mo_dtl['year'] 					= $year;
				$form_2307_mo_dtl['month'] 					= $pay_month;
				$form_2307_mo_dtl['atc_2307'] 				= $form_2307_dtl[$form_emp_id]['atc'];
				$form_2307_mo_dtl['nature_payment_2307']	= $form_2307_dtl[$form_emp_id]['income_payment_ewt'];
				$form_2307_mo_dtl['payment_amount_2307']	= $form_2307_dtl[$form_emp_id][$payment_mo_field];
				$form_2307_mo_dtl['tax_withheld_2307'] 		= 0;
				$form_2307_mo_dtl['atc_2306'] 				= $form_2307_dtl[$form_emp_id]['atc'];
				$form_2307_mo_dtl['nature_payment_2306']	= $form_2307_dtl[$form_emp_id]['income_payment_ewt'];
				$form_2307_mo_dtl['payment_amount_2306']	= $form_2307_dtl[$form_emp_id][$payment_mo_field];
				$form_2307_mo_dtl['tax_withheld_2306'] 		= 0;
				
				/*
				RLog::error("TEST FORM_2307_DTL [$form_id] [$form_emp_id]");
				RLog::error($form_2307_dtl[$form_emp_id]);
				RLog::error($form_2307_mo_dtl);
				*/
				
				$this->_save_form_2307_monthly_details($form_2307_mo_dtl, $save);
				
				// $total_and_tax = $this->_compute_tax_withheld($form_id, $form_actual_income[$form_emp_id][CP_SALARY], 
									// $form_2307_dtl[$form_emp_id][$payment_mo_field], 
									// $year, $pay_month, $bir_effective_date, $form_2307_emp_prof_flag[$form_emp_id], $save);
									
				// ====================== jendaigo : start : include employee_id============= //

				$total_and_tax = $this->_compute_tax_withheld($form_id, $form_actual_income[$form_emp_id][CP_SALARY], 
									$form_2307_dtl[$form_emp_id][$payment_mo_field], 
									$year, $pay_month, $bir_effective_date, $form_2307_emp_prof_flag[$form_emp_id], $save, $form_emp_id, $deduct_eight_id);

				// ====================== jendaigo : end : include employee_id============= //				

				$payroll_hdr_id = $employee_payroll_hdr_ids[$form_emp_id];
				// $deductions_dtl[$payroll_hdr_id][]	=	array(
					// KEY_EMPLOYEE_ID			=> $form_emp_id,
					// KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
					// KEY_DEDUCTION_ID 		=> DEDUC_BIR_EWT,
					// KEY_RAW_DEDUCTION_ID	=> DEDUC_BIR_EWT,
					// KEY_EFFECTIVE_DATE 		=> '',
					// KEY_AMOUNT				=> isset($total_and_tax['tax_withheld']) ? $total_and_tax['tax_withheld'] : 0,
					// KEY_EMPLOYER_AMOUNT		=> NULL,
					// KEY_REFERENCE_TEXT		=> NULL,
					// KEY_PRIORITY_NUM		=> 0
				// );

				// $deductions_dtl[$payroll_hdr_id][]	=	array(
					// KEY_EMPLOYEE_ID			=> $form_emp_id,
					// KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
					// KEY_DEDUCTION_ID 		=> DEDUC_BIR_VAT,
					// KEY_RAW_DEDUCTION_ID	=> DEDUC_BIR_VAT,
					// KEY_EFFECTIVE_DATE 		=> '',
					// KEY_AMOUNT				=> isset($total_and_tax['tax_withheld_2306']) ? $total_and_tax['tax_withheld_2306'] : 0,
					// KEY_EMPLOYER_AMOUNT		=> NULL,
					// KEY_REFERENCE_TEXT		=> NULL,
					// KEY_PRIORITY_NUM		=> 0					
				// );	
				
				// ====================== jendaigo : start : modify for bir deduction conditions ============= //

				$emp_deducts 			= array();
				$field                  = array("*") ;
				$tables  				= $this->payroll_tax->tbl_employee_deductions;
				
				$where                  = array();
				$where['employee_id']   = $form_emp_id;
				$where['deduction_id']  = array(array($deduct_eight_id['sys_param_value'],DEDUC_BIR_EWT, DEDUC_BIR_VAT), array('IN'));

				$emp_deducts		    = $this->payroll_tax->get_payroll_data($field, $tables, $where, TRUE);

				foreach ($emp_deducts as $emp_deduct):
					if($emp_deduct['start_date'] <= $pay_end_date)
					{
						switch($emp_deduct['deduction_id'])
						{	
							case $deduct_eight_id['sys_param_value']:
								$deductions_dtl[$payroll_hdr_id][]	=	array(
									KEY_EMPLOYEE_ID			=> $form_emp_id,
									KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
									KEY_DEDUCTION_ID 		=> $deduct_eight_id['sys_param_value'],
									KEY_RAW_DEDUCTION_ID	=> $deduct_eight_id['sys_param_value'],
									KEY_EFFECTIVE_DATE 		=> '',
									KEY_AMOUNT				=> isset($total_and_tax['tax_withheld_eight']) ? $total_and_tax['tax_withheld_eight'] : 0,
									KEY_EMPLOYER_AMOUNT		=> NULL,
									KEY_REFERENCE_TEXT		=> NULL,
									KEY_PRIORITY_NUM		=> 0
								);
								break;
							case DEDUC_BIR_EWT:
								$deductions_dtl[$payroll_hdr_id][]	=	array(
									KEY_EMPLOYEE_ID			=> $form_emp_id,
									KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
									KEY_DEDUCTION_ID 		=> DEDUC_BIR_EWT,
									KEY_RAW_DEDUCTION_ID	=> DEDUC_BIR_EWT,
									KEY_EFFECTIVE_DATE 		=> '',
									KEY_AMOUNT				=> isset($total_and_tax['tax_withheld']) ? $total_and_tax['tax_withheld'] : 0,
									KEY_EMPLOYER_AMOUNT		=> NULL,
									KEY_REFERENCE_TEXT		=> NULL,
									KEY_PRIORITY_NUM		=> 0
								);
								break;
							case DEDUC_BIR_VAT:
								$deductions_dtl[$payroll_hdr_id][]	=	array(
									KEY_EMPLOYEE_ID			=> $form_emp_id,
									KEY_PAYROLL_HDR_ID 		=> $payroll_hdr_id,
									KEY_DEDUCTION_ID 		=> DEDUC_BIR_VAT,
									KEY_RAW_DEDUCTION_ID	=> DEDUC_BIR_VAT,
									KEY_EFFECTIVE_DATE 		=> '',
									KEY_AMOUNT				=> isset($total_and_tax['tax_withheld_2306']) ? $total_and_tax['tax_withheld_2306'] : 0,
									KEY_EMPLOYER_AMOUNT		=> NULL,
									KEY_REFERENCE_TEXT		=> NULL,
									KEY_PRIORITY_NUM		=> 0					
								);
								break;
						}
					}
				endforeach;
				// ====================== jendaigo : end : modify for bir deduction conditions ============= //
			}
			
			return $deductions_dtl;
			
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	private function _save_form_2307_header($payout_details, $year, $quarter, $included_employees=array(), $save=FALSE)
	{
		try
		{
			if( ! $save) return;
			
			$table = $this->common->tbl_form_2307_header;
			$this->common->insert_general_data($table, $payout_details, FALSE, TRUE, FALSE);
			
			$form_2307_ids = $this->payroll_tax->get_form_2307_ids($year, $quarter, $included_employees);
			
			RLog::info('E: form_2307_header');
			RLog::info($form_2307_ids);
			
			return $form_2307_ids;
		}
		catch ( Exception $e )
		{
			throw $e;
		}
	}
	
	private function _save_form_2307_details($form_2307, $save=FALSE)
	{
		try
		{
			if( ! $save) return;
			
			$table = $this->common->tbl_form_2307_details;
			$form_2307_dtl_id = $this->common->insert_general_data($table, $form_2307, TRUE, TRUE, 'form_2307_id');						
			
			RLog::info('E: form_2307_details ['.$form_2307_dtl_id.']');
			
			return $form_2307_dtl_id;
			
		}
		catch ( Exception $e )
		{
			throw $e;
		}
	}
	
	private function _save_form_2307_monthly_details($form_2307, $save=FALSE)
	{
		try
		{
			if( ! $save) return;
			
			$table = $this->common->tbl_form_2307_monthly_details;
			$form_2307_dtl_id = $this->common->insert_general_data($table, $form_2307, FALSE, TRUE, FALSE);
			
			//RLog::info('E: form_2307_details ['.$form_2307_dtl_id.']');
			//return $form_2307_dtl_id;
			
		}
		catch ( Exception $e )
		{
			throw $e;
		}
	}	
	
	// private function _compute_tax_withheld($form_2307_id, $income_payment, $monthly_actual_income, $pay_year, $pay_month, $bir_effective_date, $professional_flag=NO, $save=FALSE)
	// ====================== jendaigo : start : include employee_id and ID of BIR 8% ============= //
	private function _compute_tax_withheld($form_2307_id, $income_payment, $monthly_actual_income, $pay_year, $pay_month, $bir_effective_date, $professional_flag=NO, $save=FALSE, $employee_id, $deduct_eight_id=array())
	// ====================== jendaigo : end : include employee_id and ID of BIR 8% ============= //
	{
		//RLog::error("_compute_tax_withheld save? [$save] [$professional_flag]");
		
		$total_and_tax = array('total_amount' => 0, 'tax_withheld' => 0);
		
		// Working Month in a Year
		$sys_param_type	= array(PARAM_WORKING_MONTHS);
		$sys_param_values = $this->common->get_sys_param_value($sys_param_type, FALSE);
		$sys_param_work_months = (isset($sys_param_values['sys_param_value']) ? $sys_param_values['sys_param_value'] : NULL);

		if (empty($sys_param_work_months))
			throw new Exception('Form 2307: ' . $this->lang->line('sys_param_not_defined'));
		
		$total_amount = 0;
		$tax_withheld = 0;
		
		$fields	= array('IFNULL(payment_mo_1, 0) payment_mo_1', 'IFNULL(payment_mo_2, 0) payment_mo_2', 'IFNULL(payment_mo_3, 0) payment_mo_3');
		$table	= $this->common->tbl_form_2307_details;
		$where['form_2307_id'] = $form_2307_id;
		$data = $this->common->get_general_data($fields, $table, $where, FALSE);
		RLog::info('S: 2307 _compute_tax_withheld');
		RLog::info($data);
		RLog::info('E: 2307 _compute_tax_withheld');
		
		// TODO: projection of gross annual income
		$gross_annual_income = $monthly_actual_income * $sys_param_work_months; // TODO what month		

		// $tax_table = $this->_get_tax_table($form_2307_id, $gross_annual_income, $bir_effective_date);
		// ====================== jendaigo : start : include employee_id ============= //
		$tax_table = $this->_get_tax_table($form_2307_id, $gross_annual_income, $bir_effective_date, $employee_id);
		// ====================== jendaigo : end : include employee_id ============= //
				
		$tax_rate_ewt = 0;
		$tax_rate_vat = 0;

		// ====================== jendaigo : start : remove checking if employee is eligible ============= //
		// if ($professional_flag == YES)
			// $tax_rate_ewt = $tax_table[PARAM_DEDUCTION_ST_BIR_EWT]['tax_rate'];
		// ====================== jendaigo : end : remove checking if employee is eligible ============= //
		
		$tax_rate_ewt = $tax_table[PARAM_DEDUCTION_ST_BIR_EWT]['tax_rate'];
		
		$tax_rate_vat = $tax_table[PARAM_DEDUCTION_ST_BIR_VAT]['tax_rate'];

		// ====================== jendaigo : start : include condition for BIR 8% Income Tax Rate ============= //
		$tax_rate_eight 		= 0;
		$tax_rate_eight 		= $tax_table[PARAM_DEDUCTION_ST_BIR_EIGHT]['tax_rate'];	

		$field                  = array("*") ;
		$table  				= $this->payroll_tax->tbl_employee_deductions;
		$where                  = array();
		$where['deduction_id']  = $deduct_eight_id['sys_param_value'];
		$where['employee_id']   = $employee_id;
		$ded_eight		       	= $this->payroll_tax->get_payroll_data($field, $table, $where, FALSE);
			
		// ====================== jendaigo : end : include condition for BIR 8% Income Tax Rate ============= //
		
		$pay_mo_1 = $data['payment_mo_1'];
		$pay_mo_2 = $data['payment_mo_2'];
		$pay_mo_3 = $data['payment_mo_3'];
		$total_amount = $pay_mo_1 + $pay_mo_2 + $pay_mo_3;
		//$tax_withheld = ROUND($pay_mo_1 * $tax_rate_ewt, 2) + ROUND($pay_mo_2 * $tax_rate_ewt, 2) + ROUND($pay_mo_3 * $tax_rate_ewt, 2);
		$tax_withheld = ROUND(($total_amount * $tax_rate_ewt), 2);
		
		$tax_withheld_eight = ROUND(($total_amount * $tax_rate_eight), 2); //jendaigo: include condition for BIR 8% Income Tax
		
		// for VAT (2306)
		$tax_withheld_2306 = ROUND(($total_amount * $tax_rate_vat), 2);
		
		//RLog::error('E: form_2307_details ['.$form_2307_id.']['.$total_amount.'] ['.$tax_withheld.'] ['.$tax_withheld_2306.']');

		// $total_and_tax = array('total_amount' => $total_amount, 'tax_withheld' => $tax_withheld, 'tax_withheld_2306' => $tax_withheld_2306);
		// ====================== jendaigo : start : include condition for BIR 8% Income Tax ============= //
		$total_and_tax = array('total_amount' => $total_amount, 'tax_withheld' => $tax_withheld, 'tax_withheld_2306' => $tax_withheld_2306, 'tax_withheld_eight' => $tax_withheld_eight);
		// ====================== jendaigo : end : include condition for BIR 8% Income Tax ============= //
		
		if( ! $save) return $total_and_tax;
		
		$fields = array();
		$fields['payment_total'] 	= $total_amount;
		$fields['tax_withheld'] 	= $tax_withheld;
		$fields['atc'] 				= (empty($tax_table[PARAM_DEDUCTION_ST_BIR_EWT]['exempt_status_code']) ? '-' : $tax_table[PARAM_DEDUCTION_ST_BIR_EWT]['exempt_status_code']);
		
		// for VAT (2306)
		$fields['payment_2306'] 	= $monthly_actual_income;
		$fields['tax_withheld_2306']= $tax_withheld_2306;
		
		$nature_payment_2306= NATURE_DESCRIPTION_2306;
		$atc_2306 			= ATC_CODE_2306;
		$fields['nature_payment_2306'] 	= $nature_payment_2306;
		$fields['atc_2306'] 			= $atc_2306;
		
		$this->common->update_general_data($this->common->tbl_form_2307_details, $fields, array('form_2307_id'=>$form_2307_id));
		
		// for the period
		$period_tax_withheld_2307	= ROUND(($income_payment * $tax_rate_ewt), 2);

		$period_tax_withheld_eight	= ROUND(($income_payment * $tax_rate_eight), 2); //jendaigo: include condition for BIR 8% Income Tax
		
		$period_tax_withheld_2306	= ROUND(($income_payment * $tax_rate_vat), 2);
		
		// for monthly_details
		$fields = array();
		$fields['atc_2307'] 			= (empty($tax_table[PARAM_DEDUCTION_ST_BIR_EWT]['exempt_status_code']) ? '-' : $tax_table[PARAM_DEDUCTION_ST_BIR_EWT]['exempt_status_code']);
		//$fields['nature_payment_2307'] 	= '';
		$fields['tax_withheld_2307'] 	= ROUND(($monthly_actual_income * $tax_rate_ewt), 2);
		
		$fields['atc_2306'] 			= $atc_2306;
		$fields['nature_payment_2306'] 	= $nature_payment_2306;
		$fields['tax_withheld_2306'] 	= ROUND(($monthly_actual_income * $tax_rate_vat), 2);
		
		$where = array('form_2307_id'=>$form_2307_id, 'year'=>$pay_year, 'month'=>$pay_month);
		$this->common->update_general_data($this->common->tbl_form_2307_monthly_details, $fields, $where);
		
		// $total_and_tax = array('total_amount' => $total_amount, 'tax_withheld' => $period_tax_withheld_2307, 'tax_withheld_2306' => $period_tax_withheld_2306);
		// ====================== jendaigo : start : include condition for BIR 8% Income Tax ============= //
		$total_and_tax = array('total_amount' => $total_amount, 'tax_withheld' => $period_tax_withheld_2307, 'tax_withheld_2306' => $period_tax_withheld_2306, 'tax_withheld_eight' => $period_tax_withheld_eight);
		// ====================== jendaigo : end : include condition for BIR 8% Income Tax ============= //
		
		//RLog::error("TOTAL AND TAX");
		//RLog::error($total_and_tax);
		
		return $total_and_tax;
	}	
	
	// private function _get_tax_table($employee_id, $gross_annual_income, $bir_effective_date)
	// ====================== jendaigo : start : include employee_id from header ============= //
	private function _get_tax_table($employee_id, $gross_annual_income, $bir_effective_date, $eid)
	// ====================== jendaigo : end : include employee_id from header ============= //
	{
		try
		{
			// $tax_table = array(PARAM_DEDUCTION_ST_BIR_EWT => array(), PARAM_DEDUCTION_ST_BIR_VAT => array());
			// ====================== jendaigo : start : include PARAM_DEDUCTION_ST_BIR_EIGHT ============= //
			$tax_table = array(PARAM_DEDUCTION_ST_BIR_EWT => array(), PARAM_DEDUCTION_ST_BIR_VAT => array(), PARAM_DEDUCTION_ST_BIR_EIGHT => array());
			// ====================== jendaigo : end : include PARAM_DEDUCTION_ST_BIR_EIGHT ============= //
			
			if ( ! isset($bir_effective_date))
			{
				$bir_effective_date = date('Y-m-d');
			}
			RLog::info("S: Payroll_form_2307._get_tax_table [$bir_effective_date] [$employee_id] [$gross_annual_income]");
			
			// ====================== jendaigo : start : include BIR 8% Income Tax ATC ============= //
			// Alphanumeric Tax Code for 8% Income Tax
			$sys_param_type	= array(PARAM_DEDUCTION_ST_BIR_EIGHT_ATC);
			$sys_param_value = $this->common->get_sys_param_value($sys_param_type, FALSE);
			// ====================== jendaigo : end : include BIR 8% Income Tax ATC ============= //

			// get tax table
			// $bir_table = $this->payroll_tax->get_bir_table(TAX_MONTHLY_2307, $bir_effective_date);

			// ====================== jendaigo : start : modify getting of bir_table based on tax code ============= //
			$deduction_detail 			   = array();
			$tax_table_flag 			   = TAX_MONTHLY_2307;
			$fields                        = array("A.employee_id", "A.employee_deduction_id", "B.other_detail_name", "B.other_deduction_detail_id") ;
			$tables  = array(
				'main'	=> array(
					'table'		=> $this->payroll_tax->tbl_employee_deductions,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->payroll_tax->tbl_param_other_deduction_details,
					'alias'		=> 'B',
					'type'		=> 'LEFT JOIN',
					'condition'	=> 'B.deduction_id = A.deduction_id',
				)
			);
			
			$where                         	 	= array();
			$where['A.employee_id']        	 	= $eid;
			$where['B.other_detail_name']  	 	= 'TAX CODE';
			$deduction_detail		       	 	= $this->payroll_tax->get_payroll_data($fields, $tables, $where, FALSE);
			
			$other_deduction_detail 			= array();
			$fields                        		= array("other_deduction_detail_value", "employee_deduction_id", "other_deduction_detail_id");
			$table  						 	= $this->payroll_tax->tbl_employee_deduction_other_details;
				
			$where                         	 	= array();
			$where['other_deduction_detail_id'] = $deduction_detail['other_deduction_detail_id'];
			$where['employee_deduction_id']  	= $deduction_detail['employee_deduction_id'];
			$other_deduction_detail			  	= $this->payroll_tax->get_payroll_data($fields, $table, $where, FALSE);

			if(!empty($other_deduction_detail['other_deduction_detail_value']))
			{
				$tax_table_flag .= '_'.$other_deduction_detail['other_deduction_detail_value'];
			}

			$bir_table = $this->payroll_tax->get_bir_table($tax_table_flag, $bir_effective_date);

			// ====================== jendaigo : end : modify getting of bir_table based on tax code ============= //	
			
			if ( ! isset($bir_table) OR count($bir_table) == 0)
				throw new Exception($this->lang->line('sys_param_not_defined'));

			// compute tax based on taxable income
			foreach ($bir_table as $table)
			{
				if ($table['vat_flag'] == YES)
				{
					if ($table['max_amount'] >=  $gross_annual_income OR ($table['max_amount'] == 0 AND $table['min_amount'] <= $gross_annual_income))
					{				
						//RLog::info('-- VAT bracket used: ['.$gross_annual_income.'] ['.$table['min_amount'].'] ['.$table['max_amount'].']');
						$tax_table[PARAM_DEDUCTION_ST_BIR_VAT] = $table;
					}
				}
				else
				{
					if ($table['max_amount'] >=  $gross_annual_income OR ($table['max_amount'] == 0 AND $table['min_amount'] <= $gross_annual_income))
					{
						//RLog::info('-- EWT bracket used: ['.$gross_annual_income.'] ['.$table['min_amount'].'] ['.$table['max_amount'].']');
						// $tax_table[PARAM_DEDUCTION_ST_BIR_EWT] = $table;
						
						// ====================== jendaigo : start : include table with BIR 8% Income Tax ATC ============= //
						if ($table['exempt_status_code'] == $sys_param_value['sys_param_value'])
							$tax_table[PARAM_DEDUCTION_ST_BIR_EIGHT] = $table;
						else
							$tax_table[PARAM_DEDUCTION_ST_BIR_EWT] = $table;
						// ====================== jendaigo : end : include table with BIR 8% Income Tax ATC ============= //
					}					
				}
			}

			return $tax_table;
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		return NULL;
	}

}

/* End of file Payroll_form_2307.php */
/* Location: ./application/modules/main/controllers/Payroll_form_2307.php */