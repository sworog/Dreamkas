define(function(require) {
    //requirements
    var template = require('ejs!fixtures/template.html');

    describe('utils/templateLoader', function(){
        it('template compiled to function', function(){
            expect(typeof template).toBe('function');
        });

        it('template works', function(){
            expect(template({test: {string: 'test'}})).toBe('<div class="testTemplate">test</div>');
        });
    });
});