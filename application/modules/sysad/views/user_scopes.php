<section id="content" class="p-t-n m-t-n ">
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s7 m7 l7">
            <h5 class="breadcrumbs-title"> User Scopes </h5>
            <ol class="breadcrumb m-n p-b-sm">
                <?php get_breadcrumbs();?>
            </ol>
          </div>
        </div>
         
      </div>
    </div>
    <div class="container">
      <div class="section panel p-lg p-t-n">
  		<form id="user_scope_form">
  			<input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
			<input type="hidden" name="salt" id="salt" value="<?php echo $salt ?>"/>
			<input type="hidden" name="token" id="token" value="<?php echo $token ?>"/>
			<input type="hidden" name="action" id="action" value="<?php echo $action ?>"/>	
		<div class="bg-white m-b-xs box-shadow">
		  <div class="table-display">
			
			<div class="table-cell p-md b-r valign-top brown lighten-5" style="width:45%; border-style:dashed!important; border-right-color:#cec6c6!important;">
			   <label class="active block m-b-sm">System</label>
			  <select name="system_filter" id="system_filter" class="selectize filter">				
				<?php  foreach($systems as $key => $val) : ?>
				  <option value="<?php echo $val['system_code']; ?>"><?php echo $val['system_name']; ?></option>
				<?php  endforeach; ?>
				<option value="all">All</option>
			  </select>
		    </div>
		    <div class="table-cell p-md valign-top brown lighten-5" style="width:45%;">
				  <div class="row m-n">
	            	<div class="col s12 user-avatar">
			            <div class="row m-n">
			            	<?php 
			            		/* GET USER AVATAR */
								$user_image = base_url() . PATH_USER_UPLOADS . $user_info['photo'];
								$user_image = @getimagesize($user_image) ? $user_image : base_url() . PATH_IMAGES . "avatar.jpg";
			            	?>
			                <img class="circle" width="65" height="65" src="<?php echo $user_image;?>"/> 
			                <label class="dark font-xl"><?php echo isset($user_info['fullname']) ? $user_info['fullname']:''; ?></label><br>
			                <label class="font-lg"><?php echo isset($user_info['username']) ? $user_info['username']:'';  ?></label><br>
			                <label class="font-md"><?php echo isset($user_info['email']) ? $user_info['email']:'';  ?></label>
			            </div>
			        </div>
	            </div>
		    </div>
		  </div>
		</div>

		<div class="m-t-md">
		  <table cellpadding="0" cellspacing="0" class="table table-default" id="programs_table">
		  <thead>
			<tr>
			  <th width="5%">
			  	<input type="checkbox" name="check_all" id="check_all" class="filled-in"/>
			  	<label for="check_all"></label>
			  </th>
			  <th width="35%">Module</th>
			  <th width="60%">Office</th>
			</tr>
		  </thead>
		  <tfoot id="programs_tfoot" >
		  	<tr>
		  		<td  colspan="4" class="right-align">
				  <a href="<?php echo base_url() . PROJECT_CORE ?>/users" class="waves-effect waves-teal btn-flat">Cancel</a>
		  		  <button class="btn " id="save_permission" name="save_permission" type="submit" value="Save">Save</button>
		  		</td>
		  	</tr>
		  </tfoot>
		  <tbody id="programs_tbody">
		  	<tr>
		  		<td colspan='4' style='text-align:center;'>Please select system first.</td>
		  	</tr>
		  </tbody>
		  </table>
		</div>
	</form>   
      </div>
    </div>
</section>
<style>
	 .table-default tbody .selectize-input {
		height:auto !important;
		min-height: 100px !important;
	}

</style>
<script type="text/javascript">
$(function(){
	load_user_scopes();
	function load_user_scopes()
	{
		$('#programs_tbody').isLoading();
		var form_data = { 	
							'system' :  $('#system_filter').val(),
							'id' :  $('#id').val()
						};
		$.ajax({
			url      : '<?php echo base_url().PROJECT_CORE; ?>/user_scopes/get_user_scopes',
			method   : 'POST',
			dataType : 'html',
			data     : form_data,
			success  : function(response){				
				$('#programs_tbody').isLoading("hide").html(response);
				$('#programs_tfoot').show();
				$('.selectize').not('.filter').selectize();
				check_selected();
			},
			error    :  function(jqXHR, textStatus, errorThrown){
				console.log('Error : '+textStatus);
			}
		});	
	}

	$('.filter').on('change', function(){
		load_user_scopes();
	});
	
	
	$('#check_all').on('change', function(){
		var checked = $(this).prop("checked");
		
		$("input:checkbox").prop('checked', checked);

		var selects = $('select.selectize').not('.filter');
		
		selects.each(function( index ) {
			if(checked){
				$(this)[0].selectize.enable();
			}else{
				$(this)[0].selectize.disable();
			}
		}); 
		
	});

	$(document).on('change', '.ind_checkbox', function(){
		var checked    = $(this).prop('checked');
		var selects    = $(this).closest('tr').find('select');
		var office_sel = selects[0].selectize;
		
		if(checked)
		{
			office_sel.enable();
			
			check_selected();
		}
		else
		{
			office_sel.disable();
			
			// UNCHECK CHECK ALL CHECKBOX
			$('#check_all').prop("checked", false);
		}
	});

	$('#user_scope_form').on('submit', function(ev){
		ev.preventDefault();

		var form_data = $(this).serialize();

		button_loader("save_permission",1);
		
		$.ajax({
			url      : '<?php echo base_url() . PROJECT_CORE ?>/user_scopes/save',
			data     : form_data,		
			dataType : 'json',
			method   : 'POST',
			success  : function(response){
				var notif_type = (response.status) ?  "<?php echo SUCCESS ?>" : "<?php echo ERROR ?>";
				notification_msg(notif_type, response.msg);
				
				button_loader("save_permission",0);
			},
			error   : function(jqXHR, textStatus, errorThrown){
				console.log('Error : '+textStatus);
			}
		});
		
	});	
});

function check_selected(){
	// CHECK IF ALL CHECKBOXES ARE SELECTED
	if ($('.ind_checkbox:checked').length == $('.ind_checkbox').length) {
		$('#check_all').prop("checked", true); // select check_all checkbox
	}else{
		$('#check_all').prop("checked", false); // disselect check_all checkbox
	}
}
</script>