<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sys_params_model extends SYSAD_Model {

	public function __construct() {
		parent:: __construct();
	
	}
	
	public function get_sys_param_data($fields, $table, $where = array(), $multiple = TRUE, $order_by = array(), $group_by = array(), $limit = NULL)
	{
		try
		{
			if($multiple)
			{
				return $this->select_all($fields, $table, $where, $order_by, $group_by, $limit);
			}
			else
			{
				return $this->select_one($fields, $table, $where, $order_by, $group_by, $limit);
			}
		}
		catch(PDOException $e)
		{
			throw $e;
		}
	}
	

}