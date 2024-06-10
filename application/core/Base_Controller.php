<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends MX_Controller
{
	const PDF_OUTPUT_INLINE		= 'I';
	const PDF_OUTPUT_DOWNLOAD	= 'D';
	const PDF_OUTPUT_LOCAL_FILE	= 'F';
	const PDF_OUTPUT_STRING		= 'S';
	
	const MODULE				= 'default';
	const MYSQL_ERR_PREFIX 		= 'mysql_err_';
	const MYSQL_ERR_DEFAULT 	= 'mysql_err_default';
	
	private $user_permissions 	= array();
	public	$scope				= array();
	
	// THIS VARIABLE IS USED TO ENCRYPT THE PRIMARY ID OR CODE
	public $hash_code					= '%$';
	private static $hash_code_static	= '%$';
	
	
	public $organization = array();
	
	public $user_id 	=  0;	
	public $user_roles	= array();
	
	public function __construct() 
	{
		parent::__construct();
		
		
		$this->_construct_rlog();
		
				
		//$this->load->model(PROJECT_CORE . '/permissions_model');
		
		$this->_get_log_user_roles();
		
		$this->_set_organization();
		 
		
		
	}
	
	
	private function _construct_rlog()
	{
		// Getting values from the configuration
		$level			= $this->config->item('rlog_level');
		$enable			= $this->config->item('rlog_enable');
		$error_handler	= $this->config->item('rlog_error_handler');
		$location		= realpath(APPPATH) . DS . 'logs' . DS . static::MODULE;
		
		// Setting up RLog
		RLog::location($location);
		RLog::level($level);
		RLog::enable($enable);
		RLog::setErrorHandler($error_handler);
		
	}
	
	
	private function _set_organization()
	{
		$this->load->model(PROJECT_CORE . '/orgs_model');
		
		$this->organization = $this->orgs_model->get_org_details(MAIN_ORG_ID);
		
	}
	
	// ADDED BY REJ : USE FOR KEY IN MYSQL CONCAAT A HASH KEY
	//$name = $field_name
	protected function get_hash_key($name)
	{
		return self::_get_hash_key($name);
	}
	
	private static function _get_hash_key($name)
	{
	
		return "md5(CONCAT('" . self::$hash_code_static . "', $name, '" . self::$hash_code_static . "'))";
	}
	
	
	/**
	 * 
	 * CONVERT INCLUDING NOT ASCII e.g. Ñ
	 */
	
	protected function convert_ucwords($name)
	{
		return mb_convert_case($name, MB_CASE_TITLE, "UTF-8");
	}
	
	protected function convert_strtolower($name)
	{
		return mb_strtolower($name, "UTF-8");
	}
	
	protected function convert_strtoupper($name)
	{
		return mb_strtolower($name, "UTF-8");
	}
	
	private function _get_log_user_roles()
	{
		$this->load->model(PROJECT_CORE . '/roles_model');
				
		$this->user_id		= $this->session->userdata('user_id');
		$this->user_roles	= $this->roles_model->get_user_roles($this->user_id);
		// $this->user_roles	= $this->roles_model->get_user_roles($this->hash($this->user_id));
	}
	
	public function extract_user_roles()
	{
		$roles = array();
		
		
		FOREACH($this->user_roles as $role)
		{
			$roles[] = $role['role_code'];				
		}
		
		return $roles;
	}

	public function check_permission($module_id, $button_id = NULL, $redirect = FALSE)
	{
		
 		return TRUE;
		$roles = $this->user_roles;
		if(EMPTY($roles))
		{
			return FALSE;
		}
		
		
		$flag = FALSE;

		
		foreach($roles AS $val)
		{
			IF(!EMPTY($button_id))
			{
				if(!EMPTY($this->user_permissions[$val['role_code']][$module_id][$button_id]))
				{
					$flag = TRUE;				
				}	
			}
			ELSEIF(ISSET($this->user_permissions[$val['role_code']][$module_id]))
			{
				$flag = TRUE;
			}
		}
		
		return $flag;


	}
	
	
	protected function get_scope_agencies($module_id)
	{
		try
		{
			$data					= array();
			
			// IF SCOPE IS NOT SPECIFIED
			if(EMPTY($this->scope[$module_id]))
			{
				throw new Exception($this->lang->line('err_unauthorized_access'));
			}
			
			
			switch($this->scope[$module_id])
			{
				case SCOPE_REGION_WIDE:
				
				break;
				
				case SCOPE_OWN_AGENCY:
					$data['office_id']	= array($this->session->userdata('office_id'));
					
				break;
				
				default:
					$data['office_id']	= array();
					$data['region']		= "";
				break;
			}
						
			return $data;
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
	
	protected function check_scope($region, $module_id)
	{
		try
		{
			if(EMPTY($this->scope[$module_id]))
			{
				return FALSE;
			}
				
				
			switch($this->scope[$module_id])
			{
				case SCOPE_SYSTEM_WIDE:
					return TRUE;
					break;
				case SCOPE_REGION_WIDE:
					return TRUE;
					break;
			
				case SCOPE_OWN_AGENCY:
					return $this->check_office_scope($region);
					break;
			
				default:
					return FALSE;
					break;
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
	
	protected function check_office_scope($region)
	{
		try
		{
			// CHECK IF LOG USER OFFICE EXIST IN OFFICE OF SPECIFIED REGION
			$this->load->model('user_managment/activity_task', 'service');
			$fields		= array(
					'office_id'
			);
			$table						= $this->service->table_offices;
			$where['hlurb_region_code']	= $region;
			$offices					= $this->service->get_service_column($fields, $table, 0, $where, TRUE);
			
			if(!EMPTY($offices))
			{
				$log_offices			= $this->session->userdata('office_id');
				$result					= array_intersect($offices, $log_offices);
				if(!EMPTY($result))
					return TRUE;
			}
			else
			{
				throw new Exception($this->lang->line('no_office_in_region'));
			}
			return FALSE;
			RLog::info();
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
	
	protected function get_region_wide()
	{
		try
		{
			
			$result = $this->permissions_model->get_offices();

			return $result;
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
	
	

	protected function pdf($filename, $html, $portrait = TRUE, $output = SYSAD_Controller::PDF_OUTPUT_STRING, $header = NULL, $footer = NULL, $margin_left = 10, $margin_right = 10, $margin_top = 10, $margin_bottom = 10, $margin_header=10, $margin_footer=10)
	{
		// Setting memory limit
		try
		{
			ini_set('memory_limit', '1024M');
	
			// Loading PDF library
			$this->load->library('pdf');
		
			// Create PDF object
			
			$paper = (!$portrait) ? "A4-L" : "A4";
			
			
			$pdf = $this->pdf->create("en-GB-x",$paper,"","",$margin_left,$margin_right,$margin_top,$margin_bottom,$margin_header,$margin_footer, ((!$portrait) ? '-L' : ''));
			//$pdf = $this->pdf->create('en-GB-x', 'A4' . ((!$portrait) ? '-L' : ''));
			
			if(ISSET($header) && ! EMPTY($header) OR ISSET($footer) && ! EMPTY($footer))
			{
				$pdf->SetHTMLHeader($header);
				$pdf->SetHTMLFooter($footer);	
			}
			
			// Write HTML to pdf			
			$pdf->WriteHTML($html);	
// 			$pdf->debug = true;
			
			if($output == 'F')
			{
				$pdf->Output($filename, $output);
				return $filename;
			}
			else				
				return $pdf->Output($filename, $output);
		}
		catch(Exception $e)
		{
			return $e->getMessage();
		}
		
	}
	

	protected function set_report_header($colspan=0)
	{

		$name		= $this->organization['name'];
		$address	= $this->organization['address'];
		$phone		= $this->organization['phone'];
		
		if($colspan)
		{
			$header = '
			<table width="100%">
				<thead>
					<tr>
						<td colspan="'.$colspan.'"><font size="3"><b>'.$name.'</b></font></td>
					</tr>
					
					<tr>
						<td colspan="'.$colspan.'"><font size="2">'.$address.'</font></td>
					</tr>
					
					<tr>
						<td colspan="'.$colspan.'"><font size="2">Tel. Nos '.$phone.'</font></td>
					</tr>
				</thead>
			</table>';
		}
		else
		{
			$header = '<font size="3"><b>'.$name.'</b></font>';
			$header.= '<font size="2">';
			$header.= '<br>' . $address;
			$header.= '<br>Tel. Nos ' . $phone;
			$header.= '</font>';
		}

		return $header;

				
	}

	protected function set_report_footer()
	{
				
		$short_name		= $this->organization['short_name'];
		$short_name		= "DOH - PTIS";
		
		$footer = '<hr style="margin_bottom:-5px;">';
		$footer .= '<table width="100%">';
		$footer .= '<tr>';
		$footer .= '<td align="left"><font size="2"><b>Run Time : </b>'. date('m/d/Y g:i:s a') .'</font></td>';
		$footer .= '<td align="left"><font size="2"><b>Generated By : </b>'.$short_name.'</font></td>';
		$footer .= '<td align="right"><font size="2">Page {PAGENO} of {nb}<font size="2"></td>';
		$footer .= '</tr></table>';

		return $footer;
	}
	
	
	protected function hash($code)
	{
		return md5($this->hash_code . $code . $this->hash_code);
	}
	
	protected function check_required_fields($params, $fields)
	{
		try 
		{
			$str 				= '';
			// $val[0] // NAME OF THE FIELD
			// $val[1] // NAME OF THE GROUP
			$group_count		= array();
			$group_container	= array();
			$concat_str			= '';
			foreach($fields AS $key => $val)
			{
				$name		= (is_array($val)) ? $val[0] : $val; // IF VAL IS ARRAY OR NOT
				$group		= (is_array($val) && !EMPTY($val[1])) ? $val[1] : NULL;
				
				if(is_array($params[$key]))
				{
					
					foreach($params[$key] AS $k => $v)
					{
						
						$k	 = (is_numeric($k)) ? $k+1 : $k;
						// IF GROUP
						if(!EMPTY($group))
						{
// 							RLog::info('LINE 340 - - - -' . $k . ' name' . $name . ' value ' . $v . ' test ' . json_encode(EMPTY($v)));
							$concat_str[$group][$k]			.= EMPTY($v) ? '<b>' . $name . '</b> in row ' . $k . ' is required.<br>' : '';
							$group_container[$group][$k][] 	= EMPTY($v) ?  NULL : $v;
							//$group_count[$group]		+= 0;
							$group_count[$group][$k]		= !EMPTY($group_count[$group][$k]) ? $group_count[$group][$k] : 0;
							$group_count[$group][$k]		= $group_count[$group][$k] + 1;
						}
// 						$group_container[$group][]	= "TEST";
// 						$group_container[$group][]	= NULL;
						// FIX ISSUE HERE
// 						RLog::info('LINE 327 - - - ' . json_encode($group_container[$group][$k]));
// 						RLog::info('LINE 328 - - - ' . json_encode(array_filter($group_container[$group][$k])));
// 						RLog::info('LINE 329 - - - ' . json_encode(count(array_filter($group_container[$group][$k]))));
// 						RLog::info('LINE 330 - - - ' . json_encode($group_count[$group][$k]));
// 						RLog::info('LINE 331 - - - - ' . $concat_str[$group][$k]);
// 						throw new Exception('TEST');
// 						exit();
						$filtered_group				= (!EMPTY($group) && !EMPTY($group_container[$group][$k])) ? count(array_filter($group_container[$group][$k])) : NULL;
						if((EMPTY($v) && EMPTY($group)))
						{
							$str 	.= '<b>' . $name . '</b> in row ' . $k . ' is required.<br>';
						}
						elseif(!EMPTY($filtered_group) && $group_count[$group][$k] != $filtered_group)
						{
							$str 	.= $concat_str[$group][$k];
							unset($concat_str[$group][$k]);
						}
						// IF STILL AN ARRAY ASSUME $k AS TABLE NAME IN CLIENT SIDE
						if(is_array($v))
						{
							foreach($v AS $key2 => $val2)
							{
								
								$key2	 = (is_numeric($key2)) ? $key2+1 : $key2;
								if(!EMPTY($group))
								{
									$concat_str[$group][$k][$key2]			.= EMPTY($val2) ? ' <b>' . $name . '</b> in ' . $k . ' table, row ' . $key2 . ' is required.<br>' : '';
									$group_container[$group][$k][$key2][]	= EMPTY($val2) ?  NULL : $val2;
									$group_count[$group][$k][$key2]			= !EMPTY($group_count[$group][$k]) ? $group_count[$group][$key2] : 0;
									$group_count[$group][$k][$key2]			= $group_count[$group][$k][$key2] + 1;
								}
								
								$filtered_group								= (!EMPTY($group) && !EMPTY($group_container[$group][$k][$key2])) ? 
																			count(array_filter($group_container[$group][$k][$key2])) : NULL;
								
								if(EMPTY($val2) && EMPTY($group))
								{
									
									$str 	.= ' <b>' . $name . '</b> in ' . $k . ' table, row ' . $key2 . ' is required.<br>';
								}
								elseif(!EMPTY($filtered_group) && $group_count[$group] != $filtered_group)
								{
									$str 	.= $concat_str[$group][$k][$key2];
									unset($concat_str[$group][$k][$key2]);
								}
							}
						}
					}
				}
				else
				{
					// IF GROUP
					if(!EMPTY($group))
					{
						$concat_str[$group]			.= EMPTY($v) ? '<b>' . $name . '</b> is required.<br>' : '';
						$group_container[$group][]	= EMPTY($params[$key]) ?  NULL : $params[$key];
						$group_count[$group]		= !EMPTY($group_count[$group]) ? $group_count[$group] : 0;
						$group_count[$group]		= $group_count[$group] + 1;
					}
					
					$filtered_group		= !EMPTY($group_container[$group]) ? count(array_filter($group_container[$group])) : NULL;
//					RLog::info('LINE 412 - - -- ' . json_encode($params[$key]));
					if(EMPTY($params[$key]) && EMPTY($group))
					{
						// IF NOT IN GROUP OR ONE FIELD IN GROUP IS EMPTY & OTHER IS NOT
						$str 			.= '<b>' . $name . '</b> is required.<br>';
					}
					elseif(!EMPTY($filtered_group) && $group_count[$group] != $filtered_group)
					{
						$str			.= $concat_str[$group];
						unset($concat_str[$group]);
					}
				}
			}
			if(!EMPTY($str))
			{
				throw new Exception($str);
			}
				
		} 
		catch (Exception $e) 
		{
			throw $e;
		}
	}
		
	// function from BCDA
	
	/**
	 * @return valid value
	 * Ex.
	 * 		input = 1,000.00
	 * 		_validate_* will return 1000.00
	 */
	public function validate_inputs($arr_inputs, $arr_validations)
	{
		
		try
		{
			$valid_inputs = array();
			
			FOREACH($arr_inputs AS $key => $value)
			{
				$field_validation = !EMPTY($arr_validations[$key]) ? $arr_validations[$key] : '';
				
				if( ! ISSET($field_validation) OR EMPTY($field_validation))
				{
					//RLog::info('cannot find validation parameter for ' . $key);
					continue; //if not specified continue
				}
				
				IF( ! ISSET($field_validation['data_type']) OR EMPTY($field_validation['data_type']))
				{
					throw new Exception($this->lang->line('err_invalid_data'));
				}
				
				if(EMPTY($field_validation['name']))
				{
					throw new Exception('Validation incomplete for ' . $key . ' parameter name missing');
				}
				
				SWITCH (strtolower($field_validation['data_type']))
				{
					CASE 'string':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_string($v, $field_validation);
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_string($value, $field_validation);
						}
						break;
						
					CASE 'password':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_string($v, $field_validation, TRUE);
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_string($value, $field_validation, TRUE);
						}
						break;
						
					CASE 'digit':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
// 								
								$valid_inputs[$key][$k] = $this->_validate_digits($v, $field_validation);
							
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_digits($value, $field_validation);
						}
						break;
						
					CASE 'amount':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_amount($v, $field_validation);
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_amount($value, $field_validation);
						}
						break;
						
					CASE 'date':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_date($v, $field_validation);
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_date($value, $field_validation);
						}
						break;
						
					CASE 'enum':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_enum($v, $field_validation);
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_enum($value, $field_validation);
						}
						break;
						
					CASE 'time':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_time($v, $field_validation);
							}
						}
						else 
						{
							$valid_inputs[$key] = $this->_validate_time($value, $field_validation);
						}
						break;
						
					CASE 'email':
						if(is_array($value))
						{
							foreach ($value AS $k=>$v)
							{
								$valid_inputs[$key][$k] = $this->_validate_email($v);
							}
						}
						else
						{
							$valid_inputs[$key] = $this->_validate_email($value);
						}
						break;
						
					DEFAULT:
						$valid_inputs[$key] = $value;
						break;
				}
			}
		}
		catch(Exception $e)
		{
			throw $e;
		}
		return $valid_inputs;
	}
	
	private function _validate_email($input)
	{
		try
		{
			if(ISSET($input) && ! filter_var($input, FILTER_VALIDATE_EMAIL))
			{
				throw new Exception('Please enter a valid email address in ' . $validation['name']);
			}
			return $input;
		}
		catch (Exception $e)
		{
			throw $e;
		}
		
	}
	
	private function _validate_string($input, $validation, $password_flag = FALSE)
	{
		try
		{
			if(EMPTY($password_flag)) //$password_flag === FALSE
			{
				$input = trim($input);
			}
			if(ISSET($validation['min_len']) && strlen($input) < $validation['min_len'])
			{
				throw new Exception($this->lang->line('err_min_len') . $validation['min_len'] . ' character/s for ' . $validation['name']);
			}
			if(ISSET($validation['max_len']) && strlen($input) > $validation['max_len'])
			{
				throw new Exception($this->lang->line('err_max_len') . $validation['max_len'] . ' character/s for ' . $validation['name']);
			}
			if(ISSET($validation['accepted_chars']))
			{
				if(!preg_match($validation['accepted_chars'], $input))
				{
					throw new Exception($this->lang->line('err_invalid_data') . ' for ' . $validation['name']);
				}
			}
			
			return filter_var($input, FILTER_SANITIZE_STRIPPED);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_digits($input, $validation)
	{
		if(EMPTY($input))
		{
			return $input;
		}
	
		try
		{
			$input		= str_replace(',','', $input);
			if( ! is_numeric($input))
			{
				throw new Exception('Please enter a valid number in ' . $validation['name'] . '.');
			}
	
			if(ISSET($validation['min']))
			{
				if(! is_numeric($input))
				{
					throw new Exception("Minimum amount is not a valid number in " . $validation['name'] . '.');
				}
					
				if($input > $validation['max'])
				{
					throw new Exception('Please enter a value higher than ' . number_format($validation['max'], 2) . ' in ' . $validation['name'] . '.');
				}
			}
	
			if(ISSET($validation['max']))
			{
				if(! is_numeric($input))
				{
					throw new Exception("Maximum amount is not a valid number" . $validation['name']);
				}
					
				if($input > $validation['max'])
				{
					throw new Exception('Please enter a value lower than ' . number_format($validation['max'], 2) . ' in ' . $validation['name'] . '.');
				}
	
			}
				
			if(ISSET($validation['min_len']) && strlen($input) < $validation['min_len'])
			{
				throw new Exception($this->lang->line('err_min_len') . $validation['min_len'] . ' character/s for ' . $validation['name']);
			}
			if(ISSET($validation['max_len']) && strlen($input) > $validation['max_len'])
			{
				throw new Exception($this->lang->line('err_max_len') . $validation['max_len'] . ' character/s for ' . $validation['name']);
			}
						
			return $input;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_amount($input, $validation)
	{
		if(EMPTY($input))
		{
			return $input;
		}
	
		try
		{
			$input = trim(str_replace(',', '', $input));
			if( ! is_numeric($input))
			{
				throw new Exception('Please enter a valid amount in ' . $validation['name'] . '.');
			}
			
			if(ISSET($validation['decimal']))
			{
				$input = round($input, $validation['decimal'], PHP_ROUND_HALF_UP);
			}
				
			if( ! is_numeric($input))
			{
				throw new Exception('Please enter a valid number in ' . $validation['name'] . '.');
			}
				
			if(ISSET($validation['min']))
			{
				if(! is_numeric($input))
				{
					throw new Exception("Minimum amount is not a valid number for " . $validation['name'] . '.');
				}
	
				if($input < $validation['min'])
				{
					throw new Exception('Please enter a value higher than ' . number_format($validation['min'], 2) . ' in ' . $validation['name'] . '.');
				}
			}
				
			if(ISSET($validation['max']))
			{
				if(! is_numeric($input))
				{
					throw new Exception("Maximum amount is not a valid number for " . $validation['name'] . '.');
				}
	
				if($input > $validation['max'])
				{
					throw new Exception('Please enter a value lower than ' . number_format($validation['max'], 2) . ' in ' . $validation['name'] . '.');
				}
			}
				
			if(ISSET($validation['min_len']) && strlen($input) < $validation['min_len'])
			{
				throw new Exception($this->lang->line('err_min_len') . $validation['min_len'] . ' character/s for ' . $validation['name']);
			}
			if(ISSET($validation['max_len']) && strlen($input) > $validation['max_len'])
			{
				throw new Exception($this->lang->line('err_max_len') . $validation['max_len'] . ' character/s for ' . $validation['name']);
			}
			
			
			return $input;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_date($input, $validation)
	{
		if(EMPTY($input))
		{
			return $input;
		}
		try
		{
			$new_var = str_replace('.', '/', $input);
			$new_var = str_replace('-', '/', $input);

			/*
			|---------------------------------------------------------------------
			| CHECKS INPUT DATE
			|---------------------------------------------------------------------
			| The month is between 1 and 12 inclusive.
			| The day is within the allowed number of days for the given month. Leap years are taken into consideration.
			| The year is between 1 and 32767 inclusive.
			*/
			// START
			$input_date = date_parse_from_format("Y/m/d", $new_var);

			$valid_input_date = (checkdate($input_date['month'], $input_date['day'], $input_date['year']) ? 'VALID' : 'INVALID');
			
			if($valid_input_date == 'INVALID')
			{
				throw new Exception('Please enter a valid date in ' . $validation['name'] . '.');
			}
			// END

			$valid_date = date('Y-m-d H:i:s', strtotime($new_var));
				
			$input	= date('Y-m-d', strtotime($input));
			if($valid_date === FALSE)
			{
				throw new Exception('Please enter a valid date in ' . $validation['name'] . '.');
			}
	
			if(ISSET($validation['min_date']))
			{
				$min_date = date('Y-m-d', strtotime($validation['min_date']));
				if(EMPTY($min_date)) // min_date === FALSE
				{
					throw new Exception("Minimum Date is invalid in " . $validation['name'] . '.');
				}
	
				//	echo strtotime($input) . '  ' . strtotime($validation['min_date']) . " ASDADSADSA " . (strtotime($valid_date) <= strtotime($validation['min_date'])) ;
				RLog::info('LINE 805 - - - -' . json_encode($validation));
				if(EMPTY($validation['min_date']) OR !EMPTY($validation['compare']))
				{
					RLog::info('LINE 808 - - - -' . strtotime($input) . '<' . strtotime($min_date));
					if(strtotime($input) < strtotime($min_date))
					{
						$str = !EMPTY($validation['compare']) ?  'date of ' . $validation['compare']: 'minimum date('. format_date($validation['min_date']) . ')';
						throw new Exception('Entered date in ' . $validation['name'] . ' receded the ' . $str);
					}
				}
				else
				{
					if($validation['compare']	!= ENUM_NO)
					{
						if(strtotime($input) <= strtotime($min_date))
						{
							throw new Exception('Please enter a valid date range between '. format_date($validation['min_date']) . ' - ' . format_date($validation['max_date']) . ' in ' . $validation['name'] . '.');
						}
					}
				}
			}
	
			if(ISSET($validation['max_date']))
			{
				$max_date = date('Y-m-d', strtotime($validation['max_date']));
				if(EMPTY($max_date)) // min_date === FALSE
				{
					throw new Exception("Maximum Date is invalid in " . $validation['name'] . '.');
				}
	
	
				if(EMPTY($validation['min_date']) OR !EMPTY($validation['compare']))
				{
					if(strtotime($input) > strtotime($max_date) )
					{
						$str = !EMPTY($validation['compare']) ?  'date of ' . $validation['compare']: 'maximum date('. format_date($validation['max_date']) . ')';
						throw new Exception('Entered date in ' . $validation['name'] . ' exceeded the ' . $str);
					}
				}
				else
				{
					if($validation['compare']	!= ENUM_NO)
					{
						if(strtotime($input) >= strtotime($max_date) )
						{
							throw new Exception('Please enter a valid date range between '. format_date($validation['min_date']) . ' - ' . format_date($validation['max_date']) . ' in ' . $validation['name'] . '.');
						}
					}
				}
			}
	
			return $valid_date;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_enum($input, $validation)
	{
		if(EMPTY($input))
		{
			return $input;
		}
		try
		{
			$input = trim($input);
			if(ISSET($validation['allowed_values']) && ! in_array($input, $validation['allowed_values']))
			{
				throw new Exception('Invalid data in ' . $validation['name'] . '.');
			}
	
			return $input;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	private function _validate_time($input, $validation)
	{
		if(EMPTY($input))
		{
			return $input;
		}
		try
		{
			$valid_date = date_create($input);
			if($valid_date === FALSE)
			{
				throw new Exception('Please enter a valid time in ' . $validation['name'] . '.');
			}
	
			$format_pattern = (ISSET($validation['format']) && ! EMPTY($validation['format'])) ? $validation['format'] : 'H:i:s';
			$valid_date = date_format($valid_date, $format_pattern);
	
			return $valid_date;
		}
		catch(Exception $var)
		{
			throw $var;
		}
	}
	
	//end function from bcda
	
	public function upload_attachment($config, $input_name)
	{
	
		try {
				
			$this->load->library('upload', $config);
			RLog::info(json_encode($config['upload_path']) . ' line 935 ' . json_encode(!is_dir($config['upload_path'])));
			if (!is_dir($config['upload_path']))
				mkdir($config['upload_path'], 0777, true);
	
			if (!$this->upload->do_upload($input_name))
				throw new Exception($this->upload->display_errors());
				
			$upload_data = $this->upload->data();
	
			return $upload_data;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	
	}
	
	
	public function rlog_error($exception, $return_message = FALSE)
	{
	
		IF($return_message)
			$message = $exception->getMessage();
		ELSE
			$message = $exception->getLine() . ': ' . $exception->getMessage();
	
		RLog::error($message);
	
		IF($return_message)
			return $message;
			
	}
	
	public function rlog_info($msg)
	{
		RLog::info($msg);
	}
	
	
	public function rlog_debug($msg)
	{
		RLog::debug($msg);
	}	
	
	/*
	 * Common email library initializer
	 * Note : Hiwalay siya sa sending para hindi paulit-ulit
	 * 			ang initialization in case kailangan mag-send ng
	 * 			maraminig emails (array)
	 */
	public function initialize_email()
	{
		try
		{
			$this->load->model(PROJECT_CORE . '/users_model');
			$email_params = $this->users_model->get_email_params();
			if(EMPTY($email_params)) throw new Exception('The email parameters were not set properly.');
			$config = array();
			$config['protocol'] = $email_params['PROTOCOL'];
			$config['smtp_host'] = $email_params['SMTP_HOST'];
			$config['smtp_user'] = $email_params['SMTP_USER'];
			$config['smtp_pass'] = $email_params['SMTP_PASS'];
			$config['smtp_port'] = $email_params['SMTP_PORT'];
			$config['validate'] = TRUE;
			$config['mailtype'] = 'html';
			$config['charset'] = 'iso-8859-1';
			$config['crlf'] = "\r\n";
			$config['newline'] = "\r\n";
			$this->load->library('email');
			$this->email->initialize($config);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	public function send_email($email_info = array())
	{
		try
		{
			$this->load->model(PROJECT_CORE . '/users_model');
			$email_params = $this->users_model->get_email_params();
			
			if(!array_key_exists('to', $email_info) || !array_key_exists('message', $email_info))
				throw new Exception('"to" and "message" are all required');
			
			if(EMPTY($email_info['to'])) throw new Exception('Recipient is empty.');
			if(EMPTY($email_info['message'])) throw new Exception('Message is empty.');
				
			foreach ($email_info as $key => $info)
			{
				if(EMPTY($info)) throw new Exception($key . " is empty.");
				if($key == 'from' && is_array(info))
					$this->email->$key($info[0],$info[1]);
				else
					$this->email->$key($info);
			}
			
			if(!array_key_exists('reply_to', $email_info))
				$this->email->reply_to($email_params['SMTP_REPLY_EMAIL'], $email_params['SMTP_REPLY_NAME']);
			
			if(!array_key_exists('from', $email_info))
				$this->email->from($email_params['SMTP_REPLY_EMAIL'], $email_params['SMTP_REPLY_NAME']);
			
			if(!$this->email->send())
			{
				$next_line = "\n";
				RLog::info($next_line . $this->email->print_debugger() . $next_line);
				throw new Exception('Unable to send email');
			}
			
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	/**
	 * 
	 * @param unknown $pdo_ex - instance of the PDOException
	 * @param unknown $field_names - use to convert field name to a user-friendly message.
	 */
	protected function get_user_message($pdo_ex, $arr_field_names = array(), $custom_message_per_code = array()) 
	{
		try
		{
			$this->lang->load( 'mysql_err_lang', 'language/english' );

			$msg = $pdo_ex->getMessage();
			
			$this->rlog_error($pdo_ex);
			
			// reference --> http://php.net/manual/en/pdostatement.errorinfo.php
			$code = self::MYSQL_ERR_PREFIX . $pdo_ex->errorInfo[1];
			
			$title = "";
			$err_msg = $this->lang->line($code);
			
			foreach ($arr_field_names AS $field_name => $field_title)
			{
				// check if $msg contains $field_name
				if(strpos($msg, $field_name)) 
				{
					$title = $field_title;
					break;
				}
			}
			
			if(!ISSET($err_msg) OR EMPTY($err_msg))
				$err_msg = $this->lang->line( self::MYSQL_ERR_DEFAULT );

			if( !EMPTY( $custom_message_per_code ) )
			{
				if( ISSET( $custom_message_per_code[ $pdo_ex->errorInfo[1] ] ) )
				{
					$err_msg 	= $custom_message_per_code[ $pdo_ex->errorInfo[1] ];
				}
			}
			
			return $title . $err_msg;
		}	
		catch(Exception $e)
		{
			throw $e;
		}
	}
	
	
}