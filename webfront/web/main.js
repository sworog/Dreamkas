require(
    {
        baseUrl: '/',
        paths: {
            'backbone.syphon': 'libs/backbone/backbone.syphon',
            'dictionary': 'dictionary',
            'jquery.ui': 'libs/jquery-ui/ui/minified/jquery-ui.min'
        },
        shim: {
            'libs/lhAutocomplete': ['jquery.ui'],
            'jquery.ui': ['jquery']
        }
    }, ['kit/config'], function(){
        require(['app']);
    });