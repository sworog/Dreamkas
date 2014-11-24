define(function(require, exports, module) {
    //requirements
	var Modal_stockMovement = require('blocks/modal/stockMovement/stockMovement');

    return Modal_stockMovement.extend({
		id: 'modal_supplierReturn',
		Model: require('resources/supplierReturn/model'),
	    Form: require('blocks/form/stockMovement/supplierReturn/supplierReturn'),
		Form_products: require('blocks/form/stockMovementProducts/supplierReturn/supplierReturn'),
		addButtonCaption: 'Вернуть',
		addTitle: 'Возврат поставщику',
		editTitle: 'Редактирование возврата',
		removeCaption: 'Удалить возврат',
		deletedTitle: 'Возврат поставщику удален'
    });
});