<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Voucher_process extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payroll_voucher_model', 'voucher');
		$this->payroll_voucher = modules::load('main/payroll_voucher');

	}

	public function display_payroll_voucher_process($action, $id, $token, $salt, $module)
	{
		try
		{
			$data      =  array();
			$resources = array();
			
			
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token; 
			$data['module'] = $module;
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			/*BREADCRUMBS*/
			$breadcrumbs       = array();
			$key               = "Payroll"; 
			$breadcrumbs[$key] = PROJECT_MAIN."/payroll_voucher";
			$key               = "Employee Voucher"; 
			$breadcrumbs[$key] = PROJECT_MAIN."/payroll_voucher";
			$key               = "Process Employee voucher"; 
			$breadcrumbs[$key] = "";
			set_breadcrumbs($breadcrumbs, TRUE);
			
			$this->template->load('voucher/display_voucher_payroll_process', $data, $resources);
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

	public function get_tab($form, $action, $id, $token, $salt, $module)
	{

		try
		{
			$data      =  array();
			$resources = array();
			
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}			

			switch ($form)
			{
				case 'tab_voucher_process':
					$resources['load_js']  = array(JS_SELECTIZE);
					$resources['load_css'] = array(CSS_SELECTIZE);	

					$permission_process = $this->permission->check_permission($module, ACTION_PROCESS);
					$data['permission'] = EMPTY($permission_process) ? 'disabled' : '';
					$field              = array("*","DATE_FORMAT(D.effective_date,'%m/%d/%Y') as effective_date") ;
					$tables = array(
						'main' => array(
							'table'		=> $this->voucher->tbl_vouchers,
							'alias'		=> 'A',
						),
						't2' => array(
							'table'		=> $this->voucher->tbl_payout_summary,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id',
						),
						't3' => array(
							'table'		=> $this->voucher->tbl_payout_header,
							'alias'		=> 'C',
							'type'		=> 'join',
							'condition'	=> 'B.payroll_summary_id = C.payroll_summary_id',
						),
						't4' => array(
							'table'		=> $this->voucher->tbl_payout_details,
							'alias'		=> 'D',
							'type'		=> 'join',
							'condition'	=> 'C.payroll_hdr_id = D.payroll_hdr_id',
						),
						't5' => array(
							'table'     => $this->voucher->tbl_employee_identifications,
							'alias'     => 'E',
							'type'      => 'join',
							'condition' => 'A.bank_account = E.identification_value',
						)
					);
					$where                = array();
					$key                  = $this->get_hash_key('A.payroll_summary_id');
					$where[$key]          = $id;
					$data['voucher_info'] = $this->voucher->get_general_data($field, $tables, $where, FALSE);
					$voucher_status       = $data['voucher_info']['voucher_status_id'];


					$field                = array("*") ;
					$table                = $this->voucher->tbl_param_payout_status;
					$where                = array();
					$where['active_flag'] = YES;
					if($voucher_status != PAYOUT_STATUS_PAID) $where['payout_status_id'] = array(PAYOUT_STATUS_PAID,array('!='));
					$data['payout_status'] = $payout_status = $this->voucher->get_general_data($field, $table, $where, TRUE);
					
					$resources['single'] = array(
						'payout_status' => $data['voucher_info']['voucher_status_id']
					);

					$data['has_permission'] = FALSE;

					foreach ($payout_status as $key => $status) {
						if($status['payout_status_id'] == $data['voucher_info']['voucher_status_id'])
							$data['has_permission'] = $this->permission->check_permission($module, $status['action_id']);
					}
					

				break;

				case 'tab_voucher_history':
					$resources['load_css'] = array(CSS_DATATABLE);
					$resources['load_js']  = array(JS_DATATABLE);
					$post_data             = array(	
							'payroll_summary_id' => $id
					);
					$resources['datatable'][] = array('table_id' => 'table_voucher_history', 'path' => 'main/voucher_process/get_voucher_history_list', 'advanced_filter' => true,'post_data' => json_encode($post_data));
					
				break;
	
			}
	
			$this->load->view('voucher/tabs/'.$form, $data);
			$this->load_resources->get_resource($resources);
	
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
	public function get_voucher_history_list()
	{

		try
		{
			$params   = get_params();
			
			$aColumns       = array("CONCAT(B.first_name, ' ',LEFT(B.middle_name, 1),' ',B.last_name, ' ', LEFT(B.ext_name, 3)) as processed_by","DATE_FORMAT(A.hist_date,'%M %d, %Y %h:%i %p') as hist_date","C.payout_status_name", "A.remarks");
			$bColumns       = array("CONCAT(B.first_name, ' ',LEFT(B.middle_name, 1),' ',B.last_name, ' ', LEFT(B.ext_name, 3))", "DATE_FORMAT(A.hist_date,'%M %d, %Y  %h:%i %p')", "A.remarks", "C.payout_status_name");
			
			$vouchers     = $this->voucher->get_voucher_history_list($aColumns, $bColumns, $params);
			$iTotal         = $this->voucher->history_total_length($params['payroll_summary_id']);
			$iFilteredTotal = $this->voucher->history_filtered_length($aColumns, $bColumns, $params);

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module = MODULE_PAYROLL_VOUCHER;
			foreach ($vouchers as $aRow):
				$cnt++;
				$row                = array();
				
				$row[] =  $aRow['processed_by'];
				$row[] =  $aRow['hist_date'];
				$row[] =  $aRow['remarks'];
				$row[] =  $aRow['payout_status_name'];
				
				$action = "<div class='table-actions'>";	
				//$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_voucher' onclick=\"modal_voucher_init('".$url_view ."')\" data-delay='50'></a>";
				//$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_voucher' onclick=\"modal_voucher_init('".$url_edit."')\" data-delay='50'></a>";
				
				//$action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				//$action .= "<a href='javascript:;' class='process tooltipped'  data-tooltip='Process' data-position='bottom' data-delay='50' onclick=\"content_form('voucher_process/display_payroll_voucher_process/".$url_process."', '".PROJECT_MAIN."')\"></a>"; 
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

public function process_voucher()
	{
		try
		{			
			$status     = FALSE;
			$message    = "";
			$reload_url = "";

			$params = get_params();
			$action = $params['action'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			$id     = $params['id'];
			$module = $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_voucher($params);

			$valid_data['payout_status'] = ($valid_data['payout_status'] == PAYOUT_STATUS_RETURN ? PAYOUT_STATUS_FOR_PROCESSING : $valid_data['payout_status']);
			
			Main_Model::beginTransaction();	

			$fields                     = array() ;
			$fields['payout_status_id'] = $valid_data['payout_status'];

			$where       = array();
			$key         = $this->get_hash_key('payroll_summary_id');
			$where[$key] = $id;
			$table       = $this->voucher->tbl_payout_summary;
			$this->voucher->update_general_data($table,$fields,$where);

			$fields                      = array() ;
			$fields['voucher_status_id'] = $valid_data['payout_status'];

			$where       = array();
			$key         = $this->get_hash_key('payroll_summary_id');
			$where[$key] = $id;
			$table       = $this->voucher->tbl_vouchers;
			$this->voucher->update_general_data($table,$fields,$where);

			$field       = array("*") ;
			$table       = $this->voucher->tbl_employee_personal_info;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $this->session->userdata("user_pds_id");
			$pds_info    = $this->voucher->get_general_data($field, $table, $where, FALSE);	

			$field        = array("*");
			$table        = $this->voucher->tbl_vouchers;
			$where        = array();
			$key          = $this->get_hash_key('payroll_summary_id');
			$where[$key]  = $id;
			$voucher_info = $this->voucher->get_general_data($field, $table, $where, FALSE);
			
			$user = $this->voucher->get_general_data(array('user_id'),$this->voucher->tbl_associated_accounts, array('employee_id' => $pds_info['employee_id']), FALSE);

			$this->payroll_voucher->_insert_payout_notifications($params ['module'], $voucher_info['payroll_summary_id'], 
						$valid_data['payout_status'], $user['user_id']);

			$fields                      = array() ;
			$fields['payout_summary_id'] = $voucher_info['payroll_summary_id'];
			$fields['employee_id']       = $pds_info['employee_id'];
			$fields['action_id']         = $action;
			$fields['payout_status_id']  = $valid_data['payout_status'];
			$fields['remarks']           = $valid_data['remarks'];
			$fields['hist_date']         = date('Y-m-d H:i:s');
			
			$table = $this->voucher->tbl_payout_history;
			$this->voucher->insert_general_data($table,$fields,FALSE);

			$audit_table[]  = $this->voucher->tbl_payout_history;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array();
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_INSERT;	

			/*START CODE BLOCK: UPDATE EFFECTIVE DATE IN PAYOUT DETAILS*/
			$field         = array("return_flag") ;
			$table         = $this->voucher->tbl_param_payout_status;
			$where         = array('payout_status_id' => $valid_data['payout_status']);
			$payout_status = $this->voucher->get_general_data($field, $table, $where, FALSE);

			if($payout_status['return_flag'] > 0)
			{
				$field       = array("payroll_hdr_id") ;
				$table       = $this->voucher->tbl_payout_header;
				$where       = array();
				$key         = $this->get_hash_key('payroll_summary_id');
				$where[$key] = $id;
				$payroll_hdr = $this->voucher->get_general_data($field, $table, $where, FALSE);

				if($payroll_hdr['payroll_hdr_id'])
				{
					$fields                   = array() ;
					$fields['effective_date'] = $voucher_info['voucher_date'];
					
					$where                    = array();
					$where['payroll_hdr_id']  = $payroll_hdr['payroll_hdr_id'];
					$table                    = $this->voucher->tbl_payout_details;
					$this->voucher->update_general_data($table,$fields,$where);

				}
			}
			/*END CODE BLOCK: UPDATE EFFECTIVE DATE IN PAYOUT DETAILS*/
			$audit_activity = "Voucher has been processed.";		

			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();

			$status = true;
			$message = $this->lang->line('data_saved');
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}
	private function _validate_voucher($params)
	{
		try
		{
						
			$fields                  = array();			
			$fields['payout_status'] = "Payout Status";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_voucher($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_voucher($params)
	{
		try
		{
			$validation['payout_status'] = array(
					'data_type' => 'digit',
					'name'		=> 'Payout Status',
					'max_len'	=> 11
			);
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}

/* End of file Voucher_process.php */
/* Location: ./application/modules/main/controllers/Voucher_process.php */