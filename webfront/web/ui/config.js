window.baseApiUrl = "/fixtures";
//window.baseApiUrl = "http://borovin.staging.api.lh.cs";

require.config({
    baseUrl: '/',
    paths: {
        tpl: '/libs/requirejs.tpl',
        text: '/libs/requirejs.text',
        json: '/libs/requirejs.json'
    },
    packages: [
        {
            name: 'baseApi',
            location: window.baseApiUrl
        }
    ]
});