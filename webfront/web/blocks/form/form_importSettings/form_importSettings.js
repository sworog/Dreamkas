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
                    block.saveImportUrl(block.get('set10ImportUrl.value')),
                    block.saveImportLogin(block.get('set10ImportLogin.value')),
                    block.saveImportPassword(block.get('set10ImportPassword.value')),
                    block.saveImportInterval(block.get('set10ImportInterval.value'))
                );

            saveData.done(function(importUrl, importLogin, importPassword, importInterval) {
                block.set('set10ImportUrl.id', importUrl[0].id);
                block.set('set10ImportLogin.id', importLogin[0].id);
                block.set('set10ImportPassword.id', importPassword[0].id);
                block.set('set10ImportInterval.id', importInterval[0].id);
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
                url: configUrl + (block.get('set10ImportUrl.id') ? '/' + block.get('set10ImportUrl.id') : ''),
                dataType: 'json',
                type: block.get('set10ImportUrl.id') ? 'PUT' : 'POST',
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
                url: configUrl + (block.get('set10ImportLogin.id') ? '/' + block.get('set10ImportLogin.id') : ''),
                dataType: 'json',
                type: block.get('set10ImportLogin.id') ? 'PUT' : 'POST',
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
                url: configUrl + (block.get('set10ImportPassword.id') ? '/' + block.get('set10ImportPassword.id') : ''),
                dataType: 'json',
                type: block.get('set10ImportPassword.id') ? 'PUT' : 'POST',
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
                url: configUrl + (block.get('set10ImportInterval.id') ? '/' + block.get('set10ImportInterval.id') : ''),
                dataType: 'json',
                type: block.get('set10ImportInterval.id') ? 'PUT' : 'POST',
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