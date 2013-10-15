define(function(require) {
    //requirements
    var deepExtend = require('./deepExtend'),
        objectFixture = require('../fixtures/object');

    require('lodash');

    describe('utils/deepExtend', function() {

        var object1,
            object2,
            object3;

        beforeEach(function() {
            object1 = _.cloneDeep(objectFixture);

            object2 = {
                object: {
                    object: {
                        string: 'test string level 3 object2',
                        number: 23,
                        array: ['a1 level 3 object2', 'a2 level 3 object2', 'a3 level 3 object2', 'a4 level 3 object2'],
                        bool: true,
                        func: function(param1, param2) {
                            param1 = param1 || '';
                            param2 = param2 || '';
                            return this.string + this.number + param1 + param2 + 'object2';
                        },
                        object: {
                            string: 'test string level 4 object2',
                            number: 24,
                            array: ['a1 level 4 object2', 'a2 level 4 object2', 'a3 level 4 object2', 'a4 level 4 object2'],
                            bool: false,
                            func: function(param1, param2) {
                                param1 = param1 || '';
                                param2 = param2 || '';
                                return this.string + this.number + param1 + param2 + 'object2';
                            }
                        }
                    }
                }
            };

            object3 = {
                object: {
                    object: {
                        object: {
                            string: 'test string level 3 object2',
                            number: 23,
                            array: ['a1 level 3 object2', 'a2 level 3 object2', 'a3 level 3 object2', 'a4 level 3 object2'],
                            bool: true,
                            func: function(param1, param2) {
                                param1 = param1 || '';
                                param2 = param2 || '';
                                return this.string + this.number + param1 + param2 + 'object2';
                            },
                            object: {
                                string: 'test string level 4 object2',
                                number: 24,
                                array: ['a1 level 4 object2', 'a2 level 4 object2', 'a3 level 4 object2', 'a4 level 4 object2'],
                                bool: false,
                                func: function(param1, param2) {
                                    param1 = param1 || '';
                                    param2 = param2 || '';
                                    return this.string + this.number + param1 + param2 + 'object2';
                                }
                            }
                        }
                    }
                }
            };
        });

        it('extend empty object', function() {
            var object = deepExtend({}, object1, object2, object3);

            expect(object.string).toEqual('test string level 1');
            expect(object.number).toEqual(1);
            expect(object.array).toEqual(['a1 level 1', 'a2 level 1', 'a3 level 1', 'a4 level 1']);
            expect(object.bool).toEqual(false);
            expect(object.func()).toEqual('test string level 11');

            expect(object.object.object.string).toEqual('test string level 3 object2');
            expect(object.object.object.number).toEqual(23);
            expect(object.object.object.array).toEqual(['a1 level 3 object2', 'a2 level 3 object2', 'a3 level 3 object2', 'a4 level 3 object2']);
            expect(object.object.object.bool).toEqual(true);
            expect(object.object.object.func()).toEqual('test string level 3 object223object2');

        });

    });
});