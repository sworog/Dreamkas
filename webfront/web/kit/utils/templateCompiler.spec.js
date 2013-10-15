define(function(require) {
    //requirements
    var templateCompiler = require('templateCompiler');

    describe('utils/templateCompiler', function(){

        var template;

        beforeEach(function(){
            template = '<div class="testTemplate"><%- test.string %></div>';
        });

        it('template compiled to function', function(){
            expect(typeof templateCompiler(template)).toBe('function');
        });

        it('template compiles with data', function(){
            expect(templateCompiler(template, {test: {string: 'test'}})).toBe('<div class="testTemplate">test</div>');
        });

        it('add data after compilation', function(){
            var compiledTemplate = templateCompiler(template);

            expect(compiledTemplate({test: {string: 'test'}})).toBe('<div class="testTemplate">test</div>');
        });

        it('template with function', function(){
            var spy = jasmine.createSpy();
                template = '<div class="testTemplate"><% spy(1) %></div>';

            templateCompiler(template, {spy: spy});

            expect(spy).toHaveBeenCalledWith(1);
        });
    });
});