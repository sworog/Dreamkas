define(function(require, exports, module) {
    //requirements
    var ajaxMock = require('kit/ajaxMock/ajaxMock');

    return ajaxMock({
        //request
        url: '/cashFlows',
        type: 'GET',

        //response
        status: 200,
        responseText: [
            {
                id: '1',
                direction: 'out',
                date: "2014-11-24T12:10:56+0300",
                amount: 23000,
                reason: {
                    "type": "Invoice",
                    "id": "54759e382cde6efb1a8b505a",
                    "store": {
                        "id": "54759dbe2cde6e2f198b4568",
                        "name": "22",
                        "address": "СитиМолл",
                        "contacts": "911-888-7-2",
                        "departments": [],
                        "storeManagers": [],
                        "departmentManagers": []
                    },
                    "date": "2014-10-24T11:10:56+0300",
                    "sumTotal": 3500,
                    "itemsCount": 1,
                    "products": [{
                        "id": "54759e382cde6efb1a8b505b",
                        "price": 3500,
                        "quantity": 1,
                        "totalPrice": 3500,
                        "date": "2014-11-24T11:10:56+0300",
                        "product": {
                            "typeUnits": "unit",
                            "type": "unit",
                            "id": "54759dd42cde6e35198b4cda",
                            "name": "Куртка Explossion 61402",
                            "vat": 0,
                            "purchasePrice": 2800,
                            "barcode": "2000000052588",
                            "sku": "ЦБ000004106",
                            "retailPriceMin": 3500,
                            "retailPriceMax": 3500,
                            "retailMarkupMin": 25,
                            "retailMarkupMax": 25,
                            "retailPricePreference": "retailMarkup",
                            "rounding": {"name": "nearest1", "title": "до копеек"},
                            "barcodes": [],
                            "version": "cc5cb2fde74e867ac34e6c835ecf9a54",
                            "createdDate": "2014-11-26T12:33:19+0300"
                        },
                        "priceEntered": 3500,
                        "priceWithoutVAT": 3500,
                        "totalPriceWithoutVAT": 3500,
                        "amountVAT": 0,
                        "totalAmountVAT": 0
                    }],
                    "number": "ЦБ000000554",
                    "supplier": {"id": "54759e1e2cde6efb1a8b48ce", "name": "Дыбенко", "bankAccounts": []},
                    "paid": true,
                    "accepter": "Накладных Импорт",
                    "legalEntity": "СитиМолл",
                    "sumTotalWithoutVAT": 3500,
                    "totalAmountVAT": 0,
                    "includesVAT": true
                }
            },
            {
                id: '2',
                direction: 'out',
                date: "2014-11-24T13:10:56+0300",
                amount: 45340,
                comment: 'Списания'
            },
            {
                id: '3',
                direction: 'out',
                date: "2014-11-24T11:10:56+0300",
                amount: 56450,
                comment: 'Аренда'
            },
            {
                id: '4',
                direction: 'in',
                date: "2014-11-25T11:10:56+0300",
                amount: 10000,
                comment: 'Возврат от Валио'
            },
            {
                id: '5',
                direction: 'out',
                date: "2014-11-26T11:10:56+0300",
                amount: 390,
                comment: 'Интернет'
            },
            {
                id: '6',
                direction: 'out',
                date: "2014-11-28T11:10:56+0300",
                amount: 10000,
                comment: 'Взятка пожарным'
            },
            {
                id: '7',
                direction: 'in',
                date: "2014-11-29T11:10:56+0300",
                amount: 459890,
                comment: 'Продажи'
            },
            {
                id: '8',
                direction: 'out',
                date: "2014-11-29T13:10:56+0300",
                amount: 45340,
                comment: 'Списания'
            },
            {
                id: '9',
                direction: 'in',
                date: "2014-11-30T11:10:56+0300",
                amount: 10000,
                comment: 'Нашел под кассой'
            },
            {
                id: '10',
                direction: 'in',
                date: "2014-11-30T11:10:56+0300",
                amount: 10000,
                comment: 'Нашел под кассой'
            }
        ]
    });
});