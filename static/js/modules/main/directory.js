
var Directory   = function()
{

  var init      = function()
  {
    email();
    rateYo();
  }

  var email     = function()
  {
      $("#email_address").selectize({
        delimiter: ',',
        persist: false,
        create: true
      });
  };

  var rateYo    = function()
  {
      $(".rateYo").rateYo({
        fullStar: true,
         starWidth: "23px"
      });
  };

  var upload_individual_photo = function( upload_id )
  {

      var options             = {
          url               : $base_url + "upload/",
          fileName          : "file",
          allowedTypes      : "jpeg,jpg,png,gif",
          acceptFiles       : "*",
          dragDrop          : false,
          multiple          : false,
          maxFileCount      : 1,
          allowDuplicates   : true,
          duplicateStrict   : false,
          showDone          : false,
          showAbort         : false,
          showProgress      : false,
          showPreview       : false,
          returnType        : "json",
          formData          : { "dir" : PATH_USER_UPLOADS },
          uploadFolder      : $base_url + PATH_USER_UPLOADS,
          onSelect          : function(files) {
            $(".avatar-wrapper .ajax-file-upload").hide();
          },
          onSuccess         : function(files,data,xhr) {
            var avatar = $base_url + PATH_USER_UPLOADS + data;
            $("#profile_img").attr("src", avatar);

            $('#user_image').val(data);
            $('.avatar-wrapper .ajax-file-upload-progress').hide();
            $(".avatar-wrapper .ajax-file-upload-red").html("<i class='flaticon-recycle69'></i>");
          },
          showDelete        : true,
          deleteCallback    : function(data,pd)
          {
            for(var i=0;i<data.length;i++)
            {
              $.post($base_url + "upload/delete/",{ op : "delete", name : data[i], dir : PATH_USER_UPLOADS },
              function(resp, textStatus, jqXHR)
              {
                $(".avatar-wrapper .ajax-file-upload-error").fadeOut();
                $('#user_image').val('');

                var avatar = $base_url + PATH_IMAGES + "avatar.jpg";
                $("#profile_img").attr("src", avatar);
              });
             }
            pd.statusbar.hide();
            $(".avatar-wrapper .ajax-file-upload").css("display","");
          },
          onLoad            : function(obj)
          {
            $.ajax({
              cache: true,
              url: $base_url + "upload/existing_files/",
              dataType: "json",
              data: { dir: PATH_USER_UPLOADS, file: $('#user_image').val()} ,
              success: function(data)
              {
                for(var i=0;i<data.length;i++)
                {
                  obj.createProgress(data[i]);
                }

                if(data.length > 0){
                  $(".avatar-wrapper .ajax-file-upload").hide();
                  $('.avatar-wrapper .ajax-file-upload-progress').hide();
                  $(".avatar-wrapper .ajax-file-upload-red").html("<i class='flaticon-recycle69'></i>");
                }else{
                  var avatar = $base_url + PATH_IMAGES +"avatar.jpg";
                  $("#profile_img").attr("src", avatar);
                }
              }
            });
          }
      };

      var uploadObj = $("#"+upload_id).uploadFile( options );
  };

  var for_list      = function()
  {

    if($('.check_visible').is(":checked"))
        $("#visibility").show();
    else
        $("#visibility").hide();
  }

  var list_modal_init = function()
  {
    $("#visibility").hide();
  }

  return {

    email   : function()
    {
        email();
    },

    rateYo  : function()
    {
        rateYo();
    },
    init    : function()
    {
        init();
    },
    upload_individual_photo : function( upload_id )
    {
      upload_individual_photo( upload_id );
    },
    for_list : function()
    {
      for_list();
    },
    list_modal_init : function()
    {
      list_modal_init();
    }

  }

}();
