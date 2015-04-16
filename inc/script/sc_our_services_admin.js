/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function($) {
    
//    $('#sc_services_icons option').html('<i class="' + $('#sc_services_icons option').attr('data-icon') + '"></i>');
    
    
    $('#sc_services_icons .fa').click( function() {
        $('#service_icon').val( $(this).attr('class') );
        $('#sc_services_icons .fa').removeClass('active');
        $(this).addClass('active');
    });
    
    
    sc_services_set_display();
    
    $('#sc_our_services_template').change(function(){
        sc_services_set_display();
    });
    
    
    function sc_services_set_display(){
        if('hc' == $('#sc_our_services_template').val()){
            $('#social_icons_row').hide();
            $('#honey-comb-row').show();
            $('#columns-row,#height-row,#margin-row').hide();
        }else{
            $('#social_icons_row').show();
            $('#honey-comb-row').hide();
            $('#columns-row,#height-row,#margin-row').show();
        }   
    }
    
    
    sc_services_set_order();

    $('.sortable').sc_sortable();
    $('.handles').sc_sortable({
        handle: 'span'
    });
    $('.connected').sc_sortable({
        connectWith: '.connected'
    });
    $('.exclude').sc_sortable({
        items: ':not(.disabled)'
    });
    $('.sortable').sc_sortable().bind('sortupdate', function(e, ui) {
        sc_services_set_order();
    });

    function sc_services_set_order() {
        $('.sortable li').each(function() {
            $(this).attr('sc_member_order', $(this).index());
        });
    }

    $('#set_order').click(function() {
        var post_path = $('.sortable').attr('data-action');
        // UX
        $(this).attr('disabled','disable');
        
        $('.sc_service_update_status .sc_service_updating').stop(true,false).fadeIn(200,function(){
            $(this).delay(800).fadeOut(200);
        });
        
        $('.sortable li').each(function() {
            
            var data = {
                action: 'sc_services_update_order',
                id: $(this).attr('id'),
                sc_member_order: $(this).attr('sc_member_order')
            };
            
            jQuery.post('admin-ajax.php', data, function(response) {
                
                // whatever you need to do; maybe nothing
                $('.sc_service_update_status .sc_service_updating').hide();
                $('.sc_service_update_status .sc_service_saved').stop(true,false).fadeIn(200,function() {
                    $(this).delay(1000).fadeOut(200);
                });
                $('#set_order').removeAttr('disabled');
                
                console.log( response );
                
            });
        });
    });
});

/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 * 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function($) {
    var dragging, placeholders = $();
    $.fn.sc_sortable = function(options) {
        var method = String(options);
        options = $.extend({
            connectWith: false
        }, options);
        return this.each(function() {
            if (/^enable|disable|destroy$/.test(method)) {
                var items = $(this).children($(this).data('items')).attr('draggable', method == 'enable');
                if (method == 'destroy') {
                    items.add(this).removeData('connectWith items')
                            .off('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
                }
                return;
            }
            var isHandle, index, items = $(this).children(options.items);
            var placeholder = $('<' + (/^ul|ol$/i.test(this.tagName) ? 'li' : 'div') + ' class="sortable-placeholder">');
            items.find(options.handle).mousedown(function() {
                isHandle = true;
            }).mouseup(function() {
                isHandle = false;
            });
            $(this).data('items', options.items)
            placeholders = placeholders.add(placeholder);
            if (options.connectWith) {
                $(options.connectWith).add(this).data('connectWith', options.connectWith);
            }
            items.attr('draggable', 'true').on('dragstart.h5s', function(e) {
                if (options.handle && !isHandle) {
                    return false;
                }
                isHandle = false;
                var dt = e.originalEvent.dataTransfer;
                dt.effectAllowed = 'move';
                dt.setData('Text', 'dummy');
                index = (dragging = $(this)).addClass('sortable-dragging').index();
            }).on('dragend.h5s', function() {
                if (!dragging) {
                    return;
                }
                dragging.removeClass('sortable-dragging').show();
                placeholders.detach();
                if (index != dragging.index()) {
                    dragging.parent().trigger('sortupdate', {item: dragging});
                }
                dragging = null;
            }).not('a[href], img').on('selectstart.h5s', function() {
                this.dragDrop && this.dragDrop();
                return false;
            }).end().add([this, placeholder]).on('dragover.h5s dragenter.h5s drop.h5s', function(e) {
                if (!items.is(dragging) && options.connectWith !== $(dragging).parent().data('connectWith')) {
                    return true;
                }
                if (e.type == 'drop') {
                    e.stopPropagation();
                    placeholders.filter(':visible').after(dragging);
                    dragging.trigger('dragend.h5s');
                    return false;
                }
                e.preventDefault();
                e.originalEvent.dataTransfer.dropEffect = 'move';
                if (items.is(this)) {
                    if (options.forcePlaceholderSize) {
                        placeholder.height(dragging.outerHeight());
                    }
                    dragging.hide();
                    $(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
                    placeholders.not(placeholder).detach();
                } else if (!placeholders.is(this) && !$(this).children(options.items).length) {
                    placeholders.detach();
                    $(this).append(placeholder);
                }
                return false;
            });
        });
    };
})(jQuery);
