<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_remittance extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payroll_remittance_model', 'remittance');
	}
	
	public function index()
	{
		$data = $resources = array();
		
		$resources['load_css'] 		= array(CSS_DATATABLE);
		$resources['load_js'] 		= array(JS_DATATABLE);
		$resources['datatable'][]	= array('table_id' => 'payroll_remittance_list_tbl', 'path' => 'main/payroll_remittance/get_payroll_list', 'advanced_filter' => true);

		$resources['load_modal']		= array(
			'modal_remittance'		=> array(
					'controller'	=> strtolower(__CLASS__),
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal',
					'multiple'		=> true,
					'height'		=> '300px',
					'size'			=> 'sm',
					'title'			=> SUB_MENU_REMITTANCE_PAYROLL
			),
			'modal_payment'		=> array(
					'controller'	=> strtolower(__CLASS__),
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal',
					'multiple'		=> true,
					'height'		=> '150px',
					'size'			=> 'sm',
					'title'			=> SUB_MENU_REMITTANCE_PAYROLL
			),
			'modal_remittance_attachment'		=> array(
					'controller'	=> strtolower(__CLASS__),
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal_remittance_attachment',
					'multiple'		=> true,
					'height'		=> '450px',
					'size'			=> 'md',
					'title'			=> 'Remittance Attachment'
			)
		);


		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll_remittance";
		$key					= "Remittances"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll_remittance";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('remittance/remittance', $data, $resources);

	}
	
	public function get_payroll_list()
	{

		try
		{
			$params                = get_params();
			
			$aColumns              = array("A.remittance_id","B.remittance_type_name", "A.deduction_start_date", "A.deduction_end_date", "C.remittance_status_name", "C.remittance_status_id");
			$bColumns              = array("B.remittance_type_name", "DATE_FORMAT(A-deduction_start_date, '%Y/%m/%d')", "DATE_FORMAT(A-deduction_end_date, '%Y/%m/%d')", "C.remittance_status_id");
			
			$remittance_list       = $this->remittance->get_payroll_remittance($aColumns, $bColumns, $params);

			$iTotal                = $this->remittance->total_length($this->remittance->tbl_remittances);
			$iFilteredTotal        = $this->remittance->filtered_length($aColumns, $bColumns, $params);

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

			$module             = MODULE_PAYROLL_REMITTANCE;
			$permission_view    = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit    = $this->permission->check_permission($module, ACTION_EDIT);
			$permission_remit    = $this->permission->check_permission($module, ACTION_REMIT);
			$permission_process = $this->permission->check_permission($module, ACTION_PROCESS);
			$permission_delete  = $this->permission->check_permission($module, ACTION_DELETE);
		
						
			foreach ($remittance_list as $aRow):

				$row = array();
				
				$id            = $this->hash($aRow['remittance_id']);
				
				$salt          = gen_salt();
				$token_view    = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit    = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_process = in_salt($id  . '/' . ACTION_PROCESS  . '/' . $module, $salt);
				
				$remitted          = $aRow['remittance_status_id'] == REMITTANCE_REMITTED ? 1 : 0;
				$url_view          = 'modal_remittance/' . ACTION_VIEW."/".$id ."/".$salt."/".$token_view."/".$module;
				$url_edit          = 'modal_remittance/' . ACTION_EDIT."/".$id ."/".$salt."/".$token_edit."/".$module."/".$remitted;
				$url_process       = ACTION_PROCESS."/".$id ."/".$salt."/".$token_process."/".$module."/".$remitted;
				$url_payment       = 'modal_payment/' . ACTION_EDIT."/".$id ."/".$salt."/".$token_edit."/".$module;
				$url_attachment    = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$remittance_status = $aRow['remittance_status_id'];
				$month    = substr($aRow['year_month'], 4);
				$year     = substr($aRow['year_month'], 0, 4);

				$row[]      = ucwords($aRow['remittance_type_name']);
				$row[] 	    = ($aRow['payroll_type_flag'] == PAYOUT_TYPE_FLAG_VOUCHER ? 'Voucher - ' : 'Payroll - ') . format_date('1-'.$month.'-'.$year, 'F Y');
				$row[]      = '<center>' . format_date($aRow['deduction_start_date']) . '</center>';
				$row[]      = '<center>' . format_date($aRow['deduction_end_date']) . '</center>';
				$row[]      = $aRow['remittance_status_name'];

				$action 				= "<div class='table-actions'>";
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_remittance' onclick=\"modal_remittance_init('".$url_view."')\"></a>";
				
				if((($permission_edit AND $remittance_status == REMITTANCE_FOR_REMITTANCE) OR ($permission_remit AND $remittance_status == REMITTANCE_PROCESSING))  AND !$remitted)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_remittance' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_remittance_init('".$url_edit."')\"></a>";
						
				if($permission_process)
				$action .= "<a href='javascript:;' class='process tooltipped'  data-tooltip='Process' data-position='bottom' data-delay='50' onclick=\"content_form('payroll_remittance/display_remittance_process/".$url_process."', '".PROJECT_MAIN."')\"></a>";
				
				// if($aRow['remittance_status_id'] == REMITTANCE_REMITTED)
				// $action .= "<a href='#!' class='activity tooltipped md-trigger' data-modal='modal_payment' data-tooltip='Enter payment date' data-position='bottom' data-delay='50' onclick=\"modal_payment_init('".$url_payment."')\"></a>";
				
				if($remittance_status == REMITTANCE_REMITTED AND $this->permission->check_permission($module, ACTION_UPLOAD))
				$action .= "<a href='#!' class='attach tooltipped md-trigger' data-modal='modal_remittance_attachment' data-tooltip='Attachments' data-position='bottom' data-delay='50' onclick=\"modal_remittance_attachment_init('".$url_attachment."')\"></a>";
							
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

	public function display_remittance_process($action, $id, $salt, $token, $module, $remitted=NULL)
	{
		try
		{
			$resources = array();
			$resources['load_css'] 		= array(CSS_SELECTIZE, CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_SELECTIZE, JS_DATATABLE, JS_NUMBER);		

			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_modal'] = array(
				'modal_employee_deductions' => array(
					'controller'	=> __CLASS__,
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal_employee_deductions',
					'multiple'		=> true,
					'height'		=> '360px',
					'size'			=> 'lg',
					'title'			=> 'Employee Deductions'
				)
			);
			$data =  array();
	
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token; 
			$data['module']			= $module;
			$data['remitted']		= $remitted;
	
			/*BREADCRUMBS*/
			$breadcrumbs 			= array();
			$key					= "Payroll"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/payroll_remittance";
			$key					= "Remittances"; 
			$breadcrumbs[$key]		= PROJECT_MAIN."/payroll_remittance";
			$key					= "Remittance"; 
			$breadcrumbs[$key]		= "";
			set_breadcrumbs($breadcrumbs, TRUE);
			
			$fields = array('A.office_id','B.name AS office_name');
			$tables = array(
				'main' => array(
					'table' => $this->remittance->tbl_param_offices,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->remittance->db_core . '.' . $this->remittance->tbl_organizations,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.org_code = B.org_code'
	 			)
			);
			$where = array('A.active_flag' => 'Y');
			
			$data['office_list'] = $this->remittance->get_payroll_remittance_data($fields, $tables, $where);
			
			$this->template->load('remittance/display_remittance_process', $data, $resources);
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}
	}

	public function modal($modal = NULL, $action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data              = array();
			$data['action']    = $action;
			$data['id']        = $id;
			$data['salt']      = $salt;
			$data['token']     = $token;
			$data['module']    = MODULE_PAYROLL_REMITTANCE;

			$resources['load_js']  = array(JS_DATETIMEPICKER,JS_SELECTIZE, JS_LABELAUTY);
			$resources['load_css'] = array(CSS_DATETIMEPICKER,CSS_SELECTIZE, CSS_LABELAUTY);

			switch ($modal) 
			{
				case 'modal_remittance':

					$field                    = array('*');

					if($action != ACTION_ADD)
					{
						$tables = array(
							'main' => array(
								'table' => $this->remittance->tbl_remittances,
								'alias' => 'A'
							),
							't1' => array(
								'table' => $this->remittance->tbl_param_remittance_status,
								'alias' => 'B',
								'type' => 'JOIN',
								'condition' => 'A.remittance_status_id = B.remittance_status_id'
							),
							't2' => array(
								'table' => $this->remittance->tbl_param_deductions,
								'alias' => 'C',
								'type' => 'JOIN',
								'condition' => 'A.remittance_type_id = C.remittance_type_id'
							)
						);
						$key                     = $this->get_hash_key('remittance_id');
						$where                   = array();
						$where[$key]             = $id;
						$data['remittance_info'] = $this->remittance->get_payroll_remittance_data($field,$tables,$where,FALSE);

						$resources['single'] = array(
							'remittance_type_id'   => $data['remittance_info']['remittance_type_id'],
							'certified_by'         => $data['remittance_info']['certified_by'],
							'approved_by'          => $data['remittance_info']['approved_by']
						);
						
						if($data['remittance_info']['payroll_type_flag'] == PAYOUT_TYPE_FLAG_REGULAR)
						$resources['multiple'] = array(
							'payroll_type'   => explode('|', $data['remittance_info']['payroll_type_ids'])
						);
						
					}
					$fields = array('A.employee_id, CONCAT(A.last_name, \' \' ,ifnull(A.ext_name,\'\'), \', \' ,A.first_name, \' \',LEFT(A.middle_name,1), \'.\') as fullname');

					$tables = array(
						'main' => array(
							'table' => $this->remittance->tbl_employee_personal_info,
							'alias' => 'A'
						),
						't2' => array(
							'table'     => $this->remittance->db_core .'.'. $this->remittance->tbl_sys_param,
							'alias'     => 'B',
							'type'      => 'JOIN',
							'condition' => 'A.agency_employee_id = B.sys_param_value AND B.sys_param_type = "' . PARAM_PAYROLL_CERTIFIED_BY . '" AND B.active_flag = "Y"'
						)
					);

					$data['certified_by'] = $this->remittance->get_payroll_remittance_data($fields, $tables);
				
					$tables = array(
						'main' => array(
							'table' => $this->remittance->tbl_employee_personal_info,
							'alias' => 'A'
						),
						't2' => array(
							'table'     => $this->remittance->db_core .'.'. $this->remittance->tbl_sys_param,
							'alias'     => 'B',
							'type'      => 'JOIN',
							'condition' => 'A.agency_employee_id = B.sys_param_value AND B.sys_param_type = "' . PARAM_PAYROLL_APPROVED_BY . '" AND B.active_flag = "Y"'
						)
					);

					$data['approved_by'] = $this->remittance->get_payroll_remittance_data($fields, $tables);

					$table                    = $this->remittance->tbl_param_remittance_types;
					$where                    = array();
					if($action == ACTION_ADD)
					{
						$where['active_flag'] 	= YES;			
					}
					else
					{
						$where['active_flag'] 	= array(YES, array("=", "OR", "("));
				 		$where['remittance_type_id']= array($data['remittance_info']['remittance_type_id'], array("=", ")"));				
					}	
					$data['remittance_types'] = $this->remittance->get_payroll_remittance_data(array('*'), $table, $where);
					
					$fields 					= array("*");
					$tables						= $this->remittance->tbl_param_payroll_types;
					$where 						= array();
					if($action == ACTION_ADD)
					{
						$where['active_flag'] 	= YES;			
					}
					else
					{
						$where['active_flag'] 	= array(YES, array("=", "OR", "("));
				 		$where['payroll_type_id']= array($details['payroll_type_id'], array("=", ")"));				
					}	
					$data['payroll_types']		= $this->remittance->get_payroll_remittance_data($fields, $tables, $where);
				break;

				case 'modal_payment':

					$tables = array(
						'main' => array(
							'table' => $this->remittance->tbl_remittances,
							'alias' => 'A'
						),
						't2' => array(
							'table' => $this->remittance->tbl_param_remittance_status,
							'alias' => 'B',
							'type' => 'JOIN',
							'condition' => 'A.remittance_status_id = B.remittance_status_id'
						)
					);
					$key                     = $this->get_hash_key('remittance_id');
					$where                   = array();
					$where[$key]             = $id;
					$data['remittance_info'] = $this->remittance->get_payroll_remittance_data(array('payment_date, payment_details'),$tables,$where,FALSE);

				break;
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
		$this->load->view('remittance/modals/' . $modal, $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_tab($form, $action, $id, $token, $salt, $module)
	{
		try
		{
			$resources['load_css'] 	= array(CSS_LABELAUTY);
			$resources['load_js'] 	= array(JS_LABELAUTY);
			
			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$data 					= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['token']			= $token;
			$data['salt']			= $salt;
			$data['module']			= $module;			

			// $data['summary_id'] = 134;
			switch ($form)
			{
				case 'tab_personnel_list':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE, JS_NUMBER);
					$path = "main/payroll_remittance/get_remittance_employee_list/$action/$id/$token/$salt/$module";
					$resources['datatable'][]	= array('table_id' => 'table_personnel_list', 'path' => $path, 'advanced_filter' => true);
					
				break;

				case 'tab_process_status':
					$resources['load_css'] 		= array(CSS_SELECTIZE);
					$resources['load_js'] 		= array(JS_SELECTIZE);

					$tables = array(
							'main' => array(
								'table' => $this->remittance->tbl_remittances,
								'alias' => 'A'
							),
							't2' => array(
								'table' => $this->remittance->tbl_param_remittance_status,
								'alias' => 'B',
								'type' => 'JOIN',
								'condition' => 'A.remittance_status_id = B.remittance_status_id'
							),
							't3' => array(
								'table' => $this->remittance->tbl_param_remittance_types,
								'alias' => 'C',
								'type' => 'JOIN',
								'condition' => 'A.remittance_type_id = C.remittance_type_id'
							)
						);
					$key                     = $this->get_hash_key('remittance_id');
					$where                   = array();
					$where[$key]             = $id;
					$data['remittance_info'] = $this->remittance->get_payroll_remittance_data(array('*'),$tables,$where,FALSE);
					
					$resources['single'] = array(
						'remittance_status_id'          => $data['remittance_info']['remittance_status_id']
					);
					$table                     = $this->remittance->tbl_param_remittance_status;
					$where                     = array();
					$where['active_flag']      = 'Y';
					$data['remittance_status'] = $remittance_status = $this->remittance->get_payroll_remittance_data(array('*'), $table, $where);
					
					// $data['has_permission']    = FALSE;
					
					// foreach ($remittance_status as $key => $status) {
					// 	if($status['remittance_status_id'] == $data['remittance_info']['remittance_status_id'])
					// 		$data['has_permission'] = $this->permission->check_permission($module, $status['action_id']);
					// }
					

				break;

				case 'tab_process_history':
					$resources['load_css'] 		= array(CSS_DATATABLE);
					$resources['load_js'] 		= array(JS_DATATABLE);
					$path = "main/payroll_remittance/get_remittance_history_list/$action/$id/$token/$salt/$module";
					$resources['datatable'][]	= array('table_id' => 'table_process_history', 'path' => $path, 'advanced_filter' => true);
				break;
	
			}

			$this->load->view('remittance/tabs/'.$form, $data);
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

	public function get_remittance_employee_list($action, $id, $token, $salt, $module)
	{
		try
		{
			$params = get_params();
			
			$aColumns = array('A.employee_id, D.agency_employee_id, CONCAT(D.last_name, IF(D.ext_name=\'\',\'\',CONCAT(\' \', D.ext_name)), \', \', D.first_name, \' \',LEFT(D.middle_name,1), \'.\') as fullname, H.name, G.employment_status_name, SUM(C.amount) amount, ifnull(B.reference_text,\'\') as reference_text');
			$bColumns = array('D.agency_employee_id', 'CONCAT(D.last_name, IF(D.ext_name=\'\',\'\',CONCAT(\' \', D.ext_name)), \', \', D.first_name, \' \',LEFT(D.middle_name,1), \'.\')', 'H.name', 'G.employment_status_name', 'SUM(C.amount)', 'ifnull(B.reference_text,\'\')');
			
			$return_list	= $this->remittance->get_payout_employee_list($aColumns, $bColumns, $id, $params);
			
			$employee_list	= $return_list['data'];
		
			$iFilteredTotal	= $return_list['filtered_length'];		

			$table = array(
				'main' => array(
					'table' => $this->remittance->tbl_remittance_details,
					'alias' => 'A'
				),
				't2' => array(
					'table'     => $this->remittance->tbl_payout_details,
					'alias'     => 'B',
					'type'      => 'LEFT JOIN',
					'condition' => 'A.payroll_dtl_id = B.payroll_dtl_id'
				),
				't3' => array(
					'table'     => $this->remittance->tbl_payout_header,
					'alias'     => 'C',
					'type'      => 'LEFT JOIN',
					'condition' => 'C.payroll_hdr_id = B.payroll_hdr_id'
				)
			);
			$where	= array($this->get_hash_key('remittance_id') => $id);			
			$iTotal	= $this->remittance->get_total_length($table, 'C.employee_id', $where);

			$output = array(
				"sEcho"                => intval($params['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			
			foreach ($employee_list as $aRow):
				$row = array();
				
				$id2 			= $this->hash($aRow['employee_id']);
				
				$salt2			= gen_salt();
				$token_view2 	= in_salt($id . '/' . $id2  . '/' . ACTION_VIEW  . '/' . $module, $salt2);
				$token_edit2 	= in_salt($id . '/' . $id2  . '/' . ACTION_EDIT  . '/' . $module, $salt2);
				$url_view 		= ACTION_VIEW."/".$id."/".$id2."/".$salt2."/".$token_view2."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id."/".$id2."/".$salt2."/".$token_edit2."/".$module;

				$row[]      = $aRow['agency_employee_id'];
				$row[]      = $aRow['fullname'];
				$row[]      = $aRow['name'];
				$row[]      = $aRow['employment_status_name'];
				$row[]      = '<p class="m-n right amount">&#8369; ' . number_format($aRow['amount'],2) . '</p>';


				$action = "<div class='table-actions'>";

				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_employee_deductions' onclick=\"modal_employee_deductions_init('".$url_view."')\"></a>";
				if($permission_edit)
				$action .= "<a href='javascript:;' data-tooltip='Edit' class='edit tooltipped md-trigger' data-modal='modal_employee_deductions' onclick=\"modal_employee_deductions_init('".$url_edit."')\"></a>";		
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

	public function get_remittance_history_list($action, $id, $token, $salt, $module)
	{

		try
		{
			$params         = get_params();
			
			$aColumns       = array("A.*,C.remittance_status_name,CONCAT(D.last_name, if(D.ext_name='','',CONCAT(' ', D.ext_name)), ', ', D.first_name, ' ',LEFT(D.middle_name,1), '.') as processed_by");
			$bColumns       = array("CONCAT(D.last_name, if(D.ext_name='','',CONCAT(' ', D.ext_name)), ', ', D.first_name, ' ',LEFT(D.middle_name,1), '.')", "A.hist_date", "C.remittance_status_name", "A.remarks");
			
			$return_list    = $this->remittance->get_payroll_remittance_history($aColumns, $bColumns, $id, $params);
			$history_list   = $return_list['data'];
			$iFilteredTotal = $return_list['filtered_length'];
			$iTotal         = $this->remittance->total_length($this->remittance->tbl_remittance_history);
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData" => array()
			);
		

			foreach ($history_list as $aRow):
				$row = array();
				
				$id 			= $this->hash($aRow['history_id']);
				$salt			= gen_salt();
				$token_view	 	= in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit	 	= in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				
				$url_view 		= ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit 		= ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;

				$row[] = $aRow['processed_by'];
				$row[] = '<center>' . format_date($aRow['hist_date'], 'Y/m/d H:i:s') . '</center>';
				$row[] = $aRow['remittance_status_name'];
				$row[] = $aRow['remarks'];
				
					
				$output['aaData'][] = $row;
			endforeach;
			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
		}

		echo json_encode( $output );
	}
	
	public function modal_employee_deductions($action_id = NULL, $remittance_id = NULL, $employee_id = NULL, $salt = NULL, $token = NULL, $module = NULL)
	{
		try
		{
			$status = 0;
			$data = array();
			// GET SECURITY VARIABLES
			if (EMPTY ( $action_id ) or EMPTY ( $remittance_id ) or EMPTY ( $employee_id  ) or EMPTY ( $salt ) or EMPTY ( $token ) or EMPTY ( $module )) {
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			}
			if ($token != in_salt ( $remittance_id . '/' . $employee_id  . '/' . $action_id . '/' . $module, $salt )) {
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			}
			$resources = array();
			// $resources['load_js']  = array(JS_NUMBER);
			$data['action'] = $action_id;
			$data['salt'] = $salt;
			$data['token'] = $token;
			$data['remittance_id'] = $remittance_id;
			$data['employee_id'] = $employee_id;
			$data['module'] = $module;

			$fields = array('C.deduction_name, DATE_FORMAT(A.effective_date, "%Y/%m/%d") effective_date, D.amount, A.reference_text, D.remarks, A.payroll_dtl_id, D.orig_amount');

			$table = array(
				'main' => array(
					'table' => $this->remittance->tbl_payout_details,
					'alias' => 'A'
				),
				't2' => array(
					'table'     => $this->remittance->tbl_payout_header,
					'alias'     => 'B',
					'type'      => 'LEFT JOIN',
					'condition' => 'A.payroll_hdr_id = B.payroll_hdr_id'
				),
				't3' => array(
					'table'     => $this->remittance->tbl_param_deductions,
					'alias'     => 'C',
					'type'      => 'LEFT JOIN',
					'condition' => 'C.deduction_id = A.deduction_id'
				),
				't4' => array(
					'table'	    => $this->remittance->tbl_remittance_details,
					'alias'		=> 'D',
					'type'		=> 'JOIN',
					'condition' => 'A.payroll_dtl_id = D.payroll_dtl_id'
				),
				't5' => array(
					'table'	    => $this->remittance->tbl_remittances,
					'alias'		=> 'E',
					'type'		=> 'JOIN',
					'condition' => 'D.remittance_id = E.remittance_id'
				)

			);
			$where	= array($this->get_hash_key('E.remittance_id') => $remittance_id, $this->get_hash_key('B.employee_id') => $employee_id);	
			// $where	= array();	

			$data['deduction_list'] = $this->remittance->get_payroll_remittance_data($fields, $table, $where);
			
			$status = 1;
			$data['status'] = $status;
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			print_r($message);
			$data['status'] = $status;
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			print_r($message);
			$data['status'] = $status;
		}
		$this->load->view('remittance/modals/modal_employee_deductions', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_employee_deduction_list($remittance_id=NULL, $employee_id=NULL, $action_id = NULL)
	{
		try
		{
			// di na kailangan
			$params = get_params();
			
			$aColumns = array('C.deduction_name, A.effective_date, A.amount, A.reference_text');
			$bColumns = array('C.deduction_id', 'A.effective_date', 'A.amount', 'ifnull(A.reference_text,\'\')');
			
			$return_list	= $this->remittance->get_employee_deduction_list($aColumns, $bColumns, $remittance_id, $employee_id, $params);
			
			$deduction_list	= $return_list['data'];
			$iFilteredTotal	= $return_list['filtered_length'];		

			// $table = array(
			// 	'main' => array(
			// 		'table' => $this->remittance->tbl_remittance_details,
			// 		'alias' => 'A'
			// 	),
			// 	't2' => array(
			// 		'table'     => $this->remittance->tbl_payout_details,
			// 		'alias'     => 'B',
			// 		'type'      => 'LEFT JOIN',
			// 		'condition' => 'A.payroll_dtl_id = B.payroll_dtl_id'
			// 	),
			// 	't3' => array(
			// 		'table'     => $this->remittance->tbl_payout_header,
			// 		'alias'     => 'C',
			// 		'type'      => 'LEFT JOIN',
			// 		'condition' => 'C.payroll_hdr_id = B.payroll_hdr_id'
			// 	)
			// );
			// $where	= array($this->get_hash_key('remittance_id') => $remittance_id);	
			// $where	= array($this->get_hash_key('employee_id') => $employee_id);	

			// $iTotal	= $this->remittance->get_total_length($table, 'C.employee_id', $where);
			// $iTotal = 0;
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => 0,
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			foreach ($deduction_list as $aRow):
				$row = array();
				$row[]      = $aRow['deduction_name'];
				$row[]      = '<center>' . format_date($aRow['effective_date']) . '</center>';
				if($action_id == ACTION_EDIT) $row[] = '<input class="number m-n" name="amount[]" value="' . $aRow['amount'] . '">';
				else $row[] = '<p class="p-n right">&#8369; ' . number_format($aRow['amount'],2) . '</p>';
				$row[]      = $aRow['reference_text'];
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

	public function process_remittance($is_payment=FALSE)
	{
		try
		{
			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ) or EMPTY ( $params ['module'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			$is_voucher = (!ISSET($params['payroll_type_flag']) AND EMPTY($params['payroll_type_flag'])) ? FALSE : TRUE;
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table              = $this->remittance->tbl_remittances;
			$audit_table[]      = $table;
			$audit_schema[]     = DB_MAIN;
			$audit_module       = $params['module'];

			if(!$is_payment) {
				// SERVER VALIDATION
				$valid_data                     = $this->_validate_data($params);
				/*SET UP YEAR-MONTH */
				if($valid_data['month_select'] < 10)
				{
					$valid_data['year_month'] = $valid_data['year_select'].'0'.$valid_data['month_select'];
				}
				else
				{
					$valid_data['year_month'] = $valid_data['year_select'].$valid_data['month_select'];
				}
				

				//SET FIELDS VALUE
				$fields['remittance_type_id']   = $valid_data['remittance_type_id'];
				$fields['deduction_start_date'] = $valid_data['deduction_start_date'];
				$fields['deduction_end_date']   = $valid_data['deduction_end_date'];
				$fields['`year_month`']         = $valid_data['year_month'];
				$fields['certified_by']         = $valid_data['certified_by'];
				$fields['approved_by']          = $valid_data['approved_by'];
				$fields['remittance_status_id'] = (!EMPTY($params['remittance_status_id']) ? $params['remittance_status_id'] : REMITTANCE_FOR_REMITTANCE);
				$fields['payroll_type_flag']    = (!ISSET($params['payroll_type_flag']) ? PAYOUT_TYPE_FLAG_REGULAR : $params['payroll_type_flag']);
				
				
				if(EMPTY($params['id']) OR $params['remittance_status_id'] == REMITTANCE_FOR_REMITTANCE)
				{
					// INSERT
					$table                          = $this->remittance->tbl_remittances;
					$field                          = array("deduction_start_date, deduction_end_date");
					$where                          = array();
					$where['remittance_type_id']    = $valid_data['remittance_type_id'];
					$where['payroll_type_flag']     = ($is_voucher ? PAYOUT_TYPE_FLAG_VOUCHER : PAYOUT_TYPE_FLAG_REGULAR);
					$where['remittance_status_id']  = array(REMITTANCE_REMITTED, array('!='));
					if(!EMPTY($params['id'])) {
						$key = $this->get_hash_key('remittance_id');
						$where[$key] = array($params['id'],array('!='));
					} 

					$order_by        = array("deduction_end_date" => "desc");
					// SELECT ALL REMITTANCE DATES WITH THE SAME REMITTANCE TYPE
					$remittance_date = $this->remittance->get_payroll_remittance_data($field, $table, $where, TRUE, $order_by);
					

					$exist          = FALSE;
					$new_start_date = format_date($valid_data['deduction_start_date'], 'Y-m-d');
					$new_end_date   = format_date($valid_data['deduction_end_date'],'Y-m-d');
					
					if(!EMPTY($remittance_date)) {
						foreach ($remittance_date AS $key => $date) {
							if($date['deduction_start_date'] <= $new_start_date && $date['deduction_end_date'] >= $new_start_date ) $exist = TRUE;
							if($date['deduction_start_date'] <= $new_end_date   && $date['deduction_end_date'] >= $new_end_date ) $exist = TRUE;
							if($date['deduction_start_date'] >= $new_start_date && $date['deduction_end_date'] <= $new_end_date ) $exist = TRUE;
						}
					}

					if($exist) throw new Exception( $this->lang->line('overlapped_err') );
					
					$payroll_types = array(PAYROLL_TYPE_FLAG_ALL);
					foreach($params['payroll_type'] AS $type) {
						switch ($type) {
							case 1:
							case 2:
								$payroll_types[] = PAYROLL_TYPE_FLAG_REG;
								break;
							case 3:
							case 4:
								$payroll_types[] = PAYROLL_TYPE_FLAG_JO;
								break;
						}
					}
					

					// GET ALL DEDUCTION ID UNDER THE SELECTED REMITTANCE TYPE
					$where = array();
					$where['remittance_type_id'] = $fields['remittance_type_id'];
					if(ISSET($params['payroll_type']))
						$where['employ_type_flag'] = array($payroll_types, array('IN'));
					$deductions = $this->remittance->get_payroll_remittance_data(array('GROUP_CONCAT(deduction_id) deduction_id'), $this->remittance->tbl_param_deductions, $where, FALSE);

					if(EMPTY($deductions))
						throw new Exception ( $this->lang->line ( 'no_deduction' ) );

					// GET ALL PAYROLL DETAIL ID UNDER THE SELECTED REMITTANCE TYPE
					// if(EMPTY($params['id']))
					$payroll_dtls = $this->remittance->get_payroll_details($valid_data['remittance_type_id'], $is_voucher, $params['id']);
					

					// GET ALL DEDUCTIONS WITH EFFECTIVE DATE BETWEEN SELECTED DEDUCTION START DATE AND DEDUCTION END DATE
					$new_start_date = format_date($valid_data['deduction_start_date'],'Y-m-d');
					$new_end_date   = format_date($valid_data['deduction_end_date'],'Y-m-d');
					$where                       = array();
					$where['A.deduction_id']     = array(explode(',', $deductions['deduction_id']), array('IN'));
					
					// EXCLUDE SOME PAYROLL DETAILS WITH THE SAME REMITTANCE TYPE THAT HAS BEEN REMITTED ALREADY
					if(!EMPTY($payroll_dtls))
					$where['A.payroll_dtl_id']   = array(explode(',', $payroll_dtls['payroll_dtl_id']), array('NOT IN'));

					$where['A.effective_date']   = array(array( $new_start_date, $new_end_date ), array('BETWEEN'));
					$where['C.payout_status_id'] = ($is_voucher ? PAYOUT_STATUS_PAID : PAYOUT_STATUS_APPROVED);

					if(!$is_voucher)
					$where['D.payroll_type_id']  = array($params['payroll_type'], array('IN'));
				
					$tables = array(
							'main'	=> array(
								'table'		=> $this->remittance->tbl_payout_details,
								'alias'		=> 'A',
							),
							't2'	=> array(
								'table'		=> $this->remittance->tbl_payout_header,
								'alias'		=> 'B',
								'type'		=> 'JOIN',
								'condition'	=> 'A.payroll_hdr_id = B.payroll_hdr_id',
							),
							't3'	=> array(
								'table'		=> $this->remittance->tbl_payout_summary,
								'alias'		=> 'C',
								'type'		=> 'JOIN',
								'condition'	=> 'B.payroll_summary_id = C.payroll_summary_id AND C.payout_type_flag = "' . ($is_voucher ? PAYOUT_TYPE_FLAG_VOUCHER : PAYOUT_TYPE_FLAG_REGULAR) . '"',
							),
							't4'	=> array(
								'table'		=> $this->remittance->tbl_attendance_period_hdr,
								'alias'		=> 'D',
								'type'		=> 'LEFT JOIN',
								'condition'	=> 'C.attendance_period_hdr_id = D.attendance_period_hdr_id',
							)
						);
					$payroll_details         = $this->remittance->get_payroll_remittance_data(array('A.payroll_dtl_id','A.amount'), $tables, $where);

					// DEDUCTION START DATE AND DEDUCTION END DATE MUST COVER THE EFFECTIVE DATE OF PAYROLL
					if(EMPTY($payroll_details)) {
						throw new Exception( $this->lang->line('no_remittance_to_process') );
					} 

					// POPULATE REMITTANCE DETAILS TO BE INSERTED IN THE REMITTANCE_DETAILS
					$fields['remittance_amount'] = 0;
					$remittance_details          = array();
					foreach($payroll_details AS $key => $val) {
						$fields['remittance_amount'] += $val['amount'];
						$remittance_details[$key]['payroll_dtl_id'] = $val['payroll_dtl_id'];
						$remittance_details[$key]['orig_amount'] 	= $remittance_details[$key]['amount'] = $val['amount'];
					}
					$fields['payroll_type_ids'] = implode('|', $params['payroll_type']);
					if($params['remittance_status_id'] == REMITTANCE_FOR_REMITTANCE AND !EMPTY($params['id'])) {

						$where             = array();
						$key               = $this->get_hash_key('remittance_id');
						$where[$key]       = $params['id'];
						
						$this->remittance->update_payroll_remittance($table, $fields, $where);

						$audit_action[]    = AUDIT_UPDATE;
						
						// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
						$prev_detail[]     = $this->remittance->get_payroll_remittance_data(array("*"), $table, $where);
						
						$remittance_id     = $prev_detail[0][0]['remittance_id'];
						$where['remittance_id'] = $remittance_id;
						$this->remittance->delete_payroll_remittance($this->remittance->tbl_remittance_details, $where);

					} else {

						// GET THE CURRENT USER'S EMPLOYEE ID
						$where                                     = array();
						$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
						$user_info                                 = $this->remittance->get_payroll_remittance_data(array('employee_id'), $this->remittance->tbl_employee_personal_info, $where, FALSE);
						
						$fields['date_processed']                  = date('Y-m-d H:i:s');
						$fields['processed_by']                    = $user_info['employee_id'];
						//SET AUDIT TRAIL DETAILS
						$audit_action[]		= AUDIT_INSERT;
						
						$prev_detail[]		= array();

						//INSERT REMITTANCES DATE
						$remittance_id 		= $this->remittance->insert_payroll_remittance($table, $fields, TRUE);
						
					}
					
					foreach($remittance_details AS $key => $val) {
						$remittance_details[$key]['remittance_id'] = $remittance_id;
					}

					// INSERT REMITTANCE DETAILS WITH THE NEW ADDED REMITTANCE
					$this->remittance->insert_payroll_remittance($this->remittance->tbl_remittance_details, $remittance_details);
					
					$remittance_status_id = (!EMPTY($params['remittance_status_id']) ? $params['remittance_status_id'] : REMITTANCE_FOR_REMITTANCE);
					$user = $this->remittance->get_payroll_remittance_data(array('user_id'),$this->remittance->tbl_associated_accounts, array('employee_id' => $fields['processed_by']), FALSE);
					
					$this->_insert_remittance_notifications($params['module'], $remittance_id, 
						$remittance_status_id, $user['user_id']);
					//MESSAGE ALERT
					$message 			= $this->lang->line('data_saved');
					
					// GET THE DETAIL AFTER INSERTING THE RECORD
					$curr_detail[] 		= array($fields);	
					
					// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
					$activity 			= "%s has been added";


				}
				else
				{
					// UPDATE 
	
					//WHERE 
					$where             = array();
					$key               = $this->get_hash_key('remittance_id');
					$where[$key]       = $params['id'];
					
					$audit_action[]    = AUDIT_UPDATE;
					
					// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
					$prev_detail[]     = $this->remittance->get_payroll_remittance_data(array("*"), $table, $where);
					
					if($prev_detail[0][0]['remittance_status_id'] != REMITTANCE_PROCESSING AND !$this->permission->check_permission($params['module'], ACTION_REMITTED))
					{
						throw new Exception($this->lang->line('invalid_action'));
					}


					if(empty($params['or_date'])) throw new Exception('OR Date is required.');
					if(empty($params['or_no'])) throw new Exception('OR Number is required.');
					$fields = array();
					$fields['or_date']              = (ISSET($params['or_date']) ? format_date($params['or_date'], 'Y-m-d') : null);
					$fields['or_no']                = (ISSET($params['or_no']) ? $params['or_no'] : null);
					$fields['or_date_gs']           = (ISSET($params['or_date_gs']) ? format_date($params['or_date_gs'], 'Y-m-d') : null);
					$fields['or_no_gs']             = (ISSET($params['or_no_gs']) ? $params['or_no_gs'] : null);
					$fields['remittance_status_id'] = REMITTANCE_REMITTED;
					// UPDATE DATA

					$this->remittance->update_payroll_remittance($table, $fields, $where);

					$where                                     = array();
					$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
					$employee_info                             = $this->remittance->get_payroll_remittance_data(array('employee_id'), $this->remittance->tbl_employee_personal_info, $where, FALSE);
					
					// POPULATE DATA FOR REMITTANCE HISTORY
					$valid_data = array();
					$valid_data['remittance_id']               = $prev_detail[0][0]['remittance_id'];
					$valid_data['hist_date']                   = date('Y-m-d H:i:s');
					$valid_data['employee_id']                 = $employee_info['employee_id'];
					$valid_data['remittance_status_id']        = REMITTANCE_REMITTED;
					$valid_data['remarks']					   = 'Successfully remitted.';
					
					// INSERT DATA TO REMITTANCE HISTORY
					$this->remittance->insert_payroll_remittance($this->remittance->tbl_remittance_history, $valid_data);
					// $where                                     = array();
					// $where['remittance_id'] 				   = $prev_detail[0][0]['remittance_id'];
					// $this->remittance->delete_payroll_remittance($this->remittance->tbl_remittance_details, $where);

					// foreach($remittance_details AS $key => $val) {
					// 	$remittance_details[$key]['remittance_id'] = $prev_detail[0][0]['remittance_id'];
					// }

					// INSERT REMITTANCE DETAILS WITH THE NEW ADDED REMITTANCE
					// $this->remittance->insert_payroll_remittance($this->remittance->tbl_remittance_details, $remittance_details);
					
					$user = $this->remittance->get_payroll_remittance_data(array('user_id'), $this->remittance->tbl_associated_accounts, array('employee_id' => $prev_detail[0][0]['processed_by']), FALSE);
			
					$this->_insert_remittance_notifications($params['module'], $prev_detail[0][0]['remittance_id'], $params['remittance_status_id'], $user['user_id']);
				
					//MESSAGE ALERT
					$message 		= $this->lang->line('data_updated');
					
					// GET THE DETAIL AFTER UPDATING THE RECORD
					$curr_detail[]  = array($fields);
					
					// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
					$activity 		= "%s has been updated";
				}
			} else {

				//WHERE 
				$where             = array();
				$key               = $this->get_hash_key('remittance_id');
				$where[$key]       = $params['id'];
				
				$audit_action[]    = AUDIT_UPDATE;

				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]             = $this->remittance->get_payroll_remittance_data(array("payment_date, payment_details"), $table, $where);

				$fields                    = array();
				$fields['payment_date']    = format_date($params['payment_date'], 'Y-m-d');
				$fields['payment_details'] = $params['payment_details'];
				

				//UPDATE DATA
				$this->remittance->update_payroll_remittance($table, $fields, $where);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = array($fields);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
			}

			$activity = sprintf($activity, 'Remittance');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$audit_module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
			
			Main_Model::commit();
			$status = TRUE;
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	public function process_remittance_status()
	{
		try
		{
			$status = 0;
			$params	= get_params();

			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] ) or EMPTY ( $params ['module'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'] . '/' . $params ['module'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}
			
			// SERVER VALIDATION
			$valid_data                     = $this->_validate_status_data($params);

			//SET FIELDS VALUE
			$fields['remittance_status_id']   = $valid_data['remittance_type_id'];
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table              = $this->remittance->tbl_remittances;
			$audit_table[]      = $table;
			$audit_schema[]     = DB_MAIN;
			$audit_module       = $params['module'];

			
			// UPDATE 
			
			$audit_action[]    = AUDIT_UPDATE;

			// GET THE EMPLOYEE ID OF THE CURRENT USER
			$where                                     = array();
			$where[$this->get_hash_key('employee_id')] = $this->session->userdata('user_pds_id');
			$employee_info                             = $this->remittance->get_payroll_remittance_data(array('employee_id'), $this->remittance->tbl_employee_personal_info, $where, FALSE);
			//WHERE 
			$where                                     = array();
			$key                                       = $this->get_hash_key('remittance_id');
			$where[$key]                               = $params['id'];
			$remittance_info                           = $this->remittance->get_payroll_remittance_data(array("*"), $table, $where, FALSE);

			/*if($valid_data['remittance_status_id'] == REMITTANCE_REMITTED && EMPTY($remittance_info['payment_date'])) {
				throw new Exception("Payment should be processed first before changing the status to REMITTED.");
			}*/
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]     = array($remittance_info['remittance_status_id']);
			
			$fields = array();
			$fields['remittance_status_id'] = $valid_data['remittance_status_id']; 
			
			// UPDATE DATA IN REMITTANCES
			$this->remittance->update_payroll_remittance($table, $fields, $where);

			$user = $this->remittance->get_payroll_remittance_data(array('user_id'),$this->remittance->tbl_associated_accounts, array('employee_id' => $remittance_info['processed_by']), FALSE);
			$this->_insert_remittance_notifications($params['module'], $remittance_info['remittance_id'], $params['remittance_status_id'], $user['user_id']);

			// POPULATE DATA FOR REMITTANCE HISTORY
			$valid_data['remittance_id']               = $remittance_info['remittance_id'];
			$valid_data['hist_date']                   = date('Y-m-d H:i:s');
			$valid_data['employee_id']                 = $employee_info['employee_id'];
			
			// INSERT DATA TO REMITTANCE HISTORY
			$this->remittance->insert_payroll_remittance($this->remittance->tbl_remittance_history, $valid_data);
			//MESSAGE ALERT
			$message 		= $this->lang->line('data_updated');
			
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[]  = array($fields);
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 		= "%s has been updated";

			$activity = sprintf($activity, 'Remittance');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity,
				$audit_module,
				$prev_detail,
				$curr_detail,
				$audit_action,
				$audit_table,
				$audit_schema
			);
			
			Main_Model::commit();
			$status = TRUE;
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	private function _validate_data($params)
	{
		$fields                         = array();
		
		$fields['remittance_type_id']   = "Remittance Type";
		$fields['deduction_start_date'] = "Deduction Start Date";
		$fields['deduction_end_date']   = "Deduction End Date";
		$fields['certified_by']         = "Certified By";
		$fields['approved_by']          = "Approved By";
		$fields['year_select']         	= "Year";
		$fields['month_select']         = "Month";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_input ($params);
	}

	private function _validate_input($params) 
	{
		try {
			
			$validation ['remittance_type_id'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Remittance Type',
					'max_len' 				=> 10 
			);

			$validation ['deduction_start_date'] 	= array (
					'data_type' 			=> 'date',
					'name'					=> 'Deduction Start Date',
					'max_len' 				=> 50 
			);

			$validation ['deduction_end_date'] 	= array (
					'data_type' 			=> 'date',
					'name'					=> 'Deduction End Date',
					'max_len' 				=> 50 
			);

			$validation ['certified_by'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Certified By',
					'max_len' 				=> 10 
			);

			$validation ['approved_by'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Approved By',
					'max_len' 				=> 10 
			);
			$validation ['year_select'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Year',
					'max_len' 				=> 4 
			);
			$validation ['month_select'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Month',
					'max_len' 				=> 2 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_status_data($params)
	{
		$fields                         = array();
		
		$fields['remittance_status_id']  = "Remittance Status";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_status_input ($params);
	}

	private function _validate_status_input($params) 
	{
		try {
			
			$validation ['remittance_status_id'] 	= array (
					'data_type' 			=> 'digit',
					'name'					=> 'Remittance Status',
					'max_len' 				=> 10 
			);

			$validation ['remarks'] 	= array (
					'data_type' 			=> 'string',
					'name'					=> 'Remarks',
					'max_len' 				=> 255 
			);

			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	public function modal_remittance_attachment($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data           = array();
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;
			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$resources['load_css'] = array(CSS_DATATABLE, CSS_UPLOAD);
			$resources['load_js']  = array(JS_DATATABLE, JS_UPLOAD);
			$post_data             = array(	
										'remittance_id'        => $id
										);
			$resources['datatable'][]    = array('table_id' => 'table_remittance_attachment', 'path' => 'main/payroll_remittance/get_remittance_attachment_list', 'advanced_filter' => true,'post_data' => json_encode($post_data));
			$resources['load_delete'] 	= array(
						__CLASS__,
						'delete_attachment',
						PROJECT_MAIN
					);
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
		$this->load->view('remittance/modals/modal_remittance_attachment', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_remittance_attachment_list()
	{

		try
		{
			$params          = get_params();
			
			$aColumns        = array("A.upload_id","A.date_uploaded","A.file_name", "CONCAT(B.fname,' ',B.lname) as uploader");
			$bColumns        = array("A.date_uploaded","A.file_name", "CONCAT(B.fname,' ',B.lname)");
			
			$remittance_list = $this->remittance->get_remittance_attachment_list($aColumns, $bColumns, $params);
			
			$iTotal          = $this->remittance->attachment_total_length($params['remittance_id']);
			$iFilteredTotal  = $this->remittance->attachment_filtered_length($aColumns, $bColumns, $params);
			

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);

			$module             = MODULE_PAYROLL_REMITTANCE;
			$permission_delete  = $this->permission->check_permission($module, ACTION_DELETE);
		
						
			foreach ($remittance_list as $aRow):
				
				$row = array();
				
				$id            = $this->hash($aRow['upload_id']);
				
				$salt          = gen_salt();
				$token_delete    = in_salt($id  . '/' . ACTION_DELETE  . '/' . $module, $salt);
				

				$url_delete      =  ACTION_DELETE."/".$id ."/".$token_delete."/".$salt."/".$module;

				$row[]      = '<center>' . format_date($aRow['date_uploaded']) . '</center>';
				$row[]      = $aRow['file_name'];
				$row[]      = $aRow['uploader'];

				$file_url = base_url().PATH_REMITTANCE_ATTACHMENT.$aRow['file_name'];

				$action = "<div class='table-actions'>";
			
				$action .= "<a href='".$file_url."' data-tooltip='Download' class='save tooltipped' target = '_blank' data-position='bottom' data-delay='50'></a>";
				
				$delete_action = 'content_delete("attachement", "'.$url_delete.'")';
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' title='Delete' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";

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

	public function process_remittance_attachment_upload()
	{
		try
		{
			$status = 0;
			$params = get_params();
			$action = $params['action'];
			$id     = $params['id'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			$module = $params['module'];

			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$valid_data   = $this->_validate_attachment_data($params);

			
			Main_Model::beginTransaction();
			
			$where       = array();
			$key         = $this->get_hash_key('remittance_id');
			$where[$key] = $params['id'];
			$remittance  = $this->remittance->get_payroll_remittance_data(array('remittance_id'), $this->remittance->tbl_remittances, $where, FALSE);

			$fields                  = array();
			$fields['remittance_id'] = $remittance['remittance_id'];
			$fields['file_name']     = $valid_data['file_name'];
			$fields['date_uploaded'] = date('Y-m-d');
			$fields['uploaded_by']   = $this->session->userdata('user_id');
			
			$this->remittance->insert_payroll_remittance($this->remittance->tbl_remittance_upload, $fields);
			
			$message 		= $this->lang->line('data_saved');
			
			$audit_table[]  = $this->remittance->tbl_remittance_upload;
			$audit_schema[] = DB_MAIN;
			$audit_module   = $params['module'];
			$prev_detail[]  = array();
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;

			$activity 		= "%s has been added";

			$activity = sprintf($activity, 'Remittance attachment');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$audit_module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
			
			Main_Model::commit();
			$status = TRUE;
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
			RLog::error($message);
		}
		$data['msg'] 	= $message;
		$data['status'] = $status;
		echo json_encode( $data );
	}

	private function _validate_attachment_data($params)
	{
		$fields                         = array();
		
		$fields['file_name']  = "File";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_attachment_input ($params);
	}

	private function _validate_attachment_input($params) 
	{
		try {
			

			$validation ['file_name'] 	= array (
					'data_type' => 'string',
					'name'      => 'File name',
					'max_len'   => 100 
			);

			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
	public function delete_attachment()
	{
		try
		{
			$flag 			= 0;
			$table_id 		= "";
			$data_path 		= "";
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
			
			//GET PREVIOUS DATA
			$prev_data				= array() ;
			/*GET PREVIOUS DATA*/
			$field 						= array("*") ;
			$table						= $this->remittance->tbl_remittance_upload;
			$where						= array();
			$key 						= $this->get_hash_key('upload_id');
			$where[$key]				= $id;
			$remittance_data 			= $this->remittance->get_payroll_remittance_data($field, $table, $where, FALSE);

			$remittance_id = $this->hash($remittance_data['remittance_id']);

			//DELETE DATA
			$where					= array();
			$key 					= $this->get_hash_key('upload_id');
			$where[$key]			= $id;
			$table 					= $this->remittance->tbl_remittance_upload;
			
			$this->remittance->delete_payroll_remittance($table,$where);
			
			$audit_table[]				= $this->remittance->tbl_remittance_upload;
			$audit_schema[]				= DB_MAIN;
			$prev_detail[] 				= array($remittance_data);
			$curr_detail[]				= array();
			$audit_action[] 			= AUDIT_DELETE;
			$activity 					= "%s has been deleted.";
			$audit_activity 		= sprintf($activity, 'Remittance attachment '.$remittance_data["file_name"]);

			
			
			

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			

			$filePath = PATH_REMITTANCE_ATTACHMENT.$remittance_data['file_name'];
			if (file_exists($filePath))
				unlink($filePath);

			Main_Model::commit();
			$msg 					= $this->lang->line('data_deleted');
			$flag 					= 1;
		}
		
		catch(Exception $e)
		{
			$msg = $e->getMessage();
			RLog::error($msg);
			Main_Model::rollback();
		}
		$table_id = "table_remittance_attachment";
		$data_path = PROJECT_MAIN . '/payroll_remittance/get_remittance_attachment_list/';
		$post_data 					= array(	
												'remittance_id' => $remittance_id
										);

		$response 					= array(
			"flag"            => $flag,
			"msg"             => $msg,
			"reload"          => 'datatable',
			"table_id"        => $table_id,
			"path"            => $data_path,
			"advanced_filter" => true,
			'post_data'       => json_encode($post_data)
			);
		echo json_encode($response);
	}

	public function get_total_amount()
	{
		try
		{
			$params = get_params();
			
			$output = array();
			$output['status'] = FALSE;

			$fields = array('SUM(A.amount) AS total_amount');
			$tables = array(
				'main' => array(
					'table' => $this->remittance->tbl_payout_details,
					'alias' => 'A'
				),
				't1' => array(
					'table' => $this->remittance->tbl_remittance_details,
					'alias' => 'B',
					'type' => 'LEFT JOIN',
					'condition' => 'A.payroll_dtl_id=B.payroll_dtl_id'
				),
				't2' => array(
					'table' => $this->remittance->tbl_payout_header,
					'alias' => 'C',
					'type' => 'LEFT JOIN',
					'condition' => 'A.payroll_hdr_id=C.payroll_hdr_id'
				)
			);


			$id = $this->get_hash_key('B.remittance_id');
			$where[$id] = $params['remittance_id'];
			$where['A.include_flag'] = YES;

			if(!EMPTY($params['office_id'])) {
				$office_list = '';
				$office_list = $this->remittance->get_office_child($office_list, $params['office_id']);
				$where['C.office_id'] = array($office_list,array('IN'));
			}


			$total_amount = $this->remittance->get_payroll_remittance_data($fields, $tables, $where, FALSE);
			$output['total_amount'] = number_format($total_amount['total_amount'],2);
			$output['status'] = TRUE;
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

	public function save_payout_details()
	{
		RLog::info('S: save_payout_details ...');
		
		$params = get_params();
		$msg 	= $this->lang->line('data_not_saved');
		$status = FALSE;
		
		try
		{
			if ($params['token'] != in_salt ($params['remittance_id']  . '/' . $params['employee_id']  . '/' . $params['action']  . '/' . $params['module'], $params['salt']) )
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
			
			Main_Model::beginTransaction();		

			// update existing records
			$amounts        = $params['amounts'];
			$orig_amounts   = $params['orig_amounts'];
			$remarks        = $params['remarks'];
			$payout_dtl_ids = $params['payout_dtl_ids'];
			
			$remittance_amount = 0.00;
					
			
			if (!EMPTY($payout_dtl_ids))
			{
				$table = $this->remittance->tbl_remittance_details;
				for ($i=0; $i<count($payout_dtl_ids); $i++)
				{
					$fields                 = array();
					
					$fields['amount']       = $amounts[$i];
					$fields['orig_amount']  = ($orig_amounts[$i] > 0 ? $orig_amounts[$i] : $amounts[$i]);
					$fields['less_amount']  = $fields['orig_amount'] - $fields['amount'];
					$fields['remarks'] 		= strtoupper($remarks[$i]);
					$total_deductions       += $fields['amount'];
					
					$where['payroll_dtl_id']     = $payout_dtl_ids[$i];
					$this->remittance->update_payroll_remittance($table, $fields, $where);
					
				}
			}
			
			$action = $params['action'];
			$id     = $params['remittance_id'];
			$id2    = $params['employee_id'];
			$salt   = $params['salt'];
			$token  = $params['token'];
			$module = $params['module'];

			$remittance_details          = $this->remittance->get_updated_remittance_amount($id);
			$table                       = $this->remittance->tbl_remittances;
			$fields                      = array();
			$where                       = array();
			$fields['remittance_amount'] = $remittance_details['amount'];
			$key                         = $this->get_hash_key('remittance_id');
			$where[$key]                 = $id;

			$this->remittance->update_payroll_remittance($table, $fields, $where);

			Main_Model::commit();
			
			$msg 	= $this->lang->line('data_saved');
			$status = TRUE;

		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			$msg 	= $this->lang->line($message);
			$status = FALSE;			
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			
			$msg 	= $message;
			$status = FALSE;
			
		}
		
		$info['msg']		= $msg;
		$info['status']		= $status;
		echo json_encode($info);
		
		RLog::info('E: save_payout_details ...');
	}

	private function _insert_remittance_notifications($module_id, $remittance_id, $remittance_status_id, $processed_by)
	{
		try
		{

			$params	= $this->remittance->get_notification_params($module_id, $remittance_status_id);
			
			$this->load->model('notifications_model', 'notification');

			$notif_params				= array();
			$notif_params['module_id']	= $module_id;
			
			$notif_msg		= '';
			if ($params['remitted_flag'] == 1)
			{
			 	$notif_msg = $this->lang->line('remittance_remit');
			 	$notif_params['notify_users'] = $processed_by;
			}
		 	else
		 	{
				$notif_msg = $this->lang->line('remittance_process');
				$notif_params['notify_roles']	= $params['notify_roles'];
				$notif_params['notify_orgs']	= $params['notify_orgs'];
				$notif_params['notified_by']	= $processed_by;
		 	}
		 	$title = 'Remittance';

		 	$notif_params['notification'] 	= $notif_msg;
		 	$notif_params['title']			= $title;
		 	
		 	// construct record_link
			$salt			= gen_salt();
			$id 			= $this->hash($remittance_id);
			$token_process 	= in_salt($id  . '/' . ACTION_PROCESS  . '/' . $module_id, $salt);
			
			$url_process 	= base_url()."main/payroll_remittance/display_remittance_process/".ACTION_PROCESS."/".$id ."/".$salt."/".$token_process."/".$module_id;

			$notif_params['record_link']	= $url_process;

			$this->notification->insert_notification($notif_params);
		}
		catch(Exception $e)
		{
			throw($e);
		}
	}
}


/* End of file Pds.php */
/* Location: ./application/modules/main/controllers/Pds.php */