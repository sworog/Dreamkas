define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        cookies = require('cookies'),
        Page = require('kit/page/page');

    require('lodash');
    require('jquery');

    return Form.extend({
        el: '.form_barcode',
        submit: function(formData){
            var block = this;

            return block.validateBarcode(formData);
        },
        submitSuccess: function(){
            var block = this;

            Page.current.models.product.collections.barcodes.push(block.formData);
            block.formData = null;
            block.clear();
            block.el.querySelector('[autofocus]').focus();
        },
        showErrors: function(errors){
            var block = this,
                error = errors.children.barcodes.children.pop(),
                errorString = "";

            _.forEach(error.children, function(data, fieldName){
                if (data.errors){
                    block.el.querySelector('[name="' + fieldName + '"]').classList.add('inputText_error');

                    _.forEach(data.errors, function(error){
                        if (errorString.indexOf(error) < 0){
                            errorString += error + '. '
                        }
                    });
                }
            });

            document.getElementById('barcodesTable').dataset.error = errorString;
        },
        removeErrors: function(){
            var block = this;

            Form.prototype.removeErrors.apply(block, arguments);

            delete document.getElementById('barcodesTable').dataset.error;
        },
        validateBarcode: function(barcode){
            var block = this;

            block.request = $.ajax({
                type: 'PUT',
                url: LH.baseApiUrl + '/products/' + Page.current.models.product.id + '/barcodes?validate=1&validationGroups=barcodes',
                dataType: 'json',
                headers: {
                    Authorization: 'Bearer ' + cookies.get('token')
                },
                data: {
                    barcodes: Page.current.models.product.collections.barcodes.toJSON().concat(barcode)
                }
            });

            return block.request;
        }
    });
});