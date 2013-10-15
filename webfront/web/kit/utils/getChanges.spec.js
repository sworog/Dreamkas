define(function(require) {
    //requirements
    var getChanges = require('./getChanges');

    require('lodash');

    describe('utils/getChanges', function() {
        var object = {};

        beforeEach(function() {
            object = {
                a: {
                    b: {
                        c: 1
                    },
                    d: {
                        e: 2
                    }
                },
                f: {
                    g: 3
                },
                h: 1
            };
        });

        it('no changes', function() {
            expect(getChanges({h: 1}, object)).toEqual({});
        });

        it('change primitive', function() {

            var changes = {
                a: 1,
                b: 2
            };

            expect(getChanges(changes, undefined)).toEqual(changes);
            expect(getChanges(changes, 'string')).toEqual(changes);
            expect(getChanges(changes, 1)).toEqual(changes);
            expect(getChanges(changes, null)).toEqual(changes);
            expect(getChanges(changes, false)).toEqual(changes);
            expect(getChanges(changes, true)).toEqual(changes);
        });

        it('complex change', function() {
            var changes = {
                a: {
                    b: {
                        c: null
                    },
                    x: 1,
                    d: {
                        e: 2
                    },
                    z: {}
                }
            };
            expect(getChanges(changes, object)).toEqual({a: {
                b: {
                    c: null
                },
                x: 1,
                z: {}
            }});
        });
    });
});