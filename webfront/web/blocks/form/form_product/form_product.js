define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            _ = require('underscore');

        return Form.extend({
            defaultInputLinkText: 'Введите значение',
            model: null,
            blockName: 'form_product',
            redirectUrl: '/products',
            templates: {
                index: require('tpl!./templates/form_product.html')
            },
            listeners: {
                model: {
                    change: function(){
                        var block = this;
                        block.render();
                    }
                }
            },
            events: {
                'click .productForm__inputLink': function(e) {
                    e.preventDefault;
                    var $link = $(e.currentTarget),
                        $linkedInput = $link.prev('.productForm__linkedInput');

                    switch ($linkedInput.attr('name')) {
                        case 'retailMarkup':
                            this.showRetailMarkupInput();
                            break;
                        case 'retailPrice':
                            this.showRetailPriceInput();
                            break;
                    }
                },
                'keyup [name="purchasePrice"]': function(e) {
                    if (this.$retailPriceInput.is(':hidden')) {
                        this.calculateRetailPrice();
                    }

                    if (this.$retailMarkupInput.is(':hidden')) {
                        this.calculateRetailMarkup();
                    }
                },
                'keyup [name="retailMarkup"]': function() {
                    this.calculateRetailPrice();
                },
                'keyup [name="retailPrice"]': function() {
                    this.calculateRetailMarkup();
                },
                'change [name="retailMarkup"]': function() {
                    this.renderRetailMarkupLink();
                },
                'change [name="retailPrice"]': function() {
                    this.renderRetailPriceLink();
                }
            },
            initialize: function(){
                var block = this;

                if (block.model.id){
                    block.redirectUrl = '/products/' + block.model.id
                } else {
                    block.render();
                }
            },
            findElements: function(){
                var block = this;
                Form.prototype.findElements.apply(block, arguments);

                block.$retailPricePreferenceInput = block.$el.find('[name="retailPricePreference"]');
                block.$retailPriceInput = block.$el.find('[name="retailPrice"]');
                block.$retailMarkupInput = block.$el.find('[name="retailMarkup"]');
                block.$purchasePriceInput = block.$el.find('[name="purchasePrice"]');

                block.$retailPriceLink = block.$retailPriceInput.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupInput.next('.productForm__inputLink');
            },
            render: function(){
                var block = this;

                Form.prototype.render.call(this);

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
            },
            showRetailMarkupInput: function() {
                this.$retailPriceInput.addClass('productForm__hiddenInput');
                this.$retailMarkupInput
                    .removeClass('productForm__hiddenInput')
                    .focus();

                this.$retailPricePreferenceInput.val('retailMarkup');
            },
            showRetailPriceInput: function() {
                this.$retailMarkupInput.addClass('productForm__hiddenInput');
                this.$retailPriceInput
                    .removeClass('productForm__hiddenInput')
                    .focus();

                this.$retailPricePreferenceInput.val('retailPrice');
            },
            calculateRetailPrice: function() {
                var purchasePrice = LH.helpers.normalizePrice(this.$purchasePriceInput.val()),
                    retailMarkup = LH.helpers.normalizePrice(this.$retailMarkupInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailMarkup || _.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = LH.helpers.formatPrice(+(retailMarkup / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceInput
                    .val(calculatedVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var retailPrice = LH.helpers.normalizePrice(this.$retailPriceInput.val()),
                    purchasePrice = LH.helpers.normalizePrice(this.$purchasePriceInput.val()),
                    calculatedVal;

                if (!purchasePrice || !retailPrice || _.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = LH.helpers.formatPrice(+(retailPrice * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupInput
                    .val(calculatedVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                var price = $.trim(this.$retailPriceInput.val()),
                    text;

                if (price){
                    text = LH.helpers.formatPrice(price) + ' руб.'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailPriceLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            },
            renderRetailMarkupLink: function() {
                var markup = $.trim(this.$retailMarkupInput.val()),
                    text;

                if (markup){
                    text = LH.helpers.formatPrice(markup) + '%'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailMarkupLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            }
        });
    }
);