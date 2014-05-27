define(function(require, exports, module) {
    //requirements
    var modelNode = require('./modelNode'),
        Model = require('kit/core/model');

    require('jquery');

    describe(module.id, function(){

        var model;

        beforeEach(function(){
            model = new Model({
                testValue: 'testValue'
            });

            $('body').append(modelNode(model, 'testValue'));
        });

        afterEach(function(){
            $('[model-id="' + model.id + '"]').remove();
        });

        it('attr span text', function(){
            expect($('[model-id="' + model.id + '"][model-attribute="testValue"]').text()).toEqual('testValue');

        });

        it('attr span text change', function(){

            model.set('testValue', 'newValue');

            expect($('[model-id="' + model.id + '"][model-attribute="testValue"]').text()).toEqual('newValue');

        });

    });

});