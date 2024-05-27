<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_profession_info extends Main_Controller {

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

	public function get_pds_profession_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			$resources['load_modal']    			= array(
							'modal_profession'  	=> array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_profession',
									'multiple'		=> true,
									'height'		=> '270px',
									'size'			=> 'sm',
									'title'			=> 'Profession'
							)
					);
					$resources['load_delete'] 	= array(
								$controller,
								'delete_profession',
								PROJECT_MAIN
							);
					$resources['datatable'][]	= array('table_id' => 'profession_table', 'path' => 'main/pds_profession_info/get_profession_list', 'advanced_filter' => true);

					$data['nav_page']			= PDS_PROFESSION;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/profession', $data);
		$this->load_resources->get_resource($resources);
		
	}
	
	public function get_profession_list()
	{
		try
		{
			$params         = get_params();
			
			$aColumns       = array("*");
			$bColumns       = array("profession_name");
			
			$professions    = $this->pds->get_profession_list($aColumns, $bColumns, $params);
			$iTotal         = $this->pds->profession_total_length();
			$iFilteredTotal = $this->pds->profession_filtered_length($aColumns, $bColumns, $params);
			
			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

			$module     = $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");
			
			$cnt = 0;
			foreach ($professions as $aRow):
				$cnt++;
				$row          = array();
				$action       = "";
				
				$id           = $this->hash($aRow['employee_profession_id']);
				$salt         = gen_salt();
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_edit     = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete   = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;
				
				$row[]        = strtoupper($aRow['profession_name']);
				
				$action       = "<div class='table-actions'>";

				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action        .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_profession' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_profession_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action        .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";

				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;			
		}
		catch (Exception $e)
		{
			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}
		echo json_encode( $output );

	}

	public function modal_profession($action, $id, $token, $salt, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE);
			
			$data['action']        = $action;
			$data['id']            = $id;
			$data['salt']          = $salt;
			$data['token']         = $token;
			$data['module']        = $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			// PROFESSION OTHER ID
			$result					= $this->pds->get_professions_other_id();
			$other_id 				= $result['profession_id'];
			
			//USED IN VIEW			
			$data['other_id']       = $other_id;
			
			//GET PROFESSION	
			$data['profession_type'] 	= $this->pds->get_professions($other_id);
			
			if($action != ACTION_ADD)
			{
				$data['profession_type'] = $this->pds->get_edit_professions($other_id, $id);

				/*GET PREVIOUS DATA*/
				$field                  = array("*") ;
				$table                  = $this->pds->tbl_employee_professions;
				$where                  = array();
				$key                    = $this->get_hash_key('employee_profession_id');
				$where[$key]            = $id;
				$profession         	= $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['profession'] 	= $profession;
			
				$resources['single']	= array(
					'profession_id' 	=> $profession['profession_id']
				);
			}

			$this->load->view('pds/modals/modal_profession', $data);
			$this->load_resources->get_resource($resources);
			
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			RLog::error($message);
		}	
	}

	/*PROCESS PROFESSION*/
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
			$valid_data = $this->_validate_profession($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id		  = $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field                    = array("*") ;
			$table                    = $this->pds->tbl_employee_personal_info;
			$where                    = array();
			$key                      = $this->get_hash_key('employee_id');
			$where[$key]              = $pds_employee_id;
			$personal_info            = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                   = array();
			$fields['profession_id']  = $valid_data["profession_id"];
			$fields['others_specify'] = $valid_data["others_specify"];

			if($action == ACTION_ADD)
			{	
				$fields['employee_id']  = $personal_info["employee_id"];
				
				$table                  = $this->pds->tbl_employee_professions;
				$employee_profession_id = $this->pds->insert_general_data($table,$fields,TRUE);
								
				$audit_table[]          = $this->pds->tbl_param_professions;
				$audit_schema[]         = DB_MAIN;
				$prev_detail[]          = array();
				$curr_detail[]          = array($fields);
				$audit_action[]         = AUDIT_INSERT;	
				
				$activity               = "%s has been added.";
				$audit_activity         = sprintf($activity, "New ID for".$personal_info["first_name"] . " ".$personal_info["last_name"]);

				$status = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field          = array("*") ;
				$table          = $this->pds->tbl_employee_professions;
				$where          = array();
				$key            = $this->get_hash_key('employee_profession_id');
				$where[$key]    = $id;
				$identification = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where          = array();
				$key            = $this->get_hash_key('employee_profession_id');
				$where[$key]    = $id;
				$table          = $this->pds->tbl_employee_professions;
				$this->pds->update_general_data($table,$fields,$where);
				
				$audit_table[]  = $this->pds->tbl_employee_professions;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($professions);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				$activity       = "%s has been Updated.";
				$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]."'s ID");
				
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
	private function _validate_profession($params)
	{
		try
		{
			$fields  				= array();
			$fields['profession_id']= "Profession Name";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_profession($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_input_profession($params)
	{
		try
		{			
			$validation['profession_id'] = array(
					'data_type' => 'string',
					'name'		=> 'Profession Name',
					'max_len'	=> 50
			);
			$validation['others_specify'] = array(
					'data_type' => 'string',
					'name'		=> 'Others',
					'max_len'	=> 100
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_profession()
	{
		try
		{
			$flag        = 0;
			$params      = get_params();
			$url         = $params['param_1'];
			$url_explode = explode('/',$url);
			$action      = $url_explode[0];
			$id          = $url_explode[1];
			$token       = $url_explode[2];
			$salt        = $url_explode[3];
			$module      = $url_explode[4];

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
			$prev_data		= array();
			/*GET PREVIOUS DATA*/
			$field 			= array("*");
			$table			= $this->pds->tbl_employee_professions;
			$where			= array();
			$key 			= $this->get_hash_key('employee_profession_id');
			$where[$key]	= $id;
			$profession 	= $this->pds->get_general_data($field, $table, $where, FALSE);
			
			//DELETE DATA
			$where			= array();
			$key 			= $this->get_hash_key('employee_profession_id');
			$where[$key]	= $id;
			$table 			= $this->pds->tbl_employee_professions;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($profession['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]	= $this->pds->tbl_employee_professions;
			$audit_schema[]	= DB_MAIN;
			$prev_detail[] 	= array($profession);
			$curr_detail[]	= array();
			$audit_action[] = AUDIT_DELETE;
			$activity 		= "ID with ID number %s has been deleted.";
			$audit_activity = sprintf($activity, $prev_data['profession_name']);
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
			"table_id" 				=> 'profession_table',
			"path"					=> PROJECT_MAIN . '/pds_profession_info/get_profession_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}
}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */