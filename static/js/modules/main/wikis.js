var Wikis = function()
{
	var editor = function()
	{
		if( CKEDITOR !== undefined ) 
		{
		    if($('.editor') != null)
		    {
				CKEDITOR.replace('body',{height: '300px'});
			}
		}
	};

	var createForm 	= function( obj, event, url, callback )
	{
		var form 		= '<form method="post" action="'+url+'">';

		$( obj ).on( event, function( e ) {

			callback( this, form, e  );

		} );
	}

	return {
		editor : function()
		{
			editor();
		},
		update_wiki : function()
		{
			createForm( '#update_wiki', 'click', $base_url+'wikis/kms_wikis/form', function( obj, form, e )
			{
				var jq 	= $( obj );

				form 	+= '<input type="hidden" name="action" value="edit">';
				form 	+= '</form>';

				jq.append(form);

				jq.find('form').submit();

				jq.find('form').remove();

			} );
		}
	}

}();