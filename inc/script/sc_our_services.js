/**
 * 
 * @author Smartcat Jan 21, 2015
 */
jQuery( document ).ready(function($){

    var member_height = $('#sc_our_services.grid_circles .sc_service').width();
    $('#sc_our_services.grid_circles .sc_service').each(function(){
        $(this).css({
            height: member_height
        });
    });    

    
    $('#sc_our_services .sc_service').hover(function(){
        $('.sc_service_overlay',this).stop(true,false).fadeIn(440);
        $('.wp-post-image',this).addClass('zoomIn');
        $('.sc_services_more',this).addClass('show');
        
    },function(){
       $('.sc_service_overlay',this).stop(true,false).fadeOut(440)       
       $('.wp-post-image',this).removeClass('zoomIn');
       $('.sc_services_more',this).removeClass('show');
       
    });

});
