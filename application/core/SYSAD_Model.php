<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SYSAD_Model extends Base_Model {
	
	
	protected static $dsn = DB_CORE;
				
	const MODULE = PROJECT_CORE;				

	public $tbl_actions 				= 'actions';
	public $tbl_activities				= 'activities';
	public $tbl_app_roles				= 'app_roles';
	public $tbl_apps					= 'apps';
	public $tbl_audit_trail				= 'audit_trail';
	public $tbl_audit_trail_detail		= 'audit_trail_detail';
	public $tbl_module_action_roles		= 'module_action_roles';
	public $tbl_module_actions			= 'module_actions';
	public $tbl_module_scope_roles		= 'module_scope_roles';
	public $tbl_module_scopes			= 'module_scopes';
	public $tbl_modules 				= 'modules';
	public $tbl_notifications 			= 'notifications';
	public $tbl_organizations 			= 'organizations';
	public $tbl_param_genders 			= 'param_genders';
	public $tbl_param_municities 		= 'param_municities';
	public $tbl_param_offices 			= 'param_offices';
	public $tbl_param_regions 			= 'param_regions';
	public $tbl_param_scopes 			= 'param_scopes';
	public $tbl_param_user_status		= 'param_user_status';
	public $tbl_roles 					= 'roles';	
	public $tbl_service_tabs 			= 'service_tabs';
	public $tbl_service_task_actions	= 'service_task_actions';
	public $tbl_service_tasks 			= 'service_tasks';
	public $tbl_services 				= 'services';
	public $tbl_site_settings 			= 'site_settings';
	public $tbl_sys_param 				= 'sys_param';
	public $tbl_systems 				= 'systems';
	public $tbl_to_dos 					= 'to_dos';
	public $tbl_user_offices 			= 'user_offices';
	public $tbl_user_roles 				= 'user_roles';
	public $tbl_user_settings 			= 'user_settings';
	public $tbl_users 					= 'users';
	
	
	public $tbl_files 					= 'file';
	public $tbl_file_versions 			= 'file_versions';

	


	//FOR DB MAIN
	/*public $DB_MAIN						= DB_MAIN;
	public $tbl_employee_personal_info	= 'employee_personal_info';
	public $tbl_param_employment_type	= 'param_employement_type';
	public $tbl_param_pds_status		= 'param_pds_status';
	public $tbl_employee_offices		= 'employee_offices';
	public $tbl_param_offices			= 'param_offices';*/
	



}