define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        cookie = require('kit/utils/cookie');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = LH.baseApiUrl + '/configs';

    return Form.extend({
        __name__: 'form_exportSettings',
        template: require('tpl!blocks/form/form_importSettings/templates/index.html'),
        set10ImportUrl: {},
        set10ImportLogin: {},
        set10ImportPassword: {},
        set10ImportInterval: {},
        successMessage: 'Настройки успешно сохранены',
        submit: function(data) {
            var block = this,
                saveData = $.when(
                    block.saveImportUrl(data['set10-import-url']),
                    block.saveImportLogin(data['set10-import-login']),
                    block.saveImportPassword(data['set10-import-password']),
                    block.saveImportInterval(data['set10-import-interval'])
                );

            saveData.done(function(importUrl, importLogin, importPassword, importInterval) {
                block.set10ImportUrl.id = importUrl[0].id;
                block.set10ImportLogin.id = importLogin[0].id;
                block.set10ImportPassword.id = importPassword[0].id;
                block.set10ImportInterval.id = importInterval[0].id;
            });

            return saveData;
        },
        submitError: function() {
            var block = this;

            block.showErrors({error: 'Настройки не сохранены. Обратитесь к администратору.'})
        },
        saveImportUrl: function(url) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.set10ImportUrl.id ? '/' + block.set10ImportUrl.id : ''),
                dataType: 'json',
                type: block.set10ImportUrl.id ? 'PUT' : 'POST',
                data: {
                    name: 'set10-import-url',
                    value: url
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        saveImportLogin: function(login) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.set10ImportLogin.id ? '/' + block.set10ImportLogin.id : ''),
                dataType: 'json',
                type: block.set10ImportLogin.id ? 'PUT' : 'POST',
                data: {
                    name: 'set10-import-login',
                    value: login
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        saveImportPassword: function(password) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.set10ImportPassword.id ? '/' + block.set10ImportPassword.id : ''),
                dataType: 'json',
                type: block.set10ImportPassword.id ? 'PUT' : 'POST',
                data: {
                    name: 'set10-import-password',
                    value: password
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        saveImportInterval: function(interval) {
            var block = this;

            return $.ajax({
                url: configUrl + (block.set10ImportInterval.id ? '/' + block.set10ImportInterval.id : ''),
                dataType: 'json',
                type: block.set10ImportInterval.id ? 'PUT' : 'POST',
                data: {
                    name: 'set10-import-interval',
                    value: interval
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        }
    });
});