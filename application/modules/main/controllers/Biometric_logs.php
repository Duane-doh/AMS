<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Biometric_logs extends Main_Controller {
	private $module = MODULE_TA_ATTENDANCE_LOGS;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('biometric_logs_model', 'biometric_logs');
		$this->load->model('daily_time_record_model', 'dtr');
	}
	//  davcorrea : START : date 10/17/2023 : date range filter
	// public function index()
	public function index($date_start = null, $date_end = null)
	
	{
		try
		{
			$data = $resources = array();
		
			// $resources['load_css'] 			= array(CSS_DATATABLE, CSS_SELECTIZE);
			// $resources['load_js'] 			= array(JS_DATATABLE, JS_SELECTIZE);
			// $resources['datatable'][]		= array('table_id' => 'dtr_file_list', 'path' => 'main/biometric_logs/get_dtr_file_list', 'advanced_filter' => TRUE);
			

			$resources['load_css'] 		= array(CSS_DATATABLE, CSS_SELECTIZE, CSS_DATETIMEPICKER);
			$resources['load_js'] 		= array(JS_DATATABLE, JS_SELECTIZE, JS_DATETIMEPICKER);
			if(!empty($date_start) AND !empty($date_end))
			{
				// decode parameter
				$date_start = base64_decode(urldecode($date_start));
				$date_end = base64_decode(urldecode($date_end));
				// display in filter
				$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
				$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');	
				// paramter in get_request
				$date_start = format_date($date_start,'Y-m-d');
				$date_end   = format_date($date_end,'Y-m-d');

				$resources['datatable'][] = array('table_id' => 'dtr_file_list', 'path' => 'main/biometric_logs/get_dtr_file_list/'.$date_start.'/'.$date_end.'', 'advanced_filter' => TRUE);
			}
			else
			{
				// default 1 month range
				$date_end  = date('Y/m/d');
				$date_start = date('Y/m/d', strtotime('-1 months', strtotime($date_end)));
	
				// default display in filter
				$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
				$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');
	
				$resources['datatable'][]		= array('table_id' => 'dtr_file_list', 'path' => 'main/biometric_logs/get_dtr_file_list', 'advanced_filter' => TRUE);
			
			}
			// davcorrea : END
			// $resources['datatable'][] = array('table_id' => 'attendance_table_list', 'path' => 'main/daily_time_record/get_employee_list/', 'advanced_filter' => TRUE);
			
			$resources['load_modal']		= array(
				'modal_dtr_upload'		=> array(
						'controller'	=> strtolower(__CLASS__),
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal',
						'multiple'		=> true,
						'height'		=> '300px',
						'size'			=> 'sm',
						'title'			=> 'Upload '.SUB_MENU_BIO_LOGS_UPLOAD
				),
				'modal_generate_attendance'		=> array(
						'controller'	=> strtolower(__CLASS__),
						'module'		=> PROJECT_MAIN,
						'method'		=> 'modal_generate_attendance',
						'multiple'		=> true,
						'height'		=> '350px',
						'size'			=> 'sm',
						'title'			=> 'Generate '.SUB_MENU_BIO_LOGS_UPLOAD
				),
				'modal_view_details'		=> array(
							'controller'	=> strtolower(__CLASS__),
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal',
							'multiple'		=> true,
							'height'		=> '300px',
							'size'			=> 'md',
							'title'			=> 'View '.SUB_MENU_BIO_LOGS_UPLOAD
					)
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
		/* BREADCRUMBS */
		$breadcrumbs        = array ();
		$key                = "Time & Attendance";
		$breadcrumbs [$key] = PROJECT_MAIN."/biometric_logs";
		$key                = "Attendance Logs";
		$breadcrumbs [$key] = PROJECT_MAIN."/biometric_logs";
		set_breadcrumbs ( $breadcrumbs, TRUE );
		$this->template->load('biometric_logs/biometric_upload', $data, $resources);
	}

	// public function get_dtr_file_list()
	// davcorrea: 10/13/2023 : include date range filter : start
	public function get_dtr_file_list($date_start = null, $date_end = null)
	// davcorrea : include date range filter : end
	{

		try
		{
			$params         = get_params();
			// davcorrea :10/17/2023 : reverse sort
			if($params['sSortDir_0'] == 'asc')
			{
				$params['sSortDir_0'] = "desc";
			}else
			{
				$params['sSortDir_0'] = "asc";
			}
			// END
			$aColumns		= array("*");
			$bColumns		= array("attendance_date","date_uploaded", "file_status_name");

			// $dtr_files		= $this->biometric_logs->get_biometric_files($aColumns, $bColumns, $params, $table);
			// davcorrea: 10/13/2023 : include date range fitler : start
			$data['fltr_dtr_start'] = format_date($date_start,'Y/m/d');
			$data['fltr_dtr_end']   = format_date($date_end,'Y/m/d');
			$params['date_start'] = $date_start;
			$params['date_end'] = $date_end;
			
			// davcorrea: 10/13/2023 : include date range fitler : end
			$table 	  		= $this->biometric_logs->tbl_dtr_upload_hdr;
			$dtr_files		= $this->biometric_logs->get_biometric_files($aColumns, $bColumns, $params, $table);
			$iTotal   		= $this->biometric_logs->total_length();
		
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => count($dtr_files),
				"iTotalDisplayRecords" => $iTotal["cnt"],
				"aaData" => array()
			);
			$cnt = 0;

			foreach ($dtr_files as $aRow):
				$cnt++;
				$row = array();
				$action = "<div class='table-actions'>";
			
				$id 			= $aRow["dtr_upload_hdr_id"];
				$id 			= $this->hash ($id);
				$salt 			= gen_salt();

				//VIEW DETAILS URL
				$view_token 	= in_salt($id . '/' . ACTION_VIEW, $salt);
				$view_url		= ACTION_VIEW . '/' . $id . '/' . $salt . '/' . $view_token;
				
				//PROCESS URL
				/*$process_token 	= in_salt($id . '/' . ACTION_PROCESS, $salt);
				$process_url	= ACTION_PROCESS . '/' . $id . '/' . $salt . '/' . $process_token;
				*/
				
				$row[] =  '<center>' . $aRow["attendance_date"] . '</center>';
				$row[] =  '<center>' . $aRow["date_uploaded"] . '</center>';
				$row[] =  strtoupper($aRow["file_status_name"]);
				

				$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View Details' data-position='bottom' data-modal='modal_view_details' onclick=\"modal_view_details_init('modal_view_details/".$view_url."')\" data-delay='50'></a>";
				
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

	public function get_dtr_temp_sub_list()
	{

		try
		{
			$params 		= get_params();
			
			$aColumns		= array("*");
			$bColumns		= array("B.file_name", "C.terminal_code","A.date_uploaded");
			$table 	  		= $this->biometric_logs->tbl_dtr_upload_sub;
			$hdr_id			= $id;
			$dtr_temp_sub 	= $this->biometric_logs->get_biometric_sub($aColumns, $bColumns, $params);

			$field       = array("COUNT(DISTINCT(dtr_upload_sub_id)) as count");
			$table       = $this->biometric_logs->tbl_dtr_upload_sub;
			$where       = array();
			$key         = $this->get_hash_key('dtr_upload_hdr_id');
			$where[$key] = $id;
			$iTotal      = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
			
			$output = array(
				"sEcho" => intval($_POST['sEcho']),
				"iTotalRecords" => $iTotal["count"],
				"iTotalDisplayRecords" => count($dtr_temp_sub),
				"aaData" => array()
			);
			$cnt = 0;

			//GET NUMBER OF TERMINAL
			$table 			 = $this->biometric_logs->tbl_param_terminal;
			$field 			 = array("count(*) cnt");
			$num_of_terminal = $this->biometric_logs->get_biometric_log($field, $table, array(), FALSE);
			$match_terminal	 = false;
			if($num_of_terminal['cnt'] == count($dtr_temp_sub))
				$match_terminal = true;

			foreach ($dtr_temp_sub as $aRow):
				$cnt++;
				$row = array();
				$action = "<div class='table-actions'>";
			
				$id 			= $aRow["dtr_upload_sub_id"];
				$id 			= $this->hash ($id);
				$hdr_id 		= $this->hash ($aRow["dtr_upload_hdr_id"]);
				$salt 			= gen_salt();

				//VIEW DETAILS URL
				$view_token 	= in_salt($id . '/' . ACTION_VIEW, $salt);
				$view_url		= ACTION_VIEW . '/' . $id . '/' . $salt . '/' . $view_token;
				

				//EDIT
				$token_delete 	= in_salt($id . '/' . ACTION_DELETE, $salt);		
				$url_delete 	= ACTION_DELETE."/".$id."/".$salt."/".$token_delete;
				$delete_action	= 'content_delete("dtr_files", "'.$url_delete.'","'.$hdr_id.'")';

				
				$row[] =  $aRow['file_name'];
				$row[] =  $aRow['terminal_code'];
				$row[] =  $aRow['date_uploaded'];
				
				//$action .= "<a href='#' class='view tooltipped md-trigger' data-tooltip='View Details' title='View Details' data-position='bottom' data-modal='modal_view_file_data' onclick=\"modal_view_file_data_init('modal_view_file_data/".$view_url."')\" data-delay='50'></a>";
				$action .= "<a href='".base_url(). PATH_BIOMETRIC_UPLOADS . $aRow["file_name"]."'  class='tooltipped save' data-tooltip='Download Data' title='Download Data' data-position='bottom' data-delay='50' target='_blank'></a>";
				
			/*	if(!$match_terminal)
					$action .= "<a href='javascript:;' onclick='" . $delete_action. "' class='delete tooltipped' title='Delete' data-tooltip='Delete' data-position='bottom' data-delay='50'></a>";
				*/
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

	public function modal($modal, $action_id = NULL, $id = NULL, $salt = NULL, $token = NULL)
	{
		try
		{
			$data = array();
			$resources['load_css'] 	= array('jquery-labelauty', 'selectize.default', 'jquery.datetimepicker');
			$resources['load_js'] 	= array('jquery-labelauty', 'selectize', 'jquery.datetimepicker','jquery.jscrollpane');
			switch ($modal) 
			{
				case 'modal_dtr_upload':
					$data['id']				= $id;
					$resources['load_js'][]	= 'jquery.uploadfile';
					$resources['upload'] 	= array(
						array('id' => 'file', 'page' => 'files', 'form_name' => 'upload_file_form' ,'path' => PATH_FILE_UPLOADS, 'allowed_types' => 'doc,docx,xls,xlsx,ppt,pptx,pdf,jpeg,jpg,png,gif', 'default_img_preview' => 'image_preview.png', 'multiple' => 1, 'drag_drop' => 1, 'show_preview' => 1)
					);
					$view_modal 		= $modal;
					$file_type 			= get_sysparam_value(ATTENDANCE_LOG_FORMAT);
					$data['file_type'] 	= ! empty($file_type['sys_param_value']) ? strtolower($file_type['sys_param_value']) : 'otm';
				break;
				case 'modal_view_details':
					$post_data                = array('header_id' => $id);
					$resources['datatable'][]	= array('table_id' => 'dtr_temp_sub_list', 'path' => 'main/biometric_logs/get_dtr_temp_sub_list', 'advanced_filter' => TRUE,'post_data' => json_encode($post_data));
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;
					$resources['load_modal']		= array(
						'modal_view_file_data'		=> array(
							'controller'	=> strtolower(__CLASS__),
							'module'		=> PROJECT_MAIN,
							'method'		=> 'modal',
							'multiple'		=> true,
							'height'		=> '300px',
							'size'			=> 'sm',
							'title'			=> 'View '.SUB_MENU_BIO_LOGS_UPLOAD . ' Data'
						)
					);	
					$resources['load_delete'] 		= array(
						__CLASS__,
						'delete_sub_upload',
						PROJECT_MAIN
					);
					$data['id']			= $id;

					//GET CURRENT NUMBER OF TERMINAL
					$field 				= array("*");
					$table 			 	= $this->biometric_logs->tbl_dtr_upload_sub;
					$where				= array();
					$key 				= $this->get_hash_key('dtr_upload_hdr_id');
					$where[$key]		= $id;
					$dtr_temp_sub	= $this->biometric_logs->get_biometric_log($field, $table, $where, TRUE);

					//GET NUMBER OF TERMINAL
					$table 			 = $this->biometric_logs->tbl_param_terminal;
					$field 			 = array("count(*) cnt");
					$num_of_terminal = $this->biometric_logs->get_biometric_log($field, $table, array(), FALSE);
					$match_terminal	 = false;

					if($num_of_terminal['cnt'] == count($dtr_temp_sub))
						$match_terminal = true;

					$data['match_terminal'] = $match_terminal;
					$view_modal 			= $modal;
				break;
				case 'modal_view_file_data':
					$resources['load_css'][] 	= CSS_DATATABLE;
					$resources['load_js'][] 	= JS_DATATABLE;

					$field 						= array("*");
					$table 			 			= $this->biometric_logs->tbl_dtr_upload_sub;
					$biometric_temp_sub 		= $this->biometric_logs->get_biometric_log($field, $table, array(), TRUE);
					
					$view_modal 		= $modal;
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
		$this->load->view('biometric_logs/modals/' . $view_modal, $data);
		$this->load_resources->get_resource($resources);
	}

	public function process_upload(){
	
		try{
			$status        = false;
			$message       = $this->lang->line('data_not_saved');
			$params        = get_params();
			
			$show_log      = FALSE;
			$log_content   = "\t\t\t BIOMETRIC UPLOAD LOGS \r\n\n\n";
			$log_content   .= "*--------------------------------";
			$log_content   .= "---------------------------------*\r\n\n\n";
			$file_count    = 0;
			$success_count = 0;
			$header_added  = false;

			if(!EMPTY($params['attachment']))
			{
				sort($params['attachment']);
				$this->load->helper('file');
				$field_hdr       = array();
				$field_data      = array();
				$field_sub       = array();
				$uploaded        = 0;
				$total_files     = count($params['attachment']);
				$table           = $this->biometric_logs->tbl_param_terminal;
				$field           = array("count(*) cnt");
				$num_of_terminal = $this->biometric_logs->get_biometric_log($field, $table, array(), FALSE);

				
				$biolog_dates_array = array();

				/*GET ALL DATES TO UPLOAD*/
				foreach ($params['attachment'] as $key => $attachment) 
				{
					$orignial_file_name			= explode('_',$attachment);
					$biometric_files 			= PATH_BIOMETRIC_UPLOADS.$attachment;
					$biometric_data 			= read_file($biometric_files);
					$biometric_data				= str_replace(PHP_EOL, '', $biometric_data);
					$biometric_array			= explode('<', $biometric_data);

					$date_to_upload =  '20' . substr($biometric_array[0],6,2) . '-' . substr($biometric_array[0],4,2) . '-' . substr($biometric_array[0],2,2);
					
					$biolog_dates_array[] = $date_to_upload;
				}

		
				Main_Model::beginTransaction();
				foreach ($params['attachment'] as $key => $attachment) 
				{
					$file_count++;
					// BEGIN TRANSACTION
					$orignial_file_name			= explode('_',$attachment);
					$biometric_files 			= PATH_BIOMETRIC_UPLOADS.$attachment;
					$biometric_data 			= read_file($biometric_files);
					$biometric_data				= str_replace(PHP_EOL, '', $biometric_data);
					$biometric_array			= explode('<', $biometric_data);
					
					$date_to_upload =  '20' . substr($biometric_array[0],6,2) . '-' . substr($biometric_array[0],4,2) . '-' . substr($biometric_array[0],2,2);

					$date_before = date('Y-m-d',strtotime("-1 day", strtotime ( $date_to_upload ) ) );	

					

					$error_checker = FALSE;
					$log_content   .= "File Name :   ".$orignial_file_name[0]."\r\n\n\n";
					if(count($biometric_array) <= 1 OR strlen($biometric_array[0]) != 20 OR !is_numeric($biometric_array[0]))
					{
						$show_log      = TRUE;
						$log_content   .= "Error : Uploaded File is invalid. \r\n\n";
						$log_content   .= "Upload Status :   FAIL\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
						$error_checker = TRUE;
					}
					else
					{

						if($header_added == false)
						{
							$field_hdr = array();
							$field_hdr['attendance_date'] = 20 . substr($biometric_array[0],6,2) . '-' . substr($biometric_array[0],4,2) . '-' . substr($biometric_array[0],2,2);
							$field_hdr['terminal_count']  = $num_of_terminal['cnt']; 
							$field_hdr['date_uploaded']   = date('Y-m-d'); 
							$field_hdr['file_status_id']  = BIOMETRIC_RAW_DATA;
						
							$table 			= $this->biometric_logs->tbl_dtr_upload_hdr;
							$hdr_id = $this->biometric_logs->insert_biometric_log($table, $field_hdr, true);

							//SET AUDIT TRAIL DETAILS
							$audit_table[]	= $table;
							$audit_schema[]	= DB_MAIN;
							$audit_action[]	= AUDIT_INSERT;
							
							$prev_detail[]	= array();
							$curr_detail[] = array($field_hdr);	
							$header_added = true;

						}
						
						//GET CURRENT NUMBER OF TERMINAL
						$terminal_code			= substr(trim($biometric_array[0]),0,2);
						$field                  = array("*");
						$table                  = $this->biometric_logs->tbl_param_terminal;
						$where                  = array();
						$where["terminal_code"] = $terminal_code;
						$terminal           	= $this->biometric_logs->get_biometric_log($field, $table, $where, false);


						//SUB
						$field_sub                      = array();
						$field_sub['dtr_upload_hdr_id'] = $hdr_id;
						$field_sub['file_name']         = $attachment;
						$field_sub['terminal_id']       = $terminal['terminal_id'];
					
						$table 			= $this->biometric_logs->tbl_dtr_upload_sub;
						$sub_id = $this->biometric_logs->insert_biometric_log($table, $field_sub, true);

						$audit_table[]  = $table;
						$audit_schema[] = DB_MAIN;
						$audit_action[] = AUDIT_INSERT;
						
						$prev_detail[]  = array();
						$curr_detail[]  = array($field_sub);	
						
						$line_num       = 1;
						$total_data     = count($biometric_array);
						$field_data     =  array();
						foreach ($biometric_array as $value) 
						{
							$value	= trim($value);

							if($total_data != $line_num AND (!is_numeric($value) OR strlen($value) != 20))
							{
								$log_content               .= "Error :".sprintf($this->lang->line('invalid_file_line'), $line_num)."\r\n\n";
								$error_checker             = TRUE;
							}
								
							$terminal_number 		= substr($value,0,2);
							$date 			 		= substr($value,2,2);
							$month 			 		= substr($value,4,2);
							$year 			 		= 20 . substr($value,6,2);
							$time 			 		= substr($value,8,2) . ":" . substr($value,10,2);
							$pin					= substr($value,12,8);
							/*if($pin)
							{
								$table                  = $this->biometric_logs->tbl_employee_personal_info;
								$field                  = array("count(employee_id) as cnt");
								$where                  = array();
								$where['biometric_pin'] = $pin;
								$has_pds            = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
								RLog::error('PIN :'.$pin);

								if($has_pds['cnt'] <= 0)
								{
									$log_content   .= "Biometric PIN not found : ".$pin."\r\n\n";
									$error_checker = TRUE;
								}
							}*/
							

							if(!empty($terminal_number))
							{
								$field_data[]			=  array(
								'dtr_upload_sub_id'	=> $sub_id,
								'terminal_number'	=> $terminal_number,
								'biometric_id'	 	=> $pin,
								'date'				=> $year . '-' . $month . '-' . $date,
								'time'				=> $time,
								);
							}
							
							$line_num++;
						}
						if($field_data)
						{
							$table 			= $this->biometric_logs->tbl_dtr_temp_upload_data;
							$this->biometric_logs->insert_biometric_log($table, $field_data);
						
							/*$audit_table[]	= $table;
							$audit_schema[]	= DB_MAIN;
							$audit_action[]	= AUDIT_INSERT;
							$prev_detail[]	= array();
							$curr_detail[] = $field_data;	*/
						}
						
						$activity = "%s has been added";
						$activity = sprintf($activity, 'Raw Biometric log');
		
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
						
					
					// COMMINT TRANSACTION
					if($error_checker)
					{
						// Main_Model::rollback();
						$show_log = TRUE;
						$log_content .= "Upload Status :   FAIL\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
					}
					else
					{
						// Main_Model::commit();
						$success_count++;
						$log_content .= "Upload Status :   SUCCESS\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
					}
					
					
				}
				$hash_hdr_id = $this->hash($hdr_id);
				/*PROCESS BIOMETRIC LOGS*/
				RLog::error('PROCESS CHECK!!');

				$uploaded_dates = $this->_process_biometric_logs($hash_hdr_id);

				/*POPULATE DATAS TO attendance_period_dtl table*/
				$this->_process_attendance_period_dtl($uploaded_dates);

				Main_Model::commit();
			}
			else
			{
				throw new Exception('Upload File is required.');
			}
			
			$status  = true;
			$message = "Biometric upload complete.";
			if($show_log)
			{

				
				$this->load->helper('file');
				$path = PATH_BIOMETRIC_UPLOAD_ERROR_LOGS;		
				$name = 'BIOMETRIC UPLOAD ERROR LOG -' . date('Y-m-d his') . '.txt' ;
				$error_log = $name;
				if(!is_dir($path))
				{
				  mkdir($path,0777,TRUE);
				}
				$path .= $name;
				write_file($path , $log_content);

			}

			
			if($success_count != $file_count AND $success_count > 0)
			{
				$status = true;
				$message = $success_count . " out of ".$file_count." files were successfully uploaded.<br> Please see log file to view the errors.";
			}
			elseif($success_count == 0)
			{
				$status = false;
				$message = "Failed to upload all files.<br> Please see log file to view the errors.";
			}
			elseif($success_count == $file_count)
			{
				$status = true;
				$message = $success_count . " out of ".$file_count." files were successfully uploaded.";
			}
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			Main_Model::rollback();
			RLog::error($message);
			//$message = $this->lang->line('err_internal_server');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data				= array();
		$data['status']		= $status;
		$data['message']	= $message;
		$data['file_name']	= $error_log;
		$data['show_log']	= $show_log;
	
		echo json_encode($data);
	}

	public function process_csv_upload()
	{
		try
		{
			$status        = false;
			$message       = $this->lang->line('data_not_saved');
			$params        = get_params();
			
			$show_log      = FALSE;
			$log_content   = "\t\t\t BIOMETRIC UPLOAD LOGS \r\n\n\n";
			$log_content   .= "*--------------------------------";
			$log_content   .= "---------------------------------*\r\n\n\n";
			$file_count    = 0;
			$success_count = 0;
			$index_start   = 0;

			$file_type_arr = get_sysparam_value(ATTENDANCE_LOG_FORMAT);
			$file_type 	   = ! empty($file_type_arr['sys_param_value']) ? strtolower($file_type_arr['sys_param_value']) : '';

			if ( $file_type != $params['file_type'] )
				throw new Exception("Cannot upload file. Invalid file extension", 1);

			if(!EMPTY($params['attachment']))
			{
				$this->load->helper('file');
				$field_hdr       = array();
				$field_data      = array();
				$field_sub       = array();
				$uploaded        = 0;
				$total_files     = count($params['attachment']);
				$table           = $this->biometric_logs->tbl_param_terminal;
				$field           = array("count(*) cnt");
				$num_of_terminal = $this->biometric_logs->get_biometric_log($field, $table, array(), FALSE);
				
				$biolog_dates_array = array();
		
				Main_Model::beginTransaction();
				foreach ($params['attachment'] as $key => $attachment) 
				{
					$file_count++;
					// BEGIN TRANSACTION
					$orignial_file_name	= explode('_',$attachment);
					$biometric_files 	= PATH_BIOMETRIC_UPLOADS.$attachment;
					$biometric_data 	= read_file($biometric_files);
					$biometric_array	= explode(PHP_EOL, $biometric_data);

					$biometric_str = array();

					foreach ($biometric_array as $key => $biometric)
						$biometric_str[$key] = explode(',', $biometric);		

					$error_checker = FALSE;
					$log_content   .= "File Name :   ".$orignial_file_name[0]."\r\n\n\n";

					if(count($biometric_str) <= 1 OR count($biometric_str[0]) != 9 )
					{
						$show_log      = TRUE;
						$log_content   .= "Error : Uploaded File is invalid. \r\n\n";
						$log_content   .= "Upload Status :   FAIL\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
						$error_checker = TRUE;
					}
					else
					{ 
						if ( strpos($biometric_str[0][0], 'Index' ) !== false )
						{
							unset($biometric_str[0]);
							$index_start = 1;
						}

						$field_hdr					  = array();
						$field_hdr['attendance_date'] = date('Y-m-d', strtotime($biometric_str[$index_start][3])); 
						$field_hdr['terminal_count']  = $num_of_terminal['cnt']; 
						$field_hdr['date_uploaded']   = date('Y-m-d'); 
						$field_hdr['file_status_id']  = BIOMETRIC_RAW_DATA;					
						$table 						  = $this->biometric_logs->tbl_dtr_upload_hdr;
						$hdr_id 					  = $this->biometric_logs->insert_biometric_log($table, $field_hdr, true);

						//SET AUDIT TRAIL DETAILS
						$audit_table[]	= $table;
						$audit_schema[]	= DB_MAIN;
						$audit_action[]	= AUDIT_INSERT;						
						$prev_detail[]	= array();
						$curr_detail[]  = array($field_hdr);

						//GET CURRENT NUMBER OF TERMINAL
						$terminal_code			= trim($biometric_str[$index_start][8]);
						$field                  = array("*");
						$table                  = $this->biometric_logs->tbl_param_terminal;
						$where                  = array();
						$where["terminal_code"] = $terminal_code;
						$terminal           	= $this->biometric_logs->get_biometric_log($field, $table, $where, false);

						
						
						//SUB
						$field_sub                      = array();
						$field_sub['dtr_upload_hdr_id'] = $hdr_id;
						$field_sub['file_name']         = $attachment;
						$field_sub['terminal_id']       = $terminal['terminal_id'];
						$table 							= $this->biometric_logs->tbl_dtr_upload_sub;
						$sub_id 						= $this->biometric_logs->insert_biometric_log($table, $field_sub, true);

						$audit_table[]  = $table;
						$audit_schema[] = DB_MAIN;
						$audit_action[] = AUDIT_INSERT;						
						$prev_detail[]  = array();
						$curr_detail[]  = array($field_sub);	
						
						$line_num       = 1;
						$total_data     = count($biometric_str);
						$field_data     = array();
						
						foreach ($biometric_str as $biometric) 
						{
							if ( ! empty($biometric[0]) )
							{
								if($total_data != $line_num AND (count($biometric) != 9))
								{
									$log_content  .= "Error :".sprintf($this->lang->line('invalid_file_line'), $line_num)."\r\n\n";
									$error_checker = TRUE;
								}
									
								$terminal_number 		= trim($biometric[8]);
								$date 			 		= date('Y-m-d', strtotime($biometric[3]));
								$time 			 		= date('H:i:s', strtotime($biometric[3]));
								$biometric_id			= trim($biometric[1]);

								switch (trim($biometric[4])) 
								{
									case 'Check in':
										$time_flag = FLAG_TIME_IN;
										break;
									case 'Check out':
										$time_flag = FLAG_TIME_OUT;
										break;
									case 'Break in':
										$time_flag = FLAG_BREAK_IN;
										break;
									case 'Break out':
										$time_flag = FLAG_BREAK_OUT;
										break;								
									default:
										$time_flag = NULL;
										break;
								}
								
								if( ! empty( $terminal_number ) )
								{
									$field_data[]			=  array(
										'dtr_upload_sub_id'	=> $sub_id,
										'terminal_number'	=> $terminal_number,
										'biometric_id'	 	=> $biometric_id,
										'date'				=> $date,
										'time'				=> $time,
										'time_flag'			=> $time_flag
									);
								}
								
								$line_num++;
							}

							/* jendaigo: remove to avoid multiple upload of data
							if ( $field_data )
							{
								$table = $this->biometric_logs->tbl_dtr_temp_upload_data;
								$this->biometric_logs->insert_biometric_log($table, $field_data);
							}
							
							$activity = "%s has been added";
							$activity = sprintf($activity, 'Raw Biometric log');
			
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
							*/
						}
						// ====================== jendaigo : start : moved to avoid multiple data entry to tbl_dtr_temp_upload_data ============= //

						if ( $field_data )
						{					
							$table = $this->biometric_logs->tbl_dtr_temp_upload_data;
							$this->biometric_logs->insert_biometric_log($table, $field_data);


							$activity = "%s has been added";
							$activity = sprintf($activity, 'Raw Biometric log');
			
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
						// ====================== jendaigo : end : moved to avoid multiple data entry to tbl_dtr_temp_upload_data ============= //
					}
						
					
					// COMMINT TRANSACTION
					if($error_checker)
					{
						// Main_Model::rollback();
						$show_log = TRUE;
						$log_content .= "Upload Status :   FAIL\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
					}
					else
					{
						// Main_Model::commit();
						$success_count++;
						$log_content .= "Upload Status :   SUCCESS\r\n\n\n";
						$log_content   .= "*--------------------------------";
						$log_content   .= "---------------------------------*\r\n\n\n";
					}
					
					
				}
				$hash_hdr_id = $this->hash($hdr_id);

				/*PROCESS BIOMETRIC LOGS*/
				RLog::error('PROCESS CHECK!!');

				$uploaded_dates = $this->_process_biometric_csv_logs($hash_hdr_id);

				/*POPULATE DATAS TO attendance_period_dtl table*/
				$this->_process_attendance_period_dtl($uploaded_dates);

				Main_Model::commit();
			}
			else
			{
				throw new Exception('Upload File is required.');
			}
			
			$status  = true;
			$message = "Biometric upload complete.";
			if($show_log)
			{				
				$this->load->helper('file');
				$path = PATH_BIOMETRIC_UPLOAD_ERROR_LOGS;		
				$name = 'BIOMETRIC UPLOAD ERROR LOG -' . date('Y-m-d his') . '.txt' ;
				$error_log = $name;
				if(!is_dir($path))
				{
				  mkdir($path,0777,TRUE);
				}
				$path .= $name;
				write_file($path , $log_content);

			}
			
			if($success_count != $file_count AND $success_count > 0)
			{
				$status = true;
				$message = $success_count . " out of ".$file_count." files were successfully uploaded.<br> Please see log file to view the errors.";
			}
			elseif($success_count == 0)
			{
				$status = false;
				$message = "Failed to upload all files.<br> Please see log file to view the errors.";
			}
			elseif($success_count == $file_count)
			{
				$status = true;
				$message = $success_count . " out of ".$file_count." files were successfully uploaded.";
			}
		}
		catch(PDOException $e){
			$message = $e->getMessage();
			Main_Model::rollback();
			RLog::error($message);
			//$message = $this->lang->line('err_internal_server');
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			$message = $e->getMessage();
		}
	
		$data				= array();
		$data['status']		= $status;
		$data['message']	= $message;
		$data['file_name']	= $error_log;
		$data['show_log']	= $show_log;
	
		echo json_encode($data);
	}
	
	private function _process_biometric_csv_logs($id)
	{
		try
		{
			// Initialize variables			
			$info 			= array();
			$uploaded_dates = array();

			// Tables
			$tbl_employee_attendance  = $this->biometric_logs->tbl_employee_attendance;
			$tbl_dtr_upload_hdr       = $this->biometric_logs->tbl_dtr_upload_hdr;
			$tbl_dtr_temp_upload_data = $this->biometric_logs->tbl_dtr_temp_upload_data;

			// Get temp data
			$employee_attendance = $this->biometric_logs->get_csv_bio_temp_data($id);	
			foreach ($employee_attendance as $attendance) 
			{
				// Get dates
				$uploaded_dates[] = $attendance['date'];
				// Reset fields
				$fields = array();

				// START: Populate time
				$attendance_time   = explode(",", $attendance['attendance_time']);
				$employee_id 	   = ! empty($attendance['employee_id']) ? $attendance['employee_id'] : 0;
				$attendance_date   = ! empty($attendance['date']) ? $attendance['date'] : NULL;
				$dtr_upload_hdr_id = ! empty($attendance['dtr_upload_hdr_id']) ? $attendance['dtr_upload_hdr_id'] : 0;

				// Build data per time flag
				$time_arr = array();
				foreach ($attendance_time as $time) 
				{
					$time_str = explode('|', $time);
					if ( $time_str[0] == FLAG_TIME_IN )
						$time_arr[FLAG_TIME_IN][] = $time_str[1];
					if ( $time_str[0] == FLAG_TIME_OUT )
						$time_arr[FLAG_TIME_OUT][] = $time_str[1];
					if ( $time_str[0] == FLAG_BREAK_IN )
						$time_arr[FLAG_BREAK_IN][] = $time_str[1];
					if ( $time_str[0] == FLAG_BREAK_OUT )
						$time_arr[FLAG_BREAK_OUT][] = $time_str[1];
				}

				// Get earliest time per flag
				$time_in = '';
				$time_out = '';
				$break_in = '';
				$break_out = '';
				foreach ($time_arr as $key => $time) 
				{
					if ( $key == FLAG_TIME_IN )
						$time_in = min($time);
					if ( $key == FLAG_TIME_OUT )
						$time_out = min($time);
					if ( $key == FLAG_BREAK_IN )
						$break_in = min($time);
					if ( $key == FLAG_BREAK_OUT )
						$break_out = min($time);
				}
				// END: Populate time

				// START: Insert employee_attendance
				if ( ! empty($time_in) )
				{
					// Get data if attendance already exists
					// $field                    = array('edited_flag', 'employee_attendance_id');
					$field                    = array('edited_flag', 'employee_attendance_id','time_log');// davcorrea : included time log to verify prev data before updating
					$where                    = array();
					$where['employee_id']     = $employee_id;
					$where['attendance_date'] = $attendance_date;
					$where['time_flag']       = FLAG_TIME_IN;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $tbl_employee_attendance, $where, FALSE);

					// Construct time_log
					$date_time = $attendance_date . ' ' . $time_in;
					if ( $previous_log )
					{
						//davcorrea : validate if uploaded time is less than the saved time log : START
						$prev_time_log = explode(" ", $previous_log['time_log']);
						// Update data if not yet edited
						if ( $previous_log['edited_flag'] == NO )
						{
							if($time_in < $prev_time_log[1])
							{
								$field = array('time_log' => $date_time);
								$where = array('employee_attendance_id' => $previous_log['employee_attendance_id']);
								$this->biometric_logs->update_biometric_log($tbl_employee_attendance, $field, $where);
							}
						}
						// END
					}
					else
					{
						$fields[] 			  = array(
							'reference_id'    => $dtr_upload_hdr_id,
							'employee_id'     => $employee_id,
							'attendance_date' => $attendance_date,
							'time_flag'       => FLAG_TIME_IN,
							'time_log'        => $date_time
						);
					}
				}

				if ( ! empty($time_out) )
				{
					// Get data if attendance already exists
					// $field                    = array('edited_flag', 'employee_attendance_id');
					$field                    = array('edited_flag', 'employee_attendance_id','time_log');// davcorrea : included time log to verify prev data before updating
					$where                    = array();
					$where['employee_id']     = $employee_id;
					$where['attendance_date'] = $attendance_date;
					$where['time_flag']       = FLAG_TIME_OUT;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $tbl_employee_attendance, $where, FALSE);

					// Construct time_log
					$date_time = $attendance_date . ' ' . $time_out;

					if ( $previous_log )
					{
						//davcorrea : validate if uploaded time is less than the saved time log : START
						$prev_time_log = explode(" ", $previous_log['time_log']);
						// Update data if not yet edited
						if ( $previous_log['edited_flag'] == NO )
						{

							if($time_out < $prev_time_log[1])
							{
							$field = array('time_log' => $date_time);
							$where = array('employee_attendance_id' => $previous_log['employee_attendance_id']);
							$this->biometric_logs->update_biometric_log($tbl_employee_attendance, $field, $where);
						}
					}
					// END					
					}
					else
					{
						$fields[] 			  = array(
							'reference_id'    => $dtr_upload_hdr_id,
							'employee_id'     => $employee_id,
							'attendance_date' => $attendance_date,
							'time_flag'       => FLAG_TIME_OUT,
							'time_log'        => $date_time
						);
					}
				}

				if ( ! empty($break_in) )
				{
					// Get data if attendance already exists
					// $field                    = array('edited_flag', 'employee_attendance_id');
					$field                    = array('edited_flag', 'employee_attendance_id','time_log');// davcorrea : included time log to verify prev data before updating
					$where                    = array();
					$where['employee_id']     = $employee_id;
					$where['attendance_date'] = $attendance_date;
					$where['time_flag']       = FLAG_BREAK_IN;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $tbl_employee_attendance, $where, FALSE);

					// Construct time_log
					$date_time = $attendance_date . ' ' . $break_in;

					if ( $previous_log )
					{
						//davcorrea : validate if uploaded time is less than the saved time log : START
						$prev_time_log = explode(" ", $previous_log['time_log']);
						// Update data if not yet edited
						if ( $previous_log['edited_flag'] == NO )
						{
							if($break_in < $prev_time_log[1])
							{
							$field = array('time_log' => $date_time);
							$where = array('employee_attendance_id' => $previous_log['employee_attendance_id']);
							$this->biometric_logs->update_biometric_log($tbl_employee_attendance, $field, $where);
						}
					}
					// END					
					}
					else
					{
						$fields[] 			  = array(
							'reference_id'    => $dtr_upload_hdr_id,
							'employee_id'     => $employee_id,
							'attendance_date' => $attendance_date,
							'time_flag'       => FLAG_BREAK_IN,
							'time_log'        => $date_time
						);
					}
				}

				if ( ! empty($break_out) )
				{
					// Get data if attendance already exists
					// $field                    = array('edited_flag', 'employee_attendance_id');
					$field                    = array('edited_flag', 'employee_attendance_id','time_log');// davcorrea : included time log to verify prev data before updating
					$where                    = array();
					$where['employee_id']     = $employee_id;
					$where['attendance_date'] = $attendance_date;
					$where['time_flag']       = FLAG_BREAK_OUT;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $tbl_employee_attendance, $where, FALSE);

					// Construct time_log
					$date_time = $attendance_date . ' ' . $break_out;

					if ( $previous_log )
					{
						//davcorrea : validate if uploaded time is less than the saved time log : START
						$prev_time_log = explode(" ", $previous_log['time_log']);
						// Update data if not yet edited
						if ( $previous_log['edited_flag'] == NO )
						{
							if($break_out < $prev_time_log[1])
							{
							$field = array('time_log' => $date_time);
							$where = array('employee_attendance_id' => $previous_log['employee_attendance_id']);
							$this->biometric_logs->update_biometric_log($tbl_employee_attendance, $field, $where);
						}
					}
					// END					
					}
					else
					{
						$fields[] 			  = array(
							'reference_id'    => $dtr_upload_hdr_id,
							'employee_id'     => $employee_id,
							'attendance_date' => $attendance_date,
							'time_flag'       => FLAG_BREAK_OUT,
							'time_log'        => $date_time
						);
					}
				}
			
				// if ( $fields )
				// 	$this->biometric_logs->insert_biometric_log($tbl_employee_attendance, $fields, false);
				// $where                      = array();
				// $where['biometric_id']      = $attendance['biometric_id'];
				// $where['date']              = $attendance_date;
				// $where['dtr_upload_sub_id'] = array($attendance['dtr_upload_sub_id'], array("!="));
				// $this->biometric_logs->delete_biometric_log($tbl_dtr_temp_upload_data, $where);

				// Resolved raw data logs OverWriting - davcorrea - 09/26/2023
				// =================START====================================
				if ( $fields )
					$this->biometric_logs->insert_biometric_log($tbl_employee_attendance, $fields, false);
				$time_flag = explode("|", $attendance['attendance_time']);	

				$where                      = array();
				$where['biometric_id']      = $attendance['biometric_id'];
				$where['date']              = $attendance_date;
				$where['dtr_upload_sub_id'] = array($attendance['dtr_upload_sub_id'], array("!="));
				$where['time_flag'] = $time_flag[0];
				// davcorrea : START : retain time raw time log 10/23/2023
				$where['time'] = $time_flag[1];
				// davcorrea : END
				$this->biometric_logs->delete_biometric_log($tbl_dtr_temp_upload_data, $where);
				// ==============================END==========================================
			}			
			
			// Set where
			$where			= array();
			$key 			= $this->get_hash_key('dtr_upload_hdr_id');
			$where[$key]	= $id;
			
			// Get prev detail
			$prev_dtr_dtl	= $this->biometric_logs->get_biometric_log(array("*"), $tbl_dtr_upload_hdr, $where, TRUE);
			$prev_detail[]	= $prev_dtr_dtl;
			
			// Update 
			$fields 		= array("file_status_id" => BIOMETRIC_PROCESSED);
			$this->biometric_logs->update_biometric_log($tbl_dtr_upload_hdr, $fields, $where);

			// Get current detail
			$curr_detail[]	= $this->biometric_logs->get_biometric_log(array("*"), $tbl_dtr_upload_hdr, $where, TRUE);		

			// Set audit trail
			$audit_table[]	= $tbl_dtr_upload_hdr;
			$audit_schema[]	= DB_MAIN;
			$audit_action[]	= AUDIT_INSERT;

			$activity = "%s has been processed.";
			$activity = sprintf($activity, 'Attendance Logs');
	
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

			return array_unique($uploaded_dates);
		}
		catch(PDOException $e)
		{			
			throw $e;
		}
		catch(Exception $e)
		{			
			throw $e;
		}		
	}

	public function delete_sub_upload()
	{
		try
		{
			$params 		= get_params();
			$security_data 	= explode("/", $params['param_1']);
			$action  		= $security_data[0];
			$id  			= $security_data[1];
			$salt  			= $security_data[2];
			$token  		= $security_data[3];
			$hdr_id 		= $params['param_2'];
			$flag 			= 0;

			RLog::debug($hdr_id);
			if (EMPTY ( $action ) or EMPTY ( $id ) or EMPTY ( $salt ) or EMPTY ( $token ))
				throw new Exception ( $this->lang->line ( 'err_unauthorized_access' ) );
			if ($token != in_salt ( $id . '/' . $action, $salt ))
				throw new Exception ( $this->lang->line ( 'err_invalid_request' ) );

			
			$flag = 0;
			$params	= get_params();
				
			$action = AUDIT_DELETE;
				
			// BEGIN TRANSACTION
			Main_Model::beginTransaction();
			//DELETE UPLOAD DATA FIRST
			$table 				= $this->biometric_logs->tbl_dtr_temp_upload_data;
			$where				= array();
			$key 				= $this->get_hash_key('dtr_upload_sub_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$prev_detail[]		= $this->biometric_logs->get_biometric_log(array("*"), $table, $where, TRUE);
			
			$this->biometric_logs->delete_biometric_log($table, $where);
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->biometric_logs->get_biometric_log(array("*"), $table, $where, TRUE);

			//DELETE UPLOAD SUB
			$table 				= $this->biometric_logs->tbl_dtr_upload_sub;
			$where				= array();
			$key 				= $this->get_hash_key('dtr_upload_sub_id');
			$where[$key]		= $id;

			$audit_action[]		= AUDIT_DELETE;
			$audit_table[]		= $table;
			$audit_schema[]		= DB_MAIN;
	
			// GET THE DETAIL FIRST BEFORE UPDATING THE RECORD
			$sub_hdr			= $this->biometric_logs->get_biometric_log(array("*"), $table, $where, TRUE);
			$prev_detail[]		= $sub_hdr;

			$this->biometric_logs->delete_biometric_log($table, $where);
				
			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[] 		= $this->biometric_logs->get_biometric_log(array("*"), $table, $where, TRUE);
				
			// ACTIVITY TO BE LOGGED ON THE AUDIT TRAIL
			$activity = "%s has been deleted";
			$activity = sprintf($activity, $sub_hdr[0][0]['file_name']);
	
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
			$msg  = $this->lang->line('data_deleted');				
		}
		catch(PDOException $e)
		{
			Main_Model::rollback();
		
			$msg = $this->rlog_error($e, TRUE);
		}
		catch(Exception $e)
		{
			Main_Model::rollback();
			
			$msg = $this->rlog_error($e, TRUE);
		}
		$post_data                = array('hdr_id' => $hdr_id);
		$info = array(
			"flag" 		=> $flag,
			"msg" 		=> $msg,
			"reload" 	=> 'datatable',
			"table_id" 	=> 'dtr_temp_sub_list',
			"path"		=> PROJECT_MAIN . '/biometric_logs/get_dtr_temp_sub_list/',
			'post_data' => json_encode($post_data)
		);
	
		echo json_encode($info);
	}

	private function _process_biometric_logs($id)
	{
		try
		{
			
			$info	= array();

			/*
				This array is used in populating datas to attendance_period_dtl table
			*/
			$uploaded_dates = array();

			$employee_attendance 	= $this->biometric_logs->get_bio_tem_data($id);
			
			//INSERT DATA EMPLOYEE ATTENDANCE
			foreach ($employee_attendance as $key => $value) 
			{

				/*GET DATES INSERTED*/
				$uploaded_dates[] = $value['date'];
				/*RESET FIELDS*/
				$fields    = array();
				
				
				$time       = explode(",", $value['attendance_time']);
				
				if(!EMPTY($time[0]))
				{
					//CHECK PREVIOUS ATTENDANCE
					$field                    = array("edited_flag");
					$table                    = $this->biometric_logs->tbl_employee_attendance;
					$where                    = array();
					$where['employee_id']     = $value['employee_id'];
					$where['attendance_date'] = $value['date'];
					$where['time_flag']       = FLAG_TIME_IN;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
					if($previous_log)
					{

						//UPDATE ATTENDANCE
						if($previous_log['edited_flag'] == NO)
						{
							$field = array("time_log" => $value['date']. ' '.$time[0]);
							$this->biometric_logs->update_biometric_log($table, $field, $where);
						}
						
					}
					else
					{
						$fields[] = array(
							'reference_id'    => $value['dtr_upload_hdr_id'],
							'employee_id'     => $value['employee_id'],
							'attendance_date' => $value['date'],
							'time_flag'       => FLAG_TIME_IN,
							'time_log'        => $value['date']. ' '.$time[0]
						);
					}
					
				}
				
				if(!EMPTY($time[1]))
				{
					//CHECK PREVIOUS ATTENDANCE
					$field                    = array("*");
					$table                    = $this->biometric_logs->tbl_employee_attendance;
					$where                    = array();
					$where['employee_id']     = $value['employee_id'];
					$where['attendance_date'] = $value['date'];
					$where['time_flag']       = FLAG_BREAK_OUT;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
					if($previous_log)
					{
						//UPDATE ATTENDANCE
						if($previous_log['edited_flag'] == NO)
						{
							$field = array("time_log" => $value['date']. ' '.$time[1]);
							$this->biometric_logs->update_biometric_log($table, $field, $where);
						}
					}
					else
					{
						$fields[] = array(
								'reference_id'    => $value['dtr_upload_hdr_id'],
								'employee_id'     => $value['employee_id'],
								'attendance_date' => $value['date'],
								'time_flag'       => FLAG_BREAK_OUT,
								'time_log'        => $value['date']. ' '.$time[1]
							);
					}	
				}
				if(!EMPTY($time[2]))
				{
					//CHECK PREVIOUS ATTENDANCE
					$field                    = array("*");
					$table                    = $this->biometric_logs->tbl_employee_attendance;
					$where                    = array();
					$where['employee_id']     = $value['employee_id'];
					$where['attendance_date'] = $value['date'];
					$where['time_flag']       = FLAG_BREAK_IN;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
					if($previous_log)
					{
						//UPDATE ATTENDANCE
						if($previous_log['edited_flag'] == NO)
						{
							$field = array("time_log" => $value['date']. ' '.$time[2]);
							$this->biometric_logs->update_biometric_log($table, $field, $where);
						}
					}
					else
					{
						$fields[] = array(
								'reference_id'    => $value['dtr_upload_hdr_id'],
								'employee_id'     => $value['employee_id'],
								'attendance_date' => $value['date'],
								'time_flag'       => FLAG_BREAK_IN,
								'time_log'        => $value['date']. ' '.$time[2]
							);
					}
				}
				if(!EMPTY($time[3]))
				{
					//CHECK PREVIOUS ATTENDANCE
					$field                    = array("*");
					$table                    = $this->biometric_logs->tbl_employee_attendance;
					$where                    = array();
					$where['employee_id']     = $value['employee_id'];
					$where['attendance_date'] = $value['date'];
					$where['time_flag']       = FLAG_TIME_OUT;
					$previous_log             = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
					if($previous_log)
					{
						//UPDATE ATTENDANCE
						if($previous_log['edited_flag'] == NO)
						{
							$field = array("time_log" => $value['date']. ' '.$time[3]);
							$this->biometric_logs->update_biometric_log($table, $field, $where);
						}
					}
					else
					{
						$fields[] = array(
								'reference_id'    => $value['dtr_upload_hdr_id'],
								'employee_id'     => $value['employee_id'],
								'attendance_date' => $value['date'],
								'time_flag'       => FLAG_TIME_OUT,
								'time_log'        => $value['date']. ' '.$time[3]
							);
					}
				}
				
				if($fields)	
				{
					$table 	= $this->biometric_logs->tbl_employee_attendance;
					$this->biometric_logs->insert_biometric_log($table,$fields,false);
				}
				$time_flag = explode("|", $attendance['attendance_time']);	
				
				$where                      = array();
				$where['biometric_id']      = $value['biometric_id'];
				$where['date']              = $value['date'];
				$where['dtr_upload_sub_id'] = array($value['dtr_upload_sub_id'], array("!="));
				$table                      = $this->biometric_logs->tbl_dtr_temp_upload_data;

				$this->biometric_logs->delete_biometric_log($table, $where);
			}		
			
			$table     = $this->biometric_logs->tbl_dtr_upload_hdr;

			//SET AUDIT TRAIL DETAILS
			$audit_table[]	= $table;
			$audit_schema[]	= DB_MAIN;
			$audit_action[]	= AUDIT_INSERT;
			
			//SET WHERE VALUE
			$where			= array();
			$key 			= $this->get_hash_key('dtr_upload_hdr_id');
			$where[$key]	= $id;
			
			//GET CURRENT DATA BEFORE UPDATING THE RECORD
			$prev_dtr_dtl	= $this->biometric_logs->get_biometric_log(array("*"),$table,$where,TRUE);
			$prev_detail[]	= $prev_dtr_dtl;
			
			//SET FIELDS AND UPDATE THE RECORD
			$fields = array("file_status_id" => BIOMETRIC_PROCESSED);
			$this->biometric_logs->update_biometric_log($table, $fields, $where);

			// GET THE DETAIL AFTER UPDATING THE RECORD
			$curr_detail[]	= $this->biometric_logs->get_biometric_log(array("*"),$table,$where,TRUE);
			
			
			$activity = "%s has been processed.";
			$activity = sprintf($activity, 'Attendance Logs');
	
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

			return array_unique($uploaded_dates);
		}
		catch(PDOException $e)
		{
			
			throw $e;
		}
		catch(Exception $e)
		{
			
			throw $e;
		}
		
	}

	private function _process_attendance_period_dtl($attendance_dates)
	{
		try
		{
			if($attendance_dates)
			{
				$employee_list 	= $this->biometric_logs->get_attendance_dtl_employees();

				$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
				$field                   = array("*") ;
				$where                   = array();
				$where['sys_param_type'] = "WORKING_HOURS";
				
				$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);

				foreach ($attendance_dates as $dates) {
					$period_dtls = array();

					foreach ($employee_list as $emp) {

						$field                    = array("employee_id") ;
						$where                    = array();
						$where['employee_id']     = $emp['employee_id'];
						$where['attendance_date'] = $dates;
						$table                    = $this->biometric_logs->tbl_attendance_period_dtl;
						
						$has_prev_record           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
						
						// ====================== jendaigo : start : addt'l condition for attendance_status_id tagging ============= //
						$work_schedule 			= $this->biometric_logs->get_employee_work_schedule($emp['employee_id'],$dates);
						$attendance_day         = strtolower(date('D',strtotime($dates)));

						if($emp['start_date'] > $work_schedule['start_date'])
						{
							if($attendance_day == 'sat' OR $attendance_day == 'sun' )
								$attendance_status		= ATTENDANCE_STATUS_REST_DAY;
							else
								$attendance_status		= ATTENDANCE_STATUS_ABSENT;
						}
						else
						{
							$attendance_status		= ATTENDANCE_STATUS_REGULAR_DAY;
						}
						// ====================== jendaigo : end : addt'l condition for attendance_status_id tagging ============= //
						
						if(EMPTY($has_prev_record))
						{
							$period_dtls[] = array(
								"employee_id"          => $emp['employee_id'],
								"attendance_date"      => $dates,
								"basic_hours"          => $working_hours['sys_param_value'],
								"working_hours"        => 0,
								"tardiness"            => 0,
								"tardiness_hr"         => 0,
								"tardiness_min"        => 0,
								"undertime"            => 0,
								"undertime_hr"         => 0,
								"undertime_min"        => 0,
								// "attendance_status_id" => ATTENDANCE_STATUS_REGULAR_DAY
								"attendance_status_id" => $attendance_status //jendaigo: change to variable
								);
						}

					}
					if($period_dtls)
					{
						$table = $this->biometric_logs->tbl_attendance_period_dtl;
						$this->biometric_logs->insert_biometric_log($table,$period_dtls,FALSE);
					}
					
				}
			}
			return true;
						
		}
		catch(PDOException $e)
		{
			
			throw $e;
		}
		catch(Exception $e)
		{
			
			throw $e;
		}
		
	}

	public function check_work_sched($employee_id,$date)
	{
		try
		{
			$work_schedule = $this->biometric_logs->get_employee_work_schedule($employee_id,$date);
			if(empty($work_schedule))
			{
				return "FALSE";
			}
			else
			{
				return "TRUE";
			}

		}
		catch(PDOException $e)
		{
			
			throw $e;
		}
		catch(Exception $e)
		{
			
			throw $e;
		}
		
	}
	public function check_attendance($employee_id,$date)
	{
		try
		{	
			
			$data = array();
			
			$day_off           = false;
			$earliest_in       = '';
			$latest_in         = '';
			$data['tardiness'] = 0;
			$data['undertime'] = 0;
			$break_hours       = 0;
			$break_time        = 0;

			$work_schedule = $this->biometric_logs->get_employee_work_schedule($employee_id,$date);
			
			$day           = strtolower(date('D',strtotime($date)));

			$day_off = (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;
			if($day_off == true)
			{
				$data['status'] = ATTENDANCE_STATUS_REST_DAY;
			}

			//===== MARVIN : INCLUDE 10, 12, 16 AND 24 HOURS DUTY : START =====
			if($work_schedule[$day . '_type_of_duty'] == 8)
			{
				if($work_schedule)
				{
					$break_hours = $work_schedule['break_hours'];
					$break_time = $work_schedule['break_time'];
					
					$earliest_in   = $work_schedule[$day.'_earliest_in'];
					$latest_in     = $work_schedule[$day.'_latest_in'];
	
					$day_off = (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;
							
				}
				else
				{
					$data['status'] = ATTENDANCE_STATUS_ABSENT;
					return $data;
				}
				
				if($day_off == true)
				{
					$data['status'] = ATTENDANCE_STATUS_REST_DAY;
				}
				else
				{
	
					$attendance_log = $this->biometric_logs->get_employee_attendance($employee_id,$date);
					
					/*IF EMPLOYEE HAS NO ATTENDANCE, CHECK IF DATE IS HOLIDAY*/
					$tables = array(
						'main'	=> array(
							'table'		=> $this->biometric_logs->tbl_param_work_calendar,
							'alias'		=> 'A',
						),
						't1'	=> array(
							'table'		=> $this->biometric_logs->tbl_param_holiday_types,
							'alias'		=> 'B',
							'type'		=> 'join',
							'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
						)
					);
					$where                 = array();
					$where['A.holiday_date'] = $date;
					$holiday               = $this->biometric_logs->get_biometric_log(array("*"), $tables, $where, FALSE);
					$_early_suspension_types = array(3,5,6);

					
					if($holiday AND !in_array($holiday['holiday_type_id'], $_early_suspension_types))
					{
						$data['status'] = $holiday['attendance_status_id'];
					}
					else
					{
						if($attendance_log['attendance_date'])
						{
							$sixty_seconds       = 60;
							$sixty_minutes       = 60;
							$eight_working_hours = 8;
	
							if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
							{
								$has_break           = ($break_hours > 0 ? TRUE : FALSE);
								$earliest_in         = $date.' '.$earliest_in;
								$latest_in           = $date.' '.$latest_in;
								$break_time          = $date.' '.$break_time;
								
								$minutes_to_add   = $break_hours * 60;
								
								$new_time         = new DateTime($break_time);
								$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
								
								$break_time_hours = $new_time->format('Y-m-d H:i:s');
								
								//GET HOLIDAY
								$where                 = array();
								$where['holiday_date'] = $date;
								$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
								
								//during working hours suspension flag
								$during_working_hours 		= false;					
								$during_early_suspension 	= false;	
								if(!empty($holiday2))
								{
									$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
									$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
									
									//EARLY SUSPENSION
									if($holiday2['holiday_type_id'] == '5')
									{
										$during_early_suspension = true;
										$earliest_in 	= $date . ' ' . '08:00:00';
										$latest_in 		= $date . ' ' . '08:00:00';
									}
									//DURING WORKING HOURS SUSPENSION
									if($holiday2['holiday_type_id'] == '6')
									{
										$during_working_hours = true;
										if(empty($attendance_log['time_in']) AND empty($attendance_log['time_out']))
										{
											$earliest_in 	= $date . ' ' . '08:00:00';
											$latest_in 		= $date . ' ' . '08:00:00';
										}
										else
										{
											$earliest_in 	= $attendance_log['time_in'];
											$latest_in 		= $attendance_log['time_in'];
										}
									}
									
									if($holiday_start_time > $break_time_hours)
									{
										$eight_working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes) - $break_hours;
									}
									else
									{
										$eight_working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes);
									}			
								}
								
								if(!EMPTY($attendance_log['time_in']))
								{
									$time_in = $attendance_log['time_in'];
								}
								elseif (!EMPTY($attendance_log['break_out'])) {
									$time_in = $attendance_log['break_out'];
								}
								elseif (!EMPTY($attendance_log['break_in'])) {
									$time_in = $attendance_log['break_in'];
								}
								else
								{
									$time_in = $attendance_log['time_out'];
								}
								$time_in = date('Y-m-d H:i:s', strtotime($time_in));
								
	
								/*
								 * START : LATE COMPUTATION
								 */
								if($time_in > $latest_in)
								{
									if($has_break)
									{
										if($time_in < $break_time)
										{
											$tardiness         = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
										}elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
										{
											$tardiness         = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
										}
										else
										{
											$tardiness         = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
										}
									}
									else
									{
											$tardiness         = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
									}
									if($during_early_suspension)
									{
										$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);	
									}
								}
								
								/* additionational late computation with late break-in*/
								if($has_break)
								{	
									if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
									{
										$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
									}
								}
								$data['tardiness'] 		= ($tardiness/$sixty_minutes);
								$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
								$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);
								/*
								 * END : LATE COMPUTATION
								 */				
	
								/*
								 * START : UNDERTIME COMPUTATION
								 */			
								
								if($time_in >= $earliest_in AND  $time_in <= $latest_in)
								{
									$time_in_variable = $attendance_log['time_in'];
								}
								elseif($time_in < $earliest_in)
								{
									$time_in_variable = $earliest_in;
								}
								else
								{
									$time_in_variable = $latest_in;
								}
								if(!EMPTY($attendance_log['time_out']))
								{
									$time_out = $attendance_log['time_out'];
								}
								elseif (!EMPTY($attendance_log['break_in'])) {
									$time_out = $attendance_log['break_in'];
								}
								elseif (!EMPTY($attendance_log['break_out'])) {
									$time_out = $attendance_log['break_out'];
								}
								else
								{

									if($during_working_hours || $during_early_suspension)
									{
										$time_out = $time_in_variable;
									}
									// else
									// {
									// 	if(!$during_early_suspension)
									// 	{
									// 		$time_out = $attendance_log['time_in'];
									// 	}
									// }
									// if($during_working_hours)
									// {
									// 	$time_out = $time_in_variable;
									// }
									// else
									// {
									// 	if(!$during_early_suspension)
									// 	{
									// 		$time_out = $attendance_log['time_in'];
									// 	}
									// }
									// $time_out = $attendance_log['break_in'];
								}
								$time_out = date('Y-m-d H:i:s', strtotime($time_out));
								
								/*===================== davcorrea : start : format to remove sec in the computation ============= */
								$time_in_variable = date('Y-m-d H:i', strtotime($time_in_variable));
								$time_out = date('Y-m-d H:i', strtotime($time_out));
								/*===================== davcorrea : end : format to remove sec in the computation ============= */
								if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
								{
									$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
								}
								/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
								elseif($has_break AND $time_out <= $break_time)
								{
									$undertime_temp = (strtotime($time_in_variable) + ((($eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
								}
								/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
								else
								{
									/*===================== jendaigo : start : format to remove sec in the computation ============= */
									$time_in_variable = date('Y-m-d H:i', strtotime($time_in_variable));
									$time_out = date('Y-m-d H:i', strtotime($time_out));
									/*===================== jendaigo : end : format to remove sec in the computation ============= */
									
									$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
								}

								if($during_early_suspension || $during_working_hours)
								{
									if($has_break AND $time_out >= $break_time AND $holiday_start_time >= $break_time_hours)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
									
									}
									/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									elseif($has_break AND $holiday_start_time <= $break_time_hours)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}
									/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									else
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
										
									}
								}
								
								if($undertime_temp != 0)
								{
									$undertime = ($undertime_temp / $sixty_seconds);
	
									$data['undertime']      = ($undertime > 0  AND $undertime < (20*60)) ?  round($undertime/$sixty_minutes,3): 0;
									$data['undertime_hour'] = floor($undertime/$sixty_minutes);
									$data['undertime_min']  = ($undertime%$sixty_minutes);
	
									if($data['undertime_hour'] > 8)
									{
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday3              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										if(EMPTY($holiday3))
										{
											$data['holiday_name']	= '';
											$data['undertime']      = 8;
											$data['undertime_hour'] = 8;
											$data['undertime_min']  = 0;
											
											$data['tardiness']      = 0;
											$data['tardiness_hour'] = 0;
											$data['tardiness_min']  = 0;
										}
										else
										{
											$data['holiday_name'] = ! empty($holiday3['title']) ? $holiday3['title'] : '';
										}
									}
								}
								else
								{
									$where                 = array();
									$where['holiday_date'] = $date;
									$holiday3              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
									if(!EMPTY($holiday3))
									{
										$data['holiday_name'] = ! empty($holiday3['title']) ? $holiday3['title'] : '';
										$data['undertime']      = 0;
										$data['undertime_hour'] = 0;
										$data['undertime_min']  = 0;
										
										$data['tardiness']      = 0;
										$data['tardiness_hour'] = 0;
										$data['tardiness_min']  = 0;
									}
								}
								/*
								 * END : UNDERTIME COMPUTATION
								 */
							}
							if($holiday)
							{
								$data['status'] = $holiday['attendance_status_id'];
							}
							else{
								$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
							}
							
							/*
							 * START : HOURS WORKED COMPUTATION
							 */
							$undertime = ($data['undertime']) ? $data['undertime']:0;
							$tardiness = ($data['tardiness']) ? $data['tardiness']:0;
							$working_hours = $eight_working_hours -  ($undertime + $tardiness);
							$data['working_hours'] = ($working_hours > 0 AND $working_hours < 100) ? round($working_hours,3) : 0;
							/*
							 * END : HOURS WORKED COMPUTATION
							 */
						}
						else
						{
							// $with_biometric = $this->biometric_logs->check_no_biometric($employee_id);
							// if($with_biometric['cnt'] > 0)
							if($work_schedule['work_schedule_id'] == 61)
							{
								/*IF EMPLOYEE HAS NO BIOMETRIC, REGULAR DAY WILL BE AUTOMATICALLY INSERTED*/
								
								$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
								$field                   = array("*") ;
								$where                   = array();
								$where['sys_param_type'] = "WORKING_HOURS";
								
								$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
								
								$data['working_hours'] = !EMPTY($working_hours['sys_param_value']) ? $working_hours['sys_param_value']: 8;								
								$data['status']        = ATTENDANCE_STATUS_REGULAR_DAY;
							}
							else
							{
								$earliest_in	= $date.' '.$earliest_in;
								$latest_in   	= $date.' '.$latest_in;
								$sixty_seconds 	= 60;
								$sixty_minutes 	= 60;
								
								//GET HOLIDAY
								$where                 = array();
								$where['holiday_date'] = $date;
								$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
								
								if(!empty($holiday2))
								{
									$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
									$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
									
									//EARLY SUSPENSION
									// if($holiday2['holiday_type_id'] == '5')
									// {
									// 	// $earliest_in 	= $date . ' ' . '08:00:00';
									// 	// $latest_in 		= $date . ' ' . '08:00:00';
									// 	$data['working_hours'] 	= 8;
									// 	// $data['status'] = ATTENDANCE_STATUS_SPECIAL_HOLIDAY;

									// 	$employ_type = $this->dtr->get_employee_type($employee['employee_id'],$time_log['attendance_date']);
													
									// 	if($employ_type['employ_type_flag'] == 'JO')
									// 	{
									// 		$dtr[$day]['undertime_hour'] = 8;
									// 		$total_undertime_hour = 8;
									// 	}
									// }
									//DURING WORKING HOURS SUSPENSION
									
									if($holiday2['holiday_type_id'] == '6' || $holiday2['holiday_type_id'] == '5')
									{
										// $earliest_in = $latest_in;
										$employ_type = $this->dtr->get_employee_type($employee_id,$date);
													
										if($employ_type['employ_type_flag'] == 'JO')
										{
											$data['working_hours'] 	= 8;
											$data['status'] = ATTENDANCE_STATUS_ABSENT;
										}
										else
										{
										$latest_in = $date . ' ' . '08:00:00';
										
										// $tardiness = (((strtotime($holiday_start_time) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
										
										// $data['tardiness'] = ($tardiness/$sixty_minutes);
										// $data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
										// $data['tardiness_min'] = ($tardiness%$sixty_minutes);
										$data['undertime_hour'] 	= $holiday2['start_time'] - '08:00:00';
										$data['undertime'] 	= $holiday2['start_time'] - '08:00:00';
										$data['working_hours'] 	= 8;
										$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
										}
										
									}
									
								}
								else
								{
									$data['status'] = ATTENDANCE_STATUS_ABSENT;
								}
							}
						}
					}
					/*CHECK IF EMPLOYEE IS ON LEAVE*/
					$attendance_dtl = $this->biometric_logs->get_attendance_leave_dtl($employee_id,$date);
					if($attendance_dtl)
					{
						if(EMPTY(round($attendance_dtl['leave_wop'])) AND !EMPTY(round($attendance_dtl['leave_earned_used']))){
							
							$status = ($attendance_dtl['leave_earned_used'] < 1) ? ATTENDANCE_STATUS_LEAVE_HD_WP:ATTENDANCE_STATUS_LEAVE_WP;
	
							$working_hours = ($attendance_dtl['leave_earned_used'] < 1) ? $attendance_dtl['leave_earned_used'] : 1 ;
							$data['working_hours'] += $eight_working_hours * $working_hours;	
							$data['undertime_hour'] 	= 0;
							$data['undertime'] 	= 0;						
						}
						elseif(!EMPTY(round($attendance_dtl['leave_wop']))){
	
							$no_works      = $this->_check_no_work($date,$attendance_dtl['leave_end_date'],$employee_id);
							$no_works_count = count($no_works);
							$day_count     = round($attendance_dtl['leave_wop']) + $no_works_count;
							$leave_wop     = "-".$day_count. " day";
						
							$leave_date = date('Y-m-d',strtotime($leave_wop , strtotime ( $attendance_dtl['leave_end_date'] ) ) );	
	
							if($date > $leave_date)
							{
								$status = ($attendance_dtl['leave_wop'] < 1) ? ATTENDANCE_STATUS_LEAVE_HD_WOP : ATTENDANCE_STATUS_LEAVE_WOP;
							}
							else
							{
								$status = ($attendance_dtl['leave_wop'] < 1) ? ATTENDANCE_STATUS_LEAVE_HD_WP : ATTENDANCE_STATUS_LEAVE_WP;
							}										
						}
						else
						{
							$status = ATTENDANCE_STATUS_REGULAR_DAY;
						}
						$data['status'] = $status;
					}
					if($holiday AND empty($holiday2) AND !in_array($holiday['holiday_type_id'], $_early_suspension_types))
					{
						$data['status'] = $holiday['attendance_status_id'];
					}
					
				}
			}
			else
			{
				switch($work_schedule[$day.'_type_of_duty'])
				{

					case 4:
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= ($work_schedule['break_time'] == '00:00:00' ? null : $work_schedule['break_time']);

							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in 		= $work_schedule[$day.'_latest_in'];

							$day_off 		= (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;
						}
						else
						{
							$data['status'] = ATTENDANCE_STATUS_ABSENT;
							return $data;
						}

						if($day_off == true)
						{
							$data['status'] = ATTENDANCE_STATUS_REST_DAY;
						}
						else
						{
							$attendance_log = $this->biometric_logs->get_employee_attendance($employee_id,$date);

							/*IF EMPLOYEE HAS NO ATTENDANCE, CHECK IF DATE IS HOLIDAY*/
							$tables = array(
								'main'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_work_calendar,
									'alias'		=> 'A',
								),
								't1'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_holiday_types,
									'alias'		=> 'B',
									'type'		=> 'join',
									'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
								)
							);
							$where 						= array();
							$where['A.holiday_date'] 	= $date;
							$holiday               		= $this->biometric_logs->get_biometric_log(array("*"), $tables, $where, FALSE);

							if($holiday AND $holiday['holiday_type_id'] != 3)
							{
								$data['status'] = $holiday['attendance_status_id'];
							}
							else
							{
								if($attendance_log['attendance_date'])
								{
									$sixty_seconds       = 60;
									$sixty_minutes       = 60;
									$working_hours = $work_schedule[$day . '_type_of_duty'];

									if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
									{
										$has_break 		= ($break_time > 0 ? TRUE : FALSE);
										$earliest_in 	= $date.' '.$earliest_in;
										$latest_in 		= $date.' '.$latest_in;
										$break_time 	= $date.' '.$break_time;

										$minutes_to_add   = $break_hours * 60;

										$new_time         = new DateTime($break_time);
										$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
										$break_time_hours = $new_time->format('Y-m-d H:i:s');

										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);

										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												$earliest_in 	= $date . ' ' . '08:00:00';
												$latest_in 		= $date . ' ' . '08:00:00';
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												$earliest_in = $latest_in;
											}
											
											$working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes) - $break_hours;
										}
									}
									
										if(!EMPTY($attendance_log['time_in']))
										{
											$time_in = $attendance_log['time_in'];
										}
										else if(!EMPTY($attendance_log['break_out']))
										{
											$time_in = $attendance_log['break_out'];
										}
										else if(!EMPTY($attendance_log['break_in']))
										{
											$time_in = $attendance_log['break_in'];
										}
										else
										{
											$time_in = $attendance_log['time_out'];
										}
									$time_in = date('Y-m-d H:i:s', strtotime($time_in));

									//late computation
									if($time_in > $latest_in)
									{
										if($has_break)
										{
											if($time_in < $break_time)
											{
												$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
											}
											elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
											{
												$tardiness = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
											}
											else
											{
												$tardiness = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
											}
										}
										else
										{
											$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
										}					
									}
									if($has_break)
									{
										/* additionational late computation with late break-in*/
										if(!empty($attendance_log['time_in']) AND !empty($attendance_log['break_out']) AND !empty($attendance_log['break_in']) AND !empty($attendance_log['time_out']))
										{
											if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
											{
												$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
											}
										}
									}
									$data['tardiness'] 		= ($tardiness/$sixty_minutes);
									$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
									$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);

									//undertime computation
									if($time_in >= $earliest_in AND  $time_in <= $latest_in)
									{
										$time_in_variable = $time_in;
									}
									elseif($time_in < $earliest_in)
									{
										$time_in_variable = $earliest_in;
									}
									else
									{
										$time_in_variable = $latest_in;
									}

										if(!EMPTY($attendance_log['time_out']))
										{
											$time_out = $attendance_log['time_out'];
										}
										else if(!EMPTY($attendance_log['break_in']))
										{
											$time_out = $attendance_log['break_in'];
										}
										else if(!EMPTY($attendance_log['break_out']))
										{
											$time_out = $attendance_log['break_out'];
										}
										else
										{
											$time_out = $attendance_log['time_in'];
										}
									

									$time_out = date('Y-m-d H:i:s', strtotime($time_out));
						
									if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
									}
									/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									elseif($has_break AND $time_out >= $latest_in AND $time_out <= $break_time)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}
									/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									else
									{
										/*===================== jendaigo : start : format to remove sec in the computation ============= */
										$time_in_variable = date('Y-m-d H:i', strtotime($time_in_variable));
										$time_out = date('Y-m-d H:i', strtotime($time_out));
										/*===================== jendaigo : end : format to remove sec in the computation ============= */

										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}

									// $undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									
									if($undertime_temp != 0)
									{
										$undertime = ($undertime_temp / $sixty_seconds);

										$data['undertime']      = ($undertime > 0  AND $undertime <= ($working_hours * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
										$data['undertime_hour'] = floor($undertime / $sixty_minutes);
										$data['undertime_min']  = ($undertime%$sixty_minutes);

										if(!empty($present_time_in) OR !empty($present_break_in))
										{
											if($data['undertime_hour'] > $working_hours)
											{
												$data['undertime']      = $working_hours;
												$data['undertime_hour'] = $working_hours;
												$data['undertime_min']  = 0;
												
												$data['tardiness']      = 0;
												$data['tardiness_hour'] = 0;
												$data['tardiness_min']  = 0;
											}								
										}
									}
									else
									{
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday3              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										if(!EMPTY($holiday3))
										{
											$data['holiday_name'] = ! empty($holiday3['title']) ? $holiday3['title'] : '';
											$data['undertime']      = 0;
											$data['undertime_hour'] = 0;
											$data['undertime_min']  = 0;
											
											$data['tardiness']      = 0;
											$data['tardiness_hour'] = 0;
											$data['tardiness_min']  = 0;
										}
									}

									if($holiday)
									{
										$data['status'] = $holiday['attendance_status_id'];
									}
									else{
										$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									
									//hours work computation
									$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
									$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
									$working_hours 			= $working_hours - ($undertime + $tardiness);
									$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
								}
								else
								{
									// $with_biometric = $this->biometric_logs->check_no_biometric($employee_id);
									// if($with_biometric['cnt'] > 0)
									if($work_schedule['work_schedule_id'] == 61)
									{
										/*IF EMPLOYEE HAS NO BIOMETRIC, REGULAR DAY WILL BE AUTOMATICALLY INSERTED*/
			
										$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
										$field                   = array("*") ;
										$where                   = array();
										$where['sys_param_type'] = "WORKING_HOURS";
										
										$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
			
										$data['working_hours'] = !EMPTY($working_hours['sys_param_value']) ? $working_hours['sys_param_value']: 8;								
										$data['status']        = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									else
									{
										$earliest_in	= $date.' '.$earliest_in;
										$latest_in   	= $date.' '.$latest_in;
										$sixty_seconds 	= 60;
										$sixty_minutes 	= 60;
										
										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										
										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												// $earliest_in 	= $date . ' ' . '08:00:00';
												// $latest_in 		= $date . ' ' . '08:00:00';
												$data['working_hours'] 	= 10;
												$data['status'] = ATTENDANCE_STATUS_SPECIAL_HOLIDAY;
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												// $earliest_in = $latest_in;
												$latest_in = $date . ' ' . '08:00:00';
												
												$tardiness = (((strtotime($holiday_start_time) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
												
												$data['tardiness'] = ($tardiness/$sixty_minutes);
												$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
												$data['tardiness_min'] = ($tardiness%$sixty_minutes);
												$data['working_hours'] 	= 0;
												$data['status'] = ATTENDANCE_STATUS_ABSENT;
											}
											
										}
										else
										{
											$data['status'] = ATTENDANCE_STATUS_ABSENT;
										}
									}
								}
							}
						}
						break;


					case 10:
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= ($work_schedule['break_time'] == '00:00:00' ? null : $work_schedule['break_time']);

							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in 		= $work_schedule[$day.'_latest_in'];

							$day_off 		= (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;
						}
						else
						{
							$data['status'] = ATTENDANCE_STATUS_ABSENT;
							return $data;
						}

						if($day_off == true)
						{
							$data['status'] = ATTENDANCE_STATUS_REST_DAY;
						}
						else
						{
							$attendance_log = $this->biometric_logs->get_employee_attendance($employee_id,$date);

							/*IF EMPLOYEE HAS NO ATTENDANCE, CHECK IF DATE IS HOLIDAY*/
							$tables = array(
								'main'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_work_calendar,
									'alias'		=> 'A',
								),
								't1'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_holiday_types,
									'alias'		=> 'B',
									'type'		=> 'join',
									'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
								)
							);
							$where 						= array();
							$where['A.holiday_date'] 	= $date;
							$holiday               		= $this->biometric_logs->get_biometric_log(array("*"), $tables, $where, FALSE);

							if($holiday AND $holiday['holiday_type_id'] != 3)
							{
								$data['status'] = $holiday['attendance_status_id'];
							}
							else
							{
								if($attendance_log['attendance_date'])
								{
									$sixty_seconds       = 60;
									$sixty_minutes       = 60;
									$working_hours = $work_schedule[$day . '_type_of_duty'];

									if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
									{
										$has_break 		= (!EMPTY($break_time) ? TRUE : FALSE);
										$earliest_in 	= $date.' '.$earliest_in;
										$latest_in 		= $date.' '.$latest_in;
										$break_time 	= $date.' '.$break_time;

										$minutes_to_add   = $break_hours * 60;

										$new_time         = new DateTime($break_time);
										$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
										$break_time_hours = $new_time->format('Y-m-d H:i:s');

										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);

										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												$earliest_in 	= $date . ' ' . '08:00:00';
												$latest_in 		= $date . ' ' . '08:00:00';
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												$earliest_in = $latest_in;
											}
											
											$working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes) - $break_hours;
										}
									}
									
									if(empty($attendance_log['time_out']) AND !empty($attendance_log['break_out']))
									{
										//get previous work schedule
										$prev_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '-1 day')));
										$day = strtolower(date('D',strtotime($date . '-1 day')));

										if($prev_work_schedule[$day.'_type_of_duty'] == 10)
										{
											// get previous day attendance log
											$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
											$table 						= 'employee_attendance';
											$where 						= array();
											$where['employee_id']		= $employee_id;
											$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '-1 day'));
											$result 					= $this->dtr->get_general_data($fields, $table, $where);
											
											//retain present break-in
											$present_break_in = $attendance_log['break_in'];

											//store previous latest_in
											$earliest_in 	= $prev_work_schedule[$day.'_earliest_in'];
											$latest_in 		= $prev_work_schedule[$day.'_latest_in'];

											//store new break-in from previous date
											foreach($result as $res)
											{
												if($res['time_flag'] == 'BI')
												{
													$attendance_log['break_in'] = $res['time_log'];
												}
											}
											$time_in = $attendance_log['break_in'];
										}
										else
										{
											if(!EMPTY($attendance_log['time_in']))
											{
												$time_in = $attendance_log['time_in'];
											}
											else if(!EMPTY($attendance_log['break_out']))
											{
												$time_in = $attendance_log['break_out'];
											}
											else if(!EMPTY($attendance_log['break_in']))
											{
												$time_in = $attendance_log['break_in'];
											}
											else
											{
												$time_in = $attendance_log['time_out'];
											}
										}
									}
									else
									{
										if(!EMPTY($attendance_log['time_in']))
										{
											$time_in = $attendance_log['time_in'];
										}
										else if(!EMPTY($attendance_log['break_out']))
										{
											$time_in = $attendance_log['break_out'];
										}
										else if(!EMPTY($attendance_log['break_in']))
										{
											$time_in = $attendance_log['break_in'];
										}
										else
										{
											$time_in = $attendance_log['time_out'];
										}
									}
									$time_in = date('Y-m-d H:i:s', strtotime($time_in));

									//late computation
									if($time_in > $latest_in)
									{
										if($has_break)
										{
											if($time_in < $break_time)
											{
												$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
											}
											elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
											{
												$tardiness = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
											}
											else
											{
												$tardiness = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
											}
										}
										else
										{
											$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
										}					
									}

									/* additionational late computation with late break-in*/
									if(!empty($attendance_log['time_in']) AND !empty($attendance_log['break_out']) AND !empty($attendance_log['break_in']) AND !empty($attendance_log['time_out']))
									{
										if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
										{
											$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
										}
									}
									$data['tardiness'] 		= ($tardiness/$sixty_minutes);
									$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
									$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);

									//undertime computation
									if($time_in >= $earliest_in AND  $time_in <= $latest_in)
									{
										$time_in_variable = $time_in;
									}
									elseif($time_in < $earliest_in)
									{
										$time_in_variable = $earliest_in;
									}
									else
									{
										$time_in_variable = $latest_in;
									}

									if(empty($attendance_log['time_in']) AND !empty($attendance_log['break_in']))
									{
										//get next work schedule
										$next_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '+1 day')));
										$day = strtolower(date('D',strtotime($date . '+1 day')));
										
										if($next_work_schedule[$day.'_type_of_duty'] == 10)
										{
											//get next day attendance log
											$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
											$table 						= 'employee_attendance';
											$where 						= array();
											$where['employee_id']		= $employee_id;
											$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '+1 day'));
											$result 					= $this->dtr->get_general_data($fields, $table, $where);
											
											if(!empty($result))
											{
												//retain present break-out
												$present_break_out 	= $attendance_log['break_out'];

												//store new time-out from next date
												foreach($result as $res)
												{
													if($res['time_flag'] == 'BO')
													{
														$attendance_log['break_out'] = $res['time_log'];
													}
												}
												
												$time_out = !empty($attendance_log['break_out']) ? $attendance_log['break_out'] : $attendance_log['break_in'];
											}
											else
											{
												if(!EMPTY($attendance_log['time_out']))
												{
													$time_out = $attendance_log['time_out'];
												}
												else if(!EMPTY($attendance_log['break_in']))
												{
													$time_out = $attendance_log['break_in'];
												}
												else if(!EMPTY($attendance_log['break_out']))
												{
													$time_out = $attendance_log['break_out'];
												}
												else
												{
													$time_out = $attendance_log['break_in'];
												}
											}
										}
										else
										{
											if(!EMPTY($attendance_log['time_out']))
											{
												$time_out = $attendance_log['time_out'];
											}
											else if(!EMPTY($attendance_log['break_in']))
											{
												$time_out = $attendance_log['break_in'];
											}
											else if(!EMPTY($attendance_log['break_out']))
											{
												$time_out = $attendance_log['break_out'];
											}
											else
											{
												$time_out = $attendance_log['break_in'];
											}
										}
									}
									else
									{
										if(!EMPTY($attendance_log['time_out']))
										{
											$time_out = $attendance_log['time_out'];
										}
										else if(!EMPTY($attendance_log['break_in']))
										{
											$time_out = $attendance_log['break_in'];
										}
										else if(!EMPTY($attendance_log['break_out']))
										{
											$time_out = $attendance_log['break_out'];
										}
										else
										{
											$time_out = $attendance_log['time_in'];
										}
									}

									$time_out = date('Y-m-d H:i:s', strtotime($time_out));
						
									if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
									}
									/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									elseif($has_break AND $time_out >= $latest_in AND $time_out <= $break_time)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}
									/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									else
									{
										/*===================== jendaigo : start : format to remove sec in the computation ============= */
										$time_in_variable = date('Y-m-d H:i', strtotime($time_in_variable));
										$time_out = date('Y-m-d H:i', strtotime($time_out));
										/*===================== jendaigo : end : format to remove sec in the computation ============= */

										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}

									// $undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									
									if($undertime_temp != 0)
									{
										$undertime = ($undertime_temp / $sixty_seconds);

										$data['undertime']      = ($undertime > 0  AND $undertime <= ($working_hours * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
										$data['undertime_hour'] = floor($undertime / $sixty_minutes);
										$data['undertime_min']  = ($undertime%$sixty_minutes);

										if(!empty($present_time_in) OR !empty($present_break_in))
										{
											if($data['undertime_hour'] > $working_hours)
											{
												$data['undertime']      = $working_hours;
												$data['undertime_hour'] = $working_hours;
												$data['undertime_min']  = 0;
												
												$data['tardiness']      = 0;
												$data['tardiness_hour'] = 0;
												$data['tardiness_min']  = 0;
											}								
										}
									}
									else
									{
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday3              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										if(!EMPTY($holiday3))
										{
											$data['holiday_name'] = ! empty($holiday3['title']) ? $holiday3['title'] : '';
											$data['undertime']      = 0;
											$data['undertime_hour'] = 0;
											$data['undertime_min']  = 0;
											
											$data['tardiness']      = 0;
											$data['tardiness_hour'] = 0;
											$data['tardiness_min']  = 0;
										}
									}

									if($holiday)
									{
										$data['status'] = $holiday['attendance_status_id'];
									}
									else{
										$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									
									//hours work computation
									$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
									$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
									$working_hours 			= $working_hours - ($undertime + $tardiness);
									$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
								}
								else
								{
									// $with_biometric = $this->biometric_logs->check_no_biometric($employee_id);
									// if($with_biometric['cnt'] > 0)
									if($work_schedule['work_schedule_id'] == 61)
									{
										/*IF EMPLOYEE HAS NO BIOMETRIC, REGULAR DAY WILL BE AUTOMATICALLY INSERTED*/
			
										$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
										$field                   = array("*") ;
										$where                   = array();
										$where['sys_param_type'] = "WORKING_HOURS";
										
										$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
			
										$data['working_hours'] = !EMPTY($working_hours['sys_param_value']) ? $working_hours['sys_param_value']: 8;								
										$data['status']        = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									else
									{
										$earliest_in	= $date.' '.$earliest_in;
										$latest_in   	= $date.' '.$latest_in;
										$sixty_seconds 	= 60;
										$sixty_minutes 	= 60;
										
										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										
										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												// $earliest_in 	= $date . ' ' . '08:00:00';
												// $latest_in 		= $date . ' ' . '08:00:00';
												$data['working_hours'] 	= 10;
												$data['status'] = ATTENDANCE_STATUS_SPECIAL_HOLIDAY;
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												// $earliest_in = $latest_in;
												$latest_in = $date . ' ' . '08:00:00';
												
												$tardiness = (((strtotime($holiday_start_time) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
												
												$data['tardiness'] = ($tardiness/$sixty_minutes);
												$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
												$data['tardiness_min'] = ($tardiness%$sixty_minutes);
												$data['working_hours'] 	= 0;
												$data['status'] = ATTENDANCE_STATUS_ABSENT;
											}
											
										}
										else
										{
											$data['status'] = ATTENDANCE_STATUS_ABSENT;
										}
									}
								}
							}
						}
						break;

					case 12:
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= $work_schedule['break_time'];
							
							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in 		= $work_schedule[$day.'_latest_in'];
			
							$day_off = (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;	
						}
						else
						{
							$data['status'] = ATTENDANCE_STATUS_ABSENT;
							return $data;
						}

						if($day_off == true)
						{
							$data['status'] = ATTENDANCE_STATUS_REST_DAY;
						}
						else
						{
							$attendance_log = $this->biometric_logs->get_employee_attendance($employee_id,$date);

							/*IF EMPLOYEE HAS NO ATTENDANCE, CHECK IF DATE IS HOLIDAY*/
							$tables = array(
								'main' => array(
									'table' => $this->biometric_logs->tbl_param_work_calendar,
									'alias' => 'A',
								),
								't1' => array(
									'table' 	=> $this->biometric_logs->tbl_param_holiday_types,
									'alias' 	=> 'B',
									'type' 		=> 'join',
									'condition' => 'A.holiday_type_id = B.holiday_type_id',
								)
							);
							$where 						= array();
							$where['A.holiday_date'] 	= $date;
							$holiday 					= $this->biometric_logs->get_biometric_log(array("*"), $tables, $where, FALSE);

							if($holiday AND $holiday['holiday_type_id'] != 3)
							{
								$data['status'] = $holiday['attendance_status_id'];
							}
							else
							{
								if($attendance_log['attendance_date'])
								{
									$sixty_seconds = 60;
									$sixty_minutes = 60;
									$working_hours = $work_schedule[$day.'_type_of_duty'];

									if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
									{
										$has_break           = (!EMPTY($break_time) ? TRUE : FALSE);
										$earliest_in         = $date.' '.$earliest_in;
										$latest_in           = $date.' '.$latest_in;
										$break_time          = $date.' '.$break_time;

										$minutes_to_add   = $break_hours * 60;

										$new_time         = new DateTime($break_time);
										$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

										$break_time_hours = $new_time->format('Y-m-d H:i:s');

										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										
										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												$earliest_in 	= $date . ' ' . '08:00:00';
												$latest_in 		= $date . ' ' . '08:00:00';
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												$earliest_in = $latest_in;
											}
											
											$eight_working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes) - $break_hours;
										}

										if(empty($attendance_log['time_out']) AND !empty($attendance_log['break_out']))
										{
											//get previous work schedule
											$prev_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '-1 day')));
											$day = strtolower(date('D',strtotime($date . '-1 day')));
									
											if($prev_work_schedule[$day.'_type_of_duty'] == 12)
											{
												// get previous day attendance log
												$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
												$table 						= 'employee_attendance';
												$where 						= array();
												$where['employee_id']		= $employee_id;
												$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '-1 day'));
												$result 					= $this->dtr->get_general_data($fields, $table, $where);
												
												//retain present break-in
												$present_break_in = $attendance_log['break_in'];
												
												//store previous latest_in
												$earliest_in 	= $prev_work_schedule[$day.'_earliest_in'];
												$latest_in 		= $prev_work_schedule[$day.'_latest_in'];
												
												//store new break-in from previous date
												foreach($result as $res)
												{
													if($res['time_flag'] == 'BI')
													{
														$attendance_log['break_in'] = $res['time_log'];
													}
												}
												
												$time_in = $attendance_log['break_in'];
											}
											else
											{
												if(!EMPTY($attendance_log['time_in']))
												{
													$time_in = $attendance_log['time_in'];
												}
												else if(!EMPTY($attendance_log['break_out']))
												{
													$time_in = $attendance_log['break_out'];
												}
												else if(!EMPTY($attendance_log['break_in']))
												{
													$time_in = $attendance_log['break_in'];
												}
												else
												{
													$time_in = $attendance_log['time_out'];
												}
											}
										}
										else
										{
											if(!EMPTY($attendance_log['time_in']))
											{
												$time_in = $attendance_log['time_in'];
											}
											else if(!EMPTY($attendance_log['break_out']))
											{
												$time_in = $attendance_log['break_out'];
											}
											else if(!EMPTY($attendance_log['break_in']))
											{
												$time_in = $attendance_log['break_in'];
											}
											else
											{
												$time_in = $attendance_log['time_out'];
											}
										}
										$time_in = date('Y-m-d H:i:s', strtotime($time_in));

										//late computation
										if($time_in > $latest_in)
										{
											if($has_break)
											{
												if($time_in < $break_time)
												{
													$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
												}
												elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
												{
													$tardiness = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
												}
												else
												{
													$tardiness = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
												}
											}
											else
											{
												$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
											}

											// $tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
										}

										/* additionational late computation with late break-in*/
										if(!empty($attendance_log['time_in']) AND !empty($attendance_log['break_out']) AND !empty($attendance_log['break_in']) AND !empty($attendance_log['time_out']))
										{
											if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
											{
												$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
											}
										}
										$data['tardiness'] 		= ($tardiness/$sixty_minutes);
										$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
										$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);

										//undertime computation
										if($time_in >= $earliest_in AND  $time_in <= $latest_in)
										{
											$time_in_variable = $attendance_log['time_in'];
										}
										elseif($time_in < $earliest_in)
										{
											$time_in_variable = $earliest_in;
										}
										else
										{
											$time_in_variable = $latest_in;
										}
										
										if(empty($attendance_log['time_in']) AND !empty($attendance_log['break_in']))
										{
											//get next work schedule
											$next_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '+1 day')));
											$day = strtolower(date('D',strtotime($date . '+1 day')));
											
											// if($next_work_schedule[$day.'_type_of_duty'] == 12) --> temporary disabled
											if($work_schedule[$day.'_type_of_duty'] == 12)
											{
												//get next day attendance log
												$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
												$table 						= 'employee_attendance';
												$where 						= array();
												$where['employee_id']		= $employee_id;
												$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '+1 day'));
												$result 					= $this->dtr->get_general_data($fields, $table, $where);
												
												if(!empty($result))
												{
													//retain present break-out
													$present_break_out 	= $attendance_log['break_out'];

													//store new time-out from next date
													foreach($result as $res)
													{
														if($res['time_flag'] == 'BO')
														{
															$attendance_log['break_out'] = $res['time_log'];
														}
													}
													
													$time_out = !empty($attendance_log['break_out']) ? $attendance_log['break_out'] : $attendance_log['break_in'];
												}
												else
												{
													if(!EMPTY($attendance_log['time_out']))
													{
														$time_out = $attendance_log['time_out'];
													}
													else if(!EMPTY($attendance_log['break_in']))
													{
														$time_out = $attendance_log['break_in'];
													}
													else if(!EMPTY($attendance_log['break_out']))
													{
														$time_out = $attendance_log['break_out'];
													}
													else
													{
														$time_out = $attendance_log['break_in'];
													}
												}
											}
										}
										else
										{
											if(!EMPTY($attendance_log['time_out']))
											{
												$time_out = $attendance_log['time_out'];
											}
											else if(!EMPTY($attendance_log['break_in']))
											{
												$time_out = $attendance_log['break_in'];
											}
											else if(!EMPTY($attendance_log['break_out']))
											{
												$time_out = $attendance_log['break_out'];
											}
											else
											{
												$time_out = $attendance_log['time_in'];
											}
										}

										$time_out = date('Y-m-d H:i:s', strtotime($time_out));

										if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
										{
											$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
										}
										/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
										elseif($has_break AND $time_out >= $latest_in AND $time_out <= $break_time)
										{
											$undertime_temp = (strtotime($time_in_variable) + ((($working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
										}
										/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
										else
										{
											$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
										}

										// $undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
										
										if($undertime_temp > 0)
										{
											$undertime = ($undertime_temp / $sixty_seconds);

											$data['undertime']      = ($undertime > 0  AND $undertime <= ($working_hours * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
											$data['undertime_hour'] = floor($undertime / $sixty_minutes);
											$data['undertime_min']  = ($undertime%$sixty_minutes);

											if(!empty($present_time_in) OR !empty($present_break_in))
											{
												if($data['undertime_hour'] > $working_hours)
												{
													$data['undertime']      = $working_hours;
													$data['undertime_hour'] = $working_hours;
													$data['undertime_min']  = 0;
													
													$data['tardiness']      = 0;
													$data['tardiness_hour'] = 0;
													$data['tardiness_min']  = 0;
												}								
											}
										}

										if($holiday)
										{
											$data['status'] = $holiday['attendance_status_id'];
										}
										else{
											$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
										}
										
										//hours work computation
										$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
										$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
										$working_hours 			= $working_hours - ($undertime + $tardiness);
										$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
									}
								}
								else
								{
									// $with_biometric = $this->biometric_logs->check_no_biometric($employee_id);
									// if($with_biometric['cnt'] > 0)
									if($work_schedule['work_schedule_id'] == 61)
									{
										/*IF EMPLOYEE HAS NO BIOMETRIC, REGULAR DAY WILL BE AUTOMATICALLY INSERTED*/
			
										$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
										$field                   = array("*") ;
										$where                   = array();
										$where['sys_param_type'] = "WORKING_HOURS";
										
										$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
			
										$data['working_hours'] = !EMPTY($working_hours['sys_param_value']) ? $working_hours['sys_param_value']: 8;								
										$data['status']        = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									else
									{
										$data['status'] = ATTENDANCE_STATUS_ABSENT;
									}
								}
							}
						}
						break;

					case 16:
						if($work_schedule)
						{
							$break_hours = $work_schedule['break_hours'];
							$break_time = $work_schedule['break_time'];
							
							$earliest_in   = $work_schedule[$day.'_earliest_in'];
							$latest_in     = $work_schedule[$day.'_latest_in'];
			
							$day_off = (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;
									
						}
						else
						{
							$data['status'] = ATTENDANCE_STATUS_ABSENT;
							return $data;
						}
						
						if($day_off == true)
						{
							$data['status'] = ATTENDANCE_STATUS_REST_DAY;
						}
						else
						{
							$attendance_log = $this->biometric_logs->get_employee_attendance($employee_id,$date);
					
							/*IF EMPLOYEE HAS NO ATTENDANCE, CHECK IF DATE IS HOLIDAY*/
							$tables = array(
								'main'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_work_calendar,
									'alias'		=> 'A',
								),
								't1'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_holiday_types,
									'alias'		=> 'B',
									'type'		=> 'join',
									'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
								)
							);
							$where                 = array();
							$where['A.holiday_date'] = $date;
							$holiday               = $this->biometric_logs->get_biometric_log(array("*"), $tables, $where, FALSE);
			
							if($holiday AND $holiday['holiday_type_id'] != 3)
							{
								$data['status'] = $holiday['attendance_status_id'];
							}
							else
							{
								if($attendance_log['attendance_date'])
								{
									$sixty_seconds  = 60;
									$sixty_minutes  = 60;
									$working_hours 	= $work_schedule[$day.'_type_of_duty'];

									if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
									{
										$has_break           = (!EMPTY($break_time) ? TRUE : FALSE);
										$earliest_in         = $date.' '.$earliest_in;
										$latest_in           = $date.' '.$latest_in;
										$break_time          = $date.' '.$break_time;
										
										$minutes_to_add   = $break_hours * 60;
										
										$new_time         = new DateTime($break_time);
										$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
										
										$break_time_hours = $new_time->format('Y-m-d H:i:s');
										
										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										
										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												$earliest_in 	= $date . ' ' . '08:00:00';
												$latest_in 		= $date . ' ' . '08:00:00';
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												$earliest_in = $latest_in;
											}
											
											$eight_working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes) - $break_hours;
										}
									}

									if(empty($attendance_log['time_in']) AND empty($attendance_log['break_in']))
									{
										//get previous work schedule
										$prev_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '-1 day')));
										$day = strtolower(date('D',strtotime($date . '-1 day')));
								
										if($prev_work_schedule[$day.'_type_of_duty'] == 16)
										{
											// get previous day attendance log
											$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
											$table 						= 'employee_attendance';
											$where 						= array();
											$where['employee_id']		= $employee_id;
											$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '-1 day'));
											$result 					= $this->dtr->get_general_data($fields, $table, $where);
											
											//retain present time-in and break-in
											$present_time_in 	= $attendance_log['time_in'];
											$present_break_in 	= $attendance_log['break_in'];
											
											//store previous latest_in
											$earliest_in 	= $prev_work_schedule[$day.'_earliest_in'];
											$latest_in 		= $prev_work_schedule[$day.'_latest_in'];
											
											//store new time-in from previous date
											foreach($result as $res)
											{
												if($res['time_flag'] == 'TI')
												{
													$attendance_log['time_in'] = $res['time_log'];												
												}
												if($res['time_flag'] == 'BI')
												{
													$attendance_log['break_in'] = $res['time_log'];
												}
											}
										}
									}
									
									if(!EMPTY($attendance_log['time_in']))
									{
										$time_in = $attendance_log['time_in'];
									}
									else if(!EMPTY($attendance_log['break_out']))
									{
										$time_in = $attendance_log['break_out'];
									}
									else if(!EMPTY($attendance_log['break_in']))
									{
										$time_in = $attendance_log['break_in'];
									}
									else
									{
										$time_in = $attendance_log['time_out'];
									}
									
									
									$time_in = date('Y-m-d H:i:s', strtotime($time_in));
									
									//late computation
									if($time_in > $latest_in)
									{
										if($has_break)
										{
											if($time_in < $break_time)
											{
												$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
											}
											elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
											{
												$tardiness = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
											}
											else
											{
												$tardiness = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
											}
										}
										else
										{
											$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
										}
										// $tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
									}
									/* additionational late computation with late break-in*/
									if(!empty($attendance_log['time_in']) AND !empty($attendance_log['break_out']) AND !empty($attendance_log['break_in']) AND !empty($attendance_log['time_out']))
									{
										if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
										{
											$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
										}
									}
									$data['tardiness'] 		= ($tardiness/$sixty_minutes);
									$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
									$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);
									
									//undertime computation
									if($time_in >= $earliest_in AND  $time_in <= $latest_in)
									{
										$time_in_variable = $attendance_log['time_in'];
									}
									elseif($time_in < $earliest_in)
									{
										$time_in_variable = $earliest_in;
									}
									else
									{
										$time_in_variable = $latest_in;
									}
									
									if(empty($attendance_log['time_out']) AND empty($attendance_log['break_out']))
									{
										//get next work schedule
										$next_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '+1 day')));
										$day = strtolower(date('D',strtotime($date . '+1 day')));
										
										if($next_work_schedule[$day.'_type_of_duty'] == 16 OR empty($next_work_schedule[$day.'_type_of_duty']))
										{
											//get next day attendance log
											$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
											$table 						= 'employee_attendance';
											$where 						= array();
											$where['employee_id']		= $employee_id;
											$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '+1 day'));
											$result 					= $this->dtr->get_general_data($fields, $table, $where);
											
											if(!empty($result))
											{
												//retain present time-out and break-out
												$present_time_out = $attendance_log['time_out'];
												$present_break_out = $attendance_log['break_out'];

												//store new time-out from next date
												foreach($result as $res)
												{
													if($res['time_flag'] == 'TO')
													{
														$attendance_log['time_out'] = $res['time_log'];												
													}
													if($res['time_flag'] == 'BO')
													{
														$attendance_log['break_out'] = $res['time_log'];
													}
												}
											}
											
											if(!EMPTY($attendance_log['time_out']))
											{
												$time_out = $attendance_log['time_out'];
											}
											else if(!EMPTY($attendance_log['break_out']))
											{
												$time_out = $attendance_log['break_out'];
											}
											else if(!EMPTY($attendance_log['break_in']))
											{
												$time_out = $attendance_log['break_in'];
											}
											else
											{
												$time_out = $attendance_log['time_in'];
											}
										}
									}
									else
									{
										if(!EMPTY($attendance_log['time_out']))
										{
											$time_out = $attendance_log['time_out'];
										}
										else if(!EMPTY($attendance_log['break_in']))
										{
											$time_out = $attendance_log['break_in'];
										}
										else if(!EMPTY($attendance_log['break_out']))
										{
											$time_out = $attendance_log['break_out'];
										}
										else
										{
											$time_out = $attendance_log['time_in'];
										}
									}
									
									
									$time_out = date('Y-m-d H:i:s', strtotime($time_out));

									if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
									}
									/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									elseif($has_break AND $time_out >= $latest_in AND $time_out <= $break_time)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}
									/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									else
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}

									// $undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									
									if($undertime_temp > 0)
									{
										$undertime = ($undertime_temp / $sixty_seconds);

										$data['undertime']      = ($undertime > 0  AND $undertime <= ($working_hours * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
										$data['undertime_hour'] = floor($undertime / $sixty_minutes);
										$data['undertime_min']  = ($undertime%$sixty_minutes);

										if(!empty($present_time_in) OR !empty($present_break_in))
										{
											if($data['undertime_hour'] > $working_hours)
											{
												$data['undertime']      = $working_hours;
												$data['undertime_hour'] = $working_hours;
												$data['undertime_min']  = 0;
												
												$data['tardiness']      = 0;
												$data['tardiness_hour'] = 0;
												$data['tardiness_min']  = 0;
											}								
										}
									}

									if($holiday)
									{
										$data['status'] = $holiday['attendance_status_id'];
									}
									else{
										$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									
									//hours work computation
									$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
									$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
									$working_hours 			= $working_hours - ($undertime + $tardiness);
									$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
								}
								else
								{
									// $with_biometric = $this->biometric_logs->check_no_biometric($employee_id);
									// if($with_biometric['cnt'] > 0)
									if($work_schedule['work_schedule_id'] == 61)
									{
										/*IF EMPLOYEE HAS NO BIOMETRIC, REGULAR DAY WILL BE AUTOMATICALLY INSERTED*/
			
										$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
										$field                   = array("*") ;
										$where                   = array();
										$where['sys_param_type'] = "WORKING_HOURS";
										
										$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
			
										$data['working_hours'] = !EMPTY($working_hours['sys_param_value']) ? $working_hours['sys_param_value']: 8;								
										$data['status']        = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									else
									{
										$data['status'] = ATTENDANCE_STATUS_ABSENT;
									}
								}
							}
						}
						break;

					case 24:
						if($work_schedule)
						{
							$break_hours = $work_schedule['break_hours'];
							$break_time = $work_schedule['break_time'];
							
							$earliest_in   = $work_schedule[$day.'_earliest_in'];
							$latest_in     = $work_schedule[$day.'_latest_in'];
			
							$day_off = (EMPTY($work_schedule[$day.'_earliest_in']) OR IS_NULL($work_schedule[$day.'_earliest_in'])) ? true : false;
									
						}
						else
						{
							$data['status'] = ATTENDANCE_STATUS_ABSENT;
							return $data;
						}

						if($day_off == true)
						{
							$data['status'] = ATTENDANCE_STATUS_REST_DAY;
						}
						else
						{
							$attendance_log = $this->biometric_logs->get_employee_attendance($employee_id,$date);
					
							/*IF EMPLOYEE HAS NO ATTENDANCE, CHECK IF DATE IS HOLIDAY*/
							$tables = array(
								'main'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_work_calendar,
									'alias'		=> 'A',
								),
								't1'	=> array(
									'table'		=> $this->biometric_logs->tbl_param_holiday_types,
									'alias'		=> 'B',
									'type'		=> 'join',
									'condition'	=> 'A.holiday_type_id = B.holiday_type_id',
								)
							);
							$where                 = array();
							$where['A.holiday_date'] = $date;
							$holiday               = $this->biometric_logs->get_biometric_log(array("*"), $tables, $where, FALSE);
			
							if($holiday AND $holiday['holiday_type_id'] != 3)
							{
								$data['status'] = $holiday['attendance_status_id'];
							}
							else
							{
								if($attendance_log['attendance_date'])
								{
									$sixty_seconds       = 60;
									$sixty_minutes       = 60;
									$working_hours = $work_schedule[$day.'_type_of_duty'];

									if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
									{
										$has_break           = (!EMPTY($break_time) ? TRUE : FALSE);
										$earliest_in         = $date.' '.$earliest_in;
										$latest_in           = $date.' '.$latest_in;
										$break_time          = $date.' '.$break_time;
										
										$minutes_to_add   = $break_hours * 60;
										
										$new_time         = new DateTime($break_time);
										$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
										
										$break_time_hours = $new_time->format('Y-m-d H:i:s');
										
										//GET HOLIDAY
										$where                 = array();
										$where['holiday_date'] = $date;
										$holiday2              = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
										
										if(!empty($holiday2))
										{
											$holiday_start_time = $holiday2['holiday_date'] . ' ' . $holiday2['start_time'];
											$holiday_end_time 	= $holiday2['holiday_date'] . ' ' . $holiday2['end_time'];
											
											//EARLY SUSPENSION
											if($holiday_start_time == $holiday_end_time)
											{
												$earliest_in 	= $date . ' ' . '08:00:00';
												$latest_in 		= $date . ' ' . '08:00:00';
											}
											//DURING WORKING HOURS SUSPENSION
											else
											{
												$earliest_in = $latest_in;
											}
											
											$eight_working_hours = (((strtotime($holiday_start_time) - strtotime($latest_in)) / $sixty_seconds) / $sixty_minutes) - $break_hours;
										}
									}
									
									if(empty($attendance_log['time_in']) AND empty($attendance_log['break_in']))
									{
										//get previous work schedule
										$prev_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '-1 day')));
										$day = strtolower(date('D',strtotime($date . '-1 day')));
								
										if($prev_work_schedule[$day.'_type_of_duty'] == 24)
										{
											// get previous day attendance log
											$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
											$table 						= 'employee_attendance';
											$where 						= array();
											$where['employee_id']		= $employee_id;
											$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '-1 day'));
											$result 					= $this->dtr->get_general_data($fields, $table, $where);
											
											//retain present time-in and break-in
											$present_time_in 	= $attendance_log['time_in'];
											$present_break_in 	= $attendance_log['break_in'];
											
											//store previous latest_in
											$earliest_in 	= $prev_work_schedule[$day.'_earliest_in'];
											$latest_in 		= $prev_work_schedule[$day.'_latest_in'];
											
											//store new time-in from previous date
											foreach($result as $res)
											{
												if($res['time_flag'] == 'TI')
												{
													$attendance_log['time_in'] = $res['time_log'];												
												}
												if($res['time_flag'] == 'BI')
												{
													$attendance_log['break_in'] = $res['time_log'];
												}
											}
										}
									}
									
									if(!EMPTY($attendance_log['time_in']))
									{
										$time_in = $attendance_log['time_in'];
									}
									else if(!EMPTY($attendance_log['break_out']))
									{
										$time_in = $attendance_log['break_out'];
									}
									else if(!EMPTY($attendance_log['break_in']))
									{
										$time_in = $attendance_log['break_in'];
									}
									else
									{
										$time_in = $latest_in;
									}
									
									
									$time_in = date('Y-m-d H:i:s', strtotime($time_in));
									
									//late computation
									if($time_in > $latest_in)
									{
										if($has_break)
										{
											if($time_in < $break_time)
											{
												$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
											}
											elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
											{
												$tardiness = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
											}
											else
											{
												$tardiness = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
											}
										}
										else
										{
											$tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
										}
										// $tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
									}
									/* additionational late computation with late break-in*/
									if(!empty($attendance_log['time_in']) AND !empty($attendance_log['break_out']) AND !empty($attendance_log['break_in']) AND !empty($attendance_log['time_out']))
									{
										if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
										{
											$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
										}
									}
									$data['tardiness'] 		= ($tardiness/$sixty_minutes);
									$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
									$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);
									
									//undertime computation
									if($time_in >= $earliest_in AND  $time_in <= $latest_in)
									{
										$time_in_variable = $time_in;
									}
									elseif($time_in < $earliest_in)
									{
										$time_in_variable = $earliest_in;
									}
									else
									{
										$time_in_variable = $latest_in;
									}
									
									if(empty($attendance_log['time_out']) AND empty($attendance_log['break_out']))
									{
										//get next work schedule
										$next_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '+1 day')));
										$day = strtolower(date('D',strtotime($date . '+1 day')));
										
										if($next_work_schedule[$day.'_type_of_duty'] == 24 OR empty($next_work_schedule[$day.'_type_of_duty']))
										{
											//get next day attendance log
											$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
											$table 						= 'employee_attendance';
											$where 						= array();
											$where['employee_id']		= $employee_id;
											$where['attendance_date'] 	= date('Y-m-d', strtotime($attendance_log['attendance_date'] . '+1 day'));
											$result 					= $this->dtr->get_general_data($fields, $table, $where);
											
											if(!empty($result))
											{
												//retain present time-out and break-out
												$present_time_out = $attendance_log['time_out'];
												$present_break_out = $attendance_log['break_out'];

												//store new time-out from next date
												foreach($result as $res)
												{
													if($res['time_flag'] == 'TO')
													{
														$attendance_log['time_out'] = $res['time_log'];												
													}
													if($res['time_flag'] == 'BO')
													{
														$attendance_log['break_out'] = $res['time_log'];
													}
												}									
											}
										}
									}
									
									if(!EMPTY($attendance_log['time_out']))
									{
										$time_out = $attendance_log['time_out'];
									}
									else if(!EMPTY($attendance_log['break_out']))
									{
										$time_out = $attendance_log['break_out'];
									}
									else if(!EMPTY($attendance_log['break_in']))
									{
										$time_out = $attendance_log['break_in'];
									}
									else
									{
										$time_out = $time_in_variable;
									}
									
									$time_out = date('Y-m-d H:i:s', strtotime($time_out));

									if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
									}
									/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									elseif($has_break AND $time_out >= $latest_in AND $time_out <= $break_time)
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}
									/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									else
									{
										$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									}

									// $undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									
									if($undertime_temp > 0)
									{
										$undertime = ($undertime_temp / $sixty_seconds);

										$data['undertime']      = ($undertime > 0  AND $undertime <= ($working_hours * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
										$data['undertime_hour'] = floor($undertime / $sixty_minutes);
										$data['undertime_min']  = ($undertime%$sixty_minutes);

										if(!empty($present_time_in) OR !empty($present_break_in))
										{
											if($data['undertime_hour'] > $working_hours)
											{
												$data['undertime']      = $working_hours;
												$data['undertime_hour'] = $working_hours;
												$data['undertime_min']  = 0;
												
												$data['tardiness']      = 0;
												$data['tardiness_hour'] = 0;
												$data['tardiness_min']  = 0;
											}								
										}
									}

									if($holiday)
									{
										$data['status'] = $holiday['attendance_status_id'];
									}
									else{
										$data['status'] = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									
									//hours work computation
									$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
									$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
									$working_hours 			= $working_hours - ($undertime + $tardiness);
									$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
								}
								else
								{
									// $with_biometric = $this->biometric_logs->check_no_biometric($employee_id);
									// if($with_biometric['cnt'] > 0)
									if($work_schedule['work_schedule_id'] == 61)
									{
										/*IF EMPLOYEE HAS NO BIOMETRIC, REGULAR DAY WILL BE AUTOMATICALLY INSERTED*/
			
										$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
										$field                   = array("*") ;
										$where                   = array();
										$where['sys_param_type'] = "WORKING_HOURS";
										
										$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
			
										$data['working_hours'] = !EMPTY($working_hours['sys_param_value']) ? $working_hours['sys_param_value']: 8;								
										$data['status']        = ATTENDANCE_STATUS_REGULAR_DAY;
									}
									else
									{
										$data['status'] = ATTENDANCE_STATUS_ABSENT;
									}
								}
							}
						}
						break;
				}
			}
			return $data;
		}
		catch(Exception $e)
		{
			$message = $e->getMessage();
			RLog::error($message);
			throw $e;
		}
	}
	private function _check_no_work($date_from = NULL,$date_to =NULL,$employee_id)
	{
		try
		{	
			$dates       = array();
			$date_from   = date('Y-m-d',strtotime ($date_from));
			$date_to     = date('Y-m-d',strtotime ($date_to));
			$day_off = false;
			
			$active_date = $date_from;

			if($date_from <= $date_to )
			{
				while($active_date <= $date_to )
				{
					$day    = date('N',strtotime($active_date));

					$work_schedule = $this->biometric_logs->get_employee_work_schedule($employee_id,$active_date);
			
					$day           = date('D',strtotime($date));

					if($work_schedule)
					{
						switch ($day) {
							case 'mon':
								if(EMPTY($work_schedule['mon_earliest_in']))
									$day_off = true;
								break;
							case 'tue':
								if(EMPTY($work_schedule['tue_earliest_in']))
									$day_off = true;
								break;
							case 'wed':
								if(EMPTY($work_schedule['wed_earliest_in']))
									$day_off = true;
								break;
							case 'thu':
								if(EMPTY($work_schedule['thu_earliest_in']))
									$day_off = true;
								break;
							case 'fri':
								if(EMPTY($work_schedule['fri_earliest_in']))
									$day_off = true;
								break;
							case 'sat':
								if(EMPTY($work_schedule['sat_earliest_in']))
									$day_off = true;
								break;
							case 'sun':
								if(EMPTY($work_schedule['sun_earliest_in']))
									$day_off = true;
								break;
						}
					}
					if($day_off)
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
			
			$message = $e->getMessage();
			RLog::error($message);
		}
	}

	public function modal_generate_attendance($action = NULL, $id = NULL, $token = NULL, $salt = NULL, $module = NULL)	
	{
		try
		{
			$data 					= array();
			$resources 				= array();
			$data['action']			= $action;
			$data['id']				= $id;
			$data['salt']			= $salt;
			$data['token']			= $token;
			$data['module']			= $module;

			$resources['load_css'] 	= array(CSS_DATETIMEPICKER, CSS_SELECTIZE);
			$resources['load_js']	= array(JS_DATETIMEPICKER, JS_SELECTIZE);

			$fields 					= array('employee_id', "CONCAT(last_name, ', ', first_name, IF(ext_name=''  OR ext_name IS NULL, '', CONCAT(' ', ext_name)), IF((middle_name='NA' OR middle_name='N/A' OR middle_name='-' OR middle_name='/' OR middle_name IS NULL), '', CONCAT(' ', middle_name))) as fullname",);
			$table 						= 'employee_personal_info';
			$where 						= array();
			$where['employee_id']       = array($value = array('1','3680','3681'), array("NOT IN"));
			$order_by					= array('last_name'=>'asc');
			$data['employee_list'] 					= $this->dtr->get_general_data($fields, $table, $where, TRUE, $order_by);

			$this->load->view('biometric_logs/modals/modal_generate_employee_attendance', $data);
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
	public function process_generate_employee_attendance_period_dtl()
	{
		try
		{

			$status 		= FALSE;
			$message		= "Attendance Generation Failed";

			$params			= get_params();
			$employee_id 	= $params['employee']; 
			$date_availability = modules::load('main/attendance_late_undertime');
			$attendance_dates = $date_availability->check_working_days_with_nod($employee_id,$params['date_range_from'],$params['date_range_to']);
			if($attendance_dates)
			{
				$employee_list 	= $this->biometric_logs->get_attendance_specific_employee_dtl($employee_id);
				
				
				$table                   = $this->biometric_logs->db_core.".".$this->biometric_logs->tbl_sys_param;			
				$field                   = array("*") ;
				$where                   = array();
				$where['sys_param_type'] = "WORKING_HOURS";
				
				$working_hours           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);

				foreach ($attendance_dates as $dates) {
					$period_dtls = array();

					foreach ($employee_list as $emp) {

						$field                    = array("employee_id") ;
						$where                    = array();
						$where['employee_id']     = $emp['employee_id'];
						$where['attendance_date'] = $dates;
						$table                    = $this->biometric_logs->tbl_attendance_period_dtl;
						$has_prev_record           = $this->biometric_logs->get_biometric_log($field, $table, $where, FALSE);
						
						// ====================== jendaigo : start : addt'l condition for attendance_status_id tagging ============= //
						$work_schedule 			= $this->biometric_logs->get_employee_work_schedule($emp['employee_id'],$dates);
						$attendance_day         = strtolower(date('D',strtotime($dates)));

						if($emp['start_date'] > $work_schedule['start_date'])
						{
							if($attendance_day == 'sat' OR $attendance_day == 'sun' )
								$attendance_status		= ATTENDANCE_STATUS_REST_DAY;
							else
								$attendance_status		= ATTENDANCE_STATUS_ABSENT;
						}
						else
						{
							$attendance_status		= ATTENDANCE_STATUS_REGULAR_DAY;
						}
						// ====================== jendaigo : end : addt'l condition for attendance_status_id tagging ============= //

						if(EMPTY($has_prev_record))
						{
							$period_dtls[] = array(
								"employee_id"          => $emp['employee_id'],
								"attendance_date"      => $dates,
								"basic_hours"          => $working_hours['sys_param_value'],
								"working_hours"        => 0,
								"tardiness"            => 0,
								"tardiness_hr"         => 0,
								"tardiness_min"        => 0,
								"undertime"            => 0,
								"undertime_hr"         => 0,
								"undertime_min"        => 0,
								"attendance_status_id" => $attendance_status
								);
						}

					}
					
					if($period_dtls)
					{
						$table = $this->biometric_logs->tbl_attendance_period_dtl;
						$this->biometric_logs->insert_biometric_log($table,$period_dtls,FALSE);
					}
					
				}
				$status = true;
				$message = "Successfully generated attendance";
			}

			$data					= array();
			$data['status']			= $status;
			$data['message']		= $message;
			echo json_encode($data);
						
		}
		catch(PDOException $e)
		{
			
			throw $e;
		}
		catch(Exception $e)
		{
			
			throw $e;
		}
		
	}

}


/* End of file Biometric_logs.php */
/* Location: ./application/modules/main/controllers/Biometric_logs.php */