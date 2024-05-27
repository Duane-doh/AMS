<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_family_info extends Main_Controller {

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

	public function get_pds_family_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
			$resources['load_modal']    		= array(
						'modal_family'  		=> array(
								'controller'	=> __CLASS__,
								'module'		=> PROJECT_MAIN,
								'method'		=> 'modal_family',
								'multiple'		=> true,
								'height'		=> '380px',
								'size'			=> 'md',
								'title'			=> 'Family Information'
						)
				);
			$resources['load_delete'] 	= array(
						$controller,
						'delete_family',
						PROJECT_MAIN
					);
			$resources['datatable'][]	= array('table_id' => 'pds_family_table', 'path' => 'main/pds_family_info/get_family_list', 'advanced_filter' => true);
			$data['nav_page']			= PDS_FAMILY;

		}
		catch(Exception $e)
		{
			RLog::error($e->getMessage());
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/family', $data);
		$this->load_resources->get_resource($resources);
		
	}

	public function modal_family($action, $id, $token, $salt, $module)
	{
		try
		{
			$data = array();
			
			$resources['load_css']	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE, CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER, JS_SELECTIZE, JS_LABELAUTY);

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
				$field 					= array("*") ;
				$table					= $this->pds->tbl_employee_relations;
				$where					= array();
				$key 					= $this->get_hash_key('employee_relation_id');
				$where[$key]			= $id;
				$family 				= $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['family'] 		= $family;
			
				$resources['single']	= array(
					'relation_type'     => $family['relation_type_id'],
					'ext_name'          => $family['relation_ext_name'],
					'employment_status' => $family['relation_employment_status_id'],
					'civil_status'      => $family['relation_civil_status_id']
				);
			}
			//GET RELATION TYPES
			$field 						= array("*");
			$table						= $this->pds->tbl_param_relation_types;
			$where						= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['relation_type_id']  = array($family['relation_type_id'], array("=", ")"));				
			}	
			$data['relation_types'] 	= $this->pds->get_general_data($field, $table, $where, TRUE);

			//GET CIVIL STATUSES
			$field 						= array("*");
			$table						= $this->pds->tbl_param_civil_status;
			$where						= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['civil_status_id']   = array($family['relation_civil_status_id'], array("=", ")"));				
			}	
			$data['civil_status'] 		= $this->pds->get_general_data($field, $table, $where, TRUE);

			//GET FAMILY EMPLOYMENT STATUS
			$field 						= array("*");
			$table						= $this->pds->tbl_param_relation_employment_status;
			$where						= array();
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 					= YES;			
			}
			else
			{
				$where['active_flag'] 					= array(YES, array("=", "OR", "("));
		 		$where['relation_employment_status_id'] = array($family['relation_employment_status_id'], array("=", ")"));				
			}	
			$data['employment_status'] 	= $this->pds->get_general_data($field, $table, $where, TRUE);

			$this->load->view('pds/modals/modal_family', $data);
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

	public function get_family_list($action_id = NULL)
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.employee_relation_id","A.employee_id", "CONCAT(IFNULL(A.relation_last_name, ''), IFNULL(A.relation_ext_name, ''), IF(A.relation_last_name = '' OR A.relation_last_name IS NULL, '',', '), IFNULL(A.relation_first_name, ''), ' ', IFNULL(A.relation_middle_name, '')) as fullname", "B.relation_type_name", "DATE_FORMAT(A.relation_birth_date, '%Y/%m/%d') as relation_birth_date");
			$bColumns       = array("CONCAT(A.relation_last_name,' ', IFNULL(A.relation_ext_name, ''), ', ', A.relation_first_name, ' ',A.relation_middle_name)", "B.relation_type_name", "DATE_FORMAT(A.relation_birth_date, '%Y/%m/%d')");
			$family         = $this->pds->get_family_list($aColumns, $bColumns, $params);
			$iTotal         = $this->pds->family_total_length();
			$iFilteredTotal = $this->pds->family_filtered_length($aColumns, $bColumns, $params);
			
			$output 				   = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module     = $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");
			
			$cnt = 0;		
			foreach ($family as $aRow):
				$cnt++;
				$row          = array();
				$action       = "";
				
				$id           = $this->hash($aRow['employee_relation_id']);
				$salt         = gen_salt();
				$token_view   = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view     = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit     = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete   = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;
				
				$row[]        = strtoupper(ucwords($aRow['fullname']));
				$row[]        = strtoupper($aRow['relation_type_name']);
				$row[]        = '<center>' . format_date($aRow['relation_birth_date']) . '</center>';

				$action = "<div class='table-actions'>";
				// if($permission_view)
					$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_family' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_family_init('".$url_view."')\"></a>";
				if($pds_action != ACTION_VIEW)
				{	
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_family' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_family_init('".$url_edit."')\"></a>";
					$delete_action = 'content_delete("record", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
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

	/*PROCESS FAMILY*/
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
			$valid_data = $this->_validate_family($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 				= array("*") ;
			$table				= $this->pds->tbl_employee_personal_info;
			$where				= array();
			$key 				= $this->get_hash_key('employee_id');
			$where[$key]		= $pds_employee_id;
			$personal_info 		= $this->pds->get_general_data($field, $table, $where, FALSE);
		
			$fields                                  = array() ;
			$fields['relation_last_name']            = $valid_data["relation_last_name"];
			$fields['relation_first_name']           = $valid_data["relation_first_name"];
			$fields['relation_middle_name']          = $valid_data["relation_middle_name"];
			$fields['relation_type_id']              = $valid_data["relation_type"];
			$fields['relation_birth_date']           = $valid_data["relation_birth_date"];
			$fields['relation_gender_code']          = !EMPTY($valid_data["gender"]) ? $valid_data["gender"] : NULL;
			$fields['relation_occupation']           = !EMPTY($valid_data["relation_occupation"]) ? $valid_data["relation_occupation"] : NULL;
			$fields['relation_contact_num']          = !EMPTY($valid_data["relation_contact_num"]) ? str_replace('-', '', $valid_data['relation_contact_num']) : NULL;
			$fields['relation_company']              = !EMPTY($valid_data["relation_company"]) ? $valid_data["relation_company"] : NULL;
			$fields['relation_company_address']      = !EMPTY($valid_data["relation_company_address"]) ? $valid_data["relation_company_address"] : NULL;
			$fields['relation_employment_status_id'] = !EMPTY($valid_data["employment_status"]) ? $valid_data["employment_status"] : NULL;
			$fields['relation_ext_name']             = !EMPTY($valid_data["ext_name"]) ? $valid_data["ext_name"] : NULL;
			$fields['relation_civil_status_id']      = !EMPTY($valid_data["civil_status"]) ? $valid_data["civil_status"] : NULL;
			// $fields['deceased_flag']                 = $valid_data['deceased']; ncocampo	10/31/2023
			$fields['deceased_flag']                 = EMPTY($valid_data["deceased"]) ? 'N':'Y';		
			$fields['pwd_flag']                      = EMPTY($valid_data["disable_flag"]) ? 'N':'Y';
			$fields['death_date']                    = ($valid_data["deceased"] == 'Y' AND !EMPTY($valid_data["death_date"])) ? $valid_data["death_date"] : NULL;

			// SET FIELDS TO AUDIT TRAIL
			$audit_fields                                  = array() ;
			$audit_fields['relation_last_name']            = $valid_data["relation_last_name"];
			$audit_fields['relation_first_name']           = $valid_data["relation_first_name"];
			$audit_fields['relation_middle_name']          = $valid_data["relation_middle_name"];
			$audit_fields['relation_type_id']              = $valid_data["relation_type"];
			$audit_fields['relation_birth_date']           = $valid_data["relation_birth_date"];
			$audit_fields['relation_gender_code']          = !EMPTY($valid_data["gender"]) ? $valid_data["gender"] : '';
			$audit_fields['relation_occupation']           = !EMPTY($valid_data["relation_occupation"]) ? $valid_data["relation_occupation"] : ' ';
			$audit_fields['relation_contact_num']          = !EMPTY($valid_data["relation_contact_num"]) ? str_replace('-', '', $valid_data['relation_contact_num']) : ' ';
			$audit_fields['relation_company']              = !EMPTY($valid_data["relation_company"]) ? $valid_data["relation_company"] : ' ';
			$audit_fields['relation_company_address']      = !EMPTY($valid_data["relation_company_address"]) ? $valid_data["relation_company_address"] : ' ';
			$audit_fields['relation_employment_status_id'] = !EMPTY($valid_data["employment_status"]) ? $valid_data["employment_status"] : ' ';
			$audit_fields['relation_ext_name']             = !EMPTY($valid_data["ext_name"]) ? $valid_data["ext_name"] : ' ';
			$audit_fields['relation_civil_status_id']      = !EMPTY($valid_data["civil_status"]) ? $valid_data["civil_status"] : ' ';
			// $fields['deceased_flag']                 = $valid_data['deceased']; ncocampo	 10/31/2023
			$fields['deceased_flag']               		   = EMPTY($valid_data["deceased"]) ? 'N':'Y';			
			$audit_fields['pwd_flag']                      = EMPTY($valid_data["disable_flag"]) ? 'N':'Y';
			$audit_fields['death_date']                    = !EMPTY($valid_data["death_date"]) ? $valid_data["death_date"] : ' ';
			
			if(!EMPTY($valid_data["death_date"]))
			{
				if($valid_data['death_date'] < $valid_data['relation_birth_date'])
				{
					throw new Exception('<b>Date of Death</b> should not be earlier than <b>Date of Birth</b>.');
				}

				if($valid_data['death_date'] > date('Y/m/d'))
				{
					throw new Exception('Entered date in Death Date exceeded the maximum date (' . date('Y/m/d') . ')');
				}
			}

			
			
			if($action == ACTION_ADD)
			{	
				$fields['employee_id']      = $personal_info["employee_id"];
				
				$table                      = $this->pds->tbl_employee_relations;
				$employee_identification_id = $this->pds->insert_general_data($table,$fields,TRUE);
				
				
				$audit_table[]              = $this->pds->tbl_employee_relations;
				$audit_schema[]             = DB_MAIN;
				$prev_detail[]              = array();
				$curr_detail[]              = array($audit_fields);
				$audit_action[]             = AUDIT_INSERT;	
				
				$activity                   = "%s has been added.";
				$audit_activity             = sprintf($activity, $valid_data["relation_first_name"] . " ".$valid_data["relation_last_name"]);
				
				
				$status                     = true;
				$message                    = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_relations;
				$where						= array();
				$key 						= $this->get_hash_key('employee_relation_id');
				$where[$key]				= $id;
				$family 					= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where						= array();
				$key 						= $this->get_hash_key('employee_relation_id');
				$where[$key]				= $id;
				$table 						= $this->pds->tbl_employee_relations;

				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]  = $this->pds->tbl_employee_relations;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($family);
				$curr_detail[]  = array($audit_fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				$activity       = "%s has been updated.";
				$audit_activity = sprintf($activity, $family["relation_first_name"] . " ".$family["relation_last_name"]);
				
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
	private function _validate_family($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields 						= array();

			$fields['relation_last_name']	= "Last Name";
			$fields['relation_first_name']	= "First Name";
			$fields['relation_middle_name']	= "Middle Name";
			$fields['relation_type']		= "Relationship";
			// $fields['relation_birth_date']	= "Birth Date";

			//NCOCAMPO : ENABLE BIRTHDATE FOR CHILD RELATION START : 10/31/2023
			if($params['relation_type'] == 7)
			{
				$fields['relation_birth_date']	= "Birth Date";
			}
			//NCOCMPO END 10/23/2023

			if ($params['deceased'] == 'N') 
			{
				$fields['employment_status']= "Employment Status";
				$fields['civil_status']		= "Civil Status";
			} 
			if ($params['deceased'] == 'Y') 
			{
				$fields['death_date']		= "Date of Death";
			}

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_family($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_family($params)
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
					'name'		=> 'Relationship'
			);	
			//NCOCAMPO ENABLE BIRTHDATE FOR CHILD RELATION START 10/31/2023
			if($params['relation_type'] == 7){
				$validation['relation_birth_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Birth Date',
					'max_date'	=> date("Y/m/d")
				);
			}
			//NCOCAMPO END 10/31/2023
			
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
					'name'		=> 'Employment Status'
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
					'name'		=> 'Civil Status'
			);
			$validation['disable_flag'] = array(
					'data_type' => 'string',
					'name'		=> 'Disable Tagging',
					'max_len'	=> 1
			);
			$validation['deceased'] = array(
					'data_type' => 'string',
					'name'		=> 'Deceased',
					'max_len'	=> 1
			);
			$validation['death_date'] = array(
					'data_type' => 'Date',
					'name'		=> 'Death Date'
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function delete_family()
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
			$field 					= array("*") ;
			$table					= $this->pds->tbl_employee_relations;
			$where					= array();
			$key 					= $this->get_hash_key('employee_relation_id');
			$where[$key]			= $id;
			$family 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where					= array();
			$key 					= $this->get_hash_key('employee_relation_id');
			$where[$key]			= $id;
			$table 					= $this->pds->tbl_employee_relations;
			
			$this->pds->delete_general_data($table,$where);

			//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
			$pds_employee_id            = $this->hash($family['employee_id']);
			$this->pds->update_pds_date_accomplished($pds_employee_id);
			
			$audit_table[]			= $this->pds->tbl_employee_relations;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array($family);
			$curr_detail[]			= array();
			$audit_action[] 		= AUDIT_DELETE;
			$activity 				= "%s has been deleted.";
			$audit_activity 		= sprintf($activity, $family["relation_first_name"] . " ".$family["relation_last_name"]);

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
			"table_id" 				=> 'pds_family_table',
			"path"					=> PROJECT_MAIN . '/pds_family_info/get_family_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */