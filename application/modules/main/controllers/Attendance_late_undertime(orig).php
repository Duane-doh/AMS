<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendance_late_undertime extends Main_Controller {
	private $module = MODULE_TA_WORK_SCHEDULE;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('daily_time_record_model', 'dtr');
	}
	public function check_late_undertime($attendance_log)
	{
		try
		{
			$data              = array();
			$employee_id       = $attendance_log['employee_id'];
			$date              = $attendance_log['attendance_date'];
			
			$day_off           = false;
			$earliest_in       = '';
			$latest_in         = '';
			$data['tardiness'] = 0;
			$data['undertime'] = 0;
			$break_hours       = 0;
			$break_time        = 0;
			$work_schedule     = $this->dtr->get_employee_work_schedule($employee_id,$date);
			
			$day           	   = strtolower(date('D',strtotime($date)));

			// marvin
			if($work_schedule[$day.'_type_of_duty'] == 8)
			{
				if($work_schedule)
				{
					$break_hours = $work_schedule['break_hours'];
					$break_time = $work_schedule['break_time'];

					$earliest_in   = $work_schedule[$day.'_earliest_in'];
					$latest_in     = $work_schedule[$day.'_latest_in'];
				}
				if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
				{
					$sixty_seconds       = 60;
					$sixty_minutes       = 60;
					$eight_working_hours = 8;

					$has_break           = (!EMPTY($break_time) ? TRUE : FALSE);
					$earliest_in         = $date.' '.$earliest_in;
					$latest_in           = $date.' '.$latest_in;
					$break_time          = $date.' '.$break_time;
					
					$minutes_to_add   = $break_hours * 60;
					
					$new_time         = new DateTime($break_time);
					$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
					
					$break_time_hours = $new_time->format('Y-m-d H:i:s');

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
					// if($time_in > $latest_in)
					// {
						// if($has_break)
						// {
							// if($time_in < $break_time)
							// {
								// $tardiness         = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
							// }elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
							// {
								// $tardiness         = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
							// }
							// else
							// {
								// $tardiness         = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
							// }
						// }
						// else
						// {
								// $tardiness         = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
						// }					

						// $data['tardiness'] = ($tardiness/$sixty_minutes);
						
						// $data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
						// $data['tardiness_min'] = ($tardiness%$sixty_minutes);
					// }
					
					/*====================================== MARVIN : START : LATE COMPUTATION ======================================*/
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
					if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
					{
						$tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
					}
					
					$data['tardiness'] 		= ($tardiness/$sixty_minutes);
					$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
					$data['tardiness_min'] 	= ($tardiness%$sixty_minutes);
					/*====================================== MARVIN : END : LATE COMPUTATION ======================================*/
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
						$time_out = $attendance_log['break_in'];
					}
					$time_out = date('Y-m-d H:i:s', strtotime($time_out));

					if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
					{
						$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
						// $undertime_temp = (strtotime($time_in_variable) + (($eight_working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
					}
					else
					{
						$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
						// $undertime_temp = (strtotime($time_in_variable) + (($eight_working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
					}
					

					if($undertime_temp > 0)
					{
						$undertime = ($undertime_temp / $sixty_seconds);

						$data['undertime']      = ($undertime > 0  AND $undertime < (20*60)) ?  round($undertime/$sixty_minutes,3): 0;
						$data['undertime_hour'] = floor($undertime/$sixty_minutes);
						$data['undertime_min']  = ($undertime%$sixty_minutes);

						if($data['undertime_hour'] > 8)
						{

							$where                 = array();
							$where['holiday_date'] = $date;
							$holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
							if(EMPTY($holiday))
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
								$data['holiday_name'] = ! empty($holiday['title']) ? $holiday['title'] : '';
							}
						}
					}
					/*================================================== MARVIN : START : FIX HOLIDAY ==================================================*/
					else
					{
						$where                 = array();
						$where['holiday_date'] = $date;
						$holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
						if(!EMPTY($holiday))
						{
							$data['holiday_name'] = ! empty($holiday['title']) ? $holiday['title'] : '';
							$data['undertime']      = 0;
							$data['undertime_hour'] = 0;
							$data['undertime_min']  = 0;
							
							$data['tardiness']      = 0;
							$data['tardiness_hour'] = 0;
							$data['tardiness_min']  = 0;
						}
					}
					/*================================================== MARVIN : END : FIX HOLIDAY ==================================================*/
					/*
					 * END : UNDERTIME COMPUTATION
					 */

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
			}
			else
			{				
				if($work_schedule)
				{
					$earliest_in   = $work_schedule[$day.'_earliest_in'];
					$latest_in     = $work_schedule[$day.'_latest_in'];
				}
				
				if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
				{
					$sixty_seconds       = 60;
					$sixty_minutes       = 60;
					$working_hours = $work_schedule[$day.'_type_of_duty'];

					$earliest_in         = $date.' '.$earliest_in;
					$latest_in           = $date.' '.$latest_in;

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

					//marvin
					//late computation
					if($time_in > $latest_in)
					{
						$tardiness         = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);

						$data['tardiness'] = ($tardiness/$sixty_minutes);
						
						$data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
						$data['tardiness_min'] = ($tardiness%$sixty_minutes);
					}
					
					//marvin
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
					
					$time_out = date('Y-m-d H:i:s', strtotime($time_out));

					$undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
					
					if($undertime_temp > 0)
					{
						$undertime = ($undertime_temp / $sixty_seconds);

						$data['undertime']      = ($undertime > 0  AND $undertime < (20 * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
						$data['undertime_hour'] = floor($undertime / $sixty_minutes);
						$data['undertime_min']  = ($undertime%$sixty_minutes);

						// if($data['undertime_hour'] > $working_hours)
						// {
							// $data['undertime']      = $working_hours;
							// $data['undertime_hour'] = $working_hours;
							// $data['undertime_min']  = 0;
							
							// $data['tardiness']      = 0;
							// $data['tardiness_hour'] = 0;
							// $data['tardiness_min']  = 0;
						// }
					}
					
					//marvin
					//hours work computation
					$undertime = ($data['undertime']) ? $data['undertime'] : 0;
					$tardiness = ($data['tardiness']) ? $data['tardiness'] : 0;
					$working_hours = $working_hours - ($undertime + $tardiness);
					$data['working_hours'] = ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
				}
				else
				{
					//assume this is timeout due to working schedule of 24 hours
					$earliest_in = '08:00:00';
					$latest_in = '08:00:00';
					
					$sixty_seconds       = 60;
					$sixty_minutes       = 60;
					$working_hours = $work_schedule[$day.'_type_of_duty'];

					$earliest_in         = $date.' '.$earliest_in;
					$latest_in           = $date.' '.$latest_in;
					
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
					
					$time_out = date('Y-m-d H:i:s', strtotime($time_out));

					$undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
					
					if($undertime_temp > 0)
					{
						$undertime = ($undertime_temp / $sixty_seconds);

						$data['undertime']      = ($undertime > 0  AND $undertime < (20 * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
						$data['undertime_hour'] = floor($undertime / $sixty_minutes);
						$data['undertime_min']  = ($undertime%$sixty_minutes);

						// if($data['undertime_hour'] > $working_hours)
						// {
							// $data['undertime']      = $working_hours;
							// $data['undertime_hour'] = $working_hours;
							// $data['undertime_min']  = 0;
							
							// $data['tardiness']      = 0;
							// $data['tardiness_hour'] = 0;
							// $data['tardiness_min']  = 0;
						// }
					}
					
					//marvin
					//hours work computation
					$undertime = ($data['undertime']) ? $data['undertime'] : 0;
					$tardiness = ($data['tardiness']) ? $data['tardiness'] : 0;
					$working_hours = $working_hours - ($undertime + $tardiness);
					$data['working_hours'] = ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
				}
				// else
				// {
					//marvin
					//if no duty in work schedule, assume this is time_out not time_in
					// $attendance_log['time_out'] = $attendance_log['time_in'];
					// $attendance_log['time_in'] = '';
					// $data['new_time_out'] = $attendance_log['time_out'];
				// }
				
				//marvin
				//type of duty for validation in employee_dtr controller
				$data['type_of_duty'] = 24;
			}

			return $data;
		}
		catch(Exception $e)
		{
			throw $e;			
			$message = $e->getMessage();
			RLog::error($message);
		}
	}
	
	public function check_date($employee_id,$date)
	{
		try
		{	
			$data              = array();
			
			$active_date    = date('Y-m-d',strtotime($date));

			$rest_day           = false;
			$holiday           = false;

			$work_schedule = $this->dtr->get_employee_work_schedule($employee_id,$active_date);
			
			$day           = date('D',strtotime($date));

			if($work_schedule)
			{
				switch (strtolower($day)) {
					case 'mon':
						if(EMPTY($work_schedule['mon_earliest_in']))
							$rest_day = true;
						break;
					case 'tue':
						if(EMPTY($work_schedule['tue_earliest_in']))
							$rest_day = true;
						break;
					case 'wed':
						if(EMPTY($work_schedule['wed_earliest_in']))
							$rest_day = true;
						break;
					case 'thu':
						if(EMPTY($work_schedule['thu_earliest_in']))
							$rest_day = true;
						break;
					case 'fri':
						if(EMPTY($work_schedule['fri_earliest_in']))
							$rest_day = true;
						break;
					case 'sat':
						if(EMPTY($work_schedule['sat_earliest_in']))
							$rest_day = true;
						break;
					case 'sun':
						if(EMPTY($work_schedule['sun_earliest_in']))
							$rest_day = true;
						break;
				}
			}			
			RLog::info('LINE 166 : DATE : '.$date.'   DAY: '.$day.'   REST DAY : '.$rest_day);
			$tables                = $this->dtr->tbl_param_work_calendar;
			$where                 = array();
			$where['holiday_date'] = $active_date;
			$check_holiday         = $this->dtr->get_general_data(array("*"), $tables, $where, FALSE);
			if($check_holiday)
			{
				$holiday = true;
			}
			$data['rest_day'] = $rest_day;
			$data['holiday']  = $holiday;
			
			return $data;
			
			
		}
		catch(Exception $e)
		{
			throw $e;			
			$message = $e->getMessage();
			RLog::error($message);
		}
	}

	public function check_working_days($employee_id,$date_from = NULL,$date_to =NULL)
	{
		try
		{	
			$dates       = array();
			$date_from   = date('Y-m-d',strtotime ($date_from));
			$date_to     = date('Y-m-d',strtotime ($date_to));
			
			$active_date = $date_from;

			if($date_from <= $date_to )
			{
				while($active_date <= $date_to )
				{
					$result = $this->check_date($employee_id,$active_date);
					if($result['rest_day'] != true AND $result['holiday'] != true)
					{
						$dates[] = date('Y/m/d',strtotime ($active_date));
					}
					$active_date = date('Y-m-d',strtotime('+1 day' , strtotime ( $active_date ) ) );					
				}				
			}
			

			return $dates;
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}
}

/* End of file Attendance_late_undertime.php */
/* Location: ./application/modules/main/controllers/Attendance_late_undertime.php */