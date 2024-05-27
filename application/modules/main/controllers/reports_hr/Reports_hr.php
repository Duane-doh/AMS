<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_hr extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('reports_hr_model', 'rm');
		$this->load->model('common_model', 'cm');
		$this->load->model('pds_model', 'pds');
		$this->load->library('Excel');
	}	

	public function index()
	{
		try
		{
			$data 			= array();
			$resources 		= array();
			
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY, 'jquery.number.min');

			$data['employees']         = $this->rm->get_employee_list();
			$data['reg_employees']     = $this->rm->get_reg_employee_list();
			$data['nosa_employees']     = $this->rm->get_nosa_employee_list();

			$data['certified_by']      = $this->cm->get_report_signatories(CODE_HR, CERTIFIED_BY);
			$data['reviewed_by']       = $this->cm->get_report_signatories(CODE_HR, APPROVED_BY);
			$data['prepared_by']       = $this->cm->get_report_signatories(CODE_HR, PREPARED_BY);

			$data['signatories']       = $this->cm->get_report_signatories(CODE_HR);

			$data['step_incr']   	   = $this->rm->get_step_incr_employees();

			$data['employee_longevity']= $this->rm->get_employees_longevity_list();

			$data['offices_list'] 	   = $this->rm->get_office_list();
			
			$field                     = array("position_level_id","UPPER(position_level_name) as position_level_name");
			$table                     = $this->rm->tbl_param_position_levels; 
			$where                     = array();
			$where['active_flag']      = YES;
			$data['position_level']    = $this->rm->get_reports_data($field, $table, $where, TRUE);

			$field                     = array("position_id","position_name");
			$table                     = $this->rm->tbl_param_positions; 
			$where                     = array();
			$where['active_flag']      = YES;
			$data['positions']    	   = $this->rm->get_reports_data($field, $table, $where, TRUE, array("position_name" => "ASC"));
			
			$field                     = array("position_class_level_id","UPPER(position_class_level_name) AS position_class_level_name");
			$table                     = $this->rm->tbl_param_position_class_levels;
			$where                     = array();
			$where['active_flag']      = YES;
			$data['classes']           = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("gender_code","UPPER(gender) AS gender");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_genders;
			$where                     = array();
			$data['genders']           = $this->rm->get_reports_data($field, $table, $where, TRUE);
			
			$field                     = array("profession_id","UPPER(profession_name) AS profession_name");
			$table                     = $this->rm->tbl_param_professions;
			$where                     = array();
			$where['others_flag']      = NO;
			$where['active_flag']      = YES;
			$data['professions']       = $this->rm->get_reports_data($field, $table, $where, TRUE, array("profession_name" => "ASC"));
			
			$field                     = array("employment_status_id","employment_status_name");
			$table                     = $this->rm->tbl_param_employment_status;
			$where                     = array();			
			$where['active_flag']      = YES;
			$data['employment_status'] = $this->rm->get_reports_data($field, $table, $where, TRUE, array("employment_status_name" => "ASC"));
			
			$field                     = array("compensation_id","UPPER(compensation_name) AS compensation_name");
			$table                     = $this->rm->tbl_param_compensations;
			$where                     = array();
			$where['active_flag']      = YES;
			$data['benefit_types']     = $this->rm->get_reports_data($field, $table, $where, TRUE, array("compensation_name" => "ASC"));
			
			$field                     = array("DATE_FORMAT(effectivity_date, '%m/%d/%Y') salary_adjustment_date", "effectivity_date");
			$table                     = $this->rm->tbl_param_salary_schedule;
			$where                     = array();
			$data['salary_adj_dates']  = $this->rm->get_reports_data($field, $table, $where, array('effectivity_date'), array('effectivity_date' => 'DESC'));
			
			$data['salary_grades']     = $this->rm->get_salary_grade_list();						

		/*BREADCRUMBS*/
		$breadcrumbs 			= array();

	
		$key					= "Human Resources"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/reports_hr/reports_hr/";
		$key					= "Reports"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/reports_hr/reports_hr/";

		set_breadcrumbs($breadcrumbs, TRUE);

		$this->template->load('reports_hr_view', $data, $resources);
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

	public function generate_reports($format = 'pdf', $report = NULL, $param = NULL, $office = NULL, $date = NULL, $prepared_by = NULL, $reviewed_by = NULL, $tracking_code = NULL)
	{
		try
		{
			$data            = array();
			
			$paper_width     = 210;
			$paper_length    = 297;
			$set_footer      = TRUE;
			$set_page_no     = FALSE;
			$margin_left     = 15; 
			$margin_bottom   = 15; 
			$margin_right    = 15; 
			$margin_top      = 15; 
			$margin_header   = 15;
			$margin_footer   = 20;
			$params 		 = get_params();
			$tracking_code   = strtolower($params['tracking_code']);
			
			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_FOOTER_LINE';
			$footer	    	   		   = $this->rm->get_reports_data($field, $table, $where, TRUE);			
			$footer_line1 		 	   = $footer[0]['sys_param_value'];
			$footer_line2 			   = $footer[1]['sys_param_value'];
					
			switch ($report) {
				/******************************** HR REPORTS DEMOGRAPHICS ********************************/
				case REPORT_AGE:
					$generate_report = modules::load('main/reports_hr/list_age');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_BENEFIT_ENTITLEMENT:
					$generate_report = modules::load('main/reports_hr/list_benefit_entitlement');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_BIRTH_DATE:
					$generate_report = modules::load('main/reports_hr/list_birth_date');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_CLASS:
					$generate_report = modules::load('main/reports_hr/list_class');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_EMPLOYMENT_STATUS:
					$generate_report = modules::load('main/reports_hr/list_employment_status');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;

				case REPORT_GENDER:
					$generate_report = modules::load('main/reports_hr/list_gender');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_SERVICE_LENGTH:
					$generate_report = modules::load('main/reports_hr/list_service_length');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_OFFICE:
					$generate_report = modules::load('main/reports_hr/list_office');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_POSITION_LEVEL:
					$generate_report = modules::load('main/reports_hr/list_position');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_POSITION_TITLE:
					$generate_report = modules::load('main/reports_hr/list_position_title');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_PROFESSION:
					$generate_report = modules::load('main/reports_hr/list_profession');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

 				case REPORT_SALARY_GRADE:
					$generate_report = modules::load('main/reports_hr/list_salary_grade');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_DROPPED_EMPLOYEES:	
					$generate_report = modules::load('main/reports_hr/number_of_dropped_employees');
					$data            = $generate_report->generate_report_data($param, $office, $date);		
				break;
				
				case REPORT_PROMOTED_EMPLOYEES:
					$generate_report = modules::load('main/reports_hr/number_of_promoted_employees');
					$data            = $generate_report->generate_report_data($param, $office, $date);	
				break;
				
				case REPORT_RESIGNED_EMPLOYEES:
					$generate_report = modules::load('main/reports_hr/number_of_resigned_employees');
					$data            = $generate_report->generate_report_data($param, $office, $date);	
				break;

				case REPORT_RETIREES:
					$generate_report = modules::load('main/reports_hr/number_of_retirees');
					$data            = $generate_report->generate_report_data($param, $office, $date);	
				break;

				/******************************** HR REPORTS ORGANIZATIONAL STRUCTURE ********************************/
				case REPORT_SERVICE_RECORD:
					$generate_report = modules::load('main/reports_hr/service_record');
					$data            = $generate_report->generate_report_data($param, $office, $date);
					$set_footer      = FALSE;
					$margin_top      = 5; 
					$margin_header   = 5;
					$set_page_no     = TRUE;
				break;

				case REPORT_APPOINTMENT_CERTIFICATE:
					$generate_report = modules::load('main/reports_hr/kss_porma');
					$data            = $generate_report->generate_report_data($param, $params['probationary'], $date, $prepared_by, $reviewed_by, $params['signatory_title']);
					$margin_left     = 5; 
					$margin_bottom   = 5; 
					$margin_right    = 5; 
					$margin_top      = 5; 
					$margin_header   = 5;
					$margin_footer   = 5;
					$paper_width 	 = 216;
					$paper_length 	 = 330;	
					$set_footer      = FALSE;
				break;
				//ncocampo
				case REPORT_ASSUMPTION_TO_DUTY:
					$margin_bottom   = 5; 
					$margin_top      = 5;	
					$paper_width	 = 210;
					$paper_length	 = 297;
					$set_footer      = FALSE;
					$generate_report = modules::load('main/reports_hr/assumption_to_duty');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by, $reviewed_by);
				break;
				//01/11/2024
				case REPORT_MONTHLY_ACCESSION:
					$paper_width     = 297;
					$paper_length    = 210;		
					$generate_report = modules::load('main/reports_hr/monthly_report_on_accession');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by, $reviewed_by);
				break;

				case REPORT_MONTHLY_SEPARATION:
					$paper_width     = 297;
					$paper_length    = 210;		
					$generate_report = modules::load('main/reports_hr/monthly_report_on_separation');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by, $reviewed_by);
				break;

				case REPORT_FILLED_UNFILLED_POSITION:	
					$generate_report = modules::load('main/reports_hr/filled_unfilled_position');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;
 
				case REPORT_PSIPOP_PLANTILLA:
					$paper_width     = 297;
					$paper_length    = 210;		
					$set_footer      = FALSE;
					$generate_report = modules::load('main/reports_hr/psipop_plantilla');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_PERSONNEL_MOVEMENT:	
					$paper_width     = 297;
					$paper_length    = 210;		
					$generate_report = modules::load('main/reports_hr/personnel_movement');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);
				break;

				case REPORT_PERSONAL_DATA_SHEET:
					$margin_top 	 = 10;
					$margin_bottom   = 10;
					$margin_left     = 7;
					$margin_right    = 7;
					$margin_header   = 10;
					$margin_footer 	 = 10;
					$paper_width 	 = 216;
					$paper_length 	 = 330; 	
					$generate_report = modules::load('main/reports_hr/pds_report');
					$data            = $generate_report->generate_report_data($param, $office, $date, $format);
					$set_footer      = FALSE;
				break;

				case REPORT_RAI_PART1:
					$margin_top 	 = 10;
					$margin_bottom   = 10;
					$margin_left     = 7;
					$margin_right    = 7;
					$margin_header   = 10;
					$margin_footer 	 = 10;
					$paper_width     = 330;
					$paper_length    = 216;	
					$set_footer      = FALSE;
					$set_page_no	 = TRUE;
					$generate_report = modules::load('main/reports_hr/rai_part1');
					$data            = $generate_report->generate_report_data($param, $params['office'], $date, $params['salary_grade_rai'], $reviewed_by);
				break;

				case REPORT_RAI_PART2: 
					$margin_bottom   = 3; 
					$margin_top      = 5;	
					$paper_width	 = 210;
					$paper_length	 = 297;
					$generate_report = modules::load('main/reports_hr/rai_part2');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by, $reviewed_by);
					$set_footer      = FALSE;
					

					// $paper_length     = 297;
					// $paper_width    = 210;	
					// $generate_report = modules::load('main/reports_hr/rai_part2');
					// $data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by, $reviewed_by);

				break;

				case REPORT_TRANSFEREES:
					$generate_report = modules::load('main/reports_hr/transferees');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;

				case REPORT_PRIME_HRM_ASSESSMENT:
					$generate_report = modules::load('main/reports_hr/prime_hrm_assessment');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;
				
				case REPORT_POSITION_DESCRIPTION:
					$generate_report = modules::load('main/reports_hr/position_description');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by, $reviewed_by);
					$set_footer      = FALSE;
				break;

				/******************************** HR REPORTS WELFARE AND BENEFITS ********************************/
				case REPORT_ENTITLEMENT_LONGEVITY_PAY:
					$paper_width     = 297;
					$paper_length    = 210;		
					$generate_report = modules::load('main/reports_hr/entitlement_longevity_pay');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;

				case REPORT_NOTICE_SALARY_ADJUSTMENT:
					$generate_report = modules::load('main/reports_hr/notice_of_salary_adjustment');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);				
				break;

				//NCOCAMPO 05/06/2024
				case REPORT_NOTICE_SALARY_ADJUSTMENT_COMPULSORY_RETIREMENT:
					$margin_header   = 5;
					$generate_report = modules::load('main/reports_hr/notice_of_salary_adjustment_compulsory_retirement');
					$data            = $generate_report->generate_report_data($param, $office, $date, $prepared_by);				
				break;
				//NCOCAMPO 05/06/2024

				case REPORT_NOTICE_SALARY_STEP_INCREMENT:
					$generate_report = modules::load('main/reports_hr/notice_of_salary_step_increment');
					$data            = $generate_report->generate_report_data($param, $office, $date);				
				break;

				case REPORT_NOTICE_STEP_INCREMENT:
					$generate_report = modules::load('main/reports_hr/notice_of_step_increment');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;

				case REPORT_NOTICE_LONGEVITY_PAY:
					$generate_report = modules::load('main/reports_hr/notice_longevity_pay');
					$data            = $generate_report->generate_report_data($param, $office, $date);
				break;

				case REPORT_NOTICE_LONGEVITY_PAY_INCREASE:
					$generate_report = modules::load('main/reports_hr/notice_longevity_pay_increase');
					$data            = $generate_report->generate_report_data($param, $office, $date);					
				break;

			}
			$filename = $report . "_".date('m').date('d').date('Y');
			if(strtolower($format) == 'pdf')
			{
				ini_set('memory_limit', '512M'); // boost the memory limit if it's low
				$this->load->library('pdf');
				$pdf 	= $this->pdf->load('utf-8', array($paper_width,$paper_length), $margin_top,$margin_bottom,$margin_left,$margin_right,$margin_header,$margin_footer);

				$page_no = ($set_page_no) ? 'Page {PAGENO} of {nb}' : '';
				$tracking_code = !EMPTY($tracking_code) ? $tracking_code : '.';

				$footer = '<table width="100%">';
				$footer .= '<tr>';
				$footer .= '<td align="left" valign="bottom" height="15" style="font-size: 8pt;font-family: Arial Narrow,Arial, sans-serif;">'. $tracking_code .'</td>';
				$footer .= '<td align="right" valign="bottom" height="15" style="font-size: 7pt;font-family: Arial Narrow,Arial, sans-serif;">'.$page_no .'</td>';
				$footer .= '</tr>';
				if($set_footer)
				{
					$footer .= '<tr>';
					$footer .= '<td colspan=2 style="border-top: 1px solid #000000;" align="center" height="15" valign="bottom"><font size="2">'.$footer_line1.'</font></td>';
					$footer .= '</tr>';
					$footer .= '<tr>';
					$footer .= '<td colspan=2 align="center"><font size="2">'.$footer_line2.'</font></td>';
					$footer .= '</tr>';
				}

				$footer .= '</table>';
				$pdf->SetHTMLFooter($footer);
				
				//=================== marvin : start : eligibility ========================
				if($param != 'A')
				{
					$fields 				= array('eligibility_type_id');
					$table 					= 'employee_eligibility';
					$where 					= array();
					$where['employee_id'] 	= $param;
					$result 				= $this->cm->get_general_data($fields, $table, $where, TRUE);
					foreach($result as $r)
					{
						$data['sec_record']['eligibility'][] = $r['eligibility_type_id'];			
					}					
				}
				else
				{
					foreach($data['sec_record'] as $k => $v)
					{
						$fields 				= array('eligibility_type_id');
						$table 					= 'employee_eligibility';
						$where 					= array();
						$where['employee_id'] 	= $data['sec_record'][$k]['employee_id'];
						$result 				= $this->cm->get_general_data($fields, $table, $where, TRUE);
						foreach($result as $r)
						{
							$data['sec_record'][$k]['eligibility'][] = $r['eligibility_type_id'];
						}
					}
				}
				//=================== marvin : end : eligibility ========================
				
				//=================== marvin : start : position ========================
				// if($param != 'A')
				// {
					// $fields 									= array('employ_position_id');
					// $table 										= 'employee_work_experiences';
					// $where 										= array();
					// $where['employee_id'] 						= $param;
					// $where['active_flag'] 						= 'Y';
					// $result 									= $this->cm->get_general_data($fields, $table, $where, FALSE);
					// $data['sec_record']['employ_position_id'] 	= $result['employ_position_id'];					
				// }
				// else
				// {
					// foreach($data['sec_record'] as $k => $v)
					// {
						// $fields 										= array('employ_position_id');
						// $table 											= 'employee_work_experiences';
						// $where 											= array();
						// $where['employee_id'] 							= $data['sec_record'][$k]['employee_id'];
						// $where['employ_start_date'] 					= $date;
						// $result 										= $this->cm->get_general_data($fields, $table, $where, FALSE);
						// $data['sec_record'][$k]['employ_position_id'] 	= $result['employ_position_id'];								
					// }
				// }
				// echo '<pre>';
				// print_r($data['sec_record']);
				// die();
				//=================== marvin : end : position ========================

				$html 	= $this->load->view('forms/reports/' . $report , $data, TRUE);
				
				$pdf->WriteHTML($html);
				$pdf->Output($filename.".pdf", "I");
			}

			if(strtolower($format) == 'excel')
			{
				if($report == 'pds_report') {
					$this->_generate_pds_excel($data);
				}
				else
				{

					$this->load->view('forms/reports/' . $report, $data);
					
					$echo = ob_get_contents();
					ob_end_clean();
						
					header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
					header("Content-Disposition: attachment; filename=".date('F')."_".date('d')."_".date('Y').".xls");
					header("Expires: 0");
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Cache-Control: private",false);
					
					echo $echo;
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

	private function _generate_pds_excel($data=array())
	{
		$index = 3;
		$personal_info 			  = $data['personal_info'];
		$citizenship_basis 		  = $data['citizenship_basis'];
		$contact_info 			  = $data['contact_info'];
		$identification_info 	  = $data['identification_info'];
		$identification_format 	  = $data['identification_format'];
		$residential_address_info = $data['residential_address_info'];
		$permanent_address_info   = $data['permanent_address_info'];
		$tin_val 			= '';
		$tin_format 		= '';
		$sss_val 			= '';
		$sss_format 		= '';
		$gsis_val 			= '';
		$gsis_format 		= '';
		$pagibig_val 		= '';
		$pagibig_format 	= '';
		$philhealth_val 	= '';
		$philhealth_format 	= '';
		$permanent_no 		= '';
		$email 				= '';
		$residential_no 	= '';
		$mobile_no 			= '';
		// CONTACTS VALUE
		foreach ( $contact_info as $contact ) {
			switch ($contact ['contact_type_id']) {
				case PERMANENT_NUMBER :
					$permanent_no = $contact ['contact_value'];
					break;
				case EMAIL :
					$email 		  = $contact ['contact_value'];
					break;
				case MOBILE_NUMBER :
					$mobile_no 	  = $contact ['contact_value'];
					break;
			}
		}
		// IDENTIFICATION VALUE
		foreach ( $identification_info as $identification ) {
			switch ($identification ['identification_type_id']) {
				case TIN_TYPE_ID :
					$tin_val 		= $identification ['identification_value'];
					break;
				case SSS_TYPE_ID :
					$sss_val 		= $identification ['identification_value'];
					break;
				case GSIS_TYPE_ID :
					$gsis_val 		= $identification ['identification_value'];
					break;
				case PAGIBIG_TYPE_ID :
					$pagibig_val 	= $identification ['identification_value'];
					break;
				case PHILHEALTH_TYPE_ID :
					$philhealth_val = $identification ['identification_value'];
					break;
			}
		}
		// IDENTIFICATION FORMAT
		foreach ( $identification_format as $format ) {
			switch ($format ['identification_type_id']) {
				case TIN_TYPE_ID :
					$tin_format 		= $format ['format'];
					break;
				case SSS_TYPE_ID :
					$sss_format 		= $format ['format'];
					break;
				case GSIS_TYPE_ID :
					$gsis_format 		= $format ['format'];
					break;
				case PAGIBIG_TYPE_ID :
					$pagibig_format 	= $format ['format'];
					break;
				case PHILHEALTH_TYPE_ID :
					$philhealth_format 	= $format ['format'];
					break;
			}
		}

		$objPHPExcel 		= new PHPExcel();		

		$styleFooter 		= array(
		    'font'  		=> array(
		        'color' 	=> array('rgb' => 'FE0000'),
			    'italic'	=> true,
			    'bold'		=> true,
		        'size'  	=> 8,
		        'name'  	=> 'Arial Narrow'
		    ),
		    'alignment' 	=> array( 
		     	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
		     	'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true
		    ),
		    'fill' 			=> array(
		        'type' 		=> PHPExcel_Style_Fill::FILL_SOLID,
		        'color' 	=> array('rgb' => 'e6e6e6')
	    	)
		);
		$pageNumberFooter 	= array(
		    'font'  		=> array(
			    'italic'	=> true,
		        'size'  	=> 7,
		        'name'  	=> 'Arial Narrow'
		    ),
		    'alignment' 	=> array( 
		     	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_RIGHT
		    )
		);
	    $styleHeader 		= array(
		    'font'  		=> array(
		        'bold'  	=> false,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 20,
		        'name'  	=> 'Arial Black'
		    ),
		    'alignment' 	=> array( 
		    	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
		    	'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true 
		    ),
		    'borders' 		=> array(
		    	'right' 	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
		    )
	    );
	    $styleSubHeader 	= array(
	    	'font' 			=> array(
		    	'bold'  	=> true,
		    	'italic'	=> true,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 8,
		        'name'  	=> 'Arial Narrow'
	    	),
		    'borders' 		=> array(
		    	'right' 	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
		   	)
	    );
	    $styleTitle 		= array(
	    	'font'			=> array(
		    	'bold'  	=> true,
		    	'italic'	=> true,
		        'color' 	=> array('rgb' => 'FFFFFF'),
		        'size'  	=> 11,
		        'name'  	=> 'Arial Narrow'
	    	),
		    'borders' 		=> array(
		    	'allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THICK)
		   	),
		   	'fill' 			=> array(
		        'type' 		=> PHPExcel_Style_Fill::FILL_SOLID,
		        'color' 	=> array('rgb' => '7e7e7e')
	     	)
	    );	    
	    $styleSubTitle  	= array(
	    	'font' 			=> array(
		    	'bold'  	=> false,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 8,
		        'name'  	=> 'Arial Narrow'
	    	),
	    	'alignment' 	=> array( 
		     	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 
		     	'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true
	     	),
		    'borders' 		=> array(
		    	'right' 	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		    	'top' 		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		    	'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
		   	),
		   	'fill' 			=> array(
		        'type' 		=> PHPExcel_Style_Fill::FILL_SOLID,
		        'color' 	=> array('rgb' => 'e6e6e6')
	     	)
	    );
	    $styleField 		= array(
	    	'font' 			=> array(
		    	'bold'  	=> false,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 10,
		        'name'  	=> 'Arial Narrow'
	    	),
	    	'alignment' 	=> array( 
		     	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_LEFT, 
		     	'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true
	    	),
		    'borders' 		=> array(
		    	'allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
		   	)
	    );
	    $styleCount 		= array(
	    	'font' 			=> array(
		    	'bold'  	=> false,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 8,
		        'name'  	=> 'Arial Narrow',
		        'alignment' => array('vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP)
	    	),
		   	'fill' 			=> array(
		        'type' 		=> PHPExcel_Style_Fill::FILL_SOLID,
		        'color' 	=> array('rgb' => 'e6e6e6')
	     	),
	    	'alignment' 	=> array( 
		     	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_RIGHT, 
		     	'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true
	     	),
		    'borders' 		=> array(
		    	'top' 		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		    	'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
		   	)
	    );
	    $fontItalic 		= array(
	    	'font' 			=> array(
		    	'bold'  	=> true,
		    	'italic'  	=> true,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 11,
		        'name'  	=> 'Arial Narrow'
	    	)
	    );
	    $fontItalicHeader 	= array(
	    	'font' 			=> array(
		    	'bold'  	=> true,
		    	'italic'  	=> true,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 8,
		        'name'  	=> 'Arial Narrow'
	    	)
	    );
	    $borderNoBottom 	= array(
	    	'borders' 		=> array('right' 	=> array('style' => PHPExcel_Style_Border::BORDER_THIN), 'left'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
	    );
	    $borderRightThick	= array(
	    	'borders' 		=> array(
		    	'left' 		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		    	'right' 	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
		    	'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
		    	'top' 		=> array('style' => PHPExcel_Style_Border::BORDER_THIN)
		   	)
	    );
	    $borderThick 		= array(
	    	'borders' 		=> array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))
	    );
	    $borderThin 		= array(
	    	'font' 			=> array(
		    	'bold'  	=> false,
		        'color' 	=> array('rgb' => '000000'),
		        'size'  	=> 10,
		        'name'  	=> 'Arial Narrow'
	    	),
	    	'alignment' 	=> array( 
		     	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 
		     	'vertical' 	=> PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true
	    	),
	    	'borders' 		=> array('allborders'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))
	    );
	    $smallTitle 		= array(
	    	'font'			=> array(
		    	'bold'  	=> true,
		    	'italic'	=> true,
		        'color' 	=> array('rgb' => 'FFFFFF'),
		        'size'  	=> 10,
		        'name'  	=> 'Arial Narrow'
	    	)
	    );

	    $separator 				= array('fill' => $styleSubTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
	    $questionnaireCount 	= array('font' => $styleField['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment']);
	    $questionnaireField		= array('font' => $styleField['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleField['alignment'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
	    $noBorderBottomSub 		= array('font' => $styleTitle['font'], 'fill' => $styleTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK)));
	    $noBorderTopSub 		= array('font' => $smallTitle['font'], 'fill' => $styleTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK)));
	    $centerField 			= array('font' => $styleField['font'], 'borders' => $borderRightThick['borders'], 'alignment' => $styleField['alignment']);
	    $centerSubtitle 		= array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleSubTitle['borders']);
	    $styleSignatureFooter	= array('font' => $fontItalic['font'], 'alignment' => $styleFooter['alignment'], 'fill' => $styleSubTitle['fill'], 'borders' => $borderThick['borders']);
	    $fieldThickRight 		= array('font' => $styleField['font'], 'borders' => $borderRightThick['borders'], 'alignment' => $styleField['alignment']);
	    $subTitleNoBorder 		= array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleSubTitle['alignment']);
	    $subTitleNoBorderBottom = array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleSubTitle['alignment'], 'borders' => $borderNoBottom['borders']);
	    $subTitleBorderBottom 	= array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN));


		/*********************** PAGE 1 ***********************/
		$objWorkSheet = $objPHPExcel->getSheet(0);
	 	$objWorkSheet->setTitle('C1');					    	

		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'A');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'B');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '5', 'C');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'D');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '8', 'E');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'F');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '8', 'G');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '7', 'H');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '13', 'I');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '5', 'J');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'K');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'L');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '8', 'M');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'N');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'O');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '8', 'P');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'Q');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '13', 'R');

		/*********************** HEADER ***********************/
		
		$this->_write_to_excel($objWorkSheet, 'A1:R1', NULL, array(), 2, NULL, '1');
		$this->_write_to_excel($objWorkSheet, 'A2:R2', "CS Form No. 212\nRevised 2017", array('font' => $fontItalicHeader['font'], 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 25, NULL, '2', FALSE);
		// $this->_write_to_excel($objWorkSheet, 'A3:R3', '', array('font' => $styleSubHeader['font'], 'borders' => $styleSubHeader['borders']), 10, NULL, '3', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A3:R4', 'PERSONAL DATA SHEET', $styleHeader, 30, NULL, '4', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A5:R5', 'WARNING: Any misrepresentation made in the Personal Data Sheet and the Work Experience Sheet shall cause the filing of administrative/criminal case/s against the person concerned.', $styleSubHeader, 15, NULL, '5', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A6:R6', 'READ THE ATTACHED GUIDE TO FILLING OUT THE PERSONAL DATA SHEET (PDS) BEFORE ACCOMPLISHING THE PDS FORM.', $styleSubHeader, 15, NULL, '6', FALSE);
		$str = 'Print legibly. Tick appropriate boxes ( &#9633; ) and use separate sheet if necessary. Indicate N/A if not applicable.';
		$str = html_entity_decode($str,ENT_QUOTES,'UTF-8');
		$this->_write_to_excel($objWorkSheet, 'A7:I7', $str, array('font' => $styleSubTitle['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 15, NULL, '7', FALSE);
		$this->_write_to_excel($objWorkSheet, 'J7:M7', 'DO NOT ABBREVIATE.', array('font' => array('bold' => true, 'size' => 8, 'color' => array('rgb' => '000000'), 'name' => 'Arial Narrow'), 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 15, NULL, '7', FALSE);
		$this->_write_to_excel($objWorkSheet, 'N7:O7', '1. CS ID No.', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'borders' => $styleField['borders']), 15, NULL, '7', FALSE);
		$this->_write_to_excel($objWorkSheet, 'P7:R7', '(Do not fill up. For CSC use only)', $fieldThickRight, 15, NULL, '7', FALSE);

		/*********************** PERSONAL INFO START ***********************/	   

	    $this->_write_to_excel($objWorkSheet, 'A8:R8', NULL, array('borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 4, NULL, '8', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A9:R9', 'I. PERSONAL INFORMATION', $styleTitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A10:A10', '2.', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleCount['alignment']), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A11:A12', NULL, array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B10:C10', 'SURNAME', $subTitleNoBorder, 20, NULL, '10', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D10:R10', !EMPTY($personal_info['last_name']) ? strtoupper($personal_info['last_name']):NOT_APPLICABLE, $fieldThickRight, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B11:C11', 'FIRST NAME', $subTitleNoBorder, 20, NULL, '11', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D11:M11', !EMPTY($personal_info['first_name']) ? strtoupper($personal_info['first_name']):NOT_APPLICABLE, $styleField, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'N11:P11', 'NAME EXTENSION (JR., SR)', $styleSubTitle, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'Q11:R11', !EMPTY($personal_info['ext_name']) ? strtoupper($personal_info['ext_name']):NOT_APPLICABLE, $fieldThickRight, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B12:C12', 'MIDDLE NAME', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 20, NULL, '12', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D12:R12', !EMPTY($personal_info['middle_name']) ? strtoupper($personal_info['middle_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' 	=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 20, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A13:A14', '3.', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleCount['alignment']), 20, NULL, '16', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A15:A16', NULL, array('font' => $styleCount['font'], 'fill' => $styleCount['fill']), 20, NULL, '16', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B13:C14', "DATE OF BIRTH\n(mm/dd/yyyy)", $subTitleNoBorder, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B15:C16', NULL, $subTitleNoBorder, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D13:G16', !EMPTY($personal_info['birth_date']) ? ($personal_info['birth_date']):NOT_APPLICABLE, $fieldThickRight, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H13:J14', '16. CITIZENSHIP', $subTitleNoBorderBottom, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H15:J16', NULL, $subTitleNoBorderBottom, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K13:R13', NULL, array('borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 4, NULL, '13', FALSE);

		$this->_write_to_excel($objWorkSheet, 'K14:K14', NULL, array(), 20, NULL, '14', FALSE);
		$this->_write_to_excel($objWorkSheet, 'L14:L14', ! empty($personal_info['citizenship_name']) ? '  X' : '', $styleField, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M14:M14', 'Filipino', array('font' => $styleField['font']), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'N14:N14', (strtolower($personal_info['citizenship_name']) == "filipino") ? '' : '  X', $styleField, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'O14:R14', 'Dual Citizenship', array('font' => $styleField['font'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K15:R15', NULL, array('borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 4, NULL, '15', FALSE);

		$this->_write_to_excel($objWorkSheet, 'K16:M16', NULL, array(), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'N16:N16', ($personal_info['citizenship_basis_id'] == $citizenship_basis[0]['sys_param_value']) ? "  X" : "", $styleField, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'O16:P16', 'by birth', array('font' => $styleField['font']), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'Q16:Q16', ($personal_info['citizenship_basis_id'] == $citizenship_basis[1]['sys_param_value']) ? "  X" : "", $styleField, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'R16:R16', 'by naturalization', array('font' => $styleField['font'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 20, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A17:A17', '4.', $styleCount, 20, NULL, '17', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B17:C17', 'PLACE OF BIRTH', $styleSubTitle, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D17:G17', !EMPTY($personal_info['birth_place']) ? ($personal_info['birth_place']):NOT_APPLICABLE, $fieldThickRight, 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H17:J17', 'If holder of  dual citizenship, ', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K17:M17', NULL, array(), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'N17:R17', 'Pls. indicate country:', array('font' => $styleField['font'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), 20, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A18:A18', '5.', $styleCount, 20, NULL, '18', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B18:C18', 'SEX', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D18:D18', ($personal_info['gender_code'] == 'M') ? '  X' : '', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E18:E18', 'Male', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F18:F18', ($personal_info['gender_code'] == 'F') ? '  X' : '', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G18:G18', 'Female', $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H18:J18', 'please indicate the details.', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K18:R18', (strtolower($personal_info['citizenship_name']) != 'filipino') ? strtoupper($personal_info['country_name']) : NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A19:A23', '6.', $styleCount, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B19:C23', 'CIVIL STATUS', $styleSubTitle, 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'D19:D19', ($personal_info['civil_status_id'] == CIVIL_STATUS_SINGLE) ? '  X' : '', $styleField, 20, NULL, '19', FALSE);
		$this->_write_to_excel($objWorkSheet, 'E19:E19', 'Single', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F19:F19', ($personal_info['civil_status_id'] == CIVIL_STATUS_MARRIED) ? '  X' : '', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G19:G19', 'Married', $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H19:I19', '17. RESIDENTIAL ADDRESS', $subTitleNoBorderBottom, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J19:N19', !EMPTY($residential_address_info['address_value']) ? ($residential_address_info['address_value']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'O19:R19', NULL, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'D20:D21', ($personal_info['civil_status_id'] == '3') ? '  X' : '', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E20:E21', 'Widowed', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F20:F21', ($personal_info['civil_status_id'] == '4') ? '  X' : '', $styleField, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G20:G21', 'Separated', $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H20:I21', NULL, $subTitleNoBorder, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J20:N20', 'House/Block/Lot No.', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '20', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O20:R20', 'Street', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'D22:D23', ($personal_info['civil_status_id'] == '5') ? '  X' : '', $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E22:E23', 'Other/s:', $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F22:G23', !EMPTY($personal_info['other_civil_status']) ? ($personal_info['other_civil_status']):'', $fieldThickRight, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H22:I23', NULL, $subTitleNoBorder, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J21:N22', NULL, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '21', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O21:R22', !EMPTY($residential_address_info['barangay_name']) ? ($residential_address_info['barangay_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, '22', FALSE);
		$this->_write_to_excel($objWorkSheet, 'J23:N23', 'Subdivision/Village', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '23', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O23:R23', 'Barangay', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A24:A25', '7.', $styleCount, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B24:C25', 'HEIGHT (m)', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D24:G25', !EMPTY($personal_info['height']) ? ($personal_info['height']):NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H24:I25', NULL, $subTitleNoBorder, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J24:N24', !EMPTY($residential_address_info['municity_name']) ? ($residential_address_info['municity_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 15, NULL, '24', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O24:R24', !EMPTY($residential_address_info['province_name']) ? ($residential_address_info['province_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J25:N25', 'City/Municipality', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '25', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O25:R25', 'Province', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A26:A26', '8.', $styleCount, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B26:C26', 'WEIGHT (kg)', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D26:G26', !EMPTY($personal_info['weight']) ? ($personal_info['weight']):NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H26:I26', 'ZIP CODE', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J26:R26', !EMPTY($residential_address_info['postal_number'])? strtoupper($residential_address_info['postal_number']):NOT_APPLICABLE, $fieldThickRight, 20, NULL, '26', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A27:A28', '9.', $styleCount, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B27:C28', 'BLOOD TYPE', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D27:G28', !EMPTY($personal_info['blood_type_name']) ? ($personal_info['blood_type_name']):NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H27:I28', '18. PERMANENT ADDRESS', $subTitleNoBorderBottom, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J27:N27', !EMPTY($permanent_address_info['address_value']) ? ($permanent_address_info['address_value']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 15, NULL, '27', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O27:R27', NULL, array('font' => $styleHeader['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J28:N28', 'House/Block/Lot No.', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '28', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O28:R28', 'Street', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A29:A30', '10.', $styleCount, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B29:C30', 'GSIS ID NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D29:G30', !EMPTY($gsis_val)? (is_numeric($gsis_val)?format_identifications($gsis_val,$gsis_format) : $gsis_val) : NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H29:I30', NULL, $subTitleNoBorder, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J29:N29', NULL, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 15, NULL, '29', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O29:R29', !EMPTY($permanent_address_info['barangay_name']) ? ($permanent_address_info['barangay_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J30:N30', 'Subdivision/Village', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '30', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O30:R30', 'Barangay', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A31:A32', '11.', $styleCount, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B31:C32', 'PAG-IBIG ID NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D31:G32', !EMPTY($pagibig_val)? (is_numeric($pagibig_val)? format_identifications($pagibig_val,$pagibig_format) : $pagibig_val) : NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H31:I32', NULL, $subTitleNoBorder, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J31:N31', !EMPTY($permanent_address_info['municity_name']) ? ($permanent_address_info['municity_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'O31:R31', !EMPTY($permanent_address_info['province_name']) ? ($permanent_address_info['province_name']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 15, NULL, '31', FALSE);
		$this->_write_to_excel($objWorkSheet, 'J32:N32', 'City/Municipality', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 10, NULL, '32', FALSE);
		$this->_write_to_excel($objWorkSheet, 'O32:R32', 'Province', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $fieldThickRight['borders']), 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A33:A33', '12.', $styleCount, 20, NULL, '33', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B33:C33', 'PHILHEALTH NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D33:G33', !EMPTY($philhealth_val)? (is_numeric($philhealth_val)?format_identifications($philhealth_val,$philhealth_format) : $philhealth_val) : NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H33:I33', 'ZIP CODE', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J33:R33', !EMPTY($permanent_address_info['postal_number'])? strtoupper($permanent_address_info['postal_number']):NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A34:A34', '13.', $styleCount, 20, NULL, '34', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B34:C34', 'SSS NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D34:G34', !EMPTY($sss_val)? (is_numeric($sss_val)?format_identifications($sss_val,$sss_format) : $sss_val) : NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H34:I34', '19. TELEPHONE NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J34:R34', !EMPTY($permanent_no)? format_identifications($permanent_no, TELEPHONE_FORMAT): NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A35:A35', '14.', $styleCount, 20, NULL, '35', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B35:C35', 'TIN NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D35:G35', !EMPTY($tin_val)? format_identifications($tin_val, $tin_format) : NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H35:I35', '20. MOBILE NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J35:R35', !EMPTY($mobile_no)? format_identifications($mobile_no, CELLPHONE_FORMAT): NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A36:A36', '15.', $styleCount, 20, NULL, '36', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B36:C36', 'AGENCY EMPLOYEE NO.', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D36:G36', $personal_info['agency_employee_id'], $fieldThickRight, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H36:I36', '21. E-MAIL ADDRESS (if any)', $styleSubTitle, 10, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J36:R36', !EMPTY($email)? strtolower($email):NOT_APPLICABLE, $fieldThickRight, 10, NULL, NULL, FALSE);
		/*********************** PERSONAL INFO END ***********************/

		/*********************** FAMILY INFO START ***********************/
		$this->_write_to_excel($objWorkSheet, 'A37:R37', 'II.  FAMILY BACKGROUND', $styleTitle, NULL, NULL, NULL, FALSE);

		if(!EMPTY($data['spouse']))
		{
			foreach($data['spouse'] as $sp): 
				$spouse_first	= (!EMPTY($sp['relation_first_name'])) ? $sp['relation_first_name'] 		  : NOT_APPLICABLE;
				$spouse_last	= (!EMPTY($sp['relation_last_name'])) ? $sp['relation_last_name']			  : NOT_APPLICABLE;
				$spouse_mid		= (!EMPTY($sp['relation_middle_name'])) ? $sp['relation_middle_name'] 		  : NOT_APPLICABLE;
				$spouse_ext		= (!EMPTY($sp['relation_ext_name'])) ? $sp['relation_ext_name'] 		  	  : NOT_APPLICABLE;
				$spouse_occ		= (!EMPTY($sp['relation_occupation'])) ? $sp['relation_occupation']			  : NOT_APPLICABLE;
				$spouse_emp		= (!EMPTY($sp['relation_company'])) ? $sp['relation_company']				  : NOT_APPLICABLE;
				$str_spouse_addr= (!EMPTY($sp['relation_company_address'])) ? $sp['relation_company_address'] : NOT_APPLICABLE;
				$spouse_addr 	= html_entity_decode($str_spouse_addr,ENT_QUOTES,'UTF-8');
				$spouse_con		= (!EMPTY($sp['relation_contact_num'])) ? $sp['relation_contact_num']		  : NOT_APPLICABLE;
			endforeach; 
		}
		$this->_write_to_excel($objWorkSheet, 'A38:A38', '22.', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleCount['alignment']), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A39:A40', NULL, array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B38:C38', "SPOUSE'S SURNAME", $subTitleNoBorder, 20, NULL, '38', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D38:I38', $spouse_last, $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J38:O38', '23. NAME of CHILDREN  (Write full name and list all)', $styleSubTitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'P38:R38', 'DATE OF BIRTH (mm/dd/yyyy) ', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B39:C39', 'FIRST NAME', $subTitleNoBorder, 20, NULL, '39', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D39:G39', $spouse_first, $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H39:I39', 'NAME EXTENSION (JR., SR)   '.$spouse_ext, $styleSubTitle, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B40:C40', 'MIDDLE NAME', $subTitleNoBorder, 20, NULL, '40', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D40:I40', $spouse_mid, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A41:A41', NULL, $styleCount, 20, NULL, '41', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B41:C41', 'OCCUPATION', $styleSubTitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D41:I41', $spouse_occ, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A42:A42', NULL, $styleCount, 20, NULL, '42', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B42:C42', 'EMPLOYER/BUSINESS NAME', $styleSubTitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D42:I42', $spouse_emp, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A43:A43', NULL, $styleCount, 20, NULL, '43', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B43:C43', 'BUSINESS ADDRESS', $styleSubTitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D43:I43', $str_spouse_addr, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A44:A44', NULL, $styleCount, 20, NULL, '44', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B44:C44', 'TELEPHONE NO.', $styleSubTitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D44:I44', $spouse_con, $styleField, NULL, NULL, NULL, FALSE);

		if(!EMPTY($data['father'])) { 
			foreach($data['father'] as $ft):
				$father_first	= (!EMPTY($ft['relation_first_name'])) ? $ft['relation_first_name']   : NOT_APPLICABLE;
				$father_last	= (!EMPTY($ft['relation_last_name'])) ? $ft['relation_last_name']	  : NOT_APPLICABLE;
				$father_mid		= (!EMPTY($ft['relation_middle_name'])) ? $ft['relation_middle_name'] : NOT_APPLICABLE;
				$father_ext		= (!EMPTY($ft['relation_ext_name'])) ? $ft['relation_ext_name'] 	  : NOT_APPLICABLE;
			endforeach; 
		}

		$this->_write_to_excel($objWorkSheet, 'A45:A45', '24.', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleCount['alignment']), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A46:A47', NULL, array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B45:C45', "FATHER'S SURNAME", $subTitleNoBorder, 20, NULL, '45', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D45:I45', $father_last, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B46:C46', 'FIRST NAME', $subTitleNoBorder, 20, NULL, '46', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D46:G46', $father_first, $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H46:I46', 'NAME EXTENSION (JR., SR)   '.$father_ext, $styleSubTitle, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B47:C47', 'MIDDLE NAME', $subTitleBorderBottom, 20, NULL, '47', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D47:I47', $father_mid, $styleField, NULL, NULL, NULL, FALSE);		
		
		if(!EMPTY($data['mother'])) { 
			foreach($data['mother'] as $mt):
				$mother_first	= (!EMPTY($mt['relation_first_name'])) ? $mt['relation_first_name']   : NOT_APPLICABLE;
				$mother_last	= (!EMPTY($mt['relation_last_name'])) ? $mt['relation_last_name']	  : NOT_APPLICABLE;
				$mother_mid		= (!EMPTY($mt['relation_middle_name'])) ? $mt['relation_middle_name'] : NOT_APPLICABLE;
			endforeach; 
		}

		$this->_write_to_excel($objWorkSheet, 'A48:A48', '25.', array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'alignment' => $styleCount['alignment']), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A49:A51', NULL, array('font' => $styleCount['font'], 'fill' => $styleCount['fill'], 'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 20, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B48:C48', "MOTHER'S MAIDEN NAME", array('alignment' => $styleField['alignment'], 'font' => $styleSubTitle['font'], 'fill' => $styleCount['fill'], 'borders' => array('top'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), 20, NULL, '48', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D48:I48', NULL, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B49:C49', 'SURNAME', $subTitleNoBorder, 20, NULL, '49', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D49:I49', $mother_last, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B50:C50', 'FIRST NAME', $subTitleNoBorder, 20, NULL, '50', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D50:I50', $mother_first, $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'B51:C51', 'MIDDLE NAME', $subTitleNoBorder, 20, NULL, '51', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D51:I51', $mother_mid, $styleField, NULL, NULL, NULL, FALSE);
		
		$child = $data['child'];

		for($i=39; $i<51; $i++) {
			if(!EMPTY($child[$i-39])) 
			{
				$curr = $child[$i-39];
				$this->_write_to_excel($objWorkSheet, 'J'.$i.':O'.$i.'', $curr['name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'P'.$i.':R'.$i.'', format_date($curr['relation_birth_date']), $fieldThickRight, 20, NULL, ''.$i.'', FALSE);
			} 
			else 
			{
				$this->_write_to_excel($objWorkSheet, 'J'.$i.':O'.$i.'', NULL, $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'P'.$i.':R'.$i.'', NULL, $fieldThickRight, 20, NULL, ''.$i.'', FALSE);
			}
		}

		$this->_write_to_excel($objWorkSheet, 'J51:R51', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		/*********************** FAMILY INFO END ***********************/

		/*********************** EDUCATIONAL INFO START ***********************/
		$this->_write_to_excel($objWorkSheet, 'A52:R52', 'III.  EDUCATIONAL BACKGROUND', $styleTitle, 20, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A53:A55', '26.', $styleCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B53:C55', 'LEVEL', $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D53:G55', 'NAME OF SCHOOL (Write in full)', $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H53:J55', 'BASIC EDUCATION/DEGREE/COURSE (Write in full)', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('right'=> array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K53:M54', 'PERIOD OF ATTENDANCE', $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'N53:O55', 'HIGHEST LEVEL/UNITS EARNED (if not graduated)', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('left'=> array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right'=> array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'P53:P55', 'YEAR GRADUATED', $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'Q53:R55', 'SCHOLARSHIP/ ACADEMIC HONORS RECEIVED', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K55:L55', 'From', $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M55:M55', 'To', $centerSubtitle, NULL, NULL, NULL, FALSE);		

		$educ_details = $data['educ_details'];
		$educ_list 	  = $data['educ_list'];

		$rows = 56;
		foreach ($educ_list as $educ) {
			$same = FALSE;
			if($rows < 61) {
				$this->_write_to_excel($objWorkSheet, 'A'.$rows.':C'.$rows.'', $educ['educ_level_name'], $styleSubTitle, 20, NULL, ''.$rows.'', FALSE);
				$this->_write_to_excel($objWorkSheet, 'D'.$rows.':G'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'H'.$rows.':J'.$rows.'', NULL, $fieldThickRight, NULL, NULL, NULL, FALSE);
				$this->_write_to_excel($objWorkSheet, 'K'.$rows.':L'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'M'.$rows.':M'.$rows.'', NULL, $fieldThickRight, NULL, NULL, NULL, FALSE);
				$this->_write_to_excel($objWorkSheet, 'N'.$rows.':O'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'P'.$rows.':P'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'Q'.$rows.':R'.$rows.'', NULL, $fieldThickRight, NULL, NULL, NULL, FALSE);

				foreach ($educ_details as $key => $ed) {

					if($educ['educ_level_id'] == $ed['educational_level_id']) :

						$str_school = (!EMPTY($ed['school_name'])) ? $ed['school_name'] : NOT_APPLICABLE;
						$str_degree = (!EMPTY($ed['degree_name'])) ? $ed['degree_name'] : NOT_APPLICABLE;
						$str_level  = (!EMPTY($ed['highest_level'])) ? $ed['highest_level'] : NOT_APPLICABLE;
						$str_honor  = (!EMPTY($ed['academic_honor'])) ? $ed['academic_honor'] : NOT_APPLICABLE;
						$this->_write_to_excel($objWorkSheet, 'D'.$rows.':G'.$rows.'', html_entity_decode($str_school,ENT_QUOTES,'UTF-8'), $styleField, 20, NULL, ''.$rows.'', FALSE);
						$this->_write_to_excel($objWorkSheet, 'H'.$rows.':J'.$rows.'', html_entity_decode($str_degree,ENT_QUOTES,'UTF-8'), $fieldThickRight, NULL, NULL, NULL, FALSE);
						$this->_write_to_excel($objWorkSheet, 'K'.$rows.':L'.$rows.'', (!EMPTY($ed['start_year'])) ? $ed['start_year'] : NOT_APPLICABLE, $styleField);
						$this->_write_to_excel($objWorkSheet, 'M'.$rows.':M'.$rows.'', (!EMPTY($ed['end_year'])) ? $ed['end_year'] : NOT_APPLICABLE, $fieldThickRight, NULL, NULL, NULL, FALSE);
						$this->_write_to_excel($objWorkSheet, 'N'.$rows.':O'.$rows.'', html_entity_decode($str_level,ENT_QUOTES,'UTF-8'), $styleField);
						$this->_write_to_excel($objWorkSheet, 'P'.$rows.':P'.$rows.'', (!EMPTY($ed['end_year'])) ? $ed['end_year'] : NOT_APPLICABLE, $styleField);
						$this->_write_to_excel($objWorkSheet, 'Q'.$rows.':R'.$rows.'', html_entity_decode($str_honor,ENT_QUOTES,'UTF-8'), $fieldThickRight, NULL, NULL, NULL, FALSE);
						
						// if($educ_details[$key-1]['educational_level_id'] == $ed['educational_level_id']) {
						// 	$this->_write_to_excel($objWorkSheet, 'A'.$rows.':C'.$rows.'', '', $styleField);
						// }
					endif;
				}
			}
			$rows++;
		}
		if($rows < 60) {
			for($i=$rows; $i < 61; $i++) {
				$this->_write_to_excel($objWorkSheet, 'A'.$rows.':C'.$rows.'', NULL, $styleSubTitle, 20, NULL, ''.$rows.'', FALSE);
				$this->_write_to_excel($objWorkSheet, 'D'.$rows.':G'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'H'.$rows.':J'.$rows.'', NULL, $fieldThickRight, NULL, NULL, NULL, FALSE);
				$this->_write_to_excel($objWorkSheet, 'K'.$rows.':L'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'M'.$rows.':M'.$rows.'', NULL, $fieldThickRight, NULL, NULL, NULL, FALSE);
				$this->_write_to_excel($objWorkSheet, 'N'.$rows.':O'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'P'.$rows.':P'.$rows.'', NULL, $styleField);
				$this->_write_to_excel($objWorkSheet, 'Q'.$rows.':R'.$rows.'', NULL, $fieldThickRight, NULL, NULL, NULL, FALSE);
			}
		}
		/*********************** EDUCATIONAL INFO END ***********************/

		$this->_write_to_excel($objWorkSheet, 'A61:R61', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A62:C62', 'SIGNATURE', $styleSignatureFooter, 30, NULL, '62', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D62:J62', NULL, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K62:M62', 'DATE', $styleSignatureFooter, NULL, NULL, NULL, FALSE);
		// $this->_write_to_excel($objWorkSheet, 'N62:R62', NULL, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);	
		// ====================== jendaigo : start : include personal_info date_accomplished value ============= //
		$this->_write_to_excel($objWorkSheet, 'N62:R62', !EMPTY($personal_info['date_accomplished']) ? ($personal_info['date_accomplished']):NOT_APPLICABLE, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);	
		// ====================== jendaigo : end : include personal_info date_accomplished value ============= //
		
		$this->_write_to_excel($objWorkSheet, 'A63:R63', 'CS FORM 212 (Revised 2017), Page 1 of 4', $pageNumberFooter, NULL, NULL, NULL, FALSE);

		/*********************** PAGE 2 ***********************/
	 	$objWorkSheet = $objPHPExcel->createSheet(1);
	 	$objWorkSheet->setTitle('C2');

		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'A');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '7.5', 'B');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'C');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'D');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'E');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '12', 'F');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'G');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '7', 'H');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'I');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'J');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'K');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '12', 'L');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'M');

		/*********************** ELIGIBILITY INFO START ***********************/
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), 2, NULL, '1');
	 	$this->_write_to_excel($objWorkSheet, 'A2:M2', 'IV. CIVIL SERVICE ELIGIBILITY', $styleTitle,  NULL, NULL, NULL, FALSE);

	 	$this->_write_to_excel($objWorkSheet, 'A3:A4', '27. ', $styleCount, NULL, NULL, NULL, FALSE);
		
		$this->_write_to_excel($objWorkSheet, 'B3:E4', "CAREER SERVICE/ RA 1080 (BOARD/ BAR) UNDER SPECIAL LAWS/ CES/ CSEE\nBARANGAY ELIGIBILITY / DRIVER'S LICENSE", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F3:F4', "RATING (if applicable)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G3:H4', "DATE OF EXAMINATION/\nCONFERMENT", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I3:K4', "PLACE OF EXAMINATION / CONFERMENT", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L3:M3', "LICENSE (if applicable)", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L4:L4', "NUMBER", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M4:M4', "Date of\nValidity", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);

		$govt_exam = $data['govt_exam'];
		for($i=5;$i<12;$i++) {
			if(!EMPTY($govt_exam[$i-5])) {
				$curr = $govt_exam[$i-5];
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':E'.$i.'', $curr['eligibility_type_name'], $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'F'.$i.':F'.$i.'', number_format($curr['rating'],2), $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':H'.$i.'', format_date($curr['exam_date']), $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'I'.$i.':K'.$i.'', $curr['exam_place'], $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'L'.$i.':L'.$i.'', $curr['eligibility_type_code'], $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'M'.$i.':M'.$i.'', format_date($curr['release_date']), array('alignment' => $styleField['alignment'], 'font' => $styleField['font'], 'borders' => $borderRightThick['borders']), 27, NULL, ''.$i.'', FALSE);	
			} else {	
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':E'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'F'.$i.':F'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':H'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'I'.$i.':K'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'L'.$i.':L'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'M'.$i.':M'.$i.'', "", array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 27, NULL, ''.$i.'', FALSE);
			}
		}
		/*********************** ELIGIBILITY INFO END ***********************/
		$this->_write_to_excel($objWorkSheet, 'A12:M12', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);

		/*********************** WORK EXPERIENCE INFO START ***********************/
	 	$this->_write_to_excel($objWorkSheet, 'A13:M13', "V. WORK EXPERIENCE", $noBorderBottomSub,  NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A14:M14', "(Include private employment.  Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.", $noBorderTopSub,  NULL, NULL, NULL, FALSE);

	 	$this->_write_to_excel($objWorkSheet, 'A15:A16', '28. ', $styleCount, NULL, NULL, NULL, FALSE);		
		$this->_write_to_excel($objWorkSheet, 'B15:C16', "IINCLUSIVE DATES (mm/dd/yyyy)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D15:F17', "POSITION TITLE\n(Write in full/Do not abbreviate)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G15:I17', "DEPARTMENT / AGENCY / OFFICE / COMPANY\n((Write in full/Do not abbreviate)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J15:J17', "MONTHLY SALARY", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K15:K17', "SALARY/ JOB/ PAY GRADE\n(if applicable)& STEP\n(Format '00-0')\n/ INCREMENT", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L15:L17', "STATUS OF\nAPPOINTMENT", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M15:M17', "GOV'T\nSERVICE\n(Y/N)", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A17:B17', "From", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C17:C17', "To", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$work_exp = $data['work_exp'];

		for($i=18;$i<46;$i++) {
			if(!EMPTY($work_exp[$i-18])) {
				$curr = $work_exp[$i-18];
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':B'.$i.'', format_date($curr['employ_start_date']), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'C'.$i.':C'.$i.'', (!EMPTY($curr['employ_end_date']) ? format_date($curr['employ_end_date']) : 'PRESENT'), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'D'.$i.':F'.$i.'', $curr['employ_position_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':I'.$i.'', $curr['employ_office_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'J'.$i.':J'.$i.'', number_format($curr['employ_monthly_salary'],2), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'K'.$i.':K'.$i.'', $curr['employ_salary_grade'] . ' - ' . $curr['employ_salary_step'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'L'.$i.':L'.$i.'', $curr['employment_status_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'M'.$i.':M'.$i.'', ($curr['govt_service_flag'] == 'Y' ? 'YES' : 'NO'), array('alignment' => $styleField['alignment'], 'font' => $styleField['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
			} else {
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':B'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'C'.$i.':C'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'D'.$i.':F'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':I'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'J'.$i.':J'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'K'.$i.':K'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'L'.$i.':L'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'M'.$i.':M'.$i.'', "", array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
			}
		}
		/*********************** WORK EXPERIENCE INFO END ***********************/

		$this->_write_to_excel($objWorkSheet, 'A46:M46', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A47:C47', 'SIGNATURE', $styleSignatureFooter, 30, NULL, '47', FALSE);
		$this->_write_to_excel($objWorkSheet, 'D47:H47', NULL, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I47:I47', 'DATE', $styleSignatureFooter, NULL, NULL, NULL, FALSE);
		// $this->_write_to_excel($objWorkSheet, 'J47:M47', NULL, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);	
		// ====================== jendaigo : start : include personal_info date_accomplished value ============= //
		$this->_write_to_excel($objWorkSheet, 'J47:M47', !EMPTY($personal_info['date_accomplished']) ? ($personal_info['date_accomplished']):NOT_APPLICABLE, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);	
		// ====================== jendaigo : end : include personal_info date_accomplished value ============= //
		
		$this->_write_to_excel($objWorkSheet, 'A48:M48', 'CS FORM 212 (Revised 2017), Page 2 of 4', $pageNumberFooter, NULL, NULL, NULL, FALSE);

		if(count($work_exp) > 28) {
			$objWorkSheet = $objPHPExcel->createSheet(++$index); //Setting index when creating
	 		$objWorkSheet->setTitle('Supplm for WORK EXP.');
	 		$this->_supplm_work_exp($objWorkSheet, $work_exp, $styleFooter, $styleHeader, $styleSubTitle, $styleField, $styleCount, $borderRightThick, $borderThick,  $styleTitle, $smallTitle, $centerSubtitle, $noBorderBottomSub, $noBorderTopSub, $pageNumberFooter);
		}

		/*********************** PAGE 3 ***********************/
	 	$objWorkSheet = $objPHPExcel->createSheet(2); //Setting index when creating
		$objWorkSheet->setTitle('C3');

		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'A');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '30', 'B');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'C');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'D');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'E');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'F');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'G');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '12', 'H');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'I');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '30', 'J');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'K');

		/*********************** VOLUNTARY WORK INFO START ***********************/
		$this->_write_to_excel($objWorkSheet, 'A1:K1', NULL, array(), 2, NULL, '1');
	 	$this->_write_to_excel($objWorkSheet, 'A2:K2', 'VI. VOLUNTARY WORK OR INVOLVEMENT IN CIVIC / NON-GOVERNMENT / PEOPLE / VOLUNTARY ORGANIZATION/S', $styleTitle,  NULL, NULL, NULL, FALSE);
	 	
	 	$this->_write_to_excel($objWorkSheet, 'A3:A5', '29. ', $styleCount, NULL, NULL, NULL, FALSE);		
	 	$this->_write_to_excel($objWorkSheet, 'B3:D5', "NAME & ADDRESS OF ORGANIZATION\n(Write in full)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E3:F4', "INCLUSIVE DATES\n(mm/dd/yyyy)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G3:G5', "NUMBER OF\nHOURS", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H3:K5', "POSITION / NATURE OF WORK", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E5:E5', "From", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F5:F5', "To", $centerSubtitle, NULL, NULL, NULL, FALSE);

		$vol_details = $data['vol_details'];
		for($i=6;$i<13;$i++) {
			if(!EMPTY($vol_details[$i-6])) {
				$curr = $vol_details[$i-6];
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':D'.$i.'', $curr['volunteer_org_name'] . "\n" . $curr['volunteer_org_address'], $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'E'.$i.':E'.$i.'', format_date($curr['volunteer_start_date']), $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'F'.$i.':F'.$i.'', format_date($curr['volunteer_end_date']), $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':G'.$i.'', $curr['volunteer_hour_count'], $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'H'.$i.':K'.$i.'', $curr['volunteer_position'], array('alignment' => $styleField['alignment'], 'font' => $styleField['font'], 'borders' => $borderRightThick['borders']), 27, NULL, ''.$i.'', FALSE);
			} else {
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':D'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'E'.$i.':E'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'F'.$i.':F'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':G'.$i.'', "", $styleField, 27, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'H'.$i.':K'.$i.'', "", array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 27, NULL, ''.$i.'', FALSE);
			}
			
		}
		$this->_write_to_excel($objWorkSheet, 'A13:K13', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
	 	/*********************** VOLUNTARY WORK INFO END ***********************/

		/*********************** TRAININGS INFO START ***********************/
	 	$this->_write_to_excel($objWorkSheet, 'A14:K14', 'VII.  LEARNING AND DEVELOPMENT (L&D) INTERVENTIONS/TRAINING PROGRAMS ATTENDED', $noBorderBottomSub,  NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A15:K15', '(Start from the most recent L&D/training program and include only the relevant L&D/training taken for the last five (5) years for Division Chief/Executive/Managerial positions)', $noBorderTopSub,  NULL, NULL, NULL, FALSE);
	
	 	$this->_write_to_excel($objWorkSheet, 'A16:A18', '30. ', $styleCount, NULL, NULL, NULL, FALSE);		
	 	$this->_write_to_excel($objWorkSheet, 'B16:D18', "NAME OF SEMINAR/CONFERENCE/WORKSHOP/SHORT COURSES\n(Write in full)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E16:F17', "INCLUSIVE DATES OF ATTENDANCE\n(mm/dd/yyyy)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G16:G18', "NUMBER OF HOURS", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H16:H18', "Type of LD\n( Managerial/ Supervisory/\nTechnical/etc) ", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I16:K18', "CONDUCTED / SPONSORED BY\n(Write in full)", array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'fill' => $styleSubTitle['fill'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'E18:E18', "From", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F18:F18', "To", $centerSubtitle, NULL, NULL, NULL, FALSE);

		
		$train_details = $data['train_details'];

		for($i=19;$i<40;$i++) {
			if(!EMPTY($train_details[$i-19])) {
				$curr = $train_details[$i-19];
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':D'.$i.'', $curr['training_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'E'.$i.':E'.$i.'', format_date($curr['training_start_date']), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'F'.$i.':F'.$i.'', format_date($curr['training_end_date']), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':G'.$i.'', number_format($curr['training_hour_count'],2), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'H'.$i.':H'.$i.'', $curr['training_type'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'I'.$i.':K'.$i.'', $curr['training_conducted_by'], array('alignment' => $styleField['alignment'], 'font' => $styleField['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
			} else {
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':D'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'E'.$i.':E'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'F'.$i.':F'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':G'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'H'.$i.':H'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'I'.$i.':K'.$i.'', "", array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
			}
			
		}
		/*********************** TRAININGS INFO END ***********************/
		$this->_write_to_excel($objWorkSheet, 'A40:K40', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
	 	
	 	/*********************** OTHER INFO START ***********************/
	 	$this->_write_to_excel($objWorkSheet, 'A41:K41', 'VIII. OTHER INFORMATION', $styleTitle,  NULL, NULL, NULL, FALSE);

		$skills = $data['other_params']['skills_list'];
		$recog = $data['other_params']['recog_list'];
		$member = $data['other_params']['member_list'];

	 	$this->_write_to_excel($objWorkSheet, 'A42:A42', '31. ', $styleCount, 30, NULL, '42', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B42:B42', "SPECIAL SKILLS and HOBBIES", $centerSubtitle, NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'C42:C42', '32. ', $styleCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D42:H42', "NON-ACADEMIC DISTINCTIONS / RECOGNITION\n(Write in full)", $centerSubtitle, NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'I42:I42', '33. ', $styleCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J42:K42', "MEMBERSHIP IN ASSOCIATION/ORGANIZATION\n(Write in full)", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		
		for($i=43;$i<50;$i++) {

			$this->_write_to_excel($objWorkSheet, 'A'.$i.':B'.$i.'', !EMPTY($skills[$i-43]) ? $skills[$i-43]['others_value'] : '', $styleField, 20, NULL, ''.$i.'', FALSE);
	    	$this->_write_to_excel($objWorkSheet, 'C'.$i.':H'.$i.'', !EMPTY($recog[$i-43]) ? $recog[$i-43]['others_value'] : '', $styleField, 20, NULL, ''.$i.'', FALSE);
	    	$this->_write_to_excel($objWorkSheet, 'I'.$i.':K'.$i.'', !EMPTY($member[$i-43]) ? $member[$i-43]['others_value'] : '', array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
		}
		/*********************** OTHER INFO END ***********************/

		$this->_write_to_excel($objWorkSheet, 'A50:K50', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A51:B51', 'SIGNATURE', $styleSignatureFooter, 30, NULL, '51', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C51:F51', NULL, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G51:H51', 'DATE', $styleSignatureFooter, NULL, NULL, NULL, FALSE);
		// $this->_write_to_excel($objWorkSheet, 'I51:K51', NULL, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);	
		// ====================== jendaigo : start : include personal_info date_accomplished value ============= //
		$this->_write_to_excel($objWorkSheet, 'I51:K51', !EMPTY($personal_info['date_accomplished']) ? ($personal_info['date_accomplished']):NOT_APPLICABLE, array('alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);	
		// ====================== jendaigo : end : include personal_info date_accomplished value ============= //
		
		$this->_write_to_excel($objWorkSheet, 'A52:K52', 'CS FORM 212 (Revised 2017), Page 3 of 4', $pageNumberFooter, NULL, NULL, NULL, FALSE);

		if(count($skills) > 7 OR count($recog) > 7 OR count($member) > 7) {
			$objWorkSheet = $objPHPExcel->createSheet(++$index); //Setting index when creating
	 		$objWorkSheet->setTitle('Supplm for OTHER INFO.');
	 		$this->_supplm_other_info($objWorkSheet, $data['other_params'], $styleFooter, $styleHeader, $styleSubTitle, $styleField, $styleCount, $borderRightThick, $borderThick,  $styleTitle, $centerSubtitle, $pageNumberFooter);
		}
		
		/*********************** PAGE 4 ***********************/
		$answer = $data['answers'];

	 	$objWorkSheet = $objPHPExcel->createSheet(3); 
		$objWorkSheet->setTitle('C4');

		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '2', 'A');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '2', 'B');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'C');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '30', 'D');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'E');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '30', 'F');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'G');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'H');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '7', 'I');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'J');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '5', 'K');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '17', 'L');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '4', 'M');

		$this->_write_to_excel($objWorkSheet, 'A1:M1', NULL, array('borders' => $borderThick['borders']), 2, NULL, '1', FALSE);

		/*********************** QUESTIONS ***********************/
		$this->_write_to_excel($objWorkSheet, 'A2:B2', '34.', $questionnaireCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C2:F2', 'Are you related by consanguinity or affinity to the appointing or recommending authority, or to the', $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C3:F3', 'chief of bureau or office or to the person who has immediate supervision over you in the Office, ', $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C4:F4', 'Bureau or Department where you will be apppointed,', $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C5:F5', NULL, $questionnaireField, 4, NULL, '5', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C6:F6', 'a. within the third degree?', $questionnaireField, 20, NULL, '6', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C7:F7', NULL, $questionnaireField, 4, NULL, '7', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C8:F8', 'b. within the fourth degree (for Local Government Unit - Career Employees)?', $questionnaireField, 20, NULL, '8', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A3:B8', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A9:F9', NULL, $questionnaireField, 1, NULL, '9', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A10:F11', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A12:F12', NULL, $questionnaireField, 4, NULL, '12', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A13:F13', NULL, $separator, 4, NULL, '13', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A14:B14', '35.', $questionnaireCount, 4, NULL, '13', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C14:F14', 'a. Have you ever been found guilty of any administrative offense?', $questionnaireField, 20, NULL, '14', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A15:B16', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C15:F16', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A17:F17', NULL, $questionnaireField, 4, NULL, '17', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A18:F18', NULL, $questionnaireField, 4, NULL, '18', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C19:F19', 'b. Have you been criminally charged before any court?', $questionnaireField, 20, NULL, '19', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A19:B19', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A20:F22', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A23:F23', NULL, $questionnaireField, 4, NULL, '23', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A24:F24', NULL, $separator, 4, NULL, '24', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A25:B25', '36.', $questionnaireCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C25:F25', 'Have you ever been convicted of any crime or violation of any law, decree, ordinance or regulation by any court or tribunal?', $questionnaireField, 20, NULL, '25', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A26:F27', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A28:F28', NULL, $questionnaireField, 4, NULL, '28', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A29:F29', NULL, $separator, 4, NULL, '29', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A30:B30', '37.', $questionnaireCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C30:F30', 'Have you ever been separated from the service in any of the following modes: resignation, retirement, dropped from the rolls, dismissal, termination, end of term, finished contract or phased out (abolition) in the public or private sector?', $questionnaireField, 20, NULL, '30', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A31:F32', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A33:F33', NULL, $questionnaireField, 4, NULL, '33', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A34:F34', NULL, $separator, 4, NULL, '34', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A35:B35', '38.', $questionnaireCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C35:F35', 'a. Have you ever been a candidate in a national or local election held within the last year (except Barangay election)?', $questionnaireField, 20, NULL, '35', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A36:F36', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A37:F37', NULL, $questionnaireField, 4, NULL, '37', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A38:B39', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C38:F39', 'b. Have you resigned from the government service during the three (3)-month period before the last election to promote/actively campaign for a national or local candidate?', $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A40:F40', NULL, $questionnaireField, 4, NULL, '40', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A41:F41', NULL, $separator, 4, NULL, '41', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A42:B42', '39.', $questionnaireCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C42:F42', 'Have you acquired the status of an immigrant or permanent resident of another country?', $questionnaireField, 20, NULL, '42', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A43:F44', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A45:F45', NULL, array('fill' => $styleSubTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), 4, NULL, '45', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A46:B46', '40.', $questionnaireCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C46:F46', "Pursuant to: (a) Indigenous People's Act (RA 8371); (b) Magna Carta for Disabled Persons (RA 7277); and (c) Solo Parents Welfare Act of 2000 (RA 8972), please answer the following items:", $questionnaireField, 30, NULL, '46', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A48:B48', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A47:F47', NULL, $questionnaireField, 1, NULL, '47', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C48:F48', 'a. Are you a member of any indigenous group?', $questionnaireField, 20, NULL, '48', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A49:F49', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A50:B50', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C50:F50', 'b. Are you a person with disability?', $questionnaireField, 20, NULL, '50', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A51:F51', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A52:B52', NULL, array('fill' => $styleSubTitle['fill']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C52:F52', 'c. Are you a solo parent?', $questionnaireField, 20, NULL, '52', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A53:F53', NULL, $questionnaireField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A54:F54', NULL, array('fill' => $styleSubTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 4, NULL, '54', FALSE);

		/*********************** ANSWERS ***********************/
		$this->_write_to_excel($objWorkSheet, 'G2:M5', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H6:H6', ($answer[0]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I6:I6', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J6:J6', ($answer[0]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K6:K6', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H8:H8', ($answer[1]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I8:I8', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J8:J8', ($answer[1]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K8:K8', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L6:M9', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H10:M10', "If YES, give details: ", array('borders' => $styleHeader['borders'], 'font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H11:L11', $answer[0]['question_answer_txt'].' '.$answer[1]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M11:M11', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G12:M12', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G13:M13', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'H14:H14', ($answer[2]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I14:I14', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J14:J14', ($answer[2]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K14:K14', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L14:M14', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H15:M15', "If YES, give details: ", array('borders' => $styleHeader['borders'], 'font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H16:L16', $answer[2]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M16:M16', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G17:M17', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G18:M18', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H19:H19', ($answer[3]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I19:I19', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J19:J19', ($answer[3]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K19:K19', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L19:M19', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H20:M20', "If YES, give details: ", array('borders' => $styleHeader['borders'], 'font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M20:M20', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G21:I21', 'Date Filed: ', array('font' => $styleField['font'], 'alignment' => $styleCount['alignment']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G22:I22', 'Status of Case/s:', array('font' => $styleField['font'], 'alignment' => $styleCount['alignment']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K21:L21', $answer[3]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K22:L22', NULL, array('borders' => array('font' => $styleField['font'], 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M21:M22', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G23:M23', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G24:M24', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'H25:H25', ($answer[4]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I25:I25', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J25:J25', ($answer[4]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K25:K25', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L25:M25', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H26:M26', "If YES, give details: ", array('borders' => $styleHeader['borders'], 'font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H27:L27', $answer[4]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M27:M27', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G28:M28', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G29:M29', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'H30:H30', ($answer[5]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I30:I30', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J30:J30', ($answer[5]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K30:K30', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L30:M30', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H31:M31', "If YES, give details: ", array('borders' => $styleHeader['borders'], 'font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H32:L32', $answer[5]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M32:M32', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G33:M33', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G34:M34', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		
		$this->_write_to_excel($objWorkSheet, 'H35:H35', ($answer[6]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I35:I35', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J35:J35', ($answer[6]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K35:K35', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G36:J36', 'If YES, give details:', array('font' => $styleField['font'], 'alignment' => $styleCount['alignment']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K36:L36', $answer[6]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);	
		$this->_write_to_excel($objWorkSheet, 'M35:M39', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G37:M37', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'H38:H38', ($answer[7]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, 20, NULL, '38', FALSE);
		$this->_write_to_excel($objWorkSheet, 'I38:I38', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J38:J38', ($answer[7]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K38:K38', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G39:J39', 'If YES, give details: ', array('font' => $styleField['font'], 'alignment' => $styleCount['alignment']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K39:L39', $answer[7]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);	
		$this->_write_to_excel($objWorkSheet, 'G40:M40', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G41:M41', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		
		$this->_write_to_excel($objWorkSheet, 'H42:H42', ($answer[8]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I42:I42', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J42:J42', ($answer[8]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K42:K42', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L42:M42', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H43:M43', "If YES, give details (country): ", array('borders' => $styleHeader['borders'], 'font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'H44:L44', $answer[8]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M44:M44', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G45:M45', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G46:M47', NULL, array('borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'H48:H48', ($answer[9]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I48:I48', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J48:J48', ($answer[9]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K48:K48', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G49:K49', 'If YES, please specify:', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L49:L49', $answer[9]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);	

		$this->_write_to_excel($objWorkSheet, 'H50:H50', ($answer[10]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I50:I50', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J50:J50', ($answer[10]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K50:K50', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G51:K51', 'If YES, please specify ID No: ', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L51:L51', $answer[10]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);	

		$this->_write_to_excel($objWorkSheet, 'H52:H52', ($answer[11]['question_answer_flag'] == 'Y') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'I52:I52', 'YES', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J52:J52', ($answer[11]['question_answer_flag'] == 'N') ? 'X' : '', $borderThin, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K52:K52', 'NO', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G53:K53', 'If YES, please specify ID No: ', array('font' => $styleField['font']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L53:L53', $answer[11]['question_answer_txt'], array('font' => $styleField['font'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);	
		$this->_write_to_excel($objWorkSheet, 'M48:M53', NULL, array('borders' => $styleHeader['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G54:M54', NULL, array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		/*********************** REFERENCES ***********************/
		$refn_details = $data['refn_details'];
		$refnFont 	  = array(
			'font'  		=> array(
		        'bold'  	=> false,
		        'color' 	=> array('rgb' => 'FE0000'),
		        'size'  	=> 8,
		        'name'  	=> 'Arial Narrow'
		    )
		);

		$this->_write_to_excel($objWorkSheet, 'A55:B55', '41.', array('font' => $styleSubTitle['font'], 'alignment' => $styleHeader['alignment'], 'fill' => $styleSubTitle['fill'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 27, NULL, '55', FALSE);
		$this->_write_to_excel($objWorkSheet, 'C55:C55', 'REFERENCES', array('font' => $styleSubTitle['font'], 'alignment' => $styleSubTitle['alignment'], 'fill' => $styleSubTitle['fill'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D55:I55', '(Person not related by consanguinity or affinity to applicant /appointee)', array('font' => $refnFont['font'], 'alignment' => $styleSubTitle['alignment'], 'fill' => $styleSubTitle['fill'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A56:E56', 'NAME', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), 20, NULL, '56', FALSE);
		$this->_write_to_excel($objWorkSheet, 'F56:F56', 'ADDRESS', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $styleField['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G56:I56', 'TEL. NO.', array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A57:E57', !EMPTY($refn_details[0]) ? $refn_details[0]['reference_full_name'] : NOT_APPLICABLE, $styleField, 27, NULL, '57', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A58:E58', !EMPTY($refn_details[1]) ? $refn_details[1]['reference_full_name'] : NOT_APPLICABLE, $styleField, 27, NULL, '58', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A59:E59', !EMPTY($refn_details[2]) ? $refn_details[2]['reference_full_name'] : NOT_APPLICABLE, $styleField, 27, NULL, '59', FALSE);

		$this->_write_to_excel($objWorkSheet, 'F57:F57', $refn_details[0]['reference_address'], $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F58:F58', $refn_details[1]['reference_address'], $styleField, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F59:F59', $refn_details[2]['reference_address'], $styleField, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'G57:I57', $refn_details[0]['reference_contact_info'], $fieldThickRight, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G58:I58', $refn_details[1]['reference_contact_info'], $fieldThickRight, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G59:I59', $refn_details[2]['reference_contact_info'], $fieldThickRight, NULL, NULL, NULL, FALSE);

		$declare_str = "I declare under oath that I have personally accomplished this Personal Data Sheet which is a true, correct and complete statement pursuant\nto the provisions of pertinent laws, rules and regulations of the Republic of the Philippines. I authorize the agency head/authorized \nrepresentative to verify/validate the contents stated herein. I  agree that any misrepresentation made in this document and its \nattachments shall cause the filing of administrative/criminal case/s against me.";
		$this->_write_to_excel($objWorkSheet, 'A60:B60', '42.', $questionnaireCount, 30, NULL, '60', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A61:B61', NULL, $questionnaireCount, 20, NULL, '61', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A62:I62', NULL, array('fill' => $styleSubTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A63:I63', NULL, array('fill' => $styleSubTitle['fill'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C60:I61', $declare_str, array('font' => $styleField['font'], 'fill' => $styleSubTitle['fill'], 'borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A64:J64', NULL, array(), 10, NULL, '64', FALSE);

		$declaration = $data['declaration'];

		$this->_write_to_excel($objWorkSheet, 'B65:D65', "Government Issued ID (i.e.Passport, GSIS, SSS, PRC, Driver's License, etc.)\nPLEASE INDICATE ID Number and Date of Issuance", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 25, NULL, '65', FALSE);

		$this->_write_to_excel($objWorkSheet, 'B66:C66', 'Government Issued ID: ', array('font' => $styleSubTitle['font'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), 24, NULL, '66', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B67:C68', 'ID/License/Passport No.: ', array('font' => $styleSubTitle['font'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'B69:C70', 'Date/Place of Issuance:', array('font' => $styleSubTitle['font'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'D66:D66', !EMPTY($declaration['ctc_no']) ? $declaration['ctc_no'] : NOT_APPLICABLE, array('font' => $styleField['font'], 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D67:D68', !EMPTY($declaration['other_id']) ? $declaration['other_id'] : NOT_APPLICABLE, array('font' => $styleField['font'], 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D69:D70', (!EMPTY($declaration['issued_date']) ? $declaration['issued_date'] : '') . (!EMPTY($declaration['issued_place']) ? ' / ' . strtoupper($declaration['issued_place']) : ''), array('font' => $styleField['font'], 'borders' => array('top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'A67:A67', NULL, array(), 12, NULL, '67', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A68:A68', NULL, array(), 12, NULL, '68', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A69:A69', NULL, array(), 12, NULL, '69', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A70:A70', NULL, array(), 12, NULL, '70', FALSE);

		$this->_write_to_excel($objWorkSheet, 'A71:M71', NULL, array(), 10, NULL, '71', FALSE);

		$this->_write_to_excel($objWorkSheet, 'F65:I67', NULL, array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'F68:I68', 'Signature (Sign inside the box)', array('alignment' => $styleHeader['alignment'], 'font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		// $this->_write_to_excel($objWorkSheet, 'F69:I69', !EMPTY($declaration['accomplished_date']) ? $declaration['accomplished_date'] : NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		// ====================== jendaigo : start : include personal_info date_accomplished value ============= //
		$this->_write_to_excel($objWorkSheet, 'F69:I69', !EMPTY($personal_info['date_accomplished']) ? ($personal_info['date_accomplished']):NOT_APPLICABLE, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		// ====================== jendaigo : end : include personal_info date_accomplished value ============= //
		
		$this->_write_to_excel($objWorkSheet, 'F70:I70', 'Date Accomplished', array('alignment' => $styleHeader['alignment'], 'font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

		$pic_str = "ID picture taken within\nthe last  6 months\n3.5 cm. X 4.5 cm\n(passport size)\n\nWith full and handwritten\nname tag and signature over\nprinted name\n\nComputer generated \nor photocopied picture\nis not acceptable";
		$picFont = array(
			'font' => array(
				'size' => 7,
				'name' => 'Arial Narrow'
			)
		);
		$this->_write_to_excel($objWorkSheet, 'K56:L60', $pic_str, array('font' => $picFont['font'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K61:L61', 'PHOTO', array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M55:M75', NULL, array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		
		$this->_write_to_excel($objWorkSheet, 'K63:L69', NULL, array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K70:L70', 'Right Thumbmark', array('alignment' => $styleHeader['alignment'], 'font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);

 		$sworn_str = "SUBSCRIBED AND SWORN to before me this _____________________________________________ , affiant exhibiting his/her validly issued government ID as indicated above.";
		
		$this->_write_to_excel($objWorkSheet, 'A72:M72', $sworn_str, array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment']), 30, NULL, '72', FALSE);
		$this->_write_to_excel($objWorkSheet, 'E73:I73', NULL, array('borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 60, NULL, '73', FALSE);
		$this->_write_to_excel($objWorkSheet, 'E74:I74', 'Person Administering Oath', array('alignment' => $styleHeader['alignment'], 'font' => $styleField['font'], 'fill' => $styleSubTitle['fill'], 'borders' => array('left' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK), 'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), 20, NULL, '74', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A75:M75', NULL, array('borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A76:M76', 'CS FORM 212 (Revised 2017), Page 4 of 4', $pageNumberFooter, NULL, NULL, NULL, FALSE);

		$writer = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		ob_end_clean();
		header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="sample.xlsx"');
		
		$writer->save('php://output');
	}

	private function _supplm_work_exp($objWorkSheet, $work_exp, $styleFooter, $styleHeader, $styleSubTitle, $styleField, $styleCount, $borderRightThick, $borderThick,  $styleTitle, $smallTitle, $centerSubtitle, $noBorderBottomSub, $noBorderTopSub, $pageNumberFooter)
	{
		
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'A');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '7.5', 'B');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'C');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'D');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'E');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '12', 'F');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'G');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '7', 'H');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'I');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'J');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'K');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '12', 'L');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'M');

		$this->_write_to_excel($objWorkSheet, 'A1:M1', NULL, $borderThick,  2, NULL, '1', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A2:M2', '(SUPPLEMENTARY PAGE FOR WORK EXPERIENCE)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']),  NULL, NULL, NULL, FALSE);

	 	$this->_write_to_excel($objWorkSheet, 'A3:M3', "V. WORK EXPERIENCE", $noBorderBottomSub,  NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'A4:M4', "(Include private employment.  Start from your recent work) Description of duties should be indicated in the attached Work Experience sheet.", $noBorderTopSub,  NULL, NULL, NULL, FALSE);

	 	$this->_write_to_excel($objWorkSheet, 'A5:A6', '28. ', $styleCount, NULL, NULL, NULL, FALSE);		
		$this->_write_to_excel($objWorkSheet, 'B5:C6', "IINCLUSIVE DATES (mm/dd/yyyy)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D5:F7', "POSITION TITLE\n(Write in full/Do not abbreviate)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'G5:I7', "DEPARTMENT / AGENCY / OFFICE / COMPANY\n((Write in full/Do not abbreviate)", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J5:J7', "MONTHLY SALARY", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'K5:K7', "SALARY/ JOB/ PAY GRADE\n(if applicable)& STEP\n(Format '00-0')\n/ INCREMENT", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'L5:L7', "STATUS OF\nAPPOINTMENT", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'M5:M7', "GOV'T\nSERVICE\n(Y/N)", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => $borderRightThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A7:B7', "From", $centerSubtitle, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'C7:C7', "To", $centerSubtitle, NULL, NULL, NULL, FALSE);

		for($i=8;$i<33;$i++) {
			if(!EMPTY($work_exp[$i+20])) {
				$curr = $work_exp[$i+20];
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':B'.$i.'', format_date($curr['employ_start_date']), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'C'.$i.':C'.$i.'', (!EMPTY($curr['employ_end_date']) ? format_date($curr['employ_end_date']) : 'PRESENT'), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'D'.$i.':F'.$i.'', $curr['employ_position_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':I'.$i.'', $curr['employ_office_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'J'.$i.':J'.$i.'', number_format($curr['employ_monthly_salary'],2), $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'K'.$i.':K'.$i.'', $curr['employ_salary_grade'] . ' - ' . $curr['employ_salary_step'], $styleField, 20, NULL, ''.$i.'', FALSEr);
		    	$this->_write_to_excel($objWorkSheet, 'L'.$i.':L'.$i.'', $curr['employment_status_name'], $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'M'.$i.':M'.$i.'', ($curr['govt_service_flag'] == 'Y' ? 'YES' : 'NO'), array('alignment' => $styleField['alignment'], 'font' => $styleField['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
			} else {
				$this->_write_to_excel($objWorkSheet, 'A'.$i.':B'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'C'.$i.':C'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'D'.$i.':F'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'G'.$i.':I'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'J'.$i.':J'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'K'.$i.':K'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'L'.$i.':L'.$i.'', "", $styleField, 20, NULL, ''.$i.'', FALSE);
		    	$this->_write_to_excel($objWorkSheet, 'M'.$i.':M'.$i.'', "", array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
			}
		}

		$this->_write_to_excel($objWorkSheet, 'A33:M33', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A34:M34', 'CS FORM 212 (Revised 2005), Supplementary for Work Experience', $pageNumberFooter, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'J36:M39', '');
		$this->_write_to_excel($objWorkSheet, 'J40:M40', 'Type your complete name here...', array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment']), NULL, NULL, NULL, TRUE);
		$this->_write_to_excel($objWorkSheet, 'J41:M41', 'NAME & SIGNATURE', array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment']), NULL, NULL, NULL, TRUE);
	}

	private function _supplm_other_info($objWorkSheet, $other_params, $styleFooter, $styleHeader, $styleSubTitle, $styleField, $styleCount, $borderRightThick, $borderThick,  $styleTitle, $centerSubtitle, $pageNumberFooter)
	{
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'A');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '30', 'B');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'C');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '15', 'D');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'E');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'F');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'G');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '12', 'H');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '3', 'I');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '30', 'J');
		$this->_write_to_excel($objWorkSheet, NULL, NULL, array(), NULL, '10', 'K');

		$this->_write_to_excel($objWorkSheet, 'A1:K1', NULL, $borderThick,  2, NULL, '1', FALSE);
		$this->_write_to_excel($objWorkSheet, 'A2:K2', '(SUPPLEMENTARY PAGE FOR OTHER INFORMATION)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']),  NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A3:K3', 'VIII. OTHER INFORMATION', $styleTitle,  NULL, NULL, NULL, FALSE);

		$skills = $other_params['skills_list'];
		$recog = $other_params['recog_list'];
		$member = $other_params['member_list'];

	 	$this->_write_to_excel($objWorkSheet, 'A4:A4', '31. ', $styleCount, 30, NULL, '4', FALSE);
		$this->_write_to_excel($objWorkSheet, 'B4:B4', "SPECIAL SKILLS and HOBBIES", $centerSubtitle, NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'C4:C4', '32. ', $styleCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'D4:H4', "NON-ACADEMIC DISTINCTIONS / RECOGNITION\n(Write in full)", $centerSubtitle, NULL, NULL, NULL, FALSE);
	 	$this->_write_to_excel($objWorkSheet, 'I4:I4', '33. ', $styleCount, NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'J4:K4', "MEMBERSHIP IN ASSOCIATION/ORGANIZATION\n(Write in full)", array('font' => $styleSubTitle['font'], 'fill' => $styleSubTitle['fill'], 'alignment' => $styleHeader['alignment'], 'borders' => array('bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN), 'right' => array('style' => PHPExcel_Style_Border::BORDER_THICK))), NULL, NULL, NULL, FALSE);
		
		for($i=5;$i<31;$i++) {

			$this->_write_to_excel($objWorkSheet, 'A'.$i.':B'.$i.'', !EMPTY($skills[$i+2]) ? $skills[$i+2]['others_value'] : '', $styleField, 20, NULL, ''.$i.'', FALSE);
	    	$this->_write_to_excel($objWorkSheet, 'C'.$i.':H'.$i.'', !EMPTY($recog[$i+2]) ? $recog[$i+2]['others_value'] : '', $styleField, 20, NULL, ''.$i.'', FALSE);
	    	$this->_write_to_excel($objWorkSheet, 'I'.$i.':K'.$i.'', !EMPTY($member[$i+2]) ? $member[$i+2]['others_value'] : '', array('font' => $styleSubTitle['font'], 'borders' => $borderRightThick['borders']), 20, NULL, ''.$i.'', FALSE);
		}

		$this->_write_to_excel($objWorkSheet, 'A31:K31', '(Continue on separate sheet if necessary)', array('font' => $styleFooter['font'], 'fill' => $styleFooter['fill'], 'alignment' => $styleFooter['alignment'], 'borders' => $borderThick['borders']), NULL, NULL, NULL, FALSE);
		$this->_write_to_excel($objWorkSheet, 'A32:K32', 'CS FORM 212 (Revised 2005), Supplementary for Other Information', $pageNumberFooter, NULL, NULL, NULL, FALSE);

		$this->_write_to_excel($objWorkSheet, 'J34:K37', '');
		$this->_write_to_excel($objWorkSheet, 'J38:K38', 'Type your complete name here...', array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment']), NULL, NULL, NULL, TRUE);
		$this->_write_to_excel($objWorkSheet, 'J39:K39', 'NAME & SIGNATURE', array('font' => $styleField['font'], 'alignment' => $styleHeader['alignment']), NULL, NULL, NULL, TRUE);
	
	}
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */