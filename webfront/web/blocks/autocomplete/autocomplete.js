define(function(require, exports, module) {
    //requirements
    var Block = require('block'),
        Tooltip = require('blocks/tooltip/tooltip'),
        when = require('when'),
        currentUserModel = require('models/currentUser'),
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
        search: function(query) {
            var block = this;

            query = query || block.el.value;

            if (block.request && block.request.abort){
                block.request.abort();
            }

            if (query.length >= 3){
                block.el.classList.add('preloader_stripes');

                block.request = $.ajax({
                    url: LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/products/name/search.json',
                    dataType: 'json',
                    headers: {
                        Authorization: 'Bearer ' + cookies.get('token')
                    },
                    data: {
                        query: query
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