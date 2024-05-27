var $base_url 				= $("#base_url").val();
	$loader 				= '&nbsp;&nbsp;<img src="'+ $base_url +'static/images/ajax-loader-bar-black.gif"/>',
	PATH_USER_UPLOADS 		= $('#path_user_uploads').val(),
	PATH_IMAGES 	  		= $('#path_images').val(),
	PATH_SETTINGS_UPLOADS	= $('#path_settings_upload').val(),
	PATH_FILE_UPLOADS 		= $('#path_file_uploads').val();

function button_loader(id, active){
	var loading_bar = (active === 1) ? $loader : "",
		btn = $("#" + id),
		active_btn_label = btn.val(),
		default_btn_label = btn.html();
	
	if(active){
		btn.html(active_btn_label + loading_bar); //updating
		btn.attr('disabled','disabled');
		btn.val(default_btn_label); // update
	} else {
		btn.removeAttr('disabled');	
		btn.html(btn.val()); // update
		btn.val(default_btn_label.split("&nbsp;")[0]);
	}
}

function content_form(controller, module, id){
	var module = module || "";
		id = id || "";
		path = module + "/" + controller + "/" + id;
		
	window.location.href = $base_url + path;
}

function content_delete(alert_text, param_1, param_2){
	var param_2 = param_2 || "";
		
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			deleteObj.removeData({ param_1 : param_1, param_2 : param_2 });
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html('Are you sure you want to delete this ' + alert_text + '?');	
			$('.confirmModal_content p').html('This action will permanently delete this record from the database and cannot be undone.');
		},
		onClose : function() {}
	});
}

function alert_msg(type, msg){
	Materialize.toast(msg, 5000, type);
}

function notification_msg(type, msg){
	$(".notify." + type + " p").html(msg);
	$(".notify." + type).notifyModal({
		duration : -1
	});
}

function table_export(id, type, exclude, escape){
	var escape = escape || 'false';
		ignore_col = exclude || "";
		arr = "";
		
	if(ignore_col != ""){
		var ignore_col = ignore_col.split(",");
			arr = ignore_col.map(Number);
	}
		
		
	$('#' + id).tableExport({
		type:type,
		escape:escape,
		ignoreColumn: arr
	});
}

function modal_init(data_id){
	var data_id = data_id || '',
		jscroll;
	
	jscroll = modalObj.checkIfScroll();
	
	if(jscroll){
	  modalObj.loadViewJscroll({ id : data_id });
	}else{
	  modalObj.loadView({ id : data_id });
	}
	
	return false;
}

function set_active_tab(module){
	var active_tab = window.location.hash.replace('#',''),
		controller = active_tab.replace('tab_','');
	
	load_index(active_tab, controller, module);
}

function load_index(id, controller, module){
	var path = module + "/" + controller;
	
	$(".tab-content").not("#"+id).html("");
	$("#"+id).load($base_url + path).fadeIn("slow").show();
	window.location.hash = id;
}

function load_index_post( id, controller, module )
{
	var path 		= module + "/" + controller,
		ul 			= $('.tabs'),
		link 		= ul.find('li').find('a[href="#'+id+'"]'),
		post_data 	= link.attr('data-post'),
		data 		= {};

	if( post_data != '' )
	{
		data 		= JSON.parse( post_data );
	}

	data['tab_id'] 	= id;

	$(".tab-content").not("#"+id).html("");

	$.post( $base_url + path, data ).promise().done( function ( response ) {

		$("#"+id).html(response).fadeIn("slow").show();

	} );

	window.location.hash = id;
}

function search_wrapper(id, input, items, highlight){
	var highlight = highlight || "";
	var highlight = (highlight === "") ? true : false;
	$(id).lookingfor({
		input: $(input),
		items: items,
		highlight: highlight
	});
}

function sort(id, order_by, wrapper, action, input_id, item){
	var id = id.attr('id'),
		up_ico = 'flaticon-up151',
		down_ico = 'flaticon-down95';
	
	// RESET ACTIVE BUTTONS
	$('.sort-btn').not('#' + id).removeClass('active').find("i").switchClass(up_ico, down_ico, 1000, "easeInOutQuad" );
	
    $('#' + id).toggleClass('active');

    if(($('#' + id).hasClass('active'))){
		var order = 'ASC',
			class_from = down_ico,
			class_to = up_ico;
    } else {
		var order = 'DESC',
			class_from = up_ico;
			class_to = down_ico;
    }
    
	filter(order_by, order, wrapper, action, input_id, item);
	$('#' + id).find("i").switchClass(class_from, class_to, 1000, "easeInOutQuad" );
}

