define(function(require, exports, module) {
    //requirements
    var BaseClass = require('./baseClass');

    describe(module.id, function() {

        it('baseClassInstance has set method', function() {
            var baseClassInstance = new BaseClass();

            expect(typeof baseClassInstance.set).toBe('function')
        });

        it('baseClassInstance has get method', function() {
            var baseClassInstance = new BaseClass();

            expect(typeof baseClassInstance.get).toBe('function')
        });

        it('baseClassInstance has trigger method', function() {
            var baseClassInstance = new BaseClass();

            expect(typeof baseClassInstance.trigger).toBe('function')
        });

        it('baseClassInstance has listenTo method', function() {
            var baseClassInstance = new BaseClass();

            expect(typeof baseClassInstance.listenTo).toBe('function')
        });

        it('baseClassInstance has stopListening method', function() {
            var baseClassInstance = new BaseClass();

            expect(typeof baseClassInstance.stopListening).toBe('function')
        });

        it('BaseClass extending chain', function() {
            var ExtendedClass1 = BaseClass.extend({
                a: {
                    b: {
                        c: 1,
                        d: 2
                    }
                }
            });

            var ExtendedClass2 = ExtendedClass1.extend({
                a: {
                    b: {
                        d: 3,
                        e: 4
                    }
                }
            });

            var instanceofExtendedClass1 = new ExtendedClass1;
            var instanceofExtendedClass2 = new ExtendedClass2;

            expect(instanceofExtendedClass1.a.b.c).toBe(1);
            expect(instanceofExtendedClass1.a.b.d).toBe(2);
            expect(instanceofExtendedClass1.a.b.e).toBeUndefined();

            expect(instanceofExtendedClass2.a.b.c).toBe(1);
            expect(instanceofExtendedClass2.a.b.d).toBe(3);
            expect(instanceofExtendedClass2.a.b.e).toBe(4);
        });

        it('instance options', function() {
            var ExtendedClass = BaseClass.extend({
                a: {
                    b: {
                        c: 1,
                        d: 2
                    }
                }
            });

            var instanceofExtendedClass = new ExtendedClass({
                a: {
                    b: {
                        d: 3,
                        e: 4
                    }
                }
            });

            expect(instanceofExtendedClass.a.b.c).toBe(1);
            expect(instanceofExtendedClass.a.b.d).toBe(3);
            expect(instanceofExtendedClass.a.b.e).toBe(4);
        });

        describe('baseClass.get()', function() {
            it('get "top" property', function() {
                var ExtendedClass = BaseClass.extend({
                    a: 1
                });

                var instanceofExtendedClass = new ExtendedClass();

                expect(instanceofExtendedClass.get('a')).toEqual(1);
            });

            it('get "deep" property', function() {
                var ExtendedClass = BaseClass.extend({
                    a: {
                        b: {
                            c: 1
                        }
                    }
                });

                var instanceofExtendedClass = new ExtendedClass();

                expect(instanceofExtendedClass.get('a.b.c')).toEqual(1);
            });

            it('get undefined property', function() {
                var ExtendedClass = BaseClass.extend({
                    a: {
                        b: {
                            c: 1
                        }
                    }
                });

                var instanceofExtendedClass = new ExtendedClass();

                expect(instanceofExtendedClass.get('a.b.c.d')).toBeUndefined();
            });

            it('get "top" function result', function() {
                var ExtendedClass = BaseClass.extend({
                    a: function(n1, n2) {
                        return this.b + n1 + n2
                    },
                    b: 1
                });

                var instanceofExtendedClass = new ExtendedClass();

                expect(instanceofExtendedClass.get('a', [2, 3])).toEqual(6);
            });

            it('get "deep" function result', function() {
                var ExtendedClass = BaseClass.extend({
                    a: {
                        b: {
                            c: function(n1, n2) {
                                return this.b + n1 + n2
                            },
                            b: 10
                        }
                    },
                    b: 1
                });

                var instanceofExtendedClass = new ExtendedClass();

                expect(instanceofExtendedClass.get('a.b.c', [2, 3])).toEqual(6);
            });
        });

        describe('baseClass.set()', function() {
            it('set "top" property by path', function() {
                var ExtendedClass = BaseClass.extend({
                    a: 2
                });

                var instanceofExtendedClass = new ExtendedClass();

                instanceofExtendedClass.set('a', 1);

                expect(instanceofExtendedClass.a).toEqual(1);
            });

            it('set "top" property by hash', function() {
                var ExtendedClass = BaseClass.extend({
                    a: 2
                });

                var instanceofExtendedClass = new ExtendedClass();

                instanceofExtendedClass.set({
                    a: 1
                });

                expect(instanceofExtendedClass.a).toEqual(1);
            });

            it('set "deep" property by path', function() {
                var ExtendedClass = BaseClass.extend({
                    a: {}
                });

                var instanceofExtendedClass = new ExtendedClass();

                instanceofExtendedClass.set('a.b.c', 1);

                expect(instanceofExtendedClass.a.b.c).toEqual(1);
            });

            it('set "deep" property by hash', function() {
                var ExtendedClass = BaseClass.extend({
                    a: {}
                });

                var instanceofExtendedClass = new ExtendedClass();

                instanceofExtendedClass.set({
                    a: {
                        b: {
                            c: 1
                        }
                    }
                });

                expect(instanceofExtendedClass.a.b.c).toEqual(1);
            });

            it('set property to object with set:* method', function() {
                var set_a_b_c = jasmine.createSpy('set:a.b.c');
                var set_a_b = jasmine.createSpy('set:a.b');

                var ExtendedClass = BaseClass.extend({
                    a: {},
                    'set:a.b.c': set_a_b_c,
                    'set:a.b': set_a_b
                });

                var instanceofExtendedClass = new ExtendedClass();

                instanceofExtendedClass.set('a.b.c', 1);

                expect(set_a_b_c).toHaveBeenCalledWith(1, {});
                expect(set_a_b).toHaveBeenCalledWith({
                    c: 1
                }, {});
            });

            it('set property to object with change:* events', function() {
                var change_a_b_c = jasmine.createSpy('change:a.b.c');
                var change_a_b = jasmine.createSpy('change:a.b');

                var ExtendedClass = BaseClass.extend({
                    a: {}
                });

                var instanceofExtendedClass = new ExtendedClass();

                instanceofExtendedClass.on('change:a.b.c', change_a_b_c);
                instanceofExtendedClass.on('change:a.b', change_a_b);

                instanceofExtendedClass.set('a.b.c', 1);

                expect(change_a_b_c).toHaveBeenCalledWith(1, {});
                expect(change_a_b).toHaveBeenCalledWith({
                    c: 1
                }, {});
            });
        });
    });
});