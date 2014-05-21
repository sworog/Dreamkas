define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        form_barcodes__row = require('tpl!blocks/form/form_barcodes/form_barcodes__row.html'),
        stringToFragment = require('kit/stringToFragment/stringToFragment'),
        cookies = require('cookies'),
        Page = require('kit/page/page');

    require('lodash');

    return Form.extend({
        el: '.form_barcodes',
        startListening: function(){
            var block = this;

            Form.prototype.startListening.apply(block, arguments);

            block.listenTo(Page.current.models.product.collections.barcodes, {
                add: function(barcodeModel){
                    block.el.appendChild(stringToFragment(form_barcodes__row({
                        barcodeModel: barcodeModel
                    })));
                },
                remove: function(barcodeModel){
                    block.el.removeChild(block.el.querySelector('[data-barcode_cid="' + barcodeModel.cid + '"]'));
                }
            });
        },
        submit: function(formData){
            var block = this;

            console.log(formData);

            block.request = $.ajax({
                type: 'PUT',
                url: LH.baseApiUrl + '/products/' + Page.current.models.product.id + '/barcodes',
                dataType: 'json',
                headers: {
                    Authorization: 'Bearer ' + cookies.get('token')
                },
                data: formData
            });

            return block.request;
        }
    });
});