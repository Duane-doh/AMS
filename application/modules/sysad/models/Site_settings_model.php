<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site_settings_model extends SYSAD_Model {
                
	var $settings_table = "site_settings";
	
	public function get_site_settings($setting_location = NULL, $setting_type = NULL, $setting_name = NULL)
	{
		try
		{	
			$where = array();
			
			$fields = array("*");
			$multiple = TRUE;
			
			if(!IS_NULL($setting_location))
				$where['setting_location'] = $setting_location;
			
			if(!IS_NULL($setting_type))
				$where['setting_type'] = $setting_type;
			
			if(!IS_NULL($setting_name)){
				$where['setting_name'] = $setting_name;
				$multiple = FALSE;
			}
			
			if($multiple)
				return $this->select_all($fields, $this->settings_table, $where);
			else
				return $this->select_one($fields, $this->settings_table, $where);
			
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
	
	public function update_settings($setting_type, $params, $field)
	{
		try
		{
			$val = array();
			$where = array();
			
			switch($field){
				case 'system_logo':
				  $value = (@getimagesize(base_url(). PATH_SETTINGS_UPLOADS . $params[$field])) ? $params[$field] : "";
				break;
				
				case 'password_expiry':
				  $value = ISSET($params['password_expiry']) ? $params[$field] : 0;
				break;
				
				case 'password_duration':
				case 'password_reminder':
				  $value = ISSET($params['password_expiry']) ? $params[$field] : "";
				break;
				
				default:
				  $value = $params[$field];				
			}

			$val['setting_value'] = filter_var($value, FILTER_SANITIZE_STRING);
			$where['setting_name'] = $field;
			$where['setting_type'] = $setting_type;
			
			$this->update_data($this->settings_table, $val, $where);
			
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