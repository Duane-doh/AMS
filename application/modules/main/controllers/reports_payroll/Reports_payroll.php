<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_payroll extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('reports_payroll_model', 'rm');
		$this->load->model('common_model', 'cm');
		$this->load->library('Excel');
	}	
	public function index()
	{
		try
		{
			$data 			= array();
			$resources 		= array();
			
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			// $resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY, 'jquery.number.min');

			$tables = array(

				'main'      => array(
				'table'     => $this->rm->tbl_employee_personal_info,
				'alias'     => 'A',
				),
				't2'        => array(
				'table'     => $this->rm->tbl_employee_work_experiences,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.employee_id = B.employee_id AND B.active_flag = "Y"',
			 	)
			);

			$select_fields                 = array("B.*, CONCAT(A.first_name,' ',A.last_name) employee_name");
			$data['employees']             = $this->rm->get_all_employees($select_fields, $tables);
			
			
			//WITHOUT DATE
			$field                         = array("B.office_id, A.name") ;
			$table 						   = array(
				'main' 		=> 		array(
					'table' 	=> $this->rm->DB_CORE.".".$this->rm->tbl_organizations,
					'alias'		=> 'A'
				),
				't1' 		=>  	array(
					'table'		=> $this->rm->tbl_param_offices,
					'alias'		=> 'B',
					'type'		=> 'JOIN',
					'condition' => 'A.org_code = B.org_code'
				),
				't2'		=>		array(
					'table'		=> $this->rm->tbl_payout_header,
					'alias'		=> 'C',
					'type'		=> 'JOIN',
					'condition' => 'B.office_id = C.office_id'
				)
			);
			$group_by					   = array('B.office_id');
			$where                         = array();
			$where['B.active_flag']		   = 'Y';
			$data['offices_list']          = $this->rm->get_reports_data($field, $table, $where, TRUE, NULL, $group_by);
			
			$field                         = array("effectivity_date", "other_fund_flag") ;
			$table                         = $this->rm->tbl_param_salary_schedule; 
			$where                         = array();
			$group_by                      = array("effectivity_date", "other_fund_flag");
			$data['effectivity_date']      = $this->rm->get_reports_data($field, $table, $where, TRUE, NULL, $group_by);
			
			$field                         = array("A.effective_date") ;
			// $table                         = $this->rm->tbl_payout_details; 
			$table  					   = array(
					'main'	=> array(
						'table' =>	$this->rm->tbl_payout_details,
						'alias' => 'A'
					),
					't1' 	=> array(
						'table' => $this->rm->tbl_remittances,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.effective_date BETWEEN B.deduction_start_date AND B.deduction_end_date'
					)
			);
			$where                           = array();
			$where['A.compensation_id']      = 'IS NULL';
			$where['B.remittance_status_id'] = 4;
			$group_by                        = array("effective_date");
			$data['payout_effective_date']   = $this->rm->get_reports_data($field, $table, $where, TRUE, NULL, $group_by);
			
			$field                           = array("compensation_id", "compensation_name") ;
			$table                           = $this->rm->tbl_param_compensations; 
			$where                           = array();
			$data['compensation_types']      = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                           = array("deduction_id", "deduction_name") ;
			$table                           = $this->rm->tbl_param_deductions; 
			$where                           = array();
			$data['deduction_types']         = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                           = array("payroll_type_id", "payroll_type_name");
			$table                           = $this->rm->tbl_param_payroll_types;
			$where                           = array();
			$data['payroll_types']           = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                           = array('remittance_type_id','remittance_type_name');
			$table                           = $this->rm->tbl_param_remittance_types;
			$where['active_flag']            = 'Y';
			$data['remittance_type']         = $this->rm->get_reports_data($field, $table, $where);
			
			$field                        	  	= array("compensation_id", "compensation_name") ;
			$table                      	    = $this->rm->tbl_param_compensations;
			$where                      	    = array();
			$where['active_flag']           	= YES;
			$where['special_payroll_flag']     	= YES;
			$where['parent_compensation_id'] 	= 'IS NULL';
			$order_by                       	= array('compensation_name' => 'ASC');
			$data['compensation_types_special']	= $this->rm->get_reports_data($field, $table, $where, TRUE, $order_by);

			
			$where						= array();
			$field						= array("A.attendance_period_hdr_id", "CONCAT(DATE_FORMAT(A.date_from,'%M %d'), ' - ', DATE_FORMAT(A.date_to,'%d, %Y')) payroll_period");
			$table  					= array(
					'main'	=> array(
						'table' =>	$this->rm->tbl_attendance_period_hdr,
						'alias' => 'A'
					),
					't1' 	=> array(
						'table' => $this->rm->tbl_payout_summary,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.attendance_period_hdr_id = B.attendance_period_hdr_id'
					)
			);
			$group_by							= array("payroll_period");
			$data['payroll_period']				= $this->rm->get_reports_data($field, $table, $where, TRUE, NULL, $group_by);
			
			$field                           = array('bank_id','bank_name');
			$table                           = $this->rm->tbl_param_banks;
			$where['active_flag']            = 'Y';
			$data['bank']         			= $this->rm->get_reports_data($field, $table, $where);

			// FOR BIR REMITTANCES
			$field    = array('remittance_id', 'DATE_FORMAT(deduction_start_date, "%M %Y") remittance_period', 'remittance_type_id', 'remittance_status_id');
			$table    = $this->rm->tbl_remittances;
			$where    = array();
			$order_by = array('deduction_start_date' => 'DESC');
			$data['remittance_period'] = $this->rm->get_reports_data($field, $table, $where, TRUE, $order_by);

			$where    = array();
			$where['remittance_type_id'] = REMITTANCE_WITHHOLDING_TAX;
			$data['remittance_period_bir'] = $this->rm->get_reports_data($field, $table, $where, TRUE, $order_by);
			
			//REMITTANCE SIGNATORY
			$data['rem_prepared_by']  = $this->cm->get_report_signatories(CODE_PAYROLL, PREPARED_BY);
			$data['rem_certified_by'] = $this->cm->get_report_signatories(CODE_PAYROLL, CERTIFIED_BY);
			
			//PAYROLL SIGNATORIES
			$data['signatories']       = $this->cm->get_report_signatories(CODE_PAYROLL, CERTIFIED_BY);
			$data['prepared_by']       = $this->cm->get_report_signatories(CODE_PAYROLL, PREPARED_BY);

			//PAYROLL SIGNATORIES
			$data['signatory_ca']      = $this->rm->get_report_signatories_ca();
			
			/*COVER SHEET SIGNATORIES*/
			$data['payroll_signatories']    = $this->cm->get_report_signatories(CODE_PAYROLL);

			
			// JOB	PAYROLL TYPE
			$payroll_type_jo = $this->rm->get_sysparam_value(SYS_PARAM_TYPE_PAYROLL_TYPE_JOB_ORDER); //SYS_PARAM_TYPE_PAYROLL_TYPE_JOB_ORDER
			$data['payroll_type_jo'] = $payroll_type_jo['sys_param_value'];
			
			// DEDUCTION EWT PAYROLL TYPE
			$payroll_type_dewt = $this->rm->get_sysparam_value(SYS_PARAM_TYPE_DEDUCTION_ST_BIR_EWT_ID); //SYS_PARAM_TYPE_DEDUCTION_ST_BIR_EWT_ID
			$data['payroll_type_dewt'] = $payroll_type_dewt['sys_param_value'];
			
			// DEDUCTION VAT PAYROLL TYPE
			$payroll_type_gmp = $this->rm->get_sysparam_value(SYS_PARAM_TYPE_DEDUCTION_ST_BIR_VAT_ID); //SYS_PARAM_TYPE_DEDUCTION_ST_BIR_VAT_ID
			$data['payroll_type_gmp'] = $payroll_type_gmp['sys_param_value'];
			
			/*
			 * TO BE CONTINUED
			 * BY JHOMAR 
			 */
			// STORES REPORTS
			$where    = array();
			$where['system_code'] = CODE_PAYROLL;
			$where['module_name'] = "Reports";
			$report_parent_id = $this->rm->get_payroll_report_parent_id($where);

			/*BREADCRUMBS*/
			$breadcrumbs 			= array();

			$key					= "Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/reports_payroll/reports_payroll/";
			$key					= "Reports"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/reports_payroll/reports_payroll/";

			set_breadcrumbs($breadcrumbs, TRUE);

		$this->template->load('reports_payroll_view', $data, $resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
	}

	public function generate_reports($format = 'pdf', $report = NULL)
	{
		try
		{
			$data            = array();
			$params          = get_params();
			
			$paper_width     = 250;
			$paper_length    = 353;
			$landscape       = FALSE;
			$papersize_short = FALSE;
			$set_footer      = TRUE;
			$cert_footer     = FALSE;
			$tracking_code   = $params['tracking_code'];
			$payroll_type    = $params['payroll_type'];
			$sheet           = "Page";
			
			$margin_left     = 20;
			$margin_bottom   = 20;
			$margin_right    = 20;
			$margin_top      = 20;
			$margin_header   = 20;
			$margin_footer   = 20;

			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_FOOTER_LINE';
			$footer	    	   		   = $this->rm->get_reports_data($field, $table, $where, TRUE);			
			$footer_line1 		 	   = $footer[0]['sys_param_value'];
			$footer_line2 			   = $footer[1]['sys_param_value'];

			switch ($report) {				
				case REPORT_GENERAL_PAYROLL_SUMMARY:					
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_PAYROLL_SUMMARY, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/general_payroll_cover_sheet');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_PAYROLL_SUMMARY_GRAND_TOTAL, $payroll_type);
					//                  mplazo change width to length and vice versa
					$paper_width     = $paper_size['size_length'];
					$paper_length    = $paper_size['size_width'];
					$generate_report = modules::load('main/reports_payroll/general_payroll_summary_grand_total');
					$data            = $generate_report->generate_report_data($params);		
				
				break;
				
				case REPORT_GENERAL_PAYROLL_PER_OFFICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_PAYROLL_PER_OFFICE, $payroll_type);
					//                  mplazo change width to length and vice versa
					$paper_width     = $paper_size['size_length'];
					$paper_length    = $paper_size['size_width'];
					$generate_report = modules::load('main/reports_payroll/general_payroll_summary_per_office');
					$data            = $generate_report->generate_report_data($params);		
				break;

				case REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE: 
					$sheet			 = "Sheet";
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_PAYROLL_ALPHALIST_PER_OFFICE, $payroll_type);					
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/general_payroll_alpha_list_per_office');
					$data            = $generate_report->generate_report_data($params);

					$margin_top      = 10;
					$margin_bottom   = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
				break;

				case REPORT_SPECIAL_PAYROLL_COVER_SHEET:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_SPECIAL_PAYROLL_COVER_SHEET, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/special_payroll_cover_sheet');
					$data            = $generate_report->generate_report_data($params);	
				break;

				case REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_SPECIAL_PAYROLL_SUMMARY_GRAND_TOTAL, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/special_payroll_summary_grand_total');
					$data            = $generate_report->generate_report_data($params);	
				break;

				case REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_SPECIAL_PAYROLL_SUMMARY_PER_OFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/special_payroll_summary_per_office');
					$data            = $generate_report->generate_report_data($params);	
				break;

				case REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_SPECIAL_PAYROLL_ALPHA_LIST_PER_OFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/special_payroll_alpha_list_per_office');
					$data            = $generate_report->generate_report_data($params);	
					
				break;

				case REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$margin_top      = 5;
					$margin_bottom   = 5;
					$margin_header   = 10;
					$margin_footer   = 10;

					$generate_report = modules::load('main/reports_payroll/general_payslip_for_regulars_and_non_careers');
					$data            = $generate_report->generate_report_data($params);	
					
				break;

				case REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_FOR_CONSTRACTS_OF_SERVICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/general_Payslip_for_contracts_of_service');
					$data            = $generate_report->generate_report_data($params);
					$margin_top      = 5;
					$margin_bottom   = 5;
					$margin_header   = 10;
					$margin_footer   = 10;
				break;

				case REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS: 
					$paper_size      = $this->rm->get_report_paper_size(REPORT_SPECIAL_PAYSLIP_FOR_REGULARS_AND_NONCAREERS, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/special_payslip_for_regulars_and_non_careers');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_BANK_PAYROLL_REGISTER: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BANK_PAYROLL_REGISTER, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bank_payroll_register');
					$data            = $generate_report->generate_report_data($params);
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_bottom   = 10;
					$margin_footer   = 10;
				break;

				case REPORT_ATM_ALPHA_LIST: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_ATM_ALPHA_LIST, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/atm_alpha_list');
					$data            = $generate_report->generate_report_data($params);
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_bottom   = 10;
					$margin_footer   = 10;
				break;
				case REPORT_ATM_ALPHA_LIST2: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_ATM_ALPHA_LIST2, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/atm_alpha_list2');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_REMITTANCE_SUMMARY_GRAND_TOTAL, 0);
					
					$generate_report = modules::load('main/reports_payroll/remittance_summary_grand_total');
					$data            = $generate_report->generate_report_data($params);
					
					$paper_width     = ($data['lanscape_flag']) ? $paper_size['size_width']:$paper_size['size_length'];
					$paper_length    = ($data['lanscape_flag']) ? $paper_size['size_length']:$paper_size['size_width'];

					$margin_left     = 10;
					$margin_bottom   = 10;
					$margin_right    = 10;
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_footer   = 10;

				break;

				case REPORT_REMITTANCE_SUMMARY_PER_OFFICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_REMITTANCE_SUMMARY_PER_OFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/remittance_summary_per_office');
					$data            = $generate_report->generate_report_data($params);
					
				break;

				case REPORT_REMITTANCE_LIST_PER_OFFICE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_REMITTANCE_LIST_PER_OFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/remittance_list_per_office');
					$data            = $generate_report->generate_report_data($params);

					$margin_left     = 10;
					$margin_bottom   = 10;
					$margin_right    = 10;
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
					
				break;

				case REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_CONSOLIDATED_REMITTANCE_SUMMARY_PER_OFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/consolidated_remittance_summary_per_office');
					$data            = $generate_report->generate_report_data($params);
					
					$margin_bottom   = 10;
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
					
				break;

				case REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_CONSOLIDATED_REMITTANCE_LIST_PER_OFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/consolidated_remittance_list_per_office');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GSIS_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/gsis_contributions_remittance_file_for_uploading');
					$data            = $generate_report->generate_report_data($params);

				break;
 
				case REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PHILHEALTH_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];		
					$generate_report = modules::load('main/reports_payroll/philhealth_contributions_remittance_file_for_uploading');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PAGIBIG_CONTRIBUTIONS_REMITTANCE_FILE_FOR_UPLOADING, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/pagibig_contributions_remittance_file_for_uploading');
					$data            = $generate_report->generate_report_data($params);
					$set_footer = TRUE;
					$margin_left     = 10;
					$margin_bottom   = 10;
					$margin_right    = 10;
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
				break;

				case REPORT_PAGIBIG_DEDUCTIONS_REMITTANCE_FILE_FOR_UPLOADING:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PAGIBIG_DEDUCTIONS_REMITTANCE_FILE_FOR_UPLOADING, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/pagibig_deductions_remittance_file_for_uploading');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_BIR_TAX_PAYMENTS: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_TAX_PAYMENTS, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_tax_payments');
					$data            = $generate_report->generate_report_data($params);
					
				break;

				case REPORT_DOH_COOP_REMITTANCE_FILE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_DOH_COOP_REMITTANCE_FILE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/doh_coop_remittance_file');
					$data            = $generate_report->generate_report_data($params);
					
				break; 

				case REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_1601C_MONTHLY_REPORT_OF_TAX_WITHHELD, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_1601c');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_2316_certificate_of_compensation_payment_tax_withheld');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_BIR_ALPHALIST:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_ALPHALIST, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_alphalist');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_ALPHALIST_WITH_PREVIOUS_EMPLOYER, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_alphalist_with_previous_employer');
					$data            = $generate_report->generate_report_data($params);
				
				break;

				case REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_ALPHALIST_TERMINATED_BEFORE_YEAR_END, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_alphalist_terminated_before_year_end');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE: 
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_YEAR_END_ADJUSTMENT_REPORT_PER_OFFFICE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/year_end_adjustment_report_per_office');
					$data            = $generate_report->generate_report_data($params);
					$margin_left     = 10;
					$margin_bottom   = 10;
					$margin_right    = 10;
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
				break;

				case REPORT_DISBURSEMENT_VOUCHER: 
					$set_footer      = FALSE;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_DISBURSEMENT_VOUCHER, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/disbursement_voucher');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_ENGAS_FILE_FOR_UPLOADING:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_ENGAS_FILE_FOR_UPLOADING, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/eNGAS_file_for_uploading');
					$data            = $generate_report->generate_report_data($params);

				break;

				case REPORT_GSIS_CERTIFICATE_CONTRIBUTION:
					$cert_footer     = TRUE;
					$set_footer      = FALSE;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GSIS_CERTIFICATE_CONTRIBUTION, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/gsis_certificate_of_contribution');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION:
					$cert_footer     = TRUE;
					$set_footer      = FALSE;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PHILHEALTH_CERTIFICATE_CONTRIBUTION, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/philhealth_certificate_of_contribution');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION:
					$cert_footer     = TRUE;
					$set_footer      = FALSE;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PAGIBIG_CERTIFICATE_CONTRIBUTION, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/pagibig_certificate_of_contribution');
					$data            = $generate_report->generate_report_data($params);
				break;

				case REPORT_PAGIBIG_MEMBERSHIP_FORM:
					$margin_footer	 = 10;
					$margin_header	 = 10;
					$margin_left	 = 10;
					$margin_right	 = 10;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PAGIBIG_MEMBERSHIP_FORM, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/pagibig_membership_form');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;

				case REPORT_PHILHEALTH_MEMBERSHIP_FORM:
					$margin_footer	 = 10;
					$margin_header	 = 10;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_PHILHEALTH_MEMBERSHIP_FORM, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/philhealth_membership_form');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;

				case REPORT_GSIS_MEMBERSHIP_FORM:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GSIS_MEMBERSHIP_FORM, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/gsis_membership_form');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;
				
				case REPORT_BIR_2307_CERTIFICATE_OF_CREDITABLE_TAX_WITHHELD_AT_SOURCE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_2307_CERTIFICATE_OF_CREDITABLE_TAX_WITHHELD_AT_SOURCE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_2307_certificate_of_creditable_tax_withheld_at_source');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;
				
				case REPORT_EMPLOYEES_PAID_BY_VOUCHER:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_EMPLOYEES_PAID_BY_VOUCHER, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/employees_paid_by_voucher');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;
				
				case REPORT_BIR_2306_CERTIFICATE_OF_FINAL_TAX_WITHHELD_AT_SOURCE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_2306_CERTIFICATE_OF_FINAL_TAX_WITHHELD_AT_SOURCE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_2306_certificate_of_final_tax_withheld_at_source');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;
				
				case REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL:
					$set_footer      = FALSE;
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_EMPLOYEES_NOT_INCLUDED_IN_PAYROLL, $payroll_type);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/employees_not_included_in_payroll');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = TRUE;
				break;
				
				case REPORT_COOP_REMITTANCE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_COOP_REMITTANCE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/coop_remittance');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = TRUE;
				break;
				
				case REPORT_BIR_2305_CERTIFICATE_OF_UPDATE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_BIR_2305_CERTIFICATE_OF_UPDATE, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/bir_2305_certificate_of_update');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;

					$margin_left     = 10;
					$margin_bottom   = 5;
					$margin_right    = 10;
					$margin_top      = 5;
					$margin_header   = 5;
					$margin_footer   = 5;
				break;
				
				case REPORT_RESPONSIBILITY_CODE_PER_OFFICE:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_RESPONSIBILITY_CODE_PER_OFFICE, 0);
					/* 
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					*/
					// ====================== jendaigo : start : change to landscape ============= //
					$paper_width     = $paper_size['size_length'];
					$paper_length    = $paper_size['size_width'];
					// ====================== jendaigo : end : change to landscape ============= //
					
					$generate_report = modules::load('main/reports_payroll/responsibility_code_per_office');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
					$set_footer_legend = TRUE; //jendaigo: footer legend

					$margin_left     = 10;
					$margin_bottom   = 10;
					$margin_right    = 10;
					$margin_top      = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
				break;
				
				case REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_GENERAL_PAYROLL_ALPHALIST_FOR_JO, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/general_payroll_alpha_list_for_jo');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
					$set_footer_legend = TRUE; //jendaigo: footer legend
					
					$margin_left     = 10;
					$margin_right    = 10;
					$margin_top      = 10;
					$margin_bottom   = 10;
					$margin_header   = 10;
					$margin_footer   = 10;
				break;
				case REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT:
					$paper_size 	 = $this->rm->get_report_paper_size(REPORT_EXPANDED_WITHHOLDING_TAX_AND_GOVERNMENT_MONEY_PAYMENT, 0);
					$paper_width     = $paper_size['size_width'];
					$paper_length    = $paper_size['size_length'];
					$generate_report = modules::load('main/reports_payroll/expanded_withholding_tax_and_government_money_payment');
					$data            = $generate_report->generate_report_data($params);
					$set_footer      = FALSE;
				break;
				
			}

			if(EMPTY($paper_size)) {
				throw new Exception($this->lang->line('param_not_defined'));
			}
			$data['format'] = $format;
			if($report == REPORT_BIR_ALPHALIST) {
				
				if(strtolower($format) == 'excel')
				{	
					
					$this->_generate_bir_alphalist_excel($data['records']);
				
				}
				else 
				{
					header('Content-Type: text/DAT; charset=utf-8');
					header('Content-Disposition: attachment; filename=BIR_ALPHALIST_'.date($params['year_only'].'md').'.DAT');

					$file = fopen("php://output","w");
					$record_arr = array();
				    $record_arr[] = 'H1604CF';
				    $record_arr[] = '000791464';
				    $record_arr[] = '0000';
				    $record_arr[] = '12/31/'.$params['year_only'];
				    $record_arr[] = 'N';
				    $record_arr[] = '0';

				    fwrite($file, implode(',', $record_arr)."\n");
					/* HEADER OF D7.1 */
					$record = $data['records']['D7.1'][0];
					if(!EMPTY($record)) {
			
					    /* D7.1 DETAILS */
						$details1 = array();
						foreach ($data['records']['D7.1'] as $key => $value) {
							$doh_tin  = preg_replace("/[^0-9]/", "", $value['2']);
							$value['2'] = substr($doh_tin, 0,9);

							$emp_tin  = preg_replace("/[^0-9]/", "", $value['6']);
							if(strlen($emp_tin) < 9)
							{
								$repeat = 9 - strlen($emp_tin);
								$emp_tin = $emp_tin.str_repeat('0',$repeat);
							}
							if(strlen($value['7']) < 9)
							{
								$repeat     = 4 - strlen($value['7']);
								$value['7'] = $value['7'].str_repeat('0',$repeat);
							}
							$value['6'] = substr($emp_tin, 0,9);
							$value['8']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['8']));
							$value['9']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['9']));
							$value['10'] = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['10']));
						    fwrite($file, implode(',', $value)."\n");
						    foreach ($value as $key2 => $val) {
						        if($key == 0 AND $key2 < 5) 
						        	$details1[$key2] = ($key2 == 0) ? 'C7.1' : $val;
						        if($key2 > 12 AND $key2 != 23 AND $key2 != 33) 
						        	$details1[$key2-9] += $val;
						    }
						    $details1['4'] = $value['4'];
						} 
						/* D7.1 SUMMARY */
						fwrite($file, implode(',', $details1)."\n");
					}
					
					/* HEADER OF D7.2 */
					$record = $data['records']['D7.2'][0];
					if(!EMPTY($record)) {
						
					    /* D7.2 DETAILS */
						$details2 = array();
						foreach ($data['records']['D7.2'] as $key => $value) {
							$doh_tin    = preg_replace("/[^0-9]/", "", $value['2']);
							$value['2'] = substr($doh_tin, 0,9);
							
							$emp_tin    = preg_replace("/[^0-9]/", "", $value['6']);
							if(strlen($emp_tin) < 9)
							{
								$repeat  = 9 - strlen($emp_tin);
								$emp_tin = $emp_tin.str_repeat('0',$repeat);
							}
							if(strlen($value['7']) < 9)
							{
								$repeat = 4 - strlen($value['7']);
								$value7 = $value['7'].str_repeat('0',$repeat);
							}
							$value['7']  = substr($value7, 0,4);
							$value['6']  = substr($emp_tin, 0,9);
							$value['8']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['8']));
							$value['9']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['9']));
							$value['10'] = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['10']));

						    fwrite($file, implode(',', $value)."\n");
						    foreach ($value as $key2 => $val) {
						        if($key == 0 AND $key2 < 5) $details2[$key2] = ($key2 == 0) ? 'C7.2' : $val;
						        if($key2 > 10 AND $key2 != 20) $details2[$key2-7] += $val;
						    }
						    $details2['4'] = $value['4'];
						} 
						/* D7.2 SUMMARY */
						fwrite($file, implode(',', $details2)."\n");

					    // fwrite($file, implode(',', $record_arr)."\n");
					    /* D7.3 DETAILS */
						$details3 = array();
						foreach ($data['records']['D7.3'] as $key => $value) {
							$doh_tin  = preg_replace("/[^0-9]/", "", $value['2']);
							$value['2'] = substr($doh_tin, 0,9);

							$emp_tin  = preg_replace("/[^0-9]/", "", $value['6']);
							if(strlen($emp_tin) < 9)
							{
								$repeat = 9 - strlen($emp_tin);
								$emp_tin = $emp_tin.str_repeat('0',$repeat);
							}
							if(strlen($value['7']) < 9)
							{
								$repeat     = 4 - strlen($value['7']);
								$value['7'] = $value['7'].str_repeat('0',$repeat);
							}
							$value['6'] = substr($emp_tin, 0,9);
							$value['8']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['8']));
							$value['9']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['9']));
							$value['10'] = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['10']));
						    fwrite($file, implode(',', $value)."\n");

						    foreach ($value as $key2 => $val) {
						        if($key == 0 AND $key2 < 5) $details3[$key2] = ($key2 == 0) ? 'C7.3' : $val;
						        if($key2 > 10 AND $key2 != 22 AND $key2 != 31) $details3[$key2-7] += $val;
						    }
							$details3['4'] = $value['4'];
						} 
						/* D7.3 SUMMARY */
						fwrite($file, implode(',', $details3)."\n");
					}

					/* HEADER OF D7.4 */
					$record = $data['records']['D7.4'][0];
					if(!EMPTY($record)) {
						
						/* D7.4 DETAILS */
						$details4 = array();
						foreach ($data['records']['D7.4'] as $key => $value) {
							$doh_tin  = preg_replace("/[^0-9]/", "", $value['2']);
							$value['2'] = substr($doh_tin, 0,9);

							$emp_tin  = preg_replace("/[^0-9]/", "", $value['6']);
							if(strlen($emp_tin) < 9)
							{
								$repeat = 9 - strlen($emp_tin);
								$emp_tin = $emp_tin.str_repeat('0',$repeat);
							}
							if(strlen($value['7']) < 9)
							{
								$repeat     = 4 - strlen($value['7']);
								$value['7'] = $value['7'].str_repeat('0',$repeat);
							}
							$value['6'] = substr($emp_tin, 0,9);
							$value['8']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['8']));
							$value['9']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['9']));
							$value['10'] = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['10']));
						    fwrite($file, implode(',', $value)."\n");

						    foreach ($value as $key2 => $val) {
						        if($key == 0 AND $key2 < 5) $details4[$key2] = ($key2 == 0) ? 'C7.4' : $val;
						        if($key2 > 10 AND $key2 != 31) $details4[$key2-7] += $val;
						    }
							$details4['4'] = $value['4'];
						} 
						/* D7.4 SUMMARY */
						fwrite($file, implode(',', $details4)."\n");
					}

					/* HEADER OF D7.5 */
					$record = $data['records']['D7.5'][0];
					if(!EMPTY($record)) {
					

						/* D7.4 DETAILS */
						$details5 = array();
						foreach ($data['records']['D7.5'] as $key => $value) {
							$doh_tin  = preg_replace("/[^0-9]/", "", $value['2']);
							$value['2'] = substr($doh_tin, 0,9);

							$emp_tin  = preg_replace("/[^0-9]/", "", $value['6']);
							if(strlen($emp_tin) < 9)
							{
								$repeat = 9 - strlen($emp_tin);
								$emp_tin = $emp_tin.str_repeat('0',$repeat);
							}
							if(strlen($value['7']) < 9)
							{
								$repeat     = 4 - strlen($value['7']);
								$value['7'] = $value['7'].str_repeat('0',$repeat);
							}
							$value['6'] = substr($emp_tin, 0,9);
							$value['8']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['8']));
							$value['9']  = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['9']));
							$value['10'] = preg_replace('/[^a-zA-Z\']/', '',str_replace('Ñ', 'N', $value['10']));
							

							$value['26'] = WHOLE_YEAR_WORKING_DAYS;
							$value['21'] = '12/31/'.$params['year_only'];

						    fwrite($file, implode(',', $value)."\n");

						    foreach ($value as $key2 => $val) {
						        if($key == 0 AND $key2 < 5) $details5[$key2] = ($key2 == 0) ? 'C7.5' : $val;
						        if($key2 > 10 AND $key2 != 31) $details5[$key2-7] += $val;
						    }
							$details5['4'] = $value['4'];
						} 
						/* D7.4 SUMMARY */
						fwrite($file, implode(',', $details5)."\n");
					}

					fclose($file); 
				}
				

			} else {
				// $filename = $report . "_".date('m').date('d').date('Y');
				// ====================== jendaigo : start : include payroll period in the filename ============= //
				$filename = $report . "_".$data['payroll_period']['date_from']."_to_".substr($data['payroll_period']['date_to'],-2);
				// ====================== jendaigo : end : include payroll period in the filename ============= //
				
				if(strtolower($format) == 'pdf')
				{
					ini_set('memory_limit', '512M'); // boost the memory limit if it's low
					$this->load->library('pdf');
					
					if ($report == REPORT_REPORT_BIR_2316_CERTIFICATE_OF_COMPENSATION_PAYMENT)
					{
						$pdf 	= $this->pdf->load('utf-8', array($paper_width,$paper_length), $margin_left=5,$margin_right=5,$margin_top=15,$margin_bottom=15,$margin_header=5,$margin_footer=5);
					} else {

						$pdf 	= $this->pdf->load('utf-8', array($paper_width,$paper_length), $margin_top,$margin_bottom,$margin_left,$margin_right,$margin_header,$margin_footer);
					}
					$footer = '<table width="100%">';
					$footer .= '<tr>';
					$footer .= '<td align="left" valign="top" style="margin-top: 10px;"><font size="3">&nbsp;'. $tracking_code .'</font></td>';
					if($set_footer)
					{
						$footer .= '<td align="right"><font size="2">' . $sheet . ' {PAGENO} of {nb}<font size="2"></td>';
					}
					
					// ====================== jendaigo : start : footer legend ============= //
					if($set_footer_legend)
						$footer .= '<td align="left"><font size="2"><strong>BS</strong> = Basic Salary, <strong>BSP</strong> = Basic Salary + Premium, <strong>HMDF</strong> = Home Development Mutual Fund (Pag-IBIG), <strong>SSS</strong> = Social Security System, <strong>PHIC</strong> = Philippine Health Insurance Corporation<font size="2"></td>';
					// ====================== jendaigo : end : footer legend ============= //
					
					$footer .= '</tr>';
					$path = base_url().PATH_IMG;
					if($cert_footer)
					{
						// $footer .= '<tr>';
						// $footer .= '<td align="center"><img src="' . $path . 'health for all footer logo.png" width=150 height=50></img></td>';
						// $footer .= '</tr>';
						$footer .= '<tr>';
						$footer .= '<td style="border-top: 1px solid #000000;" align="center" height="15" valign="bottom"><font size="2">'.$footer_line1.'</font></td>';
						$footer .= '</tr>';
						$footer .= '<tr>';
						$footer .= '<td align="center"><font size="2">'.$footer_line2.'</font></td>';
						$footer .= '</tr>';
					}

					$footer .= '</table>';
					$pdf->SetHTMLFooter($footer);

					// marvin : custom report : start
					switch($data['header'])
					{
						case 'PAGIBIG':
							$pdf->defaultheaderline = false;
							$pdf->SetHeader('Page No.: {PAGENO} of {nbpg}');
							$data['office_name'] = $data['records'][0]['office_name'];
							$report = 'remittance_list_per_office_pagibig';
							break;

						case 'Withholding Tax':
							// echo '<pre>';
							// print_r($data);
							// die();
							break;

						default:
							//do nothing
					}
					// marvin : custom report : end

					$html 	= $this->load->view('forms/reports/' . $report , $data, TRUE);
					
					$pdf->WriteHTML($html);
					$pdf->Output($filename.".pdf", "I");
				}

				if(strtolower($format) == 'excel')
				{
					// ====================== jendaigo : start : format excel of bank payroll register ============= //
					if ($report == REPORT_BANK_PAYROLL_REGISTER)
					{
						$this->_generate_bank_payroll_register_excel($data, $filename);
					}
					else
					{
					// ====================== jendaigo : end : format excel of bank payroll register ============= //
						$this->load->view('forms/reports/' . $report , $data);
						
						$echo = ob_get_contents();
						ob_end_clean();
							
						header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
						header("Content-Disposition: attachment; filename=".$filename.".xls");
						header("Expires: 0");
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header("Cache-Control: private",false);
						
						echo $echo;
					} // jendaigo : format excel of bank payroll register
				}
					
			}
			
		}
		catch (PDOException $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}		
	}

	public function get_payroll_period()
	{
		
		$list		= array();
		
		$params		= get_params();
		$p_period   = $this->rm->get_payroll_period($params['payroll_type']);
		
		if(!EMPTY($p_period ))
		{
			foreach ($p_period  as $aRow):
				// $list[] = array(
				// 				"value" => $aRow["attendance_period_hdr_id"],
				// 				"text" => $aRow['payroll_period']
				// 		);
				
				// marvin : include batching : start
				if(empty($aRow['remarks']))
				{
					$list[] = array(
						"value" => $aRow["attendance_period_hdr_id"],
						"text" => $aRow['payroll_period']
					);
				}
				else
				{
					$list[] = array(
						"value" => $aRow["attendance_period_hdr_id"],
						"text" => $aRow['payroll_period'] . ' (' . $aRow['remarks'] . ')'
					);
				}
				// marvin : include batching : end
			endforeach;
		}	
		
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}

	public function get_remittance_period($consolidated=FALSE)
	{
		
		$list     = array();
		$group_by = array();
		$field    = array();
		$params   = get_params();
		if(!EMPTY($params['remittance_type_id'])) {
			$where['remittance_type_id'] = $params['remittance_type_id'];
			$field[] = 'remittance_id';
		}
		else {
			$field[] = 'group_concat(remittance_id) remittance_id';
			$group_by = array('deduction_start_date');
		}
		
		$field[] = 'CONCAT(DATE_FORMAT(deduction_start_date,\'%M %d\'), \' - \', DATE_FORMAT(deduction_end_date,\'%d, %Y\')) remittance_period';
		$table = $this->rm->tbl_remittances;
		
		if($consolidated)
			$where['remittance_status_id'] = REMITTANCE_REMITTED;
		$remittance_period = $this->rm->get_reports_data($field, $table, $where, TRUE, array(), $group_by);
		
		if(!EMPTY($remittance_period ))
		{
			foreach ($remittance_period  as $aRow):
				$list[] = array(
					"value" => $aRow["remittance_id"],
					"text" => $aRow['remittance_period']
						);
			endforeach;
		}	
		$flag = ($list) ? 1 : 0;
		$info = array(
				"list" => $list,
				"flag" => $flag
		);
	
		echo json_encode($info);
	}
	
	public function get_payslip_office(){
		try
		{
		
			$flag 			= 0;
			$msg			= "";
			$options 		= array();
			$params 		= get_params();
			
			$where		 	 			= array();
			$where['A.payroll_type_id']	= $params['payroll_type'];
			$results 					= $this->rm->get_office_gen_pay($where);
			
			$options[] = array(
					'id' => "",
					'name' => "Select Offices...",
			);
			if(!EMPTY($results))
			{
				foreach($results as $data)
				{
					$options[] = array(
							'id' => $data['office_id'],
							'name' => $data['office_name'],
					);
				}
			}else{
				$options[] = array(
						'id' => "",
						'name' => "",
				);
			}
		
			// 			echo json_encode($results);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
		
		echo json_encode( $info );
	}
	
	public function get_employee_by_office(){
		try
		{
	
			$flag 			= 0;
			$msg			= "";
			$options 		= array();
			$params 		= get_params();
				
			$where		 	 				= array();
			$where['A.employ_office_id']	= $params['office_id'];
			$results 						= $this->rm->get_employee_gen_pay($where);
			
				
			$options[] = array(
					'id' => "",
					'name' => "Select Employee...",
			);
			if(!EMPTY($results))
			{
				foreach($results as $data)
				{
					$options[] = array(
							'id' => $data['id'],
							'name' => $data['name'],
					);
				}
			}else{
				$options[] = array(
						'id' => "",
						'name' => "",
				);
			}
	
			// 			echo json_encode($results);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
	
		echo json_encode( $info );
	}
	
	public function get_signatoty_details($param)
	{
		$field 						= array("UPPER(CONCAT(A.first_name,' ', LEFT(A.middle_name, 1), '. ',A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)), IF(B.others_value IS NULL, '', CONCAT(', ', B.others_value)))) as employee_name", "A.employee_id");
		$table 						= array(
				'main'				=> array(
						'table'	 	=> $this->rm->tbl_employee_personal_info,
						'alias'		=> 'A'
				),
				't2'				=> array(
						'table' 	=> $this->rm->tbl_employee_other_info,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition' => 'A.employee_id =  B.employee_id AND B.other_info_type_id = ' . OTHER_INFO_TYPE_TITLE,
				),
				't3'				=> array(
						'table' 	=> $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param,
						'alias'		=> 'C',
						'type'		=> 'JOIN',
						'condition' => 'A.agency_employee_id = C.sys_param_value'
				)
		);
		
		$where                     = array();
		$where['C.sys_param_type'] = $param;
		$where['C.active_flag']    = 'Y';
		
		return $this->rm->get_reports_data($field, $table, $where);
	}
	
	public function get_deduction_types_by_remittance()
	{
		try
		{
			
			$flag 		= 0;
			$msg		= "";
			$options	= array();
			$params 	= get_params();
			
			$where		= array();
			
			$field      = array("B.deduction_id id", "B.deduction_name name") ;
			$table      = array(
					'main' => array(
							'table' =>$this->rm->tbl_param_remittance_types,
							'alias'	=> 'A'
					),
					't2' => array(
							'table' 	=> $this->rm->tbl_param_deductions,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.remittance_type_id = B.remittance_type_id'
					)
					
			);
			$where['A.remittance_type_id']	= $params['remittance_type'];
			
			$results 	= $this->rm->get_reports_data($field, $table, $where);
			
			$options[] = array(
					'id' => "",
					'name' => "Select Deduction Type...",
			);
			if(!EMPTY($results))
			{
				foreach($results as $data)
				{
					$options[] = array(
							'id' => $data['id'],
							'name' => $data['name'],
					);
				}
			}else{
				$options[] = array(
						'id' => "",
						'name' => "",
				);
			}
			
			// 			echo json_encode($results);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
		
		echo json_encode( $info );
		
	}
	
	public function get_deduction_types_by_contribution()
	{
		try
		{
			
			$flag 		= 0;
			$msg		= "";
			$options	= array();
			$params 	= get_params();
			
			$where		= array();
			
			$field      = array("B.deduction_id id", "B.deduction_name name") ;
			$table      = array(
					'main' => array(
							'table' =>$this->rm->tbl_param_remittance_types,
							'alias'	=> 'A'
					),
					't2' => array(
							'table' 	=> $this->rm->tbl_param_deductions,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.remittance_type_id = B.remittance_type_id'
					)
					
			);
			$where['A.remittance_payee_id']	= DEDUC_GSIS;
			
			$options 	= $this->rm->get_reports_data($field, $table, $where);
			
			// 			echo json_encode($results);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
		
		echo json_encode( $info );
		
	}
	public function get_payout_dates()
	{
		try
		{
			
			$flag 		= 0;
			$msg		= "";
			$options	= array();
			$params 	= get_params();
			
			$where		= array();
			
			$field      = array("B.payout_summary_date_id id", "B.effective_date name") ;
			$table      = array(
					'main' => array(
							'table' =>$this->rm->tbl_payout_summary,
							'alias'	=> 'A'
					),
					't2' => array(
							'table' 	=> $this->rm->tbl_payout_summary_dates,
							'alias' 	=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payroll_summary_id = B.payout_summary_id'
					)
					
			);
			$where['A.attendance_period_hdr_id']	= $params['period_id'];
			
			$options 	= $this->rm->get_reports_data($field, $table, $where);
			
			// 			echo json_encode($results);
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			$msg = $e->getMessage();
			throw $e;
		}
		$info = array(
				"flag"	=> $flag,
				"msg" 	=> $msg,
				'options' => $options
		);
		
		echo json_encode( $info );
		
	}
	private function _write_to_excel($objWorkSheet, $cell=NULL, $cell_text=NULL, $style=array(), $row_height=NULL, $column_width=NULL, $dimension=NULL, $bordered=TRUE) 
	{
		try
		{
			if(!EMPTY($cell))
			{
				$cell_arr = explode(':', $cell);
				$objWorkSheet->setCellValue($cell_arr[0], $cell_text);
			}
			if(!EMPTY($cell))
			{
				$objWorkSheet->mergeCells($cell);
			}
			if(!EMPTY($style) AND !EMPTY($cell))
			{
				$objWorkSheet->getStyle($cell)->applyFromArray($style);
			}
			if(!EMPTY($row_height) AND !EMPTY($dimension))
			{
				$objWorkSheet->getRowDimension($dimension)->setRowHeight($row_height);
			}
			if(!EMPTY($column_width) AND !EMPTY($dimension))
			{
				$objWorkSheet->getColumnDimension($dimension)->setWidth($column_width);
			}
			if($bordered AND !EMPTY($cell)) {

				$objWorkSheet->getStyle($cell)->applyFromArray(array('borders' => array(
						          	'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
						      	)));
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	private function _generate_bir_alphalist_excel($records=array())
	{
		$objPHPExcel = new PHPExcel();

		$styleHeader = array(
	    'font'  => array(
	        'bold'  => true,
	        'color' => array('rgb' => '000000'),
	        'size'  => 10,
	        'name'  => 'Verdana'
	      ),
	     'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true ),
	     'borders' => array(
	     		'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
	     )
	    );

		$styleArray = array(
	    'font'  => array(
	        'color' => array('rgb' => '000000'),
	        'size'  => 7,
	        'name'  => 'Verdana'
	      ),
	     'alignment' => array( 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 'wrap' => true ),
	     'borders' => array(
	     		'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
	     )
	    );

	    $objWorkSheet = $objPHPExcel->getSheet(0);
	 	$objWorkSheet->setTitle('SCHEDULE 7.1');

	 	$this->_write_to_excel($objWorkSheet, 'A1:AA1', ' ALPHALIST OF EMPLOYEES TERMINATED BEFORE DECEMBER 31     (Reported Under BIR Form No. 2316)', $styleHeader, NULL, NULL, 1, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A2:A4', 'SEQ. NO', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'B2:B4', 'TIN', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C2:E2', 'NAME OF EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C3:C4', 'Last Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'D3:D4', 'First Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'E3:E4', 'Middle Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F2:G2', 'INCLUSIVE DATE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F3:F4', 'Start Date', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G3:G4', 'End Date', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'H2:Q2', '(4) GROSS COMPENSATION INCOME', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'H3:H4', 'Gross Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'I3:M3', 'NON - TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'I4:I4', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'J4:J4', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'K4:K4', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L4:L4', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'M4:M4', 'Total Non-Taxable/Exempt Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N3:Q3', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N4:N4', 'Basic Salary', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'O4:O4', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P4:P4', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Q4:Q4', 'Total Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R2:S3', 'EXEMPTION', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R4:R4', 'Code', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'S4:S4', 'Amount', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'T2:T4', 'Premium Paid on Health and/or Hospital Insurance', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U2:U4', 'Net Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'V2:V4', 'Tax Due (JAN-DEC)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'W2:W4', 'Tax withheld (JAN-NOV)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'X2:Y2', 'YEAD-END ADJUSTMENT', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'X3:X4', 'AMOUNT WITHHELD AND PAID FOR IN DECEMBER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Y3:Y4', 'OVER WITHHELD TAX REFUNDED TO EMPLOYEE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Z2:Z4', 'AMOUNT OF TAX WITHHELD AS ADJUSTED', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AA2:AA4', 'Substituted Filing? Yes/No', $styleArray);
	 	
	 	$record = $records['D7.1'][0];
	 	
		if(!EMPTY($record)) {
		
			$details1 = array();
			$num      = 0;
		    foreach ($records['D7.1'] as $key => $value) {
		    	  $alpha = 'A';
		          foreach ($value as $key2 => $val) {
		            if($key == 0 AND $key2 < 5) {
		              $details1[$key2] = ($key2 == 0) ? 'C7.1' : $val;
		            }
		            if($key2 > 12 AND $key2 != 23 AND $key2 != 32) {
		              $details1[$key2-8] += $val;
		            }
		          
		            $num = $key + 5;
		           
		            if($key2 >= 5 AND $key2 != 7) {
		            	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', is_numeric($val) ? number_format($val,2) : $val, $styleArray, '8');

		            }
		          }


		    } 
		    $alpha = 'A';
		    $num++;
	    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
	    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    foreach ($details1 as $key => $value) {
		      	if($key > 4 AND $key != 16)
		        	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	elseif($key == 4)
		    		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', 'TOTAL', $styleArray, '8');
		    	elseif($key == 16) {
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	}
		    	else
		    		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    }

		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		} else {
		  	$this->_write_to_excel($objWorkSheet, 'A5:AA5', 'No result for ALPHALIST OF EMPLOYEES TERMINATED BEFORE DECEMBER 31.', $styleArray, '8');
		    
		}

		$objWorkSheet = $objPHPExcel->createSheet(1); //Setting index when creating
	 	$objWorkSheet->setTitle('SCHEDULE 7.2');

	 	$this->_write_to_excel($objWorkSheet, 'A1:R1', 'ALPHALIST OF EMPLOYEES WHOSE COMPENSATION INCOME ARE EXEMPT FROM WITHHOLDING TAX BUT SUBJECT TO INCOME TAX      (Reported Under BIR Form No. 2316) (Applicable from January 1 to July 5, 2008) ', $styleHeader, NULL, NULL, 1, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A2:A4', 'SEQ. NO', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'B2:B4', 'TIN', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C2:E2', 'NAME OF EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C3:C4', 'Last Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'D3:D4', 'First Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'E3:E4', 'Middle Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F2:M2', '(4) GROSS COMPENSATION INCOME', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F3:F4', 'Gross Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G3:J3', 'NON - TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G4:G4', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'H4:H4', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'I4:I4', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'J4:J4', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'K3:K4', 'Total Non-Taxable/Exempt Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L3:M3', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L4:L4', 'Basic Salary', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'M4:M4', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N2:O3', 'EXEMPTION', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N4:N4', 'Code', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'O4:O4', 'Amount', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P2:P4', 'Premium Paid on Health and/or Hospital Insurance', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Q2:Q4', 'Net Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R2:R4', 'Tax Due', $styleArray);
	 	
	 	$record = $records['D7.2'][0];
	 	
		if(!EMPTY($record)) {
		

			$details1 = array();
			$num      = 0;
		    foreach ($records['D7.2'] as $key => $value) {
		    	  $alpha = 'A';
		          foreach ($value as $key2 => $val) {
		            if($key == 0 AND $key2 < 5) {
		              $details1[$key2] = ($key2 == 0) ? 'C7.1' : $val;
		            }
		            if($key2 > 10 AND $key2 != 19) {
		              $details1[$key2-6] += $val;
		            }
		          
		            $num = $key + 5;
		           
		            if($key2 >= 5 AND $key2 != 7) {
		            	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', is_numeric($val) ? number_format($val,2) : $val, $styleArray, '8');

		            }
		          }
		    } 
		    
		    $alpha = 'A';
		    $num++;
		    foreach ($details1 as $key => $value) {
		      	if($key > 4 AND $key != 14)
		        	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	elseif($key == 4)
		    		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', 'TOTAL', $styleArray, '8');
		    	elseif($key == 14) {
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	}
		    	else
		    		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    }
		} else {
		  	$this->_write_to_excel($objWorkSheet, 'A5:R5', 'No result for ALPHALIST OF EMPLOYEES WHOSE COMPENSATION INCOME ARE EXEMPT FROM WITHHOLDING TAX BUT SUBJECT TO INCOME TAX.', $styleArray, '8');
		    
		}

		$objWorkSheet = $objPHPExcel->createSheet(2); //Setting index when creating
	 	$objWorkSheet->setTitle('SCHEDULE 7.3');

	 	$this->_write_to_excel($objWorkSheet, 'A1:Y1', 'ALPHALIST OF EMPLOYEES  AS OF DECEMBER 31 WITH NO PREVIOUS EMPLOYER WITHIN THE YEAR     (Reported Under  BIR Form No.2316)', $styleHeader, NULL, NULL, 1, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A2:A4', 'SEQ. NO', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'B2:B4', 'TIN', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C2:E2', 'NAME OF EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C3:C4', 'Last Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'D3:D4', 'First Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'E3:E4', 'Middle Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F2:O2', '(4) GROSS COMPENSATION INCOME', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F3:F4', 'Gross Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G3:K3', 'NON - TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G4:G4', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'H4:H4', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'I4:I4', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'J4:J4', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'K4:K4', 'Total Non-Taxable/Exempt Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L3:O3', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L4:L4', 'Basic Salary', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'M4:M4', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N4:N4', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'O4:O4', 'Total Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P2:Q3', 'EXEMPTION', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P4:P4', 'Code', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Q4:Q4', 'Amount', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R2:R4', 'Premium Paid on Health and/or Hospital Insurance', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'S2:S4', 'Net Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'T2:T4', 'Tax Due (JAN-DEC)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U2:U4', 'Tax withheld (JAN-NOV)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'V2:W2', 'YEAD-END ADJUSTMENT', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'V3:V4', 'AMOUNT WITHHELD AND PAID FOR IN DECEMBER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'W3:W4', 'OVER WITHHELD TAX REFUNDED TO EMPLOYEE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'X2:X4', 'AMOUNT OF TAX WITHHELD AS ADJUSTED', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Y2:Y4', 'Substituted Filing? Yes/No', $styleArray);
	 	
	 	$record = $records['D7.3'][0];
	 	
		if(!EMPTY($record)) {
		
			$details1 = array();
			$num      = 0;
		    foreach ($records['D7.3'] as $key => $value) {
		    	  $alpha = 'A';
		    	
		          foreach ($value as $key2 => $val) {
		            if($key == 0 AND $key2 < 5) {
		              $details1[$key2] = ($key2 == 0) ? 'C7.3' : $val;
		            }
		            if($key2 > 10 AND $key2 != 21 AND $key2 != 30) {
		               $details1[$key2-6] += $val;
		            }
		          
		            $num = $key + 5;
		           
		            if($key2 >= 5 AND $key2 != 7) {
		            	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', is_numeric($val) ? number_format($val,2) : $val, $styleArray, '8');

		            }
		          }


		    } 
		    $alpha = 'A';
		    $num++;
	    	
		    foreach ($details1 as $key => $value) {
		      	if($key > 4 AND $key != 16)
		        	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	elseif($key == 4)
		    		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', 'TOTAL', $styleArray, '8');
		    	elseif($key == 16) {
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	}
		    	else
		    		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    }

		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		} else {
		  	$this->_write_to_excel($objWorkSheet, 'A5:Y5', 'No result for ALPHALIST OF EMPLOYEES  AS OF DECEMBER 31 WITH NO PREVIOUS EMPLOYER WITHIN THE YEAR.', $styleArray, '8');
		    
		}

		$objWorkSheet = $objPHPExcel->createSheet(3); //Setting index when creating
	 	$objWorkSheet->setTitle('SCHEDULE 7.4');

	 	$this->_write_to_excel($objWorkSheet, 'A1:AI1', 'ALPHALIST OF EMPLOYEES  AS OF DECEMBER 31 WITH PREVIOUS EMPLOYER/S WITHIN THE YEAR     (Reported Under BIR Form No. 2316)', $styleHeader, NULL, NULL, 1, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A2:A5', 'SEQ. NO', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'B2:B5', 'TIN', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C2:E3', 'NAME OF EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C4:C5', 'Last Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'D4:D5', 'First Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'E4:E5', 'Middle Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F2:AI2', '(4) GROSS COMPENSATION INCOME', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F3:F5', 'Gross Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G3:O3', 'PREVIOUS EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G4:K4', 'NON-TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G5:G5', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'H5:H5', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'I5:I5', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'J5:J5', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'K5:K5', 'Total Non-Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L4:O4', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L5:L5', 'Basic Salary', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'M5:M5', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N5:N5', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'O5:O5', 'Total Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P3:W3', 'PRESENT EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P4:T4', 'NON-TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P5:P5', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Q5:Q5', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R5:R5', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'S5:S5', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'T5:T5', 'Total Non-Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U4:W4', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U5:U5', 'Basic Salary', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'V5:V5', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'W5:W5', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'X3:X5', 'Total Compensation Present', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Y3:Y5', 'Total Taxable (Previous & Present Employers)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Z3:AA4', 'EXEMPTION', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Z5:Z5', 'Code', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AA5:AA5', 'Amount', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AB3:AB5', 'Premium Paid on Health and/or Hospital Insurance', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AC3:AC5', 'Net Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AD3:AD5', 'Tax Due (JAN-DEC)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AE3:AF4', 'Tax withheld (JAN-NOV)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AE5:AE5', 'PREVIOUS EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AF5:AF5', 'PRESENT EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AG3:AH3', 'YEAR - END ADJUSTMENT', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AG4:AG5', 'AMOUNT WHELD & PAID FOR IN DECEMBER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AH4:AH5', 'OVER WITHHELD TAX REFUNDED TO EMPLOYEE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AI3:AI5', 'AMOUNT OF TAX WITHHELD AS ADJUSTED', $styleArray);

	 	$record = $records['D7.4'][0];
		if(!EMPTY($record)) {
		    
		    $details1 = array();
			$num      = 0;
		    foreach ($records['D7.4'] as $key => $value) {
				$alpha = 'A';
				$num   = $key + 6;
				$add   = 0;
		          foreach ($value as $key2 => $val) {
		          	
		          		if($key2 == 14) $add = 7;
		          		
			            if($key2 > 10 AND $key2 != 24 AND $key2 != 12 AND $key2 != 13) $details1[($add+$key2)-11] += $val;

			            if($key2 == 12 OR $key2 == 13) 
			            {
			            	$prev_taxes = explode(',', $val);
			            	if($key2 == 12) $t = $key2 - 11;
		            		if($key2 == 13) $t = $key2 - 7;	
			            	
			            	foreach ($prev_taxes as $tax) {
			            		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($tax,2), $styleArray, '8');
			            		$details1[$t++] += $tax;
			            	}
			            } 

			            if($key2 >= 5 AND $key2 != 7 AND $key2 != 12 AND $key2 != 13) $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', is_numeric($val) ? number_format($val,2) : $val, $styleArray, '8');
		          }
		    } 
		    
		    $alpha = 'A';
		    $num++;
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', 'TOTAL', $styleArray, '8');
		    foreach ($details1 as $key => $value) {
		      	
		      	if($key != 21) $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	else
		    	{
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	}
		    }

		} else {
			$this->_write_to_excel($objWorkSheet, 'A6:AI6', 'No result for ALPHALIST OF EMPLOYEES  AS OF DECEMBER 31 WITH PREVIOUS EMPLOYER/S WITHIN THE YEAR.', $styleArray, '8');
		}

		$objWorkSheet = $objPHPExcel->createSheet(4); //Setting index when creating
	 	$objWorkSheet->setTitle('SCHEDULE 7.5');

	 	$this->_write_to_excel($objWorkSheet, 'A1:AX1', 'ALPHALIST OF MINIMUM WAGE EARNERS     (Reported Under BIR Form No. 2316)', $styleHeader, NULL, NULL, 1, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A2:A6', 'SEQ. NO', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'B2:B6', 'TIN', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C2:E3', 'NAME OF EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'C4:C6', 'Last Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'D4:D6', 'First Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'E4:E6', 'Middle Name', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'F2:F6', 'Region No. Where Assigned', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G2:AX2', 'GROSS COMPENSATION INCOME', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G3:T3', 'PREVIOUS EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G4:Q4', 'NON-TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'G5:G6', 'Gross Compensation Income Previous', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'H5:H6', 'Basic/SMW', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'I5:I6', 'Holiday Pay', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'J5:J6', 'Overtime Pay', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'K5:K6', 'Night Shift Differential', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'L5:L6', 'Hazard Pay', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'M5:M6', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'N5:N6', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'O5:O6', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'P5:P6', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Q5:Q6', 'Total Non-Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R4:T4', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'R5:R6', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'S5:S6', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'T5:T6', 'Total Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U3:AL3', 'PRESENT EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U4:AJ4', 'NON-TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U5:V5', 'Inclusive Date of Employment', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'U6:U6', 'From', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'V6:V6', 'To', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'W5:W6', 'Gross Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'X5:X6', 'Basic SMW Per Day', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Y5:Y6', 'Basic SMW Per Month', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'Z5:Z6', 'Basic SMW Per Year', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AA5:AA6', 'Factor Used (No. of Days/Year)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AB5:AB6', 'Holiday Pay', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AC5:AC6', 'Overtime Pay', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AD5:AD6', 'Night Shift Differential', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AE5:AE6', 'Hazard Pay', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AF5:AF6', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AG5:AG6', 'De Minimis Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AH5:AH6', 'SSS,GSIS,PHIC, & Pag-ibig Contributions, and Union Dues', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AI5:AI6', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AJ5:AJ6', 'Total Non-Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AK4:AL4', 'TAXABLE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AK5:AK6', '13th Month Pay & Other Benefits', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AL5:AL6', 'Salaries & Other Forms of Compensation', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AM3:AM6', 'Total Compensation Present', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AN3:AN6', 'Total Compensation Income (Previous & Present Employers)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AO3:AP4', 'EXEMPTION', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AO5:AO6', 'Code', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AP5:AP6', 'Amount', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AQ3:AQ6', 'Premium Paid on Health and/or Hospital Insurance', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AR3:AR6', 'Net Taxable Compensation Income', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AS3:AS6', 'TAX DUE (JAN-DEC)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AT3:AU4', 'TAX WITHHELD (JAN-NOV)', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AT5:AT6', 'PREVIOUS EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AU5:AU6', 'PRESENT EMPLOYER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AV3:AW4', 'YEAD-END ADJUSTMENT', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AV5:AV6', 'AMOUNT W/HELD & PAID FOR IN DECEMBER', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AW5:AW6', 'OVER WITHHELD TAX REFUNDED TO EMPLOYEE', $styleArray);
	 	$this->_write_to_excel($objWorkSheet, 'AX3:AX6', 'AMOUNT OF TAX WITHHELD AS ADJUSTED', $styleArray);
	 	
	 	$record = $records['D7.5'][0];
	 	
		if(!EMPTY($record)) {
		    
		    $details1 = array();
			$num      = 0;
		    foreach ($records['D7.5'] as $key => $value) {
				$alpha = 'A';
				$num   = $key + 7;
				$add   = 0;
		          foreach ($value as $key2 => $val) {
		          	
		          		if($key2 == 18) $add = 4;
		          		
			            if($key2 > 11 AND $key2 != 18 AND $key2 != 19 AND $key2 != 20 AND $key2 != 21 AND $key2 != 26 AND $key2 != 40) $details1[($add+$key2)-12] += $val;

			            if($key2 == 18 OR $key2 == 19) 
			            {
			            	$prev_taxes = explode(',', $val);
			            	if($key2 == 18) $t = $key2 - 12;
		            		if($key2 == 19) $t = $key2 - 8;	
			            	
			            	foreach ($prev_taxes as $tax) {
			            		$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($tax,2), $styleArray, '8');
			            		$details1[$t++] += $tax;
			            	}
			            } 

			            if($key2 >= 5 AND $key2 != 7 AND $key2 != 18 AND $key2 != 19) $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', is_numeric($val) ? number_format($val,2) : $val, $styleArray, '8');
		          }
		    } 
		    
		    $alpha = 'A';
		    $num++;
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
		    $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', 'TOTAL', $styleArray, '8');
		    foreach ($details1 as $key => $value) {
		      	
		      	if($key != 19 AND $key != 33 AND $key != 14) $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	else
		    	{
		    		if($key == 14) $this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', '', $styleArray, '8');
			    	$this->_write_to_excel($objWorkSheet, ''.$alpha.$num.':'.$alpha++.$num.'', number_format($value,2), $styleArray, '8');
		    	}
		    }

		} else {
			$this->_write_to_excel($objWorkSheet, 'A6:AI6', 'No result for ALPHALIST OF MINIMUM WAGE EARNERS.', $styleArray, '8');
		}

	 	$writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		ob_end_clean();
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="sample.xlsx"');
		
		$writer->save('php://output');
	}

	// ====================== jendaigo : start : format excel of bank payroll register ============= //
	private function _generate_bank_payroll_register_excel($records=array(), $filename)
	{
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(12);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);

		$styleArray = array(
			'font'  => array(
				'color' => array('rgb' => '000000'),
				'size'  => 11,
				'name'  => 'Calibri'
			  ),
			 'alignment' => array( 'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 'wrap' => true ),
	    );

	    $objWorkSheet = $objPHPExcel->getSheet(0);
	 	$objWorkSheet->setTitle('PAYROLL REGISTER');
		
		$row = 1;
		if(!EMPTY($records))
		{
			foreach ($records['effective_date'] as $date)
			{
				foreach ($records['results'][$date['effective_date']] as $result)
				{
					if( $result['amount'] > 0)
					{
						$this->_write_to_excel($objWorkSheet, 'A'.$row.':A'.$row.'', (ISSET($result['emp_acct_no'])?$result['emp_acct_no']:" "), $styleArray, 8);
						$this->_write_to_excel($objWorkSheet, 'B'.$row.':B'.$row.'', (str_replace('Ñ', 'N', $result['emp_full_name'])), $styleArray, 8);
						$this->_write_to_excel($objWorkSheet, 'C'.$row.':C'.$row.'', $result['emp_amount'], $styleArray, 8);
						
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('A'.$row.'', $result['emp_acct_no'],PHPExcel_Cell_DataType::TYPE_STRING);
						$objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$row.'', $result['emp_amount'],PHPExcel_Cell_DataType::TYPE_STRING);
					}
					$row++;
				}
			}
		} else {
		  	$this->_write_to_excel($objWorkSheet, 'A1:C1', 'No results found.', $styleArray, '8');
		}

	 	$writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		ob_end_clean();
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="'.$filename.'".xls"');
	
		
		$writer->save('php://output');
	}
	// ====================== jendaigo : end : format excel of bank payroll register ============= //
}


/* End of file Reports_payroll.php */
/* Location: ./application/modules/main/controllers/reports/Reports_payroll.php */