<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll_tab extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_SPECIAL_PAYROLL;
	private $form2316;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('special_payroll_model', 'sppayroll');
		$this->load->model('payroll_model', 'payroll');
		$this->load->model('common_model', 'common');
		
		$this->permission_module = MODULE_PAYROLL_SPECIAL_PAYROLL;
		$this->form2316 = modules::load('main/payroll_form_2316');
	}

	public function get_tab($form, $action, $id, $token, $salt, $module)
	{
		try
		{
			$resources['load_css'] 	= array(CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_LABELAUTY);
			
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
	
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['token']			= $token;
			$data['salt']			= $salt;
			$data['module']			= $module;			
			
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
			
			RLog::info('Special Payroll Tab: ' . $form);
			
			$view_root = 'special_payroll/tabs/';
			
			switch ($form)
			{
				case 'tab_process_status':
					$resources['load_css'] 	= array(CSS_SELECTIZE);
					$resources['load_js'] 	= array(JS_SELECTIZE);
					
					$fields	= array('a.compensation_id', 'c.compensation_name', 
								'a.tenure_period_start_date', 'a.tenure_period_end_date',
								'a.rating_period_start_date', 'a.rating_period_end_date',
								'GROUP_CONCAT(b.effective_date separator \',\') payout_date',
								'a.payout_status_id', 'd.payout_status_name');
					$tables	= array(
						'main'	=> array(
							'table'		=> $this->payroll->tbl_payout_summary,
							'alias'		=> 'a'
						),
						'table1'=> array(
							'table'		=> $this->payroll->tbl_payout_summary_dates,
							'alias'		=> 'b',
							'type'		=> 'JOIN',
							'condition'	=> 'b.payout_summary_id = a.payroll_summary_id'
						),
						'table2'=> array(
							'table'		=> $this->payroll->tbl_param_compensations,
							'alias'		=> 'c',
							'type'		=> 'JOIN',
							'condition'	=> 'c.compensation_id = a.compensation_id'
						),
						'table3'=> array(
							'table'		=> $this->payroll->tbl_param_payout_status,
							'alias'		=> 'd',
							'type'		=> 'JOIN',
							'condition'	=> 'd.payout_status_id = a.payout_status_id'
						)
					);
					$where		= array($this->get_hash_key('a.payroll_summary_id') => $id);
					$group_by 	= array('a.payroll_summary_id');
					$summary	= $this->common->get_general_data($fields, $tables, $where, FALSE);		
					
					if (isset($summary))
					{
						if ( ! empty($summary["tenure_period_start_date"]))
							$summary['tenure_period_start_date']= date('F d, Y',strtotime($summary["tenure_period_start_date"]));
						if ( ! empty($summary["tenure_period_end_date"]))
							$summary['tenure_period_end_date']	= date('F d, Y',strtotime($summary["tenure_period_end_date"]));
						if ( ! empty($summary["rating_period_start_date"]))
							$summary['rating_period_start_date']= date('F d, Y',strtotime($summary["rating_period_start_date"]));
						if ( ! empty($summary["rating_period_end_date"]))
							$summary['rating_period_end_date']	= date('F d, Y',strtotime($summary["rating_period_end_date"]));
						
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
					}

					$data['val'] = $summary;
					$payout_status_where = array();
					$payout_status_where['active_flag'] = YES;
					$payout_status_where['payout_flag'] = PAYOUT_STATUS_FLAG_ALL;
					$data['status'] = $this->common->get_general_data(array("*"), $this->payroll->tbl_param_payout_status, $payout_status_where);
					
					break;
	
				case 'tab_process_history':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$path = "main/special_payroll_tab/get_process_history_list/$action/$id/$token/$salt/$module";
					$resources['datatable'][]	= array('table_id' => 'table_process_history', 'path' => $path, 'advanced_filter' => true);
				break;
	
			}

			$this->load->view($view_root.$form, $data);
			$this->load_resources->get_resource($resources);			
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}

	}

	public function get_process_special_payroll($action, $payroll_summary_id, $token, $salt, $module)
	{
		try
		{
			$params         = get_params();
			
			$aColumns       = array('a.payroll_hdr_id', 'a.employee_name', 'a.office_name', 'a.position_name', 'a.tenure_in_months', 
								'a.perf_rating', 'a.basic_amount', 'b.base_rate',
								'GROUP_CONCAT(IF(b.compensation_id IS NOT NULL, b.payroll_dtl_id, NULL) separator \',\') compensation_id', 
								'GROUP_CONCAT(IF(b.deduction_id IS NOT NULL, b.payroll_dtl_id, NULL) separator \',\') deduction_id',
								'SUM(IF(b.compensation_id IS NOT NULL, b.amount, 0)) compensation_amount',
								'SUM(IF(b.deduction_id IS NOT NULL, b.amount, 0)) deduction_amount');
			$bColumns       = array('employee_name', 'office_name', 'position_name', 'tenure_in_months', 'perf_rating', 'basic_amount', 'base_rate', 
								'compensation_amount', 'deduction_amount');
			
			$return_list    = $this->sppayroll->get_special_payroll_personnel_list($payroll_summary_id, $aColumns, $bColumns, $params);
			$payroll_list   = $return_list['data'];
			$iFilteredTotal = $return_list['filtered_length'];
			
			$field          = 'payroll_summary_id';
			$table          = $this->common->tbl_payout_summary;
			$key            = $this->get_hash_key('payroll_summary_id');
			$where[$key]    = $payroll_summary_id;			
			$iTotal         = $this->common->get_total_length($table, $field, $where);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"permission_add"       => $this->permission->check_permission($this->permission_module, ACTION_ADD),
				"aaData"               => array()
			);

			$permission_view 	= $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit 	= $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete 	= $this->permission->check_permission($this->permission_module, ACTION_DELETE);

			$cnt = 0;
			foreach ($payroll_list as $aRow)
			{
				$cnt++;
				$row    = array();
				$action = "";
				
				$payroll_hdr_id		= $this->hash($aRow['payroll_hdr_id']);
				$compensation_id	= $this->hash($aRow['compensation_id']);
				$deduction_id		= $this->hash($aRow['deduction_id']);
				
				$salt         = gen_salt();
				$token_view   = in_salt($payroll_hdr_id  . '/' . ACTION_VIEW  . '/' . $this->permission_module, $salt);
				$token_edit   = in_salt($payroll_hdr_id  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt);
				$token_delete = in_salt($payroll_hdr_id . '/' . ACTION_DELETE  . '/' . $this->permission_module, $salt);
				
				$url_view     = ACTION_VIEW."/".$payroll_hdr_id ."/".$token_view."/".$salt."/".$this->permission_module;
				$url_edit     = ACTION_EDIT."/".$payroll_hdr_id ."/".$token_edit."/".$salt."/".$this->permission_module."/".$payroll_summary_id;
				$url_delete   = ACTION_DELETE."/".$payroll_hdr_id ."/".$token_delete."/".$salt."/".$this->permission_module;
				
				$row[] = $aRow['employee_name'];
				$row[] = $aRow['office_name'];
				$row[] = $aRow['position_name'];
				$row[] = $aRow['tenure_in_months'];
				$row[] = $aRow['perf_rating'];
				$row[] = '<p class="m-n right">' . number_format($aRow['basic_amount'],2) . '</p>';
				$row[] = $aRow['base_rate'];
				//$row[] = '<p class="m-n right">' . number_format($aRow['compensation_amount'],2) . '</p>';
				//$row[] = '<p class="m-n right">' . number_format($aRow['deduction_amount'],2) . '</p>';
				$row[] = '<input type=\'text\' style=\'text-align:right;\' class=\'validate number\' name=\'compensation_amounts[]\' value='.number_format($aRow['compensation_amount'],2, '.', '').'> ';
				$row[] = '<input type=\'text\' style=\'text-align:right;\' class=\'validate number\' name=\'deduction_amounts[]\' value='.number_format($aRow['deduction_amount'],2, '.', '').'> ';
				
				$hidden_vals = '<input type="hidden" name="payout_hdr_ids[]" value="'.$payroll_hdr_id.'"> ' .
					'<input type="hidden" name="compensation_ids[]" value="'.$compensation_id.'"> ' .
					'<input type="hidden" name="deduction_ids[]" value="'.$deduction_id.'"> ';
				
				if ($permission_delete)
				{
					$action     = "<div class='table-actions'>";
					$action		= $hidden_vals;
					//$action        .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_edit_special_payroll' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_edit_special_payroll_init('".$url_view."')\"></a>";
					//$action        .= "<a href='javascript:;' class='delete tooltipped md-trigger' data-tooltip='Delete' data-modal='modal_edit_special_payroll' data-position='bottom' data-delay='50'></a>";
					//$delete_action = 'content_delete("document", "'.$url_delete.'")';
					$action        .= '</div>';
				}
					
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
	
	public function get_payout_employee_list($action, $id, $token, $salt, $module)
	{
		try
		{
			$params = get_params();
			
			$aColumns = array('B.payroll_hdr_id', 'C.agency_employee_id', 'B.employee_id', 'B.employee_name', 'B.office_name', 'E.employment_status_name');
			$bColumns = array('C.agency_employee_id', 'B.employee_name', 'B.office_name', "E.employment_status_name");
			
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
			
			foreach ($employee_list as $aRow):
				$row = array();
				
				$id2 			= $this->hash($aRow['payroll_hdr_id']);
				
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id2  . '/' . ACTION_VIEW  . '/' . $module, $salt2);
				$token_edit2 	= in_salt($id2  . '/' . ACTION_EDIT  . '/' . $module, $salt2);
				
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$token_view2."/".$salt2."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$token_edit2."/".$salt2."/".$module;

				$row[]      = $aRow['agency_employee_id'];
				$row[]      = $aRow['employee_name'];
				$row[]      = $aRow['office_name'];
				$row[]      = $aRow['employment_status_name'];

				
				$action = "<div class='table-actions'>";
			
				if($permission_view)
					$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_personnel_process' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_personnel_process_init('".$url_view."')\"></a>";
				if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_personnel_process' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_personnel_process_init('".$url_edit."')\"></a>";
				
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
	
	public function get_payout_benefit_list($action, $id, $token, $salt, $module)
	{
		try
		{
			$params = get_params();	

			$aColumns       = array('D.compensation_id', 'D.compensation_code', 'D.compensation_name');
			$bColumns       = array('D.compensation_code', 'D.compensation_name');
			/* For Advanced Filters */
			$cColumns = array('D-compensation_code', 'D-compensation_name');
			
			$return_list	= $this->payroll->get_payout_employee_benefit_list($aColumns, $bColumns, $cColumns, $id, $params);
			$benefits	= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];
			
			$iTotal['cnt']	= $iFilteredTotal["cnt"]; // TODO
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);

			foreach ($benefits as $aRow):
		
				$row = array();

				$compensation_id= $aRow['compensation_id'];
				
				$id2 			= $this->hash ($compensation_id);
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id2  . '/' . ACTION_VIEW  . '/' . $this->permission_module, $salt2);
				$token_edit2 	= in_salt($id2  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt2);
				
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$token_view2."/".$salt2."/".$this->permission_module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$token_edit2."/".$salt2."/".$this->permission_module;			
				
				$row[] = $aRow['compensation_code'];
				$row[] = $aRow['compensation_name'];

				$action = "<div class='table-actions'>";

					$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_compensation_process' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_compensation_process_init('".$url_view."')\"></a>";
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
	
	public function get_payout_deduction_list($action, $id, $token, $salt, $module)
	{

		try
		{
			$params = get_params();
			
			$aColumns       = array('D.deduction_id', 'D.deduction_code', 'D.deduction_name');
			$bColumns       = array('D.deduction_code', 'D.deduction_name');
			/* For Advanced Filters */
			$cColumns = array('D-deduction_code', 'D-deduction_name');
			
			$return_list	= $this->payroll->get_payout_employee_deduction_list($aColumns, $bColumns, $cColumns, $id, $params);
			$deduction_types= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];			
			
			$iTotal['cnt']	= $iFilteredTotal["cnt"]; // TODO			
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);

			foreach ($deduction_types as $aRow):
				$row = array();
				
				
				$id2 			= $this->hash($aRow['deduction_id']);				
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id2  . '/' . ACTION_VIEW  . '/' . $this->permission_module, $salt2);
				$token_edit2 	= in_salt($id2  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt2);
				
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$token_view2."/".$salt2."/".$this->permission_module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$token_edit2."/".$salt2."/".$this->permission_module;
				
				$row[] = $aRow['deduction_code'];
				$row[] = $aRow['deduction_name'];
				
				$action = "<div class='table-actions'>";

				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_deduction_process' onclick=\"modal_deduction_process_init('".$url_view."')\" data-delay='50'></a>";
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
	

	public function get_process_history_list($action, $id, $token, $salt, $module)
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

	public function process_status()
	{
		try
		{
			$status = 0;
			$msg	= $this->lang->line('data_not_saved');

			$params	= get_params();
			
			RLog::info('S: Special_payroll_tab.process_status');
				RLog::info($params);
			RLog::info('E: Special_payroll_tab.process_status');

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

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table              = $this->payroll->tbl_payout_summary;
			$audit_table[]      = $table;
			$audit_schema[]     = Base_Model::$schema_core;
			$audit_module       = $params['module'];

			// UPDATE 
			$audit_action[]    = AUDIT_UPDATE;

			// GET THE EMPLOYEE ID OF THE CURRENT USER
			$where                                     = array();
			$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
			$employee_info                             = $this->common->get_general_data(array('employee_id'), $this->common->tbl_employee_personal_info, $where, FALSE);

			//WHERE 
			$where					= array();
			$where['id']			= $params['id'];
			$payroll_summary		= $this->payroll->get_payroll_record($where);

			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]     		= array($payroll_summary['payout_status_id']);
			
			// CHECK IF USER HAS PERMISSION TO CHANGE STATUS
			$status_action_id 	= $payroll_summary['action_id'];
			$permission_status 	= FALSE;
			if ( ! empty($status_action_id))
				$permission_status	= $this->permission->check_permission($params['module'], $status_action_id);
			RLog::info("Permission Process Status [$permission_status] [{$params['module']}] [$status_action_id]");			
			
			if ($permission_status)
			{
				// UPDATE DATA IN PAYOUT SUMMARY
				$fields = array();
				$fields['payout_status_id'] = $valid_data['payout_status_id'];
				$where						= array();
				$where['payroll_summary_id']= $payroll_summary['payroll_summary_id'];
				$this->common->update_general_data($table, $fields, $where);
				
				// INSERT TO FORM 2316 table
				$payout_dates = explode(',', $payroll_summary['effective_dates']);
				$this->payroll_common = modules::load('main/payroll_common');
				$this->payroll_common->process_payroll_approval($payroll_summary, $payout_dates, $valid_data['payout_status_id'], NO);
				
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
						$valid_data['payout_status_id'], $payroll_summary['processed_by'], FALSE);				
				
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
	
	public function modal_edit_special_payroll($action, $id, $token, $salt, $module, $employee_id = NULL)
	{
		try
		{
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');

			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data ['module']      = $module;
			$data ['action']      = $action;
			$data ['salt']        = $salt;
			$data ['token']       = $token;
			$data ['id']          = $id;
			$data ['employee_id'] = $employee_id;
			
			// DATA (if view or edit)
			$payout_hdr_id        = $id;
			$params               = array('payout_hdr_id' => $payout_hdr_id);
			
			$val                  = $this->sppayroll->get_special_payroll_personnel ($params);
			RLog::info("***===*** [$payout_hdr_id]");
				RLog::info($val);
			RLog::info("***===***");
			
			$data['val']	= $val;			

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
		
		$this->load->view('special_payroll/modals/modal_edit_special_payroll', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function save_payout_details()
	{
		RLog::info('S: save_payout_details ...');
		
		$params = get_params();
		
		RLog::info($params);

		$msg 	= $this->lang->line('data_not_saved');
		$status = FALSE;
		
		try
		{
			if ($params['token'] != in_salt ($params['id']  . '/' . $params['action']  . '/' . $params['module'], $params['salt']) )
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			
			Main_Model::beginTransaction();

			$payout_hdr_ids			= $params['payout_hdr_ids'];
			
			$compensation_ids 		= $params['compensation_ids'];
			$compensation_amounts 	= $params['compensation_amounts'];
			
			$deduction_ids 			= $params['deduction_ids'];
			$deduction_amounts 		= $params['deduction_amounts'];
			
			$total_compensation_arr	= array(); 
			$total_deduction_arr	= array();
			$net_pay				= 0.00;
			
			if ( ! empty($payout_hdr_ids))
			{
				for ($i=0; $i<count($payout_hdr_ids); $i++)
				{
					$hdr_id	= $payout_hdr_ids[$i];
					
					RLog::info("Special_payroll_tab.save_payout_details [$hdr_id] [$i]");
					
					$table 	= $this->common->tbl_payout_details;
					$where	= array();
					$key    = $this->get_hash_key('payroll_dtl_id');
					if ( ! empty ($compensation_ids[$i]))
					{
						$fields 			= array();
						$fields['amount'] 	= (empty($compensation_amounts[$i]) ? 0.00 : $compensation_amounts[$i]);
						
						$where[$key] 		= $compensation_ids[$i];
						$this->common->update_general_data($table, $fields, $where);
						
						$total_compensation_arr[$hdr_id] = $fields['amount'];
					}
					
					if ( ! empty ($deduction_ids[$i]))
					{
						$fields 			= array();
						$fields['amount'] 	= (empty($deduction_amounts[$i]) ? 0.00 : $deduction_amounts[$i]);
						
						$where[$key] 		= $deduction_ids[$i];
						$this->common->update_general_data($table, $fields, $where);
						
						$total_deduction_arr[$hdr_id] = $fields['amount'];
					}
					
					
					// update header total amounts
					$total_income				= (empty($total_compensation_arr[$hdr_id]) ? 0.00 : $total_compensation_arr[$hdr_id]);
					$total_deductions			= (empty($total_deduction_arr[$hdr_id]) ? 0.00 : $total_deduction_arr[$hdr_id]);
					
					$net_pay 	= $total_income - $total_deductions;
					$table 		= $this->common->tbl_payout_header;
					$fields		= array();
					$where 		= array();					
					
					$fields['total_income'] 	= $total_income;
					$fields['total_deductions'] = $total_deductions;
					$fields['net_pay'] 			= $net_pay;
					$key						= $this->get_hash_key('payroll_hdr_id');
					$where['payroll_hdr_id'] 	= $hdr_id;
					$this->common->update_general_data($table, $fields, $where);					
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
		
		RLog::info('E: save_payout_details ...');
	}
	
}


/* End of file special_payroll_tab.php */
/* Location: ./application/modules/main/controllers/special_payroll_tab.php */