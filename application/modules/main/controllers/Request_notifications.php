<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Request_notifications extends Main_Controller {
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('requests_model', 'requests');	
		$this->log_user_id    = $this->session->userdata('user_id');
		$this->log_user_roles = $this->session->userdata('user_roles');
	}
	public function insert_request_notification($request_id)
	{
		try
		{
			if(EMPTY($request_id))
				throw new Exception("Error inserting Request notification, Request ID is empty.");			

				/*$request_id = UNHASHED*/
				$notif_info   = $this->requests->get_request_notif_info($request_id);
				
				$id           = $this->hash($request_id);
				$module       = MODULE_REQUESTS_APPROVALS;
				$salt         = gen_salt();				
				$token_edit   = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);				
				$request_path = "main/requests/open_request/". ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				if($notif_info)
				{
					$fields                      = array() ;
					$fields['title']             = $notif_info['request_type_name'];
					$fields['notification']      = $this->lang->line('process_request_notif');
					$fields['record_link']       = $request_path;
					$fields['notify_orgs']       = $notif_info['employ_office_id'];
					$fields['notify_roles']      = $notif_info['notify_roles'];
					$fields['notified_by']       = $notif_info['user_id'];
					$fields['notification_date'] = date("Y-m-d H:i:s");
					switch ($notif_info['request_type_id']) {
						case REQUEST_PDS_RECORD_CHANGES:
							$fields['module_id'] = MODULE_HR_PERSONAL_DATA_SHEET;
							break;
						case REQUEST_LEAVE_APPLICATION:
							$fields['module_id'] = MODULE_TA_LEAVES;
							break;
						case REQUEST_CERTIFICATE_EMPLOYMENT:
							$fields['module_id'] = MODULE_HUMAN_RESOURCES;
							break;
						case REQUEST_SERVICE_RECORD:
							$fields['module_id'] = MODULE_HUMAN_RESOURCES;
							break;
						case REQUEST_CERTIFICATE_CONTRIBUTION:
							$fields['module_id'] = MODULE_PAYROLL;
							break;
						case REQUEST_MANUAL_ADJUSTMENT:
							$fields['module_id'] = MODULE_TA_DAILY_TIME_RECORD;
							break;
						case REQUEST_DEDUCTION_RECORD_CHANGES:
							$fields['module_id'] = MODULE_TA_DAILY_TIME_RECORD;
							break;
						case REQUEST_PAYSLIP:
							$fields['module_id'] = MODULE_PAYROLL;
							break;
					}
				
					$table    = $this->requests->db_core.".".$this->requests->tbl_notifications;
					$notif_id = $this->requests->insert_general_data($table,$fields,TRUE);
				}
				
				return TRUE;
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			RLog::error($message);
			throw $e;
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw $e;
		}
		
	}
	public function insert_request_final_notification($request_task_id,$approved =TRUE)
	{
		try
		{
				/*$request_task_id = HASHED*/
				$notif_info   = $this->requests->get_task_notif_info($request_task_id);
				if($notif_info)
				{
					$id           = $this->hash($notif_info['request_id']);
					$module       = MODULE_PORTAL_MY_REQUESTS;
					$salt         = gen_salt();				
					$token_view   = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);				
					$request_path = "main/requests/open_request/".ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;

					$fields                      = array() ;
					$fields['title']             = $notif_info['request_type_name'];
					$fields['notification']      = ($approved == TRUE) ? $this->lang->line('process_request_approved'):$this->lang->line('process_request_rejected');
					$fields['record_link']       = $request_path;
					$fields['notify_users']      = $notif_info['user_id'];
					$fields['notified_by']       = $notif_info['assigned_to'];
					$fields['notification_date'] = date("Y-m-d H:i:s");
					
				
					$table    = $this->requests->db_core.".".$this->requests->tbl_notifications;
					$notif_id = $this->requests->insert_general_data($table,$fields,TRUE);
				}
				
				return TRUE;
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			RLog::error($message);
			throw $e;
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw $e;
		}
		
	}
	public function update_notification() 
	{
		$flag = 0;
		$msg = "";
		
		try
		{
			$notification_id          = get_param('notification_id');
			
			$fields                   = array() ;
			$fields['read_by']        = $this->session->userdata("name"); ;
			
			$where                    = array();
			$where['notification_id'] = $notification_id;
			$table                    = $this->requests->db_core.'.'.$this->requests->tbl_notifications;
			$this->requests->update_general_data($table,$fields,$where);
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			RLog::error($message);
		}			
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
		
		$result	= array(
			"flag" => $flag,
			"msg" => $msg
		); 
												
		echo json_encode($result);
	}
}


/* End of file Employee_requests.php */
/* Location: ./application/modules/main/controllers/Employee_requests.php */
