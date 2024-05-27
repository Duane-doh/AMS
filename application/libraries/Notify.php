<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notify {
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('notifications_model');
	}
	
	/**
	 * $notification - notification message.
	 * $notify_who - 2 dimensional array consisting the following indexes 'notify_users', 'notify_orgs', 'notify_roles'.
	 * 				second array is array('-1') if all.
	 */
	
	public function insert_notification($notification, $notify_who)
	{
		try
		{
			$params = array("notification" => $notification);
			
			foreach ($notify_who as $k => $v):
				if(!EMPTY($notify_who[$k]))
					$params[$k] = $notify_who[$k];
			endforeach;
				
			$this->CI->notifications_model->insert_notification($params);
		}
		catch(PDOException $e)
		{
			throw new PDOException($e->getMessage());
		}
		catch(Exception $e)
		{
			throw new Exception($e->getMessage());
		}
		
	}
	
}