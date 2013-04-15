(function($) {

    $.fn.initBlock = function(url, options) {
        return this.each(function() {

            _.extend(options, {
                el: this,
                _url: url
            });

            try {
                require([url], function(Block) {
                    new Block(options)
                });
            } catch (error) {
                console.warn(error);
            }

        });
    };

    $.fn.initBlocks = function() {
        return this.each(function() {

            var $blocks;

            switch (arguments[0]) {
                case 'inner':
                    $blocks = $(this).find('[block]');
                    break;
                default:
                    $blocks = $(this).find('[block]').add($(this).filter('[block]'));
                    break;
            }

            $blocks.each(function() {
                var $el = $(this),
                    blockUrl = $el.attr('block'),
                    blockOptions = (new Function('return ' + $el.attr('block-options')))() || {};

                $el.removeAttr('block-options');
                $el.removeAttr('block');

                $el.initBlock(blockUrl, blockOptions);
            });
        });
    };
})(jQuery);