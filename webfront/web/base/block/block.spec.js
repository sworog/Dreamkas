define(function(require, exports, module) {
    //requirements
    var Block = require('./block');

    require('lodash');
    require('jquery');

    var divEl;

    describe(module.id, function() {

        beforeEach(function() {
            divEl = document.createElement('div');

            divEl.className = 'testElement';
            divEl.id = 'testElement';

            document.body.appendChild(divEl);
        });

        afterEach(function() {
            document.body.innerHTML = '';
        });

        describe('base.block.constructor()', function(){
            var ExtendedBlock = Block.extend({
                a: {
                    b: {
                        c: 1,
                        d: 2
                    }
                },
                d: 2
            });

            var extendedBlock = new ExtendedBlock({
                a: {
                    b: {
                        c: 2,
                        e: 4
                    },
                    c: 5
                },
                d: 6
            });

            it('options merge', function() {
                expect(extendedBlock.a.b.c).toEqual(2);
                expect(extendedBlock.a.b.d).toEqual(2);
                expect(extendedBlock.a.b.e).toEqual(4);
                expect(extendedBlock.a.c).toEqual(5);
                expect(extendedBlock.d).toEqual(6);
            });

            it('prototype does not change', function() {
                expect(ExtendedBlock.prototype.a.b.c).toEqual(1);
                expect(ExtendedBlock.prototype.a.b.d).toEqual(2);
                expect(ExtendedBlock.prototype.a.b.e).toBeUndefined();
                expect(ExtendedBlock.prototype.a.c).toBeUndefined();
                expect(ExtendedBlock.prototype.d).toEqual(2);
            });

            it('extend chain', function() {
                var ExtendedBlock_1 = ExtendedBlock.extend({
                    a: {
                        b: {
                            c: 10,
                            e: 20
                        }
                    },
                    d: 20
                });

                expect(ExtendedBlock_1.prototype.a.b.c).toEqual(10);
                expect(ExtendedBlock_1.prototype.a.b.d).toEqual(2);
                expect(ExtendedBlock_1.prototype.a.b.e).toEqual(20);
                expect(ExtendedBlock_1.prototype.d).toEqual(20);
            });

            it('block cid', function(){

                var ExtendedBlock = Block.extend({});

                var ExtendedBlock1 = Block.extend({
                    cid: 'test 1'
                });

                var ExtendedBlock2 = Block.extend({
                    cid: function(){
                        return this.a;
                    },
                    a: 'test 2'
                });

                var extendedBlock = new ExtendedBlock();
                var extendedBlock1 = new ExtendedBlock1();
                var extendedBlock2 = new ExtendedBlock2();

                expect(extendedBlock.get('cid')).toBeDefined();
                expect(extendedBlock1.get('cid')).toEqual('test 1');
                expect(extendedBlock2.get('cid')).toEqual('test 2');
            });

            it('call initialize()', function(){
                var ExtendedBlock = Block.extend({
                    initialize: jasmine.createSpy('initialize')
                });

                var extendedBlock = new ExtendedBlock({a: 1}, 2);

                expect(extendedBlock.initialize).toHaveBeenCalledWith({a: 1}, 2);
            });

            it('call startListening()', function(){
                var ExtendedBlock = Block.extend({
                    startListening: jasmine.createSpy('startListening')
                });

                var extendedBlock = new ExtendedBlock();

                expect(extendedBlock.startListening).toHaveBeenCalled();
            });
        });

        describe('block.el', function() {
            it('by el selector', function() {

                var ExtendedBlock = Block.extend({
                    el: '.testElement'
                });

                var extendedBlock = new ExtendedBlock();

                expect(extendedBlock.el).toEqual(divEl);
            });

            it('self-created block.el', function() {
                var ExtendedBlock = Block.extend({
                    className: 'test',
                    id: 'test'
                });

                var extendedBlock = new ExtendedBlock();

                expect(_.isElement(extendedBlock.el)).toBeTruthy();
                expect(extendedBlock.el.className).toEqual('test');
                expect(extendedBlock.el.id).toEqual('test');
            });

            it('call undelegateEvents()', function(){

                var ExtendedBlock = Block.extend({
                    undelegateEvents: jasmine.createSpy('undelegateEvents')
                });

                var extendedBlock = new ExtendedBlock();

                expect(extendedBlock.undelegateEvents).toHaveBeenCalled();
            });

            it('call delegateEvents()', function(){

                var ExtendedBlock = Block.extend({
                    delegateEvents: jasmine.createSpy('delegateEvents')
                });

                var extendedBlock = new ExtendedBlock();

                expect(extendedBlock.delegateEvents).toHaveBeenCalled();
            });
        });

        describe('block.listeners', function() {

            it('listen self events', function() {

                var event1 = jasmine.createSpy('event1');
                var event2 = jasmine.createSpy('event2');

                var ExtendedBlock = Block.extend({
                    listeners: {
                        'event1': event1,
                        'event2': event2
                    }
                });

                var extendedBlock = new ExtendedBlock();

                extendedBlock.trigger('event1', 2, 'extra');
                extendedBlock.trigger('event2', 3, 'extra');

                expect(event1).toHaveBeenCalledWith(2, 'extra');
                expect(event2).toHaveBeenCalledWith(3, 'extra');
            });

            it('listen properties events', function() {

                var event1 = jasmine.createSpy('event1');
                var event2 = jasmine.createSpy('event2');

                var ExtendedBlock = Block.extend({
                    listeners: {
                        'a': {
                            'event1': event1
                        },
                        'b.c.d': {
                            'event2': event2
                        }
                    },
                    a: new Block(),
                    b: {
                        c: {
                            d: new Block()
                        }
                    }
                });

                var extendedBlock = new ExtendedBlock();

                extendedBlock.a.trigger('event1', 2, 'extra');
                extendedBlock.b.c.d.trigger('event2', 3, 'extra');

                expect(event1).toHaveBeenCalledWith(2, 'extra');
                expect(event2).toHaveBeenCalledWith(3, 'extra');
            });

            it('stop listening events', function() {

                var event1 = jasmine.createSpy('event1');
                var event2 = jasmine.createSpy('event2');
                var event3 = jasmine.createSpy('event3');

                var ExtendedBlock = Block.extend({
                    listeners: {
                        'event1': event1,
                        'a': {
                            'event1': event1
                        },
                        'b.c.d': {
                            'event2': event2
                        }
                    },
                    a: new Block(),
                    b: {
                        c: {
                            d: new Block()
                        }
                    }
                });

                var extendedBlock = new ExtendedBlock();

                extendedBlock.stopListening();

                extendedBlock.trigger('event1', 1, 'extra');
                extendedBlock.trigger('event2', 2, 'extra');
                extendedBlock.trigger('event2', 3, 'extra');

                expect(event1).not.toHaveBeenCalled();
                expect(event2).not.toHaveBeenCalled();
                expect(event3).not.toHaveBeenCalled();
            });
        });

        describe('block.events', function() {

            afterEach(function() {
                document.body.innerHTML = '';
            });

            it('delegate DOM events', function() {

                var event1 = jasmine.createSpy('event1');

                var el = document.createElement('div');
                document.body.appendChild(el);
                el.innerHTML = '<span class="testElement">testElement</span>';

                var ExtendedBlock = Block.extend({
                    events: {
                        'click .testElement': event1
                    },
                    el: el
                });

                new ExtendedBlock();

                $('.testElement').trigger('click');

                expect(event1).toHaveBeenCalled();
            });

            it('undelegate DOM events', function() {

                var event1 = jasmine.createSpy('event1');

                var element = document.createElement('div');
                document.body.appendChild(element);
                element.innerHTML = '<span class="testElement">testElement</span>';

                var ExtendedBlock = Block.extend({
                    events: {
                        'click .testElement': event1
                    },
                    element: element
                });

                var extendedBlock = new ExtendedBlock();

                document.body.appendChild(element);

                extendedBlock.undelegateEvents();

                $('.testElement').trigger('click');

                expect(event1).not.toHaveBeenCalled();
            });
        });

        describe('block.render()', function() {

            it('simple rendering', function(){
                var ExtendedBlock = Block.extend({
                    el: '.testElement',
                    template: function(obj){
                        return '<div class="testElement"><span id="testElement__inner">testElement__inner</span></div>'
                    }
                });

                var extendedBlock = new ExtendedBlock();

                extendedBlock.render();

                expect(extendedBlock.el).toEqual(divEl);
                expect(extendedBlock.el.children[0].innerHTML).toEqual('testElement__inner');
            });

            it('rendering with new attributes', function(){
                var ExtendedBlock = Block.extend({
                    el: '.testElement',
                    className: 'extendedBlock',
                    template: function(obj){
                        return '<div class="' + obj.className + '"><span id="testElement__inner">testElement__inner</span></div>'
                    }
                });

                var extendedBlock = new ExtendedBlock();

                extendedBlock.render();

                expect(extendedBlock.el).toEqual(divEl);
                expect(extendedBlock.el.className).toEqual('extendedBlock');
            });

        });
    });

});