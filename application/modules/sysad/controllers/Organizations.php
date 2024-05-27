<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Organizations extends SYSAD_Controller {
	
	private $module = MODULE_ORGANIZATION;
	private $table_id = 'organizations_table';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('orgs_model', 'om', TRUE);
	}
	
	public function index()
	{	
		$data 	   =  array();
		$resources =  array();
		
		$resources['load_css'] = array(CSS_DATATABLE, CSS_SELECTIZE);
		$resources['load_js']  = array(JS_DATATABLE, JS_SELECTIZE, 'tableExport','jquery.base64','html2canvas','jspdf/libs/sprintf','jspdf/jspdf','jspdf/libs/base64');
		$resources['datatable'] = array('table_id' => $this->table_id, 'path' => PROJECT_CORE . '/organizations/get_organization_list/','advanced_filter'=>true);
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "User Management"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/organizations";
		$key					= "Organizations"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/organizations";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('organizations', $data, $resources);
		
	}

	public function get_organization_list($sector_code=NULL)
	{
		try{
			$rows     = array();
			$params   = get_params();

			// $aColumns = array('a.org_code', 'a.org_parent', 'a.name', 'a.website', 'a.email','b.name as parent_org_name' );
			// $bColumns = array('a.name', 'b.name','a.website','a.email' );

			//NCOCAMPO:ADDED active_flag 10/25/2023:START
			$aColumns = array('a.org_code', 'a.org_parent', 'a.name', 'a.website', 'a.email','b.name as parent_org_name', "IF(c.active_flag = 'Y', 'ACTIVE', 'INACTIVE')  as active_flag"  );
			$bColumns = array('a.name', 'b.name','a.website','a.email', 'active_flag');
			//NCOCAMPO:ADDED active_flag 10/25/2023:END
			
			$organizations  = $this->om->get_org_list($aColumns, $bColumns, $params, $sector_code);

			$iFilteredTotal = $this->om->filtered_length($aColumns, $bColumns, $params);
			$iTotal 	    = $this->om->total_length();

			
			$keys			= array_keys($organizations);
			$last_key 		= array_pop($keys);	 

			foreach($organizations as $key => $val):
				$actions    = '';
				$id  	    = base64_url_encode($val['org_code']);
				$salt	    = gen_salt();
				$token      = in_salt($val['org_code'], $salt);
						 
				$del_action = 'content_delete(\'Organization\', \''.$id.'\');';	
				$href		= $id.'/'.$salt.'/'.$token;
				$actions   .= '<div class="table-actions">';
				$actions   .= '<a href="javascript:;" id="edit_programs" class="md-trigger edit tooltipped" data-tooltip="Edit" data-position="bottom" data-modal="modal_organizations" onclick="modal_init(\''.$href.'\');" ></a>';
				$actions   .= '<a href="javascript:;" onclick="'.$del_action.'" class="delete tooltipped" data-tooltip="Delete" data-position="bottom" data-delay="50"></a>';
				$actions   .= '</div>';
				
				if($last_key == $key):
					$resources['load_js'] = array('modalEffects');
					$actions.= $this->load_resources->get_resource($resources, TRUE);
				endif; 

				
				$rows[]     = array(
						$val['name'],
						$val['parent_org_name'],
						// $val['org_parent'],
						$val['website'],
						$val['email'],
						$val['active_flag'], //NCOCAMPO: ADDED active_flag to array
						$actions
				);

			endforeach;
	
			$output  = array(
					'aaData' 		=> $rows,
					'sEcho'		    => intval($_POST['sEcho']),
					'iTotalRecords' => $iTotal["cnt"],
					'iTotalDisplayRecords' => $iFilteredTotal["cnt"]
			);

			
				
			echo json_encode($output);
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function modal($id=NULL, $salt=NULL, $token=NULL)
	{
		try{
			$data 	   = array();
			$resources = array();
			$org_code  = 0;
			if( ! EMPTY($id) && ! EMPTY($salt) && ! EMPTY($token)):
				
				$org_code = base64_url_decode($id);
				
				// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
				check_salt($org_code, $salt, $token);
				
				$org_details = $this->om->get_org_details($org_code);

				
				$data['id']    = $id;
				$data['salt']  = $salt;
				$data['token'] = $token;
				$data['org_details'] = $org_details;
			endif;
			$field                          = array('*');
			$where                          = array();
			$table                          = $this->om->db_main.".".$this->om->tbl_param_office_types;
			$data['office_type']            = $this->om->get_general_data($field, $table, $where, TRUE);			
			$data['other_orgs']   = $this->om->get_other_orgs($org_code);
			$data['rcc_list']   = $this->om->get_rcc_list();
			$data['table_path']   = PROJECT_CORE . '/organizations/get_organization_list/';

			$this->load->view("modals/organizations", $data);

		}
		catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	public function save()
	{
		try{
			$status 	 = 0;
			$params 	 = get_params();
			
			// SERVER VALIDATION
			$this->_validate($params);

			// GET SECURITY VARIABLES
			$id	= $params['id'];
			$salt = $params['salt'];
			$token = $params['token'];
			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_table[] 	= $this->om->tbl_organizations;
			$audit_schema[]	= Base_Model::$schema_core;
			
			$org_short_name = $params['org_short_name'];
			
			if( ! EMPTY($id) && ! EMPTY($salt) && ! EMPTY($token)):
				$audit_action[] = AUDIT_UPDATE;
				
				$org_code   = base64_url_decode($id);
					
				// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
				check_salt($org_code, $salt, $token);
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[] = array($this->om->get_org_details($org_code));


				
				$this->om->update_org($params, $org_code);


				$fields                               = array();
				$where                                = array();
				$where['org_code']                    = $org_code;
				$fields['office_type_id']             = filter_var($params['office_type'], FILTER_SANITIZE_NUMBER_INT);
				$fields['responsibility_center_code'] = filter_var($params['responsibility_center_code'], FILTER_SANITIZE_STRING);
				$fields['active_flag'] 				  =filter_var( ! empty($params['active_flag']) ? $params['active_flag'] : NO, FILTER_SANITIZE_STRING);
				$table                                = $this->om->db_main.".".$this->om->tbl_param_offices;
				$this->om->update_general_data($table,$fields,$where);

				$msg = $this->lang->line('data_updated');
				//START : davcorrea: reflect office name change in employees work experience table
				$fields                               = array();
				$where                                = array();
				$where['active_flag']                 = strtoupper("Y");
				$where['employ_office_name']          = $prev_detail[0][0]['name'];
				$fields['employ_office_name']         = filter_var($params['org_name'], FILTER_SANITIZE_STRING);
				$table                                = $this->om->db_main.".".$this->om->tbl_employee_work_experiences;
				$this->om->update_general_data($table,$fields,$where);
				//===============END=======================
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = array($this->om->get_org_details($org_code));
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = $this->lang->line('audit_trail_update');
				
			else:
				$prev_detail[]	= array();
				$audit_action[] = AUDIT_INSERT;
				
				$org_code                             = $this->om->insert_org($params);

				$fields                               = array();
				$fields['org_code']                   = $org_code;
				$fields['office_type_id']             = filter_var($params['office_type'], FILTER_SANITIZE_NUMBER_INT);
				$fields['responsibility_center_code'] = filter_var($params['responsibility_center_code'], FILTER_SANITIZE_STRING);
				$fields['active_flag'] 				  = ! empty($params['active_flag']) ? $params['active_flag'] : NO;
				$table                                = $this->om->db_main.".".$this->om->tbl_param_offices;
				$this->om->insert_general_data($table,$fields);

				$msg = $this->lang->line('data_saved');
				
				// GET THE DETAIL AFTER INSERTING THE RECORD
				$curr_detail[]    = array($this->om->get_org_details($org_code));
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = $this->lang->line('audit_trail_add');
			endif;
			
			$activity = sprintf($activity, $org_short_name);
			
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
			
			SYSAD_Model::commit();
			$status = 1;
			
		}
		catch(PDOException $e)
		{
			SYSAD_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
			
		}
		catch(Exception $e)
		{
			SYSAD_Model::rollback();
			$msg = $this->rlog_error($e, TRUE);
		}
		
		echo json_encode(array(
			'status' 		=> $status, 
			'msg' 		=> $msg,
			'table_id' 	=> $this->table_id,
			'path' 		=> PROJECT_CORE . '/organizations/get_organization_list/'
		));
	}
	
	
	public function delete_organizations()
	{
		try
		{
			$status 	    = 0;
			$params 	    = get_params();
			$org_code       = base64_url_decode($params['param_1']);

			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_action[]	= AUDIT_DELETE;
			$audit_table[]	= $this->om->tbl_organizations;
			$audit_schema[]	= Base_Model::$schema_core;
			
			// GET THE DETAIL FIRST BEFORE DELETING THE RECORD
			$prev_detail[] = array($this->om->get_org_details($org_code));
			
			$this->om->delete_org($org_code);
			$msg = $this->lang->line('data_deleted');
			
			$curr_detail[] = array();
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = sprintf($this->lang->line('audit_trail_delete'), $prev_detail[0][0]['short_name']);
				
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
				
			SYSAD_Model::commit();
			$status = 1;
			
		}
		catch(PDOException $e)
		{
			SYSAD_Model::rollback();
		
			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			SYSAD_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
	
		echo json_encode(array(
			'flag' 		=> $status, 
			'msg' 		=> $msg, 
			'reload' 	=> 'datatable', 
			'table_id' 	=> $this->table_id,
			'path' 		=> PROJECT_CORE . '/organizations/get_organization_list/'
		));
	}
	
	private function _validate($params)
	{
		if( ! ISSET($params['org_short_name']) || EMPTY($params['org_short_name']))
			throw new Exception(sprintf($this->lang->line('is_required'), 'Organization Short Name'));
		
		if( ! ISSET($params['org_name']) || EMPTY($params['org_name']))
			throw new Exception(sprintf($this->lang->line('is_required'), 'Organization Name'));

		if( ! ISSET($params['tel_no']) || EMPTY($params['tel_no']))
			throw new Exception(sprintf($this->lang->line('is_required'), 'Telephone No.'));
		
		$org_details = $this->om->get_org_details($params['org_code']);
		
		if(EMPTY($params['id']) && EMPTY($params['salt']) && EMPTY($params['token'])){
			
			if( ! ISSET($params['org_code']) || EMPTY($params['org_code']))
				throw new Exception(sprintf($this->lang->line('is_required'), 'Organization Code')); 
			 
			if(! EMPTY($org_details))
				throw new Exception('Duplicate Organization Code');
			
		}
	}
	
}
