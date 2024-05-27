<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bir extends Main_Controller {
	private $module = MODULE_PAYROLL_CL_BIR_TABLE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     = array();
			$resources                = array();
			$data['action_id']        = $action_id;
			$resources['load_css'][]  = CSS_DATATABLE;
			$resources['load_js'][]   = JS_DATATABLE;
			$resources['datatable'][]	= array('table_id' => 'bir_table_dt', 'path' => 'main/code_library_payroll/bir/get_bir_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] 	= array(
				'modal_bir'				=> array(
					'controller'		=> 'code_library_payroll/bir',
					'module'			=> PROJECT_MAIN,
					'method'			=> 'modal_bir',
					'multiple'			=> true,
					'height'			=> '300px',
					'size'				=> 'xl',
					'title'				=> 'BIR'
				)
			);
			$resources['load_delete'] 		= array(
				'code_library_payroll/bir',
				'delete',
				PROJECT_MAIN
			);
			
			$data['action_id'] = $action_id;
			
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

		$this->load->view('code_library/tabs/bir', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_bir_list()
	{

		try
		{
			$params 					= get_params();
			
			$aColumns					= array("*");
			$bColumns					= array("effective_date", "tax_table_flag", "IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table 	  					= $this->cl->tbl_param_bir;
			$where						= array();
			$bir 						= $this->cl->get_bir_list($aColumns, $bColumns, $params, $table, $where);
			$iTotal   					= $this->cl->get_code_library_data(array("COUNT(DISTINCT(bir_id)) AS count"), $this->cl->tbl_param_bir, NULL, false);
		
			$output 					= array(
				"sEcho" 				=> intval($_POST['sEcho']),
				"iTotalRecords" 		=> count($bir),
				"iTotalDisplayRecords" 	=> $iTotal["count"],
				"aaData" 				=> array()
			);
			//PERMISSIONS
			$permission_view 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_VIEW);
			$permission_edit 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_EDIT);
			$permission_delete 			= $this->permission->check_permission(MODULE_PAYROLL_CL_BANK_BRANCH, ACTION_DELETE);

			$cnt = 0; 
			foreach ($bir as $aRow):
				$cnt++;
				$row 					= array();

				$action 				= "<div class='table-actions'>";
			
				$bir_id 				= $aRow["bir_id"];
				$id 					= $this->hash ($bir_id);
				$salt 					= gen_salt();
				$token_view 			= in_salt($id . '/' . ACTION_VIEW, $salt);
				$token_edit 			= in_salt($id . '/' . ACTION_EDIT, $salt);
				$token_delete 			= in_salt($id . '/' . ACTION_DELETE, $salt);
				$view_action 			= ACTION_VIEW . "/". $id . "/" . $salt  . "/" . $token_view;	
				$edit_action 			= ACTION_EDIT . "/". $id . "/" . $salt  . "/" . $token_edit;			
				$url_delete 			= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action			= 'content_delete("BIR Table", "'.$url_delete.'")';

				$row[] = '<center>' . format_date($aRow['effective_date']) . '</center>';
				$row[] = $aRow['tax_table_flag'];
				$row[] = strtoupper(($aRow['active_flag'] == "Y") ? Y:N);
				
				if($permission_view)
				$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_bir' onclick=\"modal_bir_init('".$view_action."')\"></a>";
				if($permission_edit)
				$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_bir' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_bir_init('".$edit_action."')\"></a>";
				if($permission_delete)
				$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				
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

	public function modal_bir($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources 					= array();
			$resources['load_css']		= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js'] 		= array(JS_DATETIMEPICKER, JS_SELECTIZE, 'jquery.number.min');


			// GET SECURITY VARIABLES
			if ($action != ACTION_ADD) {
				if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($token != in_salt ( $id . '/' . $action, $salt )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			$data['action_id'] 				= $action;
			$data['nav_page']				= MODULE_PAYROLL_CL_BIR_TABLE;
			$data ['action'] 				= $action;
			$data ['salt'] 					= $salt;
			$data ['token'] 				= $token;
			$data ['id'] 					= $id;
			
			// STORES BIR TAX STORES
			$where = array();
			$where['sys_param_type'] = BIR_TAX_TABLE;
			$data['bir_tax_table'] = $this->cl->get_bir_tax_table($where);

			if(!EMPTY($id))
			{

				$tables = array(

					'main'      => array(
					'table'     => $this->cl->tbl_param_bir,
					'alias'     => 'A',
					),
					't2'        => array(
					'table'     => $this->cl->tbl_param_bir_details,
					'alias'     => 'B',
					'type'      => 'LEFT JOIN',
					'condition' => 'A.bir_id = B.bir_id',
				 	)
				);
				$select_fields = array("A.*, B.*");
				//EDIT
				$table 						= $this->cl->tbl_param_bir;
				$where						= array();
				$key 						= $this->get_hash_key('A.bir_id');
				$where[$key]				= $id;
				$bir_info 					= $this->cl->get_code_library_details($select_fields, $tables, $where);	
				
				$data['bir_info']			= $bir_info;

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
		
		$this->load->view('code_library/modals/modal_bir', $data);
		$this->load_resources->get_resource($resources);
	}
	
	public function process()
	{
		try
		{
			$status = 0;
			$params	= get_params();
			// GET SECURITY VARIABLES
			if ($params ['action'] != ACTION_ADD) {
				if (EMPTY ( $params ['action'] ) or EMPTY ( $params ['id'] ) or EMPTY ( $params ['salt'] ) or EMPTY ( $params ['token'] )) {
					throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
				}
				if ($params ['token'] != in_salt ( $params ['id'] . '/' . $params ['action'], $params ['salt'] )) {
					throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );
				}
			}

			// SERVER VALIDATION
			$valid_data 				= $this->_validate_data_bir($params);
			
			//SET FIELDS VALUE
			$fields['effective_date']  	= $valid_data['effective_date'];

			//$fields['account_number']  	= $valid_data['account_number'];
			$fields['active_flag']  	= isset($valid_data['active_flag']) ? "Y" : "N";
			$fields['tax_table_flag']	= $valid_data['tax_table_flag'];
			
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 			= $this->cl->tbl_param_bir;
			$audit_table[]	= $table;
			$audit_schema[]	= Base_Model::$schema_core;
			
			if(EMPTY($params['id']))
			{
				//INSERT 

				//SET AUDIT TRAIL DETAILS
				$audit_action[]		= AUDIT_INSERT;
				
				$prev_detail[]		= array();

				//INSERT DATA
				$bir_id 			= $this->cl->insert_code_library($table, $fields, TRUE);
				$valid_data_details = array();
				$is_start 	= TRUE;
				$is_end		= FALSE;
				$table_row	= 1;
				foreach ($params['min_amount'] as $key => $value) 
				{	
					$bir_details               		   		= array();
					$bir_details['min_amount'] 		   		= $params['min_amount'][$key];
					$bir_details['max_amount'] 		   		= $params['max_amount'][$key];
					$bir_details['tax_amount'] 		   		= $params['tax_amount'][$key];
					$bir_details['tax_rate']   		   		= $params['tax_rate'][$key];
					$bir_details['exempt_status_code'] 		= $params['exempt_status_code'][$key];
					$bir_details['professional_flag']  		= isset($params['professional_flag'][$key]) ? "Y" : "N";
					$bir_details['non_professional_flag']   = isset($params['non_professional_flag'][$key]) ? "Y" : "N";
					$bir_details['vat_flag']  				= isset($params['vat_flag'][$key]) ? "Y" : "N";
					if( ISSET( $params['min_amount'][$key+1] ) )
					{
						if($params['min_amount'][$key] > $params['max_amount'][$key] )
						{
							$cnt = 0;
							$cnt = $key + 1;
							throw new Exception('Maximum must be greater than the minimum amount. <b>[ROW - ' . $cnt . ']</b>');
						}
					}
					else
					{
						$is_end = TRUE;
					}
					$valid_data_details[$key]  = $this->_validate_data_bir_details($bir_details, $is_start, $is_end,$table_row);
					$valid_data_details[$key]['bir_id'] = $bir_id;
					$counter++;
					$is_start = FALSE;
					$table_row++;
				}
				$this->cl->insert_code_library($this->cl->tbl_param_bir_details, $valid_data_details);
				//MESSAGE ALERT
				$message 			= $this->lang->line('data_saved');

				//WHERE VALUES
				$where 	 			= array();
				$where['bir_id']	= $bir_id;

				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[] 		= $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);	
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 			= "%s has been added";
			}
			else
			{
				//UPDATE 

				//WHERE 
				$where			= array();
				$key 			= $this->get_hash_key('bir_id');
				$where[$key]	= $params['id'];
				
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				//UPDATE DATA
				$this->cl->update_code_library($table, $fields, $where);
				$this->cl->delete_code_library($this->cl->tbl_param_bir_details, $where);
				$valid_data_details = array();
				$is_start 	= TRUE;
				$is_end		= FALSE;
				$table_row	= 1;
				foreach ($params['min_amount'] as $key => $value) 
				{	
					$bir_details               		   		= array();
					$bir_details['min_amount'] 		   		= $params['min_amount'][$key];
					$bir_details['max_amount'] 		   		= $params['max_amount'][$key];
					$bir_details['tax_amount'] 		   		= $params['tax_amount'][$key];
					$bir_details['tax_rate']   		   		= $params['tax_rate'][$key];
					$bir_details['exempt_status_code'] 		= $params['exempt_status_code'][$key];
					$bir_details['professional_flag']  		= isset($params['professional_flag'][$key]) ? "Y" : "N";
					$bir_details['non_professional_flag']   = isset($params['non_professional_flag'][$key]) ? "Y" : "N";
					$bir_details['vat_flag']  				= isset($params['vat_flag'][$key]) ? "Y" : "N";

					if( ISSET( $params['min_amount'][$key+1] ) )
					{
						if($params['min_amount'][$key] > $params['max_amount'][$key] )
						{	
							$cnt = 0;
							$cnt = $key + 1;
							throw new Exception('Maximum must be greater than the minimum amount. <b>[ROW - ' . $cnt . ']</b>');
						}
					}
					else
					{
						$is_end = TRUE;
					}

					$valid_data_details[$key]  = $this->_validate_data_bir_details($bir_details,$is_start,$is_end,$table_row);
					$valid_data_details[$key]['bir_id'] = $prev_detail[0][0]['bir_id'];
					$is_start = FALSE;
					$table_row++;
				}
				$this->cl->insert_code_library($this->cl->tbl_param_bir_details, $valid_data_details);

				//MESSAGE ALERT
				$message 		= $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity 		= "%s has been updated";
				
			}
			
			$activity = sprintf($activity, 'BIR Table');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
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

	public function delete()
	{
		try
		{
			$params 			= get_params();
			$security_data 		= explode("/", $params['param_1']);
			$action  			= $security_data[0];
			$id  				= $security_data[1];
			$salt  				= $security_data[2];
			$token  			= $security_data[3];
			$flag 				= 0;

			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag 				= 0;
			$params				= get_params();
				
			$action 			= AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			$table 				= $this->cl->tbl_param_bir;
			$where				= array();
			$key 				= $this->get_hash_key('bir_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
			
			$this->cl->delete_code_library($table, $where);
			$msg 				= $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $prev_detail[]  = $this->cl->get_code_library_data(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity 			= "%s has been deleted";
			$activity 			= sprintf($activity, 'BIR table');
	
			// LOG AUDIT TRAIL
			$this->audit_trail->log_audit_trail(
				$activity, 
				$this->module, 
				$prev_detail, 
				$curr_detail, 
				$audit_action, 
				$audit_table,
				$audit_schema
			);
				
			Main_Model::commit();
			$flag = 1;
				
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
		
			$this->rlog_error($e, TRUE);
			$msg = $this->lang->line('parent_delete_error');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		$info 					= array(
			"flag" 				=> $flag,
			"msg" 				=> $msg,
			"reload" 			=> 'datatable',
			"table_id" 			=> 'bir_table_dt',
			"path"				=> PROJECT_MAIN . '/code_library_payroll/bir/get_bir_list/',
			"advanced_filter" 	=> true
		);
	
		echo json_encode($info);
	}

	private function _validate_data_bir($params)
	{
		$fields                   = array();
		$fields['effective_date'] = "Effectivity Date";
		$fields['tax_table_flag'] = "Tax Table Flag";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_bir_input ($params);
	}

	private function _validate_bir_input($params) 
	{
		try {

			$validation ['effective_date'] 	= array (
					'data_type' 			=> 'date',
					'name'					=> 'Effectivity Date',
					'max_len' 				=> 50
			);
			
			$validation ['tax_table_flag'] 	= array (
					'data_type' 			=> 'string',
					'name'					=> 'Tax Table Flag',
					'max_len' 				=> 50
			);
			
			$validation ['active_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Active Flag',
					'max_len' 				=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}

	private function _validate_data_bir_details($params, $is_start, $is_end,$table_row)
	{
		$fields               = array();
		if( $is_start === FALSE ) {
			$fields['min_amount'] = "Minimum Amount. [ROW - ".$table_row."]";
		}
		if( $is_end === FALSE )
		{
			$fields['max_amount'] = "Maximum Amount. <b>[ROW - ".$table_row."]";
		}
		//$fields['tax_amount'] = "Tax Amount";
		// $fields['tax_rate']   = "Tax Rate";

		$this->check_required_fields($params, $fields);		

		return $this->_validate_bir_details_input ($params);
	}

	private function _validate_bir_details_input($params) 
	{
		try {

			$validation ['min_amount'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Minimum Amount',
					'max_len' 			=> 10 
			);

			$validation ['max_amount'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Maximum Amount',
					'max_len' 			=> 10 
			);
			
			$validation ['tax_amount'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Tax Amount',
					'max_len' 			=> 10 
			);			

			$validation ['tax_rate'] 	= array (
					'data_type' 		=> 'digit',
					'name'				=> 'Tax Rate',
					'max_len' 			=> 5 
			);

			$validation ['exempt_status_code'] 	= array (
					'data_type' 				=> 'string',
					'name'						=> 'Status Code',
					'max_len' 					=> 6 
			);

			$validation ['professional_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Professional Flag',
					'max_len' 				=> 1 
			);

			$validation ['non_professional_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'Non Professional Flag',
					'max_len' 				=> 1 
			);

			$validation ['vat_flag'] 	= array (
					'data_type' 			=> 'string',
					'name' 					=> 'VAT Flag',
					'max_len' 				=> 1 
			);
			
			return $this->validate_inputs($params, $validation );

		} catch ( Exception $e ) {
			throw $e;
		}
	}
}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */