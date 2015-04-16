/**
 * 
 * @author Smartcat Jan 21, 2015
 */
jQuery( document ).ready(function($){

    smartcat_resize_grid();
    $( window ).resize(function() {
        smartcat_resize_grid();
    });


    
    function smartcat_resize_grid(){
        var member_height = $('#sc_our_services.smartcat_zoomOut .sc_service').width();
        $('#sc_our_services.smartcat_zoomOut .sc_service').each(function(){
            $(this).css({
                height: member_height
            });
        });         
        var member_height = $('#sc_our_services.smartcat_slide .sc_service').width();
        $('#sc_our_services.smartcat_slide .sc_service').each(function(){
            $(this).css({
                height: member_height
            });
        });         
    }


    $('#sc_our_services.smartcat_images .sc_service').hover( function () {
        $('.sc-overlay', $(this)).stop( true, false ).animate({
            height      :   '50%'
        }, 400 );
        $('.sc_service_name', $(this)).stop( true, false ).animate({
            bottom      :   '50px'
        }, 400 );
    }, function() {
        $('.sc-overlay', $(this)).stop( true, false ).animate({
            height      :   '40px'
        }, 400 );
        $('.sc_service_name', $(this)).stop( true, false ).animate({
            bottom      :   '0'
        }, 400 );
    });

    $('#sc_our_services.smartcat_zoomOut .sc_service').hover( function () {
        
        $('.sc_service_name', $(this) ).removeClass('bounceOutDown').delay(100).queue( function ( next ) {
            $( this ).addClass( 'animated bounceInUp show' );
            next();
        });
        $('.sc_services_read_more', $(this) ).removeClass('bounceOutRight').delay(400).queue( function ( next ) {
            $( this ).addClass('animated bounceInRight show');
            next();
        });

    }, function () {
        
        $('.sc_services_read_more', $(this) ).addClass( 'bounceOutRight' ).delay(600).queue( function ( next ) {
            $( this ).removeClass( 'show animated' );
            next();
        });
        $('.sc_service_name', $(this) ).addClass( 'bounceOutDown' ).delay(600).queue( function ( next ) {
            $( this ).removeClass('show animated');
            next();
        });
        
    });


    $('#sc_our_services.smartcat_slide .sc_service').hover( function () {
        
        $('.sc_service_name', $(this) ).removeClass('bounceOutDown').delay(100).queue( function ( next ) {
            $( this ).addClass( 'animated bounceInUp show' );
            next();
        });
        $('.sc_services_content', $(this) ).removeClass('fadeOutRight').delay(400).queue( function ( next ) {
            $( this ).addClass('animated fadeInRight show');
            next();
        });

    }, function () {
        
        $('.sc_services_content', $(this) ).addClass( 'fadeOutRight' ).delay(600).queue( function ( next ) {
            $( this ).removeClass( 'show animated fadeInRight' );
            next();
        });
        $('.sc_service_name', $(this) ).addClass( 'bounceOutDown' ).delay(600).queue( function ( next ) {
            $( this ).removeClass('show animated');
            next();
        });
        
    });


    $('#sc_our_services.smartcat_quad .sc_service').hover( function () {
        
        var item = $(this);
        
        $('.sc-overlay', $(this) ).stop( true, false ).animate({
            height  :   '100%'
        }, 300, function() {
            $('.sc_services_read_more', item ).show().removeClass('flipOutY').addClass('animated flip');
            $('.sc_service_name', item ).addClass('animated pulse');
        });

    }, function () {
        
        var item = $(this);
        
        $('.sc_services_read_more', item ).addClass('flipOutY').removeClass('flip').delay(600).queue( function (next) {
            
            $(this).hide();
            
            $('.sc_service_name', item ).removeClass('animated pulse');
            $('.sc-overlay', item ).stop( true, false ).animate({
                height  :   '20%'
            }, 200, function() {

            });                  
            
            next();
            
            
            
        });
        
  

        
    });
    
    
    function matchColHeights(selector) {
        var maxHeight = 0;
        $(selector).each(function() {
            
            var height = $(this).height();
            
            console.log( height );

            
            if (height > maxHeight) {
                maxHeight = height;
            }
            
            console.log( maxHeight );
            
        });
        $(selector).css({
            height : maxHeight
        });
    }
    
//    $('#sc_our_services .sc_service').hover(function(){
//        $('.sc_service_overlay',this).stop(true,false).fadeIn(440);
//        $('.wp-post-image',this).addClass('zoomIn');
//        $('.sc_services_more',this).addClass('show');
//        
//    },function(){
//       $('.sc_service_overlay',this).stop(true,false).fadeOut(440)       
//       $('.wp-post-image',this).removeClass('zoomIn');
//       $('.sc_services_more',this).removeClass('show');
//       
//    });

});
