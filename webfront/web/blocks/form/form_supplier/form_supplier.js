define(function(require, exports, module) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        SupplierModel = require('models/supplier'),
        cookie = require('kit/libs/cookie');

    var templates = {
        form_supplier__agreementField: require('tpl!./form_supplier__agreementField.html')
    };

    require('jquery');

    var authorizationHeader = 'Bearer ' + cookie.get('token');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/suppliers',
        el: '.form_supplier',
        model: function() {
            return new SupplierModel();
        },
        events: {
            'change [type="file"]': function(e) {
                var block = this,
                    reader = new FileReader(),
                    file = e.target.files[0],
                    button = e.target.parentElement,
                    $form__field = $(button).closest('.form__field'),
                    agreementInput = $form__field.find('[name="agreement"]');

                button.classList.add('preloader_rows');
                block.disable(true);

                reader.onload = function(evt) {
                    $.ajax({
                        url: LH.baseApiUrl + '/files',
                        dataType: 'json',
                        type: 'POST',
                        headers: {
                            Authorization: authorizationHeader,
                            'x-file-name': file.name

                        },
                        data: evt.target.result,
                        success: function(res) {
                            block.renderAgreementField(res);
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
        },
        renderAgreementField: function(agreement) {
            var block = this,
                form_supplier__fileField = block.el.querySelector('.form_supplier__fileField');

            $(form_supplier__fileField).replaceWith(templates.form_supplier__agreementField({
                agreement: agreement
            }));
        }
    });
});