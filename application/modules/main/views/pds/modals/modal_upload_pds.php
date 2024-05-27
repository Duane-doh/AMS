<form id="pds_upload_form">
	<input type="hidden" id="last_count" name="last_count" value="0"/>
	<input type="hidden" id="commit_flag_cnt" name="commit_flag_cnt" value=""/>
	<input type="hidden" id="success_count" name="success_count" value="0"/>

	<div class="form-float-label">
	   <div class="row m-n field-multi-attachment">
			<div class="col s12">
				<div id="attachment_upload">Select File</div>
			</div>
		</div>
	</div>
	<div class="md-footer default">
	  <a class="btn btn-success-flat cancel_modal">Cancel</a>
	    <button type="button" class="btn btn-success " id="save_pds_upload" value="<?php echo BTN_SAVE ?>"><?php echo BTN_SAVE ?></button><!-- 
	    <button type="button" class="btn btn-success " id="save_pds_upload_ptis_format" value="<?php echo BTN_SAVE ?>">SAVE PTIS FORMAT</button> -->
	</div>
</form>

<script>
$(function(){
	jQuery(document).off('click', '#save_pds_upload');
	jQuery(document).on('click', '#save_pds_upload', function(e){
		var attachment = $("#attachment").val();
		if(attachment !== "")
		{
			$("#pds_upload_form").trigger('submit');
		}
		else
		{
			 notification_msg("<?php echo ERROR ?>", "Upload File is required.");
		}
	});
	
  $('#pds_upload_form').parsley();
   jQuery(document).off('submit', '#pds_upload_form');
	jQuery(document).on('submit', '#pds_upload_form', function(e){
    e.preventDefault();
    
	if ( $(this).parsley().isValid() ) {
	  var data = $(this).serialize();
	  
	  button_loader('save_pds_upload', 1);

	  $.post("<?php echo base_url() . PROJECT_MAIN ?>/Pds_upload/process_pds_upload/", data, function(result) {
		if(result.status){
			if(result.proceed_flag == 'Y')
			{
				notification_msg("<?php echo SUCCESS ?>", result.message);
				button_loader("save_pds_upload",0);
				modal_upload_pds.closeModal();
				var path = $base_url +  '<?php echo PATH_PDS_UPLOAD_ERROR_LOGS;?>' +  result.file_name;
				if(result.show_log)
				{
			  		setTimeout(function(){ 
				  		window.open(path, '_blank');
				  	}, 2000);
				}
			}
			else
			{
				$('#last_count').val(result.last_count);
				$('#success_count').val(result.success_count);
				confirm_duplicate();
				button_loader("save_pds_upload",0);
			}
		} else {
		  notification_msg("<?php echo ERROR ?>", result.message);
		  button_loader('save_pds_upload', 0);
		  modal_upload_pds.closeModal();
		  var path = $base_url +  '<?php echo PATH_PDS_UPLOAD_ERROR_LOGS;?>' +  result.file_name;
		  console.log(path);
		  
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
		allowedTypes:"xlsx",
		acceptFiles:"*",	
		dragDrop: true,
		multiple: true,
		allowDuplicates: true,
		duplicateStrict: false,
		maxFileSize: file_size,
		showDone: false,
		showProgress: true,
		showPreview: false,
		returnType:"json",	
		formData: {"dir":"<?php echo PATH_PDS_UPLOADS?>"},
		uploadFolder:$base_url + "<?php echo PATH_PDS_UPLOADS?>",
		onSuccess:function(files,data,xhr){
			
			$( ".field-multi-attachment .ajax-file-upload-filename" ).each(function() {
				var html = $(this).html();
				if(html == files){
					var id = data.toString().split('.');
					$('<input/>').attr({type:'hidden',name:'attachment[]', value:data, id: id[0]}).appendTo('#pds_upload_form');
				}
			});
			
		},
		showDelete:true,
		deleteCallback: function(data,pd)
		{
			for(var i=0;i<data.length;i++)
			{
				$.post($base_url + "upload/delete/",{op:"delete",name:data[i],dir:"<?php echo PATH_PDS_UPLOADS?>"},
				function(resp, textStatus, jqXHR)
				{ 
					var id = data.toString().split('.');
					$("#pds_upload_form input[id='"+ id[0] +"']").remove();
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
function confirm_duplicate(){
	
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {

			var last_count = $('#last_count').val();

			$('#commit_flag_cnt').val(last_count);
			$("#pds_upload_form").trigger('submit');
		},
		onCancelBut : function() {
			var last_count = parseInt($('#last_count').val());
			$('#last_count').val(last_count + 1);
			$("#pds_upload_form").trigger('submit');
		},
		onLoad : function() {
			$('.confirmModal_content h4').html('<span class="orange-text">Warning!</span>');	
			$('.confirmModal_content p').html('Employee information already exist. Continue anyway?');
		},
		onClose : function() {}
	});
}
</script>