function filter(filter_1, filter_2, wrapper, action, input_id, item){
	
	var data = {filter_1: filter_1, filter_2: filter_2};

	$(wrapper).isLoading();
	
	$.post($base_url + action, data, function(result){
	  $(wrapper).isLoading("hide").html(result);
	  search_wrapper(wrapper, input_id, item, 1);
	},'json');
}

function load_datatable(table_id, path, scrollX, group_column, colspan, advanced_filter, data_to_pass, no_sort_cols = [-1]){
	
	var scrollX 			= scrollX || "",
		modal 				= modal || false,
		group_column 		= group_column || 0,
		colspan 			= colspan || 0,
		order 				= (table_id == 'audit_log_table')? 3 : 0,
		cols 				= [],
		options 		 	= {},
		table_obj 			= $("#"+table_id),
		search_params 		= {};

	options 				= {
		"bDestroy": true,
		"bProcessing": true,
		"bServerSide": true,
		"scrollX": scrollX,
		"sAjaxSource": $base_url + path, 
		"order": [[ order, "asc" ]],
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			if( Object.keys(search_params).length !== 0 )
			{
				for( var key in search_params )
				{  
					aoData.push( { 'name' : key, 'value' : search_params[ key ] } );
				}
				
			}

			if( data_to_pass !== undefined && data_to_pass != '' )
			{
				if( typeof( data_to_pass ) !== 'object' )
				{
					data_to_pass 	= JSON.parse( data_to_pass );
				}
				else if( data_to_pass instanceof Array )
				{
					$.merge( aoData, data_to_pass );
				}
				
				if( Object.keys(data_to_pass).length !== 0 )
				{
					for( var key in data_to_pass )
					{ 
						aoData.push( { 'name' : key, 'value' : data_to_pass[ key ] } )
					}
				}
			}

			$.ajax( {
				"dataType": 'json', 
				"type": "POST", 
				"url": sSource, 
				"data": aoData, 
				"success": fnCallback
			} );	
		}
	};
		
	if(group_column > 0)
	{
		for(cnt = 0; cnt < group_column; cnt++)
		{
			cols.push(cnt);
		}	

		options['drawCallback'] 	= function ( settings ) {
			var api = this.api();
			var rows = api.rows( {page:'current'} ).nodes();
			var last=null;
			var td_class = "";
			var td_colspan = "";
			
			$.each(cols, function( index, value ) {
				
				td_class = " class='yellow lighten-3 font-semibold'";
				if (group_column === value+1){
					if(colspan > 0)	
						td_colspan = "colspan='"+colspan+"'";
				}	
					
				api.column(value, {page:'current'} ).data().each( function ( group, i ) {
					if ( last !== group && group.length > 0) {
						$(rows).eq( i ).before(
							'<td '+td_colspan + td_class+'>' + group + '</td>'
						);
	 
						last = group;
					}
				} );
			});
			
			/* To automatically activate tooltips */
			if( $( '.tooltipped' ).length !== 0 )
			{
				$('.tooltipped').tooltip({delay: 50});
			}
			
			/* To automatically activate modal */
			if( ModalEffects !== undefined && $( '.md-trigger' ).length !== 0 )
			{
				ModalEffects.re_init();
			}

			if( $('input.number').length !== 0 && typeof( $.fn.number ) !== undefined )
			{
				$('input.number').number(true, 2);	
			}
		};

		options['columnDefs'] 	= [
			{ orderable: false, targets: -1 },
			{ visible: false, targets: cols }
		];
			
	} else {	

		options['drawCallback'] 	= function() {
			if( $( '.tooltipped' ).length !== 0 )
			{
				$('.tooltipped').tooltip({delay: 50});
			}
			
			/* To automatically activate modal */
			if( ModalEffects !== undefined && $( '.md-trigger' ).length !== 0 )
			{
				ModalEffects.re_init();
			}
			
			if( $('input.number').length !== 0 && typeof( $.fn.number ) !== undefined )
			{
				$('input.number').number(true, 2);	
			}
		}

		options['columnDefs'] 	=  [
			{ orderable: false, targets: -1 }
		];
	}	

	if( advanced_filter )
	{
		options["orderCellsTop"] = true;
		options['searching'] 	 = false;
	}
	
	if(no_sort_cols != 'undefined' && no_sort_cols != '')
	{
		options['columnDefs'] 	=  [
		    { orderable: false, targets: no_sort_cols }
		];
	}
	if($.inArray(0, no_sort_cols) > -1){
	    options["order"]= [[ 1, "asc" ]];
	}

	var table 	= {
		table_id : function()
		{
			table_obj.DataTable( options );
		}
	}

    search_params['action'] 	= 'filter';
    /* 
	 * FOR GLOBAL OFFICE FILTER - PARAMETER
	 * ADDED BY JAB
     */
    search_params[$('#office_filter').attr('name')] = $('#office_filter').val();

	table.table_id();
	if( advanced_filter )
	{
		/*
		 * FILTERING THRU PRESSING ENTER BUTTON 
		 */
		$('.form-filter').bind('keyup', function(e) {
			if(e.keyCode == 13) {

				$('.filter-submit').trigger('click');
			}
		}); 

	 	table_obj.on('click', '.filter-submit', function(e) {
	        e.stopImmediatePropagation();
	        search_params['action'] 	= 'filter';

        	$('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])', table_obj).each(function() {
	        	search_params[$(this).attr("name")] = $(this).val();
	        });

        	  // get all checkboxes
            $('input.form-filter[type="checkbox"]:checked', table_obj).each(function() {
                search_params[$(this).attr("name")] = $(this).val();
            });

            // get all radio buttons
            $('input.form-filter[type="radio"]:checked', table_obj).each(function() {
                search_params[$(this).attr("name")] = $(this).val();
            });


            /* 
			 * FOR GLOBAL OFFICE FILTER - PARAMETER
			 * ADDED BY JAB
		     */
            search_params[$('#office_filter').attr('name')] = $('#office_filter').val();

	        table.table_id();

	        $('.tooltipped').tooltip('remove');
            
            relocate_datatable_length(table_id);
	    });

     	table_obj.on('click', '.filter-cancel', function(e) {
            
            e.stopImmediatePropagation();

     	 	$('textarea.form-filter, select.form-filter, input.form-filter', table_obj).each(function() {
                $(this).val("");
            });

            $('input.form-filter[type="checkbox"]', table_obj).each(function() {
                $(this).attr("checked", false);
            });

            search_params 				= {};

         	search_params['action'] 	= 'filter_cancel';

         	/* 
			 * FOR GLOBAL OFFICE FILTER - PARAMETER
			 * ADDED BY JAB
		     */
            search_params[$('#office_filter').attr('name')] = $('#office_filter').val();
            
            table.table_id();

            $('.tooltipped').tooltip('remove');

            relocate_datatable_length(table_id);
        });


 	}

    relocate_datatable_length(table_id);
}
/* 
 * FOR GLOBAL OFFICE FILTER - ONCLIK FUNCTION
 * ADDED BY JAB
 */
