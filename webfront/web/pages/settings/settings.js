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
            content: require('rv!./content.html')
        },
        components: {
            'form-exportSettings': require('blocks/form/form_exportSettings/form_exportSettings'),
            'form-importSettings': require('blocks/form/form_importSettings/form_importSettings')
        },
        fetch: function() {
            var page = this;

            return $.when(
                page.getExportUrl(),
                page.getExportLogin(),
                page.getExportPassword(),

                page.getImportUrl(),
                page.getImportLogin(),
                page.getImportPassword(),
                page.getImportInterval()
            ).then(function(exportUrl, exportLogin, exportPassword, importUrl, importLogin, importPassword, importInterval) {
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