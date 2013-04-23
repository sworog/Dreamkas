define(
    [
        '/views/kit/main.js',
        '/models/product.js',
        '/utils/main.js',
        'tpl!./main.html'
    ],
    function(Block, Product, utils, mainTpl) {
        return Block.extend({
            tpl: {
                main: mainTpl
            },
            model: Product,
            defaultInputLinkText: 'Введите значение',

            initialize: function() {
                var block = this;

                block.render();

                block.$retailPricePreferenceInput = block.$el.find('[name="retailPricePreference"]');
                block.$retailPriceInput = block.$el.find('[name="retailPrice"]');
                block.$retailMarkupInput = block.$el.find('[name="retailMarkup"]');
                block.$purchasePriceInput = block.$el.find('[name="purchasePrice"]');

                block.$retailPriceLink = block.$retailPriceInput.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupInput.next('.productForm__inputLink');

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
            },
            events: {
                'click .productForm__inputLink': function(e) {
                    e.preventDefault;
                    var block = this,
                        $link = $(e.currentTarget),
                        $linkedInput = $link.prev('.productForm__linkedInput');

                    switch ($linkedInput.attr('name')) {
                        case 'retailMarkup':
                            block.showRetailMarkupInput();
                            break;
                        case 'retailPrice':
                            block.showRetailPriceInput();
                            break;
                    }
                },
                'keyup [name="purchasePrice"]': function(e) {
                    var block = this;

                    if (block.$retailPriceInput.is(':hidden')) {
                        block.calculateRetailPrice();
                    }

                    if (block.$retailMarkupInput.is(':hidden')) {
                        block.calculateRetailMarkup();
                    }
                },
                'keyup [name="retailMarkup"]': function(e) {
                    var block = this;
                    block.calculateRetailPrice();
                },
                'keyup [name="retailPrice"]': function(e) {
                    var block = this;
                    block.calculateRetailMarkup();
                },
                'change [name="retailMarkup"]': function(e) {
                    var block = this;
                    block.renderRetailMarkupLink();
                },
                'change [name="retailPrice"]': function(e) {
                    var block = this;
                    block.renderRetailPriceLink();
                }
            },
            showRetailMarkupInput: function() {
                var block = this;

                block.$retailPriceInput.addClass('productForm__hiddenInput');
                block.$retailMarkupInput
                    .removeClass('productForm__hiddenInput')
                    .focus();

                block.$retailPricePreferenceInput.val('retailMarkup');
            },
            showRetailPriceInput: function() {
                var block = this;

                block.$retailMarkupInput.addClass('productForm__hiddenInput');
                block.$retailPriceInput
                    .removeClass('productForm__hiddenInput')
                    .focus();

                block.$retailPricePreferenceInput.val('retailPrice');
            },
            calculateRetailPrice: function() {
                var block = this,
                    calculatedVal,
                    purchasePrice = utils.normalizePrice(block.$purchasePriceInput.val()),
                    retailMarkup = utils.normalizePrice(block.$retailMarkupInput.val());

                if (_.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = utils.formatPrice(purchasePrice + retailMarkup / 100 * purchasePrice);
                }

                block.$retailPriceInput
                    .val(calculatedVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var block = this,
                    calculatedVal,
                    retailPrice = utils.normalizePrice(block.$retailPriceInput.val()),
                    purchasePrice = utils.normalizePrice(block.$purchasePriceInput.val());

                if (_.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = utils.formatPrice(retailPrice * 100 / purchasePrice - 100);
                }

                block.$retailMarkupInput
                    .val(calculatedVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                var block = this;
                block.$retailPriceLink.find('.productForm__inputLinkText').html($.trim(block.$retailPriceInput.val()) || block.defaultInputLinkText);
            },
            renderRetailMarkupLink: function() {
                var block = this;
                block.$retailMarkupLink.find('.productForm__inputLinkText').html($.trim(block.$retailMarkupInput.val()) || block.defaultInputLinkText);
            }
        });
    }
);