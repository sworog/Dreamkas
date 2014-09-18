define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Block.extend({
        template: require('ejs!./template.ejs'),
		model: null
    });
});