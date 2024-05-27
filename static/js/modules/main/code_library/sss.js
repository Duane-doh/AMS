var sss = function(){

	var FormInit = function(){
		$('.totalNumber').attr('disabled','');
		$('#sss_form').parsley();
		
	}

	var InitAjax = function(){
		$('#sss_form').on('submit', function(){
			var that = $(this);
			var data = {};

			that.find('[name]').each(function(index, value){
				var that = $(this),
					name  = that.attr('name'),
					value = that.val();
					data[name] = value;
			});
			
			$.ajax({
				url:$base_url + 'main/code_library_payroll/sss/process',
				type:'POST',
				data:data,
				dataType:'json',
				success:function(r){
					if(r.status == 'success')
					{
						notification_msg("success",r.msg);
						modal_sss.closeModal();
						load_datatable(r.table_id,r.path,false,0,0,true);
					}
					else
					{
						notification_msg("error",r.msg);
					}
				}

			});
			return false;
		});
	}
	
	return{
		initialize_form:function(){
			FormInit();
		},
		initialize_ajax:function(){
			InitAjax();
		}
	}

}();
