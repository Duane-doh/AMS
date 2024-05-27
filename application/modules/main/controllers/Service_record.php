<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_record extends Main_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('service_record_model', 'service_record');
	}

	public function index()
	{
		$data = $resources = array();

		$resources['load_css']    = array(CSS_DATATABLE);
		$resources['load_js']     = array(JS_DATATABLE);
		$resources['datatable'][] = array('table_id' => 'table_service_record', 'path' => 'main/service_record/get_service_record_list', 'advanced_filter' => true);

		$this->template->load('service_record/service_record', $data, $resources);
	}

	//GET ALL LIST OF EMPLOYEE
	public function get_service_record_list()
	{
		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.employee_id","A.agency_employee_id", "A.first_name", "A.last_name", "C.office_name", "D.status_name");
			$bColumns       = array("A.agency_employee_id", "A.last_name", "A.first_name", "C.office_name", "D.status_name");
			
			$pds_records    = $this->service_record->get_employee_list($aColumns, $bColumns, $params);
			$iTotal         = $this->service_record->total_length();
			$iFilteredTotal = $this->service_record->filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module = MODULE_USER;
			
			$cnt = 0;
			foreach ($pds_records as $aRow):
				$cnt++;
				$row    = array();
				$action = "";
				

				$id           = $this->hash($aRow['employee_id']);
				
				$salt         = gen_salt();
				$token_view   = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view     = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				
				$row[]        = $aRow['agency_employee_id'];
				$row[]        = $aRow['last_name'];
				$row[]        = $aRow['first_name'];
				$row[]        = $aRow['office_name'];
				$row[]        = $aRow['status_name'];
				
				$action       = "<div class='table-actions'>";

				
				// if($permission_view)
				$action 	.= "<a href='".base_url() . PROJECT_MAIN ."/service_record/get_employee_service_record_list/".$url_view."' class='view tooltipped' data-tooltip='View' data-position='bottom' data-delay='50' onclick=''></a>";
				
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
	
	//GET THE LIST SERVICE RECORDS OF THE SPECIFIC EMPLOYEE
	public function get_employee_service_record_list($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{

		$data['personal_info']    = $this->service_record->get_employee_service_record_info($id);
		$data['employee_id']      = $id;
		$data['module']           = $module;
		$resources                = array();
		
		$resources                = array();
		$resources['load_css']    = array('jquery-labelauty', 'jquery.dataTables');
		$resources['load_js']     = array('jquery-labelauty', 'jquery.dataTables.min');
		$resources['datatable'][] = array('table_id' => 'table_employee_service_record', 'path' => 'main/service_record/get_employee_service_record/'.$id, 'advanced_filter' => true);
		
		$resources['load_modal'] 	= array(
							'modal_service_record' 	=> array(
									'controller'	=> __CLASS__,
									'module'		=> PROJECT_MAIN,
									'method'		=> 'modal_service_record',
									'multiple'		=> true,
									'height'		=> '320px',
									'size'			=> 'sm',
									'title'			=> 'Service Record'
							),
							'modal_service_record_appointment' => array(
									'controller'			   => __CLASS__,
									'module'				   => PROJECT_MAIN,
									'method'		           => 'modal_service_record_appointment',
									'multiple'		           => true,
									'height'		           => '450px',
									'size'			           => 'sm',
									'title'			           => 'Appointment'
							),	
							'modal_service_record_request' => array(
									'controller'			   => __CLASS__,
									'module'				   => PROJECT_MAIN,
									'method'		           => 'modal_service_record_request',
									'multiple'		           => true,
									'height'		           => '320px',
									'size'			           => 'sm',
									'title'			           => 'Request'
							)
					);
		$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_service_record',
						PROJECT_MAIN
					);

		$this->template->load('service_record/employee_service_record_list', $data, $resources);
	}

	//GET THE DETAILED INFORMATION OF SPECIFIC SERVICE RECORD
	public function get_employee_service_record($id)
	{
		try
		{

			$params                  = get_params();
			
			$aColumns                = array("A.service_record_id", "A.employee_id", "A.service_start", "A.service_end", "CONCAT(B.first_name, ' ', B.middle_name, '. ', B.last_name, ' ', B.ext_name) as name", "D.employment_type_name", "CONCAT(E.office_name, ' ', E.office_address) as station", "IF(A.position = 0, G.position_name,C.position_name) as final_position", "A.plantilla_id");
			$bColumns                = array("A.service_start", "A.service_end","C.position_name", "D.employment_type_name","CONCAT(E.office_name, ' ', E.office_address) as station");
			
			//GET ALL THE LIST OF SERVICE RECORD OF THE EMPLOYEE
			$employee_service_record = $this->service_record->get_employee_service_record($aColumns, $bColumns, $params, $id);
			$iTotal                  = $this->service_record->filtered_length_employee_service_record();
			$iFilteredTotal          = $this->service_record->total_length_employee_service_record($aColumns, $bColumns, $params);
			
			$output = array(
			"sEcho"                  => intval($_POST['sEcho']),
			"iTotalRecords"          => $iTotal["cnt"],
			"iTotalDisplayRecords"   => $iFilteredTotal["cnt"],
			"aaData"                 => array()
			);

			$module = MODULE_USER;
			$cnt    = 0;

			$employee_id   = $id;

			foreach ($employee_service_record as $aRow):
				$cnt++;
				$row           = array();
				$action        = "";
				
				$id            = $this->hash($aRow['service_record_id']);
				$salt          = gen_salt();
				$token_view    = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit    = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_delete  = in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view      = ACTION_VIEW."/".$id."/".$token_view."/".$salt."/".$module."/".$employee_id;
				$url_edit      = ACTION_EDIT."/".$id."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				$url_delete    = ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module."/".$employee_id;
				
				$row[]         = $aRow["service_start"];
				$row[]         = $aRow["service_end"];
				$row[]         = $aRow["final_position"];
				$row[]         = $aRow["station"];
				$row[]         = $aRow["employment_type_name"];
			
				$action        = "<div class='table-actions'>";
				
				if(EMPTY($aRow["plantilla_id"]))
				{
					$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_service_record' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_service_record_init('".$url_view."')\"></a>";
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_service_record' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_service_record_init('".$url_edit."')\"></a>";
				}
				else
				{
					$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-modal='modal_service_record_appointment' data-tooltip='View' data-position='bottom' data-delay='50' onclick=\"modal_service_record_appointment_init('".$url_view."')\"></a>";
					$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-modal='modal_service_record_appointment' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_service_record_appointment_init('".$url_edit."')\"></a>";
				}

				$delete_action = 'content_delete("Service Record", "'.$url_delete.'")';
				// if($permission_delete)
				$action        .= "<a href='javascript:;' onclick='".$delete_action."' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				$action        .= "</div>";
				if($cnt == count($employee_service_record)){
					$action.= "<script src='". base_url() . PATH_JS."modalEffects.js' type='text/javascript'></script>";
					$action.= "<script src='". base_url() . PATH_JS."classie.js' type='text/javascript'></script>";
					$action.= "<script>$(function(){ $('.tooltipped').tooltip({delay: 50});	});</script>";
				}
				
				$row[]              = $action;
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

	//MODAL FOR ADDING OTHER SERVICE (OUTSIDE DOH)
	public function modal_service_record($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		try
		{
			$resources['load_css'] = array(CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js']  = array(JS_SELECTIZE, JS_DATETIMEPICKER, 'jquery.number.min');
			
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$data ['module']               = $module;
			$data ['action']               = $action;
			$data ['salt']                 = $salt;
			$data ['token']                = $token;
			$data ['id']                   = $id;
			$data ['employee_id']          = $employee_id;
			$data['param_position']        = $this->service_record->get_param_position();
			$data['param_employment_type'] = $this->service_record->get_param_employment_type();
			$data['param_office']          = $this->service_record->get_param_office();
			$data['param_branch']          = $this->service_record->get_param_branch();
			$data['param_leave_type']      = $this->service_record->get_param_leave_type();

			if($action != ACTION_ADD)
				{
				
					$field                           = array("*") ;
					$table                           = $this->service_record->tbl_employee_service_record;
					$where                           = array();
					$key                             = $this->get_hash_key('service_record_id');
					$where[$key]                     = $id;
					$employee_service_record         = $this->service_record->get_general_data($field, $table, $where, FALSE);
					$data['employee_service_record'] = $employee_service_record;
			
					$resources['single']	= array(
						'position_name'        => $employee_service_record['position'],
						'employment_type_name' => $employee_service_record['service_status'],
						'office_name'          => $employee_service_record['station'],
						'branch_name'          => $employee_service_record['service_branch'],
						'leave_type_name'      => $employee_service_record['service_lwop']
						);
				}

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
		
		$this->load->view('service_record/modals/modal_service_record', $data);
		$this->load_resources->get_resource($resources);
	}

	//PROCESS FOR ADDING OTHER SERVICE (OUTSIDE DOH)
	public function process_employee_service_record()
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
			$valid_data = $this->_validate_employee_service_record($params);

			Main_Model::beginTransaction();

			$fields = array();

			$fields['service_start']  = $valid_data["service_start"];
			$fields['service_end']    = $valid_data["service_end"];
			$fields['position']       = $valid_data["position_name"];
			$fields['service_status'] = $valid_data["employment_type_name"];
			$fields['annual_salary']  = $valid_data["annual_salary"];
			$fields['station']        = $valid_data["office_name"];
			$fields['service_branch'] = $valid_data["branch_name"];
			$fields['service_lwop']   = $valid_data["leave_type_name"];
			$fields['service_date']   = $valid_data["service_date"];
			$fields['end_cause']      = $valid_data["end_cause"];

			if($action == ACTION_ADD)
			{

				$field                   = array("employee_id") ;
				$table                   = $this->service_record->tbl_employee_personal_info;
				$where                   = array();
				$key                     = $this->get_hash_key('employee_id');
				$where[$key]             = $employee_id;
				$employee_service_record = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				
				$fields['employee_id']   = $employee_service_record['employee_id'];
				$table                   = $this->service_record->tbl_employee_service_record;
				$employee_system_id      = $this->service_record->insert_general_data($table,$fields,TRUE);
				
				$audit_table[]           = $this->service_record->tbl_employee_service_record;
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
				$table          = $this->service_record->tbl_employee_service_record;
				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $id;
				$personal_info  = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				
				$where          = array();
				$key            = $this->get_hash_key('service_record_id');
				$where[$key]    = $id;
				$table          = $this->service_record->tbl_employee_service_record;
				
				$this->service_record->update_general_data($table,$fields,$where);
				
				$audit_table[]  = $this->service_record->tbl_employee_service_record;
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

	//VALIDATE ALL REQUIRED FIELDS IN OTHER SERVICE MODAL (OUTSIDE DOH)
	private function _validate_employee_service_record($params)
	{
		try
		{
			//SPECIFY HERE INPUT NAME FROM USER
			//ALL REQUIRED FIELDS
			$fields                         = array();
			$fields['service_start']        = "Service Start";
			$fields['service_end']          = "Service End";
			$fields['position_name']        = "Position";
			$fields['employment_type_name'] = "Employment Status";
			$fields['annual_salary']        = "Annual Salary";
			$fields['office_name']          = "Station/Place of Assignment";
			$fields['branch_name']          = "Branch";
			$fields['service_date']         = "Separation Date";
			$fields['end_cause']            = "Separation Cause";


			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_employee_service_record($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	//VALIDATE ALL INPUT DATA IN OTHER SERVICE MODAL (OUTSIDE DOH)
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

	//MODAL FOR ADDING SERVICE (INSIDE DOH)
	public function modal_service_record_appointment($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		try
		{
			$resources['load_css'] = array(CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js']  = array(JS_SELECTIZE, JS_DATETIMEPICKER, 'jquery.number.min');
			
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data ['module']                  = $module;
			$data ['action']                  = $action;
			$data ['salt']                    = $salt;
			$data ['token']                   = $token;
			$data ['id']                      = $id;
			$data ['employee_id']             = $employee_id;
			
			$data['param_employment_type']    = $this->service_record->get_param_employment_type();
			$data['param_nature_appointment'] = $this->service_record->get_param_nature_appointment();
			$data['param_plantilla']          = $this->service_record->get_param_plantilla();
			$data['param_office']             = $this->service_record->get_param_office();
			$data['param_leave_type']         = $this->service_record->get_param_leave_type();

			
			$field                      = array("plantilla_id");
			$table                      = $this->service_record->tbl_employee_service_record;
			$where                      = array();
			$key                        = $this->get_hash_key('employee_id');
			$where[$key]                = $employee_id;
			$employee_service_record    = $this->service_record->get_general_data($field, $table, $where, TRUE);
			$data['employee_plantilla'] = $employee_service_record;

			if($action != ACTION_ADD)
				{
				
					$field                           = array("*") ;
					$table                           = $this->service_record->tbl_employee_service_record;
					$where                           = array();
					$key                             = $this->get_hash_key('service_record_id');
					$where[$key]                     = $id;
					$employee_service_record         = $this->service_record->get_general_data($field, $table, $where, FALSE);
					$data['employee_service_record'] = $employee_service_record;
			

					$resources['single']  = array(
						'plantilla'          => $employee_service_record['plantilla_id'],
						'office_name'        => $employee_service_record['station'],
						'employment_type'    => $employee_service_record['service_status'],
						'nature_appointment' => $employee_service_record['nature_appointment_id'],
						'leave_type_name'    => $employee_service_record['service_lwop']
					);

				}

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
		
		$this->load->view('service_record/modals/modal_service_record_appointment', $data);
		$this->load_resources->get_resource($resources);
	}

	//PROCESS FOR ADDING SERVICE APPOINTMENT (INSIDE DOH)
	public function process_service_record_appointment()
	{
		try
		{
			$status       = FALSE;
			$message      = "";
			$reload_url   = "";
			
			$params       = get_params();
			$action       = $params['action'];
			$token        = $params['token'];
			$salt         = $params['salt'];
			$id           = $params['id'];
			$module       = $params['module'];
			$employee_id  = $params['employee_id'];

			$active_flag  = $params['active_flag'];

			Main_Model::beginTransaction();

			$field1                  = array("employee_id") ;
			$table1                  = $this->service_record->tbl_employee_personal_info;
			$where1                  = array();
			$key1                    = $this->get_hash_key('employee_id');
			$where1[$key1]           = $employee_id;
			$employee_service_record = $this->service_record->get_general_data($field1, $table1, $where1, FALSE);

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			if (!EMPTY($active_flag))
			{
				$employee_id  = $employee_service_record['employee_id'];

				// CHECK DATA VALIDATION
				$valid_data = $this->_validate_employee_service_record_appointment($params);
				
				$field2                          = array("*") ;
				$table2                          = $this->service_record->tbl_employee_service_record;
				$where2                          = array();
				$where2['employee_id']           = $employee_id;
				$where2['active_plantilla']      = "Y";
				$employee_service_record         = $this->service_record->get_general_data($field2, $table2, $where2, FALSE);
				
				$salary_step                     = $params['salary_step'];
				$salary_grade                    = $employee_service_record['salary_grade']; 
				$date                            = date('Y-m-d');
				
				$select_fields                   = array("max(effectivity_date) date, amount, salary_schedule_id");
				$tables                          = $this->service_record->tbl_param_salary_schedule;
				$where                           = array();
				$where['effectivity_date']       = array($date, array("<="));
				$latest_date                     = $this->service_record->get_specific_param_plantilla($select_fields, $tables, $where);
				
				$field                           = array("amount") ;
				$table                           = $this->service_record->tbl_param_salary_schedule;
				$where                           = array();
				$where['salary_grade']           = $salary_grade;
				$where['salary_step']            = $salary_step;
				$where['effectivity_date']       = $latest_date['date'];
				$salary_schedule                 = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				$fields['service_start']         = $valid_data["service_start_step"];
				$fields['service_end']           = "0000-00-00";
				$fields['active_plantilla']      = "Y";
				$fields['annual_salary']         = $salary_schedule['amount'] * 12;
				$fields['station']               = $employee_service_record["station"];
				$fields['service_branch']        = $employee_service_record['service_branch'];
				$fields['service_status']        = $employee_service_record['service_status'];
				$fields['nature_appointment_id'] = $employee_service_record['nature_appointment_id'];
				$fields['salary_grade']          = $employee_service_record['salary_grade'];
				$fields['salary_step']           = $valid_data['salary_step'];
				$fields['plantilla_id']          = $employee_service_record['plantilla_id'];
				$fields['publication_date']      = $employee_service_record["publication_date"];

			}
			else
			{
				$field       = array("branch_id") ;
				$table       = $this->service_record->tbl_param_branch;
				$where       = array();
				$key         = "branch_name";
				$where[$key] = "National";
				$branch      = $this->service_record->get_general_data($field, $table, $where, FALSE);
	           	 
				// CHECK DATA VALIDATION
				$valid_data = $this->_validate_employee_service_record_appointment($params);

				$fields                      = array();
				
				$fields['service_end']       = "0000-00-00";
				$fields['active_plantilla']  = "Y";
				$fields['service_branch']    = $branch['branch_id'];
				$fields['service_start']     = $valid_data["service_start"];
				$fields['station']           = $valid_data["office_name"];
				$fields['service_status']    = $valid_data["employment_type"];
				$fields['plantilla_id']      = $valid_data["plantilla"];
				$fields['publication_date']  = $valid_data["publication_date"];
				$fields['publication_place'] = $valid_data["publication_place"];
				$fields['annual_salary']     = $params['annual_salary'];
				$fields['salary_grade']      = $params['salary_grade'];
				$fields['salary_step']       = $params['salary_step_info'];
				$fields['service_lwop']      = $valid_data['leave_type_name'];
				$fields['end_cause']         = $valid_data['separation_cause'];

				if($params['fix_var_type'] == 'appointment')
				{
					$fields['nature_appointment_id'] = $valid_data['nature_appointment'];
					$fields['previous_appointee']    = $valid_data['previous_appointee'];
					$fields['previous_cause']        = $valid_data['previous_cause'];

				}else{
					$fields['personnel_movement']    = $valid_data['personnel_movement'];
				}

			}


			if($action == ACTION_ADD)
			{
				
				$table3                      = $this->service_record->tbl_employee_service_record;
				$fields3['active_plantilla'] = 'N';
				$where3                      = array();
				$key3                        = 'active_plantilla';
				$where3[$key3]               = 'Y';
				$this->service_record->update_general_data($table3,$fields3,$where3);
				
				$fields['employee_id']       = $employee_service_record['employee_id'];
				$table                       = $this->service_record->tbl_employee_service_record;
				$employee_system_id          = $this->service_record->insert_general_data($table,$fields,TRUE);
				
				$audit_table[]               = $this->service_record->tbl_employee_service_record;
				$audit_schema[]              = DB_MAIN;
				$prev_detail[]               = array();
				$curr_detail[]               = array($fields);
				$audit_action[]              = AUDIT_INSERT;	
				
				$activity                    = "%s has been added to employee records.";
				$audit_activity              = sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);
				
				$status                      = true;
				$message                     = $this->lang->line('data_saved');
			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field          = array("*") ;
				$table          = $this->service_record->tbl_employee_service_record;
				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $id;
				$personal_info  = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				
				$where          = array();
				$key            = $this->get_hash_key('service_record_id');
				$where[$key]    = $id;
				$table          = $this->service_record->tbl_employee_service_record;
				
				$this->service_record->update_general_data($table,$fields,$where);
				
				$audit_table[]  = $this->service_record->tbl_employee_service_record;
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

	//VALIDATE ALL REQUIRED FIELDS IN APPOINTMENT MODAL (INSIDE DOH)
	private function _validate_employee_service_record_appointment($params)
	{
		try
		{
			//SPECIFY HERE INPUT NAME FROM USER
			//ALL REQUIRED FIELDS
			$fields = array();

			if($params['fix_var_type'] === 'appointment')
			{
				$fields['nature_appointment'] = "Nature Of Employment";
			}
			else
			{
				$fields['personnel_movement'] = "Personnel movement";
			}

			if(!EMPTY($params['active_flag']))
			{
				$fields['service_start_step'] = "Service Start";
				$fields['salary_step']        = "Salary Step";
			}
			else
			{
				
				$fields['service_start']      = "Service Start";
				$fields['office_name']        = "Station/Place of Assignment";
				$fields['plantilla']          = "Plantilla Item Number";
				$fields['publication_date']   = "Appointing Date";
				$fields['employment_type']    = "Employment Status";
				
			}

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_employee_service_record_appointment($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	//VALIDATE ALL INPUT DATA IN APPOINTMENT MODAL (OUTSIDE DOH)
	private function _validate_input_employee_service_record_appointment($params)
	{
		try
		{
			
			if($params['fix_var_type'] == 'appointment')
			{
				$validation['nature_appointment'] = array(
					'data_type' => 'digit',
					'name'		=> 'Nature of appointment',
					'max_len'   => 1
				);
				$validation['previous_appointee'] = array(
					'data_type' => 'String',
					'name'		=> 'Previous appointee',
					'max_len'   => 45
				);
				$validation['previous_cause'] = array(
					'data_type' => 'String',
					'name'		=> 'Previous Cause',
					'max_len'   => 45
				);
			}
			else
			{
				$validation['personnel_movement'] = array(
					'data_type' => 'digit',
					'name'		=> 'Personnel movement',
					'max_len'   => 1
				);
			}

			if(!EMPTY($params['active_flag']))
			{
				$validation['salary_step'] = array(
					'data_type' => 'digit',
					'name'		=> 'Service Start',
					'max_len'   => 1
				);
				$validation['service_start_step'] = array(
					'data_type' => 'date',
					'name'		=> 'Service Start'
				);
			}
			else
			{
				$validation['service_start'] = array(
					'data_type' => 'date',
					'name'		=> 'Service Start'
				);
				$validation['office_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Station/Place of Assignment',
					'max_len'	=> 1
				);
				$validation['plantilla'] = array(
					'data_type' => 'digit',
					'name'		=> 'Plantilla Item Number',
					'max_len'	=> 1
				);
				$validation['publication_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Appointing Date'
				);
				$validation['employment_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Employment Type',
					'max_len'	=> 1
				);
				$validation['leave_type_name'] = array(
					'data_type' => 'digit',
					'name'		=> 'Leave Type',
					'max_len'	=> 1
				);
				$validation['publication_place'] = array(
					'data_type' => 'string',
					'name'		=> 'Publication Place',
					'max_len'	=> 45
				);
				$validation['publication_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Publication Date'
				);
				$validation['separation_cause'] = array(
					'data_type' => 'String',
					'name'		=> 'Separation Cause',
					'max_len'	=> 45
				);
			}
			
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	//LIST OF ALL REQUESTS MADE BY SPECIFIC EMPLOYEE (EMPLOYEE PORTAL)
	public function modal_service_record_request($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL)
	{
		try
		{
			
			
			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$resources['load_modal']  = array(
					'modal_service_record_document' => array(
							'controller' => __CLASS__,
							'module'     => PROJECT_MAIN,
							'method'     => 'modal_service_record_document',
							'multiple'   => true,
							'height'     => '300px',
							'size'       => 'sm',
							'title'      => 'Supporting Documents'
					)
			);
			 $resources['loaded_init']  = array( 
                'ModalEffects.re_init();'
            );

			$this->load->view('service_record/modals/modal_service_record_request', $data);
			$this->load_resources->get_resource($resources);
			
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
	}

	//PROCESS FOR REQUESTS
	public function process_service_record_request()
	{
		try
		{
			// $status 		= FALSE;
			// $message		= "";
			// $reload_url 	= "";

			// $params			= get_params();
			// $action			= $params['action'];
			// $token			= $params['token'];
			// $salt			= $params['salt'];
			// $id				= $params['id'];
			// $module			= $params['module'];
			// $employee_id	= $params['employee_id'];

			// if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			// {
			// 	throw new Exception($this->lang->line('err_invalid_request'));
			// }
			// if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			// {
			// 	throw new Exception($this->lang->line('err_unauthorized_access'));
			// }

			// // Rlog::info(json_encode($params));
			// // exit();
			// // CHECK DATA VALIDATION
			// $valid_data = $this->_validate_employee_service_record_appointment($params);

			// Main_Model::beginTransaction();

			// $fields                     = array();

			// $fields['service_start']    = $valid_data["service_start"];
			// $fields['service_end']      = "0000-00-00";
			// $fields['active_plantilla'] = "Y";
			// $fields['station']          = $valid_data["office_name"];
			// $fields['service_branch']   = $valid_data["branch_name"];
			// $fields['service_lwop']     = $valid_data["leave_type_name"];
			// $fields['plantilla']        = $valid_data["plantilla"];

			// if($action == ACTION_ADD)
			// {

			// 	$field                   = array("employee_id") ;
			// 	$table                   = $this->service_record->tbl_employee_personal_info;
			// 	$where                   = array();
			// 	$key                     = $this->get_hash_key('employee_id');
			// 	$where[$key]             = $employee_id;
			// 	$employee_service_record = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				
			// 	$fields['employee_id']   = $employee_service_record['employee_id'];
			// 	$table                   = $this->service_record->tbl_employee_service_record;
			// 	$employee_system_id      = $this->service_record->insert_general_data($table,$fields,TRUE);
				
			// 	$audit_table[]           = $this->service_record->tbl_employee_service_record;
			// 	$audit_schema[]          = DB_MAIN;
			// 	$prev_detail[]           = array();
			// 	$curr_detail[]           = array($fields);
			// 	$audit_action[]          = AUDIT_INSERT;	
				
			// 	$activity                = "%s has been added to employee records.";
			// 	$audit_activity          = sprintf($activity, $valid_data["first_name"] . " ".$valid_data["last_name"]);
				
			// 	$status                  = true;
			// 	$message                 = $this->lang->line('data_saved');
			// }
			// else
			// {
			// 	/*GET PREVIOUS DATA*/
			// 	$field          = array("*") ;
			// 	$table          = $this->service_record->tbl_employee_service_record;
			// 	$where          = array();
			// 	$key            = $this->get_hash_key('employee_id');
			// 	$where[$key]    = $id;
			// 	$personal_info  = $this->service_record->get_general_data($field, $table, $where, FALSE);
				
				
			// 	$where          = array();
			// 	$key            = $this->get_hash_key('service_record_id');
			// 	$where[$key]    = $id;
			// 	$table          = $this->service_record->tbl_employee_service_record;
				
			// 	$this->service_record->update_general_data($table,$fields,$where);
				
			// 	$audit_table[]  = $this->service_record->tbl_employee_service_record;
			// 	$audit_schema[] = DB_MAIN;
			// 	$prev_detail[]  = array($personal_info);
			// 	$curr_detail[]  = array($fields);
			// 	$audit_action[] = AUDIT_UPDATE;	
				
			// 	$activity       = "%s has been Updated.";
			// 	$audit_activity = sprintf($activity, $personal_info["first_name"] . " ".$personal_info["last_name"]);
				
				
			// 	$status         = true;
			// 	$message        = $this->lang->line('data_updated');
			// }
			// $this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			// Main_Model::commit();
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
		$data['status']  = TRUE; //$status;
		$data['message'] = $this->lang->line('data_saved');//$message;

		echo json_encode($data);
	}

	//LIST OF ALL SUPPORTING DOCUMENT RESPECTIVELY TO THE SERVICE RECORD
	public function modal_service_record_document()
	{
		try
		{
			$data = array();
				
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE);
	
			$this->load->view('service_record/modals/modal_service_record_document', $data);
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

	//LIST OF ALL PLANTILLA
	public function get_param_plantilla () 
	{
		try 
		{
			
			$flag   = 0;
			$msg    = ERROR;
			$params = get_params();

			if(empty($params['select_id'])) { throw new Exception("Invalid request plantilla is required."); }
			
			$id            = $params['select_id'];
			$date 		   = date('Y-m-d');

			$select_fields				= array("max(effectivity_date) date");
			$tables 					= $this->service_record->tbl_param_salary_schedule;
			$where 						= array();
			$where['effectivity_date'] 	= array($date, array("<="));
			$latest_date				= $this->service_record->get_specific_param_plantilla($select_fields, $tables, $where);
			
			$select_fields = array("B.appointment_status_id, B.appointment_status_name, C.position_id, C.position_name, C.salary_grade, C.salary_step, D.designation_id, D.designation_name, E.amount");

			$tables = array(
				'main'	=> array(
					'table'		=> "param_plantilla",
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> "param_appointment_status",
					'alias'		=> 'B',
					'type'		=> 'left join',
					'condition'	=> 'A.appointment_status_id = B.appointment_status_id',
				),
				't3'	=> array(
					'table'		=> "param_positions",
					'alias'		=> 'C',
					'type'		=> 'left join',
					'condition'	=> 'A.position_id = C.position_id',
				),
				't4'	=> array(
					'table'		=> "param_designations",
					'alias'		=> 'D',
					'type'		=> 'left join',
					'condition'	=> 'A.designation_id = D.designation_id',
				),
				't5'	=> array(
					'table'		=> "param_salary_schedule",
					'alias'		=> 'E',
					'type'		=> 'left join',
					'condition'	=> 'C.salary_grade = E.salary_grade and C.salary_step = E.salary_step',
				)
			);

			$where                       = array();
			$where["A.plantilla_id"]     = $id;
			$where['E.effectivity_date'] = $latest_date['date'];
			
			$data                        = $this->service_record->get_specific_param_plantilla($select_fields, $tables, $where);

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

	//DELETE THE SPECIFIC SERVICE RECORD
	public function delete_service_record()
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
			$employee_id = $url_explode[5];


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
			$prev_data      = array() ;
			/*GET PREVIOUS DATA*/
			$field          = array("*") ;
			$table          = $this->service_record->tbl_employee_service_record;
			$where          = array();
			$key            = $this->get_hash_key('service_record_id');
			$where[$key]    = $id;
			$service_record = $this->service_record->get_general_data($field, $table, $where, FALSE);
			
			//DELETE DATA
			$where          = array();
			$key            = $this->get_hash_key('service_record_id');
			$where[$key]    = $id;
			$table          = $this->service_record->tbl_employee_service_record;
			
			$this->service_record->delete_general_data($table,$where);
			
			$audit_table[]  = $this->service_record->tbl_employee_service_record;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($service_record);
			$curr_detail[]  = array();
			$audit_action[] = AUDIT_DELETE;
			$activity       = "%s has been deleted.";
			$audit_activity = sprintf($activity, $prev_data['position']);
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			
			Main_Model::commit();
			$msg  = $this->lang->line('data_deleted');
			$flag = 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		
		$response = array(
			"flag"            => $flag,
			"msg"             => $msg,
			"reload"          => 'datatable',
			"table_id"        => 'table_employee_service_record',
			"path"            => PROJECT_MAIN . '/service_record/get_employee_service_record/'.$employee_id,
			"advanced_filter" => true
		);

		echo json_encode($response);
	}

	
}


/* End of file Service_record.php*/
/* Location: ./application/modules/main/controllers/Service_record.php */
