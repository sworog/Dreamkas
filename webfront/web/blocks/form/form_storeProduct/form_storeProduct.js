define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            roundPrice = require('kit/roundPrice/roundPrice'),
            formatMoney = require('kit/formatMoney/formatMoney'),
            numeral = require('numeral');

        return Form.extend({
            el: '.form_storeProduct',
            defaultInputLinkText: 'Введите значение',
            events: {
                'click .productForm__inputLink': function(e) {
                    e.preventDefault();
                    var $link = $(e.delegateTarget),
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
                    this.renderRetailPriceLink();
                    this.renderRounding();
                },
                'keyup [name="retailPrice"]': function() {
                    this.calculateRetailMarkup();
                    this.renderRetailMarkupLink();
                }
            },
            initialize: function(){
                var block = this;

                if (block.model.id){
                    block.redirectUrl = '/stores/' + PAGE.params.storeId + '/products/' + block.model.id
                }

                block.$retailPricePreferenceInput = $(block.el).find('[name="retailPricePreference"]');
                block.$retailPriceInput = $(block.el).find('[name="retailPrice"]');
                block.$retailMarkupInput = $(block.el).find('[name="retailMarkup"]');
                block.$rounding = $(block.el).find('.productForm__rounding');

                block.$retailPriceLink = block.$retailPriceInput.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupInput.next('.productForm__inputLink');

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
                block.renderRounding();
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
                var purchasePrice = numeral().unformat(formatMoney(this.model.get('product.purchasePrice'))),
                    retailMarkup = numeral().unformat(formatMoney(this.$retailMarkupInput.val())),
                    calculatedVal;

                if (!purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailMarkup)) {
                    calculatedVal = '';
                } else {
                    calculatedVal = formatMoney(+(retailMarkup / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceInput
                    .val(calculatedVal);
            },
            calculateRetailMarkup: function() {
                var purchasePrice = numeral().unformat(formatMoney(this.model.get('product.purchasePrice'))),
                    retailPrice = numeral().unformat(formatMoney(this.$retailPriceInput.val())),
                    calculatedVal;

                if (!purchasePrice || !retailPrice || _.isNaN(purchasePrice) || _.isNaN(retailPrice)){
                    calculatedVal = '';
                } else {
                    calculatedVal = formatMoney(+(retailPrice * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupInput
                    .val(calculatedVal);
            },
            renderRetailPriceLink: function() {
                var price = $.trim(this.$retailPriceInput.val()),
                    text;

                if (price){
                    text = formatMoney(price) + ' руб.'
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
                    text = formatMoney(markup) + '%'
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
                    rounding = block.model.get('product.rounding.name');

                if (price){
                    block.$rounding.show();
                    block.$rounding.addClass('preloader_spinner');
                    roundPrice(price, rounding).done(function(data){
                        block.$rounding.removeClass('preloader_spinner');
                        block.$rounding.html('(' + formatMoney(data.price) + ' руб. - округлено ' + LH.getText(rounding) + ')');
                    });
                } else {
                    block.$rounding.hide();
                }
            }
        });
    }
);