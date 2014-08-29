define(function(require, exports, module) {
    //requirements
    var Block = require('./block');

    describe(module.id, function(){

        it('init block with nested options', function(){

            var Block_1 = Block.extend({
                a: {
                    c: 'c'
                }
            });

            var block = new Block_1({
                a: {
                    b: 'b'
                }
            });

            expect(block.a).toEqual({b: 'b', c: 'c'});
        });

        it('init block without new', function(){
            var block = Block();

            expect(block instanceof Block).toBeTruthy();
        });

        it('generate el from template', function(){
            var block = new Block({
                template: function(){
                    return '<div id="block"></div>'
                }
            });

            expect(block.el.id).toEqual('block');
        });

        it('init nested block', function(){

            var block = new Block({
                template: function(){
                    return '<div><div block="nestedBlock"></div></div>';
                },
                blocks: {
                    nestedBlock: Block.extend({
                        template: function(){
                            return '<div id="nestedBlock" class="nestedBlock"></div>';
                        }
                    })
                }
            });

            expect(block.el.querySelector('.nestedBlock').id).toEqual('nestedBlock');
        });

        it('init nested block with params', function(){

            var block = new Block({
                template: function(){
                    return '<div><div block="nestedBlock" data-class-name="nestedBlock" data-text="nestedBlock"></div></div>';
                },
                blocks: {
                    nestedBlock: Block.extend({
                        template: function(){
                            return '<div class="' + this.className + '">' + this.text + '</div>';
                        }
                    })
                }
            });

            expect(block.el.querySelector('.nestedBlock').textContent).toEqual('nestedBlock');
        });

        it('init nested block without template', function(){
            var block = new Block({
                template: function(){
                    return '<div><span block="nestedBlock">nestedBlock</span></div>';
                },
                blocks: {
                    nestedBlock: Block.extend({
                        initialize: function(){
                            this.el.classList.add('nestedBlock');
                        }
                    })
                }
            });

            expect(block.el.querySelector('.nestedBlock').textContent).toEqual('nestedBlock');
        });


        it('remove nested block', function(){

            spyOn(Block.prototype, 'remove').and.callThrough();

            var block = new Block({
                template: function(){
                    return '<div><div block="nestedBlock"></div></div>';
                },
                blocks: {
                    nestedBlock: Block.extend()
                }
            });

            block.remove();

            expect(Block.prototype.remove.calls.count()).toEqual(2);
        });

        it('call render method', function(){
            var block = new Block({
                text: 'text',
                template: function(){
                    return '<div>' + this.text + '</div>';
                }
            });

            block.text = 'updated text';

            block.render();

            expect(block.el.textContent).toEqual('updated text');
        });

        it('block.el has instance reference', function(){
            var block = new Block();

            expect(block.el.block instanceof Block);
        });

        it('block init models properties', function(){
            var block = new Block({
                models: {
                    a: 'a'
                }
            });

            expect(block.models.a).toEqual('a');
        });

        it('block init models constructors', function(){
            var block = new Block({
                b: 'b',
                models: {
                    a: function(){
                        return this.b;
                    }
                }
            });

            expect(block.models.a).toEqual('b');
        });

        it('block init collections properties', function(){
            var block = new Block({
                collections: {
                    a: ['a', 'b', 'c']
                }
            });

            expect(block.collections.a).toEqual(['a', 'b', 'c']);
        });

        it('block init collections constructors', function(){
            var block = new Block({
                b: ['a', 'b', 'c'],
                collections: {
                    a: function(){
                        return this.b;
                    }
                }
            });

            expect(block.collections.a).toEqual(['a', 'b', 'c']);
        });

        it('deep nested blocks', function(){
            var block = new Block({
                template: function(){
                    return '<div><b block="nestedBlock_1"></b></div>';
                },
                blocks: {
                    nestedBlock_1: Block.extend({
                        template: function(){
                            return '<div class="nestedBlock_1"><b block="nestedBlock_2"></b></div>';
                        },
                        blocks: {
                            nestedBlock_2: Block.extend({
                                template: function(){
                                    return '<div class="nestedBlock_2">nestedBlock_2</div>';
                                }
                            })
                        }
                    })
                }
            });

            expect(block.el.querySelector('.nestedBlock_2').textContent).toEqual('nestedBlock_2');
        });
    });
});