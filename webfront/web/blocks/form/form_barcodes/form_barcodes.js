define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        cookies = require('cookies'),
        Page = require('kit/page/page');

    require('lodash');

    return Form.extend({
        el: '.form_barcodes',
        render: function(){},
        submit: function(formData){
            var block = this;

            block.request = $.ajax({
                type: 'PUT',
                url: LH.baseApiUrl + '/products/' + Page.current.models.product.id + '/barcodes',
                dataType: 'json',
                headers: {
                    Authorization: 'Bearer ' + cookies.get('token')
                },
                data: {
                    barcodes: Page.current.models.product.collections.barcodes.toJSON()
                }
            });

            return block.request;
        }
    });
});