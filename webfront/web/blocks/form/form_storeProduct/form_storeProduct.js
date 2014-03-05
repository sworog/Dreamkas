define(function(require) {
        //requirements
        var Form = require('blocks/form/form'),
            roundPrice = require('utils/roundPrice'),
            numeral = require('numeral');

        return Form.extend({
            __name__: 'form_storeProduct',
            defaultInputLinkText: 'Введите значение',
            template: require('tpl!blocks/form/form_storeProduct/templates/index.html'),
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
                    this.renderRounding();
                }
            },
            initialize: function(){
                var block = this;

                if (block.model.id){
                    block.redirectUrl = '/products/' + block.model.id
                }
            },
            findElements: function(){
                var block = this;
                Form.prototype.findElements.apply(block, arguments);

                block.$retailPricePreferenceInput = block.$('[name="retailPricePreference"]');
                block.$retailPriceInput = block.$('[name="retailPrice"]');
                block.$retailMarkupInput = block.$('[name="retailMarkup"]');
                block.$rounding = block.$('.productForm__rounding');

                block.$retailPriceLink = block.$retailPriceInput.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupInput.next('.productForm__inputLink');
            },
            render: function(){
                var block = this;

                Form.prototype.render.call(this);

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
                block.renderRounding()
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
                var purchasePrice = numeral().unformat(LH.formatMoney(this.model.get('product').purchasePrice)),
                    retailMarkup = numeral().unformat(LH.formatMoney(this.$retailMarkupInput.val())),
                    calculatedVal;

                if (!purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = LH.formatMoney(+(retailMarkup / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceInput
                    .val(calculatedVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var purchasePrice = numeral().unformat(LH.formatMoney(this.model.get('product').purchasePrice)),
                    retailPrice = numeral().unformat(LH.formatMoney(this.$retailPriceInput.val())),
                    calculatedVal;

                if (!purchasePrice || !retailPrice || _.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = LH.formatMoney(+(retailPrice * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupInput
                    .val(calculatedVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                var price = $.trim(this.$retailPriceInput.val()),
                    text;

                if (price){
                    text = LH.formatMoney(price) + ' руб.'
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

                if (markup) {
                    text = LH.formatMoney(markup) + '%'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailMarkupLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            },
            renderRounding: function(){
                var block = this,
                    price = $.trim(block.$retailPriceInput.val()),
                    rounding = block.model.get('product').rounding.name;

                if (price){
                    block.$rounding.show();
                    block.$rounding.addClass('preloader_spinner');
                    roundPrice(price, rounding).done(function(data){
                        block.$rounding.removeClass('preloader_spinner');
                        block.$rounding.html('(' + LH.formatMoney(data.price) + ' руб. - округлено ' + LH.translate(rounding) + ')');
                    });
                } else {
                    block.$rounding.hide();
                }
            }
        });
    }
);