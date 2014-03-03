define(function(require, exports, module) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        SupplierModel = require('models/supplier'),
        FileModel = require('models/file');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/suppliers',
        el: '.form_supplier',
        model: function(){
            return new SupplierModel();
        },
        events: {
            'change [type="file"]': function(e) {
                var block = this,
                    reader = new FileReader(),
                    file = e.target.files[0],
                    button = e.target.parentElement,
                    form__field = button.parentElement,
                    agreementInput = form__field.querySelector('[name="agreement"]'),
                    fileModel = new FileModel();

                button.classList.add('preloader_rows');
                block.disable(true);

                reader.onload = function(evt) {
                    fileModel.save({
                        file: evt.target.result
                    }, {
                        success: function() {
                            agreementInput.value = fileModel.id;
                            button.classList.remove('preloader_rows');
                            block.disable(false);
                        },
                        error: function(errors) {
                            agreementInput.value = '';
                            block.showErrors(errors);
                            button.classList.remove('preloader_rows');
                            block.disable(false);
                        }
                    });
                };

                reader.readAsBinaryString(file);
            }
        }
    });
});