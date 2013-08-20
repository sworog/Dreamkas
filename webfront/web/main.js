require(
    {
        baseUrl: '/',
        paths: {
            'backbone.syphon': 'libs/backbone/backbone.syphon',

            'jquery.ui': '/libs/jquery-ui/ui/minified/jquery-ui.min',
            'jquery.mod': 'libs/jquery/jquery.mod',
            'jquery.maskedinput': 'libs/jquery/jquery.maskedinput'
        },
        shim: {
            'libs/lhAutocomplete': ['jquery.ui'],
            'jquery.ui': ['jquery']
        },
        config: {
            //Set the config for the i18n
            //module ID
            i18n: {
                locale: LH.locale
            }
        }
    }, ['app']);