function office_filtering() {
	$('.filter-submit').trigger('click');
}

function relocate_datatable_length(table_id) {

	var length_wrapper = $('#'+table_id+'_length');

	$('#'+table_id+'_info').attr('style','position:absolute');
	$('#'+table_id+'_wrapper').append(length_wrapper);
}

function generate_report(controller, extension, filter){
	var filter = filter || "",
		url = $base_url + controller + "/" + extension + "/" + filter;
	
	window.open(url, '_blank');
}

/* Replaces all broken images with "avatar" class with the default no avatar image */
function avatar_fix(){
	$('img.avatar').error(function(){
		$(this).attr('src', $base_url + 'static/images/avatar/no_avatar.jpg');
	});
}

function help_text(form_id){
	$.each($('.help', '#' + form_id),function(){
		var data = $(this).data(),
			text = data.helpText;
		
		$(this).append('<i class="help-tooltip material-icons titleModal" data-placement="right" title="'+text+'">help</i>');
	});
}

function do_process(role_code, proceeding_step, action, is_return, org_code){
	var org_code = org_code || "";
	$('#confirm_modal').confirmModal({
		topOffset : 0,
		onOkBut : function() {
			workflowObj.workflowData({ role_code : role_code, proceeding_step : proceeding_step, is_return : is_return, org_code : org_code });
		},
		onCancelBut : function() {},
		onLoad : function() {
			$('.confirmModal_content h4').html(action);	
			$('.confirmModal_content p').html('This action will submit the form and proceed to the specified stage of process. Are you sure?');
		},
		onClose : function() {}
	});
}

/*--------------------------------------------------------------
  TABS by Doc
  - Loads initial tab: <li class="tab"><a class="active"></li>
  	  If there's no "active" class, the default is the first tab. 
  - Loads the same active tab when refreshed.
--------------------------------------------------------------*/

