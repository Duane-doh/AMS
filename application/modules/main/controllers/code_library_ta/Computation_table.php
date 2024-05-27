<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Computation_table extends Main_Controller {
	private $module = MODULE_TA_COMPUTATION_TABLE;
	private $sort_num_add;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
// 		$this->$sort_num_add=0;
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL, $modal)
	{

		try
		{
			$data                     = array();
			$resources                = array();
			$data['action_id']        = $action_id;
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js']		= array(JS_DATATABLE);
			$resources['datatable'][]	= array('table_id' => 'computation_table', 'path' => 'main/code_library_ta/computation_table/get_computation_table_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_computation_table_detail' 		=> array(
					'controller'		=> 'code_library_ta/'.__CLASS__,
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_computation_table_detail',
					'multiple'			=> true,
					'height'			=> '420px',
					'size'				=> 'md',
					'title'				=> 'Computation Table'
				)
			);
			
			$resources['load_delete'] 		= array(
				'code_library_ta/'.__CLASS__,
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

		$this->load->view('code_library/tabs/computation_table', $data);
		$this->load_resources->get_resource($resources);
	}


	public function get_computation_table_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array(
				"DATE_FORMAT(A.start_date, '%Y/%m/%d') as start_date",
				"DATE_FORMAT(A.end_date, '%Y/%m/%d') as end_date",
					"A.computation_table_id", "B.type_name"," A.active_flag"
			);
			$bColumns					= array("A.start_date","B.end_date", "type_name", "active_flag");
			$table 	  					= $this->cl->tbl_param_computation_table;
			$where						= array();
			$comp_table 				= $this->cl->get_computation_table_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(computation_table_id)) AS count"), $this->cl->tbl_param_computation_table, NULL, false);
			$iFilteredTotal 			= $this->cl->comp_table_list_filtered_length($aColumns, $bColumns, $params, $table);
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["count"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_DELETE);
			// echo"<pre>";
			// print_r($permission_view);
			// print_r($permission_edit);
			// print_r($permission_delete);
			// die();
			foreach ($comp_table as $aRow):
				$row 					= array();

				$action 				= "<div class='table-actions'>";
				$computation_table_id 	= $aRow["computation_table_id"];
				$id 					= $this->hash ($computation_table_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("computation table", "'.$url_delete.'")';
				

				$row[] = strtoupper($aRow['start_date']);
				$row[] = strtoupper(($aRow['end_date'] == NULL) ? "PRESENT": $aRow['end_date']);
				$row[] = strtoupper($aRow['type_name']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				if($permission_view)
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_computation_table_detail' onclick=\"modal_computation_table_detail_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_computation_table_detail' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_computation_table_detail_init('".$edit_action."')\"></a>";
				if($permission_delete)
				if($aRow['active_flag'] == "Y")
				{
					$action .= "<a href='javascript:;' id='delete_btn' onclick='hidepanel();" . $delete_action. "; ' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
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

	public function get_computation_table_detail_list()
	{

		try
		{
			$params 					= get_params();
			$params         = get_params();
			if($params['sSortDir_0'] == 'asc')
			{
				$params['sSortDir_0'] = "desc";
			}else
			{
				$params['sSortDir_0'] = "asc";
			}
			$aColumns					= array(
				"computation_table_detail_id",
				"DATE_FORMAT(start_date, '%Y/%m/%d') as start_date",
				"DATE_FORMAT(end_date, '%Y/%m/%d') as end_date",
				"active_flag"
			);
			$bColumns					= array("start_date", "end_date", "active_flag");
			$table 	  					= $this->cl->tbl_param_computation_table_detail;
			$key 						= $this->get_hash_key('computation_table_id');
			$where						= $params['id'];
			$comp_table 				= $this->cl->get_computation_table_detail_list($aColumns, $bColumns, $params, $table, $where);			
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(computation_table_id)) AS count"), $this->cl->tbl_param_computation_table_detail, NULL, false);
			$iFilteredTotal 			= $this->cl->comp_table_detail_list_filtered_length($aColumns, $bColumns, $params, $table, $where);
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal,
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_TA_COMPUTATION_TABLE, ACTION_DELETE);
			foreach ($comp_table as $aRow):
				$row 					= array();

				$action 				= "<div class='table-actions'>";
				$computation_table_detail_id 	= $aRow["computation_table_detail_id"];
				$id 					= $this->hash ($computation_table_detail_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("computation table", "'.$url_delete.'")';
				
				// $row[] = $computation_table_detail_id;
				$row[] = strtoupper($aRow['start_date']);
				$row[] = strtoupper(($aRow['end_date'])) == NULL? "PRESENT": $aRow['end_date'];
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				if($permission_view)
				{
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger m-l-md m-t-md' data-modal='modal_computation_table_detail_details' onclick=\"modal_computation_table_detail_details_init('".$view_action."')\" style='content:url(../../../static/images/search.png);'></a>";
				}
				if($aRow['active_flag'] == "Y")
				{
					if($params['action'] == ACTION_EDIT)
					{
						if($permission_edit)
						{
							$action .= "<a href='javascript:;' class='edit tooltipped md-trigger m-l-sm m-t-md' data-modal='modal_computation_table_detail_details' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_computation_table_detail_details_init('".$edit_action."')\" style='content:url(../../../static/images/edit.png);'></a>";
						}
						if($permission_delete)
						{
							$action .= "<a href='javascript:;' onclick='" . $delete_action. ";modal_computation_table_detail.closeModal();' class='delete tooltipped m-l-sm m-t-md' data-tooltip='DELETE' data-position='bottom' data-delay='50' style='content:url(../../../static/images/trash.png);'></a>";
						}
					}
						
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

	public function modal_add_computation_table_detail($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			
			$data                     = array();
			$resources                = array();
			$resources['load_css'] 	= array(CSS_MODAL_COMPONENT,CSS_DATATABLE,CSS_COLORPICKER);
			$resources['load_js']	= array(JS_MODAL_CLASSIE,JS_MODAL_EFFECTS,JS_DATATABLE, JS_CALENDAR, JS_CALENDAR_MOMENT, JS_COLORPICKER, JS_COLORGROUP);
			$data ['action_id'] 			= $action;
			$data ['nav_page']  			= CODE_LIBRARY_COMPUTATION_TABLE;
			$data ['action']    			= $action;
			$data ['salt']      			= $salt;
			$data ['token']    				= $token;
			$data ['id']       			 	= $id;
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
		
		$this->load->view('code_library/modals/modal_computation_table_detail', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_computation_table_detail($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data                     = array();
			$resources                = array();
			$data['action_id']        = $action_id;
			$resources['load_css'] 	= array(CSS_MODAL_COMPONENT,CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js']	= array(JS_MODAL_CLASSIE,JS_MODAL_EFFECTS,JS_SELECTIZE, JS_CALENDAR, JS_CALENDAR_MOMENT, JS_DATETIMEPICKER);
			$data ['action_id'] 			= $action;
			$data ['nav_page']  			= CODE_LIBRARY_COMPUTATION_TABLE;
			$data ['action']    			= $action;
			$data ['salt']      			= $salt;
			$data ['token']    				= $token;
			$data ['id']       			 	= $id;
			if(!EMPTY($id))
			{
				$data ['action_id'] 			= $action;
				$data ['nav_page']  			= CODE_LIBRARY_COMPUTATION_TABLE;
				$data ['action']    			= $action;
				$data ['salt']      			= $salt;
				$data ['token']    				= $token;
				$data ['id']       			 	= $id;
				//EDIT
				$table 						 	= $this->cl->tbl_param_computation_table_detail;
				$where						 	= array();
				$key 					 		= $this->get_hash_key('computation_table_id');
				$where[$key]			 		= $id;
				$comp_table_detail_info 			 	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	

				$table 						 	= $this->cl->tbl_param_computation_table;
				$where						 	= array();
				$key 					 		= $this->get_hash_key('computation_table_id');
				$where[$key]			 		= $id;
				$comp_table_info 			 	= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	
				$data['comp_table_info'] 		= $comp_table_info;
				foreach ($comp_table_detail_info as $rowVal)
				{
					if($comp_table_info['computation_table_type_id'] == '5')
					{
						$num_equiv = $rowVal['computation_type_equivalent']/2;
					}
					else{
						$num_equiv = $rowVal['computation_type_equivalent'];
					}
					$id 					= $this->hash ($rowVal['computation_table_detail_id']);
					$salt 					= gen_salt();
					$computation_type_equivalent = $rowVal['computation_type_equivalent'];
					$point_equivalent 			 = $rowVal['point_equivalent'];
					$data['data_row'] .= "<tr><td> ".$num_equiv."</td><td><input class='number' name='values[".$computation_type_equivalent."]->".$point_equivalent."' type='number' step='.001' style='text-align:center;' value='". $rowVal['point_equivalent']."' required></td> ";
				}
			}
			else
			{
				
				$table 						 	= $this->cl->tbl_param_computation_table_type;
				$where						 	= array();
				$where['active_flag'] 			= "Y";
				$comp_table_type 			 	= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				$data['comp_table_type'] 		= $comp_table_type;
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
		
		$this->load->view('code_library/modals/modal_computation_table_detail', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_computation_table_detail_details($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 						= array();
			$resources['load_css'] 			= array(CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js'] 			= array(JS_SELECTIZE, JS_DATETIMEPICKER);
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			
			
			if($action == ACTION_ADD)
			{
				$data ['action_id'] 			= $action;
			$data ['nav_page']  			= CODE_LIBRARY_COMPUTATION_TABLE;
			$data ['action']    			= $action;
			$data ['salt']      			= $salt;
			$data ['token']    				= $token;
				//ADD				
				$table 						 	= $this->cl->tbl_param_computation_table;
				$where						 	= array();
				$where['computation_table_id']	= $id;
				$comp_table_info 			 	= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	

				$table 						 	= $this->cl->tbl_param_computation_table_detail;
				$where						 	= array();
				$where['computation_table_id']	= $id;
				$where['active_flag']			= "Y";
				$comp_table_detail_info 			 	= $this->cl->get_code_library_data(array("*"), $table, $where, FALSE);	

				$data['type_name']					= $comp_table_info['type_name'];
				$data['computation_table_id']	 	= $comp_table_info['computation_table_id'];
				$num_of_details_fields              = $comp_table_info['num_of_details_fields'];
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;

				for($cnt = 1; $cnt <= $num_of_details_fields; $cnt++)
				{
					$salt 					= gen_salt();
					$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
					$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
					$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
					$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view."/".$cnt ;	
					$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit."/".$cnt ;
					if($comp_table_info['computation_table_id'] == '11')
					{
						$cnt1 = $cnt/2;
					}
					else
					{
						$cnt1 = $cnt;
					}

					$point_equivalent 			 =  0;
					$data['data_row'] .= "<tr><td> ".$cnt1."</td><td><input class='number' name='values[".$cnt."]->".$point_equivalent."' type='number' step='.001' style='text-align:center;' required></td> ";
				}

				
			}
			else{
				

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
		
		$this->load->view('code_library/modals/modal_computation_table_detail_details', $data);
		$this->load_resources->get_resource($resources);
	}

	public function add_details()
	{
		try
		{
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


			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_computation_table;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;

				// Insert new effectivity dates
				//reset fields
				$where = array();
				$fields = array();
				$fields['effectivity_date'] 		= $params['effectivity_date'];
				$fields['type_name'] 				= $params['type_name'];
				$fields['num_of_details_fields'] 	= $params['num_of_details'];
				$fields['active_flag'] 				= "Y";

				$audit_action[]						= AUDIT_INSERT;

				//Insert Data
				$comp_table_detail_id 			= $this->cl->insert_code_library($table, $fields, TRUE);
				$activity 				= "Inserted new computation table row";

				
				
				//MESSAGE ALERT
				$message 				= "Computation table Details Added";
				//WHERE VALUES
				
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// LOG AUDIT TRAIL
				// $this->audit_trail->log_audit_trail(
				// $activity, 
				// $this->module, 
				// $curr_detail, 
				// $audit_action, 
				// $audit_table,
				// $audit_schema
				// );
				$status = TRUE;
			
			
			Main_Model::commit();
			
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
	
	public function process_details()
	{
		try
		{
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
			if(!empty($params['end_date']))
				{
					if($params['end_date'] <= $params['start_date'])
					{
						throw new Exception ( "Start date and End date overlapped");
					}
				}

			// SERVER VALIDATION
			$valid_data 								= $this->_validate_data_comp_data($params);	

			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_computation_table;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
			if(EMPTY($params['id']))
			{
				//validate if added start Date is overlapped with other dates
				
				
				$where           					= array();
				$where['computation_table_type_id']	= $params['computation_table_type_id'];
				$where['active_flag']				= "N";
				$prev_dates							= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				foreach($prev_dates as $prev_date)
				{
					if($params['start_date'] <= $prev_date['end_date'] && $params['start_date'] >= $prev_date['start_date'])
					{
						throw new Exception ( "Start Date overlapped with previous record");
					}
				}
				//Update previous active computation table end date and set to inactive
				$start_date = $params['start_date'];
				$reduce_start_date= date('Y-m-d',strtotime('-1 day' , strtotime ( $start_date ) ) );
				$fields['end_Date'] 				= $reduce_start_date; 
				$fields['date_modified'] 			= date("Y-m-d");
				$fields['active_flag']	 			= "N";
				$where           					= array();
				$where['computation_table_type_id']	= $params['computation_table_type_id'];
				$where['active_flag']		 		= "Y";
				$audit_action[]						= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				$activity 				= "Updated computation table detail dates";
				
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
				
				
				// Insert new effectivity dates
				//reset fields
				$where = array();
				$fields = array();
				$fields['start_date'] 				= $start_date; 
				$fields['date_created'] 			= date("Y-m-d");
				$fields['computation_table_type_id']= $params['computation_table_type_id'];
				$fields['active_flag']	 			= "Y";
				
				$audit_action[]						= AUDIT_INSERT;
				
				//Insert Data
				$comp_table_id 			= $this->cl->insert_code_library($table, $fields, TRUE);
				$activity 				= "Inserted new computation table detail effectivity date";
				
				
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
				
				// begin insert
				$audit_action[]			= AUDIT_INSERT;
				$table 			= $this->cl->tbl_param_computation_table_detail;
				$audit_table[]	= $table;
				$audit_schema[]	= DB_MAIN;
				$fields = array();
				$where = array();
				//INSERT DATA
				foreach($params['values'] as $computation_type_equivalent => $point_equivalent)
				{
					$fields = array();
					$fields['computation_type_equivalent'] = $computation_type_equivalent;
					$fields['point_equivalent'] = $point_equivalent;
					$fields['computation_table_id'] = $comp_table_id;
					
					$comp_table_detail_details_id 			= $this->cl->insert_code_library($table, $fields, TRUE);
					
				}
				
				//MESSAGE ALERT
				$message 				= "Computation table Details Added";
				//WHERE VALUES
				
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				
				$activity 				= "Inserted new computation table detail details rows";
				
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
				$status = TRUE;
			
			}
			else
			{
				//Update computation table detail 
				if($params['end_date'] == 'PRESENT')
				{
					$params['end_date'] = NULL;
				}
				if(!empty($params['end_date']))
				{
					$fields['end_date'] = $params['end_date'];
					$fields['active_flag'] = "N";
				}
				$fields['start_date'] 				= $params['start_date']; 
				$fields['date_modified'] 			= date("Y-m-d");
				$where           					= array();
				$key								= $this->get_hash_key('computation_table_id');
				$where[$key] 						= $params['id'];
				$audit_action[]						= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);

				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				$activity 				= "Updated computation table detail dates";

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
				
				//prepare data for query to param computation table detail details
				$table 			= $this->cl->tbl_param_computation_table_detail;
				$audit_table[]	= $table;
				$audit_schema[]	= DB_MAIN;
				$where          = array();
				$key			= $this->get_hash_key('computation_table_id');
				$where[$key] 	= $params['id'];
				$audit_action[]			= AUDIT_DELETE;
				// Delete before inserting new data
				$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
				$this->cl->delete_code_library($table, $where);

				$activity 				= "Deleted computation table detail details rows";

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
				

				$audit_action[]			= AUDIT_INSERT;
				// begin insert
				//INSERT DATA
				foreach($params['values'] as $computation_type_equivalent => $point_equivalent)
				{
					$fields = array();
					$fields['computation_type_equivalent'] = $computation_type_equivalent;
					$fields['point_equivalent'] = $point_equivalent;
					$fields['computation_table_id'] = $params['computation_table_id'];

					$comp_table_detail_details_id 			= $this->cl->insert_code_library($table, $fields, TRUE);

				}

				//MESSAGE ALERT
				$message 				= "Computation table Details updated";
				//WHERE VALUES

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 			= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL

				
				$activity 				= "Inserted new computation table detail details rows";

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
				$status = TRUE;
			}
			
			Main_Model::commit();
			
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
			$table 				= $this->cl->tbl_param_computation_table;
			$where				= array();
			$key 				= $this->get_hash_key('computation_table_id');
			$where[$key]		= $id;
			
			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
			
			
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			// GET THE DETAIL OF COMPUTATION TABLE DATE TO BE ACTIVATED
			$this->cl->delete_code_library($table, $where);

			$where           					= array();
			$order_by          					= array();
			$order_by['end_date']				="desc";
			$where['computation_table_type_id'] 						= $prev_detail[0][0]['computation_table_type_id'];
			$comp_table_info  					= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE, $order_by);

			$comp_table_info_id = $comp_table_info[0]['computation_table_id'];

			$fields 							= array();
			$fields['end_date'] 				= NULL; 
			$fields['date_modified'] 			= date("Y-m-d");
			$fields['active_flag'] 					= "Y";

			$where           					= array();
			$where['computation_table_id']						= $comp_table_info_id;
			$audit_action[]						= AUDIT_UPDATE;
			
			
			//UPDATE DATA
			$this->cl->update_code_library($table, $fields, $where);


				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				$activity 				= "Updated computation table detail date";

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
			
			$msg 				= "Deleted a data from computation table detail";
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['computation_table_id']);
	
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

			$table 				= $this->cl->tbl_param_computation_table_detail;
			$where				= array();
			$key 				= $this->get_hash_key('computation_table_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			
			$msg 				= "Deleted a data from computation table detail details";
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, $prev_detail[0][0]['computation_table_id']);
	
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
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}

		$post_data = array();

		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'computation_table',
			"path"				=> PROJECT_MAIN . '/code_library_ta/computation_table/get_computation_table_list/',
			"advanced_filter" 	=> true,
			"post_data"         => $post_data 
		);
		echo json_encode($info);
	}

	private function _validate_data_comp_data($params)
	{
		$fields                 				= array();
		$fields['start_date']  					= "Start Date";
		$this->check_required_fields($params, $fields);	
		
		return $this->_validate_computation_table_input($params);
	}

	private function _validate_computation_table_input($params) 
	{
		try {
			
			$validation ['start_date'] = array (
					'data_type' 					=> 'date',
					'name' 							=> 'Start Date',
					'max_len'						=> 11
			);

			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */ 