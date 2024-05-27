<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Responsibility_center extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_RESPONSIBILITY_CENTER;
	
	//PERMISSIONS
	private $permission_view;
	private $permission_edit;
	private $permission_delete;
	private $permission_add;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
		
		//PERMISSIONS
		$this->permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_RESPONSIBILITY_CENTER, ACTION_VIEW);
		$this->permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_RESPONSIBILITY_CENTER, ACTION_EDIT);
		$this->permission_delete 		= $this->permission->check_permission(MODULE_PAYROLL_CL_RESPONSIBILITY_CENTER, ACTION_DELETE);
		$this->permission_add 			= $this->permission->check_permission(MODULE_PAYROLL_CL_RESPONSIBILITY_CENTER, ACTION_ADD);
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
			$resources['datatable'][] = array('table_id' => 'responsibility_center_table', 'path' => 'main/code_library_payroll/responsibility_center/get_responsibility_center_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] = array(
				'modal_responsibility_center' 	=> array(
					'controller' 		=> 'code_library_payroll/'.__CLASS__,
					'module' 			=> PROJECT_MAIN,
					'method' 			=> 'modal_responsibility_center',
					'multiple' 			=> TRUE,
					// 'height' 			=> '200px',
					'height' 			=> '400px', //jendaigo: increase height of modal
					'size' 				=> 'sm',
					'title' 			=> 'Responsibility Center'
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
		$this->load->view('code_library/tabs/responsibility_center', $data);
		$this->load_resources->get_resource($resources);
		
	}

	public function get_responsibility_center_list()
	{

		try
		{
			if (EMPTY($this->permission_view)) {
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			}
			$params 					=get_params();
			
			/*
			$aColumns					= array("responsibility_center_desc", "responsibility_center_code", "active_flag");
			$bColumns					= array("responsibility_center_desc", "responsibility_center_code", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_responsibility_centers;
			$where						= array();
			$responsibility_center		= $this->cl->get_responsibility_center_list($aColumns, $bColumns, $params, $table, $where);
			*/
			// ====================== jendaigo : start : include prexc code details ============= //
			$aColumns					= array("A.responsibility_center_desc", "A.responsibility_center_code", "C.prexc_code", "A.active_flag");
			$bColumns					= array("responsibility_center_desc", "A.responsibility_center_code", "prexc_code", "IF(A.active_flag = 'Y', 'Active', 'Inactive')");
			$responsibility_center		= $this->cl->get_responsibility_center_list($aColumns, $bColumns, $params, array());
			// ====================== jendaigo : start : include prexc code details ============= //
			
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(responsibility_center_code)) AS count"), $this->cl->tbl_param_responsibility_centers, NULL, false);
			
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($responsibility_center),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
		
			// ====================== jendaigo : start : include declaration of permission ============= //
			//PERMISSIONS
			$permission_view		= $this->permission_view;
			$permission_edit		= $this->permission_edit;
			$permission_delete		= $this->permission_delete;
			$permission_add			= $this->permission_add;
			// ====================== jendaigo : end : include declaration of permission ============= //
		
			$cnt = 0;
			foreach ($responsibility_center as $aRow):
			$cnt++;
			$row 						= array();
			$action 					= "<div class='table-actions'>";
				
			$responsibility_center_id	= $aRow["responsibility_center_code"];
			$id 						= $this->hash ($responsibility_center_id);
			$salt 						= gen_salt();
			$token_view 				= in_salt($id . '/' . ACTION_VIEW, $salt);
			$token_edit 				= in_salt($id . '/' . ACTION_EDIT, $salt);
			$token_delete 				= in_salt($id . '/' . ACTION_DELETE, $salt);
			$view_action 				= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
			$edit_action 				= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;
			$url_delete 				= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
			$delete_action				= 'content_delete("Responsibility Center", "'.$url_delete.'")';

			$row[] = strtoupper($aRow['responsibility_center_desc']);
			$row[] = strtoupper($aRow['responsibility_center_code']);
			$row[] = strtoupper($aRow['prexc_code']); //jendaigo: include prexc code
			$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);

			if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_responsibility_center' onclick=\"modal_responsibility_center_init('".$view_action."')\"></a>";
			if($aRow['responsibility_center_desc'] != 'Department of Health')
			{
				if($permission_edit)
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_responsibility_center' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_responsibility_center_init('".$edit_action."')\"></a>";
				if($permission_delete)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
			}
			
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

	public function modal_responsibility_center($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
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
			$resources['load_css']			= array(CSS_SELECTIZE); //jendaigo: include load_css
			$resources['load_js']  			= array(JS_SELECTIZE, JS_NUMBER); //jendaigo: include load_js
			$data['action_id'] 				= $action;
			$data['nav_page']				= CODE_LIBRARY_RESPONSIBILITY_CENTER;
			
			
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table = array(
					'main' => array(
						'table' => $this->cl->tbl_param_responsibility_centers,
						'alias' => 'A'
					),
					't1' => array(
						'table'     => $this->cl->tbl_param_responsibility_prexc_codes,
						'alias'     => 'B',
						'type'      => 'JOIN',
						'condition' => 'A.responsibility_center_code = B.responsibility_center_code'
					)
				);

				$where              		= array();
				$key                		= $this->get_hash_key('A.responsibility_center_code');
				$where[$key]        		= $id;
				$responsibility_center_info = $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);
				$data['responsibility_center_info'] 	= $responsibility_center_info;
			}
			
			// ====================== jendaigo : start : get prexc code list ============= //
			$table              		= $this->cl->tbl_param_prexc_codes;
			$data['prexc_code_info'] 	= $this->cl->get_code_library_data(array("*"), $table, array(), TRUE);
			// ====================== jendaigo : end : get prexc code list ============= //
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

		$this->load->view('code_library/modals/modal_responsibility_center', $data);
		$this->load_resources->get_resource($resources); //jendaigo: include load_resources
	}

	public function process()
	{
		try
		{
			if (EMPTY($this->permission_add)) {
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			}
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
			$valid_data								= $this->_validate_data_responsibility_center($params);

			// INPUT VALIDATIONS
			$this->_addtl_validate_input_responsibility_center($params, $valid_data);

			//SET FIELDS VALUE
			$fields['responsibility_center_code']	= $valid_data['responsibility_center_code'];
			$fields['responsibility_center_desc']	= $valid_data['responsibility_center_desc'];
			$fields['active_flag']			 		= isset($valid_data['active_flag']) ? "Y" : "N";
			
			// ====================== jendaigo : start : set fields for rc prexc code ============= //
			//SET FIELDS VALUE FOR RESPONSIBILITY PREXC CODE
			$fields_rcprexc['responsibility_center_code']	= $valid_data['responsibility_center_code'];
			$fields_rcprexc['prexc_code_id']				= $valid_data['prexc_code'];
			$fields_rcprexc['active_flag']			 		= isset($valid_data['active_flag']) ? "Y" : "N";
			// ====================== jendaigo : end : set fields for rc prexc code ============= //
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_responsibility_centers;
			$table_rcprexc 						= $this->cl->tbl_param_responsibility_prexc_codes; //jendaigo: rc prexc table declaration

			if(EMPTY($params['id']))
			{
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]			= $this->cl->tbl_param_responsibility_centers;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[]  		= array();
				$curr_detail[]  		= array($fields);
				$audit_action[] 		= AUDIT_INSERT;
				
				// ====================== jendaigo : start : saving of rc prexc code ============= //
				$this->cl->insert_code_library($table_rcprexc, $fields_rcprexc, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]			= $this->cl->tbl_param_responsibility_prexc;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[]  		= array();
				$curr_detail[]  		= array($fields_rcprexc);
				$audit_action[] 		= AUDIT_INSERT;
				// ====================== jendaigo : end : saving of rc prexc code ============= //
				
				//MESSAGE ALERT
				$message 				= $this->lang->line('data_saved');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 				= "%s has been added";
			}
			else
			{
				//WHERE
				$where			= array();
				$key 			= $this->get_hash_key('responsibility_center_code');
				$where[$key]	= $params['id'];

				// ====================== jendaigo : start : previous data and delete record respcode prexc code ============= //
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$fields_dtl		= array();
				$fields_dtl		= array("responsibility_center_code", "prexc_code_id", "active_flag");
				$previous_dtl	= $this->cl->get_code_library_data($fields_dtl, $table_rcprexc, $where, FALSE);
				
				//DELETE PREVIOUS DATA
				$this->cl->delete_code_library($table_rcprexc, $where);
				// ====================== jendaigo : start : previous data and delete record respcode prexc code ============= //

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous		= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_responsibility_centers;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				// ====================== jendaigo : start : saving of respcode prexc code ============= //
				//INSERT MODIFIED DATA
				$this->cl->insert_code_library($table_rcprexc, $fields_rcprexc, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_responsibility_prexc;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous_dtl);
				$curr_detail[]  = array($fields_rcprexc);
				$audit_action[] = AUDIT_UPDATE;
				// ====================== jendaigo : end : saving of respcode prexc code ============= //

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');

				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";

			}
				
			$activity = sprintf($activity, $params['responsibility_center_desc']);

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
			
			$where				= array();
			$key 				= $this->get_hash_key('responsibility_center_code');
			$where[$key]		= $id;

			//****DETAILS TABLE****
			$table_resp_prexc_codes 	= $this->cl->tbl_param_responsibility_prexc_codes;

			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$previous			= $this->cl->get_code_library_data(array("*"), $table_resp_prexc_codes, $where, FALSE);
				
			$this->cl->delete_code_library($table_resp_prexc_codes, $where);

			// GET THE DETAIL AFTER UPDATING THE RECORD
			$current 			= $this->cl->get_code_library_data(array("*"), $table_resp_prexc_codes, $where, FALSE);

			//SET AUDIT TRAIL DETAILS
			$audit_table[]	= $table_resp_prexc_codes;
			$audit_schema[]	= DB_MAIN;
			$prev_detail[]  = array($previous);
			$curr_detail[]  = array($current);
			$audit_action[] = AUDIT_DELETE;

			//****MAIN TABLE****
			$table_resp_ctr	 	= $this->cl->tbl_param_responsibility_centers;
			
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$previous			= $this->cl->get_code_library_data(array("*"), $table_resp_ctr, $where, FALSE);

			$this->cl->delete_code_library($table_resp_ctr, $where);
			$msg 				= $this->lang->line('data_deleted');

			// GET THE DETAIL AFTER UPDATING THE RECORD
			$current	 		= $this->cl->get_code_library_data(array("*"), $table_resp_ctr, $where, FALSE);

			//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $table_resp_ctr;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($current);
				$audit_action[] = AUDIT_DELETE;
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[1][0]['responsibility_center_desc']);

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
			"table_id"        	=> 'responsibility_center_table',
			"path"            	=> PROJECT_MAIN . '/code_library_payroll/responsibility_center/get_responsibility_center_list/',
			"advanced_filter" 	=> true
		);

		echo json_encode($info);
	}

	private function _validate_data_responsibility_center($params)
	{
		$fields                 				= array();
		$fields['responsibility_center_desc']  	= "Responsibility Center Description";
		$fields['responsibility_center_code']	= "Responsibility Center Code";

		$this->check_required_fields($params, $fields);

		return $this->_validate_responsibility_center_input ($params);
	}

	private function _validate_responsibility_center_input($params)
	{
		try {
				
			$validation ['responsibility_center_desc'] 	= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Responsibility Center Description',
					'max_len' 					=> 255
			);
			$validation ['responsibility_center_code']	= array(
					'data_type'					=> 'string',
					'name'						=> 'Responsibility Center Code',
					'max_len'					=> 50
			);
			$validation ['prexc_code']			= array(
					'data_type'					=> 'string',
					'name'						=> 'PREXC Code',
					'max_len'					=> 50
			);
			$validation ['active_flag'] 		= array (
					'data_type' 				=> 'enum',
					'name' 						=> 'Active Flag',
					'max_len' 					=> 1
			);
				
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	
	private function _addtl_validate_input_responsibility_center($params, $valid_data) 
	{
		$field	= array("A.*", "CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '. ')), B.last_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name))) as fullname");
		$tables 		= array(
			'main' 			=> array(
				'table' 	=> $this->cl->tbl_employee_responsibility_codes,
				'alias' 	=> 'A'
			),
			't1' 			=> array(
				'table'		=> $this->cl->tbl_employee_personal_info,
				'alias'		=> 'B',
				'type'      => 'JOIN',
				'condition' => 'B.employee_id = A.employee_id'
			)
		);
		$where	= array();
		$key    				= $this->get_hash_key('A.responsibility_center_code');
		$where[$key]			= $params['id'];
		$employee_respcodes 	= $this->cl->get_code_library_data($field, $tables, $where, TRUE);

		if(!EMPTY($employee_respcodes))
		{
			$employees = null;
			foreach ($employee_respcodes as $erespcode) 
			{
				if($erespcode['responsibility_center_code'] != $valid_data['responsibility_center_code'])
					$employees .= '<br>'.$erespcode['fullname'];
			}

			if(!EMPTY($employees))
				throw new Exception("This action can't be competed because Responsbility Center is already assigned to employee(s) ".$employees.".");
		}
	}
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */