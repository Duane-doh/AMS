<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Files extends SYSAD_Controller {
	
	private $module = MODULE_FILE;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('files_model', 'files', TRUE);
	}
	
	public function index()
	{	
		$resources = array();
		
		$data["files"] = $this->files->get_latest_attachments();
		/*BREADCRUMBS*/
		$breadcrumbs 			= array();
		$key					= "System"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/files";
		$key					= "Files"; 
		$breadcrumbs[$key]		= PROJECT_CORE."/files";
		set_breadcrumbs($breadcrumbs, TRUE);
		$this->template->load('files', $data, $resources);
	}
	
	public function modal($id = NULL, $salt = NULL, $token = NULL, $file_version_id = NULL){
		
		try{
			$data = array();
			
			$resources['load_css'] = array(CSS_SELECTIZE);
			$load_js = array(JS_SELECTIZE, 'jquery.jscrollpane');
			
			if(!IS_NULL($id)){
				$id = base64_url_decode($id);
				$file_version_id = (!IS_NULL($file_version_id)) ? base64_url_decode($file_version_id) : NULL;
				
				// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
				check_salt($id, $salt, $token);
				
				$files = $this->files->get_latest_attachments($id, $file_version_id);
				$data["files"] = $files;
				
				if(!EMPTY($files["cy"]))
					$resources['single'] = array('file_budget_year' => $files["cy"]);
			}else{
				array_push($load_js,'jquery.uploadfile');
				$resources['upload'] = array(
					array('id' => 'file', 'page' => 'files', 'form_name' => 'upload_file_form' ,'path' => PATH_FILE_UPLOADS, 'allowed_types' => 'doc,docx,xls,xlsx,ppt,pptx,pdf,jpeg,jpg,png,gif,zip', 'default_img_preview' => 'image_preview.png', 'multiple' => 1, 'drag_drop' => 1, 'show_preview' => 1)
				);
			}
			
			$resources['load_js'] = $load_js;
			$this->load->view("modals/files", $data);
			$this->load_resources->get_resource($resources);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function modal_file_revision($id = NULL, $salt = NULL, $token = NULL, $file_version_id = NULL){
		
		try{
			$data = array();
			
			$id = base64_url_decode($id);
			$file_version_id = !IS_NULL($file_version_id) ? base64_url_decode($file_version_id) : NULL;
			
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
				
			$data["file"] = $this->files->get_latest_attachments($id, $file_version_id);
				
			$resources['upload'] = array(
				array('id' => 'file_version', 'page' => 'file_version', 'form_name' => 'upload_file_version_form' ,'path' => PATH_FILE_UPLOADS, 'allowed_types' => 'doc,docx,xls,xlsx,ppt,pptx,pdf,jpeg,jpg,png,gif', 'default_img_preview' => 'image_preview.png', 'show_preview' => 1)
			);
			
			$resources['load_css'] = array(CSS_SELECTIZE, CSS_LABELAUTY, CSS_UPLOAD);
			$resources['load_js'] = array(JS_SELECTIZE, JS_LABELAUTY, JS_UPLOAD, 'jquery.jscrollpane');
			
			$this->load->view("modals/files_version", $data);
			$this->load_resources->get_resource($resources);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function modal_version_list($id = NULL, $salt = NULL, $token = NULL){
		
		try{
			$data = array();
			
			$id = base64_url_decode($id);
			
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
				
			$data["file_version"] = $this->construct_file_version_list($id);
			
			$this->load->view("modals/file_version_list", $data);
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}	
	}
	
	public function load_file_version_list($id)
	{
		$list = $this->construct_file_version_list($id);
		
		echo json_encode($list);
	}
	
	public function construct_file_version_list($id)
	{
		$data = "";
		$i = 0;
		
		$file_version = $this->files->get_file_versions($id);
		
		foreach($file_version as $version): 
		  $i++;
		  $file_name = !EMPTY($display_name) ? $version['display_name'] : $version['file_name'];
		  $file_version_id = !IS_NULL($version['file_version_id']) ? base64_url_encode($version['file_version_id']) : NULL;
		  $file_id = base64_url_encode($version['file_id']);
		  
		  $file = $version['file_name'];
		  $path = PATH_TASK_UPLOADS . $file;
		  $ext = pathinfo($path, PATHINFO_EXTENSION);
		  
		  $data.='<li class="'.$ext.'">
				<div class="table-cell" style="width:90%">
				  <small class="font-semibold m-r-md">Version '.$version['version'].'</small> '.$file_name.'
				</div>
				<div class="table-cell right-align" style="width:10%">
				  <div class="actions">';
		  
		  if($i == 1){
			$data.='<a href="javascript:;" class="delete" onclick=\'content_delete("file", "'.$file_id.'", "'.$file_version_id.'|1")\'>&nbsp;</a>';
		  }
		  
		  $data.='</div></div></li>';
		  
		endforeach;
		
		return $data;
	}

	public function sort_by()
	{
		$params	= get_params();
		$order_by = array();
		
		$filter = explode("|",$params['filter_1']);
		$before_arr =array_slice($filter,0,count($filter)-1);

		$orig_filter = array_pop($filter);
		
		$order_by[$orig_filter] = $params['filter_2'];
		foreach($before_arr as $arr):
		  $order_by[$arr] = "ASC";
		endforeach;
		
		
		$list = $this->files->get_latest_attachments(NULL, NULL, $order_by);
		
		$previous_grouping = $class = '';
		$data = '';
		
		foreach($list as $file): 
		  $filename = $file['file_name'];
		  $display_name = !EMPTY($file['display_name']) ? $file['display_name'] : $file['file_name'];
		  $version = $file['version'];
		  
		  switch($orig_filter){
			case 'created_date':
				$current_grouping = date("F d", strtotime($file['created_date']));
			break;
			
			case 'file_name':
				$current_grouping = substr(strtoupper($display_name), 0, 1);
			break;
		  }
		  
		  $path = PATH_TASK_UPLOADS . $filename;
		  $ext = pathinfo($path, PATHINFO_EXTENSION);
		  
		  $id = $file['file_id'];
		  $file_version_id = !IS_NULL($file['file_version_id']) ? base64_url_encode($file['file_version_id']) : NULL;
		  $base_id = base64_url_encode($id);
		  $salt = gen_salt();
		  $token = in_salt($id, $salt);
		  $url = $base_id . '/' . $salt . '/' . $token . '/' . $file_version_id;
		  
		  if($ext == "JPEG" || $ext == "JPG" || $ext == "jpeg" || $ext == "jpg" || $ext == "png"){
			$image_path = base_url(). PATH_FILE_UPLOADS . $file['file_name'];
			list($width, $height) = @getimagesize($image_path);
			$class = ($width > $height) ? " landscape": " portrait";
		  }
		  
		  if(!EMPTY($previous_grouping) && $previous_grouping !== $current_grouping) 
			$data.= '</ul>';
		  
		  if($previous_grouping !== $current_grouping) 
			$data.= '<h5 class="page-content-title">'.$current_grouping.'</h5><ul class="list-grid file-type">';
		
		  $data .= '<li class="list-item">
			  <div class="'.$ext.' box-shadow">';
			  
			if($ext == "JPEG" || $ext == "JPG" || $ext == "jpeg" || $ext == "jpg" || $ext == "png"){
			  $data .= '<div class="'.$class.'">
		        <img src="'.$image_path.'"/>
			  </div>';
			}
			
			if($version > 1){
			  $data .= '<div class="list-counter">Version '.$version.'</div>';
			}
			
			$data .= '<div class="row m-b-n list-details">
				  <div class="col s9">
					<p class="truncate m-n">'.$display_name.'</p>
				  </div>
					
				  <div class="col s3">
					<a class="dropdown-button" href="#!" data-activates="dropdown'.$id.'"><i class="material-icons">more_vert</i></a>
				  </div>
				</div>
			  </div>
			  
			  <ul id="dropdown'.$id.'" class="dropdown-content box-shadow">
				<li><a href="#!" class="md-trigger" data-modal="modal_upload_file" onclick=\'modal_init("'.$url .'")\'><i class="material-icons">mode_edit</i> Edit</a></li>
				<li><a href="#!" class="md-trigger" data-modal="modal_file_version_list" onclick=\'version_list_modal_init("'.$base_id . '/' . $salt . '/' . $token .'")\'><i class="material-icons">add_to_photos</i> Versions</a></li>
				<li><a href="#!" onclick=\'content_delete("file", "'.$base_id.'", "'.$file_version_id.'")\'><i class="material-icons">delete</i> Delete</a></li>
				<li><a href="#!" class="file-version-trigger md-trigger" id="upload_version_'.$url.'" data-modal="modal_upload_file_revision"  onclick=\'revision_modal_init("'.$url.'")\'><i class="material-icons">backup</i> Upload a new version</a></li>
			  </ul>
			</li>'; 					
			  
		  $previous_grouping = $current_grouping;
		endforeach;
		
		$resources['load_js'] = array('materialize','modalEffects','classie');
		$data .= $this->load_resources->get_resource($resources, TRUE);			

		echo json_encode($data);
		
	}
		
	public function process()
	{
		try
		{
			$flag = 0;
			$params	= get_params();
			
			$action = (EMPTY($params['id']))? AUDIT_INSERT : AUDIT_UPDATE;
			
			// SERVER VALIDATION
			$this->_validate($params);
	
			// GET SECURITY VARIABLES
			$id	= filter_var($params['id'], FILTER_SANITIZE_STRING);
			$salt = $params['salt'];
			$token = $params['token'];
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
	
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_schema[]	= Base_Model::$schema_core;
				
			if(EMPTY($id))
			{
				$file_arr = "";
				foreach($params['multiple_file_name'] as $file):
					$audit_action[]	= AUDIT_INSERT;
					$prev_detail[] = array();
					
					if(ISSET($params['file_id']) && !EMPTY($params['file_id'])){
						// UPLOAD NEW FILE VERSIONS
						$audit_table[]	= $this->files->tbl_file_versions;
						$id = $this->files->insert_file_version($file, $params);
						
						// GET THE DETAIL AFTER INSERTING THE RECORD
						$curr_detail[] = array($this->files->get_file_version_details($id));	
					}else{
						// UPLOAD NEW FILE
						$audit_table[]	= $this->files->tbl_files;
						$id = $this->files->insert_files($file, $params);

						// GET THE DETAIL AFTER INSERTING THE RECORD
						$curr_detail[] = array($this->files->get_file_details($id));	
					}
					
					$file_arr.= ' ' . $file . ',';
				endforeach;
					
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s file has been added";
				$activity = sprintf($activity, trim(substr($file_arr, 0, -1)));
				
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
				
				$msg = $this->lang->line('data_saved');
			}
			else
			{
				$audit_table[]	= !EMPTY($params['file_version_id']) ? $this->files->tbl_file_versions : $this->files->tbl_files;
				$audit_action[]	= AUDIT_UPDATE;
				
				// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
				$prev_detail[] = !EMPTY($params['file_version_id']) ? $this->files->get_file_version_details($params['file_version_id']) : $this->files->get_file_details($id);
				
				$this->files->update_files($params);
				$msg = $this->lang->line('data_updated');
				
				// GET THE DETAIL AFTER UPDATING THE RECORD
				$curr_detail[] = !EMPTY($params['file_version_id']) ? $this->files->get_file_version_details($params['file_version_id']) : $this->files->get_file_details($id);
				
				// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
				$activity = "%s has been updated";
				$activity = sprintf($activity, $curr_detail[0][0]['file_name']);
				
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
			}
			
			SYSAD_Model::commit();
			$flag = 1;
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
	
		$info = array(
			"flag" => $flag,
			"msg" => $msg
		);
	
		echo json_encode($info);
	
	}
	
	private function _validate($params)
	{
		if(EMPTY($params['file_budget_year']))
			throw new Exception('Budget Year is required.');
	
	}
	
	public function delete_file()
	{
		try
		{
			$flag = 0;
			$params	= get_params();
				
			$action = AUDIT_DELETE;
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			$file_id = base64_url_decode($params['param_1']);
			$arr = explode( '|', $params['param_2']);
			$version_id = base64_url_decode($arr[0]);
			$is_modal = ISSET($arr[1]) ? $arr[1] : "";
			
			$file_version_id = (ISSET($params['param_2']) && !EMPTY($params['param_2'])) ? $version_id : NULL;
			
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_action[]	= AUDIT_DELETE;
			$audit_table[]	= !IS_NULL($file_version_id) ? $this->files->tbl_file_versions : $this->files->tbl_files;
			$audit_schema[]	= Base_Model::$schema_core;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[] = !IS_NULL($file_version_id) ? $this->files->get_file_version_details($file_version_id) : $this->files->get_file_details($file_id);
			
			$this->files->delete_file($file_id, $file_version_id);
			$msg = $this->lang->line('data_deleted');
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] = !IS_NULL($file_version_id) ? $this->files->get_file_version_details($file_version_id) : $this->files->get_file_details($file_id);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been deleted";
			$activity = sprintf($activity, $prev_detail[0][0]['file_name']);
				
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
			$flag = 1;
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
	
		$info = array(
			"flag" => $flag,
			"msg" => $msg
		);
		
		if(!IS_NULL($file_version_id) && !EMPTY($is_modal)){
			$info["reload"] = "list";
			$info["wrapper"] = "file_version_list";
			$info["path"] = base_url() . PROJECT_CORE . "/files/load_file_version_list/" . $file_id;
		}
		echo json_encode($info);
	}
	
	public function insert_file_version()
	{
		try
		{
			$flag = 0;
			$params	= get_params();
			
			$action = AUDIT_INSERT;
			
			// SERVER VALIDATION
			//$this->_validate($params);
	
			// GET SECURITY VARIABLES
			$id	= filter_var($params['file_id'], FILTER_SANITIZE_STRING);
			$salt = $params['salt'];
			$token = $params['token'];
	
			// CHECK IF THE SECURITY VARIABLES WERE CORRUPTED OR INTENTIONALLY EDITED BY THE USER
			check_salt($id, $salt, $token);
	
			// BEGIN TRANSACTION
			SYSAD_Model::beginTransaction();
			
			$audit_action[]	= AUDIT_INSERT;
			$audit_table[]	= $this->files->tbl_file_versions;
			$audit_schema[]	= Base_Model::$schema_core;
				
			$prev_detail[] = array();		
			$id = $this->files->insert_file_version($params);
			
			// GET THE DETAIL AFTER INSERTING THE RECORD
			$curr_detail[] = $this->files->get_file_version_details($id);	
			$file = $curr_detail[0][0]['file_name'];
			$msg = $this->lang->line('data_saved');
			
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s file has been added";
			$activity = sprintf($activity, $file);
			
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
			$flag = 1;
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
	
		$info = array(
				"flag" => $flag,
				"msg" => $msg
		);
	
		echo json_encode($info);
	}
}