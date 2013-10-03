define(function(require) {
    //requirements
    var classExtend = require('./classExtend');

    require('lodash');

    describe('utils/classExtend', function(){

        var Klass = function(param1, param2){
            this.param1 = param1;
            this.param2 = param2;
        };

        Klass.extend = classExtend;


    });

});