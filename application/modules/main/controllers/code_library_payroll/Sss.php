<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sss extends Main_Controller {
	private $module ;
	private $per_view;
	private $per_edit;
	private $per_delete;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('code_library_model', 'cl');
		$this->module = MODULE_PAYROLL_CL_SSS;
		$this->tbl_sss = DOH_PTIS_TABLE_SSS;
		$this->per_view = $this->permission->check_permission($this->module, ACTION_VIEW);
		$this->per_edit = $this->permission->check_permission($this->module, ACTION_EDIT);
		$this->per_delete = $this->permission->check_permission($this->module, ACTION_DELETE);
	
		$this->code_lib_js = 'sss';
	}
	
	public function initialize($action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{

		try
		{
			$data                     = array();
			$resources                = array();
			$resources['load_css']  = array(CSS_DATATABLE);
			$resources['load_js']   = array(JS_NUMBER,JS_DATATABLE,'modules/main/code_library/'. $this->code_lib_js);
			$resources['datatable'][]	= array('table_id' => 'sss_table', 'path' => 'main/code_library_payroll/sss/get_sss_list', 'advanced_filter' => TRUE);
			$resources['load_modal'] = array(
					'modal_sss' => [
							'controller' => 'code_library_payroll/'.__CLASS__,
							'module' => PROJECT_MAIN,
							'method' => 'modal_sss',
							'multiple' => true,
							'height' => '400px',
							'size' => 'lg',
							'title' => 'SSS'
					]
			);
			$resources['load_delete'] 		= array(
				'code_library_payroll/'.__CLASS__,
				'delete',
				PROJECT_MAIN
			);

			// CREATE SECURITY VARIABLES
			$data['action_id'] = $action_id;
			$data['salt'] = $salt;
			$data['token'] = $token;
			
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

		$this->load->view('code_library/tabs/sss', $data);
		$this->load_resources->get_resource($resources);
	}

	public function get_sss_list()
	{

		try
		{
			//get data table variables
			$params = get_params();
			$aColumns = array("DATE_FORMAT(effectivity_date, '%Y/%m/%d') as effectivity_date",'active_flag');
			$bColumns = array("DATE_FORMAT(effectivity_date, '%Y/%m/%d')","IF(active_flag = 'Y', 'Active', 'Inactive')");
			$table = $this->tbl_sss;
			$sWhere = array();
			$result_table = $this->cl->get_sss_list($params, $aColumns, $bColumns, $table, $sWhere);
			//result data table variables
			$iTotal = count($result_table);
			$displayRecord['count'] = $iTotal;
			$response = [
				'sEcho' => intval(0),
				'iTotalRecords' => $iTotal,
				'iTotalDisplayRecords' => $displayRecord['count'],
				'aaData' => array()
			];

			//Permissions
			$permission_view = $this->per_view;
			$permission_edit = $this->per_edit;
			$permission_delete = $this->per_delete;

			$cnt = 0;

			foreach($result_table as $rRow):
				$cnt++;

				$row = array();
				$action = "<div class='table-actions'>";
				$id  = $rRow['effectivity_date'];

				//START CONSTRUCT SECURITY VARIABLES
				$hashed_id = $this->hash($id);
				$encoded_id = base64_url_encode($hashed_id);
				$salt = gen_salt();
				$token = in_salt($encoded_id,$salt);

				//actions
				$view_action = ACTION_VIEW."/".$encoded_id."/".$salt."/".$token;
				$edit_action = ACTION_EDIT."/".$encoded_id."/".$salt."/".$token;
				$url_delete = ACTION_DELETE."/".$encoded_id."/".$salt."/".$token;
				$delete_action = "content_delete('SSS Effectivity Date','".$url_delete."')";

				$row[] = '<center>' . $rRow['effectivity_date']. '</center>';
				$row[] = strtoupper(($rRow['active_flag'] == "Y")? Y:N);

				if($permission_view){
					$action .= "<a href='javascript:;' data-tooltip='View' class='view tooltipped md-trigger' data-modal='modal_sss' onclick=\"modal_sss_init('".$view_action."')\"></a>";
				}
				
				if($permission_edit){
					$action .= "<a href='#!' class='edit tooltipped md-trigger' data-modal='modal_sss' data-tooltip='Edit' data-position='bottom' data-delay='50' onclick=\"modal_sss_init('".$edit_action."')\"></a>";
				}
				
				if($permission_delete){
					$action .= "<a href='javascript:;' onclick=\"". $delete_action ."\" class='delete tooltipped' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				}
					
				//close div table actions
				$action .= "</div>";

				//pass the created html tags to array
				$row[] = $action;
				$response['aaData'][] = $row;

				endforeach;


		}
		catch(PDOException $e)
		{
			throw $e;
		}
		catch(Exception $e)
		{
			throw $e;
		}

		echo json_encode( $response );

	}
	
	public function modal_sss($action = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$resources = array();
			$resources['load_css'] = array(CSS_DATETIMEPICKER);
			$resources['load_js'] = array(JS_DATETIMEPICKER,JS_NUMBER);
			$resources['loaded_init'] = array('sss.initialize_form();','sss.initialize_ajax();');

			$salt = gen_salt();
			$token = in_salt($action,$salt);

			$data['action'] = $action;
			$data['salt'] = $salt;
			$data['token'] = $token;

			if(!IS_NULL($id))
			{
				$view_permission = $this->per_view;
				$edit_permission = $this->per_edit;

				/* if(EMPTY($edit_permission))
				{
					throw new Exception($this->lang->line('err_unauthorized_access'));
					
				} */

				if(!empty($id))
				{
					$id = base64_url_decode($id);
					$hash_key = $this->get_hash_key('effectivity_date');
					$aColumns = array("effectivity_date","active_flag");
					$where = array();
					$where[$hash_key] = $id;
					$data['sss'] = $this->cl->get_sss_details($where);

					$hash_id = $this->hash($data['sss_id']);
					$data['date_id'] = $data['sss'][0]['effectivity_date'];
					$data['encoded_date_id'] = $this->hash($data['date_id']);
				}

			}

			$view = $this->load->view('code_library/modals/modal_sss',$data, TRUE);
			$view .=$this->load_resources->get_resource($resources, TRUE);

			echo $view;

		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			echo $message;

		}
	}

	public function process()
	{
		try
		{
			$status = 0;
			$params = get_params();
			
			// RESULT VARIABLES
			$flag = 0;
			$msg = ERROR;
			$class = ERROR;

			// SECURITY VARIABLES
			$id = $params['id'];
			$action_id = $params['action'];
			$salt = $params['salt'];
			$token = $params['token'];

			// CHECK SECURITY VARIABLES 
			check_salt($action_id,$salt,$token);

			// SERVER VALIDATION
			$valid_data = $this->_validate_data_sss($params);

			// BEGIN TRANSACTION
			Main_Model::beginTransaction();

			if($id != "")
			{
				// ------ UPDATE ---------
				$hash_key = $this->get_hash_key('effectivity_date');
				$where[$hash_key] = $params['id'];

				$previous = $this->cl->get_code_library_data(['*'], DOH_PTIS_TABLE_SSS, $where, TRUE);
				
				// DELETE THE PREVIOUS SET DATA
				$this->cl->delete_code_library(DOH_PTIS_TABLE_SSS, $where);

				//INSERT THE NEW SET DATA
				for($x = 0; $x < count($params['salary_range_to']);$x++)
				{
					$fields = array();

					$fields['effectivity_date'] = $valid_data['effectivity_date'];
					$fields['salary_range_from'] = $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] = $valid_data['salary_range_to'][$x];
					$fields['salary_base'] = $valid_data['salary_credit'][$x];
					$fields['total_monthly_amount'] = $valid_data['employee_share'][$x] + $valid_data['employer_share'][$x];
					$fields['employee_share'] = $valid_data['employee_share'][$x];
					$fields['employer_share'] = $valid_data['employer_share'][$x];
					$fields['active_flag'] = (!empty($valid_data['active_flag']))? "N":"Y";

					$this->cl->insert_code_library(DOH_PTIS_TABLE_SSS,$fields);
				}

				//UPDATE RESULT DETAIL
				$msg = $this->lang->line('data_updated');
				$flag = 1;
				$class = SUCCESS;
				

				// SET AUDIT TRAIL DETAILS
				$audit_table[] = DOH_PTIS_TABLE_SSS;
				$audit_schema[] = DB_MAIN;
				$prev_detail[] = array($previous);
				$curr_detail[] = array($fields);
				$audit_action[] = AUDIT_INSERT;

				// ACTIVITY TO BE LOGGED ON AUDIT TRAIL
				$activity = "%s has been updated";
				$activity = sprintf($activity, $params['effectivity_date']);
				
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
				
			}
			else
			{

				// ------ INSERT ---------
				for($x = 0; $x < count($valid_data['salary_range_to']);$x++)
				{
					$fields = array();

					$fields['effectivity_date'] = $valid_data['effectivity_date'];
					$fields['salary_range_from'] = $valid_data['salary_range_from'][$x];
					$fields['salary_range_to'] = $valid_data['salary_range_to'][$x];
					$fields['salary_base'] = $valid_data['salary_credit'][$x];
					$fields['total_monthly_amount'] = $valid_data['employee_share'][$x] + $valid_data['employer_share'][$x];
					$fields['employee_share'] = $valid_data['employee_share'][$x];
					$fields['employer_share'] = $valid_data['employer_share'][$x];
					$fields['active_flag'] = (!empty($valid_data['active_flag']))? "Y":"N";
					$this->cl->insert_code_library(DOH_PTIS_TABLE_SSS,$fields);

				}

				//UPDATE RESULT DETAILS
				$msg = $this->lang->line('data_saved');
				$flag = 1;
				$class = SUCCESS;

				// SET AUDIT TRAIL DETAILS
				$audit_table[] = DOH_PTIS_TABLE_SSS;
				$audit_schema[] = DB_MAIN;
				$prev_detail[] = array();
				$curr_detail[] = array($fields);
				$audit_action[] = AUDIT_INSERT;
				
				// ACTIVITY TO BE LOGGED ON AUDIT TRAIL
				$activity = "%s has been added";
				$activity = sprintf($activity, $params['effectivity_date']);
				
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

			}
			
		}
		catch (PDOException $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($message);
		}
		catch (Exception $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($message);
		}
		
		$response = [
			"status" => $class,
			"msg" => $msg,
			"flag" => $flag,
			"class" => $class,
			"reload" => "datatable",
			"table_id" => "sss_table",
			"path" => "main/code_library_payroll/sss/get_sss_list"
		];

		echo json_encode($response);
	}

	public function delete()
	{
		try
		{
			// RESULT DETAILS
			$flag = 0;
			$msg = ERROR;
			$class = ERROR;
			
			$params = get_params();
			$sec_data = explode('/',$params['param_1']);

			$action = $sec_data[0];
			$id = $sec_data[1];
			$salt = $sec_data[2];
			$token = $sec_data[3];

			Main_Model::beginTransaction();
			if($action != ACTION_DELETE)
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			// check security variable
			check_salt($id, $salt, $token);

			$id = base64_url_decode($id);
			
			$where = array();
			$hash_key = $this->get_hash_key('effectivity_date');
			$where[$hash_key] = $id;
			
			// GET PREVIOUS DETAIL
			$prev_detail[] = $this->cl->get_code_library_data(['*'], DOH_PTIS_TABLE_SSS, $where, TRUE);
			$curr_detail = $this->cl->get_code_library_data(['*'], DOH_PTIS_TABLE_SSS, $where, TRUE);

			$this->cl->delete_code_library(DOH_PTIS_TABLE_SSS,$where);
			
			//UPDATE RESULT DETAILS
			$flag = 1;
			$msg = $this->lang->line('data_deleted');
			$class = SUCCESS;
			
			// ACTIVITY TO BE LOGGED ON AUDIT TRAIL
			$activity = "%s has been deleted";
			$activity = sprintf($activity, $params['effectivity_date']);
			
			// SET AUDIT TRAIL DETAILS
			$audit_table[] = DOH_PTIS_TABLE_SSS;
			$audit_schema[] = DB_MAIN;
			$prev_detail[] = array($prev_detail);
			$curr_detail = array();
			$audit_action[] = AUDIT_DELETE;
			
			$this->audit_trail->log_audit_trail(
					$activity,
					$this->module,
					$prev_detail,
					$curr_detail,
					$audit_action,
					$audit_table,
					$audit_schema
			);
			
			// COMMIT AUDIT TRAIL DETAILS
			Main_Model::commit();
			
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($e);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$msg = $e->getMessage();
			RLog::error($e);
		}
		$info = array(
			"msg" => $msg,
			"flag" => $flag,
			"class" => $class,
			"reload" => "datatable",
			"table_id" => "sss_table",
			"path"	=> 'main/code_library_payroll/sss/get_sss_list'
		);
		echo json_encode($info);
	}

	private function _validate_data_sss($params)
	{
		$fields = array();
		// $fields['salary_range_from'] = "Salary Range From";
		// $fields['salary_range_to'] = "Salary Range To";
		// $fields['salary_credit'] = "Salary Credit";
		// $fields['employer_share'] = "Employer Share";
		// $fields['employee_share'] = "Employee Share";

		$this->check_required_fields($params, $fields);

		return $this->_validate_sss_input($params);

	}

	private function _validate_sss_input($params) 
	{
		try 
		{

			$validation['effectivity_date'] = array(
				'data_type' => 'date',
				'name' => 'Effecvity Date'
			);

			$validation['salary_range_from'] = array(
				'data_type' => 'string',
				'name' => 'Salary Range From',
				'max_len' => 45
			);

			$validation['salary_range_to'] = array(
				'data_type' => 'string',
				'name' => 'Salary Range To',
				'max_len' => 45
			);

			$validation['salary_credit'] = array(
				'data_type' => 'string',
				'name' => 'Salary Credit',
				'max_len' => 45
			);

			$validation['employer_share'] = array(
				'data_type' => 'string',
				'name' => 'Employer Share',
				'max_len' => 45
			);

			$validation['employee_share'] = array(
				'data_type' => 'string',
				'name' => 'Employee Share',
				'max_len' => 45
			);

			$validation['active_flag'] = array(
				'data_type' => 'string',
				'name' => 'Active Flag',
				'max_len' => 1
			);

			return $this->validate_inputs($params, $validation);

		}

		catch ( Exception $e )
		{
			throw $e;
		}
	}

}

/* End of file Code_library.php */
/* Location: ./application/modules/main/controllers/Code_library_hr.php */