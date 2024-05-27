var $base_url   = $("#base_url").val();
var cloned      = {};

// JSCROLLPANE
var settings    = {autoReinitialise: true};
var $body       = $('body');

var handleModal = function(options){

    var vars = {
        controller: '',
        method: '',
        modal_id: '',
        module: '',
        height : '',
        ajax    : false
    };

    this.construct = function(options){
        $.extend(vars , options);
    }

    this.loadView = function(data){
        method = (vars.method === '')? "modal" : vars.method;
        path = vars.controller + "/" + method;
        path = (data.id === '')? path : path + "/" + data.id;
        mod = (vars.module === '')? '' : vars.module + "/";
        ajax_modal  = ( data.ajax_modal !== undefined ) ? data.ajax_modal : '';

        if( $('.md-modal.md-show').length > 0 )
        {
             var clone          = $("#" + vars.modal_id ).clone().addClass($('#'+vars.modal_id).attr('id')),
                 next_script    = $('#'+vars.modal_id).next('script'),
                 prev_script    = $('#'+vars.modal_id).prev('script');
            
            if( !cloned[ vars.modal_id ] )
            {
                $("#" + vars.modal_id ).remove();
                next_script.remove();
                prev_script.remove(); 

                $('body').find('div.md-overlay:not([style])').last().before(prev_script.clone());
                $('body').find('div.md-overlay:not([style])').last().before(clone);
                cloned[vars.modal_id] = true;
                $('body').find('div.md-overlay:not([style])').last().before(next_script.clone());
            }
            else
            {
                var modal_inside = $('#'+vars.modal_id).parents('.md-modal').find('#'+vars.modal_id);

                modal_inside.remove();
                modal_inside.prev('script').remove();
                modal_inside.next('script').remove();
            }
        }

        $("#" + vars.modal_id + "_content").html($("#loading").html());

        if( vars.ajax )
        {
            if( ajax_modal != '' )
            {
                $("#" + vars.modal_id + "_content").html(ajax_modal).promise().done( function() {
                    $("#" + vars.modal_id + "_content").find('a.cancel_modal').on( 'click', function() {
                        $("#" + vars.modal_id).removeClass("md-show");
                        if( $('.md-modal.md-show').length == 0 )
                        {
                            $body.removeClass('md-open');
                            $body.removeAttr('style');
                        }
                        if($('.md-modal.md-show').length > 0)
                        {
                            ModalEffects.reduceZindex();
                            $('.md-overlay').last().remove();
                        }
                    });
                });
            }
        }
        else
        {
            $("#" + vars.modal_id + "_content").load($base_url + mod + path, function() {
                $("#" + vars.modal_id + "_content").find('a.cancel_modal').on( 'click', function() {
                   $("#" + vars.modal_id).removeClass("md-show");
                   if( $('.md-modal.md-show').length == 0 )
                   {
                        $body.removeClass('md-open');
                        $body.removeAttr('style');
                   }
                   if($('.md-modal.md-show').length > 0)
                   {
                        ModalEffects.reduceZindex();
                        $('.md-overlay').last().remove();
                   }
                });
            });
        }
        
        $(".md-content").draggable({
            handle: "h3.md-header",
            containment: "#content-wrapper", 
            scroll: false
        });
        
        $('h3.md-header').css('cursor', 'move');
        $(".md-modal").center();
        $(".md-modal").css({
           'padding-top' : '2%',
            'top' : '50%',
            'bottom' : '0'
        });
    }
    
    this.loadViewJscroll = function(data){
        method = (vars.method === '')? "modal" : vars.method;
        path = vars.controller + "/" + method;
        path = (data.id === '')? path : path + "/" + data.id;
        mod = (vars.module === '')? '' : vars.module + "/";
        ajax_modal  = ( data.ajax_modal !== undefined ) ? data.ajax_modal : '';
        var height;

        if( vars.height !== '' )
        {
            height = vars.height;
        }
        
        if( $('.md-modal.md-show').length > 0 )
        {
            var clone          = $("#" + vars.modal_id ).clone().addClass($('#'+vars.modal_id).attr('id')),
                next_script    = $('#'+vars.modal_id).next('script'),
                 prev_script    = $('#'+vars.modal_id).prev('script');
            
            if( !cloned[ vars.modal_id ] )
            {
                 $("#" + vars.modal_id ).remove();
                 next_script.remove();
                 prev_script.remove(); 

                 $('body').find('div.md-overlay:not([style])').last().before(prev_script.clone());
                 $('body').find('div.md-overlay:not([style])').last().before(clone);
                 cloned[vars.modal_id] = true;
                 $('body').find('div.md-overlay:not([style])').last().before(next_script.clone());
            }
            else
            {
                var modal_inside = $('#'+vars.modal_id).parents('.md-modal').find('#'+vars.modal_id);

                modal_inside.remove();
                modal_inside.prev('script').remove();
                modal_inside.next('script').remove();
            }
        }

        container = $("#" + vars.modal_id + "_content");
        
        container.html($("#loading").html());
        
        if( vars.ajax )
        {
            if( ajax_modal != '' )
            {
                container.html(ajax_modal).promise().done( function() {
                    if( container.find('form').children().length !== 0 )
                    {
                        var parent_of_footer = container.find('form').children().find('div.md-footer').parents('div.row');

                        if( container.find('form').children().find('div.md-footer').parents('div.row').length !== 0 )
                        {
                            container.find('form').children().not(parent_of_footer).not('input[id*="ajax-upload-id-"]').wrapAll('<div class="modal_scroll"></div>');                
                        }
                        else 
                        {
                            container.find('form').children().not('div.md-footer').not('input[id*="ajax-upload-id-"]').wrapAll('<div class="modal_scroll"></div>');    
                        }
                    }
                    else
                    {
                        container.find('form').append('<div class="modal_scroll"></div>');   
                    }
                    
                    container.find('div.modal_scroll').attr( 'style', 'height:'+height );        
                    
                    container.find('div.modal_scroll').jScrollPane(settings);

                    api = container.find('div.modal_scroll').data('jsp');    

                    api.reinitialise();
                    
                    $("#" + vars.modal_id + "_content").find('a.cancel_modal').on( 'click', function() {
                       $("#" + vars.modal_id).removeClass("md-show");
                       if( $('.md-modal.md-show').length == 0 )
                       {
                            $body.removeClass('md-open');
                            $body.removeAttr('style');
                       }
                       if($('.md-modal.md-show').length > 0)
                       {
                            ModalEffects.reduceZindex();
                            $('.md-overlay').last().remove();
                       }
                        
                    } );
                });
            }
        }
        else
        {
            container.load($base_url + mod + path, function() {

                if( container.find('form').children().length !== 0 )
                {
                    var parent_of_footer = container.find('form').children().find('div.md-footer').parents('div.row');

                    if( container.find('form').children().find('div.md-footer').parents('div.row').length !== 0 )
                    {
                        container.find('form').children().not(parent_of_footer).not('input[id*="ajax-upload-id-"]').wrapAll('<div class="modal_scroll"></div>');                
                    }
                    else 
                    {
                        container.find('form').children().not('div.md-footer').not('input[id*="ajax-upload-id-"]').wrapAll('<div class="modal_scroll"></div>');    
                    }
                }
                else
                {
                    container.find('form').append('<div class="modal_scroll"></div>');   
                }
                
                container.find('div.modal_scroll').attr( 'style', 'height:'+height );        
                
                container.find('div.modal_scroll').jScrollPane(settings);

                api = container.find('div.modal_scroll').data('jsp');    

                api.reinitialise();
                
                $("#" + vars.modal_id + "_content").find('a.cancel_modal').on( 'click', function() {
                   $("#" + vars.modal_id).removeClass("md-show");
                   if( $('.md-modal.md-show').length == 0 )
                   {
                        $body.removeClass('md-open');
                        $body.removeAttr('style');
                   }
                   if($('.md-modal.md-show').length > 0)
                   {
                        ModalEffects.reduceZindex();
                        $('.md-overlay').last().remove();
                   }
                });
            });

        }

        container.bind(
            'mousewheel',
            function(e)
            {
                e.preventDefault();
            }
        )

        $(".md-content").draggable({
            handle: "h3.md-header",
            containment: "#content-wrapper", 
            scroll: false
        });

        $('h3.md-header').css('cursor', 'move');
        $(".md-modal").center();
        $(".md-modal").css({
            'padding-top' : '2%',
            'top' : '50%',
            'bottom' : '0'
        });
    }
    
    this.closeModal = function(data){
        var $body   = $('body');
        
        if( $('.md-modal.md-show').length == 1 || $('.md-modal.md-show').length == 0 )
        {
            $body.removeClass('md-open');
            $body.removeAttr('style');
        }
        
        $("#" + vars.modal_id).removeClass("md-show");

        if($('.md-modal.md-show').length > 0)
        {
            ModalEffects.reduceZindex();
            $('.md-overlay').last().remove();
        }
    }

    this.checkIfScroll = function()
    {
        return ( vars.height !== '' );
    }

    this.construct(options);
}

