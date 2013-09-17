define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        cookie = require('kit/utils/cookie');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = LH.baseApiUrl + '/configs';

    return Form.extend({
        __name__: 'form_settings',
        templates: {
            index: require('tpl!blocks/form/form_settings/templates/index.html')
        },
        set10IntegrationUrl: {},
        set10IntegrationLogin: {},
        set10IntegrationPassword: {},
        successMessage: 'Настройки успешно сохранены',
        submit: function(data){
            var block = this,
                saveData = $.when(block.saveUrl(data['set10-integration-url']), block.saveLogin(data['set10-integration-login']), block.savePassword(data['set10-integration-password']));

            saveData.done(function(urlData, loginData, passwordData){
                block.set10IntegrationUrl.id = urlData[0].id;
                block.set10IntegrationLogin.id = loginData[0].id;
                block.set10IntegrationPassword.id = passwordData[0].id;
                block.submitSuccess();
            });

            saveData.fail(function(){
                block.submitError();
            });

            saveData.always(function(){
                block.submitComplete();
            });
        },
        submitError: function(){
            var block = this;

            block.showErrors({error: 'Настройки не сохранены. Обратитесь к администратору.'})
        },
        saveUrl: function(url){
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
        saveLogin: function(login){
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
        savePassword: function(password){
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