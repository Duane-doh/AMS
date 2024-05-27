<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_questionnaire_info extends Main_Controller {

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
	
	

	public function get_pds_questionnaire_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			
			$resources['load_css'][] 	= CSS_LABELAUTY;
			$resources['load_js'][] 	= JS_LABELAUTY;
			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_param_questions;
			$where							= array();
			$where['parent_question_id']	= "IS NULL";
			$data['parent_questions'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);

			$field 							= array("*") ;
			$table							= $this->pds->tbl_param_questions;
			$where							= array();
			$where['parent_question_flag']	= "N";
			$data['child_questions'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);
			/*GET EMPLOYEE PREVIOUS ANSWERS*/
			$field 				= array("*") ;
			$table				= $this->pds->tbl_employee_questions;
			$where				= array();
			$key 				= $this->get_hash_key('employee_id');
			$where[$key]		= $id;
			$data['answers'] 	= $this->pds->get_general_data($field, $table, $where, TRUE);

			$data['nav_page']			= PDS_QUESTIONNAIRE;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/questionnaire', $data);
		$this->load_resources->get_resource($resources);
		
	}

	/*PROCESS QUQESTIONS*/
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
			// $valid_data = $this->_validate_question($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			//GET PERVEIOUS RECORD
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_personal_info;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $id;
			$personal_info 					= $this->pds->get_general_data($field, $table, $where, FALSE);

			$field 							= array("*") ;
			$table							= $this->pds->tbl_param_questions;
			$where							= array();
			$where['parent_question_flag']	= "N";
			$questions 						= $this->pds->get_general_data($field, $table, $where, TRUE);

			//DELETE OLD DATA
			$where					= array();
			$key 					= $this->get_hash_key('employee_id');
			$where[$key]			= $id;
			$table 					= $this->pds->tbl_employee_questions;
			
			$this->pds->delete_general_data($table,$where);

			$fields	= array();
			$where	= array();
			$row_cnt = 0;
			if($questions)
			{
				foreach($questions as $question)
				{
					$row_cnt ++;
					$new_id = $question["question_id"];
					$detail = $params['detail_'.$new_id];
					$choice = $params['request_type_'.$new_id];

					if($choice == "Y")
					{
						$fields[]					= array(
							'employee_id'			=> $personal_info["employee_id"],
							'question_id'			=> $new_id,
							'question_answer_flag'	=> $choice,
							'question_answer_txt'	=> $detail
						);
					}
					else if($choice == "N")
					{
						$fields[]					= array(
							'employee_id'			=> $personal_info["employee_id"],
							'question_id'			=> $new_id,
							'question_answer_flag'	=> $choice,
							'question_answer_txt'	=> NULL
						);
					}
					else
					{
						throw new Exception('Required to answer the question in <b>[ROW - ' . $row_cnt . ']</b>');
					}
					
				}
				$table	= $this->pds->tbl_employee_questions;
				$this->pds->insert_general_data($table,$fields,FALSE);
			}
			
			$audit_table[]			= $this->pds->tbl_employee_questions;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Employee questionnaire %s has been updated.";
			$audit_activity 		= sprintf($activity, "");

			$status = true;
			$message = $this->lang->line('data_saved');

			$this->pds->update_pds_date_accomplished($pds_employee_id);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			//$message = json_encode($declaration);
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
}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */