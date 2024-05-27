<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contract_record extends Main_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Contract_record_model', 'contract_record');
	}

	public function index()
	{
		$data = $resources = array();

		$resources['load_css'] 		= array(CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_DATATABLE);
		$resources['datatable'][]	= array('table_id' => 'table_contract_record', 'path' => 'main/contract_record/get_contract_record_list', 'advanced_filter' => true);

		$this->template->load('contract_record/contract_record', $data, $resources);

	}

	public function get_contract_record_list()
	{

		try
		{
			$params 	= get_params();
			
			$aColumns 	= array("A.employee_id","A.agency_employee_id", "A.first_name", "A.last_name", "C.office_name", "D.status_name");
			$bColumns 	= array("A.agency_employee_id", "A.last_name", "A.first_name", "C.office_name", "D.status_name");
		
			$pds_records 	= $this->contract_record->get_employee_list($aColumns, $bColumns, $params);
			$iTotal			= $this->contract_record->total_length();
			$iFilteredTotal = $this->contract_record->filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho" 					=> intval($_POST['sEcho']),
				"iTotalRecords" 			=> $iTotal["cnt"],
				"iTotalDisplayRecords"	 	=> $iFilteredTotal["cnt"],
				"aaData"				 	=> array()
			);
			
			$module = MODULE_USER;
			
			$cnt = 0;
			foreach ($pds_records as $aRow):
				$cnt++;
				$row = array();
				$action = "";
				

				$id = $this->hash($aRow['employee_id']);

				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				
				$row[] = $aRow['agency_employee_id'];
				$row[] = $aRow['last_name'];
				$row[] = $aRow['first_name'];
				$row[] = $aRow['office_name'];
				$row[] = $aRow['status_name'];

				$action = "<div class='table-actions'>";

				
				// if($permission_view)
				$action 	.= "<a href='".base_url() . PROJECT_MAIN ."/contract_record/get_employee_contract_record_list/".$url_view."' class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>";
				
				$action 	.= '</div>';
					
				if($cnt == count($pds_records))
				{
					$action.= "<script src='". base_url() . PATH_JS."modalEffects.js' type='text/javascript'></script>";
					$action.= "<script src='". base_url() . PATH_JS."classie.js' type='text/javascript'></script>";
					$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				}
				
				$row[] = $action;
					
				$output['aaData'][] = $row;
			endforeach;
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}

		echo json_encode( $output );
	}
	
	public function get_employee_contract_record_list($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{

		$data['personal_info'] 				= $this->contract_record->get_employee_contract_record_info($id);
		$data['employee_id'] 				= $id;
		$data['module']						= $module;


		$resources = array();

		$resources 					= array();
		$resources['load_css'] 		= array('jquery-labelauty', 'jquery.dataTables');
		$resources['load_js'] 		= array('jquery-labelauty', 'jquery.dataTables.min');
		$resources['datatable'][]	= array('table_id' => 'table_employee_contract_record', 'path' => 'main/contract_record/get_employee_contract_record/'.$id . '/'. $module ,'advanced_filter' => true);
		$resources['load_modal'] 	= array(
							'modal_contract_record' 	=> array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_contract_record',
									'multiple'		=> true,
									'height'		=> '320px',
									'size'			=> 'sm',
									'title'			=> 'Contract Record'
							)
					);
		$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_contract_record',
						PROJECT_MAIN
					);

		$this->template->load('contract_record/employee_contract_record_list', $data, $resources);
	}

	public function get_employee_contract_record($id, $module)
	{

		try
		{
			$params = get_params();

			// $aColumns 	= array("A.*", "B.office_name", "C.pds_status_name");
			$bColumns 	= array("contract_number", "start_date", "end_date", "position");

			// $pds_info 	= $this->pds->get_pds_list($aColumns, $bColumns, $params);
			// $iTotal		= $this->pds->total_length();
			// $iFilteredTotal = $this->users->filtered_length($aColumns, $bColumns, $params);
			// $iTotal["cnt"]		= 3;
			// $iFilteredTotal["cnt"] = 1;

			// $output = array(
			// 	"sEcho" => intval($_POST['sEcho']),
			// 	"iTotalRecords" => $iTotal["cnt"],
			// 	"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
			// 	"aaData" => array()
			// );
			// $cnt = 0;

			rlog::info("here".$module);

			$contract_record = array(

				0 => array(
						'contract_number' => '001',
						'start_date'      => '01/01/2013',
						'end_date'        => '01/01/2014',
						'position'        => 'Utility Worker III',
					),
				1 => array(
						'contract_number' => '002',
						'start_date'      => '01/01/2013',
						'end_date'        => '01/01/2014',
						'position'        => 'Utility Worker III',
					),
				2 => array(
						'contract_number' => '003',
						'start_date'      => '01/01/2013',
						'end_date'        => '01/01/2014',
						'position'        => 'Utility Worker III',
					)
			);

			foreach ($contract_record as $aRow):
				$cnt++;
				$row = array();
				$action = "<div class='table-actions'>";

				$user_id = $aRow["contract_number"];
				$id = base64_url_encode($user_id);
				$salt = gen_salt();
				$token = in_salt($user_id, $salt);
				$url = $id."/".$salt."/".$token;
				$delete_action = 'content_delete("user","'.$id.'")';
				$img_src = (@getimagesize(base_url() . PATH_USER_UPLOADS . $aRow["photo"])) ? PATH_USER_UPLOADS . $aRow["photo"] : PATH_IMAGES . "avatar.jpg";

				for ( $i=0 ; $i<count($bColumns) ; $i++ )
				{
					$row[] =  $aRow[ $bColumns[$i] ];
				}

				
				
				if($module == MODULE_PERSONNEL_PORTAL) {
					$url_view  = ACTION_VIEW."/".$id."/".$token_edit."/".$salt."/".$module."/".$employee_id;
					$action   .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_contract_record' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_contract_record_init('".$url_view."')\"></a>";
				}else{
					$url_edit      = ACTION_EDIT."/".$id."/".$token_edit."/".$salt."/".$module."/".$employee_id;
					// $url_delete = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;
					//$url_edit    = ACTION_EDIT;
					$action        .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_contract_record' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_contract_record_init('".$url_edit."')\"></a>";
					// $action     .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_contract_record' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_contract_record_init('".$action_id."')\"></a>";
					// if($this->permission->check_permission(MODULE_USER, ACTION_DELETE))
					$action        .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}


				
				

				$action .= "</div>";
				
				$row[] = $action;

				$output['aaData'][] = $row;
			endforeach;


		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}

		echo json_encode( $output );
	}

	//public function modal_contract_record($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	public function modal_contract_record($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		try
		{
			$resources['load_css'] = array(CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js']  = array(JS_SELECTIZE, JS_DATETIMEPICKER, 'jquery.number.min');
			$data['action']        = $action;


			
			// if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			// {
			// 	throw new Exception($this->lang->line('err_invalid_request'));
			// }
			// if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			// {
			// 	throw new Exception($this->lang->line('err_unauthorized_access'));
			// }
			

			// $data ['module']               = $module;
			// $data ['action']               = $action;
			// $data ['salt']                 = $salt;
			// $data ['token']                = $token;
			// $data ['id']                   = $id;
			// $data ['employee_id']          = $employee_id;
			// $data['param_position']        = $this->contract_record->get_param_position();
			// $data['param_employment_type'] = $this->contract_record->get_param_employment_type();
			// $data['param_office']          = $this->contract_record->get_param_office();
			// $data['param_branch']          = $this->contract_record->get_param_branch();
			// $data['param_leave_type']      = $this->contract_record->get_param_leave_type();


			
			// if($action != ACTION_ADD)
			// 	{
				
			// 		$field                           = array("*") ;
			// 		$table                           = $this->contract_record->tbl_employee_contract_record;
			// 		$where                           = array();
			// 		$key                             = $this->get_hash_key('contract_record_id');
			// 		$where[$key]                     = $id;
			// 		$employee_contract_record         = $this->contract_record->get_general_data($field, $table, $where, FALSE);
			// 		$data['employee_contract_record'] = $employee_contract_record;
			
			// 		$resources['single']	= array(
			// 			'position_name' 		=> $employee_contract_record['position'],
			// 			'employment_type_name' 	=> $employee_contract_record['service_status'],
			// 			'office_name' 			=> $employee_contract_record['station'],
			// 			'branch_name' 			=> $employee_contract_record['service_branch'],
			// 			'leave_type_name' 		=> $employee_contract_record['service_lwop']
			// 			);
				// }

		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		
		$this->load->view('contract_record/modals/modal_contract_record', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_param_plantilla () 
	{
		try 
		{
			
			$flag   = 0;
			$msg    = ERROR;
			$params = get_params();

			if(empty($params['select_id'])) { throw new Exception("Invalid request plantilla is required."); }
			
			$id            = $params['select_id'];
			
			$select_fields = array("B.position_name", "D.employment_type_name", "A.annual_salary");

			$tables = array(
				'main'	=> array(
					'table'		=> "param_plantilla",
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> "param_positions",
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.position_id = B.position_id',
				),
				't4'	=> array(
					'table'		=> "param_employment_status",
					'alias'		=> 'D',
					'type'		=> 'left join',
					'condition'	=> 'A.employment_type_id = D.employment_status_id',
				)
			);

			$where                 = array();
			$where["plantilla_id"] = $id;
			$data                  = $this->contract_record->get_specific_param_plantilla($select_fields, $tables, $where);
			$data['annual_salary'] = number_format($data['annual_salary'], 2);

			if(empty($data)) { throw new Exception("Invalid, No data associated on selected plantilla."); }
			
			$flag = 1;
			$msg  = SUCCES;

		} catch (Exception $e) {
			$msg =  $e->getMessage();
		}

		$info = array(
			"flag"  => $flag,
			"msg" 	=> $msg,
			"data"	=> $data
		);

		echo json_encode($info);
	}

	public function process_employee_contract_record()
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
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			// CHECK DATA VALIDATION
			$valid_data = $this->_validate_employee_contract_record($params);

			Main_Model::beginTransaction();

			$fields = array();

			$fields['service_start'] 	= $valid_data["service_start"];
			$fields['service_end'] 		= $valid_data["service_end"];
			$fields['position'] 		= $valid_data["position_name"];
			$fields['service_status'] 	= $valid_data["employment_type_name"];
			$fields['annual_salary'] 	= $valid_data["annual_salary"];
			$fields['station'] 			= $valid_data["office_name"];
			$fields['service_branch'] 	= $valid_data["branch_name"];
			$fields['service_lwop'] 	= $valid_data["leave_type_name"];
			$fields['service_date'] 	= $valid_data["service_date"];
			$fields['end_cause'] 		= $valid_data["end_cause"];

			if($action == ACTION_ADD)
			{

				$field                   = array("employee_id") ;
				$table                   = $this->contract_record->tbl_employee_personal_info;
				$where                   = array();
				$key                     = $this->get_hash_key('employee_id');
				$where[$key]             = $employee_id;
				$employee_contract_record = $this->contract_record->get_general_data($field, $table, $where, FALSE);
				
				
				$fields['employee_id']   = $employee_contract_record['employee_id'];
				$table                   = $this->contract_record->tbl_employee_contract_record;
				$employee_system_id      = $this->contract_record->insert_general_data($table,$fields,TRUE);
				
				$audit_table[]           = $this->contract_record->tbl_employee_contract_record;
				$audit_schema[]          = DB_MAIN;
				$prev_detail[]           = array();
				$curr_detail[]           = array($fields);
				$audit_action[]          = AUDIT_INSERT;	
				
				$activity                = "%s has been added to employee records.";
				$audit_activity          = sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);
				
				$status                  = true;
				$message                 = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				// $fields['last_modified_by']		= $this->log_user_id;
				// $fields['last_modified_date']	= date("Y-m-d H:i:s");

				$field          = array("*") ;
				$table          = $this->contract_record->tbl_employee_contract_record;
				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $id;
				$personal_info  = $this->contract_record->get_general_data($field, $table, $where, FALSE);
				
				
				$where          = array();
				$key            = $this->get_hash_key('contract_record_id');
				$where[$key]    = $id;
				$table          = $this->contract_record->tbl_employee_contract_record;
				
				$this->contract_record->update_general_data($table,$fields,$where);
				
				$audit_table[]  = $this->contract_record->tbl_employee_contract_record;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($personal_info);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				$activity       = "%s has been Updated.";
				$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
				
				
				$status         = true;
				$message        = $this->lang->line('data_updated');
			}
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
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

	public function process_contract_record_appointment()
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
			$employee_id	= $params['employee_id'];

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			// Rlog::info(json_encode($params));
			// exit();
			// CHECK DATA VALIDATION
			$valid_data = $this->_validate_employee_contract_record_appointment($params);

			Main_Model::beginTransaction();

			$fields                     = array();

			$fields['service_start']    = $valid_data["service_start"];
			$fields['service_end']      = "0000-00-00";
			$fields['active_plantilla'] = "Y";
			$fields['station']          = $valid_data["office_name"];
			$fields['service_branch']   = $valid_data["branch_name"];
			$fields['service_lwop']     = $valid_data["leave_type_name"];
			$fields['plantilla']        = $valid_data["plantilla"];

			if($action == ACTION_ADD)
			{

				$field                   = array("employee_id") ;
				$table                   = $this->contract_record->tbl_employee_personal_info;
				$where                   = array();
				$key                     = $this->get_hash_key('employee_id');
				$where[$key]             = $employee_id;
				$employee_contract_record = $this->contract_record->get_general_data($field, $table, $where, FALSE);
				
				
				$fields['employee_id']   = $employee_contract_record['employee_id'];
				$table                   = $this->contract_record->tbl_employee_contract_record;
				$employee_system_id      = $this->contract_record->insert_general_data($table,$fields,TRUE);
				
				$audit_table[]           = $this->contract_record->tbl_employee_contract_record;
				$audit_schema[]          = DB_MAIN;
				$prev_detail[]           = array();
				$curr_detail[]           = array($fields);
				$audit_action[]          = AUDIT_INSERT;	
				
				$activity                = "%s has been added to employee records.";
				$audit_activity          = sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);
				
				$status                  = true;
				$message                 = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field          = array("*") ;
				$table          = $this->contract_record->tbl_employee_contract_record;
				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $id;
				$personal_info  = $this->contract_record->get_general_data($field, $table, $where, FALSE);
				
				
				$where          = array();
				$key            = $this->get_hash_key('contract_record_id');
				$where[$key]    = $id;
				$table          = $this->contract_record->tbl_employee_contract_record;
				
				$this->contract_record->update_general_data($table,$fields,$where);
				
				$audit_table[]  = $this->contract_record->tbl_employee_contract_record;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($personal_info);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	
				
				$activity       = "%s has been Updated.";
				$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
				
				
				$status         = true;
				$message        = $this->lang->line('data_updated');
			}
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
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
			$employee_id 	= $url_explode[5];


			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
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
			$table						= $this->service_record->tbl_employee_service_record;
			$where						= array();
			$key 						= $this->get_hash_key('service_record_id');
			$where[$key]				= $id;
			$service_record 			= $this->service_record->get_general_data($field, $table, $where, FALSE);

			//DELETE DATA
			$where					= array();
			$key 					= $this->get_hash_key('service_record_id');
			$where[$key]			= $id;
			$table 					= $this->service_record->tbl_employee_service_record;
			
			$this->service_record->delete_general_data($table,$where);
			
			$audit_table[]				= $this->service_record->tbl_employee_service_record;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($service_record);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$activity 					= "%s has been deleted.";
			$audit_activity 			= sprintf($activity, $prev_data['position']);
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
			"table_id" 				=> 'table_employee_service_record',
			"path"					=> PROJECT_MAIN . '/service_record/get_employee_service_record/'.$employee_id,
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}

	private function _validate_employee_service_record($params)
	{
		try
		{
			//SPECIFY HERE INPUT NAME FROM USER
			//ALL REQUIRED FIELDS
			$fields = array();
			$fields['service_start'] 			= "Service Start";
			$fields['service_end'] 				= "Service End";
			$fields['position_name'] 			= "Designation";
			$fields['employment_type_name'] 	= "Status";
			$fields['annual_salary'] 			= "Annual Salary";
			$fields['office_name'] 				= "Station/Place of Assignment";
			$fields['branch_name'] 				= "Branch";
			$fields['service_date'] 			= "Separation Date";
			$fields['end_cause'] 				= "Separation Cause";


			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_employee_service_record($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_input_employee_service_record($params)
	{
		try
		{
			$validation['service_start'] = array(
					'data_type' => 'date',
					'name'		=> 'Service Start',
			);
			$validation['service_end'] = array(
					'data_type' => 'date',
					'name'		=> 'Service Start'
			);
			$validation['position_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Designation',
					'max_len'	=> 1
			);
			$validation['employment_type_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Status',
					'max_len'	=> 1
			);
			$validation['annual_salary'] = array(
					'data_type' => 'amount',
					'name'		=> 'Annual Salary',
					'decimal'	=> 2
			);
			$validation['office_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Station/Place of Assignment',
					'max_len'	=> 1
			);
			$validation['branch_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Branch',
					'max_len'	=> 1
			);
			$validation['leave_type_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'L/V ABS W/O PAY',
					'max_len'	=> 1
			);
			$validation['service_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Separation Date'
			);
			$validation['end_cause'] = array(
					'data_type' => 'string',
					'name'		=> 'Separation Cause',
					'max_len'	=> 15,
					'min_len'	=> 3
			);

			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_employee_service_record_appointment($params)
	{
		try
		{
			//SPECIFY HERE INPUT NAME FROM USER
			//ALL REQUIRED FIELDS
			$fields = array();

			$fields['service_start'] = "Service Start";
			$fields['office_name']   = "Station/Place of Assignment";
			$fields['branch_name']   = "Branch";
			$fields['plantilla']     = "Plantilla Item Number";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_employee_service_record_appointment($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function _validate_input_employee_service_record_appointment($params)
	{
		try
		{
			$validation['service_start'] = array(
				'data_type' => 'date',
				'name'		=> 'Service Start',
			);
			$validation['office_name'] = array(
				'data_type' => 'digit',
				'name'		=> 'Station/Place of Assignment',
				'max_len'	=> 1
			);
			$validation['branch_name'] = array(
				'data_type' => 'digit',
				'name'		=> 'Branch',
				'max_len'	=> 1
			);
			$validation['plantilla'] = array(
				'data_type' => 'digit',
				'name'		=> 'Plantilla Item Number',
				'max_len'	=> 1
			);
			$validation['leave_type_name'] = array(
				'data_type' => 'digit',
				'name'		=> 'Leave Type Name',
				'max_len'	=> 1
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
