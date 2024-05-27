
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ta_leave_credit_cert extends Main_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('reports_ta_model', 'rm');
		$this->load->model('common_model', 'cm');
	}
	
	public function generate_report_data($params)
	{
		try
		{
			$data   = array();
			
			$employee_id          = $params['certified_by'];
			$data['certified_by'] = $this->cm->get_report_signatory_details($employee_id);
			// GET/STORES EMPLOYEE INFO
			$field                = array("CONCAT(first_name, IF((middle_name='NA' OR middle_name='N/A' OR middle_name='-' OR middle_name='/' OR middle_name IS NULL), '', CONCAT(' ', LEFT(middle_name,1), '.')), ' ', last_name, IF(ext_name=''  OR ext_name IS NULL, '', CONCAT(' ', ext_name))) full_name", "last_name", "gender_code", "civil_status_id") ;
			$table                = $this->rm->tbl_employee_personal_info;
			$where                = array();
			$where['employee_id'] = $params['employee_filtered'];
			$data['employee']     = $this->rm->get_reports_data($field, $table, $where, FALSE, NULL, NULL);
			// GET/STORES EMPLOYEE POSITION AND OFFICE
			$field                = array('MAX(employee_work_experience_id)','employ_position_name', 'employ_office_name');
			$table                = $this->rm->tbl_employee_work_experiences;
			$where                = array();
			$where['employee_id'] = $params['employee_filtered'];

			$data['details']      = $this->rm->get_reports_data($field, $table, $where, FALSE, NULL, NULL);

			// GET/STORES EMPLOYEE REGULAR LEAVES
			$field = array('B.leave_type_name', 'IFNULL(C.leave_balance, "0") leave_balance');
			$table = array(
				'main'	=> array(	
						'table' => $this->rm->DB_CORE.".".$this->rm->tbl_sys_param,
						'alias'	=> 'A'
				),
				't1' 	=> array(
						'table' 	=> $this->rm->tbl_param_leave_types,
						'alias' 	=> 'B',
						'type'		=> 'LEFT JOIN',
						'condition' => 'B.leave_type_id = A.sys_param_value'
				),
				't2'	=> array(
						'table'		=> $this->rm->tbl_employee_leave_balances,
						'alias'		=> 'C',
						'type'		=> 'LEFT JOIN',
						'condition' => 'B.leave_type_id = C.leave_type_id AND C.employee_id = ' . $params['employee_filtered']
				)				
					
			);
			$where                   = array();
			$where['sys_param_type'] = SYS_PARAM_TYPE_LEAVE_TYPE_REGULAR;
			$order_by                = array('B.sort_order' => 'ASC');
			$data['leave_regular']   = $this->rm->get_reports_data($field, $table, $where, TRUE, $order_by, NULL);
			
			// GET/STORES EMPLOYEE SPECIAL LEAVES
			$field = array('B.leave_type_name', 'SUM(IFNULL(C.leave_earned_used, "0")) leave_earned');
			$table = array(
				'main'	=> array(	
						'table' => $this->rm->DB_CORE.".".$this->rm->tbl_sys_param,
						'alias'	=> 'A'
				),
				't1' 	=> array(
						'table' 	=> $this->rm->tbl_param_leave_types,
						'alias' 	=> 'B',
						'type'		=> 'JOIN',
						'condition' => 'B.leave_type_id = A.sys_param_value AND B.cert_flag = "Y"'
				),
				't2'	=> array(
						'table'		=> $this->rm->tbl_employee_leave_details,
						'alias'		=> 'C',
						'type'		=> 'LEFT JOIN',
						'condition' => '(B.leave_type_id = C.leave_type_id OR B.leave_type_id = C.orig_leave_type_id) AND C.employee_id = ' . $params['employee_filtered']
				)				
			);
			$where                              = array();
			$where['sys_param_type']            = SYS_PARAM_TYPE_LEAVE_TYPE_SPECIAL;
			$where['C.leave_transaction_type_id'] = LEAVE_FILE_LEAVE;
			$group_by                           = array('B.leave_type_id');
			$where['C.leave_earned_used']       = array(0, array(">"));
			$data['leave_special']              = $this->rm->get_reports_data($field, $table, $where, TRUE, NULL, $group_by);

			$leave_last_date      = $this->rm->get_leave_last_trans_date($params['employee_filtered']);

			$data['leave_last_date'] = $leave_last_date['last_trans_date'];
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
		return $data;
	}
}