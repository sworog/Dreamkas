define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        cookie = require('utils/cookie');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = LH.baseApiUrl + '/config';

    return Form.extend({
        __name__: 'form_settings',
        templates: {
            index: require('tpl!blocks/form/form_settings/templates/index.html')
        },
        successMessage: 'Настройки успешно сохранены',
        onSubmit: function(data){
            var block = this,
                saveData = $.when(block.saveUrl(data.url), block.saveLogin(data.login), block.savePassword(data.password));

            saveData.done(function(){
                block.onSubmitSuccess();
            });

            saveData.fail(function(){
                block.onSubmitError();
            });

            saveData.always(function(){
                block.onSubmitComplete();
            });
        },
        saveUrl: function(url){
            var block = this;

            return $.ajax({
                url: configUrl,
                dataType: 'json',
                data: {
                    url: url
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        saveLogin: function(login){
            var block = this;

            return $.ajax({
                url: configUrl,
                dataType: 'json',
                data: {
                    login: login
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        savePassword: function(password){
            var block = this;

            return $.ajax({
                url: configUrl,
                dataType: 'json',
                data: {
                    password: password
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        }
    });
});