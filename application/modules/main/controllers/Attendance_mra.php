<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_mra extends Main_Controller {
	private $log_user_id    =  '';
	private $log_user_roles = array();

	public function __construct()
	{
		parent::__construct();
		$this->load->model('attendance_period_model', 'attendance');
		$this->log_user_id    = $this->session->userdata('user_id');
		$this->log_user_roles = $this->session->userdata('user_roles');
	}	
	
	public function modal_employee_mra($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module, $attendance_period_hdr_id)
	{
		try
		{
			$data           = array();
			$resources      = array();
			
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;
			$data['attendance_period_hdr_id'] = $attendance_period_hdr_id;

			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($attendance_period_hdr_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $attendance_period_hdr_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$field                 = array("*") ;
			$table                 = $this->attendance->tbl_attendance_period_summary;
			$where                 = array();
			$key                   = $this->get_hash_key('employee_id');
			$where[$key]           = $id;
			$key                   = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key] = $attendance_period_hdr_id;
			$data['mra_summary'] = $this->attendance->get_general_data($field, $table, $where, FALSE);

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
		$this->load->view('attendance_period/modals/modal_employee_mra', $data);
		$this->load_resources->get_resource($resources);
	}

	public function process_employee_mra()
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
			$attendance_period_hdr_id			= $params['attendance_period_hdr_id'];
			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($attendance_period_hdr_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $attendance_period_hdr_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_data_summary($params);
			
			Main_Model::beginTransaction();


			$fields                = array();
			$fields['lwop_ut_hr']  = $valid_data["lwop_ut_hr"];
			$fields['lwop_ut_min'] = $valid_data["lwop_ut_min"];

			
			/*GET PREVIOUS DATA*/
			$table        = $this->attendance->tbl_attendance_period_summary;
			
			$field        = array("*") ;			
			$where        = array();
			$key          = $this->get_hash_key('employee_id');
			$where[$key]  = $id;
			$key          = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key]  = $attendance_period_hdr_id;
			$employee_mra = $this->attendance->get_general_data($field, $table, $where, FALSE);

			$where       = array();
			$key         = $this->get_hash_key('employee_id');
			$where[$key] = $id;
			$key         = $this->get_hash_key('attendance_period_hdr_id');
			$where[$key] = $attendance_period_hdr_id;
			$this->attendance->update_general_data($table,$fields,$where);

			$audit_table[]  = $table;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($employee_mra);
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;			
				
			$audit_activity = "Employee Late/Undertime without pay summary has been updated.";

			$message = $this->lang->line('data_updated');

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
				
			Main_Model::commit();
			$status = true;
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

	private function _validate_data_summary($params)
	{
		try
		{
			$validation['lwop_ut_hr'] = array(
					'data_type' => 'digit',
					'name'		=> 'Late/Undertime w/o Pay Hours'
			);
			$validation['lwop_ut_min'] = array(
					'data_type' => 'digit',
					'name'		=> 'Late/Undertime w/o Pay Minutes'
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	private function _validate_mra($params)
	{
		try
		{
						
			//SPECIFY HERE INPUTS FROM USER
			$fields                    = array();
			$fields['attendance_status_id'] = 'Attendance Status';
			$this->check_required_fields($params, $fields);
				
			return $this->_validate_input_mra($params);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	private function _validate_input_mra($params)
	{
		try
		{
			
			// $validation['working_hours'] = array(
			// 		'data_type' => 'digit',
			// 		'name'      => 'Total Hours Worked'
			// );
			$validation['tardiness_hours'] = array(
					'data_type' => 'digit',
					'name'		=> 'Late Hours'
			);
			$validation['undertime_hours'] = array(
					'data_type' => 'digit',
					'name'		=> 'Undertime Hours'
			);

			$validation['tardiness_min'] = array(
					'data_type' => 'digit',
					'name'		=> 'Late Minutes'
			);
			$validation['undertime_min'] = array(
					'data_type' => 'digit',
					'name'		=> 'Undertime Minutes'
			);
			$validation['attendance_status_id'] = array(
					'data_type' => 'digit',
					'name'		=> 'Attendance Status',
					'max_len' => 11
			);
			return $this->validate_inputs($params, $validation);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function modal_employee_daily_mra($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module, $attendance_period_hdr_id)
	{
		try
		{
			$data           = array();
			$resources      = array();
			
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;
			$data['attendance_period_hdr_id'] = $attendance_period_hdr_id;

			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($attendance_period_hdr_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $attendance_period_hdr_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}

			$resources['load_css'] 		= array(CSS_DATATABLE);
			$resources['load_js'] 		= array(JS_DATATABLE);
			$resources['load_modal']		= array(
				'modal_update_employee_mra'		=> array(
						'controller'	=> __CLASS__,
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_update_employee_mra',
						'multiple'		=> true,
						'height'		=> '300px',
						'size'			=> 'sm',
						'title'			=> "Employee MRA"
				)
			);	
			$post_data = array(
				'employee_id' => $id,
				'attendance_period_hdr_id' => $attendance_period_hdr_id
				);
			$resources['datatable'][]	= array('table_id' => 'table_employee_daily_mra', 'path' => 'main/attendance_mra/get_employee_daily_mra','advanced_filter'=>true,'post_data' => json_encode($post_data));
			

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
		$this->load->view('attendance_period/modals/modal_employee_daily_mra', $data);
		$this->load_resources->get_resource($resources);
	}
	public function get_employee_daily_mra()
	{
			try
		{
			$params         = get_params();

			$aColumns       = array("A.attendance_period_dtl_id","A.attendance_date", "A.working_hours", "CONCAT(IF(A.tardiness_hr IS NULL OR A.tardiness_hr <= 0,'',CONCAT(A.tardiness_hr,' hrs ')) ,IF(A.tardiness_min IS NULL OR A.tardiness_min <= 0,'',CONCAT(A.tardiness_min,' mins '))) as tardiness", "CONCAT(IF(A.undertime_hr IS NULL OR A.undertime_hr <= 0,'',CONCAT(A.undertime_hr,' hrs ')) ,IF(A.undertime_min IS NULL OR A.undertime_min <= 0,'',CONCAT(A.undertime_min,' mins '))) as undertime","B.attendance_status_name");
			$bColumns       = array("A.attendance_date", "A.working_hours", "A.tardiness", "A.undertime","B.attendance_status_name");
			
			$employee_list  = $this->attendance->get_employee_daily_mra($aColumns, $bColumns, $params);
			$iTotal         = $this->attendance->daily_mra_total_length($params['attendance_period_hdr_id']);
			$iFilteredTotal = $this->attendance->daily_mra_filtered_length($aColumns, $bColumns, $params);
			
			$output = array(
				"sEcho"                => intval($_POST['sEcho']),
				"iTotalRecords"        => $iTotal["cnt"],
				"iTotalDisplayRecords" => $iFilteredTotal["cnt"],
				"aaData"               => array()
			);
			
			$module          = MODULE_TA_ATTENDANCE_PERIOD;			
			$permission_view = $this->permission->check_permission($module, ACTION_VIEW);
			$permission_edit = $this->permission->check_permission($module, ACTION_EDIT);
			
			
			foreach ($employee_list as $aRow):
				$row        = array();
				
				// $id         = $this->hash($aRow['attendance_period_dtl_id']);				
				$id         =$aRow['attendance_period_dtl_id'];				
				$salt       = gen_salt();

				$token_edit = in_salt($id  . '/' . ACTION_EDIT  . '/' . $module . '/' . $params['attendance_period_hdr_id']. '/' . $params['employee_id'], $salt);
				$url_edit   = ACTION_EDIT."/".$id ."/".$token_edit."/".$salt."/".$module."/".$params['attendance_period_hdr_id']. '/' . $params['employee_id'];

				$row[] = $aRow['attendance_date'];
				$row[] = $aRow['working_hours'];
				$row[] = $aRow['tardiness'];
				$row[] = $aRow['undertime'];
				$row[] = $aRow['attendance_status_name'];

				
				$action = "<div class='table-actions'>";			
				if($permission_edit)
				$action .= "<a href='javascript:;' class='edit tooltipped md-trigger' data-tooltip='Edit MRA' data-position='bottom' data-modal='modal_update_employee_mra' onclick=\"modal_update_employee_mra_init('".$url_edit."')\" data-delay='50'></a>";
				
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

	public function modal_update_employee_mra($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $attendance_period_hdr_id = NULL, $employee_id = NULL)
	{
		try
		{
			$data           = array();
			$resources      = array();
			
			$data['action'] = $action;
			$data['id']     = $id;
			$data['salt']   = $salt;
			$data['token']  = $token;
			$data['module'] = $module;
			$data['attendance_period_hdr_id'] = $attendance_period_hdr_id;
			$data['employee_id'] = $employee_id;

			
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($attendance_period_hdr_id) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $attendance_period_hdr_id  . '/' . $employee_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			$resources['load_css'] 		= array(CSS_SELECTIZE);
			$resources['load_js'] 		= array(JS_SELECTIZE);
			// $attendance_period_dtl_ids	   = $this->attendance->get_general_data(('attendance_period_dtl_id'), $this->attendance->tbl_attendance_period_dtl);
			// echo"<pre>";
			// print_r($attendance_period_dtl_ids);
			// die();
			// foreach($attendance_period_dtl_ids as $attendance_period_dtl_id)
			// {
			// 	$prep_string = "%$".$attendance_period_dtl_id['attendance_period_dtl_id']."%$";
			// 	$hashed_id = md5($prep_string);
			// 	if($hashed_id == $id)
			// 	{
			// 		$id = $attendance_period_dtl_id['attendance_period_dtl_id'];
			// 		break;
			// 	}
			// }
			$field                 = array("*") ;
			$table                 = $this->attendance->tbl_attendance_period_dtl;
			$where                 = array();
			$key                   = $this->get_hash_key('attendance_period_dtl_id');
			$where['attendance_period_dtl_id']           = $id;
			$mra_summary = $this->attendance->get_general_data($field, $table, $where, FALSE);
			//new working hours
			$sixty_minutes = 60;
			$work_hours = $mra_summary['basic_hours'];
			$total_hours_deduc = $mra_summary['tardiness_hr'] + $mra_summary['undertime_hr'];
			$total_mins_deduc = $mra_summary['undertime_min'] + $mra_summary['tardiness_min'];
			$work_hours = $work_hours - $total_hours_deduc;
			$work_hours = $work_hours * $sixty_minutes;
			$work_hours = $work_hours - $total_mins_deduc;
			$total_hours_worked = intval($work_hours/ $sixty_minutes);
			$total_minutes_worked = fmod($work_hours, $sixty_minutes);
			$mra_summary['working_hours'] = $total_hours_worked. " Hours and ". $total_minutes_worked . " Minutes";
			$data['mra_summary'] = $mra_summary;
				// echo"<pre>";
				// print_r($data);
				// die();
			$field                 = array("*") ;
			$table                 = $this->attendance->tbl_param_attendance_status;
			$where                 = array();
			$data['status'] = $this->attendance->get_general_data($field, $table, $where, TRUE);

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
		$this->load->view('attendance_period/modals/modal_update_employee_mra', $data);
		$this->load_resources->get_resource($resources);
	}
	public function process_employee_daily_mra()
	{
		try
		{			
			$status 		= FALSE;
			$message		= "";

			$params                   = get_params();
			$action                   = $params['action'];
			$token                    = $params['token'];
			$salt                     = $params['salt'];
			$id                       = $params['id'];
			$module                   = $params['module'];
			$attendance_period_hdr_id = $params['attendance_period_hdr_id'];
			$employee_id              = $params['employee_id'];
			if(EMPTY($action) OR EMPTY($id) OR EMPTY($token) OR EMPTY($salt) OR EMPTY($module) OR EMPTY($attendance_period_hdr_id) OR EMPTY($employee_id))
			{
				throw new Exception($this->lang->line('invalid_action'));
			}
			if($token != in_salt($id . '/' . $action  . '/' . $module  . '/' . $attendance_period_hdr_id  . '/' . $employee_id, $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*CHECK DATA VALIDATION*/
			$valid_data = $this->_validate_mra($params);
			
			Main_Model::beginTransaction();
			

			$fields                         = array();
			$fields['tardiness_hr']         = $valid_data["tardiness_hours"];
			$fields['tardiness_min']        = $valid_data["tardiness_min"];
			$fields['undertime_hr']         = $valid_data["undertime_hours"];
			$fields['undertime_min']        = $valid_data["undertime_min"];
			$total_hours_deduc = $valid_data["tardiness_hours"] + $valid_data["undertime_hours"];
			$total_mins_deduc = $valid_data["tardiness_min"] + $valid_data["undertime_min"];
			$total_deduc = $total_mins_deduc + ($total_hours_deduc * 60);
			$working_hours = ($params['basic_hours'] * 60) - $total_deduc;
			$fields['working_hours']        = $working_hours / 60;
			$fields['attendance_status_id'] = $valid_data["attendance_status_id"];

			
			/*GET PREVIOUS DATA*/
			$table        = $this->attendance->tbl_attendance_period_dtl;
			
			$field        = array("*") ;			
			$where        = array();
			$where['attendance_period_dtl_id']  = $id;
			$employee_mra = $this->attendance->get_general_data($field, $table, $where, FALSE);

			$where       = array();
			$where['attendance_period_dtl_id'] = $id;
			$this->attendance->update_general_data($table,$fields,$where);

			//$attendance_period = modules::load('main/attendance_period/');
			//$result     = $attendance_period->update_attendance_period_summary($employee_mra['attendance_period_hdr_id']);

			$audit_table[]  = $table;
			$audit_schema[] = DB_MAIN;
			$prev_detail[]  = array($employee_mra);
			$curr_detail[]  = array($fields);
			$audit_action[] = AUDIT_UPDATE;			
				
			$audit_activity = "Employee". $employee_id ." MRA has been updated.";

			$message = $this->lang->line('data_updated');

			$this->audit_trail->log_audit_trail($audit_activity, $module, $prev_detail, $curr_detail, $audit_action, $audit_table, $audit_schema);
			$status = true;
				
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
}


/* End of file Attendance_mra.php */
/* Location: ./application/modules/main/controllers/Attendance_mra.php */