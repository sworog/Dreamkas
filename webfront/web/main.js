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
                location: 'dictionary'
            },
            {
                name: 'utils',
                location: 'utils'
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

require(['models/currentUser'], function(currentUserModel) {
    currentUserModel.fetch({
        success: function(){
            console.log(arguments);
            require(['loaders/authorized']);
        },
        error: function(){
            console.log(arguments);
            require(['loaders/unauthorized']);
        }
    });
});