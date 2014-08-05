define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        config = require('config'),
        cookies = require('cookies'),
        _ = require('lodash');

    return Form.extend({
        el: '.form_barcode',
        submit: function(){
            var block = this;

            return block.validateBarcode(block.formData);
        },
        submitSuccess: function(){
            var block = this;

            PAGE.models.product.collections.barcodes.push(block.formData);
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

//            _.forEach(document.getElementById('barcodesTable').querySelectorAll('.inputText_error'), function(input){
//                input.classList.remove('inputText_error')
//            });

            delete document.getElementById('barcodesTable').dataset.error;
        },
        validateBarcode: function(barcode){
            var block = this;

            block.request = $.ajax({
                type: 'PUT',
                url: config.baseApiUrl + '/products/' + PAGE.models.product.id + '/barcodes?validate=1',
                dataType: 'json',
                headers: {
                    Authorization: 'Bearer ' + cookies.get('token')
                },
                data: {
                    barcodes: PAGE.models.product.collections.barcodes.toJSON().concat(barcode)
                }
            });

            return block.request;
        }
    });
});