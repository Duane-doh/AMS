<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaves extends Main_Controller {
	private $log_user_id		=  '';
	private $log_user_roles		= array();
	public function __construct()
	{
		parent::__construct();
		$this->load->model('leaves_model', 'leaves');
		$this->log_user_id			= $this->session->userdata('user_id');
		$this->log_user_roles		= $this->session->userdata('user_roles');
	}
	
	public function index()
	{
		$data = array();
		$resources = array();
		
		$resources['load_css'] 		= array(CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_DATATABLE);
		$resources['datatable'][]	= array('table_id' => 'table_leave_type_list', 'path' => 'main/leaves/get_leave_type_list','advanced_filter' => true);
		$resources['load_modal']		= array(
			'modal_leave_type_adjustment'		=> array(
					'controller'	=> __CLASS__,
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal_leave_type_adjustment',
					'multiple'		=> true,
					'height'		=> '500px',
					'size'			=> 'lg',
					'title'			=> SUB_MENU_LEAVE
			)
		);
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Time & Attendance"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/leaves";
		$key					= "Leaves"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/leaves";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('leaves/leaves', $data, $resources);
		
	}

	public function get_employee_list()
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.employee_id","A.agency_employee_id", "CONCAT(A.first_name,' ',A.last_name) as fullname", "", "E.name", "D.employment_status_name");
			$bColumns       = array("A.agency_employee_id", "CONCAT(A.first_name,' ',A.last_name)", "E.name", "D.employment_status_name");
			
			$employee_list  = $this->leaves->get_employee_list($aColumns, $bColumns, $params);
			$iTotal         = $this->leaves->employee_total_length();
			$iFilteredTotal = $this->leaves->employee_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module = MODULE_TA_LEAVES;
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			
			
			foreach ($employee_list as $aRow):
				$row = array();
				
				$id 			= $this->hash($aRow['employee_id']);
				
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;

				$row[] = $aRow['agency_employee_id'];
				$row[] = $aRow['fullname'];
				$row[] = $aRow['name'];
				$row[] = $aRow['employment_status_name'];

				
				$action = "<div class='table-actions'>";
			
				if($permission_view)
				$action .= "<a href='javascript:;' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_employee_leave' onclick=\"modal_employee_leave_init('".$url_view."')\" data-delay='50'></a>";
				if($permission_edit)
				$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_employee_leave' onclick=\"modal_employee_leave_init('".$url_edit."')\" data-delay='50'></a>";
				
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
	public function modal_employee_leave($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			$post_data 					= array(	
												'employee_id' => $id,
												'action'   => $action
										);
			$resources['datatable'][]    = array('table_id' => 'table_employee_leave_list', 'path' => 'main/leaves/get_employee_leave_list','advanced_filter'=>true,'post_data' => json_encode($post_data));
			$resources['load_modal']		= array(
				'modal_employee_leave_adjustment'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_employee_leave_adjustment',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> "Leave Adjustment" 
				),
				'modal_leave_history'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_history',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'lg',
						'title'			=> "Leave History"
				),
				'modal_add_employee_leave_type'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_add_employee_leave_type',
						'multiple'		=> true,
						'height'		=> '400px',
						'size'			=> 'sm',
						'title'			=> "Leave Type"
				)
			);	

		$this->load->view('leaves/modals/modal_employee_leave', $data);
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
	public function get_employee_leave_list()
	{

		try
		{
			$params = get_params();

			$aColumns 	= array("A.leave_type_id", "A.leave_type_name", "B.leave_balance","SUM(IF(E.request_sub_status_id = 1 AND F.no_of_days IS NOT NULL, F.no_of_days,0))as pending_leave");
			$bColumns 	= array("A.leave_type_name", "B.leave_balance","B.leave_balance","SUM(IF(E.request_sub_status_id = 1 AND F.no_of_days IS NOT NULL, F.no_of_days,0))");
		
			$leave_type_list = $this->leaves->employee_get_leave_type_list($aColumns, $bColumns, $params);
			$iTotal          = $this->leaves->employee_leave_type_total_length();
			$iFilteredTotal  = $this->leaves->employee_leave_type_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module = MODULE_TA_LEAVES;
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			$employee_id = $params['employee_id'];
			foreach ($leave_type_list as $aRow):
				$row = array();
				
				$id 			= $this->hash($aRow['leave_type_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module. '/' . $employee_id, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module. '/' . $employee_id, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;

				$row[] = $aRow['leave_type_name'];
				$row[] = !EMPTY($aRow['leave_balance']) ? $aRow['leave_balance'] : '0';
				$row[] = !EMPTY($aRow['pending_leave']) ? $aRow['pending_leave'] : '0';
				
				$action = "<div class='table-actions'>";

				if($permission_view)
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_leave_history' onclick=\"modal_leave_history_init('".$url_view."')\" data-delay='50'></a>";
				if($permission_edit)
				$action .= "<a href='#' class='apply tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_employee_leave_adjustment' onclick=\"modal_employee_leave_adjustment_init('".$url_edit."')\" data-delay='50'></a>";
				
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
	public function modal_add_employee_leave_type($action, $id, $token, $salt, $module)
	{
		try
		{
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			$resources['load_css'] 		= array(CSS_SELECTIZE,CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_SELECTIZE,JS_DATETIMEPICKER);	

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$data['leave_types'] = $this->leaves->get_employee_leave_types($id);

			$this->load->view('leaves/modals/modal_add_employee_leave_type', $data);
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
	public function modal_employee_leave_adjustment($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;

			$resources['load_css'] 		= array(CSS_LABELAUTY,CSS_SELECTIZE,CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_LABELAUTY,JS_SELECTIZE,JS_DATETIMEPICKER);
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$field                     = array("*") ;
			$table                     = $this->leaves->tbl_param_leave_transaction_types;
			$where                     = array();
			$where['active_flag']      = YES;
			$data['transaction_types'] = $this->leaves->get_general_data($field, $table, $where, TRUE);

			$field                     = array("DATE_FORMAT(leave_start_date,'%M %d, %Y') as leave_start_date_str", "DATE_FORMAT(leave_end_date,'%M %d, %Y') as leave_end_date_str", "leave_detail_id") ;
			$table                     = $this->leaves->tbl_employee_leave_details;
			$order_by 			         = array('leave_start_date' => 'DESC');
			$where                     = array();
			$key1                	   = $this->get_hash_key('leave_type_id');
			$key2                	   = $this->get_hash_key('employee_id');
			$where[$key1]       	   = $id;
			$where[$key2]       	   = $employee_id;
			$where['leave_transaction_type_id']       	   = "4";
			$data['approved_leaves'] = $this->leaves->get_general_data($field, $table, $where, TRUE, $order_by);

			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];


			$this->load->view('leaves/modals/modal_employee_leave_adjustment', $data);
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

	public function modal_leave_history($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data = array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			$resources['load_modal']		= array(
				'modal_leave_history_detail'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_history_detail',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'sm',
						'title'			=> "Leave History Detail"
				)
				
				//===========================================================marvin===========================================================
				,
				'modal_leave_card'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_leave_card',
						'multiple'		=> true,
						'height'		=> '500px',
						'size'			=> 'sm',
						'title'			=> "Leave Card"
				)
				//===========================================================marvin===========================================================
			);	
			$post_data = array(
				'employee_id' => $employee_id,
				'leave_type_id' => $id

				);
			$resources['datatable'][]	= array('table_id' => 'table_leave_history', 'path' => 'main/leaves/get_employee_leave_history','advanced_filter'=>true,'post_data' => json_encode($post_data));
			
			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];

			$this->load->view('leaves/modals/modal_leave_history', $data);
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
	public function get_employee_leave_history()
	{

		try
		{
			$params = get_params();

			$aColumns 	= array("A.leave_detail_id","DATE_FORMAT(A.leave_transaction_date,'%M %d, %Y') as transaction_date","DATE_FORMAT(A.effective_date,'%M %d, %Y') as effective_date","B.leave_transaction_type_name","A.leave_earned_used");
			$bColumns 	= array("DATE_FORMAT(A.leave_transaction_date,'%M %d, %Y')","DATE_FORMAT(A.effective_date,'%M %d, %Y')", "B.leave_transaction_type_name","A.leave_earned_used");
		
			$leave_history = $this->leaves->get_leave_history_list($aColumns, $bColumns, $params);
			$iTotal          = $this->leaves->leave_history_total_length($params);
			$iFilteredTotal  = $this->leaves->leave_history_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			$module = MODULE_TA_LEAVES;
			$employee_id = $params['employee_id'];
			foreach ($leave_history as $aRow):
				$row = array();
				
				$id 			= $this->hash($aRow['leave_detail_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module. '/' . $employee_id, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module."/".$employee_id;

				$row[] = $aRow['transaction_date'];
				$row[] = $aRow['effective_date'];
				$row[] = $aRow['leave_transaction_type_name'];
				$row[] = $aRow['leave_earned_used'];
				
				$action = "<div class='table-actions'>";

				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_leave_history_detail' onclick=\"modal_leave_history_detail_init('".$url_view."')\" data-delay='50'></a>";
				// $action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_leave_card' onclick=\"modal_leave_card_init('".$url_edit."')\" data-delay='50'></a>";
				
				/*=============================== MARVIN : START : INCLUDE EDITING OF LEAVE FOR ALLOWED ROLES =========================*/
				// $token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module. '/' . $employee_id, $salt);
				// $url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$employee_id;
				
				// $allowroles = array(
					// 'SUPER_USER',
					// 'TAADMIN',
					// 'TAMANAGER',
					// 'TASTAFF',
					// 'TASUPERVISOR'
				// );
				
				// $custom_action = "";
				
				// foreach($this->session->userdata['user_roles'] as $userroles)
				// {
					// if(in_array($userroles, $allowroles))
					// {
						// $custom_action = "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_leave_card' onclick=\"modal_leave_card_init('".$url_edit."')\" data-delay='50'></a>";
					// }
				// }
				
				//disable edit for initial balance transaction type
				// temporary disable 07-13-2020
				// if($aRow['leave_transaction_type_name'] != 'Initial Balance')
				// {
					// $action .= $custom_action;				
				// }
				/*=============================== MARVIN : END : INCLUDE EDITING OF LEAVE FOR ALLOWED ROLES =========================*/
				
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

	public function modal_leave_history_detail($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			
			$field                 = array("*") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_employee_leave_details,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_types,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.leave_type_id = B.leave_type_id',
				),
				't3'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_transaction_types,
					'alias'		=> 'C',
					'type'		=> 'join',
					'condition'	=> 'A.leave_transaction_type_id = C.leave_transaction_type_id',
				)
			);
			$where                 = array();
			$key                   = $this->get_hash_key('A.leave_detail_id');
			$where[$key]           = $id;
			$data['leave_history'] = $this->leaves->get_general_data($field, $tables, $where, FALSE);
			RLog::info('LINE 453 =>'.json_encode($data['leave_history']));
			$this->load->view('leaves/modals/modal_leave_history_detail', $data);
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
	//===========================================================marvin===========================================================
	//add edit leave adjustment

	//START davcorrea added modal for returning of leaves.
	public function modal_cancel_approved_leave($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;
			$data['employee_id']	= $employee_id;

			$resources['load_css'] 		= array(CSS_LABELAUTY,CSS_SELECTIZE,CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_LABELAUTY,JS_SELECTIZE,JS_DATETIMEPICKER);
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$field                     = array("*") ;
			$table                     = $this->leaves->tbl_param_leave_transaction_types;
			$where                     = array();
			$where['active_flag']      = YES;
			$data['transaction_types'] = $this->leaves->get_general_data($field, $table, $where, TRUE);

			$field                     = array("DATE_FORMAT(leave_start_date,'%M %d, %Y') as leave_start_date_str", "DATE_FORMAT(leave_end_date,'%M %d, %Y') as leave_end_date_str", "leave_detail_id") ;
			$table                     = $this->leaves->tbl_employee_leave_details;
			$order_by 			         = array('leave_start_date' => 'DESC');
			$where                     = array();
			$key1                	   = $this->get_hash_key('leave_type_id');
			$key2                	   = $this->get_hash_key('employee_id');
			$where[$key1]       	   = $id;
			$where[$key2]       	   = $employee_id;
			$where['leave_transaction_type_id']       	   = "4";
			$data['approved_leaves'] = $this->leaves->get_general_data($field, $table, $where, TRUE, $order_by);

			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];


			$this->load->view('leaves/modals/modal_cancel_approved_leave', $data);
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
	//END ==================
	public function modal_leave_card($action, $id, $token, $salt, $module, $employee_id)
	{
		try
		{
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);
			
			$data 					= array();
			$data['action']			= $action;
			$data['id'] 			= $id;
			$data['token'] 			= $token;
			$data['salt'] 			= $salt;
			$data['module']			= $module;
			$data['employee_id'] 	= $employee_id;
			
			$field 	= '*';
			$tables = array(
				'main' 	=> array(
					'table' 	=> $this->leaves->tbl_employee_leave_details,
					'alias' 	=> 'A'
				),
				't2'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_types,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.leave_type_id = B.leave_type_id',
				),
				't3' 	=> array(
					'table' 	=> $this->leaves->tbl_param_leave_transaction_types,
					'alias' 	=> 'C',
					'type' 		=> 'join',
					'condition' => 'A.leave_transaction_type_id = C.leave_transaction_type_id'
				)
			);
			
			$where 				= array();
			$key 				= $this->get_hash_key('A.leave_detail_id');
			$where[$key] 		= $id;
			$data['leave_card'] = $this->leaves->get_general_data($field, $tables, $where, FALSE);
			$data['transaction_types'] = $this->leaves->get_general_data(array('*'), $this->leaves->tbl_param_leave_transaction_types, array('active_flag' => YES), TRUE);
			$data['hash_leave_type_id'] = $this->hash($data['leave_card']['leave_type_id']);
			
			RLog::info('LINE 453 =>'.json_encode($data));
			$this->load->view('leaves/modals/modal_leave_card', $data);
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
	//===========================================================marvin===========================================================
	//===========================================================marvin===========================================================
	public function update_modal_leave_card()
	{
		try
		{			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params				= get_params();
			$action				= $params['action'];
			$id					= $params['id'];
			$token				= $params['token'];
			$salt				= $params['salt'];
			$module				= $params['module'];
			$employee_id		= $params['employee_id'];
			$leave_type_id		= $this->hash($params['leave_type_id']);
			$leave_detail_id 	= $this->hash($params['leave_detail_id']);
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			/*CHECK DATA VALIDITY*/

			$valid_data = $this->_validate_employee_adjustment($params);
			
			if(!EMPTY($valid_data['leave_start_date']))
			{
				$result       = $this->check_working_days($valid_data['leave_start_date'],$valid_data['leave_end_date']);
				
				$working_days = count($result);
				$no_of_days   = $valid_data['leave_earned_used'] + $valid_data['no_of_days_wop'];

				if($no_of_days > ($working_days - 1) AND $no_of_days <= $working_days)
				{
					
				}
				else
				{
					throw new Exception("Number of working days does not match with <b>Number of Days</b> plus <b>Number of Days w/o Pay</b>.<br> Working Days : ".$working_days);
				}
			}
			
			Main_Model::beginTransaction();

			$field 	= array('*');

			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_types,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->tbl_employee_leave_balances,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.leave_type_id = B.leave_type_id',
				)
			);
			
			$where          = array();
			$key            = $this->get_hash_key('B.leave_type_id');
			$where[$key]    = $leave_type_id;
			$key            = $this->get_hash_key('B.employee_id');
			$where[$key]    = $employee_id;
			$leave_balances = $this->leaves->get_general_data($field, $tables, $where, FALSE);
			
			$where          = array();
			$tables         = $this->leaves->tbl_employee_personal_info;
			$key            = $this->get_hash_key('employee_id');
			$where[$key]    = $employee_id;
			$personal_info  = $this->leaves->get_general_data($field, $tables, $where, FALSE);
			
			$where          = array();
			$tables         = $this->leaves->tbl_param_leave_types;
			$key            = $this->get_hash_key('leave_type_id');
			$where[$key]    = $leave_type_id;
			$leave_type     = $this->leaves->get_general_data($field, $tables, $where, FALSE);

			switch($valid_data['transaction_type'])
			{
				case LEAVE_INITIAL_BALANCE:
					if($leave_balances)
					{
						//update employee_leave_balances
						$fields 					= array() ;
						$fields['leave_balance'] 	= $valid_data["leave_earned_used"];

						$where						= array();
						$key 						= $this->get_hash_key('leave_type_id');
						$where[$key]				= $leave_type_id;
						$key 						= $this->get_hash_key('employee_id');
						$where[$key]				= $employee_id;
						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->update_general_data($table,$fields,$where);

						$audit_table[] 		= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[] 	= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[] 		= array($fields);
						$audit_action[] 	= AUDIT_UPDATE;	
						//end
						
						//update employee_leave_details
						$fields                              	= array() ;
						$fields['employee_id']               	= $personal_info["employee_id"];
						$fields['leave_type_id']             	= $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] 	= $valid_data["transaction_type"];
						$fields['leave_earned_used']         	= $valid_data["leave_earned_used"];
						$fields['effective_date'] 				= $valid_data["effective_date"];
						// $fields['leave_transaction_date']    	= date("Y-m-d");
						/*================== MARVIN : START : MANUAL ADDING OF TRANSACTION DATE ==================*/
						$fields['leave_transaction_date']    	= isset($valid_data['leave_transaction_date']) ? $valid_data['leave_transaction_date'] : date("Y-m-d");
						/*================== MARVIN : END : MANUAL ADDING OF TRANSACTION DATE ==================*/
						$fields['remarks']                   	= $valid_data["remarks"];
						
						$where									= array();
						$key									= $this->get_hash_key('leave_detail_id');
						$where[$key]							= $leave_detail_id;
						$table 									= $this->leaves->tbl_employee_leave_details;						
						$this->leaves->update_general_data($table,$fields,$where);
						//end

						$audit_table[]		= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]		= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[]		= array($fields);
						$audit_action[] 	= AUDIT_UPDATE;	

						$activity 			= "%s has been updated.";
						$audit_activity 	= "Employee's initial leave balance has been updated.";
					}
					break;
					
				case LEAVE_CREDIT_LEAVE:
					if($leave_balances)
					{
						//update employee_leave_balances
						$fields = array();
						
						if($params['prev_leave_transaction_type_id'] == 4)
						{
							$fields['leave_balance'] = ($leave_balances["leave_balance"] + $params['prev_leave_earned_used']) + $valid_data["leave_earned_used"];					
						}
						else
						{
							$fields['leave_balance'] = ($leave_balances["leave_balance"] - $params['prev_leave_earned_used']) + $valid_data["leave_earned_used"];
						}
						
						$where						= array();
						$key 						= $this->get_hash_key('leave_type_id');
						$where[$key]				= $leave_type_id;
						$key 						= $this->get_hash_key('employee_id');
						$where[$key]				= $employee_id;
						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->update_general_data($table,$fields,$where);

						$audit_table[] 		= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[] 	= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[] 		= array($fields);
						$audit_action[] 	= AUDIT_UPDATE;
						//end

						//update employee_leave_details
						$fields                              	= array();
						$fields['employee_id']               	= $personal_info["employee_id"];
						$fields['leave_type_id']             	= $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] 	= $valid_data["transaction_type"];
						$fields['leave_earned_used']         	= $valid_data["leave_earned_used"];
						$fields['effective_date'] 				= $valid_data["effective_date"];
						$fields['leave_transaction_date'] 		= date("Y-m-d");
						$fields['remarks'] 						= $valid_data["remarks"];
						
						$where									= array();
						$key									= $this->get_hash_key('leave_detail_id');
						$where[$key]							= $leave_detail_id;
						$table 									= $this->leaves->tbl_employee_leave_details;
						$this->leaves->update_general_data($table,$fields,$where);
						//end

						$audit_table[]		= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]		= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[]		= array($fields);
						$audit_action[] 	= AUDIT_UPDATE;	
						
						$activity 			= "Employee's leave balance had %s days credit added.";
						$audit_activity 	= sprintf($activity, $valid_data["leave_earned_used"]);
					}
					break;
					
				case LEAVE_REVERSE_LEAVE:
					if($leave_balances)
					{
						//update employee_leave_balances
						$fields = array() ;
						
						if($params['prev_leave_transaction_type_id'] == 4)
						{
							$fields['leave_balance'] = ($leave_balances["leave_balance"] + $params['prev_leave_earned_used']) + $valid_data["leave_earned_used"];					
						}
						else
						{
							$fields['leave_balance'] = ($leave_balances["leave_balance"] - $params['prev_leave_earned_used']) + $valid_data["leave_earned_used"];
						}		
						
						$where						= array();
						$key 						= $this->get_hash_key('leave_type_id');
						$where[$key]				= $leave_type_id;
						$key 						= $this->get_hash_key('employee_id');
						$where[$key]				= $employee_id;
						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->update_general_data($table,$fields,$where);

						$audit_table[] 		= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[] 	= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[] 		= array($fields);
						$audit_action[] 	= AUDIT_UPDATE;	
						//end
						
						//update employee_leave_details
						$fields                              	= array() ;
						$fields['employee_id']               	= $personal_info["employee_id"];
						$fields['leave_type_id']             	= $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] 	= $valid_data["transaction_type"];
						$fields['leave_earned_used']         	= $valid_data["leave_earned_used"];
						$fields['effective_date'] 				= $valid_data["effective_date"];
						$fields['leave_start_date']         	= $valid_data["leave_start_date"];
						$fields['leave_end_date']           	= $valid_data["leave_end_date"];
						$fields['leave_transaction_date']    	= date("Y-m-d");
						$fields['remarks']                   	= $valid_data["remarks"];
						
						$where									= array();
						$key									= $this->get_hash_key('leave_detail_id');
						$where[$key]							= $leave_detail_id;
						$table 									= $this->leaves->tbl_employee_leave_details;
						$this->leaves->update_general_data($table,$fields,$where);
						//end

						$audit_table[]		= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]		= DB_MAIN;
						$prev_detail[] 		= array();
						$curr_detail[]		= array($fields);
						$audit_action[] 	= AUDIT_UPDATE;	

						$activity 			= "Employee's leave balance had %s days credit returned.";
						$audit_activity 	= sprintf($activity, $valid_data["leave_earned_used"]);
					}
					break;
					
				case LEAVE_FILE_LEAVE:
					//update employee_leave_balances
					$leave_type 			= (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['deduct_bal_leave_type_id'] : $leave_balances['leave_type_id'];
					$field                  = array('*');
					$where                  = array();
					$where["employee_id"]   = $leave_balances['employee_id'];
					$where["leave_type_id"] = $leave_type;
					$tables                 = $this->leaves->tbl_employee_leave_balances;
					$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);

					/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
					if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
					{
						/*
						IF ENOUGH BALANCE:
							SET NORMAL LEAVE WITH OR WITH OUT PAY
						*/
						if($params['prev_leave_transaction_type_id'] != 4)
						{
							$new_leave_balance = ($check_balance["leave_balance"] - $params['prev_leave_earned_used']) - $valid_data["leave_earned_used"];
							$leave_with_pay    = $valid_data["leave_earned_used"];
							$leave_without_pay = $valid_data["no_of_days_wop"];					
						}
						else
						{
							$new_leave_balance = ($check_balance["leave_balance"] + $params['prev_leave_earned_used']) - $valid_data["leave_earned_used"];
							$leave_with_pay    = $valid_data["leave_earned_used"];
							$leave_without_pay = $valid_data["no_of_days_wop"];	
						}				
					}
					else
					{
						/*
						IF NOT ENOUGH BALANCE:
							-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
							-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
						*/
						$new_leave_balance = 0;
						$leave_with_pay    = $check_balance["leave_balance"] + $params['prev_leave_earned_used'];
						$leave_without_pay = ($valid_data['leave_earned_used'] - ($check_balance['leave_balance'] + $params['prev_leave_earned_used'])) + $valid_data['no_of_days_wop'];
					}

					$fields                  = array() ;
					$fields['leave_balance'] = $new_leave_balance;					
					
					$where                   = array();
					$where['leave_type_id']  = $leave_type;
					$key                     = $this->get_hash_key('employee_id');
					$where[$key]             = $employee_id;
					$table                   = $this->leaves->tbl_employee_leave_balances;
					$this->leaves->update_general_data($table,$fields,$where);
					//end
					
					$audit_table[] 		= $this->leaves->tbl_employee_leave_balances;
					$audit_schema[] 	= DB_MAIN;
					$prev_detail[] 		= array();
					$curr_detail[] 		= array($fields);
					$audit_action[] 	= AUDIT_UPDATE;	

					//update employee_leave_details
					$fields                              	= array() ;
					$fields['employee_id']               	= $personal_info["employee_id"];
					$fields['leave_type_id']             	= $leave_type;
					$fields['orig_leave_type_id']        	= (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['leave_type_id'] : NULL;
					$fields['leave_transaction_type_id'] 	= $valid_data["transaction_type"];
					$fields['leave_earned_used']         	= $leave_with_pay;
					$fields['leave_wop']            		= $leave_without_pay;
					$fields['leave_start_date']          	= $valid_data["leave_start_date"];
					$fields['leave_end_date']            	= $valid_data["leave_end_date"];
					$fields['leave_transaction_date']    	= date("Y-m-d");
					$fields['remarks']                   	= $valid_data["remarks"];
					
					$where									= array();
					$key									= $this->get_hash_key('leave_detail_id');
					$where[$key]							= $leave_detail_id;

					$table 									= $this->leaves->tbl_employee_leave_details;
					$this->leaves->update_general_data($table,$fields,$where);
					//end

					$audit_table[] 		= $this->leaves->tbl_employee_leave_details;
					$audit_schema[] 	= DB_MAIN;
					$prev_detail[] 		= array();
					$curr_detail[] 		= array($fields);
					$audit_action[] 	= AUDIT_UPDATE;	

					
					$activity 			= "Employee's leave balance had %s days deducted.";
					$audit_activity 	= sprintf($activity, $valid_data["leave_earned_used"]);
					break;
			}
			
			// TODO
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
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
	
		$data 				= array();
		$data['status'] 	= $status;
		$data['message'] 	= $message;
	
		echo json_encode($data);
	}
	//===========================================================marvin===========================================================
	public function get_leave_type_list()
	{

		try
		{
			$params = get_params();
			
			$aColumns 	= array("*");
			$bColumns 	= array("leave_type_name","active_flag");
			
			$leave_types = $this->leaves->get_leave_type_list($aColumns, $bColumns, $params);
			$iTotal          = $this->leaves->leave_type_total_length($params);
			$iFilteredTotal  = $this->leaves->leave_type_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			$module = MODULE_TA_LEAVES;

			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			foreach ($leave_types as $aRow):
				$row = array();
				
				$id 			= $this->hash($aRow['leave_type_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;

				$row[] = strtoupper($aRow['leave_type_name']);
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				$action = "<div class='table-actions'>";
				if($permission_view)
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_leave_type_adjustment' onclick=\"modal_leave_type_adjustment_init('".$url_view."')\" data-delay='50'></a>";
				if($permission_edit)
				$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_leave_type_adjustment' onclick=\"modal_leave_type_adjustment_init('".$url_edit."')\" data-delay='50'></a>";
				
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

	public function modal_leave_type_adjustment($action, $id, $token, $salt, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATATABLE,CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATATABLE,JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) )
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			// $fields = array('A.office_id','B.name AS office_name');
			// $tables = array(
				// 'main' => array(
					// 'table' => $this->leaves->tbl_param_offices,
					// 'alias' => 'A'
				// ),
				// 't1'   => array(
					// 'table' => $this->leaves->db_core . '.' . $this->leaves->tbl_organizations,
					// 'alias' => 'B',
					// 'type'  => 'JOIN',
					// 'condition' => 'A.org_code = B.org_code'
	 			// )
			// );
			// $where = array('A.active_flag' => 'Y');
			// $data['office_list'] = $this->leaves->get_general_data($fields, $tables, $where);
			
			//=======================================================marvin=======================================================
			//set user scopes
			$user_scopes['human_resources'] 		= isset($_SESSION['user_offices'][9]) ? $_SESSION['user_offices'][9] : '';
			$user_scopes['personal_data_sheets'] 	= isset($_SESSION['user_offices'][10]) ? $_SESSION['user_offices'][10] : '';
			$user_scopes['performance_evaluation'] 	= isset($_SESSION['user_offices'][11]) ? $_SESSION['user_offices'][11] : '';
			$user_scopes['time_and_attendance'] 	= isset($_SESSION['user_offices'][49]) ? $_SESSION['user_offices'][49] : '';
			$user_scopes['attendance_logs'] 		= isset($_SESSION['user_offices'][50]) ? $_SESSION['user_offices'][50] : '';
			$user_scopes['daily_time_record'] 		= isset($_SESSION['user_offices'][51]) ? $_SESSION['user_offices'][51] : '';
			$user_scopes['leaves'] 					= isset($_SESSION['user_offices'][53]) ? $_SESSION['user_offices'][53] : '';
			$user_scopes['payroll'] 				= isset($_SESSION['user_offices'][61]) ? $_SESSION['user_offices'][61] : '';
			$user_scopes['general_payroll'] 		= isset($_SESSION['user_offices'][63]) ? $_SESSION['user_offices'][63] : '';
			$user_scopes['special_payroll'] 		= isset($_SESSION['user_offices'][64]) ? $_SESSION['user_offices'][64] : '';
			$user_scopes['voucher'] 				= isset($_SESSION['user_offices'][65]) ? $_SESSION['user_offices'][65] : '';
			$user_scopes['remittance'] 				= isset($_SESSION['user_offices'][66]) ? $_SESSION['user_offices'][66] : '';
			$user_scopes['compensation'] 			= isset($_SESSION['user_offices'][12]) ? $_SESSION['user_offices'][12] : '';
			$user_scopes['deductions'] 				= isset($_SESSION['user_offices'][13]) ? $_SESSION['user_offices'][13] : '';
			
			$user_scope = explode(',',$user_scopes['leaves']);
			
			//filter base on role
			if(in_array('AO', $_SESSION['user_roles']) OR in_array('IMMSUP', $_SESSION['user_roles']) OR in_array('LVAPPOFF', $_SESSION['user_roles']))
			{
				$fields = array('A.user_id','A.lname','A.fname','A.mname');
				$tables = array(
					'main' => array(
						'table' => $this->leaves->db_core . '.' . $this->leaves->tbl_users,
						'alias' => 'A'
					)
				);
				$where = array('A.status_id' => 1);
				$where['A.user_id'] = array($user_scope, array('IN'));
				$data['user_list'] = $this->leaves->get_general_data($fields, $tables, $where);
			}
			else
			{
				$fields = array('A.office_id','B.name AS office_name');
				$tables = array(
					'main' => array(
						'table' => $this->leaves->tbl_param_offices,
						'alias' => 'A'
					),
					't1'   => array(
						'table' => $this->leaves->db_core . '.' . $this->leaves->tbl_organizations,
						'alias' => 'B',
						'type'  => 'JOIN',
						'condition' => 'A.org_code = B.org_code'
					)
				);
				$where = array('A.active_flag' => 'Y');
				$data['office_list'] = $this->leaves->get_general_data($fields, $tables, $where);
			}
			//=======================================================marvin=======================================================
			
			$post_data = array(
				'leave_type_id' => $id);
			$resources['datatable'][]	= array('table_id' => 'table_leave_type_adjustment', 'path' => 'main/leaves/get_leave_type_employee_list','advanced_filter'=>true,'post_data' => json_encode($post_data));
			
			$resources['load_modal']		= array(
						'modal_adjust_leave'     => array(
						'controller'             => __CLASS__,
						'module'                 => PROJECT_MAIN,
						'method'                 => 'modal_adjust_leave',
						'multiple'               => true,
						'height'                 => '500px',
						'size'                   => 'xl',
						'title'                  => "Leave Adjustment"
						),
						'modal_add_personnel'    => array(
						'controller'             => __CLASS__,
						'module'                 => PROJECT_MAIN,
						'method'                 => 'modal_add_personnel',
						'multiple'               => true,
						'height'                 => '500px',
						'size'                   => 'xl',
						'title'                  => "Add Employee"
						),
						'modal_remove_personnel' => array(
						'controller'             => __CLASS__,
						'module'                 => PROJECT_MAIN,
						'method'                 => 'modal_remove_personnel',
						'multiple'               => true,
						'height'                 => '500px',
						'size'                   => 'xl',
						'title'                  => "Remove Employee"
						),
						'modal_leave_history'		=> array(
								'controller'	=> __CLASS__,
								'module'		=> PROJECT_MAIN,
								'method'		=> 'modal_leave_history',
								'multiple'		=> true,
								'height'		=> '500px',
								'size'			=> 'lg',
								'title'			=> "Leave History"
						),
						'modal_add_monthly_leave_credit'		=> array(
								'controller'	=> __CLASS__,
								'module'		=> PROJECT_MAIN,
								'method'		=> 'modal_add_monthly_leave_credit',
								'multiple'		=> true,
								'height'		=> '220px',
								'size'			=> 'sm',
								'title'			=> "Monthly Leave Credits"
						),
						// added new modal
						'modal_cancel_approved_leave'		=> array(
							'controller'	=> __CLASS__,
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal_cancel_approved_leave',
							'multiple'		=> true,
							'height'		=> '500px',
							'size'			=> 'sm',
							'title'			=> "Return Leave"
						)	
						// end
			);

			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$data['leave_dtls'] = $this->leaves->get_general_data($field, $table, $where, FALSE);

			$this->load->view('leaves/modals/modal_leave_type_adjustment', $data);
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

	public function modal_add_monthly_leave_credit($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_SELECTIZE);
			$resources['load_js']  = array(JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];


			$leave_credits         = $this->leaves->get_leave_monthly_credit_dropdown($id);
			$data['credits_dropdown'] = array();
			if(isset($leave_credits['yearmonth_one']))
			{
				$credit = explode('-', $leave_credits['yearmonth_one']);
				$data['credits_dropdown'][] = array('month_id' =>$credit[0],'label' =>$credit[1]);
			}
			if(isset($leave_credits['yearmonth_two']))
			{
				$credit = explode('-', $leave_credits['yearmonth_two']);
				$data['credits_dropdown'][] = array('month_id' =>$credit[0],'label' =>$credit[1]);
			}
			if(isset($leave_credits['yearmonth_three']))
			{
				$credit = explode('-', $leave_credits['yearmonth_three']);
				$data['credits_dropdown'][] = array('month_id' =>$credit[0],'label' =>$credit[1]);
			}

			$this->load->view('leaves/modals/modal_add_monthly_leave_credit', $data);
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


	public function modal_adjust_leave($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$field                     = array("*") ;
			$table                     = $this->leaves->tbl_param_leave_transaction_types;
			$where                     = array();
			$where['active_flag'] 	   = YES;
			$data['transaction_types'] = $this->leaves->get_general_data($field, $table, $where, TRUE);
			
			$field                     = array("*") ;
			$table                     = $this->leaves->tbl_param_positions;
			$where                     = array();
			$where['active_flag'] 	   = YES;
			$data['positions']         = $this->leaves->get_general_data($field, $table, $where, TRUE);
			
			$data['salary_grade']      = $this->leaves->get_salary_grade();


			$field                     = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->db_core.".".$this->leaves->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
			$where                     = array();
			$where['A.active_flag'] = YES;
			$data['offices'] = $this->leaves->get_general_data($field, $tables, $where, TRUE);

			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];

			$this->load->view('leaves/modals/modal_adjust_leave', $data);
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

	public function modal_add_personnel($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$field                     = array("*") ;
			$table                     = $this->leaves->tbl_param_positions;
			$where                     = array();
			$where['active_flag'] 	= YES;
			$data['positions']         = $this->leaves->get_general_data($field, $table, $where, TRUE);
			
			$data['salary_grade']      = $this->leaves->get_salary_grade();


			$field                     = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->db_core.".".$this->leaves->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
			$where                     = array();
			$where['A.active_flag'] 	   = YES;
			$data['offices'] = $this->leaves->get_general_data($field, $tables, $where, TRUE);

			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];

			$this->load->view('leaves/modals/modal_add_personnel', $data);
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
	public function modal_remove_personnel($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module)
	{
		try
		{
			$data                  = array();
			$resources             = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']  = array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			$field                     = array("*") ;
			$table                     = $this->leaves->tbl_param_positions;
			$where                     = array();
			$where['active_flag'] 	= YES;
			$data['positions']         = $this->leaves->get_general_data($field, $table, $where, TRUE);
			
			$data['salary_grade']      = $this->leaves->get_salary_grade();


			$field                     = array("A.office_id","B.name") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_param_offices,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->db_core.".".$this->leaves->tbl_organizations,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.org_code = B.org_code',
				)
			);
			$where                     = array();
			$where['A.active_flag'] 	   = YES;
			$data['offices'] = $this->leaves->get_general_data($field, $tables, $where, TRUE);

			$field              = array("*") ;
			$table              = $this->leaves->tbl_param_leave_types;
			$where              = array();
			$key                = $this->get_hash_key('leave_type_id');
			$where[$key]        = $id;
			$leave_type         = $this->leaves->get_general_data($field, $table, $where, FALSE);
			$data['leave_type'] = $leave_type['leave_type_name'];

			$this->load->view('leaves/modals/modal_remove_personnel', $data);
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
	
	public function process_add_employee_leave()
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
			$employee_id	= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($employee_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($employee_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			/*CHECK DATA VALIDITY*/
			$valid_data = $this->_validate_add_employee_leave($params);

			Main_Model::beginTransaction();

			
			$where          = array();
			$tables         = $this->leaves->tbl_employee_personal_info;
			$key            = $this->get_hash_key('employee_id');
			$where[$key]    = $employee_id;
			$personal_info  = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);
			
			
			$fields                  = array() ;
			$fields['employee_id']   = $personal_info["employee_id"];
			$fields['leave_type_id'] = $valid_data["leave_type"];
			$fields['leave_balance'] = $valid_data["leave_earned_used"];

			$table 						= $this->leaves->tbl_employee_leave_balances;
			$this->leaves->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$fields                              = array() ;
			$fields['employee_id']               = $personal_info["employee_id"];
			$fields['leave_type_id']             = $valid_data["leave_type"];
			$fields['leave_transaction_type_id'] = LEAVE_INITIAL_BALANCE;
			$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
			$fields['effective_date']           = $valid_data["effective_date"];
			$fields['leave_transaction_date']    = date("Y-m-d");
			$fields['remarks']                   = $valid_data["remarks"];

			$table 							= $this->leaves->tbl_employee_leave_details;
			$this->leaves->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->leaves->tbl_employee_leave_details;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	

			$activity 				= "New Leave Type has been add to %s 's account.";
			$audit_activity 		= $personal_info["first_name"]." ".$personal_info["last_name"];
					
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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

	private function _validate_add_employee_leave($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields                   = array();
			
			$fields['leave_type']     = "Leave Type";
			$fields['effective_date'] = "Effective Date";
			$fields['remarks']        = "Remarks";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_add_employee_leave($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_add_employee_leave($params)
	{
		try
		{
			
			$validation['leave_type'] = array(
					'data_type' => 'enum',
					'name'		=> 'Leave Type',
					'max_len'	=> 1
			);	
			$validation['leave_earned_used'] = array(
					'data_type' => 'digit',
					'name'		=> 'Number of Days',
					'max_len'	=> 8
			);	
			$validation['effective_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Effective Date'
			);		
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function process_employee_leave_adjustment()
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
			$leave_type_id	= $params['id'];
			$module			= $params['module'];
			$employee_id	= $params['employee_id'];
			
			if(EMPTY($action) OR EMPTY($leave_type_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($leave_type_id . '/' . $action  . '/' . $module . '/' . $employee_id , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			/*CHECK DATA VALIDITY*/
			$valid_data = $this->_validate_employee_adjustment($params);

			if(!EMPTY($valid_data['leave_start_date']))
			{
				$result       = $this->check_working_days($valid_data['leave_start_date'],$valid_data['leave_end_date']);
				
				$working_days = count($result);
				$no_of_days   = $valid_data['leave_earned_used'] + $valid_data['no_of_days_wop'];

				if($no_of_days > ($working_days - 1) AND $no_of_days <= $working_days)
				{
					
				}
				else
				{
					throw new Exception("Number of working days does not match with <b>Number of Days</b> plus <b>Number of Days w/o Pay</b>.<br> Working Days : ".$working_days);
				}
			}
			Main_Model::beginTransaction();

			$field 						= array("*") ;
			$tables = array(
				'main'	=> array(
					'table'		=> $this->leaves->tbl_param_leave_types,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->leaves->tbl_employee_leave_balances,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.leave_type_id = B.leave_type_id',
				)
			);
			$where          = array();
			$key            = $this->get_hash_key('B.leave_type_id');
			$where[$key]    = $leave_type_id;
			$key            = $this->get_hash_key('B.employee_id');
			$where[$key]    = $employee_id;
			$leave_balances = $this->leaves->get_general_data($field, $tables, $where, FALSE);

			
			
			$where          = array();
			$tables         = $this->leaves->tbl_employee_personal_info;
			$key            = $this->get_hash_key('employee_id');
			$where[$key]    = $employee_id;
			$personal_info  = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);
			
			$where          = array();
			$tables         = $this->leaves->tbl_param_leave_types;
			$key            = $this->get_hash_key('leave_type_id');
			$where[$key]    = $leave_type_id;
			$leave_type     = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);

			switch($valid_data['transaction_type'])
			{
				case LEAVE_INITIAL_BALANCE:
					// davcorrea : 09/29/2023 : added validation forced leave cannot be added to employees with less than 10 VL
				// ===================================START================================================================
				if($leave_balances['leave_type_id'] == LEAVE_TYPE_FORCED)
				{
					$field 						= array("*") ;
					$tables = array(
						'main'	=> array(
							'table'		=> $this->leaves->tbl_param_leave_types,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->leaves->tbl_employee_leave_balances,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.leave_type_id = B.leave_type_id',
						)
					);
					$where                       = array();
					$where['B.leave_type_id']    = LEAVE_TYPE_VACATION;
					$key                         = $this->get_hash_key('B.employee_id');
					$where[$key]                 = $employee_id;
					$leave_Vl_balances           = $this->leaves->get_general_data($field, $tables, $where, FALSE);
					if($leave_Vl_balances['leave_balance'] < 10){
						throw new Exception("Vacation leave Balance is less than the minimum toa add Mandatory/Forced Leave");
					}
				}
				// ====================================END================================================
					if($leave_balances)
					{
						$fields 					= array() ;
						$fields['leave_balance']	= $valid_data["leave_earned_used"] + $leave_balances['leave_balance'];	

						$where						= array();
						$key 						= $this->get_hash_key('leave_type_id');
						$where[$key]				= $leave_type_id;
						$key 						= $this->get_hash_key('employee_id');
						$where[$key]				= $employee_id;
						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->update_general_data($table,$fields,$where);

						$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_UPDATE;	

						$fields                              = array() ;
						$fields['employee_id']               = $personal_info["employee_id"];
						$fields['leave_type_id']             = $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						$fields['effective_date']           = $valid_data["effective_date"];
						$fields['leave_transaction_date']    = date("Y-m-d");
						$fields['remarks']                   = $valid_data["remarks"];

						$table 							= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$activity 				= "%s has been updated.";
						$audit_activity 		= "Employee's initial leave balance has been updated.";

					}
					else
					{
						$fields                          = array() ;
						$fields['employee_id']           = $personal_info["employee_id"];
						$fields['leave_type_id']         = $leave_type["leave_type_id"];
						$fields['leave_balance'] = $valid_data["leave_earned_used"];

						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$fields                              = array() ;
						$fields['employee_id']               = $personal_info["employee_id"];
						$fields['leave_type_id']             = $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						$fields['effective_date']           = $valid_data["effective_date"];
						$fields['leave_transaction_date']    = date("Y-m-d");
						$fields['remarks']                   = $valid_data["remarks"];

						$table 							= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$activity 				= "%s has been updated.";
						$audit_activity 		= "Employee's initial leave balance has been updated.";
					}
				break;
				case LEAVE_CREDIT_LEAVE:
					if($leave_balances)
					{
						$fields 							= array() ;
						$fields['leave_balance']			= $leave_balances["leave_balance"] + $valid_data["leave_earned_used"];					
						
						$where						= array();
						$key 						= $this->get_hash_key('leave_type_id');
						$where[$key]				= $leave_type_id;
						$key 						= $this->get_hash_key('employee_id');
						$where[$key]				= $employee_id;
						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->update_general_data($table,$fields,$where);

						$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_UPDATE;	

						$fields                              = array() ;
						$fields['employee_id']               = $personal_info["employee_id"];
						$fields['leave_type_id']             = $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						$fields['effective_date']           = $valid_data["effective_date"];
						$fields['leave_transaction_date']    = date("Y-m-d");
						$fields['remarks']                   = $valid_data["remarks"];

						$table 							= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						
						$activity 				= "Employee's leave balance had %s days credit added.";
						$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);



					}
					else
					{
						$fields                          = array() ;
						$fields['employee_id']           = $personal_info["employee_id"];
						$fields['leave_type_id']         = $leave_type["leave_type_id"];
						$fields['leave_balance']         = $valid_data["leave_earned_used"];

						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$fields                              = array() ;
						$fields['employee_id']               = $personal_info["employee_id"];
						$fields['leave_type_id']             = $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						$fields['effective_date']           = $valid_data["effective_date"];
						$fields['leave_transaction_date']    = date("Y-m-d");
						$fields['remarks']                   = $valid_data["remarks"];

						$table 							= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$activity 				= "Employee's leave balance had %s days credit added.";
						$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
					}
				break;
				case LEAVE_REVERSE_LEAVE:
					if($leave_balances)
					{
						


						$field 						= array("*") ;
						$tables =	$this->leaves->tbl_employee_leave_details;
						$where                       = array();
						$where['leave_detail_id']    = $params['approved_leaves'];
						$leave_dtl           = $this->leaves->get_general_data($field, $tables, $where, FALSE);

						$field 						= array("*") ;
						$tables 					=	$this->leaves->tbl_employee_personal_info;
						$where                      = array();
						$where['employee_id']    	= $leave_dtl['employee_id'];
						$_emp_dtl_for_audit_trail           = $this->leaves->get_general_data($field, $tables, $where, FALSE);


						$fields 							= array() ;
						$fields['leave_balance']			= $leave_balances["leave_balance"] + $leave_dtl["leave_earned_used"];					
						$where						= array();
						$key 						= $this->get_hash_key('leave_type_id');
						$where[$key]				= $leave_type_id;
						$key 						= $this->get_hash_key('employee_id');
						$where[$key]				= $employee_id;
						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->update_general_data($table,$fields,$where);


						if($leave_balances['leave_type_id'] == LEAVE_TYPE_FORCED)
						{
							$field 						 = array("leave_balance") ;
							$tables 					 =	$this->leaves->tbl_employee_leave_balances;
							$where                       = array();
							$key 						 = $this->get_hash_key('employee_id');
							$where[$key]				 = $employee_id;
							$where['leave_type_id']		 = LEAVE_TYPE_VACATION;
							$vac_leave_balance           = $this->leaves->get_general_data($field, $tables, $where, FALSE);

							$fields 					= array() ;
							$fields['leave_balance']	= $vac_leave_balance["leave_balance"] + $leave_dtl["leave_earned_used"];					
							$where						= array();
							$where['leave_type_id']		= LEAVE_TYPE_VACATION;
							$key 						= $this->get_hash_key('employee_id');
							$where[$key]				= $employee_id;
							$table 						= $this->leaves->tbl_employee_leave_balances;
							$this->leaves->update_general_data($table,$fields,$where);

						}

						$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_UPDATE;	

						$fields 							= array() ;
						$fields['leave_transaction_type_id']			= LEAVE_REVERSE_LEAVE;					
						
						$where                       = array();
						$where['leave_detail_id']    = $params['approved_leaves'];
						$table 						= $this->leaves->tbl_employee_leave_details;
						$this->leaves->update_general_data($table,$fields,$where);



						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_UPDATE;	

						// $fields                              = array() ;
						// $fields['employee_id']               = $personal_info["employee_id"];
						// $fields['leave_type_id']             = $leave_type["leave_type_id"];
						// $fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						// $fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						// $fields['effective_date']           = $valid_data["effective_date"];
						// $fields['leave_start_date']         = $valid_data["leave_start_date"];
						// $fields['leave_end_date']           = $valid_data["leave_end_date"];
						// $fields['leave_transaction_date']    = date("Y-m-d");
						// $fields['remarks']                   = $valid_data["remarks"];

						// $table 							= $this->leaves->tbl_employee_leave_details;
						// $this->leaves->insert_general_data($table,$fields,FALSE);

						// $audit_table[]			= $this->leaves->tbl_employee_leave_details;
						// $audit_schema[]			= DB_MAIN;
						// $prev_detail[] 			= array();
						// $curr_detail[]			= array($fields);
						// $audit_action[] 		= AUDIT_INSERT;	

						
						// $activity 				= ."'s leave balance had %s days returned.";
						// echo"<pre>";
						// print_r($$_emp_dtl_for_audit_trail);
						// die();
						$audit_activity 		= $_emp_dtl_for_audit_trail['last_name']. "," . $_emp_dtl_for_audit_trail['first_name'] ."s leave ". $leave_dtl['leave_start_date']. "-".  $leave_dtl['leave_end_date'] ." has been returned";
					}
					else
					{
						$fields                          = array() ;
						$fields['employee_id']           = $personal_info["employee_id"];
						$fields['leave_type_id']         = $leave_type["leave_type_id"];
						$fields['leave_balance']         = $valid_data["leave_earned_used"];

						$table 						= $this->leaves->tbl_employee_leave_balances;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$fields                              = array() ;
						$fields['employee_id']               = $personal_info["employee_id"];
						$fields['leave_type_id']             = $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						$fields['effective_date']           = $valid_data["effective_date"];
						$fields['leave_start_date']         = $valid_data["leave_start_date"];
						$fields['leave_end_date']           = $valid_data["leave_end_date"];
						$fields['leave_transaction_date']    = date("Y-m-d");
						$fields['remarks']                   = $valid_data["remarks"];
						$table 							= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$activity 				= "Employee's leave balance had %s days credit added.";
						$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
					}
				break;				case LEAVE_FILE_LEAVE:
				case LEAVE_DEDUCTION:
					if($leave_balances['leave_type_id'] == LEAVE_TYPE_FORCED)
				{
					$leave_type = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['deduct_bal_leave_type_id'] : $leave_balances['leave_type_id'];
					$field                  = array('*');
					$where                  = array();
					$where["employee_id"]   = $leave_balances['employee_id'];
					$where["leave_type_id"] = $leave_type;
					$tables                 = $this->leaves->tbl_employee_leave_balances;
					$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);

					/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
					if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
					{
						/*
						IF ENOUGH BALANCE:
							SET NORMAL LEAVE WITH OR WITH OUT PAY
						*/
						$new_leave_balance = $check_balance['leave_balance'] - $valid_data["leave_earned_used"];
						$leave_with_pay    = $valid_data["leave_earned_used"];
						$leave_without_pay = $valid_data["no_of_days_wop"];
					}
					else
					{
						/*
						IF NOT ENOUGH BALANCE:
							-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
							-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
						*/
						$new_leave_balance = 0;
						$leave_with_pay    = $check_balance['leave_balance'];
						$leave_without_pay = ($valid_data['leave_earned_used'] - $check_balance['leave_balance']) + $valid_data['no_of_days_wop'];
					}

					$fields                  = array() ;
					$fields['leave_balance'] = $new_leave_balance;					
					
					$where                   = array();
					$where['leave_type_id']  = $leave_type;
					$key                     = $this->get_hash_key('employee_id');
					$where[$key]             = $employee_id;
					$table                   = $this->leaves->tbl_employee_leave_balances;
					$this->leaves->update_general_data($table,$fields,$where);

					$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_UPDATE;	

					// $fields                              = array() ;
					// $fields['employee_id']               = $personal_info["employee_id"];
					// $fields['leave_type_id']             = $leave_type;
					// $fields['orig_leave_type_id']        = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['leave_type_id'] : NULL;
					// $fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
					// $fields['leave_earned_used']         = $leave_with_pay;
					// $fields['leave_wop']            = $leave_without_pay;
					// $fields['leave_start_date']          = $valid_data["leave_start_date"];
					// $fields['leave_end_date']            = $valid_data["leave_end_date"];
					// $fields['leave_transaction_date']    = date("Y-m-d");
					// $fields['remarks']                   = $valid_data["remarks"];

					// $table 							= $this->leaves->tbl_employee_leave_details;
					// $this->leaves->insert_general_data($table,$fields,FALSE);

					// $audit_table[]			= $this->leaves->tbl_employee_leave_details;
					// $audit_schema[]			= DB_MAIN;
					// $prev_detail[] 			= array();
					// $curr_detail[]			= array($fields);
					// $audit_action[] 		= AUDIT_INSERT;	

					
					// $activity 				= "Employee's leave balance had %s days deducted.";
					// $audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);

					$leave_type = $leave_balances['leave_type_id'];
					$field                  = array('*');
					$where                  = array();
					$where["employee_id"]   = $leave_balances['employee_id'];
					$where["leave_type_id"] = $leave_type;
					$tables                 = $this->leaves->tbl_employee_leave_balances;
					$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);

					/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
					if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
					{
						/*
						IF ENOUGH BALANCE:
							SET NORMAL LEAVE WITH OR WITH OUT PAY
						*/
						$new_leave_balance = $check_balance['leave_balance'] - $valid_data["leave_earned_used"];
						$leave_with_pay    = $valid_data["leave_earned_used"];
						$leave_without_pay = $valid_data["no_of_days_wop"];
					}
					else
					{
						/*
						IF NOT ENOUGH BALANCE:
							-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
							-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
						*/
						$new_leave_balance = 0;
						$leave_with_pay    = $check_balance['leave_balance'];
						$leave_without_pay = ($valid_data['leave_earned_used'] - $check_balance['leave_balance']) + $valid_data['no_of_days_wop'];
					}

					$fields                  = array() ;
					$fields['leave_balance'] = $new_leave_balance;					
					
					$where                   = array();
					$where['leave_type_id']  = $leave_type;
					$key                     = $this->get_hash_key('employee_id');
					$where[$key]             = $employee_id;
					$table                   = $this->leaves->tbl_employee_leave_balances;
					$this->leaves->update_general_data($table,$fields,$where);

					$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_UPDATE;	

					$fields                              = array() ;
					$fields['employee_id']               = $personal_info["employee_id"];
					$fields['leave_type_id']             = $leave_type;
					$fields['orig_leave_type_id']        = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['leave_type_id'] : NULL;
					$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
					$fields['leave_earned_used']         = $leave_with_pay;
					$fields['leave_wop']            = $leave_without_pay;
					$fields['leave_start_date']          = $valid_data["leave_start_date"];
					$fields['leave_end_date']            = $valid_data["leave_end_date"];
					$fields['leave_transaction_date']    = date("Y-m-d");
					$fields['remarks']                   = $valid_data["remarks"];

					$table 							= $this->leaves->tbl_employee_leave_details;
					$this->leaves->insert_general_data($table,$fields,FALSE);

					$audit_table[]			= $this->leaves->tbl_employee_leave_details;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	

					
					$activity 				= "Employee's leave balance had %s days deducted.";
					$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
				}
				else
				{
					$leave_type = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['deduct_bal_leave_type_id'] : $leave_balances['leave_type_id'];
					$field                  = array('*');
					$where                  = array();
					$where["employee_id"]   = $leave_balances['employee_id'];
					$where["leave_type_id"] = $leave_type;
					$tables                 = $this->leaves->tbl_employee_leave_balances;
					$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);

					/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
					if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
					{
						/*
						IF ENOUGH BALANCE:
							SET NORMAL LEAVE WITH OR WITH OUT PAY
						*/
						$new_leave_balance = $check_balance['leave_balance'] - $valid_data["leave_earned_used"];
						$leave_with_pay    = $valid_data["leave_earned_used"];
						$leave_without_pay = $valid_data["no_of_days_wop"];
					}
					else
					{
						/*
						IF NOT ENOUGH BALANCE:
							-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
							-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
						*/
						$new_leave_balance = 0;
						$leave_with_pay    = $check_balance['leave_balance'];
						$leave_without_pay = ($valid_data['leave_earned_used'] - $check_balance['leave_balance']) + $valid_data['no_of_days_wop'];
					}

					$fields                  = array() ;
					$fields['leave_balance'] = $new_leave_balance;					
					
					$where                   = array();
					$where['leave_type_id']  = $leave_type;
					$key                     = $this->get_hash_key('employee_id');
					$where[$key]             = $employee_id;
					$table                   = $this->leaves->tbl_employee_leave_balances;
					$this->leaves->update_general_data($table,$fields,$where);

					$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_UPDATE;	

					$fields                              = array() ;
					$fields['employee_id']               = $personal_info["employee_id"];
					$fields['leave_type_id']             = $leave_type;
					$fields['orig_leave_type_id']        = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['leave_type_id'] : NULL;
					$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
					$fields['leave_earned_used']         = $leave_with_pay;
					$fields['leave_wop']            = $leave_without_pay;
					$fields['leave_start_date']          = $valid_data["leave_start_date"];
					$fields['leave_end_date']            = $valid_data["leave_end_date"];
					$fields['leave_transaction_date']    = date("Y-m-d");
					$fields['remarks']                   = $valid_data["remarks"];

					$table 							= $this->leaves->tbl_employee_leave_details;
					$this->leaves->insert_general_data($table,$fields,FALSE);

					$audit_table[]			= $this->leaves->tbl_employee_leave_details;
					$audit_schema[]			= DB_MAIN;
					$prev_detail[] 			= array();
					$curr_detail[]			= array($fields);
					$audit_action[] 		= AUDIT_INSERT;	

					
					$activity 				= "Employee's leave balance had %s days deducted.";
					$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
				}
					
				break;
			}
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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
	private function _validate_employee_adjustment($params)
	{
		try
		{
			//SPECIFY HERE INPUTS FROM USER
			$fields = array();
			if($params['transaction_type'] == LEAVE_FILE_LEAVE )
			{
				$fields['leave_start_date']  = "Leave Start Date";
				$fields['leave_end_date']    = "Leave End Date";
				if(EMPTY($params['no_of_days_wop']))
				{
					$fields['leave_earned_used'] = "Number of Days";
				}
			}
			else
			{
				if($params['transaction_type'] != LEAVE_REVERSE_LEAVE )
				{
					$fields['effective_date'] = "Effectivity Date";
				}
				
			}
			$fields['transaction_type']  = "Leave Transaction Type";
			$fields['remarks']           = "Remarks";
			
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_employee_adjustment($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_employee_adjustment($params)
	{
		try
		{
			
			$validation['transaction_type'] = array(
					'data_type' => 'enum',
					'name'		=> 'Leave Transaction Type',
					'max_len'	=> 1
			);	
			$validation['leave_earned_used'] = array(
					'data_type' => 'digit',
					'name'		=> 'Number of Days',
					'max_len'	=> 8
			);	
			$validation['no_of_days_wop'] = array(
					'data_type' => 'digit',
					'name'		=> 'Number of Days w/o Pay',
					'max_len'	=> 8
			);
			//marvin
			$validation['leave_transaction_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Transaction Date',
			);
			//marvin
			$validation['effective_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Effectivity Date',
			);
			$validation['leave_start_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Leave Start Date',
					'max_date'  => $params['leave_end_date']
			);	
			$validation['leave_end_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Leave End Date'
			);	
			$validation['remarks'] = array(
					'data_type' => 'string',
					'name'		=> 'Remarks'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function get_leave_type_employee_list()
	{
		try
		{
			$params = get_params();

			// $aColumns 	= array("A.employee_id","A.agency_employee_id","CONCAT(A.first_name,' ',A.last_name) as fullname","J.name","B.leave_balance","SUM(IF(E.request_sub_status_id = 1 AND F.no_of_days IS NOT NULL, F.no_of_days,0))as pending_leave");
			// ====================== jendaigo : start : change name format ============= //
			$aColumns 	= array("A.employee_id","A.agency_employee_id","CONCAT(A.last_name, ', ', A.first_name, IF(A.ext_name='' OR A.ext_name IS NULL, '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/' OR A.middle_name IS NULL), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) as fullname","J.name","B.leave_balance","SUM(IF(E.request_sub_status_id = 1 AND F.no_of_days IS NOT NULL, F.no_of_days,0))as pending_leave");
			// ====================== jendaigo : end : change name format ============= //
			
			$bColumns 	= array("A.agency_employee_id","fullname","J.name","B.leave_balance","pending_leave");
		
			$employee_list 	= $this->leaves->leave_type_employee_list($aColumns, $bColumns, $params);
			$iTotal		= $this->leaves->leave_type_employee_list_total_length();
			$iFilteredTotal = $this->leaves->leave_type_employee_list_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
			
			$module = MODULE_TA_LEAVES;
			/*
			$permission_view = $this->permission->check_permission($this->permission_module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($this->permission_module, ACTION_EDIT);
			$permission_delete = $this->permission->check_permission($this->permission_module, ACTION_DELETE);
			*/
			
			foreach ($employee_list as $aRow):
				$cnt++;
				$row = array();

				$id 			= $this->hash($aRow['employee_id']);
				
				$salt			= gen_salt();
				$token_view	 	= in_salt($params['leave_type_id'] . '/' . ACTION_VIEW  . '/' . $module .'/'.$id , $salt);
				$token_edit	 	= in_salt($params['leave_type_id']   . '/' . ACTION_EDIT  . '/' . $module.'/'.$id, $salt);
				
				$url_view 		= ACTION_VIEW."/".$params['leave_type_id'] ."/".$token_view."/".$salt."/".$module.'/'.$id;
				$url_edit 		= ACTION_EDIT."/".$params['leave_type_id'] ."/".$token_edit."/".$salt."/".$module.'/'.$id;

				
				$row[] = $aRow['agency_employee_id'];
				$row[] = $aRow['fullname'];
				$row[] = $aRow['name'];
				$row[] = !EMPTY($aRow['leave_balance']) ? $aRow['leave_balance']:'0';
				$row[] = !EMPTY($aRow['pending_leave']) ? $aRow['pending_leave']:'0';
				$action = "<div class='table-actions'>";

				$benefit_employee = $aRow["employee_id"];
				$id               = $this->hash ($benefit_id);
				$salt             = gen_salt();
				$token_edit       = in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete     = in_salt($id . '/' . ACTION_DELETE, $salt);
				$edit_action      = ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete       = ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				
				$delete_action    = 'content_delete("benefit", "'.$url_delete.'")';
			
				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View History' data-position='bottom' data-modal='modal_leave_history' onclick=\"modal_leave_history_init('".$url_view."')\" data-delay='50'></a>";
				// START davcorrea button modal
				$action .= "<a href='#' class='flaticon-return9 tooltipped md-trigger' style='color:#666; font-size:8px;' data-tooltip='Return Leaves' data-position='bottom' data-modal='modal_cancel_approved_leave' onclick=\"modal_cancel_approved_leave_init('".$url_view."')\" data-delay='50'></a>";
				// END davcorrea 
				//$action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
				$action .= "</div>";

				if($cnt == $total)
					$action .= $resources;
				
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
	public function get_add_personnel_list()
	{
		$params				= get_params();
		$list 				= array();
		$append_personnnel_list = "";

		
		$employee_list       		= $this->leaves->get_add_personnel_list($params);
		
		if($employee_list)
		{
			foreach ($employee_list as $aRow):		

				$id 			= $this->hash($aRow['employee_id']);
				
				$list[] = array(
							"value" => $id,
							"text" => ucwords($aRow["fullname"])
					);
				$append_personnnel_list .= '<tr class="employee_div">
				  					<td><input type="hidden" name="employee_list[]" value="'.$id.'">'.$aRow["agency_employee_id"].'</td>
			  					<td>'.ucwords($aRow["fullname"]).'</td>
			  					<td class="table-actions"><a herf="javascript:;" class="delete cursor-pointer" id="remove_personnel"></a></td>
		  					</tr>';
			endforeach;

			
		}
		
		
		$info = array(
				"list" => $list,
				"append_personnnel_list" => $append_personnnel_list
		);
	
		echo json_encode($info);
	}

	public function get_specific_personnel()
	{
		$params				= get_params();
		$list 				= array();
		$append_personnnel_list = "";
		$region_code 			= $params['region_code'];

		
		$employee_list       		= $this->leaves->get_specific_personnel_list($params);
		
		if($employee_list)
		{
			foreach ($employee_list as $aRow):		

				$id 			= $this->hash($aRow['employee_id']);
				
				$list[] = array(
							"value" => $id,
							"text" => ucwords($aRow["fullname"])
					);
				$append_personnnel_list .= '<tr class="employee_div">
				  					<td><input type="hidden" name="employee_list[]" value="'.$id.'">'.$aRow["agency_employee_id"].'</td>
			  					<td>'.ucwords($aRow["fullname"]).'</td>
			  					<td class="table-actions"><a herf="javascript:;" class="delete cursor-pointer" id="remove_personnel"></a></td>
		  					</tr>';
			endforeach;
		}
		
		
		$info = array(
				"list" => $list,
				"append_personnnel_list" => $append_personnnel_list
		);
	
		echo json_encode($info);
	}
	public function process_add_personnel()
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
			$leave_type_id	= $params['id'];
			$module			= $params['module'];
			$employee_id	= $params['employee_id'];
			
			if(EMPTY($action) OR EMPTY($leave_type_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($leave_type_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if(EMPTY($params['employee_list']))
				throw new Exception($this->lang->line('invalid_action'));
			
			Main_Model::beginTransaction();

			$field 						= array("*") ;
			$tables = $this->leaves->tbl_param_leave_types;
				
			$where          = array();
			$key            = $this->get_hash_key('leave_type_id');
			$where[$key]    = $leave_type_id;
			$leave_type 	= $this->leaves->get_general_data($field, $tables, $where, FALSE);
			foreach($params['employee_list'] as $employee_id)
			{
				$where          = array();
				$tables         = $this->leaves->tbl_employee_personal_info;
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $employee_id;
				$personal_info  = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);
				
				$fields                          = array() ;
				$fields['employee_id']           = $personal_info["employee_id"];
				$fields['leave_type_id']         = $leave_type["leave_type_id"];
				$fields['leave_balance']         = 0;					

				$table 						= $this->leaves->tbl_employee_leave_balances;
				$this->leaves->insert_general_data($table,$fields,FALSE);

				$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array();
				$curr_detail[]			= array($fields);
				$audit_action[] 		= AUDIT_INSERT;	
			}
			

			$audit_activity 		= "Personnels were added to leave type ".$leave_type['leave_type_name'];


			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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
	public function get_remove_personnel_list()
	{
		$params				= get_params();
		$list 				= array();
		$append_personnnel_list = "";

		
		$employee_list       		= $this->leaves->get_remove_personnel_list($params);
		
		if($employee_list)
		{
			foreach ($employee_list as $aRow):		

				$id 			= $this->hash($aRow['employee_id']);
				
				$list[] = array(
							"value" => $id,
							"text" => ucwords($aRow["fullname"])
					);
				$append_personnnel_list .= '<tr class="employee_div">
				  					<td><input type="hidden" name="employee_list[]" value="'.$id.'">'.$aRow["agency_employee_id"].'</td>
			  					<td>'.ucwords($aRow["fullname"]).'</td>
			  					<td class="table-actions"><a herf="javascript:;" class="delete cursor-pointer" id="remove_personnel"></a></td>
		  					</tr>';
			endforeach;

			
		}
		
		
		$info = array(
				"list" => $list,
				"append_personnnel_list" => $append_personnnel_list
		);
	
		echo json_encode($info);
	}
	public function process_remove_personnel()
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
			$leave_type_id	= $params['id'];
			$module			= $params['module'];
			$employee_id	= $params['employee_id'];
			
			if(EMPTY($action) OR EMPTY($leave_type_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($leave_type_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if(EMPTY($params['employee_list']))
				throw new Exception($this->lang->line('invalid_action'));
			
			Main_Model::beginTransaction();

			
			foreach($params['employee_list'] as $employee_id)
			{
				$where          = array();
				$tables         = $this->leaves->tbl_employee_leave_balances;
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $employee_id;
				$key            = $this->get_hash_key('leave_type_id');
				$where[$key]    = $leave_type_id;
				$prev  = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);
				
								

				$where          = array();
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $employee_id;
				$key            = $this->get_hash_key('leave_type_id');
				$where[$key]    = $leave_type_id;


				$table 						= $this->leaves->tbl_employee_leave_details;
				$this->leaves->delete_general_data($table,$where);
				
				$table 						= $this->leaves->tbl_employee_leave_balances;
				$this->leaves->delete_general_data($table,$where);



				$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
				$audit_schema[]			= DB_MAIN;
				$prev_detail[] 			= array($prev);
				$curr_detail[]			= array();
				$audit_action[] 		= AUDIT_DELETE;	
			}
			

			$audit_activity 		= "Personnels were deleted from leave type ".$leave_type['leave_type_name'];


			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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

	public function process_leave_adjustment()
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
			$leave_type_id	= $params['id'];
			$module			= $params['module'];
			
			if(EMPTY($action) OR EMPTY($leave_type_id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($leave_type_id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			if(EMPTY($params['employee_list']))
				throw new Exception("No personnel found in list.");
			/*CHECK DATA VALIDITY*/
			$valid_data = $this->_validate_employee_adjustment($params);
			if(!EMPTY($valid_data['leave_start_date']))
			{
				$result       = $this->check_working_days($valid_data['leave_start_date'],$valid_data['leave_end_date']);
				
				$working_days = count($result);
				$no_of_days   = $valid_data['leave_earned_used'] + $valid_data['no_of_days_wop'];

				if($no_of_days > ($working_days - 1) AND $no_of_days <= $working_days)
				{
					
				}
				else
				{
					throw new Exception("Number of working days does not match with <b>Number of Days</b> plus <b>Number of Days w/o Pay</b>.<br> Working Days : ".$working_days);
				}
			}
			
			Main_Model::beginTransaction();
			foreach($params['employee_list'] as $employee_id)
			{
				$field 						= array("*") ;
				$tables = array(
					'main'	=> array(
						'table'		=> $this->leaves->tbl_param_leave_types,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->leaves->tbl_employee_leave_balances,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'A.leave_type_id = B.leave_type_id',
					)
				);
				$where          = array();
				$key            = $this->get_hash_key('B.leave_type_id');
				$where[$key]    = $leave_type_id;
				$key            = $this->get_hash_key('B.employee_id');
				$where[$key]    = $employee_id;
				$leave_balances = $this->leaves->get_general_data($field, $tables, $where, FALSE);
				

				$where          = array();
				$tables         = $this->leaves->tbl_employee_personal_info;
				$key            = $this->get_hash_key('employee_id');
				$where[$key]    = $employee_id;
				$personal_info  = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);
			
				$where          = array();
				$tables         = $this->leaves->tbl_param_leave_types;
				$key            = $this->get_hash_key('leave_type_id');
				$where[$key]    = $leave_type_id;
				$leave_type     = $this->leaves->get_general_data(array('*'), $tables, $where, FALSE);
				
				
				switch($valid_data['transaction_type'])
				{
					case LEAVE_INITIAL_BALANCE:
					case LEAVE_CREDIT_LEAVE:
					case LEAVE_REVERSE_LEAVE:
						// davcorrea : 09/29/2023 : added validation forced leave cannot be added to employees with less than 10 VL
				// ===================================START================================================================
				if($leave_balances['leave_type_id'] == LEAVE_TYPE_FORCED)
				{
					$field 						= array("*") ;
					$tables = array(
						'main'	=> array(
							'table'		=> $this->leaves->tbl_param_leave_types,
							'alias'		=> 'A',
						),
						't2'	=> array(
							'table'		=> $this->leaves->tbl_employee_leave_balances,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.leave_type_id = B.leave_type_id',
							)
						);
						$where                       = array();
						$where['B.leave_type_id']    = LEAVE_TYPE_VACATION;
						$key                         = $this->get_hash_key('B.employee_id');
						$where[$key]                 = $employee_id;
						$leave_Vl_balances           = $this->leaves->get_general_data($field, $tables, $where, FALSE);
						if($leave_Vl_balances['leave_balance'] < 10){
							
							continue 2;
						}
				}
				// ====================================END================================================
						if($leave_balances)
						{
							
							$fields                  = array() ;
							$fields['leave_balance'] = $valid_data["leave_earned_used"] + $leave_balances['leave_balance'];	
							
							$where                   = array();
							$key                     = $this->get_hash_key('leave_type_id');
							$where[$key]             = $leave_type_id;
							$key                     = $this->get_hash_key('employee_id');
							$where[$key]             = $employee_id;
							$table                   = $this->leaves->tbl_employee_leave_balances;
							$this->leaves->update_general_data($table,$fields,$where);

							$audit_table[]  = $this->leaves->tbl_employee_leave_balances;
							$audit_schema[] = DB_MAIN;
							$prev_detail[]  = array();
							$curr_detail[]  = array($fields);
							$audit_action[] = AUDIT_UPDATE;	

						}
						else
						{
							$fields                  = array() ;
							$fields['employee_id']   = $personal_info["employee_id"];
							$fields['leave_type_id'] = $leave_type["leave_type_id"];
							$fields['leave_balance'] = $valid_data["leave_earned_used"];
							
							$table                   = $this->leaves->tbl_employee_leave_balances;
							$this->leaves->insert_general_data($table,$fields,FALSE);

							$audit_table[]  = $this->leaves->tbl_employee_leave_balances;
							$audit_schema[] = DB_MAIN;
							$prev_detail[]  = array();
							$curr_detail[]  = array($fields);
							$audit_action[] = AUDIT_INSERT;	

						}

						$fields                              = array() ;
						$fields['employee_id']               = $personal_info["employee_id"];
						$fields['leave_type_id']             = $leave_type["leave_type_id"];
						$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
						$fields['leave_earned_used']         = $valid_data["leave_earned_used"];
						$fields['effective_date']           = $valid_data["effective_date"];
						$fields['leave_transaction_date']    = date("Y-m-d");
						$fields['remarks']                   = $valid_data["remarks"];

						$table 							= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$fields,FALSE);

						$audit_table[]			= $this->leaves->tbl_employee_leave_details;
						$audit_schema[]			= DB_MAIN;
						$prev_detail[] 			= array();
						$curr_detail[]			= array($fields);
						$audit_action[] 		= AUDIT_INSERT;	

						$activity 				= "Employee's leave balance had %s days credit added.";
						$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
					break;					
					case LEAVE_FILE_LEAVE:
					case LEAVE_COMMUTATION:
					case LEAVE_DEDUCTION:
						if($leave_balances['leave_type_id'] == LEAVE_TYPE_FORCED)
						{
							$leave_type = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['deduct_bal_leave_type_id'] : $leave_balances['leave_type_id'];
							$field                  = array('*');
							$where                  = array();
							$where["employee_id"]   = $leave_balances['employee_id'];
							$where["leave_type_id"] = $leave_type;
							$tables                 = $this->leaves->tbl_employee_leave_balances;
							$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);
		
							/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
							if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
							{
								/*
								IF ENOUGH BALANCE:
									SET NORMAL LEAVE WITH OR WITH OUT PAY
								*/
								$new_leave_balance = $check_balance['leave_balance'] - $valid_data["leave_earned_used"];
								$leave_with_pay    = $valid_data["leave_earned_used"];
								$leave_without_pay = $valid_data["no_of_days_wop"];
							}
							else
							{
								/*
								IF NOT ENOUGH BALANCE:
									-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
									-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
								*/
								$new_leave_balance = 0;
								$leave_with_pay    = $check_balance['leave_balance'];
								$leave_without_pay = ($valid_data['leave_earned_used'] - $check_balance['leave_balance']) + $valid_data['no_of_days_wop'];
							}
		
							$fields                  = array() ;
							$fields['leave_balance'] = $new_leave_balance;					
							
							$where                   = array();
							$where['leave_type_id']  = $leave_type;
							$key                     = $this->get_hash_key('employee_id');
							$where[$key]             = $employee_id;
							$table                   = $this->leaves->tbl_employee_leave_balances;
							$this->leaves->update_general_data($table,$fields,$where);
		
							$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
							$audit_schema[]			= DB_MAIN;
							$prev_detail[] 			= array();
							$curr_detail[]			= array($fields);
							$audit_action[] 		= AUDIT_UPDATE;	
		
							$leave_type = $leave_balances['leave_type_id'];
							$field                  = array('*');
							$where                  = array();
							$where["employee_id"]   = $leave_balances['employee_id'];
							$where["leave_type_id"] = $leave_type;
							$tables                 = $this->leaves->tbl_employee_leave_balances;
							$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);
		
							/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
							if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
							{
								/*
								IF ENOUGH BALANCE:
									SET NORMAL LEAVE WITH OR WITH OUT PAY
								*/
								$new_leave_balance = $check_balance['leave_balance'] - $valid_data["leave_earned_used"];
								$leave_with_pay    = $valid_data["leave_earned_used"];
								$leave_without_pay = $valid_data["no_of_days_wop"];
							}
							else
							{
								/*
								IF NOT ENOUGH BALANCE:
									-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
									-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
								*/
								$new_leave_balance = 0;
								$leave_with_pay    = $check_balance['leave_balance'];
								$leave_without_pay = ($valid_data['leave_earned_used'] - $check_balance['leave_balance']) + $valid_data['no_of_days_wop'];
							}
		
							$fields                  = array() ;
							$fields['leave_balance'] = $new_leave_balance;					
							
							$where                   = array();
							$where['leave_type_id']  = $leave_type;
							$key                     = $this->get_hash_key('employee_id');
							$where[$key]             = $employee_id;
							$table                   = $this->leaves->tbl_employee_leave_balances;
							$this->leaves->update_general_data($table,$fields,$where);
		
							$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
							$audit_schema[]			= DB_MAIN;
							$prev_detail[] 			= array();
							$curr_detail[]			= array($fields);
							$audit_action[] 		= AUDIT_UPDATE;	
		
							$fields                              = array() ;
							$fields['employee_id']               = $personal_info["employee_id"];
							$fields['leave_type_id']             = $leave_type;
							$fields['orig_leave_type_id']        = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['leave_type_id'] : NULL;
							$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
							$fields['leave_earned_used']         = $leave_with_pay;
							$fields['leave_wop']            = $leave_without_pay;
							$fields['leave_start_date']          = $valid_data["leave_start_date"];
							$fields['leave_end_date']            = $valid_data["leave_end_date"];
							$fields['leave_transaction_date']    = date("Y-m-d");
							$fields['remarks']                   = $valid_data["remarks"];
		
							$table 							= $this->leaves->tbl_employee_leave_details;
							$this->leaves->insert_general_data($table,$fields,FALSE);
		
							$audit_table[]			= $this->leaves->tbl_employee_leave_details;
							$audit_schema[]			= DB_MAIN;
							$prev_detail[] 			= array();
							$curr_detail[]			= array($fields);
							$audit_action[] 		= AUDIT_INSERT;	
		
							
							$activity 				= "Employee's leave balance had %s days deducted.";
							$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
						}
						else
						{
							$leave_type = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['deduct_bal_leave_type_id'] : $leave_balances['leave_type_id'];
							$field                  = array('*');
							$where                  = array();
							$where["employee_id"]   = $leave_balances['employee_id'];
							$where["leave_type_id"] = $leave_type;
							$tables                 = $this->leaves->tbl_employee_leave_balances;
							$check_balance          = $this->leaves->get_general_data($field, $tables, $where, false);
		
							/*CHECK IF AVAILABLE LEAVE BALANCE IS SUFFICIENT ENOUGH FOR THE TRANSACTION*/
							if($check_balance['leave_balance'] >= $valid_data["leave_earned_used"])
							{
								/*
								IF ENOUGH BALANCE:
									SET NORMAL LEAVE WITH OR WITH OUT PAY
								*/
								$new_leave_balance = $check_balance['leave_balance'] - $valid_data["leave_earned_used"];
								$leave_with_pay    = $valid_data["leave_earned_used"];
								$leave_without_pay = $valid_data["no_of_days_wop"];
							}
							else
							{
								/*
								IF NOT ENOUGH BALANCE:
									-ALL THE AVAILABLE LEAVE BALANCE WILL BE USED AS LEAVE WITH PAY
									-THE REMAINING NUMBER OF DAYS WILL BE ADDED TO LEAVE WITHOUT PAY
								*/
								$new_leave_balance = 0;
								$leave_with_pay    = $check_balance['leave_balance'];
								$leave_without_pay = ($valid_data['leave_earned_used'] - $check_balance['leave_balance']) + $valid_data['no_of_days_wop'];
							}
		
							$fields                  = array() ;
							$fields['leave_balance'] = $new_leave_balance;					
							
							$where                   = array();
							$where['leave_type_id']  = $leave_type;
							$key                     = $this->get_hash_key('employee_id');
							$where[$key]             = $employee_id;
							$table                   = $this->leaves->tbl_employee_leave_balances;
							$this->leaves->update_general_data($table,$fields,$where);
		
							$audit_table[]			= $this->leaves->tbl_employee_leave_balances;
							$audit_schema[]			= DB_MAIN;
							$prev_detail[] 			= array();
							$curr_detail[]			= array($fields);
							$audit_action[] 		= AUDIT_UPDATE;	
		
							$fields                              = array() ;
							$fields['employee_id']               = $personal_info["employee_id"];
							$fields['leave_type_id']             = $leave_type;
							$fields['orig_leave_type_id']        = (!EMPTY($leave_balances['deduct_bal_leave_type_id'])) ? $leave_balances['leave_type_id'] : NULL;
							$fields['leave_transaction_type_id'] = $valid_data["transaction_type"];
							$fields['leave_earned_used']         = $leave_with_pay;
							$fields['leave_wop']            = $leave_without_pay;
							$fields['leave_start_date']          = $valid_data["leave_start_date"];
							$fields['leave_end_date']            = $valid_data["leave_end_date"];
							$fields['leave_transaction_date']    = date("Y-m-d");
							$fields['remarks']                   = $valid_data["remarks"];
		
							$table 							= $this->leaves->tbl_employee_leave_details;
							$this->leaves->insert_general_data($table,$fields,FALSE);
		
							$audit_table[]			= $this->leaves->tbl_employee_leave_details;
							$audit_schema[]			= DB_MAIN;
							$prev_detail[] 			= array();
							$curr_detail[]			= array($fields);
							$audit_action[] 		= AUDIT_INSERT;	
		
							
							$activity 				= "Employee's leave balance had %s days deducted.";
							$audit_activity 		= sprintf($activity, $valid_data["leave_earned_used"]);
						}
							
					break;
				}
				// davcorrea :10/24/2023 : START: validate if leave adjustments is successful , will not be sucessful if only one employee is selected and employee has less than 10 vl and selected adjustment is FL
				$status= true;
				// davcorrea : END
			}
			// davcorrea :10/24/2023 : START: validate if leave adjustments is successful , will not be sucessful if only one employee is selected and employee has less than 10 vl and selected adjustment is FL
			if(!$status)
			{
				throw new Exception("Employee Vacation Leave is below minimum to add MANDATORY/ Forced leave");
			}
			// davcorrea : END
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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

	public function process_monthly_leave_credit()
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
			if(EMPTY($params['month_year']))
				throw new Exception($this->lang->line('invalid_action'));
			
			Main_Model::beginTransaction();

			$field                                  = array("*") ;
			$tables                                 = $this->leaves->tbl_attendance_period_hdr;
			
			$where                                  = array();
			$where['DATE_FORMAT(date_from,"%m%Y")'] = $params["month_year"];
			$where['payroll_type_id']               = 1;/*REGULAR PAYROLL*/
			$period_status                          = $this->leaves->get_general_data($field, $tables, $where, TRUE);

			$promt_period_error = FALSE;
			if($period_status)
			{
				foreach ($period_status as $key => $value) {
					if($value['period_status_id'] == ATTENDANCE_PERIOD_PROCESSING)
						$promt_period_error = TRUE;
				}
			}
			else
			{
				$promt_period_error = TRUE;
			}
			if($promt_period_error)
				throw new Exception('Please complete <b>Attendance Period</b> for the selected month to proceed the process.');

			$field 						= array("*") ;
			$tables = $this->leaves->tbl_param_leave_types;
				
			$where          = array();
			$key            = $this->get_hash_key('leave_type_id');
			$where[$key]    = $id;
			$leave_type 	= $this->leaves->get_general_data($field, $tables, $where, FALSE);
			/*CHECK PREVIOUS MONTH RECORDS*/
			$month       = substr($params["month_year"],0,2);
			$year        = substr($params["month_year"],2,5) ;
			$month_year  = $year."-".$month."-01";
			$start_month = date('Y-m-d',strtotime($month_year));
			$end_month   = date('Y-m-t',strtotime($month_year));
			$month_year  = date('mY',strtotime ( $month_year ));	
			$leave_credits 	= $this->leaves->get_leave_monthly_credit_employess($leave_type['leave_type_id'], $month_year);
			

			if($leave_credits)
			{
				foreach ($leave_credits as $value) {
					$detail_fields 					= array() ;
					$detail_fields_deduction 		= array() ;
					
					
					// davcorrea get employee absences for the month and get the computation equivalent for leave earning
					$absents_details      = $this->leaves->get_employee_absents($value['employee_id'],$month_year);
					$absents['lwop_days'] = count($absents_details);
					if($absents['lwop_days'] > 0)
					{
						$where 									= array();
						$where['computation_table_id'] 			= TA_COMP_TABLE_VLWOP;//table for VLWOP
						$days 									= $absents['lwop_days'] * 2;
						$where['computation_type_equivalent'] 	= $days;
						$point_equivalent 						= $this->leaves->get_general_data(array("point_equivalent"), $this->leaves->tbl_param_computation_table_detail, $where, FALSE);
						$new_leave_earned 						= $point_equivalent['point_equivalent'];
					}
					else
					{
						$new_leave_earned = 1.25;
					}

					// davcorrea get employee lates and undertime
					$lates      = $this->leaves->get_employee_lates_and_undertime($value['employee_id'],$month_year);
					$sixty_minutes = 60;
					if($lates['total_ut_mins'] > $sixty_minutes)
					{
						$total_hrs_deduc  = intval($lates['total_ut_mins'] / $sixty_minutes) + $lates['total_ut_hrs'];
						$total_mins_deduc = fmod($lates['total_ut_mins'] , $sixty_minutes);
					}
					else
					{
						$total_hrs_deduc  = $lates['total_ut_hrs'];
						$total_mins_deduc = $lates['total_ut_mins'];
					}
					$total_hrs_deduc_equiv  = 0;
					$total_mins_deduc_equiv = 0;
					$deduction_remarks 		= "";
					// davcorrea get computation table equivalent and construct remarks
					if($total_hrs_deduc > 0)
					{
						$where 									= array();
						$where['computation_table_id'] 			= TA_COMP_TABLE_HOUR;
						$where['computation_type_equivalent'] 	= $total_hrs_deduc;
						$point_equivalent 						= $this->leaves->get_general_data(array("point_equivalent"), $this->leaves->tbl_param_computation_table_detail, $where, FALSE);
						$total_hrs_deduc_equiv 					= $point_equivalent['point_equivalent'];
						$deduction_remarks 					   .= $total_hrs_deduc . " hour/s";
					}
					if($total_hrs_deduc > 0 AND $total_mins_deduc > 0)
					{
						$deduction_remarks .= " , ";
					}
					if($total_mins_deduc > 0)
					{
						$where 									= array();
						$where['computation_table_id'] 			= TA_COMP_TABLE_MINUTE;
						$where['computation_type_equivalent'] 	= $total_mins_deduc;
						$point_equivalent 						= $this->leaves->get_general_data(array("point_equivalent"), $this->leaves->tbl_param_computation_table_detail, $where, FALSE);
						$total_mins_deduc_equiv					= $point_equivalent['point_equivalent'];
						$deduction_remarks 					   .= $total_mins_deduc . " min/s";
					}
					$total_deduc = $total_hrs_deduc_equiv + $total_mins_deduc_equiv;
					
					// davcorrea get balance after earning and deductions
					if($leave_type['leave_type_id'] == LEAVE_TYPE_VACATION)
					{
						if($total_deduc > ($value['leave_balance'] + $new_leave_earned))
						{
							$_lwop = $total_deduc - ($value['leave_balance'] + $new_leave_earned);
							$total_deduc = $value['leave_balance'] + $new_leave_earned;
							$new_leave_balance = 0;
						}
						else{
							$new_leave_balance = $value['leave_balance'] + $new_leave_earned + (-$total_deduc);
							$_lwop = 0;
						}

					}else
					{
						$new_leave_balance = $value['leave_balance'] + $new_leave_earned;
					}
					
					$remarks = "Monthly leave credits earned";
					$detail_fields[] = array(
											'employee_id'               => $value['employee_id'],
											'leave_type_id'             => $leave_type["leave_type_id"],
											'leave_transaction_date'    => date('Y-m-d'),
											'effective_date'            => $end_month,
											'leave_start_date'          => $start_month,
											'leave_end_date'            => $end_month,
											'leave_earned_used'         => $new_leave_earned,
											'leave_transaction_type_id' => LEAVE_CREDIT_LEAVE,
											'leave_wop'                  => 0,
											'remarks'                   => $remarks

											) ;
					// davcorrea insert earned leaves
					$table 	= $this->leaves->tbl_employee_leave_details;
					$this->leaves->insert_general_data($table,$detail_fields,FALSE);

					// davcorrea insert deducted leaves from undertime and lates
					if($leave_type['leave_type_id'] == LEAVE_TYPE_VACATION)
					{
						if( $total_deduc > 0)
						{
							$detail_fields_deduction[] = array(
											'employee_id'               => $value['employee_id'],
											'leave_type_id'             => $leave_type["leave_type_id"],
											'leave_transaction_date'    => date('Y-m-d'),
											'effective_date'           	=> $end_month,
											'leave_start_date'			=> $start_month,
											'leave_end_date'			=> $end_month,
											'leave_earned_used'         => $total_deduc,
											'leave_transaction_type_id' => LEAVE_DEDUCTION,
											'leave_wop'                  => $_lwop,
											'remarks'                   => $deduction_remarks
											) ;
						$table 	= $this->leaves->tbl_employee_leave_details;
						$this->leaves->insert_general_data($table,$detail_fields_deduction,FALSE);
						}
						foreach($absents_details as $absences_days)
						{
							$detail_fields_absences 		= array() ;
							$detail_fields_absences[] = array(
								'employee_id'               => $value['employee_id'],
								'leave_type_id'             => $leave_type["leave_type_id"],
								'leave_transaction_date'    => $absences_days['attendance_date'],
								'effective_date'           	=> $end_month,
								'leave_start_date'			=> $absences_days['attendance_date'],
								'leave_end_date'			=> $absences_days['attendance_date'],
								'leave_earned_used'         => 0,
								'leave_transaction_type_id' => LEAVE_FILE_LEAVE,
								'leave_wop'                  => 1,
								'remarks'                   => "ABSENT W/O Official Leave"
								) ;
								$table 	= $this->leaves->tbl_employee_leave_details;
								$this->leaves->insert_general_data($table,$detail_fields_absences,FALSE);
							}
							
					}
					// davcorrea update leave balance
					$fields 					= array() ;
					$fields['leave_balance']	= $new_leave_balance;	

					$where						= array();
					$key 						= $this->get_hash_key('leave_type_id');
					$where[$key]				= $id;
					$where['employee_id']		= $value['employee_id'];
					$table 						= $this->leaves->tbl_employee_leave_balances;
					$this->leaves->update_general_data($table,$fields,$where);
				}
				
			}
			
			$fields                          = array() ;
			$fields['leave_year_month']      = $params["month_year"];
			$fields['leave_type_id']         = $leave_type["leave_type_id"];
			$fields['date_processed']        = date('Y-m-d');					

			$table 						= $this->leaves->tbl_leave_monthly_credits;
			$this->leaves->insert_general_data($table,$fields,FALSE);

			$audit_table[]			= $this->leaves->tbl_leave_monthly_credits;
			$audit_schema[]			= DB_MAIN;
			$prev_detail[] 			= array();
			$curr_detail[]			= array($fields);
			$audit_action[] 		= AUDIT_INSERT;	
			$audit_activity 		= $leave_type['leave_type_name']." monthly credit has been proccessed.";

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);

			Main_Model::commit();
			$status = true;
			$message = $this->lang->line('data_updated');
			
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
	public function check_working_days($date_from = NULL,$date_to =NULL)
	{
		try
		{	
			$dates       = array();
			$date_from   = date('Y-m-d',strtotime ($date_from));
			$date_to     = date('Y-m-d',strtotime ($date_to));
			
			$active_date = $date_from;

			if($date_from <= $date_to )
			{
				while($active_date <= $date_to )
				{
					$result = $this->check_date($active_date);
					if($result)
					{
						$dates[] = $active_date;
					}
					$active_date = date('Y-m-d',strtotime('+1 day' , strtotime ( $active_date ) ) );					
				}				
			}
			

			return $dates;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	public function check_date($date)
	{
		try
		{	

			$day    = date('N',strtotime($date));

			if($day == '6' OR $day == '7')
			{
				return false;
			}
			else
			{
				$active_date    = date('Y-m-d',strtotime($date));

				$tables                = $this->leaves->tbl_param_work_calendar;
				$where                 = array();
				$where['holiday_date'] = $active_date;
				$holiday               = $this->leaves->get_general_data(array("*"), $tables, $where, FALSE);
				if($holiday)
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */