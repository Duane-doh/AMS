<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_education_info extends Main_Controller {

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
	
	

	public function get_pds_education_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
					$resources['load_js'][] 		= JS_DATATABLE;
					$resources['load_modal']    	= array(
							'modal_education' 		=> array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_education',
									'multiple'		=> true,
									'height'		=> '390px',
									'size'			=> 'sm',
									'title'			=> SUB_MENU_EDUCATION
							)
					);
					$resources['load_delete'] 	= array(
								$controller,
								'delete_education',
								PROJECT_MAIN
							);
					$resources['datatable'][]	= array('table_id' => 'pds_education_table', 'path' => 'main/pds_education_info/get_education_list', 'advanced_filter' => true);
					$data['nav_page']			= PDS_EDUCATION;


		}
		catch(Exception $e)
		{
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/education', $data);
		$this->load_resources->get_resource($resources);
		
	}


	public function get_education_list()
	{

		try
		{
			$params = get_params();
			
			$aColumns 	= array("A.employee_education_id","A.employee_id","B.educ_level_name", "C.school_name", "D.degree_name", "A.end_year", "A.relevance_flag");
			$bColumns 	= array("B.educ_level_name", "C.school_name", "D.degree_name", "A.end_year", "A.relevance_flag");
			
			$education 		= $this->pds->get_education_list($aColumns, $bColumns, $params);
			$iTotal			= $this->pds->education_total_length();
			$iFilteredTotal = $this->pds->education_filtered_length($aColumns, $bColumns, $params);
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			$module 	= $this->session->userdata("pds_module");
			$pds_action 	= $this->session->userdata("pds_action");
			/*
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			*/
			$cnt = 0;
			foreach ($education as $aRow):
				$cnt++;
				$row = array();
				$action = "";
				

				$id 			= $this->hash($aRow['employee_education_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
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

				$row[] = $aRow['educ_level_name'];
				
				// $row[] = $aRow['school_name'];
				$row[] = strtoupper($aRow['school_name']); //jendaigo : change school_name format
				
				$row[] = $aRow['degree_name'];
				$row[] = '<center>' . $aRow['end_year'] . '</center>';
				$relevance   = "<div class='switch responsive-tablet'><center>
						<label>
						    <input type='checkbox' class='filled-in' name='relevance".$cnt."' id='relevance".$cnt."' value='Y' onclick=\"update_relevance('".$aRow['employee_education_id']."')\" ".(($aRow['relevance_flag'] == 'Y') ? "checked" : "")." ".($pds_action == ACTION_VIEW ? 'disabled' : '').">
						    <span class='lever'></span><br><br>
							".(($aRow['relevance_flag'] == 'Y') ? "Relevant" : "Not Relevant")."
						</label></center>
					</div>";

							
				
				// $row[] = ($module == MODULE_PERSONNEL_PORTAL) ? $action : $relevance;
				
				$action = "<div class='table-actions'>";
				
				// if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_education' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_education_init('".$url_view."')\"></a>";
				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_education' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_education_init('".$url_edit."')\"></a>";
					
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($education)){
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
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}

		echo json_encode( $output );
	}

	public function modal_education($action, $id, $token, $salt, $module)
	{
		try
		{
			$data = array();
			$resources = array();

			$resources['load_css']	= array(CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_SELECTIZE, JS_LABELAUTY, JS_NUMBER);

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
				$table						= $this->pds->tbl_employee_educations;
				$where						= array();
				$key 						= $this->get_hash_key('employee_education_id');
				$where[$key]				= $id;
				$education 					= $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['education'] 			= $education;
			
				$resources['single']	= array(
											'level'                => $education['educational_level_id'],
											'school_name'          => $education['school_id'],
											'degree_course'        => $education['education_degree_id'],
											);
			}
			//GET CITIZENSHIPS
			$field 						 	= array("*");
			$table						 	= $this->pds->tbl_param_educational_levels;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['educ_level_id'] 	= array($education['educational_level_id'], array("=", ")"));				
			}	
			$data['education_level'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);
			
			//GET CITIZENSHIPS
			$field 							= array("*");
			$table							= $this->pds->tbl_param_schools;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['school_id'] 		= array($education['school_id'], array("=", ")"));	
		 	}
			$data['schools'] 				= $this->pds->get_general_data($field, $table, $where, TRUE);
			//GET CITIZENSHIPS
			$field 							   = array("*");
			$table							   = $this->pds->tbl_param_education_degrees;
			$where							   = array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		   = YES;			
			}
			else
			{
				$where['active_flag'] 		   = array(YES, array("=", "OR", "("));
		 		$where['degree_id']  		   = array($education['education_degree_id'], array("=", ")"));	
		 	}
			$data['degrees'] 				   = $this->pds->get_general_data($field, $table, $where, TRUE);

			$this->load->view('pds/modals/modal_education', $data);
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

	/*PROCESS EDUCATION*/
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
			$valid_data = $this->_validate_education($params);

			Main_Model::beginTransaction();

			$pds_employee_id                = $this->session->userdata("pds_employee_id");
			
			/*GET EMPLOYEE*/
			$field                          = array("*") ;
			$table                          = $this->pds->tbl_employee_personal_info;
			$where                          = array();
			$key                            = $this->get_hash_key('employee_id');
			$where[$key]                    = $pds_employee_id;
			$personal_info                  = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                         = array() ;
			$fields['educational_level_id'] = $valid_data["level"];
			$fields['end_year']       		= $valid_data["end_year"];
			$fields['start_year']       	= $valid_data["start_year"];
			$fields['school_id']            = $valid_data["school_name"];
			$fields['highest_level']        = $valid_data["highest_level"];
			$fields['academic_honor']       = $valid_data["educ_honors_received"];
			$fields['education_degree_id']  = $valid_data["degree_course"];
			$fields['year_graduated_flag']  = $valid_data["year_graduated_flag"];
			$fields['relevance_flag']		= isset($valid_data['relevance_flag']) ? "Y" : "N";

			if($valid_data["year_graduated_flag"] == 'Y')
			{
				if($valid_data['end_year'] < $valid_data['start_year'])
				{
					throw new Exception('<b>Year Graduated</b> should not be earlier than <b>Year Started</b>.');
				}				
			}

			if($valid_data["year_graduated_flag"] == 'N')
			{
				if($valid_data['end_year'] < $valid_data['start_year'])
				{
					throw new Exception('<b>Year Ended</b> should not be earlier than <b>Year Started</b>.');
				}				
			}

			if($action == ACTION_ADD)
			{	

				$fields['employee_id']			= $personal_info["employee_id"];

				$table 							= $this->pds->tbl_employee_educations;
				$employee_education_id			= $this->pds->insert_general_data($table,$fields,TRUE);


				$audit_table[]			= $this->pds->tbl_employee_educations;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "%s has been added.";
				$audit_activity 		= sprintf($activity, $valid_data["school_name"]);


				$status = true;
				$message = $this->lang->line('data_saved');


			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_educations;
				$where						= array();
				$key 						= $this->get_hash_key('employee_education_id');
				$where[$key]				= $id;
				$education 					= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where						= array();
				$key 						= $this->get_hash_key('employee_education_id');
				$where[$key]				= $id;
				$table 						= $this->pds->tbl_employee_educations;
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]			= $this->pds->tbl_employee_educations;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($education);
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_UPDATE;	
					
				$activity 				= "%s has been updated.";
				$audit_activity 		= sprintf($activity, $education["school_name"]);

				
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
	private function _validate_education($params)
	{
		try
		{
			/*if(!empty($params['year_graduated']))
			{
				$max = (int)date('Y');
				if($params['year_graduated'] < 1900 OR $params['year_graduated'] > $max)
					throw new Exception("<b>Year Graduated</b> has an invalid data.");
			}*/
			
			
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();
			

			$fields['level']			= "Level";
			$fields['school_name']		= "Name of School";
			$fields['start_year']		= "Year Started";
			//$fields['degree_course']	= "Degree/Course";

			if ($params['year_graduated_flag'] == 'Y') {
				$fields['end_year']			= "Year Graduated";
			} else {
				$fields['end_year']			= "Year Ended";
				$fields['highest_level']	= "Highest Grade/Level/Units Earned";
			}

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_education($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_education($params)
	{
		try
		{
			
			$validation['level'] = array(
					'data_type' => 'string',
					'name'		=> 'Level',
					'max_len'	=> 50
			);	
			$validation['school_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Name of School',
					'max_len'	=> 11
			);
			$validation['start_year'] = array(
					'data_type' => 'digit',
					'name'		=> 'Year Started',
					'max_len'	=> 4
			);
			$validation['end_year'] = array(
					'data_type' => 'digit',
					'name'		=> 'Year Graduated',
					'max_len'	=> 4
			);
			$validation['year_graduated_flag'] = array(
					'data_type' => 'string',
					'name'		=> 'Graduated Tagging',
					'max_len'	=> 1
			);
			$validation['highest_level'] = array(
					'data_type' => 'string',
					'name'		=> 'Highest Grade/Level/Units Earned',
					'max_len'	=> 45
			);
			$validation['educ_honors_received'] = array(
					'data_type' => 'string',
					'name'		=> 'Scholarship/Academic Honors Received',
					'max_len'	=> 45
			);
			$validation['degree_course'] = array(
					'data_type' => 'digit',
					'name'		=> 'Degree/Course',
					'max_len'	=> 11
			);
			$validation['relevance_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Relevance Flag',
					'max_len' 			=> 1 
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function delete_education()
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
			//GET PREVIOUS DATA
			$prev_data				= array() ;
			/*GET PREVIOUS DATA*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_educations;
			$where						= array();
			$key 						= $this->get_hash_key('employee_education_id');
			$where[$key]				= $id;
			$education 					= $this->pds->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where					= array();
			$key 					= $this->get_hash_key('employee_education_id');
			$where[$key]			= $id;
			$table 					= $this->pds->tbl_employee_educations;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($education['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]				= $this->pds->tbl_employee_educations;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($education);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$activity 					= "%s has been deleted.";
			$audit_activity 		= sprintf($activity, $education["school_name"]);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 					= $this->lang->line('data_deleted');
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
			"table_id" 				=> 'pds_education_table',
			"path"					=> PROJECT_MAIN . '/pds_education_info/get_education_list/',
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
			$table                  = $this->pds->tbl_employee_educations;
			$where                  = array();
			$where['employee_education_id']            = $params['employee_education_id'];
			$emp_education        = $this->pds->get_general_data($field, $table, $where, FALSE);
			if($emp_education['relevance_flag'] == "Y")
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