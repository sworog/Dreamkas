define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    var count = 1;

    return ajaxMock({
        //request
        url: '/firstStart',
        type: 'GET',

        //response
        status: 200,
        response: function(){

            var response;

            var response1 = {
                complete: false
            };

            var response2 = {
                complete: false,
                stores: [
                    {
                        store: {
                            id: "54759dbe2cde6e2f198b4567",
                            address: "Ленина 34",
                            name: "Первый"
                        }
                    }
                ]
            };

            var response3 = {
                complete: false,
                stores: [
                    {
                        store: {
                            id: "54759dbe2cde6e2f198b4567",
                            address: "Ленина 34",
                            name: "Первый"
                        },
                        inventoryCostOfGoods: 122300.45 // себестоимость
                    },
                    {
                        store: {
                            id: "54759dbe2cde6e2f198b4527",
                            address: "Ленина 34",
                            name: "Первый"
                        },
                        inventoryCostOfGoods: 13400.45 // себестоимость
                    }
                ]
            };

            var response4 = {
                complete: false,
                stores: [
                    {
                        store: {
                            id: "54759dbe2cde6e2f198b4567",
                            address: "Ленина 34",
                            name: "Первый"
                        },
                        inventoryCostOfGoods: 122300, // себестоимость
                        sale: {
                            costOfGoods: 12300, // себестоимость
                            grossSales: 435600, // прибыль
                            amount: 3450 // последняя продажа
                        }
                    }
                ]
            };

            var response5 = {
                complete: true
            };

            switch (count) {
                case 1:
                    response = response1;
                    break;

                case 2:
                    response = response2;
                    break;

                case 3:
                    response = response3;
                    break;

                case 4:
                    response = response4;
                    break;

                default:
                    response = response5;
                    break;
            }

            count++;

            this.responseText = response;
        }
    });
});