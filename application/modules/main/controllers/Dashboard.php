<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('dashboard_model', 'dashboard');
	}
	
	public function index()
	{
		try
		{ 
			date_default_timezone_set("Asia/Manila");
			$current_date          = date('Y-m-d');
			
			$data                  = array();
			$resources             = array();
			
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE, 'chart.min');
			
			$user_id               = $this->session->userdata('user_id');
			
			//PERSONNEL DASHBOARD
			$data['title_1']       = "Leaves";
			$data['title_2']       = "Attendance";
			$data['title_3']       = "Late";
			$data['sub_title_1']   = "Total Number of Approved Leaves";
			$data['sub_title_2']   = "Total Number of Incomplete Attendance";
			$data['sub_title_3']   = "Total Number of Late";
			$data['hide_bar']      = "hide";

			$resources['load_modal']    = array(
			'modal_upload_pds'  => array(
				'controller'	=> "Pds_upload",
				'module'		=> PROJECT_MAIN,
				'method'		=> 'modal_upload_pds',
				'multiple'		=> true,
				'height'		=> '350px',
				'size'			=> 'sm',
				'title'			=> "Upload PDS"
			)
		);		

		$fields 			= array('A.office_id','B.name AS office_name');
		$tables 			= array(
			'main' 			=> array(
				'table' 	=> $this->dashboard->tbl_param_offices,
				'alias' 	=> 'A'
			),
			't1'   			=> array(
				'table' 	=> $this->dashboard->db_core . '.' . $this->dashboard->tbl_organizations,
				'alias' 	=> 'B',
				'type'  	=> 'JOIN',
				'condition' => 'A.org_code = B.org_code'
 			)
		);

		$where 				 = array('A.active_flag' => YES);
		$data['office_list'] = $this->dashboard->get_general_data($fields, $tables, $where, TRUE);
			
		$this->template->load('dashboard', $data, $resources);
			
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

		//echo json_encode( $output );
	}
	public function get_dashboard($system_code, $office_id = null)
	{
		try
		{
			$data                  = array();
			$resources             = array();

			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE, 'chart.min');

			$fields 			= array('A.office_id','B.name AS office_name');
			$tables 			= array(
				'main' 			=> array(
					'table' 	=> $this->dashboard->tbl_param_offices,
					'alias' 	=> 'A'
				),
				't1'   			=> array(
					'table' 	=> $this->dashboard->db_core . '.' . $this->dashboard->tbl_organizations,
					'alias' 	=> 'B',
					'type'  	=> 'JOIN',
					'condition' => 'A.org_code = B.org_code'
	 			)
			);

			$where 				 = array('A.active_flag' => YES);
			$data['office_list'] = $this->dashboard->get_general_data($fields, $tables, $where, TRUE);

			$month_jan = '01';
			$month_feb = '02';
			$month_mar = '03';
			$month_apr = '04';
			$month_may = '05';
			$month_jun = '06';
			$month_jul = '07';
			$month_aug = '08';
			$month_sep = '09';
			$month_oct = '10';
			$month_nov = '11';
			$month_dec = '12';

			switch ($system_code) 
			{	
				case CODE_PORTAL:
					$user_id             = $this->session->userdata('user_pds_id');
					$data['title_1a']    = "Sick Leaves";
					$data['title_1b']    = "Vacation Leaves";
					$data['title_2']     = "Attendance";
					$data['title_3']     = "Late";
					$data['sub_title_1'] = "Total Number of Remaining Leaves";
					$data['sub_title_2'] = "Total Number of Incomplete Attendance";
					$data['sub_title_3'] = "Total Number of Late";
					$data['hide_bar']    = "hide";
					$data['system_code'] = $system_code;

					//TOTAL NUMBER OF REMAINING LEAVES
					$table							= $this->dashboard->tbl_employee_leave_balances;
					$field                          = array('SUM(IF(leave_type_id = 1 ,leave_balance,0)) AS sl', 'SUM(IF(leave_type_id = 2 ,leave_balance,0)) AS vl');					
					$where                          = array();
					$key         					= $this->get_hash_key('employee_id');
					$where[$key]         			= $user_id;
					$data['count_1']                = $this->dashboard->get_general_data($field, $table, $where, FALSE);
					$vl_cnt 						= round($data['count_1']['vl'], 3);
					$sl_cnt 						= round($data['count_1']['sl'], 3);
					$data['length'] 				= strlen($vl_cnt) + strlen($sl_cnt);
									
					//TOTAL INCOMPLETE ATTENDANCE
					$data['count_2'] = $this->dashboard->get_incomplete_attendance($user_id);
					
					//NUMBER OF LATE
					$tables			= array(
						'main'		=> array(
						'table'		=> $this->dashboard->tbl_attendance_period_dtl,
						'alias'		=> 'A'
						),
						't1'		=> array(
						'table'		=> $this->dashboard->tbl_employee_work_experiences,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'A.employee_id = B.employee_id'
						),
						't2'		=> array(
						'table'		=> $this->dashboard->tbl_param_offices,
						'alias'		=> 'C',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'B.employ_office_id = C.office_id'
						)
					);

					$field                          			   		= array('SUM(A.tardiness) AS count');					
					$where                          			   		= array();
					$where['A.tardiness'] 			   			   		= array(0 ,array('>'));
					$where['DATE_FORMAT(A.attendance_date, "%Y-%m")'] 	= date('Y-m');
					$key         								   		= $this->get_hash_key('B.employee_id');
					$where[$key]         						   		= $user_id;
					$data['count_3']                			   		= $this->dashboard->get_general_data($field, $tables, $where, FALSE);

					// EMPLOYEE LATE LINE GRAPH
					$data['late_count'] = $this->dashboard->get_monthly_employee_late($user_id);
				break;

				case CODE_HR: 
					$data['title_1']              = "Job Order";
					$data['title_2']              = "Employee";
					$data['title_3']              = "Personnel";
					$data['sub_title_1']          = "Total Number of Job Order";
					$data['sub_title_2']          = "Total Number of Regular Employee";
					$data['sub_title_3']          = "Total Number of Personnel";
					$data['bar_graph_1']          = "Monthly Number of Personnel";
					$data['bar_graph_2']          = "Number of Personnel per Cluster";
					$data['bar_graph_sub_1']      = "Monthly Number of Personnel";
					$data['bar_graph_sub_2']      = "Number of Personnel per Cluster";					
					$data['hide_line']            = "hide";
					$data['system_code']		  = $system_code;
					$data['office_id'] 			  = $office_id;	

					//GET TOTAL NUMBER OF JOB ORDER
					$tables 		= array(
						'main'		=> array(
						'table'		=> $this->dashboard->tbl_employee_personal_info,
						'alias'		=> 'A'
						),
						't1'		=> array(
						'table'		=> $this->dashboard->tbl_employee_work_experiences,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.employee_id = B.employee_id'
						),
						't2'        => array(
						'table'     => $this->dashboard->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	)
					);
					
					$field                        	= array("count(DISTINCT A.employee_id) AS count");					
					$where                        	= array();
					$where['B.employ_type_flag']  	= DOH_JO;
					$where['B.separation_mode_id']  = 'IS NULL';
					$where['B.active_flag']       	= YES;
					if(!EMPTY($office_id))
					$where['C.office_id'] 		  	= array($this->dashboard->get_office_child('', $office_id),array('IN'));
					$data['count_1']              	= $this->dashboard->get_general_data($field, $tables, $where, FALSE);				
					
					$field                        	= array("count(DISTINCT A.employee_id) AS count");					
					$where                        	= array();
					$where['B.employ_type_flag']  	= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT), array('IN'));
					$where['B.separation_mode_id']  = 'IS NULL';
					$where['B.active_flag']       	= YES;
					if(!EMPTY($office_id))
					$where['C.office_id'] 		  	= array($this->dashboard->get_office_child('', $office_id),array('IN'));
					$data['count_2']          	  	= $this->dashboard->get_general_data($field, $tables, $where, FALSE);
					
					$field                        	= array("B.employ_start_date");					
					$where                        	= array();
					$where['B.separation_mode_id']  = 'IS NULL';																																																																																																																																																	;
					$where['B.active_flag']       	= YES;
					$where['B.employ_type_flag']  	= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
					if(!EMPTY($office_id))
					$where['C.office_id'] 		  	= array($this->dashboard->get_office_child('', $office_id),array('IN'));				
					$group                        	= array("A.employee_id", "B.employ_start_date");
					$data['month_num_personnel']  	= $this->dashboard->get_general_data($field, $tables, $where, TRUE, $order_by = array(), $group);
					$data['count_3'] 				= count($data['month_num_personnel']);

					if($office_id)
					$offices 			= $this->dashboard->get_office_child('', $office_id);
					$data['january'] 	= $this->dashboard->get_monthly_employee_count($month_jan, $offices);
					$data['february'] 	= $this->dashboard->get_monthly_employee_count($month_feb, $offices);
					$data['march'] 	 	= $this->dashboard->get_monthly_employee_count($month_mar, $offices);
					$data['april'] 	 	= $this->dashboard->get_monthly_employee_count($month_apr, $offices);
					$data['may'] 	 	= $this->dashboard->get_monthly_employee_count($month_may, $offices);
					$data['june'] 	 	= $this->dashboard->get_monthly_employee_count($month_jun, $offices);
					$data['july'] 	 	= $this->dashboard->get_monthly_employee_count($month_jul, $offices);
					$data['august'] 	= $this->dashboard->get_monthly_employee_count($month_aug, $offices);
					$data['september'] 	= $this->dashboard->get_monthly_employee_count($month_sep, $offices);
					$data['october'] 	= $this->dashboard->get_monthly_employee_count($month_oct, $offices);
					$data['november'] 	= $this->dashboard->get_monthly_employee_count($month_nov, $offices);
					$data['december'] 	= $this->dashboard->get_monthly_employee_count($month_dec, $offices);					
				break;

				case CODE_TA:
					//TIME AND ATTENDANCE DASHBOARD
					$data['title_1a']    		 = "Sick Leaves";
					$data['title_1b']    		 = "Vacation Leaves";
					$data['title_2']             = "Attendance";
					$data['title_3']             = "Personnel";
					$data['sub_title_1']         = "Total Number of Remaining Leaves";
					$data['sub_title_2']         = "Total Number of Incomplete Attendance";
					$data['sub_title_3']         = "Total Personnel with Incomplete Attendance";
					$data['bar_graph_1']         = "Monthly Number of Leaves ";
					$data['bar_graph_2']         = "Number of Leaves per Cluster ";
					$data['bar_graph_sub_1']     = "Monthly Number of Leaves ";
					$data['bar_graph_sub_2']     = "Number of Leaves  per Cluster ";
					$data['bar_graph_summary_1'] = "Here is some more information about this product that is only revealed once clicked on.";
					$data['bar_graph_summary_2'] = "Here is some more information about this product that is only revealed once clicked on.";
					$data['hide_line']           = "hide";
					$data['system_code']		 = $system_code;
					$data['office_id'] 			 = $office_id;	

					//TOTAL NUMBER OF PENDING LEAVES
					$tables 		= array(
						'main'		=> array(
						'table'		=> $this->dashboard->tbl_employee_leave_balances,
						'alias'		=> 'A'
						),
						't3'		=> array(
						'table'		=> $this->dashboard->tbl_employee_work_experiences,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'A.employee_id = B.employee_id'
						),
						't4'        => array(
						'table'     => $this->dashboard->tbl_param_offices,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employ_office_id = C.office_id'
					 	)
					);

					$field                          		    = array('SUM(IF(A.leave_type_id = 1 ,A.leave_balance,0)) AS sl', 'SUM(IF(A.leave_type_id = 2 ,A.leave_balance,0)) AS vl');					
					$where                                      = array();
					$where['B.separation_mode_id']  			= 'IS NULL';
					$where['B.active_flag']       				= YES;
					if(!EMPTY($office_id))
					$where['C.office_id'] 		  				= array($this->dashboard->get_office_child('', $office_id),array('IN'));	
					$data['count_1']                            = $this->dashboard->get_general_data($field, $tables, $where, FALSE, $order_by = array(), $group);
					
					$vl_cnt 						= round($data['count_1']['vl'], 3);
					$sl_cnt 						= round($data['count_1']['sl'], 3);
					$data['length'] 				= strlen($vl_cnt) + strlen($sl_cnt);
					
					if(!EMPTY($office_id))
					$offices		  							= $this->dashboard->get_office_child('', $office_id);	

					//TOTAL NUMBER OF INCOMPLETE ATTENDANCE	
					$select_field = '';		
					$select_field = 'COUNT(A.employee_id) AS count';
					$data['count_2']                            = $this->dashboard->get_total_incomplete_attendance($offices, $select_field);

					//TOTAL PERSONNEL WITH INCOMPLETE ATTENDANCE
					$select_field = '';		
					$select_field = 'COUNT(DISTINCT A.employee_id) AS count';
					$data['count_3']                            = $this->dashboard->get_total_incomplete_attendance($offices, $select_field);
					
					//MONTHLY NUMBER OF LEAVES 
					if($office_id)
					$offices 			= $this->dashboard->get_office_child('', $office_id);
					$data['january'] 	= $this->dashboard->get_monthly_leave_count($month_jan, $offices);
					$data['february'] 	= $this->dashboard->get_monthly_leave_count($month_feb, $offices);
					$data['march'] 		= $this->dashboard->get_monthly_leave_count($month_mar, $offices);
					$data['april'] 		= $this->dashboard->get_monthly_leave_count($month_apr, $offices);
					$data['may'] 		= $this->dashboard->get_monthly_leave_count($month_may, $offices);
					$data['june'] 		= $this->dashboard->get_monthly_leave_count($month_jun, $offices);
					$data['july'] 		= $this->dashboard->get_monthly_leave_count($month_jul, $offices);
					$data['august'] 	= $this->dashboard->get_monthly_leave_count($month_aug, $offices);
					$data['september'] 	= $this->dashboard->get_monthly_leave_count($month_sep, $offices);
					$data['october'] 	= $this->dashboard->get_monthly_leave_count($month_oct, $offices);
					$data['november'] 	= $this->dashboard->get_monthly_leave_count($month_nov, $offices);
					$data['december'] 	= $this->dashboard->get_monthly_leave_count($month_dec, $offices);	
				break;

				case CODE_PAYROLL:
					$data['title_1']             = "Amount";
					$data['title_2']             = "Payroll";
					$data['title_3']             = "Voucher";
					$data['sub_title_1']         = "Total Number of Unremitted Amount";
					$data['sub_title_2']         = "Total Number of Incomplete Payroll";
					$data['sub_title_3']         = "Total Number of Incomplete Voucher";
					$data['bar_graph_1']         = "Monthly Payroll  Total Amount";
					$data['bar_graph_2']         = "Payroll Total  Amount per Cluster ";
					$data['bar_graph_sub_1']     = "Monthly Payroll  Total Amount";
					$data['bar_graph_sub_2']     = "Payroll Total  Amount per Cluster ";
					$data['bar_graph_summary_1'] = "Here is some more information about this product that is only revealed once clicked on.";
					$data['bar_graph_summary_2'] = "Here is some more information about this product that is only revealed once clicked on.";
					$data['hide_line']           = "hide";
					$data['system_code']		 = $system_code;
					$data['office_id'] 			 = $office_id;	

					//TOTAL NUMBER OF UNREMITTED AMOUNT
					$tables 		= array(
						'main'		=> array(
						'table'		=> $this->dashboard->tbl_remittances,
						'alias'		=> 'A'
						),
						't1'		=> array(
						'table'		=> $this->dashboard->tbl_remittance_details,
						'alias'		=> 'B',
						'type'		=> 'JOIN',
						'condition'	=> 'B.remittance_id = A.remittance_id'
						),
						't2'		=> array(
						'table'		=> $this->dashboard->tbl_payout_details,
						'alias'		=> 'D',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'D.payroll_dtl_id = B.payroll_dtl_id'
						),
						't3'		=> array(
						'table'		=> $this->dashboard->tbl_payout_header,
						'alias'		=> 'E',
						'type'		=> 'JOIN',
						'condition'	=> 'E.payroll_hdr_id = D.payroll_hdr_id'
						),
						't4'        => array(
						'table'     => $this->dashboard->tbl_employee_work_experiences,
						'alias'     => 'F',
						'type'      => 'LEFT JOIN',
						'condition' => 'E.employee_id = F.employee_id'
					 	),
						't5'        => array(
						'table'     => $this->dashboard->tbl_param_offices,
						'alias'     => 'G',
						'type'      => 'LEFT JOIN',
						'condition' => 'F.employ_office_id = G.office_id'
					 	)
					);
					$field                         	 = array("COUNT(DISTINCT E.employee_id) AS count");					
					$where                         	 = array();
					$where['A.remittance_status_id'] = array($value = array(REMITTANCE_FOR_REMITTANCE, REMITTANCE_PROCESSING), array("IN"));
					$where['F.separation_mode_id']   = 'IS NULL';																																																																																																																																																	;
					$where['F.active_flag']       	 = YES;
					$where['F.employ_type_flag']  	 = array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
					if(!EMPTY($office_id))
					$where['G.office_id'] 		  	 = array($this->dashboard->get_office_child('', $office_id),array('IN'));
					$data['count_1']               	 = $this->dashboard->get_general_data($field, $tables, $where, FALSE);

					//TOTAL NUMBER OF INCOMPLETE PAYROLL
					$tables 		= array(
						'main'		=> array(
						'table'		=> $this->dashboard->tbl_payout_summary,
						'alias'		=> 'A'
						),
						't1'		=> array(
						'table'		=> $this->dashboard->tbl_payout_header,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id'
						),
						't2'        => array(
						'table'     => $this->dashboard->tbl_employee_work_experiences,
						'alias'     => 'C',
						'type'      => 'LEFT JOIN',
						'condition' => 'B.employee_id = C.employee_id'
					 	),
						't3'        => array(
						'table'     => $this->dashboard->tbl_param_offices,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.employ_office_id = D.office_id'
					 	)
					);	
								
					$field                     		= array("COUNT(DISTINCT B.employee_id) AS count");					
					$where                     		= array();
					$where['A.payout_status_id'] 	= array($value = array(PAYOUT_STATUS_FOR_PROCESSING, PAYOUT_STATUS_FOR_REVIEW), array("IN"));
					$where['A.payout_type_flag']  	= array($value = array(PAYOUT_TYPE_FLAG_REGULAR), array("IN"));			
					$where['C.separation_mode_id']  = 'IS NULL';																																																																																																																																																	;
					$where['C.active_flag']       	= YES;
					$where['C.employ_type_flag']  	= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
					if(!EMPTY($office_id))
					$where['D.office_id'] 		  	= array($this->dashboard->get_office_child('', $office_id),array('IN'));
					$data['count_2']           		= $this->dashboard->get_general_data($field, $tables, $where, FALSE);

					//TOTAL NUMBER OF INCOMPLETE VOUCHER
					$tables 		= array(
						'main'		=> array(
						'table'		=> $this->dashboard->tbl_vouchers,
						'alias'		=> 'A'
						),
						't1'		=> array(
						'table'		=> $this->dashboard->tbl_payout_summary,
						'alias'		=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id'
						),
						't2'		=> array(
						'table'		=> $this->dashboard->tbl_payout_header,
						'alias'		=> 'C',
						'type'		=> 'LEFT JOIN',
						'condition'	=> 'B.payroll_summary_id = C.payroll_summary_id'
						),
						't3'        => array(
						'table'     => $this->dashboard->tbl_employee_work_experiences,
						'alias'     => 'D',
						'type'      => 'LEFT JOIN',
						'condition' => 'C.employee_id = D.employee_id'
					 	),
						't4'        => array(
						'table'     => $this->dashboard->tbl_param_offices,
						'alias'     => 'E',
						'type'      => 'LEFT JOIN',
						'condition' => 'D.employ_office_id = E.office_id'
					 	)
					);
					
					$field                       	= array("COUNT(DISTINCT C.employee_id) AS count");					
					$where                       	= array();
					$where['A.voucher_status_id'] 	= VOUCHER_STATUS_FOR_PROCESSING;
					$where['B.payout_type_flag']  	= PAYOUT_TYPE_FLAG_VOUCHER;				
					$where['D.separation_mode_id']  = 'IS NULL';																																																																																																																																																	;
					$where['D.active_flag']       	= YES;
					$where['D.employ_type_flag']  	= array(array(DOH_GOV_APPT, DOH_GOV_NON_APPT, DOH_JO), array('IN'));
					if(!EMPTY($office_id))
					$where['E.office_id'] 		  	= array($this->dashboard->get_office_child('', $office_id),array('IN'));
					$data['count_3']             	= $this->dashboard->get_general_data($field, $tables, $where, FALSE); 

					//MONTHLY PAYROLL TOTAL AMOUNT
					if($office_id)
					$offices 			= $this->dashboard->get_office_child('', $office_id);
					$data['january'] 	= $this->dashboard->get_monthly_payroll_amount($month_jan, $offices);
					$data['february'] 	= $this->dashboard->get_monthly_payroll_amount($month_feb, $offices);
					$data['march'] 		= $this->dashboard->get_monthly_payroll_amount($month_mar, $offices);
					$data['april'] 		= $this->dashboard->get_monthly_payroll_amount($month_apr, $offices);
					$data['may'] 		= $this->dashboard->get_monthly_payroll_amount($month_may, $offices);
					$data['june'] 		= $this->dashboard->get_monthly_payroll_amount($month_jun, $offices);
					$data['july'] 		= $this->dashboard->get_monthly_payroll_amount($month_jul, $offices);
					$data['august'] 	= $this->dashboard->get_monthly_payroll_amount($month_aug, $offices);
					$data['september'] 	= $this->dashboard->get_monthly_payroll_amount($month_sep, $offices);
					$data['october'] 	= $this->dashboard->get_monthly_payroll_amount($month_oct, $offices);
					$data['november'] 	= $this->dashboard->get_monthly_payroll_amount($month_nov, $offices);
					$data['december'] 	= $this->dashboard->get_monthly_payroll_amount($month_dec, $offices);	

					//PAYROLL TOTAL AMOUNT PER CLUSTER
				break;
			}			

			$this->template->load('dashboard', $data, $resources);
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
}


/* End of file Dashboard.php */
/* Location: ./application/modules/main/controllers/Dashboard.php */