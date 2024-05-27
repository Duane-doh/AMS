<!-- START CONTENT -->
<section id="content" class="p-t-n m-t-n ">
    
    <!--breadcrumbs start-->
    <div id="breadcrumbs-wrapper" class=" grey lighten-3">
      <div class="container">
        <div class="row">
          <div class="col s12 m12 l12">
            <h5 class="breadcrumbs-title"> Permissions </h5>
            <ol class="breadcrumb m-n p-b-sm">
                <?php get_breadcrumbs();?>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!--breadcrumbs end-->
    
    <!--start container-->
    <div class="container">
      <div class="section panel p-lg">
  <!--start section-->
  		<form id="permission_form">
		<div class="bg-white m-b-lg box-shadow m-b-lg">
		  <div class="table-display">
			<div class="table-cell p-md b-r valign-top" style="width:45%; border-style:dashed!important; border-right-color:#eee!important;">
			  <label class="active block m-b-sm">System</label>
			  <select name="system_filter" id="system_filter" class="selectize filter">
				<option value="all">All</option>
				<?php  foreach($systems as $key => $val) : ?>
				  <option value="<?php echo $val['system_code']; ?>"><?php echo $val['system_name']; ?></option>
				<?php  endforeach; ?>
			  </select>
		    </div>
			<div class="table-cell p-md b-r valign-top" style="width:45%; border-style:dashed!important; border-right-color:#eee!important;">
			  <label class="active block m-b-sm">Role</label>
			  <select name="role_filter" id="role_filter" class="selectize filter">
			  	<option value=""></option>
				<?php  foreach($roles as $key => $val) : ?>
				  <option value="<?php echo $val['role_code']; ?>"><?php echo $val['role_name']; ?></option>
				<?php  endforeach; ?>
			  </select>
		    </div>
		  </div>
		</div>


		<div class="m-t-lg">
		  <table cellpadding="0" cellspacing="0" class="table table-default" id="programs_table">
		  <thead>
			<tr>
			  <th width="5%">
			  	<input type="checkbox" name="check_all" id="check_all" class="filled-in"/>
			  	<label for="check_all"></label>
			  </th>
			  <th width="45%">Module</th>
			  <th width="40%">Action</th>
			  <th width="15%">Scope</th>
			</tr>
		  </thead>
		  <tfoot id="programs_tfoot" >
		  	<tr>
		  		<td  colspan="4" class="right-align">
				  <a href="<?php echo base_url() . PROJECT_CORE ?>/permissions" class="waves-effect waves-teal btn-flat">Cancel</a>
		  		  <button class="btn " id="save_permission" name="save_permission" type="submit" value="Save">Save</button>
		  		</td>
		  	</tr>
		  </tfoot>
		  <tbody id="programs_tbody">
		  	<tr>
		  		<td colspan='4' style='text-align:center;'>Please select system and role first.</td>
		  	</tr>
		  </tbody>
		  </table>
		</div>
	</form>

  <!--end section-->              
      </div>
    </div>
    <!--end container-->

</section>
<!-- END CONTENT -->
<script type="text/javascript">
$(function(){
	
	function load_permission()
	{
		$('#programs_tbody').isLoading();
		var form_data = { 'system' :  $('#system_filter').val(), 'role' : $('#role_filter').val() };
		$.ajax({
			url      : '<?php echo base_url().PROJECT_CORE; ?>/permissions/get_permission',
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
		load_permission();
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
		var action_sel = selects[0].selectize;
		var scope_sel  = (selects.hasOwnProperty(1)) ? selects[1].selectize : 0;
		
		if(checked)
		{
			action_sel.enable();
			
			if(scope_sel !== 0)
				scope_sel.enable();	
			
			check_selected();
		}
		else
		{
			action_sel.disable();
			
			if(scope_sel !== 0)
				scope_sel.disable();
			
			// UNCHECK CHECK ALL CHECKBOX
			$('#check_all').prop("checked", false);
		}
	});

	$('#permission_form').on('submit', function(ev){
		ev.preventDefault();

		var form_data = $(this).serialize();

		button_loader("save_permission",1);
		
		$.ajax({
			url      : '<?php echo base_url() . PROJECT_CORE ?>/permissions/save',
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