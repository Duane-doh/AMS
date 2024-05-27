<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_voucher extends Main_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('payroll_voucher_model', 'voucher');
		$this->load->model('payroll_model', 'payroll');
		$this->load->model('payroll_process_model', 'payroll_process');
		$this->load->model('common_model', 'common');
	}
	
	public function index()
	{
		try
		{
		$data      = array();
		$resources = array();
		
		$resources['load_css']    = array(CSS_DATATABLE, CSS_SELECTIZE);
		$resources['load_js']     = array(JS_DATATABLE, JS_SELECTIZE, JS_NUMBER);
		$resources['datatable'][] = array('table_id' => 'payroll_list', 'path' => 'main/payroll_voucher/get_payroll_list', 'advanced_filter' => true);
		
		$resources['load_modal']  = array(
			'modal_voucher'		=> array(
					'controller'	=> __CLASS__,
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal_voucher',
					'multiple'		=> true,
					'height'		=> '450px',
					'size'			=> 'md',
					'title'			=> 'Prepare Voucher'
			),
			'modal_payment_date'		=> array(
					'controller'	=> __CLASS__,
					'module'		=> PROJECT_MAIN,
					'method'		=> 'modal_payment_date',
					'multiple'		=> true,
					'height'		=> '230px',
					'size'			=> 'sm',
					'title'			=> 'Voucher Payment Date'
			)
		);
		$fields = array('A.office_id','B.name AS office_name');
		$tables = array(
			'main' => array(
				'table' => $this->voucher->tbl_param_offices,
				'alias' => 'A'
			),
			't1'   => array(
				'table' => $this->voucher->db_core . '.' . $this->voucher->tbl_organizations,
				'alias' => 'B',
				'type'  => 'JOIN',
				'condition' => 'A.org_code = B.org_code'
			)
		);
		$where = array('A.active_flag' => 'Y');
		$data['office_list'] = $this->voucher->get_general_data($fields, $tables, $where);
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "Payroll"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll_voucher";
		$key					= "Employee Voucher"; 
		$breadcrumbs[$key]		= PROJECT_MAIN."/payroll_voucher";
		set_breadcrumbs($breadcrumbs, TRUE);

		$this->template->load('voucher/voucher', $data, $resources);

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

	public function get_payroll_list()
	{

		try
		{
			$params   = get_params();
			
			$aColumns = array("A.voucher_id","A.voucher_status_id","A.payroll_summary_id","D.employee_name", "A.voucher_description", "DATE_FORMAT(B.process_start_date,'%M %d, %Y') as process_date", "D.net_pay", "C.payout_status_name");
			$bColumns = array("D.employee_name", "A.voucher_description", "DATE_FORMAT(B.process_start_date,'%M %d, %Y')", "C.payout_status_name");
			
			$vouchers       = $this->voucher->get_payroll_voucher_list($aColumns, $bColumns, $params);
			$iTotal         = $this->voucher->voucher_total_length();
			$iFilteredTotal = $this->voucher->voucher_filtered_length($aColumns, $bColumns, $params);

			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module = MODULE_PAYROLL_VOUCHER;
			foreach ($vouchers as $aRow):
				$cnt++;
				$row    = array();
				$action = "<div class='table-actions'>";
				
				$id                 = $this->hash($aRow["voucher_id"]);
				$payroll_summary_id = $this->hash($aRow["payroll_summary_id"]);
				$salt               = gen_salt();
				$token_view         = in_salt($id  . '/' . ACTION_VIEW  . '/' . $module, $salt);
				$token_edit         = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module, $salt);
				$token_process      = in_salt($payroll_summary_id . '/' . ACTION_PROCESS  . '/' . $module, $salt);
				
				$url_view      = ACTION_VIEW."/".$id ."/".$token_view."/".$salt."/".$module;
				$url_edit      = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module;
				$url_process   = ACTION_PROCESS."/".$payroll_summary_id ."/".$token_process."/".$salt."/".$module;
				$delete_action = 'content_delete("voucher","'.$id.'")';
							
				
				$row[] =  $aRow['employee_name'];
				$row[] =  $aRow['voucher_description'];
				$row[] =  '<p class="m-n right">' . number_format($aRow['net_pay'],2) . '</p>';
				$row[] =  $aRow['process_date'];
				$row[] =  $aRow['payout_status_name'];

				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View' data-position='bottom' data-modal='modal_voucher' onclick=\"modal_voucher_init('".$url_view ."')\" data-delay='50'></a>";
				$edit_permission = $this->permission->check_permission($module, ACTION_EDIT);
				if($edit_permission AND $aRow['voucher_status_id'] == PAYOUT_STATUS_FOR_PROCESSING)
				$action .= "<a href='#' class='edit tooltipped md-trigger' data-tooltip='Edit' data-position='bottom' data-modal='modal_voucher' onclick=\"modal_voucher_init('".$url_edit."')\" data-delay='50'></a>";
				$payment_permission = $this->permission->check_permission($module, ACTION_PAYMENT);
				if($payment_permission AND ($aRow["voucher_status_id"] == PAYOUT_STATUS_APPROVED OR $aRow["voucher_status_id"] == PAYOUT_STATUS_PAID))
				$action .= "<a href='#' class='activity tooltipped md-trigger' data-tooltip='Update Payment Date' data-position='bottom' data-modal='modal_payment_date' onclick=\"modal_payment_date_init('".$url_edit."')\" data-delay='50'></a>";
				
				//$action .= "<a href='javascript:;' onclick='' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				$process_permission = $this->permission->check_permission($module, ACTION_PROCESS);
				if($process_permission)
				$action .= "<a href='javascript:;' class='process tooltipped'  data-tooltip='Process' data-position='bottom' data-delay='50' onclick=\"content_form('voucher_process/display_payroll_voucher_process/".$url_process."', '".PROJECT_MAIN."')\"></a>"; 
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

	
	public function modal_voucher($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$office_list = $this->session->userdata('user_offices');

			$data 					= array();
			$resources['load_css']	= array(CSS_DATETIMEPICKER,CSS_SELECTIZE);
			$resources['load_js'] 	= array(JS_DATETIMEPICKER,JS_SELECTIZE,JS_EDITOR);

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

			$field                = array("*") ;
			$table                = $this->voucher->tbl_param_banks;
			$where                = array();
			$where['active_flag'] = YES;
			$data['banks']        = $this->voucher->get_general_data($field, $table, $where, TRUE);

			// $field             = array("A.*","CONCAT(A.agency_employee_id, ' - ', A.first_name,' ',A.last_name) as employee_name", "C.identification_value AS bank_account");
			// ====================== jendaigo : start : change name format ============= //
			$field             = array("A.*","CONCAT(A.agency_employee_id, ' - ', A.last_name, ', ', A.first_name, IF(A.ext_name='', '', CONCAT(' ', A.ext_name)), IF((A.middle_name='NA' OR A.middle_name='N/A' OR A.middle_name='-' OR A.middle_name='/'), '', CONCAT(' ', LEFT(A.middle_name, 1), '. '))) as employee_name", "C.identification_value AS bank_account");
			// ====================== jendaigo : end : change name format ============= //
			
			// $table             = $this->voucher->tbl_employee_personal_info;
			$table = array(
				'main' => array(
					'table' 	=> $this->voucher->tbl_employee_personal_info,
					'alias'		=> 'A'
				),
				't1' => array(
					'table' 	=> $this->voucher->tbl_employee_work_experiences,
					'alias' 	=> 'B',
					'type' 		=> 'JOIN',
					'condition' => 'A.employee_id=B.employee_id AND B.active_flag = \'' . YES . '\'' 
				),
				't2'    => array(
					'table'		=> $this->voucher->tbl_employee_identifications,
					'alias'		=> 'C',
					'type'		=> 'LEFT JOIN',
					'condition' => 'A.employee_id=C.employee_id AND C.identification_type_id = ' . BANKACCT_TYPE_ID
				)
			);
			$where = array();
			$where['B.employ_office_id'] = array(explode(',', $office_list[$module]), array('IN'));
			$data['employees'] = $this->voucher->get_general_data($field, $table, $where, TRUE);
			
			$table = $this->voucher->tbl_param_compensations;
			$where = array();
			$where['inherit_parent_id_flag'] = NO;
			$not_parent_comp                 = $this->voucher->get_general_data('GROUP_CONCAT(DISTINCT parent_compensation_id) AS parent_compensation_id', $table, $where, FALSE);

			$field                           = array("*","CONCAT(compensation_code,' - ',compensation_name) as compensation_detail") ;
			$where                           = array();
			$where['active_flag']            = YES;
			$where['inherit_parent_id_flag'] = array(array(NO, NA), array('IN'));
			$where['compensation_id']        = array(explode(',', $not_parent_comp['parent_compensation_id']), array('NOT IN'));
			$data['compensations']           = $this->voucher->get_general_data($field, $table, $where, TRUE);
		

			$field                = array("*","CONCAT(deduction_code,' - ',deduction_name) as deduction_detail") ;
			$table                = $this->voucher->tbl_param_deductions;
			$where                = array();
			$where['active_flag'] = YES;
			$where['employee_flag']= YES;
			$data['deductions']   = $this->voucher->get_general_data($field, $table, $where, TRUE);

			// $field                = array("B.*","CONCAT(B.first_name,' ',B.last_name) as employee_name") ;
			// ====================== jendaigo : start : change name format ============= //
			$field                = array("B.*","CONCAT(B.first_name, IF((B.middle_name='NA' OR B.middle_name='N/A' OR B.middle_name='-' OR B.middle_name='/'), '', CONCAT(' ', LEFT(B.middle_name, 1), '. ')), B.last_name, IF(B.ext_name='', '', CONCAT(' ', B.ext_name))) as employee_name") ;
			// ====================== jendaigo : end : change name format ============= //
			
			$tables = array(
				'main'	=> array(
					'table'		=> $this->voucher->db_core.".".$this->voucher->tbl_sys_param,
					'alias'		=> 'A',
				),
				't2'	=> array(
					'table'		=> $this->voucher->tbl_employee_personal_info,
					'alias'		=> 'B',
					'type'		=> 'join',
					'condition'	=> 'A.sys_param_value = B.agency_employee_id',
				)
			);
			$where                   = array();
			$where['sys_param_type'] = PARAM_PAYROLL_CERTIFIED_BY;
			$data['certified_by']    = $this->voucher->get_general_data($field, $tables, $where, TRUE);

			
			$where                   = array();
			$where['sys_param_type'] = PARAM_PAYROLL_CA_CERTIFIED_BY;
			$data['ca_certified_by']    = $this->voucher->get_general_data($field, $tables, $where, TRUE);	

			$where                   = array();
			$where['sys_param_type'] = PARAM_PAYROLL_APPROVED_BY;
			$data['approved_by']    = $this->voucher->get_general_data($field, $tables, $where, TRUE);

			if($action != ACTION_ADD)
			{
				$field                = array("*","DATE_FORMAT(A.voucher_date,'%Y/%m/%d') as voucher_date") ;
				$tables = array(
					'main'	=> array(
						'table'		=> $this->voucher->tbl_vouchers,
						'alias'		=> 'A',
					),
					't2'	=> array(
						'table'		=> $this->voucher->tbl_payout_summary,
						'alias'		=> 'B',
						'type'		=> 'join',
						'condition'	=> 'A.payroll_summary_id = B.payroll_summary_id',
					),
					't3'	=> array(
						'table'		=> $this->voucher->tbl_payout_header,
						'alias'		=> 'C',
						'type'		=> 'join',
						'condition'	=> 'B.payroll_summary_id = C.payroll_summary_id',
					),
					't4'	=> array(
						'table'		=> $this->voucher->tbl_payout_details,
						'alias'		=> 'D',
						'type'		=> 'join',
						'condition'	=> 'C.payroll_hdr_id = D.payroll_hdr_id',
					)
				);
				$where        = array();
				$key          = $this->get_hash_key('A.voucher_id');
				$where[$key]  = $id;
				$voucher_info = $this->voucher->get_general_data($field, $tables, $where, FALSE);

				$resources['single'] = array(
						'employee'     => $voucher_info['employee_id'],
						'certified'    => $voucher_info['certified_by'],
						'ca_certified' => $voucher_info['certified_cash_by'],
						'approved'     => $voucher_info['approved_by']
				);
				$data['voucher_info'] = $voucher_info;


				$field                     = array("*") ;
				$table                     = $this->voucher->tbl_payout_details;
				$where                     = array();
				$where['payroll_hdr_id']   = $voucher_info['payroll_hdr_id'];
				$where['compensation_id']  = "IS NOT NULL";
				$data['compensation_list'] = $this->voucher->get_general_data($field, $table, $where, TRUE);
				
				$field                   = array("*") ;
				$table                   = $this->voucher->tbl_payout_details;
				$where                   = array();
				$where['payroll_hdr_id'] = $voucher_info['payroll_hdr_id'];
				$where['deduction_id']   = "IS NOT NULL";
				$data['deduction_list']  = $this->voucher->get_general_data($field, $table, $where, TRUE);
			}
			$this->load_resources->get_resource($resources);
			$this->load->view('voucher/modals/modal_voucher', $data);

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

	public function modal_payment_date($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data                  = array();
			$resources['load_js']  = array(JS_DATETIMEPICKER);
			$resources['load_css'] = array(CSS_DATETIMEPICKER);

			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;

			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$field                = array("*","DATE_FORMAT(payment_date,'%Y/%m/%d') as payment_date") ;
			$tables               = $this->voucher->tbl_vouchers;
			$where                = array();
			$key                  = $this->get_hash_key('voucher_id');
			$where[$key]          = $id;
			$data['voucher_info'] = $this->voucher->get_general_data($field, $tables, $where, FALSE);

			$this->load->view('voucher/modals/modal_payment_date', $data);
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
	public function process_voucher()
	{
		try
		{			
			$status 		= FALSE;
			$message		= "";
			$reload_url 	= "";

			$params			= get_params();

			$action = $params['action'];
			$token  = $params['token'];
			$salt   = $params['salt'];
			$id     = $params['id'];
			$module = $params['module'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_voucher($params);

			$check_total_income          = isset($params["compensation_amount"]) ? array_sum($params["compensation_amount"]) : 0;
			$check_total_deductions      = isset($params["deduction_amount"]) ? array_sum($params["deduction_amount"]) : 0;
			if($check_total_income == 0 AND $check_total_deductions == 0)
			{
				throw new Exception('Creating a voucher should includes at least one type from Compensations or Deductions.');
			}
			
			Main_Model::beginTransaction();		
			$field       = array("*") ;
			$table       = $this->voucher->tbl_employee_personal_info;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $this->session->userdata("user_pds_id");
			$pds_info    = $this->voucher->get_general_data($field, $table, $where, false);	

			if($action == ACTION_ADD)
			{	
				$fields                       = array() ;
				$fields['payout_type_flag']   = PAYOUT_TYPE_FLAG_VOUCHER;
				$fields['process_start_date'] = date('Y-m-d H:i:s');
				$fields['processed_by']       = $pds_info['employee_id'];
				$fields['certified_by']       = $valid_data["certified"];
				$fields['approved_by']        = $valid_data["approved"];
				$fields['certified_cash_by']  = $valid_data["ca_certified"];
				$fields['payout_status_id']   = PAYOUT_STATUS_FOR_PROCESSING;

				$table              = $this->voucher->tbl_payout_summary;
				$payroll_summary_id = $this->voucher->insert_general_data($table,$fields,TRUE);

				$audit_table[]  = $this->voucher->tbl_payout_summary;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	

				$fields                        = array() ;
				$fields['payroll_summary_id']  = $payroll_summary_id;
				$fields['voucher_description'] = strtoupper($valid_data["voucher_description"]);
				$fields['voucher_date']        = $valid_data["voucher_date"];
				$fields['bank_account']        = $valid_data["bank"];
				$fields['voucher_status_id']   = VOUCHER_STATUS_FOR_PROCESSING;
				$fields['voucher_footer']      = strtoupper($params["voucher_footer"]);
				
				$table      = $this->voucher->tbl_vouchers;
				$voucher_id = $this->voucher->insert_general_data($table,$fields,TRUE);
				
				$user = $this->voucher->get_general_data(array('user_id'),$this->voucher->tbl_associated_accounts, array('employee_id' => $pds_info['employee_id']), FALSE);

				$this->_insert_payout_notifications($params ['module'], $payroll_summary_id, 
						VOUCHER_STATUS_FOR_PROCESSING, $user['user_id']);

				$audit_table[]  = $this->voucher->tbl_vouchers;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	

				$employee_id = $this->hash($valid_data['employee']);
				$employee_info  = $this->voucher->get_employee_personal_info($employee_id);

				$fields                          = array() ;
				$fields['payroll_summary_id']    = $payroll_summary_id;
				$fields['employee_id']           = $employee_info["employee_id"];
				$fields['employee_name']         = $employee_info["employee_name"];
				$fields['plantilla_item_number'] = $employee_info["employ_plantilla_id"];
				$fields['office_id']             = $employee_info["office_id"];
				$fields['office_name']           = $employee_info["office_name"];
				$fields['position_name']         = $employee_info["position_name"];
				$fields['salary_grade']          = $employee_info["salary_grade"];
				$fields['pay_step']              = $employee_info["salary_step"];
				$fields['total_income']          = isset($params["compensation_amount"]) ? array_sum($params["compensation_amount"]) : 0;
				$fields['total_deductions']      = isset($params["deduction_amount"]) ? array_sum($params["deduction_amount"]) : 0;
				$fields['net_pay']               = $fields['total_income'] - $fields['total_deductions'];

				$table          = $this->voucher->tbl_payout_header;
				$payroll_hdr_id = $this->voucher->insert_general_data($table,$fields,TRUE);

				$audit_table[]  = $this->voucher->tbl_payout_header;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	
				
				if($params['compensation'])
				{
					foreach ($params['compensation'] as $key => $compensation) {
						if($params['compensation_amount'][$key])
						{
							$field                    = array("*") ;
							$table                    = $this->voucher->tbl_param_compensations;
							$where                    = array();
							$where['compensation_id'] = $compensation;
							$comp_info                = $this->voucher->get_general_data($field, $table, $where, false);	

							$fields                        = array() ;
							$fields['payroll_hdr_id']      = $payroll_hdr_id;
							$fields['compensation_id']     = ($comp_info['inherit_parent_id_flag'] == YES) ?  $comp_info['parent_compensation_id'] : $compensation;
							$fields['raw_compensation_id'] = $compensation;
							
							$fields['amount']              = $params['compensation_amount'][$key];
							$fields['orig_amount']         = $params['compensation_amount'][$key];
							
							$table = $this->voucher->tbl_payout_details;
							$this->voucher->insert_general_data($table,$fields,FALSE);

							$audit_table[]  = $this->voucher->tbl_payout_details;
							$audit_schema[] = DB_MAIN;
							$prev_detail[]  = array();
							$curr_detail[]  = array($fields);
							$audit_action[] = AUDIT_INSERT;
						}
							
					}
					
				}
				if($params['deduction'])
				{
					foreach ($params['deduction'] as $key => $deduction) {
						if($params['deduction_amount'][$key])
						{

							$field                    = array("*") ;
							$table                    = $this->voucher->tbl_param_deductions;
							$where                    = array();
							$where['deduction_id'] = $deduction;
							$ded_info                = $this->voucher->get_general_data($field, $table, $where, false);	

							$fields                        = array() ;
							$fields['payroll_hdr_id']      = $payroll_hdr_id;
							$fields['deduction_id']     = ($ded_info['inherit_parent_id_flag'] == YES) ?  $ded_info['parent_deduction_id'] : $deduction;
							$fields['raw_deduction_id'] = $deduction;
							
							$fields['amount']              = $params['deduction_amount'][$key];
							$fields['orig_amount']         = $params['deduction_amount'][$key];

							$table = $this->voucher->tbl_payout_details;
							$this->voucher->insert_general_data($table,$fields,FALSE);

							$audit_table[]  = $this->voucher->tbl_payout_details;
							$audit_schema[] = DB_MAIN;
							$prev_detail[]  = array();
							$curr_detail[]  = array($fields);
							$audit_action[] = AUDIT_INSERT;
							
						}
							
					}
					
				}
				$fields                      = array() ;
				$fields['payout_summary_id'] = $payroll_summary_id;
				$fields['payout_date_num']   = 1;
				$fields['effective_date']    = $valid_data["voucher_date"];
				
				$table      = $this->voucher->tbl_payout_summary_dates;
				$this->voucher->insert_general_data($table,$fields,FALSE);

				$audit_table[]  = $this->voucher->tbl_payout_summary_dates;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	


				$fields                      = array() ;
				$fields['payout_summary_id'] = $payroll_summary_id;
				$fields['employee_id']       = $pds_info['employee_id'];
				$fields['action_id']         = ACTION_ADD;
				$fields['payout_status_id']  = PAYOUT_STATUS_FOR_PROCESSING;
				$fields['hist_date']         = date('Y-m-d H:i:s');
				
				$table      = $this->voucher->tbl_payout_history;
				$this->voucher->insert_general_data($table,$fields,FALSE);

				$audit_table[]  = $this->voucher->tbl_payout_history;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	

				$audit_activity       = "New Employee Voucher has been added.";

				$status = true;
				$message = $this->lang->line('data_saved');


			}
			else
			{
				/*GET PREVIOUS DATA*/
				$field       = array("*") ;
				$table       = $this->voucher->tbl_vouchers;
				$where        = array();
				$key          = $this->get_hash_key('voucher_id');
				$where[$key]  = $id;
				$voucher_info = $this->voucher->get_general_data($field, $table, $where, FALSE);

				$payroll_summary_id = $voucher_info['payroll_summary_id'];


				$field                       = array("*") ;
				$table                       = $this->voucher->tbl_payout_summary;
				$where                       = array();
				$where['payroll_summary_id'] = $payroll_summary_id;
				$prev_payroll_summary        = $this->voucher->get_general_data($field, $table, $where, FALSE);
				$payout_status_id            = $prev_payroll_summary['payout_status_id'];

				$fields                       = array() ;
				$fields['payout_type_flag']   = PAYOUT_TYPE_FLAG_VOUCHER;
				$fields['certified_by']       = $valid_data["certified"];
				$fields['approved_by']        = $valid_data["approved"];
				$fields['certified_cash_by']  = $valid_data["ca_certified"];

				$where                       = array();
				$where['payroll_summary_id'] = $payroll_summary_id;
				$table                       = $this->voucher->tbl_payout_summary;
				$this->voucher->update_general_data($table,$fields,$where);

				$audit_table[]  = $this->voucher->tbl_payout_summary;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($prev_payroll_summary);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	

				$fields                        = array() ;
				$fields['voucher_description'] = strtoupper($valid_data["voucher_description"]);
				$fields['voucher_date']        = $valid_data["voucher_date"];
				$fields['bank_account']        = $valid_data["bank"];
				$fields['voucher_footer']      = strtoupper($params["voucher_footer"]);

				$table       = $this->voucher->tbl_vouchers;
				$where        = array();
				$key          = $this->get_hash_key('voucher_id');
				$where[$key]  = $id;
				$this->voucher->update_general_data($table,$fields,$where);

				$audit_table[]  = $this->voucher->tbl_vouchers;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($voucher_info);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;	

				$field                       = array("*") ;
				$table                       = $this->voucher->tbl_payout_header;
				$where                       = array();
				$where['payroll_summary_id'] = $payroll_summary_id;
				$prev_payout_header          = $this->voucher->get_general_data($field, $table, $where, FALSE);
				
				$payroll_hdr_id              = $prev_payout_header['payroll_hdr_id'];
				
				$employee_id                 = $this->hash($valid_data['employee']);
				$employee_info               = $this->voucher->get_employee_personal_info($employee_id);
				
				$fields                          = array() ;
				$fields['payroll_summary_id']    = $payroll_summary_id;
				$fields['employee_id']           = $employee_info["employee_id"];
				$fields['employee_name']         = $employee_info["employee_name"];
				$fields['plantilla_item_number'] = $employee_info["employ_plantilla_id"];
				$fields['office_id']             = $employee_info["office_id"];
				$fields['office_name']           = $employee_info["office_name"];
				$fields['position_name']         = $employee_info["position_name"];
				$fields['salary_grade']          = $employee_info["salary_grade"];
				$fields['pay_step']              = $employee_info["salary_step"];
				$fields['total_income']          = isset($params["compensation_amount"]) ? array_sum($params["compensation_amount"]) : 0;
				$fields['total_deductions']      = isset($params["deduction_amount"]) ? array_sum($params["deduction_amount"]) : 0;
				$fields['net_pay']               = $fields['total_income'] - $fields['total_deductions'];

				$where                       = array();
				$where['payroll_summary_id'] = $payroll_summary_id;
				$table                       = $this->voucher->tbl_payout_header;
				$this->voucher->update_general_data($table,$fields,$where);

				$audit_table[]  = $this->voucher->tbl_payout_header;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($prev_payout_header);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				$where                   = array();
				$where['payroll_hdr_id'] = $payroll_hdr_id;
				$table                   = $this->voucher->tbl_payout_details;
				$this->voucher->delete_general_data($table,$where);

				if($params['compensation'])
				{
					foreach ($params['compensation'] as $key => $compensation) {
						if($params['compensation_amount'][$key])
						{
							$fields                    = array() ;
							$fields['payroll_hdr_id']  = $payroll_hdr_id;
							$fields['compensation_id'] = $compensation;
							// $fields['effective_date']  = $valid_data["voucher_date"];
							$fields['amount']          = $params['compensation_amount'][$key];

							$table = $this->voucher->tbl_payout_details;
							$this->voucher->insert_general_data($table,$fields,FALSE);

							$audit_table[]  = $this->voucher->tbl_payout_details;
							$audit_schema[] = DB_MAIN;
							$prev_detail[]  = array();
							$curr_detail[]  = array($fields);
							$audit_action[] = AUDIT_INSERT;
						}
							
					}
					
				}
				if($params['deduction'])
				{
					foreach ($params['deduction'] as $key => $deduction) {
						if($params['deduction_amount'][$key])
						{
							$fields                   = array() ;
							$fields['payroll_hdr_id'] = $payroll_hdr_id;
							$fields['deduction_id']   = $deduction;
							// $fields['effective_date'] = $valid_data["voucher_date"];
							$fields['amount']         = $params['deduction_amount'][$key];

							$table = $this->voucher->tbl_payout_details;
							$this->voucher->insert_general_data($table,$fields,FALSE);

							$audit_table[]  = $this->voucher->tbl_payout_details;
							$audit_schema[] = DB_MAIN;
							$prev_detail[]  = array();
							$curr_detail[]  = array($fields);
							$audit_action[] = AUDIT_INSERT;
							
						}
							
					}
					
				}
				$field                      = array("*") ;
				$table                      = $this->voucher->tbl_payout_summary_dates;
				$where                      = array();
				$where['payout_summary_id'] = $payroll_summary_id;
				$prev_summary_dates         = $this->voucher->get_general_data($field, $table, $where, FALSE);
				
				$fields                     = array() ;
				$fields['effective_date']   = $valid_data["voucher_date"];
				
				$where                      = array();
				$where['payout_summary_id'] = $payroll_summary_id;
				$table                      = $this->voucher->tbl_payout_summary_dates;
				$this->voucher->update_general_data($table,$fields,$where);

				$audit_table[]  = $this->voucher->tbl_payout_summary_dates;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array($prev_summary_dates);
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_UPDATE;

				$fields                      = array() ;
				$fields['payout_summary_id'] = $payroll_summary_id;
				$fields['employee_id']       = $pds_info['employee_id'];
				$fields['action_id']         = ACTION_EDIT;
				$fields['payout_status_id']  = $payout_status_id;
				$fields['hist_date']         = date('Y-m-d H:i:s');
				
				$table = $this->voucher->tbl_payout_history;
				$this->voucher->insert_general_data($table,$fields,FALSE);

				$audit_table[]  = $this->voucher->tbl_payout_history;
				$audit_schema[] = DB_MAIN;
				$prev_detail[]  = array();
				$curr_detail[]  = array($fields);
				$audit_action[] = AUDIT_INSERT;	


				$audit_activity = "Voucher has been updated.";				

				
				$status = true;
				$message = $this->lang->line('data_updated');
			}
			
			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
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
	private function _validate_voucher($params)
	{
		try
		{
						
			$fields = array();
			
			$fields['voucher_description'] = "Voucher Description";
			$fields['voucher_date']        = "Voucher Date";
			$fields['employee']            = "Employee";
			$fields['bank']                = "Bank Account";
			$fields['certified']           = "Certified By";
			$fields['ca_certified']        = "CA Certified By";
			$fields['approved']            = "Approved By";
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_voucher($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_voucher($params)
	{
		try
		{
			$validation['voucher_description'] = array(
					'data_type' => 'string',
					'name'		=> 'Voucher Description',
					'max_len'	=> 255
			);
			$validation['voucher_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Voucher Date'
			);
			$validation['employee'] = array(
					'data_type' => 'digit',
					'name'		=> 'Employee',
					'max_len'	=> 11
			);
			$validation['bank'] = array(
					'data_type' => 'string',
					'name'		=> 'Bank Account',
					'max_len'	=> 50
			);
			$validation['certified'] = array(
					'data_type' => 'digit',
					'name'		=> 'Certified By',
					'max_len'	=> 11
			);
			$validation['ca_certified'] = array(
					'data_type' => 'digit',
					'name'		=> 'CA Certified By',
					'max_len'	=> 11
			);
			$validation['approved'] = array(
					'data_type' => 'digit',
					'name'		=> 'Approved By',
					'max_len'	=> 11
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

public function process_payment_date()
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
			$valid_data = $this->_validate_payment_date($params);
			
			Main_Model::beginTransaction();	

			$field        = array("*") ;
			// $table        = $this->voucher->tbl_vouchers;
			$table 		  = array(
					'main' => array(
						'table' => $this->voucher->tbl_vouchers,
						'alias' => 'A'
					),
					't1'   => array(
						'table' => $this->voucher->tbl_payout_summary,
						'alias' => 'B',
						'type' => 'JOIN',
						'condition' => 'A.payroll_summary_id=B.payroll_summary_id'
					),
					't2'   => array(
						'table' => $this->voucher->tbl_payout_header,
						'alias' => 'C',
						'type' => 'JOIN',
						'condition' => 'B.payroll_summary_id=C.payroll_summary_id'
					)
			);
			$where        = array();
			$key          = $this->get_hash_key('voucher_id');
			$where[$key]  = $id;
			$voucher_info = $this->voucher->get_general_data($field, $table, $where, FALSE);
			
			// UPDATE VOUCHER TABLE
			$fields                      = array() ;
			$fields['payment_date']      = $valid_data['payment_date'];
			$fields['payment_details']   = $valid_data['payment_details'];
			$fields['voucher_status_id'] = PAYOUT_STATUS_PAID;
			
			$where       = array();
			$key         = $this->get_hash_key('voucher_id');
			$where[$key] = $id;
			$table       = $this->voucher->tbl_vouchers;
			$this->voucher->update_general_data($table,$fields,$where);

			// UPDATE PAYOUT SUMMARY TABLE
			$fields 				   = array();
			$fields['payout_status_id']= PAYOUT_STATUS_PAID;

			$where                     = array();
			$where['payroll_summary_id'] = $voucher_info['payroll_summary_id'];
			$table                     = $this->voucher->tbl_payout_summary;
			$this->voucher->update_general_data($table, $fields, $where);
			
			$deductions = $this->voucher->get_bir_deduction($voucher_info['payroll_summary_id']);
			
			$deductions = explode(',', $deductions);

			$form_2316 = FALSE;
			$form_2307 = FALSE;
			foreach ($deductions AS $deduction) {
				if($deduction == DEDUC_BIR) {
					$form_2316 = TRUE;
				}
				if($deduction == DEDUC_BIR_EWT OR $deduction == DEDUC_BIR_VAT) {
					$form_2307 = TRUE;
				}
			}
			/* RE-COMPUTE FORM BIR DEDUCTION ON 2316 DETAILS */
			if($form_2316) {
				$this->form2316 = modules::load('main/payroll_form_2316');
				$tax_table_flag = TAX_ANNUALIZED;
				$payout_date    = format_date($valid_data['payment_date'],'Y-m-d');
				$save           = TRUE;
				$project_tax    = FALSE;
				$monthly_only   = FALSE;
				
				$form_2316_ids  = $this->form2316->construct_form_2316($tax_table_flag, $payout_date, NULL, NULL, $included_employees, NULL, $save, $project_tax, $monthly_only);
			}
			/* RE-COMPUTE FORM BIR DEDUCTION ON 2307 DETAILS */
			if($form_2307) {
				$this->form2307 = modules::load('main/payroll_form_2307');

				$payout_year        = format_date($valid_data['payment_date'],'Y');
				$dte                = new DateTime($valid_data['payment_date']);
				$payroll_summary_id = $voucher_info['payroll_summary_id'];
				$included_employees = array($voucher_info['employee_id']);

				$this->form2307->construct_form_2307($payout_year, $dte, $payroll_summary_id, $included_employees, NULL, TRUE);
			}
			$user = $this->voucher->get_general_data(array('user_id'),$this->voucher->tbl_associated_accounts, array('employee_id' => $voucher_info['processed_by']), FALSE);

			$this->_insert_payout_notifications($params ['module'], $voucher_info['payroll_summary_id'], 
						PAYOUT_STATUS_PAID, $user['user_id']);

			$field = array("payroll_hdr_id","employee_id") ;
			$table = $this->voucher->tbl_payout_header;
			$where = array();
			$where['payroll_summary_id'] = $voucher_info['payroll_summary_id'];
			$payroll_header              = $this->voucher->get_general_data($field, $table, $where, FALSE);
			
			// UPDATE PAYOUT DETAILS TABLE
			$fields = array();
			$fields['effective_date'] = $valid_data['payment_date'];

			$where                   = array();
			$where['payroll_hdr_id'] = $payroll_header['payroll_hdr_id'];
			$table                   = $this->voucher->tbl_payout_details;
			$this->voucher->update_general_data($table, $fields, $where);

			$field       = array("*") ;
			$table       = $this->voucher->tbl_employee_personal_info;
			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $this->session->userdata("user_pds_id");
			$pds_info    = $this->voucher->get_general_data($field, $table, $where, false);	
			
			// UPDATE PAYOUT HISTORY TABLE
			$fields                      = array() ;
			$fields['payout_summary_id'] = $voucher_info['payroll_summary_id'];
			$fields['employee_id']       = $pds_info['employee_id'];
			$fields['action_id']         = ACTION_PROCESS;
			$fields['payout_status_id']  = PAYOUT_STATUS_PAID;
			$fields['hist_date']         = date('Y-m-d H:i:s');
			
			$table = $this->voucher->tbl_payout_history;
			$this->voucher->insert_general_data($table,$fields,FALSE);
			$audit_table[]  = $this->voucher->tbl_vouchers;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($voucher_info);
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;	

			$audit_activity = "Voucher payment date has been updated.";		

			
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
	private function _validate_payment_date($params)
	{
		try
		{
						
			$fields                  = array();			
			$fields['payment_date'] = "Payment Date";

			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_payment_date($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_payment_date($params)
	{
		try
		{
			$validation['payment_date'] = array(
					'data_type' => 'date',
					'name'		=> 'Payment Date'
			);
			$validation['payment_details'] = array(
					'data_type' => 'string',
					'name'		=> 'Payment Details',
					'max_len'	=> 100
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function _insert_payout_notifications($module_id, $voucher_id, $voucher_status_id, $processed_by)
	{
		try
		{

			$params	= $this->voucher->get_notification_params($module_id, $voucher_status_id);

			$this->load->model('notifications_model', 'notification');
			
			$notif_params				= array();
			$notif_params['module_id']	= $module_id;
			
			$notif_msg		= '';
			// if ($params['approved_flag'] == 1)
			// {
			//  	$notif_msg = $this->lang->line('process_voucher_approved');
			// 	$notif_params['notify_roles']	= $params['notify_roles'];
			// 	$notif_params['notify_orgs']	= $params['notify_orgs'];
			// 	$notif_params['notified_by']	= $processed_by;
			// }
			if ($params['action_id'] == ACTION_PAYMENT)
			{
				$notif_msg = $this->lang->line('process_voucher_approved');
				$notif_params['notify_users'] = $processed_by;
			}
			else
			{
				$notif_msg = $this->lang->line('process_voucher_notif');
				$notif_params['notify_roles']	= $params['notify_roles'];
				$notif_params['notify_orgs']	= $params['notify_orgs'];
				$notif_params['notified_by']	= $processed_by;
			}
			$title = 'Employee Voucher';

			$notif_params['notification'] 	= $notif_msg;
			$notif_params['title']			= $title;
			
			// construct record_link
			$salt			= gen_salt();
			$id 			= $this->hash($voucher_id);
			$token_process 	= in_salt($id  . '/' . ACTION_PROCESS  . '/' . $module_id, $salt);
			
			$url_process 	= base_url()."main/voucher_process/display_payroll_voucher_process/".ACTION_PROCESS."/".$id ."/".$token_process."/".$salt."/".$module_id;

			$notif_params['record_link']	= $url_process;

			$this->notification->insert_notification($notif_params);
		}
		catch(Exception $e)
		{
			throw($e);
		}
	}

	public function get_bank_account()
	{

		try
		{
			$output = array();
			$params   = get_params();

			$fields = array('identification_value');
			$table = $this->voucher->tbl_employee_identifications;
			$where['employee_id'] = $params['employee_id'];
			$where['identification_type_id'] = BANKACCT_TYPE_ID;

			$bank_account = $this->voucher->get_general_data($fields, $table, $where, FALSE);
			if(empty($bank_account)) throw new Exception('The personnel has no bank account.');
			$status = TRUE;
			
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
		$output['bank_account'] = $bank_account['identification_value'];
		$output['status'] = $status;
		$output['message'] = $message;
		echo json_encode( $output );
	}
}


/* End of file Payroll_voucher.php */
/* Location: ./application/modules/main/controllers/Payroll_voucher.php */