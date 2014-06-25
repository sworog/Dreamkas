define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            getText = require('kit/getText'),
            formatMoney = require('kit/formatMoney'),
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
            elements: {
                units: '.form_product__units',
                productTypePropertiesFields: '.form_product__productTypePropertiesFields',
                retailPricePreferenceFields: '.form_product__retailPricePreferenceFields'
            },
            models: {
                subCategory: null
            },
            partials: {
                unit: require('ejs!./unitFields.ejs'),
                weight: require('ejs!./weightFields.ejs'),
                alcohol: require('ejs!./alcoholFields.ejs'),
                retailPricePreferenceFields: require('ejs!./retailPricePreferenceFields.ejs')
            },
            events: {
                'keyup [name="purchasePrice"]': function(e){
                    var block = this;

                    block.renderRetailPricePreferenceFields(e.target.value);
                },
                'change [name="type"]': function(e) {
                    this.renderProductTypeSpecificFields(e.target.value);
                }
            },
            renderProductTypeSpecificFields: function(productTypeSelected) {
                var block = this;

                block.elements.units.innerHTML = getText('units', getText('productTypes', productTypeSelected, 'units'), 'capitalFull');

                block.elements.productTypePropertiesFields.innerHTML = block.partials[productTypeSelected]({
                    model: block.model
                });
            },
            renderRetailPricePreferenceFields: function(purchasePrice){
                var block = this;

                $(block.elements.retailPricePreferenceFields).html($(block.partials.retailPricePreferenceFields({
                    model: block.model,
                    purchasePrice: purchasePrice
                })).html());
            }
        });
    }
);