var handleData = function(options){

    var vars = {
        controller: '',
        method: '',
        module: ''
    } 

    this.construct = function(options){
        $.extend(vars , options);
    }
    
    this.updateData = function(data){

        var url;

        if( vars.module != '' )
        {
            url         =  vars.module + "/" + vars.controller + "/" + vars.method;
        }
        else 
        {
            url         = vars.controller + "/" + vars.method;
        }
        
        $.post($base_url + url, data, function(result){
            var type = (result.flag == 1) ? "success" : "error";
            callback = result.callback || '';
            
            if(result.flag == 1){
                var reload = result.reload || '';
                switch(reload)
                {
                    case 'datatable':
                        var scrollX = result.scroll || "",
                            group_column = result.group_column || 0,
                            colspan = result.colspan || 0,
                            advanced_filter = result.advanced_filter || false,
                            data_to_pass    = result.post_data || '';
                            
                        load_datatable(result.table_id, result.path, scrollX, group_column, colspan, advanced_filter, data_to_pass);
                    break;
                    case 'list':
                        $("#" + result.wrapper).isLoading();
                        
                        $.post(result.path, function(e){
                          $("#" + result.wrapper).isLoading("hide").html(e);
                        },'json');
                        
                    break;
                    case 'dynamic_table':
                        var data            = result.data || {},
                            response_type   = result.response_type || 'html',
                            wrapper         = result.wrapper,
                            functions       = result.functions || [],
                            i = 0,
                            len;
                        $.post( $base_url + result.path, data, function( response ) {
                            $( wrapper ).html( response );
                            
                            if( functions.length !== 0 )
                            {
                                if( functions instanceof Array )
                                {

                                    len     = functions.length;

                                    for( ; i < len; i++ )
                                    {
                                        eval( functions[ i ] );
                                    }

                                }
                                else
                                {
                                    eval( functions );
                                }
                            }

                        }, response_type ); 
                    break;
                    default:
                        location.reload(true); 
                }

                if( callback != '' )
                {
                    eval( callback );
                }
            }
            notification_msg(type, result.msg);
        }, 'json');
    }

    this.removeData = function(data){

        var url;

        if( vars.module != '' )
        {
            url         =  vars.module + "/" + vars.controller + "/" + vars.method;
        }
        else 
        {
            url         = vars.controller + "/" + vars.method;
        }
        
        $.post($base_url + url, data, function(result){
            var type = (result.flag == 1) ? "success" : "error",
                callback = result.callback || '';
            
            if(result.flag == 1){
                var reload = result.reload || '';
                switch(reload)
                {
                    case 'datatable':
                        var scrollX = result.scroll || "",
                            group_column = result.group_column || 0,
                            colspan = result.colspan || 0,
                            advanced_filter = result.advanced_filter || false,
                            data_to_pass    = result.post_data || '';
                            
                        load_datatable(result.table_id, result.path, scrollX, group_column, colspan, advanced_filter, data_to_pass);
                    break;
                    case 'list':
                        $("#" + result.wrapper).isLoading();
                        
                        $.post(result.path, function(e){
                          $("#" + result.wrapper).isLoading("hide").html(e);
                        },'json');
                        
                    break;
                    case 'dynamic_table':
                        var data            = result.data || {},
                            response_type   = result.response_type || 'html',
                            wrapper         = result.wrapper,
                            functions       = result.functions || [],
                            i = 0,
                            len;
                        $.post( $base_url + result.path, data, function( response ) {
                            $( wrapper ).html( response );
                            
                            if( functions.length !== 0 )
                            {
                                if( functions instanceof Array )
                                {

                                    len     = functions.length;

                                    for( ; i < len; i++ )
                                    {
                                        eval( functions[ i ] );
                                    }

                                }
                                else
                                {
                                    eval( functions );
                                }
                            }

                        }, response_type ); 
                    break;
                    default:
                        location.reload(true); 
                }

                if( callback != '' )
                {
                    eval( callback );
                }
            }
            
            notification_msg(type, result.msg);
        }, 'json');
    }
    
    this.workflowData = function(data){
        var id = data.role_code.toLowerCase();
            link_id = id + "_workflow";
            icon_id = id + "_icon";
            choices_id = id + "_choices";
            
        $.post($base_url + vars.module + "/" + vars.controller + "/" + vars.method, data, function(result){
            if(result.flag == "success"){
                if(data.is_return == 1){
                    $("#" + icon_id).removeClass("flaticon-minus99 amber darken-1");
                    $("#" + icon_id).addClass("flaticon-left193 red");
                } else {
                    $("#" + icon_id).removeClass("flaticon-minus99 amber darken-1");
                    $("#" + icon_id).addClass("flaticon-checkmark21");
                }   
                
                $("#" + link_id).prop("onclick", null);
                $("#" + icon_id).off();
                
                if($("#" + choices_id).length)
                        $("#" + choices_id).removeClass("fixed-action-btn");
            }   
            notification_msg(result.flag, result.msg);
        }, 'json');
    }
    
    this.loadData = function(data){
        var cy = data.cy || $("#c_year").val();
        $("#" + data.id).isLoading();
        $.post($base_url + vars.module + "/" + vars.controller + "/" + vars.method, {office : data.office, cy : cy, parent_id : data.parent_id}, function(result){
            $("#" + data.id).isLoading("hide").html(result);
        });
    }

    this.construct(options);

}

