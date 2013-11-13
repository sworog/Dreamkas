define(function(require) {
    //requirements
    var modelNode = require('./modelNode'),
        Model = require('../core/model');

    require('jquery');

    describe('utils/modelNode', function(){

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