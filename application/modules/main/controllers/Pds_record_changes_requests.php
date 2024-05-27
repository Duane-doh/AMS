<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pds_record_changes_requests extends Main_Controller {
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pds_model', 'pds');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	public function insert_sub_request($type, $id,$action)
	{
		try
		{
			$fields                          = array() ;
			$fields['request_sub_type_id']   = $type;
			$fields['employee_id']           = $id;
			$fields['request_sub_status_id'] = SUB_REQUEST_NEW;
			$fields['action']                = $action;
			
			$table                           = $this->pds->tbl_requests_sub;
			$sub_request_id                  = $this->pds->insert_general_data($table,$fields,TRUE);

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
	/*PROCESS PERSONAL INFO*/
	public function process_peronal_info()
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
			
			// REMOVE THE SYMBOLS IN EACH IDENTIFICATIONS
			$field                   = array('*');
			$table                   = $this->pds->tbl_param_identification_types;
			$where                   = array( 'builtin_flag' => 'Y' );
			$identifications 		 = $this->pds->get_general_data($field, $table, $where);
			$separator = array();
			foreach ($identifications as $key => $value)
			{
				$concat = explode('x', $value['format']);
				$separator[] = $concat[count($concat)-1];
			}
			//davcorrea : START: Middle Name validation
			if(strpos($params['middle_name'] , '-') !== false || strpos($params['middle_name'], '/') !== false || strpos($params['middle_name'], '\\') !== false)
			{
				throw new Exception("Middle Name Must Not Contain Special Characters. Please Put NA If Middle Name is Not Applicable.");
			}
			// davcorrea END
			$params['tin_value']        = str_replace($separator[0], '', $params['tin_value']);
			$params['sss_value']        = str_replace($separator[1], '', $params['sss_value']);
			$params['gsis_value']       = str_replace($separator[2], '', $params['gsis_value']);
			$params['pagibig_value']    = str_replace($separator[3], '', $params['pagibig_value']);
			$params['philhealth_value'] = str_replace($separator[4], '', $params['philhealth_value']);
			
			$params["telephone_residential_value"]  = str_replace(CONTACT_SEPARATOR, '', $params["telephone_residential_value"]);
			$params["telephone_permanent_value"]   	= str_replace(CONTACT_SEPARATOR, '', $params["telephone_permanent_value"]);
			$params["cellphone_value"]   			= str_replace(CONTACT_SEPARATOR, '', $params["cellphone_value"]);
			
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_peronal_info($params);
			/*GET PREVIOUS PERSONAL INFO DATA*/
			$field                           = array("*") ;
			$table                           = $this->pds->tbl_requests_sub;
			$where                           = array();
			$key                             = $this->get_hash_key('employee_id');
			$where[$key]                     = $id;
			$where['request_sub_type_id']    = TYPE_REQUEST_PDS_PERSONAL_INFO;
			$where['request_sub_status_id']  = SUB_REQUEST_NEW;
			$where['request_id']             = "IS NULL";
			$req_personal_info               = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			if($req_personal_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}

			Main_Model::beginTransaction();

			/*GET PREVIOUS PERSONAL INFO DATA*/
			$field                           = array("*") ;
			$table                           = $this->pds->tbl_employee_personal_info;
			$where                           = array();
			$key                             = $this->get_hash_key('employee_id');
			$where[$key]                     = $id;
			$personal_info                   = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			/*GET PREVIOUS IDENTIFICATION DATA*/
			$field                           = array("*") ;
			$table                           = $this->pds->tbl_employee_identifications;
			$where                           = array();
			$key                             = $this->get_hash_key('employee_id');
			$where[$key]                     = $id;
			$where['identification_type_id'] = array("5", array("<="));
			$order_by 						 = array('identification_type_id' => 'ASC');
			$identification_info             = $this->pds->get_general_data($field, $table, $where, TRUE, $order_by);
			
			/*GET PREVIOUS CONTACTS DATA*/
			$field                           = array("*") ;
			$table                           = $this->pds->tbl_employee_contacts;
			$where                           = array();
			$key                             = $this->get_hash_key('employee_id');
			$where[$key]                     = $id;
			$where['contact_type_id']        = array("4", array("<="));
			$contact_info                    = $this->pds->get_general_data($field, $table, $where, TRUE);
			
			/*GET PREVIOUS ADDRESS DATA*/
			$field                           = array("*") ;
			$table                           = $this->pds->tbl_employee_addresses;
			$where                           = array();
			$key                             = $this->get_hash_key('employee_id');
			$where[$key]                     = $id;
			$where['address_type_id']        = array("2", array("<="));
			$address_info                    = $this->pds->get_general_data($field, $table, $where, TRUE);


			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_PERSONAL_INFO,$personal_info["employee_id"],$action);
			
			RLog::Info("here-->".json_encode($identification_info));
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			/*############################ START : INSERT PERSONAL INFO DATA #############################*/
			$fields                           = array() ;
			$fields['employee_id']            = $personal_info["employee_id"];
			$fields['request_sub_id']         = $sub_request_id;
			$fields['agency_employee_id']     = $valid_data["agency_employee_id"];
			$fields['biometric_pin']          = $valid_data["biometric_pin"];
			$fields['first_name']             = $valid_data["first_name"];
			$fields['last_name']              = $valid_data["last_name"];
			$fields['middle_name']            = $valid_data["middle_name"];
			$fields['ext_name']               = $valid_data["ext_name"];
			$fields['birth_date']             = $valid_data["birth_date"];
			$fields['birth_place']            = $valid_data["birth_place"];
			$fields['gender_code']            = $valid_data["gender"];
			$fields['civil_status_id']        = $valid_data["civil_status"];
			$fields['citizenship_id']         = $valid_data["citizenships"];
			$fields['citizenship_basis_id']   = $valid_data["citizenship_basis"];
			$fields['height']                 = $valid_data["height"];
			$fields['weight']                 = $valid_data["weight"];
			$fields['blood_type_id']          = $valid_data["blood_type"];
			$fields['pds_status_id']          = 1;
			
			$fields['agency_employee_id_old'] 	= $valid_data["agency_employee_id"];
			$fields['biometric_pin_old']      	= $valid_data["biometric_pin"];
			$fields['first_name_old']         	= $personal_info["first_name"];
			$fields['last_name_old']          	= $personal_info["last_name"];
			$fields['middle_name_old']        	= $personal_info["middle_name"];
			$fields['ext_name_old']           	= $personal_info["ext_name"];
			$fields['birth_date_old']         	= $personal_info["birth_date"];
			$fields['birth_place_old']        	= $personal_info["birth_place"];
			$fields['gender_code_old']        	= $personal_info["gender_code"];
			$fields['civil_status_id_old']    	= $personal_info["civil_status_id"];
			$fields['citizenship_id_old']     	= $personal_info["citizenship_id"];
			$fields['citizenship_basis_id_old'] = $personal_info["citizenship_basis_id"];
			$fields['height_old']             	= $personal_info["height"];
			$fields['weight_old']             	= $personal_info["weight"];
			$fields['blood_type_id_old']      	= $personal_info["blood_type_id"];
			$fields['pds_status_id_old']      	= 1; 
			
			$table                            = $this->pds->tbl_requests_employee_personal_info;
			$this->pds->insert_general_data($table,$fields,FALSE);			

			//IDENTIFICATION ID
			$fields_identification                   = array();
			$table_identification                    = $this->pds->tbl_requests_employee_identifications;

			if($params['tin_value']!=$identification_info[0]["identification_value"])
			{
				$request_action = (EMPTY($identification_info[0])) ? ACTION_ADD : ACTION_EDIT;
				$fields_identification                               = array();
				$sub_request_id                                      = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$request_action);
				$fields_identification['request_sub_id']             = $sub_request_id;
				$fields_identification['employee_id']                = $personal_info["employee_id"];
				$fields_identification['employee_identification_id'] = !EMPTY($identification_info[0]["employee_identification_id"]) ? $identification_info[0]["employee_identification_id"] : 0;
				$fields_identification['identification_value']       = $valid_data["tin_value"];
				$fields_identification['identification_value_old']   = $identification_info[0]["identification_value"];
				$fields_identification['identification_type_id']     = TIN_TYPE_ID;
				$fields_identification['identification_type_id_old'] = TIN_TYPE_ID;

				$this->pds->insert_general_data($table_identification,$fields_identification, FALSE);
			}

			if($params['sss_value']!=$identification_info[1]["identification_value"])
			{				
				$request_action = (EMPTY($identification_info[1])) ? ACTION_ADD : ACTION_EDIT;
				$fields_identification                               = array();
				$sub_request_id                                      = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$request_action);
				$fields_identification['request_sub_id']             = $sub_request_id;
				$fields_identification['employee_id']                = $personal_info["employee_id"];
				$fields_identification['employee_identification_id'] = !EMPTY($identification_info[1]["employee_identification_id"]) ? $identification_info[1]["employee_identification_id"] : 0;
				$fields_identification['identification_value']       = $valid_data["sss_value"];
				$fields_identification['identification_value_old']   = $identification_info[1]["identification_value"];
				$fields_identification['identification_type_id']     = SSS_TYPE_ID;
				$fields_identification['identification_type_id_old'] = SSS_TYPE_ID;

				$this->pds->insert_general_data($table_identification,$fields_identification, FALSE);
			}

			if($params['gsis_value']!=$identification_info[2]["identification_value"])
			{
				$request_action = (EMPTY($identification_info[2])) ? ACTION_ADD : ACTION_EDIT;
				$fields_identification                                   = array();
				$sub_request_id                                          = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$request_action);
				$fields_identification['request_sub_id']                 = $sub_request_id;
				$fields_identification['employee_id']                    = $personal_info["employee_id"];
				$fields_identification['employee_identification_id']     = !EMPTY($identification_info[2]["employee_identification_id"]) ? $identification_info[2]["employee_identification_id"] : 0;
				$fields_identification['identification_value']           = $valid_data["gsis_value"];
				$fields_identification['identification_value_old']       = $identification_info[2]["identification_value"];
				$fields_identification['identification_type_id']		 = GSIS_TYPE_ID;	
				$fields_identification['identification_type_id_old']     = GSIS_TYPE_ID;

				$this->pds->insert_general_data($table_identification,$fields_identification, FALSE);
			}			

			if($params['pagibig_value']!=$identification_info[3]["identification_value"])
			{
				$request_action = (EMPTY($identification_info[3])) ? ACTION_ADD : ACTION_EDIT;
				$fields_identification                                   = array();
				$sub_request_id                                          = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$request_action);
				$fields_identification['request_sub_id']                 = $sub_request_id;
				$fields_identification['employee_id']                    = $personal_info["employee_id"];
				$fields_identification['employee_identification_id']     = !EMPTY($identification_info[3]["employee_identification_id"]) ? $identification_info[3]["employee_identification_id"] : 0;
				$fields_identification['identification_value']           = $valid_data["pagibig_value"];
				$fields_identification['identification_value_old']       = $identification_info[3]["identification_value"];
				$fields_identification['identification_type_id']         = PAGIBIG_TYPE_ID;
				$fields_identification['identification_type_id_old']     = PAGIBIG_TYPE_ID;

				$this->pds->insert_general_data($table_identification,$fields_identification, FALSE);
			}

			if($params['philhealth_value']!=$identification_info[4]["identification_value"])
			{
				$request_action = (EMPTY($identification_info[4])) ? ACTION_ADD : ACTION_EDIT;
				$fields_identification                               = array();
				$sub_request_id                                      = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$request_action);
				$fields_identification['request_sub_id']             = $sub_request_id;
				$fields_identification['employee_id']                = $personal_info["employee_id"];
				$fields_identification['employee_identification_id'] = !EMPTY($identification_info[4]["employee_identification_id"]) ? $identification_info[4]["employee_identification_id"] : 0;
				$fields_identification['identification_value']       = $valid_data["philhealth_value"];
				$fields_identification['identification_value_old']   = $identification_info[4]["identification_value"];
				$fields_identification['identification_type_id']     = PHILHEALTH_TYPE_ID;
				$fields_identification['identification_type_id_old'] = PHILHEALTH_TYPE_ID;

				$this->pds->insert_general_data($table_identification,$fields_identification, FALSE);
			}
			//CONTACT INFORMATION
			$fields_contact = array();
			$table_contact  = $this->pds->tbl_requests_employee_contacts;

			if($params['telephone_residential_value']!=$contact_info[0]["contact_value"])
			{
				$request_action = (EMPTY($contact_info[0])) ? ACTION_ADD : ACTION_EDIT;
				$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$request_action);
				$fields_contact['request_sub_id']      = $sub_request_id;
				$fields_contact['employee_id']         = $personal_info["employee_id"];
				$fields_contact['employee_contact_id'] = !EMPTY($contact_info[0]["employee_contact_id"]) ? $contact_info[0]["employee_contact_id"] : 0;
				$fields_contact['contact_value']       = $valid_data["telephone_residential_value"];
				$fields_contact['contact_value_old']   = $contact_info[0]["contact_value"];
				$fields_contact['contact_type_id']     = RESIDENTIAL_NUMBER;
				$fields_contact['contact_type_id_old'] = RESIDENTIAL_NUMBER;
				$this->pds->insert_general_data($table_contact, $fields_contact, FALSE);
			}

			if($params['email_value']!=$contact_info[1]["contact_value"])
			{
				$request_action = (EMPTY($contact_info[1])) ? ACTION_ADD : ACTION_EDIT;
				$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$request_action);
				$fields_contact['request_sub_id']      = $sub_request_id;
				$fields_contact['employee_id']         = $personal_info["employee_id"];
				$fields_contact['employee_contact_id'] = !EMPTY($contact_info[1]["employee_contact_id"]) ? $contact_info[1]["employee_contact_id"] : 0;
				$fields_contact['contact_value']       = $valid_data["email_value"];
				$fields_contact['contact_value_old']   = $contact_info[1]["contact_value"];
				$fields_contact['contact_type_id']     = EMAIL;
				$fields_contact['contact_type_id_old'] = EMAIL;
				$this->pds->insert_general_data($table_contact, $fields_contact, FALSE);
			}

			if($params['telephone_permanent_value']!=$contact_info[2]["contact_value"])
			{
				$request_action = (EMPTY($contact_info[2])) ? ACTION_ADD : ACTION_EDIT;
				$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$request_action);
				$fields_contact['request_sub_id']      = $sub_request_id;
				$fields_contact['employee_id']         = $personal_info["employee_id"];
				$fields_contact['employee_contact_id'] = !EMPTY($contact_info[2]["employee_contact_id"]) ? $contact_info[2]["employee_contact_id"] : 0;
				$fields_contact['contact_value']       = $valid_data["telephone_permanent_value"];
				$fields_contact['contact_value_old']   = $contact_info[2]["contact_value"];
				$fields_contact['contact_type_id']     = PERMANENT_NUMBER;
				$fields_contact['contact_type_id_old'] = PERMANENT_NUMBER;
				$this->pds->insert_general_data($table_contact, $fields_contact, FALSE);
			}

			if($params['cellphone_value']!=$contact_info[3]["contact_value"])
			{
				$request_action = (EMPTY($contact_info[3])) ? ACTION_ADD : ACTION_EDIT;
				$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$request_action);
				$fields_contact['request_sub_id']      = $sub_request_id;
				$fields_contact['employee_id']         = $personal_info["employee_id"];
				$fields_contact['employee_contact_id'] = !EMPTY($contact_info[3]["employee_contact_id"]) ? $contact_info[3]["employee_contact_id"] : 0;
				$fields_contact['contact_value']       = $valid_data["cellphone_value"];
				$fields_contact['contact_value_old']   = $contact_info[3]["contact_value"];
				$fields_contact['contact_type_id']     = MOBILE_NUMBER;
				$fields_contact['contact_type_id_old'] = MOBILE_NUMBER;
				$this->pds->insert_general_data($table_contact, $fields_contact, FALSE);
			}

			//ADDRESS INFORMATION 
			$fields_address    = array();
			$table_address     = $this->pds->tbl_requests_employee_addresses;
		
			$residential_address_value = $params['residential_house_num']. '|'. $params['residential_street_name']. '|'. $params['residential_subdivision_name'];

			if(($params['barangay_residential']!=$address_info[0]['barangay_code']) OR ($residential_address_value!=$address_info[0]["address_value"]) OR ($params["residential_zip_code"]!=$address_info[0]["postal_number"]))
			{
				$request_action = (EMPTY($address_info[0])) ? ACTION_ADD : ACTION_EDIT;
				$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_ADDRESS_INFO,$personal_info["employee_id"],$request_action);
				$fields_address['request_sub_id']      = $sub_request_id;
				$fields_address['employee_id']         = $personal_info["employee_id"];
				$fields_address['employee_address_id'] = !EMPTY($address_info[0]["employee_address_id"]) ? $address_info[0]["employee_address_id"] : 0;				
				$fields_address['barangay_code']       = $params['barangay_residential'];
				$fields_address['municity_code']       = $params['municipalities_residential'];
				$fields_address['province_code']       = $params['province_residential'];
				$fields_address['region_code']         = $params['region_residential'];
				$fields_address['address_type_id']     = RESIDENTIAL_ADDRESS;
				$fields_address['barangay_code_old']   = $address_info[0]['barangay_code'];
				$fields_address['municity_code_old']   = $address_info[0]['municity_code'];
				$fields_address['province_code_old']   = $address_info[0]['province_code'];
				$fields_address['region_code_old']     = $address_info[0]['region_code'];
				$fields_address['address_value']       = $residential_address_value;	
				$fields_address['address_value_old']   = $address_info["residential_address_value"];
				$fields_address['postal_number']       = $valid_data["residential_zip_code"];	
				$fields_address['postal_number_old']   = $address_info[0]["postal_number"];
				$fields_address['address_type_id_old'] = RESIDENTIAL_ADDRESS;

				$this->pds->insert_general_data($table_address, $fields_address, FALSE);
			}

			$permanent_address_value = $params['permanent_house_num']. '|'. $params['permanent_street_name']. '|'. $params['permanent_subdivision_name'];

			if(($params['barangay_permanent']!=$address_info[1]['barangay_code']) OR ($permanent_address_value!=$address_info[1]["address_value"]) OR ($params["permanent_zip_code"]!=$address_info[1]["postal_number"]))
			{
				$request_action = (EMPTY($address_info[0])) ? ACTION_ADD : ACTION_EDIT;
				$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_ADDRESS_INFO,$personal_info["employee_id"],$request_action);
				$fields_address['request_sub_id']      = $sub_request_id;
				$fields_address['employee_id']         = $personal_info["employee_id"];
				$fields_address['employee_address_id'] = !EMPTY($address_info[1]["employee_address_id"]) ? $address_info[1]["employee_address_id"] : 0;				
				$fields_address['barangay_code']       = $params['barangay_permanent'];
				$fields_address['municity_code']       = $params['municipalities_permanent'];
				$fields_address['province_code']       = $params['province_permanent'];
				$fields_address['region_code']         = $params['region_permanent'];
				$fields_address['address_type_id']     = PERMANENT_ADDRESS;
				$fields_address['barangay_code_old']   = $address_info[1]['barangay_code'];
				$fields_address['municity_code_old']   = $address_info[1]['municity_code'];
				$fields_address['province_code_old']   = $address_info[1]['province_code'];
				$fields_address['region_code_old']     = $address_info[1]['region_code'];
				$fields_address['address_value_old']   = $address_info["permanent_address_value"];
				$fields_address['address_value']       = $permanent_address_value;
				$fields_address['postal_number']       = $valid_data["permanent_zip_code"];	
				$fields_address['postal_number_old']   = $address_info[1]["postal_number"];
				$fields_address['address_type_id_old'] = PERMANENT_ADDRESS;

				$this->pds->insert_general_data($table_address, $fields_address, FALSE);
			}
			
			$audit_table[]			= $this->pds->tbl_requests_employee_personal_info;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_UPDATE;

			$reload_url     		= ACTION_EDIT."/".$id ."/".$token."/".$salt."/".$module;
			$activity 				= "Personal Info record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			
			$status = true;
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
	
		$data					= array();
		$data['reload_url'] 	= $reload_url;
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}
	private function _validate_peronal_info($params)
	{
		try
		{
			$fields 							   = array();
			$fields['agency_employee_id']          = "Employee Number";
			$fields['biometric_pin']               = "Biometric Pin";
			$fields['first_name']                  = "First Name";
			$fields['last_name']                   = "Last Name";
			$fields['middle_name']                 = "Middle Name";
			$fields['birth_date']                  = "Birth Date";
			$fields['birth_place']                 = "Birth Place";
			$fields['gender']                      = "Gender";
			$fields['civil_status']                = "Civil Status";
			$fields['citizenships']                = "Citizenship";
			if ($params['citizenships'] != CITIZENSHIP_FILIPINO )
				$fields['citizenship_basis']       = "Basis of Citizenship";
			$fields['height']                      = "Height";
			$fields['weight']                      = "Weight";
			$fields['blood_type']                  = "Blood Type";
			$fields['tin_value']                   = "TIN Number";

			$fields['province_residential']        = "Residential Province";
			$fields['municipalities_residential']  = "Residential Municipality";
			$fields['barangay_residential']        = "Residential Barangay";
			$fields['residential_house_num']       = "Residential House/ Block/ Lot Number";
			$fields['residential_street_name']     = "Residential Street";
			$fields['residential_subdivision_name']= "Residential Subdivision/ Village";
			$fields['residential_zip_code']        = "Residential Zip Code";
			$fields['province_permanent']          = "Permanent Province";
			$fields['municipalities_permanent']    = "Permanent Municipality";
			$fields['barangay_permanent']          = "Permanent Barangay";
			$fields['permanent_house_num']         = "Permanent House/ Block/ Lot Number";
			$fields['permanent_street_name']       = "Permanent Street";
			$fields['permanent_subdivision_name']  = "Permanent Subdivision/ Village";
			$fields['permanent_zip_code']          = "Permanent Zip Code";
			// $fields['residential_address_value']   = "Residential Address";
			// $fields['permanent_address_value']     = "Permanent Address";
			// $fields['residential_zip_code']        = "Residential Zip Code";
        	// $fields['permanent_zip_code']          = "Permanent Zip Code";
			// $param['municipality_residential']     = "Residential Municipality";
			// $param['municipality_permanent']       = "Permanent Municipality";

			if(EMPTY($params['gsis_value']))
			{
				throw new Exception('<b>GSIS Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['pagibig_value']))
			{
				throw new Exception('<b>Pag-IBIG Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['philhealth_value']))
			{
				throw new Exception('<b>Philhealth Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['sss_value']))
			{
				throw new Exception('<b>SSS Number</b>' . $this->lang->line('not_applicable'));
			}
			//NCOCAMPO: NOTIFICATION ADDED 10/25/2023 :START
			if(EMPTY($params['cellphone_value']))
			{
				throw new Exception('<b>Cellphone Number</b>' . $this->lang->line('not_applicable'));
			}
			//NCOCAMPO: NOTIFICATION ADDED 10/25/2023 :END
			/*if(EMPTY($params['telephone_residential_value']))
			{
				throw new Exception('<b>Residential Telephone Number</b>' . $this->lang->line('not_applicable'));
			}
			if(EMPTY($params['telephone_permanent_value']))
			{
				throw new Exception('<b>Permanent Telephone Number</b>' . $this->lang->line('not_applicable'));
			}*/

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_peronal_info($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_peronal_info($params)
	{
		try
		{
				// CHECK "NA" OR "N/A" FOR IDENTIFICATION NUMBERS
			$gsis_str 		= str_replace("-", "", $params['gsis_value']);
			$gsis_valid 	= $this->check_na($gsis_str);
			if(!$gsis_valid)
			{
				throw new Exception('Invalid input in <b>GSIS Number</b>.');
			}
			
			$sss_str 		= str_replace("-", "", $params['sss_value']);
			$sss_valid		= $this->check_na($sss_str);
			if(!$sss_valid)
			{
				throw new Exception('Invalid input in <b>SSS Number</b>.');
			}

			$phil_str 		= str_replace("-", "", $params['philhealth_value']);
			$phil_valid		= $this->check_na($phil_str);
			if(!$phil_valid)
			{
				throw new Exception('Invalid input in <b>Philhealth Number</b>.');
			}

			$pagibig_str 		= str_replace("-", "", $params['pagibig_value']);
			$pagibig_valid		= $this->check_na($pagibig_str);
			if(!$pagibig_valid)
			{
				throw new Exception('Invalid input in <b>Pag-IBIG Number</b>.');
			}

			//NCOCAMPO:CHECK "NA" OR "N/A" FOR CONTACT NUMBERS 10/25/2023 :START
			$cellphone_number_str 		= str_replace("-", "", $params['cellphone_value']);
			$cellphone_number_valid		= $this->check_na($cellphone_number_str);
			if(!$cellphone_number_valid)
			{
				throw new Exception('Invalid input in <b>Cellphone Number</b>.');
			}
			//NCOCAMPO:CHECK "NA" OR "N/A" FOR CONTACT NUMBERS 10/25/2023 :END	

			/*$perm_tel_str 		= str_replace("-", "", $params['telephone_permanent_value']);
			$perm_tel_valid		= $this->check_na($perm_tel_str);
			if(!$perm_tel_valid)
			{
				throw new Exception('Invalid input in <b>Permanent Telephone Number</b>.');
			}

			$perm_res_str 		= str_replace("-", "", $params['telephone_residential_value']);
			$perm_res_valid		= $this->check_na($perm_res_str);
			if(!$perm_res_valid)
			{
				throw new Exception('Invalid input in <b>Residential Telephone Number</b>.');
			}*/
			
			$validation['agency_employee_id'] = array(
					'data_type' => 'string',
					'name'		=> 'Employee Number',
					'max_len'	=> 8,
					'min_len'	=> 8
			);
			$validation['biometric_pin'] = array(
					'data_type' => 'string',
					'name'		=> 'Biometric Pin',
					'max_len'	=> 8,
					'min_len'	=> 8
			);
			$validation['first_name'] = array(
					'data_type' => 'string',
					'name'		=> 'First Name',
					'max_len'	=> 50
			);
			$validation['last_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Last Name',
					'max_len'	=> 50
			);
			$validation['middle_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Middle Name',
					'max_len'	=> 50
			);	
			$validation['ext_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Last Name',
					'max_len'	=> 50
			);	
			$validation['birth_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Birth Date',
					'max_date'	=> date("Y/m/d")
			);
			$validation['birth_place'] = array(
					'data_type' => 'string',
					'name'		=> 'Birth Place',
					'max_len'	=> 100
			);	
			$validation['civil_status'] = array(
					'data_type' => 'digit',
					'name'		=> 'Civil Status'
			);	
			$validation['citizenships'] = array(
					'data_type' => 'digit',
					'name'		=> 'Citizenship'
			);			
			$validation['citizenship_basis'] = array(
					'data_type' => 'digit',
					'name'		=> 'Basis of Citizenship'
			);	
			$validation['height'] = array(
					'data_type' => 'amount',
					'name'		=> 'Height',
					'decimal'	=> 2
			);	
			$validation['weight'] = array(
					'data_type' => 'amount',
					'name'		=> 'Weight',
					'decimal'	=> 2
			);	
			$validation['blood_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Blood Type'
			);
			$validation['gender'] = array(
					'data_type' => 'string',
					'name'		=> 'Gender',
					'max_len'	=> 1
			);
			
			// CHECK IF IDENTIFICATION VALUES IS NUMERIC AND ADD MINIMUM LENGTH
			$validation['gsis_value'] = array(
					'data_type' => 'string',
					'name'		=> 'GSIS Number',
			);
			if(is_numeric($gsis_str))
			{
				$validation['gsis_value']['min_len']	= 8;
			}
			
			$validation['pagibig_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Pag-ibig Number',
			);			
			if(is_numeric($pagibig_str))
			{
				$validation['pagibig_value']['min_len']	= 12;
			}

			$validation['philhealth_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Philhealth Number',
			);		
			if(is_numeric($phil_str))
			{
				$validation['philhealth_value']['min_len']	= 12;
			}			
			
			$validation['sss_value'] = array(
					'data_type' => 'string',
					'name'		=> 'SSS Number',
			);
			if(is_numeric($sss_str))
			{
				$validation['sss_value']['min_len']	= 10;
			}
			
			$validation['tin_value'] = array(
					'data_type' => 'string',
					'name'		=> 'TIN Number',
					'min_len'	=> 12
			);

			//NCOCAMPO: ADDED CELLPHONE NUMBER VALIDATION 10/25/2023 :START
			$validation['cellphone_value'] = array(
				'data_type' => 'string',
				'name'		=> 'Cellphone Number',
			);
			if(is_numeric($cellphone_number_str))
			{
				$validation['cellphone_value']['min_len']	= 11;
			}
			//NCOCAMPO:ADDED CELLPHONE NUMBER VALIDATION 10/25/2023 :END	


				/*$validation['tin_value'] = array(
					'data_type' => 'string',
					'name'		=> 'TIN Number',
					'min_len'	=> 12
				);*/
				// $validation['residential_address_value'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Address',
				// 	'max_len'	=> 100
				// );
				// $validation['municipality_residential'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Municipality',
				// 	'max_len'	=> 100
				// );
				// $validation['residential_zip_code'] = array(
				// 	'data_type' => 'digit',
				// 	'name'		=> 'Residential Zip Code',
				// 	'max_len'	=> 6
				// );
				// $validation['telephone_residential_value'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Telephone Number',
				// 	'max_len'	=> 15
				// );
				// $validation['permanent_address_value'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Permanent Address',
				// 	'max_len'	=> 100
				// );
				// $validation['municipality_permanent'] = array(
				// 	'data_type' => 'string',
				// 	'name'		=> 'Residential Municipality',
				// 	'max_len'	=> 100
				// );
				$validation['permanent_house_num'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent House/ Block/ Lot Number',
					'max_len'	=> 100
				);
				$validation['permanent_street_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent Street',
					'max_len'	=> 100
				);
				$validation['permanent_subdivision_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent Subdivision/ Village',
					'max_len'	=> 100
				);
				$validation['permanent_zip_code'] = array(
					'data_type' => 'digit',
					'name'		=> 'Permanent Zip Code',
					'max_len'	=> 6
				);
				$validation['residential_house_num'] = array(
					'data_type' => 'string',
					'name'		=> 'Residential House/ Block/ Lot Number',
					'max_len'	=> 100
				);
				$validation['residential_street_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Residential Street',
					'max_len'	=> 100
				);
				$validation['residential_subdivision_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Residential Subdivision/ Village',
					'max_len'	=> 100
				);
				$validation['residential_zip_code'] = array(
					'data_type' => 'digit',
					'name'		=> 'Residential Zip Code',
					'max_len'	=> 6
				);

			$validation['telephone_permanent_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Permanent Telephone Number',
					'max_len'	=> 15
			);

			if(!EMPTY($param['email_value'])):
				$validation['email_value'] = array(
						'data_type' => 'email',
						'name'		=> 'Email Address',
						'max_len'	=> 15
				);
			else:
				$validation['email_value'] = array(
						'data_type' => 'string',
						'name'		=> 'Email Address',
				);
			endif;

			$validation['cellphone_value'] = array(
					'data_type' => 'string',
					'name'		=> 'Cellphone Number',
					'max_len'	=> 15
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	/*PROCESS IDENTIFICATION*/
	public function process_identification()
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
			// GETTING THE REAL VALUE OF AN IDENTIFICATION FROM THE VALUE WITH FORMATTING
			$value_arr                        = explode('|', $params['identification_type_id']);
			$concat                           = explode('x', $value_arr[1]);
			$separator                        = $concat[count($concat)-1];
			$params['identification_value']   = str_replace($separator, '', $params['identification_value']);
			$params['identification_type_id'] = $value_arr[0];
			
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_identification($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id		= $this->session->userdata("pds_employee_id");
			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_identifications;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_identification_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 							  = array() ;
			$fields['request_sub_id']			  = $sub_request_id;
			$fields['employee_identification_id'] = 0;
			$fields['identification_type_id']	  = $valid_data["identification_type_id"];
			$fields['identification_value']		  = $valid_data["identification_value"];
			$fields['employee_id']				  = $personal_info["employee_id"];

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                                = array("*") ;
				$table                                = $this->pds->tbl_employee_identifications;
				$where                                = array();
				$key                                  = $this->get_hash_key('employee_identification_id');
				$where[$key]                          = $id;
				$identification                       = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_identification_id'] = $identification['employee_identification_id'];
				$fields['identification_type_id_old'] = $identification["identification_type_id"];
				$fields['identification_value_old']   = $identification["identification_value"];
			}

			$table 					= $this->pds->tbl_requests_employee_identifications;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_identifications;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Indentification record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_identification($params)
	{
		try
		{
			$fields 							= array();
			$fields['identification_type_id']	= "Identification Type";
			$fields['identification_value']		= "Identification Number";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_identification($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_input_identification($params)
	{
		try
		{			
			$validation['identification_value'] = array(
					'data_type' 				=> 'string',
					'name'						=> 'Identification Number',
					'max_len'					=> 50
			);
			$validation['identification_type_id'] = array(
					'data_type' 				  => 'digit',
					'name'						  => 'Identification Type'
			);	

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_identification()
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

			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_identifications;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_identification_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field                                = array("*") ;
			$table                                = $this->pds->tbl_employee_personal_info;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_id');
			$where[$key]                          = $pds_employee_id;
			$personal_info                        = $this->pds->get_general_data($field, $table, $where, FALSE);			
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id                       = $this->insert_sub_request(TYPE_REQUEST_PDS_IDENTIFICATION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                                = array("*") ;
			$table                                = $this->pds->tbl_employee_identifications;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_identification_id');
			$where[$key]                          = $id;
			$identification                       = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                               = array() ;
			$fields['request_sub_id']             = $sub_request_id;
			$fields['employee_identification_id'] = $identification['employee_identification_id'];
			$fields['identification_type_id_old'] = $identification["identification_type_id"];
			$fields['identification_value_old']   = $identification["identification_value"];
			$fields['employee_id']                = $personal_info["employee_id"];

			$table                                = $this->pds->tbl_requests_employee_identifications;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                        = $this->pds->tbl_requests_employee_identifications;
			$audit_schema[]                       = DB_MAIN;
			$prev_detail[]                        = array();
			$curr_detail[]                        = array($fields);
			$audit_action[]                       = AUDIT_INSERT;
			$activity                             = "Indentification record changes request of %s has been added.";
			$audit_activity                       = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg                                  = $this->lang->line('save_record_changes');
			$flag                                 = 1;
		}
		
		catch(PDOException $e){
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($msg);
			//$message = $this->lang->line('data_not_saved');
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
			"table_id" 				=> 'identification_table',
			"path"					=> PROJECT_MAIN . '/pds_identification_info/get_identification_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS FAMILY*/
	public function process_family()
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

			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_relations;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_relation_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_FAMILY_INFO,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields                                  = array() ;
			$audit_fields                            = array() ;
			$fields['request_sub_id']                = $sub_request_id;
			$fields['employee_id']                   = $personal_info["employee_id"];
			$fields['employee_relation_id']          = 0;
			$fields['relation_last_name']            = $valid_data["relation_last_name"];
			$fields['relation_first_name']           = $valid_data["relation_first_name"];
			$fields['relation_middle_name']          = $valid_data["relation_middle_name"];
			$fields['relation_type_id']              = $valid_data["relation_type"];
			$fields['relation_birth_date']           = $valid_data["relation_birth_date"];
			
			//MARVIN : DISABLE DUE TO PRIVACY ACT
			// $fields['relation_gender_code']          = $valid_data["gender"];
			
			$fields['relation_occupation']           = !EMPTY($valid_data["relation_occupation"]) ? $valid_data["relation_occupation"] : NULL;
			$fields['relation_contact_num']          = !EMPTY($valid_data["relation_contact_num"]) ? str_replace('-', '', $valid_data['relation_contact_num']) : NULL;
			$fields['relation_company']              = !EMPTY($valid_data["relation_company"]) ? $valid_data["relation_company"] : NULL;
			$fields['relation_company_address']      = !EMPTY($valid_data["relation_company_address"]) ? $valid_data["relation_company_address"] : NULL;
			$fields['relation_employment_status_id'] = !EMPTY($valid_data["employment_status"]) ? $valid_data["employment_status"] : NULL;
			$fields['relation_ext_name']             = !EMPTY($valid_data["ext_name"]) ? $valid_data["ext_name"] : NULL;
			$fields['relation_civil_status_id']      = !EMPTY($valid_data["civil_status"]) ? $valid_data["civil_status"] : NULL;
			$fields['deceased_flag']                 = $valid_data['deceased'];			
			$fields['pwd_flag']                      = EMPTY($valid_data["disable_flag"]) ? 'N':'Y';
			$fields['death_date']                    = ($valid_data["deceased"] == 'Y' AND !EMPTY($valid_data["death_date"])) ? $valid_data["death_date"] : NULL;

			// SET FIELDS TO AUDIT TRAIL			
			$audit_fields['request_sub_id']                = $sub_request_id;
			$audit_fields['employee_id']                   = $personal_info["employee_id"];
			$audit_fields['employee_relation_id']          = 0;
			$audit_fields['relation_last_name']            = $valid_data["relation_last_name"];
			$audit_fields['relation_first_name']           = $valid_data["relation_first_name"];
			$audit_fields['relation_middle_name']          = $valid_data["relation_middle_name"];
			$audit_fields['relation_type_id']              = $valid_data["relation_type"];
			$audit_fields['relation_birth_date']           = $valid_data["relation_birth_date"];
			
			//MARVIN : DISABLE DUE TO PRIVACY ACT
			// $audit_fields['relation_gender_code']          = $valid_data["gender"];
			
			$audit_fields['relation_occupation']           = !EMPTY($valid_data["relation_occupation"]) ? $valid_data["relation_occupation"] : ' ';
			$audit_fields['relation_contact_num']          = !EMPTY($valid_data["relation_contact_num"]) ? str_replace('-', '', $valid_data['relation_contact_num']) : ' ';
			$audit_fields['relation_company']              = !EMPTY($valid_data["relation_company"]) ? $valid_data["relation_company"] : ' ';
			$audit_fields['relation_company_address']      = !EMPTY($valid_data["relation_company_address"]) ? $valid_data["relation_company_address"] : ' ';
			$audit_fields['relation_employment_status_id'] = !EMPTY($valid_data["employment_status"]) ? $valid_data["employment_status"] : ' ';
			$audit_fields['relation_ext_name']             = !EMPTY($valid_data["ext_name"]) ? $valid_data["ext_name"] : ' ';
			$audit_fields['relation_civil_status_id']      = !EMPTY($valid_data["civil_status"]) ? $valid_data["civil_status"] : ' ';
			$audit_fields['deceased_flag']                 = $valid_data['deceased'];		
			$audit_fields['pwd_flag']                      = EMPTY($valid_data["disable_flag"]) ? 'N':'Y';
			$audit_fields['death_date']                    = ($valid_data["deceased"] == 'Y' AND !EMPTY($valid_data["death_date"])) ? $valid_data["death_date"] : ' ';

			if(!EMPTY($valid_data["death_date"]))
			{
				if($valid_data['death_date'] < $valid_data['relation_birth_date'])
				{
					throw new Exception('<b>Date of Death</b> should not be earlier than <b>Date of Birth</b>.');
				}
			}

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                                       = array("*") ;
				$table                                       = $this->pds->tbl_employee_relations;
				$where                                       = array();
				$key                                         = $this->get_hash_key('employee_relation_id');
				$where[$key]                                 = $id;
				$relation                                    = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_relation_id']              = $relation['employee_relation_id'];
				$fields['relation_last_name_old']            = $relation["relation_last_name"];
				$fields['relation_first_name_old']           = $relation["relation_first_name"];
				$fields['relation_middle_name_old']          = $relation["relation_middle_name"];
				$fields['relation_type_id_old']              = $relation["relation_type_id"];
				$fields['relation_birth_date_old']           = $relation["relation_birth_date"];
				$fields['relation_gender_code_old']          = $relation["relation_gender_code"];
				$fields['relation_occupation_old']           = !EMPTY($relation["relation_occupation"]) ? $relation["relation_occupation"] : NULL;
				$fields['relation_contact_num_old']          = !EMPTY($relation["relation_contact_num"]) ? $relation["relation_contact_num"] : NULL;
				$fields['relation_company_old']              = !EMPTY($relation["relation_company"]) ? $relation["relation_company"] : NULL;
				$fields['relation_company_address_old']      = !EMPTY($relation["relation_company_address"]) ? $relation["relation_company_address"] : NULL;
				$fields['relation_employment_status_id_old'] = !EMPTY($relation["relation_employment_status_id"]) ? $relation["relation_employment_status_id"] : NULL;
				$fields['relation_ext_name_old']             = !EMPTY($relation["relation_ext_name"]) ? $relation["relation_ext_name"] : NULL;
				$fields['relation_civil_status_id_old']      = !EMPTY($relation["relation_civil_status_id"]) ? $relation["relation_civil_status_id"] : NULL;
				$fields['deceased_flag_old']                 = $relation["deceased_flag"];			
				$fields['death_date_old']                    = !EMPTY($relation["death_date"]) ? $relation["death_date"] : NULL;
				$fields['pwd_flag_old']                      = $relation["pwd_flag"];   

				// SET FIELDS TO AUDIT TRAIL
				$audit_fields['employee_relation_id']        	   = $relation['employee_relation_id'];
				$audit_fields['relation_last_name_old']            = $relation["relation_last_name"];
				$audit_fields['relation_first_name_old']           = $relation["relation_first_name"];
				$audit_fields['relation_middle_name_old']          = $relation["relation_middle_name"];
				$audit_fields['relation_type_id_old']              = $relation["relation_type_id"];
				$audit_fields['relation_birth_date_old']           = $relation["relation_birth_date"];
				$audit_fields['relation_gender_code_old']          = $relation["relation_gender_code"];
				$audit_fields['relation_occupation_old']           = !EMPTY($relation["relation_occupation"]) ? $relation["relation_occupation"] : ' ';
				$audit_fields['relation_contact_num_old']          = !EMPTY($relation["relation_contact_num"]) ? $relation["relation_contact_num"] : ' ';
				$audit_fields['relation_company_old']              = !EMPTY($relation["relation_company"]) ? $relation["relation_company"] : ' ';
				$audit_fields['relation_company_address_old']      = !EMPTY($relation["relation_company_address"]) ? $relation["relation_company_address"] : ' ';
				$audit_fields['relation_employment_status_id_old'] = !EMPTY($relation["relation_employment_status_id"]) ? $relation["relation_employment_status_id"] : ' ';
				$audit_fields['relation_ext_name_old']             = !EMPTY($relation["relation_ext_name"]) ? $relation["relation_ext_name"] : ' ';
				$audit_fields['relation_civil_status_id_old']      = !EMPTY($relation["relation_civil_status_id"]) ? $relation["relation_civil_status_id"] : ' ';
				$audit_fields['deceased_flag_old']                 = $relation["deceased_flag"];			
				$audit_fields['death_date_old']                    = !EMPTY($relation["death_date"]) ? $relation["death_date"] : ' ';
				$audit_fields['pwd_flag_old']                      = $relation["pwd_flag"];  
			}

			$table 					= $this->pds->tbl_requests_employee_relations;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_relations;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($audit_fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Family record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
			
			//MARVIN : DISABLE DUE TO PRIVACY ACT
			// $fields['relation_birth_date']	= "Birth Date";
			// if ($params['deceased'] == 'N') 
			// {
				// $fields['employment_status']= "Employment Status";
				// $fields['civil_status']		= "Civil Status";
			// } 
			// if ($params['deceased'] == 'Y') 
			// {
				// $fields['death_date']		= "Date of Death";
			// }
			
			//MARVIN : ENABLE BIRTHDATE FOR CHILD RELATION
			if($params['relation_type'] == 7)
			{
				$fields['relation_birth_date']	= "Birth Date";
			}
			
			//MARVIN : DEFAULT VALUE FOR DECEASED
			$params['deceased'] = 'N';

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
			
			//MARVIN : ENABLE BIRTHDATE FOR CHILD RELATION
			if($params['relation_type'] == 7)
			{
				$validation['relation_birth_date'] = array(
						'data_type' => 'date',
						'name'		=> 'Birth Date',
						'max_date'	=> date("Y/m/d")
				);
			}
			
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_relations;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_relation_id", $id);

			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_FAMILY_INFO,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                                  = array("*") ;
			$table                                  = $this->pds->tbl_employee_relations;
			$where                                  = array();
			$key                                    = $this->get_hash_key('employee_relation_id');
			$where[$key]                            = $id;
			$relation                               = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                                 	 = array() ;
			$fields['request_sub_id']               	 = $sub_request_id;
			//$fields['action']                     	 = $action;
			$fields['employee_id']                  	 = $personal_info["employee_id"];
			$fields['employee_relation_id']              = $relation['employee_relation_id'];
			$fields['relation_last_name_old']            = $relation["relation_last_name"];
			$fields['relation_first_name_old']           = $relation["relation_first_name"];
			$fields['relation_middle_name_old']          = $relation["relation_middle_name"];
			$fields['relation_type_id_old']              = $relation["relation_type_id"];
			$fields['relation_birth_date_old']           = $relation["relation_birth_date"];
			$fields['relation_occupation_old']           = $relation["relation_occupation"];
			$fields['relation_contact_num_old']          = $relation["relation_contact_num"];
			$fields['relation_company_old']              = $relation["relation_company"];
			$fields['relation_company_address_old']      = $relation["relation_company_address"];
			$fields['relation_employment_status_id_old'] = $relation["relation_employment_status_id"];
			$fields['relation_ext_name_old']             = $relation["relation_ext_name"];
			$fields['relation_gender_code_old']          = $relation["relation_gender_code"];
			$fields['relation_civil_status_id_old']      = $relation["relation_civil_status_id"];
			$fields['deceased_flag_old']                 = !EMPTY($relation["deceased_flag"]) ? $relation["deceased_flag"] : NULL;
			$fields['death_date_old']                    = $relation["death_date"];
			$fields['pwd_flag_old']                      = $relation["pwd_flag"];  

			// SET FIELDS TO AUDIT TRAIL
			$audit_fields                                 	   = array();
			$audit_fields['request_sub_id']               	   = $sub_request_id;
			$audit_fields['employee_id']                  	   = $personal_info["employee_id"];
			$audit_fields['employee_relation_id']        	   = $relation['employee_relation_id'];
			$audit_fields['relation_last_name_old']            = $relation["relation_last_name"];
			$audit_fields['relation_first_name_old']           = $relation["relation_first_name"];
			$audit_fields['relation_middle_name_old']          = $relation["relation_middle_name"];
			$audit_fields['relation_type_id_old']              = $relation["relation_type_id"];
			$audit_fields['relation_birth_date_old']           = $relation["relation_birth_date"];
			$audit_fields['relation_gender_code_old']          = $relation["relation_gender_code"];
			$audit_fields['relation_occupation_old']           = !EMPTY($relation["relation_occupation"]) ? $relation["relation_occupation"] : ' ';
			$audit_fields['relation_contact_num_old']          = !EMPTY($relation["relation_contact_num"]) ? $relation["relation_contact_num"] : ' ';
			$audit_fields['relation_company_old']              = !EMPTY($relation["relation_company"]) ? $relation["relation_company"] : ' ';
			$audit_fields['relation_company_address_old']      = !EMPTY($relation["relation_company_address"]) ? $relation["relation_company_address"] : ' ';
			$audit_fields['relation_employment_status_id_old'] = !EMPTY($relation["relation_employment_status_id"]) ? $relation["relation_employment_status_id"] : ' ';
			$audit_fields['relation_ext_name_old']             = !EMPTY($relation["relation_ext_name"]) ? $relation["relation_ext_name"] : ' ';
			$audit_fields['relation_civil_status_id_old']      = !EMPTY($relation["relation_civil_status_id"]) ? $relation["relation_civil_status_id"] : ' ';
			$audit_fields['deceased_flag_old']                 = !EMPTY($relation["deceased_flag"]) ? $relation["deceased_flag"] : ' ';		
			$audit_fields['death_date_old']                    = !EMPTY($relation["death_date"]) ? $relation["death_date"] : ' ';
			$audit_fields['pwd_flag_old']                      = $relation["pwd_flag"];  
			
			$table                      = $this->pds->tbl_requests_employee_relations;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]				= $this->pds->tbl_requests_employee_relations;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array();
			$curr_detail[]				= array($audit_fields);
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
			"table_id" 				=> 'pds_family_table',
			"path"					=> PROJECT_MAIN . '/pds_family_info/get_family_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS EDUCATION*/
	public function process_education()
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

			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_educations;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_education_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_EDUCATION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields                         = array();	 		
			$fields['request_sub_id']		= $sub_request_id;
			$fields['employee_id']          = $personal_info["employee_id"];			
			$fields['employee_education_id']= 0;
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

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                               = array("*") ;
				$table                               = $this->pds->tbl_employee_educations;
				$where                               = array();
				$key                                 = $this->get_hash_key('employee_education_id');
				$where[$key]                         = $id;
				$education                           = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_education_id'] 	 = $education['employee_education_id'];
				$fields['educational_level_id_old']  = $education["educational_level_id"];
				$fields['school_id_old']             = $education["school_id"];
				$fields['start_year_old']            = $education["start_year"];
				$fields['end_year_old']              = $education["end_year"];
				$fields['highest_level_old']         = $education["highest_level"];
				$fields['academic_honor_old']     	 = $education["academic_honor"];
				$fields['education_degree_id_old']   = $education["education_degree_id"];
				$fields['year_graduated_flag_old']   = $education["year_graduated_flag"];
				$fields['relevance_flag_old']	     = $education["relevance_flag"];
			}

			$table          = $this->pds->tbl_requests_employee_educations;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]  = $this->pds->tbl_requests_employee_educations;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array();
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_INSERT;	
			
			$activity       = "Education record changes request of %s has been added.";
			$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
			$fields 					= array();
			$fields['level']			= "Level";
			$fields['school_name']		= "Name of School";
			$fields['start_year']		= "Year Started";
			//$fields['degree_course']	= "Degree/Course";

			if ($params['year_graduated_flag'] == 'Y')
			{
				$fields['end_year']			= "Year Graduated";
			} 
			else 
			{
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
					'name'		=> 'Name of School'
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
					'name'		=> 'Degree/Course'
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_educations;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_education_id", $id);

			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field                              = array("*") ;
			$table                              = $this->pds->tbl_employee_personal_info;
			$where                              = array();
			$key                                = $this->get_hash_key('employee_id');
			$where[$key]                        = $pds_employee_id;
			$personal_info                      = $this->pds->get_general_data($field, $table, $where, FALSE);			
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id                     = $this->insert_sub_request(TYPE_REQUEST_PDS_EDUCATION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                              = array("*") ;
			$table                              = $this->pds->tbl_employee_educations;
			$where                              = array();
			$key                                = $this->get_hash_key('employee_education_id');
			$where[$key]                        = $id;
			$education                          = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                              = array() ;
			$fields['request_sub_id']            = $sub_request_id;
			$fields['employee_id']               = $personal_info["employee_id"];
			$fields['employee_education_id'] 	 = $education['employee_education_id'];
			$fields['educational_level_id_old']  = $education["educational_level_id"];
			$fields['school_id_old']             = $education["school_id"];
			$fields['start_year_old']            = $education["start_year"];
			$fields['end_year_old']              = $education["end_year"];
			$fields['highest_level_old']         = $education["highest_level"];
			$fields['academic_honor_old']     	 = $education["academic_honor"];
			$fields['education_degree_id_old']   = $education["education_degree_id"];
			$fields['year_graduated_flag_old']   = $education["year_graduated_flag"];
			$fields['relevance_flag_old']   	 = $education["relevance_flag"];

			$table                              = $this->pds->tbl_requests_employee_educations;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                      = $this->pds->tbl_requests_employee_educations;
			$audit_schema[]                     = DB_MAIN;
			$prev_detail[]                      = array();
			$curr_detail[]                      = array($fields);
			$audit_action[]                     = AUDIT_INSERT;
			$activity                           = "Education record changes request of %s has been added.";
			$audit_activity                     = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
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
			"table_id" 				=> 'pds_education_table',
			"path"					=> PROJECT_MAIN . '/pds_education_info/get_education_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}
	/*PROCESS ADDRESS*/
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
			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_addresses;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_address_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_ADDRESS_INFO,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$str_residential   				= $params["municipality_residential"];
			$codes_residential 				= explode(' ',$str_residential);

			$fields 						= array();
			$fields['request_sub_id']  		= $sub_request_id;
			$fields['employee_id']     		= $personal_info["employee_id"];
			$fields['employee_address_id'] 	= 0;
			$fields['address_type_id'] 		= $valid_data["address_type_id"];
			$fields['postal_number']   		= $valid_data["postal_number"];
			$fields['address_value']   		= $valid_data["address_value"];
			$fields['barangay_code']   		= $codes_residential[0];
			$fields['municity_code']   		= $codes_residential[1];
			$fields['province_code']   		= $codes_residential[2];
			$fields['region_code']     		= $codes_residential[3];

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                         = array("*") ;
				$table                         = $this->pds->tbl_employee_addresses;
				$where                         = array();
				$key                           = $this->get_hash_key('employee_address_id');
				$where[$key]                   = $id;
				$address                       = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_address_id'] = $address['employee_address_id'];
				$fields['address_type_id_old'] = $address["address_type_id"];
				$fields['postal_number_old']   = $address["postal_number"];
				$fields['address_value_old']   = $address["address_value"];
				$fields['barangay_code_old']   = $address['barangay_code'];
				$fields['municity_code_old']   = $address['municity_code'];
				$fields['province_code_old']   = $address['province_code'];
				$fields['region_code_old']     = $address['region_code'];
			}
			$table 					= $this->pds->tbl_requests_employee_addresses;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_addresses;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Address record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_address($params)
	{
		try
		{
			$fields                             = array();			
			$fields['address_type_id']          = "Address Type";
			//$fields['postal_number']            = "Zip Code";
			$fields['address_value']            = "Address";
			//$fields['municipality_residential'] = "Barangay/Municipality/Province/Region";

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
			$validation['municipality'] = array(
					'data_type' => 'digit',
					'name'		=> 'Municipality',
					'max_len'	=> 2
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
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_contact($params);
			
			Main_Model::beginTransaction();
			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_contacts;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_contact_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 						= array();
			$fields['request_sub_id']		= $sub_request_id;
			$fields['employee_id']			= $personal_info["employee_id"];
			$fields['employee_contact_id'] 	= 0;
			$fields['contact_type_id']		= $valid_data["contact_type"];
			$fields['contact_value']		= $valid_data["contact_value"];
			
			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                         = array("*") ;
				$table                         = $this->pds->tbl_employee_contacts;
				$where                         = array();
				$key                           = $this->get_hash_key('employee_contact_id');
				$where[$key]                   = $id;
				$contacts                      = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_contact_id'] = $contacts['employee_contact_id'];
				$fields['contact_type_id_old'] = $contacts['contact_type_id'];
				$fields['contact_value_old']   = $contacts['contact_value'];

			}

			$table 					= $this->pds->tbl_requests_employee_contacts;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_contacts;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Contacts record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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

	// public function delete_contact()
	// {
	// 	try
	// 	{
	// 		$flag        = 0;
	// 		$params      = get_params();
	// 		$url         = $params['param_1'];
	// 		$url_explode = explode('/',$url);
	// 		$action      = $url_explode[0];
	// 		$id          = $url_explode[1];
	// 		$token       = $url_explode[2];
	// 		$salt        = $url_explode[3];
	// 		$module      = $url_explode[4];

	// 		if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
	// 		{
	// 			throw new Exception($this->lang->line('invalid_action'));
	// 		}
	// 		if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
	// 		{
	// 			throw new Exception($this->lang->line('err_unauthorized_access'));
	// 		}
	// 		Main_Model::beginTransaction();
	// 		$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
	// 		$table				= $this->pds->tbl_requests_employee_contacts;
	// 		$req_info			= $this->pds->check_pds_record($table, "A.employee_contact_id", $id);
	// 		if($req_info)
	// 		{
	// 			throw new Exception($this->lang->line('request_prohibited'));
	// 		}
	// 		/*GET EMPLOYEE*/
	// 		$field                                = array("*") ;
	// 		$table                                = $this->pds->tbl_employee_personal_info;
	// 		$where                                = array();
	// 		$key                                  = $this->get_hash_key('employee_id');
	// 		$where[$key]                          = $pds_employee_id;
	// 		$personal_info                        = $this->pds->get_general_data($field, $table, $where, FALSE);
			
	// 		/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
	// 		$sub_request_id                       = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$action);
			
	// 		/*############################ END : INSERT SUB REQUEST DATA #############################*/
	// 		$field                                = array("*") ;
	// 		$table                                = $this->pds->tbl_employee_contacts;
	// 		$where                                = array();
	// 		$key                                  = $this->get_hash_key('employee_contact_id');
	// 		$where[$key]                          = $id;
	// 		$contact                              = $this->pds->get_general_data($field, $table, $where, FALSE);
			
	// 		$fields                               = array() ;
	// 		$fields['request_sub_id']             = $sub_request_id;
	// 		//$fields['action']                   = $action;
	// 		$fields['employee_id']                = $personal_info["employee_id"];
	// 		$fields['contact_type_id_old']        = $contact["contact_type_id"];
	// 		$fields['contact_value_old'] 		  = $contact["contact_value"];
	// 		$fields['employee_contact_id'] 		  = $contact['employee_contact_id'];
			
	// 		$table                                = $this->pds->tbl_requests_employee_contacts;
	// 		$this->pds->insert_general_data($table,$fields,FALSE);
			
	// 		$audit_table[]                        = $this->pds->tbl_requests_employee_contacts;
	// 		$audit_schema[]                       = DB_MAIN;
	// 		$prev_detail[]                        = array();
	// 		$curr_detail[]                        = array($fields);
	// 		$audit_action[]                       = AUDIT_INSERT;
	// 		$activity                             = "Contact record changes request of %s has been added.";
	// 		$audit_activity                       = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
	// 		$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
	// 		Main_Model::commit();
	// 		$msg                                  = $this->lang->line('save_record_changes');
	// 		$flag                                 = 1;
	// 	}
		
	// 	catch(Exception $e)
	// 	{
	// 		$msg = $e->getMessage();
	// 		RLog::error($msg);
	// 		Main_Model::rollback();
	// 	}
		
	// 	$response 					= array(
	// 		"flag" 					=> $flag,
	// 		"msg" 					=> $msg,
	// 		"reload" 				=> 'datatable',
	// 		"table_id" 				=> 'contacts_table',
	// 		"path"					=> PROJECT_MAIN . '/pds_contact_info/get_address_list/',
	// 		"advanced_filter" 		=> true
	// 		);
	// 	echo json_encode($response);
	// }

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
				$pds_employee_id                   = $this->session->userdata("pds_employee_id");
				
				$table                             = $this->pds->tbl_requests_employee_addresses;
				$req_info                          = $this->pds->check_pds_record($table, "A.employee_address_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
				/*GET EMPLOYEE*/
				$field                         = array("*") ;
				$table                         = $this->pds->tbl_employee_personal_info;
				$where                         = array();
				$key                           = $this->get_hash_key('employee_id');
				$where[$key]                   = $pds_employee_id;
				$personal_info                 = $this->pds->get_general_data($field, $table, $where, FALSE);				
				
				/*############################ START : INSERT SUB REQUEST DATA #############################*/
				
				$sub_request_id                = $this->insert_sub_request(TYPE_REQUEST_PDS_ADDRESS_INFO,$personal_info["employee_id"],$action);
				
				/*############################ END : INSERT SUB REQUEST DATA #############################*/

				$field                         = array("*") ;
				$table                         = $this->pds->tbl_employee_addresses;
				$where                         = array();
				$key                           = $this->get_hash_key('employee_address_id');
				$where[$key]                   = $id;
				$address                       = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields                        = array() ;
				$fields['request_sub_id']      = $sub_request_id;
				$fields['employee_id']         = $personal_info["employee_id"];
				$fields['employee_address_id'] = $address['employee_address_id'];
				$fields['address_type_id_old'] = $address["address_type_id"];
				$fields['postal_number_old']   = $address["postal_number"];
				$fields['address_value_old']   = $address["address_value"];
				$fields['barangay_code_old']   = $address["barangay_code"];
				$fields['municity_code_old']   = $address["municity_code"];
				$fields['province_code_old']   = $address["province_code"];
				$fields['region_code_old']     = $address["region_code"];

				$table                         = $this->pds->tbl_requests_employee_addresses;

				$this->pds->insert_general_data($table,$fields,FALSE);
				
				$audit_table[]                 = $this->pds->tbl_requests_employee_addresses;
				$audit_schema[]                = DB_MAIN;
				$prev_detail[]                 = array();
				$curr_detail[]                 = array($fields);
				$audit_action[]                = AUDIT_INSERT;
				$activity                      = "Address record changes request of %s has been added.";
				$audit_activity                = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
				
				$table_id                      = "address_table";
				$data_path                     = PROJECT_MAIN . '/pds_contact_info/get_address_list/';

			}
			else
			{
				$pds_employee_id	= $this->session->userdata("pds_employee_id");
				
				$table				= $this->pds->tbl_requests_employee_contacts;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_contact_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
				/*GET EMPLOYEE*/
				$field 						= array("*") ;
				$table						= $this->pds->tbl_employee_personal_info;
				$where						= array();
				$key 						= $this->get_hash_key('employee_id');
				$where[$key]				= $pds_employee_id;
				$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

				/*############################ START : INSERT SUB REQUEST DATA #############################*/

				$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_CONTACT_INFO,$personal_info["employee_id"],$action);
				
				/*############################ END : INSERT SUB REQUEST DATA #############################*/

				$field                         = array("*") ;
				$table                         = $this->pds->tbl_employee_contacts;
				$where                         = array();
				$key                           = $this->get_hash_key('employee_contact_id');
				$where[$key]                   = $id;
				$contacts                      = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields                        = array() ;
				$fields['request_sub_id']      = $sub_request_id;
				$fields['employee_id']         = $personal_info["employee_id"];
				$fields['contact_type_id_old'] = $contacts["contact_type_id"];
				$fields['contact_value_old']   = $contacts["contact_value"];
				$fields['employee_contact_id'] = $contacts['employee_contact_id'];

				$table                      = $this->pds->tbl_requests_employee_contacts;

				$this->pds->insert_general_data($table,$fields,FALSE);
				
				$audit_table[]				= $this->pds->tbl_requests_employee_contacts;
				$audit_schema[]				= DB_MAIN;
				$prev_detail[] 				= array();
				$curr_detail[]				= array($fields);
				$audit_action[] 			= AUDIT_INSERT;
				$activity 					= "Contacts record changes request of %s has been added.";
				$audit_activity 			= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
				
				$table_id = "contacts_table";
				$data_path = PROJECT_MAIN . '/pds_contact_info/get_contact_list/';
			}
			
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
			"table_id" 				=> $table_id,
			"path"					=> $data_path,
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS EDUCATION*/
	public function process_government_exam()
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
			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_eligibility;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_eligibility_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);


			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_ELIGIBILITY,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 						   = array() ;
			$fields['request_sub_id']		   = $sub_request_id;
			$fields['employee_id']			   = $personal_info["employee_id"];
			$fields['employee_eligibility_id'] = 0;
			$fields['eligibility_type_id']	   = $valid_data["eligibility_type_id"];
			$fields['rating']				   = $valid_data["rating"];
			$fields['exam_date']			   = $valid_data["exam_date"];
			$fields['exam_place']			   = $valid_data["exam_place"];
			$fields['release_date']			   = ! empty($valid_data["release_date"]) ? $valid_data["release_date"] : NULL;
			$fields['license_no']			   = ! empty($valid_data["license_no"]) ? $valid_data["license_no"] : NULL;
			$fields['relevance_flag']			= isset($valid_data['relevance_flag']) ? "Y" : "N";

			$audit_fields 							 = array() ;
			$audit_fields['request_sub_id']			 = $sub_request_id;
			$audit_fields['employee_id']			 = $personal_info["employee_id"];
			$audit_fields['employee_eligibility_id'] = 0;
			$audit_fields['eligibility_type_id']	 = $valid_data["eligibility_type_id"];
			$audit_fields['rating']					 = $valid_data["rating"];
			$audit_fields['exam_date']				 = $valid_data["exam_date"];
			$audit_fields['exam_place']				 = $valid_data["exam_place"];
			$audit_fields['release_date']			 = ! empty($valid_data["release_date"]) ? $valid_data["release_date"] : ' ';
			$audit_fields['license_no']			     = ! empty($valid_data["license_no"]) ? $valid_data["license_no"] : ' ';

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                             = array("*") ;
				$table                             = $this->pds->tbl_employee_eligibility;
				$where                             = array();
				$key                               = $this->get_hash_key('employee_eligibility_id');
				$where[$key]                       = $id;
				$eligibility                       = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_eligibility_id'] = !EMPTY($eligibility["employee_eligibility_id"]) ? $eligibility["employee_eligibility_id"] : NULL;
				$fields['eligibility_type_id_old'] = !EMPTY($eligibility["eligibility_type_id"]) ? $eligibility["eligibility_type_id"] : NULL;
				$fields['rating_old']              = !EMPTY($eligibility["rating"]) ? $eligibility["rating"] : NULL;
				$fields['exam_date_old']           = !EMPTY($eligibility["exam_date"]) ? $eligibility["exam_date"] : NULL;
				$fields['exam_place_old']          = !EMPTY($eligibility["exam_place"]) ? $eligibility["exam_place"] : NULL;
				$fields['release_date_old']        = !EMPTY($eligibility["release_date"]) ? $eligibility["release_date"] : NULL;
				$fields['license_no_old']          = !EMPTY($eligibility["license_no"]) ? $eligibility["license_no"] : NULL;
				$fields['relevance_flag_old']	   = !EMPTY($eligibility["relevance_flag"]) ? $eligibility["relevance_flag"] : NULL;

				$audit_fields['employee_eligibility_id'] = !EMPTY($eligibility["employee_eligibility_id"]) ? $eligibility["employee_eligibility_id"] : ' ';
				$audit_fields['eligibility_type_id_old'] = !EMPTY($eligibility["eligibility_type_id"]) ? $eligibility["eligibility_type_id"] : ' ';
				$audit_fields['rating_old']              = !EMPTY($eligibility["rating"]) ? $eligibility["rating"] : ' ';
				$audit_fields['exam_date_old']           = !EMPTY($eligibility["exam_date"]) ? $eligibility["exam_date"] : ' ';
				$audit_fields['exam_place_old']          = !EMPTY($eligibility["exam_place"]) ? $eligibility["exam_place"] : ' ';
				$audit_fields['release_date_old']        = !EMPTY($eligibility["release_date"]) ? $eligibility["release_date"] : ' ';
				$audit_fields['license_no_old']          = !EMPTY($eligibility["license_no"]) ? $eligibility["license_no"] : ' ';
			}

			$table 					= $this->pds->tbl_requests_employee_eligibility;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_eligibility;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($audit_fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Eligibility record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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

			if ( $params['eligibility_type_flag'] == ELIGIBILITY_TYPE_FLAG_RA )
			{
				$fields['license_no'] 		= "License Number";
				$fields['release_date'] 	= "Date of Validity";
			}
			
			if ( $params['eligibility_type_flag'] != ELIGIBILITY_TYPE_FLAG_CSPD )
			{
				if(EMPTY($params['rating']))
				{
					throw new Exception('<b>Rating</b>' . $this->lang->line('not_applicable'));
				}
			}

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
					'name'		=> 'Date of Release'
			);			
			if ( $params['eligibility_type_flag'] != ELIGIBILITY_TYPE_FLAG_CSPD )
			{
				$validation['rating'] = array(
					'data_type' => 'string',
					'name'		=> 'Rating',
				);
			}

			if(is_numeric($rating_str))
			{
				$validation['rating']['max']	= 100;
			}
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_eligibility;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_eligibility_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_ELIGIBILITY,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                             = array("*") ;
			$table                             = $this->pds->tbl_employee_eligibility;
			$where                             = array();
			$key                               = $this->get_hash_key('employee_eligibility_id');
			$where[$key]                       = $id;
			$eligibility                       = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                            = array() ;
			$fields['request_sub_id']          = $sub_request_id;
			$fields['employee_id']             = $personal_info["employee_id"];
			$fields['eligibility_type_id_old'] = $eligibility["eligibility_type_id"];
			$fields['rating_old']              = $eligibility["rating"];
			$fields['exam_date_old']           = $eligibility["exam_date"];
			$fields['exam_place_old']          = $eligibility["exam_place"];
			$fields['release_date_old']        = $eligibility["release_date"];
			$fields['license_no_old']          = $eligibility["license_no"];				
			$fields['employee_eligibility_id'] = $eligibility['employee_eligibility_id'];

			$audit_fields['request_sub_id']          = $sub_request_id;
			$audit_fields['employee_id']             = $personal_info["employee_id"];
			$audit_fields['eligibility_type_id_old'] = !EMPTY($eligibility["eligibility_type_id"]) ? $eligibility["eligibility_type_id"] : ' ';
			$audit_fields['rating_old']              = !EMPTY($eligibility["rating"]) ? $eligibility["rating"] : ' ';
			$audit_fields['exam_date_old']           = !EMPTY($eligibility["exam_date"]) ? $eligibility["exam_date"] : ' ';
			$audit_fields['exam_place_old']          = !EMPTY($eligibility["exam_place"]) ? $eligibility["exam_place"] : ' ';
			$audit_fields['release_date_old']        = !EMPTY($eligibility["release_date"]) ? $eligibility["release_date"] : ' ';
			$audit_fields['license_no_old']          = !EMPTY($eligibility["license_no"]) ? $eligibility["license_no"] : ' ';
		
			$table                             = $this->pds->tbl_requests_employee_eligibility;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                     = $this->pds->tbl_requests_employee_eligibility;
			$audit_schema[]                    = DB_MAIN;
			$prev_detail[]                     = array();
			$curr_detail[]                     = array($audit_fields);
			$audit_action[]                    = AUDIT_INSERT;
			$activity                          = "Eligibility record changes request of %s has been added.";
			$audit_activity                    = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
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
			"table_id" 				=> 'pds_government_exam_table',
			"path"					=> PROJECT_MAIN . '/pds_government_exam_info/get_government_exam_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS WORK EXPERIENCE*/
	public function process_work_experience()
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
			$valid_data = $this->_validate_work_experience($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id		= $this->session->userdata("pds_employee_id");
			
			if(strtotime($params['employ_start_date']) > strtotime($params['employ_end_date']))
			{
				throw new Exception('<b>End Date</b> should not be earlier than <b>Start Date</b>.');
			}

			if($action == ACTION_ADD)
			{
				$work_id 	= '';
			} else
			{
				$work_id = $id;
			}

			// $start_date = $valid_data['employ_start_date'];
			// $end_date  	= $valid_data['employ_end_date'];
			
			//===== marvin : correct index : start =====
			$start_date = $valid_data['employ_start_date_non_doh'];
			$end_date  	= $valid_data['employ_end_date_non_doh'];
			//===== marvin : correct index : end =====
		
			// TEST DATE OVERLAP
			$test_date = array();
			$test_date = $this->pds->check_date_overlap($start_date, $end_date, $work_id);

			if($test_date)
			{
				throw new Exception('Entered date range overlapped other existing work experience.');
			}

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_work_experiences;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_work_experience_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_WORK_EXPERIENCE,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 							   = array() ;
			$fields['request_sub_id']			   = $sub_request_id;
			$fields['employee_id'] 				   = $personal_info["employee_id"];
			$fields['employee_work_experience_id'] = 0;

			if($params['govt_service_flag'] == ACTIVE OR $params['employ_type_flag'] == NON_DOH_GOV)
			{
				//OUTSIDE DOH GOVERNMENT SERVICE
				$employ_type_flag               = NON_DOH_GOV;
				$fields['employ_salary_grade']  = $valid_data['employ_salary_grade_non_doh'];
				$fields['employ_salary_step']   = $valid_data['employ_salary_step_non_doh'];
				$fields['service_lwop']         =!EMPTY($valid_data['leaves']) ? $valid_data['leaves'] : 0;
				$fields['government_branch_id'] = $valid_data["branch_name"];
				$fields['separation_mode_id']   = $valid_data["separation_mode_non_doh"];
				$fields['govt_service_flag']    = 'Y';				
				$fields['relevance_flag']  		= (! empty($valid_data['relevance_flag'])) ? "Y" : "N";
			}
			else
			{
				//PRIVATE COMPANY
				$employ_type_flag            = PRIVATE_WORK;
				$fields['govt_service_flag'] = 'N';				
				$fields['relevance_flag'] 	 = 'N';				
			}

			$fields['employ_start_date']     = $valid_data["employ_start_date_non_doh"];
			$fields['employ_end_date']       = $valid_data["employ_end_date_non_doh"];
			$fields['employ_position_name']  = $valid_data["employ_position"];
			$fields['employ_office_name']    = $valid_data["employ_company_name"];
			$fields['employ_monthly_salary'] = $valid_data["employ_monthly_salary_non_doh"];
			$fields['employment_status_id']  = $valid_data["employment_status_non_doh"];
			$fields['employ_type_flag']      = $employ_type_flag;
			$fields['remarks']  			 = !EMPTY($valid_data['remarks']) ? $valid_data['remarks'] : ' ';

			if(strtotime($params['employ_start_date_non_doh']) > strtotime($params['employ_end_date_non_doh']))
			{
				$this->lang->line('date_range');
			}

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                                 = array("*") ;
				$table                                 = $this->pds->tbl_employee_work_experiences;
				$where                                 = array();
				$key                                   = $this->get_hash_key('employee_work_experience_id');
				$where[$key]                           = $id;
				$experience                            = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_work_experience_id'] = $experience['employee_work_experience_id'];				
				$fields['employ_start_date_old']       = $experience["employ_start_date"];
				$fields['employ_end_date_old']         = $experience["employ_end_date"];
				$fields['employ_position_name_old']    = $experience["employ_position_name"];
				$fields['employ_office_name_old']      = $experience["employ_office_name"];
				$fields['employ_monthly_salary_old']   = $experience["employ_monthly_salary"];
				$fields['employment_status_id_old']    = $experience["employment_status_id"];
				$fields['employ_type_flag_old']        = $experience["employ_type_flag"];
				$fields['employ_salary_grade_old']     = $experience['employ_salary_grade'];
				$fields['employ_salary_step_old']      = $experience['employ_salary_step'];
				$fields['service_lwop_old']            = $experience['service_lwop'];
				$fields['government_branch_id_old']    = $experience["government_branch_id"];
				$fields['separation_mode_id_old']      = $experience["separation_mode_id"];
				$fields['relevance_flag_old']  		   = $experience["relevance_flag"];
				$fields['remarks_old']  		   	   = $experience["remarks"];
			}

			$table 					= $this->pds->tbl_requests_employee_work_experiences;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_work_experiences;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Work experience record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_work_experience($params)
	{
		try
		{						
			$fields 								 = array();
			$fields['employ_start_date_non_doh']     = "Date Started";
			$fields['employ_end_date_non_doh']       = "Date Ended";
			$fields['employ_position']       		 = "Position";
			$fields['employ_company_name']   		 = "Department/Agency/Office/Company";
			$fields['employ_monthly_salary_non_doh'] = "Monthly Salary";
			$fields['employment_status_non_doh']  	 = "Employment Status";			

			if(!EMPTY($params['govt_service_flag']) OR $params['employ_type_flag'] == NON_DOH_GOV)
			{
				$fields['employ_salary_grade_non_doh'] = "Salary Grade";
				$fields['employ_salary_step_non_doh']  = "Salary Step";
				$fields['branch_name']         		   = "Branch";
				$fields['separation_mode_non_doh']     = "Separation Cause";
			}

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_work_experience($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_input_work_experience($params)
	{
		try
		{
			$validation['employ_start_date_non_doh'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['employ_end_date_non_doh'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended'
			);
			$validation['employ_position'] = array(
					'data_type' => 'string',
					'name'		=> 'Position',
					'max_len'	=> 50
			);
			$validation['employ_company_name'] = array(
					'data_type' => 'string',
					'name'		=> 'License Number',
					'max_len'	=> 100
			);
			$validation['employ_monthly_salary_non_doh'] = array(
					'data_type' => 'amount',
					'name'		=> 'Monthly Salary',
					'decimal'	=> 2,
					'max'		=> 999999
			);	
			$validation['employment_status_non_doh'] = array(
					'data_type' => 'digit',
					'name'		=> 'Status of Appointment'
			);
			$validation['govt_service_flag'] = array(
					'data_type' => 'digit',
					'name'		=> 'Government Service',
					'max_len'	=> 1
			);
			$validation['separation_mode_non_doh'] = array(
				'data_type' => 'digit',
				'name'		=> 'Separation Mode'
			);
			$validation['employ_salary_grade_non_doh'] = array(
				'data_type' => 'digit',
				'name'		=> 'Salary Grade',
				'max_len'	=> 3
			);		
			$validation['employ_salary_step_non_doh'] = array(
				'data_type' => 'digit',
				'name'		=> 'Pay Step',
				'max_len'	=> 3
			);
			$validation['branch_name'] = array(
				'data_type' => 'digit',
				'name'		=> 'Branch'
			);
			$validation['leaves'] = array(
				'data_type' => 'digit',
				'name'		=> 'Leave/s without Pay',
				'max_len'	=> 10
			);
			$validation['relevance_flag'] = array (
					'data_type' 		=> 'string',
					'name' 				=> 'Relevance Flag',
					'max_len' 			=> 1 
			);
			$validation['remarks'] = array (
					'data_type'    => 'string',
					'name' 		   => 'Remarks'
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_work_experience()
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_work_experiences;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_work_experience_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field                                 = array("*") ;
			$table                                 = $this->pds->tbl_employee_personal_info;
			$where                                 = array();
			$key                                   = $this->get_hash_key('employee_id');
			$where[$key]                           = $pds_employee_id;
			$personal_info                         = $this->pds->get_general_data($field, $table, $where, FALSE);			
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id                        = $this->insert_sub_request(TYPE_REQUEST_PDS_WORK_EXPERIENCE,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                                 = array("*") ;
			$table                                 = $this->pds->tbl_employee_work_experiences;
			$where                                 = array();
			$key                                   = $this->get_hash_key('employee_work_experience_id');
			$where[$key]                           = $id;
			$experience                            = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                                = array() ;
			$fields['request_sub_id']              = $sub_request_id;
			$fields['employee_id']                 = $personal_info["employee_id"];
			$fields['employee_work_experience_id'] = $experience['employee_work_experience_id'];
			$fields['employ_start_date_old']       = $experience["employ_start_date"];
			$fields['employ_end_date_old']         = $experience["employ_end_date"];
			$fields['employ_position_name_old']    = $experience["employ_position_name"];
			$fields['employ_office_name_old']      = $experience["employ_office_name"];
			$fields['employ_monthly_salary_old']   = $experience["employ_monthly_salary"];
			$fields['employment_status_id_old']    = $experience["employment_status_id"];
			$fields['employ_type_flag_old']        = $experience["employ_type_flag"];
			$fields['employ_salary_grade_old']     = $experience['employ_salary_grade'];
			$fields['employ_salary_step_old']      = $experience['employ_salary_step'];
			$fields['service_lwop_old']            = $experience['service_lwop'];
			$fields['government_branch_id_old']    = $experience["government_branch_id"];
			$fields['separation_mode_id_old']      = $experience["separation_mode_id"];			
			
			$table                                 = $this->pds->tbl_requests_employee_work_experiences;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                         = $this->pds->tbl_requests_employee_work_experiences;
			$audit_schema[]                        = DB_MAIN;
			$prev_detail[]                         = array();
			$curr_detail[]                         = array($fields);
			$audit_action[]                        = AUDIT_INSERT;
			$activity                              = "Work experience record changes request of %s has been added.";
			$audit_activity                        = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
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
			"table_id" 				=> 'pds_work_experience_table',
			"path"					=> PROJECT_MAIN . '/pds_work_experience_info/get_work_experience_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS PROFESSION*/
	public function process_profession()
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
			$valid_data = $this->_validate_profession($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_professions;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_profession_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field                    = array("*") ;
			$table                    = $this->pds->tbl_employee_personal_info;
			$where                    = array();
			$key                      = $this->get_hash_key('employee_id');
			$where[$key]              = $pds_employee_id;
			$personal_info            = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id           = $this->insert_sub_request(TYPE_REQUEST_PDS_PROFESSION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields                   		  = array() ;
			$fields['request_sub_id'] 		  = $sub_request_id;
			$fields['employee_id']    		  = $personal_info["employee_id"];
			$fields['employee_profession_id'] = 0;
			$fields['profession_id']  		  = $valid_data["profession_id"];
			$fields['others_specify'] 		  = $valid_data["others_specify"];

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                            = array("*") ;
				$table                            = $this->pds->tbl_employee_professions;
				$where                            = array();
				$key                              = $this->get_hash_key('employee_profession_id');
				$where[$key]                      = $id;
				$profession                       = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_profession_id'] = $profession['employee_profession_id'];
				$fields['profession_id_old']      = $profession["profession_id"];
				$fields['others_specify_old']     = $profession["others_specify"];
			}

			$table          = $this->pds->tbl_requests_employee_professions;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]  = $this->pds->tbl_requests_employee_professions;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array();
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_INSERT;	
			
			$activity       = "Indentification record changes request of %s has been added.";
			$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$status         = true;
			$message        = $this->lang->line('save_record_changes');
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

	private function _validate_profession($params)
	{
		try
		{
			$fields 				 = array();
			$fields['profession_id'] = "Profession Name";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_profession($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_input_profession($params)
	{
		try
		{			
			$validation['profession_id'] = array(
				'data_type' => 'string',
				'name'		=> 'Profession Name'
			);
			$validation['others_specify'] = array(
				'data_type' => 'string',
				'name'		=> 'Others',
				'max_len'	=> 100
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_profession()
	{
		try
		{
			$flag        = 0;
			$params      = get_params();
			$url         = $params['param_1'];
			$url_explode = explode('/',$url);
			$action      = $url_explode[0];
			$id          = $url_explode[1];
			$token       = $url_explode[2];
			$salt        = $url_explode[3];
			$module      = $url_explode[4];

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
			
			$table				= $this->pds->tbl_requests_employee_professions;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_profession_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field                                = array("*") ;
			$table                                = $this->pds->tbl_employee_personal_info;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_id');
			$where[$key]                          = $pds_employee_id;
			$personal_info                        = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id                       = $this->insert_sub_request(TYPE_REQUEST_PDS_PROFESSION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                                = array("*") ;
			$table                                = $this->pds->tbl_employee_professions;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_profession_id');
			$where[$key]                          = $id;
			$profession                           = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                               = array() ;
			$fields['request_sub_id']             = $sub_request_id;
			$fields['employee_id']                = $personal_info["employee_id"];
			$fields['profession_id_old']     	  = $profession["profession_id"];
			$fields['employee_profession_id'] 	  = $profession['employee_profession_id'];
			$fields['others_specify_old']     	  = $profession["others_specify"];
			
			$table                                = $this->pds->tbl_requests_employee_professions;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                        = $this->pds->tbl_requests_employee_professions;
			$audit_schema[]                       = DB_MAIN;
			$prev_detail[]                        = array();
			$curr_detail[]                        = array($fields);
			$audit_action[]                       = AUDIT_INSERT;
			$activity                             = "Profession record changes request of %s has been added.";
			$audit_activity                       = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg                                  = $this->lang->line('save_record_changes');
			$flag                                 = 1;
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
			"table_id" 				=> 'pds_profession_table',
			"path"					=> PROJECT_MAIN . '/pds_profession_info/get_profession_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS VOLUNTARY WORK*/
	public function process_voluntary_work()
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
			$valid_data = $this->_validate_voluntary_work($params);
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_voluntary_works;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_voluntary_work_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_VOLUNTARY_WORK,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 							  = array() ;
			$fields['request_sub_id']			  = $sub_request_id;
			$fields['employee_id']				  = $personal_info["employee_id"];
			$fields['employee_voluntary_work_id'] = 0;
			$fields['volunteer_org_name']    	  = $valid_data["volunteer_org_name"];
			$fields['volunteer_org_address'] 	  = $valid_data["volunteer_org_address"];
			$fields['volunteer_start_date']  	  = $valid_data["volunteer_start_date"];
			$fields['volunteer_end_date']    	  = !EMPTY($valid_data["volunteer_end_date"]) ? $valid_data["volunteer_end_date"] : NULL;
			$fields['volunteer_hour_count']  	  = $valid_data["volunteer_hour_count"];
			$fields['volunteer_position']    	  = $valid_data["volunteer_position"];

			$audit_fields 								= array() ;
			$audit_fields['request_sub_id']				= $sub_request_id;
			$audit_fields['employee_id']				= $personal_info["employee_id"];
			$audit_fields['employee_voluntary_work_id'] = 0;
			$audit_fields['volunteer_org_name']    		= $valid_data["volunteer_org_name"];
			$audit_fields['volunteer_org_address'] 		= $valid_data["volunteer_org_address"];
			$audit_fields['volunteer_start_date']  		= $valid_data["volunteer_start_date"];
			$audit_fields['volunteer_end_date']    		= !EMPTY($valid_data["volunteer_end_date"]) ? $valid_data["volunteer_end_date"] : ' ';
			$audit_fields['volunteer_hour_count']  		= $valid_data["volunteer_hour_count"];
			$audit_fields['volunteer_position']    		= $valid_data["volunteer_position"];

			if (!empty($valid_data["volunteer_end_date"]))
			{
				if($valid_data['volunteer_end_date'] < $valid_data['volunteer_start_date'])
				{
					throw new Exception('Date Ended should not be earlier than Date Started.');
				}
			}	

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                                = array("*") ;
				$table                                = $this->pds->tbl_employee_voluntary_works;
				$where                                = array();
				$key                                  = $this->get_hash_key('employee_voluntary_work_id');
				$where[$key]                          = $id;
				$voluntary                            = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_voluntary_work_id'] = $voluntary['employee_voluntary_work_id'];
				$fields['volunteer_org_name_old']     = $voluntary["volunteer_org_name"];
				$fields['volunteer_org_address_old']  = $voluntary["volunteer_org_address"];
				$fields['volunteer_start_date_old']   = $voluntary["volunteer_start_date"];
				$fields['volunteer_end_date_old']     = $voluntary["volunteer_end_date"];
				$fields['volunteer_hour_count_old']   = $voluntary["volunteer_hour_count"];
				$fields['volunteer_position_old']     = $voluntary["volunteer_position"];

				$audit_fields['employee_voluntary_work_id'] = !EMPTY($voluntary["employee_voluntary_work_id"]) ? $voluntary["employee_voluntary_work_id"] : ' ';
				$audit_fields['volunteer_org_name_old']     = !EMPTY($voluntary["volunteer_org_name"]) ? $voluntary["volunteer_org_name"] : ' ';
				$audit_fields['volunteer_org_address_old']  = !EMPTY($voluntary["volunteer_org_address"]) ? $voluntary["volunteer_org_address"] : ' ';
				$audit_fields['volunteer_start_date_old']   = !EMPTY($voluntary["volunteer_start_date"]) ? $voluntary["volunteer_start_date"] : ' ';
				$audit_fields['volunteer_end_date_old']     = !EMPTY($voluntary["volunteer_end_date"]) ? $voluntary["volunteer_end_date"] : ' ';
				$audit_fields['volunteer_hour_count_old']   = !EMPTY($voluntary["volunteer_hour_count"]) ? $voluntary["volunteer_hour_count"] : ' ';
				$audit_fields['volunteer_position_old']     = !EMPTY($voluntary["volunteer_position"]) ? $voluntary["volunteer_position"] : ' ';
			}
			$table 					= $this->pds->tbl_requests_employee_voluntary_works;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_voluntary_works;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($audit_fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Voluntary works record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_voluntary_work($params)
	{
		try
		{
			$fields 							= array();
			$fields['volunteer_org_name']		= "Organization Name";
			$fields['volunteer_org_address']	= "Organization Address";
			$fields['volunteer_start_date']		= "Date Started";
			$fields['volunteer_hour_count']		= "Number of Hours";
			$fields['volunteer_position']		= "Position/Nature of Work";
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_voluntary_work($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_voluntary_work($params)
	{
		try
		{
			$validation['volunteer_org_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Organization Name',
					'max_len'	=> 100
			);
			$validation['volunteer_org_address'] = array(
					'data_type' => 'string',
					'name'		=> 'Organization Address',
					'max_len'	=> 300
			);
			$validation['volunteer_start_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Started'
			);
			$validation['volunteer_end_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Date Ended'
			);
			$validation['volunteer_hour_count'] = array(
					'data_type' => 'amount',
					'name'		=> 'Number of Hours',
					'decimal'	=> 2,
					'max'		=> 999999
			);
			$validation['volunteer_position'] = array(
					'data_type' => 'string',
					'name'		=> 'Position/Nature of Work',
					'max_len'	=> 100
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_voluntary_work()
	{
		try
		{
			$flag        = 0;
			$params      = get_params();
			$url         = $params['param_1'];
			$url_explode = explode('/',$url);
			$action      = $url_explode[0];
			$id          = $url_explode[1];
			$token       = $url_explode[2];
			$salt        = $url_explode[3];
			$module      = $url_explode[4];

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
			
			$table				= $this->pds->tbl_requests_employee_voluntary_works;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_voluntary_work_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field                                = array("*") ;
			$table                                = $this->pds->tbl_employee_personal_info;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_id');
			$where[$key]                          = $pds_employee_id;
			$personal_info                        = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id                       = $this->insert_sub_request(TYPE_REQUEST_PDS_VOLUNTARY_WORK,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                                = array("*") ;
			$table                                = $this->pds->tbl_employee_voluntary_works;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_voluntary_work_id');
			$where[$key]                          = $id;
			$voluntary                            = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                               = array() ;
			$fields['request_sub_id']             = $sub_request_id;
			$fields['employee_id']                = $personal_info["employee_id"];
			$fields['volunteer_org_name_old']     = $voluntary["volunteer_org_name"];
			$fields['volunteer_org_address_old']  = $voluntary["volunteer_org_address"];
			$fields['volunteer_start_date_old']   = $voluntary["volunteer_start_date"];
			$fields['volunteer_end_date_old']     = $voluntary["volunteer_end_date"];
			$fields['volunteer_hour_count_old']   = $voluntary["volunteer_hour_count"];
			$fields['volunteer_position_old']     = $voluntary["volunteer_position"];
			$fields['employee_voluntary_work_id'] = $voluntary['employee_voluntary_work_id'];

			$audit_fields                               = array() ;
			$audit_fields['request_sub_id']             = $sub_request_id;
			$audit_fields['employee_voluntary_work_id'] = !EMPTY($voluntary["employee_voluntary_work_id"]) ? $voluntary["employee_voluntary_work_id"] : ' ';
			$audit_fields['volunteer_org_name_old']     = !EMPTY($voluntary["volunteer_org_name"]) ? $voluntary["volunteer_org_name"] : ' ';
			$audit_fields['volunteer_org_address_old']  = !EMPTY($voluntary["volunteer_org_address"]) ? $voluntary["volunteer_org_address"] : ' ';
			$audit_fields['volunteer_start_date_old']   = !EMPTY($voluntary["volunteer_start_date"]) ? $voluntary["volunteer_start_date"] : ' ';
			$audit_fields['volunteer_end_date_old']     = !EMPTY($voluntary["volunteer_end_date"]) ? $voluntary["volunteer_end_date"] : ' ';
			$audit_fields['volunteer_hour_count_old']   = !EMPTY($voluntary["volunteer_hour_count"]) ? $voluntary["volunteer_hour_count"] : ' ';
			$audit_fields['volunteer_position_old']     = !EMPTY($voluntary["volunteer_position"]) ? $voluntary["volunteer_position"] : ' ';
			
			$table                                = $this->pds->tbl_requests_employee_voluntary_works;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                        = $this->pds->tbl_requests_employee_voluntary_works;
			$audit_schema[]                       = DB_MAIN;
			$prev_detail[]                        = array();
			$curr_detail[]                        = array($audit_fields);
			$audit_action[]                       = AUDIT_INSERT;
			$activity                             = "Voluntary works record changes request of %s has been added.";
			$audit_activity                       = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg                                  = $this->lang->line('save_record_changes');
			$flag                                 = 1;
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
			"table_id" 				=> 'pds_voluntary_work_table',
			"path"					=> PROJECT_MAIN . '/pds_voluntary_work_info/get_voluntary_wok_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS TRAININGS*/
	public function process_trainings()
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

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_trainings;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_training_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_TRAININGS,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 							= array() ;
			$fields['request_sub_id']			= $sub_request_id;
			$fields['employee_id']				= $personal_info["employee_id"];
			$fields['employee_training_id']     = 0;
			$fields['training_name']			= $valid_data["training_title"];
			$fields['training_start_date']		= $valid_data["training_start_date"];
			$fields['training_end_date']		= $valid_data["training_end_date"];
			$fields['training_hour_count']		= $valid_data["training_hour_count"];
			$fields['training_type'] 		 	= $valid_data["training_type"];
			$fields['training_conducted_by']	= $valid_data["training_conducted_by"];
			$fields['relevance_flag']			= isset($valid_data['relevance_flag']) ? "Y" : "N";
			
			if($valid_data['training_end_date'] < $valid_data['training_start_date'])
			{
				throw new Exception('Date Ended should not be earlier than Date Started.');
			}

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                               = array("*") ;
				$table                               = $this->pds->tbl_employee_trainings;
				$where                               = array();
				$key                                 = $this->get_hash_key('employee_training_id');
				$where[$key]                         = $id;
				$training                            = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_training_id']      = $training['employee_training_id'];
				$fields['training_name_old']         = $training["training_name"];
				$fields['training_start_date_old']   = $training["training_start_date"];
				$fields['training_end_date_old']     = $training["training_end_date"];
				$fields['training_hour_count_old']   = $training["training_hour_count"];
				$fields['training_type_old'] 		 = $training["training_type"];
				$fields['training_conducted_by_old'] = $training["training_conducted_by"];
				$fields['relevance_flag_old']	     = $training["relevance_flag"];
			}
			
			$table 					= $this->pds->tbl_requests_employee_trainings;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_trainings;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Training record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
			$fields['training_type'] 		= "Training Type";
			$fields['training_conducted_by']= "Conducted/Sponsored By";

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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_trainings;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_training_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_TRAININGS,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field 								 = array("*") ;
			$table                               = $this->pds->tbl_employee_trainings;
			$where                               = array();
			$key                                 = $this->get_hash_key('employee_training_id');
			$where[$key]                         = $id;
			$training                            = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                              = array() ;
			$fields['request_sub_id']            = $sub_request_id;
			$fields['employee_id']               = $personal_info["employee_id"];
			$fields['training_name_old']         = $training["training_name"];
			$fields['training_start_date_old']   = $training["training_start_date"];
			$fields['training_end_date_old']     = $training["training_end_date"];
			$fields['training_hour_count_old']   = $training["training_hour_count"];
			$fields['training_conducted_by_old'] = $training["training_conducted_by"];
			$fields['employee_training_id']      = $training['employee_training_id'];
			
			$table                               = $this->pds->tbl_requests_employee_trainings;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                       = $this->pds->tbl_requests_employee_trainings;
			$audit_schema[]                      = DB_MAIN;
			$prev_detail[]                       = array();
			$curr_detail[]                       = array($fields);
			$audit_action[]                      = AUDIT_INSERT;
			$activity                            = "Training record changes request of %s has been added.";
			$audit_activity                      = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
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
			"table_id" 				=> 'pds_trainings_table',
			"path"					=> PROJECT_MAIN . '/pds_trainings_info/get_trainings_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS OTHER INFO*/
	public function process_other_info()
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
			$valid_data = $this->_validate_other_info($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id		= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_other_info;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_other_info_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*");
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_OTHER_INFO,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 						  = array() ;
			$fields['request_sub_id']		  = $sub_request_id;
			$fields['employee_id']			  = $personal_info["employee_id"];
			$fields['employee_other_info_id'] = 0;
			$fields['other_info_type_id']	  = $valid_data["other_info_type_id"];
			$fields['others_value']			  = $valid_data["others_value"];

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                            = array("*") ;
				$table                            = $this->pds->tbl_employee_other_info;
				$where                            = array();
				$key                              = $this->get_hash_key('employee_other_info_id');
				$where[$key]                      = $id;
				$other_info                       = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_other_info_id'] = $other_info['employee_other_info_id'];
				$fields['other_info_type_id_old'] = $other_info["other_info_type_id"];
				$fields['others_value_old']       = $other_info["others_value"];
			}
			$table 					= $this->pds->tbl_requests_employee_other_info;

			$this->pds->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->pds->tbl_requests_employee_other_info;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Other information record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_other_info;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_other_info_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field                            = array("*") ;
			$table                            = $this->pds->tbl_employee_personal_info;
			$where                            = array();
			$key                              = $this->get_hash_key('employee_id');
			$where[$key]                      = $pds_employee_id;
			$personal_info                    = $this->pds->get_general_data($field, $table, $where, FALSE);			
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id                   = $this->insert_sub_request(TYPE_REQUEST_PDS_OTHER_INFO,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                            = array("*") ;
			$table                            = $this->pds->tbl_employee_other_info;
			$where                            = array();
			$key                              = $this->get_hash_key('employee_other_info_id');
			$where[$key]                      = $id;
			$other_info                       = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                           = array() ;
			$fields['request_sub_id']         = $sub_request_id;
			$fields['employee_id']            = $personal_info["employee_id"];
			$fields['other_info_type_id_old'] = $other_info["other_info_type_id"];
			$fields['others_value_old']       = $other_info["others_value"];
			$fields['employee_other_info_id'] = $other_info['employee_other_info_id'];
			
			$table                            = $this->pds->tbl_requests_employee_other_info;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                    = $this->pds->tbl_requests_employee_other_info;
			$audit_schema[]                   = DB_MAIN;
			$prev_detail[]                    = array();
			$curr_detail[]                    = array($fields);
			$audit_action[]                   = AUDIT_INSERT;
			$activity                         = "Other information record changes request of %s has been added.";
			$audit_activity                   = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg                              = $this->lang->line('save_record_changes');
			$flag                             = 1;
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

	/*PROCESS REFERENCES*/
	public function process_reference()
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
			$valid_data = $this->_validate_reference($params);
			
			Main_Model::beginTransaction();

			$pds_employee_id	= $this->session->userdata("pds_employee_id");

			if($action != ACTION_ADD)
			{
				$table				= $this->pds->tbl_requests_employee_references;
				$req_info			= $this->pds->check_pds_record($table, "A.employee_reference_id", $id);
				if($req_info)
				{
					throw new Exception($this->lang->line('request_prohibited'));
				}
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_REFERENCES,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields 							= array() ;
			$fields['request_sub_id']			= $sub_request_id;
			$fields['employee_id']				= $personal_info["employee_id"];
			$fields['employee_reference_id']    = 0;
			$fields['reference_full_name']		= $valid_data["reference_full_name"];
			$fields['reference_address']		= $valid_data["reference_address"];
			$fields['reference_contact_info']	= str_replace('-', '', $valid_data['reference_contact_info']);

			if($action != ACTION_ADD)
			{
				/*GET PREVIOUS DATA*/
				$field                                = array("*") ;
				$table                                = $this->pds->tbl_employee_references;
				$where                                = array();
				$key                                  = $this->get_hash_key('employee_reference_id');
				$where[$key]                          = $id;
				$reference                            = $this->pds->get_general_data($field, $table, $where, FALSE);
				
				$fields['employee_reference_id']      = $reference['employee_reference_id'];
				$fields['reference_full_name_old']    = $reference["reference_full_name"];
				$fields['reference_address_old']      = $reference["reference_address"];
				$fields['reference_contact_info_old'] = $reference["reference_contact_info"];
			}

			$table          = $this->pds->tbl_requests_employee_references;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$field         			= array("COUNT(*) AS count") ;
			$table					= $this->pds->tbl_employee_references;
			$where					= array();
			$key 					= $this->get_hash_key('employee_id');
			$where[$key]			= $pds_employee_id;
			$cnt_data 				= $this->pds->get_general_data($field, $table, $where, FALSE);
			
			if($cnt_data['count'] >= 3)
			{
				throw new Exception("You can only add maximum of 3 references.");
			}
			
			$audit_table[]  = $this->pds->tbl_requests_employee_references;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array();
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_INSERT;	
			
			$activity       = "References record changes request of %s has been added.";
			$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_reference($params)
	{
		try
		{						
			$fields 							= array();
			$fields['reference_full_name']		= "Name";
			$fields['reference_address']		= "Address";
			$fields['reference_contact_info']	= "Telephone Number";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_reference($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}	
	}
	
	private function _validate_input_reference($params)
	{
		try
		{
			$validation['reference_full_name'] = array(
					'data_type' => 'string',
					'name'		=> 'Name',
					'max_len'	=> 100
			);
			$validation['reference_address'] = array(
					'data_type' => 'string',
					'name'		=> 'Address',
					'max_len'	=> 300
			);
			$validation['reference_contact_info'] = array(
					'data_type' => 'string',
					'name'		=> 'Telephone Number',
					'max_len'	=> 300
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function delete_reference()
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
			$pds_employee_id	= $this->session->userdata("pds_employee_id");
			
			$table				= $this->pds->tbl_requests_employee_references;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_reference_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}
			/*GET EMPLOYEE*/
			$field 						= array("*") ;
			$table						= $this->pds->tbl_employee_personal_info;
			$where						= array();
			$key 						= $this->get_hash_key('employee_id');
			$where[$key]				= $pds_employee_id;
			$personal_info 				= $this->pds->get_general_data($field, $table, $where, FALSE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_REFERENCES,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$field                                = array("*");
			$table                                = $this->pds->tbl_employee_references;
			$where                                = array();
			$key                                  = $this->get_hash_key('employee_reference_id');
			$where[$key]                          = $id;
			$reference                            = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			$fields                               = array() ;
			$fields['request_sub_id']             = $sub_request_id;
			$fields['employee_id']                = $personal_info["employee_id"];
			$fields['reference_full_name_old']    = $reference["reference_full_name"];
			$fields['reference_address_old']      = $reference["reference_address"];
			$fields['reference_contact_info_old'] = $reference["reference_contact_info"];
			$fields['employee_reference_id']      = $reference['employee_reference_id'];
			
			$table                                = $this->pds->tbl_requests_employee_references;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]                        = $this->pds->tbl_requests_employee_references;
			$audit_schema[]                       = DB_MAIN;
			$prev_detail[]                        = array();
			$curr_detail[]                        = array($fields);
			$audit_action[]                       = AUDIT_INSERT;
			$activity                             = "References record changes request of %s has been added.";
			$audit_activity                       = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
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
			"table_id" 				=> 'pds_references_table',
			"path"					=> PROJECT_MAIN . '/pds_references_info/get_reference_list/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	/*PROCESS REFERENCES*/
	public function process_declaration()
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

			$table				= $this->pds->tbl_requests_employee_declaration;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}

			/*GET EMPLOYEE*/
			$field                       = array("*") ;
			$table                       = $this->pds->tbl_employee_personal_info;
			$where                       = array();
			$key                         = $this->get_hash_key('employee_id');
			$where[$key]                 = $pds_employee_id;
			$personal_info               = $this->pds->get_general_data($field, $table, $where, FALSE);
			
			/*GET PREVIOUS PERSONAL INFO DATA*/
			$field                       = array("*") ;
			$table                       = $this->pds->tbl_employee_declaration;
			$where                       = array();
			$key                         = $this->get_hash_key('employee_id');
			$where[$key]                 = $id;
			$declaration                 = $this->pds->get_general_data($field, $table, $where, FALSE);
			if(EMPTY($declaration))
			{
				$action = ACTION_ADD;
			}
			else
			{
				$action = ACTION_EDIT;
			}
			
			/*############################ START : INSERT SUB REQUEST DATA #############################*/
			
			$sub_request_id              = $this->insert_sub_request(TYPE_REQUEST_PDS_DECLARATION,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/			
			
			$fields                            = array() ;
			$fields['request_sub_id']          = $sub_request_id;
			$fields['employee_id']             = $personal_info["employee_id"];				
			$fields['employee_declaration_id'] = 0;
			$fields['ctc_no']                  = $valid_data["ctc_no"];
			$fields['issued_place']            = $valid_data["issued_place"];
			$fields['issued_date']             = $valid_data["issued_date"];
			$fields['govt_issued_id']		   = ! empty($valid_data["govt_issued_id"]) ? $valid_data['govt_issued_id'] : NULL;	

			$fields['ctc_no_old']              = $declaration["ctc_no"];
			$fields['issued_place_old']        = $declaration["issued_place"];
			$fields['issued_date_old']         = $declaration["issued_date"];
			$fields['govt_issued_id_old']      = $declaration["govt_issued_id"];			
			
			$table                       = $this->pds->tbl_requests_employee_declaration;

			$this->pds->insert_general_data($table,$fields,FALSE);
			
			$audit_table[]               = $this->pds->tbl_requests_employee_declaration;
			$audit_schema[]              = DB_MAIN;
			$prev_detail[]               = array();
			$curr_detail[]               = array($fields);
			$audit_action[]              = AUDIT_INSERT;	
			
			$activity                    = "Declaration record changes request of %s has been added.";
			$audit_activity              = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	private function _validate_declaration($params)
	{
		try
		{
			$fields 					= array();
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

	/*PROCESS REFERENCES*/
	public function process_question()
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

			$pds_employee_id                = $this->session->userdata("pds_employee_id");
			/*GET PREVIOUS PERSONAL INFO DATA*/
			// $field                          = array("*") ;
			// $table                          = $this->pds->tbl_requests_sub;
			// $where                          = array();
			// $key                            = $this->get_hash_key('employee_id');
			// $where[$key]                    = $id;
			// $where['request_sub_type_id']   = TYPE_REQUEST_PDS_QUESTIONNAIRE;
			// $where['request_sub_status_id'] = SUB_REQUEST_NEW;
			// $req_info                       = $this->pds->get_general_data($field, $table, $where, FALSE);

			$table				= $this->pds->tbl_requests_employee_questions;
			$req_info			= $this->pds->check_pds_record($table, "A.employee_id", $id);
			if($req_info)
			{
				throw new Exception($this->lang->line('request_prohibited'));
			}

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

			$field 							= array("*") ;
			$table							= $this->pds->tbl_employee_questions;
			$key 							= $this->get_hash_key('employee_id');
			$where							= array();
			$where[$key]					= $id;
			$emp_questions 					= $this->pds->get_general_data($field, $table, $where, TRUE);

			/*############################ START : INSERT SUB REQUEST DATA #############################*/

			$sub_request_id = $this->insert_sub_request(TYPE_REQUEST_PDS_QUESTIONNAIRE,$personal_info["employee_id"],$action);
			
			/*############################ END : INSERT SUB REQUEST DATA #############################*/

			$fields	 = array();
			$where	 = array();
			$row_cnt = 0;
			if($questions)
			{
				foreach($questions as $key => $question)
				{
					$row_cnt ++;
					$new_id = $question["question_id"];
					$detail = $params['detail_'.$new_id];
					$choice = $params['request_type_'.$new_id];

					if($choice == "Y")
					{
						$fields[]						= array(
							'request_sub_id'			=> $sub_request_id,
							'employee_id'				=> $personal_info["employee_id"],
							'question_id'				=> $new_id,
							'question_answer_flag'		=> $choice,
							'question_answer_txt'		=> $detail,
							'question_answer_flag_old'	=> ! empty($emp_questions[$key]['question_answer_flag']) ? $emp_questions[$key]['question_answer_flag'] : NULL,
							'question_answer_txt_old'	=> ! empty($emp_questions[$key]['question_answer_txt']) ? $emp_questions[$key]['question_answer_txt'] : NULL
						);
					}
					else if($choice == "N")
					{
						$fields[]						= array(
							'request_sub_id'			=> $sub_request_id,
							'employee_id'				=> $personal_info["employee_id"],
							'question_id'				=> $new_id,
							'question_answer_flag'		=> $choice,
							'question_answer_txt'		=> NULL,
							'question_answer_flag_old'	=> ! empty($emp_questions[$key]['question_answer_flag']) ? $emp_questions[$key]['question_answer_flag'] : NULL,
							'question_answer_txt_old'	=> ! empty($emp_questions[$key]['question_answer_txt']) ? $emp_questions[$key]['question_answer_txt'] : NULL
						);
					}
					else
					{
						throw new Exception('Required to answer the question in <b>[ROW - ' . $row_cnt . ']</b>');
					}
					
				}
				$table	= $this->pds->tbl_requests_employee_questions;
				$this->pds->insert_general_data($table,$fields,FALSE);
			}
			
			$audit_table[]			= $this->pds->tbl_requests_employee_questions;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "Questions record changes request of %s has been added.";
			$audit_activity 		= sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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
	
		$data					= array();
		$data['status']			= $status;
		$data['message']		= $message;
	
		echo json_encode($data);
	}

	/*PROCESS PERSONAL INFO*/
	public function process_pds_request()
	{
		try
		{
			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";
			$process_id 	= REQUEST_WORKFLOW_PDS_RECORD_CHANGES;
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
			$record_changes				= $this->pds->count_pending_requests($id,NULL);

			
			if($record_changes['record_count'] < 1)
			{
				throw new Exception("No PDS record changes found.");
			}
		
			$field                       = array("*") ;
			$table                       = $this->pds->tbl_employee_personal_info;
			$where                       = array();
			$key                         = $this->get_hash_key('employee_id');
			$where[$key]                 = $id;
			$personal_info               = $this->pds->get_general_data($field, $table, $where, FALSE);			
			
			$fields                      = array();
			$fields['employee_id']       = $personal_info["employee_id"];
			$fields['request_type_id']   = REQUEST_PDS_RECORD_CHANGES;
			$fields['request_status_id'] = REQUEST_NEW;
			$fields['date_requested']    = date("Y-m-d H:i:s");
			
			$table                       = $this->pds->tbl_requests;
			$request_id                  = $this->pds->insert_general_data($table,$fields,TRUE);

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

			$table 					= $this->pds->tbl_requests;
			$this->pds->update_general_data($table,$fields,$where);
			
			$fields 				= array() ;
			$fields['request_id']	= $request_id;
			$where					= array();
			$where['request_id']	= "IS NULL";
			$sub_types 	= array(
				TYPE_REQUEST_PDS_PERSONAL_INFO,
				TYPE_REQUEST_PDS_IDENTIFICATION,
				TYPE_REQUEST_PDS_ADDRESS_INFO,
				TYPE_REQUEST_PDS_CONTACT_INFO,
				TYPE_REQUEST_PDS_FAMILY_INFO,
				TYPE_REQUEST_PDS_EDUCATION,
				TYPE_REQUEST_PDS_ELIGIBILITY,
				TYPE_REQUEST_PDS_WORK_EXPERIENCE,
				TYPE_REQUEST_PDS_VOLUNTARY_WORK,
				TYPE_REQUEST_PDS_TRAININGS,
				TYPE_REQUEST_PDS_OTHER_INFO,
				TYPE_REQUEST_PDS_QUESTIONNAIRE,
				TYPE_REQUEST_PDS_REFERENCES,
				TYPE_REQUEST_PDS_DECLARATION
			);

			$where["request_sub_type_id"] = array($sub_types, array("IN"));
			$key                          = $this->get_hash_key('employee_id');
			$where[$key]                  = $id;

			$table 			= $this->pds->tbl_requests_sub;
			$this->pds->update_general_data($table,$fields,$where);
			
			// $this->pds->update_requests_sub($request_id,$id);

			$workflow 		= $this->pds->get_initial_task($process_id);
						

			$fields 					= array() ;
			$fields['request_id']		= $request_id;
			$fields['task_detail']		= $workflow['name'];
			$fields['process_stage_id']	= $workflow['process_stage_id'];
			$fields['process_step_id']	= $workflow['process_step_id'];
			$fields['process_id']		= $process_id;
			$fields['task_status_id']	= 1;

			$table 						= $this->pds->tbl_requests_tasks;
			$this->pds->insert_general_data($table,$fields,FALSE);
			
			/*INSERT NOTIFICATION*/
			$request_notifications = modules::load('main/request_notifications');
			$request_notifications->insert_request_notification($request_id);
			
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
					$request_table = $this->pds->tbl_requests_employee_personal_info;
				break;
				case TYPE_REQUEST_PDS_IDENTIFICATION:
					$request_table = $this->pds->tbl_requests_employee_identifications;
				break;
				case TYPE_REQUEST_PDS_ADDRESS_INFO:
					$request_table = $this->pds->tbl_requests_employee_addresses;
				break;
				case TYPE_REQUEST_PDS_CONTACT_INFO:
					$request_table = $this->pds->tbl_requests_employee_contacts;
				break;
				case TYPE_REQUEST_PDS_FAMILY_INFO:
					$request_table = $this->pds->tbl_requests_employee_relations;
				break;
				case TYPE_REQUEST_PDS_EDUCATION:
					$request_table = $this->pds->tbl_requests_employee_educations;
				break;
				case TYPE_REQUEST_PDS_ELIGIBILITY:
					$request_table = $this->pds->tbl_requests_employee_eligibility;
				break;
				case TYPE_REQUEST_PDS_WORK_EXPERIENCE:
					$request_table = $this->pds->tbl_requests_employee_work_experiences;
				break;
				case TYPE_REQUEST_PDS_VOLUNTARY_WORK:
					$request_table = $this->pds->tbl_requests_employee_voluntary_works;
				break;
				case TYPE_REQUEST_PDS_TRAININGS:
					$request_table = $this->pds->tbl_requests_employee_trainings;
				break;
				case TYPE_REQUEST_PDS_OTHER_INFO:
					$request_table = $this->pds->tbl_requests_employee_other_info;
				break;
				case TYPE_REQUEST_PDS_QUESTIONNAIRE:
					$request_table = $this->pds->tbl_requests_employee_questions;
				break;
				case TYPE_REQUEST_PDS_REFERENCES:
					$request_table = $this->pds->tbl_requests_employee_references;
				break;
				case TYPE_REQUEST_PDS_DECLARATION:
					$request_table = $this->pds->tbl_requests_employee_declaration;
				break;
				case TYPE_REQUEST_PDS_PROFESSION:
					$request_table = $this->pds->tbl_requests_employee_professions;
				break;
				default:
					throw new Exception($this->lang->line('invalid_action'));
				break;
			}
			$field 							= array("*");
			$table							= $this->pds->tbl_requests_sub;
			$where							= array();
			$key 							= $this->get_hash_key('employee_id');
			$where[$key]					= $id;
			$where['request_sub_type_id'] 	= $type;
			$where['request_id'] 			= 'IS NULL';
			$sub_request 					= $this->pds->get_general_data($field, $table, $where, TRUE);
			
			if($sub_request)
			{
				foreach($sub_request as $request)
				{
					$request['request_sub_id'];
					$where = array();
					$where['request_sub_id'] = $request['request_sub_id'];
					$this->pds->delete_general_data($request_table,$where);

					$table							= $this->pds->tbl_requests_sub;
					$this->pds->delete_general_data($table,$where);
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
}
/* End of file Pds_record_changes_requests.php */
/* Location: ./application/modules/main/controllers/Pds_record_changes_requests.php */