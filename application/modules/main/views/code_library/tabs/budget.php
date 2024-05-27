 <form id="attendance_form" name="user_form" class="form-vertical form-styled  m-t-lg p-lg" autocomplete="off">
	    <div class="form-basic">		 
			<div class="row m-b-lg">
		   	 	<div class="col s12">
					  <div class="input-field">
				    <input type="text" name="department" id="department" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="" class="validate" />
				    <label for="department">Department</label>
				  </div>
			    </div>
			</div>
			<div class="row m-b-lg">
		   	 	<div class="col s12">
					  <div class="input-field">
				    <input type="text" name="corporation" id="corporation" data-parsley-required="true" data-parsley-validation-threshold="0" data-parsley-trigger="keyup" value="" class="validate" />
				    <label for="corporation">Corporation</label>
				  </div>
			    </div>
			</div>
			<div class="right-align m-t-lg">
			<?php IF ($action_id != ACTION_VIEW): ?>
				<button class="btn btn-success " type="submit" name="action">Save</button>
			<?php ENDIF; ?>
			<a class="waves-effect waves-teal btn-flat">Cancel</a>
			</div>
		</div>
</form>
	


<script type="text/javascript">
	
$(function(){
	<?php if($action_id != ACTION_ADD): ?>
		$(".input-field label").addClass("active");
	<?php endif; ?>	
});

</script>