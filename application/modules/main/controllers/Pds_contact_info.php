<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Pds_contact_info extends Main_Controller {

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

	public function get_pds_contact_info($action=NULL, $id=NULL, $token=NULL, $salt=NULL, $module=NULL)
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
					'modal_address_info'  	=> array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_address_info',
							'multiple'		=> true,
							'height'		=> '350px',
							'size'			=> 'sm',
							'title'			=> 'Address Information'
					),
					'modal_contact_info'  	=> array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_contact_info',
							'multiple'		=> true,
							'height'		=> '200px',
							'size'			=> 'sm',
							'title'			=> SUB_MENU_CONTACT_INFO
					),
			);
			$resources['load_delete'] 		= array(
						$controller,
						'delete_address',
						PROJECT_MAIN
					);

			$resources['datatable'][]		= array('table_id' => 'address_table', 'path' => 'main/pds_contact_info/get_address_list', 'advanced_filter' => true);
			$resources['datatable'][]		= array('table_id' => 'contacts_table', 'path' => 'main/pds_contact_info/get_contact_list', 'advanced_filter' => true);
			$data['nav_page']				= PDS_CONTACT_INFO;
		}
		catch(Exception $e)
		{
			$data['message'] = $e->getMessage();
		}

		$this->load->view('pds/tabs/contact_info', $data);
		$this->load_resources->get_resource($resources);		
	}

	public function get_address_list()
	{
		try
		{
			$params 	= get_params();
			
			$aColumns 	= array("A.employee_address_id","A.employee_id","B.address_type_name", "A.address_value", "A.postal_number");
			$bColumns 	= array("B.address_type_name", "A.address_value", "A.postal_number");
			
			$address 		= $this->pds->get_address_list($aColumns, $bColumns, $params);
			$iTotal			= $this->pds->address_total_length();
			$iFilteredTotal = $this->pds->address_filtered_length($aColumns, $bColumns, $params);

			$output						= array(
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
			foreach ($address as $aRow):
				$cnt++;
				$row = array();
				$action = "";
				

				$id 			= $this->hash($aRow['employee_address_id']);
				$salt			= gen_salt();
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

				$row[] = strtoupper($aRow['address_type_name']);
				$row[] = strtoupper($aRow['address_value']);
				$row[] = $aRow['postal_number'];

				$action = "<div class='table-actions'>";
				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_address_info' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_address_info_init('".$url_edit."')\"></a>";
					
					$delete_action = 'content_delete("address information", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($address)){
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
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => 0,
				"aaData"               => array()
			);
		}

		echo json_encode( $output );
	}

	public function get_contact_list()
	{

		try
		{
			$params 	= get_params();
			
			$aColumns 	= array("A.employee_contact_id","A.employee_id","B.contact_type_name", "A.contact_value", 'A.contact_type_id');
			$bColumns 	= array("B.contact_type_name", "A.contact_value");
			
			$contacts 		= $this->pds->get_contacts_list($aColumns, $bColumns, $params);
			$iTotal			= $this->pds->contacts_total_length();
			$iFilteredTotal = $this->pds->contacts_filtered_length($aColumns, $bColumns, $params);

			$output						= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords"			=> $iTotal["cnt"],
				"iTotalDisplayRecords" 	=> $iFilteredTotal["cnt"],
				"aaData" 				=> array()
			);
			$module 	= $this->session->userdata("pds_module");
			$pds_action = $this->session->userdata("pds_action");

			$cnt 		= 0;
			foreach ($contacts as $aRow):
				$cnt++;
				$row 			= array();
				$action 		= "";				

				$id 			= $this->hash($aRow['employee_contact_id']);
				$salt			= gen_salt();
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".ACTIVE;

				$row[] 			= strtoupper($aRow['contact_type_name']);

				if($aRow['contact_type_id'] == MOBILE_NUMBER2)
				{
					$row[] 		= strtoupper(format_identifications($aRow['contact_value'], CELLPHONE_FORMAT));	
				}
				else if($aRow['contact_type_id'] == EMAIL2)
				{
					$row[] 		= strtolower($aRow['contact_value']);
				}
				else
				{
					$row[] 		= strtoupper($aRow['contact_value']);
				}
				
				$action 		= "<div class='table-actions'>";

				if($pds_action != ACTION_VIEW)
				{
					// if($permission_edit)
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_contact_info' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_contact_info_init('".$url_edit."')\"></a>";
					
					$delete_action = 'content_delete("contact information", "'.$url_delete.'")';
					// if($permission_delete)
					$action .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
				$action .= "</div>";
				if($cnt == count($contacts)){
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

	public function modal_address_info($action, $id, $token, $salt, $module)
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
		
			//GET MUNICIPALITY PROVINCE AND REGION 
			// $tables 			= array(
			// 	'main'			=> array(
			// 		'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_barangays,
			// 		'alias'		=> 'D',
			// 	),
			// 	't1'			=> array(
			// 		'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_municities,
			// 		'alias'		=> 'A',
			// 		'type'		=> 'left join',
			// 		'condition'	=> 'D.municity_code = A.municity_code',
			// 	),
			// 	't2'			=> array(
			// 		'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_provinces,
			// 		'alias'		=> 'B',
			// 		'type'		=> 'left join',
			// 		'condition'	=> 'A.province_code = B.province_code',
			// 	),
			// 	't3'			=> array(
			// 		'table'		=> $this->pds->db_core.".".$this->pds->tbl_param_regions,
			// 		'alias'		=> 'C',
			// 		'type'		=> 'left join',
			// 		'condition'	=> 'B.region_code = C.region_code',
			// 	)
			// );
			
			// $field               = array("concat_ws(', ', D.barangay_name, A.municity_name, B.province_name, C.region_name)  as Address, concat_ws(' ', D.barangay_code, A.municity_code, B.province_code, C.region_code)  as codes") ;					
			// $where               = array();
			// $data['address_key'] = $this->pds->get_general_data($field, $tables, $where, TRUE);

			//GET MUNICIPALITY PROVINCE AND REGION 			
			$address_value = $this->pds->get_addresses();
			$data['address_value']   = $address_value['addresses'];

			if($action != ACTION_ADD)
			{
				//GET ADDRESS
				$field                       = array("address_type_id, address_value, postal_number, concat_ws(' ', barangay_code, municity_code, province_code, region_code)  as codes") ;
				$table                       = $this->pds->tbl_employee_addresses;
				$where                       = array();
				$key                         = $this->get_hash_key('employee_address_id');
				$where[$key]                 = $id;
				$address_info                = $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['address_info']        = $address_info;
			
				$resources['single'] 		   = array(
					'address_type_id'          => $address_info['address_type_id'],
					'municipality_residential' => $address_info['codes']
				);
			}

			//GET ADDRESS TYPE
			$field 							= array("*");
			$table							= $this->pds->tbl_param_address_types;
			$where							= array();
			$where['builtin_flag']			= NO;
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['address_type_id']   = array($address_info['address_type_id'], array("=", ")"));				
			}			
			$data['address_types'] 			= $this->pds->get_general_data($field, $table, $where, TRUE);

			$this->load->view('pds/modals/modal_address_info', $data);
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

	public function modal_contact_info($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();
			$data['action_id'] 		= $action_id; 
			
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
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_contacts;
				$where						= array();
				$key 						= $this->get_hash_key('employee_contact_id');
				$where[$key]				= $id;
				$contacts 					= $this->pds->get_general_data($field, $table, $where, FALSE);
				$data['contacts'] 			= $contacts;
			
				$resources['single']= array(
					'contact_type' 	=> $contacts['contact_type_id']
				);
			}
			
			$field 							= array("*") ;
			$table							= $this->pds->tbl_param_contact_types;
			$where							= array();
			$where['builtin_flag']			= NO;
			if($action == ACTION_ADD)
			{
				$where['active_flag'] 		= YES;			
			}
			else
			{
				$where['active_flag'] 		= array(YES, array("=", "OR", "("));
		 		$where['contact_type_id']   = array($contacts['contact_type_id'], array("=", ")"));				
			}		
			$data['contact_types'] 			= $this->pds->get_general_data($field, $table, $where, TRUE);

			$this->load->view('pds/modals/modal_contact_info', $data);
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

	public function get_province()
	{
		$params					= get_params();
		$list 					= array();
		$region_code 			= $params['region_code'];

		//GET PROVINCES
		$field                  = array("*") ;
		$table                  = $this->pds->db_core.".".$this->pds->tbl_param_provinces;
		$where                  = array();
		$where['region_code']   = $region_code;
		$provinces       		= $this->pds->get_general_data($field, $table, $where, TRUE);		
		
		foreach ($provinces as $aRow):					
			$list[] 	= array(
				"value" => $aRow["province_code"],
				"text" 	=> $aRow["province_name"]
			);
		endforeach;
		
		$info 		= array(
			"list" 	=> $list
		);
	
		echo json_encode($info);
	}

	public function get_municipality()
	{
		$params					= get_params();
		$list 					= array();
		$province_code 			= $params['province_code'];

		//GET PROVINCES
		$field                  = array("*") ;
		$table                  = $this->pds->db_core.".".$this->pds->tbl_param_municities;
		$where                  = array();
		$where['province_code'] = $province_code;
		$municipalities       	= $this->pds->get_general_data($field, $table, $where, TRUE);		
		
		foreach ($municipalities as $aRow):					
			$list[] 	= array(
				"value" => $aRow["municity_code"],
				"text" 	=> $aRow["municity_name"]
			);
		endforeach;
		
		$info 		= array(
			"list" 	=> $list
		);
	
		echo json_encode($info);
	}

	public function process_address()
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
			$valid_data = $this->_validate_address($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 						= array("*");
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			$str_residential   			= $params["municipality_residential"];
			$codes_residential 			= explode(' ',$str_residential);

			$fields 				   	= array();			
			$fields['address_type_id']	= $valid_data["address_type_id"];
			$fields['postal_number']   	= $valid_data["postal_number"];
			$fields['address_value']  	= $valid_data["address_value"];
			$fields['barangay_code']  	= $codes_residential[0];
			$fields['municity_code']   	= $codes_residential[1];
			$fields['province_code']  	= $codes_residential[2];
			$fields['region_code']    	= $codes_residential[3];

			if($action == ACTION_ADD)
			{	
				$fields['employee_id']	= $personal_info["employee_id"];

				$table 					= $this->pds->tbl_employee_addresses;
				$employee_education_id	= $this->pds->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->pds->tbl_employee_addresses;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "New address %s has been added.";
				$audit_activity 		= sprintf($activity, $valid_data["address_value"]);

				$status  = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 			= array("*") ;
				$table			= $this->pds->tbl_employee_addresses;
				$where			= array();
				$key 			= $this->get_hash_key('employee_address_id');
				$where[$key]	= $id;
				$address 		= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where			= array();
				$key 			= $this->get_hash_key('employee_address_id');
				$where[$key]	= $id;
				$table 			= $this->pds->tbl_employee_addresses;

				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]	= $this->pds->tbl_employee_addresses;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[] 	= array($address);
				$curr_detail[]	= array($fields);
				$audit_action[] = AUDIT_UPDATE;	
					
				$activity 		= "%s has been updated.";
				$audit_activity = sprintf($activity, $address["address_value"]);
				
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

	private function _validate_address($params)
	{
		try
		{
			$fields 							= array();
			$fields['address_type_id']          = "Address Type";
			$fields['municipality_residential'] = "Municipality";
			$fields['address_value'] 			= "Address";
		
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_address($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_address($params)
	{
		try
		{			
			$validation['address_type_id'] = array(
					'data_type' => 'enum',
					'name'		=> 'Address Type'
			);	
			$validation['postal_number'] = array(
					'data_type' => 'string',
					'name'		=> 'Zip Code',
					'max_len'	=> 10
			);
			$validation['address_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Address'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*PROCESS CONTACTS*/
	public function process_contact()
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

			// REMOVE SPECIAL CHARACTERS BEFORE SAVING
			if($params["contact_type"] == MOBILE_NUMBER2)
			{				
				$contact 				 = $params["contact_value"];
				$params["contact_value"] = str_replace('-', '', $contact);
			}

			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_contact($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id			= $this->session->userdata("pds_employee_id");

			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			$fields 					= array() ;
			$fields['contact_type_id']	= $valid_data["contact_type"];
			$fields['contact_value']	= $valid_data["contact_value"];
			
			if($action == ACTION_ADD)
			{	
				$fields['employee_id']	= $personal_info["employee_id"];

				$table 					= $this->pds->tbl_employee_contacts;
				$employee_contact_id	= $this->pds->insert_general_data($table,$fields,TRUE);

				$audit_table[]			= $this->pds->tbl_employee_contacts;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	

				$activity 				= "New contact %s has been added.";
				$audit_activity 		= sprintf($activity, $valid_data["contact_value"]);

				$status  = true;
				$message = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field 			= array("*") ;
				$table			= $this->pds->tbl_employee_contacts;
				$where			= array();
				$key 			= $this->get_hash_key('employee_contact_id');
				$where[$key]	= $id;
				$contact 		= $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$where			= array();
				$key 			= $this->get_hash_key('employee_contact_id');
				$where[$key]	= $id;
				$table 			= $this->pds->tbl_employee_contacts;
				$this->pds->update_general_data($table,$fields,$where);

				$audit_table[]	= $this->pds->tbl_employee_contacts;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[] 	= array($contact);
				$curr_detail[]	= array($fields);
				$audit_action[] = AUDIT_UPDATE;	
					
				$activity 		= "%s has been updated.";
				$audit_activity = sprintf($activity, $contact["contact_value"]);

				
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

	private function _validate_contact($params)
	{
		try
		{
			$fields 					= array();
			$fields['contact_type']		= "Contact Type";
			$fields['contact_value']	= "Contact Value";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_contact($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_contact($params)
	{
		try
		{			
			$validation['contact_type'] = array(
					'data_type' => 'enum',
					'name'		=> 'Contact Type'
			);	
			$validation['contact_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Contact',
					'max_len'	=> 100
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_address()
	{
		try
		{
			$flag 			= 0;
			$table_id 		= "";
			$data_path 		= "";
			$params			= get_params();
			$url 			= $params['param_1'];
			$url_explode	= explode('/',$url);
			$action 		= $url_explode[0];
			$id				= $url_explode[1];
			$token 			= $url_explode[2];
			$salt 			= $url_explode[3];
			$module			= $url_explode[4];
			$from_contact	= $url_explode[5];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			Main_Model::beginTransaction();

			if(EMPTY($from_contact))
			{
				//GET PREVIOUS DATA
				$prev_data		= array();
				/*GET PREVIOUS DATA*/
				$field 			= array("*") ;
				$table			= $this->pds->tbl_employee_addresses;
				$where			= array();
				$key 			= $this->get_hash_key('employee_address_id');
				$where[$key]	= $id;
				$address 		= $this->pds->get_general_data($field, $table, $where, FALSE);

				//DELETE DATA
				$where			= array();
				$key 			= $this->get_hash_key('employee_address_id');
				$where[$key]	= $id;
				$table 			= $this->pds->tbl_employee_addresses;
				
				$this->pds->delete_general_data($table,$where);

				//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
				$pds_employee_id            = $this->hash($address['employee_id']);
				$this->pds->update_pds_date_accomplished($pds_employee_id);
				
				$audit_table[]	= $this->pds->tbl_employee_addresses;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[] 	= array($address);
				$curr_detail[]	= array();
				$audit_action[] = AUDIT_DELETE;
				$activity 		= "%s has been deleted.";
				$audit_activity = sprintf($activity, $address["address_value"]);
				$table_id 		= "address_table";
				$data_path 		= PROJECT_MAIN . '/pds_contact_info/get_address_list/';

			}
			else
			{
				//GET PREVIOUS DATA
				$prev_data		= array();
				/*GET PREVIOUS DATA*/
				$field 			= array("*") ;
				$table			= $this->pds->tbl_employee_contacts;
				$where			= array();
				$key 			= $this->get_hash_key('employee_contact_id');
				$where[$key]	= $id;
				$contact 		= $this->pds->get_general_data($field, $table, $where, FALSE);

				//DELETE DATA
				$where			= array();
				$key 			= $this->get_hash_key('employee_contact_id');
				$where[$key]	= $id;
				$table 			= $this->pds->tbl_employee_contacts;
				
				$this->pds->delete_general_data($table,$where);

				//UPDATE PERSONAL INFO - DATE ACCOMPLISHED
				$pds_employee_id            = $this->hash($contact['employee_id']);
				$this->pds->update_pds_date_accomplished($pds_employee_id);
				
				$audit_table[]	= $this->pds->tbl_employee_contacts;
				$audit_schema[]	= DB_MAIN;
				$prev_detail[] 	= array($contact);
				$curr_detail[]	= array();
				$audit_action[] = AUDIT_DELETE;
				$activity 		= "%s has been deleted.";
				$audit_activity = sprintf($activity, $contact["contact_value"]);
				$table_id  		= "contacts_table";
				$data_path 		= PROJECT_MAIN . '/pds_contact_info/get_contact_list/';
			}			

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg 				= $this->lang->line('data_deleted');
			$flag 				= 1;
		}		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$response 				= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> $table_id,
			"path"				=> $data_path,
			"advanced_filter" 	=> true
			);
		echo json_encode($response);
	}

}
/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */