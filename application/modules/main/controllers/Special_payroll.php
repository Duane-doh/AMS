<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Special_payroll extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_SPECIAL_PAYROLL;
	private $payroll_process;
	private $payroll_tax_diff;
	private $payroll_common;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('special_payroll_model', 'sppayroll');
		$this->load->model('common_model', 'common');
		$this->load->model('payroll_model', 'payroll');
		$this->load->model('payroll_process_model', 'payroll_proc');
		$this->payroll_process 		= modules::load('main/payroll_process');
		$this->payroll_common 		= modules::load('main/payroll_common');
		$this->payroll_tax_diff 	= modules::load('main/payroll_tax_diff');
		
		$this->permission_module = MODULE_PAYROLL_SPECIAL_PAYROLL;
	}
	
	public function index()
	{
		
		// clear previously selected employees
		$sess_sel_employee_id = $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $data['module'];
		$this->session->unset_userdata($sess_sel_employee_id);	
		$resources                = array();

		$resources['load_css']    = array(CSS_DATATABLE);
		$resources['load_js']     = array(JS_DATATABLE);
		$resources['datatable'][] = array('table_id' => 'payroll_list', 'path' => 'main/special_payroll/get_special_payroll_list', 'advanced_filter' => true);

		$resources['load_modal']		= array(
					'modal_add_special_payroll'		=> array(
							'controller'	=> strtolower(__CLASS__),
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_add_special_payroll',
							'multiple'		=> true,
							'height'		=> '550px',
							'size'			=> 'sm',
							'title'			=> 'Special Payroll'
					),
					'modal_special_payroll_info'		=> array(
							'controller'	=> strtolower(__CLASS__),
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_special_payroll_info',
							'multiple'		=> true,
							'height'		=> '550px',
							'size'			=> 'sm',
							'title'			=> 'Special Payroll'
					)

		);

		$resources['load_delete'] 	= array(
					'special_payroll',
					'delete_payroll',
					PROJECT_MAIN
		);

		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/special_payroll";
		$key					= "Special Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/special_payroll";
		set_breadcrumbs($breadcrumbs, TRUE);

		$data = array();
		$data['module'] = $this->permission_module;
		$permission_add 	= $this->permission->check_permission($this->permission_module, ACTION_ADD);
		$data['permission_add'] = $permission_add;	
		
		$this->template->load('special_payroll/special_payroll', $data, $resources);
	}	

	public function display_special_payroll_process($action, $id, $token, $salt, $module)
	{
		$data                  =  array();
		$resources             = array();
		
		$data['action']        = $action;
		$data['id']            = $id;
		$data['salt']          = $salt;
		$data['token']         = $token; 
		$data['module']        = $module;

		$resources['load_css'] 	= array(CSS_SELECTIZE, CSS_DATATABLE);
		$resources['load_js'] 	= array(JS_SELECTIZE, JS_DATATABLE);
		
		//S: office list
		$fields = array('A.office_id','B.name AS office_name');
		$tables = array(
			'main' => array(
				'table' => $this->common->tbl_param_offices,
				'alias' => 'A'
			),
			't1'   => array(
				'table' => $this->common->db_core . '.' . $this->common->tbl_organizations,
				'alias' => 'B',
				'type'  => 'JOIN',
				'condition' => 'A.org_code = B.org_code'
 			)
		);
		$where = array('A.active_flag' => 'Y');
		$data['office_list'] = $this->common->get_general_data($fields, $tables, $where);
		//E: office list		
		
		/*BREADCRUMBS*/
		$breadcrumbs           = array();
		$key                   = "Process Special Payroll"; 
		$breadcrumbs[$key]     = PROJECT_MAIN."/special_payroll/display_special_payroll_process/".$action."/".$id."/".$token."/".$salt."/".$module;
		set_breadcrumbs($breadcrumbs, FALSE);
		
		$this->template->load('special_payroll/display_special_payroll_process', $data, $resources);
	}

	public function get_special_payroll_list()
	{
		try
		{
			$params = get_params();
			
			$aColumns 	= array("A.payroll_summary_id", "E.effective_date", "A.process_start_date processed_date", "A.processed_by", 
				"CONCAT(U.lname, ', ', U.fname, ' ', U.mname) processed_by_name", 
				"A.payout_status_id", "F.payout_status_name", 
				"A.compensation_id", "D.compensation_name");
			$bColumns 	= array("compensation_name", "DATE_FORMAT(effective_date, '%Y/%m/%d')", "payout_status_name");
			
			$return_list	= $this->sppayroll->get_special_payroll_list($aColumns, $bColumns, $params);
			$payroll_list	= $return_list['data'];

			$iFilteredTotal	= $return_list['filtered_length'];
			
			$field	= 'payroll_summary_id';
			$table	= $this->common->tbl_payout_summary;
			$where	= array('payout_type_flag' => PAYOUT_TYPE_FLAG_SPECIAL); // S for Special Payroll			
			$iTotal	= $this->common->get_total_length($table, $field, $where);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"permission_add" => $this->permission->check_permission($this->permission_module, ACTION_ADD),
				"aaData" => array()
			);
			
			$cnt = 0;

			$permission_view 	= $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit 	= $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete 	= $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			$permission_process = $this->permission->check_permission($this->permission_module, ACTION_PROCESS);
			
			foreach ($payroll_list as $aRow)
			{
				$cnt++;
				$row          	= array();
				$action       	= "<div class='table-actions'>";
				
				$id				= $this->hash($aRow['payroll_summary_id']);
				
				$salt         	= gen_salt();
				$token_view   	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $this->permission_module, $salt);
				$token_edit   	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt);
				$token_delete 	= in_salt($id  . '/' . ACTION_DELETE . '/' . $this->permission_module, $salt);
				$token_process	= in_salt($id  . '/' . ACTION_PROCESS  . '/' . $this->permission_module, $salt);
				
				$payout_status_id 	= $aRow['payout_status_id'];
				
				$url_view     	= ACTION_VIEW.'/'.$id .'/'.$token_view.'/'.$salt.'/'.$this->permission_module;
				$url_edit     	= ACTION_EDIT.'/'.$id .'/'.$token_edit.'/'.$salt.'/'.$this->permission_module;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$this->permission_module;
				$url_process  	= ACTION_PROCESS.'/'.$id .'/'.$token_process.'/'.$salt.'/'.$this->permission_module;
							
				$row[] =  $aRow['compensation_name'];
				$date = new DateTime($aRow['effective_date']);
				$row[] =  '<center>'.$date->format('Y/m/d').'</center>';
				$row[] =  $aRow['payout_status_name'];

				if($permission_view)
					$action .= "<a href='javascript:;' class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"content_form('special_payroll/prepare_special_payroll/".$url_view."', '".PROJECT_MAIN."')\"></a>";
				if($permission_edit && ($payout_status_id == PARAM_PAYOUT_STATUS_INITIAL))
					$action .= "<a href='javascript:;' class='edit tooltipped' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"content_form('special_payroll/prepare_special_payroll/".$url_edit."', '".PROJECT_MAIN."')\"></a>";
				if($permission_process)
					$action .= "<a href='javascript:;' class='process tooltipped'  data-tooltip='Process' data-position='bottom' data-delay='50' onclick=\"content_form('special_payroll/display_special_payroll_process/".$url_process."', '".PROJECT_MAIN."')\"></a>";
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
	
	private function _set_modal_special_payroll($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		$data 	= array();
		try
		{
			$val	= array('compensation_id' => '', 'effective_date' => '', 'bank_id' => '', 
				'certified_by' => '', 'approved_by' => '', 'certified_cash_by' => '');
			
			// PARAMETERS
			$data ['action']            = $action;
			$data ['id']                = $id;
			$data ['token']             = $token;
			$data ['salt']              = $salt;
			$data ['module']            = $this->permission_module;
			
			$field                      = array('compensation_id', 'tenure_rqmt_flag', 'compensation_name', 'monetization_flag') ;
			$table                      = $this->common->tbl_param_compensations;

			$where                      	= array();
			$where['active_flag']       	= YES;
			$where['special_payroll_flag'] 	= YES;
			$where['parent_compensation_id'] = 'IS NULL';  
			$order_by                   	= array('compensation_name' => 'ASC'); 
			$data['compensation_types'] 	= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
			
			$field                      = array('bank_id', 'bank_name') ;
			$table                      = $this->common->tbl_param_banks;
			$where                      = array('active_flag' => 'Y');
			$order_by                   = array("bank_name" => 'ASC'); 
			$data['banks']              = $this->common->get_general_data($field, $table, $where, TRUE, $order_by);

			// signatories
			$data['personnel_certify']		= $this->payroll->get_signatories(PARAM_PAYROLL_CERTIFIED_BY);
			$data['personnel_approvers']	= $this->payroll->get_signatories(PARAM_PAYROLL_APPROVED_BY);
			$data['personnel_ca_certify']	= $this->payroll->get_signatories(PARAM_PAYROLL_CA_CERTIFIED_BY);			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		return $data;
	}
	

	
	
	public function process_special_payroll()
	{
		$reload_url = NULL;
		try 
		{
			$params = get_params();
			$info	= array();
			$status = FALSE;
			$msg 	= $this->lang->line('data_not_saved');
			
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
			$compensation_type_arr	= explode('|', $params['compensation_type_id']);
			$monetize_flag 			= (count($compensation_type_arr) > 1 ? $compensation_type_arr[2] : 0);
			$compensation_type_id 	= ($compensation_type_arr !== FALSE ? $compensation_type_arr[0] : 0);
			$tenure_rqmt_flag 		= ($compensation_type_arr !== FALSE ? $compensation_type_arr[1] : 0);

			$params['compensation_type_id']	= $compensation_type_id;
			$params['tenure_rqmt_flag']		= $tenure_rqmt_flag;
			$valid_data = $this->_validate_form($params);
			
			Main_Model::beginTransaction();

			$audit_action_type = AUDIT_INSERT;
			$prev_detail[]  = array();
			if (isset($params['id']) && $params['action'] == ACTION_EDIT)
			{
				$audit_action_type = AUDIT_UPDATE;			
				// get payroll_hdr_id
				$field = array('payroll_summary_id',
							'payout_type_flag',
							'bank_id',
							'attendance_period_hdr_id',
							'compensation_id',
							'monetize_flag',
							'tenure_period_start_date',
							'tenure_period_end_date',
							'rating_period_start_date',
							'rating_period_end_date',
							'process_start_date',
							'process_end_date',
							'processed_by',
							'certified_by',
							'approved_by',
							'certified_cash_by',
							'payout_status_id');
				$key   = $this->get_hash_key ('payroll_summary_id');
				$where = array();
				$where[$key] = $params['id'];
				$table = $this->common->tbl_payout_summary;
				$payout_summary_rec = $this->common->get_general_data($field, $table, $where, FALSE);
				if ( ! isset($payout_summary_rec))
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

				if ($payout_summary_rec['payout_status_id'] != PARAM_PAYOUT_STATUS_INITIAL)
					throw new Exception ($this->lang->line ( 'unable_continue_record_submitted' ));					

				$payout_summary_id  = $payout_summary_rec['payroll_summary_id'];
				$prev_detail[]		= $payout_summary_rec;

				$valid_data['payroll_summary_id'] = $payout_summary_id;

				// if EDIT, clear data first before re-computation
				$this->payroll_process->clear_payroll_data($params ['id']);
				// if monetization, update employee_leave_details.paid_flag to N
				if ($payout_summary_rec['monetize_flag'] === YES)
					$this->payroll_proc->update_monetize_leave_details($payout_summary_id, NO, NULL, TRUE);
			}
			
			//=========INSERT PAYROLL SUMMARY=========
			$valid_data['monetize_flag']	= $monetize_flag;
			$payout_summary					= $this->_insert_payout_summary($valid_data);
			$payout_summary_id 				= $payout_summary['payroll_summary_id'];
			//========================================
			
			/*INSERT SELECTED EMPLOYEES - Ruel*/
			$this->_save_payroll_employees($payout_summary_id, $monetize_flag, $params);			

			// START: PREPARE DATA FOR AUDIT TRAIL
			$table              = $this->common->tbl_payout_summary;
			$audit_table[]      = $table;
			$audit_schema[]     = Base_Model::$schema_core;
			$audit_module       = $this->permission_module;
			// GET THE EMPLOYEE ID OF THE CURRENT USER
			$where                                     = array();
			$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
			$employee_info                             = $this->common->get_general_data(array('employee_id'), $this->common->tbl_employee_personal_info, $where, FALSE);
			// SET DETAIL FOR AUDIT TRAIL
			$curr_detail[] 	= array($payout_summary);
			$audit_action[] = $audit_action_type;
			// END: PREPARE DATA FOR AUDIT TRAIL
			
			$compensation_types      = $this->sppayroll->get_compensation_type($compensation_type_id, TRUE);
			
			//GET SELECTED EMPLOYEES FROM PAYOUT_EMPLOYEES WHERE INCLUDE_FLAG = 'Y'
			$payout_employees = $this->payroll_proc->get_payout_employees($payout_summary_id);
			
			$compensation_personnels = array();
			$payout_date             = $valid_data['payout_date'];
			
			RLog::info('==========S1: COMPENSATION_TYPES ==========');
			RLog::info($compensation_types);
			RLog::info($payout_employees);
			RLog::info('==========E1: COMPENSATION_TYPES ==========');
			
			$included_employees		= array();
			if ( ! empty($payout_employees) && ! empty($payout_employees['employees']) > 0)
				$included_employees = explode(',', $payout_employees['employees']);
			else
				throw new Exception('Employees: ' . $this->lang->line('param_not_defined'));
			
			if (isset($compensation_types))
			{
				$with_tax 	= NO;
				$dte		= new DateTime($payout_date);
				$pay_date	= $dte->format('Y-m-t');
				foreach ($compensation_types as $compensation_type)
				{
					$period_params     = array();
					
					$frmt_dte = new DateTime($valid_data['tenure_period_to']);
					$period_params['tenure_period_from'] = $valid_data['tenure_period_from'];
					$period_params['tenure_period_to']   = $frmt_dte->format('Y-m-d');
					$period_params['rating_period_from'] = ( ! empty($valid_data['rating_period_from']) ? $valid_data['rating_period_from'] : NULL);
					$period_params['rating_period_to']   = ( ! empty($valid_data['rating_period_to']) ? $valid_data['rating_period_to'] : NULL);
					
					if ($with_tax == NO)
						$with_tax = $compensation_type['taxable_flag'];
					
					$ct_personnels	= $this->_compute_compensation_type($compensation_type, $payout_summary_id, $included_employees, $pay_date, $period_params);
					
					$compensation_personnels = array_merge($compensation_personnels, $ct_personnels);
				}
				
				// compute tax if benefit is taxable
				if ($with_tax == YES)
				{
					$sys_params = $this->payroll_proc->get_sys_gen_params();
					$compensation_personnels = $this->payroll_tax_diff->re_compute_tax($compensation_personnels, $pay_date, $included_employees, NULL, TRUE, TRUE, TRUE, $sys_params);
				}
			}
			
			if (empty($compensation_personnels))
				throw new Exception($this->lang->line('no_record_to_process'));			
			
			//INSERT PAYROLL HEADER
			RLog::info('==========S1: COMPENSATION_PERSONNELS ==========');
			RLog::info($compensation_personnels);
			RLog::info('==========E1: COMPENSATION_PERSONNELS ==========');
			
			//===========INSERT PAYOUT HEADER=========
			$payout_details = $this->_insert_payout_header($payout_summary_id, $compensation_personnels, $payout_date);
			//========================================

			RLog::info('==========S2: PAYOUT DETAILS ==========');
			RLog::info($payout_details);
			RLog::info('==========E2: PAYOUT DETAILS ==========');

			if (isset($compensation_personnels))
			{
				//=========INSERT PAYOUT DETAILS=========
				$this->_insert_payout_details($payout_details);
				//========================================
			}
			
			// POPULATE DATA FOR PAYOUT HISTORY
			$hist_data						= array();
			$hist_data['payout_summary_id']	= $payout_summary_id;
			$hist_data['payout_status_id']	= PARAM_PAYOUT_STATUS_INITIAL;
			$hist_data['hist_date']        	= date('Y-m-d H:i:s');
			$hist_data['action_id']      	= ($audit_action_type === AUDIT_INSERT ? ACTION_ADD : ACTION_EDIT);
			$hist_data['employee_id']      	= $employee_info['employee_id'];
			
			// INSERT DATA TO PAYOUT HISTORY
			$this->common->insert_general_data($this->common->tbl_payout_history, $hist_data);			

			/*START - Ruel*/
			if($params ['action'] == ACTION_ADD || $params ['action'] == ACTION_EDIT)
			{
				$id         = $this->hash($payout_summary_id);
				$salt       = gen_salt();
				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $this->permission_module, $salt);
				
				$reload_url   = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$this->permission_module;				
			}
			/*END - Ruel*/

			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been added";
			if ($params['action'] == ACTION_EDIT)
			{
				$activity = "%s has been updated";
			}

			$activity = sprintf($activity, 'Special Payroll');
			
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
			$msg 	= $this->lang->line('data_saved');
			$status = TRUE;
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($msg);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($msg);
		}

		$info['msg']        = $msg;
		$info['status']     = $status;	
		$info['reload_url'] = $reload_url;
		echo json_encode($info);
	}

	/*
	 * START: Helper functions
	 */
	
	/**
	 * This helper function validates user inputs.
	 * 
	 * @param array $params
	 */
	private function _validate_form($params)
	{
		try
		{
			$fields = array();

			$fields['compensation_type_id']    = "Compensation Type";
			$fields['payout_date']             = "Payout Date";
			$fields['bank_id']                 = "Bank";
			$fields['certified_correct_by_id'] = "Certified Correct By";
			$fields['approved_by_id']          = "Approved By";
			$fields['certified_cash_by_id']    = "Certified Cash Available By";
			
			if ($params['tenure_rqmt_flag'] != TENURE_RQMT_NA)
			{
				$fields['tenure_period_from'] = "Covered Period From";
				$fields['tenure_period_to']   = "Covered Period To";
			}
		
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_inputs($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_inputs($params)
	{
		try
		{
			$validation['compensation_type_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Compensation Type',
					'max_len'	=> 11
			);	
			$validation['payout_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Payout Date',
					'max_len'	=> 10
					//'max_date'	=> date("Y/m/d")
			);
			$validation['bank_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Bank'
			);
			$validation['tenure_period_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Covered Period From',
					'max_len'	=> 10
			);
			$validation['tenure_period_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Covered Period To',
					'max_len'	=> 10
			);
			$validation['rating_period_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Rating Period From',
					'max_len'	=> 10
			);
			$validation['rating_period_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Rating Period To',
					'max_len'	=> 10
			);
			$validation['certified_correct_by_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Certified Correct By'
			);
			$validation['approved_by_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Bank'
			);
			$validation['certified_cash_by_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Certified Cash Available By'
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}	
	
	/**
	 * This helper method saves payout summary record.
	 * @param array $data Record to be saved
	 * @return integer Payout summary unique ID
	 */
	private function _insert_payout_summary($data)
	{
		RLog::info('START 1: _insert_payout_summary ' . $this->common->tbl_payout_summary);

		$payout_summary	= array();
		try
		{
			// payout_summary
			if ( ! empty($data['payroll_summary_id']))
				$payout_summary['payroll_summary_id']	= $data['payroll_summary_id'];

			$payout_summary['payout_type_flag']			= PAYOUT_TYPE_FLAG_SPECIAL;
			$payout_summary['bank_id']					= $data['bank_id'];
			$payout_summary['compensation_id']			= $data['compensation_type_id'];
			$payout_summary['monetize_flag']			= $data['monetize_flag'];
			
			if ( ! empty($data['tenure_period_from']))
				$payout_summary['tenure_period_start_date'] = $data['tenure_period_from'];
			if ( ! empty($data['tenure_period_to']))
				$payout_summary['tenure_period_end_date'] = $data['tenure_period_to'];
			
			if ( ! empty($data['rating_period_from']))
				$payout_summary['rating_period_start_date'] = $data['rating_period_from'];
			if ( ! empty($data['rating_period_to']))
				$payout_summary['rating_period_end_date'] = $data['rating_period_to'];
			
			$payout_summary['certified_by']				= $data['certified_correct_by_id'];
			$payout_summary['approved_by']				= $data['approved_by_id'];
			$payout_summary['certified_cash_by']		= $data['certified_cash_by_id'];
			$payout_summary['process_start_date']		= date('Y-m-d H:i:s');
			$payout_summary['processed_by']				= $this->user_id;
			$payout_summary['payout_status_id']			= PAYOUT_STATUS_FOR_PROCESSING;
	
			$table 										= $this->common->tbl_payout_summary;
			$payout_summary_id							= $this->common->insert_general_data($table, $payout_summary, TRUE, TRUE, 'payroll_summary_id');
			$payout_summary['payroll_summary_id']		= $payout_summary_id;
			
			// payout_summary_dates
			$payout_summary_dt							= array();
			$payout_summary_dt['payout_summary_id']		= $payout_summary_id;
			$payout_summary_dt['payout_date_num']		= 1; // fixed to 1 since 1 payout only
			$payout_summary_dt['effective_date']		= $data['payout_date'];
	
			$table 										= $this->common->tbl_payout_summary_dates;
			$payout_summary_date_id						= $this->common->insert_general_data($table, $payout_summary_dt, TRUE);
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		RLog::info('START 2: _insert_payout_summary');
		
		return $payout_summary;
	}
	
	/**
	 * This helper method saves payout header records.
	 * @param integet $payout_summary_id Parent payout summary reference number 
	 * @param array $compensation_personnels Personnels to be saved
	 * @return array Payout header records
	 */
	private function _insert_payout_header($payout_summary_id, $compensation_personnels, $payout_date)
	{
		RLog::info('START: _insert_payout_header ['.$payout_summary_id.']');
		
		$payout_details = array();
		$compensation_details = array();
		$deduction_details = array();
		try
		{
			$payout_header_employee = array();
			$payout_header_totals	= array();
			foreach ($compensation_personnels as $personnel)
			{
				$emp_id = $personnel['employee_id'];
				
				$personnel_amount	= (isset($personnel[KEY_AMOUNT][KEY_AMOUNT]) ? $personnel[KEY_AMOUNT][KEY_AMOUNT] : 0);
				
				if ( ! isset($payout_header_employee[$emp_id]) )
				{
					$payout_header                               = array();
					$payout_header['payroll_summary_id']         = $payout_summary_id;
					$payout_header['employee_id']                = $emp_id;
					$payout_header['employee_name']              = $personnel['employee_name'];
					$payout_header['plantilla_item_number']      = $personnel['employ_plantilla_id'];
					$payout_header['office_id']                  = $personnel['employ_office_id'];
					$payout_header['office_name']                = (empty($personnel['employ_office_name']) ? '' : $personnel['employ_office_name']);
					$payout_header['position_name']              = (empty($personnel['employ_position_name']) ? '' : $personnel['employ_position_name']);
					$payout_header['salary_grade']               = $personnel['employ_salary_grade'];
					$payout_header['pay_step']                   = $personnel['employ_salary_step'];
					
					$payout_header['basic_amount']               = $personnel['employ_monthly_salary'];
					$payout_header['tenure_in_months']           = $personnel['tenure_in_months'];
					$payout_header['perf_rating']                = $personnel['perf_rating'];
					$payout_header['perf_rating_description']    = $personnel['perf_rating_description'];
					
					$payout_header['total_income']               = (empty($personnel_amount) ? 0.00 : $personnel_amount);
					$payout_header['total_deductions']           = (empty($personnel['deduction_amount']) ? 0.00 : $personnel['deduction_amount']);
					$payout_header['net_pay']                    = ($payout_header['total_income'] - $payout_header['total_deductions']);
					$payout_header['net_pay']                    = $payout_header['total_income'];
					
					$table                                       = $this->common->tbl_payout_header;
					$payout_header_id                            = $this->common->insert_general_data($table, $payout_header, TRUE);

					$payout_header_employee[$emp_id]			 = $payout_header_id;
					$payout_header_totals[$emp_id] = array('income'=>0.00, 'deductions'=>0.00);
				}

				$raw_compensation_id 	= $personnel['compensation_id'];
				$inherit_parent_id		= $personnel['inherit_parent_id_flag'];
				$parent_compensation_id	= $personnel['parent_compensation_id'];
				$compensation_id 		= ($inherit_parent_id == YES && ! empty($parent_compensation_id)) 
											? $parent_compensation_id : $raw_compensation_id;
				
				$compensation_details[$emp_id][$compensation_id]['employee_id']      = $emp_id;
				$compensation_details[$emp_id][$compensation_id]['compensation_id']  = $compensation_id;
				$compensation_details[$emp_id][$compensation_id]['raw_compensation_id']  = $raw_compensation_id;
				$compensation_details[$emp_id][$compensation_id]['base_rate']        = isset($personnel['base_rate']) ? $personnel['base_rate'] : NULL;
				$compensation_details[$emp_id][$compensation_id]['amount']           = $personnel_amount;
				
				$compensation_details[$emp_id][$compensation_id]['orig_amount']      = (isset($personnel[KEY_AMOUNT][KEY_ORIG_AMOUNT]) ? $personnel[KEY_AMOUNT][KEY_ORIG_AMOUNT] : 0);
				$compensation_details[$emp_id][$compensation_id]['less_amount']      = (isset($personnel[KEY_AMOUNT][KEY_LESS_AMOUNT]) ? $personnel[KEY_AMOUNT][KEY_LESS_AMOUNT] : 0);
				
				$compensation_details[$emp_id][$compensation_id]['effective_date']   = $payout_date;
				$compensation_details[$emp_id][$compensation_id]['payout_header_id'] = $payout_header_employee[$emp_id];
				
				$payout_header_totals[$emp_id]['income'] +=  $personnel_amount;
															
				if ( ! empty($personnel['deductions']) )
				{
					$deductions = $personnel['deductions'];
					foreach($deductions as $d => $amt)
					{
						$deduction_details[$emp_id][$d]['deduction_id']	= $d;
						$deduction_details[$emp_id][$d]['amount'] 		= $amt;
						$deduction_details[$emp_id][$d]['orig_amount'] 	= $amt;
						$deduction_details[$emp_id][$d]['less_amount'] 	= 0.00;
						
						$deduction_details[$emp_id][$d]['employee_id']    	= $emp_id;
						$deduction_details[$emp_id][$d]['effective_date'] 	= $payout_date;
						$deduction_details[$emp_id][$d]['payout_header_id'] = $payout_header_employee[$emp_id];
						
						$payout_header_totals[$emp_id]['deductions'] += $amt;						
					}
				} 
			}
			
			$payout_details['compensation'] 	= $compensation_details;		
			$payout_details['deduction'] 		= $deduction_details;
			
			// update header totals
			$table	= $this->common->tbl_payout_header;
			foreach ($payout_header_totals as $emp_id => $vals)
			{
				$payout_header 						= array();
				$payout_header['total_income']		= $vals['income'];
				$payout_header['total_deductions']  = $vals['deductions'];
				$payout_header['net_pay']           = ($payout_header['total_income'] - $payout_header['total_deductions']);
				
				$where				= array('payroll_hdr_id' => $payout_header_employee[$emp_id]);
				$this->common->update_general_data($table, $payout_header, $where);				
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		RLog::info('END: _insert_payout_header ['.$payout_summary_id.']');
		
		return $payout_details;
	}	

	/**
	 * This helper method saves payout detail records.
	 * @param array $payroll_details Personnels to be saved
	 * @return array
	 */	
	private function _insert_payout_details($payroll_details)
	{
		RLog::info('START: _insert_payout_details');
		
		try
		{
			$details = $payroll_details['compensation'];
			foreach ($details as $payout)
			{
				foreach ($payout as $k => $detail)
				{
					$payout_detail                    = array();
					$payout_detail['payroll_hdr_id']  		= $detail['payout_header_id'];
					$payout_detail['compensation_id'] 		= $detail['compensation_id'];
					$payout_detail['raw_compensation_id'] 	= $detail['raw_compensation_id'];
					$payout_detail['effective_date']  		= $detail['effective_date'];
					$payout_detail['base_rate']       		= $detail['base_rate'];
					$payout_detail['amount']          		= (empty($detail['amount']) ? 0.00 : $detail['amount']);
					$payout_detail['orig_amount']    		= (empty($detail['orig_amount']) ? 0.00 : $detail['orig_amount']);
					$payout_detail['less_amount']     		= (empty($detail['less_amount']) ? 0.00 : $detail['less_amount']);
					
					$table                            = $this->common->tbl_payout_details;
					$payout_detail_id                 = $this->common->insert_general_data($table, $payout_detail, TRUE);
					
					$detail['payout_detail_id']       = $payout_detail_id;
				}
			}
			
			$details = $payroll_details['deduction'];
			foreach ($details as $payout)
			{
				foreach ($payout as $k => $detail)
				{
					$payout_detail                    	= array();
					$payout_detail['payroll_hdr_id']  	= $detail['payout_header_id'];
					//$payout_detail['deduction_id'] 	  = $detail['deduction_id'];
					$payout_detail['deduction_id'] 	  	= $k;
					$payout_detail['raw_deduction_id']	= $k;
					$payout_detail['effective_date']  	= $detail['effective_date'];
					$payout_detail['amount']          	= $detail['amount'];
					$payout_detail['orig_amount']		= $detail['orig_amount'];
					$payout_detail['less_amount']		= $detail['less_amount'];
					
					$table                            = $this->common->tbl_payout_details;
					$payout_detail_id                 = $this->common->insert_general_data($table, $payout_detail, TRUE);
					
					$detail['payout_detail_id']       = $payout_detail_id;
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
		
		RLog::info('END: _insert_payout_details');
	}	
	

	/**
	 * This helper function handles the computation for compensation.
	 * @param array $compensation_type Contains compensation type record
	 * @param covered_date Date covered
	 * @return array List of personnels with computed compensation amount
	 */
	private function _compute_compensation_type($compensation_type, $payout_summary_id, $included_employees, $covered_date=NULL, $period_params=array())
	{
		RLog::info('START: _compute_compensation_type' . '['.$compensation_type['compensation_id'].']');
		
		$compensation_personnels = array();
		
		try
		{
			$compensation_type_id = $compensation_type['compensation_id'];
			
			$personnels           = array();
			
			// if aggregate, check if prorated
			$prorated_rates     = array();
			$attendance_count	= array();
			if ($compensation_type['pro_rated_flag'] != PRORATE_NA)
			{
				$field          = array('*') ;
				$table          = $this->common->tbl_param_compensation_prorated;
				$where          = array('compensation_id' => $compensation_type_id, 'separated_flag' => NO);
				$order_by		= array("from_val" => 'ASC'); 
				$prorated_rates	= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
				
				$where          				= array('compensation_id' => $compensation_type_id, 'separated_flag' => YES);
				$prorated_rates['separated'] 	= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
			}
			
			// GET EMPLOYMENT TYPE WITH TENURE
			$employment_type_tenure = $this->payroll_proc->get_doh_employ_type();
			if (empty($employment_type_tenure))
				throw new Exception('DOH Employment Type: ' . $this->lang->line('param_not_defined'));			
			
			if ($compensation_type['monetization_flag'] !== YES)
			{
				$personnels = $this->sppayroll->get_personnel_tenure($compensation_type_id, $covered_date, $employment_type_tenure, $included_employees, $period_params);
				RLog::info("-- === -- [$compensation_type_id]");
					RLog::info($personnels);
				RLog::info("-- === --");
			}

			$compensation_type_flag = $compensation_type['compensation_type_flag'];
			$sys_params = array();
			
			switch ($compensation_type_flag)
			{
				case COMPENSATION_TYPE_FLAG_FIXED:
					$param_dates = array();
					if ( ! empty($period_params))
					{
						$param_dates['compensation_start_date'] = $period_params['tenure_period_from'];
						$param_dates['compensation_end_date']	= $period_params['tenure_period_to'];
					}
					
					$compensation_personnels = $this->_compute_fixed_compensation_type($compensation_type, $personnels, $covered_date, $param_dates, $prorated_rates);
					break;
					
				case COMPENSATION_TYPE_FLAG_VARIABLE:
					$compensation_personnels = $this->_compute_variable_compensation_type($compensation_type, $personnels, $covered_date, $prorated_rates);
					break;
					
				case COMPENSATION_TYPE_FLAG_SYSTEM:
					if ($compensation_type['monetization_flag'] == YES)
					{
						RLog::info('MONETIZATION !!!');
						
						$personnels = $this->sppayroll->get_personnel_with_monetize($compensation_type_id, $payout_summary_id, $included_employees);
						$compensation_personnels = $this->_compute_monetization($compensation_type, $personnels);	
					}
					else
					{
						$sys_params = $this->payroll_proc->get_sys_gen_params();

						if ($compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_TAX_REFUND_ANNUAL])
						{
							$compensation_personnels = $this->payroll_tax_diff->compute_tax_refund($personnels, $period_params['tenure_period_to'], TAX_ANNUALIZED, 
									NULL, $included_employees, NULL, TRUE, FALSE, $sys_params);	
						}
						else if ($compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_TAX_REFUND_MONTHLY])
						{
							$compensation_personnels = $this->payroll_tax_diff->compute_tax_refund($personnels, $period_params['tenure_period_to'], TAX_MONTHLY_2316, 
									NULL, $included_employees, NULL, TRUE, FALSE, $sys_params);	
						}						
						else //OTHER SYS GEN
						{
							if (empty($sys_params[PARAM_COMPENSATION_CODE_SALDIFFL]) || empty($sys_params[PARAM_COMPENSATION_CODE_HAZDIFFL])
									|| empty($sys_params[PARAM_COMPENSATION_CODE_LONGEDIFFL]) || empty($sys_params[PARAM_COMPENSATION_ID_HAZARD_PAY]))
								throw new Exception('Sys Gen Params: ' . $this->lang->line('param_not_defined'));
							
							$salary_schedules 			= array();
							$gsis_table 				= array();
							$compensation_type_hazard 	= array();
							if ($compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_SALDIFFL]
									|| $compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_HAZDIFFL]
									|| $compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_LONGEDIFFL] )
							{
								$salary_schedules = $this->sppayroll->get_covered_salary_schedule($period_params['tenure_period_from'], $period_params['tenure_period_to'], NULL);
								
								$arr_key = array('effective_year', 'effective_month', 'salary_grade', 'salary_step');
								$arr_val = array('other_fund_flag' => 'amount');
								$salary_schedules = set_key_value($salary_schedules, $arr_key, $arr_val, FALSE);
	
								if ($compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_SALDIFFL])
									$gsis_table = $this->payroll_common->get_gsis_table($period_params['tenure_period_to']);
									
								else if ($compensation_type['compensation_code'] == $sys_params[PARAM_COMPENSATION_CODE_HAZDIFFL])
								{	
									$prorated_rates 	= array();
									// get pro_rated_flag of Hazard Pay
									if ($compensation_type['pro_rated_flag'] != PRORATE_NA)
									{
										$field          = array('compensation_id', 'from_val', 'to_val', 'percentage', 'separated_flag') ;
										$table          = $this->common->tbl_param_compensation_prorated;
										$where          = array('compensation_id' => $sys_params[PARAM_COMPENSATION_ID_HAZARD_PAY]);
										$order_by		= array('from_val' => 'ASC'); 
										$prorated_rates	= $this->common->get_general_data($field, $table, $where, TRUE, $order_by);
									}
								}
							}
							
							foreach ($personnels as $personnel)
							{
								$compensation_type['employee_id']	= $personnel['employee_id'];
								$compensation_type['salary_grade']	= $personnel['employ_salary_grade'];
								$compensation_type['pay_step'] 		= $personnel['employ_salary_step'];
								$compensation_type['anniv_emp_date']= $personnel['anniv_emp_date'];
								$amount_rec = $this->payroll_common->get_system_generated_amount($compensation_type, $payout_summary_id, 
										$period_params['tenure_period_from'], $period_params['tenure_period_to'], NULL, 
										$sys_params, array(), array(), $salary_schedules, $gsis_table, $prorated_rates);
										
								foreach($amount_rec as $key => $amount)
								{
									if ($key == 'deduction')
									{
										$personnel['deduction_amount'] = 0;
										foreach($amount as $d => $amt)
										{
											$personnel['deduction_amount'] += $amt;
											$personnel['deductions'] = array($d => $amt);
										}
									}
									else
									{
										$personnel['amount'] = $amount;
									}
								}
			
								$compensation_personnels[] = $personnel;
							}
						}
					}
						
					break;
			}
			
		}
		catch (PDOException $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		catch (Exception $e)
		{
			RLog::error($e->getMessage());
			throw $e;
		}
		
		RLog::info('END: _compute_compensation_type');
		
		return $compensation_personnels;
	}	
	
	/**
	 * This helper function handles the computation for compensation with fixed amount.
	 * @param array $compensation_type Contains compensation type record
	 * @param personnels List of personnel with fixed compensation
	 * @param covered_date Date covered
	 * @return array List of personnels with computed compensation amount
	 */
	private function _compute_fixed_compensation_type($compensation_type, $personnels, $covered_date=NULL, $param_dates=array(), $prorated_rates=array())
	{
		RLog::info('START: _compute_fixed_compensation_type ['.$compensation_type['frequency_id'].']');
		
		$compensation_personnels = array();
		
		try
		{
			// get tenure requirement
			$tenure_rqmt_flag     = $compensation_type['tenure_rqmt_flag']; // T - tenure; DP - days present; NA - Not Applicable
			$tenure_rqmt_val      = $compensation_type['tenure_rqmt_val']; // Number of months; NULL if flag is NA
			$tenure_rqmt_val      = ($tenure_rqmt_val == NULL ? 0 : $tenure_rqmt_val);
			
			$prorated_flag = $compensation_type['pro_rated_flag'];
			
			// get amount
			$fixed_amount         = $compensation_type['amount'];
			
			// compute amount based on frequency
			$sys_param_type       = PARAM_FREQUENCY_ONE_TIME_ONLY;
			$sys_param_values     = $this->common->get_sys_param_value($sys_param_type, TRUE);
			foreach ($personnels as $personnel)
			{
				// check if already retired/resigned
				$separated_flag = FALSE;
				if ( ! empty($personnel['separation_mode_id']))
				{
					if ( empty($prorated_rates['separated']))
					{
		 				$personnel[KEY_AMOUNT][KEY_AMOUNT] = 0.00;
						$personnel[KEY_AMOUNT][KEY_ORIG_AMOUNT] = 0.00;
 						$personnel[KEY_AMOUNT][KEY_LESS_AMOUNT] = 0.00;
						$compensation_personnels[] = $personnel;
						continue;
					}
					$separated_flag = TRUE;
				}
				
				// if with tenure requirement and not prorated, check if employee satisfies the tenure requirement
				if ($tenure_rqmt_flag != TENURE_RQMT_NA && $prorated_flag == PRORATE_NA)
				{
					// if employee's tenure is less than the requirement, not entitled
					if ($personnel['tenure_in_months'] < $tenure_rqmt_val)
					{
		 				$personnel[KEY_AMOUNT][KEY_AMOUNT] 		= 0.00;
						$personnel[KEY_AMOUNT][KEY_ORIG_AMOUNT] = 0.00;
 						$personnel[KEY_AMOUNT][KEY_LESS_AMOUNT] = 0.00;
						$compensation_personnels[] = $personnel;
						continue;
					}
				}
				
				if (!array_search($compensation_type['frequency_id'], $sys_param_values))
				{
					$compensation_type['employee_id'] = $personnel['employee_id'];
					RLog::info('--S: FIXED PARAM DATES--');
						RLog::info($covered_date);
						RLog::info($param_dates);
					RLog::info('--E: FIXED PARAM DATES--');
					$use_param_dates = TRUE;
					if (empty($param_dates))
						$use_param_dates = FALSE;

					//$fixed_amount 		= $this->payroll_common->compute_amount_with_frequency($compensation_type, $covered_date, $use_param_dates, $param_dates);
					$fixed_amount_arr	= $this->payroll_common->compute_amount_with_frequency($compensation_type, $covered_date, $use_param_dates, $param_dates);;
					$fixed_amount		= $fixed_amount_arr[KEY_AMOUNT]; 
				}				
				
				$amount = $fixed_amount;
				if ($prorated_flag != PRORATE_NA)
				{
					$salary_grade		= (isset($compensation_type['employ_salary_grade']) ? $compensation_type['employ_salary_grade'] : NULL);
					$tenure_in_months	= ( $separated_flag ? $personnel['tenure_months_year'] : $personnel['tenure_in_months']);
					$prorated_rates_use	= ( $separated_flag ? $prorated_rates['separated'] : $prorated_rates);
					RLog::info('PRORATE: ['.$personnel['employee_id'].']['.$personnel['separation_mode_id'].'] ['.$tenure_in_months.']');
					
					$amount_arr			= $this->payroll_common->compute_prorated_value($compensation_type, $amount, $tenure_in_months, $salary_grade, $prorated_rates_use, $separated_flag);
					$amount 			= $amount_arr[KEY_AMOUNT];
				}
				
 				$personnel[KEY_AMOUNT][KEY_AMOUNT] 		= $amount;
 				$personnel[KEY_AMOUNT][KEY_ORIG_AMOUNT] = $amount;
 				$personnel[KEY_AMOUNT][KEY_LESS_AMOUNT] = 0;
				$compensation_personnels[] = $personnel;
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}

		//RLog::info($compensation_personnels);
		RLog::info('END: _compute_fixed_compensation_type');
		
		return $compensation_personnels;
	}
	
	/**
	 * This helper function handles the computation for compensation with variable amount.
	 * @param array $compensation_type Contains compensation type record
	 * @param personnels List of personnel with fixed compensation
	 * @param covered_date Date covered
	 * @return array List of personnels with computed compensation amount
	 */
	private function _compute_variable_compensation_type($compensation_type, $personnels, $covered_date=NULL, $prorated_rates=array())
	{
		RLog::info('START: _compute_variable_compensation_type ['.$covered_date.']');
		
		$compensation_personnels = array();
		
		try
		{
			$compensation_type_id = $compensation_type['compensation_id'];
			$multiplier_id        = $compensation_type['multiplier_id'];
			$multiplier_rate      = $compensation_type['rate'];
			$multiplier_rate      = (($multiplier_rate == NULL ? 0.00 : $multiplier_rate) / ONE_HUNDRED);
			
			// get tenure requirement
			$tenure_rqmt_flag     = $compensation_type['tenure_rqmt_flag']; // T - tenure; A - aggregate; DP - days present; NA - Not Applicable
			$tenure_rqmt_val      = $compensation_type['tenure_rqmt_val']; // Number of months; NULL if flag is NA
			$tenure_rqmt_val      = ($tenure_rqmt_val == NULL ? 0 : $tenure_rqmt_val);
			
			$prorated_flag = $compensation_type['pro_rated_flag'];			
			
			foreach ($personnels as $personnel)
			{
				RLog::info('VARIABLE Loop Personnels ['.$multiplier_rate.'] ['.$tenure_rqmt_val.'] ['.$personnel['tenure_in_months'].'] ['.$personnel['separation_mode_id'].']');
				
				
				// check if already retired/resigned
				$separated_flag = FALSE;
				if ( ! empty($personnel['separation_mode_id']))
				{
					if ( empty($prorated_rates['separated']))
					{
		 				$personnel[KEY_AMOUNT][KEY_AMOUNT] = 0.00;
						$compensation_personnels[] = $personnel;
						continue;
					}
					$separated_flag = TRUE;
				}				
				
				// if with tenure requirement and not prorated, check if employee satisfies the tenure requirement
				if ($tenure_rqmt_flag != TENURE_RQMT_NA && $prorated_flag == PRORATE_NA)
				{
					// if employee's tenure is less than the requirement, not entitled
					if ($personnel['tenure_in_months'] < $tenure_rqmt_val)
					{
		 				$personnel[KEY_AMOUNT][KEY_AMOUNT] = 0.00;
						$compensation_personnels[] = $personnel;
						continue;
					}
				}				

				// get amount
				$amount = 0.00;
				switch ($multiplier_id) {
					case MULTIPLIER_BASIC_SALARY:
						$amount = $personnel['employ_monthly_salary'];
						break;
					/*
					case MULTIPLIER_TAXABLE_INCOME:
						$employee_id         = $personnel['employee_id'];
						$p_employee          = array($employee_id);
						//$proj_taxable_income = $this->payroll_lib->compute_taxable_income($p_employee, $covered_date);
						$amount              = $proj_taxable_income[$employee_id];
						$amount              = ROUND( ($amount/12), 2);
						break;
					*/
				}
				
				$amount                    = $amount * $multiplier_rate;
				
				$personnel['base_rate']    = $compensation_type['rate'];
				
				$amount_arr = array();
				if ($prorated_flag === PRORATE_NA)
				{
					$amount_arr[KEY_AMOUNT]			= $amount;
					$amount_arr[KEY_ORIG_AMOUNT]	= $amount;
					$amount_arr[KEY_LESS_AMOUNT]	= 0.00;
				}
				else
				{
					$salary_grade		= isset($compensation_type['employ_salary_grade']) ? $compensation_type['employ_salary_grade'] : NULL;
					$tenure_in_months	= ( $separated_flag ? $personnel['tenure_months_year'] : $personnel['tenure_in_months']);
					$prorated_rates_use	= ( $separated_flag ? $prorated_rates['separated'] : $prorated_rates);
					RLog::info('PRORATE: ['.$personnel['employee_id'].']['.$personnel['separation_mode_id'].'] ['.$tenure_in_months.']');
					
					$amount_arr 			= $this->payroll_common->compute_prorated_value($compensation_type, $amount, $tenure_in_months, $salary_grade, $prorated_rates_use, $separated_flag);					
				}
				
				$personnel[KEY_AMOUNT] = $amount_arr;
				
				$compensation_personnels[] = $personnel;
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		RLog::info($compensation_personnels);
		RLog::info('END: _compute_variable_compensation_type');
		
		return $compensation_personnels;
	}
	
	private function _compute_monetization($compensation_type, $personnels)
	{
		RLog::info('START: _compute_monetization');
		RLog::info($personnels);
		
		$compensation_personnels = array();
		try
		{
			// get codes for working days and monetization factor
			$sys_param_type          = array(PARAM_WORKING_DAYS, PARAM_MONETIZATION_FACTOR);
			$sys_param_values        = $this->common->get_sys_param_value($sys_param_type, TRUE);
			$sys_param_factor        = NULL;
			$sys_param_days_in_month = NULL;
			foreach ($sys_param_values as $value)
			{
				switch ($value['sys_param_type'])
				{
					case PARAM_WORKING_DAYS:
						$sys_param_days_in_month = $value['sys_param_value'];
						break;
					case PARAM_MONETIZATION_FACTOR:
						$sys_param_factor = $value['sys_param_value'];
						break;
				}
			}
			// throw exception if at least one of the variables is not defined
			if( ! isset($sys_param_factor) OR ! isset($sys_param_days_in_month) )
				throw new Exception($this->lang->line('sys_param_not_defined'));

			
			foreach ($personnels as $personnel)
			{
				// get basic salary of employee
				$basic_salary              = $personnel['employ_monthly_salary'];
				
				// multiply daily rate by factor
				$basic_salary              = round( ($basic_salary * $sys_param_factor), 2, PHP_ROUND_HALF_UP);
				
				// multiply by num of day applied
				$monetized_amount          = round( ($basic_salary * $personnel['num_days']), 2, PHP_ROUND_HALF_UP);
				
				$personnel['amount'][KEY_AMOUNT] 	  = $monetized_amount;
				$personnel['amount'][KEY_ORIG_AMOUNT] = $monetized_amount;
				$personnel['amount'][KEY_LESS_AMOUNT] = 0.00;
				$compensation_personnels[] = $personnel;
			}
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			throw $e;
		}
		
		RLog::info('END: _compute_monetization');
		
		return $compensation_personnels;		
	}	
	

	/*
	 * END: Helper functions
	 */
	
	public function prepare_special_payroll($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		try
		{
			$resources = array();
			$resources['load_js']  = array(JS_SELECTIZE, JS_DATETIMEPICKER, JS_DATATABLE, JS_NUMBER);
			$resources['load_css'] = array(CSS_SELECTIZE, CSS_DATETIMEPICKER, CSS_DATATABLE);
			$resources['load_modal']		= array(
					'modal_employee_pds'		=> array(
							'controller'	=> 'Payroll_quick_links',
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_employee_pds',
							'multiple'		=> true,
							'height'		=> '400px',
							'size'			=> 'sm',
							'title'			=> 'Employee Information'
					)
				);			
			
			if ($action != ACTION_ADD)
			{
				if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
				{
					throw new Exception($this->lang->line('err_invalid_request'));
				}
				if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
				{
					throw new Exception($this->lang->line('err_unauthorized_access'));
				}
			}			
	
			$data = $this->_set_modal_special_payroll($action, $id, $token, $salt, $module, $employee_id);
			if ($action == ACTION_VIEW OR $action == ACTION_EDIT)
				{
					$summary_id		= $id;
					$params 		= array('summary_id' => $summary_id);
					
					$val			= $this->sppayroll->get_special_payroll($params);
					
					$val['effective_date'] = date('Y/m/d', strtotime($val['effective_date']));
	
					if ( ! empty($val['tenure_period_start_date']))
						$val['tenure_period_start_date'] = date('Y/m/d', strtotime($val['tenure_period_start_date']));
	
					if ( ! empty($val['tenure_period_end_date']))
						$val['tenure_period_end_date'] = date('Y/m/d', strtotime($val['tenure_period_end_date']));
	
					if ( ! empty($val['rating_period_start_date']))
						$val['rating_period_start_date'] = date('Y/m/d', strtotime($val['rating_period_start_date']));
	
					if ( ! empty($val['rating_period_end_date']))
						$val['rating_period_end_date'] = date('Y/m/d', strtotime($val['rating_period_end_date']));
	
	
					$data['val']	= $val;		
				}
				
			//S: office list
			$fields = array('A.office_id','B.name AS office_name');
			$tables = array(
				'main' => array(
					'table' => $this->common->tbl_param_offices,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->common->db_core . '.' . $this->common->tbl_organizations,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.org_code = B.org_code'
	 			)
			);
			$where = array('A.active_flag' => 'Y');
			$data['office_list'] = $this->common->get_general_data($fields, $tables, $where);
			//E: office list
	
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Prepare Special Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/special_payroll/prepare_special_payroll/".$action."/".$id."/".$token."/".$salt."/".$module;
			set_breadcrumbs($breadcrumbs, FALSE);
			
			$this->template->load('special_payroll/prepare_special_payroll', $data, $resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
	}
	
	public function get_payroll_employee_list($action, $module, $compensation_type_id=0, $payroll_id=0)
	{
		try
		{
			// clear previously selected employees
			//$sess_sel_employee_id = $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $module;
			//$this->session->unset_userdata($sess_sel_employee_id);
			
			$params		= get_params();
			$aaData 	= array();
			$employees	= array();
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => 0,
				"iTotalDisplayRecords" => 0,
				"aaData" => $aaData
			);		
			
			//RLog::info($params);

			if ($action == ACTION_VIEW)
				$compensation_type_id	= isset($params['sp_type_id']) ? $params['sp_type_id'] : $compensation_type_id;
			else
				$compensation_type_id 	= isset($params['compensation_type_id']) ? $params['compensation_type_id'] : $compensation_type_id;

			$compensation_type_id	= explode('|', $compensation_type_id);
			
			$monetize_flag 			= (count($compensation_type_id) > 2 ? $compensation_type_id[2] : 0);
			$compensation_type_id 	= (count($compensation_type_id) > 0 ? $compensation_type_id[0] : 0);
			
			$payroll_id 			= isset($params['id']) ? $params['id'] : $payroll_id;			
			
			if ($action == ACTION_ADD)
			{
				if ($compensation_type_id < 1) {
					echo json_encode( $output );
					return;
				}
			}

			if ($monetize_flag === YES)
				$employee_rec		= $this->sppayroll->get_table_monetize_employee_list($payroll_id, $params);
			else
				$employee_rec		= $this->sppayroll->get_table_employee_list($compensation_type_id, $params);

			$employees				= $employee_rec['data'];
			$iFilteredTotal			= $employee_rec['filtered_length'];			
			
			$all_employee_rec		= $this->sppayroll->get_all_table_employee_list($compensation_type_id, $payroll_id, $monetize_flag);

			$fields                 = array("GROUP_CONCAT(CAST(employee_id as char)) as employees");
			$tables                 = $this->common->tbl_payout_employee;
			$key                    = $this->get_hash_key('payroll_summary_id');
			$where                  = array();
			$where['included_flag'] = YES;
			$where[$key]            = $payroll_id;
			$selected_result     	= $this->common->get_general_data($fields, $tables,$where,FALSE);
			$selected_employees 	= explode(',', $selected_result['employees']);
			$checked = '';
			
			$permission_view		= $this->permission->check_permission($module, ACTION_VIEW) && $action == ACTION_EDIT;
			$permission_edit		= $this->permission->check_permission($module, ACTION_EDIT) && $action == ACTION_EDIT;
			
			$checked_employees = array();
// 			$sess_sel_employee_id 	= $this->_get_selected_employee_sess_var();
// 			$sess_sel_employees 	= $this->_get_selected_employee_sess_val($sess_sel_employee_id);
// 			if (empty($sess_sel_employees))
// 			{
				// set the employees in session var
				foreach($all_employee_rec as $key => $row)
				{
					$checked_num = 0;
					if($action == ACTION_ADD)
					{
						if(EMPTY($row['separation_mode_id']))
							$checked_num = 1;
					} 
					else if(!EMPTY($selected_employees))		
					{
						if(in_array($row['employee_id'], $selected_employees))
							$checked_num = 1;
							
					}
					$checked_employees[$row['employee_id']] = $checked_num;
				}
				// set the employees in session var
				$sess_sel_employees = $this->_set_selected_employees($checked_employees);				
// 			}
			RLog::info('-- S: SELECTED EMPLOYEES --');
			RLog::info($sess_sel_employees);
			RLog::info('-- E: SELECTED EMPLOYEES --');			
			
			if($employees)
			{
				$output['iTotalRecords']= $iFilteredTotal['cnt'];
				$output['iTotalDisplayRecords']= $iFilteredTotal['cnt'];
				foreach($employees as $key => $aRow)
				{
					$id 			= $this->hash($aRow['employee_id']);
					$salt			= gen_salt();
					$token_view 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);

					$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;

					$checked = '';
					if($sess_sel_employees[$aRow['employee_id']] == 1)
					{
						$checked = 'checked';
					}
					
					if ($action == ACTION_VIEW)
						$disabled = 'disabled';					

					$row = array();
	
					$row[] =  '<center> <input type="checkbox" name="selected_employees['.$aRow['employee_id'].']" id="check_'.$aRow['employee_id'].'" value="'.$aRow['employee_id'].'"class="ind_checkbox filled-in" '.$checked.' '. $disabled . '/>
									  <label for="check_'.$aRow['employee_id'].'"></label> </center>';
					$row[] =  $aRow['agency_employee_id'];
					$row[] =  $aRow['employee_name'];
					$row[] =  $aRow['office_name'];;
					$row[] =  $aRow['employment_status_name'];

					$action_col = "<div class='table-actions table-links'>";
					if($permission_view)
					{
						$action_col .= "<a href='#!' class='tooltipped md-trigger m-n' data-modal='modal_employee_pds' data-tooltip='PDS' data-position='bottom' data-delay='50' onclick=\"modal_employee_pds_init('".$url_view."')\"><i class='flaticon-user153 p-n'></i></a>";
					}
					$action_col .= "</div>";
					
					$row[] = $action_col;

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
	
	private function _save_payroll_employees($payroll_summary_id, $monetize_flag, $params)
	{
		try
		{
// 			$selected_employees = ( ! empty($params['selected_employees']) ? $params['selected_employees'] : array() ) ;
			$sess_sel_employee_id = $this->_get_selected_employee_sess_var();
			
			$sess_sel_employees = $this->_get_selected_employee_sess_val($sess_sel_employee_id);
			$selected_employees = array();
			foreach($sess_sel_employees as $emp_id => $checked_num)
			{
				if ($checked_num == 1)
					$selected_employees[$emp_id] = $emp_id;
			}
			
			RLog::info('SAVE THESE EMPLOYEES');
			RLog::info($selected_employees);
			RLog::info($params['selected_employees']);
			
			//ORIGIN INSIDE IF STATEMENT
			$where = array('payroll_summary_id' => $payroll_summary_id);
			$table = $this->common->tbl_payout_employee;
			$this->common->delete_general_data($table, $where);
			
			
			if(!EMPTY($selected_employees) AND !EMPTY($payroll_summary_id))
			{

				$compensations        = explode('|', $params['compensation_type_id']);
			
				$compensation_type_id = $compensations[0];
				$tenure_period_to     = format_date($params['tenure_period_to'],'Y-m-d');
				
				if ($monetize_flag === YES)
				{
					$hash_payout_summary_id = $this->hash($payroll_summary_id); 
					$employee_rec	= $this->sppayroll->get_table_monetize_employee_list($hash_payout_summary_id, NULL);
				}
				else
					$employee_rec	= $this->sppayroll->get_table_employee_list($compensation_type_id,$tenure_period_to);
				
				$employees			= $employee_rec['data'];
				
				RLog::info('save this 2: ' );
				RLog::info($employees);				

				if($employees)
				{
					$fields = array();
					foreach ($employees as  $value)
					{

						$included_flag	= (isset($selected_employees[$value['employee_id']])) ? YES:NO;
						$fields[]		= array(
											'payroll_summary_id' => $payroll_summary_id,
											'employee_id'        => $value['employee_id'],
											'included_flag'      => $included_flag
										);
						if ($monetize_flag === YES AND $included_flag === YES)
							$this->payroll_proc->update_monetize_leave_details($payroll_summary_id, YES, $value['leave_detail_id'], FALSE);
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
	
	public function delete_payroll()
	{
		try
		{
			$flag = 0;
			$params			= get_params();
			RLog::info('DELETE');
			RLog::info($params);
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
			$field 					= array('payroll_summary_id', 'payout_type_flag', 'bank_id', 'compensation_id', 
										'monetize_flag', 'tenure_period_start_date', 'tenure_period_end_date',
										'certified_by', 'approved_by', 'certified_cash_by', 'payout_status_id') ;
			$table					= $this->common->tbl_payout_summary;
			$where					= array();
			$key_id					= $this->get_hash_key('payroll_summary_id');
			$where[$key_id]			= $id;
			$prev_data 				= $this->common->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			// child tables
			$payroll_proc = modules::load('main/payroll_process');
			$payroll_proc->clear_payroll_data($id);
			// payout_history
			$where					= array($this->get_hash_key('payout_summary_id') => $id);
			$table 					= $this->common->tbl_payout_history;
			$this->common->delete_general_data($table, $where);
			// summary table
			$where					= array($key_id => $id);
			$table 					= $this->common->tbl_payout_summary;
			$this->common->delete_general_data($table, $where);
			
			// if monetization, update employee_leave_details.paid_flag to N
			if ($prev_data['monetize_flag'] === YES)
			{
				$upd_table 	= $this->common->tbl_employee_leave_details;
				$upd_fields	= array();
				$upd_fields['paid_flag']		= NO;
				$upd_fields['payout_summary_id']= NULL;
				
				$upd_where 	= array();
				$upd_where['payout_summary_id'] = $prev_data['payroll_summary_id'];
				
				$this->common->update_general_data($upd_table, $upd_fields, $upd_where);				
			}
			
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
		
		$response 					= array(
			"flag" 					=> $flag,
			"msg" 					=> $msg,
			"reload" 				=> 'datatable',
			"table_id" 				=> 'payroll_list',
			"path"					=> PROJECT_MAIN . '/special_payroll/get_special_payroll_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}	
	
	
	private function _get_selected_employee_sess_var()
	{
		$params	= get_params();
		RLog::info("Logged-in User: " . $this->session->user_pds_id);
		
		return $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $params['module'];
	}	
	
	private function _get_selected_employee_sess_val($sess_sel_employee_id)
	{
		$sess_sel_employees = NULL;
		if ($this->session->has_userdata($sess_sel_employee_id))
			$sess_sel_employees = $this->session->userdata($sess_sel_employee_id);
		
		return $sess_sel_employees;
	}
	
	
	private function _set_selected_employee($emp_id, $checked_num, $all_employees=0)
	{
		$sess_sel_employee_id = $this->_get_selected_employee_sess_var();
		$sess_sel_employees = $this->_get_selected_employee_sess_val($sess_sel_employee_id);
		
		
		if($emp_id == 'all') {
// 			$sess_sel_employee_id = $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $data['module'];
// 			$this->session->unset_userdata($sess_sel_employee_id);
			$sess_sel_employees = array_fill_keys(array_keys($sess_sel_employees), $checked_num);
			$this->session->set_userdata($sess_sel_employee_id, $sess_sel_employees);
		} else {
			$sess_sel_employees[$emp_id] = $checked_num;
// 			if($sess_sel_employees['all'] == 1 AND $checked_num == 0) {
// 				$sess_sel_employees['all'] = 0;
// 			} 
			$this->session->set_userdata($sess_sel_employee_id, $sess_sel_employees);
			
		}
		
		
		RLog::info('session var ['.$sess_sel_employee_id.']');
		RLog::info('-- S: SELECTED EMPLOYEES --');
		RLog::info($this->session->userdata($sess_sel_employee_id));
		RLog::info('-- E: SELECTED EMPLOYEES --');
		
		return $this->session->userdata($sess_sel_employee_id);
	}	
	
	public function set_selected_employee($emp_id=0, $checked_num=0, $all_employees=0)
	{
		$info	= array('success' => 1);
		$params = get_params();
		$sess_sel_employees = $this->_set_selected_employee($emp_id, intval($checked_num), $all_employees);
		
		echo json_encode($info);
	}	

	
	public function set_compensation_type_change()
	{
		$params	= get_params();
		
		// clear previously selected employees
		$sess_sel_employee_id = $this->session->user_pds_id . '_' . $this->session->user_id . '_' . $data['module'];
		$this->session->unset_userdata($sess_sel_employee_id);
		
		$info	= array('success' => 1);

		echo json_encode($info);
	}	
	private function _set_selected_employees($selected_employees_ui)
	{
		$sess_sel_employee_id = $this->_get_selected_employee_sess_var();
		$sess_sel_employees = $this->_get_selected_employee_sess_val($sess_sel_employee_id);
		foreach ($selected_employees_ui as $emp_id => $checked_num)
		{
			$sess_sel_employees[$emp_id] = $checked_num;			
		}
		
		$this->session->set_userdata($sess_sel_employee_id, $sess_sel_employees);
		
		return $this->session->userdata($sess_sel_employee_id);
	}
	public function is_selected_all()
	{
		$sess_sel_employee_id = $this->_get_selected_employee_sess_var();
		$sess_sel_employees = $this->_get_selected_employee_sess_val($sess_sel_employee_id);
		$is_select_all = FALSE;
		if( ! EMPTY($sess_sel_employees) && ! IN_ARRAY(0, $sess_sel_employees)) 
		{
				$is_select_all = TRUE;
		}
		$data = array('is_select_all' => $is_select_all);
		echo json_encode( $data );
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
	
}


/* End of file Special_payroll.php */
/* Location: ./application/modules/main/controllers/Special_payroll.php */