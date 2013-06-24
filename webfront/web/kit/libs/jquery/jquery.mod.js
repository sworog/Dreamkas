(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    $.fn.mod = function(mod, val) {
        var modClass;

        if (typeof val == 'undefined') {

            $.each($(this).attr('class').split(' '), function(index, className) {
                if (className.indexOf(mod) == 0) {
                    modClass = className;
                    return false;
                }
            });

            return modClass.split('_').pop();
        }

        return this.each(function() {
            $.each($(this).attr('class').split(' '), function(index, className) {
                if (className.indexOf(mod) >= 0) {
                    modClass = className;
                    return false;
                }
            });

            if (modClass) {
                $(this).removeClass(modClass);
            }

            $(this).addClass(mod + '_' + val);

        });
    };

    $.fn.removeMod = function(mod) {
        return this.each(function() {
            var $block = $(this);
            $.each($(this).attr('class').split(' '), function(index, className) {
                if (className.indexOf(mod) >= 0) {
                    $block.removeClass(className);
                    return false;
                }
            });
        });
    };
}));