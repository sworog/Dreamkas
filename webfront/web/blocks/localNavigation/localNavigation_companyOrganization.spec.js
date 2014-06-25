define(function(require, exports, module) {
    //requirements
    var $ = require('jquery');

    return function(){
        describe(module.id, function(){

            it('organization local link', function(){
                expect($('.localNavigation [href^="/company/organization"]').text()).toEqual('Данные организации');
            });

        });
    };
});