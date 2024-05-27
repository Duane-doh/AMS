<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dropdown extends Main_Controller {
	private $module = MODULE_SYSTEM_CL_DROPDOWN;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     = array();
			$resources                = array();
			$data['action_id']        = $action_id;
			$resources['load_css'][]  = CSS_DATATABLE;
			$resources['load_js'][]   = JS_DATATABLE;
			$resources['datatable'][]	= array('table_id' => 'dropdown_table', 'path' => 'main/code_library_system/dropdown/get_dropdown_list', 'advanced_filter' => TRUE);
			$resources['load_modal']	= array(
				'modal_dropdown'		=> array(
					'controller'		=> 'code_library_system/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_dropdown',
					'multiple'			=> true,
					'height'			=> '425px',
					'size'				=> 'md',
					'title'				=> 'Dropdown'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_system/'.__CLASS__,
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

		$this->load->view('code_library/tabs/dropdown', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_dropdown_list()
	{

		try
		{
			$message        			= "";
			$params          			= get_params();

			$output 					= array(
				"sEcho"				 	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> 0,
				"iTotalDisplayRecords" 	=> 0,
				"aaData" 				=> array()
			);

			$table 			 			= '';
			$aColumns        			= array("A.*,B.dtl_no");
			$bColumns        			= array("A.dropdown_name", "A.table_name", "A.created_date");
			$result          			= $this->cl->get_dropdown_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(dropdown_id)) AS count"), $this->cl->tbl_param_dropdown, NULL, false);
			
			$output 					= array(
				"sEcho"				 	=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($result),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			
			if(!EMPTY($result))
			{
				//PERMISSIONS
				$permission_view 		= $this->permission->check_permission(MODULE_SYSTEM_CL_DROPDOWN, ACTION_VIEW);
				$permission_edit 		= $this->permission->check_permission(MODULE_SYSTEM_CL_DROPDOWN, ACTION_EDIT);
				$permission_delete 		= $this->permission->check_permission(MODULE_SYSTEM_CL_DROPDOWN, ACTION_DELETE);

				foreach ($result as $aRow):
					$row 				= array();

					$action 			= "<div class='table-actions'>";
				
					$dropdown_id 		= $aRow["dropdown_id"];
					$id 				= $this->hash ($dropdown_id);
					$salt 				= gen_salt();
					$token_view 		= in_salt($id . '/' . ACTION_VIEW,  	$salt);
					$token_edit 		= in_salt($id . '/' . ACTION_EDIT, 		$salt);
					$token_delete 		= in_salt($id . '/' . ACTION_DELETE, 	$salt);
					$view_action 		= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;
					$edit_action 		= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
					$url_delete 		= ACTION_DELETE."/". $id . "/" . $salt  . "/" . $token_delete;
					$delete_action		= 'content_delete("dropdown", "'.$url_delete.'")';
					
					$row[] = strtoupper($aRow['dropdown_name']);
					$row[] = strtoupper($aRow['table_name']);
					$row[] = '<center>' . format_date($aRow['created_date']) . '</center>';

					if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_dropdown' onclick=\"modal_dropdown_init('".$view_action."')\"></a>";
					if($permission_edit && EMPTY($aRow['dtl_no']))
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_dropdown' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_dropdown_init('".$edit_action."')\"></a>";
					if($permission_delete && EMPTY($aRow['dtl_no']))
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
					
					$action .= "</div>";

					$row[] = $action;
					
					$output['aaData'][] = $row;
				endforeach;
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

	public function modal_dropdown($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 						= array();
			$resources['load_css']			= array(CSS_SELECTIZE);
			$resources['load_js']  			= array(JS_SELECTIZE);


			$status		= FALSE;
			$message	= "";
			$data		= array();

			if($action != ACTION_ADD)
			{
				if(EMPTY($action) OR EMPTY($id) OR EMPTY($salt) OR EMPTY($token)){
					throw new Exception($this->lang->line('err_unauthorized_access'));

				}
				if($token != in_salt($id . '/' . $action, $salt)) {
					throw new Exception($this->lang->line('err_invalid_request'));
				}
			}

			$tables			= $this->cl->get_code_library_data(array("*"),$this->cl->tbl_data_dictionary,NULL);

			$data['tables']	= $tables;

			$display_view	= '';
			$cond_counter	= 1;
			$dd_where 		= FALSE;
			$sel_option		= '';

			if(!EMPTY($id))
			{

				$where 					= array();
				$key					= $this->get_hash_key("dropdown_id");
				$where[$key]			= $id;
				$table 					= $this->cl->tbl_param_dropdown;
				$drop_down				= $this->cl->get_code_library_data(array("*"),$table,$where,FALSE);

				if(EMPTY($drop_down))
					throw new Exception($this->lang->line('err_invalid_request'));
				$table 					= $this->cl->tbl_param_dropdown_conditions;
				$dropdown_where			= $this->cl->get_code_library_data(array("*"),$table,$where,TRUE);

				$table			= $this->cl->tbl_data_dictionary;
				$where			= array();
				$key			= 'table_name';
				$where[$key]	= $drop_down['table_name'];
				$fields			= $this->cl->get_code_library_data(array("column_name"),$table,$where,TRUE);
				$sel_option		.= '<option value="" selected></option>';

				if(!EMPTY($fields))
				{
					foreach ($fields as $col)
					{
						if($col['column_name']!='line_no')
							$sel_option .= '<option value="'.$col['column_name'].'">'.$col['column_name'].'</option>';
					}
				}
				if(!EMPTY($dropdown_where))
				{
					$disabled		= $action == ACTION_VIEW ? "disabled" : "";
					$dd_where 		= TRUE;

					$operators 		= array("=","!=","<=",">=","LIKE");

					foreach ($dropdown_where as $value)
					{
						if($value['where_condition'] == 'WHERE')
						{
							$display_view .= '
							<div class="list-group-item" id="where_div">
								<div class = "row">
									<label class="col s2 p-t-sm center">WHERE</label>
									<input type="hidden" name="where_condition[]" '.$disabled.' value="'.$value['where_condition'].'" id="where_condition_1">
									<div class="col s3">
										<select name="where_field[]" id="where_field_1" '.$disabled.' placeholder="Select Fields" type="text" class="selectize validate where_field">';
											foreach ($fields as $col)
											{
												$selected = '';
												if($value['where_field'] == $col['column_name'])
													$selected = 'selected';
												$display_view .= '<option value="'.$col['column_name'].'" '.$selected.'>'.$col['column_name'].'</option>';
											}
										$display_view .= '</select>
									</div>
									<div class="col s2">
										<select name="where_operator[]" '.$disabled.' id="where_operator" placeholder="Operator" type="text" class="selectize validate">
											<option value=""></option>';
											foreach ($operators as $operator)
											{
												$selected = '';
												if($value['where_operator'] == $operator)
													$selected = 'selected';
												$display_view .= '<option value="'.$operator.'" '.$selected.'>'.$operator.'</option>';
											}
										$display_view .='</select>
									</div>
									<div class="col s2">
										<input type="text" '.$disabled.' name="where_value[]" id="where_value" value="'.$value['where_value'].'"  placeholder="Enter where value" value="" class="browser-default left validate input-sm" >
									</div>';
									if($action == ACTION_EDIT)
									{
										$display_view .=
										'<div class="col s1">
											<a class="btn btn-success left" name="add_table" id="add_table" onclick="add_where()"><i class="flaticon-add175"></i>Where</a>
										</div>';
									}
							$display_view .='</div>';
						}
						else
						{
							$display_view .=
							'<div class = "row" id="where_condition_div_'.$cond_counter.'">
								<div class="col s2">
									<select name="where_condition[]" '.$disabled.' placeholder="Condition" type="text" class="selectize validate" required="true">
										<option value=""></option>';
										if($value['where_condition'] == 'AND')
										{
											$selected_and = 'selected';
											$selected_or = '';
										}
										else
										{
											$selected_and = '';
											$selected_or = 'selected';
										}
										$display_view .= '<option value="AND" '.$selected_and.'>AND</option>
										<option value="OR" '.$selected_or.'>OR</option>
									</select>
								</div>
								<div class="col s3">
									<select name="where_field[]" '.$disabled.' placeholder="Select Fields" type="text" class="selectize validate where_field" required="true">
										<option value=""></option>';
										foreach ($fields as $col)
										{
											$selected = '';
											if($value['where_field'] == $col['column_name'])
												$selected = 'selected';
											$display_view .= '<option value="'.$col['column_name'].'" '.$selected.'>'.$col['column_name'].'</option>';
										}
									$display_view .= '</select>
								</div>
								<div class="col s2">
									<select name="where_operator[]" '.$disabled.' placeholder="Operator" type="text" class="selectize validate" required="true" data-parsley-trigger="change">
										<option value=""></option>';
										foreach ($operators as $operator)
										{
											$selected = '';
											if($value['where_operator'] == $operator)
												$selected = 'selected';
											$display_view .= '<option value="'.$operator.'" '.$selected.'>'.$operator.'</option>';
										}
									$display_view .='</select>
								</div>
								<div class="col s2">
									<input type="text" name="where_value[]" '.$disabled.' value="'.$value['where_value'].'" placeholder="Enter where value" class="form-control input-sm" required="true">
								</div>';
								if($action == ACTION_EDIT)
								{
									$display_view .=
									'<div class="col s1">
										<a onclick="delete_condition(this)" id="delete_condition_'.$cond_counter.'" title="Delete Condition" class="btn btn-xs default"><i class="Small flaticon-minus102"></i></a>
									</div>';
								}
							$display_view .='</div>';
						}

						$cond_counter++;
					}
					$display_view .= '</div>';

				}

				$data['sel_option']		= $sel_option;
				$data['drop_down']		= $drop_down;
				$data['dd_where']		= $dd_where;
				$data['display_view']	= $display_view;
				$data['cond_counter']	= $cond_counter;
				$data['salt']			= $salt;
				$data['token'] 			= $token;
				$data['id']				= $id;

			}

			$data['action_id']		= $action;
			$status 				= TRUE;
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
		
		$this->load->view('code_library/modals/modal_dropdown', $data);
		$this->load_resources->get_resource($resources);
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
			$valid_data             			= $this->_validate_data_dropdown($params);
			//SET FIELDS VALUE
			$fields                  			= array();
			$fields['dropdown_name'] 			= $valid_data['dropdown_name'];
			$fields['columns']       			= html_entity_decode($valid_data['columns'], ENT_QUOTES);
			$fields['table_name']   			= $valid_data['table'];
			

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 								= $this->cl->tbl_param_dropdown;
			$audit_table[]						= $table;
			$audit_schema[]						= Base_Model::$schema_core;
				
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]					= AUDIT_INSERT;
				
				$prev_detail[]					= array();

				$fields['created_by']    		= $this->session->userdata('user_id');
				$fields['created_date']  		= date('Y-m-d');
				//INSERT DATA
				$dropdown_id 					= $this->cl->insert_code_library($table, $fields, TRUE);
				
				$table 							= $this->cl->tbl_param_dropdown_conditions;
				//INSERT DATA INTO OTHER DEDUCTION DETAILS
				for ($i = 0; $i < count($params['where_condition']); $i++ ) {

					$fields                    	= array();
					$fields['dropdown_id']     	= $dropdown_id;
					$fields['where_condition'] 	= $params['where_condition'][$i];
					$fields['where_field']     	= $params['where_field'][$i];
					$fields['where_operator']  	= $params['where_operator'][$i];
					$fields['where_value']     	= $params['where_value'][$i];

					$this->cl->insert_code_library($table, $fields);
				}
				
				//MESSAGE ALERT
				$message 						= $this->lang->line('data_saved');

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[]  				= array($fields);//$this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where							= array();
				$key 							= $this->get_hash_key('dropdown_id');
				$where[$key]					= $params['id'];
				
				$audit_action[]					= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  				= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);

				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				//EDIT
				$where							= array();
				$key 							= $this->get_hash_key('dropdown_id');
				$where[$key]					= $params['id'];

				$this->cl->delete_code_library($this->cl->tbl_param_dropdown_conditions, $where);

				$dropdown  						= $this->cl->get_code_library_data(array("dropdown_id"), $table, $where, FALSE);
				//INSERT DATA INTO CONDITION DETAILS
				for ($i = 0; $i < count($params['where_condition']); $i++ ) {

					$fields                    	= array();
					$fields['dropdown_id']     	= $dropdown['dropdown_id'];
					$fields['where_condition'] 	= $params['where_condition'][$i];
					$fields['where_field']     	= $params['where_field'][$i];
					$fields['where_operator']  	= $params['where_operator'][$i];
					$fields['where_value']     	= $params['where_value'][$i];

					$this->cl->insert_code_library($this->cl->tbl_param_dropdown_conditions, $fields);
				}

				//MESSAGE ALERT
				$message 						= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  				= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 						= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, $params['dropdown_name']);
	
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
			$table 				= $this->cl->tbl_param_dropdown;

			$where				= array();
			$key 				= $this->get_hash_key('dropdown_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['dropdown_name']);
	
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
		
			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'dropdown_table',
			"path"				=> PROJECT_MAIN . '/code_library_system/dropdown/get_dropdown_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_dropdown($params)
	{
		$fields                  = array();
		$fields['dropdown_name'] = "Dropdown Name";
		$fields['columns']       = "Dropdown Columns";
		$fields['table']         = "Dropdown Table";

		$this->check_required_fields($params, $fields);	

		return $this->_validate_dropdown_input ($params);
	}

	private function _validate_dropdown_input($params) 
	{
		try {
			
			$validation ['dropdown_name'] 		= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Dropdown Name',
					'max_len' 					=> 50 
			);
			$validation ['columns'] 			= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Dropdown Columns',
					'max_len' 					=> 100
			);
			$validation ['table'] 				= array (
					'data_type' 				=> 'string',
					'name' 						=> 'Dropdown Table',
					'max_len' 					=> 50
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */