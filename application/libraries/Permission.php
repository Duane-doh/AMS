<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Permission {
	
	public function __construct()
	{
		$this->CI =& get_instance();
		//$this->CI->load->model(PROJECT_CORE.'/permissions_model');
	}
	
	/**
	 * $module_id - ID of the module being accessed
	 * 
	 * $button_action - Actions that a user can use depending on its access level
	 *  
	 * $redirect - redirect to unauthorized access error page if necessary
	 *     
	 */
	
	public function check_permission($module_id, $button_action = NULL, $redirect = FALSE){
	
		try
		{
			
			$permissions = $this->CI->permissions_model->get_permission_access($module_id, $button_action);
		
			$has_access	= FALSE;
			
			if(!EMPTY($permissions))
			{
				$user_roles = $this->CI->session->userdata('user_roles');
		
				if(!EMPTY($user_roles)){
		
					foreach($permissions as $permission):
						$role_code = $permission['role_code'];
							
						if(in_array($role_code, $user_roles))
							$has_access	= TRUE;
					endforeach;
				}
			}
		
			if($has_access){
				if(!$redirect)
					return TRUE;
			} else {
				if($redirect){
					redirect(base_url() . 'unauthorized' , 'location');
				} else {
					return FALSE;
				}
			}
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

	// START: Added by SGT for PTIS
	// CHECK IF USER HAS ACCESS TO THE OFFICE

	/**
	 * This function constructs a condition clause that filters the offices handled by user.
	 * @param $field_office string <table_filter_office>.<field_name_office>
	 * @param $has_where boolean TRUE if the SQL statement already has a WHERE clause; Otherwise, FALSE
	 * @param $or_null boolean FALSE if to include employees with no assigned offices; Otherwise, TRUE
	 * @param $module_id string
	 */
	public function filter_by_office($field_office, $has_where=TRUE, $or_null=FALSE, $module_id)
	{
		RLog::info("Permission.filter_by_office: [$field_office] [$has_where] [$or_null] [$module_id]");
		
		if (empty($module_id))
			throw new Exception($this->CI->lang->line ( 'err_invalid_request' ) . ' Missing Module ID');		
		
		$filter_offices = '';
		try {
			if (empty($field_office))
			{
				RLog::info($this->CI->lang->line ( 'err_invalid_request' ));
				throw new Exception ( $this->CI->lang->line ( 'err_invalid_request' )  . ' Missing Office Field');
			}
			
			$user_offices = $this->CI->session->userdata('user_offices');
			
			if ( ! empty($user_offices))
			{
				$offices_csv = (isset($user_offices[$module_id]) ? $user_offices[$module_id] : '') ;
				
				RLog::info('offices_csv: ' . $offices_csv);
				
				if (empty($offices_csv))
					$offices_csv = 0;
				
				if ($has_where)
					$filter_offices .= ' AND ';
				else
					$filter_offices .= ' WHERE ';
				
				$filter_offices 	.= " ($field_office IN ($offices_csv) ";
				
				if ($or_null)
					$filter_offices .= " OR $field_office IS NULL ";
					
				$filter_offices 	.= " ) ";
			}
			
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw $e;
		}
		
		return $filter_offices;
	}
	
	/**
	 *  @param $office_id Office ID of the employee, can be NULL if employee_id has value
	 *  @param $employee_id Unique ID of the employee, can be NULL if office_id has value
	 *  @param $or_null Allow access even if employee has no assigned office
	 */
	public function check_office_permission($office_id=NULL, $employee_id=NULL, $hash_employee_id=FALSE, $or_null=FALSE, $module_id=NULL)
	{
		RLog::info("Permission.check_office_permission: [$office_id] [$employee_id] [$or_null] [$module_id]");
		
		if (empty($module_id))
			throw new Exception($this->CI->lang->line ( 'err_invalid_request' ) . ' Missing Module ID');
		
		try {
			if (empty($office_id) && empty($employee_id))
			{
				return FALSE;
			}
			
			// if office is empty, get office_id of employee
			if (empty($office_id))
			{
				$this->CI->load->model('common_model');
				$employee_office = $this->CI->common_model->get_employee_office($employee_id, $hash_employee_id, $module_id);
				$office_id = $employee_office['office_id'];
			}
			
			// TODO
			// if employee has no office, no access ?
			if (empty($office_id))
			{
				if (empty($or_null))
					return FALSE;
				else
					return TRUE;
			}
			
			$user_offices = $this->CI->session->userdata('user_offices');
			
			if ( ! empty($user_offices))
			{
				$offices_csv = (isset($user_offices[$module_id]) ? $user_offices[$module_id] : '') ;
				
				$offices_arr = explode(',', $offices_csv);
				
				if (in_array($office_id, $offices_arr))
				{
					return TRUE;
				}
			}
		}
		catch (Exception $e)
		{			
			$this->rlog_error($e);
			throw new Exception($e->getMessage());
		}
		
		return FALSE;
	}
	// END: Added by SGT for PTIS
	
}