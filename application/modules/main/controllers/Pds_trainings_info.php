<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_trainings_info extends Main_Controller {

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

	public function get_pds_trainings_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			$resources['load_modal']    	= array(
					'modal_trainings'  		=> array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_trainings',
							'multiple'		=> true,
							'height'		=> '400px',
							'size'			=> 'sm',
							'title'			=> 'Training'
					)
			);
			$resources['load_delete'] 	= array(
						$controller,
						'delete_trainings',
						PROJECT_MAIN
					);
			$resources['datatable'][]	= array('table_id' => 'pds_trainings_table', 'path' => 'main/pds_trainings_info/get_trainings_list', 'advanced_filter' => true);
			$data['nav_page']			= PDS_TRAININGS;
		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/trainings', $data);
		$this->load_resources->get_resource($resources);
		
	}

	public function get_trainings_list()
	{
		try
		{
			$params 	= get_params();
			$params['iSortCol_0'] = '1';
			if($params['sSortDir_0'] == 'asc')
			{
				$params['sSortDir_0'] = "desc";
			}else
			{
				$params['sSortDir_0'] = "asc";
			}
			$aColumns 	= array("A.employee_training_id","A.employee_id","A.training_name", "DATE_FORMAT(A.training_start_date,'%Y/%m/%d') as training_start_date", "DATE_FORMAT(A.training_end_date,'%Y/%m/%d') as training_end_date", "A.training_hour_count", "A.training_type", "A.training_conducted_by", "A.relevance_flag");
			$bColumns	= array("A.training_name", "DATE_FORMAT(A.training_start_date,'%Y/%m/%d')", "DATE_FORMAT(A.training_end_date,'%Y/%m/%d')", "A.training_hour_count", "A.training_conducted_by", "A.training_type", "A.relevance_flag");
			$trainings 			= $this->pds->get_trainings_list($aColumns, $bColumns, $params);
			$iTotal				= $this->pds->trainings_total_length();
			$iFilteredTotal 	= $this->pds->trainings_filtered_length($aColumns, $bColumns, $params);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			$module 	= $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");
			/*
			$permission_view = $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			*/
			$cnt = 0;			
			foreach ($trainings as $aRow):
				$cnt++;
				$row 			= array();
				$action 		= "";				

				$id 			= $this->hash($aRow['employee_training_id']);
				$salt			= gen_salt();
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

				if($aRow['relevance_flag'] == "Y")
				{
					$relevance = "checked";
					$relevance_label = "Relevant";
				}
				else
				{
					$relevance = "";
					$relevance_label = "Not Relevant";
				}

				$row[] = strtoupper($aRow['training_name']);
				$row[] = '<center>' . $aRow['training_start_date'] . '</center>';
				$row[] = '<center>' . $aRow['training_end_date'] . '</center>';
				$row[] = '<p class="m-n right">' . $aRow['training_hour_count'] . '</p>';
				$row[] = strtoupper($aRow['training_conducted_by']);
				$row[] = strtoupper($aRow['training_type']);

				$relevance   = "<div class='switch responsive-tablet'><center>
						<label>
						    <input type='checkbox' class='filled-in' name='relevance".$cnt."' id='relevance".$cnt."' value='Y' onclick=\"update_relevance('".$aRow['employee_training_id']."')\" ".(($aRow['relevance_flag'] == 'Y') ? "checked" : "")." ".($pds_action == ACTION_VIEW ? 'disabled' : '').">
						    <span class='lever'></span><br><br>
							".(($aRow['relevance_flag'] == 'Y') ? "Relevant" : "Not Relevant")."
						</label></center>
					</div>";

				$action = "<div class='table-actions'>";

				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_trainings' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_trainings_init('".$url_edit."')\"></a>";
					
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($trainings)){
					$action.= "<script src='". base_url() . PATH_JS."modalEffects.js' type='text/javascript'></script>";
					$action.= "<script src='". base_url() . PATH_JS."classie.js' type='text/javascript'></script>";
					$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				}	

				if($module == MODULE_PERSONNEL_PORTAL) 
					{
						$row[] = $action;
					}
				else{
						$row[] = $relevance;
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

	public function modal_trainings($action, $id, $token, $salt, $module)
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
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_trainings;
				$where						= array();
				$key 						= $this->get_hash_key('employee_training_id');
				$where[$key]				= $id;
				$data['trainings'] 			= $this->pds->get_general_data($field, $table, $where, FALSE);				
			}

			$this->load->view('pds/modals/modal_trainings', $data);
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

	/*PROCESS TRAININGS*/
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
			$valid_data = $this->_validate_trainings($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id			= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields                          = array() ;
			$fields['training_name']         = $valid_data["training_title"];
			$fields['training_start_date']   = $valid_data["training_start_date"];
			$fields['training_end_date']     = $valid_data["training_end_date"];
			$fields['training_hour_count']   = $valid_data["training_hour_count"];
			$fields['training_conducted_by'] = $valid_data["training_conducted_by"];
			$fields['training_type'] 		 = $valid_data["training_type"];
			$fields['relevance_flag']		 = isset($valid_data['relevance_flag']) ? "Y" : "N";

			if($valid_data['training_end_date'] < $valid_data['training_start_date'])
			{
				throw new Exception('<b>Date Ended</b> should not be earlier than <b>Date Started</b>.');
			}

			if($action == ACTION_ADD)
			{	

				$fields['employee_id']  = $personal_info["employee_id"];				
				$table                  = $this->pds->tbl_employee_trainings;
				$employee_training_id   = $this->pds->insert_general_data($table,$fields,TRUE);

				$audit_table[]  = $this->pds->tbl_employee_trainings;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	
				
				$activity       = "New employee training with the title %s has been added.";
				$audit_activity = sprintf($activity, $valid_data["training_title"]);

				$status 		= true;
				$message 		= $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 			= array("*") ;
				$table			= $this->pds->tbl_employee_trainings;
				$where			= array();
				$key 			= $this->get_hash_key('employee_training_id');
				$where[$key]	= $id;
				$trainings 		= $this->pds->get_general_data($field, $table, $where, FALSE);				

				$where			= array();
				$key 			= $this->get_hash_key('employee_training_id');
				$where[$key]	= $id;
				$table 			= $this->pds->tbl_employee_trainings;

				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]	= $this->pds->tbl_employee_trainings;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[] 	= array($trainings);
				$curr_detail[]	= array($fields);
				$audit_action[] = AUDIT_UPDATE;	
					
				$activity 		= "Employee training with the title %s has been updated.";
				$audit_activity = sprintf($activity, $trainings["training_title"]);
				
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

	private function _validate_trainings($params)
	{
		try
		{
			$fields 						= array();
			$fields['training_title']		= "Training Title";
			$fields['training_start_date']	= "Date Started";
			$fields['training_end_date']	= "Date Ended";
			$fields['training_hour_count']	= "Number of Hours";
			$fields['training_conducted_by']= "Conducted/Sponsored By";
			$fields['training_type'] 		= "Training Type";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_trainings($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_trainings($params)
	{
		try
		{
			$validation['training_title'] = array(
					'data_type' => 'string',
					'name'		=> 'Training Title',
					'max_len'	=> 250
			);
			$validation['training_start_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['training_end_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended'
			);
			$validation['training_hour_count'] = array(
					'data_type' => 'amount',
					'name'		=> 'Number of Hours',
					'decimal'	=> 2,
					'max'		=> 9999
			);
			$validation['training_conducted_by'] = array(
					'data_type' => 'string',
					'name'		=> 'Conducted/Sponsored By',
					'max_len'	=> 100
			);
			$validation['relevance_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Relevance Flag',
					'max_len' 			=> 1 
			);
			$validation['training_type'] = array(
					'data_type' => 'string',
					'name'		=> 'Training Type',
					'max_len'	=> 100
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_trainings()
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
			$prev_data		= array() ;
			/*GET PREVIOUS DATA*/
			$field 			= array("*") ;
			$table			= $this->pds->tbl_employee_trainings;
			$where			= array();
			$key 			= $this->get_hash_key('employee_training_id');
			$where[$key]	= $id;
			$trainings 		= $this->pds->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where			= array();
			$key 			= $this->get_hash_key('employee_training_id');
			$where[$key]	= $id;
			$table 			= $this->pds->tbl_employee_trainings;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($trainings['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]	= $this->pds->tbl_employee_trainings;
			$audit_schema[]	= DB_MAIN;
			$prev_detail[] 	= array($trainings);
			$curr_detail[]	= array();
			$audit_action[] = AUDIT_DELETE;
			$audit_activity = "Employee's training has been deleted.";
			
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
			"table_id" 				=> 'pds_trainings_table',
			"path"					=> PROJECT_MAIN . '/pds_trainings_info/get_trainings_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	public function update_relevance() 
	{
		try 
		{
			

			$params 				= get_params();
			$field                  = array("relevance_flag");
			$table                  = $this->pds->tbl_employee_trainings;
			$where                  = array();
			$where['employee_training_id']            = $params['employee_training_id'];
			$emp_training        = $this->pds->get_general_data($field, $table, $where, FALSE);
			if($emp_training['relevance_flag'] == "Y")
			{
				$fields['relevance_flag']      	   = 'N';	
			}
			else
			{
				$fields['relevance_flag']      	   = 'Y';	
			}

					
			
			$this->pds->update_general_data($table,$fields,$where);

			$flag 	= 1;
			$msg  	= $this->lang->line('data_processed');

		} 
		catch (Exception $e) {
			$msg 	=  $e->getMessage();
		}

		$info 		 = array(
			"flag"   => $flag,
			"msg" 	 => $msg
		);

		echo json_encode($info);
	}
}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */