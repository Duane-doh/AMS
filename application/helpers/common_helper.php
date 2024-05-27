<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| List of Useful Generic Functions
|--------------------------------------------------------------------------
*/

/*
|----------------------------------------------------------------------
| Get Parameters from $_GET, $_POST, and $_FILES Function
|----------------------------------------------------------------------
| Note: Because almost all method that connected to AJAX is using this 
| funtion, XSS is auto enabled. XSS or Cross Site Scripting Hack
| prevention filter which can either run automatically to filter all
| POST and COOKIE data that is encountered, or you can run it on a per
| item basis
|
| @return array
*/

function get_params($xss = TRUE)
{
	$CI =& get_instance();

	$get = $CI->input->get(NULL, $xss) ? $CI->input->get(NULL, $xss) : array();
	$post = $CI->input->post(NULL, $xss) ? $CI->input->post(NULL, $xss) : array();
	$params = array_merge(array_map('_secure_param', array_merge($get, $post)), $_FILES);

	return $params;
}

/* Single Parameter */
function get_param($key, $xss = TRUE) {
	if (is_string($key) && !empty($key)) {
		$params = get_params($xss);
		return (array_key_exists($key, $params)) ? $params[$key] : '';
	}

	return FALSE;
}

function _secure_param($value)
{
	if (is_array($value)) {
		return array_map('_secure_param', $value);
	} else {
		return urldecode(trim($value));
	}
}


/*
|----------------------------------------------------------------------
| Get Settings Function
|----------------------------------------------------------------------
| Get a specific site setting detail
|
| @param string $type
| @param string $name
|
| @return string
*/

function get_setting($type, $name)
{
	$CI =& get_instance();
	$CI->load->model("settings_model");
	
	$setting = $CI->settings_model->get_specific_setting($type, $name);
	
	return $setting["setting_value"];
}


/*
|----------------------------------------------------------------------
| Generate Salt Function
|----------------------------------------------------------------------
| @param boolean $high_risk	: set to TRUE if the value being hashed 
| 							  needs to be more secured (e.g. password) 
|
| @return string
*/

function gen_salt($high_risk = FALSE) {
	if($high_risk){
		return hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE));
	} else {
		return substr(hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), TRUE)), 0, SALT_LENGTH);
	}	
}


/*
|----------------------------------------------------------------------
| Generate Token Function
|----------------------------------------------------------------------
| @param string $id			: specifies the value of the id
| @param string $salt		: specifies the value of the salt
| @param boolean $high_risk	: set to TRUE if the value being hashed 
| 							  needs to be more secured (e.g. password)
|
| @return string
*/

function in_salt($id, $salt, $high_risk = FALSE) {
	if($high_risk){
		return hash('sha512', sha1($id) . $salt);
	} else {
		return substr(hash('sha512', sha1($id) . $salt), 0, SALT_LENGTH);
	}	
}


/*
|----------------------------------------------------------------------
| Check Salt Function
|----------------------------------------------------------------------
| Check if the token or salt were not maliciously changed
|
| @param string $id
| @param string $salt
| @param string $token
*/

function check_salt($id, $salt, $token) {
	$CI =& get_instance();

	if($token != in_salt($id, $salt))
	{
		throw new Exception($CI->lang->line('invalid_action'));
	}
}


/*
|----------------------------------------------------------------------
| URL Encode Function
|----------------------------------------------------------------------
| Encodes a string to safely pass values in the URL
| 
| @param string $input
| 
| return string
*/

function base64_url_encode($input)
{
	$CI =& get_instance();
	
	return strtr($CI->encryption->encrypt($input), '+/=', '.~-');
}


/*
|----------------------------------------------------------------------
| URL Decode Function
|----------------------------------------------------------------------
| Decodes any encoded values in the given string
|
| @param string $input
| 
| return string
*/

function base64_url_decode($input)
{
	$CI =& get_instance();
	
	return $CI->encryption->decrypt(strtr($input, '.~-', '+/='));
}


/*
|----------------------------------------------------------------------
| Get Values Function
|----------------------------------------------------------------------
| Function for queries that needs dependency. This will run the query 
| from a particular model without the need of a controller
|
| @param string $model		: name of the model where the query is 
| 							  needed to be executed
| @param string $function	: name of the function located in the 
| 							  specified model
| @param array $params		: array of values needed by the query for 
| 							  dependency
| @param string $module		: name of the project where the function is
| 							  located
| 
| @return array
*/

function get_values($model, $function, $params = array(), $module = NULL)
{
	$mod = (!IS_NULL($module))? $module."/".$model : $model;
	$CI =& get_instance();
	
	if(!$CI->load->is_model_loaded($mod))
		$CI->load->model($mod);
	
	try{
		
		$values = $CI->$model->$function($params);
	
		return $values;
	}
	catch(PDOException $e)
	{
		throw new PDOException($e->getMessage());
	}
	catch(Exception $e)
	{
		throw new Exception($e->getMessage());
	}		
}


/*
|----------------------------------------------------------------------
| Time Ago Function
|----------------------------------------------------------------------
| Used for comments and other form of communication to tell the time 
| in seconds/minutes/hours/days/months/years/decades ago instead of the
| exact time which might not be correct to some in another time zone
| 
| @param datetime $time		: specifies the date/time to be converted
| @param int $ago			: set to 1 to append "ago" in the returned 
| 							  value
| @param int $period_only	: set to 1 to exclude date in the returned 
| 							  value
| 
| @return string
*/

function get_date_format($time, $ago = 0, $period_only = 0)
{
	$date = $prefix = $suffix = "";
	$convert_time = strtotime($time);
	$month_total_days = date("t");
	$periods = array("second", "minute", "hour", "day", "month", "year", "decade");
	$lengths = array("60","60","24",$month_total_days,"12","10");

	$now = time();

	IF($ago){
	   $difference = $now - $convert_time;
	   $suffix = " ago";
	}else{
	   $difference = $convert_time - $now;
	   $prefix = "In ";	
	}

	for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
	   $difference /= $lengths[$j];
	}

	$difference = floor($difference);

	if($difference > 1)
		$periods[$j].= "s";
	
	switch($periods[$j]){
		/* IF CURRENT PERIOD IS DAY */
		case $periods[3]:
			if($difference > 7)
				$date = date(', D M jS', strtotime($time));
		break;
		
		/* IF CURRENT PERIOD IS MONTH */
		case $periods[4]:
			$date = date(', D M jS', strtotime($time));
			$difference = ($difference == 1) ? "a" : $difference;
		break;
	}
	
	$time_ago = ($difference == 0 && is_numeric($difference)) ? "Just Now" : $prefix . $difference . ' ' . $periods[$j] . $suffix;
	$time_ago = ($period_only) ? $time_ago : $time_ago . $date;

	return $time_ago;
}


/*
|----------------------------------------------------------------------
| Generate Years Dropdown Function
|----------------------------------------------------------------------
| @Create dropdown of years
| 
| @param int $start_year	: specifies the year when the list will start
| @param int $end_year		: specifies the year when the list will end
| @param string $id			: the name and id of the select object
| @param int $selected		: the value to be selected from the dropdown
| 
| @return string
*/

function create_years($start_year, $end_year, $id = 'year_select', $selected = NULL, $disabled = FALSE, $discend = FALSE)
{

	/* CURRENT YEAR */
	$selected = is_null($selected) ? "": $selected;
	$disabled = ($disabled == TRUE) ? ' disabled ' : '';

	/* RANGE OF YEARS */
	$r = range($start_year, $end_year);

	/* CREATE SELECT OBJECT */
	$select = '<select name="'.$id.'" id="'.$id.'" class="selectize" '.$disabled.'>';
	$select .='<option value="">Select Year</option>';
	if($discend) {
		for($i=count($r); $i >= 0; $i--) 
		{
			$select .= '<option value="'.$r[$i].'"';
			$select .= ($r[$i]==$selected) ? ' selected="selected"' : '';
			$select .= '>'.$r[$i].'</option>\n';
		}
	} else {
		foreach( $r as $year )
		{
			$select .= '<option value="'.$year.'"';
			$select .= ($year==$selected) ? ' selected="selected"' : '';
			$select .= '>'.$year.'</option>\n';
		}
	}
	
	$select .= '</select>';
	
	return $select;
}


/*
|----------------------------------------------------------------------
| Generate Months Dropdown Function
|----------------------------------------------------------------------
| @Create dropdown list of months
| 
| @param string $id		: the name and id of the select object
| @param int $selected	: the value to be selected from the dropdown
| @param boolean $all	: set to FALSE to remove 'ALL' option from the 
| 						  dropdown list
| 
| @return string
*/

function create_months($id = 'month_select', $selected = NULL, $all = TRUE, $disabled = FALSE)
{
	/* ARRAY OF MONTHS */
	$months = array(
			1  =>'January',
			2  =>'February',
			3  =>'March',
			4  =>'April',
			5  =>'May',
			6  =>'June',
			7  =>'July',
			8  =>'August',
			9  =>'September',
			10 =>'October',
			11 =>'November',
			12 =>'December');

	/*** current month ***/
	$selected = is_null($selected) ? "" : $selected;
	$disabled = ($disabled == TRUE) ? ' disabled ' : '';

	$select = '<select name="'.$id.'" id="'.$id.'" class="selectize" '.$disabled.'>';
	$select .='<option value="">Select Month</option>';
	if($all)
		$select .= '<option value="0">All</option>\n';
	
	foreach($months as $key=>$mon)
	{
		$select .= "<option value=\"$key\"";
		$select .= ($key==$selected) ? ' selected="selected"' : '';
		$select .= ">$mon</option>\n";
	}
	$select .= '</select>';
	return $select;
}


/*
 |----------------------------------------------------------------------
 | Create Dropdown List of Days
 |----------------------------------------------------------------------
 | @Create dropdown list of days
 |
 | @param string $id The name and id of the select object
 |
 | @param int $selected
 |
 | @return string
 */

function create_days($id='day_select', $selected=null)
{
	/*** range of days ***/
	$r = range(1, 31);

	/*** current day ***/
	$selected = is_null($selected) ? date('d') : $selected;

	$select = "<select name=\"$id\" id=\"$id\" class='selectize'>\n";
	foreach ($r as $day)
	{
		$select .= "<option value=\"$day\"";
		$select .= ($day==$selected) ? ' selected="selected"' : '';
		$select .= ">$day</option>\n";
	}
	$select .= '</select>';
	return $select;
}


/*
 |----------------------------------------------------------------------
 | To have a universal way of parse json data
 |----------------------------------------------------------------------
 | Para pwedeng imanipulate yung output in case kailangan
 |
 */

function parse_json($params)
{
	echo json_encode(format_output($params));
}


function format_output($params)
{
	if(is_array($params))
		return array_map('format_output', $params);
	else
		return utf8_encode($params);
}

/*
|----------------------------------------------------------------------
| File Size Convert
|----------------------------------------------------------------------
| Converts bytes into human readable file size
| 
| @param string $bytes
| 
| @return string human readable file size (2,87 Мб)
*/

function file_size_convert($bytes)
{
	$bytes = floatval($bytes);
		$arr_bytes = array(
			0 => array(
				"UNIT" => "TB",
				"VALUE" => pow(1024, 4)
			),
			1 => array(
				"UNIT" => "GB",
				"VALUE" => pow(1024, 3)
			),
			2 => array(
				"UNIT" => "MB",
				"VALUE" => pow(1024, 2)
			),
			3 => array(
				"UNIT" => "KB",
				"VALUE" => 1024
			),
			4 => array(
				"UNIT" => "B",
				"VALUE" => 1
			),
		);

	foreach($arr_bytes as $arr_item)
	{
		if($bytes >= $arr_item["VALUE"])
		{
			$result = $bytes / $arr_item["VALUE"];
			$result = str_replace(".", "." , strval(round($result, 2)))." ".$arr_item["UNIT"];
			break;
		}
	}
	return $result;
}

function get_pass_error_msg()
{
	$CI =& get_instance();

	if(!$CI->load->is_model_loaded('settings_model'))
		$CI->load->model('settings_model');

	return $CI->settings_model->get_pass_error_msg();

}


/*
|----------------------------------------------------------------------
| Relative Date
|----------------------------------------------------------------------
| Return a string with a date relative to today
| 
| @param datetime strtotime($time)
| 
| @return string
*/

function relative_date($time) {
	
	$today		= strtotime(date('M j, Y'));
	$reldays	= ($time - $today)/86400;
	
	if ($reldays >= 0 && $reldays < 1) {
		return 'Today';
	} else if ($reldays >= 1 && $reldays < 2) {
		return 'Tomorrow';
	} else if ($reldays >= -1 && $reldays < 0) {
		return 'Yesterday';
	}
	 
	if (abs($reldays) < 7) {
		if ($reldays > 0) {
			$reldays = floor($reldays);
			return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
		} else {
			$reldays = abs(floor($reldays));
			return $reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';
		}
	}
	 
	if (abs($reldays) < 182) {
		//return date('l, j F',$time ? $time : time());
		return date('l',$time ? $time : time());
	} else {
		//return date('l, j F, Y',$time ? $time : time());
		return date('l',$time ? $time : time());
	}
}


/*
|----------------------------------------------------------------------
| CONVERT TO ROMAN
|----------------------------------------------------------------------
| Converts Number to Roman Numerals
| 
| @param int
| 
| @return string
*/

function convert_to_roman($integer, $upcase = true) 
{ 
    $table = array(
		'M'=>1000, 
		'CM'=>900, 
		'D'=>500, 
		'CD'=>400, 
		'C'=>100, 
		'XC'=>90, 
		'L'=>50, 
		'XL'=>40, 
		'X'=>10, 
		'IX'=>9, 
		'V'=>5, 
		'IV'=>4, 
		'I'=>1
	); 
	
    $return = ''; 
    while($integer > 0) 
    { 
        foreach($table as $rom=>$arb) 
        { 
            if($integer >= $arb) 
            { 
                $integer -= $arb; 
                $return .= $rom; 
                break; 
            } 
        } 
    } 
	
	$return = ($upcase) ? $return : strtolower($return);
	
    return $return; 
}


function format_date($date=null, $report=NULL)
{
	$format	= !EMPTY($report) ? $report : 'Y/m/d';
	
	$date	= !EMPTY($date)	? date($format, strtotime($date)) : '';
	return $date;	
}
/*
|----------------------------------------------------------------------
| Generation of Breadcrumbs
|----------------------------------------------------------------------
| Getters & setters of breadcrumbs
|
*/

function set_breadcrumbs($breadcrumbs, $overwrite=FALSE)
{
	$CI =& get_instance();
	//LENGTH OF PASSED BREADCRUMBS
	$index_length 			= COUNT($breadcrumbs) - 1; //2
	//GET ALL THE KEYS
	$array_keys				= array_keys($breadcrumbs); //0 = view 1 = case
	//GET THE KEY OF THE LAST BREADCRUMB ARRAY
	$last_array_key			= $array_keys[$index_length]; //[CASE TITLE]
	//GET THE CURRENT BREADCRUMB IN SESSION
	$current_breadcrumbs 	= $CI->session->userdata('breadcrumbs');
	$new_breadcrumbs		= array();

	if($overwrite):
	$new_breadcrumbs = $breadcrumbs;
	else:
	if(array_key_exists($last_array_key, $current_breadcrumbs)):

	foreach($current_breadcrumbs as $name => $link):

	$new_breadcrumbs[$name] = $link;

	if($last_array_key == $name) break;

	endforeach;

	else:

	$new_breadcrumbs = array_merge($current_breadcrumbs, $breadcrumbs);
	endif;
	endif;

	$CI->session->set_userdata('breadcrumbs', $new_breadcrumbs);
	//return $new_breadcrumbs;
}

function get_breadcrumbs()
{
	$CI 		=& get_instance();
	$base_url	= base_url();
	
	$html		= <<<EOH
				<li><a href="{$base_url}main/dashboard/get_dashboard/PORTAL">Home</a></li>
EOH;
	
	$breadcrumbs = $CI->session->userdata('breadcrumbs');
	$count		 = count($breadcrumbs);

	$x = 1;
	
	if( ! EMPTY($breadcrumbs)):
		foreach($breadcrumbs AS $key => $val):
		$onlick  = ( ! EMPTY($val) && ($count > $x) && $val !="#") ?  $base_url.$val : '#';
		$class = ( ! EMPTY($val) && ($count > $x)) ? '' : 'active';
		$html .= <<<EOH
					<li><a href="{$onlick}" class = "{$class}">{$key}</a></li>
EOH;
		$x++;
		endforeach;
	endif;
	
		echo $html;
}

	
/*
|----------------------------------------------------------------------
| Generation of Menu
|----------------------------------------------------------------------
| Gets modules from database and generates menu
|
*/

function get_modules( $show_hidden = FALSE, $parent_module_id = NULL, $system_code )
{
	$CI =& get_instance();

	$CI->load->model( 'Modules_model', 'modules', TRUE );

	$result = $CI->modules->get_modules( $show_hidden, $parent_module_id, $system_code );

	return $result;
}

function get_menu( $project )
{
	$modules 				= get_modules( FALSE, NULL, $project );
	$CI   					=& get_instance();

	foreach($modules as $module)
	{
		$sub_module_str		= "";
		$child_is_active	= 0;

		$controller 		= strtolower( $module['module_code'] );

		$url 				= base_url().$controller.'/';

		if( $module['display_child_flag'] == '1' )
		{
			$sub_modules 	= get_modules(FALSE, $module['module_id'], $project);

			if( !EMPTY( $sub_modules ) )
			{
				$sub_module_str  		   .= '<div class="collapsible-body">';
				$sub_module_str 		   .= '<ul class="menu-item">';

				foreach( $sub_modules as $sub_module )
				{
					$sub_controller 		= strtolower( $sub_module['module_code'] );

					$sub_url 				= base_url().$sub_controller.'/';

					if(preg_match('/\b'.$CI->router->fetch_class().'\b/', $sub_controller ) === 1)
					{
						$child_is_active 	= 1;
						$sub_active			= "style='color:white;'";
					}
					else 
					{
							$sub_active		= "";
					}

					$sub_module_str.= '
						<li >
							<a '.$sub_active.' href="'.$sub_url.'">                                                        
                    			'.$sub_module['module_name'].'
                  				</a>
                			</li>
                		';	
				}

				$sub_module_str 		   .= '</ul>';
    			$sub_module_str 	       .= '</div>';
			}
		}

		if(!empty($child_is_active))
			$active = "active";
		else
			$active = (preg_match('/\b'.$CI->router->fetch_class().'\b/', $controller ) === 1) ? "active" : "";

		if( EMPTY( $sub_module_str ) )
		{
			$par_url = $url;
		}
		else 
		{
			$par_url = '#';
		}

		echo '
			<li class="'.$active.'">
				<div class="collapsible-header '.$active.'">
					<a class="waves-effect waves-light" href="'.$par_url.'">
						<i class="'.$module['icon'].'"></i>
						<span class="hide-display show-on-hover">
							'.$module['module_name'].'
						</span>
					</a>
				</div>
				'.$sub_module_str.'
			</li>
		';
	}
}
/*
 * SAMPLE FORMAT
 * ---> 3x4x1x- <---
 * 'x' CHARACTER SHOULD ALWAYS BE USED AS THE DELIMITER OF THE FORMAT
 * THE LAST CHARACTER WILL BE THE OFFICIAL SEPARATOR/DELIMITER
 */
function format_identifications($data_string,$format=NULL) {

	if(!EMPTY($format)) {
		// EXPLODE THE FORMAT BY ITS DELIMITER X
		$format        = explode('x', $format);
		// RETRIVE THE LAST INDEX TO GET THE DELIMITER OF THE IDENTIFICATION TYPE
		$delimiter     = $format[count($format)-1];
		$value         = '';
		$temp_size     = 0;
		$size          = 0;
		$format_length = count($format)-1;

		for($i=0; $i<$format_length; $i++) {
			$size += ($format[$i]);
			for($j=$temp_size; $j<$size; $j++) {

				if($data_string[$temp_size] != '') {
					$value .= $data_string[$temp_size++];
				}
			}
			if($temp_size == $size && $i < $format_length-1)
			$value .= $delimiter;
		}
		$data_string = $value;
	}
	return $data_string;
}

/*
 * Checks if $val is between $min and $max  
 */	
function is_between_num($val, $min, $max)
{
	$min = ( $min == 0 ? 0.00 : $min );
	$max = ( $max == 0 ? $val : $max );
	if ($max == 0.00) return true;
	
	return ($val >= $min && $val <= $max);
}

/*
 * Returns an array of key=>value based on given key name and value name (eg. )
 */
function set_key_value($array, $arr_key, $arr_val, $is_val_arr=FALSE)
{
	$result = array();
	foreach ($array as $item)
	{ 
		$tmp_val = array();
		if (is_array($item)) {
			$key = '';
			$val = NULL;
			if (is_array($arr_key))
			{
				foreach($arr_key as $k)
				{
					$key .= (empty($key) ? '' : '-');
					$key .= $item[$k];
				}
			}
			else
			{
				$key = $item[$arr_key];
			}
				
			if (is_array($arr_val))
			{
				if ($is_val_arr)
				{
					$val = array();
					foreach($arr_val as $k => $v)
					{
						$val[$v] = $item[$v];
					}					
				}
				else
				{
					$val = array();
					foreach($arr_val as $k => $v)
					{
						$val[$item[$k]] = $item[$v];
					}
				}				
			}
			else
			{
				$val = $item[$arr_val];
			}
			
			$result[$key] = $val;
	    } 
	}
	
	return $result;
}


/*
 * Returns the quarter of given month
 */
function get_quarter_of_month($month)
{
	return ceil($month / 3);
}

/*
 * Returns the number 
 */
function get_month_num_in_quarter($month)
{
	$qtr_months = array(0, 1, 2, 3, 1, 2, 3, 1, 2, 3, 1, 2, 3);
	return $qtr_months[$month];
}

/*
 * Returns the number 
 */
function get_month_num_in_half_year($month)
{
	$semi_months = array(0, 1, 2, 3, 4, 5, 6, 1, 2, 3, 4, 5, 6);
	return $semi_months[$month];
}

/*
 * Returns PDO Message
 */
function get_pdo_message($errorInfo)
{
	$return = array();
	if (count($errorInfo) == 3)
	{
		$return[0] = $errorInfo[0] . '-' . $errorInfo[1]; 
		$return[1] = $errorInfo[2];
	}
	return $return;
}


/*
 * 
 */
function convert_string_to_val($str, $idx=-1)
{
	if (empty($str))
		return NULL;

	$tmp_val = explode(',', $str);
	
	if ($idx > -1)
		return (isset($tmp_val[$idx]) ? $tmp_val[$idx] : NULL);
	else
		return $tmp_val;
}

/*
 * 
 */
function build_sorter($key) {
    return function ($a, $b) use ($key) {
        return strnatcmp($b[$key], $a[$key]);
    };
}

function number_to_words($number)
{
    $integer = (int) $number;
	$num = number_format($number, 2, ".", ",");
	$fraction = substr(strrchr($num, "."), 1);
	
    $output = "";

    if ($integer{0} == "-")
    {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+")
    {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0")
    {
        $output .= "Zero";
    }
    else
    {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g)
        {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++)
        {
            if ($groups2[$z] != "")
            {
                $groups2[10] = trim(str_replace('and ','', $groups2[10]));
				$groups2[11] = trim(str_replace('and ','', $groups2[11]));
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? "  "
                            : " "
                    );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0)
    {
		$fraction = rtrim(chunk_split($fraction, 1, " "), " ");
        $fraction = explode(" ", $fraction);
		$fraction = convertTwoDigit($fraction[0], $fraction[1]);
		return $output .= ' Pesos and '.$fraction.' Centavos only';
		
    } else {
		
		return $output;
		
	}
}

function convertGroup($index)
{
    switch ($index)
    {
        case 11:
            return " Decillion";
        case 10:
            return " Nonillion";
        case 9:
            return " Octillion";
        case 8:
            return " Septillion";
        case 7:
            return " Sextillion";
        case 6:
            return " Quintrillion";
        case 5:
            return " Quadrillion";
        case 4:
            return " Trillion";
        case 3:
            return " Billion";
        case 2:
            return " Million";
        case 1:
            return " Thousand";
        case 0:
            return "";
    }
}

function convertThreeDigit($digit1, $digit2, $digit3)
{
    $buffer = "";

    if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
    {
        return "";
    }

    if ($digit1 != "0")
    {
        $buffer .= convertDigit($digit1) . " Hundred";
        if ($digit2 != "0" || $digit3 != "0")
        {
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0")
    {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0")
    {
        $buffer .= convertDigit($digit3);
    }

    return $buffer;
}

function convertTwoDigit($digit1, $digit2)
{
    if ($digit2 == "0")
    {
        switch ($digit1)
        {
            case "1":
                return "Ten";
            case "2":
                return "Twenty";
            case "3":
                return "Thirty";
            case "4":
                return "Forty";
            case "5":
                return "Fifty";
            case "6":
                return "Sixty";
            case "7":
                return "Seventy";
            case "8":
                return "Eighty";
            case "9":
                return "Ninety";
        }
    } else if ($digit1 == "1")
    {
        switch ($digit2)
        {
            case "1":
                return "Eleven";
            case "2":
                return "Twelve";
            case "3":
                return "Thirteen";
            case "4":
                return "Fourteen";
            case "5":
                return "Fifteen";
            case "6":
                return "Sixteen";
            case "7":
                return "Seventeen";
            case "8":
                return "Eighteen";
            case "9":
                return "Nineteen";
        }
    } else
    {
        $temp = convertDigit($digit2);
        switch ($digit1)
        {
            case "2":
                return "Twenty-$temp";
            case "3":
                return "Thirty-$temp";
            case "4":
                return "Forty-$temp";
            case "5":
                return "Fifty-$temp";
            case "6":
                return "Sixty-$temp";
            case "7":
                return "Seventy-$temp";
            case "8":
                return "Eighty-$temp";
            case "9":
                return "Ninety-$temp";
        }
    }
}

function convertDigit($digit)
{
    switch ($digit)
    {
        case "0":
            return "Zero";
        case "1":
            return "One";
        case "2":
            return "Two";
        case "3":
            return "Three";
        case "4":
            return "Four";
        case "5":
            return "Five";
        case "6":
            return "Six";
        case "7":
            return "Seven";
        case "8":
            return "Eight";
        case "9":
            return "Nine";
    }
}

function convertTitleCase($title) 
{
	try
	{

		$title_str 		 = strtolower($title);

		$param_lower = get_sysparam_value(PARAM_RESERVED_LOWERCASE_WORDS);
		$param_upper = get_sysparam_value(PARAM_RESERVED_UPPERCASE_WORDS);
		$smallwordsarray = explode(',', $param_lower['sys_param_value']);
		$upperwordsarray = explode(',', $param_upper['sys_param_value']);
		$words 			 = explode(' ', $title_str);
		foreach ($words as $key => $word) 
		{
			if (!$key or !in_array($word, $smallwordsarray))
			{
				$words[$key] = ucwords($word);
			}
			if(in_array($word, $upperwordsarray))
			{
				$words[$key] = strtoupper($word);
			}
		}

		$new_title = implode(' ', $words);

	}
	catch(Exception $e)
	{
		$message = $e->getMessage();
		RLog::error($message);
	}
	return $new_title;
}
function get_sysparam_value($sys_param_type)
{
	$CI =& get_instance();

	$CI->load->model( 'settings_model');

	$result = $CI->settings_model->get_sysparam_value( $sys_param_type );

	return $result;
}