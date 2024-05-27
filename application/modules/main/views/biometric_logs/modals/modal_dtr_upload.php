<form id="dtr_upload_form">
	<input type="hidden" name="id" value="<?php echo $id?>">
	<input type="hidden" name="file_type" id="file_type" value="<?php echo $file_type?>">
	<div class="form-float-label">
		
	   <div class="row m-n field-multi-attachment">
			<div class="col s12">
			 
				<div id="attachment_upload">Select File</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
	  <a class="btn-flat cancel_modal">Cancel</a>
		<?php //if($this->permission->check_permission(MODULE_ROLE, ACTION_SAVE)):?>
	    <button class="btn btn-success " type="button" id="save_dtr_upload" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button>
	  <?php //endif; ?>	 
	</div>

</form>

<script>
$(function(){
	jQuery(document).off('click', '#save_dtr_upload');
	jQuery(document).on('click', '#save_dtr_upload', function(e){
		var attachment = $("#attachment").val();
		if(attachment !== "")
		{
			$("#dtr_upload_form").trigger('submit');
		}
		else
		{
			 notification_msg("<?php echo ERROR ?>", "Upload File is required.");
		}
	});
	
  $('#dtr_upload_form').parsley();
   jQuery(document).off('submit', '#dtr_upload_form');
	jQuery(document).on('submit', '#dtr_upload_form', function(e){
    e.preventDefault();
    
	if ( $(this).parsley().isValid() ) {
	  var data = $(this).serialize();

	  var file_type = $("#file_type").val();

	  if ( file_type == 'csv' )
	  	var file_path = 'process_csv_upload/';
	  if ( file_type == 'otm' )
	  	var file_path = 'process_upload/';
	  
	  button_loader('save_dtr_upload', 1);

	  $.post("<?php echo base_url() . PROJECT_MAIN ?>/biometric_logs/" + file_path, data, function(result) {
		if(result.status){

			$(".cancel_modal").trigger('click');
			load_datatable('dtr_file_list', '<?php echo PROJECT_MAIN ?>/biometric_logs/get_dtr_file_list',false,0,0,true);
			notification_msg("<?php echo SUCCESS ?>", result.message);
			var path = $base_url +  '<?php echo PATH_BIOMETRIC_UPLOAD_ERROR_LOGS;?>' +  result.file_name;
			if(result.show_log){
		  	
			  	setTimeout(function(){ 
			  		window.open(path, '_blank');
			  	}, 2000);
			}
		} 
		else 
		{
		  	notification_msg("<?php echo ERROR ?>", result.message);
		  	button_loader('save_dtr_upload', 0);
		  	var path = $base_url +  '<?php echo PATH_BIOMETRIC_UPLOAD_ERROR_LOGS;?>' +  result.file_name;
			if(result.show_log){
			  	setTimeout(function(){ 
			  		window.open(path, '_blank');
			  	}, 2000);
			}
		}

	  }, 'json');       
	  
    }
  });
	var file_size = 50;
	 file_size = file_size*1024*1024;
	var uploadObj = $("#attachment_upload").uploadFile({
		url: $base_url + "upload/",
		fileName: "file",
		allowedTypes:"<?php echo $file_type; ?>",
		acceptFiles:"*",	
		dragDrop: true,
		multiple: true,
		allowDuplicates: false,
		duplicateStrict: true,
		maxFileSize: file_size,
		showDone: false,
		showProgress: true,
		showPreview: false,
		returnType:"json",	
		formData: {"dir":"<?php echo PATH_BIOMETRIC_UPLOADS?>"},
		uploadFolder:$base_url + "<?php echo PATH_BIOMETRIC_UPLOADS?>",
		onSuccess:function(files,data,xhr){
			
			$( ".field-multi-attachment .ajax-file-upload-filename" ).each(function() {
				var html = $(this).html();
				if(html == files){
					var id = data.toString().split('.');
					$('<input/>').attr({type:'hidden',name:'attachment[]', value:data, id: id[0]}).appendTo('#dtr_upload_form');
				}
			});
			
		},
		showDelete:true,
		deleteCallback: function(data,pd)
		{
			for(var i=0;i<data.length;i++)
			{
				$.post($base_url + "upload/delete/",{op:"delete",name:data[i],dir:"<?php echo PATH_BIOMETRIC_UPLOADS?>"},
				function(resp, textStatus, jqXHR)
				{ 
					var id = data.toString().split('.');
					$("#dtr_upload_form input[id='"+ id[0] +"']").remove();
					$(".ajax-file-upload-error").fadeOut();
				});
			}
			pd.statusbar.hide();
				
		},
		onLoad:function(obj)
		{
			
		}
	});
  
}); 
</script>