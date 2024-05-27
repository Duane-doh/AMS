var Workflow 		= function()
{
	var my_add_row  = function()
	{
		var options = {
			btn_id 		: 'add_workflow',
			tbl_id 		: 'workflow_wrapper',
			append_type : 'after',
			not_table 	: true,
			rem_rule 	: {
				rem_elem : 'a.delete_row',
				rem_find : 'div:last'
			},
			scroll 		 : function( tbl_id, row_index, apnd_type, btn )
			{
				 $( '.modal_scroll').find('.jspContainer').animate( { scrollTop : $( btn ).offset().top + row_index }, 'slow' );
			},
			before_copy_row : function(  row_index, self, tbl, tbl_copy )
			{

				var selectize = $( '.workflow_wrapper' ).find( 'select.selectize' );

				selectize.each( function() {
					if( this.selectize !== undefined )
					{
						this.selectize.destroy();
					}
				} )
				
			},
			after_copy_row  : function( row_index, par_btn, remove_func, tbl_id, args )
			{
				var table  		= $( '#'+tbl_id+'_row_'+row_index );
				 	last_div 	= table.find('div.row:last');

				 last_div.after(
				 	'<div class="row m-n">'+ 				
						'<div class="col s12">'+
							'<a class="delete_row">'+
								'Remove'+
							'</a>'+
						'</div>'+
					'</div>'
				 );

				 datepicker_init();

				 remove_func( tbl_id, row_index, args );

			 	 var rows 	= $( '.workflow_wrapper' );

				rows.each( function() {
					
					var selectize = rows.find( 'select.selectize' );

					if( selectize.length !== 0 && selectize.length === 1 )
					{
						if( selectize[0].selectize === undefined )
						{
							selectize.selectize();
							selectize[0].selectize.clear();
						}

					}
					else 
					{
						selectize.each( function() {

							if( this.selectize === undefined )
							{
								$(this).selectize();
								this.selectize.clear();
							}
						} )						
					}

				} );

			}
		};

		add_rows( options );
	}

	return {
		my_add_row : function()
		{
			my_add_row();
		}
	}

}();