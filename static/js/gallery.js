$(function(){
    var base_url = $("#base_url").val();
    Galleria.loadTheme(base_url + 'static/js/galleria/themes/classic/galleria.classic.min.js');
    Galleria.run('.galleria');
});
