<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_voluntary_work_info extends Main_Controller {

	private $log_user_id       =  '';
	private $log_user_roles    = array();
	
	private $permission_view   = FALSE;
	private $permission_edit   = FALSE;
	private $permission_delete = FALSE;
	
	private $permission_module = MODULE_; // TBD 

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_model', 'pds');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}	

	public function get_pds_voluntary_work_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
	{
		try
		{
			$data 	= array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			
			if($module == MODULE_PERSONNEL_PORTAL)
			{
				$controller = "pds_record_changes_requests";
			}
			else
			{
				$controller = __CLASS__;
			}
			
			$resources['load_css'][] 				= CSS_DATATABLE;
			$resources['load_js'][] 				= JS_DATATABLE;
			$resources['load_modal']   				= array(
							'modal_voluntary_work'  => array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_voluntary_work',
									'multiple'		=> true,
									'height'		=> '360px',
									'size'			=> 'sm',
									'title'			=> 'Voluntary Work Information'
							)
					);
					$resources['load_delete'] 	= array(
								$controller,
								'delete_voluntary_work',
								PROJECT_MAIN
							);
					$resources['datatable'][]	= array('table_id' => 'pds_voluntary_work_table', 'path' => 'main/pds_voluntary_work_info/get_voluntary_wok_list', 'advanced_filter' => true);
					$data['nav_page']			= PDS_VOLUNTARY_WORK;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/voluntary_work', $data);
		$this->load_resources->get_resource($resources);		
	}
	
	public function get_voluntary_wok_list($action_id = NULL)
	{
		try
		{
			$params 	= get_params();
			
			$aColumns 	= array("employee_voluntary_work_id","employee_id","volunteer_org_name", "DATE_FORMAT(volunteer_start_date, '%Y/%m/%d') AS volunteer_start_date", "DATE_FORMAT(volunteer_end_date, '%Y/%m/%d') AS volunteer_end_date", "volunteer_hour_count", "volunteer_position");
			$bColumns 	= array("volunteer_org_name", "DATE_FORMAT(volunteer_start_date, '%Y/%m/%d')", "DATE_FORMAT(volunteer_end_date, '%Y/%m/%d')", "volunteer_hour_count", "volunteer_position");

			$voluntary_work 	= $this->pds->get_voluntary_work_list($aColumns, $bColumns, $params);
			$iTotal				= $this->pds->voluntary_work_total_length();
			$iFilteredTotal 	= $this->pds->voluntary_work_filtered_length($aColumns, $bColumns, $params);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			$module 	= $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");

			$cnt = 0;			
			foreach ($voluntary_work as $aRow):
				$cnt++;
				$row 			= array();
				$action 		= "";				

				$id 			= $this->hash($aRow['employee_voluntary_work_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

				$row[] = strtoupper($aRow['volunteer_org_name']);
				$row[] = '<center>' . $aRow['volunteer_start_date'] . '</center>';
				$row[] = '<center>' . $aRow['volunteer_end_date'] . '</center>';
				$row[] = '<p class="m-n right">' . $aRow['volunteer_hour_count'] . '</p>';
				$row[] = strtoupper($aRow['volunteer_position']);

				$action = "<div class='table-actions'>";
				// if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_voluntary_work' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_voluntary_work_init('".$url_view."')\"></a>";

				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_voluntary_work' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_voluntary_work_init('".$url_edit."')\"></a>";
					
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}

				$action .= "</div>";
				if($cnt == count($voluntary_work)){
					$action.= "<script src='". base_url() . PATH_JS."modalEffects.js' type='text/javascript'></script>";
					$action.= "<script src='". base_url() . PATH_JS."classie.js' type='text/javascript'></script>";
					$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				}
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;			
		}
		catch (Exception $e)
		{
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}

		echo json_encode( $output );
	}

	public function modal_voluntary_work($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();
			$resources 				= array();

			$resources['load_css']	= array(CSS_DATETIMEPICKER);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_NUMBER);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field 						= array("*");
				$table						= $this->pds->tbl_employee_voluntary_works;
				$where						= array();
				$key 						= $this->get_hash_key('employee_voluntary_work_id');
				$where[$key]				= $id;
				$data['voluntary'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);				
			}

			$this->load->view('pds/modals/modal_voluntary_work', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (PDOException $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}
	}	

	/*PROCESS VOLUNTARY WORK*/
	public function process()
	{
		try
		{			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params			= get_params();
			$action			= $params['action'];
			$token			= $params['token'];
			$salt			= $params['salt'];
			$id				= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_voluntary_work($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id				 = $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field                           = array("*") ;
			$table                           = $this->pds->tbl_employee_personal_info;
			$where                           = array();
			$key                             = $this->get_hash_key('employee_id');
			$where[$key]                     = $pds_employee_id;
			$personal_info                   = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                          = array() ;
			$fields['volunteer_org_name']    = $valid_data["volunteer_org_name"];
			$fields['volunteer_org_address'] = $valid_data["volunteer_org_address"];
			$fields['volunteer_start_date']  = $valid_data["volunteer_start_date"];
			$fields['volunteer_end_date']    = !EMPTY($valid_data["volunteer_end_date"]) ? $valid_data["volunteer_end_date"] : NULL;
			$fields['volunteer_hour_count']  = $valid_data["volunteer_hour_count"];
			$fields['volunteer_position']    = $valid_data["volunteer_position"];

			// SET FIELDS TO ADIT TRAIL
			$audit_fields                          = array() ;
			$audit_fields['volunteer_org_name']    = $valid_data["volunteer_org_name"];
			$audit_fields['volunteer_org_address'] = $valid_data["volunteer_org_address"];
			$audit_fields['volunteer_start_date']  = $valid_data["volunteer_start_date"];
			$audit_fields['volunteer_end_date']    = $valid_data["volunteer_end_date"];
			$audit_fields['volunteer_hour_count']  = $valid_data["volunteer_hour_count"];
			$audit_fields['volunteer_position']    = $valid_data["volunteer_position"];

			if (!empty($valid_data["volunteer_end_date"]))
			{
				if($valid_data['volunteer_end_date'] < $valid_data['volunteer_start_date'])
				{
					throw new Exception('<b>Date Ended</b> should not be earlier than <b>Date Started</b>.');
				}
			}			
			
			if($action == ACTION_ADD)
			{	

				$fields['employee_id']			= $personal_info["employee_id"];
				$table 							= $this->pds->tbl_employee_voluntary_works;
				$employee_voluntary_work_id		= $this->pds->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->pds->tbl_employee_voluntary_works;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($audit_fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "New voluntary work with the position %s has been added.";
				$audit_activity 		= sprintf($activity, $valid_data["volunteer_position"]);

				$status  = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_voluntary_works;
				$where						= array();
				$key 						= $this->get_hash_key('employee_voluntary_work_id');
				$where[$key]				= $id;
				$voluntary_work				= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where						= array();
				$key 						= $this->get_hash_key('employee_voluntary_work_id');
				$where[$key]				= $id;
				$table 						= $this->pds->tbl_employee_voluntary_works;
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->pds->tbl_employee_voluntary_works;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($voluntary_work);
				$curr_detail[]			= array($audit_fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$activity 				= "Voluntary work with the position %s has been updated.";
				$audit_activity 		= sprintf($activity, $voluntary_work["volunteer_position"]);
				
				$status = true;
				$message = $this->lang->line('data_updated');
			}
			
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			
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
	private function _validate_voluntary_work($params)
	{
		try
		{	$fields                          = array();			
			$fields['volunteer_org_name']    = "Organization Name";
			$fields['volunteer_org_address'] = "Organization Address";
			$fields['volunteer_start_date']  = "Date Started";
			$fields['volunteer_hour_count']  = "Number of Hours";
			$fields['volunteer_position']    = "Position/Nature of Work";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_voluntary_work($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_voluntary_work($params)
	{
		try
		{
			$validation['volunteer_org_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Organization Name',
					'max_len'	=> 100
			);
			$validation['volunteer_org_address'] = array(
					'data_type' => 'string',
					'name'		=> 'Organization Address',
					'max_len'	=> 300
			);
			$validation['volunteer_start_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['volunteer_end_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended'
			);
			$validation['volunteer_hour_count'] = array(
					'data_type' => 'amount',
					'name'		=> 'Number of Hours',
					'decimal'	=> 2,
					'max'		=> 999999
			);
			$validation['volunteer_position'] = array(
					'data_type' => 'string',
					'name'		=> 'Position/Nature of Work',
					'max_len'	=> 255
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_voluntary_work()
	{
		try
		{
			$flag 			= 0;
			$params			= get_params();
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
			$prev_data      = array() ;
			/*GET PREVIOUS DATA*/
			$field          = array("*") ;
			$table          = $this->pds->tbl_employee_voluntary_works;
			$where          = array();
			$key            = $this->get_hash_key('employee_voluntary_work_id');
			$where[$key]    = $id;
			$voluntary_work = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			//DELETE DATA
			$where          = array();
			$key            = $this->get_hash_key('employee_voluntary_work_id');
			$where[$key]    = $id;
			$table          = $this->pds->tbl_employee_voluntary_works;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($voluntary_work['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]	= $this->pds->tbl_employee_voluntary_works;
			$audit_schema[]	= DB_MAIN;
			$prev_detail[] 	= array($voluntary_work);
			$curr_detail[]	= array();
			$audit_action[] = AUDIT_DELETE;
			$activity 		= "Voluntary work with the position %s has been deleted.";
			$audit_activity = sprintf($activity, $voluntary_work["volunteer_position"]);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 			= $this->lang->line('data_deleted');
			$flag 			= 1;
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
			"table_id" 				=> 'pds_voluntary_work_table',
			"path"					=> PROJECT_MAIN . '/pds_voluntary_work_info/get_voluntary_wok_list/',
			"advanced_filter" 		=> true
			);

		echo json_encode($response);
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */