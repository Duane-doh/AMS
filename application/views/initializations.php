<script type="text/javascript">

	<?php 
	if( ISSET( $resources['load_init'] ) AND !EMPTY( $resources['load_init'] ) ){
		foreach( $resources['load_init'] as $init ){
			echo $init;  
		}
	}
	?>

	$(function(){
		$('.tooltipped').tooltip('remove');
		$('.tooltipped').tooltip({delay: 50});

		// SIDEBAR NAVIGATION
		$(".button-collapse").sideNav();

		// SHORTEN TEXT
		$(".shorten-text").each(function() {
			$(this).shorten({
				showChars: $(this).data('char'),
				moreText: "Show More",
				lessText: "Show Less"
			});
		});

		// JSCROLLPANE
		$('.scroll-pane').jScrollPane(settings).bind('mousewheel',
			function(e)
			{
				e.preventDefault();
			}
		);

		// ACCORDION MENU
		$('.collapsible').collapsible({
			accordion : false 
			/* A setting that changes the collapsible behavior to expandable 
			instead of the default accordion style */
		});

		// SCROLLSPY
		$('.scrollspy').scrollSpy();

		// TABS
		$('ul.tabs').tabs();

		// FULLSCREEN MODAL
		$(".fullscreen-trigger").each(function() {
			var target = $(this).data("modal-target");
			$(this).animatedModal({
				color:'#fff',
				modalTarget: target,
				animatedIn:'bounceInUp',
				animatedOut:'bounceOutDown'
			});
		});

		// DROPDOWN
		$('.dropdown-button').dropdown({
			inDuration: 300,
			outDuration: 225,
			constrain_width: false, // Does not change width of dropdown to that of the activator
			hover: false, // Activate on hover
			gutter: 0, // Spacing from edge
			belowOrigin: true, // Displays dropdown below the button
			alignment: 'left' // Displays dropdown with edge aligned to the left of button
		});

		// FLOAT LABEL FORM ON FOCUS FUNCTION
		$(".form-float-label input[type='text'], .form-float-label input[type='password'], .form-float-label input[type='email'], .form-float-label input[type='url'], .form-float-label input[type='time'], .form-float-label textarea.materialize-textarea").focusin(function() {
			// $(this).closest(".col").css("background","#DCEAF1");
		}).focusout(function() {
			// $(this).closest(".col").css("background","none");
		});
		// END FLOAT LABEL FORM ON FOCUS FUNCTION

		$(".form-float-label .parsley-error").closest(".row").css("background", "#ffebee");

		// NOTIFICATION DROPDOWN FUNCTION
		$(".top-bar-notif a").blur(function() {
			$(this).parent().removeClass("active");
		});	 

		$(".top-bar-notif a").click(function() {
			$(this).parent().addClass("active");
		});

		<?php if( ISSET( $initial ) ) : ?>

			load_initial_tab();

		<?php endif; ?>

		// END NOTIFICATION DROPDOWN FUNCTION

		<?php if(!EMPTY($resources["load_js"])){ ?>

			<!-- DATEPICKER -->
			<?php if(in_array('jquery.datetimepicker', $resources["load_js"])): ?>
				datepicker_init();
			<?php else: ?>
				<!-- DESTROY DATEPICKER TO HIDE xdsoft_datetimepicker -->
				if($('.xdsoft_datetimepicker').length > 0)
				$('.xdsoft_datetimepicker').remove();
			<?php endif; ?>

			<!-- SELECTIZE -->
			<?php if(in_array('selectize', $resources["load_js"])): ?>

				selectize_init();

				<!-- ASSIGN VALUES TO SELECT (SINGLE) -->
				<?php
				if(ISSET($resources["single"]))
				{
					foreach($resources["single"] as $k => $v):
				?>
						$("#<?php echo $k ?>")[0].selectize.setValue('<?php echo $v ?>');
				<?php
					endforeach;
				}
				?>

				<!-- ASSIGN VALUES TO SELECT (MULTIPLE) -->
				<?php
				if(ISSET($resources["multiple"]))
				{
					foreach($resources["multiple"] as $a => $b):
						if(! EMPTY($b))
						{
							for($i = 0; $i < count($b); $i ++)
							{
				?>
								$("#<?php echo $a ?>")[0].selectize.addItem("<?php echo $b[$i] ?>");
				<?php
							}
						}
					endforeach;
				}
			endif;
			?>

			<!-- LABELAUTY RADIO BUTTON -->
			<?php if(in_array('jquery-labelauty', $resources["load_js"])): ?>
				labelauty_init();
			<?php endif; ?>

			<!-- NUMBER FORMAT -->
			<?php if(in_array('jquery.number.min', $resources["load_js"])): ?>
				$('input.number').number(true, 2);
			<?php endif; ?>

			<?php if(in_array('popModal.min',$resources["load_js"])){ ?>
				$('.popmodal-dropdown').click(function(){
					var data = $(this).data();
					
					$("#" + data.idSelector).val(data.id);
				
					if(data.idSelector === 'approve_id'){
						$("#approve_user_roles")[0].selectize.clear();
					}else{
						$("#reject_reason").val("");
					}
				});
			<?php } ?>

			<!-- UPLOAD FILE -->
			<?php if( ISSET( $resources['upload'] ) AND !EMPTY( $resources['upload'] ) ):
				foreach($resources["upload"] as $upload):
					if(ISSET($upload['multiple'])):
			?>
						var multiple = true;
					<?php endif; ?>
					<?php if(ISSET($upload['page'])){?>
							var page = "<?php echo $upload['page'] ?>";
						<?php } ?>
					<?php if(ISSET($upload['show_progress'])): ?>
						var show_progress = true;
					<?php endif; ?>

					<?php if(ISSET($upload['drag_drop'])): ?>
						var drag_drop = true;
					<?php endif; ?>

					<?php if(ISSET($upload['show_preview'])): ?>
						var show_preview = true;

						<?php if(ISSET($upload['preview_height'])){ ?>
							var preview_height = "<?php $upload['preview_height'] ?>";
						<?php } ?>

						<?php if(ISSET($upload['preview_width'])){ ?>
							var preview_width = "<?php $upload['preview_width'] ?>";
						<?php } ?>
					<?php endif; ?>

					var page = page || "",
					multiple = multiple || false,
					show_progress = show_progress || false,
					drag_drop = drag_drop || false,
					show_preview = show_preview || false,
					preview_height = preview_height || "auto",
					preview_width = preview_width || "80px";

					var uploadObj = $("#<?php echo $upload['id']?>_upload").uploadFile({
						url: $base_url + "upload/",
						fileName: "file",
						allowedTypes:"<?php echo $upload['allowed_types']?>",
						acceptFiles:"*",	
						dragDrop: drag_drop,
						multiple: multiple,
						maxFileCount: 1,
						allowDuplicates: true,
						duplicateStrict: false,
						showDone: false,
						showProgress: show_progress,
						showPreview: show_preview,

						<?php if(ISSET($upload['show_preview'])){ ?>
							previewHeight: preview_height,
							previewWidth: preview_width,
						<?php } ?>

						returnType:"json",	
						formData: {"dir":"<?php echo $upload['path']?>"},
						uploadFolder:$base_url + "<?php echo $upload['path']?>",
						onSuccess:function(files,data,xhr){ 

							if(page == 'files')
							{

								$( ".field-multi-attachment .ajax-file-upload-filename" ).each(function() {
									var html = $(this).html();
									
									if(html == files){
										// EXPLODE VALUE OF DATA
										var id = data.toString().split('.');
										$('<input/>').attr({type:'hidden',name:'multiple_file_name[]', value:data, id: id[0]}).appendTo('#upload_file_form');
										
										$("#save_upload_file").prop("disabled", false);
									}
								});
							}
							else
							{
								var avatar = $base_url + "<?php echo $upload['path']?>" + data;
								$("#<?php echo $upload['id']?>_src").attr("src", avatar);

								$("#<?php echo $upload['id']?>").val(data);
								$("#<?php echo $upload['id']?>_upload").prev(".ajax-file-upload").hide();
								$("#<?php echo $upload['id']?>_upload + div + div.ajax-file-upload-statusbar .ajax-file-upload-red").text("Delete");
						
							}
								
						
						},
						showDelete:true,
						deleteCallback: function(data,pd)
						{
							for(var i=0;i<data.length;i++)
							{
								$.post($base_url + "upload/delete/",{op:"delete",name:data[i],dir:"<?php echo $upload['path']?>"},
								function(resp, textStatus, jqXHR)
								{ 
									$("#<?php echo $upload['id']?>_upload + div + div.ajax-file-upload-statusbar .ajax-file-upload-error").fadeOut();	
									$("#<?php echo $upload['id']?>").val(''); 

									<?php if(ISSET($upload['default_img_preview'])){ ?>
										var avatar = $base_url + "<?php echo PATH_IMAGES . $upload['default_img_preview'] ?>";
										$("#<?php echo $upload['id']?>_src").attr("src", avatar);
									<?php } ?>
								});
							}
							pd.statusbar.hide();
							$("#<?php echo $upload['id']?>_upload").prev(".ajax-file-upload").show();
						},
						onLoad:function(obj)
						{
							$.ajax({
								cache: true,
								url: $base_url + "upload/existing_files/",
								dataType: "json",
								data: { dir: '<?php echo $upload['path']?>', file: $("#<?php echo $upload['id']?>").val()} ,
								success: function(data) 
								{
									for(var i=0;i<data.length;i++)
									{
										obj.createProgress(data[i]);
									}	

									if(data.length > 0){
										$("#<?php echo $upload['id']?>_upload").prev(".ajax-file-upload").hide();
										$("#<?php echo $upload['id']?>_upload + div + div.ajax-file-upload-statusbar .ajax-file-upload-red").text("Delete");
									}else{
										<?php if(ISSET($upload['default_img_preview'])){ ?>
											var avatar = $base_url + "<?php echo PATH_IMAGES . $upload['default_img_preview'] ?>";
											$("#<?php echo $upload['id']?>_src").attr("src", avatar);
										<?php } ?>
									}
								}
							});
						}
					});

			<?php
				endforeach;
			endif;
			?>

			<!-- DATATABLE -->
			<?php if(in_array('jquery.dataTables.min', $resources["load_js"]) and ISSET($resources["datatable"])){
				$datatable 		= $resources["datatable"];
			?>

			<?php 
				if( ISSET( $datatable[0] ) )
				{
					foreach( $datatable as $table ) 
					{
						$scroll 		= ISSET($table["scroll"]) ? "100%" : "";
						$advance_filter = ISSET( $table['advanced_filter'] ) ? true : false;
						$group_column 	= ISSET( $table['group_column'] ) ? $table['group_column'] : 0;
						$colspan 		= ISSET( $table['colspan'] ) ? $table['colspan'] : 0;
						$data_to_pass 	= ISSET( $table['post_data'] ) ? $table['post_data'] : '';
			?>	
					load_datatable('<?php echo $table["table_id"] ?>', '<?php echo $table["path"] ?>', '<?php echo $scroll ?>', <?php echo $group_column ?>, '<?php echo $colspan ?>','<?php echo $advance_filter ?>', '<?php echo $data_to_pass ?>');
			<?php
					}
				}
				else
				{
			?>
			<?php 
				$scroll 		= ISSET($datatable["scroll"]) ? "100%" : "";
				$advance_filter = ISSET( $datatable['advanced_filter'] ) ? true : false;
				$group_column 	= ISSET( $datatable['group_column'] ) ? $datatable['group_column'] : 0;
				$colspan 		= ISSET( $datatable['colspan'] ) ? $datatable['colspan'] : 0;
				$data_to_pass 	= ISSET( $datatable['post_data'] ) ? $datatable['post_data'] : '';

			?>

				load_datatable('<?php echo $datatable["table_id"] ?>', '<?php echo $datatable["path"] ?>', '<?php echo $scroll ?>', <?php echo $group_column ?>, '<?php echo $colspan ?>','<?php echo $advance_filter ?>', '<?php echo $data_to_pass ?>');
			<?php
				}
			?>
			
			<?php  } ?>
  		<?php } ?>
  		
  		avatar_fix();

  		 $("#alerts_div").load("<?php echo base_url() ?>nodejs/index.html");
	});

	<?php if( ISSET( $resources['loaded_init'] ) AND !EMPTY( $resources['loaded_init'] ) ){
		foreach( $resources['loaded_init'] as $init ){
			echo $init; 
		}
	} ?>
</script>