define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            getText = require('kit/getText'),
            formatMoney = require('kit/formatMoney'),
            formatAmount = require('kit/formatAmount'),
            formatNumber = require('kit/formatNumber/formatNumber'),
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
                retailPricePreferenceFields: require('ejs!./retailPricePreferenceFields.ejs'),
                retailMarkupField: require('ejs!./retailMarkupField.ejs'),
                retailPriceField: require('ejs!./retailPriceField.ejs')
            },
            events: {
                'keyup [name="purchasePrice"]': function(e){
                    var block = this;

                    block.model.set('purchasePrice', formatNumber(e.target.value) || null);

                    block.model.calculateRanges();

                    block.renderRetailPricePreferenceFields();
                },
                'keyup [name="retailMarkupMax"]': function(e){
                    var block = this;

                    block.model.set('retailMarkupMax', formatNumber(e.target.value) || null);

                    block.model.calculateRanges();

                    block.renderRetailPriceField();
                },
                'keyup [name="retailMarkupMin"]': function(e){
                    var block = this;

                    block.model.set('retailMarkupMin', formatNumber(e.target.value) || null);

                    block.model.calculateRanges();

                    block.renderRetailPriceField();
                },
                'keyup [name="retailPriceMin"]': function(e){
                    var block = this;

                    block.model.set('retailPriceMin', formatNumber(e.target.value) || null);

                    block.model.calculateRanges();

                    block.renderRetailMarkupField();
                },
                'keyup [name="retailPriceMax"]': function(e){
                    var block = this;

                    block.model.set('retailPriceMax', formatNumber(e.target.value) || null);

                    block.model.calculateRanges();

                    block.renderRetailMarkupField();
                },
                'change [name="type"]': function(e) {
                    var block = this;

                    $(block.el).find('.form_product__typeFields').attr('disabled', true);
                    $(block.el).find('.form_product__typeFields[rel="' + e.target.value + '"]').removeAttr('disabled');

                },
                'change [name="retailPricePreference"]': function(e){
                    var block = this;

                    block.model.set('retailPricePreference', e.target.value);

                    block.model.calculateRanges();

                    block.renderRetailPricePreferenceFields();

                    switch (e.target.value){
                        case 'retailMarkup':
                            block.el.querySelector('[name="retailMarkupMin"]').focus();
                            break;
                        case 'retailPrice':
                            block.el.querySelector('[name="retailPriceMin"]').focus();
                            break;
                    }


                }
            },
            renderRetailPricePreferenceFields: function(){
                var block = this;

                $(block.el).find('.form_product__retailPricePreferenceFields').html($(block.partials.retailPricePreferenceFields({
                    model: block.model
                })));

            },
            renderRetailMarkupField: function(){
                var block = this;

                $(block.el).find('.form_product__retailMarkupField').replaceWith($(block.partials.retailMarkupField({
                    model: block.model
                })));

            },
            renderRetailPriceField: function(){
                var block = this;

                $(block.el).find('.form_product__retailPriceField').replaceWith($(block.partials.retailPriceField({
                    model: block.model
                })));

            }
        });
    }
);