var utils = {
    
    removeAfter: function(selector, currentPos, fieldName) {
        $(selector).each(function(i, el) {
            var curEl = $(el);
            if(parseInt(curEl.attr('pos')) > currentPos ) {
                curEl.remove();
            }
        });
        $(selector).last().attr('name', fieldName);
    },

    tableContains: function(tableObj, fieldText) {
        
        var textFound = false;
        var fields = tableObj.find('td').each(function(i, el){
            if($(el).text() == fieldText) {
                textFound = true;
                return textFound;
            }
        });

        return textFound;
    },

    removeFlash: function(container) {

        // Remove current messages
        $(container).find('.alert').remove();
        
    },

    addFlash: function(type, container, message) {

        // Add new messages
        $(container).prepend('<div class="alert alert-' + type + ' alert-block">\
        <a class="close" data-dismiss="alert" href="#">×</a>\
        ' + message + '\
        </div>');  

        // Scroll modal to top
        $(window).scrollTop( $(container).offset().top ); 
        $(container).parents('.modal').animate({ scrollTop: 0 }, 'slow');
    },

    addVueFlash: function(type, container, message) {

        // Add new messages
        $(container).prepend('<div class="alert alert-' + type + ' alert-block">\
        <a class="close" data-dismiss="alert" href="#">×</a>\
        ' + message + '\
        </div>');  

        // Scroll modal to top
        $(window).scrollTop( $(container).offset().top );        
        $(container).parents('.modal').scrollTop(0);
        console.log(container);

    }

}

export default utils;