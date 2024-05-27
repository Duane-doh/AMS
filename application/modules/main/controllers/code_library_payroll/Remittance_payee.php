<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Remittance_payee extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_REMITTANCE_PAYEE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}

	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data = array();
			$resources = array();
			$data['action_id'] = $action_id;
			$resources['load_css'][] = CSS_DATATABLE;
			$resources['load_js'][] = JS_DATATABLE;
			$resources['datatable'][] = array('table_id' => 'remittance_payee_table', 'path' => 'main/code_library_payroll/remittance_payee/get_remittance_payee_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] = array(
					'modal_remittance_payee' 	=> array(
							'controller' 		=> 'code_library_payroll/'.__CLASS__,
							'module' 			=> PROJECT_MAIN,
							'method' 			=> 'modal_remittance_payee',
							'multiple' 			=> TRUE,
							'height' 			=> '200px',
							'size' 				=> 'sm',
							'title' 			=> 'Remittance Payee'
					)
			);
			
			$resources['load_delete'] 		= array(
					'code_library_payroll/'.__CLASS__,
					'delete',
					PROJECT_MAIN
			);
		
			$data['action_id'] = $action_id;
		
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
		
		$this->load->view('code_library/tabs/remittance_payee', $data);
		$this->load_resources->get_resource($resources);
		
	}

	public function get_remittance_payee_list()
	{

		try
		{
			$params 					=get_params();

			$aColumns					= array("*");
			$bColumns					= array("remittance_payee_name", "report_short_code", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_remittance_payees;
			$where						= array();
			$remittance_payee			= $this->cl->get_remittance_payee_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(remittance_payee_id)) AS count"), $this->cl->tbl_param_remittance_payees, NULL, false);

			$output 					= array(
					"sEcho" 				=> intval($_POST['sEcho']),
					"iTotalRecords" 		=> count($remittance_payee),
					"iTotalDisplayRecords" 	=> $iTotal["count"],
					"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_REMITTANCE_PAYEE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_REMITTANCE_PAYEE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_REMITTANCE_PAYEE, ACTION_DELETE);
				
			$cnt = 0;
			foreach ($remittance_payee as $aRow):
			$cnt++;
			$row 					= array();
			$action 				= "<div class='table-actions'>";
				
			$remittance_payee_id	= $aRow["remittance_payee_id"];
			$id 					= $this->hash ($remittance_payee_id);
			$salt 					= gen_salt();
			$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
			$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
			$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
			$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
			$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;
			$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
			$delete_action			= 'content_delete("Remittance Payee", "'.$url_delete.'")';

			$row[] = strtoupper($aRow['remittance_payee_name']);
			$row[] = strtoupper($aRow['report_short_code']);
			$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

			if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_remittance_payee' onclick=\"modal_remittance_payee_init('".$view_action."')\"></a>";
				if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_remittance_payee' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_remittance_payee_init('".$edit_action."')\"></a>";
					if($permission_delete)
						$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";

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

	public function modal_remittance_payee($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources 						= array();
			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_REMITTANCE_PAYEE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
				
			if(!EMPTY($id))
			{
				//EDIT
				$table              		= $this->cl->tbl_param_remittance_payees;
				$where              		= array();
				$key                		= $this->get_hash_key('remittance_payee_id');
				$where[$key]        		= $id;
				$remittance_payee_info 		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				$data['remittance_payee_info'] 	= $remittance_payee_info;
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

		$this->load->view('code_library/modals/modal_remittance_payee', $data);
	}

	public function process()
	{
		try
		{
			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data							= $this->_validate_data_remittance_payee($params);

			//SET FIELDS VALUE
			$fields['remittance_payee_name']	= $valid_data['remittance_payee_name'];
			$fields['report_short_code']		= $valid_data['report_short_code'];
			$fields['active_flag']			 	= isset($valid_data['active_flag']) ? "Y" : "N";

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_remittance_payees;

			if(EMPTY($params['id']))
			{
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]			= $this->cl->tbl_param_remittance_payees;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[]  		= array();
				$curr_detail[]  		= array($fields);
				$audit_action[] 		= AUDIT_INSERT;

				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//WHERE
				$where			= array();
				$key 			= $this->get_hash_key('remittance_payee_id');
				$where[$key]	= $params['id'];

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_remittance_payees;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";

			}
				
			$activity = sprintf($activity, $params['remittance_payee_name']);

			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
					$activity,
					$this->module,
					$prev_detail,
					$curr_detail,
					$audit_action,
					$audit_table,
					$audit_schema
					);
				
			Main_Model::commit();
			$status = TRUE;
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function delete()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				if ($token != in_salt ( $id . '/' . $action, $salt ))
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

						
					$flag 				= 0;
					$params				= get_params();

					$action 			= AUDIT_DELETE;

					// BEGIN TRANSACTION
					Main_Model::beginTransaction();
					$table 				= $this->cl->tbl_param_remittance_payees;
					$where				= array();
					$key 				= $this->get_hash_key('remittance_payee_id');
					$where[$key]		= $id;

					$audit_action[]		= AUDIT_DELETE;
					$audit_table[]		= $table;
					$audit_schema[]		= Base_Model::$schema_core;

					// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
					$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
						
					$this->cl->delete_code_library($table, $where);
					$msg 				= $this->lang->line('data_deleted');

					// GET THE DETAIL AFTER UPDATING THE RECORD
					$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

					// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
					$activity 			= "%s has been deleted";
					$activity 			= sprintf($activity, $prev_detail[0][0]['remittance_payee_name']);

					// LOG AUDIT TRAIL
					$this->audit_trail->log_audit_trail(
							$activity,
							$this->module,
							$prev_detail,
							$curr_detail,
							$audit_action,
							$audit_table,
							$audit_schema
							);

					Main_Model::commit();
					$flag = 1;

		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
				
			$msg = $this->rlog_error($e, TRUE);
		}

		$info 					= array(
				"flag"            	=> $flag,
				"msg"            	=> $msg,
				"reload"          	=> 'datatable',
				"table_id"        	=> 'remittance_payee_table',
				"path"            	=> PROJECT_MAIN . '/code_library_payroll/remittance_payee/get_remittance_payee_list/',
				"advanced_filter" 	=> true
		);

		echo json_encode($info);
	}

	private function _validate_data_remittance_payee($params)
	{
		$fields                 			= array();
		$fields['remittance_payee_name']  	= "Remittance Payee Name";
		$fields['report_short_code']		= "Report Short Code";

		$this->check_required_fields($params, $fields);

		return $this->_validate_remittance_payee_input ($params);
	}

	private function _validate_remittance_payee_input($params)
	{
		try {
				
			$validation ['remittance_payee_name'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Remittance Payee Name',
					'max_len' 					=> 100
			);
			$validation ['report_short_code']	= array(
					'data_type'					=> 'string',
					'name'						=> 'Report Short Code',
					'max_len'					=> 5
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1
			);
				
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */