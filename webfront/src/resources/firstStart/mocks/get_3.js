define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/firstStart',
        type: 'GET',

        //response
        status: 200,
        responseText: [
            {
                store: {
                    id: "54759dbe2cde6e2f198b4567",
                    costOfGoods: 122300, // себестоимость,
                    address: "Марата 5",
                    name: "1"
                },
                sale: {
                    costOfGoods: 12300, // себестоимость,
                    grossSales: 435600, // прибыль
                    amount: 3450 // последняя продажа
                }
            },
            {
                store: {
                    id: "54759dbe2cde6e2f198b4567",
                    costOfGoods: 122300, // себестоимость,
                    address: "Советская 1",
                    name: "2"
                }
            },
            {
                store: {
                    id: "54759dbe2cde6e2f198b4567",
                    address: "Ленина 34",
                    name: "3"
                }
            }
        ]
    });
});