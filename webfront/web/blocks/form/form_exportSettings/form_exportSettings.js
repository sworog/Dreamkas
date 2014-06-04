define(function(require) {
    //requirements
    var Form = require('kit/form'),
        config = require('config'),
        cookie = require('cookies');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = config.baseApiUrl + '/configs';

    return Form.extend({
        template: require('rv!./template.html'),
        successMessage: 'Настройки успешно сохранены',
        submit: function() {
            var block = this,
                saveData = $.when(
                    block.saveExportUrl(block.get('set10IntegrationUrl.value')),
                    block.saveExportLogin(block.get('set10IntegrationLogin.value')),
                    block.saveExportPassword(block.get('set10IntegrationPassword.value'))
                );

            saveData.done(function(exportUrl, exportLogin, exportPassword) {
                block.set('set10IntegrationUrl.id', exportUrl[0].id);
                block.set('set10IntegrationLogin.id', exportLogin[0].id);
                block.set('set10IntegrationPassword.id', exportPassword[0].id);
            });

            return saveData;
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