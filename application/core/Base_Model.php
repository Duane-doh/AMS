<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base_Model extends CI_Model {

	// THIS WILL HOLD THE DATABASE SCHEME OR THE DATA SOURCE NAME
	protected static $dsn;
	
	// THIS WILL HOLD THE DATABASE CONNECTIONS
	private static $conn = array();
	
	// THESE VARIABLES USED IN THE SELECT STATEMENT
	private $order_by					= "order_by";
	private $limit						= "limit";	
	
	// THIS VARIABLE IS ADDED FOR THE HASHING PRIMARY CODE OR ID
	public $hash_code					= '%$';	
	private static $hash_code_static	= '%$';
	
	// STATIC DATABASE
	public static $schema_core	= DB_CORE;
	//public static $schema_main	= DB_MAIN;
	
	
	
	public function __construct()
	{
		$this->_construct_rlog();
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
	
	
	/**
	 * THIS FUNCTION WILL LOAD OR RETURN EXISTING DATA CONNECTION
	 */
	protected static function get_connection()
	{
		
		if(!in_array(static::$dsn, array_keys(self::$conn)))
		{
			$CI =& get_instance();
			
			self::$conn[static::$dsn] = $CI->load->database(static::$dsn, TRUE);
		}
		return self::$conn[static::$dsn];
	}
	
	public static function beginTransaction()
	{
		
		try				
		{
			$self 	= new static;
			
			$self->rlog_info('=== START ===');
			$self->rlog_info('FUNCTION beginTransaction()');
			
			$db = static::get_connection();
			
			if(!$db->inTransaction())
				$db->beginTransaction();
		}
		catch (PDOException $e)
		{
			$self->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$self->rlog_error($e);
		}
	}
	
	public static function commit()
	{
		
		try
		{
			$self 	= new static;
			
			$self->rlog_info('=== START ===');
			$self->rlog_info('FUNCTION commit()');
			
			$db = static::get_connection();			
			
			if($db->inTransaction())
				$db->commit();
		}
		catch (PDOException $e)
		{
			$self->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$self->rlog_error($e);
		}
	}
	
	public static function rollback()
	{
		
		try
		{

			$self 	= new static;
			
			$self->rlog_info('=== START ===');
			$self->rlog_info('FUNCTION rollback()');			
			
			$db = static::get_connection();
						
			if($db->inTransaction())
				$db->rollBack();
		}
		catch (PDOException $e)
		{
			$self->rlog_error($e);
		}
		catch (Exception $e)
		{			
			$self->rlog_error($e);
		}
	}
	
	/*
	 * USE FOR KEY IN MYSQL CONCAT A HASH KEY
	 * $name = $field_name
	 */
	protected function get_hash_key($name)
	{
		return "md5(CONCAT('" . self::$hash_code_static . "', $name, '" . self::$hash_code_static . "'))";
	}
	

	
	protected function query($query, $val=NULL, $multiple=TRUE, $execute=TRUE)
	{
		
		
		try 
		{
		
			self::rlog_info('=== START ===');
			self::rlog_info('FUNCTION: query()');
			
			$db		= static::get_connection();
			
			self::rlog_info('QUERY: ' . $query);
			$stmt	= $db->prepare($query);
			
			IF($execute)
			{
				self::rlog_info('VALUE: ' . var_export($val, TRUE));
				$stmt->execute($val);
				
				IF($multiple === TRUE)
				{
					return $stmt->fetchAll(PDO::FETCH_ASSOC);
				} 
				ELSEIF($multiple === FALSE) {
					return $stmt->fetch(PDO::FETCH_ASSOC);
				}
			}
		}
		catch (PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch (Exception $e)
		{			
			self::rlog_error($e);
			
			throw $e;
		}
		
	}
	
	/* CRUD */	
	protected function select_all($fields_arr, $table, $where_arr = array(), $order_arr = array(), $group_arr = array(), $limit = "", $log = TRUE)
	{
		try {			
			return $this->_select_data($fields_arr, $table, $where_arr, $order_arr, $group_arr, $limit, TRUE, NULL, $log);
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}
	}
	
	protected function select_one($fields_arr, $table, $where_arr = array(), $order_arr = array(), $group_arr = array(), $limit = "", $log = TRUE)
	{
		try {
			return $this->_select_data($fields_arr, $table, $where_arr, $order_arr, $group_arr, $limit, FALSE, NULL, $log);
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}
	}
	
	protected function select_column($fields_arr, $table, $column, $where_arr = array(), $multiple = FALSE, $order_arr = array(), $group_arr = array(), $limit = "", $log = TRUE)
	{
		try 
		{
			return $this->_select_data($fields_arr, $table, $where_arr, $order_arr, $group_arr, $limit, $multiple, $column, $log);
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}
	}
	
	
	/*
	 * HOW TO USE WHERE FIELD IN THE SELECT_DATA FUNCTION
	 * 
	 * NORMAL USE
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name = ?
	 * CODE		: $where[$field_name] = $value;
	 
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name = ? AND $field_name2 = ?
	 * CODE		: $where[$field_name] 	= $value;
	 * 			  $where[$field_name2] 	= $value; 
	 * 
	 * OPERATOR	: IS NULL
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name IS NULL
	 * CODE		: $where[$field_name] = "IS NULL";
	 * 
	 * OPERATOR	: IS NOT NULL
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name IS NOT NULL	 
	 * CODE		: $where[$field_name] = "IS NOT NULL";
	 * 
	 * OPERATOR	: BETWEEN
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name BETWEEN 1 AND 2
	 * CODE		: $where[$field_name] = array($value = array(1,2), array("BETWEEN"))
	 * 
	 * 
	 * OPERATOR : LIKE (specify the occurence of modulo)
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name LIKE ?
	 * CODE		: $where[$field_name] = array( '%' . $value . '%', array("LIKE"))
	 * 
	 * 
	 * OPERATOR : =, <>, != , >, >=, <, <=
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name <= ?
	 * CODE		: $where[$field_name] = array($value, array("<="));
	 * 
	 * 
	 * OPERATOR : IN, NOT IN
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name IN(?,?,?,?)
	 * CODE		: $where[$field_name] = array($value = array(1,2,3,n), array("IN"));
	 * 
	 * 
	 * OPERATOR : OR (DEFAULT IS 'AND' OPERATOR)
	 * QUERY	: SELECT * FROM TABLE WHERE $field_name != ? OR $field_name2 = ?
	 * CODE		: $where[$field_name] = array($value, array("!=", "OR")); 
	 * 			  $where[$field_name2] = array($value, array("="));
	 * 
	 *  
	 * OPERATOR : (, )
	 * QUERY	: SELECT * FROM TABLE WHERE ($field_name != ? OR $field_name2 = ?) 
	 * CODE		: $where[$field_name] = array($value, array("!=", "OR", "(")); 
	 * 			  $where[$field_name2] = array($value, array("=", ")"));
	 * NOTE		: The '(' should exist first before ')'
	 * 
	 * 
	 * OPERATOR : OTHER WAY AROUND FOR "(",")" USING IS NULL
	 * NOTE		: USING IS NULL/NOT NULL WILL IGNORE VALUE IN array[0]
	 * QUERY	: SELECT status_id, status FROM param_user_status WHERE (status_id IS NULL AND  status IS NULL)
	 * CODE		: 
	 * 
	 * Incorrect:
	 * $where['status_id'] = array(
	 *		"ASDADA", 				// this value will be ignored
	 *		array('IS NULL', "(")	// because of is NULL
	 *	);
	 *
	 * Correct:
	 * $where['status'] = array(
	 *		NULL,
	 *		array('IS NULL', ")")				
	 * );
	 * 
	 */
		
	private function _select_data($fields_arr, $table, $where_arr = array(), $order_arr = array(), $group_arr = array(), $limit = "", $multiple=TRUE, $column = NULL, $log = TRUE)
	{

		
		
		try
		{
		
			/*self::rlog_info('=== START ===');
			self::rlog_info('FUNCTION _select_data()');*/
						
			$db = static::get_connection();
								
			$data			= $this->get_tables($table);			
			$main_table		= $data['main'];
			$join_table		= $data['join'];
			
			/*self::rlog_info('MAIN TABLE: ' . $main_table);
			self::rlog_info('JOIN TABLE: ' . $join_table);*/
			
			IF(is_array($fields_arr))
			{
				$fields	= "";
				foreach ($fields_arr as $field):
					$fields .= $field.", ";
				endforeach;
				
				$fields = rtrim($fields, ", ");
			}
			ELSE
				$fields = $fields_arr;
			
			
			// CONSTRUCT WHERE STATEMENT
			$new_arr 	= $this->get_where_statement($where_arr);
			$new_or  	= $this->_get_or_statment($where_arr);
			$where 		= $new_arr['where'];
			$where  	.= $new_or['where'];
			
			$val		= $new_arr['val'];
			$val 		= array_merge( $val, $new_or['val'] );
			
			/*self::rlog_info('WHERE : ' . $where);*/
			// GROUP BY
			$group_by	= "";
			if(!EMPTY($group_arr)){
				$group_by .= "GROUP BY ";
					
				foreach ($group_arr as $b):
					$group_by .= $b.", ";
				endforeach;
			
				$group_by = rtrim($group_by, ", ");
			}
			
			// ORDER BY
			$order_by		= "";
			if(!EMPTY($order_arr)){
				$order_by .= "ORDER BY ";
					
				foreach ($order_arr as $a => $b):
					$order_by .= $a." ".$b.", ";
				endforeach;
	
				$order_by = rtrim($order_by, ", ");
			}
			
	
			$query = <<<EOS
				SELECT $fields
				FROM $main_table
				$join_table
				$where
				$group_by
				$order_by
				$limit
EOS;
	
			self::rlog_info('QUERY: ' . $query);
			self::rlog_info('VALUE: ' . var_export($val, TRUE));
			/* echo '<pre>';
			print_r($query);
			print_r($val) */;
			$stmt = $db->prepare($query);
			$stmt->execute($val);
			
			if(is_numeric($column) && ISSET($column))
			{
				
				$method_name 	= ($multiple) ? 'fetchAll' : 'fetch';
				
				return $stmt->{ $method_name }( PDO::FETCH_ASSOC | PDO::FETCH_COLUMN, $column );
				
			}
			else 
			{
				
				$method_name 	= ($multiple) ? 'fetchAll' : 'fetch';
					
				$result = $stmt->{ $method_name } (PDO::FETCH_ASSOC);
								
				return $result;
			}
	
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}

	}		
	
	protected function get_tables($table)
	{
		try
		{
			$main_table	= "";
			$join_where = "";
			$join_table = "";
			$type_joins = array("inner join", "left join", "right join", "full join", 'join');

			if(is_array($table))
			{
				// GET MAIN TABLE (TO BE USE IN "FROM")			
				$schema		= !EMPTY($table['main']['schema']) ? $table['main']['schema'] . '.' : '';
			
				$main_table = $schema . $table['main']['table'];
				
				if(ISSET($table['main']['alias']))
				{
					$main_table = $main_table  . " " . $table['main']['alias'];
				}
					
				// CHECK OTHER TABLES TO JOIN
				foreach($table as $k => $value)
				{
						
					if($k === 'main') continue;
							
					$type 			= $value['type'];
					$table_name 	= $value['table'];
					$alias 			= (ISSET($value['alias']) ? $value['alias'] : "");
					$condition 		= $value['condition'];
					$value['schema']= !EMPTY($value['schema']) ? $value['schema'] . '.' : '';
					$table_name 	= $value['schema'] . $table_name . " " . $alias;
											
					if(in_array(strtolower($type), $type_joins))
					{
						// LEFT JOIN, RIGHT JOIN AND FULL JOIN
						$join_table .= $type . " " . $table_name . " ON " . $condition . " ";
					}
				}
			}
			else
			{
				$main_table = $table;					
			}
			
			return array('main'	=> $main_table, 'join'	=> $join_table);
		}
		catch(PDOException $e)
		{			
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
	}
	protected function get_where_statement($where_arr, $val = NULL, $where_init = " WHERE ", $where_params = NULL)
	{
		try {
			return self::_get_where_statement_static($where_arr, $val, $where_init, $where_params);
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
	}
	
	private function _get_or_statment($where_arr, $val = NULL, $where_init = " AND ", $where_params = NULL)
	{
		$str 	= '';
		$val 	= array();
		
		try
		{
			if( ISSET( $where_arr['OR'] ) )
			{
				foreach( $where_arr['OR'] as $key => $or )
				{
					$init 	= ' OR ';
					
					if( $key == 0 )
					{
						$init = ' AND ';
					}
					
					$where = $this->get_where_statement($or, NULL, $init );
					
					$str 	.= $where['where'];
					$val 	= array_merge( $where['val'], $val );
				}
			}
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
				
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
				
			throw $e;
		}
		
		return array(
			'where'	=> $str,
			'val'	=> $val
		);
	}
	
	// $where_params IS EXISTING WHERE
	private static function _get_where_statement_static($where_arr, $val = NULL, $where_init = " WHERE ", $where_params = NULL)
	{
		try 
		{	
			$where = !EMPTY($where_params) ? $where_params : '';
			
			$special_where		= array("IS NULL", "IS NOT NULL");
			$arr_logical_oprtor	= array("OR", "AND");
			$arr_oprtor			= array("=", "<>", "!=", ">", ">=", "<", "<=","NOT LIKE", "LIKE","NOT BETWEEN", "BETWEEN");
			$arr_special_keys	= array("IN", "NOT IN");
			$separator_keys		= array("(", ")");
			
			
			
			if(!EMPTY($where_arr))
			{
				$where .= $where_init;
				
				foreach ($where_arr as $k => $v):
				
				if( $k == 'OR' )
				{
					continue;
				}
				
				if (is_array($v))
				{
					//pass the current value for val
					//HOLDERS
					$operator_holder	= "";
					$logical_holder 	= "";
					$special_holder		= "";
					$val_holder			= $v[0];
					
					$spec_where_holder	= "";
					$start_separator	= "";
					$end_separator		= "";
					
					$count_value		= count($v);
					
					// ARRAY 0 EXPECT THE VALUE
					// ARRAY 1 EXPECT AN ARRAY OF CONDITIONS
					
					if($count_value > 3 OR EMPTY($v[1]) OR !is_array($v[1]) )
					{
						throw new Exception('Invalid syntax validation for ' . $k);
					}
					
					// OPERATOR
					foreach($v[1] AS $key => $value):
					
						if(is_array($value))
						{
							throw new Exception('Invalid syntax exceed an array for ' . $k);
						}
						
						$value = strtoupper(trim($value));
						if(in_array($value, $arr_oprtor))
						{
							$operator_holder 	= $value;
						}
						else if(in_array($value, $arr_logical_oprtor))
						{
							$logical_holder 	= $value;
						}
						else if(in_array($value, $arr_special_keys))
						{
							$special_holder 	= $value;
						}
						else if(in_array($value, $special_where) && $value != "0" && $value != "1")
						{
							$spec_where_holder 	= $value;
						}
						else if($separator_keys[0] == $value)
						{
							$start_separator 	.= ' ' . $value;
						}
						else if($separator_keys[1] == $value)
						{
							$end_separator 		.= $value . ' ';
						}
						else
						{
							throw new Exception('Invalid syntax unknown operator ' . $value . ' for field ' . $k);
						}
						
					endforeach;
					
					
						
					//THROW ERROR IF HAVE SPECIAL AND OPERATOR
					if(!EMPTY($special_holder) && !EMPTY($operator_holder) && !EMPTY($spec_where_holder))
					{
						throw  new Exception("Error. More than one operator exist in " . $k . " field.");
					}
						
					$where .=  $start_separator . $k . ' ';
						
					if(!EMPTY($spec_where_holder))
					{
						$where		.= $spec_where_holder;
						$val_holder = NULL;
					}
					
					if(ISSET($val_holder))
					{
						if(count($val_holder) < 2 && EMPTY($special_holder))
						{
							if(EMPTY($v[2]))
							{
								$val[] = $val_holder;
							}
						}
							
						if(!EMPTY($operator_holder))
						{
							$where .= $operator_holder;
							if(count($val_holder) > 1)
							{
								// BETWEEN
								$where		.= " ? AND ? ";
								$val[]		= $val_holder[0];
								$val[]		= $val_holder[1];
							}
							else
							{
								$where		.= EMPTY($v[2]) ? " ? " : $val_holder;
							}
						}
						
						if(!EMPTY($special_holder))
						{
							// IF IN/ NOT IN
							$where 		.= $special_holder . "(";
							
							foreach($val_holder AS $val_holder_val)
							{
								$where	.= "?,";
								//convert to string
								$val[] 		= trim($val_holder_val);
							}
							$where		= rtrim($where, ',');
							$where		.= ") ";
						}
					}
					
					
						
					$logical_holder	= !EMPTY($logical_holder) ? $logical_holder : " AND ";
					$where 			.= $end_separator . $logical_holder . " ";
					
					
					/**
					 *	THIRD PARAMETER ARRAY IS THE VALUE NEEDED FOR $value having this SAMPLE QUERY
					 * $value						=	"(SELECT MAX(sys_sequence_no) FROM service_tasks WHERE service_request_id = ? 
															AND " sys_sequence_no < ?)";
						$where['sys_sequence_no']	= array($value, array('=', ')'), array($request_id, $sys_seq_no));
					 */

					if(!EMPTY($v[2]))
					{
						if(!is_array($v[2]))
						{
							$val[]	= $v[2];
						}
						else
						{
							foreach($v[2] AS $dta)
							{
								$val[]	= $dta;
							}
						}
					}
					

					
				}
				else
				{
					if(in_array($v, $special_where) && $v != "0" && $v != "1")
					{
	
						$where .= $k . " " . $v . " AND ";
					}
					else
					{
						$val[] = $v;
						$where .= $k." = ? AND ";
					}
				}
				endforeach;
			
				$where = rtrim($where, " AND ");
				$where = rtrim($where, " OR ");
			}
			
			$data['where']	= $where;
			$data['val']	= $val;
			return $data;
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}		
	}
	
	
	
	protected function insert_data($table, $params, $return_id = FALSE,  $on_dup_update = FALSE, $on_dup_field_id=NULL)
	{
		
		try
		{
			
			self::rlog_info('=== START ===');
			self::rlog_info('FUNCTION insert_data()');
			
			$db = static::get_connection();
			
			$val			= array();
			$fields			= "";
			$values 		= "";
			$on_dup 		= '';
			$upd 			= ''; 
			
			foreach ($params as $k => $v):
				$k = str_replace('-','.',$k);
				if(is_array($v))
				{
					$field_names_1	= "";
					$params_1		= "";
					foreach($v AS $key => $dta)
					{
						$key = str_replace('-','.',$key);
						$field_names_1 .= EMPTY($field_names_1) ? "(" :", ";
						$field_names_1 .= $key;
						$params_1 .= EMPTY($params_1) ? "(?" :", ?";
						$val[] 		= $dta;
						
						$upd 	    .= $key.'=VALUES('.$key.'),';
					}
					if(EMPTY($fields))
					{
						$fields = EMPTY($field_names_1) ? "" : $field_names_1. ")";
					}
					$values .= EMPTY($params_1) ? "" : (EMPTY($values) ? "" : ",").$params_1.")";
				}
				else
				{
					$val[] = $v;
					$fields .= EMPTY($fields) ? "(" :", ";
					$fields .= $k;
					$values .= EMPTY($values) ? "(?" :", ?";
					
					$upd 	    .= $k.'=VALUES('.$k.'),';
				}

				
			endforeach;
						
			
			
			 
			$fields = (substr($fields, -1) != ')') ? $fields . ')' : $fields;
			$values = (substr($values, -1) != ')') ? $values . ')':	$values;

			if( $on_dup_update ) 
			{
				// SGT 20161104: To get correct auto-increment ID
				if ($return_id && isset($on_dup_field_id))
				{
					$upd .= "$on_dup_field_id = LAST_INSERT_ID($on_dup_field_id)";
				}

				$upd 	= rtrim( $upd, ',' );

				$on_dup = ' ON DUPLICATE KEY UPDATE '.$upd;
			}
			
			$query = <<<EOS
				INSERT INTO $table
				$fields
				VALUES
				$values
				$on_dup
EOS;
			
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
			
			if($return_id){
				$last_insert_id = $db->lastInsertId();
				
				self::rlog_info('LAST INSERT ID ' . $last_insert_id);
				
				return $last_insert_id;
			}
	
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}
	
	}	
	
	protected function update_data($table, $params, $where_arr, $order_arr = array())
	{
		
		try
		{

			self::rlog_info('=== START ===');
			self::rlog_info('FUNCTION update_data()');			
			
			$db = static::get_connection();
			
			$val	= array();
			$fields	= "";
				
			foreach ($params as $k => $v):
				if(is_array($v))
				{
					$fields .= $k . " = " . $v[2] . $v[1] . ' ? ';
					$val[]	= $v[0];
				}
				else 
				{
					$fields .= $k." = ?, ";
					$val[] = $v;
				}				
			endforeach;
			
			$fields		= rtrim($fields, ", ");
			
			
			
			
			$new_arr 	= $this->get_where_statement($where_arr, $val);
			$where		= $new_arr['where'];
			$where		= rtrim($where, " AND ");
			$val		= $new_arr['val'];
			
			

				
			// ORDER BY
			$order_by	 = "";
			if(!EMPTY($order_arr)){
				$order_by .= "ORDER BY ";
					
				foreach ($order_arr as $a => $b):
					$order_by .= $a." ".$b.", ";
				endforeach;
			
				$order_by = rtrim($order_by, ", ");
			}
				
			$query = <<<EOS
				UPDATE $table SET
				$fields
				$where 
				$order_by
EOS;
			
			
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
	
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}
	
	}		
	
	protected function delete_data($table, $where_arr)
	{
		
		try
		{
			self::rlog_info('=== START ===');
			self::rlog_info('FUNCTION delete_data()');
			
			$db		= static::get_connection();			
			$val	= array();
			
				
			$new_arr 	= $this->get_where_statement($where_arr, $val);
			$val		= $new_arr['val'];
			$where		= $new_arr['where'];
			$where 		= rtrim($where, " AND ");
				
			$query = <<<EOS
				DELETE FROM $table
				$where 
EOS;
			
			self::rlog_info('QUERY ' . $query);
			self::rlog_info('VALUES ' . var_export($val, TRUE));
			
			$stmt = $db->prepare($query);
			$stmt->execute($val);
	
		}
		catch(PDOException $e)
		{
			self::rlog_error($e);
			
			throw $e;
		}
		catch(Exception $e)
		{
			self::rlog_error($e);
			
			throw $e;			
		}
	
	}
	/* CRUD */	
	
	/* FOR DATATABLE */
	protected function filtering($aColumns, $params, $has_where)
	{

		$sWhere_arr = array();
		$sWhere = "";
		$search_params = array();
		
		if (ISSET($params['sSearch']) && $params['sSearch'] != "")
		{
			
			$sWhere = ($has_where)? " AND (" : "WHERE (";
			
			$cnt1 	= count($aColumns);

			for ($i=0; $i<$cnt1; $i++)
			{
				if (ISSET($params['bSearchable_'.$i]) && $params['bSearchable_'.$i] == "true")
				{
					$sWhere .= "LOWER(".str_replace("-", ".",$aColumns[$i]).") LIKE ? OR ";
					
					$search_params[] = "%".strtolower(filter_var($params['sSearch'], FILTER_SANITIZE_STRING))."%";
				}
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
			
		}
		
		/* Individual column filtering */
		if( ISSET( $params['action'] ) AND !EMPTY( $params['action'] ) )
		{
			foreach($aColumns as $aColumn)
			{
				
				if(ISSET( $params[$aColumn] ) AND !EMPTY($params[$aColumn]))
				{
					
					$new_column		= str_replace("-", ".",$aColumn);
					
					if (!$has_where)
					{
						$sWhere = "WHERE ";
						$has_where = TRUE;
					}
					else
					{
						$sWhere .= " AND ";
					}

					$sWhere     .= "LOWER(".$new_column.") LIKE ? ";	

					$search_params[] = "%".strtolower(filter_var($params[$aColumn], FILTER_SANITIZE_STRING))."%";
				}
			}
		}
		else 
		{

			$cnt 	= count($aColumns);

			for ($i=0 ; $i<$cnt; $i++)
			{
				if (ISSET($params['bSearchable_'.$i]) && $params['bSearchable_'.$i] == "true" && $params['sSearch_'.$i] != '')
				{
					if ($sWhere == "")
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumns[$i]." LIKE ? ";
					
					$search_params[] = "%".strtolower(filter_var($params['sSearch'], FILTER_SANITIZE_STRING))."%";
				}
				
			}

		}
		/*print_r($sWhere);
		print_r($search_params);*/
		$sWhere_arr["search_str"] = $sWhere;
		$sWhere_arr["search_params"] = $search_params;
	
		return $sWhere_arr;
	}
		
	protected function ordering($aColumns, $params)
	{
		$sOrder = "";
		if (ISSET($params['iSortCol_0']))
		{
			$sOrder = "ORDER BY  ";
			for ($i=0 ; $i<intval( $params['iSortingCols'] ) ; $i++)
			{
				if ($params[ 'bSortable_'.intval($params['iSortCol_'.$i]) ] == "true")
				{
					$sOrder .= $aColumns[ intval( $params['iSortCol_'.$i] ) ]."
					".($params['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			 
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
				$sOrder = "";
		}
		
		return $sOrder;
	}	
	
	protected function paging($params)
	{
		$sLimit = "";
		if (ISSET($params['iDisplayStart']) && $params['iDisplayLength'] != '-1')
		{
			$sLimit = "LIMIT ".intval( $params['iDisplayStart'] ).", ".
				intval( $params['iDisplayLength'] );
		}
	
		return $sLimit;
	}
	/* FOR DATATABLE */
	
	
	public function rlog_error($exception, $return_message = FALSE)
	{
	
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
	 * ADDED FUNCTIONS TO GET ALL THE CHILDREN OF A SELECTED OFFICE
	 * JAB LAZIM
	 * START
	 */

	// GET THE ORG_CODE OF THE PASSED OFFICE_ID PARAM
	private function _get_org_code($office_id) {
		$schema_core = DB_CORE;
		$val = array();
		$val[] = $office_id;
		try {
			$query = <<<EOS
			SELECT A.org_code from {$schema_core}.organizations A
			JOIN param_offices B ON A.org_code = B.org_code
			WHERE B.office_id = ?
EOS;
		return $this->query($query, $val, FALSE);

		} catch(PDOException $e) {
			throw $e;
		}
	}
	
	// GET THE OFFICES UNDER SOME PARENT OFFICES
	private function _get_offices($org_code) {
		$schema_core = DB_CORE;
		$params      = implode(',', $org_code);

		try {
			$query = <<<EOS
				SELECT B.office_id, A.org_code from {$schema_core}.organizations A 
				JOIN param_offices B ON A.org_code = B.org_code
				WHERE A.org_parent IN ( $params )
EOS;
			return $this->query($query);

		} catch(PDOException $e) {
			throw $e;
		}
	}

	// A RECURSIVE FUNCTION THAT WILL GET EACH CHILDREN OF CURRENT SELECTED OFFICE LIST
	public function get_office_child($param_office, $office_id=NULL, $org_code=NULL) {

		try {

			$org_codes = array();	
			if(!EMPTY($office_id)) {
				$param_office[] = $office_id;
				$org_code       = $this->_get_org_code($office_id);
				$org_codes[]    = "'" . $org_code['org_code'] . "'";
				$param_office   = $this->get_office_child($param_office, NULL, $org_codes);

			} else {
				$offices   = $this->_get_offices($org_code);
				
				foreach ($offices as $key => $value) {
					$param_office[] = $value['office_id'];
					$org_codes[]    = "'" . $value['org_code'] . "'";
				}
				if(!EMPTY($offices))
				$param_office = $this->get_office_child($param_office, NULL, $org_codes);
			}

		} catch (PDOException $e) {
			throw $e;
		}

		return $param_office;
	}

	/*
	 * END
	 */
}
