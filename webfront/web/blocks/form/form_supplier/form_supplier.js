define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        SupplierModel = require('models/supplier'),
        cookie = require('cookies');

    var templates = {
        form_supplier__agreementField: require('tpl!./form_supplier__agreementField.html')
    };

    require('jquery');
    require('lodash');

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
                    file = e.target.files[0],
                    button = e.target.parentElement,
                    $form__field = $(button).closest('.form__field'),
                    agreementInput = $form__field.find('[name="agreement"]');

                button.classList.add('preloader_rows');
                block.removeErrors();
                block.removeSuccessMessage();
                block.disable(true);

                $.ajax({
                    url: LH.baseApiUrl + '/files',
                    dataType: 'json',
                    type: 'POST',
                    headers: {
                        Authorization: authorizationHeader,
                        'x-file-name': encodeURIComponent(file.name)
                    },
                    data: file,
                    processData: false,
                    success: function(res) {
                        block.renderAgreementField(res);
                        button.classList.remove('preloader_rows');
                        block.disable(false);
                    },
                    error: function(error) {
                        agreementInput.value = '';
                        block.showFileErrors(error.responseJSON);
                        button.classList.remove('preloader_rows');
                        block.disable(false);
                    }
                });
            },
            'click .form_supplier__removeFile': function(e) {
                e.preventDefault();
                e.stopPropagation();

                var block = this;

                if (e.target.getAttribute('disabled')) {
                    return;
                }

                if (confirm('Вы уверены, что хотите удалить файл?')) {
                    block.renderAgreementField();
                }
            }
        },
        showFileErrors: function(error){
            var block = this,
                form_supplier__fileField = block.el.querySelector('.form_supplier__fileField');

            if (error && error.errors){
                form_supplier__fileField.dataset.error = error.errors.join(', ');
            } else {
                form_supplier__fileField.dataset.error = 'Неизвестная ошибка';
            }
        },
        renderAgreementField: function(agreement) {
            var block = this,
                form_supplier__fileField = block.el.querySelector('.form_supplier__fileField');

            $(form_supplier__fileField).replaceWith(templates.form_supplier__agreementField({
                agreement: agreement,
                successMessage: true
            }));
        },
        disable: function(disabled) {
            var block = this;

            Form.prototype.disable.apply(block, arguments);

            if (disabled) {
                $(block.el).find('[type="file"]').closest('.button').attr('disabled', true);
                $(block.el).find('.form_supplier__removeFile').attr('disabled', true);
            } else {
                $(block.el).find('[type="file"]').closest('.button').removeAttr('disabled');
                $(block.el).find('.form_supplier__removeFile').removeAttr('disabled');
            }
        }
    });
});