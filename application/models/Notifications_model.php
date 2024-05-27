<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifications_model extends SYSAD_Model {
    
	var $notifications_table = "notifications";
	
	public function insert_notification($params)
	{
		try
		{
			$notified_by = $this->session->user_id;
			if (! empty($params['notified_by']))
				$notified_by = $params['notified_by'];

			$val = array(
					"notification" => $params["notification"],
					"notified_by" => $notified_by,
					"notification_date" => date("Y-m-d H:i:s"),

					// for PTIS
					"module_id" => $params["module_id"],
					"title" => $params["title"],
					"record_link" => $params["record_link"]
				);
			
			if(ISSET($params["notify_users"])){
				if (is_array($params["notify_users"]))
					$notify_users = implode(",",$params["notify_users"]);
				else
					$notify_users = $params["notify_users"];
				
				$val["notify_users"] = $notify_users;
			}	
				
			if(ISSET($params["notify_orgs"])){
				if (is_array($params["notify_orgs"]))
					$notify_orgs = implode(",",$params["notify_orgs"]);
				else
					$notify_orgs = $params["notify_orgs"];
				$val["notify_orgs"] = $notify_orgs;
			}	
				
			if(ISSET($params["notify_roles"])){
				if (is_array($params["notify_roles"]))
					$notify_roles = implode(",",$params["notify_roles"]);
				else
					$notify_roles = $params["notify_roles"];
				
				$val["notify_roles"] = $notify_roles;
			}
				
			$this->insert_data($this->notifications_table, $val);
			
		}
		catch(PDOException $e)
		{
			$this->rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			$this->rlog_error($e);
			
			throw $e;			
		}
	}
		
}

/* End of file notifications_model.php */
/*/application/models/notifications_model.php*/
