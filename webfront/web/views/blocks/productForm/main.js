define(
    [
        '/views/kit/main.js',
        '/models/product.js',
        'tpl!./main.html'
    ],
    function(Block, Product, mainTpl) {
        return Block.extend({
            initialize: function(){
                var block = this;

                Block.prototype.initialize.apply(block, arguments);
                block.$retailPricePreference = block.$el.find('[name="retailPricePreference"]');

            },
            events: {
                'click .productForm__inputLink': function(e){
                    e.preventDefault;
                    var block = this,
                        $link = $(e.currentTarget),
                        $linkedInput = $link.siblings('.productForm__linkedInput');

                    block.$el
                        .find('.productForm__linkedInput')
                        .addClass('productForm__hiddenInput');

                    $linkedInput
                        .removeClass('productForm__hiddenInput')
                        .focus();

                    block.$retailPricePreference.val($linkedInput.attr('name'))
                },
                'change .productForm__linkedInput': function(e){
                    e.preventDefault;
                    var block = this,
                        $linkedInput = $(e.currentTarget),
                        $linkText = $linkedInput.siblings('.productForm__inputLink').find('.productForm__inputLinkText');

                    $linkText.text($.trim($linkedInput.val()));
                }
            },
            tpl: {
                main: mainTpl
            },
            model: Product
        });
    }
);