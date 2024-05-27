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
					$holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
					
					//during working hours suspension flag
					$during_working_hours 		= false;					
					$during_early_suspension 	= false;					
					if(!empty($holiday))
					{
						$holiday_start_time = $holiday['holiday_date'] . ' ' . $holiday['start_time'];
						$holiday_end_time 	= $holiday['holiday_date'] . ' ' . $holiday['end_time'];

						//EARLY SUSPENSION
						if($holiday['holiday_type_id'] == '5')
						{
								$during_early_suspension = true;
								$earliest_in 	= $date . ' ' . '08:00:00';
								$latest_in 		= $date . ' ' . '08:00:00';
						}
						//DURING WORKING HOURS SUSPENSION
						if($holiday['holiday_type_id'] == '6')
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
					}
					
					$time_out = date('Y-m-d H:i:s', strtotime($time_out));
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
						$undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
					}
					if($during_early_suspension)
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
						else
						{
							$where                 = array();
							$where['holiday_date'] = $date;
							$holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
							$_early_suspension_types = array(3,5,6);
							if(!EMPTY($holiday) AND !in_array($holiday['holiday_type_id'], $_early_suspension_types))
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
						/* ================ : marvin : start : include suspension in flexi ================ */
						// else
						// {
							// $where                 = array();
							// $where['holiday_date'] = $date;
							// $holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
							
							// if(!EMPTY($holiday))
							// {
								// $holiday_start_time = $holiday['holiday_date'] . ' ' . $holiday['start_time'];
								// $holiday_end_time = $holiday['holiday_date'] . ' ' . $holiday['end_time'];
								// $time_out_log = date('Y-m-d H:i:s', strtotime($attendance_log['time_out']));
								
								//EARLY SUSPENSION
								// if($holiday_start_time == $holiday_end_time AND $time_out_log >= $holiday_start_time)
								// {
									// $data['holiday_name'] 	= !empty($holiday['title']) ? $holiday['title'] : '';
									// $data['undertime']      = 0;
									// $data['undertime_hour'] = 0;
									// $data['undertime_min']  = 0;
								// }
								//DURING WORKING HOURS SUSPENSION
								// else
								// {
									//LATE COMPUTATION
									// $latest_in = $date . ' ' . '08:00:00';
									
									// if($time_in > $latest_in)
									// {
										// if($has_break)
										// {
											// if($time_in < $break_time)
											// {
												// $tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);
											// }
											// elseif($time_in >= $break_time AND $time_in <= $break_time_hours)
											// {
												// $tardiness = ((strtotime($break_time) - strtotime($latest_in)) / $sixty_seconds);
											// }
											// else
											// {
												// $tardiness = (((strtotime($time_in) - strtotime($latest_in)) - (($break_hours * $sixty_minutes)*$sixty_seconds)) / $sixty_seconds);
											// }
										// }
										// else
										// {
											// $tardiness = ((strtotime($time_in) - strtotime($latest_in)) / $sixty_seconds);						
										// }					

									// }
									
									/* additionational late computation with late break-in*/
									// if(date('Y-m-d H:i:s', strtotime($attendance_log['break_in'])) > $break_time_hours)
									// {
										// $tardiness += ((strtotime($attendance_log['break_in']) - strtotime($break_time_hours)) / $sixty_seconds);
									// }
									
									// $data['tardiness'] 		= ($tardiness/$sixty_minutes);
									// $data['tardiness_hour'] = floor($tardiness/$sixty_minutes);
									// $data['tardiness_min'] 	= ($tardiness%$sixty_minutes);
									
									//UNDERTIME COMPUTATION
									// $break_time_hours = $holiday_start_time;
									// if($has_break AND $time_out >= $break_time AND $time_out <= $break_time_hours)
									// {
										// $undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($break_time_hours);
									// }
									/*===================== MARVIN : START : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									// elseif($has_break AND $time_out >= $latest_in AND $time_out <= $break_time)
									// {
										// $undertime_temp = (strtotime($time_in_variable) + ((($eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									// }
									/*===================== MARVIN : END : UNDERTIME COMPUTATION FOR TIME OUT BEFORE BREAK TIME ============= */
									// else
									// {
										// $undertime_temp = (strtotime($time_in_variable) + ((($break_hours + $eight_working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
									// }
									
									// if($undertime_temp > 0)
									// {
										// $undertime = ($undertime_temp / $sixty_seconds);

										// $data['undertime']      = ($undertime > 0  AND $undertime < (20*60)) ?  round($undertime/$sixty_minutes,3): 0;
										// $data['undertime_hour'] = floor($undertime/$sixty_minutes);
										// $data['undertime_min']  = ($undertime%$sixty_minutes);
									// }
								// }
							// }
						// }
						/* ================ : marvin : end : include suspension in flexi ================ */											
					}
					/*================================================== MARVIN : START : FIX HOLIDAY ==================================================*/
					else
					{
						
						$where                 = array();
						$where['holiday_date'] = $date;
						$holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
						if(!EMPTY($holiday))
						{
							if($holiday['holiday_type_id'] !== '5' || $holiday['holiday_type_id'] !=='6')
							{				

							}
							else
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
				//marvin : check work schedule : start
				switch($work_schedule[$day.'_type_of_duty'])
				{
					case 4:
					
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= ($work_schedule['break_time'] == '00:00:00' ? null : $work_schedule['break_time']);
							
							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in 		= $work_schedule[$day.'_latest_in'];
						}
						
						if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
						{
							$sixty_seconds 	= 60;
							$sixty_minutes 	= 60;
							$working_hours 	= $work_schedule[$day.'_type_of_duty'];
							$has_break 		= ($break_time > 0 ? TRUE : FALSE);
							
							$earliest_in 	= $date.' '.$earliest_in;
							$latest_in   	= $date.' '.$latest_in;
							
							$break_time     = $date.' '.$break_time;
					
							$minutes_to_add = $break_hours * 60;
							
							$new_time 		= new DateTime($break_time);
							$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
							
							$break_time_hours = $new_time->format('Y-m-d H:i:s');
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
						
						/* additionational late computation with late break-in*/
						if($has_break)
							{
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
						if(empty($attendance_log['time_in']) AND !empty($attendance_log['break_in']))
						{
							//get next work schedule
							$next_work_schedule = $this->dtr->get_employee_work_schedule($employee_id, date('Y-m-d', strtotime($date . '+1 day')));
							$day = strtolower(date('D',strtotime($date . '+1 day')));
							
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
						
						
						
						$undertime_temp = (strtotime($time_in_variable) + ((($working_hours) * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
						
						// $undertime_temp = (strtotime($time_in_variable) + (($working_hours * $sixty_minutes) * $sixty_seconds)) - strtotime($time_out);
						
						if($undertime_temp != 0)
						{

							$undertime = ($undertime_temp / $sixty_seconds);

							$data['undertime']      = ($undertime > 0  AND $undertime <= ($working_hours * 60)) ?  round($undertime / $sixty_minutes, 3) : 0;
							$data['undertime_hour'] = floor($undertime / $sixty_minutes);
							$data['undertime_min']  = ($undertime%$sixty_minutes);
							
							if($data['undertime_hour'] > $working_hours)
							{

								$where                 = array();
								$where['holiday_date'] = $date;
								$holiday               = $this->dtr->get_general_data(array("*"), $this->dtr->tbl_param_work_calendar, $where, FALSE);
								if(EMPTY($holiday))
								{
									$data['holiday_name']	= '';
									$data['undertime']      = $working_hours;
									$data['undertime_hour'] = $working_hours;
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
						
						//hours work computation
						$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
						$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
						$working_hours 			= $working_hours - ($undertime + $tardiness);
						$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
					
						break;
					// ============================================================
					//davcorrea Include 4 hours work sched
					case 10:
					
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= ($work_schedule['break_time'] == '00:00:00' ? null : $work_schedule['break_time']);
							
							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in 		= $work_schedule[$day.'_latest_in'];
						}
						
						if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
						{
							$sixty_seconds 	= 60;
							$sixty_minutes 	= 60;
							$working_hours 	= $work_schedule[$day.'_type_of_duty'];
							
							$has_break 		= (!EMPTY($break_time) ? TRUE : FALSE);
							
							$earliest_in 	= $date.' '.$earliest_in;
							$latest_in   	= $date.' '.$latest_in;
							
							$break_time     = $date.' '.$break_time;
					
							$minutes_to_add = $break_hours * 60;
							
							$new_time 		= new DateTime($break_time);
							$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
							
							$break_time_hours = $new_time->format('Y-m-d H:i:s');
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
						
						//hours work computation
						$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
						$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
						$working_hours 			= $working_hours - ($undertime + $tardiness);
						$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
						
						break;
						
					case 12:
						
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= $work_schedule['break_time'];

							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in   	= $work_schedule[$day.'_latest_in'];
						}
						
						if(!EMPTY($earliest_in) AND !EMPTY($latest_in) AND !empty($attendance_log['time_in']) AND !empty($attendance_log['time_out']))
						{
							$sixty_seconds 	= 60;
							$sixty_minutes 	= 60;
							$working_hours 	= $work_schedule[$day.'_type_of_duty'];

							$has_break 		= (!EMPTY($break_time) ? TRUE : FALSE);
							
							$earliest_in 	= $date.' '.$earliest_in;
							$latest_in   	= $date.' '.$latest_in;

							$break_time     = $date.' '.$break_time;
					
							$minutes_to_add = $break_hours * 60;
							
							$new_time 		= new DateTime($break_time);
							$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
							
							$break_time_hours = $new_time->format('Y-m-d H:i:s');
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
							
							if($next_work_schedule[$day.'_type_of_duty'] == 12)
							{
								//get next day attendance log
								$fields 					= array('time_flag', 'DATE_FORMAT(time_log, "%Y/%m/%d %h:%i %p") AS time_log');
								$table 						= 'employee_attendance';
								$where 						= array();
								$where['employee_id']		= $attendance_log['employee_id'];
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
						
						//hours work computation
						$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
						$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
						$working_hours 			= $working_hours - ($undertime + $tardiness);
						$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
						break;
					
					case 16:
						
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= $work_schedule['break_time'];

							$earliest_in 	= $work_schedule[$day.'_earliest_in'];
							$latest_in   	= $work_schedule[$day.'_latest_in'];
						}
						
						if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
						{
							$sixty_seconds 	= 60;
							$sixty_minutes 	= 60;
							$working_hours 	= $work_schedule[$day.'_type_of_duty'];

							$has_break 		= (!EMPTY($break_time) ? TRUE : FALSE);
							
							$earliest_in 	= $date.' '.$earliest_in;
							$latest_in   	= $date.' '.$latest_in;

							$break_time     = $date.' '.$break_time;
					
							$minutes_to_add = $break_hours * 60;
							
							$new_time 		= new DateTime($break_time);
							$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
							
							$break_time_hours = $new_time->format('Y-m-d H:i:s');
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
						
						//hours work computation
						$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
						$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
						$working_hours 			= $working_hours - ($undertime + $tardiness);
						$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
						break;
						
					case 24:
					
						if($work_schedule)
						{
							$break_hours 	= $work_schedule['break_hours'];
							$break_time 	= $work_schedule['break_time'];

							$earliest_in = $work_schedule[$day.'_earliest_in'];
							$latest_in   = $work_schedule[$day.'_latest_in'];
						}
						
						if(!EMPTY($earliest_in) AND !EMPTY($latest_in))
						{
							$sixty_seconds = 60;
							$sixty_minutes = 60;
							$working_hours = $work_schedule[$day.'_type_of_duty'];

							$has_break 		= (!EMPTY($break_time) ? TRUE : FALSE);
							
							$earliest_in = $date.' '.$earliest_in;
							$latest_in   = $date.' '.$latest_in;

							$break_time     = $date.' '.$break_time;
					
							$minutes_to_add = $break_hours * 60;
							
							$new_time 		= new DateTime($break_time);
							$new_time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
							
							$break_time_hours = $new_time->format('Y-m-d H:i:s');
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
								$where['employee_id']		= $attendance_log['employee_id'];
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
						
						//hours work computation
						$undertime 				= ($data['undertime']) ? $data['undertime'] : 0;
						$tardiness 				= ($data['tardiness']) ? $data['tardiness'] : 0;
						$working_hours 			= $working_hours - ($undertime + $tardiness);
						$data['working_hours'] 	= ($working_hours > 0 AND $working_hours < 100) ? round($working_hours, 3) : 0;
						break;
				}
				//marvin : check work schedule : end
				
				$data['type_of_duty'] = $work_schedule[$day.'_type_of_duty'];
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
			$where['holiday_type_id'] = '2';
			$check_holiday         = $this->dtr->get_general_data(array("*"), $tables, $where, FALSE);
			if($check_holiday)
			{
				$holiday = true;
			}
			$tables                = $this->dtr->tbl_param_work_calendar;
			$where                 = array();
			$where['holiday_date'] = $active_date;
			$where['holiday_type_id'] = '4';
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
	
	/*===== marvin : start : include nature_of_deduction =====*/
	public function check_working_days_with_nod($employee_id,$date_from = NULL,$date_to =NULL)
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
					// $result = $this->check_date($employee_id,$active_date);
					// if($result['rest_day'] != true AND $result['holiday'] != true)
					// {
						$dates[] = date('Y/m/d',strtotime ($active_date));
					// }
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
	/*===== marvin : end : include nature_of_deduction =====*/
}

/* End of file Attendance_late_undertime.php */
/* Location: ./application/modules/main/controllers/Attendance_late_undertime.php */