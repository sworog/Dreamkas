define(function(require) {
    //requirements
    var Form = require('kit/form/form'),
        config = require('config'),
        cookie = require('cookies');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = config.baseApiUrl + '/configs';

    return Form.extend({
        el: '.form_exportSettings',
        successMessage: 'Настройки успешно сохранены',

        submit: function() {
            var block = this;

            return Promise.all([
                block.saveExportUrl(block.formData['set10-integration-url']),
                block.saveExportLogin(block.formData['set10-integration-login']),
                block.saveExportPassword(block.formData['set10-integration-password'])
            ]).then(function(results) {

                var exportUrl = results[0],
                    exportLogin = results[1],
                    exportPassword = results[2];

                block.set('set10IntegrationUrl.id', exportUrl[0].id);
                block.set('set10IntegrationLogin.id', exportLogin[0].id);
                block.set('set10IntegrationPassword.id', exportPassword[0].id);
            });
        },
        submitError: function() {
            var block = this;

            block.showErrors({error: 'Настройки не сохранены. Обратитесь к администратору.'})
        },
        saveExportUrl: function(url) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.get('set10IntegrationUrl.id') ? '/' + block.get('set10IntegrationUrl.id') : ''),
                dataType: 'json',
                type: block.get('set10IntegrationUrl.id') ? 'PUT' : 'POST',
                data: {
                    name: 'set10-integration-url',
                    value: url
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        saveExportLogin: function(login) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.get('set10IntegrationLogin.id') ? '/' + block.get('set10IntegrationLogin.id') : ''),
                dataType: 'json',
                type: block.get('set10IntegrationLogin.id') ? 'PUT' : 'POST',
                data: {
                    name: 'set10-integration-login',
                    value: login
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        saveExportPassword: function(password) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.get('set10IntegrationPassword.id') ? '/' + block.get('set10IntegrationPassword.id') : ''),
                dataType: 'json',
                type: block.get('set10IntegrationPassword.id') ? 'PUT' : 'POST',
                data: {
                    name: 'set10-integration-password',
                    value: password
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        }
    });
});