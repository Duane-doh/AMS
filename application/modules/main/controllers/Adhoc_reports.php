<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adhoc_reports extends Main_Controller {
	
	private $module = MODULE_ADHOC_REPORTS;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('adhoc_reports_model', 'arm');
		$this->load->model('code_library_model', 'cl');

	}
	
	public function module($page=NULL)
	{
		try
		{

			$data 			= array();
			$resources 		= array();
			$data['page'] 	= $page;
		
			
			$this->template->load('adhoc_reports/display_adhoc_reports_info', $data, $resources);

		}
		catch (PDOException $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $message = $e->getMessage();
			RLog::error($message);
		}
	}

	public function get_tab($form)
	{

		try
		{
			$data 					= array();
			$resources['load_css'] 	= array(CSS_LABELAUTY, CSS_SELECTIZE,CSS_DATETIMEPICKER, CSS_CALENDAR);
			$resources['load_js'] 	= array(JS_LABELAUTY,JS_SELECTIZE, JS_DATETIMEPICKER, JS_CALENDAR, JS_CALENDAR_MOMENT);
			switch ($form) 
			{

				
				case 'data_download':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'data_download_table', 'path' => 'main/adhoc_reports/get_data_download_list', 'advanced_filter' => TRUE);
					// $resources['datatable'][]	= array('table_id' => 'download_history_table', 'path' => 'main/adhoc_reports/get_download_history_list', 'advanced_filter' => TRUE);
					$resources['load_modal']	= array(
							'modal_data_download'		=> array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_data_download',
									'multiple'		=> true,
									'height'		=> '450px',
									'size'			=> 'xl',
									'title'			=> 'Data Download'
							)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;

				case 'table_group':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['datatable'][]	= array('table_id' => 'table_group_table', 'path' => 'main/adhoc_reports/get_table_group_list', 'advanced_filter' => TRUE);
					$resources['load_modal'] 	= array(
							'modal_table_group' 		=> array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_table_group',
									'multiple'		=> true,
									'height'		=> '450px',
									'size'			=> 'lg',
									'title'			=> 'Table Group'
							)
					);
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete',
						PROJECT_MAIN
					);
					$view_form = $form;
				break;
				/*------------------------------ ADHOC REPORTS SWITCH TAB END ------------------------------*/

			}

			$this->load->view('adhoc_reports/tabs/'.$view_form, $data);
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

	/*------------------------------ ADHOC REPORTS PROCESS START ------------------------------*/
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
			switch ($params['module']) 
			{
				case 'data_download':
					// SERVER VALIDATION 
					$valid_data             = $this->_validate_data_download_list($params);
					
					if($params['action'] == ACTION_DOWNLOAD)
					{
						 
						$result                      = $this->_download_data($params);
						$data['reference_no']        = $result['reference_no'];
						$data['download_history_id'] = $result['download_history_id'];
						$data['remarks']             = $result['remarks'];
						$msg                         = $result['message'];
						$status                      = $result['status'];

						$fields                         = array();
						$fields['last_downloaded_time'] = $result['end_download'];
						$table                          = $this->arm->tbl_download_template_hdr;
						$key                            = $this->get_hash_key('reference_no');
						$where[$key]                    = $result['reference_no'];
						$this->arm->update_adhoc_data($table, $fields, $where);

					}
					else
					{
						//SET FIELDS VALUE
						$fields['name']         = $params['hdr_download_name'];
						$fields['status']       = $params['hdr_status'];
						if($params['hdr_group'] != NULL) {
							$fields['group_hdr_id'] = $params['hdr_group'];
						}
						$fields['description']  = $params['hdr_description'];
						$fields['notes']        = $params['hdr_notes'];
						$fields['time_entered'] = Date('Y-m-d h:i:s');
						$fields['entered_by']   = $this->session->user_id;

						$details = array();
						for($i=0; $i<count($params['table']); $i++)
						{
							$details[$i]['table_name']  = $params['table'][$i];
							$details[$i]['field_name']  = $params['field'][$i];
							$details[$i]['dropdown']    = !EMPTY($params['dropdown'][$i]) ? $params['dropdown'][$i] : NULL;
							$details[$i]['start_value'] = $params['column_start_value'][$i];
							$details[$i]['end_value']   = $params['column_end_value'][$i];
						}

						// BEGIN TRANSACTION
						Main_Model::beginTransaction();
						$table 			= $this->arm->tbl_download_template_hdr;
						$audit_table[]	= $table;
						$audit_schema[]	= DB_MAIN;
							
						if(EMPTY($params['id']))
						{
							//INSERT 

							//SET AUDIT TRAIL DETAILS
							$audit_action[]       = AUDIT_INSERT;
							
							$prev_detail[]        = array();
							
							//INSERT DATA
							$download_template_id = $this->arm->insert_adhoc_data($table, $fields, TRUE);

							//WHERE 
							
							$id                   = $this->hash($download_template_id);
							
							$where                = array();
							$key                  = $this->get_hash_key('reference_no');
							$where[$key]          = $id;
							
							//MESSAGE ALERT
							$message              = $this->lang->line('data_saved');
							
							// GET THE DETAIL AFTER INSERTING THE RECORD
							$curr_detail[]        = $this->arm->get_adhoc_data(array("*"), $table, $where);	
							
							// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
							$activity             = "%s has been added";
							$params['id']		  = $id;
						}
						else
						{
							//UPDATE 

							//WHERE 
							$where                = array();
							$key                  = $this->get_hash_key('reference_no');
							$where[$key]          = $params['id'];
							
							$audit_action[]       = AUDIT_UPDATE;
							
							// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
							$prev_detail[]        = $this->arm->get_adhoc_data(array("*"), $table, $where);

							//UPDATE DATA
							$this->arm->update_adhoc_data($table, $fields, $where);
							$this->arm->delete_adhoc_data($this->arm->tbl_download_template_dtl, $where);
							
							//MESSAGE ALERT
							$message              = $this->lang->line('data_updated');
							
							// GET THE DETAIL AFTER UPDATING THE RECORD
							$curr_detail[]        = $this->arm->get_adhoc_data(array("*"), $table, $where);

							$download_template_id = $curr_detail[0][0]['reference_no'];

							// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
							$activity             = "%s has been updated";
							
						}
						
						$activity = sprintf($activity, $params['hdr_download_name']);
				
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
						
						$table = $this->arm->tbl_download_template_dtl;

						for($i=0; $i < count($details); $i++)
						{
							$details[$i]['reference_no'] = $download_template_id;
							$details[$i]['dropdown'] = $details[$i]['dropdown'] > 0 ? $details[$i]['dropdown'] : NULL;
						}
						// INSERT ALL DETAILS OF TEMPLATE AND DELETE ALL PREVIOUS IF EXIST
						$this->arm->insert_adhoc_data($table, $details);

						Main_Model::commit();
					}
					break;
				case 'table_group':

					//SET FIELDS VALUE
					// $valid_data	= $this->_validate_table_group($params);
					$valid_data = $params;

					if(!EMPTY($params['id']))
					{
						$table       = $this->arm->tbl_table_group_hdr;
						$where       = array();
						$key         = $this->get_hash_key("group_hdr_id");
						$where[$key] = $params['id'];
						$return_val  = $this->arm->get_adhoc_data(array("group_name"), $table, $where, FALSE);
						$script   	 = 'DROP VIEW ' . strtolower($return_val['group_name']);

						$this->arm->run_query($script);
					}
					else
					{
						$table       = $this->arm->tbl_table_group_hdr;
						$where       = array();
						$key         = 'group_name';

						$where[$key] = 'ptis_' . strtolower($params['group_name']);

						$return_val  = $this->arm->get_adhoc_data(array("group_name"), $table, $where, FALSE);

						if(!EMPTY($return_val['group_name']))
						throw new Exception("Table Group is already exist");
					}


					$columns = '';
					// GET THE AVAILABLE COLUMNS FOR THE MAIN TABLE
					$fields	  = $this->_get_view_field($valid_data['main_table']);

					if(!EMPTY($fields))
					{
						foreach ($fields AS $flds) 
						{
							$columns .= $valid_data['main_table'] . '.' . $flds['column_name'] . ' AS ' .$valid_data['main_table'] . '_' . $flds['column_name'] . ', ';
						}
					}
					foreach ($valid_data['join_table'] as $tbl) 
					{
						$fields		 = $this->_get_view_field($tbl);
						if(!EMPTY($fields))
						{
							foreach ($fields as $flds) 
							{
								$columns	.= $tbl . '.' . $flds['column_name'] . ' AS ' .$tbl . '_' . $flds['column_name'] . ', ';
							}
						}
						
					}
					$columns	= substr($columns	, 0, -2);
					
					$query 	= ' CREATE OR REPLACE VIEW ' . 'ptis_' . $valid_data['group_name'] . ' AS ';	 
					$query .= ' SELECT ' . $columns . ' FROM ' . $valid_data['main_table'];

					$join_count	= count($valid_data['join']);
					foreach ($valid_data['join'] as $key => $join) 
					{

						$query .= ' ' .$join .' ' . $valid_data['join_table'][$key];

						if(ISSET($params['on_condition'][$key]))
						{
							$query .= ' ON ';
							foreach($params['alias_a'][$key] as $index => $table_from)
							{
								if(ISSET($params['operator'][$key][$index]))
								{
									$query .= ' ' . $params['operator'][$key][$index] . ' ';
								}
								$query .= $table_from . '.' . $params['field_a'][$key][$index];
								$query .= ' = ' . $params['alias_b'][$key][$index] . '.' . $params['field_b'][$key][$index];  
							}
						}
					}

					// EXECUTE THE SCRIPT TO CREATE THE VIEW TABLE

					$this->arm->execute($query);
					
					Main_Model::beginTransaction();

					$table                  = $this->arm->tbl_table_group_hdr;
					$fields                 = array();
					$fields['group_name']   = 'ptis_' . strtolower($valid_data['group_name']);
					$fields['columns']      = '*';
					$fields['table_name']   = $valid_data['main_table'];
					$fields['created_by']    = $this->session->userdata('user_id');
					$fields['created_date'] = date("Y-m-d H:i:s");
					
					if(EMPTY($params['id']))
					{
						$hdr_id				= $this->arm->insert_adhoc_data($table, $fields, TRUE);
						// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
						$audit_action[]		= AUDIT_INSERT;
						$audit_table[]		= $table;
						$audit_schema[]		= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[]		= array($fields);
						// END: THESE VARIABLES ARE USED FOR AUDIT TRAIL

						$table_dtl			= $this->arm->tbl_table_group_dtl;
						$table_cond			= $this->arm->tbl_table_group_condition;
				
						foreach ($valid_data['join'] as $key => $join) 
						{
							$fields_dtl						= array();
							$fields_dtl['group_hdr_id']		= $hdr_id;
							$fields_dtl['join_connection']	= $join;
							$fields_dtl['table_name']		= $valid_data['join_table'][$key];
							$fields_dtl['with_condition']	= ISSET($params['on_condition'][$key]) ? 1 : 0;
							
							$dtl_id							= $this->arm->insert_adhoc_data($table_dtl, $fields_dtl,TRUE);
							// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
							$audit_action[]					= AUDIT_INSERT;
							$audit_table[]					= $table_dtl;
							$audit_schema[]					= DB_MAIN;
							$prev_detail[] 					= array();
							$curr_detail[]					= array($fields_dtl);
							
							if(ISSET($params['on_condition'][$key]))
							{
								$fields_cond	= array();	

								foreach($params['alias_a'][$key] as $index => $table_from)
								{
									$fields_cond[]		=	array(
										'group_dtl_id'	=> $dtl_id,
										'first_table'	=> $table_from,
										'first_field'	=> $params['field_a'][$key][$index],
										'second_table'	=> $params['alias_b'][$key][$index],
										'second_field'	=> $params['field_b'][$key][$index],
										'operator'		=> ISSET($params['operator'][$key][$index]) ? $params['operator'][$key][$index] : 'ON'
									);
								}

								$this->arm->insert_adhoc_data($table_cond, $fields_cond);

								// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
								$audit_action[] = AUDIT_INSERT;
								$audit_table[]  = $table_cond;
								$audit_schema[] = DB_MAIN;
								$prev_detail[]  = array();
								$curr_detail[]  = $fields_cond;
								// END: THESE VARIABLES ARE USED FOR AUDIT TRAIL
								
							}

						}


						$activity = "%s has been added.";
						$activity = sprintf($activity, 'Table Group');
						$message  = $this->lang->line('data_saved');
					}
					else
					{
						$table_hdr 	   = $this->arm->tbl_table_group_hdr;
						$where         = array();
						$key           = $this->get_hash_key("group_hdr_id");
						$where[$key]   = $params['id'];
						//AUDIT TRAIL GET PREVIOUS DATA
						$group_hdr     = $this->arm->get_adhoc_data(array("*"), $table_hdr, $where);
						$group_id      = $this->arm->get_adhoc_data(array("group_hdr_id"), $table_hdr, $where, FALSE);
						$prev_detail[] = $group_hdr;
						//END: AUDIT TRAIL GET PREVIOUS DATA

						//UPDATE DATA GROUP HEADER
						$this->arm->update_adhoc_data($table_hdr, $fields, $where);

						//END: AUDIT TRAIL GET CURRENT DATA
						$audit_action[] = AUDIT_UPDATE;
						$audit_table[]  = $table_hdr;
						$audit_schema[] = DB_MAIN;
						$curr_detail[]  = array($fields);
						//END: AUDIT TRAIL GET CURRENT DATA

						//DELETE OLD GROUP TABLE DETAILS
						$table_dtl       = $this->arm->tbl_table_group_dtl;
						$where_dtl       = array();
						$key             = $this->get_hash_key("group_hdr_id");
						$where_dtl[$key] = $params['id'];
						$group_dtl_id    = $this->arm->get_adhoc_data(array("group_dtl_id"), $table_dtl, $where);


						//DELETE OLD GROUP TABLE CONDITION
						$table_cond		= $this->arm->tbl_table_group_condition;
						$where			= array();
						$key			= 'group_dtl_id';
						$array_in		= array();

						foreach ($group_dtl_id as $ids)
						{
							$array_in[] = $ids[$key];
						}

						$where[$key] = array($array_in, array("IN"));
						
						//AUDIT TRAIL
						$audit_action[]	= AUDIT_DELETE;
						$audit_table[]	= $table_cond;
						$audit_schema[]	= DB_MAIN;
						$prev_data		= $this->arm->get_adhoc_data(array("*"), $table_cond, $where, TRUE);
						$prev_detail[] 	= $prev_data;
						//END: AUDIT TRAIL


						//AUDIT TRAIL GET CURRENT DATA
						$curr_detail[] = array(); // CURRENT DETAIL IF DELETED
						$activity      = "";
						//END: AUDIT TRAIL GET CURRENT DATA

						//AUDIT TRAIL
						$audit_action[]	= AUDIT_DELETE;
						$audit_table[]	= $table_dtl;
						$audit_schema[]	= DB_MAIN;
						$prev_data		= $this->arm->get_adhoc_data(array("*"), $table_dtl, $where_dtl, TRUE);
						$prev_detail[] 	= $prev_data;
						//END: AUDIT TRAIL

						$this->arm->delete_adhoc_data($table_dtl, $where_dtl);

						//AUDIT TRAIL GET CURRENT DATA
						$curr_detail[]	= array(); // CURRENT DETAIL IF DELETED
						$audit_activity = "";
						//END: AUDIT TRAIL GET CURRENT DATA

						//INSERT NEW GROUP DETAILS AND CONDTION
						foreach ($valid_data['join'] as $key => $join) 
						{
							$fields_dtl						= array();
							$fields_dtl['group_hdr_id']		= $group_id['group_hdr_id'];
							$fields_dtl['join_connection']	= $join;
							$fields_dtl['table_name']		= $valid_data['join_table'][$key];
							$fields_dtl['with_condition']	= ISSET($params['on_condition'][$key]) ? 1 : 0;

							$dtl_id							= $this->arm->insert_adhoc_data($table_dtl, $fields_dtl, TRUE);
							// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
							$audit_action[]					= AUDIT_INSERT;
							$audit_table[]					= $table_dtl;
							$audit_schema[]					= DB_MAIN;
							$prev_detail[] 					= array();
							$curr_detail[]					= array($fields_dtl);
							
							
							if(ISSET($params['on_condition'][$key]))
							{
								$fields_cond	= array();	

								foreach($params['alias_a'][$key] as $index => $table_from)
								{
									$fields_cond[]		=	array(
										'group_dtl_id'	=> $dtl_id,
										'first_table'	=> $table_from,
										'first_field'	=> $params['field_a'][$key][$index],
										'second_table'	=> $params['alias_b'][$key][$index],
										'second_field'	=> $params['field_b'][$key][$index],
										'operator'		=> ISSET($params['operator'][$key][$index]) ? $params['operator'][$key][$index] : 'ON'
									);
								}

								$this->arm->insert_adhoc_data($table_cond, $fields_cond);

								// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
								$audit_action[] = AUDIT_INSERT;
								$audit_table[]  = $table_cond;
								$audit_schema[] = DB_MAIN;
								$prev_detail[]  = array();
								$curr_detail[]  = $fields_cond;
								// END: THESE VARIABLES ARE USED FOR AUDIT TRAIL
								
							}
						}

						$activity = "%s has been updated.";
						$activity = sprintf($activity, 'Table Group');
						$message  = $this->lang->line('data_upated');

					}

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

					break;
			
				
			}
			$status = 1;
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

	private function _download_data($params) {

		try {

			$status = FALSE;
			$data 	= array ();
			// $params = get_params ();

			$action_id 		= $params ['action'];
			$id 			= $params ['id'];
			$salt 			= $params ['salt'];
			$token 			= $params ['token'];
			$start_value 	= $params ['start_value'];
			$end_value 		= $params ['end_value'];
			$transpose 		= $params ['transpose'];
			

			$table_names 	= $params['table_name'];
			$field_names 	= $params['field_name'];
			
			$start_download = date ( 'Y-m-d H:i:s' );
			$end_download   = date ( 'Y-m-d H:i:s' );
			
			Main_Model::beginTransaction ();
			$where             = array();
			$key               = $this->get_hash_key('reference_no');
			$where[$key]       = $id;
			$download_template = $this->arm->get_adhoc_data(array("reference_no"), $this->arm->tbl_download_template_hdr,$where,FALSE);

			$history_fields 					= array ();
			$history_fields ['remarks'] 		= $params['description'];
			$history_fields ['start_download'] 	= $start_download;
			$history_fields ['end_download'] 	= $end_download;
			$history_fields ['downloaded_by'] 	= $this->session->userdata('user_id');
			$history_fields ['reference_no']    = $download_template['reference_no'];

			$table 								= $this->arm->tbl_download_history_hdr;

			$history_id 						= $this->arm->insert_adhoc_data( $table, $history_fields, TRUE );
			$data ['download_history_id'] 		= $history_id;
			$data ['reference_no'] 				= $id;
			$data ['remarks'] 					= $params['description'];
			$data ['end_download'] 				= $end_download;
			
			// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
			$audit_action[] 	= AUDIT_INSERT;
			$audit_table[] 		= $table;
			$audit_schema[] 	= DB_MAIN;
			$prev_detail[] 		= array ();
			$curr_detail[]		= array ($history_fields);
			// END: THESE VARIABLES ARE USED FOR AUDIT TRAIL
			
			$history_fields_dtl = array ();
			$table = $this->arm->tbl_download_history_dtl;
			
			foreach ( $table_names as $key => $tn ) {

				$transpose_val = isset($transpose[$field_names [$key]]) ? 'Y' : 'N';

				$history_fields_dtl [] = array (
						'download_history_id' => $history_id,
						'table_name'          => $tn,
						'field_name'          => $field_names [$key],
						'start_value'         => $start_value [$key],
						'end_value'           => $end_value [$key],
						'transpose'           => $transpose_val
				);
			}
			
			$this->arm->insert_adhoc_data( $table, $history_fields_dtl);
			
			// START: THESE VARIABLES ARE USED FOR AUDIT TRAIL
			$audit_action []	= AUDIT_INSERT;
			$audit_table [] 	= $table;
			$audit_schema [] 	= DB_MAIN;
			$prev_detail [] 	= array ();
			$curr_detail [] 	= $history_fields_dtl;
			// END: THESE VARIABLES ARE USED FOR AUDIT TRAIL
			
			$activity = "%s has been added.";
			$activity = sprintf ( $activity, 'Data Download history' );
			$message  = $this->lang->line('data_saved');

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

		} catch ( PDOException $e ) {
			Main_Model::rollback ();
			$message = $e->getMessage ();
			RLog::error ( $e->getLine () . ": " . $e->getMessage () );
		} catch ( Exception $e ) {
			Main_Model::rollback ();
			$message = $e->getMessage ();
			RLog::error ( $e->getLine () . ": " . $e->getMessage () );
		}
		
		$data['message'] = $message;
		$data['status'] = $status;

		return $data;
	}

	/*------------------------------ ADHOC REPORTS PROCESS END ------------------------------*/


	/*------------------------ADHOC REPORTS VALIDATE REQUIRED FIELDS START ----------------------------*/

	private function _validate_data_download_list($params)
	{
		if($params['action'] == ACTION_DOWNLOAD)
		{
			if(EMPTY($params['description']))
			throw new Exception('Description is required.');
		}
		else
		{
			if(EMPTY($params['hdr_download_name']))
				throw new Exception('Download Name is required.');

			// if(EMPTY($params['hdr_status']))
			// 	throw new Exception('Download Status is required.');	
		}

		return $this->_validate_data_download_input ($params);
	}

	private function _validate_data_table_group($params)
	{
		if(EMPTY($params['sys_param_type']))
			throw new Exception('System Parameter Type is required.');	

		if(EMPTY($params['sys_param_name']))
			throw new Exception('System Parameter Name is required.');

		if(EMPTY($params['sys_param_value']))
			throw new Exception('System Parameter Value is required.');

		return $this->_validate_table_group_input ($params);
	}

	/*------------------------ADHOC REPORTS VALIDATE REQUIRED FIELDS END ----------------------------*/

	/*------------------------ADHOC REPORTS VALIDATE INPUT START ----------------------------*/

	private function _validate_data_download_input($params) 
	{
		try {
			
			$validation ['check_list_name'] = array (
					'data_type' => 'string',
					'name' 		=> 'Checklist Name',
					'max_len' 	=> 45 
			);
			$validation ['check_list_type'] = array (
					'data_type' => 'string',
					'name' 		=> 'Checklist Type',
					'max_len' 	=> 45 
			);
			$validation ['check_list_description'] = array (
					'data_type' => 'string',
					'name' 		=> 'Checklist Description',
					'max_len' 	=> 255 
			);
			$validation ['active_flag'] = array (
					'data_type' => 'string',
					'name' 		=> 'Active Flag',
					'max_len' 	=> 1 
			);
			
			return $this->validate_inputs($params, $validation );
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_table_group_input($params) 
	{
		try {
			
			$validation ['sys_param_type'] = array (
					'data_type' => 'string',
					'name' 		=> 'System Parameter Name',
					'max_len' 	=> 45 
			);
			$validation ['sys_param_name'] = array (
					'data_type' => 'string',
					'name' 		=> 'System Parameter Type',
					'max_len' 	=> 100 
			);
			$validation ['sys_param_value'] = array (
					'data_type' => 'string',
					'name' 		=> 'System Parameter Value',
					'max_len' 	=> 45 
			);
			$validation ['active_flag'] = array (
					'data_type' => 'string',
					'name' 		=> 'Active Flag',
					'max_len' 	=> 1 
			);
			
			return $this->validate_inputs($params, $validation );
		} catch ( Exception $e ) {
			throw $e;
		}
	}

	/*------------------------ADHOC REPORTS VALIDATE VALIDATE INPUT END ----------------------------*/

	/*------------------------------ ADHOC REPORTS GET LIST START------------------------------*/
	
	public function get_data_download_list()
	{

		try
		{
			$params 		 = get_params();
				
			$aColumns		 = array("reference_no", "name", "status", "last_downloaded_time");
			$bColumns		 = array("name", "last_downloaded_time", "last_downloaded_time");
			
			$where			 = array();
			$data_download 	 = $this->arm->get_download_templates($aColumns, $bColumns, $params, $where);
			$iTotal   		 = $this->arm->get_download_templates(array("COUNT(DISTINCT(reference_no)) as count" ), $bColumns, $params, $where, false);

			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["count"],
				"iTotalDisplayRecords" => count($data_download),
				"aaData" => array()
			);
			$cnt = 0;

			foreach ($data_download as $aRow):
				$cnt++;
				$row             = array();
				$action          = "<div class='table-actions'>";
				
				$reference_no       = $aRow["reference_no"];
				$id                 = $this->hash($reference_no);
				$salt               = gen_salt();
				
				$token_view         = in_salt($id . '/' . ACTION_VIEW, 		$salt);
				$token_edit         = in_salt($id . '/' . ACTION_EDIT, 		$salt);
				$token_download     = in_salt($id . '/' . ACTION_DOWNLOAD, 	$salt);
				$token_delete       = in_salt($id . '/' . ACTION_DELETE, 	$salt);
				$token_history      = in_salt($id . '/' . ACTION_HISTORY, 	$salt);
				
				$view_action        = ACTION_VIEW 		. "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action        = ACTION_EDIT 		. "/". $id . "/" . $salt  . "/" . $token_edit;	
				$download_action    = ACTION_DOWNLOAD 	. "/". $id . "/" . $salt  . "/" . $token_download;	
				$history_action     = ACTION_HISTORY 	. "/". $id . "/" . $salt  . "/" . $token_history;		
				$url_delete         = ACTION_DELETE		. "/". $id  ."/"  .$salt   ."/" . $token_delete."/data_download";
				$delete_action      = 'content_delete("Download template", "'.$url_delete.'")';
				
				
				$row[]              = '<center>' . ucwords($aRow["name"]) . '</center>';
				$row[]              = '<center>' . format_date($aRow["last_downloaded_time"]) . '</center>';
				$row[]              = '<center>' . (($aRow["status"]) ? 'Published' : 'Draft') . '</center>';
				
				
				$action             .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_data_download' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_data_download_init('".$view_action."')\"></a>";
				$action             .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_data_download' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_data_download_init('".$edit_action."')\"></a>";
				$action             .= "<a href='#!' class='tooltipped md-trigger' data-modal='modal_data_download' data-tooltip='Download' data-position='bottom' data-delay='50' onclick=\"modal_data_download_init('".$download_action."')\"><i class='material-icons' style='color:#555555'>cloud_download</i></a>";
				$action             .= "<a href='#!' class='tooltipped md-trigger' data-modal='modal_data_download' data-tooltip='History' data-position='bottom' data-delay='50' onclick=\"modal_data_download_init('".$history_action."')\"><i class='material-icons' style='color:#555555'>restore</i></a>";
				if(EMPTY($aRow['last_downloaded_time']))
				$action             .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				$action             .= "</div>";
				$row[]              = $action;
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

	public function get_table_group_list()
	{

		try
		{
			$params  = get_params();
			
			$columns = array("A.created_date","A.group_hdr_id", "A.group_name", "CONCAT(C.fname,' ', C.lname) name", 'ifnull(D.group_hdr_id,0) AS is_used');
			// COLUMNS TO FILTER
			$filter = array(
					"A.group_name",
					"A.created_date",
					"CONCAT(C.fname,' ', C.lname)",
			);

			$group           = 'GROUP BY group_name';
			$where           = '';
			
			$table_groups    = $this->arm->get_group_list($columns, $filter, $params, $where, FALSE, TRUE, $group);
			$columns         = array("A.created_date", "A.group_name","CONCAT(C.fname,' ', C.lname) name","COUNT(DISTINCT(A.group_name)) as count");
			$count           = $this->arm->get_group_list($columns, $filter, $params, $where, FALSE, FALSE);
			$display_records = !EMPTY($count['count']) ? $count['count']: 0;

			$output = array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords"			=> $display_records,
				"iTotalDisplayRecords" 	=> $display_records,
				"aaData"				=> array()
			);
			$cnt = 0;

			foreach ($table_groups as $aRow):
				$cnt++;
				$row = array();
				$action = "<div class='table-actions'>";
				
				$group_hdr_id 			= $aRow["group_hdr_id"];
				$id 					= $this->hash ($group_hdr_id);
				$salt 					= gen_salt();
				$view_edit 				= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);

				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $view_edit;
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete."/table_group";

				$delete_action			= 'content_delete("MESSAGE", "'.$url_delete.'")';
				
				$row[] = $aRow['group_name'];
				$row[] = '<center>' . format_date($aRow['created_date']) . '</center>';
				$row[] = $aRow['name'];

				$action .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_table_group' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_table_group_init('".$view_action."')\"></a>";
				if(!$aRow['is_used']) {
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_table_group' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_table_group_init('".$edit_action."')\"></a>";
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($sys_param)){
					$resources['load_js'] = array(JS_MODAL_EFFECTS,JS_MODAL_CLASSIE);
					$action.= $this->load_resources->get_resource($resources, TRUE);
				}
				
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
	
	public function get_download_history_list()
	{

		try
		{
			$params 		 = get_params();

			$aColumns		 = array("*");
			$bColumns		 = array("table_name", "field_name", "start_value", "end_value");
			$table 	  		 = $this->arm->tbl_download_history_dtl;
			$where			 = array();
			$data_download 	 = $this->arm->get_download_history($aColumns, $bColumns, $params, $table, array('download_history_id' => $params['download_history_id']));
			$iTotal   		 = $this->arm->get_download_history(array("COUNT(download_history_id) as count" ), $bColumns, $params, $table, $where, false);

			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["count"],
				"iTotalDisplayRecords" => count($data_download),
				"aaData" => array()
			);
			$cnt = 0;

			foreach ($data_download as $aRow):
				$cnt++;
			
				$row                = array();
				$row[]              = '<center>' . $aRow["table_name"] . '</center>';
				$row[]              = '<center>' . $aRow["field_name"]. '</center>';
				$row[]              = '<center>' . $aRow["start_value"] . '</center>';
				$row[]              = '<center>' . $aRow["end_value"] . '</center>';
				
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

	/*------------------------------ ADHOC REPORTS GET LIST END------------------------------*/

	/*------------------------------ ADHOC REPORTS DELETE START------------------------------*/

	public function delete()
	{
		try
		{
			$params 		= get_params();

			$security_data 	= explode("/", $params['param_1']);
			$action  		= $security_data[0];
			$id  			= $security_data[1];
			$salt  			= $security_data[2];
			$token  		= $security_data[3];
			$module  		= $security_data[4];
			$flag 			= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			
			switch ($module) 
			{
				case 'table_group':
				$path           = 'get_table_group_list';
				$data_table     = 'table_group_table';
				$table          = $this->arm->tbl_table_group_hdr;
				$where          = array();
				$key            = $this->get_hash_key('group_hdr_id');
				$where[$key]    = $id;
				
				$audit_action[] = AUDIT_DELETE;
				$audit_table[]  = $table;
				$audit_schema[] = DB_MAIN;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->arm->get_adhoc_data(array("*"), $table, $where);
				
				$this->arm->delete_adhoc_data($table, $where);
				
				// DROP GROUP VIEW CREATED
				if(!EMPTY($prev_detail[0][0]['group_name'])) {
					$script = 'DROP VIEW ' . $prev_detail[0][0]['group_name'];
					$this->arm->run_query($script);
				}
				
				$msg            = $this->lang->line('data_deleted');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $prev_detail[]  = $this->arm->get_adhoc_data(array("*"), $table, $where);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity       = "%s has been deleted";
				$activity       = sprintf($activity, $prev_detail[0][0]['group_name']);
				break;
				
				case 'data_download':
				$path           = 'get_data_download_list';
				$data_table     = 'data_download_table';
				$table          = $this->arm->tbl_download_template_hdr;
				$where          = array();
				$key            = $this->get_hash_key('reference_no');
				$where[$key]    = $id;
				
				$audit_action[] = AUDIT_DELETE;
				$audit_table[]  = $table;
				$audit_schema[] = DB_MAIN;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $prev_detail[]  = $this->arm->get_adhoc_data(array("*"), $table, $where);
				
				$this->arm->delete_adhoc_data($table, $where);
				$msg            = $this->lang->line('data_deleted');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = array();
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity       = "%s has been deleted";
				$activity       = sprintf($activity, $prev_detail[0][0]['name']);
				break;
			}
			
	
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
		
		$info = array(
			"flag" 		=> $flag,
			"msg" 		=> $msg,
			"reload" 	=> 'datatable',
			"table_id" 	=> $data_table,
			"path"		=> PROJECT_MAIN . '/adhoc_reports/' . $path
		);
	
		echo json_encode($info);
	}

	public function delete_sys_param()
	{
		try
		{
			$params 		= get_params();
			$security_data 	= explode("/", $params['param_1']);
			$action  		= $security_data[0];
			$id  			= $security_data[1];
			$salt  			= $security_data[2];
			$token  		= $security_data[3];
			$flag 			= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_sys_param;
			$where				= array();
			$key 				= $this->get_hash_key('sys_param_id');
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
			$activity 			= sprintf($activity, $prev_detail[0][0]['sys_param_name']);
	
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
	
		$info = array(
			"flag" 		=> $flag,
			"msg" 		=> $msg,
			"reload" 	=> 'datatable',
			"table_id" 	=> 'sys_param_table',
			"path"		=> PROJECT_MAIN . '/code_library/get_sys_param_list/'
		);
	
		echo json_encode($info);
	}

	/*------------------------------ ADHOC REPORTS DELETE END------------------------------*/

	/*------------------------------ ADHOC MODALS START------------------------------ */

	public function modal_data_download($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources = array();
			if($action != ACTION_HISTORY && $action != ACTION_DOWNLOAD) { 
				$resources['load_css']   = array(CSS_SELECTIZE);
				$resources['load_js']    = array(JS_SELECTIZE);
			}
			//LIST OF DATA_DICTIONARY
			$field                   = array("*") ;
			$table                   = $this->arm->db_main.".".$this->arm->tbl_data_dictionary;
			$where                   = array();
			
			$data['data_dictionary'] = $this->arm->get_adhoc_data($field, $table, $where);
			$tables = array(

				'main'      => array(
				'table'     => $this->arm->tbl_table_group_hdr,
				'alias'     => 'A',
				),
				't2'        => array(
				'table'     => $this->arm->tbl_table_group_dtl,
				'alias'     => 'B',
				'type'      => 'LEFT JOIN',
				'condition' => 'A.group_hdr_id = B.group_hdr_id',
			 	)
			);
			$select_fields = array("A.group_name, A.group_hdr_id, B.table_name");
			$data['table_group_columns'] = $this->arm->get_adhoc_data($select_fields, $tables, $where);
			$table                       = $this->arm->tbl_table_group_hdr;
			$data['table_group']         = $this->arm->get_adhoc_data($field, $table, $where);
		

			$data ['action_id'] = $action;
			$data ['nav_page']	= MODULE_ADHOC_REPORTS;
			$data ['action'] 	= $action;
			$data ['salt'] 		= $salt;
			$data ['token'] 	= $token;
			$data ['id'] 		= $id;

			$data ['dropdown'] 	= $this->arm->get_adhoc_data(array("*"), $this->arm->tbl_param_dropdown);

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			if(!EMPTY($id))
			{
				//EDIT
				$table                     = $this->arm->tbl_download_template_hdr;
				$where                     = array();
				$key                       = $this->get_hash_key('reference_no');
				$where[$key]               = $id;
				$data['download_template'] = $this->arm->get_adhoc_data(array("*"), $table, $where, FALSE);
				
				$where                     = array();
				$where['reference_no']     = $data['download_template']['reference_no'];
				
				$table = array(
					'main' => array(
						'table' => $this->arm->tbl_download_template_dtl,
						'alias' => 'A'
					),
					't2' => array(
						'table' => $this->arm->tbl_param_dropdown,
						'alias' => 'B',
						'type' => 'LEFT JOIN',
						'condition' => 'A.dropdown = B.dropdown_id',
					)
				);
				$data['download_template_dtl'] = $this->arm->get_adhoc_data(array("A.*, B.dropdown_id, B.dropdown_name,B.columns AS dropdown_columns,B.table_name AS dropdown_table_name"), $table, $where);
				
				switch ($action) {
					case ACTION_DOWNLOAD:
						$view = '';
						if (!EMPTY( $data['download_template_dtl'] )) {

							$dwnld_ctr = 1;
							foreach ( $data['download_template_dtl'] as $dd ) {
								
								$transpose_chckbx = $dwnld_ctr != $dwnld_cnt ? '<input type="checkbox" class="transpose" id="transpose_'.$dwnld_ctr.'" name="transpose['.$dd['field_name'].']" value="1">' : '';
								$view .= '<tr>';
								$view .= '<td>' . $dd ['table_name'] . '<input type="hidden" name="table_name[]" value="' . $dd ['table_name'] . '"></td>';
								$view .= '<td>' . $dd ['field_name'] . '<input type="hidden" name="field_name[]" value="' . $dd ['field_name'] . '"></td>';
								$view .= '<td>' . $dd ['dropdown_name'] . '</td>';
								$view .= '<td>';
								if(!EMPTY($dd ['dropdown']))
								{
									
									$dropdown_conditions = $this->arm->get_adhoc_data(array("*"), $this->arm->tbl_param_dropdown_conditions, array("dropdown_id" => $dd ['dropdown']));
									

									$query = ' SELECT ' . $dd['dropdown_columns'] . ' FROM ' . $dd['dropdown_table_name'] . ' ';

									if($dropdown_conditions)
									{
										foreach ($dropdown_conditions as  $dc) 
										{
											if($dc['where_value'] == 'LIKE')
												$dc['where_value'] = '%'.$dc['where_value'] . '%';

											$query .= $dc['where_condition']. " "; 
											$query .= $dc['where_field'] . " ";
											$query .= $dc['where_operator'] . " "; 
											$query .= ' "' . $dc['where_value'] . '" ';
										}
									}

									$dropdown_values = $this->arm->run_query($query,NULL,TRUE);

									$view .= '<select class="browser-default left" placeholder="Start Value" name="start_value[]">';
									$view .= '<option value="">Start value</option>';
									$view .= !EMPTY($dd ['start_value']) ? '<option value="'.$dd ['start_value'].' selected">'.$dd ['start_value'].'</option>' : '';
									$dropdown_option = '';

									foreach ($dropdown_values as $key => $value) 
									{
										foreach($value as $field_key => $field_value)
										{
											$dropdown_option .= '<option value="'.$field_value.'">'.$field_value.'</option>';		
										}
										
									}
									$view .= $dropdown_option;
									$view .= '</select>';

									$end_value_view  = '<select class="browser-default left" placeholder="Start Value" name="end_value[]">';
									$end_value_view .= '<option value="">End value</option>';
									$end_value_view .= !EMPTY($dd ['end_value']) ? '<option value="'.$dd ['end_value'].' selected">'.$dd ['end_value'].'</option>' : '';
									$end_value_view .= $dropdown_option;
									$end_value_view .= '</select>';

								}
								else
								{
									$view .= '<input type="text" class="browser-default left" placeholder="Start Value" name="start_value[]" value="' . $dd ['start_value'] . '">';
									$end_value_view ='<input type="text" class="browser-default left" placeholder="End Value" name="end_value[]" value="' . $dd ['end_value'] . '">';
								}
								$view .= '</td>';
								$view .= '<td>';
								$view .= $end_value_view;
								$view .= '</td>';
								$view .= '<td align="center">'.$transpose_chckbx.'</td>';
								$view .= '</tr>';
								$dwnld_ctr++;
							}
						}
						break;
					
					case ACTION_HISTORY:

						$resources['load_modal']	= array(
								'modal_download_history'	=> array(
										'controller'	=> __CLASS__,
										'module'		=> PROJECT_MAIN,
										'method'		=> 'modal_download_history',
										'multiple'		=> true,
										'height'		=> '350px',
										'size'			=> 'md',
										'title'			=> 'Download History'
								)
						);
						$where       = array();
						$key         = $this->get_hash_key('A.reference_no');
						$where[$key] = $id;
						$tables = array(

							'main'      => array(
							'table'     => $this->arm->tbl_download_history_hdr,
							'alias'     => 'A',
							),
							't2'        => array(
							'table'     => $this->arm->db_core . '.' . $this->arm->tbl_users,
							'alias'     => 'B',
							'type'      => 'JOIN',
							'condition' => 'B.user_id = A.downloaded_by',
						 	)
						);

						$download_history = $this->arm->get_adhoc_data(array("A.*, CONCAT(B.lname, ', ', B.fname, ' ',LEFT(B.mname,1), '.') as downloaded_by"), $tables, $where);
						
						$view = '';
						if(!EMPTY($download_history)) {
							foreach($download_history AS $history) {

								$action              = "<div class='table-actions'>";
								
								$download_history_id = $history["download_history_id"];
								$id                  = $this->hash($download_history_id);
								$salt                = gen_salt();
								
								$token_view          = in_salt($id . '/' . ACTION_VIEW, 		$salt);
								
								$view_action         = ACTION_VIEW 		. "/". $id . "/" . $salt  . "/" . $token_view;	
								
								
								$action             .= "<a href='#!' class='view tooltipped md-trigger' data-modal='modal_download_history' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_download_history_init('".$view_action."')\"></a>";
								
								$action             .= "</div>";

								$view 	.= '<tr>'
										.	'<td>' . $history['remarks'] . '</td>'
										.	'<td>' . $history['downloaded_by'] . '</td>'
										.	'<td><center>' . format_date($history['end_download']) . '</center></td>'
										. 	'<td>' . $action . '</td>'
										.  '</tr>'; 
							}
						} else {
							$view 	.= '<tr>'
										.	'<td class="center" colspan=4>No history found...</td>'
										.  '</tr>'; 
						}
						break;
				}
				
				$data ['view'] = $view;
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
		
		$this->load->view('adhoc_reports/modals/modal_data_download', $data);
		$this->load_resources->get_resource($resources);
	}

	public function modal_table_group($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{

			$resources 					= array();

			$table						= $this->arm->tbl_data_dictionary;
			$data['data_dictionary'] 	= $this->arm->get_adhoc_data(array("*"), $table, array());

			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SECURITY PARAMETERS
			$data ['action'] 	= $action;
			$data ['salt'] 		= $salt;
			$data ['token'] 	= $token;
			$data ['id'] 		= $id;
			
			if(!EMPTY($id))
			{
				//EDIT
				$table             = $this->arm->tbl_table_group_hdr;
				$where             = array();
				$key               = $this->get_hash_key('group_hdr_id');
				$where[$key]       = $id;
				$table_group_info  = $this->arm->get_adhoc_data(array("*"), $table, $where, FALSE);	
				$table             = $this->arm->tbl_table_group_dtl;	
				$details           = $this->arm->get_adhoc_data(array("*"), $table, $where);
				
				foreach ($details as $key => $arr) {
					$where                      = array();
					$where['group_dtl_id']      = $arr['group_dtl_id'];
					$details[$key]['condition'] = $this->arm->get_adhoc_data(array("*"), $this->arm->tbl_table_group_condition, $where);
				}

				$table_group_info['details'] = $details;
				$data['group_hdr']           = $table_group_info;
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
		
		$this->load->view('adhoc_reports/modals/modal_table_group', $data);
		$this->load_resources->get_resource($resources);
	}


	public function modal_download_history($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data           = array();
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}


		$this->load->view('adhoc_reports/modals/modal_download_history', $data);
		$this->load_resources->get_resource($resources);
		
	}
	public function download_data($reference_no, $download_history_id, $remarks) {
		// $report = date ( 'Y-m-d' ) . '.xls';

		ob_start();
		$data = array();
		
		$where 			= array ();
		$key 			= $this->get_hash_key ( 'reference_no' );
		$where [$key] 	= $reference_no;
		$table 			= $this->arm->tbl_download_template_hdr;
		$download_hdr 	= $this->arm->get_adhoc_data( array ("*"), $table, $where, FALSE );

		$report_title	= strtoupper($download_hdr ['name']) . '_' . date('Y_m_d') . '.xls';
		
		$where 			= array ();
		$key 			= 'download_history_id';
		$where[$key] 	= $download_history_id;
		$table 			= $this->arm->tbl_download_history_dtl;
		$history_dtl 	= $this->arm->get_adhoc_data( array ("*"), $table, $where);

		$distinct_table = $this->arm->get_adhoc_data ( array ("distinct(table_name)" ), $table, $where);
		// CHECK WHAT TABLE OR VIEW TO BE USED
		// if (! EMPTY ( $download_hdr ['group_hdr_id'] )) {
		// 	$where 			= array ();
		// 	$key 			= 'group_hdr_id';
		// 	$table 			= $this->arm->tbl_table_group_hdr;
		// 	$where [$key] 	= $download_hdr ['group_hdr_id'];
		// 	$group_data 	= $this->data_download->get_data_download ( array ("*"), $table, $where, FALSE );
			
		// 	$view_table 	= $group_data ['group_name'];
		// 	$view 			= TRUE;
		// } else {
		// 	$view_table 	= $distinct_table ['table_name'];
		// 	$view 			= FALSE;
		// }
		$view_table = ' ';
		$alpha = 'A';
		for($i=0;$i<count($distinct_table);$i++)
		{
			if($i+1 == count($distinct_table))
				$view_table .= $distinct_table[$i]['table_name'] . ' ' . $alpha;
			else
				$view_table .= $distinct_table[$i]['table_name'] . ' ' . $alpha . ', ';
			$distinct_table[$i]['alias'] = $alpha++;
		}
		// $view_table = $distinct_table;
		$view       = FALSE;
		$where      = "WHERE 1 = 1";
		$fields     = array();
		$order_by   = array();
		
		if ($history_dtl) {

			$transpose_fields	= array();
			foreach ( $history_dtl as $dtl ) {
				// GET DATA TYPE OF FIELD
				if ($view)
					$field = $dtl ['table_name'] . '_' . $dtl ['field_name'];
				else {
					foreach ($distinct_table as $key => $r) {
						if($r['table_name'] == $dtl['table_name']) {
							$field = $r['alias'] . '.' . $dtl['field_name'];
						}
						$field_label = $dtl ['field_name'];
					}
				}


				if($dtl['transpose'] == "Y")
				{
					$transpose_fields[]	= $field;
					
				}

				$fields[] 	= $field;
				$field_labels[] = $field_label;

				$start = $dtl ['start_value'];
				$end   = $dtl ['end_value'];

				if(!EMPTY($start))
					$order_by[] = $field;
				
			
				if (! EMPTY ( $start ) and ! EMPTY ( $end )) {
					$where .= " AND " . $field . " BETWEEN '" . $start . "' AND '" . $end . "'";


				} else {
					if (! EMPTY ( $start ))
						$where .= " AND " . $field . " BETWEEN '" . $start . "' AND '" . $start . "'";
					elseif (! EMPTY ( $end ))
						$where .= " AND " . $field . " BETWEEN '" . $end . "' AND '" . $end . "'" ;
				}


			}

			// for($i=1;$i<count($history_dtl);$i++) {

			// 	$table                 = 'information_scheme.columns';
			// 	$where                 = array();
			// 	$where['table_schema'] = DB_MAIN;
			// 	$where['table_name']   = $history_dtl[$i-1]['table_name'];
			// 	$where['column_name']  = $history_dtl[$i]['field_name'];
			// 	$result                = $this->arm->get_adhoc_data(array('column_name'),$table,$where);
				
			// 	$same_field            = !EMPTY($result) ? TRUE : FALSE;


			// }


		}
		$order_by		= implode(', ', $order_by);

		$data['info'] 	= $this->arm->get_download_details( $fields, $view_table, $where, TRUE, $order_by );

		$data['fields'] = $field_labels;

		IF(!EMPTY($transpose_fields))
		{

			$trans_info 		= array();
			$trans_field_arr	= array();
			$unique_val_arr		= array();
			IF (! EMPTY ( $info )) {
				
				$ctr = 0;
				
				FOREACH ( $info as $info) {


					$trans_fields 		= "";
					$start_trans_field	= "";
					$last_trans_field	= "";
					$next_trans_field	= "";
					$unique_val			= "";

					FOREACH ( $fields as $field )
					{

					
						if(in_array($field, $transpose_fields))
						{

							IF(EMPTY($start_trans_field))
								$start_trans_field	= $field;

							$trans_fields 		= $trans_fields . '<BR><BR>' . $info[$field];
							$last_trans_field 	= $field;
						}
						else
						{
							if(!EMPTY($last_trans_field))
							{
								$next_trans_field	= $field;
								break;
							}
						}

						IF(EMPTY($start_trans_field))
							$unique_val.= $info[$field];
					}


					$key = array_search($unique_val, $unique_val_arr);


					IF($key === FALSE)		
					{
						$unique_val_arr[$ctr] = $unique_val;
					}									
					ELSE
						$ctr = $key;


					// CHECK IF THERE IS EXISTING DATA OF NON TRANSPOSED FIELDS

					FOREACH ( $fields as $field )
					{

					

						if(in_array($field, $transpose_fields))
						{
							IF($last_trans_field == $field)
							{

								
								$strip_fields = strip_tags($trans_fields, '<BR>');
								$strip_fields = trim(preg_replace('/\s+/', ' ', $strip_fields));

								IF(!in_array($strip_fields, $trans_field_arr))
										$trans_field_arr[]					= $strip_fields;

								$trans_info[$ctr][$strip_fields] 	= $info[$next_trans_field];
								
								
							}
						}
						ELSE
						{

							IF($field != $next_trans_field)
							{
								$trans_field_arr[$field]	= $field;
								$trans_info[$ctr][$field] 	= $info[$field];	
							}
							
						}
					}

					$ctr++;
					
				}
			}

			$fields = $trans_field_arr;
			$info 	= $trans_info;


		}



		$row = '<tr>';
		FOREACH ( $fields as $field ) {
			$row .= '<td>' . $field . '</td>';
		}
		$row .= '</tr>';	

		IF (! EMPTY ( $info )) {
		
			FOREACH ( $info as $info ) {
				$row .= '<tr>';
				FOREACH ( $fields as $field ) {

					
						$x = (!EMPTY($info [$field])) ? strip_tags($info [$field]) : "";
					$row .= '<td>' .$x. '</td>';
				}
				$row .= '</tr>';
			}
		} else {
			$col = count ( $fields );
			$row .= '<tr><td colspan="' . $col . '">' . $this->lang->line ( 'no_records_found' ) . '</td></tr>';
		}

		
		$result = '<table border="1" width="100%">';
		$result .= $row;
		$result .= '</table><BR><BR>';



		$where = array ();
		$key = 'download_history_id';
		$where [$key] = $download_history_id;
		$table = $this->arm->tbl_download_history_hdr;
	

		$history_fields = array ();
		$history_fields ['end_download'] = date ( 'Y-m-d H:i:s' );

		$this->arm->update_adhoc_data( $table, $history_fields, $where );
		$data['remarks'] = $remarks;
		$this->load->view('adhoc_reports/modals/modal_data_download_excel', $data);
			
		$echo = ob_get_contents();
		ob_end_clean();
			
		header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=" . $report_title . ".xls");  //File name extension was wrong
		//header("Content-Disposition: attachment; filename=Properties_for_Sale_.xls");  //File name extension was wrong
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		
		echo $echo;
	}

	private function _get_view_field($table_name)
	{
		try
		{
			$table_name		= $table_name;
			$table			= $this->arm->tbl_data_dictionary;
			$where			= array();
			$key			= 'table_name';
			$where[$key]	= $table_name;
			$fields			= $this->arm->get_adhoc_data(array("column_name"),$table,$where);
			return $fields;
		}
		catch (PDOException $e)
		{
			throw $e;
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}

		private function _validate_table_group($params)
	{
		try
		{
			$fields					= array();	
			$fields['group_name'] 	= 'Group name';
			//$fields['columns'] 		= 'Column fields';
			$fields['main_table'] 	= 'Primary table';

			$this->check_required_fields($params, $fields);
			return $this->_validate_input($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}

	/*------------------------------ ADHOC MODALS END------------------------------ */
}

/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */