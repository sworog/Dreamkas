define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Form_settings = require('blocks/form/form_settings/form_settings'),
        cookie = require('utils/cookie');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = LH.baseApiUrl + '/configs/by/name';

    return Page.extend({
        pageName: 'page_common_balance',
        templates: {
            '#content': require('tpl!./templates/settings.html')
        },
        permissions: {
            //products: 'GET'
        },
        initialize: function(){
            var page = this,
                getData = $.when(page.getUrl(), page.getLogin(), page.getPassword());

            getData.done(function(url, login, password){

                page.render();

                new Form_settings({
                    set10IntegrationUrl: url[0] || {},
                    set10IntegrationLogin: login[0] || {},
                    set10IntegrationPassword: password[0] || {},
                    el: document.getElementById('form_settings')
                });
            });
        },
        getUrl: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-integration-url'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        getLogin: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-integration-login'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        getPassword: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-integration-password'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        }
    });
});