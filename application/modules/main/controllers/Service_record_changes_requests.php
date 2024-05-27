<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_record_changes_requests extends Main_Controller {
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('service_record_model', 'service_record');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	public function insert_sub_request($type, $id,$action)
	{
		try
		{
			$fields                           = array() ;
			$fields['request_sub_type_id']    = $type;
			$fields['employee_id']            = $id;
			$fields['requests_sub_status_id'] = SUB_REQUEST_NEW;
			$fields['action']                 = $action;
			
			$table                            = $this->service_record->tbl_requests_sub;
			$sub_request_id                   = $this->service_record->insert_general_data($table,$fields,TRUE);

			return $sub_request_id;
		}
		catch(PDOException $e){
			return $message = $e->getMessage();
			RLog::error($message);
			//$message = $this->lang->line('data_not_saved');
		}
		catch(Exception $e)
		{
			return $message = $e->getMessage();
		}
	}
		
	/*PROCESS SERVICE RECORD*/
	public function process_employee_service_record()
	{
		try
		{
			
			$status      = FALSE;
			$message     = "";
			$reload_url  = "";
			
			$params      = get_params();
			$action      = $params['action'];
			$token       = $params['token'];
			$salt        = $params['salt'];
			$id          = $params['id'];
			$module      = $params['module'];
			$employee_id = $params['employee_id'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_service_record($params);
			
			Main_Model::beginTransaction();

			if($action != ACTION_ADD)
			{
				$table    = $this->service_record->tbl_requests_employee_relations;
				$req_info = $this->service_record->check_pds_record($table, "A.employee_relation_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			
			$field                   = array("employee_id, first_name, last_name") ;
			$table                   = $this->service_record->tbl_employee_personal_info;
			$where                   = array();
			$key                     = $this->get_hash_key('employee_id');
			$where[$key]             = $employee_id;
			$employee_service_record = $this->service_record->get_general_data($field, $table, $where, FALSE);


			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_SERVICE_RECORD,$employee_service_record["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/
			$fields                                  = array() ;
			$fields['request_sub_id']                = $sub_request_id;
			//$fields['action']                      = $action;
			$fields['employee_id']                   = $employee_service_record["employee_id"];

			$fields['service_start']  = $valid_data["service_start"];
			$fields['service_end']    = $valid_data["service_end"];
			$fields['position']       = $valid_data["position_name"];
			$fields['service_status'] = $valid_data["employment_type_name"];
			$fields['annual_salary']  = $valid_data["annual_salary"];
			$fields['station']        = $valid_data["office_name"];
			$fields['service_branch'] = $valid_data["branch_name"];
			$fields['service_lwop']   = $valid_data["leave_type_name"];
			$fields['service_date']   = $valid_data["service_date"];
			$fields['end_cause']      = $valid_data["end_cause"];
			$fields['created_by']     = $this->log_user_id;
			$fields['created_date']   = date("Y-m-d H:i:s");
			if($action != ACTION_ADD)
			{
			/*GET PREVIOUS DATA*/
				$field                          = array("*") ;
				$table                          = $this->service_record->tbl_employee_relations;
				$where                          = array();
				$key                            = $this->get_hash_key('employee_relation_id');
				$where[$key]                    = $id;
				$relation                       = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_relation_id'] = $relation['employee_relation_id'];
			}

			$table = $this->service_record->tbl_requests_employee_relations;
			$this->service_record->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->service_record->tbl_requests_employee_relations;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Service record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $employee_service_record["first_name"] . " ".$employee_service_record["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status  = true;
			$message = $this->lang->line('save_record_changes');
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
	
		$data            = array();
		$data['status']  = $status;
		$data['message'] = $message;
	
		echo json_encode($data);
	}

	private function _validate_service_record($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();

			$fields['relation_last_name']	= "Last Name";
			$fields['relation_first_name']	= "First Name";
			$fields['relation_middle_name']	= "Middle Name";
			$fields['relation_type']		= "Relationship";
			$fields['relation_birth_date']	= "Birth Date";
			$fields['employment_status']	= "Employment Status";
			$fields['gender']				= "Gender";
			$fields['civil_status']			= "Civil Status";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_service_record($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_input_service_record($params)
	{
		try
		{
			
			$validation['relation_last_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Last Name',
					'max_len'	=> 100
			);
			$validation['relation_first_name'] = array(
					'data_type' => 'string',
					'name'		=> 'First Name',
					'max_len'	=> 100
			);
			$validation['relation_middle_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Middle Name',
					'max_len'	=> 100
			);
			$validation['relation_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Relationship',
					'max_len'	=> 11
			);	
			$validation['relation_birth_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Birth Date',
					'max_date'	=> date("Y/m/d")
			);
			$validation['relation_occupation'] = array(
					'data_type' => 'string',
					'name'		=> 'Occupation',
					'max_len'	=> 50
			);
			$validation['relation_contact_num'] = array(
					'data_type' => 'string',
					'name'		=> 'Contact Number',
					'max_len'	=> 50
			);
			$validation['relation_company'] = array(
					'data_type' => 'string',
					'name'		=> 'Company Name',
					'max_len'	=> 100
			);
			$validation['relation_company_address'] = array(
					'data_type' => 'string',
					'name'		=> 'Company Address',
					'max_len'	=> 300
			);
			$validation['employment_status'] = array(
					'data_type' => 'digit',
					'name'		=> 'Employment Status',
					'max_len'	=> 11
			);
			$validation['ext_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Extension Name',
					'max_len'	=> 45
			);
			$validation['gender'] = array(
					'data_type' => 'string',
					'name'		=> 'Gender',
					'max_len'	=> 1
			);
			$validation['civil_status'] = array(
					'data_type' => 'digit',
					'name'		=> 'Civil Status',
					'max_len'	=> 11
			);
			$validation['disable_flag'] = array(
					'data_type' => 'string',
					'name'		=> 'Disable Tagging',
					'max_len'	=> 1
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_service_record()
	{
		try
		{
			$flag = 0;
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->service_record->tbl_requests_employee_relations;
			$req_info			= $this->service_record->check_pds_record($table, "A.employee_relation_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->service_record->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->service_record->get_general_data($field, $table, $where, FALSE);


			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_SERVICE_RECORD,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/
			$field       = array("*") ;
			$table       = $this->service_record->tbl_employee_relations;
			$where       = array();
			$key         = $this->get_hash_key('employee_relation_id');
			$where[$key] = $id;
			$relations   = $this->service_record->get_general_data($field, $table, $where, FALSE);

			$fields 							= array() ;
			$fields['request_sub_id']			= $sub_request_id;
			//$fields['action']					= $action;
			$fields['employee_id']				= $personal_info["employee_id"];
			$fields['employee_relation_id']		= $relations['employee_relation_id'];
			$fields['relation_last_name']		= $relations["relation_last_name"];
			$fields['relation_first_name']		= $relations["relation_first_name"];
			$fields['relation_middle_name']		= $relations["relation_middle_name"];
			$fields['relation_type']			= $relations["relation_type"];
			$fields['relation_birth_date']		= $relations["relation_birth_date"];
			$fields['relation_occupation']		= $relations["relation_occupation"];
			$fields['relation_contact_num']		= $relations["relation_contact_num"];
			$fields['relation_company']			= $relations["relation_company"];
			$fields['relation_company_address']	= $relations["relation_company_address"];
			$fields['created_by']				= $this->log_user_id;
			$fields['created_date']				= date("Y-m-d H:i:s");
			$table 							= $this->service_record->tbl_requests_employee_relations;
			$this->service_record->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]				= $this->service_record->tbl_requests_employee_relations;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array();
			$curr_detail[]				= array($fields);
			$audit_action[] 			= AUDIT_INSERT;
			$activity 					= "Family record changes request of %s has been added.";
			$audit_activity 			= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 					= $this->lang->line('save_record_changes');
			$flag 					= 1;
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
			"table_id" 				=> 'table_employee_service_record',
			"path"					=> PROJECT_MAIN . '/service_record/get_employee_service_record	/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS OF SUBMITTING REPORT*/
	public function process_pds_request()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";
			$process_id 	= 1;
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
			
			Main_Model::beginTransaction();
				$record_changes				= $this->service_record->count_pending_requests($id,NULL);

				
				if($record_changes['record_count'] < 1)
				{
					throw new Exception("No PDS record changes found.");
				}

			
				$field 						= array("*") ;
				$table						= $this->service_record->tbl_employee_personal_info;
				$where						= array();
				$key 						= $this->get_hash_key('employee_id');
				$where[$key]				= $id;
				$personal_info 				= $this->service_record->get_general_data($field, $table, $where, FALSE);


				$fields 						= array();
				$fields['employee_id']			= $personal_info["employee_id"];
				$fields['request_type_id']		= REQUEST_RECORD_CHANGES;
				$fields['process_id']			= $process_id;
				$fields['request_status_id']	= REQUEST_NEW;
				$fields['date_requested']		= date("Y-m-d H:i:s");
				$fields['created_by']			= $this->log_user_id;
				$fields['created_date']			= date("Y-m-d H:i:s");

				$table 							= $this->service_record->tbl_requests;
				$request_id						= $this->service_record->insert_general_data($table,$fields,TRUE);

				/*############################ END : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/

				/*############################ START : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/
				$quotient = 100 / $request_id;
				$addedZeroes = "";
				if ($quotient > 10) {
					$addedZeroes = "00";
				}
				elseif ($quotient > 0) {
					$addedZeroes = "0";
				}
				else {
					$addedZeroes = "";
				}
				
			
				$fields 				= array() ;
				$fields['request_code']	= date("Ym").$addedZeroes.$request_id;
				$where					= array();
				$where['request_id']	= $request_id;

				$table 					= $this->service_record->tbl_requests;
				$this->service_record->update_general_data($table,$fields,$where);

				
				$this->service_record->update_requests_sub($request_id,$id);

				$workflow 		= $this->service_record->get_initial_task($process_id);
							

				$fields 					= array() ;
				$fields['request_id']		= $request_id;
				$fields['task_detail']		= $workflow['name'];
				$fields['process_id']		= $workflow['process_id'];
				$fields['process_stage_id']	= $workflow['process_stage_id'];
				$fields['process_step_id']	= $workflow['process_step_id'];
				$fields['task_status_id']	= 1;

				$table 						= $this->service_record->tbl_requests_tasks;
				$this->service_record->insert_general_data($table,$fields,FALSE);

			
			Main_Model::commit();
			$status = true;
			$message = "Record changes was successfully submitted for approval.<br><br><b class='red-text'>Note:</b> You are not allowed to edit your Personal Data Sheet until this request be completely processed.";
			
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

	public function delete_pds_changes()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$params			= get_params();
			$type			= $params['type'];
			$id				= $params['id'];
			
			if(EMPTY($type) OR EMPTY($id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			Main_Model::beginTransaction();
			switch ($type){
				case TYPE_REQUEST_PDS_PERSONAL_INFO:
					$request_table = $this->service_record->tbl_requests_employee_personal_info;
				break;
				case TYPE_REQUEST_PDS_IDENTIFICATION:
					$request_table = $this->service_record->tbl_requests_employee_identification_info;
				break;
				case TYPE_REQUEST_PDS_ADDRESS_INFO:
					$request_table = $this->service_record->tbl_requests_employee_address_info;
				break;
				case TYPE_REQUEST_PDS_CONTACT_INFO:
					$request_table = $this->service_record->tbl_requests_employee_contact_info;
				break;
				case TYPE_REQUEST_SERVICE_RECORD:
					$request_table = $this->service_record->tbl_requests_employee_relations;
				break;
				case TYPE_REQUEST_PDS_EDUCATION:
					$request_table = $this->service_record->tbl_requests_employee_education_info;
				break;
				case TYPE_REQUEST_PDS_ELIGIBILITY:
					$request_table = $this->service_record->tbl_requests_employee_eligibility;
				break;
				case TYPE_REQUEST_PDS_WORK_EXPERIENCE:
					$request_table = $this->service_record->tbl_requests_employee_work_experiences;
				break;
				case TYPE_REQUEST_PDS_VOLUNTARY_WORK:
					$request_table = $this->service_record->tbl_requests_employee_voluntary_works;
				break;
				case TYPE_REQUEST_PDS_TRAININGS:
					$request_table = $this->service_record->tbl_requests_employee_trainings;
				break;
				case TYPE_REQUEST_PDS_OTHER_INFO:
					$request_table = $this->service_record->tbl_requests_employee_other_info;
				break;
				case TYPE_REQUEST_PDS_QUESTION:
					$request_table = $this->service_record->tbl_requests_employee_questions;
				break;
				case TYPE_REQUEST_PDS_REFERENCES:
					$request_table = $this->service_record->tbl_requests_employee_references;
				break;
				case TYPE_REQUEST_PDS_DECLARATION:
					$request_table = $this->service_record->tbl_requests_employee_declaration;
				break;
				default:
					throw new Exception($this->lang->line('invalid_action'));
				break;
			}
			$field 							= array("*") ;
			$table							= $this->service_record->tbl_requests_sub;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $id;
			$where['request_sub_type_id'] 	= $type;
			$where['request_id'] 			= 'IS NULL';
			$sub_request 					= $this->service_record->get_general_data($field, $table, $where, TRUE);
			
			if($sub_request)
			{
				foreach($sub_request as $request)
				{
					$request['request_sub_id'];
					$where = array();
					$where['request_sub_id'] = $request['request_sub_id'];
					$this->service_record->delete_general_data($request_table,$where);

					$table							= $this->service_record->tbl_requests_sub;
					$this->service_record->delete_general_data($table,$where);
				}
			}
			
			Main_Model::commit();
			$status = true;
			$message = "Record was successfully removed from record changes.";
			
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
}
/* End of file Pds_record_changes_requests.php */
/* Location: ./application/modules/main/controllers/Pds_record_changes_requests.php */