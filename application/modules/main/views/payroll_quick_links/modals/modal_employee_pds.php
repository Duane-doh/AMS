<form id="performance_evaluation_form">
		<div class="row">
			<div class="col s12">
				<div class="col s12 p-t-lg user-avatar">
					<div class="row m-n ">
						<?php 
							$avatar_src = base_url() . PATH_USER_UPLOADS . $avatar['photo'];
							$avatar_src = @getimagesize($avatar_src) ? $avatar_src : base_url() . PATH_IMAGES . "avatar.jpg";
						?>
						<img class="circle" width="65" height="65" src="<?php echo $avatar_src?>"/> 
                        <label class="dark font-xl"><?php echo ucwords($personal_info['fullname']); ?></label><br>
                        <label class="font-lg"><?php echo $personal_info['position_name']; ?></label><br>
                        <label class="font-md"><?php echo $personal_info['name']; ?></label><br>
                        <label class="font-md" style="padding-left: 75px;"><?php echo $personal_info['agency_employee_id']; ?></label>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s12">
				<table class="table-default table-layout-auto">
					<thead>
						<th>Identification Type</th>
						<th>Identification Value</th>
					</thead>
					<tbody>
						<?php if($identifications):?>
							<?php foreach ($identifications as $id): ?>
							<tr>
								<td><?php echo isset($id['identification_type_name']) ? strtoupper($id['identification_type_name']):''?></td>
								<td><?php echo isset($id['identification_value']) ? format_identifications($id['identification_value'], $id['format']):''?></td>
							</tr>
							<?php endforeach; ?>
						<?php else:?>
							<tr>
								<td colspan='2'><center>No records found.</center></td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		<?php if($professional_info) : ?>
		<div class="row">
			<div class="col s12">
				<table class="table-default table-layout-auto">
					<thead>
						<th>Eligibility</th>
					</thead>
					<tbody>
						<tr>
							<td><?php echo strtoupper($professional_info['others_value']) ?></td>
						</tr>
					</tbody>
				</table>
			</div>	
		</div>
		<?php endif; ?>
	<div class="md-footer default">
	</div>
</form>
