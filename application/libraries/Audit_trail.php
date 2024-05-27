<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Audit_trail {
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('audit_trail_model');
	}
	
	/**
	 * $action - action made by the user, set of actions were placed in constant.php.
	 * $module - module in the system where the specific action/activity was made.
	 * $activity - list of activities will be referenced to param_activities table.
	 * $prev_detail = original value of a field before updating, this is saved to track what was changed if any.
	 * $curr_detail = current value of a field.
	 */
	
	public function log_audit_trail($activity, $module, $prev_detail = array(), $curr_detail = array(), $action_arr = array(), $table_arr = array(), $schema_arr = array())
	{	
		try 
		{
			
			if(EMPTY($activity))
				throw new Exception('Activity was not set for audit trail.');
			
			if(EMPTY($module))
				throw new Exception('Module was not set for audit trail.');
			
			$trail 				= array();			
			$trail["activity"] 	= $activity;
			$trail["module"] 	= $module;
			$change_log 		= 0; //jendaigo:change initialization position
			
			$id = $this->CI->audit_trail_model->insert_audit_trail($trail);
			
			for($i = 0; $i < COUNT($prev_detail); $i++)
			{
				
				$prev_data 	= $prev_detail[$i];
				$curr_data 	= $curr_detail[$i];
				$action		= $action_arr[$i];
				$table		= $table_arr[$i];
				$schema		= $schema_arr[$i];				

				switch($action)
				{
					case AUDIT_INSERT:
						
						foreach($curr_data as $curr_data)
						{
							while(list($key, $curr_val) = each($curr_data))
							{
								$field		= $table . "." . $key;
								$params[]	= array(
									'audit_trail_id'	=> $id,
									'field'				=> $field,
									'prev_detail'		=> '',
									'curr_detail'		=> $curr_val,
									'action'			=> $action,
									'trail_schema'		=> $schema
								);
							}	
						}
					break;
					
					case AUDIT_UPDATE:
						
						$index = 0;
						
						foreach($curr_data as $curr_data)
						{	
							while(list($key, $val) = each($curr_data))
							{
								// $change_log = 0;
								$prev_val = $prev_data[$index][$key];
								$curr_val = $val;
							
								$field = $table . "." . $key;
							
								// IF PREVIOUS VALUE IS NOT EQUAL TO CURRENT VALUE, LOG TO AUDIT TRAIL DETAIL
								// if($prev_val != $curr_avl)
								if($prev_val != $curr_val) //jendaigo: modify curr_val variable
								{
									$change_log = 1;
									$params[] = array(
										'audit_trail_id'	=> $id,
										'field'				=> $field,
										'prev_detail'		=> $prev_val,
										'curr_detail'		=> $curr_val,
										'action'			=> $action,
										'trail_schema'		=> $schema
									);
								}
							}
							$index++;
						}
						
					break;
					
					case AUDIT_DELETE:
						
						foreach($prev_data as $prev_data)
						{
							while(list($key, $prev_val) = each($prev_data))
							{
								$field = $table . "." . $key;
								
								$params[] = array(
									'audit_trail_id'	=> $id,
									'field'				=> $field,
									'prev_detail'		=> $prev_val,
									'curr_detail'		=> '',
									'action'			=> $action,
									'trail_schema'		=> $schema
								);
							}
						}
					break;
					
					case AUDIT_PROCESS:
						$params = array();
					break;
					
				}
				
			}
			
			if(EMPTY($params) && ($action != AUDIT_UPDATE && $action != AUDIT_PROCESS ) )
				throw new Exception('Parameters were not set in the audit trail.');

			if($action == AUDIT_INSERT ||  $action == AUDIT_DELETE || ($action == AUDIT_UPDATE && ($change_log)))
				$this->CI->audit_trail_model->insert_audit_trail_detail($params);
			
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