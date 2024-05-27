
<form id="work_schedule_form">
	<input type="hidden" name="id" id="id" value="<?php echo !EMPTY($id) ? $id : NULL?>">
	<input type="hidden" name="salt" id="salt" value="<?php echo !EMPTY($salt) ? $salt : NULL?>">
	<input type="hidden" name="token" id="token" value="<?php echo !EMPTY($token) ? $token : NULL?>">
	<input type="hidden" name="action" id="action" value="<?php echo !EMPTY($action) ? $action : NULL?>">

	<div class="form-float-label">
		<div class="row">
			<div class="col s6">
				<div class="input-field">
					<label for="work_schedule_name">Work Schedule Name<span class="required">*</span></label>
					<input type="text" class="validate" required name="work_schedule_name" id="work_schedule_name" value="<?php echo isset($work_schedule_info['work_schedule_name']) ? $work_schedule_info['work_schedule_name'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="break_hours">Break Hours<span class="required">*</span></label>
					<input type="number" class="validate" required name="break_hours" id="break_hours" step="0.05" value="<?php echo isset($work_schedule_info['break_hours']) ? $work_schedule_info['break_hours'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<!--marvin-->
			<!--added a breaktime-->
			<div class="col s3">
				<div class="input-field">
					<label for="break_time">Break Time</label>
					<input type="text" class="timepicker" name="break_time" id="break_time" value="<?php echo isset($work_schedule_info['break_time']) ? $work_schedule_info['break_time'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">MON</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="mon_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="mon_earliest_in" id="mon_earliest_in" value="<?php //echo isset($work_schedule_info['mon_earliest_in']) ? $work_schedule_info['mon_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="mon_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="mon_latest_in" id="mon_latest_in" value="<?php //echo isset($work_schedule_info['mon_latest_in']) ? $work_schedule_info['mon_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">MON</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="mon_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="mon_earliest_in" id="mon_earliest_in" value="<?php echo isset($work_schedule_info['mon_earliest_in']) ? $work_schedule_info['mon_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="mon_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="mon_latest_in" id="mon_latest_in" value="<?php echo isset($work_schedule_info['mon_latest_in']) ? $work_schedule_info['mon_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="mon_duty_24" class="with-gap" name="mon_type_of_duty" value="24" <?php //echo $work_schedule_info['mon_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="mon_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="mon_duty_16" class="with-gap" name="mon_type_of_duty" value="16" <?php //echo $work_schedule_info['mon_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="mon_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="mon_duty_12" class="with-gap" name="mon_type_of_duty" value="12" <?php //echo $work_schedule_info['mon_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="mon_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="mon_duty_8" class="with-gap" name="mon_type_of_duty" value="8" <?php //echo $work_schedule_info['mon_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="mon_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="mon_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="mon_type_of_duty" name="mon_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['mon_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['mon_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['mon_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['mon_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['mon_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['mon_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['mon_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">TUE</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="tue_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="tue_earliest_in" id="tue_earliest_in" value="<?php //echo isset($work_schedule_info['tue_earliest_in']) ? $work_schedule_info['tue_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="tue_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="tue_latest_in" id="tue_latest_in" value="<?php //echo isset($work_schedule_info['tue_latest_in']) ? $work_schedule_info['tue_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">TUE</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="tue_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="tue_earliest_in" id="tue_earliest_in" value="<?php echo isset($work_schedule_info['tue_earliest_in']) ? $work_schedule_info['tue_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="tue_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="tue_latest_in" id="tue_latest_in" value="<?php echo isset($work_schedule_info['tue_latest_in']) ? $work_schedule_info['tue_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="tue_duty_24" class="with-gap" name="tue_type_of_duty" value="24" <?php //echo $work_schedule_info['tue_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="tue_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="tue_duty_16" class="with-gap" name="tue_type_of_duty" value="16" <?php //echo $work_schedule_info['tue_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="tue_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="tue_duty_12" class="with-gap" name="tue_type_of_duty" value="12" <?php //echo $work_schedule_info['tue_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="tue_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="tue_duty_8" class="with-gap" name="tue_type_of_duty" value="8" <?php //echo $work_schedule_info['tue_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="tue_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="tue_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="tue_type_of_duty" name="tue_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['tue_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['tue_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['tue_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['tue_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['tue_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['tue_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['tue_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">WED</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="wed_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="wed_earliest_in" id="wed_earliest_in" value="<?php //echo isset($work_schedule_info['wed_earliest_in']) ? $work_schedule_info['wed_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="wed_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="wed_latest_in" id="wed_latest_in" value="<?php //echo isset($work_schedule_info['wed_latest_in']) ? $work_schedule_info['wed_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">WED</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="wed_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="wed_earliest_in" id="wed_earliest_in" value="<?php echo isset($work_schedule_info['wed_earliest_in']) ? $work_schedule_info['wed_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="wed_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="wed_latest_in" id="wed_latest_in" value="<?php echo isset($work_schedule_info['wed_latest_in']) ? $work_schedule_info['wed_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="wed_duty_24" class="with-gap" name="wed_type_of_duty" value="24" <?php //echo $work_schedule_info['wed_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="wed_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="wed_duty_16" class="with-gap" name="wed_type_of_duty" value="16" <?php //echo $work_schedule_info['wed_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="wed_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="wed_duty_12" class="with-gap" name="wed_type_of_duty" value="12" <?php //echo $work_schedule_info['wed_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="wed_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="wed_duty_8" class="with-gap" name="wed_type_of_duty" value="8" <?php //echo $work_schedule_info['wed_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="wed_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="wed_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="wed_type_of_duty" name="wed_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['wed_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['wed_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['wed_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['wed_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['wed_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['wed_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['wed_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">THU</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="thu_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="thu_earliest_in" id="thu_earliest_in" value="<?php //echo isset($work_schedule_info['thu_earliest_in']) ? $work_schedule_info['thu_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="thu_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="thu_latest_in" id="thu_latest_in" value="<?php //echo isset($work_schedule_info['thu_latest_in']) ? $work_schedule_info['thu_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">THU</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="thu_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="thu_earliest_in" id="thu_earliest_in" value="<?php echo isset($work_schedule_info['thu_earliest_in']) ? $work_schedule_info['thu_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="thu_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="thu_latest_in" id="thu_latest_in" value="<?php echo isset($work_schedule_info['thu_latest_in']) ? $work_schedule_info['thu_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="thu_duty_24" class="with-gap" name="thu_type_of_duty" value="24" <?php //echo $work_schedule_info['thu_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="thu_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="thu_duty_16" class="with-gap" name="thu_type_of_duty" value="16" <?php //echo $work_schedule_info['thu_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="thu_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="thu_duty_12" class="with-gap" name="thu_type_of_duty" value="12" <?php //echo $work_schedule_info['thu_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="thu_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="thu_duty_8" class="with-gap" name="thu_type_of_duty" value="8" <?php // $work_schedule_info['thu_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="thu_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="thu_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="thu_type_of_duty" name="thu_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['thu_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['thu_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['thu_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['thu_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['thu_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['thu_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['thu_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">FRI</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="fri_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="fri_earliest_in" id="fri_earliest_in" value="<?php //echo isset($work_schedule_info['fri_earliest_in']) ? $work_schedule_info['fri_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="sat_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="fri_latest_in" id="fri_latest_in" value="<?php //echo isset($work_schedule_info['fri_latest_in']) ? $work_schedule_info['fri_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">FRI</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="fri_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="fri_earliest_in" id="fri_earliest_in" value="<?php echo isset($work_schedule_info['fri_earliest_in']) ? $work_schedule_info['fri_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="sat_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="fri_latest_in" id="fri_latest_in" value="<?php echo isset($work_schedule_info['fri_latest_in']) ? $work_schedule_info['fri_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="fri_duty_24" class="with-gap" name="fri_type_of_duty" value="24" <?php //echo $work_schedule_info['fri_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="fri_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="fri_duty_16" class="with-gap" name="fri_type_of_duty" value="16" <?php //echo $work_schedule_info['fri_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="fri_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="fri_duty_12" class="with-gap" name="fri_type_of_duty" value="12" <?php //echo $work_schedule_info['fri_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="fri_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="fri_duty_8" class="with-gap" name="fri_type_of_duty" value="8" <?php //echo $work_schedule_info['fri_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="fri_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="fri_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="fri_type_of_duty" name="fri_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['fri_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['fri_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['fri_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['fri_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['fri_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['fri_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['fri_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">SAT</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="sat_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="sat_earliest_in" id="sat_earliest_in" value="<?php //echo isset($work_schedule_info['sat_earliest_in']) ? $work_schedule_info['sat_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="sat_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="sat_latest_in" id="sat_latest_in" value="<?php //echo isset($work_schedule_info['sat_latest_in']) ? $work_schedule_info['sat_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel grey darken-3 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">SAT</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="sat_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="sat_earliest_in" id="sat_earliest_in" value="<?php echo isset($work_schedule_info['sat_earliest_in']) ? $work_schedule_info['sat_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="sat_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="sat_latest_in" id="sat_latest_in" value="<?php echo isset($work_schedule_info['sat_latest_in']) ? $work_schedule_info['sat_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="sat_duty_24" class="with-gap" name="sat_type_of_duty" value="24" <?php //echo $work_schedule_info['sat_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sat_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="sat_duty_16" class="with-gap" name="sat_type_of_duty" value="16" <?php //echo $work_schedule_info['sat_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sat_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="sat_duty_12" class="with-gap" name="sat_type_of_duty" value="12" <?php //echo $work_schedule_info['sat_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sat_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="sat_duty_8" class="with-gap" name="sat_type_of_duty" value="8" <?php //echo $work_schedule_info['sat_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sat_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="sat_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="sat_type_of_duty" name="sat_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['sat_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['sat_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['sat_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['sat_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['sat_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['sat_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['sat_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class="row">
			<!--
			<div class="col s2">
				<div class="card-panel deep-orange darken-4 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">SUN</span>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label  for="sun_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="sun_earliest_in" id="sun_earliest_in" value="<?php //echo isset($work_schedule_info['sun_earliest_in']) ? $work_schedule_info['sun_earliest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s5">
				<div class="input-field">
					<label for="sun_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="sun_latest_in" id="sun_latest_in" value="<?php //echo isset($work_schedule_info['sun_latest_in']) ? $work_schedule_info['sun_latest_in'] : NULL?>" <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			-->
			<!--marvin-->
			<div class="col s2">
				<div class="card-panel deep-orange darken-4 valign-middle p-md">
					<span class="font-md font-bold valign-middle white-text">SUN</span>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label  for="sun_earliest_in">Earliest Time</label>
					<input type="text" class="validate timepicker" name="sun_earliest_in" id="sun_earliest_in" value="<?php echo isset($work_schedule_info['sun_earliest_in']) ? $work_schedule_info['sun_earliest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s3">
				<div class="input-field">
					<label for="sun_latest_in">Latest Time</label>
					<input type="text" class="validate timepicker" name="sun_latest_in" id="sun_latest_in" value="<?php echo isset($work_schedule_info['sun_latest_in']) ? $work_schedule_info['sun_latest_in'] : NULL?>" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>/>
				</div>
			</div>
			<div class="col s4">
				<div>
					<!--
					<label class="active">Type of Duty</label>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="sun_duty_24" class="with-gap" name="sun_type_of_duty" value="24" <?php //echo $work_schedule_info['sun_type_of_duty'] == 24 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sun_duty_24">24</label>
						</div>
						<div class="col s6">
							<input type="radio" id="sun_duty_16" class="with-gap" name="sun_type_of_duty" value="16" <?php //echo $work_schedule_info['sun_type_of_duty'] == 16 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sun_duty_16">16</label>
						</div>
					</div>
					<div class="row">
						<div class="col s6">
							<input type="radio" id="sun_duty_12" class="with-gap" name="sun_type_of_duty" value="12" <?php //echo $work_schedule_info['sun_type_of_duty'] == 12 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sun_duty_12">12</label>
						</div>
						<div class="col s6">
							<input type="radio" id="sun_duty_8" class="with-gap" name="sun_type_of_duty" value="8" <?php //echo $work_schedule_info['sun_type_of_duty'] == 8 ? 'checked=checked' : NULL ?> <?php //echo $action == ACTION_VIEW ? 'disabled' :'' ?> />
							<label for="sun_duty_8">8</label>
						</div>
					</div>
					-->
					<!-- MARVIN : INCLUDE 10 HRS DUTY : START -->
					<div class="row">
						<div class="col s8">
							<div class="input-field">
								<label for="sun_type_of_duty" class="active">Type of Duty</label>
								<br>
								<select id="sun_type_of_duty" name="sun_type_of_duty" class="selectize" <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>>
									<option value="" <?php echo $work_schedule_info['sun_type_of_duty'] == '' ? 'selected' : NULL ?>>None</option>
									<option value="4" <?php echo $work_schedule_info['sun_type_of_duty'] == 4 ? 'selected' : NULL ?>>4</option>
									<option value="8" <?php echo $work_schedule_info['sun_type_of_duty'] == 8 ? 'selected' : NULL ?>>8</option>
									<option value="10" <?php echo $work_schedule_info['sun_type_of_duty'] == 10 ? 'selected' : NULL ?>>10</option>
									<option value="12" <?php echo $work_schedule_info['sun_type_of_duty'] == 12 ? 'selected' : NULL ?>>12</option>
									<option value="16" <?php echo $work_schedule_info['sun_type_of_duty'] == 16 ? 'selected' : NULL ?>>16</option>
									<option value="24" <?php echo $work_schedule_info['sun_type_of_duty'] == 24 ? 'selected' : NULL ?>>24</option>
								</select>
							</div>
						</div>
					</div>
					<!-- MARVIN : INCLUDE 10 HRS DUTY : END -->
				</div>
			</div>
		</div>
		<div class='row switch p-md b-b-n'>
			<label>
				Inactive
				<input name='active_flag' type='checkbox' value='Y' <?php echo ($work_schedule_info['active_flag'] == "Y") ? "checked" : "" ?> <?php echo $action == ACTION_ADD ? 'checked' :'' ?> <?php echo $action == ACTION_VIEW ? 'disabled' :'' ?>> 
				<span class='lever'></span>Active
			</label>
		</div>
	</div>
	<div class="md-footer default">
	  	<?php if($action != ACTION_VIEW):?>
	  		<a class="waves-effect waves-teal btn-flat cancel_modal">Cancel</a>
		    <button class="btn btn-success " id="save_work_schedule" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  	<?php endif; ?>
	</div>
</form>

<script>
$(function (){
	$('#work_schedule_form').parsley();
	$('#work_schedule_form').submit(function(e) {
	    e.preventDefault();
	    
		if ( $(this).parsley().isValid() ) {
			var data = $(this).serialize();
		  	button_loader('save_work_schedule', 1);
		  	var option = {
					url  : $base_url + 'main/code_library_ta/work_schedule/process',
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.msg);
							modal_work_schedule.closeModal();
							load_datatable('work_schedule_table', '<?php echo PROJECT_MAIN ?>/code_library_ta/work_schedule/get_work_schedule_list',false,0,0,true);
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.msg);
						}	
						
					},
					
					complete : function(jqXHR){
						button_loader('save_work_schedule', 0);
					}
			};

			General.ajax(option);    
	    }
  	});

  	<?php if($action != ACTION_ADD){ ?>
		$('.input-field label').addClass('active');
  	<?php } ?>
})
</script>