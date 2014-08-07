define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        params: {
            productsSortBy: 'name',
            productsSortDirection: 'descending'
        },
        collections: {
            groups: function() {
                var GroupsCollection = require('collections/groups/groups');

                return new GroupsCollection();
            },
            products: function(){
                var page = this,
                    ProductsCollection = require('collections/products/products'),
                    productCollection = new ProductsCollection([], {
                        groupId: page.params.groupId
                    });

                productCollection.on({
                    remove: function(){
                        var modal = $('.modal:visible');

                        modal.one('hidden.bs.modal', function(e) {
                            page.render();
                        });

                        modal.modal('hide');

                    }
                });

                return productCollection;
            }
        },
        models: {
            group: function() {
                var page = this,
                    GroupModel = require('models/group/group'),
                    groupModel = new GroupModel({
                        id: page.params.groupId
                    });

                groupModel.on({
                    destroy: function() {
                        var modal = $('.modal:visible');

                        modal.one('hidden.bs.modal', function(e) {
                            router.navigate('/catalog');
                        });

                        modal.modal('hide');
                    }
                });

                return groupModel;
            },
            product: null
        },
        events: {
            'click .product__link': function(e){
                var page = this,
                    productId = e.currentTarget.dataset.product_id;

                if (!page.models.product || page.models.product.id !== productId){
                    page.models.product = page.collections.products.get(productId);
                    page.render();
                }

                $('#modal-productEdit').modal('show');

            },
            'click .table_products th': function(e) {
                var page = this;

                page.params.productsSortBy = e.target.dataset.productsSortBy;
                page.params.productsSortDirection = e.target.dataset.sortedDirection || 'descending';

                router.save(page.params);
            }
        },
        blocks: {
            form_groupEdit: function() {
                var page = this,
                    Form_group = require('blocks/form/form_group/form_group'),
                    form_group = new Form_group({
                        model: page.models.group,
                        el: document.getElementById('form_groupEdit')
                    });

                form_group.on('submit:success', function() {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        page.collections.groups.fetch().then(function() {
                            page.render()
                        });
                    });

                    modal.modal('hide');
                });

                return form_group;
            },
            form_productAdd: function() {
                var page = this,
                    Form_product = require('blocks/form/form_product/form_product'),
                    form_product = new Form_product({
                        el: document.getElementById('form_productAdd')
                    });

                form_product.on('submit:success', function(response) {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        if (response.subCategory.id === page.models.group.id){
                            page.collections.products.add(response);
                            page.render();
                        } else {
                            router.navigate('/catalog/groups/' + response.subCategory.id);
                        }
                    });

                    modal.modal('hide');
                });

                return form_product;
            },
            form_productEdit: function() {
                var page = this,
                    Form_product = require('blocks/form/form_product/form_product'),
                    form_product = new Form_product({
                        el: document.getElementById('form_productEdit'),
                        model: page.models.product,
                        collection: page.collections.products
                    });

                form_product.on('submit:success', function(response) {
                    var modal = $('.modal:visible');

                    modal.one('hidden.bs.modal', function(e) {
                        if (response.subCategory.id === page.models.group.id){
                            page.render();
                        } else {
                            router.navigate('/catalog/groups/' + response.subCategory.id);
                        }
                    });

                    modal.modal('hide');
                });

                return form_product;
            },
            select_group: function() {
                var page = this;

                $('.select_group').select2({
                    minimumInputLength: 1,
                    matcher: function (term, text, option) {
                        if (option.attr('add-option') !== undefined && term != '') {
                            return true;
                        }

                        return text.toUpperCase().indexOf(term.toUpperCase())>=0;
                    },
                    formatResult: function(item, container, query) {
                        item.newGroupName = '';
                        if ($(item.element[0]).attr('add-option') !== undefined) {
                            item.newGroupName = query.term;
                        }
                        return item.text + item.newGroupName;
                    },
                    formatSelection: function(item, container) {
                        page.$('[name="newGroupName"]').val(item.newGroupName || '');
                        return item.text + (item.newGroupName || '');
                    }
                });

                return {
                    remove: function() {
                        $('.select_group').select2('destroy').remove();
                    }
                };
            }
        },
        render: function(){
            var page = this;

            page.collections.sortedProducts = page.collections.products.sortBy(page.params.productsSortBy);

            if (page.params.productsSortDirection === 'descending'){
                page.collections.sortedProducts.reverse();
            }

            Page.prototype.render.apply(page, arguments);
        }
    });
});