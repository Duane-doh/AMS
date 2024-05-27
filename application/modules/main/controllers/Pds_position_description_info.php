<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_position_description_info extends Main_Controller {

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

	public function get_pds_position_description_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
				$data['action']			= ACTION_VIEW;
			}
			$resources['load_css'][] 	= CSS_DATETIMEPICKER;
			$resources['load_js'][] 	= JS_DATETIMEPICKER;
			$resources['load_js'][] 	= JS_EDITOR;
			$resources['load_css'][] 	= CSS_LABELAUTY;
			$resources['load_js'][] 	= JS_LABELAUTY;
			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_position_description;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $id;
			$data['description'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);

			if(EMPTY($data['description']))
			{
				$field                = array("*") ;
				$table                = $this->pds->tbl_employee_work_experiences;
				$where                = array();
				$key                  = $this->get_hash_key('employee_id');
				$where[$key]          = $id;
				$where['active_flag'] = YES;
				$work_xp              = $this->pds->get_general_data($field, $table, $where, FALSE);

				$data['description']['position_designation']    = $work_xp['employ_position_name'];
				$data['description']['proposed_title']          = $work_xp['employ_position_name'];
				$data['description']['position_classification'] = $work_xp['employ_position_name'];
			}
			if($data['description'])
			{
				$data['contacts']          = explode(',', $data['description']['contacts']);
				$data['working_condition'] = explode(',', $data['description']['working_condition']);

			}
			$data['nav_page']			= PDS_DECLARATION;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}
		$this->load_resources->get_resource($resources);
		$this->load->view('pds/tabs/position_description', $data);
		
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
			$valid_data = $this->_validate_description($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_position_description;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $pds_employee_id;
			$description 					= $this->pds->get_general_data($field, $table, $where, FALSE);

			$contacts          = array();
			$working_condition = array();
			if($params["general_public"])
				$contacts[]        = $params["general_public"];
			if($params["other_agency"])
				$contacts[]        = $params["other_agency"];
			if($params["supervisors"])
				$contacts[]        = $params["supervisors"];
			if($params["management"])
				$contacts[]        = $params["management"];
			if($params["contact_others"])
				$contacts[]        = $params["contact_others"];

			if($params['working_condition'])
				$working_condition = $params['working_condition'];

			$fields                            = array() ;
			$fields['position_designation']    = $valid_data["position_designation"];
			$fields['proposed_title']          = $valid_data["proposed_title"];
			$fields['position_classification'] = $valid_data["position_classification"];
			$fields['immediate_position']      = $valid_data["immediate_position"];
			$fields['next_higher_position']    = $valid_data["next_higher_position"];
			$fields['directly_supervised']     = $valid_data["directly_supervised"];
			$fields['work_tools_used']         = $valid_data["work_tools_used"];
			$fields['contacts']                = implode(',', $contacts);
			$fields['working_condition']       = implode(',', $working_condition);
			$fields['unit_general_function']   = $params["unit_general_function"];

			// SET FIELDS TO ADIT TRAIL
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			if(EMPTY($description))
			{	
				$fields['employee_id']			= $personal_info["employee_id"];

				$table 							= $this->pds->tbl_employee_position_description;
				$this->pds->insert_general_data($table,$fields,FALSE);

				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	
			}
			else
			{
				$where						= array();
				$key 						= $this->get_hash_key('employee_id');
				$where[$key]				= $id;
				$table 						= $this->pds->tbl_employee_position_description;
				$this->pds->update_general_data($table,$fields,$where);

				$prev_detail[] 			= array($declaration);
				$curr_detail[]			= array($audit_fields);
				$audit_action[] 		= AUDIT_UPDATE;	
			}
			$status = true;
			$message = $this->lang->line('data_updated');

			$audit_table[]			= $this->pds->tbl_employee_declaration;
			$audit_schema[]			= DB_MAIN;
			$activity 				= "Employee position description %s has been updated.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"].' '.$personal_info["last_name"]);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
		}
		catch(PDOException $e){
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
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
	private function _validate_description($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_description($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_description($params)
	{
		try
		{
			$validation['position_designation'] = array(
					'data_type' => 'string',
					'name'		=> '8. OFFICIAL, DESIGNATION OF POSITION',
					'max_len'	=> 255
			);
			$validation['proposed_title'] = array(
					'data_type' => 'string',
					'name'		=> '9. WORKING OR PROPOSED TITLE',
					'max_len'	=> 255
			);
			$validation['position_classification'] = array(
					'data_type' => 'string',
					'name'		=> '10. OCPC CLASSIFICATION OF POSITION',
					'max_len'	=> 255
			);
			$validation['immediate_position'] = array(
					'data_type' => 'string',
					'name'		=> '14. POSITION TITLE OF IMMEDIATE SUPERVISOR',
					'max_len'	=> 255
			);
			$validation['next_higher_position'] = array(
					'data_type' => 'string',
					'name'		=> '15. POSITION TITLE OF NEXT HIGHER SUPERVISOR',
					'max_len'	=> 255
			);
			$validation['directly_supervised'] = array(
					'data_type' => 'string',
					'name'		=> '16. NAMES, TITLES AND ITEM NUMBERS OF THOSE WHO YOU DIRECTLY SUPERVISE',
			);
			$validation['work_tools_used'] = array(
					'data_type' => 'string',
					'name'		=> '17. MACHINES, EQUIPMENTS, TOOLS, ETC. USED REGULARLY IN PERFORMANCE OF WORK.',
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