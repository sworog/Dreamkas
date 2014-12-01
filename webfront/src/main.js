require({
    baseUrl: '/'
}, ['require.config'], function() {
    setTimeout(function(){
        require(['app']);
    }, 0);
});