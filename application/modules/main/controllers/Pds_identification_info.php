<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_identification_info extends Main_Controller {

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
	
	public function get_pds_identification_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			
			$resources['load_css'][] 		= CSS_DATATABLE;
			$resources['load_js'][] 		= JS_DATATABLE;
			$resources['load_modal']   		= array(
					'modal_identification'  => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_identification',
							'multiple'		=> true,
							'height'		=> '300px',
							'size'			=> 'sm',
							'title'			=> 'Identification'
					)
			);
			
			$resources['load_delete'] 		= array(
						$controller,
						'delete_identification',
						PROJECT_MAIN
					);
			$resources['datatable'][]		= array('table_id' => 'identification_table', 'path' => 'main/pds_identification_info/get_identification_list', 'advanced_filter' => true);

			$data['nav_page']				= PDS_IDENTIFICATION;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/identification', $data);
		$this->load_resources->get_resource($resources);
		
	}

	public function get_identification_list()
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.employee_identification_id","A.employee_id","B.identification_type_name", "A.identification_value", "B.format");
			$bColumns       = array("B.identification_type_name", "A.identification_value");
			
			$identification = $this->pds->get_identification_list($aColumns, $bColumns, $params);
			$iTotal         = $this->pds->identification_total_length();
			$iFilteredTotal = $this->pds->identification_filtered_length($aColumns, $bColumns, $params);
			
			$output 					= array(
				"sEcho"                	=> intval($_POST['sEcho']),
				"iTotalRecords"        	=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData"               	=> array()
			);

			$module     = $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");
			
			$cnt        = 0;
			foreach ($identification as $aRow):
				$cnt++;
				$row          = array();
				$action       = "";
				
				$id           = $this->hash($aRow['employee_identification_id']);
				$salt         = gen_salt();
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_edit     = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete   = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;
				
				$row[]        = strtoupper($aRow['identification_type_name']);
				$row[]        = strtoupper(format_identifications($aRow['identification_value'],$aRow['format']));
				
				$action       = "<div class='table-actions'>";

				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action        .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_identification' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_identification_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("ID", "'.$url_delete.'")';
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
			$output 					= array(
				"sEcho"               	=> intval($_POST['sEcho']),
				"iTotalRecords"       	=> 0,
				"iTotalDisplayRecords"	=> 0,
				"aaData"              	=> array()
			);
		}

		echo json_encode( $output );
	}

	public function modal_identification($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			
			$resources['load_css']	= array(CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_SELECTIZE);

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
				$field              = array("*");
				$table 				= array(
					'main' 			=> array(
						'table' 	=> $this->pds->tbl_employee_identifications,
						'alias' 	=> 'A'
					),
					't1' 			=> array(
						'table'		=> $this->pds->tbl_param_identification_types,
						'alias'		=> 'B',
						'type'      => 'JOIN',
						'condition' => 'A.identification_type_id = B.identification_type_id'
					)
				);
				$where                  = array();
				$key                    = $this->get_hash_key('A.employee_identification_id');
				$where[$key]            = $id;
				$identification         = $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['identification'] = $identification;
			
				$resources['single']		 = array(
					'identification_type_id' => $identification['identification_type_id'] . '|' . $identification['format']
				);
			}

			//GET IDENTIFICATION TYPES
			$field 							= array("*") ;
			$table							= $this->pds->tbl_param_identification_types;
			$where							= array();
			$where['builtin_flag']			= NO;
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 			 = array(YES, array("=", "OR", "("));
		 		$where['identification_type_id'] = array($identification['identification_type_id'], array("=", ")"));				
			}			
			$data['identification_types'] 	= $this->pds->get_general_data($field, $table, $where);

			$this->load->view('pds/modals/modal_identification', $data);
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

	/*PROCESS IDENTIFICATION*/
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

			// GETTING THE REAL VALUE OF AN IDENTIFICATION FROM THE VALUE WITH FORMATTING
			$value_arr                        = explode('|', $params['identification_type_id']);
			$concat                           = explode('x', $value_arr[1]);
			$separator                        = $concat[count($concat)-1];
			$params['identification_value']   = str_replace($separator, '', $params['identification_value']);
			$params['identification_type_id'] = $value_arr[0];

			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_identification($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 				= array("*") ;
			$table				= $this->pds->tbl_employee_personal_info;
			$where				= array();
			$key 				= $this->get_hash_key('employee_id');
			$where[$key]		= $pds_employee_id;
			$personal_info 		= $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields 							= array() ;
			$fields['identification_type_id']	= $valid_data["identification_type_id"];
			$fields['identification_value']		= $valid_data["identification_value"];

			if($action == ACTION_ADD)
			{	
				$fields['employee_id']			= $personal_info["employee_id"];

				$table 							= $this->pds->tbl_employee_identifications;
				$employee_identification_id		= $this->pds->insert_general_data($table,$fields,TRUE);


				$audit_table[]			= $this->pds->tbl_employee_identifications;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "%s has been added.";
				$audit_activity 		= sprintf($activity, "New ID for".$personal_info["first_name"] . " ".$personal_info["last_name"]);

				$status = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_identifications;
				$where						= array();
				$key 						= $this->get_hash_key('employee_identification_id');
				$where[$key]				= $id;
				$identification 			= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->pds->tbl_employee_identifications;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($identification);
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$activity 				= "%s has been Updated.";
				$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]."'s ID");
				
				$status  = true;
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

	private function _validate_identification($params)
	{
		try
		{
			$fields 							= array();
			$fields['identification_type_id']	= "Identification Type";
			$fields['identification_value']		= "Identification Number";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_identification($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_input_identification($params)
	{
		try
		{			
			$validation['identification_value'] 	= array(
					'data_type' 					=> 'string',
					'name'							=> 'Identification Number',
					'max_len'						=> 50
			);
			$validation['identification_type_id'] 	= array(
					'data_type' 					=> 'digit',
					'name'							=> 'Identification Type'
			);	
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function delete_identification()
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
			$prev_data					= array() ;
			/*GET PREVIOUS DATA*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_identifications;
			$where						= array();
			$key 						= $this->get_hash_key('employee_identification_id');
			$where[$key]				= $id;
			$identification 			= $this->pds->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where						= array();
			$key 						= $this->get_hash_key('employee_identification_id');
			$where[$key]				= $id;
			$table 						= $this->pds->tbl_employee_identifications;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($identification['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]				= $this->pds->tbl_employee_identifications;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($identification);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$activity 					= "ID with ID number %s has been deleted.";
			$audit_activity 			= sprintf($activity, $prev_data['identification_value']);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 						= $this->lang->line('data_deleted');
			$flag 						= 1;
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
			"table_id" 				=> 'identification_table',
			"path"					=> PROJECT_MAIN . '/pds_identification_info/get_identification_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}
	
}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */