<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payroll_model', 'payroll');
		$this->load->model('common_model', 'common');
		
		$this->permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	}
	
	public function index()
	{
		// clear previously selected employees
		$sess_sel_employee_id = $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $data['module'];
		$this->session->unset_userdata($sess_sel_employee_id);		
		
		$permission_add 	= $this->permission->check_permission($this->permission_module, ACTION_ADD);
		
		$data = $resources = array();
		$data['permission_add'] = $permission_add;
		$data['module'] = $this->permission_module;
		
		$resources['load_css'] 		= array(CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_DATATABLE);
		$resources['datatable'][]	= array('table_id' => 'payroll_list', 'path' => 'main/payroll/get_payroll_list', 'advanced_filter' => TRUE);
		$resources['load_delete'] 	= array(
					'payroll',
					'delete_payroll',
					PROJECT_MAIN
		);		
		
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll";
		$key					= "General Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll";
		set_breadcrumbs($breadcrumbs, TRUE);
		
		
		
		$this->template->load('general_payroll/payroll', $data, $resources);

	}
	public function get_payroll_list()
	{
		try
		{
			$params 	= get_params();
			// $aColumns 	= array("a.payroll_summary_id","c.payroll_type_name", 
			// 				"d.bank_name", "b.date_from", "b.date_to", 
			// 				"a.payout_status_id", "e.payout_status_name");
			
			// marvin : include remarks for batching : start
			$aColumns 	= array("a.payroll_summary_id","c.payroll_type_name","d.bank_name","b.date_from","b.date_to","a.payout_status_id","e.payout_status_name","b.remarks");
			// marvin : include remarks for batching : end
			
			$bColumns 	= array("a.payout_status_id", "c.payroll_type_name", "DATE_FORMAT(b.date_from, '%Y/%m/%d')", "DATE_FORMAT(b.date_to, '%Y/%m/%d')");
			
			$payroll_rec	= $this->payroll->get_payroll_list($aColumns, $bColumns, $params);
			$payroll		= $payroll_rec['data']; 
			$iFilteredTotal	= $payroll_rec['filtered_length'];
			$iTotal   		= $this->payroll->get_payroll_list(array("COUNT(DISTINCT(a.payroll_summary_id)) as cnt" ), $bColumns, $params, FALSE);
		
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal['data']['cnt'],
				"iTotalDisplayRecords" => $iFilteredTotal['cnt'],
				"aaData" => array()
			);
			
			$permission_view 	= $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit 	= $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete 	= $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			$permission_process = $this->permission->check_permission($this->permission_module, ACTION_PROCESS);
			
			foreach ($payroll as $aRow)
			{
				$row = array();

				$id 			= $this->hash($aRow['payroll_summary_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $this->permission_module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt);
				$token_delete 	= in_salt($id  . '/' . ACTION_DELETE . '/' . $this->permission_module, $salt);
				$token_process 	= in_salt($id  . '/' . ACTION_PROCESS  . '/' . $this->permission_module, $salt);
				
				$payout_status_id 	= $aRow['payout_status_id'];

				//RLog::info('Payroll ' . $aRow['date_from'] . ' ' . $payout_status_id . ' ' . PARAM_PAYOUT_STATUS_INITIAL . ' ' . $permission_edit);
				
				$url_view 		= ACTION_VIEW."/".$this->permission_module."/".$id ."/".$token_view."/".$salt;
				$url_edit 		= ACTION_EDIT."/".$this->permission_module."/".$id ."/".$token_edit."/".$salt;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$this->permission_module;
				$url_process 	= ACTION_PROCESS."/".$id ."/".$token_process."/".$salt."/".$this->permission_module;
				
				$action = "<div class='table-actions'>";								
				
				$row[] =  $aRow['payroll_type_name'];
				$row[] =  $aRow['bank_name'];
				$date = new DateTime($aRow['date_from']);
				$row[] =  '<center>'.$date->format('Y/m/d').'</center>';
				$date = new DateTime($aRow['date_to']);
				$row[] =  '<center>'.$date->format('Y/m/d').'</center>';
				$row[] =  $aRow['payout_status_name'];
				// marvin : include remarks for batching : start
				$row[] =  $aRow['remarks'];
				// marvin : include remarks for batching : end
				
				if ($permission_view)
					$action .= "<a href='javascript:;' class='view tooltipped'  data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"content_form('payroll/prepare_payroll/".$url_view."', '".PROJECT_MAIN."')\"></a>";
				if ($permission_edit && ($payout_status_id == PARAM_PAYOUT_STATUS_INITIAL))
					$action .= "<a href='javascript:;' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"content_form('payroll/prepare_payroll/".$url_edit."', '".PROJECT_MAIN."')\" ></a>";				
				if($permission_process)
					$action .= "<a href='javascript:;' class='process tooltipped'  data-tooltip='Process' data-position='bottom' data-delay='50' onclick=\"content_form('payroll/display_payroll_process/".$url_process."', '".PROJECT_MAIN."')\"></a>";
				if ($permission_delete && ($payout_status_id == PARAM_PAYOUT_STATUS_INITIAL))
				{
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";					
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
	
	public function save_payroll()
	{
		try
		{
			$params = get_params();
			
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action']  . '/' . $params ['module'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			//VALIDATE INPUT
			$valid_data = $this->_validate_form($params);
			
			$field 				= array('payroll_summary_id', 'payout_status_id');
			$key   				= $this->get_hash_key ('payroll_summary_id');
			$where 				= array();
			$where[$key] 		= $params['id'];
			$table 				= $this->common->tbl_payout_summary;
			$payout_summary_rec = $this->common->get_general_data($field, $table, $where, FALSE);
			$payroll_summary_id = $payout_summary_rec['payroll_summary_id'];
			
			$payout_remittance = $this->payroll->get_payroll_remittance(array($payroll_summary_id));
			if(!EMPTY($payout_remittance)) {
				$msg	= $this->lang->line('err_change_payroll_status');
				$status = FALSE;
			}
			else 
			{
				$payroll_proc = modules::load('main/payroll_process');
				$result = $payroll_proc->save_payroll($valid_data, $params);
				$this->_save_payroll_employees($result['payroll_summary_id'],$params);	
				if($params ['action'] == ACTION_ADD || $params ['action'] == ACTION_EDIT)
				{
					$id         = $this->hash($result ['payroll_summary_id']);
					$salt       = gen_salt();
					$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt);
					
					$result['reload_url']   = ACTION_EDIT."/".$params ['module']."/".$id."/".$token_edit."/".$salt."/";				
				}
			}
			$result['msg'] = $msg;
			echo json_encode($result);
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}
	
	public function process_payroll()
	{
		try
		{
			$params = get_params();
			//RLog::info($params);
			
			// GET SECURITY VARIABLES
			if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action']  . '/' . $params ['module'], $params ['salt'] ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			// MARVIN : INCLUDE EMPLOYEE EXCLUDE IN DTR EDIT : START
			// EXCLUDE EMPLOYEE
			$sess_sel_employee_id 	= $this->_get_selected_employee_sess_var();
			$sess_sel_employees 	= $this->_get_selected_employee_sess_val($sess_sel_employee_id);

			$excluded_employee = array();

			foreach($sess_sel_employees as $k => $v)
			{
				if($v != 1)
				{
					array_push($excluded_employee, $k);
				}
			}

			// UPDATE
			foreach($excluded_employee as $ex_emp)
			{
				$table = 'attendance_period_dtl';
				$field = array();
				$field['attendance_period_hdr_id'] = null;
				$where = array();
				$where['employee_id'] = $ex_emp;
				$where['attendance_period_hdr_id'] = $params['payroll_period'];
				$this->common->update_general_data($table, $field, $where);
			}
			// MARVIN : INCLUDE EMPLOYEE EXCLUDE IN DTR EDIT : END
	
			//VALIDATE INPUT
			$valid_data = $this->_validate_form($params);
			$payroll_proc = modules::load('main/payroll_process');
			$result = $payroll_proc->process_payroll($valid_data, $params);
			echo json_encode($result);
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
	}

	private function _validate_form($params)
	{
		try
		{
			$fields['payroll_type'] 			= "Payroll Type";
			$fields['payroll_period'] 			= "Attendance Period";
			$fields['bank_id'] 					= "Bank";
			$fields['certified_correct_by_id']	= "Certified Correct By";
			$fields['approved_by_id'] 			= "Approved By";
			$fields['certified_cash_by_id'] 	= "Certified Cash Available By";
			$fields['payout_date_1'] 			= "Payout Date 1";
			$fields['payout_count'] 			= "Payout Count";
			
			$this->check_required_fields($params, $fields);

			return $this->_validate_inputs ($params);
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_inputs($params) 
	{
		try {
			$validation ['payroll_type'] = array (
					'data_type' => 'digit',
					'name'		=> 'Payroll Type',
					'max_len' 	=> 11 
			);
						
			$validation ['payroll_period'] = array (
					'data_type' => 'digit',
					'name'		=> 'Attendance Period',
					'max_len' 	=> 11 
			);
			$validation ['bank_id'] = array (
					'data_type' => 'digit',
					'name'		=> 'Bank',
					'max_len' 	=> 11 
			);
			$validation['certified_correct_by_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Certified Correct By'
			);
			$validation['approved_by_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Approved By'
			);
			$validation['certified_cash_by_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'NCA Available By'
			);
			$validation['payout_count'] = array(
					'data_type' => 'digit',
					'name'		=> 'Payout Count',
					'max_len'	=> 10
			);
			
			if (isset($params['payout_count']) && $params['payout_count'] > 0)
			{
				for ($pd=1; $pd<=$params['payout_count']; $pd++)
				{
					$valid_key = 'payout_date_' . $pd; 
					$validation[$valid_key] = array(
							'data_type' => 'date',
							'name'		=> 'Payout Date ' . $pd,
							'max_len'	=> 10
					);
				}
			}

			return $this->validate_inputs($params, $validation );
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	private function _get_payroll_periods($payroll_type_id, $payroll_summary_id)
	{
		$info = array();
		
		try
		{
			$payroll_period 			= $this->payroll->get_payroll_period($payroll_type_id, $payroll_summary_id);
	
			$fields                   	= array("*");		
			$tables                   	= $this->payroll->tbl_param_payroll_types;		
			$where                    	= array();
			$where['payroll_type_id'] 	= $payroll_type_id;
			
			$payout_count             	= $this->common->get_general_data($fields, $tables, $where, false);
	
			$list 						= array();
			if($payroll_period)
			{
				foreach ($payroll_period as $aRow):		
	
					// $list[] 	= array(
					// 		"value" => $aRow["attendance_period_hdr_id"],
					// 		"text" 	=> date('F d, Y',strtotime($aRow["date_from"]))." - ".date('F d, Y',strtotime($aRow["date_to"]))
					// );

					// marvin : batching : start
					if(empty($aRow['remarks']))
					{
						$list[] 	= array(
							"value" => $aRow["attendance_period_hdr_id"],
							"text" 	=> date('F d, Y',strtotime($aRow["date_from"]))." - ".date('F d, Y',strtotime($aRow["date_to"]))
						);
					}
					else
					{
						$list[] 	= array(
							"value" => $aRow["attendance_period_hdr_id"],
							"text" 	=> date('F d, Y',strtotime($aRow["date_from"]))." - ".date('F d, Y',strtotime($aRow["date_to"])) . " (" . $aRow['remarks'] . ")"
						);
					}
					// marvin : batching : end
				endforeach;			
			}
					
			$info = array(
					"list"         => $list,
					"payout_count" => $payout_count['payout_count']
			);
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

		return $info;
	}

	public function get_payroll_period_dropdown()
	{
		$params					= get_params();

		// clear previously selected employees
		$sess_sel_employee_id 	= $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $params['module'];
		$this->session->unset_userdata($sess_sel_employee_id);
		
		$payroll_summary_id 	= $params['id'];
		
		$info					= $this->_get_payroll_periods($params['payroll_type'], $payroll_summary_id);
	
		echo json_encode($info);
	}

	public function display_payroll_process($action, $id, $token, $salt, $module)
	{
		$resources = array();
		$resources['load_css'] 	= array(CSS_SELECTIZE, CSS_DATATABLE);
		$resources['load_js'] 	= array(JS_SELECTIZE, JS_DATATABLE, JS_NUMBER);
	
		if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
		{
			throw new Exception($this->lang->line('err_invalid_request'));
		}
		if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
		{
			throw new Exception($this->lang->line('err_unauthorized_access'));
		}
		
		$resources['load_modal'] = array(
				'modal_employee_pds'			=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_pds',
					'multiple'						=> true,
					'height'						=> '400px',
					'size'							=> 'sm',
					'title'							=> 'Employee Information'
				),
				'modal_employee_deduction'		=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_deduction',
					'multiple'						=> true,
					'height'						=> '500px',
					'size'							=> 'lg',
					'title'							=> 'Employee Deductions'
				),
				'modal_employee_compensation'	=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_compensation',
					'multiple'						=> true,
					'height'						=> '500px',
					'size'							=> 'lg',
					'title'							=> 'Employee Compensations'
				),
				'modal_employee_mra'			=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_mra',
					'multiple'						=> true,
					'height'						=> '500px',
					'size'							=> 'lg',
					'title'							=> 'Employee Monthly Report on Attendance'
				)
			);
		$data 					=  array();

		$data['action']			= $action;
		$data['id']				= $id;
		$data['salt']			= $salt;
		$data['token']			= $token; 
		$data['module']			= $module;
		
		//S: office list
		$fields 				= array('A.office_id','B.name AS office_name');
		$tables 				= array(
			'main' 		=> array(
				'table' 	=> $this->common->tbl_param_offices,
				'alias' 	=> 'A'
			),
			't1'   		=> array(
				'table' 	=> $this->common->db_core . '.' . $this->common->tbl_organizations,
				'alias' 	=> 'B',
				'type' 		=> 'JOIN',
				'condition' => 'A.org_code = B.org_code'
 			)
		);
		$where = array('A.active_flag' => 'Y');
		$data['office_list'] 	= $this->common->get_general_data($fields, $tables, $where);
		//E: office list

		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Process General Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll/display_payroll_process/".$action."/".$id."/".$token."/".$salt."/".$module;
		set_breadcrumbs($breadcrumbs, FALSE);
		
		$this->template->load('general_payroll/display_payroll_process', $data, $resources);
	}

	public function prepare_payroll($action, $module, $id=NULL, $token=NULL, $salt=NULL)
	{
		try
		{
			$resources = array();
			$resources['load_css']	= array(CSS_SELECTIZE, CSS_DATETIMEPICKER, CSS_DATATABLE);
			$resources['load_js'] 	= array(JS_SELECTIZE, JS_DATETIMEPICKER, JS_DATATABLE);

			$resources['load_modal']		= array(
				'modal_employee_pds'			=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_pds',
					'multiple'						=> true,
					'height'						=> '400px',
					'size'							=> 'sm',
					'title'							=> 'Employee Information'
				),
				'modal_employee_deduction'		=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_deduction',
					'multiple'						=> true,
					'height'						=> '500px',
					'size'							=> 'lg',
					'title'							=> 'Employee Deductions'
				),
				'modal_employee_compensation'	=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_compensation',
					'multiple'						=> true,
					'height'						=> '500px',
					'size'							=> 'lg',
					'title'							=> 'Employee Compensations'
				),
				'modal_employee_mra'			=> array(
					'controller'					=> 'Payroll_quick_links',
					'module'						=> PROJECT_MAIN,
					'method'						=> 'modal_employee_mra',
					'multiple'						=> true,
					'height'						=> '500px',
					'size'							=> 'lg',
					'title'							=> 'Employee Compensations'
				)
			);
			
			if($action != ACTION_ADD)
			{
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ) or EMPTY ( $module ))
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				if ($token != in_salt ( $id . '/' . $action  . '/' . $module, $salt ))
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}

			$data 					=  array();
	
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token; 
			$data['module']			= $module;	
			
			$details 				= array();
			if($action != ACTION_ADD)
			{
				$params 				= array();
				$params['id'] 			= $id;
				$details				= $this->payroll->get_payroll_record($params);
			}
			
			$data['val'] 			= $details;

			$fields 				= array("*");
			$tables					= $this->common->tbl_param_payroll_types;
			$where 					= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 	= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['payroll_type_id']	= array($details['payroll_type_id'], array("=", ")"));				
			}	
			
			$data['payroll_types']		= $this->common->get_general_data($fields, $tables, $where);
			$data['payroll_periods']	= array();

			if($action != ACTION_ADD)
			{
				if (isset($details))
				{
					$payroll_period 			= $details['attendance_period_hdr_id'];
					$payroll_periods 			= $this->_get_payroll_periods($details['payroll_type_id'], $id);
					$data['payroll_periods'] 	= (isset($payroll_periods['list']) ? $payroll_periods['list'] : array());
					//$data['payout_count'] = $payroll_periods['payout_count'];
					$payout_count 				= $details['payout_count'];
					$data['payout_count'] 		= $payout_count;
					
					$payout_dates 				= explode(',', $details['effective_dates']);
					
					$pay_date_arr 				= array();
					for($pd=1, $cnt=0; $pd<=$payout_count; $pd++, $cnt++)
					{
						if (isset($payout_dates[$cnt]))
							$pay_date_arr[$pd] 		= date('Y/m/d', strtotime($payout_dates[$cnt]));
						else
						 	$pay_date_arr[$pd] 		= date('Y/m/d');
					}
					$data['payout_dates'] 		= $pay_date_arr;
				}
			}			
			
			// banks			
			$where 							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;			
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['bank_id']				= array($details['bank_id'], array("=", ")"));				
			}	
			$data['banks']					= $this->common->get_general_data(array("*"), $this->common->tbl_param_banks, $where);
			
			// signatories
			$data['personnel_certify']		= $this->payroll->get_signatories(PARAM_PAYROLL_CERTIFIED_BY);
			$data['personnel_approvers']	= $this->payroll->get_signatories(PARAM_PAYROLL_APPROVED_BY);
			$data['personnel_ca_certify']	= $this->payroll->get_signatories(PARAM_PAYROLL_CA_CERTIFIED_BY);
			
			//S: office list
			$fields 						= array('A.office_id','B.name AS office_name');
			$tables 						= array(
				'main' 			=> array(
					'table' 		=> $this->common->tbl_param_offices,
					'alias' 		=> 'A'
				),
				't1'   			=> array(
					'table' 		=> $this->common->db_core . '.' . $this->common->tbl_organizations,
					'alias' 		=> 'B',
					'type'  		=> 'JOIN',
					'condition' 	=> 'A.org_code = B.org_code'
	 			)
			);
			$where 					= array('A.active_flag' => 'Y');
			$data['office_list'] 	= $this->common->get_general_data($fields, $tables, $where);
			//E: office list			

			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$breadcrumbs 			= array();
			$key					= "Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/payroll";
			$key					= "General Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/payroll";
			$key					= "Prepare General Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/payroll/prepare_payroll/".$action."/".$module."/".$id."/".$token."/".$salt;
			set_breadcrumbs($breadcrumbs, TRUE);
			
			$this->template->load('general_payroll/prepare_payroll', $data, $resources);
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
		}
	}
	
	public function get_payroll_employee_list($action, $module, $payroll_type_id=0, $payroll_period_id=0, $payroll_id=0)
	{
		try
		{
			$params		= get_params();
			$aaData 	= array();
			$employees	= array();
			
			$output = array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> 0,
				"iTotalDisplayRecords"	=> 0,
				"aaData" 				=> $aaData
			);		
			
			$payroll_type_id 	= isset($params['payroll_type']) ? $params['payroll_type'] : $payroll_type_id;
			$payroll_period_id 	= isset($params['payroll_period']) ? $params['payroll_period'] : $payroll_period_id;
			$payroll_id 		= isset($params['id']) ? $params['id'] : $payroll_id;			
			
			if ($action == ACTION_ADD)
			{
				if ($payroll_type_id < 1 && $payroll_period_id < 1) {
					echo json_encode( $output );
					return;
				}
			}
			$params['module_id'] 	= MODULE_PAYROLL_GENERAL_PAYROLL;
			$employee_rec			= $this->payroll->get_table_employee_list($payroll_type_id, $payroll_period_id, $params);
			$employees				= $employee_rec['data'];
			$iFilteredTotal			= $employee_rec['filtered_length'];
	
			$all_employee_rec		= $this->payroll->get_all_table_employee_list($payroll_type_id, $payroll_period_id);

			$fields                 = array("GROUP_CONCAT(CAST(employee_id as char)) as employees");
			$tables                 = $this->common->tbl_payout_employee;
			$key                    = $this->get_hash_key('payroll_summary_id');
			$where                  = array();
			$where['included_flag'] = YES;
			$where[$key]            = $payroll_id;
			$selected_result     	= $this->common->get_general_data_group_concat($fields, $tables,$where,FALSE);
			$selected_employees 	= explode(',', $selected_result['employees']);
			$checked 				= '';
			
			$permission_view		= $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit		= $this->permission->check_permission($module, ACTION_EDIT) && $action == ACTION_EDIT;
			
			$checked_employees 		= array();

// 			$sess_sel_employee_id 	= $this->_get_selected_employee_sess_var();
// 			$sess_sel_employees 	= $this->_get_selected_employee_sess_val($sess_sel_employee_id);
// 			if (EMPTY($sess_sel_employees))
// 			{
				// set the employees in session var
				foreach($all_employee_rec as $key => $row)
				{
					//RLog::info('EMPLOYEE worked ' . $row['employee_id'] . ' [' . $row['worked_hours'] . ']');
					$checked_num 	= 0;
					if($action == ACTION_ADD)
					{
						if ($row['worked_hours'] < 1)
							$checked_num = -1;
						else if(EMPTY($row['separation_mode_id']))
							$checked_num = 1;
					} 
					else if($action == ACTION_EDIT && $row['worked_hours'] < 1)
					{
						$checked_num = -1;
					}
					else if($selected_employees)		
					{
						if(in_array($row['employee_id'], $selected_employees))
							$checked_num = 1;
					}
					$checked_employees[$row['employee_id']] = $checked_num;
				}
				// set the employees in session var
				$sess_sel_employees = $this->_set_selected_employees($checked_employees);				
// 			}

			if($employees)
			{
				$output['iTotalRecords']		= $iFilteredTotal['cnt'];
				$output['iTotalDisplayRecords']	= $iFilteredTotal['cnt'];
				foreach($employees as $key => $aRow)
				{
					$id 			= $this->hash($aRow['employee_id']);
					$salt			= gen_salt();
					$token_view 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
					$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);

					$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
					$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;

					$checked 		= '';
					$disabled 		= '';
					$process_style 	= " style='visibility:hidden;' ";
					if ($aRow['worked_hours'] < 1)
					{
						$disabled = 'disabled'; //jendaigo : disable condition to include employee in the generation even absent
					} 
					else
					{ 
						if($sess_sel_employees[$aRow['employee_id']] == 1)
						{
							$checked 		= 'checked';
							$process_style 	= '';						
						}
					}
					
					if ($action == ACTION_VIEW)
						$disabled = 'disabled';
						
					$checkbox_class = '';
					if(EMPTY($disabled)) 
					{
						$checkbox_class = 'ind_checkbox';
					}
					$row 				= array();
					$row[] 				=  '<center> <input type="checkbox" name="selected_employees['.$aRow['employee_id'].']" id="check_'.$aRow['employee_id'].'" value="'.$aRow['employee_id'].'" class="'.$checkbox_class.' filled-in" '.$checked.' '. $disabled . '/>
									  		<label for="check_'.$aRow['employee_id'].'"></label> </center>';
					
					$row[] 				=  $aRow['agency_employee_id'];
					$row[] 				=  $aRow['employee_name'];
					$row[] 				=  $aRow['office_name'];;
					$row[] 				=  $aRow['employment_status_name'];

					$action_col 		= "<div class='table-actions table-links'>";
				
					$office_permission 	= $this->permission->check_office_permission($aRow['office_id'], null,false,true,MODULE_PAYROLL);
					
					if($permission_edit == true AND $office_permission == true)
					{
						$action_col .= "<a href='javascript:;' class='process tooltipped'  data-tooltip='Re-compute' data-position='bottom' data-delay='50' onclick='process_employee(".$aRow['employee_id'].")' id='proc_employee_id_".$aRow['employee_id']."' $process_style></a>";
					}
					
					if($permission_view)
					{
						$action_col .= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_pds' data-tooltip='PDS' data-position='bottom' data-delay='50' onclick=\"modal_employee_pds_init('".$url_view."')\"><i class='flaticon-user153 p-n'></i></a>";
					}
					$action_col 		.= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_compensation' data-tooltip='Compensations' data-position='bottom' data-delay='50' onclick=\"modal_employee_compensation_init('".$url_edit."')\"><i class='flaticon-portfolio32'></i></a>";
					$action_col 		.= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_deduction' data-tooltip='Deductions' data-position='bottom' data-delay='50' onclick=\"modal_employee_deduction_init('".$url_edit."')\"><i class='flaticon-minus100'></i></a>";
					
					$action_col 		.= "</div>";
					
					$row[] 				= $action_col;

					$output['aaData'][] = $row;
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
		
		echo json_encode( $output );
	}
	
	private function _save_payroll_employees($payroll_summary_id, $params)
	{
		try
		{
			//$selected_employees = $params['selected_employees'];
			$sess_sel_employee_id 	= $this->_get_selected_employee_sess_var();
			$sess_sel_employees 	= $this->_get_selected_employee_sess_val($sess_sel_employee_id);
			$selected_employees 	= array();
			foreach($sess_sel_employees as $emp_id => $checked_num)
			{
				if ($checked_num == 1)
					$selected_employees[$emp_id] = $emp_id;
			}
			/*
			RLog::info('SAVE THESE EMPLOYEES');
			RLog::info($selected_employees);
			RLog::info($params['selected_employees']);
			*/
			$where 			= array('payroll_summary_id' => $payroll_summary_id);
			$table 			= $this->common->tbl_payout_employee;
			$this->common->delete_general_data($table, $where);
			if(!EMPTY($selected_employees) AND !EMPTY($payroll_summary_id))
			{
				$employees		= $this->payroll->get_all_table_employee_list($params['payroll_type'], $params['payroll_period']);
				if($employees)
				{
					$fields 	= array();
					foreach ($employees as  $value) {

						$fields[]	= array(
							'payroll_summary_id' => $payroll_summary_id,
							'employee_id'        => $value['employee_id'],
							'included_flag'      => (isset($selected_employees[$value['employee_id']]))? 'Y':'N'
						);
					}
					
					$this->common->insert_general_data($table, $fields, FALSE);
				}
				
			}
			return TRUE;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
	}
	
	public function process_selected_employee($employee_id)
	{
		try
		{
			RLog::info('-- process_selected_employee --');
			
			$params				= get_params();
		
			$result['success'] 	= 1; 
			$result['emp_id'] 	= $employee_id;
			
			// GET SECURITY VARIABLES
			if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if (EMPTY ($employee_id) OR ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action']  . '/' . $params ['module'], $params ['salt'] )) )
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
	
			$valid_data 		= $this->_validate_form($params);
			//RLog::info($valid_data);				
				
			$payroll_proc 		= modules::load('main/payroll_process');
			$result 			= $payroll_proc->process_payroll($valid_data, $params, $employee_id);

			
			echo json_encode($result);

		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}		
		
	}
	
	public function delete_payroll()
	{
		try
		{
			$flag 			= 0;
			$params			= get_params();
			//RLog::info('DELETE');
			//RLog::info($params);
			$url 			= $params['param_1'];
			$url_explode	= explode('/',$url);
			$action 		= $url_explode[0];
			$id				= $url_explode[1];
			$token 			= $url_explode[2];
			$salt 			= $url_explode[3];
			$module			= $url_explode[4];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			Main_Model::beginTransaction();
			//GET PREVIOUS DATA
			$prev_data				= array() ;
			/*GET PREVIOUS DATA*/
			$field 					= array('payroll_summary_id', 'payout_type_flag', 'bank_id', 'attendance_period_hdr_id', 'process_start_date',
										'certified_by', 'approved_by', 'certified_cash_by', 'payout_status_id') ;
			$table					= $this->common->tbl_payout_summary;
			$where					= array();
			$key_id					= $this->get_hash_key('payroll_summary_id');
			$where[$key_id]			= $id;
			$prev_data 				= $this->common->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			// child tables
			$payroll_proc 			= modules::load('main/payroll_process');

			// ====================== jendaigo : start : move before clear_payroll_data ============= //
			// UPDATE DEDUCTION PAID COUNT [DEDUCT PAID COUNT]
			$this->payroll_process->update_deduction_paid_count($prev_data['payroll_summary_id'], NULL, NULL, FALSE);
			// ====================== jendaigo : end : move before clear_payroll_data ============= //

			$payroll_proc->clear_payroll_data($id);
			// payout_history
			$where					= array($this->get_hash_key('payout_summary_id') => $id);
			$table 					= $this->common->tbl_payout_history;
			$this->common->delete_general_data($table, $where);
			// payout summary_dates
			$where					= array($this->get_hash_key('payout_summary_id') => $id);
			$table 					= $this->common->tbl_payout_summary_dates;
			$this->common->delete_general_data($table, $where);			
			// summary table
			$where					= array($key_id => $id);
			$table 					= $this->common->tbl_payout_summary;
			$this->common->delete_general_data($table, $where);
			// update attendance_period_hdr status
			$fields					= array('period_status_id' => PARAM_PAYOUT_STATUS_INITIAL);
			$where					= array('attendance_period_hdr_id' => $prev_data['attendance_period_hdr_id']);
			$table 					= $this->common->tbl_attendance_period_hdr;
			$this->common->update_general_data($table, $fields, $where);

			// ====================== jendaigo : start : move before clear_payroll_data ============= //
			// UPDATE DEDUCTION PAID COUNT [DEDUCT PAID COUNT]
			// $this->payroll_process->update_deduction_paid_count($prev_data['payroll_summary_id'], NULL, NULL, FALSE);
			// ====================== jendaigo : end : move before clear_payroll_data ============= //

			$audit_table[]			= $this->common->tbl_payout_summary;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array($prev_data);
			$curr_detail[]			= array();
			$audit_action[] 		= AUDIT_DELETE;
			$audit_activity 		= "General Payroll record has been deleted.";

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 					= $this->lang->line('data_deleted');
			$flag 					= 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$response 			= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'payroll_list',
			"path"					=> PROJECT_MAIN . '/payroll/get_payroll_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}	
	
	private function _get_selected_employee_sess_var()
	{
		$params	= get_params();
		//RLog::info("Logged-in User: " . $this->session->user_pds_id);
		
		return $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $params['module'];
	}	
	
	private function _get_selected_employee_sess_val($sess_sel_employee_id)
	{
		$sess_sel_employees 	= NULL;
		if ($this->session->has_userdata($sess_sel_employee_id)) {
			$sess_sel_employees 	= $this->session->userdata($sess_sel_employee_id);
		} 
		return $sess_sel_employees;
	}
	
	private function _set_selected_employees($selected_employees_ui)
	{
		$sess_sel_employee_id 	= $this->_get_selected_employee_sess_var();
		$sess_sel_employees 	= $this->_get_selected_employee_sess_val($sess_sel_employee_id);
		foreach ($selected_employees_ui as $emp_id => $checked_num)
		{
			$sess_sel_employees[$emp_id] = $checked_num;			
		}
		
		$this->session->set_userdata($sess_sel_employee_id, $sess_sel_employees);
		
		return $this->session->userdata($sess_sel_employee_id);
	}
	
	private function _set_selected_employee($emp_id, $checked_num, $all_employees)
	{
		$success 				= 1;
		
		$sess_sel_employee_id 	= $this->_get_selected_employee_sess_var();
		$sess_sel_employees 	= $this->_get_selected_employee_sess_val($sess_sel_employee_id);
		
		if ($sess_sel_employees[$emp_id] == -1)
		{
			if ($checked_num == 1)
				$success = -1;
			else
				$success = 0;
		}
		else
		{
			if($emp_id == 'all') {
				$emloyee_checked = array();
				foreach($sess_sel_employees as $empl=>$key) {
					if($key != -1)
						$emloyee_checked[$empl] = $key;
				}
				$sess_sel_employees 			= array_fill_keys(array_keys($emloyee_checked), $checked_num);
			}
			else	
				$sess_sel_employees[$emp_id] 	= $checked_num;
			$this->session->set_userdata($sess_sel_employee_id, $sess_sel_employees);
		}
		/*
		RLog::info('session var ['.$sess_sel_employee_id.']');
		RLog::info('-- S: SELECTED EMPLOYEES --');
		RLog::info($this->session->userdata($sess_sel_employee_id));
		RLog::info('-- E: SELECTED EMPLOYEES --');
		*/
		//return $this->session->userdata($sess_sel_employee_id);
		return $success;
	}	
	
	public function set_selected_employee($emp_id=0, $checked_num=0, $all_employees=0)
	{
		$process_icon_id 	= 'proc_employee_id_' . $emp_id; 
		$info				= array('success' => 1, 'msg' => '', 'proc_employee_id' => $process_icon_id);
		
		$success 			= $this->_set_selected_employee($emp_id, intval($checked_num), $all_employees);
		if ($success == -1)
		{
			$info['success'] 	= $success;
			$info['msg'] 		= $this->lang->line('employee_no_attendance');
		}
		
		echo json_encode($info);
	}
	
	public function set_payroll_period_change()
	{
		$params					= get_params();
		
		// clear previously selected employees
		$sess_sel_employee_id 	= $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $params['module'];
		$this->session->unset_userdata($sess_sel_employee_id);
		
		$info					= array('success' => 1);

		echo json_encode($info);
	}
	
	public function get_included_employee_count()
	{
		try
		{
			$count 			= 0;
			$params			= get_params();
			$payroll_id 	= $params['payroll_id'];

			if(!EMPTY($params['office_id']))
			{
				$office_list 	= $this->payroll->get_office_child('', $params['office_id']);
			}
			
			$fields 		= array("count(A.employee_id) as cnt");
			$tables 		= $this->common->tbl_payout_employee;

			$tables 		= array(
				'main' 			=> array(
					'table' 		=> $this->common->tbl_payout_employee,
					'alias' 		=> 'A'
				),
				't1'   			=> array(
					'table' 		=> $this->common->tbl_employee_personal_info,
					'alias' 		=> 'B',
					'type'  		=> 'JOIN',
					'condition' 	=> 'A.employee_id = B.employee_id'
	 			),
				't2'   			=> array(
					'table' 		=> $this->common->tbl_employee_work_experiences,
					'alias' 		=> 'C',
					'type'  		=> 'JOIN',
					'condition' 	=> 'B.employee_id = C.employee_id AND C.active_flag = "Y"'
	 			)
			);
			$key                    = $this->get_hash_key('A.payroll_summary_id');
			$where                  = array();
			$where['included_flag'] = YES;

			if(!EMPTY($params['office_id']))
			{
				$where['C.employ_office_id'] = array($office_list, array('IN'));
			}
			$where[$key]     		= $payroll_id;
			$selected_result 		= $this->common->get_general_data($fields, $tables,$where,FALSE);
			$count           		= $selected_result['cnt'];
			
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
		$data = array('count' => $count);
		echo json_encode( $data );
	}
	
	public function is_selected_all()
	{
		$sess_sel_employee_id = $this->_get_selected_employee_sess_var();
		$sess_sel_employees = $this->_get_selected_employee_sess_val($sess_sel_employee_id);
		$is_select_all;
		if(!EMPTY($sess_sel_employees)) {
			if(in_array(0, $sess_sel_employees)) {
				$is_select_all = FALSE;
			} else {
				$is_select_all = TRUE;
			}
		}
		$data = array('is_select_all' => $is_select_all);
		echo json_encode( $data );
	}
}


/* End of file Payroll.php */
/* Location: ./application/modules/main/controllers/Payroll.php */