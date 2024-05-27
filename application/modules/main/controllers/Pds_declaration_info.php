<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_declaration_info extends Main_Controller {

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
	
	

	public function get_pds_declaration_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			
			$resources['load_css'][] 	= CSS_DATETIMEPICKER;
			$resources['load_js'][] 	= JS_DATETIMEPICKER;
			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_declaration;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $id;
			$data['declaration'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$data['nav_page']			= PDS_DECLARATION;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/declaration', $data);
		$this->load_resources->get_resource($resources);
		
	}

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
			$valid_data = $this->_validate_declaration($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_declaration;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $pds_employee_id;
			$declaration 					= $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields 						= array() ;
			$fields['ctc_no']				= ! empty($valid_data["ctc_no"]) ? $valid_data['ctc_no'] : NULL;
			$fields['issued_place']			= ! empty($valid_data["issued_place"]) ? $valid_data['issued_place'] : NULL;
			$fields['issued_date']			= ! empty($valid_data["issued_date"]) ? $valid_data['issued_date'] : NULL;
			$fields['govt_issued_id']		= ! empty($valid_data["govt_issued_id"]) ? $valid_data['govt_issued_id'] : NULL;		

			if(EMPTY($declaration))
			{	
				/*GET EMPLOYEE*/
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_personal_info;
				$where						= array();
				$key 						= $this->get_hash_key('employee_id');
				$where[$key]				= $pds_employee_id;
				$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

				$fields['employee_id']			= $personal_info["employee_id"];

				$table 							= $this->pds->tbl_employee_declaration;
				$employee_training_id 			= $this->pds->insert_general_data($table,$fields,TRUE);


				$audit_table[]			= $this->pds->tbl_employee_declaration;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "Employee declaration %s has been added.";
				$audit_activity 		= sprintf($activity, $valid_data["ctc_no"]);

				$status = true;
				$message = $this->lang->line('data_saved');


			}
			else
			{

				$where						= array();
				$key 						= $this->get_hash_key('employee_id');
				$where[$key]				= $id;
				$table 						= $this->pds->tbl_employee_declaration;
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->pds->tbl_employee_declaration;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($declaration);
				$curr_detail[]			= array($audit_fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$activity 				= "Employee declaration %s has been updated.";
				$audit_activity 		= sprintf($activity, $declaration["ctc_no"]);


				
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
	private function _validate_declaration($params)
	{
		try
		{
						
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();

			$fields['govt_issued_id']	= "Government Issued ID";
			$fields['ctc_no']			= "ID/License/Passport No.";
			$fields['issued_place']		= "Place Issued";
			$fields['issued_date']		= "Date Issued";
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_declaration($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_declaration($params)
	{
		try
		{
			$validation['ctc_no'] = array(
					'data_type' => 'string',
					'name'		=> 'Government Issued ID',
					'max_len'	=> 45
			);
			$validation['issued_place'] = array(
					'data_type' => 'string',
					'name'		=> 'Place Issued',
					'max_len'	=> 225
			);
			$validation['issued_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Issued'
			);
			$validation['govt_issued_id'] = array(
					'data_type' => 'string',
					'name'		=> 'ID/License/Passport No.',
					'max_len'	=> 45
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */