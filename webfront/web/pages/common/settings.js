define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Form_exportSettings = require('blocks/form/form_exportSettings/form_exportSettings'),
        Form_importSettings = require('blocks/form/form_importSettings/form_importSettings'),
        cookie = require('kit/utils/cookie');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = LH.baseApiUrl + '/configs/by/name';

    return Page.extend({
        __name__: 'page_common_settings',
        partials: {
            '#content': require('tpl!./templates/settings.html')
        },
        permissions: {
            configs: 'GET'
        },
        initialize: function(){
            var page = this,
                getData = $.when(
                    page.getExportUrl(),
                    page.getExportLogin(),
                    page.getExportPassword(),

                    page.getImportUrl(),
                    page.getImportLogin(),
                    page.getImportPassword(),
                    page.getImportInterval()
                );

            getData.done(function(exportUrl, exportLogin, exportPassword, importUrl, importLogin, importPassword, importInterval){

                page.render();

                new Form_exportSettings({
                    set10IntegrationUrl: exportUrl[0] || {},
                    set10IntegrationLogin: exportLogin[0] || {},
                    set10IntegrationPassword: exportPassword[0] || {},
                    el: document.getElementById('form_exportSettings')
                });

                new Form_importSettings({
                    set10ImportUrl: importUrl[0] || {},
                    set10ImportLogin: importLogin[0] || {},
                    set10ImportPassword: importPassword[0] || {},
                    set10ImportInterval: importInterval[0] || {},
                    el: document.getElementById('form_importSettings')
                });
            });
        },
        getExportUrl: function(){
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
        getExportLogin: function(){
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
        getExportPassword: function(){
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
        },
        getImportUrl: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-import-url'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        getImportLogin: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-import-login'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        getImportPassword: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-import-password'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        },
        getImportInterval: function(){
            return $.ajax({
                url: configUrl,
                dataType: 'json',
                type: 'GET',
                data: {
                    query: 'set10-import-interval'
                },
                headers: {
                    Authorization: authorizationHeader
                }
            })
        }
    });
});