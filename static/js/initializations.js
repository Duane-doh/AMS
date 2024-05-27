/*
|--------------------------------------------------------------------------
| INITIALIZATION
|--------------------------------------------------------------------------
|
| These functions are used when initializing plugins in modals, tabs, etc.
| Instead of reloading the plugin scripts.
| 
| To be placed inside 
| 	$resources['loaded_init'] = array();
|	$this->load_resources->get_resource($resources);
*/

function selectize_init()
{
	if(  $( 'select.selectize' ).length !== 0 )
	{
		$( 'select.selectize' ).each( function() {
			
			if( !$(this).hasClass( 'selectized' ) )
			{
				$(this).selectize();
			}
		} )
	}

	if( $( 'select.tagging' ).length !== 0 )
	{
		$( 'select.tagging' ).each( function() {

			if( !$(this).hasClass( 'selectized' ) )
			{
				$(this).selectize({
					plugins: ['remove_button'],
					createOnBlur: true,
				    create: true,
				    delimiter: ',',
				    persist: false
				});
			}

		} );
	}

	if( $( 'select.tagging-max' ).length  !== 0 )
	{
		$(  'select.tagging-max' ).each( function() {
			if( !$(this).hasClass( 'selectized' ) )
			{
				$(this).selectize({ maxItems: 3 });
			}
		} )
	}
}

function datepicker_init()
{
	$('.datetimepicker').datetimepicker({
		timepicker:true,
		scrollInput: false,
		format:'Y/m/d h:i A',
		formatDate:'Y/m/d h:i A'
	  });
	$('.datepicker').datetimepicker({
		timepicker:false,
		scrollInput: false,
		format:'Y/m/d',
		formatDate:'Y/m/d'
	  });
		
	$('.timepicker').datetimepicker({
		datepicker:false,
		format:'h:i A'
	});
		
	$('.datepicker_start').datetimepicker({
		format:'Y/m/d',
		formatDate:'Y/m/d',
		scrollInput: false,
		onShow:function( ct ){
		  this.setOptions({
			maxDate:jQuery('.datepicker_end').val()?jQuery('.datepicker_end').val():false
		  })
		},
		timepicker:false
	});
		
	$('.datepicker_end').datetimepicker({
		format:'Y/m/d',
		formatDate:'Y/m/d',
		scrollInput: false,
		onShow:function( ct ){
			this.setOptions({
				minDate:jQuery('.datepicker_start').val()?jQuery('.datepicker_start').val():false
			})
		},
		timepicker:false
	});

	$('.datepicker_min_today').datetimepicker({
		format:'Y/m/d',
		formatDate:'Y/m/d',
		scrollInput: false,
		onShow:function( ct ){
			this.setOptions({
				minDate: '+1'
			})
		},
		timepicker:false
	});

	$('.datepicker_max_today').datetimepicker({
		format:'Y/m/d',
		formatDate:'Y/m/d',
		scrollInput: false,
		onShow:function( ct ){
			this.setOptions({
				maxDate: '+1'
			})
		},
		timepicker:false
	});

	if($('.datepicker,.datepicker_start,.datepicker_end,.timepicker,.datepicker_max_today,.datepicker_min_today').length === 0)
	$('.datepicker,.datepicker_start,.datepicker_end,.timepicker,.datepicker_max_today,.datepicker_min_today').datetimepicker('destroy');
}

function labelauty_init()
{
	if( $('.labelauty').length !== 0 )
	{
		
		$(".labelauty").not('.labelauty-initialized').labelauty({
			checked_label: "",
			unchecked_label: "",
			class: "labelauty-initialized"
		});
	}
}

function collapsible_init()
{
	$('.collapsible').collapsible({
		accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
	});
}

function scrollspy_init()
{
	$('.scrollspy').scrollSpy();
}

function dropdown_button_init(class_id)
{
	$('.' + class_id).dropdown();
}
