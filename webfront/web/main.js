require(
    {
        baseUrl: '/',
        paths: {
            'backbone.syphon': 'libs/backbone/backbone.syphon',
            'backbone.queryparams': 'libs/backbone/backbone.queryparams',

            'jquery.require': 'libs/jquery/jquery.require',
            'jquery.mod': 'libs/jquery/jquery.mod',
            'jquery.maskedinput': 'libs/jquery/jquery.maskedinput',

            'tpl': 'kit/utils/tpl',
            'i18n': 'libs/require/i18n'
        },
        packages: [
            {
                name: 'moment',
                location: 'libs/moment',
                main: 'moment'
            },
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
    }, ['app'], function(App){
        new App();
    });