<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_government_exam_info extends Main_Controller {

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

	public function get_pds_government_exam_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
					'modal_government_exam' => array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_government_exam',
							'multiple'		=> true,
							'height'		=> '350px',
							'size'			=> 'sm',
							'title'			=> 'Civil Service Eligibility'
					)
			);
			$resources['load_delete'] 	= array(
						$controller,
						'delete_government_exam',
						PROJECT_MAIN
					);
			$resources['datatable'][]	= array('table_id' => 'pds_government_exam_table', 'path' => 'main/pds_government_exam_info/get_government_exam_list', 'advanced_filter' => true);
			$data['nav_page']			= PDS_GOVERNMENT_EXAM;

		}
		catch(Exception $e)
		{
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/government_exam', $data);
		$this->load_resources->get_resource($resources);		
	}

	/*GET ELIGIBILITY DATATABLE DATA*/
	public function get_government_exam_list($action_id = NULL)
	{
		try
		{
			$params 	= get_params();
			
			$aColumns 	= array("A.employee_eligibility_id","A.employee_id","B.eligibility_type_name","IF(A.rating IS NULL, 'NA', A.rating) rating", "DATE_FORMAT(A.exam_date, '%Y/%m/%d') as exam_date", "A.exam_place", "A.relevance_flag");

			$bColumns 	= array("B.eligibility_type_name", "IF(A.rating IS NULL, 'NA', A.rating)", "DATE_FORMAT(A.exam_date, '%Y/%m/%d')", "A.exam_place", "A.relevance_flag");
			
			$eligibility	= $this->pds->get_eligibility_list($aColumns, $bColumns, $params);
			$iTotal			= $this->pds->eligibility_total_length();
			$iFilteredTotal = $this->pds->eligibility_filtered_length($aColumns, $bColumns, $params);

			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			$module 	= $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");
			/*
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($module, ACTION_DELETE);
			*/
			$cnt = 0;
			foreach ($eligibility as $aRow):
				$cnt++;
				$row 			= array();
				$action 		= "";				

				$id 			= $this->hash($aRow['employee_eligibility_id']);
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

				$row[] = $aRow['eligibility_type_name'];
				$row[] = '<p class="m-n right">' . strtoupper($aRow['rating']) . '</p>';
				$row[] = '<center>' . format_date($aRow['exam_date']) . '</center>';
				$row[] = strtoupper($aRow['exam_place']);

				$relevance   = "<div class='switch responsive-tablet'><center>
						<label>
						    <input type='checkbox' class='filled-in' name='relevance".$cnt."' id='relevance".$cnt."' value='Y' onclick=\"update_relevance('".$aRow['employee_eligibility_id']."')\" ".(($aRow['relevance_flag'] == 'Y') ? "checked" : "")." ".($pds_action == ACTION_VIEW ? 'disabled' : '').">
						    <span class='lever'></span><br><br>
							".(($aRow['relevance_flag'] == 'Y') ? "Relevant" : "Not Relevant")."
						</label></center>
					</div>";

				$action = "<div class='table-actions'>";
				// if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_government_exam' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_government_exam_init('".$url_view."')\"></a>";
				if($pds_action != ACTION_VIEW)
				{	
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_government_exam' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_government_exam_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
						$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($eligibility)){
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
	
	public function modal_government_exam($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();		
			$resources 				= array();	

			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_NUMBER);

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
				$table						= $this->pds->tbl_employee_eligibility;
				$where						= array();
				$key 						= $this->get_hash_key('employee_eligibility_id');
				$where[$key]				= $id;
				$eligibility 				= $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['eligibility'] 		= $eligibility;
			
				// $resources['single']		= array(
				// 	'eligibility_type_id' 	=> $eligibility['eligibility_type_id']
				// );
			}
			
			$field 							= array("*");
			$table							= $this->pds->tbl_param_eligibility_types;
			$where							= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		   = YES;			
			}
			else
			{
				$where['active_flag'] 		   = array(YES, array("=", "OR", "("));
		 		$where['eligibility_type_id']  = array($eligibility['eligibility_type_id'], array("=", ")"));	
		 	}
			$data['eligibility_types'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);

			$this->load->view('pds/modals/modal_government_exam', $data);
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

			$eligibility_type                = explode('|', $params['eligibility_type_id']);
	        $params['eligibility_type_id']   = ! empty($eligibility_type[0]) ? $eligibility_type[0] : '';
	        $params['eligibility_type_flag'] = ! empty($eligibility_type[1]) ? $eligibility_type[1] : '';
			
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_government_exam($params);

			
			Main_Model::beginTransaction();

			$pds_employee_id				= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_personal_info;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $pds_employee_id;
			$personal_info 					= $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields 						= array() ;
			$fields['eligibility_type_id']	= $valid_data["eligibility_type_id"];
			
			$rating = (strtolower($valid_data["rating"]) == 'na' OR strtolower($valid_data["rating"]) == 'n/a') ? NULL : $valid_data["rating"];
			$fields['rating']				= $rating;
			$fields['exam_date']			= $valid_data["exam_date"];
			$fields['exam_place']			= $valid_data["exam_place"];
			$fields['release_date']			= ! empty($valid_data["release_date"]) ? $valid_data["release_date"] : NULL;
			$fields['license_no']			= ! empty($valid_data["license_no"]) ? $valid_data["license_no"] : NULL;
			$fields['relevance_flag']		 = isset($valid_data['relevance_flag']) ? "Y" : "N";

			if($action == ACTION_ADD)
			{	
				$fields['employee_id']		= $personal_info["employee_id"];
				$table 						= $this->pds->tbl_employee_eligibility;
				$employee_eligibility_id	= $this->pds->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->pds->tbl_employee_eligibility;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "New %s has been added.";
				$audit_activity 		= sprintf($activity, "eligibility record");

				$status = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 			= array("*") ;
				$table			= $this->pds->tbl_employee_eligibility;
				$where			= array();
				$key 			= $this->get_hash_key('employee_eligibility_id');
				$where[$key]	= $id;
				$eligibility 	= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where			= array();
				$key 			= $this->get_hash_key('employee_eligibility_id');
				$where[$key]	= $id;
				$table 			= $this->pds->tbl_employee_eligibility;
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]	= $this->pds->tbl_employee_eligibility;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[] 	= array($eligibility);
				$curr_detail[]	= array($fields);
				$audit_action[] = AUDIT_UPDATE;	
					
				$activity 		= "%s has been updated.";
				$audit_activity = sprintf($activity, "Eligibility record");
				
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

	private function _validate_government_exam($params)
	{
		try
		{
			$fields 						= array();
			$fields['eligibility_type_id']	= "Eligibility";
			$fields['exam_date']			= "Examination Date";
			$fields['exam_place']			= "Examination Place";
			
			// echo "<pre>";
			// print_r($param);
			// die();

			if ( $params['eligibility_type_flag'] == ELIGIBILITY_TYPE_FLAG_RA )
			{
				$fields['license_no'] 		= "License Number";
				$fields['release_date'] 	= "Date of Validity";

			}

			// if(EMPTY($params['rating']))
			// {
			// 	throw new Exception('<b>Rating</b>' . $this->lang->line('not_applicable'));
			// }

			//NCOCAMPO: PD 907 - RATING IS NOT REQUIRED  AND OTHER TYPE OF ELIGIBILITY :START 
			if ( $params['eligibility_type_flag'] != ELIGIBILITY_TYPE_FLAG_CSPD )
			{
				if(EMPTY($params['rating']))
				{
					throw new Exception('<b>Rating</b>' . $this->lang->line('not_applicable'));
				}
			}
			//NCOCAMPO: PD 907 - RATING IS NOT REQUIRED  AND OTHER TYPE OF ELIGIBILITY :END 








			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_government_exam($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_government_exam($params)
	{
		try
		{
			$rating_str 		= strtoupper($params['rating']);
			$rating_valid		= $this->check_na($rating_str);

			
			if ( $params['eligibility_type_flag'] == ELIGIBILITY_TYPE_FLAG_CSPD )
			{
				$rating_valid = true;
			}



			if(!$rating_valid)
			{
				throw new Exception('Invalid input in <b>Rating</b>.');
			}

			$validation['eligibility_type_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Eligibility'
			);	
			$validation['exam_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date of Examination/Conferment'
			);
			$validation['exam_place'] = array(
					'data_type' => 'string',
					'name'		=> 'Place of Examination/Conferment',
					'max_len'	=> 225
			);
			$validation['license_no'] = array(
					'data_type' => 'string',
					'name'		=> 'License Number',
					'max_len'	=> 50
			);
			$validation['release_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date of Validity'
			);			
			$validation['relevance_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Relevance Flag',
					'max_len' 			=> 1 
			);
			//NCOCAMPO:PD 907 - RATING IS NOT REQUIRED  AND OTHER TYPE OF ELIGIBILITY:START
			if ( $params['eligibility_type_flag'] != ELIGIBILITY_TYPE_FLAG_CSPD )
			{
				$validation['rating'] = array(
					'data_type' => 'string',
					'name'		=> 'Rating',
				);
			}
			//NCOCAMPO:PD 907 - RATING IS NOT REQUIRED  AND OTHER TYPE OF ELIGIBILITY:END

			if(is_numeric($rating_str))
			{
				$validation['rating']['max']	= 100;
			}

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_government_exam()
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
			$prev_data     = array() ;
			/*GET PREVIOUS DATA*/
			$field         = array("*") ;
			$table         = $this->pds->tbl_employee_eligibility;
			$where         = array();
			$key           = $this->get_hash_key('employee_eligibility_id');
			$where[$key]   = $id;
			$eligibility   = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			//DELETE DATA
			$where         = array();
			$key           = $this->get_hash_key('employee_eligibility_id');
			$where[$key]   = $id;
			$table         = $this->pds->tbl_employee_eligibility;
			
			$this->pds->delete_general_data($table,$where);
			
			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($identification['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]  = $this->pds->tbl_employee_eligibility;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($eligibility);
			$curr_detail[]  = array();
			$audit_action[] = AUDIT_DELETE;
			$activity       = "%s has been deleted.";
			$audit_activity = sprintf($activity, "An eligibility record");
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg            = $this->lang->line('data_deleted');
			$flag           = 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$response 			  = array(
			"flag"            => $flag,
			"msg"             => $msg,
			"reload"          => 'datatable',
			"table_id"        => 'pds_government_exam_table',
			"path"            => PROJECT_MAIN . '/pds_government_exam_info/get_government_exam_list/',
			"advanced_filter" => true
			);
		echo json_encode($response);
	}
	
	public function check_na($val)
	{
		$valid	= true;
		if(!is_numeric($val))
		{
			if(strcmp($val, "NA") === 0)
			{
				$valid	= true;
			}elseif(strcmp($val, "N/A") === 0){
				$valid	= true;
			}else{
				$valid = false;
			}
		}
		
		return $valid;
	}
	public function update_relevance() 
	{
		try 
		{
			

			$params 				= get_params();
			$field                  = array("relevance_flag");
			$table                  = $this->pds->tbl_employee_eligibility;
			$where                  = array();
			$where['employee_eligibility_id']            = $params['employee_eligibility_id'];
			$emp_eligibility        = $this->pds->get_general_data($field, $table, $where, FALSE);
			if($emp_eligibility['relevance_flag'] == "Y")
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