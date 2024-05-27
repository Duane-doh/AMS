<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_ta extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('html');
		$this->load->model('reports_ta_model', 'rm');
		$this->load->model('common_model', 'cm');
		$this->load->model('pds_model', 'pds');
	}	
	public function index()
	{
		try
		{
			$data 			= array();
			$resources 		= array();
			
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY);

			$table 						   = array(
					'main' 		=> 		array(
							'table' 	=> $this->rm->tbl_employee_personal_info,
							'alias'		=> 'A'
					),
					't1' 		=>  	array(
							'table'		=> $this->rm->tbl_employee_work_experiences,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition' => 'A.employee_id = B.employee_id'
					)
			);

			// $fields                    = array("DISTINCT A.employee_id", "UPPER(CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)))) as employee_name","A.first_name","A.last_name");
			// ====================== jendaigo : start : change name format ============= //
			$fields                    = array("DISTINCT A.employee_id", "UPPER(CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', LEFT(A.middle_name, 1), '.')))) as employee_name","A.first_name","A.last_name");
			// ====================== jendaigo : end : change name format ============= //
			
			$where                     = array();
			$where['A.first_name']     = "IS NOT NULL";
			$where['A.last_name']     = "IS NOT NULL";
			$order_by                  = array();
			$order_by['employee_name'] = 'asc';
			$data['employees']         = $this->rm->get_reports_data($fields, $table,$where,TRUE,$order_by);

			// $field                     = array("org_code","name") ;
			// $table                     = $this->rm->DB_CORE.".".$this->rm->tbl_organizations;
			// $where                     = array();
			// $data['offices_list'] 	   = $this->rm->get_reports_data($field, $table, $where, TRUE);

			$data['offices_list'] 	   = $this->rm->get_office_list();
			$where                     = array();
			$table 					   = $this->rm->tbl_param_payroll_types;
			$data['payroll_types']         = $this->rm->get_reports_data(('*'), $table,$where,TRUE);
			// FILTERED OFFICE
/*			$field                         = array("B.office_id, A.name") ;
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
							'type'		=> 'LEFT JOIN',
							'condition' => 'B.office_id = C.office_id'
					)
			);
			$group_by					   = array('B.office_id');
			$where                         = array();
			$where['B.active_flag']		   = 'Y';
			$data['offices_filtered']      = $this->rm->get_reports_data($field, $table, $where, TRUE, NULL, $group_by);*/
			
			// $field 							 = array("UPPER(CONCAT(A.first_name,' ',A.last_name, IF(A.ext_name='','',CONCAT(' ', A.ext_name)))) as employee_name", " A.employee_id");
			// $table 							 = array(
			// 									'main'				=> array(
			// 										'table'	 	=> 	$this->rm->tbl_employee_personal_info,
			// 										'alias'		=>  'A'
			// 									),
			// 									't1'				=> array(
			// 										'table' 	=>  $this->rm->DB_CORE.'.'.$this->rm->tbl_sys_param,
			// 										'alias'		=>  'B',
			// 										'type'		=>	'JOIN',
			// 										'condition' =>	'A.agency_employee_id = B.sys_param_value'
			// 									)
			// );

			// $where                     = array();
			// $where['B.sys_param_type'] = PARAM_TA_CERTIFIED_BY;
			// $where['B.active_flag']    = 'Y';
			
			// $data['certified_by']      = $this->rm->get_reports_data($field, $table, $where);

			// $where['B.sys_param_type'] = PARAM_TA_APPROVED_BY;
			// $data['approved_by']       = $this->rm->get_reports_data($field, $table, $where);

			$data['certified_by']      = $this->cm->get_report_signatories(CODE_TA, CERTIFIED_BY);
			$data['approved_by']       = $this->cm->get_report_signatories(CODE_TA, APPROVED_BY);
			$data['prepared_by']       = $this->cm->get_report_signatories(CODE_TA, PREPARED_BY);

			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Time & Attendance"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/reports_ta/reports_ta/";
			$key					= "Reports"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/reports_ta/reports_ta/";

			set_breadcrumbs($breadcrumbs, TRUE);

			$this->template->load('reports_ta_view', $data, $resources);
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
			$data           	= array();
			$params         	= get_params();
			
			$tracking_code   	= strtolower($params['tracking_code']);

			$field                     = array("sys_param_value");
			$table                     = $this->rm->DB_CORE.".".$this->rm->tbl_sys_param;
			$where                     = array();
			$where['sys_param_type']   = 'REPORT_FOOTER_LINE';
			$footer	    	   		   = $this->rm->get_reports_data($field, $table, $where, TRUE);			
			$footer_line1 		 	   = $footer[0]['sys_param_value'];
			$footer_line2 			   = $footer[1]['sys_param_value'];


			$margin_left     = 20; //TOP
			$margin_bottom   = 20; // RIGHT
			$margin_right    = 20; // BOTTOM
			$margin_top      = 20; // LEFT
			$margin_header   = 20;
			$margin_footer   = 20;
			$paper_width  = 210;
			$paper_length = 297;
			$set_footer   = FALSE;

			// FOR TYPE OF FILE (EXCEL/PDF)
			switch ($report) {
				case REPORTS_TA_DAILY_TIME_RECORD: 
					$generate_report = modules::load('main/reports_ta/ta_daily_time_record');
					$data            = $generate_report->generate_report_data($params);
					
					//add work schedule in dtr
					// $emp_id 		 = (!EMPTY($params['employee']) ? $params['employee'] : $params['employee_filtered']);
					// $this->load->model('daily_time_record_model', 'dtr');
					// $data['work_schedule'] = $this->dtr->get_employee_work_schedule($emp_id,$params['date_range_to']);
				break;
				case REPORTS_TA_LEAVE_APPLICATION: 
					$generate_report = modules::load('main/reports_ta/ta_leave_aplication');
					$data            = $generate_report->generate_report_data($params);
				break;
				case REPORTS_TA_LEAVE_CARD: 
					$set_footer = false;
					$generate_report = modules::load('main/reports_ta/ta_leave_card');
					$data            = $generate_report->generate_report_data($params);	
				break;
				case REPORTS_TA_MONTHLY_ATTENDANCE: 
					$generate_report 	= modules::load('main/reports_ta/ta_monthly_attendance');
					$data            	= $generate_report->generate_report_data($params);	

					$mra_footer = '<table class="table-max" cellpadding="0">'
						.'<tbody>'
							.'<tr class="f-size-12">'
								.'<td width="60%">Prepared By:</td>'
								.'<td width="40%">Certified Correct By:</td>'
							.'</tr>'
							.'<tr class="f-size-12">'
								.'<td colsan="2"><br><br><br></td>'
							.'</tr>'
							.'<tr>'
								.'<td>'.$data['prepared_by']['signatory_name'].'</td>'
								.'<td>'.$data['certified_by']['signatory_name'].'</td>'
							.'</tr>'
							.'<tr>'
								.'<td>'.$data['prepared_by']['office_name'].'</td>'
								.'<td>'.$data['certified_by']['office_name'].'</td>'
							.'</tr>'
							.'<tr>'
								.'<td colspan=2 align="left" valign="bottom" height="15" style="font-family: "Arial Narrow", Arial, sans-serif;font-size: 8pt;"><br>'.$tracking_code.'</td>'
							.'</tr>'
						.'</tbody>'
					.'</table>';
					$margin_left     = 12; //TOP
					$margin_bottom   = 20; // RIGHT
					$margin_right    = 12; // BOTTOM
					$margin_top      = 20; // LEFT
					$margin_footer = 30;

				break;
				case REPORTS_TA_LEAVE_BALANCE_STATEMENT: 					
					//$set_footer      = TRUE;
					$generate_report = modules::load('main/reports_ta/ta_leave_balance_statement');
					$data            = $generate_report->generate_report_data($params);		
				break;
				
				case REPORTS_TA_LEAVE_CREDIT_CERT:				
					$set_footer      = TRUE;
					//$certificate	 = TRUE;
					$generate_report = modules::load('main/reports_ta/ta_leave_credit_cert');
					$data            = $generate_report->generate_report_data($params);				
				break;
				
				case REPORTS_TA_LEAVE_WITHOUT_PAY_CERT:						
					$set_footer      = TRUE;
					//$certificate	 = TRUE;
					$generate_report = modules::load('main/reports_ta/ta_leave_without_pay_cert');
					$data            = $generate_report->generate_report_data($params);				
				break;

				case REPORT_COE_WITH_COMPENSATIONS:
				case REPORT_COE_WITHOUT_COMPENSATIONS:
					$set_footer      	= TRUE;
					$generate_report 	= modules::load('main/reports_ta/coe_with_compensations');
					$with_compensation 	= ($report == REPORT_COE_WITH_COMPENSATIONS) ? TRUE : FALSE;
					$data            	= $generate_report->generate_report_data($params, $with_compensation);
					$margin_header      = 10;
				break;

				case REPORTS_TA_NO_WORK_SCHED_LIST: 
					$generate_report = modules::load('main/reports_ta/ta_no_work_sched_list');
					$data            = $generate_report->generate_report_data($params);
					
					//add work schedule in dtr
					// $emp_id 		 = (!EMPTY($params['employee']) ? $params['employee'] : $params['employee_filtered']);
					// $this->load->model('daily_time_record_model', 'dtr');
					// $data['work_schedule'] = $this->dtr->get_employee_work_schedule($emp_id,$params['date_range_to']);
				break;
			}

			if(strtolower($format) == 'pdf')
			{
				ini_set('memory_limit', '512M'); // boost the memory limit if it's low
				$this->load->library('pdf');
				//Legal Size Paper

				//$pdf 	= $this->pdf->load('utf-8', array($paper_width,$paper_length));				
				$pdf 	= $this->pdf->load('utf-8', array($paper_width,$paper_length), $margin_top,$margin_bottom,$margin_left,$margin_right,$margin_header,$margin_footer);
				$footer = '<table width="100%">';
				$footer .= '<tr>';
				$footer .= '<td align="left" valign="bottom" height="15" style="font-size: 8pt;font-family: Arial Narrow,Arial, sans-serif;">'. $tracking_code .'</td>';
				$footer .= '</tr>';
				if($set_footer)
				{
					
					$footer .= '<tr>';
					$footer .= '<td style="border-top: 1px solid #000000;" align="center" height="15" valign="bottom"><font size="2">'.$footer_line1.'</font></td>';
					$footer .= '</tr>';
					$footer .= '<tr>';
					$footer .= '<td align="center"><font size="2">'.$footer_line2.'</font></td>';
					$footer .= '</tr>';
				}				

				$footer .= '</table>';

				$footer = ($report == REPORTS_TA_MONTHLY_ATTENDANCE) ? $mra_footer : $footer;
				
				// $pdf->SetHTMLFooter($footer);
				// $html 	= $this->load->view('forms/reports/' . $report , $data, TRUE);
				// $pdf->WriteHTML($html);
					
				//===== jendaigo : start : MRA page # & last page footer =====//
				if($report == REPORTS_TA_MONTHLY_ATTENDANCE)
				{
					$mra_fpagenum = '<table class="table-max" cellpadding="0">'
						.'<tbody>'
							.'<tr>'
								.'<td colspan=2 align="right" valign="bottom" height="15" style="font-family: "Arial Narrow", Arial, sans-serif;font-size: 8pt;"><br>{PAGENO} of {nbpg}</td>'
							.'</tr>'
						.'</tbody>'
					.'</table>';
					
					$pdf->SetHTMLFooter($mra_fpagenum);
					$html 	= $this->load->view('forms/reports/' . $report , $data, TRUE);
					$pdf->WriteHTML($html);
					$pdf->SetHTMLFooter($footer.$mra_fpagenum);
				}
				else
				{
					$pdf->SetHTMLFooter($footer);
					$html 	= $this->load->view('forms/reports/' . $report , $data, TRUE);
					$pdf->WriteHTML($html);
				}
				//====== jendaigo : end : MRA page # & last page footer ======//
				
				$pdf->Output();
			}

			if(strtolower($format) == 'excel')
			{
				$this->load->view('forms/reports/' . $report , $data);
				
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

	public function get_leave_requests()
	{
		$params      = get_params();
		$list        = array();
		
		$employee_id = $params['employee'];
		
		$requests    = $this->rm->get_leave_requests($employee_id);
		
		if(!EMPTY($requests))
		{
			foreach ($requests as $aRow):
				$request_id = $this->hash($aRow["request_id"]);	
				$list[] = array(
								"value" => $request_id,
								"text" => $aRow["request_code"]
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

	public function get_mra_attendance_period()
	{
		
		$list        = array();
		
		$field                     = array("*") ;
		$tables = array(

				'main'      => array(
				'table'     => $this->rm->tbl_attendance_period_hdr,
				'alias'     => 'A',
				),
				't2'        => array(
				'table'     => $this->rm->tbl_param_payroll_types,
				'alias'     => 'B',
				'type'      => 'JOIN',
				'condition' => 'A.payroll_type_id = B.payroll_type_id',
			 	)
			);
		$where                     = array();
		$order_by = array('A.date_from' => 'desc');
		$attendance_period         = $this->rm->get_reports_data($field, $tables, $where, TRUE,$order_by);
		
		if(!EMPTY($attendance_period))
		{
			foreach ($attendance_period as $aRow):
				$date_from = date('Y/m/d',strtotime($aRow["date_from"]));
				$date_to   = date('Y/m/d',strtotime($aRow["date_to"]));
				$list[] = array(
								"value" => $aRow["attendance_period_hdr_id"],
								"text" => $date_from." - ".$date_to." - ".$aRow["payroll_type_name"]
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
	public function get_mra_office()
	{
		
		$list        = array();
		
		$params = get_params();
		$offices         = $this->rm->get_mra_office($params['attendance_period']);
		
		if(!EMPTY($offices))
		{
			foreach ($offices as $aRow):
				$list[] = array(
								"value" => $aRow["office_id"],
								"text" => $aRow["name"]
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
	public function get_mra_employee()
	{
		
		$list      = array();
		
		$params    = get_params();
		$employees = $this->rm->get_mra_employee($params);
		
		if(!EMPTY($employees))
		{
			foreach ($employees as $aRow):
				$list[] = array(
								"value" => $aRow["employee_id"],
								"text" => $aRow["employee_name"]
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

	public function get_payroll_period()
	{
		
		$list        = array();
		
		$params = get_params();
		$p_period         = $this->rm->get_payroll_period($params['payroll_type']);
		
		if(!EMPTY($p_period ))
		{
			foreach ($p_period  as $aRow):
				$list[] = array(
								"value" => $aRow["attendance_period_hdr_id"],
								"text" => $aRow["date_from"] ." - ". $aRow["date_to"]
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
	
	public function get_employee_by_office(){
		try
		{
	
			$flag 			= 0;
			$msg			= "";
			$options 		= array();
			$params 		= get_params();
	
			$where		 	 				= array();
			$where['A.employ_office_id']	= $params['office_id'];
			// $results 						= $this->rm->get_employee_by_office($where);
			//filter without job order employees davcorrea 10/03/2023
			// ======================START===========================
			// if($params['report'] == REPORTS_TA_LEAVE_CREDIT_CERT)
			if($params['report'] == REPORTS_TA_LEAVE_CREDIT_CERT || $params['report'] == REPORTS_TA_LEAVE_WITHOUT_PAY_CERT)// include cert of LWOP to not include JO : davcorrea : 11/06/2023
			{
				$results 						= $this->rm->get_employee_by_office_wo_jo($where);
		
			}
			else
			{
				$results 						= $this->rm->get_employee_by_office($where);
			}
			// ===========================END=========================
	
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
}


/* End of file Reports_payroll.php */
/* Location: ./application/modules/main/controllers/reports/Reports_ta.php */