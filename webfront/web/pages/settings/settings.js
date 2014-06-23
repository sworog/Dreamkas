define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        config = require('config'),
        cookie = require('cookies'),
        $ = require('jquery');

    var authorizationHeader = 'Bearer ' + cookie.get('token'),
        configUrl = config.baseApiUrl + '/configs/by/name';

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        blocks: {
            form_exportSettings: function(){
                var page = this,
                    Form_exportSettings = require('blocks/form/form_exportSettings/form_exportSettings');

                return new Form_exportSettings({
                    set10IntegrationUrl: page.get('set10IntegrationUrl'),
                    set10IntegrationLogin: page.get('set10IntegrationLogin'),
                    set10IntegrationPassword: page.get('set10IntegrationPassword')
                });
            },
            form_importSettings: function(){
                var page = this,
                    Form_importSettings = require('blocks/form/form_importSettings/form_importSettings');

                return new Form_importSettings({
                    set10ImportUrl: page.get('set10ImportUrl'),
                    set10ImportLogin: page.get('set10ImportLogin'),
                    set10ImportPassword: page.get('set10ImportPassword'),
                    set10ImportInterval: page.get('set10ImportInterval')
                });
            }
        },
        fetch: function() {
            var page = this;

            return Promise.all([
                    page.getExportUrl(),
                    page.getExportLogin(),
                    page.getExportPassword(),

                    page.getImportUrl(),
                    page.getImportLogin(),
                    page.getImportPassword(),
                    page.getImportInterval()
            ]).then(function(results) {

                var exportUrl = results[0] || [],
                    exportLogin = results[1] || [],
                    exportPassword = results[2] || [],

                    importUrl = results[3] || [],
                    importLogin = results[4] || [],
                    importPassword = results[5] || [],
                    importInterval = results[6] || [];

                page.set({
                        set10IntegrationUrl: exportUrl[0],
                        set10IntegrationLogin: exportLogin[0],
                        set10IntegrationPassword: exportPassword[0],

                        set10ImportUrl: importUrl[0],
                        set10ImportLogin: importLogin[0],
                        set10ImportPassword: importPassword[0],
                        set10ImportInterval: importInterval[0]
                    });
                });
        },
        getExportUrl: function() {
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
        getExportLogin: function() {
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
        getExportPassword: function() {
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
        getImportUrl: function() {
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
        getImportLogin: function() {
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
        getImportPassword: function() {
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
        getImportInterval: function() {
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