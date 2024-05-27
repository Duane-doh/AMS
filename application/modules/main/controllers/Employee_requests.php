<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employee_requests extends Main_Controller {
	
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_requests_model', 'requests');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	
	public function index()
	{
		$data =  array();
		$resources = array();
		$resources['load_modal'] = array(
				'modal_employee_request' => array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal',
						'multiple'		=> true,
						'height'		=> '480px',
						'size'			=> 'sm',
						'title'			=> 'Request'
				),
				'modal_supporting_docs'	=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_supporting_docs',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'md',
						'title'			=> 'Supporting Documents'
				)
		);

		$resources['load_delete'] 	= array(
								__CLASS__,
								'delete_request',
								PROJECT_MAIN
							);
		$resources['load_css'] 		= array(CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_DATATABLE);
		$resources['datatable'][]	= array('table_id' => 'table_employee_request', 'path' => 'main/employee_requests/get_employee_requests', 'advanced_filter' => true);
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "My Portal"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/employee_requests";
		$key					= "Requests"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/employee_requests";
		set_breadcrumbs($breadcrumbs, TRUE);	
		$this->template->load('employee_requests', $data, $resources);
		
	}
	public function get_employee_requests()
	{

		try
		{
			$params = get_params();
			$params['sSortDir_0'] = 'desc'; //ncocampo: sorting desc

			$aColumns 	= array("A.employee_id","A.request_id","A.request_status_id","A.request_code", "B.request_type_id", "B.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y') as date_requested", "C.request_status_name", "D.request_sub_id");
			$bColumns 	= array("A.request_code","B.request_type_name", "DATE_FORMAT(A.date_requested,'%M %d, %Y')", "C.request_status_name");
		
			$sample_data 	= $this->requests->get_employee_requests_list($aColumns, $bColumns, $params);
			// echo"<pre>";
			// print_r($sample_data);
			// die();
			$iTotal		= $this->requests->total_length();
			$iFilteredTotal = $this->requests->filtered_length($aColumns, $bColumns, $params);

			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			$module            = MODULE_PORTAL_MY_REQUESTS;
			
			$permission_view   = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_cancel   = $this->permission->check_permission($module, ACTION_CANCEL);
			
			foreach ($sample_data as $aRow):
				$row = array();
				if($aRow['request_type_id'] == REQUEST_MANUAL_ADJUSTMENT)
				{	
					
					$table = "requests_sub";
					$where = array();
					$where['request_id'] = $aRow['request_id'];
					$req_sub_ids = $this->requests->get_general_data("request_sub_id", $table, $where, TRUE);
					foreach($req_sub_ids as $req_sub_id)
					{
						$table = "requests_employee_attendance";
					$where = array();
					$where['request_sub_id'] = $req_sub_id['request_sub_id'];
					$requested_dates = $this->requests->get_general_data("DATE_FORMAT(attendance_date,'%M %d, %Y') as attendance_date", $table, $where, FALSE);
					if(!empty($requested_dates)){break;}
					}
				}elseif($aRow['request_type_id'] == REQUEST_LEAVE_APPLICATION)
				{
					$table = "requests_leaves";
					$where = array();
					$where['request_sub_id'] = $aRow['request_sub_id'];
					$requested_dates = $this->requests->get_general_data(array("DATE_FORMAT(date_from,'%M %d, %Y') as date_from", "DATE_FORMAT(date_to,'%M %d, %Y') as date_to"), $table, $where, FALSE);
				}
				$action = "";
				

				$id 			= $aRow['request_id'];
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_cancel 	= in_salt($id  . '/' . ACTION_CANCEL  . '/' . $module, $salt);
				$token_delete	= in_salt($id . '/' . ACTION_DELETE  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_delete 	= ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

				$row[] = $aRow['request_code'];
				$row[] = strtoupper($aRow['request_type_name']);
				$row[] = strtoupper($aRow['date_requested']);
			
				if($aRow['request_type_id'] == REQUEST_MANUAL_ADJUSTMENT)
				{	
					$row[] = strtoupper($requested_dates['attendance_date']);
				}elseif($aRow['request_type_id'] == REQUEST_LEAVE_APPLICATION)
				{
					$row[] = strtoupper($requested_dates['date_from']. " - " .$requested_dates['date_to'] );
				}
				else
				{
					$row[] = " ";
				}
				$row[] = strtoupper($aRow['request_status_name']);

				$action = "<div class='table-actions'>";
				if($permission_view)
				$action .= "<a href='#' class='view tooltipped' data-tooltip='View' data-position='bottom' onclick=\"content_form('requests/open_request/".$url_view."','main')\" data-delay='50'></a>";

				// if($permission_cancel == true AND $aRow['request_status_id'] != REQUEST_CANCELLED AND $aRow['request_status_id'] != REQUEST_APPROVED AND $aRow['request_status_id'] != REQUEST_REJECTED)
				// $action .= "<a href='javascript:;' data-tooltip='Supporting Documents' class='attach tooltipped md-trigger' data-modal='modal_supporting_docs' onclick=\"modal_supporting_docs_init('".$url_view."')\"></a>";
				
				if($permission_cancel == true AND $aRow['request_status_id'] != REQUEST_CANCELLED AND $aRow['request_status_id'] != REQUEST_APPROVED AND $aRow['request_status_id'] != REQUEST_REJECTED)
				$action .= "<a href='javascript:;' class='cancel tooltipped' data-tooltip='Cancel' data-position='bottom'  onclick=\"cancel_request('".ACTION_CANCEL."','".$id."','".$token_cancel."','".$salt."','".$module."')\" data-delay='50'></a>";
				
				$delete_action = 'content_delete("record", "'.$url_delete.'")';
				// if($permission_delete)
				// if($aRow['request_status_id'] == REQUEST_NEW)
				// $action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
			
				/*================================MARVIN================================*/
				/*================================ START : enable printing of application for leave : ================================*/
				// if($aRow['request_type_id'] == REQUEST_LEAVE_APPLICATION AND $aRow['request_status_id'] == REQUEST_APPROVED)
				if($aRow['request_type_id'] == REQUEST_LEAVE_APPLICATION)
				/*================================MARVIN================================*/
			
				$action .= "<a href='".base_url().PROJECT_MAIN."/employee_requests/print_leave_form/".$url_view."' target='_blank' class='print tooltipped' data-tooltip='Print Leave Form' data-position='bottom'   data-delay='50'></a>";
				
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
	public function modal($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();
			$resources 				= array();

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE,CSS_LABELAUTY);
			$resources['load_js']	= array(JS_DATETIMEPICKER,JS_SELECTIZE,JS_LABELAUTY);


			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['leave_types'] 	= $this->requests->get_general_data(array("*"), $this->requests->tbl_param_leave_types, array(), TRUE);
			$data['cert_types'] 	= $this->requests->get_general_data(array("*"), $this->requests->tbl_param_request_certification_types, array(), TRUE);
			

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
		$this->load->view('modals/modal_employee_request', $data);
		$this->load_resources->get_resource($resources);
	}

	public function process_requests()
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
			$valid_data = $this->_validate_requests($params);
			
			Main_Model::beginTransaction();
			$user_pds_id	= $this->session->userdata("user_pds_id");
			
			switch($valid_data['request_major_type'])
			{
				case REQUEST_ATTENDANCE:
					switch($valid_data['request_sub_type'])
					{
						case TYPE_REQUEST_ATTENDANCE_LEAVE_APPLICATION:
							$process_id = 1;
							/*############################ START : GET EMPLOYEE DATA #############################*/

							$table						= $this->requests->tbl_employee_personal_info;
							$where						= array();
							$key 						= $this->get_hash_key('employee_id');
							$where[$key]				= $user_pds_id;
							$pds_data 					= $this->requests->get_general_data(array("employee_id"), $table, $where, FALSE);
							
							/*############################ END : GET EMPLOYEE DATA #############################*/

							/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
							$fields 						= array();
							$fields['employee_id']			= $pds_data["employee_id"];
							$fields['request_type_id']		= $valid_data["request_major_type"];
							$fields['process_id']			= $process_id;
							$fields['request_status_id']	= 1;
							$fields['date_requested']		= date("Y-m-d H:i:s");
							$fields['created_by']			= $this->log_user_id;
							$fields['created_date']			= date("Y-m-d H:i:s");

							$table 							= $this->requests->tbl_requests;
							$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

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
							$table 					= $this->requests->tbl_requests;

							$this->requests->update_general_data($table,$fields,$where);

							/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

							/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
							$fields 							= array();
							$fields['employee_id']				= $pds_data["employee_id"];
							$fields['request_id']				= $request_id;
							$fields['request_sub_type_id']		= $valid_data["request_sub_type"];
							$fields['requests_sub_status_id']	= SUB_REQUEST_NEW;
							$fields['requests_sub_status_id']	= 1;

							$table 								= $this->requests->tbl_requests_sub;
							$request_sub_id						= $this->requests->insert_general_data($table,$fields,TRUE);
							/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

							/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
							$fields 						= array();
							$fields['request_sub_id']		= $request_sub_id;
							$fields['leave_type_id']		= $valid_data["leave_type"];
							$fields['no_of_days']			= $valid_data["no_of_days"];
							$fields['date_from']			= $valid_data["date_from"];
							$fields['date_to']				= $valid_data["date_to"];
							$fields['specific_details']		= $valid_data["leave_details"];
							$fields['communication_flag']	= isset($valid_data["requested_flag"]) ? 1 : 0;

							$table 							= $this->requests->tbl_requests_leaves;
							$this->requests->insert_general_data($table,$fields,FALSE);

							/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
							

							/*SET UNHASED REQUEST ID TO $final_request_id */
							$final_request_id = $request_id;


							/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
							
							$workflow 		= $this->requests->get_initial_task($process_id);
							

							$fields 					= array() ;
							$fields['request_id']		= $final_request_id;
							$fields['task_detail']		= $workflow['name'];
							$fields['process_id']		= $workflow['process_id'];
							$fields['process_stage_id']	= $workflow['process_stage_id'];
							$fields['process_step_id']	= $workflow['process_step_id'];
							$fields['task_status_id']	= 1;

							$table 						= $this->requests->tbl_requests_tasks;
							$this->requests->insert_general_data($table,$fields,FALSE);

							/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/

							$status = true;
							$message = "Request has been successfully submitted.";
						break;
						case TYPE_REQUEST_ATTENDANCE_OFFICIAL_BUSINESS:
							$process_id = 1;
							/*############################ START : GET EMPLOYEE DATA #############################*/

							$table						= $this->requests->tbl_employee_personal_info;
							$where						= array();
							$key 						= $this->get_hash_key('employee_id');
							$where[$key]				= $user_pds_id;
							$pds_data 					= $this->requests->get_general_data(array("employee_id"), $table, $where, FALSE);
							
							/*############################ END : GET EMPLOYEE DATA #############################*/

							/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
							$fields 						= array();
							$fields['employee_id']			= $pds_data["employee_id"];
							$fields['request_type_id']		= $valid_data["request_major_type"];
							$fields['process_id']			= $process_id;
							$fields['request_status_id']	= 1;
							$fields['date_requested']		= date("Y-m-d H:i:s");
							$fields['created_by']			= $this->log_user_id;
							$fields['created_date']			= date("Y-m-d H:i:s");

							$table 							= $this->requests->tbl_requests;
							$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

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
							$table 					= $this->requests->tbl_requests;

							$this->requests->update_general_data($table,$fields,$where);

							/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

							/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
							$fields 							= array();
							$fields['employee_id']				= $pds_data["employee_id"];
							$fields['request_id']				= $request_id;
							$fields['request_sub_type_id']		= $valid_data["request_sub_type"];
							$fields['requests_sub_status_id']	= SUB_REQUEST_NEW;

							$table 								= $this->requests->tbl_requests_sub;
							$request_sub_id						= $this->requests->insert_general_data($table,$fields,TRUE);
							/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

							/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
							$fields 						= array();
							$fields['request_sub_id']		= $request_sub_id;
							$fields['date_from']			= $valid_data["ob_date_from"];
							$fields['date_to']				= $valid_data["ob_date_from"];
							$fields['specific_details']		= $valid_data["specific_details"];

							$table 							= $this->requests->tbl_requests_official_business;
							$this->requests->insert_general_data($table,$fields,FALSE);

							/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
							

							/*SET UNHASED REQUEST ID TO $final_request_id */
							$final_request_id = $request_id;


							/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
							
							$workflow 		= $this->requests->get_initial_task($process_id);
							

							$fields 					= array() ;
							$fields['request_id']		= $final_request_id;
							$fields['task_detail']		= $workflow['name'];
							$fields['process_id']		= $workflow['process_id'];
							$fields['process_stage_id']	= $workflow['process_stage_id'];
							$fields['process_step_id']	= $workflow['process_step_id'];
							$fields['task_status_id']	= 1;

							$table 						= $this->requests->tbl_requests_tasks;
							$this->requests->insert_general_data($table,$fields,FALSE);

							/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/

							$status = true;
							$message = "Request has been successfully submitted.";
						break;
						case TYPE_REQUEST_ATTENDANCE_MANUAL_ADJUSTMENT:
							$process_id = 1;
							/*############################ START : GET EMPLOYEE DATA #############################*/

							$table						= $this->requests->tbl_employee_personal_info;
							$where						= array();
							$key 						= $this->get_hash_key('employee_id');
							$where[$key]				= $user_pds_id;
							$pds_data 					= $this->requests->get_general_data(array("employee_id"), $table, $where, FALSE);
							
							/*############################ END : GET EMPLOYEE DATA #############################*/

							/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
							$fields 						= array();
							$fields['employee_id']			= $pds_data["employee_id"];
							$fields['request_type_id']		= $valid_data["request_major_type"];
							$fields['process_id']			= $process_id;
							$fields['request_status_id']	= 1;
							$fields['date_requested']		= date("Y-m-d H:i:s");
							$fields['created_by']			= $this->log_user_id;
							$fields['created_date']			= date("Y-m-d H:i:s");

							$table 							= $this->requests->tbl_requests;
							$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

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
							$table 					= $this->requests->tbl_requests;

							$this->requests->update_general_data($table,$fields,$where);

							/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

							/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
							$fields 							= array();
							$fields['employee_id']				= $pds_data["employee_id"];
							$fields['request_id']				= $request_id;
							$fields['request_sub_type_id']		= $valid_data["request_sub_type"];
							$fields['requests_sub_status_id']	= SUB_REQUEST_NEW;

							$table 								= $this->requests->tbl_requests_sub;
							$request_sub_id						= $this->requests->insert_general_data($table,$fields,TRUE);
							/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

							/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
							$fields 						= array();
							$fields['request_sub_id']		= $request_sub_id;
							$fields['date_from']			= $valid_data["ob_date_from"];
							$fields['date_to']				= $valid_data["ob_date_from"];
							$fields['specific_details']		= $valid_data["specific_details"];

							$table 							= $this->requests->tbl_requests_manual_adjustments;
							$this->requests->insert_general_data($table,$fields,FALSE);

							/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
							

							/*SET UNHASED REQUEST ID TO $final_request_id */
							$final_request_id = $request_id;


							/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
							
							$workflow 		= $this->requests->get_initial_task($process_id);
							

							$fields 					= array() ;
							$fields['request_id']		= $final_request_id;
							$fields['task_detail']		= $workflow['name'];
							$fields['process_id']		= $workflow['process_id'];
							$fields['process_stage_id']	= $workflow['process_stage_id'];
							$fields['process_step_id']	= $workflow['process_step_id'];
							$fields['task_status_id']	= 1;

							$table 						= $this->requests->tbl_requests_tasks;
							$this->requests->insert_general_data($table,$fields,FALSE);

							/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/

							$status = true;
							$message = "Request has been successfully submitted.";
						break;
					}
				break;
				case REQUEST_CERTIFICATION:
					$process_id = 1;
					/*############################ START : GET EMPLOYEE DATA #############################*/

					$table						= $this->requests->tbl_employee_personal_info;
					$where						= array();
					$key 						= $this->get_hash_key('employee_id');
					$where[$key]				= $user_pds_id;
					$pds_data 					= $this->requests->get_general_data(array("employee_id"), $table, $where, FALSE);
					
					/*############################ END : GET EMPLOYEE DATA #############################*/

					/*############################ START : INSERT REQUEST ***PARENT*** TABLE DATA #############################*/
					$fields 						= array();
					$fields['employee_id']			= $pds_data["employee_id"];
					$fields['request_type_id']		= $valid_data["request_major_type"];
					$fields['process_id']			= $process_id;
					$fields['request_status_id']	= 1;
					$fields['date_requested']		= date("Y-m-d H:i:s");
					$fields['created_by']			= $this->log_user_id;
					$fields['created_date']			= date("Y-m-d H:i:s");

					$table 							= $this->requests->tbl_requests;
					$request_id						= $this->requests->insert_general_data($table,$fields,TRUE);

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
					$table 					= $this->requests->tbl_requests;

					$this->requests->update_general_data($table,$fields,$where);

					/*############################ END : UPDATE REQUEST PARENT TABLE REQUEST CODE #############################*/

					/*############################ START : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/
					$fields 							= array();
					$fields['employee_id']				= $pds_data["employee_id"];
					$fields['request_id']				= $request_id;
					$fields['request_sub_type_id']		= NULL;
					$fields['requests_sub_status_id']	= 1;

					$table 								= $this->requests->tbl_requests_sub;
					$request_sub_id						= $this->requests->insert_general_data($table,$fields,TRUE);
					/*############################ END : INSERT REQUEST ***SUB PARENT*** TABLE DATA #############################*/

					/*############################ START : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
					$fields 						= array();
					$fields['request_sub_id']		= $request_sub_id;
					$fields['specific_details']		= $valid_data["cert_details"];
					$fields['certfication_type_id']	= $valid_data["cert_type"];

					$table 							= $this->requests->tbl_requests_certifications;
					$this->requests->insert_general_data($table,$fields,FALSE);

					/*############################ END : INSERT REQUEST ***CHILD(SPECIFIC REQUEST TYPE)*** TABLE DATA #############################*/
					

					/*SET UNHASED REQUEST ID TO $final_request_id */
					$final_request_id = $request_id;


					/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/
					
					$workflow 		= $this->requests->get_initial_task($process_id);
					

					$fields 					= array() ;
					$fields['request_id']		= $final_request_id;
					$fields['task_detail']		= $workflow['name'];
					$fields['process_id']		= $workflow['process_id'];
					$fields['process_stage_id']	= $workflow['process_stage_id'];
					$fields['process_step_id']	= $workflow['process_step_id'];
					$fields['task_status_id']	= 1;

					$table 						= $this->requests->tbl_requests_tasks;
					$this->requests->insert_general_data($table,$fields,FALSE);

					/*############################ END : GET AND INSERT REQUEST'S INITIAL TASK #############################*/

					$status = true;
					$message = "Request has been successfully submitted.";
				break;
			}


			
			/*
			$audit_table[]			= $this->requests->tbl_employee_relations;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "%s has been added.";
			$audit_activity 		= sprintf($activity, $valid_data["relation_first_name"] . " ".$valid_data["relation_last_name"]);


			$status = true;
			$message = $this->lang->line('data_saved');

			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				*/
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
	private function _validate_requests($params)
	{
		try
		{
			if(EMPTY($params['request_major_type']))
				throw new Exception("<b>Request Type</b> is requied.");
			
			$fields = array();
			switch($params['request_major_type'])
			{
				case REQUEST_ATTENDANCE:

					if(EMPTY($params['request_sub_type']))
						throw new Exception("<b>Sub Request Type</b> is requied.");
					switch($params['request_sub_type'])
					{
						case TYPE_REQUEST_ATTENDANCE_LEAVE_APPLICATION:
							$fields['leave_type']	= "Leave Type";
							$fields['no_of_days']	= "No. of Days";
							$fields['date_from']	= "Date From";
							$fields['date_to']		= "Date To";
							$fields['leave_details']= "Specific Details";
						break;
						case TYPE_REQUEST_ATTENDANCE_OFFICIAL_BUSINESS:
							$fields['ob_date_from']		= "Date From";
							$fields['ob_date_to']		= "Date To";
							$fields['specific_details'] = "Specific Details";
						break;
						case TYPE_REQUEST_ATTENDANCE_MANUAL_ADJUSTMENT:
						break;
						default:
						throw new Exception("Invalid value for <b>Sub Request Type</b>.");
					}
				break;
				case REQUEST_CERTIFICATION:
					$fields['cert_type']	= "Certification Type";
					$fields['cert_details']	= "Specific Details";
				break;
				default:
						throw new Exception("Invalid value for <b>Request Type</b>.");
			}

			

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_requests($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_requests($params)
	{
		try
		{
			
			$validation['request_major_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Request Type',
					'max_len'	=> 11
			);	
			$validation['request_sub_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Sub Request Type',
					'max_len'	=> 11
			);	
			$validation['leave_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Leave Type',
					'max_len'	=> 11
			);	
			$validation['requested_flag'] = array(
					'data_type' => 'digit',
					'name'		=> 'Communication',
					'max_len'	=> 1
			);
			$validation['no_of_days'] = array(
					'data_type' => 'digit',
					'name'		=> 'No. of Days',
					'max_len'	=> 11
			);
			$validation['date_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Date From'
			);
			$validation['date_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Date To'
			);
			$validation['leave_details'] = array(
					'data_type' => 'string',
					'name'		=> 'Specific Details',
					'max_len'	=> 225
			);
			$validation['ob_date_from'] = array(
					'data_type' => 'date',
					'name'		=> 'Date From'
			);
			$validation['ob_date_to'] = array(
					'data_type' => 'date',
					'name'		=> 'Date To'
			);
			$validation['specific_details'] = array(
					'data_type' => 'string',
					'name'		=> 'Specific Details'
			);
			$validation['cert_type'] = array(
					'data_type' => 'digit',
					'name'		=> 'Certification Type',
					'max_len'	=> 11
			);
			$validation['cert_details'] = array(
					'data_type' => 'string',
					'name'		=> 'Specific Details'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function cancel_request()
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
			
			Main_Model::beginTransaction();

			$fields 						= array();
			$fields['request_status_id']	= REQUEST_CANCELLED;

			$where						= array();
			// $key 						= $this->get_hash_key('request_id');
			$where['request_id']				= $id;
			$table 						= $this->requests->tbl_requests;
			$this->requests->update_general_data($table,$fields,$where);
			// davcorrea : cancelled request should not be edittable : START
			$fields 						= array();
			$fields['request_sub_status_id']	= SUB_REQUEST_REJECTED;

			$where						= array();
			// $key 						= $this->get_hash_key('request_id');
			$where['request_id']				= $id;
			$table 						= $this->requests->tbl_requests_sub;
			$this->requests->update_general_data($table,$fields,$where);
			// END
			/*
			$audit_table[]			= $this->pds->tbl_employee_relations;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array($family);
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_UPDATE;	
				
			$activity 				= "%s has been updated.";
			$audit_activity 		= sprintf($activity, $family["relation_first_name"] . " ".$family["relation_last_name"]);

			
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				*/
			Main_Model::commit();
			$status = true;
			$message = "Request has been cancelled successfully.";
			
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
	public function delete_request()
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
			
			/*$prev_data				= array() ;
			$field 					= array("*") ;
			$table					= $this->requests->tbl_employee_references;
			$where					= array();
			$key 					= $this->get_hash_key('request_id');
			$where[$key]			= $id;
			$requests 				= $this->requests->get_general_data($field, $table, $where, FALSE);

			$where					= array();
			$key 					= $this->get_hash_key('employee_reference_id');
			$where[$key]			= $id;
			$table 					= $this->pds->tbl_employee_references;
			
			$this->pds->delete_general_data($table,$where);
			
			$audit_table[]				= $this->pds->tbl_employee_references;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($reference);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$activity 				= "Employee reference %s has been deleted.";
			$audit_activity 		= sprintf($activity, $reference["reference_full_name"]);

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			*/
			Main_Model::commit();
			$msg 					= $this->lang->line('data_deleted');
			$flag 					= 1;
		}
		catch (PDOException $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
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
			"table_id" 				=> 'table_employee_request',
			"path"					=> PROJECT_MAIN . '/employee_requests/get_employee_requests/',
			"advanced_filter" 		=> true
			);
		echo json_encode($response);
	}
	public function print_leave_form($action, $id, $token, $salt, $module)
	{
		try
		{
			$data = array();
			/*$data['sick_balance'] = 0;
			$data['vac_balance'] = 0;*/

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$params['leave_request'] = $this->hash($id);
			$generate_report = modules::load('main/reports_ta/ta_leave_aplication');
			$data            = $generate_report->generate_report_data($params);


/*
			$data['leave_detail'] = $this->requests->get_leave_application_detail($id);

			$field                = array("*") ;
			$table                = $this->requests->tbl_employee_leave_balances;
			$where                = array();
			$where["employee_id"] = $data['leave_detail']['employee_id'];
			$leave_balances       = $this->requests->get_general_data($field, $table, $where, TRUE);
			if($leave_balances)
			{
				foreach ($leave_balances as $value) {
					if($value['leave_type_id'] == LEAVE_TYPE_SICK)
					{
						$data['sick_balance'] = $value['leave_balance'];
					}
					if($value['leave_type_id'] == LEAVE_TYPE_VACATION)
					{
						$data['vac_balance'] = $value['leave_balance'];
					}
				}
			}*/
			
			$this->load->helper('html');
			ini_set('memory_limit', '512M'); // boost the memory limit if it's low
			$this->load->library('pdf');
			//Legal Size Paper
			$pdf 	= $this->pdf->load('utf-8', array(210,297));
			$html 	= $this->load->view('forms/reports/'.REPORTS_TA_LEAVE_APPLICATION, $data, TRUE);
			$pdf->WriteHTML($html);
			$pdf->Output();
			
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			print_r($message);
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		
	}

	public function modal_supporting_docs($action, $id, $token, $salt, $module)
	{
		try
		{
			$params = get_params();

			$data           = array();			
			$resources      = array();

			$data['action']			= $action;
			$data['id']				= $id;
			$data['token']			= $token;
			$data['salt']			= $salt;
			$data['module']			= $module;

			// GET SECURITY VARIABLES
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ) or EMPTY ( $module )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action.'/'. $module, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}

			$resources['load_css'] = array(CSS_DATATABLE);
			$resources['load_js']  = array(JS_DATATABLE);

			$post_data = array('id'=>$id);

			$resources['datatable'][] = array('table_id' => 'table_supporting_docs', 'path' => 'main/employee_requests/get_supporting_docs_list', 'advanced_filter' => true, 'post_data' => json_encode($post_data));


			$this->load->view('modals/modal_supporting_docs', $data);
			$this->load_resources->get_resource($resources);
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;
		}		
	}

	public function get_supporting_docs_list()
	{
	
		try
		{
			$params = get_params();

			$aColumns 	= array('E.supp_doc_type_name', 'DATE_FORMAT(F.date_received, "%Y/%m/%d") as date_received');
			$bColumns 	= array('E.supp_doc_type_name', 'DATE_FORMAT(F.date_received, "%Y/%m/%d")');
		
			$tasks          = $this->requests->get_supporting_docs_list($aColumns, $bColumns, $params);
			$iTotal         = $this->requests->get_supporting_docs_total_length($aColumns, $bColumns, $params);
			$iFilteredTotal = $this->requests->get_supporting_docs_filtered_length($aColumns, $bColumns, $params);

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

	
			foreach ($tasks as $aRow):

				$row = array();

				if(EMPTY($aRow['date_received']))
				{
					$if_submitted = '<span class="flaticon-cross95 grey-text text-darken-1"></span>';
				}
				else
				{
					$if_submitted = '<span class="flaticon-verify8 grey-text text-darken-1"></span>';
				}
				$row[] =  $if_submitted;
				$row[] =  $aRow['supp_doc_type_name'];
				$row[] =  '<center>' . format_date($aRow['date_received']) . '</center>';

				$action = "<div class='table-actions'>";

				// if($this->permission->check_permission(MODULE_USER, ACTION_EDIT))
				// $action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_leave_history' onclick=\"modal_leave_history_init('".$url_view."')\" data-delay='50'></a>";
				// $action .= "<a href='#' class='apply tooltipped md-trigger' data-tooltip='Apply Leave' data-position='bottom' data-modal='modal_apply_leave' onclick=\"modal_apply_leave_init('".$url_edit."')\" data-delay='50'></a>";
				
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
}


/* End of file Employee_requests.php */
/* Location: ./application/modules/main/controllers/Employee_requests.php */