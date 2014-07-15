define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        cookie = require('cookies'),
        _ = require('lodash');

    var authorizationHeader = 'Bearer ' + cookie.get('token');

    return Form.extend({
        el: '.form_supplier',
        redirectUrl: '/suppliers',
        partials: {
            fileBox: require('ejs!./fileBox.ejs')
        },
        model: function(){
            var Model = require('models/supplier/supplier');

            return new Model();
        },
        events: {
            'change [type="file"]': function(e) {
                var block = this,
                    file = e.target.files[0],
                    button = e.target.parentElement,
                    $form__field = $(button).closest('.form__field'),
                    agreementInput = $form__field.find('[name="agreement"]');

                button.classList.add('preloader_stripes');
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
                        block.model.set('agreement', res);
                        block.set('successMessage', true);
                        block.renderFileBox();
                        block.disable(false);
                    },
                    error: function(error) {
                        agreementInput.value = '';
                        block.showFileErrors(error.responseJSON, error);
                        button.classList.remove('preloader_stripes');
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
                    block.model.set('agreement', null);
                    block.set('successMessage', false);
                    block.renderFileBox();
                }
            }
        },
        initialize: function(){
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.model = block.get('model');
        },
        renderFileBox: function(){
            var block = this;

            $('#form__fileBox').replaceWith(block.partials.fileBox(_.pick(block, 'model', 'successMessage')));
        },
        showFileErrors: function(errorJson, error){
            var block = this,
                form_supplier__fileField = block.el.querySelector('.form_supplier__fileField');

            if (errorJson && errorJson.errors){
                form_supplier__fileField.dataset.error = errorJson.errors.join(', ');
            } else {
                form_supplier__fileField.dataset.error = 'Неизвестная ошибка: ' + error.status + ', '+ error.statusText;
            }
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