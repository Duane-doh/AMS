<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_general_tab extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	private $form2316;
	private $payroll_common;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payroll_model', 'payroll');
		$this->load->model('payroll_process_model', 'payroll_process');
		$this->load->model('common_model', 'common');
		$this->load->model('reports_model', 'rm');
		
		$this->permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
		$this->form2316 = modules::load('main/payroll_form_2316');
		$this->payroll_common = modules::load('main/payroll_common');
	}

	public function get_tab($form, $action, $id, $token, $salt, $module, $gp_flag=YES)
	{
		try
		{
			$resources['load_css'] 	= array(CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_LABELAUTY);
			
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
				throw new Exception($this->lang->line('err_unauthorized_access'));
	
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['token']			= $token;
			$data['salt']			= $salt;
			$data['module']			= $module;
			$data['gp_flag']		= $gp_flag;
			
			// get payroll_summary_id
			$field = array('payroll_summary_id');
			$key   = $this->get_hash_key('payroll_summary_id');
			$where = array();
			$where[$key] = $id;
			$table = $this->payroll->tbl_payout_summary;
			$payout_summary_rec = $this->common->get_general_data($field, $table, $where, FALSE);
			if ( ! isset($payout_summary_rec))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			$data['summary_id'] = $payout_summary_rec['payroll_summary_id'];
			
			switch ($form)
			{
				case 'tab_personnel_list':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$path = "main/payroll_general_tab/get_payout_employee_list/$action/$id/$token/$salt/$module/$gp_flag";
					$resources['datatable'][]	= array('table_id' => 'table_payout_employee', 'path' => $path, 'advanced_filter' => true);
					$resources['load_modal'] = array(
						'modal_personnel_process' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_personnel_process',
							'multiple'		=> true,
							'height'		=> '460px',
							'size'			=> 'xl',
							'title'			=> 'Payroll Employee'
						)
					);
					
				break;
	
				case 'tab_compensation_list':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$path = "main/payroll_general_tab/get_payout_benefit_list/$action/$id/$token/$salt/$module/$gp_flag";
					$resources['datatable'][]	= array('table_id' => 'table_payout_benefits', 'path' => $path, 'advanced_filter' => TRUE);
					$resources['load_modal'] = array(
					'modal_compensation_process' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_compensation_process',
							'multiple'		=> true,
							'height'		=> '460px',
							'size'			=> 'xl',
							'title'			=> 'Compensation'
						)
					);
				break;
	
				case 'tab_deduction_list':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$path = "main/payroll_general_tab/get_payout_deduction_list/$action/$id/$token/$salt/$module/$gp_flag";
					$resources['datatable'][]	= array('table_id' => 'table_payout_deductions', 'path' => $path, 'advanced_filter' => true);
					$resources['load_modal'] = array(
					'modal_deduction_process' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_deduction_process',
							'multiple'		=> true,
							'height'		=> '460px',
							'size'			=> 'xl',
							'title'			=> 'Deduction'
						)
					);
				break;
				
				case 'tab_process_status':
					$resources['load_css'] 	= array(CSS_SELECTIZE);
					$resources['load_js'] 	= array(JS_SELECTIZE);
					
					$fields	= array('A.payout_status_id, B.payout_status_name', 'C.date_from', 'C.date_to', 'D.payroll_type_name', 
									'GROUP_CONCAT(DISTINCT E.effective_date) payout_date', 'B.action_id');
					$tables	= array(
						'main'	=> array(
							'table'		=> $this->payroll->tbl_payout_summary,
							'alias'		=> 'A'
						),
						'table1'=> array(
							'table'		=> $this->payroll->tbl_param_payout_status,
							'alias'		=> 'B',
							'type'		=> 'JOIN',
							'condition'	=> 'A.payout_status_id = B.payout_status_id'
						),
						'table2'=> array(
							'table'		=> $this->payroll->tbl_attendance_period_hdr,
							'alias'		=> 'C',
							'type'		=> 'JOIN',
							'condition'	=> 'A.attendance_period_hdr_id = C.attendance_period_hdr_id'
						),
						'table3'=> array(
							'table'		=> $this->payroll->tbl_param_payroll_types,
							'alias'		=> 'D',
							'type'		=> 'JOIN',
							'condition'	=> 'C.payroll_type_id = D.payroll_type_id'
						),
						'table4'=> array(
							'table'		=> $this->payroll->tbl_payout_summary_dates,
							'alias'		=> 'E',
							'type'		=> 'JOIN',
							'condition'	=> 'E.payout_summary_id = A.payroll_summary_id'
						)
					);
					$where		= array($this->get_hash_key('A.payroll_summary_id') => $id);
					$group_by 	= array('A.payout_status_id, B.payout_status_name, C.date_from, C.date_to, D.payroll_type_name, action_id');
					$summary	= $this->common->get_general_data_group_concat($fields, $tables, $where, FALSE, array(), $group_by);		
					
					$status_action_id = 0;
					if (isset($summary))
					{
						$summary['payroll_period'] = date('F d, Y',strtotime($summary["date_from"]))." - ".date('F d, Y',strtotime($summary["date_to"]));
						
						$payout_dates = $summary['payout_date'];
						$payout_dates = explode(',', $payout_dates);
						
						$payout_date = '';
						foreach($payout_dates as $pd)
						{
							if ( ! empty($payout_date))
								$payout_date .= ', ';
	
							$payout_date .= date('F d, Y',strtotime($pd));
						}
						
						$summary['payout_date'] = $payout_date;
						$status_action_id 		= $summary['action_id'];
					}

					$data['val'] = $summary;
					$payout_status_where = array();
					$payout_status_where['active_flag'] = YES;
					$payout_status_where['payout_flag'] = PAYOUT_STATUS_FLAG_ALL;
					$data['status'] = $this->common->get_general_data(array("*"), $this->payroll->tbl_param_payout_status, $payout_status_where);
					
					$permission_status = FALSE;
					if ( ! empty($status_action_id))
						$permission_status	= $this->permission->check_permission($this->permission_module, $status_action_id);
						
					$data['permission_status']	= $permission_status;
					RLog::info("Permission Change Status [{$data['permission_status']}] [{$this->permission_module}] [$status_action_id]");
					
					break;
	
				case 'tab_process_history':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$path = "main/payroll_general_tab/get_process_history_list/$action/$id/$token/$salt/$module/$gp_flag";
					$resources['datatable'][]	= array('table_id' => 'table_process_history', 'path' => $path, 'advanced_filter' => true);
				break;
	
			}
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}

		$this->load->view('general_payroll/tabs/'.$form, $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_payout_employee_list($action, $id, $token, $salt, $module, $gp_flag=YES)
	{
		try
		{
			$params = get_params();
			
			$aColumns = array('B.payroll_hdr_id', 'C.agency_employee_id', 'B.employee_id', 'B.employee_name', 'B.office_id', 'B.office_name', 'E.employment_status_name', 'A.payout_status_id', 'A.attendance_period_hdr_id');
			$bColumns = array('B.employee_name', 'B.office_name', 'E.employment_status_name');
			
			$return_list	= $this->payroll->get_payout_employee_list($aColumns, $bColumns, $id, $params);
			$employee_list	= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];			
			
			$field	= 'payroll_summary_id';
			$table	= $this->payroll->tbl_payout_header;
			$where	= array($this->get_hash_key('payroll_summary_id') => $id);			
			$iTotal	= $this->common->get_total_length($table, $field, $where);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			
			foreach ($employee_list as $aRow)
			{
				$row = array();
				
				$id2 			= $this->hash($aRow['payroll_hdr_id']);
				
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id2  . '/' . ACTION_VIEW  . '/' . $module, $salt2);
				$token_edit2 	= in_salt($id2  . '/' . ACTION_EDIT  . '/' . $module, $salt2);
				$token_delete2 	= in_salt($id2  . '/' . ACTION_DELETE  . '/' . $module, $salt2);
				
				$payout_status_id 	= $aRow['payout_status_id'];
				
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$token_view2."/".$salt2."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$token_edit2."/".$salt2."/".$module."/".$gp_flag;
				$url_delete 	= ACTION_DELETE."/".$id."/".$id2."/".$token_delete2."/".$salt2."/".$module."/".$gp_flag;
				// $delete_action	= 'content_delete("Employee in Payroll", "'.$url_delete.'")';
				/*SECURITY VARIABLES FOR QUICK LINKS*/
				$id_link    = $this->hash($aRow['employee_id']);
				$salt_link  = gen_salt();
				$token_link = in_salt($id_link  . '/' . ACTION_EDIT  . '/' . $module, $salt_link);				
				$url_link   = ACTION_EDIT."/".$id_link."/".$token_link."/".$salt_link."/".$module;

				$row[]      = $aRow['agency_employee_id'];
				$row[]      = $aRow['employee_name'];
				$row[]      = $aRow['office_name'];
				$row[]      = $aRow['employment_status_name'];

				
				$action = "<div class='table-actions table-links'>";
			
				if($permission_view)
					$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_personnel_process' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_personnel_process_init('".$url_view."')\"></a>";
				
				$office_permission = $this->permission->check_office_permission($aRow['office_id'], null,false,true,MODULE_PAYROLL);
					
				if($permission_edit && ($payout_status_id == PARAM_PAYOUT_STATUS_INITIAL) AND $office_permission == TRUE)
				{
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_personnel_process' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_personnel_process_init('".$url_edit."')\"></a>";
					// $action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
					$action .= "<a href='javascript:;' class='delete tooltipped'  data-tooltip='Delete' data-position='bottom' data-delay='50' onclick=\"delete_payroll_employee('".$url_delete."')\"></a>";
				}
			
				if ($gp_flag == YES)
				{
					$pds_action = ($action != ACTION_ADD) ? "modal_employee_pds_init('".$url_link."')":"notification_msg('error', 'Save record first.');";
					$comp_action = ($action != ACTION_ADD) ? "modal_employee_deduction_init('".$url_link."')":"notification_msg('error', 'Save record first.');";
					$ded_action = ($action != ACTION_ADD) ? "modal_employee_compensation_init('".$url_link."')":"notification_msg('error', 'Save record first.');";
					$print_mra_action = ($action != ACTION_ADD) ? "modal_employee_mra_init('".$url_link."/".$aRow['employee_id']."/".$aRow['attendance_period_hdr_id']."')":"notification_msg('error', 'Save record first.');";
		
					$action .= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_pds' data-tooltip='PDS' data-position='bottom' data-delay='50' onclick=\"".$pds_action."\"><i class='flaticon-user153 p-n'></i></a>";
					$action .= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_compensation' data-tooltip='Compensations' data-position='bottom' data-delay='50' onclick=\"".$ded_action."\"><i class='flaticon-portfolio32'></i></a>";
					$action .= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_deduction' data-tooltip='Deductions' data-position='bottom' data-delay='50' onclick=\"".$comp_action."\"><i class='flaticon-minus100'></i></a>";
					// $action .= "<a href='#!' class='md-trigger m-n' data-modal='modal_employee_mra' data-tooltip='Print MRA' data-position='bottom' data-delay='50' onclick=\"".$print_mra_action."\"><i class='flaticon-download157'></i></a>";
					$action .= "<a href='#!' class='tooltipped m-n' data-tooltip='View MRA' data-position='bottom' data-delay='50' onclick=\"print_mra('".$aRow['employee_id'] . "','" . $aRow['attendance_period_hdr_id'] ."')\"><i class='flaticon-weekly18'></i></a>";
				
				}
				
				$action .= "</div>";
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
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

		echo json_encode( $output );
	}
	
	public function get_payout_benefit_list($action, $id, $token, $salt, $module, $gp_flag=YES)
	{
		try
		{
			$params = get_params();	

			$aColumns       = array('D.compensation_id', 'D.compensation_code', 'D.compensation_name', 'A.payout_status_id');
			$bColumns       = array('D.compensation_code', 'D.compensation_name');
			/* For Advanced Filters */
			$cColumns = array('D-compensation_code', 'D-compensation_name');
			
			$return_list	= $this->payroll->get_payout_employee_benefit_list($aColumns, $bColumns, $cColumns, $id, $params);
			$benefits	= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];
			$iTotal	= $this->payroll->get_total_payout_detail_count($id, TRUE);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);

			foreach ($benefits as $aRow):
		
				$row = array();

				$compensation_id= $aRow['compensation_id'];
				
				$id2 			= $this->hash ($compensation_id);
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id2  . '/' . ACTION_VIEW  . '/' . $module, $salt2);
				$token_edit2 	= in_salt($id2  . '/' . ACTION_EDIT  . '/' . $module, $salt2);
				
				$payout_status_id 	= $aRow['payout_status_id'];
				
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$token_view2."/".$salt2."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$token_edit2."/".$salt2."/".$module;			
				
				$row[] = $aRow['compensation_code'];
				$row[] = $aRow['compensation_name'];

				$action = "<div class='table-actions'>";
					if($permission_view)
						$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_compensation_process' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_compensation_process_init('".$url_view."')\"></a>";
					if($permission_edit && ($payout_status_id == PARAM_PAYOUT_STATUS_INITIAL))
						$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_compensation_process' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_compensation_process_init('".$url_edit."')\"></a>";
				
				$action .= "</div>";
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
				
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
	
		echo json_encode( $output );
	}
	
	public function get_payout_deduction_list($action, $id, $token, $salt, $module, $gp_flag=YES)
	{

		try
		{
			$params = get_params();
			
			$aColumns       = array('D.deduction_id', 'D.deduction_code', 'D.deduction_name', 'A.payout_status_id');
			$bColumns       = array('D.deduction_code', 'D.deduction_name');
			/* For Advanced Filters */
			$cColumns = array('D-deduction_code', 'D-deduction_name');
			
			$return_list	= $this->payroll->get_payout_employee_deduction_list($aColumns, $bColumns, $cColumns, $id, $params);
			$deduction_types= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];			
			
			$iTotal	= $this->payroll->get_total_payout_detail_count($id, FALSE);
			RLog::info($iFilteredTotal);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);

			foreach ($deduction_types as $aRow):
				$row = array();
				
				
				$id2 			= $this->hash($aRow['deduction_id']);				
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id2  . '/' . ACTION_VIEW  . '/' . $module, $salt2);
				$token_edit2 	= in_salt($id2  . '/' . ACTION_EDIT  . '/' . $module, $salt2);
				
				$payout_status_id 	= $aRow['payout_status_id'];
				
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$token_view2."/".$salt2."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$token_edit2."/".$salt2."/".$module;
				
				$row[] = $aRow['deduction_code'];
				$row[] = $aRow['deduction_name'];
				
				$action = "<div class='table-actions'>";

				if($permission_view)
					$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_deduction_process' onclick=\"modal_deduction_process_init('".$url_view."')\" data-delay='50'></a>";
				if($permission_edit && ($payout_status_id == PARAM_PAYOUT_STATUS_INITIAL))
					$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_deduction_process' onclick=\"modal_deduction_process_init('".$url_edit."')\" data-delay='50'></a>";
				
				$action .= "</div>";
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
		
			
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

		echo json_encode( $output );
	}
	

	public function get_process_history_list($action, $id, $token, $salt, $module, $gp_flag=YES)
	{

		try
		{
			$params          = get_params();
			
			$aColumns        = array("CONCAT_WS(' ',B.first_name,B.last_name,B.ext_name) processed_by", "C.payout_status_name", 
								"DATE_FORMAT(A.hist_date, '%Y/%m/%d %H:%i:%s') hist_date", "A.remarks");
			$bColumns        = array("processed_by", 'payout_status_name', 'hist_date', 'remarks');
			$params          = get_params();

			$history	= $this->payroll->get_payout_history_list($aColumns, $bColumns, $id, $params);
			$iFilteredTotal	= count($history);			
			
			$iTotal['cnt']	= $iFilteredTotal; // TODO			
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);

			foreach ($history as $aRow)
			{
				$row = array();
				$row[] = $aRow['processed_by'];
				$row[] = $aRow['hist_date'];
				$row[] = $aRow['payout_status_name'];
				$row[] = $aRow['remarks'];
				$row[] = '';
				$output['aaData'][] = $row;
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

		echo json_encode( $output );
	}

	public function modal_personnel_process($action, $id, $id2, $token, $salt, $module, $gp_flag=YES)
	{
		try
		{
			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');
			
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['id2']			= $id2;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['payout_details'] = $this->get_personnel_modal_data_list($action, $id, $id2, $token, $salt, $module);
			$data['total_amount']	= number_format( (count($data['payout_details']) > 0 ? $data['payout_details'][0]['net_pay'] : 0.00), 2);


			$table = array(
				'main' => array(
					'table' => $this->payroll->tbl_payout_header,
					'alias' => 'A'
				),
				't1' => array(
					'table' => $this->payroll->tbl_payout_summary_dates,
					'alias' => 'B',
					'type' => 'LEFT JOIN',
					'condition' => 'A.payroll_summary_id=B.payout_summary_id'
				)
			);
			$where = array();
			$key = $this->get_hash_key('payroll_hdr_id');
			$where[$key] = $id2;
			$data['payout_header'] = $this->payroll->get_payroll_data(array('A.employee_name', 'GROUP_CONCAT(DISTINCT DATE_FORMAT(B.effective_date, \'%Y/%m/%d\')) AS effective_date'), $table, $where, FALSE, array(), array('A.employee_name'));
			
			$data['effective_date'] = explode(',', $data['payout_header']['effective_date']);
			
			$data['option_compensation']	= $this->_get_detail_flag_options(PAYOUT_DETAIL_TYPE_COMPENSATION, $id2);
			$data['option_deductions']		= $this->_get_detail_flag_options(PAYOUT_DETAIL_TYPE_DEDUCTION, $id2);
			$data['gp_flag']				= $gp_flag;
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
		$this->load->view('general_payroll/modals/modal_personnel_process', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function modal_compensation_process($action, $id, $id2, $token, $salt, $module)
	{
		$data = array();
		
		try
		{
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE, JS_NUMBER);
			
			if ($token != in_salt ($id2  . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}
			
			$path = "main/payroll_general_tab/get_compensation_modal_data_list/$action/$id/$id2/$token/$salt/$module";
			$resources['datatable'][]	= array('table_id' => 'table_compensation_process', 'path' => $path, 'advanced_filter' => TRUE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['id2']			= $id2;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['url']            = $path;
			
			$table = $this->payroll->tbl_param_compensations;
			$where = array();
			$key = $this->get_hash_key('compensation_id');
			$where[$key] = $id2;

			$compensation = $this->payroll->get_payroll_data(array('compensation_name'), $table, $where, FALSE);
			$data['compensation_name'] = $compensation['compensation_name'];
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		$this->load->view('general_payroll/modals/modal_compensation_process', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function modal_deduction_process($action, $id, $id2, $token, $salt, $module)
	{
		try
		{
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			
			if ($token != in_salt ($id2  . '/' . $action  . '/' . $module, $salt))
			{
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}
			
			$path = "main/payroll_general_tab/get_deduction_modal_data_list/$action/$id/$id2/$token/$salt/$module";
			$resources['datatable'][]	= array('table_id' => 'table_deduction_process', 'path' => $path, 'advanced_filter' => TRUE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['id2']			= $id2;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			$total_amount = $this->payroll->get_payout_total ($id, 'C.deduction_id', $id2);
			$data['total_amount']	= number_format($total_amount['amount'], 2);
			$table = $this->payroll->tbl_param_deductions;
			$where = array();
			$key = $this->get_hash_key('deduction_id');
			$where[$key] = $id2;
			$deduction = $this->payroll->get_payroll_data(array('deduction_name'), $table, $where, FALSE);
			$data['deduction_name'] = $deduction['deduction_name'];

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
		$this->load->view('general_payroll/modals/modal_deduction_process', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_personnel_modal_data_list($action, $id, $id2, $token, $salt, $module)
	{
		try
		{
			$params = get_params();
			
			$payout_details	= $this->payroll->get_employee_payout_details($id2);

			$rows = array();
			
			foreach ($payout_details as $aRow)
			{
				$id = $this->hash($aRow['payroll_dtl_id']);
				
				$dtl_flag = (isset($aRow['compensation_id']) ? PAYOUT_DETAIL_TYPE_COMPENSATION : PAYOUT_DETAIL_TYPE_DEDUCTION);
				$payout_dtl_name = (isset($aRow['compensation_id']) ? $aRow['compensation_name'] : $aRow['deduction_name']);
				$payout_remarks = (isset($aRow['compensation_id']) ? $aRow['remarks_compensation'] : $aRow['remarks_deduction']);
				
				$aRow['dtl_flag'] = $dtl_flag;
				$aRow['payout_dtl_id'] = $id;
				$aRow['payout_dtl_name'] = $payout_dtl_name;
				$aRow['remarks'] = $payout_remarks;
				
				$rows[] = $aRow;
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
		
		return $rows;
	}
	
	private function _get_detail_flag_options($details_flag, $payroll_hdr_id)
	{
		$options = "<option value=''></option>";
		try
		{
			$key_id = 'compensation_id';
			$key_name = 'compensation_name';
			if ($details_flag == PAYOUT_DETAIL_TYPE_DEDUCTION)
			{
				$key_id = 'deduction_id';
				$key_name = 'deduction_name';
			}			
			
			$return = $this->payroll->get_detail_flag_options($details_flag, $payroll_hdr_id);
			foreach ($return as $option)
			{
				$id = $option[$key_id];
				$name = $option[$key_name];
				$options .= "<option value='$id'>$name</option>";
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
		
		return $options;
	}
	
	

	public function get_compensation_modal_data_list($action, $id, $id2, $token, $salt, $module)
	{
		try
		{
			$params = get_params();
			
			$aColumns 		= array('B.payroll_hdr_id', 'C.payroll_dtl_id', 'E.agency_employee_id', 'B.employee_name', 'B.office_id', 'B.office_name', 'B.position_name', 
								'DATE_FORMAT(C.effective_date, \'%Y/%m/%d\') payout_date', 'IFNULL(C.amount, 0) amount', 'IFNULL(C.orig_amount, 0) orig_amount', 'IFNULL(C.less_amount, 0) less_amount', 
								'IFNULL(C.remarks_compensation, \'\') remarks_compensation');			
			$bColumns       = array('B.employee_name', 'B.office_name', 'B.position_name', 
								'payout_date', 'amount', 'orig_amount', 'less_amount');
			/* For Advanced Filters */
			$cColumns = array('E-agency_employee_id', 'B-employee_name', 'B-office_id', 'B-office_name', 'B-position_name', 'C-effective_date', 'C-amount', 'C-orig_amount', 'C-less_amount', 'C-remarks_compensation');
			
			$return_list    = $this->payroll->get_payout_employee_benefit_list($aColumns, $bColumns, $cColumns, $id, $params, $id2);
			$employee_list  = $return_list['data'];
			$iFilteredTotal = $return_list['filtered_length'];
			$iTotal         = $return_list['filtered_length']; // TODO
			/*
			$field	= 'payroll_hdr_id';
			$table	= $this->payroll->tbl_payout_detail;
			$where	= array($this->get_hash_key('payroll_hdr_id') => $id2);			
			$iTotal	= $this->common->get_total_length($table, $field, $where);
			*/
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			
			$total_amount = 0;
			foreach ($employee_list as $aRow)
			{
				$row = array();

				//payroll_hdr_id
				$hdr_id = $this->hash($aRow['payroll_hdr_id']);
				$dtl_id = $this->hash($aRow['payroll_dtl_id']);
				
				$row[]  = $aRow['agency_employee_id'];
				$row[]  = $aRow['employee_name'];
				$row[]  = $aRow['office_name'];
				$row[]  = $aRow['position_name'];
				$row[]  = '<center>' . $aRow['payout_date'] . '</center>';
				$row[] 	= '<div class="right">' . number_format($aRow['amount'], 2, '.', ',') . '</div>';
				$row[]  = '<div class="right">' . number_format($aRow['orig_amount'], 2, '.', ',') . '</div>';
				
				if ($action == ACTION_VIEW)
				{
					$row[] = '<div class="right">' . number_format($aRow['less_amount'], 2, '.', ',') . '</div>';
					$row[] = $aRow['remarks_compensation'];
				}
				else
				{
					$row[] = '<input type="text" style="text-align:right;" class="validate number" id="less_amount_'.$dtl_id.'" name="less_amounts[]" value="'.number_format($aRow['less_amount'],2).'" onchange="validate_less_amount(\''.$dtl_id.'\')"><input type="hidden" id="orig_amount_'.$dtl_id.'" name="orig_amounts[]" value="'.$aRow['orig_amount'].'"> ';
					$row[] = "<input type='text' name='remarks[]' value='".$aRow['remarks_compensation']."'>";
					
					$hidden_vals   = '<input type="hidden" name="payout_hdr_ids[]" value="'.$hdr_id.'">' 
					. '<input type ="hidden" name="payout_dtl_ids[]" value="'.$dtl_id.'">'				
					. '<input type ="hidden" name="payout_dtl_types[]" value="'.PAYOUT_DETAIL_TYPE_COMPENSATION.'">';					
				}

				$total_amount += $aRow['amount']; 
				
				$action_col   = "<div class='table-actions'>";
				$action_col   .= $hidden_vals;
				
				$action_col   .= "</div>";
				
				$row[] = $action_col;
					
				$output['aaData'][] = $row;
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

		echo json_encode( $output );		
		
	}

	public function get_deduction_modal_data_list($action, $id, $id2, $token, $salt, $module)
	{
		try
		{
			$params   = get_params();
			
			$aColumns = array('B.payroll_hdr_id', 'C.payroll_dtl_id', 'E.agency_employee_id', 'B.employee_name', 'B.office_id', 'B.office_name', 'B.position_name', 
			'DATE_FORMAT(C.effective_date, \'%Y/%m/%d\') payout_date', 'IFNULL(C.amount, 0) amount', 'IFNULL(C.orig_amount, 0) orig_amount', 'IFNULL(C.less_amount, 0) less_amount', 
			'IFNULL(C.remarks_deduction, \'\') remarks_deduction', 'D.priority_num');			
			$bColumns = array('B.employee_name', 'B.office_name', 'B.position_name', 
								'payout_date', 'amount', 'orig_amount', 'less_amount');
			/* For Advanced Filters */
			$cColumns = array('E-agency_employee_id', 'B-employee_name', 'B-office_id', 'B-office_name', 'B-position_name', 'C-effective_date', 'C-amount', 'C-orig_amount', 'C-less_amount', 'C-remarks_deduction');			
			
			$return_list	= $this->payroll->get_payout_employee_deduction_list($aColumns, $bColumns, $cColumns, $id, $params, $id2);
			$deduction_types= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];
			
			$iTotal['cnt']	= $iFilteredTotal["cnt"]; // TODO			

			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iFilteredTotal["cnt"],
				"iTotalDisplayRecords" => $iTotal['cnt'],
				"aaData" => array()
			);
			
			$total_amount = 0;
			foreach ($deduction_types as $aRow)
			{
				$row        = array();
				
				$hdr_id     = $this->hash($aRow['payroll_hdr_id']);
				$dtl_id     = $this->hash($aRow['payroll_dtl_id']);
				
				$salt       = gen_salt();
				$token_view = in_salt($dtl_id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit = in_salt($dtl_id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view   = ACTION_VIEW."/".$dtl_id ."/".$token_view."/".$salt."/".$module;
				$url_edit   = ACTION_EDIT."/".$dtl_id ."/".$token_edit."/".$salt."/".$module;


				$row[] = $aRow['agency_employee_id'];
				$row[] = $aRow['employee_name'];
				$row[] = $aRow['office_name'];
				$row[] = $aRow['position_name'];
				$row[] = '<center>' . $aRow['payout_date'] . '<center>';
				$row[] = '<div class="right">' . number_format($aRow['amount'], 2, '.', ',') . '</div>';
				$row[] = '<div style="text-align:right;">' . number_format($aRow['orig_amount'], 2, '.', ',') . '</div>';
				
				if ($action == ACTION_VIEW)
				{
					$row[] 	= '<div style="text-align:right;">' . number_format($aRow['less_amount'], 2, '.', ',') . '</div>';
					$row[] 	= $aRow['remarks_deduction']; 		
				}
				else
				{				
					$row[] = '<input type="text" style="text-align:right;" class="validate number" onchange="validate_less_amount(\''.$hdr_id.'\')" id="less_amount_'.$hdr_id.'" name="less_amounts[]" value="'.number_format($aRow['less_amount'],2).'"><input type="hidden" id="orig_amount_'.$hdr_id.'" name="orig_amounts[]" value="'.number_format($aRow['orig_amount'],2).'"> ';
					$row[] = "<input type='text' style='text-align:left;' name='remarks[]' value='".$aRow['remarks_deduction']."'>";
					
					$hidden_vals = '<input type="hidden" name="payout_hdr_ids[]" value="'.$hdr_id.'">' 
									. '<input type="hidden" name="payout_dtl_ids[]" value="'.$dtl_id.'">'				
									. '<input type="hidden" name="payout_dtl_types[]" value="'.PAYOUT_DETAIL_TYPE_DEDUCTION.'">';
				}

				$total_amount += $aRow['amount']; 
				
				$action_col = "<div class='table-actions'>";
				$action_col .= $hidden_vals;
			
				// if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				// $action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_personnel_process' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_personnel_process_init('".$url_view."')\"></a>";
				// $action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_personnel_process' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_personnel_process_init('".$url_edit."')\"></a>";
				
				$action_col .= "</div>";
				
				$row[] = $action_col;
					
				$output['aaData'][] = $row;
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

		echo json_encode( $output );
	}
	
	public function save_payout_details()
	{
		RLog::info('S: save_payout_details ...');
		
		$params = get_params();

		$msg 	= $this->lang->line('data_not_saved');
		$status = FALSE;
		
		try
		{
			if ($params['token'] != in_salt ($params['id2']  . '/' . $params['action']  . '/' . $params['module'], $params['salt']) )
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			
			Main_Model::beginTransaction();
			
			// get payroll_hdr_id
			$field = array('payroll_hdr_id', 'total_income', 'total_deductions', 'net_pay');
			$key   = $this->get_hash_key ('payroll_hdr_id');
			$where = array();
			$where[$key] = $params['id2'];
			$table = $this->payroll->tbl_payout_header;
			$payout_hdr_rec = $this->common->get_general_data($field, $table, $where, FALSE);
			if ( ! isset($payout_hdr_rec))
			{
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}			

			// update existing records
			$payout_dates     = $params['payout_dates'];
			$adjust_amounts   = $params['less_amounts'];
			$orig_amounts     = $params['orig_amounts'];
			$remarks          = $params['remarks'];
			$payout_dtl_ids   = $params['payout_dtl_ids'];
			$payout_dtl_flags = $params['payout_dtl_flags'];

			$total_income 		= 0.00;
			$total_deductions 	= 0.00;
			$net_pay			= 0.00;
			
			$orig_total_income     = $payout_hdr_rec['total_income'];
			$orig_total_deductions = $payout_hdr_rec['total_deductions'];			
			
			if (isset($payout_dtl_ids))
			{
				$table = $this->payroll->tbl_payout_details;
				for ($i=0; $i<count($payout_dtl_ids); $i++)
				{
					$fields = array();

					// $payout_dte = $payout_dates[$i];
					// $payout_dte = new DateTime($payout_dte);
					$fields['effective_date'] = format_date( $payout_dates[$i], 'Y-m-d');
					$fields['less_amount']    = $adjust_amounts[$i];
					$fields['amount']         = $orig_amounts[$i] + $adjust_amounts[$i];

					$remark_field = 'remarks_compensation';
					switch ($payout_dtl_flags[$i])
					{
						case PAYOUT_DETAIL_TYPE_COMPENSATION: 
							$total_income += $fields['amount'];
							$remark_field = 'remarks_compensation';
							break;
						case PAYOUT_DETAIL_TYPE_DEDUCTION: 
							$total_deductions += $fields['amount'];
							$remark_field     = 'remarks_deduction';
							break;

					}					
					$fields[$remark_field]	= $remarks[$i];
					
					$key = $this->get_hash_key('payroll_dtl_id');
					$where[$key] = $payout_dtl_ids[$i];
					
					$this->common->update_general_data($table, $fields, $where);
					
					
				}
			}
			
			// insert new records
			$new_payout_dates     = $params['new_payout_dates'];
			$new_orig_amounts     = $params['new_orig_amounts'];
			$new_less_amounts     = $params['new_less_amounts'];
			$new_payout_dtl_ids   = $params['new_payout_dtl_ids'];
			$new_payout_dtl_flags = $params['new_payout_dtl_flags'];
			$new_remarks          = $params['new_remarks'];
			
			if (isset($new_payout_dtl_ids) && count($new_payout_dtl_ids) > 0)
			{
				$table = $this->payroll->tbl_payout_details;
				
				for ($i=0; $i<count($new_payout_dtl_ids); $i++)
				{
					$fields                   = array();
					$fields['payroll_hdr_id'] = $payout_hdr_rec['payroll_hdr_id'];
					$fields['effective_date'] = format_date($new_payout_dates[$i], 'Y-m-d');
					$fields['orig_amount']    = $new_orig_amounts[$i];
					$fields['less_amount']    = $new_less_amounts[$i];
					$fields['amount']         = $new_orig_amounts[$i] + $new_less_amounts[$i];
					$fields['sys_flag']       = NO;
					
					$remark_field = 'remarks_compensation';
					switch ($params['dtl_type_flag'])
					{
						case PAYOUT_DETAIL_TYPE_COMPENSATION: 
							$fields['compensation_id'] 		= $new_payout_dtl_ids[$i];
							$fields['raw_compensation_id']	= $new_payout_dtl_ids[$i];
							$total_income              		+= $fields['amount'];
							$remark_field              		= 'remarks_compensation';
						
							break;
						case PAYOUT_DETAIL_TYPE_DEDUCTION: 
							$fields['deduction_id'] 	= $new_payout_dtl_ids[$i];
							$fields['raw_deduction_id']	= $new_payout_dtl_ids[$i];
							$total_deductions       	+= $fields['amount'];
							$remark_field           	= 'remarks_deduction';
							
							break;
					}

					$fields[$remark_field] = $new_remarks[$i];
					
					$this->common->insert_general_data($table, $fields);
				}
			}
			
			
			$action           = $params['action'];
			$id               = $params['id'];
			$id2              = $params['id2'];
			$salt             = $params['salt'];
			$token            = $params['token'];
			$module           = $params['module'];
			$payout_details   = $this->get_personnel_modal_data_list($action, $id, $id2, $token, $salt, $module);
			$total_deductions = 0;
			$total_income     = 0;

 			foreach ($payout_details as $key => $p) {
 				if($p['dtl_flag'] == PAYOUT_DETAIL_TYPE_COMPENSATION) +$total_income += +$p['amount'];
 				else +$total_deductions += +$p['amount'];
 			}
			// update header total amounts
			$net_pay                    = $total_income - $total_deductions;
			$table                      = $this->payroll->tbl_payout_header;
			$fields                     = array();
			$where                      = array();
			$fields['total_income']     = $total_income;
			$fields['total_deductions'] = $total_deductions;
			$fields['net_pay']          = $net_pay;
			$where['payroll_hdr_id']    = $payout_hdr_rec['payroll_hdr_id'];

			$this->common->update_general_data($table, $fields, $where);

			Main_Model::commit();
			
			$msg 	= $this->lang->line('data_saved');
			$status = TRUE;

		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			$msg 	= $this->lang->line($message);
			$status = FALSE;			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			$msg 	= $message;
			$status = FALSE;
			
		}
		
		$info['msg']		= $msg;
		$info['status']		= $status;
		echo json_encode($info);
		
		RLog::info('E: save_payout_details ...');
	}
	
	public function save_payout_detail_by_type()
	{
		RLog::info('S: save_payout_detail_by_type ...');
		
		$params = get_params();
		
		RLog::info($params);

		$msg 	= $this->lang->line('data_not_saved');
		$status = FALSE;
		
		try
		{
			if ($params['token'] != in_salt ($params['id2']  . '/' . $params['action']  . '/' . $params['module'], $params['salt']) )
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			
			Main_Model::beginTransaction();

			$payout_hdr_ids   = $params['payout_hdr_ids'];
			$payout_dtl_ids   = $params['payout_dtl_ids'];
			$payout_dtl_types = $params['payout_dtl_types'];
			$adjust_amounts     = $params['less_amounts'];
			$orig_amounts     = $params['orig_amounts'];
			$remarks          = $params['remarks'];
			

			$total_compensation_arr	= array(); 
			$total_deduction_arr	= array();
			$net_pay				= 0.00;
			
			if ( ! empty($payout_hdr_ids))
			{
				for ($i=0; $i<count($payout_hdr_ids); $i++)
				{
					$hdr_id	= $payout_hdr_ids[$i];
					
					RLog::info("Payroll_tab.save_payout_details_by_type [$hdr_id] [$i]");
					
					$table 	= $this->common->tbl_payout_details;
					$where	= array();
					$key    = $this->get_hash_key('payroll_dtl_id');
					if ( ! empty ($payout_dtl_ids[$i]))
					{
						$fields                = array();
						$adjust_amount         = (empty($adjust_amounts[$i]) ? 0.00 : str_replace(',', '', $adjust_amounts[$i]));
						$orig_amount           = (empty($orig_amounts[$i]) ? 0.00 : str_replace(',', '', $orig_amounts[$i]));
						$fields['amount']      = $orig_amount + $adjust_amount;
						$fields['less_amount'] = $adjust_amount;
						
						$remark_field = 'remarks_compensation';
						if ($payout_dtl_types[$i] == PAYOUT_DETAIL_TYPE_COMPENSATION)
						{
							$total_compensation_arr[$hdr_id] = $fields['amount'];
							$remark_field = 'remarks_compensation';
						}
						if ($payout_dtl_types[$i] == PAYOUT_DETAIL_TYPE_DEDUCTION)
						{
							$total_deduction_arr[$hdr_id] = $fields['amount'];
							$remark_field = 'remarks_deduction';
						}

						$fields[$remark_field]	= (empty($remarks[$i]) ? NULL : $remarks[$i]);
						$where[$key] 			= $payout_dtl_ids[$i];
						$this->common->update_general_data($table, $fields, $where);
					}
					
					// update header total amounts
					$this->payroll_process->update_payroll_hdr_pay($hdr_id);
				}				
			}
			Main_Model::commit();
			
			$msg 	= $this->lang->line('data_saved');
			$status = TRUE;

		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			$msg 	= $message;
			$status = FALSE;
			
		}
		
		$info['msg']		= $msg;
		$info['status']		= $status;
		echo json_encode($info);
		
		RLog::info('E: save_payout_detail_by_type ...');
	}	
	
	public function process_status()
	{
		try
		{
			$status = FALSE;
			$msg	= $this->lang->line('data_not_saved');

			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ) or EMPTY ( $params ['module'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			// SERVER VALIDATION
			$valid_data         = $this->_validate_status_data($params);
			
			
			//GET THE CURRENT DETAIL FIRST BEFORE UPDATING THE RECORD 
			$where					= array();
			$where['id']			= $params['id'];
			$payroll_summary		= $this->payroll->get_payroll_record($where);
			$payout_remittance 		= $this->payroll_process->get_payroll_remittance(array($payroll_summary['payroll_summary_id']));
			if(!EMPTY($payout_remittance) && $valid_data['payout_status_id'] != PAYOUT_STATUS_APPROVED) {
				$msg	= $this->lang->line('err_change_payroll_status');
				$status = FALSE;
// 				Main_Model::rollback();
			}
			else
			{
				// BEGIN TRANSACTION
				Main_Model::beginTransaction();
				
				$prev_detail[]     		= array($payroll_summary['payout_status_id']);
				
				// CHECK IF USER HAS PERMISSION TO CHANGE STATUS
				$status_action_id 	= $payroll_summary['action_id'];
				$permission_status 	= FALSE;
				if ( ! empty($status_action_id))
					$permission_status	= $this->permission->check_permission($params['module'], $status_action_id);
				RLog::info("Permission Process Status [$permission_status] [{$params['module']}] [$status_action_id]");
				
				if ($permission_status)
				{
					// UPDATE
					$table              = $this->payroll->tbl_payout_summary;
					$audit_table[]      = $table;
					$audit_schema[]     = Base_Model::$schema_core;
					$audit_module       = $params['module'];			
					$audit_action[]    	= AUDIT_UPDATE;
		
					// GET THE EMPLOYEE ID OF THE CURRENT USER
					$where                                     = array();
					$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
					$employee_info                             = $this->common->get_general_data(array('employee_id'), $this->common->tbl_employee_personal_info, $where, FALSE);
		
					// UPDATE DATA IN PAYOUT SUMMARY
					$fields = array();
					$fields['payout_status_id'] = $valid_data['payout_status_id'];
					$where						= array();
					$where['payroll_summary_id']= $payroll_summary['payroll_summary_id'];
					$this->common->update_general_data($table, $fields, $where);
					
					
					// INSERT TO FORM 2316 table
					$payout_dates = explode(',', $payroll_summary['effective_dates']);
					//$this->_process_approval($payroll_summary, $payout_dates, $valid_data['payout_status_id']);
					$this->payroll_common->process_payroll_approval($payroll_summary, $payout_dates, $valid_data['payout_status_id'], YES);
		
					// POPULATE DATA FOR PAYOUT HISTORY
					$valid_data['payout_summary_id']= $payroll_summary['payroll_summary_id'];
					$valid_data['hist_date']        = date('Y-m-d H:i:s');
					$valid_data['action_id']        = ACTION_PROCESS;
					$valid_data['employee_id']      = $employee_info['employee_id'];
					
					// INSERT DATA TO PAYOUT HISTORY
					$this->common->insert_general_data($this->common->tbl_payout_history, $valid_data);
					//MESSAGE ALERT
					$msg 		= $this->lang->line('data_updated');
					
					// GET THE DETAIL AFTER UPDATING THE RECORD
					$curr_detail[]  = array($fields);
					
					// INSERT TO NOTIFICATIONS
					$this->payroll_common->insert_payout_notifications($params ['module'], $payroll_summary['payroll_summary_id'], 
							$valid_data['payout_status_id'], $payroll_summary['processed_by']);
					
					// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
					$activity 		= "%s has been updated";
		
					$activity = sprintf($activity, 'General Payroll Status');
			
					// LOG AUDIT TRAIL
					$this->audit_trail->log_audit_trail(
						$activity, 
						$audit_module, 
						$prev_detail, 
						$curr_detail, 
						$audit_action, 
						$audit_table,
						$audit_schema
					);
					
					Main_Model::commit();
					$status = TRUE;		
				}
				else
				{
					$msg 	= $this->lang->line('err_unauthorized_process');
					$status = FALSE;
					Main_Model::rollback();
				}
			}
			
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($msg);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($msg);
		}
		$data['msg'] 	= $msg;
		$data['status'] = $status;
		echo json_encode( $data );
	}
	private function _validate_status_data($params)
	{
		$fields                         = array();
		
		$fields['payout_status_id']  = "Payout Status";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_status_input ($params);
	}

	private function _validate_status_input($params) 
	{
		try {
			
			$validation ['payout_status_id'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Payout Status',
					'max_len' 				=> 10 
			);

			$validation ['remarks'] 	= array (
					'data_type' 			=> 'string',
					'name'					=> 'Remarks',
					'max_len' 				=> 255 
			);

			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	//private function _process_approval($tax_table_flag, $payout_dates, $payroll_summary_id, $attendance_period_hdr_id, $status_id, $payout_type_flag=PAYOUT_TYPE_FLAG_REGULAR)
	private function _process_approval_old($payroll_summary, $payout_dates, $status_id)
	{
		try
		{
			$tax_table_flag 			= $payroll_summary['tax_table_flag'];
			$payroll_summary_id			= $payroll_summary['payroll_summary_id'];
			$attendance_period_hdr_id	= $payroll_summary['attendance_period_hdr_id']; 
			$payout_type_flag			= $payroll_summary['payout_type_flag'];
			
			$payout_count 		= count($payout_dates);
			$payout_date_from	= $payout_dates[0];
			$payout_date_to 	= $payout_dates[$payout_count-1];
			
			$field = array('payout_status_id');
			$table = $this->common->tbl_param_payout_status;
			$where = array('approved_flag' => 1, 'payout_flag' => 'A');
			$stat_approved = $this->common->get_general_data($field, $table, $where, FALSE);
			$stat_approved = (isset($stat_approved['payout_status_id']) ? $stat_approved['payout_status_id'] : NULL);
			RLog::info("-- _process_approval [$tax_table_flag] [$payout_date_from] [$payroll_summary_id] [$attendance_period_hdr_id] [$status_id] [$stat_approved]");
			
			if (isset($stat_approved) && $stat_approved === $status_id)
			{
				if ($payout_type_flag == PAYOUT_TYPE_FLAG_REGULAR)
				{
					// UPDATE DEDUCTION PAID COUNT
					$this->payroll_process->update_deduction_paid_count($payroll_summary_id, $payout_date_to);
				}
				
				// RE-COMPUTE FORM 2316
				$this->form2316->construct_form_2316($tax_table_flag, 
					$payout_date_to, 
					NULL, // unsaved deductions
					$payroll_summary_id, 
					NULL, // $included_employees 
					NULL, // $sys_param_stat_approved
					TRUE, // $save
					TRUE, // $project_tax
					FALSE // $monthly_only
					);
				
				$new_period_status_id = ATTENDANCE_PERIOD_COMPLETED;
			} 
			else
			{
				$new_period_status_id = ATTENDANCE_PERIOD_PROCESSED;				
			}
			
			// UPDATE ATTENDANCE PERIOD STATUS TO COMPLETED
			$this->payroll_process->update_attendance_period($attendance_period_hdr_id, $new_period_status_id);
		} catch (Exception $e) {
			throw $e;
		}			
	}

											
	public function delete_payroll_employee($action, $id, $id2, $token, $salt, $module, $gp_flag=YES)
	{
		try
		{
			$info	= array();
			$status = FALSE;
			$msg 	= $this->lang->line('data_not_saved');
			
			//$chk_salt = in_salt(id2 . '/' . $action  . '/' . $module , $salt);
			//RLog::info("T[$token] ID[$id2] A[$action] M[$module] S[$salt] [$chk_salt]");
			if ($token != in_salt($id2 . '/' . $action  . '/' . $module , $salt))
				throw new Exception($this->lang->line('err_invalid_request'));			

			Main_Model::beginTransaction();
			
			// PREPARE DATA FOR AUDIT TRAIL
			$table              = $this->common->tbl_payout_header;
			$audit_table[]      = $table;
			$audit_schema[]     = Base_Model::$schema_core;
			$audit_module       = $this->permission_module;
			// GET THE EMPLOYEE ID OF THE CURRENT USER
			$where                                     = array();
			$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
			$employee_info                             = $this->common->get_general_data(array('employee_id'), $this->common->tbl_employee_personal_info, $where, FALSE);

			
			// S: PROCESS DELETE EMPLOYEE REQUEST HERE
			$removed_hdr_rec = $this->_remove_employee_from_payroll($id2);
			
			if ($gp_flag !== YES)
			{
				// for Special Payroll - Monetize
				$chk_field = array('payroll_summary_id', 'monetize_flag');
				$key   = $this->get_hash_key('payroll_summary_id');
				$where = array();
				$where[$key] = $id;
				$table = $this->common->tbl_payout_summary;
				$payout_summary_rec = $this->common->get_general_data($chk_field, $table, $where, FALSE);
				
				if ($payout_summary_rec['monetize_flag'] === YES)
					$this->payroll_process->update_monetize_leave_details($payout_summary_rec['payroll_summary_id'], NO, NULL, TRUE);
			}
			// E: PROCESS DELETE EMPLOYEE REQUEST HERE
			
			// SET DETAIL FOR AUDIT TRAIL
			$audit_table = array();
			$audit_action= array();
			$audit_action[] = AUDIT_PROCESS;
			$prev_detail[] = $removed_hdr_rec;
			$curr_detail[] = $removed_hdr_rec;
			
			//RLog::error($prev_detail);
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been removed from payout";
			$activity = sprintf($activity, $removed_hdr_rec['employee_name']);

			$this->payroll_common->log_audit_trail($activity, $audit_module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);		
			
			Main_Model::commit();
			$msg 	= $this->lang->line('data_deleted');
			$status = TRUE;
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$pdo_err = get_pdo_message($e->errorInfo);
			if ( ! empty($pdo_err))
				$msg 	= sprintf($this->lang->line($pdo_err[0]), $pdo_err[1]);
				
			if (empty($msg))
				$msg 	= $e->getMessage();
				
			$status	= FALSE;
			RLog::error($msg);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg 	= $e->getMessage();
			$status	= FALSE;
			RLog::error($msg);
		}
		// $post_data = array(
		// 		'employee_id' => $employee_id,
		// 		'module'      => $module
	 // 	);
		$data['msg']		= $msg;
		$data['status']		= $status;
		
		echo json_encode( $data );
	}
	
	private function _remove_employee_from_payroll($hdr_id)
	{
		try
		{
			$field = array('payroll_hdr_id', 'payroll_summary_id', 'employee_id', 'employee_name');
			$key   = $this->get_hash_key('payroll_hdr_id');
			$where = array();
			$where[$key] = $hdr_id;
			$table = $this->payroll->tbl_payout_header;
			$payout_hdr_rec = $this->common->get_general_data($field, $table, $where, FALSE);
			if (empty($payout_hdr_rec))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );			

			$payroll_hdr_id 	= $payout_hdr_rec['payroll_hdr_id'];
			$payroll_summary_id	= $payout_hdr_rec['payroll_summary_id'];
			$payroll_employee_id= $payout_hdr_rec['employee_id'];
			
			// payout details
			$table = $this->common->tbl_payout_details;
			$where = array('payroll_hdr_id' => $payroll_hdr_id);
			$this->common->delete_general_data($table, $where);
			
			// payout header
			$table = $this->common->tbl_payout_header;
			$where = array('payroll_hdr_id' => $payroll_hdr_id);
			$this->common->delete_general_data($table, $where);

			// update payout_employee
			$table = $this->common->tbl_payout_employee;
			$fields= array('included_flag' => NO);
			$where = array('payroll_summary_id' => $payroll_summary_id, 'employee_id' => $payroll_employee_id);
			$this->common->update_general_data($table, $fields, $where);

			return $payout_hdr_rec;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function delete_payout_details()
	{
		try
		{
			$params = get_params();
			$info	= array();
			$status = FALSE;
			$msg 	= $this->lang->line('data_not_saved');
			
			if (EMPTY($params['payroll_dtl_id']))
				throw new Exception($this->lang->line('err_invalid_request'));			

			Main_Model::beginTransaction();
			
			$table                   = $this->common->tbl_payout_details;			
			$where                   = array();
			$where['payroll_dtl_id'] = $params['payroll_dtl_id'];
			
			$prev_detail[]           = $this->common->get_general_data(array('*'), $table, $where, TRUE);	
			$this->common->delete_general_data($table, $where);
			
			
			$audit_table[]  = $table;
			$audit_schema[] = DB_MAIN;
			$audit_action[] = AUDIT_DELETE;
			$audit_module   = $this->permission_module;
			
			$activity = "Compensation/Deduction has been removed from payout";

			$this->payroll_common->log_audit_trail($activity, $audit_module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);		
			
			Main_Model::commit();
			$msg 	= $this->lang->line('data_deleted');
			$status = TRUE;
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$pdo_err = get_pdo_message($e->errorInfo);
			if ( ! empty($pdo_err))
				$msg 	= sprintf($this->lang->line($pdo_err[0]), $pdo_err[1]);
				
			if (empty($msg))
				$msg 	= $e->getMessage();
				
			$status	= FALSE;
			RLog::error($msg);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg 	= $e->getMessage();
			$status	= FALSE;
			RLog::error($msg);
		}
		$data['msg']		= $msg;
		$data['status']		= $status;
		
		echo json_encode( $data );
	}
}


/* End of file Payroll_general_tab.php */
/* Location: ./application/modules/main/controllers/Payroll_general_tab.php */