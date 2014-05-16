define(function(require, exports, module) {
    //requirements
    var stringToFragment = require('./stringToFragment');

    describe(module.id, function(){
        it('string to documentFragment', function(){
            var fragment = stringToFragment('text 1<div class="div1">div 1</div>text 2<div class="div2">div 2</div>');
            expect(fragment.childNodes.length).toBe(4);
            expect(fragment.querySelector('.div1').innerText).toBe('div 1');
        });
    });
});