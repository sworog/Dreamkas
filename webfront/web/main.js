require.config(
    {
        baseUrl: '/',
        paths: {
            'backbone.queryparams': 'libs/backbone/backbone.queryparams'
        },
        shim: {
            'backbone.queryparams': ['backbone']
        },
        packages: [
            {
                name: 'nls',
                location: 'nls'
            }
        ],
        config: {
            //Set the config for the i18n
            //module ID
            i18n: {
                locale: LH.locale === 'auto' ? undefined : LH.locale
            }
        }
    });

require(['jquery', 'models/currentUser', 'models/userPermissions'], function($, currentUserModel, userPermissionsModel) {

    $.when(currentUserModel.fetch(), userPermissionsModel.fetch()).then(
        function() {
            require(['loaders/authorized']);
        },
        function() {
            require(['loaders/unauthorized']);
        });

});