define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            __name__: 'form_product',
            defaultInputLinkText: 'Введите значение',
            model: null,
            subCategoryModel: null,
            templates: {
                index: require('tpl!blocks/form/form_product/templates/index.html')
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
                    if (this.$retailPriceSpan.is(':hidden')) {
                        this.calculateRetailPrice();
                    }

                    if (this.$retailMarkupSpan.is(':hidden')) {
                        this.calculateRetailMarkup();
                    }
                },
                'keyup [name="retailMarkupMin"], [name="retailMarkupMax"]': function() {
                    this.calculateRetailPrice();
                },
                'keyup [name="retailPriceMin"], [name="retailPriceMax"]': function() {
                    this.calculateRetailMarkup();
                },
                'change [name="retailMarkupMin"], [name="retailMarkupMax"]': function() {
                    this.renderRetailMarkupLink();
                },
                'change [name="retailPriceMin"], [name="retailPriceMax"]': function() {
                    this.renderRetailPriceLink();
                }
            },

            initialize: function(){
                var block = this;

                if (block.model.id){
                    block.redirectUrl = '/products/' + block.model.id
                } else {
                    block.redirectUrl = '/catalog/' + block.model.get('group').id + '/' + block.model.get('category').id + '/' + block.model.get('subCategory').id
                }
            },
            findElements: function(){
                var block = this;
                Form.prototype.findElements.apply(block, arguments);

                block.$retailPricePreferenceInput = block.$el.find('[name="retailPricePreference"]');
                block.$retailPriceMinInput = block.$el.find('[name="retailPriceMin"]');
                block.$retailPriceMaxInput = block.$el.find('[name="retailPriceMax"]');
                block.$retailMarkupMinInput = block.$el.find('[name="retailMarkupMin"]');
                block.$retailMarkupMaxInput = block.$el.find('[name="retailMarkupMax"]');
                block.$retailPriceSpan = block.$el.find('span.retailPrice');
                block.$retailMarkupSpan = block.$el.find('span.retailMarkup');
                block.$purchasePriceInput = block.$el.find('[name="purchasePrice"]');

                block.$retailPriceLink = block.$retailPriceSpan.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupSpan.next('.productForm__inputLink');
            },
            render: function(){
                var block = this;

                Form.prototype.render.call(this);

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
            },
            showRetailMarkupInput: function() {
                this.$retailPriceSpan.addClass('productForm__hiddenInput');
                this.$retailMarkupSpan
                    .removeClass('productForm__hiddenInput')
                    .focus();

                this.$retailPricePreferenceInput.val('retailMarkup');
            },
            showRetailPriceInput: function() {
                this.$retailMarkupSpan.addClass('productForm__hiddenInput');
                this.$retailPriceSpan
                    .removeClass('productForm__hiddenInput')
                    .focus();

                this.$retailPricePreferenceInput.val('retailPrice');
            },
            calculateRetailPrice: function() {
                var purchasePrice = LH.normalizePrice(this.$purchasePriceInput.val()),
                    retailMarkupMin = LH.normalizePrice(this.$retailMarkupMinInput.val()),
                    retailMarkupMax = LH.normalizePrice(this.$retailMarkupMaxInput.val()),
                    calculatedMinVal, calculatedMaxVal;

                if (!purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailMarkupMin)) {
                    calculatedMinVal = '';
                } else {
                    calculatedMinVal = LH.formatPrice(+(retailMarkupMin / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                if (!purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailMarkupMax)) {
                    calculatedMaxVal = '';
                } else {
                    calculatedMaxVal = LH.formatPrice(+(retailMarkupMax / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceMinInput
                    .val(calculatedMinVal)
                    .change();
                this.$retailPriceMaxInput
                    .val(calculatedMaxVal)
                    .change();
            },
            calculateRetailMarkup: function() {
                var retailPriceMin = LH.normalizePrice(this.$retailPriceMinInput.val()),
                    retailPriceMax = LH.normalizePrice(this.$retailPriceMaxInput.val()),
                    purchasePrice = LH.normalizePrice(this.$purchasePriceInput.val()),
                    calculatedMinVal, calculatedMaxVal;

                if (!purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailPriceMin)){
                    calculatedMinVal = '';
                } else {
                    calculatedMinVal = LH.formatPrice(+(retailPriceMin * 100 / purchasePrice).toFixed(2) - 100);
                }

                if (!purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailPriceMax)){
                    calculatedMaxVal = '';
                } else {
                    calculatedMaxVal = LH.formatPrice(+(retailPriceMax * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupMinInput
                    .val(calculatedMinVal)
                    .change();
                this.$retailMarkupMaxInput
                    .val(calculatedMaxVal)
                    .change();
            },
            renderRetailPriceLink: function() {
                var priceMin = $.trim(this.$retailPriceMinInput.val()),
                    priceMax = $.trim(this.$retailPriceMaxInput.val()),
                    text;

                if (priceMin && priceMax){
                    text = LH.formatPrice(priceMin) + " - " + LH.formatPrice(priceMax) + ' руб.'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailPriceLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            },
            renderRetailMarkupLink: function() {
                var markupMin = $.trim(this.$retailMarkupMinInput.val()),
                    markupMax = $.trim(this.$retailMarkupMaxInput.val()),
                    text;

                if (markupMin && markupMax) {
                    text = LH.formatPrice(markupMin) + " - " + LH.formatPrice(markupMax) + '%'
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