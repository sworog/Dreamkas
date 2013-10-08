define(function(require) {
    //requirements
    var get = require('./get');

    require('lodash');

    describe('utils/get', function(){

        var object = {};

        beforeEach(function(){
            object = {
                nullValue: null,
                trueValue: true,
                falseValue: false,
                stringValue: 'string value',
                numberValue: 1,
                zeroValue: 0,
                undefinedValue: undefined,
                functionValue: function(){
                    return 'function value'
                }
            };

            object.objectValue = _.clone(object);
        });

        it('get does not affect object properties', function() {

            var originalObject = _.cloneDeep(object);

            get(object, 'nullValue');
            get(object, 'trueValue');
            get(object, 'falseValue');
            get(object, 'stringValue');
            get(object, 'numberValue');
            get(object, 'zeroValue');
            get(object, 'undefinedValue');
            get(object, 'functionValue');
            get(object, 'objectValue');

        });
    });
});