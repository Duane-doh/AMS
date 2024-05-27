<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plantilla extends Main_Controller {
	private $module = MODULE_HR_CL_PLANTILLA;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     	= array();
			$resources                	= array();
			$data['action_id']        	= $action_id;
			$resources['load_css'][]   	= CSS_DATATABLE;
			$resources['load_js']		= array(JS_DATATABLE, JS_EDITOR);
			$resources['datatable'][]	= array('table_id' => 'table_plantilla', 'path' => 'main/code_library_hr/plantilla/get_plantilla_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_plantilla' 		=> array(
					'controller'		=> 'code_library_hr/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_plantilla',
					'multiple'			=> true,
					'height'			=> '500px',
					'size'				=> 'lg',
					'title'				=> CODE_LIBRARY_PLANTILLA
				)
			);
			$resources['load_delete'] 	= array(
				'code_library_hr/'.__CLASS__,
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

		$this->load->view('code_library/tabs/plantilla', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_plantilla_list()
	{
		try
		{
			
			$params         			= get_params();
			
			$aColumns     				= array("A.plantilla_id", "A.plantilla_code", "A.parent_plantilla_id", "B.position_name", "B.position_name AS plantilla_parent_name", "D.name", "IF(A.active_flag = 'Y', 'Active', 'Inactive') as active_flag");
			$bColumns      				= array('A.plantilla_code', 'B.position_name', 'D.name', "D.name", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_plantilla_items;
			$where						= array();
			$plantilla 					= $this->cl->get_plantilla_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(plantilla_id)) AS count"), $this->cl->tbl_param_plantilla_items, NULL, false);
			$iFilteredTotal 			= $this->cl->plantilla_filtered_length($aColumns, $bColumns, $params, $table);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_HR_CL_PLANTILLA, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_HR_CL_PLANTILLA, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_HR_CL_PLANTILLA, ACTION_DELETE);

			$cnt = 0;
			foreach ($plantilla as $aRow):
				$cnt++;
				$row     				= array();
				$action   				= "";
				
				$action   				= "<div class='table-actions'>";
				
				$plantilla_id     		= $aRow["plantilla_id"];
				$id                		= $this->hash($plantilla_id);
				$salt              		= gen_salt();
				$token_view         	= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit         	= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete      		= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action        	= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
				$edit_action        	= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete         	= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action      	= 'content_delete("plantilla", "'.$url_delete.'")';
				
				$row[] = strtoupper($aRow['plantilla_code']);
				$row[] = strtoupper($aRow['position_name']); 
				$row[] = strtoupper($aRow['name']); 
				$row[] = strtoupper($aRow['active_flag']);
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_plantilla' onclick=\"modal_plantilla_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_plantilla' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_plantilla_init('".$edit_action."')\"></a>";
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

	public function modal_plantilla($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$date				   			= date('Y-m-d');
			$resources['load_css'] 			= array(CSS_SELECTIZE);
			$resources['load_js'] 			= array(JS_SELECTIZE, 'jquery.number.min');

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			$data ['action']   				= $action;
			$data ['salt']     				= $salt;
			$data ['token']    				= $token;
			$data ['id']      				= $id;
			
			$data['parents']      			= $this->cl->get_parent_plantilla_name($id);

			if($action != ACTION_ADD)
			{
				$data['parents']      		= $this->cl->get_parent_plantilla_name($id);

				$field                  	= array("*") ;
				$table                  	= $this->cl->tbl_param_plantilla_items;
				$key                    	= $this->get_hash_key('plantilla_id');
				$where                 		= array();
				$where[$key]           	 	= $id;
				$plantilla_info         	= $this->cl->get_code_library_data($field, $table, $where, FALSE);
				$data['plantilla_info'] 	= $plantilla_info;
				
				$resources['single']   		= array(
					'office'          		=> $plantilla_info['office_id'],
					'division'          	=> $plantilla_info['division_id'],
					'parent_plantilla_id'  	=> $plantilla_info['parent_plantilla_id'],
					'position_id'           => $plantilla_info['position_id'],
					'plantilla_level_name'  => $plantilla_info['plantilla_level_id']
				);            
			}

			$field                      	= array("*") ;
			$table                     		= $this->cl->tbl_param_positions;
			$where                      	= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['position_id']  		= array($plantilla_info['position_id'], array("=", ")"));				
			}	
			$data['positions']          	= $this->cl->get_code_library_data($field, $table, $where, TRUE);

			//ncocampo
			$field 								= array("*") ;
			$table								= $this->cl->tbl_param_plantilla_levels;
			$where								= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 			= YES;			
			}
			else
			{
				$where['active_flag'] 			= array(YES, array("=", "OR", "("));
		 		$where['plantilla_level_id'] 	= array($plantilla_info['plantilla_level_id'], array("=", ")"));				
			}
			$data['plantilla_level'] 			= $this->cl->get_code_library_data($field, $table, $where, TRUE);
			$plantilla_level 					= $data['plantilla_level'];
			//ncocampo

			$where_office                   = '';
			if($action == ACTION_ADD)
			{
				$where_office 		   		= "A.active_flag = 'Y' AND C.office_type_id = 2";			
			}
			else
			{
				$where_office 				= "(A.active_flag = 'Y' OR A.office_id = ".$plantilla_info['office_id']. ")AND C.office_type_id = 2";
		 	}
		 	$offices 				= $this->cl->get_offices($where_office);
			$data['offices']      			= $offices;

			$where_division                 = '';
			if($action == ACTION_ADD)
			{
				$where_division 		   	= "A.active_flag = 'Y' AND C.office_type_id = 3";			
			}
			else
			{
				$where_division 			= "(A.active_flag = 'Y' OR A.office_id = ".$plantilla_info['office_id']. ")AND C.office_type_id = 3";
		 	}
		 	$divisions 				= $this->cl->get_division($where_division);
		 	$data['divisions']      		= $divisions;
/*
			$field                      	= array("*") ;
			$table                     		= $this->cl->db_core.'.'.$this->cl->tbl_organizations;
			$where                      	= array();
			$data['offices']          		= $this->cl->get_code_library_data($field, $table, $where, TRUE);*/
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
		
		$this->load->view('code_library/modals/modal_plantilla', $data);
		$this->load_resources->get_resource($resources);
	}
	public function get_position_duty() 
	{
		$data="";
		try {
			$params 			= get_params();
			
			$table 				= $this->cl->tbl_param_position_duties;
			$field 				= array("*");
			$key                = 'position_id';
			$where              = array();
			$where[$key]        = $params['position_id'];
			$duty         		= $this->cl->get_code_library_data($field, $table, $where, FALSE);
			$data				= $duty['duties'];
		}
		catch (PDOException $e)
		{
			$msg= $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$msg= $e->getMessage();
			RLog::error($message);
		}
		$info	= array('duty'=>$data);
		echo json_encode($info);
	}
	public function process()
	{
		try
		{
			$status = 0;
			$params	= get_params();
			$duties = $_POST['duties'];
			
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					//throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					//throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data                      = $this->_validate_data_plantilla($params);
// 			$this->_validate_duties($duties);
			
			//SET FIELDS VALUE
			$fields                          = array();
			$fields['plantilla_code']        = $valid_data['plantilla_code'];
			$fields['position_id']           = $valid_data['position_id'];
			$fields['plantilla_level_id']    = $valid_data['plantilla_level_name'];
			$fields['office_id'] 			 = $valid_data['office'];
			$fields['parent_plantilla_id']   = !EMPTY($valid_data['parent_plantilla_id']) ? $valid_data['parent_plantilla_id']:NULL;
			$fields['active_flag']			 = isset($valid_data['active_flag']) ? "Y" : "N";			
			$fields['general_function']  	 = $valid_data['general_function'];
			$fields['duties']   		 	 = $duties;
			$fields['division_id']   	     = !EMPTY($valid_data['division']) ? $valid_data['division']:NULL;
			$fields['rata']			 		 = isset($valid_data['rata']) ? "Y" : "N";
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table                           = $this->cl->tbl_param_plantilla_items;
	
			if(EMPTY($params['id']))
			{
				//INSERT DATA
				$this->cl->insert_code_library($table, $fields, TRUE);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]				 = $table;
				$audit_schema[]				 = DB_MAIN;
				$prev_detail[]  			 = array();
				$curr_detail[]  			 = array($fields);
				$audit_action[] 			 = AUDIT_INSERT;		
				
				//MESSAGE ALERT
				$message              		 = $this->lang->line('data_saved');
									
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity             		 = "%s has been added";
			}
			else
			{
				//WHERE 
				$where          = array();
				$key            = $this->get_hash_key('plantilla_id');
				$where[$key]    = $params['id'];
								
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$previous  		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//SET AUDIT TRAIL DETAILS
				$audit_table[]	= $this->cl->tbl_param_plantilla_items;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[]  = array($previous);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;
				
				//MESSAGE ALERT
				$message        = $this->lang->line('data_updated');
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity       = "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['plantilla_name']);
	
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
		$data['msg']    = $message;
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
			$table 				= $this->cl->tbl_param_plantilla_items;
			$where				= array();
			$key 				= $this->get_hash_key('plantilla_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['plantilla_name']);
	
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
			$msg = $this->get_user_message($e);	
			Main_Model::rollback();
		
			//$msg = $e->getMessage();
		}
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'table_plantilla',
			"path"				=> PROJECT_MAIN . '/code_library_hr/plantilla/get_plantilla_list/',
			"advanced_filter" 	=> true
		);

		echo json_encode($info);
	}

	private function _validate_data_plantilla($params)
	{
		$fields                 			= array();
		$fields['plantilla_code']  			= "Item number";
		$fields['office']  					= "Office";
		$fields['position_id']  			= "Position";
// 		$fields['general_function']  		= "General Function";
		

		$this->check_required_fields($params, $fields);	

		return $this->_validate_plantilla_input($params);
	}

	private function _validate_plantilla_input($params) 
	{
		try 
		{
			$validation ['plantilla_code'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Item Number',
					'max_len' 					=> 50 
			);
			$validation ['parent_plantilla_id'] = array (
					'data_type' 				=> 'digit',
					'name' 						=> 'Parent Plantilla',
					'max_len' 					=> 11
			);
			$validation ['office'] 				= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Office',
					'max_len' 					=> 11 
			);
			$validation ['division'] 				= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Division',
					'max_len' 					=> 11 
			);
			$validation ['position_id'] 		= array (
					'data_type'					=> 'string',
					'name' 						=> 'Position',
					'max_len' 					=> 11 
			);
			$validation ['plantilla_level_name'] = array (
				'data_type'						 => 'string',
				'name' 							 => 'Plantilla Level',
				'max_len' 						 => 11 
			);
			$validation ['active_flag']			= array (
					'data_type' 				=> 'string',
					'name'      				=> 'Active Flag',
					'max_len'  					=> 1 
			);
			$validation ['rata']			= array (
				'data_type' 				=> 'string',
				'name'      				=> 'Rata',
				'max_len'  					=> 1 
			);
			$validation ['general_function'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'General Function',
			);
			$validation ['duties'] 	= array (
					'data_type'					=> 'string',
					'name'      				=> 'Duties',
			);
			
			return $this->validate_inputs($params, $validation );
		
		} catch ( Exception $e ) {
			throw $e;
		}
	}
	public function _validate_duties($duties)
	{
		try
		{
			if(EMPTY($duties))
				throw new Exception("Duties is required.");
		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Plantilla.php */
/* Location: ./application/modules/main/controllers/code_library_hr/Plantilla.php */