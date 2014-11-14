define(function(require, exports, module) {
    //requirements
	var Modal_stockMovement = require('blocks/modal/stockMovement/stockMovement');

    return Modal_stockMovement.extend({
		id: 'modal_stockIn',
		Model: require('resources/stockIn/model'),
		Form: require('blocks/form/stockIn/stockIn'),
		Form_products: require('blocks/form/stockMovementProducts/stockIn/stockIn'),
		addButtonCaption: 'Оприходовать',
		addTitle: 'Оприходование товаров',
		editTitle: 'Редактирование оприходования',
		removeCaption: 'Удалить оприходование',
		deletedTitle: 'Оприходование удалено'
    });
});