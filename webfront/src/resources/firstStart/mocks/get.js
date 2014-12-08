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

            var response1 = [];

            var response2 = [
                {
                    store: {
                        id: "54759dbe2cde6e2f198b4567",
                        address: "Ленина 34",
                        name: "3"
                    }
                }
            ];

            var response3 = [
                {
                    store: {
                        id: "54759dbe2cde6e2f198b4567",
                        costOfGoods: 122300, // себестоимость,
                        address: "Марата 5",
                        name: "1"
                    }
                },
                {
                    store: {
                        id: "54759dbe2cde6e2f198b4567",
                        address: "Ленина 34",
                        name: "3"
                    }
                }
            ];

            var response4 = [
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
            ];

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