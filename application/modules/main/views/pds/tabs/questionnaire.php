<form class="m-b-md" id="form_question">
	<input type="hidden" name="id" value="<?php echo $id ?>"/>
	<input type="hidden" name="salt" value="<?php echo $salt ?>"/>
	<input type="hidden" name="token" value="<?php echo $token ?>"/>
	<input type="hidden" name="action" value="<?php echo $action ?>"/>
	<input type="hidden" name="module" value="<?php echo $module ?>"/>
	<?php $i = 34; 
		if($parent_questions):?>
		<?php foreach($parent_questions as $p_question):?>
			<?php if($p_question['parent_question_flag'] == "Y"):?>
				<div class="row">
					<div class="col s6 p-md p-r-lg">
						<label class="font-md dark font-semibold" style="text-align:justify !important"><?php echo isset($p_question['question_txt']) ? $i.'.&nbsp;'.$p_question['question_txt'] : $parent_question_num = $i; $i++?></label>
					</div>
				</div>
				<?php $letter = 'a';foreach($child_questions as $c_question):?>
					<?php if($c_question['parent_question_id'] == $p_question['question_id']):?>
							<?php 
								$answer_yes = "";
								$answer_no 	= "";
								$answer_detail = "";
								if($answers):
									foreach($answers as $answer):
									 	if($c_question['question_id'] == $answer['question_id']):
									 		
											
											if($answer['question_answer_flag'])
											{
												if($answer['question_answer_flag'] == "Y")
												{
													$answer_yes 	= "checked";
													$answer_detail 	= $answer['question_answer_txt'];
												}
												else
												{
													$answer_no 		= "checked";
												}

											}
										endif;
									endforeach;
								endif;

							?>
						<div class="form-basic">
							<div class="row p-t-md nearest_div">
								<div class="col s6 p-md p-r-lg">
									  <label class="font-md p-l-md"><?php echo isset($c_question['question_txt']) ? $letter.'.&nbsp;'.$c_question['question_txt'] : "" ;$letter++;?></label>
								 </div>
								  <div class="col s2 p-n">
									 <div class="row">
										<div class="col s6 p-n">
											<input type="radio" class="labelauty answer" name="request_type_<?php echo $c_question['question_id']?>"  value="Y" data-labelauty="Yes" <?php echo $answer_yes?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
										</div>	
										 <div class="col s6 p-n m-l-n-md">
											<input type="radio" class="labelauty answer" name="request_type_<?php echo $c_question['question_id']?>"  value="N" data-labelauty="No" <?php echo $answer_no?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
										 </div>	
									</div>
								</div>
								<div class="col s4">
									<div class="input-field m-n">
									  <input id="detail_<?php echo $c_question['question_id']?>" name="detail_<?php echo $c_question['question_id']?>" type="text" class="validate result_detail"  value="<?php echo $answer_detail;?>" <?php echo !EMPTY($answer_yes) ? ' required ':'';?> <?php echo ($action==ACTION_VIEW OR (isset($answer['question_answer_flag']) && !EMPTY($answer_no))) ? 'disabled' : '' ?>>
									  <label for="detail_<?php echo $c_question['question_id']?>" class="<?php echo EMPTY($answer_detail) ? "":"active"?>">Details</label>
									</div>
								 </div>
							</div>
						</div>
					<?php endif;?>
				<?php endforeach;?>
			<?php else:?>
					<?php 
						$answer_yes = "";
						$answer_no 	= "";
						$answer_detail = "";
						if($answers):
							foreach($answers as $answer):
							 	if($p_question['question_id'] == $answer['question_id']):
							 		
									
									if($answer['question_answer_flag'])
									{
										if($answer['question_answer_flag'] == "Y")
										{
											$answer_yes 	= "checked";
											$answer_detail 	= $answer['question_answer_txt'];
										}
										else
										{
											$answer_no 		= "checked";
										}

									}
								endif;
							endforeach;
						endif;

					?>
					<div class="row p-t-md nearest_div">
						<div class="col s6 p-md p-r-lg">
							  <label class="font-md dark font-semibold"><?php echo isset($p_question['question_txt']) ? $i.'.&nbsp;'.$p_question['question_txt'] : "" ; $i++;?></label>
						 </div>
						  <div class="col s2 p-n">
						  	<div class="form-basic">
								 <div class="row">
									<div class="col s6 p-n">
										<input type="radio" class="labelauty answer" name="request_type_<?php echo $p_question['question_id']?>"  value="Y" data-labelauty="Yes" <?php echo $answer_yes;?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
									</div>	
									 <div class="col s6 p-n m-l-n-md">
										<input type="radio" class="labelauty answer" name="request_type_<?php echo $p_question['question_id']?>"  value="N" data-labelauty="No" <?php echo $answer_no;?> <?php echo $action==ACTION_VIEW ? 'disabled' : '' ?>/>
									 </div>	
								</div>
							</div>
						</div>
						<div class="col s4">
							<div class="form-basic">
								<div class="row">
								 	<div class="col s12">
										<div class="input-field m-n">
								  			<input id="detail_<?php echo $p_question['question_id']?>" name="detail_<?php echo $p_question['question_id']?>" type="text" class="validate result_detail"  value="<?php echo $answer_detail;?>" <?php echo !EMPTY($answer_yes) ? ' required ':'';?> <?php echo ($action==ACTION_VIEW OR (isset($answer['question_answer_flag']) && !EMPTY($answer_no))) ? 'disabled' : '' ?>>
								  			<label for="detail_<?php echo $p_question['question_id']?>" class="<?php echo EMPTY($answer_detail) ? "":"active"?>">Details</label>
										</div>
									</div>
							 	</div>
							</div>
						 </div>
					</div>
			<?php endif;?>
		<?php endforeach;?>
	<?php endif;?>
</form>
<div class="right-align m-t-lg">
	<?php IF ($action != ACTION_VIEW): ?>
		<button class="btn btn-success " type="submit" name="action" id="save_question">Save</button>
	<?php ENDIF; ?>
	</div>
<script type="text/javascript">
$(function(){
	jQuery(document).off('change', '.answer');
	jQuery(document).on('change', '.answer', function(e){	
		
		var value = $(this).val();
		if(value == "N")
		{
			$(this).closest("div.nearest_div").find("input[type=text]").prop('disabled', true);
			$(this).closest("div.nearest_div").find("input[type=text]").val('');
			$(this).closest("div.nearest_div").find("input[type=text]").prop('required', false);
		}
		else
		{
			$(this).closest("div.nearest_div").find("input[type=text]").prop('disabled', false);
			$(this).closest("div.nearest_div").find("input[type=text]").prop('required', true);
		}
	});
	jQuery(document).off('click', '#save_question');
	jQuery(document).on('click', '#save_question', function(e){	
	
		$("#form_question").trigger('submit');
	});
 	$('#form_question').parsley();

 	jQuery(document).off('submit', '#form_question');
	jQuery(document).on('submit', '#form_question', function(e){
	    e.preventDefault();
	    if ( $(this).parsley().isValid() ) {

	  		var data = $('#form_question').serialize();
	  		var process_url = "";
	  		<?php if($module == MODULE_PERSONNEL_PORTAL):?>
				process_url = $base_url + 'main/pds_record_changes_requests/process_question';
			<?php else: ?>
				process_url = $base_url + 'main/pds_questionnaire_info/process';
			<?php endif; ?>
		   $('#tab_content').isLoading();
		    var option = {
					url  : process_url,
					data : data,
					success : function(result){
						if(result.status)
						{
							notification_msg("<?php echo SUCCESS ?>", result.message);								
						}
						else
						{
							notification_msg("<?php echo ERROR ?>", result.message);
						}	
						
					},
					
					complete : function(jqXHR){
						 $('#tab_content').isLoading( "hide" );
					}
			};
			General.ajax(option);  		    
	      }

	});
});
</script>