function load_initial_tab()
{
	var tabs = $(".tabs-wrapper"),
	cnt  = 0,
	len;

	if( tabs.length !== 0 )
	{
		len = tabs.length;

		for( ; cnt < len; cnt++ )
		{
			var initial = $( tabs[cnt] ).find( 'li a.active' ),
			id;

			if( initial.length !== 0 && initial.length === 1 )
			{
				initial.click();
			}
			else 
			{
				initial.each( function() {
					id  		= $( this ).attr('href');

					var div 	= $( this ).parents('div.tabs-wrapper').parent();

					if( div.find('div'+id).not(':hidden').length !== 0 )
					{
						$(this).click();
					}

				} )
			}
		}
	}
}

String.prototype.replaceAll = function(search, replacement) {

    var target = this;

    return target.split(search).join(replacement);
};

function format_identifications(format,data_string,event,id) {
	if(event.type=="keypress" && event.keyCode != 8) {

		if(format != '')
		{
			// SPLIT THE FORMAT BY ITS DELIMETER
			format = format.split('x');
			var format_separator = format[format.length-1];

			// REMOVE SEPARATORS
			var data_string   = data_string.replaceAll(format_separator,'');
			
			var value         = '';
			var temp_size     = 0;
			var size          = 0;
			var format_length = format.length-1;

			for(var i=0; i<format_length; i++)
			{
				size += Number(format[i]);
				for(var j=temp_size;j<size; j++)
				{
					if(data_string.charAt(temp_size) != '')
					{
						value += data_string.charAt(temp_size);
						++temp_size;
					}
				}
				if(temp_size == size && i < format_length-1)
				value += format_separator;
			}

			$('#'+id).prop('maxlength',size + (format_length-1));
			$('#'+id).val(value);
		} else {
			$('#'+id).prop('maxlength','30');
		}
	}
}

/* load_selectize
*
* @param url: selectize option resource. returned data format [{id: id, name: name},..]
* @param data: request data ojbject
* @param target: select id to be reloaded
* @param selected_val: selected option value
*/

function load_selectize(param,callBack){
	/* Sets of predefined config for ajax request */
	var predefined = {
			async: true
	}
	/* Merge the two configs */
   var param = $.extend(predefined, param);

	
	$.ajax({
	    type: "POST",
	    url: param.url,
	    data: param.data,
	    dataType: 'json',
	    async: param.async,
	    success: function(results){
	        selectize_reload(param.target, results.options, param.selected_val,function() {callBack()});
	    },
	});
}

function selectize_reload(selectize_id, options, selected_val,callBack){
   var htmldata = '';
   var new_value_options   = '[';
       for (var key in options) {
           htmldata += '<option value="'+options[key].id+'">'+options[key].name+'</option>';

           var keyPlus = parseInt(key) + 1;
           if (keyPlus == options.length) {
               new_value_options += '{text: String("'+options[key].name+'"), value: String("'+options[key].id+'")}';
           } else {
               new_value_options += '{text: String("'+options[key].name+'"), value: String("'+options[key].id+'")},';
           }
       }
       new_value_options   += ']';

   //convert to json object
   new_value_options = eval('(' + new_value_options + ')');
   
   //unset options value 0 to null
  $.each(new_value_options, function(key, obj){
   	var option_value = new_value_options[key].value;
  		if(option_value == 0)
  			new_value_options[key].value = '';
   });

   if (new_value_options[0] != undefined) {
       // re-fill html select option field 
       $("#"+selectize_id).html(htmldata);
       // re-fill/set the selectize values
       var selectize = $("#"+selectize_id)[0].selectize;

       selectize.clear();
       selectize.clearOptions();
       selectize.renderCache['option'] = {};
       selectize.renderCache['item'] = {};
       selectize.addOption(new_value_options);
       
       var selected_val = selected_val || new_value_options[0].value;
      	selectize.setValue(selected_val);
   }
   if (callBack != undefined) {
	   callBack();
	}
}
function number_format(number,decimals,dec_point,thousands_sep) {
    number  = number*1;//makes sure `number` is numeric value
    var str = number.toFixed(decimals?decimals:0).toString().split('.');
    var parts = [];
    for ( var i=str[0].length; i>0; i-=3 ) {
        parts.unshift(str[0].substring(Math.max(0,i-3),i));
    }
    str[0] = parts.join(thousands_sep?thousands_sep:',');
    return str.join(dec_point?dec_point:'.');
}
function update_read_date($notification_id,$record_link) {
  	$.ajax({
	    type: "POST",
	    url: $base_url + 'main/request_notifications/update_notification',
	    data: {notification_id: $notification_id},
	    dataType: 'json',
	    success: function(results){
	        window.location = $base_url + $record_link;
	    },
	})
}