// Always center modal when opening
$.fn.center = function() {
    
    $(".md-content").removeAttr("style");
    
    this.css({
        'position': 'fixed',
        'left': '50%',
        'top': '40%',
        'right': '50%',
        'bottom': '-10%'
    });
    
    return this;
}

/**
 * Number.prototype.format(n, x)
 * 
 * @param integer n: length of decimal
 * @param integer x: length of sections
 */
Number.prototype.format = function(n, x) {
    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')';
    return this.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,');
};

var dateFormat = function () {
    var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
        timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standard|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
        timezoneClip = /[^-+\dA-Z]/g,
        pad = function (val, len) {
            val = String(val);
            len = len || 2;
            while (val.length < len) val = "0" + val;
            return val;
        };

    // Regexes and supporting functions are cached through closure
    return function (date, mask, utc) {
        var dF = dateFormat;

        // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
        if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
            mask = date;
            date = undefined;
        }

        // Passing date through Date applies Date.parse, if necessary
        date = date ? new Date(date) : new Date;
        if (isNaN(date)) throw SyntaxError("invalid date");

        mask = String(dF.masks[mask] || mask || dF.masks["default"]);

        // Allow setting the utc argument via the mask
        if (mask.slice(0, 4) == "UTC:") {
            mask = mask.slice(4);
            utc = true;
        }

        var _ = utc ? "getUTC" : "get",
            d = date[_ + "Date"](),
            D = date[_ + "Day"](),
            m = date[_ + "Month"](),
            y = date[_ + "FullYear"](),
            H = date[_ + "Hours"](),
            M = date[_ + "Minutes"](),
            s = date[_ + "Seconds"](),
            L = date[_ + "Milliseconds"](),
            o = utc ? 0 : date.getTimezoneOffset(),
            flags = {
                d:    d,
                dd:   pad(d),
                ddd:  dF.i18n.dayNames[D],
                dddd: dF.i18n.dayNames[D + 7],
                m:    m + 1,
                mm:   pad(m + 1),
                mmm:  dF.i18n.monthNames[m],
                mmmm: dF.i18n.monthNames[m + 12],
                yy:   String(y).slice(2),
                yyyy: y,
                h:    H % 12 || 12,
                hh:   pad(H % 12 || 12),
                H:    H,
                HH:   pad(H),
                M:    M,
                MM:   pad(M),
                s:    s,
                ss:   pad(s),
                l:    pad(L, 3),
                L:    pad(L > 99 ? Math.round(L / 10) : L),
                t:    H < 12 ? "a"  : "p",
                tt:   H < 12 ? "am" : "pm",
                T:    H < 12 ? "A"  : "P",
                TT:   H < 12 ? "AM" : "PM",
                Z:    utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                o:    (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                S:    ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
            };

        return mask.replace(token, function ($0) {
            return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
        });
    };
}();

// Some common format strings
dateFormat.masks = {
    "default":      "ddd mmm dd yyyy HH:MM:ss",
    shortDate:      "m/d/yy",
    mediumDate:     "mmm d, yyyy",
    longDate:       "mmmm d, yyyy",
    fullDate:       "dddd, mmmm d, yyyy",
    shortTime:      "h:MM TT",
    mediumTime:     "h:MM:ss TT",
    longTime:       "h:MM:ss TT Z",
    isoDate:        "yyyy-mm-dd",
    isoTime:        "HH:MM:ss",
    isoDateTime:    "yyyy-mm-dd'T'HH:MM:ss",
    isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
};

// Internationalization strings
dateFormat.i18n = {
    dayNames: [
        "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
        "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
    ],
    monthNames: [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
        "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
    ]
};

// For convenience...
Date.prototype.format = function (mask, utc) {
    return dateFormat(this, mask, utc);
};
