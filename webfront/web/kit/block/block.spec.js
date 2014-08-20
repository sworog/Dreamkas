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
                    return '<div><nestedBlock></nestedBlock></div>';
                },
                blocks: {
                    nestedBlock: function(){
                        return new Block({
                            template: function(){
                                return '<div id="nestedBlock" class="nestedBlock"></div>';
                            }
                        });
                    }
                }
            });

            expect(block.el.querySelector('.nestedBlock').id).toEqual('nestedBlock');
        });

        it('init nested block with params', function(){

            var NestedBlock = Block.extend({
                template: function(){
                    return '<div class="' + this.className + '">' + this.text + '</div>';
                }
            });

            var block = new Block({
                template: function(){
                    return '<div><nestedBlock data-class-name="nestedBlock" data-text="nestedBlock"></nestedBlock></div>';
                },
                blocks: {
                    nestedBlock: NestedBlock
                }
            });

            expect(block.el.querySelector('.nestedBlock').innerText).toEqual('nestedBlock');
        });

        it('destroy nested block', function(){

            spyOn(Block.prototype, 'destroy').and.callThrough();

            var block = new Block({
                template: function(){
                    return '<div><nestedBlock></nestedBlock></div>';
                },
                blocks: {
                    nestedBlock: function(){
                        return new Block();
                    }
                }
            });

            block.destroy();

            expect(Block.prototype.destroy.calls.count()).toEqual(2);
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

            expect(block.el.innerText).toEqual('updated text');
        });

    });
});