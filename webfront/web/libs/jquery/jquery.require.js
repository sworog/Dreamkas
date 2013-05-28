(function(root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery'], factory);
    } else {
        // Browser globals
        factory(jQuery);
    }
}(this, function($) {
    $.fn.require = function(url, options) {
        return this.each(function() {

            if (!url){
                var $elements = $(this).find('[require]').add($(this).filter('[require]'));

                $elements.each(function() {
                    var $el = $(this),
                        url = $el.attr('require'),
                        options = (new Function('return ' + $el.attr('options')))() || {};

                    $el.require(url, options);
                });
            } else {
                $.extend(options, {
                    el: this
                });

                require([url], function(Module) {
                    new Module(options)
                });
            }

            $(this).removeAttr('options');
            $(this).removeAttr('require');

        });
    };
}));