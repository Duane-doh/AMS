<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_quick_links extends Main_Controller {
	
	private $permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('common_model', 'common');		
		$this->load->model('pds_model', 'pds');	
		$this->load->model('reports_model', 'rm');
		
		$this->permission_module = MODULE_PAYROLL_GENERAL_PAYROLL;
	}
	
	
	public function modal_employee_pds($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
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

			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			$data['personal_info'] 	= $this->pds->get_employee_info($id);

			$field 						= array("A.identification_value","B.identification_type_name", "B.format") ;
			$tables = array(
				'main' => array(
					'table' => $this->common->tbl_employee_identifications,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->common->tbl_param_identification_types,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.identification_type_id = B.identification_type_id'
	 			)
			);
			$order_by                          = array("CASE WHEN A.identification_type_id = '" . BANKACCT_TYPE_ID . "' THEN 0 ELSE 1 END ,A.identification_type_id" => 'ASC');
			$where                             = array();
			$key                               = $this->get_hash_key('A.employee_id');
			$where[$key]                       = $id;
			$where['A.identification_type_id'] = array(array(TIN_TYPE_ID,SSS_TYPE_ID,GSIS_TYPE_ID,PAGIBIG_TYPE_ID,PHILHEALTH_TYPE_ID, BANKACCT_TYPE_ID), array("IN"));
			$data['identifications']           = $this->common->get_general_data($field, $tables, $where, TRUE ,$order_by);
			
			$field 						= array("B.photo") ;
			$tables = array(
				'main' => array(
					'table' => $this->common->tbl_associated_accounts,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->pds->db_core.".".$this->pds->tbl_users,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.user_id = B.user_id'
	 			)
			);
			$where          = array();
			$key            = $this->get_hash_key('A.employee_id');
			$where[$key]    = $id;
			$data['avatar'] = $this->common->get_general_data($field, $tables, $where, FALSE);
			
			$table = array(
				'main' 		=> array(
					'table'		=> $this->pds->tbl_employee_other_info,
					'alias'		=> 'A'
				),
				't1'		=> array(
					'table'     => $this->pds->tbl_param_other_info_types,
					'alias'     => 'B',
					'type'      => 'JOIN',
					'condition' => 'A.other_info_type_id = B.other_info_type_id'
				)
			);
			$where                             = array();
			$key                               = $this->get_hash_key('A.employee_id');
			$where[$key]                       = $id;
			$where['B.info_professional_flag'] = YES;
			$data['professional_info']         = $this->common->get_general_data(array('others_value'), $table, $where, FALSE);
			
			$this->load->view('payroll_quick_links/modals/modal_employee_pds', $data);
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
	public function modal_employee_deduction($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data                = array();
			$resources           = array();
			
			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*SWITCH MODULE AND OVERIDE TOKEN TO EDIT*/
			$module = MODULE_HR_DEDUCTIONS;
			$token  = in_salt($id  . '/' . $action  . '/' . $module, $salt);

			
			$data['personal_info'] 	= $this->pds->get_employee_info($id);
			

			$field = array("B.photo");

			$tables = array(
				'main' => array(
					'table' => $this->common->tbl_associated_accounts,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->pds->db_core.".".$this->pds->tbl_users,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.user_id = B.user_id'
	 			)
			);
			$where          = array();
			$key            = $this->get_hash_key('A.employee_id');
			$where[$key]    = $id;
			$data['avatar'] = $this->common->get_general_data($field, $tables, $where, FALSE);


			$where              = array();
			$where['module_id'] = $module;
			$employee_office    = $this->common->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->common->tbl_modules, $where, FALSE);
			
			$office_id = $data['personal_info']['employ_office_id'];

			if($employee_office['use_admin_office'] > 0)
			{
				$office_id = $data['personal_info']['admin_office_id'];
			}
			
			// GET CURRENT USER'S GRANTED OFFICES
			$user_offices = $this->session->userdata('user_offices');
			$user_offices = $user_offices[$module];
			// COMPARE IF THE SELECTED EMPLOYEE'S OFFICE IS IN THE LIST
			$data['has_permission'] = (in_array($office_id, explode(',', $user_offices)));

			/*OVERIDE ACCESS TO VIEW*/
			if($data['has_permission'] == false)
			{
				$action = ACTION_VIEW;
				$token = in_salt($id  . '/' . $action  . '/' . $module, $salt);
			}

			$data['action']      = $action;
			$data['employee_id'] = $id;
			$data['salt']        = $salt;
			$data['token']       = $token;
			$data['module']      = $module;
			
			$this->load->view('payroll_quick_links/modals/modal_employee_deduction', $data);
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
	public function modal_employee_compensation($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)
	{
		try
		{
			$data                = array();
			$resources           = array();
			

			
			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*SWITCH MODULE AND OVERIDE TOKEN TO EDIT*/
			$module = MODULE_HR_COMPENSATION;
			$token  = in_salt($id  . '/' . $action  . '/' . $module, $salt);

			$data['action']      = $action;
			$data['employee_id'] = $id;
			$data['salt']        = $salt;
			$data['token']       = $token;
			$data['module']      = $module;
			
			$data['personal_info'] 	= $this->pds->get_employee_info($id);

			$field 						= array("B.photo") ;
			$tables = array(
				'main' => array(
					'table' => $this->common->tbl_associated_accounts,
					'alias' => 'A'
				),
				't1'   => array(
					'table' => $this->pds->db_core.".".$this->pds->tbl_users,
					'alias' => 'B',
					'type'  => 'JOIN',
					'condition' => 'A.user_id = B.user_id'
	 			)
			);
			$where                           = array();
			$key                             = $this->get_hash_key('A.employee_id');
			$where[$key]                     = $id;
			$data['avatar']         = $this->common->get_general_data($field, $tables, $where, FALSE);

			

			$where              = array();
			$where['module_id'] = $module;
			$employee_office    = $this->common->get_general_data(array('use_admin_office'), DB_CORE.'.'.$this->common->tbl_modules, $where, FALSE);
			
			$office_id = $data['personal_info']['employ_office_id'];

			if($employee_office['use_admin_office'] > 0)
			{
				$office_id = $data['personal_info']['admin_office_id'];
			}
			
			// GET CURRENT USER'S GRANTED OFFICES
			$user_offices = $this->session->userdata('user_offices');
			$user_offices = $user_offices[$module];
			// COMPARE IF THE SELECTED EMPLOYEE'S OFFICE IS IN THE LIST
			$data['has_permission'] = (in_array($office_id, explode(',', $user_offices)));

			/*OVERIDE ACCESS TO VIEW*/
			if($data['has_permission'] == false)
			{
				$action = ACTION_VIEW;
				$token = in_salt($id  . '/' . $action  . '/' . $module, $salt);
			}

			$data['action']      = $action;
			$data['employee_id'] = $id;
			$data['salt']        = $salt;
			$data['token']       = $token;
			$data['module']      = $module;
			
			$this->load->view('payroll_quick_links/modals/modal_employee_compensation', $data);
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

	public function modal_employee_mra($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL, $employee_id = NULL, $attendance_period_hdr_id = NULL)
	{
		try
		{
			$data                = array();
			$resources           = array();
			
			if (empty($action) OR empty($id) OR empty($token) OR empty($salt) OR empty($module))
			{
				throw new Exception($this->lang->line('err_invalid_request'));
			}
			if ($token != in_salt($id . '/' . $action  . '/' . $module , $salt))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			/*SWITCH MODULE AND OVERIDE TOKEN TO EDIT*/
			// $module = MODULE_HR_DEDUCTIONS;
			// $token  = in_salt($id  . '/' . $action  . '/' . $module, $salt);
			$params = array();
			$params['mra_attendance_period'] = $attendance_period_hdr_id;
			$params['mra_employee'] = explode(',', $employee_id);

			$mra_details = $this->rm->get_monthly_report_of_attendance($params);

			$field                             	= array("DATE_FORMAT(date_from,'%m/%d/%Y') as date_from","DATE_FORMAT(date_to,'%m/%d/%Y') as date_to") ;
			$table                             	= $this->rm->tbl_attendance_period_hdr;
			$where                             	= array();
			$where["attendance_period_hdr_id"] 	= $params['mra_attendance_period'];
			$data['period_detail']				= $this->rm->get_reports_data($field, $table, $where, FALSE);

			$old_office       = "";
			$old_employee     = "";
			$office_counter   = 0;
			$employee_counter = 0;
			$detail_counter   = 0;

			$mra_array = array();

			foreach ($mra_details as $key => $mra) 
			{
				if($mra['name'] != $old_office)
				{
					$employee_counter = 0;
					$office_counter++;
					$old_office = $mra['name'];
					$mra_array[$office_counter]['office'] = $mra['name'];
				}
				if($mra['employee_name'] != $old_employee)
				{
					$old_employee = $mra['employee_name'];
					$employee_counter++;
					$mra_array[$office_counter]['employee'][$employee_counter]['employee_name'] = $mra['employee_name'];
					$mra_array[$office_counter]['employee'][$employee_counter]['mra_detail'][] = $mra;
				}
				else
				{
					$mra_array[$office_counter]['employee'][$employee_counter]['mra_detail'][] = $mra;
				}
					
			}
			$data['mra_array'] = $mra_array;

			/*ini_set('memory_limit', '512M'); // boost the memory limit if it's low
			$this->load->library('pdf');
			//Legal Size Paper
			$set_footer      = TRUE;
			$certificate	 = TRUE;
			$pdf 	= $this->pdf->load('utf-8', array(216,280));
			
			if($set_footer)
			{
				$generated_by = $this->session->userdata('name');
				$footer = '<hr><table width="100%">';
				$footer .= '<tr>';
				$footer .= '<td align="left"><font size="2"><b>Generated By : </b>'. $generated_by .'</font></td>';
				$footer .= '<td><font size="2"><b>Run Time : </b>'. date('m/d/Y g:i:s a') .'</font></td>';
				$footer .= '<td align="right"><font size="2">Page {PAGENO} of {nb}<font size="2"></td>';
				$footer .= '</tr></table>';
				
				$pdf->SetHTMLFooter($footer);
			}*/

			$this->load->view('forms/reports/'.REPORTS_TA_MONTHLY_ATTENDANCE, $data);
			
			
			// // GET CURRENT USER'S GRANTED OFFICES
			// $user_offices = $this->session->userdata('user_offices');
			// $user_offices = $user_offices[$module];
			// // COMPARE IF THE SELECTED EMPLOYEE'S OFFICE IS IN THE LIST
			// $data['has_permission'] = (in_array($office_id, explode(',', $user_offices)));

			// /*OVERIDE ACCESS TO VIEW*/
			// if($data['has_permission'] == false)
			// {
			// 	$action = ACTION_VIEW;
			// 	$token = in_salt($id  . '/' . $action  . '/' . $module, $salt);
			// }

			// $data['action']      = $action;
			// $data['employee_id'] = $id;
			// $data['salt']        = $salt;
			// $data['token']       = $token;
			// $data['module']      = $module;
			
			// $this->load->view('payroll_quick_links/modals/modal_employee_deduction', $data);
			// $this->load_resources->get_resource($resources);
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
	public function print_employee_mra()
	{
		try
		{
			
			$data            = array();				
			$params          = get_params();
			$generate_report = modules::load('main/reports_ta/ta_monthly_attendance');
			$data            = $generate_report->generate_report_data($params,false);

			$this->load->helper('html');
			ini_set('memory_limit', '512M'); // boost the memory limit if it's low
			$this->load->library('pdf');
			//Legal Size Paper
			$pdf 	= $this->pdf->load('utf-8', array(210,297));
			
			$html 	= $this->load->view('forms/reports/'.REPORTS_TA_MONTHLY_ATTENDANCE, $data, TRUE);
			$pdf->WriteHTML($html);
			$pdf->Output();
			
			
		}
		catch (PDOException $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		catch (Exception $e)
		{
			$message = $e->getMessage();
			echo $message;
			RLog::error($message);
		}
		
	}
}
/* End of file Payroll_quick_links.php */
/* Location: ./application/modules/main/controllers/Payroll_quick_links.php */