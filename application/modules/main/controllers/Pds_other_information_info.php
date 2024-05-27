<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_other_information_info extends Main_Controller {

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
	
	public function get_pds_other_information_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			
			$resources['load_css'][] 			= CSS_DATATABLE;
			$resources['load_js'][] 			= JS_DATATABLE;
			$resources['load_modal']   			= array(
					'modal_other_information'  	=> array(
							'controller'		=> __CLASS__,
							'module'			=> PROJECT_MAIN,
							'method'			=> 'modal_other_information',
							'multiple'			=> true,
							'height'			=> '270px',
							'size'				=> 'sm',
							'title'				=> 'Other Information'
					)
			);
			$resources['load_delete'] 	= array(
						$controller,
						'delete_other_info',
						PROJECT_MAIN
					);

			$employee_id 				= $id;
			$resources['datatable'][]	= array('table_id' => 'pds_other_info_table', 'path' => 'main/pds_other_information_info/get_other_info_list/'.$employee_id, 'advanced_filter' => true);
			
			$data['nav_page']			= PDS_OTHER_INFORMATION;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/other_info', $data);
		$this->load_resources->get_resource($resources);		
	}

	public function get_other_info_list($employee_id)
	{
		try
		{
			$params 	= get_params();
			
			$aColumns 	= array("A.employee_other_info_id","A.employee_id","A.others_value", "B.other_info_type_name");
			$bColumns 	= array("A.others_value", "B.other_info_type_name");

			$other_info 	 	= $this->pds->get_other_info_list($aColumns, $bColumns, $params);
			$iTotal				= $this->pds->other_info_total_length();
			$iFilteredTotal 	= $this->pds->other_info_filtered_length($aColumns, $bColumns, $params);
			
			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module     = $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");
			/*
			$permission_view = $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			*/
			$cnt = 0;
			foreach ($other_info as $aRow):
				$cnt++;
				$row 			= array();
				$action 		= "";
				
				$id 			= $this->hash($aRow['employee_other_info_id']);
				$salt			= gen_salt();
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;

				$row[] = strtoupper($aRow['others_value']);
				$row[] = strtoupper($aRow['other_info_type_name']);

				$action = "<div class='table-actions'>";

				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_other_information' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_other_information_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($other_info)){
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
			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}

		echo json_encode( $output );
	}

	public function modal_other_information($action, $id, $token, $salt, $module, $employee_id = NULL)
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
			$data['employee_id']	= $employee_id;

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
				$data['other_info'] 		= $this->pds->get_previous_other_info($id);		

				$employee_id 				= $this->hash($data['other_info']['employee_id']);
				$data['employee_id']		= $employee_id;		 	
			
				$resources['single']		= array(
					'other_info_type_id' 	=> $data['other_info']['other_info_type_id']
				);		
			}

			$field 							= array("*") ;
			$table							= $this->pds->tbl_param_other_info_types;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['other_info_type_id']= array($data['other_info']['other_info_type_id'], array("=", ")"));	
		 	}
			$data['info_types'] 			= $this->pds->get_general_data($field, $table, $where, TRUE);

			$this->load->view('pds/modals/modal_other_information', $data);
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

	/*PROCESS OTHER INFO*/
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

			// $info_type_id 	= explode('-',$params['other_info_type_id']);
			// $type_id 		= $info_type_id[0];
			// $info_flag 		= $info_type_id[1];

			// $params['other_info_type_id'] 	= $type_id;
			// if($info_flag == 'N')
			// $params['others_value']			= NOT_APPLICABLE;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_other_info($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields 						= array() ;
			$fields['other_info_type_id']	= $valid_data["other_info_type_id"];
			$fields['others_value']			= strtoupper($valid_data["others_value"]);

			if($action == ACTION_ADD)
			{	
				$fields['employee_id']			= $personal_info["employee_id"];

				$table 							= $this->pds->tbl_employee_other_info;
				$employee_other_info_id			= $this->pds->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->pds->tbl_employee_other_info;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "New PDS other information %s has been added.";
				$audit_activity 		= sprintf($activity, '');

				$status = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field          = array("*") ;
				$table          = $this->pds->tbl_employee_other_info;
				$where          = array();
				$key            = $this->get_hash_key('employee_other_info_id');
				$where[$key]    = $id;
				$other_info     = $this->pds->get_general_data($field, $table, $where, FALSE);				
				
				$where          = array();
				$key            = $this->get_hash_key('employee_other_info_id');
				$where[$key]    = $id;
				$table          = $this->pds->tbl_employee_other_info;
				
				$this->pds->update_general_data($table,$fields,$where);
				
				$audit_table[]  = $this->pds->tbl_employee_other_info;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($other_info);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				$activity       = "PDS other information %s has been updated.";
				$audit_activity = sprintf($activity, '');

				$status 		= true;
				$message 		= $this->lang->line('data_updated');
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
	private function _validate_other_info($params)
	{
		try
		{						
			$fields 						= array();
			$fields['other_info_type_id']	= "Information Type";
			$fields['others_value']			= "Information Details";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_other_info($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_other_info($params)
	{
		try
		{
			$validation['other_info_type_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Information Type'
			);		
			$validation['others_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Information Details',
					'max_len'	=> 225
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_other_info()
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
			$prev_data		= array();
			/*GET PREVIOUS DATA*/
			$field 			= array("*");
			$table			= $this->pds->tbl_employee_other_info;
			$where			= array();
			$key 			= $this->get_hash_key('employee_other_info_id');
			$where[$key]	= $id;
			$other_info 	= $this->pds->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where			= array();
			$key 			= $this->get_hash_key('employee_other_info_id');
			$where[$key]	= $id;
			$table 			= $this->pds->tbl_employee_other_info;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($other_info['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]	= $this->pds->tbl_employee_other_info;
			$audit_schema[]	= DB_MAIN;
			$prev_detail[] 	= array($other_info);
			$curr_detail[]	= array();
			$audit_action[] = AUDIT_DELETE;
			$activity 		= "PDS other information %s has been deleted.";
			$audit_activity = sprintf($activity, '');

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
			"table_id" 				=> 'pds_other_info_table',
			"path"					=> PROJECT_MAIN . '/pds_other_information_info/get_other_info_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	public function get_other_info_flag() 
	{
		try 
		{			
			$flag    = 0;
			$msg     = ERROR;
			$params  = get_params();			
			
			$id      = $params['select_id'];		
			
			$data    = $this->pds->get_other_info_flag($id);
						
			$flag 	 = 1;
			$msg  	 = SUCCESS;

		} 
		catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info 		= $data;

		echo json_encode($info);
	}

	public function get_employee_eligibility() 
	{
		try 
		{			
			$flag    = 0;
			$msg     = ERROR;
			$params  = get_params();	
			$emp_id  = $params['id'];		
			
			$data    = $this->pds->get_employee_eligibility($emp_id);
						
			$flag 	 = 1;
			$msg  	 = SUCCESS;

		} 
		catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info 		= $data;

		echo json_encode($info);
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */