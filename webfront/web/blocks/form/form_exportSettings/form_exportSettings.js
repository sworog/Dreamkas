define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        cookie = require('cookies');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = LH.baseApiUrl + '/configs';

    return Form.extend({
        __name__: 'form_exportSettings',
        template: require('tpl!blocks/form/form_exportSettings/templates/index.html'),
        set10IntegrationUrl: {},
        set10IntegrationLogin: {},
        set10IntegrationPassword: {},
        successMessage: 'Настройки успешно сохранены',
        submit: function(data) {
            var block = this,
                saveData = $.when(
                    block.saveExportUrl(data['set10-integration-url']),
                    block.saveExportLogin(data['set10-integration-login']),
                    block.saveExportPassword(data['set10-integration-password'])
                );

            saveData.done(function(exportUrl, exportLogin, exportPassword) {
                block.set10IntegrationUrl.id = exportUrl[0].id;
                block.set10IntegrationLogin.id = exportLogin[0].id;
                block.set10IntegrationPassword.id = exportPassword[0].id;
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
                url: configUrl + (block.set10IntegrationUrl.id ? '/' + block.set10IntegrationUrl.id : ''),
                dataType: 'json',
                type: block.set10IntegrationUrl.id ? 'PUT' : 'POST',
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
                url: configUrl + (block.set10IntegrationLogin.id ? '/' + block.set10IntegrationLogin.id : ''),
                dataType: 'json',
                type: block.set10IntegrationLogin.id ? 'PUT' : 'POST',
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
                url: configUrl + (block.set10IntegrationPassword.id ? '/' + block.set10IntegrationPassword.id : ''),
                dataType: 'json',
                type: block.set10IntegrationPassword.id ? 'PUT' : 'POST',
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