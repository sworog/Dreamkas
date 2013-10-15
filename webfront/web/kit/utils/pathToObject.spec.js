define(function(require) {
    //requirements
    var pathToObject = require('./pathToObject');

    describe('utils/pathToObject', function(){
        var path;

        beforeEach(function(){
            path = 'a.b.c';
        });

        it('convert path to object', function(){
            expect(pathToObject(path, 'test')).toEqual({a:{b:{c: 'test'}}});
        });
    });
});