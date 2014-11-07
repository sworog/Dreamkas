define(function(require, exports, module) {
    //requirements
    var mockjax = require('kit/mockjax/mockjax'),
        config = require('config');

    mockjax({
        url: config.baseApiUrl + '/suppliers',
        status: 200,

        responseText: [{
            "type": "Invoice",
            "id": "544e4e6e2cde6ef3368b45cc",
            "store": {
                "id": "544e281e2cde6ea7798b4588",
                "name": "Первый",
                "departments": [],
                "storeManagers": [],
                "departmentManagers": []
            },
            "date": "2014-10-27T00:00:00+0300",
            "sumTotal": 20,
            "itemsCount": 1,
            "products": [{
                "id": "544e4e6e2cde6ef3368b45cd",
                "price": 20,
                "quantity": 1,
                "totalPrice": 20,
                "date": "2014-10-27T00:00:00+0300",
                "product": {
                    "typeUnits": "unit",
                    "type": "unit",
                    "id": "544e28332cde6e70058b4586",
                    "name": "Пиво",
                    "units": "шт",
                    "vat": 0,
                    "sku": "10001",
                    "retailPricePreference": "retailMarkup",
                    "rounding": {"name": "nearest1", "title": "\u0434\u043e \u043a\u043e\u043f\u0435\u0435\u043a"},
                    "barcodes": [],
                    "version": "0282075d01fd62aa25fb4edf03be5a9e",
                    "createdDate": "2014-10-27T14:11:02+0300"
                },
                "priceEntered": 20,
                "priceWithoutVAT": 20,
                "totalPriceWithoutVAT": 20,
                "amountVAT": 0,
                "totalAmountVAT": 0
            }],
            "number": "10017",
            "paid": false,
            "sumTotalWithoutVAT": 20,
            "totalAmountVAT": 0,
            "includesVAT": false
        }, {
            "type": "WriteOff",
            "id": "544e4e852cde6ef3368b45d8",
            "store": {
                "id": "544e281e2cde6ea7798b4588",
                "name": "Первый",
                "departments": [],
                "storeManagers": [],
                "departmentManagers": []
            },
            "date": "2014-10-27T00:00:00+0300",
            "sumTotal": 23,
            "itemsCount": 1,
            "products": [{
                "id": "544e4e852cde6ef3368b45d9",
                "price": 23,
                "quantity": 1,
                "totalPrice": 23,
                "date": "2014-10-27T00:00:00+0300",
                "product": {
                    "typeUnits": "unit",
                    "type": "unit",
                    "id": "544e28332cde6e70058b4586",
                    "name": "Пиво",
                    "units": "шт",
                    "vat": 0,
                    "sku": "10001",
                    "retailPricePreference": "retailMarkup",
                    "rounding": {"name": "nearest1", "title": "\u0434\u043e \u043a\u043e\u043f\u0435\u0435\u043a"},
                    "barcodes": [],
                    "version": "0282075d01fd62aa25fb4edf03be5a9e",
                    "createdDate": "2014-10-27T14:11:02+0300"
                },
                "cause": "Упал"
            }],
            "number": "10018"
        }, {
            "type": "StockIn",
            "id": "544e4e8e2cde6ef3368b45db",
            "store": {
                "id": "544e281e2cde6ea7798b4588",
                "name": "Первый",
                "departments": [],
                "storeManagers": [],
                "departmentManagers": []
            },
            "date": "2014-10-27T00:00:00+0300",
            "sumTotal": 23,
            "itemsCount": 1,
            "products": [{
                "id": "544e4e8e2cde6ef3368b45dc",
                "price": 23,
                "quantity": 1,
                "totalPrice": 23,
                "date": "2014-10-27T00:00:00+0300",
                "product": {
                    "typeUnits": "unit",
                    "type": "unit",
                    "id": "544e28332cde6e70058b4586",
                    "name": "Пиво",
                    "units": "шт",
                    "vat": 0,
                    "sku": "10001",
                    "retailPricePreference": "retailMarkup",
                    "rounding": {"name": "nearest1", "title": "\u0434\u043e \u043a\u043e\u043f\u0435\u0435\u043a"},
                    "barcodes": [],
                    "version": "0282075d01fd62aa25fb4edf03be5a9e",
                    "createdDate": "2014-10-27T14:11:02+0300"
                }
            }],
            "number": "10019"
        }, {
            "type": "SupplierReturn",
            "id": "544e4e972cde6e0b758b463c",
            "store": {
                "id": "544e281e2cde6ea7798b4588",
                "name": "Первый",
                "departments": [],
                "storeManagers": [],
                "departmentManagers": []
            },
            "date": "2014-10-27T00:00:00+0300",
            "sumTotal": 23,
            "itemsCount": 1,
            "products": [{
                "id": "544e4e972cde6e0b758b463d",
                "price": 23,
                "quantity": 1,
                "totalPrice": 23,
                "date": "2014-10-27T00:00:00+0300",
                "product": {
                    "typeUnits": "unit",
                    "type": "unit",
                    "id": "544e28332cde6e70058b4586",
                    "name": "Пиво",
                    "units": "шт",
                    "vat": 0,
                    "sku": "10001",
                    "retailPricePreference": "retailMarkup",
                    "rounding": {"name": "nearest1", "title": "\u0434\u043e \u043a\u043e\u043f\u0435\u0435\u043a"},
                    "barcodes": [],
                    "version": "0282075d01fd62aa25fb4edf03be5a9e",
                    "createdDate": "2014-10-27T14:11:02+0300"
                }
            }],
            "number": "10020",
            "paid": false
        }]

    });
});