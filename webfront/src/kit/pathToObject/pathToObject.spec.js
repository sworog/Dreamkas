define(function(require, exports, module) {
    //requirements
    var pathToObject = require('./pathToObject');

    describe(module.id, function(){
        var path;

        beforeEach(function(){
            path = 'a.b.c';
        });

        it('convert path to object', function(){
            expect(pathToObject(path, 'test')).toEqual({a:{b:{c: 'test'}}});
        });
    });
});