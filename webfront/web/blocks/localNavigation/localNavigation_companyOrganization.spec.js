define(function(require, exports, module) {
    //requirements
    var $ = require('jquery');

    return function(){
        describe(module.id, function(){

            it('organization local link', function(){
                expect($('.localNavigation__link:eq(0)').text()).toEqual('Данные организации');
            });

        });
    };
});