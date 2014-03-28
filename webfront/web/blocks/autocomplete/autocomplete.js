define(function(require, exports, module) {
    //requirements
    var Block = require('block'),
        Tooltip = require('blocks/tooltip/tooltip'),
        when = require('when'),
        cookies = require('cookies');

    return Block.extend({
        moduleId: module.id,
        el: '.autocomplete',
        events: {
            'blur': function(){
                var block = this;

                block.cancel();
            },
            'focus': function(){
                var block = this;

                block.search();
            },
            'keydown': function(e) {
                var block = this;

                switch (e.keyCode) {
                    case 40: //down
                        e.preventDefault();
                        block.nextItem();
                        break;
                    case 38: //up
                        e.preventDefault();
                        block.prevItem();
                        break;
                    case 13: //enter
                        e.preventDefault();
                        block.selectItem();
                        break;
                }
            },
            'keyup': function(e) {
                var block = this;

                switch (e.keyCode) {
                    case 40: //down
                        break;
                    case 38: //up
                        break;
                    case 13: //enter
                        break;
                    case 27: //escape
                        block.cancel();
                        break;
                    default:
                        block.set('query', block.el.value);
                        block.search();
                        break;
                }
            }
        },
        blocks: {
            tooltip: function() {
                var block = this;

                return new Tooltip({
                    trigger: block.el,
                    template: function(){
                        return block.templates.results(block);
                    }
                });
            }
        },
        templates: {
            results: require('tpl!./results.html')
        },
        data: [],
        selectedItem: null,
        focusedItem: 0,
        request: null,
        query: '',
        minQuery: 3,
        maxResults: 5,
        focusItem: function(index){
            var block = this,
                tooltip = block.blocks.tooltip,
                autocomplete__item = tooltip.el.querySelectorAll('.autocomplete__item');

            if (index >= 0 && autocomplete__item.length > index){

                _.forEach(autocomplete__item, function(item){
                    item.classList.remove('autocomplete__item_focused');
                });

                autocomplete__item[index].classList.add('autocomplete__item_focused');
                block.set('focusedItem', index);
            }
        },
        nextItem: function() {
            var block = this;

            block.focusItem(block.focusedItem + 1);

        },
        prevItem: function() {
            var block = this;

            block.focusItem(block.focusedItem - 1);
        },
        selectItem: function(index) {
            var block = this,
                item = block.data[index] || block.data[block.focusedItem];

            block.el.value = item.product.name;

            block.set('selectedItem', index);

            block.cancel();
            block.clear();
            block.trigger('select', item);
        },
        cancel: function() {
            var block = this,
                tooltip = block.blocks.tooltip;

            if (block.request && block.request.abort){
                block.request.abort();
            }

            tooltip.hide();
        },
        clear: function(){
            var block = this;

            block.el.value = '';
            block.selectedItem = null;
        },
        search: function() {
            var block = this;

            if (block.request && block.request.abort){
                block.request.abort();
            }

            if (block.query.length >= block.minQuery){
                block.el.classList.add('preloader_stripes');

                block.request = $.ajax({
                    url: block.el.dataset.url,
                    dataType: 'json',
                    headers: {
                        Authorization: 'Bearer ' + cookies.get('token')
                    },
                    data: {
                        query: block.query
                    },
                    success: function(data) {
                        block.data = data;
                        block.showResults();
                        block.el.classList.remove('preloader_stripes');
                    }
                });
            } else {
                block.cancel();
                block.el.classList.remove('preloader_stripes');
            }
        },
        showResults: function() {
            var block = this,
                tooltip = block.blocks.tooltip;

            tooltip.show();
            block.focusItem(0);
        }
    });
});