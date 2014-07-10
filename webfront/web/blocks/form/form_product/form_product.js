define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            getText = require('kit/getText/getText'),
            numeral = require('numeral');

        return Form.extend({
            el: '.form_product',
            defaultInputLinkText: 'Введите значение',
            redirectUrl: function(){
                var block = this,
                    redirectUrl;
                
                if (block.model.id){
                    redirectUrl = '/products/' + block.model.id
                } else {
                    redirectUrl = '/groups/' + block.models.subCategory.get('category.group.id') + '/categories/' + block.models.subCategory.get('category.id') + '?subCategoryId' + block.models.subCategory.id
                }
                
                return redirectUrl;
            },
            model: function(){
                var block = this,
                    ProductModel = require('models/product');
                
                return new ProductModel({
                    subCategoryId: block.models.subCategory
                });
            },
            models: {
                subCategory: null
            },
            partials: {
                unit: require('tpl!./unitFields.ejs'),
                weight: require('tpl!./weightFields.ejs'),
                alcohol: require('tpl!./alcoholFields.ejs')
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
                    this.renderPriceInputs();

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
                },
                'change [name="type"]': function(e) {
                    this.renderProductTypeSpecificFields(e.target.value);
                }
            },
            initialize: function(){
                var block = this;

                Form.prototype.initialize.apply(block, arguments);

                block.$retailPricePreferenceInput = $(block.el).find('[name="retailPricePreference"]');
                block.$retailPriceMinInput = $(block.el).find('[name="retailPriceMin"]');
                block.$retailPriceMaxInput = $(block.el).find('[name="retailPriceMax"]');
                block.$retailMarkupMinInput = $(block.el).find('[name="retailMarkupMin"]');
                block.$retailMarkupMaxInput = $(block.el).find('[name="retailMarkupMax"]');
                block.$retailPriceSpan = $(block.el).find('span.retailPrice');
                block.$retailMarkupSpan = $(block.el).find('span.retailMarkup');
                block.$purchasePriceInput = $(block.el).find('[name="purchasePrice"]');

                block.$retailPriceLink = block.$retailPriceSpan.next('.productForm__inputLink');
                block.$retailMarkupLink = block.$retailMarkupSpan.next('.productForm__inputLink');

                block.$retailMarkupField = $(block.el).find('.productForm__retailMarkupField');
                block.$retailPriceField = $(block.el).find('.productForm__retailPriceField');

                block.$productUnits = $(block.el).find('[name="units"]');
                block.$productTypePropertiesFields = $(block.el).find('.form_product__productTypePropertiesFields');
                block.$productTypeRadio = $(block.el).find('[name="type"]');

                block.renderPriceInputs();
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
                var purchasePriceVal = this.$purchasePriceInput.val(),
                    retailMarkupMinVal = this.$retailMarkupMinInput.val(),
                    retailMarkupMaxVal = this.$retailMarkupMaxInput.val(),
                    purchasePrice = $.trim(purchasePriceVal).length ? numeral().unformat(LH.formatMoney(purchasePriceVal)) : null,
                    retailMarkupMin = $.trim(retailMarkupMinVal).length ? numeral().unformat(LH.formatMoney(retailMarkupMinVal)) : null,
                    retailMarkupMax = $.trim(retailMarkupMaxVal).length ? numeral().unformat(LH.formatMoney(retailMarkupMaxVal)) : null,
                    calculatedMinVal, calculatedMaxVal;

                if (retailMarkupMin === null || !purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailMarkupMin)) {
                    calculatedMinVal = '';
                } else {
                    calculatedMinVal = LH.formatMoney(+(retailMarkupMin / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                if (retailMarkupMax === null || !purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailMarkupMax)) {
                    calculatedMaxVal = '';
                } else {
                    calculatedMaxVal = LH.formatMoney(+(retailMarkupMax / 100 * purchasePrice).toFixed(2) + purchasePrice);
                }

                this.$retailPriceMinInput.val(retailMarkupMin !== null ? numeral().unformat(calculatedMinVal) : '');
                this.$retailPriceMaxInput.val(retailMarkupMax !== null ? numeral().unformat(calculatedMaxVal) : '');

                this.renderRetailPriceLink();
            },
            calculateRetailMarkup: function() {
                var retailPriceMinVal = this.$retailPriceMinInput.val(),
                    retailPriceMaxVal = this.$retailPriceMaxInput.val(),
                    purchasePriceVal = this.$purchasePriceInput.val(),
                    retailPriceMin = $.trim(retailPriceMinVal).length ? numeral().unformat(LH.formatMoney(retailPriceMinVal)) : null,
                    retailPriceMax = $.trim(retailPriceMaxVal).length ? numeral().unformat(LH.formatMoney(retailPriceMaxVal)) : null,
                    purchasePrice = $.trim(purchasePriceVal).length ? numeral().unformat(LH.formatMoney(purchasePriceVal)) : null,
                    calculatedMinVal, calculatedMaxVal;

                console.log(retailPriceMin, retailPriceMax);

                if (retailPriceMin === null || !purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailPriceMin)){
                    calculatedMinVal = '';
                } else {
                    calculatedMinVal = LH.formatMoney(+(retailPriceMin * 100 / purchasePrice).toFixed(2) - 100);
                }

                if (retailPriceMax === null || !purchasePrice || _.isNaN(purchasePrice) || _.isNaN(retailPriceMax)){
                    calculatedMaxVal = '';
                } else {
                    calculatedMaxVal = LH.formatMoney(+(retailPriceMax * 100 / purchasePrice).toFixed(2) - 100);
                }

                this.$retailMarkupMinInput.val(retailPriceMin !== null ? numeral().unformat(calculatedMinVal) : '');
                this.$retailMarkupMaxInput.val(retailPriceMax !== null ? numeral().unformat(calculatedMaxVal) : '');

                this.renderRetailPriceLink();
            },
            disablePriceInputs: function(){
                var block = this;

                block.$retailMarkupField.addClass('form__field_disabled');
                block.$retailPriceField.addClass('form__field_disabled');

                block.$retailPriceMinInput.show().prop('disabled', true);
                block.$retailPriceMaxInput.show().prop('disabled', true);
                block.$retailMarkupMinInput.show().prop('disabled', true);
                block.$retailMarkupMaxInput.show().prop('disabled', true);
            },
            enablePriceInputs: function(){
                var block = this;

                block.$retailMarkupField.removeClass('form__field_disabled');
                block.$retailPriceField.removeClass('form__field_disabled');

                block.$retailPriceMinInput.show().prop('disabled', false);
                block.$retailPriceMaxInput.show().prop('disabled', false);
                block.$retailMarkupMinInput.show().prop('disabled', false);
                block.$retailMarkupMaxInput.show().prop('disabled', false);
            },
            renderPriceInputs: function(){
                var block = this;

                if (!block.$purchasePriceInput.val()){
                    block.disablePriceInputs();
                } else {
                    block.enablePriceInputs();
                }

                block.renderRetailMarkupLink();
                block.renderRetailPriceLink();
            },
            renderRetailPriceLink: function() {
                var priceMin = $.trim(this.$retailPriceMinInput.val()),
                    priceMax = $.trim(this.$retailPriceMaxInput.val()),
                    text;

                if (priceMin && priceMax){
                    text = LH.formatMoney(priceMin) + " - " + LH.formatMoney(priceMax) + ' руб.'
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
                    text = LH.formatMoney(markupMin) + " - " + LH.formatMoney(markupMax) + '%'
                } else {
                    text = this.defaultInputLinkText;
                }

                this.$retailMarkupLink
                    .find('.productForm__inputLinkText')
                    .html(text);
            },
            renderProductTypeSpecificFields: function(productTypeSelected) {
                var block = this;

                block.$productUnits.html(getText('units', getText('productTypes', productTypeSelected, 'units'), 'capitalFull'));
                block.$productTypePropertiesFields.html(block.partials[productTypeSelected]({
                    model: block.model
                }));
            }
        });
    }
);